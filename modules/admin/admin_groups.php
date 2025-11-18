<?php
// O cabeçalho já inicia a sessão e faz a conexão via PDO
include('admin_header.php');
require_once('../../config/log_helper.php');

$feedback_message = '';
$edit_mode = false;
$group_to_edit = null;
$members_list = [];
$admin_user_id = $_SESSION['user_id'];
$admin_user_name = $_SESSION['full_name'] ?? 'Administrador';

// --- PROCESSAMENTO DE AÇÕES ---

// 1. ADICIONAR OU ATUALIZAR GRUPO (via POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_group'])) {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $group_id = isset($_POST['group_id']) ? intval($_POST['group_id']) : 0;

    if (empty($name)) {
        $feedback_message = "<p class='error-message'>O nome do grupo é obrigatório.</p>";
    } else {
        if ($group_id > 0) { // Atualizar
            $stmt = $pdo->prepare("UPDATE groups SET name = ?, description = ? WHERE group_id = ?");
            if ($stmt->execute([$name, $description, $group_id])) {
                $feedback_message = "<p class='success-message'>Grupo atualizado com sucesso!</p>";
                logAction($pdo, 'Grupo Editado', $admin_user_name, "Grupo '{$name}' (ID: #{$group_id}) foi atualizado.", $admin_user_id);
            } else {
                $feedback_message = "<p class='error-message'>Erro ao atualizar o grupo.</p>";
            }
        } else { // Inserir
            $creator_id = $_SESSION['user_id'];
            $stmt = $pdo->prepare("INSERT INTO groups (name, description, creator_id) VALUES (?, ?, ?)");
            if ($stmt->execute([$name, $description, $creator_id])) {
                $new_id = $pdo->lastInsertId();
                $feedback_message = "<p class='success-message'>Grupo criado com sucesso!</p>";
                logAction($pdo, 'Novo Grupo', $admin_user_name, "Grupo '{$name}' (ID: #{$new_id}) foi criado.", $admin_user_id);
            } else {
                $feedback_message = "<p class='error-message'>Erro ao criar o grupo.</p>";
            }
        }
    }
}

// 2. AÇÕES VIA GET (DELETAR GRUPO, EDITAR, REMOVER MEMBRO)
if (isset($_GET['action'])) {
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;

    // Ação para deletar o grupo
    if ($_GET['action'] == 'delete' && $id > 0) {
        $stmt_get_name = $pdo->prepare("SELECT name FROM groups WHERE group_id = ?");
        $stmt_get_name->execute([$id]);
        $group_data = $stmt_get_name->fetch();
        $group_name_for_log = $group_data ? $group_data['name'] : 'Desconhecido';

        $stmt = $pdo->prepare("DELETE FROM groups WHERE group_id = ?");
        if ($stmt->execute([$id])) {
            $feedback_message = "<p class='success-message'>Grupo deletado com sucesso!</p>";
            logAction($pdo, 'Grupo Excluído', $admin_user_name, "Grupo '{$group_name_for_log}' (ID: #{$id}) foi excluído.", $admin_user_id);
        } else {
            $feedback_message = "<p class='error-message'>Erro ao deletar o grupo.</p>";
        }
    }

    // Ação para entrar no modo de edição
    if ($_GET['action'] == 'edit' && $id > 0) {
        $edit_mode = true;
        $stmt_group = $pdo->prepare("SELECT * FROM groups WHERE group_id = ?");
        $stmt_group->execute([$id]);
        $group_to_edit = $stmt_group->fetch(PDO::FETCH_ASSOC);

        $stmt_members = $pdo->prepare("
            SELECT u.user_id, u.full_name, u.email, u.profile_image_url, ug.joined_at
            FROM user_groups ug
            JOIN users u ON ug.user_id = u.user_id
            WHERE ug.group_id = ?
            ORDER BY u.full_name ASC
        ");
        $stmt_members->execute([$id]);
        $members_list = $stmt_members->fetchAll(PDO::FETCH_ASSOC);
    }

    // Ação para remover um membro do grupo
    if ($_GET['action'] == 'remove_member' && isset($_GET['user_id']) && isset($_GET['group_id'])) {
        $user_id = intval($_GET['user_id']);
        $group_id = intval($_GET['group_id']);

        // (Opcional: lógica de log para remoção de membro)
        $stmt = $pdo->prepare("DELETE FROM user_groups WHERE user_id = ? AND group_id = ?");
        if ($stmt->execute([$user_id, $group_id])) {
            header("Location: admin_groups.php?action=edit&id=$group_id&feedback=member_removed");
        } else {
            header("Location: admin_groups.php?action=edit&id=$group_id&feedback=error");
        }
        exit();
    }
}

if (isset($_GET['feedback'])) {
    if ($_GET['feedback'] == 'member_removed') {
        $feedback_message = "<p class='success-message'>Membro removido do grupo com sucesso!</p>";
    }
    if ($_GET['feedback'] == 'error') {
        $feedback_message = "<p class='error-message'>Ocorreu um erro ao processar a solicitação.</p>";
    }
}

// --- CONSULTA PARA EXIBIR A TABELA DE GRUPOS ---
$search_query = isset($_GET['search']) ? trim($_GET['search']) : '';
$sql = "SELECT g.group_id, g.name, g.description, g.created_at, u.full_name as creator_name, COUNT(ug.user_id) as total_members
        FROM groups g
        JOIN users u ON g.creator_id = u.user_id
        LEFT JOIN user_groups ug ON g.group_id = ug.group_id";
$params = [];
if (!empty($search_query)) {
    $sql .= " WHERE g.name LIKE ? OR g.description LIKE ?";
    $params[] = "%$search_query%";
    $params[] = "%$search_query%";
}
$sql .= " GROUP BY g.group_id ORDER BY g.created_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$groups_result = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<main class="container">
    <div class="page-header">
        <h1>Grupos</h1>
        <form action="admin_groups.php" method="GET" class="search-form">
            <input type="search" name="search" placeholder="Buscar por nome ou descrição..." value="<?php echo htmlspecialchars($search_query); ?>">
            <button type="submit" class="btn btn-primary"><i class="ri-search-line"></i></button>
        </form>
    </div>

    <?php echo $feedback_message; ?>

    <div class="table-container">
        <h2>Lista de Grupos</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome do Grupo</th>
                    <th>Criador</th>
                    <th>Membros</th>
                    <th>Data de Criação</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($groups_result && count($groups_result) > 0): ?>
                    <?php foreach ($groups_result as $group): ?>
                        <tr>
                            <td><?php echo $group['group_id']; ?></td>
                            <td><?php echo htmlspecialchars($group['name']); ?></td>
                            <td><?php echo htmlspecialchars($group['creator_name']); ?></td>
                            <td><?php echo $group['total_members']; ?></td>
                            <td><?php echo date("d/m/Y H:i", strtotime($group['created_at'])); ?></td>
                            <td class="actions">
                                <a href="admin_groups.php?action=edit&id=<?php echo $group['group_id']; ?>#form-group" class="btn btn-icon btn-edit" title="Editar Grupo">
                                    <i class="ri-pencil-line"></i>
                                </a>
                                <a href="admin_groups.php?action=delete&id=<?php echo $group['group_id']; ?>" onclick="return confirm('Tem certeza que deseja deletar este grupo? Esta ação é irreversível.');" class="btn btn-icon btn-delete" title="Deletar Grupo">
                                    <i class="ri-delete-bin-line"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6">Nenhum grupo encontrado.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="form-container" id="form-group">
        <h2><?php echo $edit_mode ? 'Editar Grupo' : 'Adicionar Novo Grupo'; ?></h2>
        <form action="admin_groups.php" method="POST">
            <?php if ($edit_mode): ?>
                <input type="hidden" name="group_id" value="<?php echo $group_to_edit['group_id']; ?>">
            <?php endif; ?>

            <div class="form-group">
                <label for="name">Nome do Grupo:</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($group_to_edit['name'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="description">Descrição:</label>
                <textarea id="description" name="description" rows="3"><?php echo htmlspecialchars($group_to_edit['description'] ?? ''); ?></textarea>
            </div>

            <?php if ($edit_mode && !empty($members_list)): ?>
                <div class="form-group">
                    <h2>Membros do Grupo "<?php echo htmlspecialchars($group_to_edit['name']); ?>"</h2>
                    <table>
                        <thead>
                            <tr>
                                <th>Membro</th>
                                <th>Email</th>
                                <th>Data de Entrada</th>
                                <th>Ação</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($members_list as $member): ?>
                                <tr>
                                    <td>
                                        <div class="user-info">
                                            <img src="<?php echo htmlspecialchars(str_replace('../', '../../', $member['profile_image_url'])); ?>" alt="Foto de Perfil">
                                            <span><?php echo htmlspecialchars($member['full_name']); ?></span>
                                        </div>
                                    </td>
                                    <td><?php echo htmlspecialchars($member['email']); ?></td>
                                    <td><?php echo date("d/m/Y", strtotime($member['joined_at'])); ?></td>
                                    <td class="actions">
                                        <a href="admin_groups.php?action=remove_member&user_id=<?php echo $member['user_id']; ?>&group_id=<?php echo $group_to_edit['group_id']; ?>" onclick="return confirm('Tem certeza que deseja remover este membro do grupo?');" class="btn btn-icon btn-delete" title="Remover Membro">
                                            <i class="ri-user-unfollow-line"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php elseif ($edit_mode): ?>
                <div class="form-group">
                    <h2>Membros do Grupo</h2>
                    <p>Este grupo ainda não possui membros.</p>
                </div>
            <?php endif; ?>

            <div class="form-actions">
                <button type="submit" name="save_group" class="btn btn-primary"><?php echo $edit_mode ? 'Salvar Alterações' : 'Adicionar Grupo'; ?></button>
                <?php if ($edit_mode): ?>
                    <a href="admin_groups.php" class="btn">Cancelar Edição</a>
                <?php endif; ?>
            </div>
        </form>
    </div>
</main>

<?php
include('admin_footer.php');
?>