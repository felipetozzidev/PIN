<?php
// O cabeçalho já inicia a sessão e faz a conexão via PDO
include('admin_header.php');
require_once('../../config/log_helper.php');

// Inicializa variáveis
$feedback_message = '';
$edit_mode = false;
$role_to_edit = ['role_id' => '', 'name' => '', 'description' => ''];
$users_with_role = [];
$admin_user_id = $_SESSION['user_id'];
$admin_user_name = $_SESSION['full_name'] ?? 'Administrador';

// --- LÓGICA PARA PROCESSAR AÇÕES (POST e GET) ---

// 1. AÇÃO DE ADICIONAR OU ATUALIZAR (via POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $role_id = isset($_POST['role_id']) ? intval($_POST['role_id']) : 0;

    if ($role_id > 0) { // Atualizar
        $stmt = $pdo->prepare("UPDATE roles SET name = ?, description = ? WHERE role_id = ?");
        if ($stmt->execute([$name, $description, $role_id])) {
            $feedback_message = "<p class='success-message'>Nível de acesso atualizado com sucesso!</p>";
            logAction($pdo, 'Nível Atualizado', $admin_user_name, "Nível '{$name}' (ID: #{$role_id}) foi atualizado.", $admin_user_id);
        } else {
            $feedback_message = "<p class='error-message'>Erro ao atualizar o nível de acesso.</p>";
        }
    } else { // Adicionar
        $stmt = $pdo->prepare("INSERT INTO roles (name, description) VALUES (?, ?)");
        if ($stmt->execute([$name, $description])) {
            $new_id = $pdo->lastInsertId();
            $feedback_message = "<p class='success-message'>Nível de acesso adicionado com sucesso!</p>";
            logAction($pdo, 'Nível Adicionado', $admin_user_name, "Nível '{$name}' (ID: #{$new_id}) foi criado.", $admin_user_id);
        } else {
            $feedback_message = "<p class='error-message'>Erro ao adicionar o nível de acesso.</p>";
        }
    }
}

// 2. AÇÃO DE DELETAR (via GET)
if (isset($_GET['delete_id'])) {
    $id_to_delete = intval($_GET['delete_id']);
    $admin_role_id = $_SESSION['role_id'];

    if ($id_to_delete == $admin_role_id) {
        $feedback_message = "<p class='error-message'>Você não pode deletar seu próprio nível de acesso.</p>";
    } else {
        $stmt_get_name = $pdo->prepare("SELECT name FROM roles WHERE role_id = ?");
        $stmt_get_name->execute([$id_to_delete]);
        $role_data = $stmt_get_name->fetch();
        $role_name_for_log = $role_data ? $role_data['name'] : 'Desconhecido';

        try {
            $pdo->beginTransaction();
            // Lógica para encontrar um nível substituto (fallback)
            // Esta lógica pode ser simplificada para sempre mover para o nível 'Discente' (ID 4) se ele existir.
            // Aqui mantemos a sua lógica original de buscar o próximo ou anterior.
            $stmt_fallback = $pdo->prepare("SELECT role_id FROM roles WHERE role_id != ? ORDER BY role_id LIMIT 1");
            $stmt_fallback->execute([$id_to_delete]);
            $fallback_role = $stmt_fallback->fetch();
            $fallback_role_id = $fallback_role ? $fallback_role['role_id'] : null;

            if ($fallback_role_id) {
                $stmt_update_users = $pdo->prepare("UPDATE users SET role_id = ? WHERE role_id = ?");
                $stmt_update_users->execute([$fallback_role_id, $id_to_delete]);
            }

            $stmt_delete_level = $pdo->prepare("DELETE FROM roles WHERE role_id = ?");
            $stmt_delete_level->execute([$id_to_delete]);

            $pdo->commit();
            $feedback_message = "<p class='success-message'>Nível de acesso deletado! Usuários associados foram movidos.</p>";

            $log_details = "Nível '{$role_name_for_log}' (ID: #{$id_to_delete}) foi deletado.";
            if ($fallback_role_id) {
                $log_details .= " Usuários movidos para o nível ID #{$fallback_role_id}.";
            }
            logAction($pdo, 'Nível Excluído', $admin_user_name, $log_details, $admin_user_id);
        } catch (Exception $e) {
            $pdo->rollBack();
            $feedback_message = "<p class='error-message'>Erro ao deletar o nível de acesso: " . $e->getMessage() . "</p>";
        }
    }
}

// 3. AÇÃO PARA ENTRAR EM MODO DE EDIÇÃO (via GET)
if (isset($_GET['edit_id'])) {
    $role_id = intval($_GET['edit_id']);
    $edit_mode = true;

    $stmt = $pdo->prepare("SELECT * FROM roles WHERE role_id = ?");
    $stmt->execute([$role_id]);
    $role_to_edit = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt_users = $pdo->prepare("SELECT user_id, full_name, email, profile_image_url FROM users WHERE role_id = ? ORDER BY full_name ASC");
    $stmt_users->execute([$role_id]);
    $users_with_role = $stmt_users->fetchAll(PDO::FETCH_ASSOC);
}


// --- CONSULTA PARA EXIBIR A TABELA DE NÍVEIS ---
$sql = "SELECT r.*, (SELECT COUNT(*) FROM users u WHERE u.role_id = r.role_id) as total_users 
        FROM roles r 
        ORDER BY r.role_id ASC";
$roles_result = $pdo->query($sql);
?>

<main class="container">
    <div class="page-header">
        <h1>Níveis de Acesso</h1>
    </div>

    <?php echo $feedback_message; ?>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome do Nível</th>
                    <th>Descrição</th>
                    <th>Usuários</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($roles_result && $roles_result->rowCount() > 0): ?>
                    <?php while ($role = $roles_result->fetch(PDO::FETCH_ASSOC)): ?>
                        <tr>
                            <td><?php echo $role['role_id']; ?></td>
                            <td><?php echo htmlspecialchars($role['name']); ?></td>
                            <td><?php echo htmlspecialchars($role['description'] ?? 'N/A'); ?></td>
                            <td><span class="badge"><?php echo $role['total_users']; ?></span></td>
                            <td class="actions">
                                <?php $is_self_level = ($role['role_id'] == $_SESSION['role_id']); ?>

                                <button
                                    class="btn btn-icon btn-edit"
                                    title="Editar Nível"
                                    onclick="window.location.href='admin_roles.php?edit_id=<?php echo $role['role_id']; ?>#form-role';">
                                    <i class="ri-pencil-line"></i>
                                </button>

                                <button
                                    class="btn btn-icon <?php echo $is_self_level ? '' : 'btn-delete'; ?>"
                                    title="<?php echo $is_self_level ? 'Você não pode deletar seu próprio nível' : 'Deletar Nível'; ?>"
                                    onclick="<?php echo $is_self_level ? '' : "if(confirm('Tem certeza? Usuários associados serão movidos.')) { window.location.href='admin_roles.php?delete_id=" . $role['role_id'] . "'; }"; ?>"
                                    <?php if ($is_self_level) echo 'disabled'; ?>>
                                    <i class="ri-delete-bin-line"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">Nenhum nível de acesso encontrado.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="form-container" id="form-role">
        <h2><?php echo $edit_mode ? 'Editar Nível de Acesso' : 'Adicionar Novo Nível'; ?></h2>
        <form action="admin_roles.php" method="POST">
            <?php if ($edit_mode): ?>
                <input type="hidden" name="role_id" value="<?php echo $role_to_edit['role_id']; ?>">
            <?php endif; ?>

            <div class="form-group">
                <label for="name">Nome do Nível:</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($role_to_edit['name']); ?>" required>
            </div>
            <div class="form-group">
                <label for="description">Descrição:</label>
                <textarea id="description" name="description" rows="3"><?php echo htmlspecialchars($role_to_edit['description']); ?></textarea>
            </div>

            <?php if ($edit_mode): ?>
                <div class="form-group">
                    <h2>Usuários com o nível "<?php echo htmlspecialchars($role_to_edit['name']); ?>"</h2>
                    <table>
                        <thead>
                            <tr>
                                <th>Usuário</th>
                                <th>Email</th>
                                <th>Ação</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($users_with_role)): ?>
                                <?php foreach ($users_with_role as $user): ?>
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
                                        <td class="actions">
                                            <a href="admin_users.php?search=<?php echo urlencode($user['email']); ?>" class="btn btn-primary" target="_blank">
                                                <i class="ri-eye-line"></i> Ver Usuário
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="3">Nenhum usuário encontrado com este nível de acesso.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary"><?php echo $edit_mode ? 'Salvar Alterações' : 'Adicionar Nível'; ?></button>
                <?php if ($edit_mode): ?>
                    <a href="admin_roles.php" class="btn">Cancelar Edição</a>
                <?php endif; ?>
            </div>
        </form>
    </div>
</main>

<?php
include('admin_footer.php');
?>