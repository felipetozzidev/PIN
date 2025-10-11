<?php
// O cabeçalho já inicia a sessão e faz a conexão via PDO
include('admin_header.php');
require_once('../../config/log_helper.php');

$feedback_message = '';
$admin_user_id = $_SESSION['user_id'];
$admin_user_name = $_SESSION['full_name'] ?? 'Administrador';

// Lógica para apagar usuário
if (isset($_GET['delete_id'])) {
    $id_to_delete = intval($_GET['delete_id']);
    if ($id_to_delete == $admin_user_id) {
        $feedback_message = "<p class='error-message'>Você não pode apagar a sua própria conta.</p>";
    } else {
        // Buscar nome do usuário antes de deletar para o log
        $stmt_get_name = $pdo->prepare("SELECT full_name FROM users WHERE user_id = ?");
        $stmt_get_name->execute([$id_to_delete]);
        $user_data = $stmt_get_name->fetch();
        $user_name_for_log = $user_data ? $user_data['full_name'] : 'Desconhecido';

        $stmt = $pdo->prepare("DELETE FROM users WHERE user_id = ?");
        if ($stmt->execute([$id_to_delete])) {
            $feedback_message = "<p class='success-message'>Usuário apagado com sucesso!</p>";
            logAction($pdo, 'Usuário Deletado', $admin_user_name, "Usuário '{$user_name_for_log}' (ID: #{$id_to_delete}) foi excluído.", $admin_user_id);
        } else {
            $feedback_message = "<p class='error-message'>Erro ao apagar usuário.</p>";
        }
    }
}

// Lógica para atualizar o nível do usuário
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_role'])) {
    $id_to_update = intval($_POST['user_id']);
    $new_role_id = intval($_POST['role_id']);

    if ($id_to_update == $admin_user_id) {
        $feedback_message = "<p class='error-message'>Você não pode alterar o seu próprio nível de acesso.</p>";
    } else {
        $stmt = $pdo->prepare("UPDATE users SET role_id = ? WHERE user_id = ?");
        if ($stmt->execute([$new_role_id, $id_to_update])) {
            $feedback_message = "<p class='success-message'>Nível do usuário atualizado com sucesso!</p>";

            // Buscar nomes para o log
            $stmt_info = $pdo->prepare("SELECT u.full_name, r.name FROM users u JOIN roles r ON r.role_id = ? WHERE u.user_id = ?");
            $stmt_info->execute([$new_role_id, $id_to_update]);
            $info_data = $stmt_info->fetch();
            $user_name_for_log = $info_data['full_name'] ?? 'ID ' . $id_to_update;
            $role_name_for_log = $info_data['name'] ?? 'ID ' . $new_role_id;

            logAction($pdo, 'Usuário Modificado', $admin_user_name, "Nível do usuário '{$user_name_for_log}' (ID: #{$id_to_update}) foi alterado para '{$role_name_for_log}'.", $admin_user_id);
        } else {
            $feedback_message = "<p class='error-message'>Erro ao atualizar o nível.</p>";
        }
    }
}

// Buscar todos os níveis para o <select>
$roles_stmt = $pdo->query("SELECT * FROM roles");
$roles = $roles_stmt->fetchAll(PDO::FETCH_ASSOC);

// Lógica de busca
$search_query = isset($_GET['search']) ? trim($_GET['search']) : '';
$sql = "SELECT u.user_id, u.full_name, u.email, u.profile_image_url, r.name as role_name, u.role_id, u.created_at, u.updated_at 
        FROM users u 
        LEFT JOIN roles r ON u.role_id = r.role_id";
$params = [];
if (!empty($search_query)) {
    $sql .= " WHERE u.full_name LIKE ? OR u.email LIKE ?";
    $params[] = "%$search_query%";
    $params[] = "%$search_query%";
}
$sql .= " ORDER BY u.user_id DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<main class="container">
    <div class="page-header">
        <h1>Usuários</h1>
        <form action="admin_users.php" method="GET" class="search-form">
            <input type="search" name="search" class="header-search" placeholder="Nome ou email..." value="<?php echo htmlspecialchars($search_query); ?>">
            <button type="submit" class="btn btn-primary"><i class="ri-search-line"></i></button>
        </form>
    </div>

    <?php echo $feedback_message; ?>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Usuário</th>
                    <th>Email</th>
                    <th>Nível de Acesso</th>
                    <th>Data Criação</th>
                    <th>Data Atualização</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($users && count($users) > 0): ?>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td>
                                <div class="user-info">
                                    <?php
                                    $db_path = $user['profile_image_url'];
                                    $image_path = (substr($db_path, 0, 3) === '../') ? '../../' . substr($db_path, 3) : $db_path;
                                    ?>
                                    <img src="<?php echo htmlspecialchars($image_path); ?>" alt="Foto de Perfil">
                                    <span><?php echo htmlspecialchars($user['full_name']); ?></span>
                                </div>
                            </td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td>
                                <form action="admin_users.php" method="POST" style="display: flex; gap: 0.5rem;">
                                    <input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>">
                                    <select name="role_id" <?php if ($user['user_id'] == $_SESSION['user_id']) echo 'disabled'; ?>>
                                        <?php
                                        foreach ($roles as $role) {
                                            $selected = ($role['role_id'] == $user['role_id']) ? 'selected' : '';
                                            echo "<option value='{$role['role_id']}' {$selected}>" . htmlspecialchars($role['name']) . "</option>";
                                        }
                                        ?>
                                    </select>
                                    <button type="submit" name="update_role" class="btn btn-primary" <?php if ($user['user_id'] == $_SESSION['user_id']) echo 'disabled'; ?>>Salvar</button>
                                </form>
                            </td>
                            <td><?php echo date("d/m/Y H:i", strtotime($user['created_at'])); ?></td>
                            <td><?php echo date("d/m/Y H:i", strtotime($user['updated_at'])); ?></td>
                            <td>
                                <div class="actions">
                                    <?php $is_self = ($user['user_id'] == $_SESSION['user_id']); ?>
                                    <button
                                        class="btn btn-icon <?php echo $is_self ? '' : 'btn-delete'; ?>"
                                        title="<?php echo $is_self ? 'Você não pode apagar a sua própria conta' : 'Apagar Usuário'; ?>"
                                        onclick="<?php echo $is_self ? '' : "if(confirm('Tem certeza que deseja apagar este usuário? Esta ação é irreversível.')) { window.location.href='admin_users.php?delete_id=" . $user['user_id'] . "'; }"; ?>"
                                        <?php if ($is_self) echo 'disabled'; ?>>
                                        <i class="ri-delete-bin-7-line"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6">Nenhum usuário encontrado.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>

<?php
include('admin_footer.php');
?>