<?php
// O cabeçalho já inicia a sessão e faz a conexão via PDO
include('admin_header.php');
require_once('../../config/log_helper.php');

$feedback_message = '';
$community_to_edit = null;
$members_list = [];
$admin_user_id = $_SESSION['user_id'];
$admin_user_name = $_SESSION['full_name'] ?? 'Administrador';

// Lógica para Adicionar ou Editar Comunidade
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_community'])) {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $community_id = isset($_POST['community_id']) ? intval($_POST['community_id']) : 0;

    if (empty($name) || empty($description)) {
        $feedback_message = "<p class='error-message'>Nome e descrição são obrigatórios.</p>";
    } else {
        if ($community_id > 0) { // Atualizar
            $stmt = $pdo->prepare("UPDATE communities SET name = ?, description = ? WHERE community_id = ?");
            if ($stmt->execute([$name, $description, $community_id])) {
                $feedback_message = "<p class='success-message'>Comunidade salva com sucesso!</p>";
                logAction($pdo, 'Comunidade Editada', $admin_user_name, "Comunidade '{$name}' (ID: #{$community_id}) foi atualizada.", $admin_user_id);
            } else {
                $feedback_message = "<p class='error-message'>Erro ao salvar comunidade.</p>";
            }
        } else { // Inserir
            $creator_id = $_SESSION['user_id'];
            $stmt = $pdo->prepare("INSERT INTO communities (name, description, creator_id) VALUES (?, ?, ?)");
            if ($stmt->execute([$name, $description, $creator_id])) {
                $new_comm_id = $pdo->lastInsertId();
                $feedback_message = "<p class='success-message'>Comunidade criada com sucesso!</p>";
                logAction($pdo, 'Nova Comunidade', $admin_user_name, "Comunidade '{$name}' (ID: #{$new_comm_id}) foi criada.", $admin_user_id);
            } else {
                $feedback_message = "<p class='error-message'>Erro ao criar comunidade.</p>";
            }
        }
    }
}

// Lógica para Ações (Apagar, remover membro, alterar cargo)
if (isset($_GET['action'])) {
    // Ação para apagar a comunidade
    if ($_GET['action'] == 'delete' && isset($_GET['id'])) {
        $id_to_delete = intval($_GET['id']);
        $stmt_get_name = $pdo->prepare("SELECT name FROM communities WHERE community_id = ?");
        $stmt_get_name->execute([$id_to_delete]);
        $comm_data = $stmt_get_name->fetch();
        $comm_name_for_log = $comm_data ? $comm_data['name'] : 'Desconhecida';

        $stmt = $pdo->prepare("DELETE FROM communities WHERE community_id = ?");
        if ($stmt->execute([$id_to_delete])) {
            logAction($pdo, 'Comunidade Excluída', $admin_user_name, "Comunidade '{$comm_name_for_log}' (ID: #{$id_to_delete}) foi excluída.", $admin_user_id);
            header("Location: admin_communities.php?feedback=deleted_ok");
        } else {
            header("Location: admin_communities.php?feedback=error");
        }
        exit();
    }

    // Ação para remover um membro
    if ($_GET['action'] == 'remove_member' && isset($_GET['user_id']) && isset($_GET['community_id'])) {
        $user_id = intval($_GET['user_id']);
        $community_id = intval($_GET['community_id']);
        // (Lógica de log e remoção aqui, se necessário)
        $stmt = $pdo->prepare("DELETE FROM user_communities WHERE user_id = ? AND community_id = ?");
        if ($stmt->execute([$user_id, $community_id])) {
            header("Location: admin_communities.php?edit_id=$community_id&feedback=member_removed");
        } else {
            header("Location: admin_communities.php?edit_id=$community_id&feedback=error");
        }
        exit();
    }

    // Ação para alterar o cargo de um membro
    if ($_GET['action'] == 'change_role' && isset($_GET['user_id']) && isset($_GET['community_id']) && isset($_GET['role'])) {
        $user_id = intval($_GET['user_id']);
        $community_id = intval($_GET['community_id']);
        $role = $_GET['role'];

        if (in_array($role, ['member', 'moderator', 'admin'])) {
            $stmt = $pdo->prepare("UPDATE user_communities SET member_type = ? WHERE user_id = ? AND community_id = ?");
            if ($stmt->execute([$role, $user_id, $community_id])) {
                // (Lógica de log aqui)
                header("Location: admin_communities.php?edit_id=$community_id&feedback=role_changed");
            } else {
                header("Location: admin_communities.php?edit_id=$community_id&feedback=error");
            }
            exit();
        }
    }
}

// Feedback para o usuário
if (isset($_GET['feedback'])) {
    switch ($_GET['feedback']) {
        case 'deleted_ok':
            $feedback_message = "<p class='success-message'>Comunidade apagada com sucesso!</p>";
            break;
        case 'member_removed':
            $feedback_message = "<p class='success-message'>Membro removido da comunidade!</p>";
            break;
        case 'role_changed':
            $feedback_message = "<p class='success-message'>Cargo do membro atualizado com sucesso!</p>";
            break;
        case 'error':
            $feedback_message = "<p class='error-message'>Ocorreu um erro ao processar a solicitação.</p>";
            break;
    }
}

// Busca dados da comunidade para edição E a lista de membros
if (isset($_GET['edit_id'])) {
    $id_to_edit = intval($_GET['edit_id']);
    $stmt_comm = $pdo->prepare("SELECT * FROM communities WHERE community_id = ?");
    $stmt_comm->execute([$id_to_edit]);
    $community_to_edit = $stmt_comm->fetch(PDO::FETCH_ASSOC);

    $stmt_members = $pdo->prepare("
        SELECT u.user_id, u.full_name, u.email, u.profile_image_url, uc.member_type, uc.joined_at 
        FROM user_communities uc 
        JOIN users u ON uc.user_id = u.user_id 
        WHERE uc.community_id = ? 
        ORDER BY FIELD(uc.member_type, 'admin', 'moderator', 'member'), u.full_name ASC
    ");
    $stmt_members->execute([$id_to_edit]);
    $members_list = $stmt_members->fetchAll(PDO::FETCH_ASSOC);
}

// Busca todas as comunidades com contagem de membros para a lista principal
$sql_lista = "SELECT c.*, COUNT(uc.user_id) as total_members, u.full_name as creator_name
              FROM communities c 
              LEFT JOIN user_communities uc ON c.community_id = uc.community_id
              JOIN users u ON c.creator_id = u.user_id
              GROUP BY c.community_id 
              ORDER BY c.community_id DESC";
$communities_result = $pdo->query($sql_lista);
?>

<main class="container">
    <h1>Comunidades</h1>
    <?php echo $feedback_message; ?>

    <div class="table-container">
        <h2>Lista de Comunidades</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Criador</th>
                    <th>Membros</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($communities_result && $communities_result->rowCount() > 0): ?>
                    <?php while ($comm = $communities_result->fetch(PDO::FETCH_ASSOC)): ?>
                        <tr>
                            <td><?php echo $comm['community_id']; ?></td>
                            <td><?php echo htmlspecialchars($comm['name']); ?></td>
                            <td><?php echo htmlspecialchars($comm['creator_name']); ?></td>
                            <td><?php echo $comm['total_members']; ?></td>
                            <td class="actions">
                                <a href="admin_communities.php?edit_id=<?php echo $comm['community_id']; ?>#form-community" class="btn btn-icon btn-edit" title="Editar">
                                    <i class="ri-pencil-line"></i>
                                </a>
                                <a href="admin_communities.php?action=delete&id=<?php echo $comm['community_id']; ?>" onclick="return confirm('Tem a certeza? A comunidade e todas as suas associações serão removidas.');" class="btn btn-icon btn-delete" title="Apagar">
                                    <i class="ri-delete-bin-line"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">Nenhuma comunidade encontrada.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="form-container" id="form-community">
        <h2><?php echo $community_to_edit ? 'Editar Comunidade' : 'Adicionar Nova Comunidade'; ?></h2>
        <form action="admin_communities.php" method="POST">
            <?php if ($community_to_edit): ?>
                <input type="hidden" name="community_id" value="<?php echo $community_to_edit['community_id']; ?>">
            <?php endif; ?>
            <div class="form-group">
                <label for="name">Nome da Comunidade</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($community_to_edit['name'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="description">Descrição</label>
                <textarea id="description" name="description" rows="3" required><?php echo htmlspecialchars($community_to_edit['description'] ?? ''); ?></textarea>
            </div>

            <?php if ($community_to_edit): ?>
                <div class="form-group">
                    <h2>Membros de "<?php echo htmlspecialchars($community_to_edit['name']); ?>"</h2>
                    <table>
                        <thead>
                            <tr>
                                <th>Membro</th>
                                <th>Email</th>
                                <th>Cargo</th>
                                <th>Desde</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($members_list)): ?>
                                <?php foreach ($members_list as $member): ?>
                                    <tr>
                                        <td>
                                            <div class="user-info">
                                                <img src="<?php echo htmlspecialchars(str_replace('../', '../../', $member['profile_image_url'])); ?>" alt="Foto de Perfil">
                                                <span><?php echo htmlspecialchars($member['full_name']); ?></span>
                                            </div>
                                        </td>
                                        <td><?php echo htmlspecialchars($member['email']); ?></td>
                                        <td>
                                            <span class="status-badge status-<?php echo strtolower($member['member_type']); ?>">
                                                <?php echo htmlspecialchars(ucfirst($member['member_type'])); ?>
                                            </span>
                                        </td>
                                        <td><?php echo date("d/m/Y", strtotime($member['joined_at'])); ?></td>
                                        <td class="actions">
                                            <div class="profile-dropdown">
                                                <button type="button" class="btn btn-icon" title="Mais Ações"><i class="ri-settings-line"></i></button>
                                                <div class="dropdown-content">
                                                    <a href="admin_communities.php?action=change_role&community_id=<?php echo $id_to_edit; ?>&user_id=<?php echo $member['user_id']; ?>&role=admin">Promover a Admin</a>
                                                    <a href="admin_communities.php?action=change_role&community_id=<?php echo $id_to_edit; ?>&user_id=<?php echo $member['user_id']; ?>&role=moderator">Promover a Moderador</a>
                                                    <a href="admin_communities.php?action=change_role&community_id=<?php echo $id_to_edit; ?>&user_id=<?php echo $member['user_id']; ?>&role=member">Rebaixar a Membro</a>
                                                    <a href="admin_communities.php?action=remove_member&community_id=<?php echo $id_to_edit; ?>&user_id=<?php echo $member['user_id']; ?>" onclick="return confirm('Tem certeza que deseja remover este membro?');" style="color: var(--danger-color);">Remover Membro</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5">Esta comunidade ainda não possui membros.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>

            <div class="form-actions">
                <button type="submit" name="save_community" class="btn btn-primary">
                    <i class="ri-save-line"></i> Salvar Comunidade
                </button>
                <?php if ($community_to_edit): ?>
                    <a href="admin_communities.php" class="btn">Cancelar Edição</a>
                <?php endif; ?>
            </div>
        </form>
    </div>
</main>

<?php include 'admin_footer.php'; ?>