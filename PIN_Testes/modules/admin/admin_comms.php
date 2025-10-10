<?php
require_once('../../config/conn.php');
require_once('../../config/log_helper.php'); // Adicionado para usar a função de log
include 'admin_header.php';

$feedback_message = '';
$comunidade_para_editar = null;
$members_list = [];

// Lógica para Adicionar ou Editar Comunidade
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_comm'])) {
    $nome_com = trim($_POST['nome_com']);
    $descricao_com = trim($_POST['descricao_com']);
    $id_com = isset($_POST['id_com']) ? intval($_POST['id_com']) : 0;
    $admin_user_name = $_SESSION['nome_usu'] ?? 'Administrador'; // Pega nome do admin da sessão

    if (empty($nome_com) || empty($descricao_com)) {
        $feedback_message = "<p class='error-message'>Nome e descrição são obrigatórios.</p>";
    } else {
        if ($id_com > 0) { // Atualizar
            $stmt = $conn->prepare("UPDATE comunidades SET nome_com = ?, descricao_com = ? WHERE id_com = ?");
            $stmt->bind_param("ssi", $nome_com, $descricao_com, $id_com);

            if ($stmt->execute()) {
                $feedback_message = "<p class='success-message'>Comunidade salva com sucesso!</p>";
                log_activity($conn, 'Comunidade Editada', $admin_user_name, "Comunidade '{$nome_com}' (ID: #{$id_com}) foi atualizada.");
            } else {
                $feedback_message = "<p class='error-message'>Erro ao salvar comunidade.</p>";
            }
        } else { // Inserir
            $id_criador = $_SESSION['id_usu']; // O admin logado é o criador
            $stmt = $conn->prepare("INSERT INTO comunidades (nome_com, descricao_com, id_criador) VALUES (?, ?, ?)");
            $stmt->bind_param("ssi", $nome_com, $descricao_com, $id_criador);

            if ($stmt->execute()) {
                $new_comm_id = $conn->insert_id;
                $feedback_message = "<p class='success-message'>Comunidade salva com sucesso!</p>";
                log_activity($conn, 'Nova Comunidade', $admin_user_name, "Comunidade '{$nome_com}' (ID: #{$new_comm_id}) foi criada.");
            } else {
                $feedback_message = "<p class='error-message'>Erro ao salvar comunidade.</p>";
            }
        }
        $stmt->close();
    }
}

// Lógica para Ações (Apagar comunidade, remover membro, alterar cargo)
if (isset($_GET['action'])) {
    $admin_user_name = $_SESSION['nome_usu'] ?? 'Administrador';

    // Ação para apagar a comunidade inteira
    if ($_GET['action'] == 'delete' && isset($_GET['id'])) {
        $id_para_deletar = intval($_GET['id']);

        // Antes de deletar, buscar o nome da comunidade para o log
        $stmt_get_name = $conn->prepare("SELECT nome_com FROM comunidades WHERE id_com = ?");
        $stmt_get_name->bind_param("i", $id_para_deletar);
        $stmt_get_name->execute();
        $result_get_name = $stmt_get_name->get_result();
        $comm_data = $result_get_name->fetch_assoc();
        $comm_name_for_log = $comm_data ? $comm_data['nome_com'] : 'Desconhecida';
        $stmt_get_name->close();

        $stmt = $conn->prepare("DELETE FROM comunidades WHERE id_com = ?");
        $stmt->bind_param("i", $id_para_deletar);
        if ($stmt->execute()) {
            log_activity($conn, 'Comunidade Excluída', $admin_user_name, "Comunidade '{$comm_name_for_log}' (ID: #{$id_para_deletar}) foi excluída.");
            header("Location: admin_comms.php?feedback=deleted_ok");
        } else {
            header("Location: admin_comms.php?feedback=error");
        }
        $stmt->close();
        exit();
    }

    // Ação para remover um membro da comunidade
    if ($_GET['action'] == 'remove_member' && isset($_GET['user_id']) && isset($_GET['comm_id'])) {
        $user_id = intval($_GET['user_id']);
        $comm_id = intval($_GET['comm_id']);

        // Buscar nomes para o log
        $stmt_info = $conn->prepare("SELECT u.nome_usu, c.nome_com FROM usuarios u, comunidades c WHERE u.id_usu = ? AND c.id_com = ?");
        $stmt_info->bind_param("ii", $user_id, $comm_id);
        $stmt_info->execute();
        $info_data = $stmt_info->get_result()->fetch_assoc();
        $user_name_for_log = $info_data['nome_usu'] ?? 'ID ' . $user_id;
        $comm_name_for_log = $info_data['nome_com'] ?? 'ID ' . $comm_id;
        $stmt_info->close();

        $stmt = $conn->prepare("DELETE FROM usuarios_comunidades WHERE id_usu = ? AND id_com = ?");
        $stmt->bind_param("ii", $user_id, $comm_id);
        if ($stmt->execute()) {
            log_activity($conn, 'Membro Removido', $admin_user_name, "Usuário '{$user_name_for_log}' (ID: #{$user_id}) foi removido da comunidade '{$comm_name_for_log}' (ID: #{$comm_id}).");
            header("Location: admin_comms.php?edit_id=$comm_id&feedback=member_removed");
        } else {
            header("Location: admin_comms.php?edit_id=$comm_id&feedback=error");
        }
        $stmt->close();
        exit();
    }

    // Ação para alterar o cargo de um membro
    if ($_GET['action'] == 'change_role' && isset($_GET['user_id']) && isset($_GET['comm_id']) && isset($_GET['role'])) {
        $user_id = intval($_GET['user_id']);
        $comm_id = intval($_GET['comm_id']);
        $role = $_GET['role'];

        if (in_array($role, ['membro', 'moderador', 'admin'])) {
            // Buscar nomes para o log
            $stmt_info = $conn->prepare("SELECT u.nome_usu, c.nome_com FROM usuarios u, comunidades c WHERE u.id_usu = ? AND c.id_com = ?");
            $stmt_info->bind_param("ii", $user_id, $comm_id);
            $stmt_info->execute();
            $info_data = $stmt_info->get_result()->fetch_assoc();
            $user_name_for_log = $info_data['nome_usu'] ?? 'ID ' . $user_id;
            $comm_name_for_log = $info_data['nome_com'] ?? 'ID ' . $comm_id;
            $stmt_info->close();

            $stmt = $conn->prepare("UPDATE usuarios_comunidades SET tipo_membro = ? WHERE id_usu = ? AND id_com = ?");
            $stmt->bind_param("sii", $role, $user_id, $comm_id);
            if ($stmt->execute()) {
                log_activity($conn, 'Cargo Alterado', $admin_user_name, "Cargo do usuário '{$user_name_for_log}' (ID: #{$user_id}) alterado para '{$role}' na comunidade '{$comm_name_for_log}' (ID: #{$comm_id}).");
                header("Location: admin_comms.php?edit_id=$comm_id&feedback=role_changed");
            } else {
                header("Location: admin_comms.php?edit_id=$comm_id&feedback=error");
            }
            $stmt->close();
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
    $id_para_editar = intval($_GET['edit_id']);

    // Busca dados da comunidade
    $stmt_comm = $conn->prepare("SELECT * FROM comunidades WHERE id_com = ?");
    $stmt_comm->bind_param("i", $id_para_editar);
    $stmt_comm->execute();
    $result_comm = $stmt_comm->get_result();
    $comunidade_para_editar = $result_comm->fetch_assoc();
    $stmt_comm->close();

    // Busca lista de membros da comunidade
    $stmt_members = $conn->prepare("
        SELECT u.id_usu, u.nome_usu, u.email_usu, u.imgperfil_usu, uc.tipo_membro, uc.data_entrada 
        FROM usuarios_comunidades uc 
        JOIN usuarios u ON uc.id_usu = u.id_usu 
        WHERE uc.id_com = ? 
        ORDER BY FIELD(uc.tipo_membro, 'admin', 'moderador', 'membro'), u.nome_usu ASC
    ");
    $stmt_members->bind_param("i", $id_para_editar);
    $stmt_members->execute();
    $result_members = $stmt_members->get_result();
    while ($row = $result_members->fetch_assoc()) {
        $members_list[] = $row;
    }
    $stmt_members->close();
}

// Busca todas as comunidades com contagem de membros para a lista principal
$sql_lista = "SELECT c.*, COUNT(uc.id_usu) as total_membros, u.nome_usu as nome_criador
              FROM comunidades c 
              LEFT JOIN usuarios_comunidades uc ON c.id_com = uc.id_com
              JOIN usuarios u ON c.id_criador = u.id_usu
              GROUP BY c.id_com 
              ORDER BY c.id_com DESC";
$resultado = $conn->query($sql_lista);
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
                <?php if ($resultado && $resultado->num_rows > 0): ?>
                    <?php while ($com = $resultado->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $com['id_com']; ?></td>
                            <td><?php echo htmlspecialchars($com['nome_com']); ?></td>
                            <td><?php echo htmlspecialchars($com['nome_criador']); ?></td>
                            <td><?php echo $com['total_membros']; ?></td>
                            <td class="actions">
                                <a href="admin_comms.php?edit_id=<?php echo $com['id_com']; ?>#form-comunidade" class="btn btn-icon btn-edit" title="Editar">
                                    <i class="ri-pencil-line"></i>
                                </a>
                                <a href="admin_comms.php?action=delete&id=<?php echo $com['id_com']; ?>" onclick="return confirm('Tem a certeza? A comunidade e todas as suas associações serão removidas.');" class="btn btn-icon btn-delete" title="Apagar">
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

    <div class="form-container" id="form-comunidade">
        <h2><?php echo $comunidade_para_editar ? 'Editar Comunidade' : 'Adicionar Nova Comunidade'; ?></h2>
        <form action="admin_comms.php" method="POST">
            <?php if ($comunidade_para_editar): ?>
                <input type="hidden" name="id_com" value="<?php echo $comunidade_para_editar['id_com']; ?>">
            <?php endif; ?>
            <div class="form-group">
                <label for="nome_com">Nome da Comunidade</label>
                <input type="text" id="nome_com" name="nome_com" value="<?php echo htmlspecialchars($comunidade_para_editar['nome_com'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="descricao_com">Descrição</label>
                <textarea id="descricao_com" name="descricao_com" rows="3" required><?php echo htmlspecialchars($comunidade_para_editar['descricao_com'] ?? ''); ?></textarea>
            </div>

            <!-- Seção de Membros (só aparece no modo de edição) -->
            <?php if ($comunidade_para_editar): ?>
                <div class="form-group">
                    <h2>Membros de "<?php echo htmlspecialchars($comunidade_para_editar['nome_com']); ?>"</h2>
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
                                                <?php
                                                $db_path = $member['imgperfil_usu'];
                                                $image_path = $db_path;
                                                // Verifica se o caminho começa com '../' e o ajusta
                                                if (substr($db_path, 0, 3) === '../') {
                                                    $image_path = '../../' . substr($db_path, 3);
                                                }
                                                ?>
                                                <img src="<?php echo htmlspecialchars($image_path); ?>" alt="Foto de Perfil">
                                                <span><?php echo htmlspecialchars($member['nome_usu']); ?></span>
                                            </div>
                                        </td>
                                        <td><?php echo htmlspecialchars($member['email_usu']); ?></td>
                                        <td>
                                            <span class="status-badge status-<?php echo strtolower($member['tipo_membro']); ?>">
                                                <?php echo htmlspecialchars(ucfirst($member['tipo_membro'])); ?>
                                            </span>
                                        </td>
                                        <td><?php echo date("d/m/Y", strtotime($member['data_entrada'])); ?></td>
                                        <td class="actions">
                                            <div class="profile-dropdown">
                                                <button type="button" class="btn btn-icon" title="Mais Ações"><i class="ri-settings-line"></i></button>
                                                <div class="dropdown-content">
                                                    <a href="admin_comms.php?action=change_role&comm_id=<?php echo $id_para_editar; ?>&user_id=<?php echo $member['id_usu']; ?>&role=admin">Promover a Admin</a>
                                                    <a href="admin_comms.php?action=change_role&comm_id=<?php echo $id_para_editar; ?>&user_id=<?php echo $member['id_usu']; ?>&role=moderador">Promover a Moderador</a>
                                                    <a href="admin_comms.php?action=change_role&comm_id=<?php echo $id_para_editar; ?>&user_id=<?php echo $member['id_usu']; ?>&role=membro">Rebaixar a Membro</a>
                                                    <a href="admin_comms.php?action=remove_member&comm_id=<?php echo $id_para_editar; ?>&user_id=<?php echo $member['id_usu']; ?>" onclick="return confirm('Tem certeza que deseja remover este membro da comunidade?');" style="color: var(--danger-color);">Remover Membro</a>
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
            <button type="submit" name="save_comm" class="btn btn-primary">
                <i class="ri-save-line"></i> Salvar Comunidade
            </button>
            <?php if ($comunidade_para_editar): ?>
                <a href="admin_comms.php" class="btn">Cancelar Edição</a>
            <?php endif; ?>

        </form>
    </div>
</main>

<?php
include 'admin_footer.php';
$conn->close();
?>