<?php
require_once('../../config/conn.php');
require_once('../../config/log_helper.php'); // Adicionado para usar a função de log
include('admin_header.php');

$feedback_message = '';
$admin_user_name = $_SESSION['nome_usu'] ?? 'Administrador';

// Lógica para apagar usuario
if (isset($_GET['delete_id'])) {
    $id_para_deletar = intval($_GET['delete_id']);
    if ($id_para_deletar == $_SESSION['id_usu']) {
        $feedback_message = "<p class='error-message'>Você não pode apagar a sua própria conta.</p>";
    } else {
        // Buscar nome do usuário antes de deletar para o log
        $stmt_get_name = $conn->prepare("SELECT nome_usu FROM usuarios WHERE id_usu = ?");
        $stmt_get_name->bind_param("i", $id_para_deletar);
        $stmt_get_name->execute();
        $user_data = $stmt_get_name->get_result()->fetch_assoc();
        $user_name_for_log = $user_data ? $user_data['nome_usu'] : 'Desconhecido';
        $stmt_get_name->close();

        $stmt = $conn->prepare("DELETE FROM usuarios WHERE id_usu = ?");
        $stmt->bind_param("i", $id_para_deletar);
        if ($stmt->execute()) {
            $feedback_message = "<p class='success-message'>Utilizador apagado com sucesso!</p>";
            log_activity($conn, 'Usuário Deletado', $admin_user_name, "Usuário '{$user_name_for_log}' (ID: #{$id_para_deletar}) foi excluído.");
        } else {
            $feedback_message = "<p class='error-message'>Erro ao apagar utilizador.</p>";
        }
        $stmt->close();
    }
}

// Lógica para atualizar o nível do usuario
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_nvl'])) {
    $id_para_atualizar = intval($_POST['id_usu']);
    $novo_nivel_id = intval($_POST['id_nvl']);

    if ($id_para_atualizar == $_SESSION['id_usu']) {
        $feedback_message = "<p class='error-message'>Você não pode alterar o seu próprio nível de acesso.</p>";
    } else {
        $stmt = $conn->prepare("UPDATE usuarios SET id_nvl = ? WHERE id_usu = ?");
        $stmt->bind_param("ii", $novo_nivel_id, $id_para_atualizar);
        if ($stmt->execute()) {
            $feedback_message = "<p class='success-message'>Nível do utilizador atualizado com sucesso!</p>";

            // Buscar nomes para o log
            $stmt_info = $conn->prepare("SELECT u.nome_usu, n.nome_nvl FROM usuarios u, niveis n WHERE u.id_usu = ? AND n.id_nvl = ?");
            $stmt_info->bind_param("ii", $id_para_atualizar, $novo_nivel_id);
            $stmt_info->execute();
            $info_data = $stmt_info->get_result()->fetch_assoc();
            $user_name_for_log = $info_data['nome_usu'] ?? 'ID ' . $id_para_atualizar;
            $level_name_for_log = $info_data['nome_nvl'] ?? 'ID ' . $novo_nivel_id;
            $stmt_info->close();

            log_activity($conn, 'Usuário Modificado', $admin_user_name, "Nível do usuário '{$user_name_for_log}' (ID: #{$id_para_atualizar}) foi alterado para '{$level_name_for_log}'.");
        } else {
            $feedback_message = "<p class='error-message'>Erro ao atualizar o nível.</p>";
        }
        $stmt->close();
    }
}

$niveis = $conn->query("SELECT * FROM niveis");

$search_query = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
$sql = "SELECT u.id_usu, u.nome_usu, u.email_usu, u.imgperfil_usu, n.nome_nvl, u.id_nvl, u.datacriacao_usu, u.dataatualizacao_usu 
        FROM usuarios u 
        LEFT JOIN niveis n ON u.id_nvl = n.id_nvl";
if (!empty($search_query)) {
    $sql .= " WHERE u.nome_usu LIKE '%$search_query%' OR u.email_usu LIKE '%$search_query%'";
}
$sql .= " ORDER BY u.id_usu DESC";
$resultado = $conn->query($sql);
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
                <?php if ($resultado && $resultado->num_rows > 0): ?>
                    <?php while ($usuario = $resultado->fetch_assoc()): ?>
                        <tr>
                            <td>
                                <div class="user-info">
                                    <?php
                                    $db_path = $usuario['imgperfil_usu'];
                                    $image_path = $db_path;
                                    // Verifica se o caminho começa com '../' e o ajusta
                                    if (substr($db_path, 0, 3) === '../') {
                                        $image_path = '../../' . substr($db_path, 3);
                                    }
                                    ?>
                                    <img src="<?php echo htmlspecialchars($image_path); ?>" alt="Foto de Perfil">
                                    <span><?php echo htmlspecialchars($usuario['nome_usu']); ?></span>
                                </div>
                            </td>
                            <td><?php echo htmlspecialchars($usuario['email_usu']); ?></td>
                            <td>
                                <form action="admin_users.php" method="POST" style="display: flex; gap: 0.5rem;">
                                    <input type="hidden" name="id_usu" value="<?php echo $usuario['id_usu']; ?>">
                                    <select name="id_nvl" <?php if ($usuario['id_usu'] == $_SESSION['id_usu']) echo 'disabled'; ?>>
                                        <?php
                                        mysqli_data_seek($niveis, 0);
                                        while ($nivel = $niveis->fetch_assoc()) {
                                            $selected = ($nivel['id_nvl'] == $usuario['id_nvl']) ? 'selected' : '';
                                            echo "<option value='{$nivel['id_nvl']}' {$selected}>{$nivel['nome_nvl']}</option>";
                                        }
                                        ?>
                                    </select>
                                    <button type="submit" name="update_nvl" class="btn btn-primary" <?php if ($usuario['id_usu'] == $_SESSION['id_usu']) echo 'disabled'; ?>>Salvar</button>
                                </form>
                            </td>
                            <td><?php echo date("d/m/Y H:i", strtotime($usuario['datacriacao_usu'])); ?></td>
                            <td><?php echo date("d/m/Y H:i", strtotime($usuario['dataatualizacao_usu'])); ?></td>
                            <td>
                                <div class="actions">
                                    <?php
                                    $is_self = ($usuario['id_usu'] == $_SESSION['id_usu']);
                                    ?>
                                    <button
                                        class="btn btn-icon <?php echo $is_self ? '' : 'btn-delete'; ?>"
                                        title="<?php echo $is_self ? 'Você não pode apagar a sua própria conta' : 'Apagar Utilizador'; ?>"
                                        onclick="<?php echo $is_self ? '' : "if(confirm('Tem a certeza que deseja apagar este utilizador? Esta ação é irreversível.')) { window.location.href='admin_users.php?delete_id=" . $usuario['id_usu'] . "'; }"; ?>"
                                        <?php if ($is_self) echo 'disabled'; ?>>
                                        <i class="ri-delete-bin-7-line"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6">Nenhum utilizador encontrado.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>

<?php
include('admin_footer.php');
$conn->close();
?>