<?php
// Habilita a exibição de todos os erros para diagnóstico.
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Garante que a conexão e os helpers sejam carregados primeiro.
require_once('../../config/conn.php');
require_once('../../config/log_helper.php'); // Adicionado para usar a função de log
include('admin_header.php');

// Inicializa variáveis para feedback e modo de edição.
$feedback_message = '';
$edit_mode = false;
$nivel_para_edicao = ['id_nvl' => '', 'nome_nvl' => '', 'descricao_nvl' => ''];
$users_with_level = []; // Array para guardar os usuários do nível

// --- LÓGICA PARA PROCESSAR AÇÕES (POST e GET) ---
$admin_user_name = $_SESSION['nome_usu'] ?? 'Administrador';

// 1. AÇÃO DE ADICIONAR OU ATUALIZAR (via POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome_nvl = trim($_POST['nome_nvl']);
    $descricao_nvl = trim($_POST['descricao_nvl']);

    if (isset($_POST['id_nvl']) && !empty($_POST['id_nvl'])) {
        $id_nvl = intval($_POST['id_nvl']);
        $stmt = $conn->prepare("UPDATE niveis SET nome_nvl = ?, descricao_nvl = ? WHERE id_nvl = ?");
        $stmt->bind_param("ssi", $nome_nvl, $descricao_nvl, $id_nvl);
        if ($stmt->execute()) {
            $feedback_message = "<p class='success-message'>Nível de acesso atualizado com sucesso!</p>";
            log_activity($conn, 'Nível Atualizado', $admin_user_name, "Nível '{$nome_nvl}' (ID: #{$id_nvl}) foi atualizado.");
        } else {
            $feedback_message = "<p class='error-message'>Erro ao atualizar o nível de acesso.</p>";
        }
        $stmt->close();
    } else {
        $stmt = $conn->prepare("INSERT INTO niveis (nome_nvl, descricao_nvl) VALUES (?, ?)");
        $stmt->bind_param("ss", $nome_nvl, $descricao_nvl);
        if ($stmt->execute()) {
            $new_id = $conn->insert_id;
            $feedback_message = "<p class='success-message'>Nível de acesso adicionado com sucesso!</p>";
            log_activity($conn, 'Nível Adicionado', $admin_user_name, "Nível '{$nome_nvl}' (ID: #{$new_id}) foi criado.");
        } else {
            $feedback_message = "<p class='error-message'>Erro ao adicionar o nível de acesso.</p>";
        }
        $stmt->close();
    }
}

// 2. AÇÃO DE DELETAR (via GET)
if (isset($_GET['delete_id'])) {
    $id_para_deletar = intval($_GET['delete_id']);
    $admin_level_id = $_SESSION['id_nvl'];

    if ($id_para_deletar == $admin_level_id) {
        $feedback_message = "<p class='error-message'>Você não pode deletar seu próprio nível de acesso.</p>";
    } else {
        $stmt_get_name = $conn->prepare("SELECT nome_nvl FROM niveis WHERE id_nvl = ?");
        $stmt_get_name->bind_param("i", $id_para_deletar);
        $stmt_get_name->execute();
        $level_data = $stmt_get_name->get_result()->fetch_assoc();
        $level_name_for_log = $level_data ? $level_data['nome_nvl'] : 'Desconhecido';
        $stmt_get_name->close();

        $conn->begin_transaction();
        try {
            $fallback_level_id = null;
            $stmt_next = $conn->prepare("SELECT MIN(id_nvl) as next_id FROM niveis WHERE id_nvl > ?");
            $stmt_next->bind_param("i", $id_para_deletar);
            $stmt_next->execute();
            $result_next = $stmt_next->get_result()->fetch_assoc();
            $stmt_next->close();

            if ($result_next && $result_next['next_id'] !== null) {
                $fallback_level_id = $result_next['next_id'];
            } else {
                $stmt_prev = $conn->prepare("SELECT MAX(id_nvl) as prev_id FROM niveis WHERE id_nvl < ?");
                $stmt_prev->bind_param("i", $id_para_deletar);
                $stmt_prev->execute();
                $result_prev = $stmt_prev->get_result()->fetch_assoc();
                $stmt_prev->close();
                if ($result_prev && $result_prev['prev_id'] !== null) {
                    $fallback_level_id = $result_prev['prev_id'];
                }
            }

            if ($fallback_level_id) {
                $stmt_update_users = $conn->prepare("UPDATE usuarios SET id_nvl = ? WHERE id_nvl = ?");
                $stmt_update_users->bind_param("ii", $fallback_level_id, $id_para_deletar);
                $stmt_update_users->execute();
                $stmt_update_users->close();
            }

            $stmt_delete_level = $conn->prepare("DELETE FROM niveis WHERE id_nvl = ?");
            $stmt_delete_level->bind_param("i", $id_para_deletar);
            $stmt_delete_level->execute();
            $stmt_delete_level->close();

            $conn->commit();
            $feedback_message = "<p class='success-message'>Nível de acesso deletado! Usuários associados foram movidos.</p>";

            $log_details = "Nível '{$level_name_for_log}' (ID: #{$id_para_deletar}) foi deletado.";
            if ($fallback_level_id) {
                $log_details .= " Usuários movidos para o nível ID #{$fallback_level_id}.";
            }
            log_activity($conn, 'Nível Excluído', $admin_user_name, $log_details);
        } catch (mysqli_sql_exception $exception) {
            $conn->rollback();
            $feedback_message = "<p class='error-message'>Erro ao deletar o nível de acesso.</p>";
        }
    }
}

// 3. AÇÃO PARA ENTRAR EM MODO DE EDIÇÃO (via GET)
if (isset($_GET['edit_id'])) {
    $id_nvl = intval($_GET['edit_id']);
    $edit_mode = true;

    // Busca detalhes do nível
    $stmt = $conn->prepare("SELECT * FROM niveis WHERE id_nvl = ?");
    $stmt->bind_param("i", $id_nvl);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $nivel_para_edicao = $result->fetch_assoc();
    }
    $stmt->close();

    // Busca usuários que pertencem a este nível
    $stmt_users = $conn->prepare("SELECT id_usu, nome_usu, email_usu, imgperfil_usu FROM usuarios WHERE id_nvl = ? ORDER BY nome_usu ASC");
    $stmt_users->bind_param("i", $id_nvl);
    $stmt_users->execute();
    $result_users = $stmt_users->get_result();
    while ($row = $result_users->fetch_assoc()) {
        $users_with_level[] = $row;
    }
    $stmt_users->close();
}


// --- CONSULTA PARA EXIBIR A TABELA DE NÍVEIS ---
$sql = "SELECT n.*, (SELECT COUNT(*) FROM usuarios u WHERE u.id_nvl = n.id_nvl) as total_usuarios 
        FROM niveis n 
        ORDER BY n.id_nvl ASC";
$resultado = $conn->query($sql);
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
                <?php if ($resultado && $resultado->num_rows > 0): ?>
                    <?php while ($nivel = $resultado->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $nivel['id_nvl']; ?></td>
                            <td><?php echo htmlspecialchars($nivel['nome_nvl']); ?></td>
                            <td><?php echo htmlspecialchars($nivel['descricao_nvl'] ?? 'N/A'); ?></td>
                            <td><span class="badge"><?php echo $nivel['total_usuarios']; ?></span></td>
                            <td class="actions">
                                <?php $is_self_level = ($nivel['id_nvl'] == $_SESSION['id_nvl']); ?>

                                <button
                                    class="btn btn-icon btn-edit"
                                    title="Editar Nível"
                                    onclick="window.location.href='admin_niveis.php?edit_id=<?php echo $nivel['id_nvl']; ?>#form-nivel';">
                                    <i class="ri-pencil-line"></i>
                                </button>

                                <button
                                    class="btn btn-icon <?php echo $is_self_level ? '' : 'btn-delete'; ?>"
                                    title="<?php echo $is_self_level ? 'Você não pode deletar seu próprio nível' : 'Deletar Nível'; ?>"
                                    onclick="<?php echo $is_self_level ? '' : "if(confirm('Tem certeza? Usuários associados serão movidos.')) { window.location.href='admin_niveis.php?delete_id=" . $nivel['id_nvl'] . "'; }"; ?>"
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

    <div class="form-container" id="form-nivel">
        <h2><?php echo $edit_mode ? 'Editar Nível de Acesso' : 'Adicionar Novo Nível'; ?></h2>
        <form action="admin_niveis.php" method="POST">
            <?php if ($edit_mode): ?>
                <input type="hidden" name="id_nvl" value="<?php echo $nivel_para_edicao['id_nvl']; ?>">
            <?php endif; ?>

            <div class="form-group">
                <label for="nome_nvl">Nome do Nível:</label>
                <input type="text" id="nome_nvl" name="nome_nvl" value="<?php echo htmlspecialchars($nivel_para_edicao['nome_nvl']); ?>" required>
            </div>
            <div class="form-group">
                <label for="descricao_nvl">Descrição:</label>
                <textarea id="descricao_nvl" name="descricao_nvl" rows="3"><?php echo htmlspecialchars($nivel_para_edicao['descricao_nvl']); ?></textarea>
            </div>
            <!-- Seção de Usuários com este Nível (só aparece no modo de edição) -->
            <?php if ($edit_mode): ?>
                <div class="form-group">
                    <h2>Usuários com o nível "<?php echo htmlspecialchars($nivel_para_edicao['nome_nvl']); ?>"</h2>
                    <table>
                        <thead>
                            <tr>
                                <th>Usuário</th>
                                <th>Email</th>
                                <th>Ação</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($users_with_level)): ?>
                                <?php foreach ($users_with_level as $user): ?>
                                    <tr>
                                        <td>
                                            <div class="user-info">
                                                <?php
                                                $db_path = $user['imgperfil_usu'];
                                                $image_path = (substr($db_path, 0, 3) === '../') ? '../../' . substr($db_path, 3) : $db_path;
                                                ?>
                                                <img src="<?php echo htmlspecialchars($image_path); ?>" alt="Foto de Perfil">
                                                <span><?php echo htmlspecialchars($user['nome_usu']); ?></span>
                                            </div>
                                        </td>
                                        <td><?php echo htmlspecialchars($user['email_usu']); ?></td>
                                        <td class="actions">
                                            <a href="admin_users.php?search=<?php echo urlencode($user['email_usu']); ?>" class="btn btn-primary" target="_blank">
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
                    <a href="admin_niveis.php" class="btn">Cancelar Edição</a>
                <?php endif; ?>
            </div>
        </form>
    </div>
</main>

<?php
// Inclui o rodapé do painel de administração e fecha a conexão.
include('admin_footer.php');
$conn->close();
?>