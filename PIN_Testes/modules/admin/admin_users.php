<?php
require_once('../../config/conn.php');
include('admin_header.php');

$feedback_message = '';

// Lógica para deletar usuário
if (isset($_GET['delete_id'])) {
    $id_para_deletar = intval($_GET['delete_id']);
    // Não permitir que o admin se auto-delete
    if ($id_para_deletar == $_SESSION['id_usu']) {
        $feedback_message = "<p class='error-message'>Você não pode deletar sua própria conta.</p>";
    } else {
        $stmt = $conn->prepare("DELETE FROM usuarios WHERE id_usu = ?");
        $stmt->bind_param("i", $id_para_deletar);
        if ($stmt->execute()) {
            $feedback_message = "<p class='success-message'>Usuário deletado com sucesso!</p>";
        } else {
            $feedback_message = "<p class='error-message'>Erro ao deletar usuário.</p>";
        }
        $stmt->close();
    }
}

// Lógica para atualizar o nível do usuário
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_level'])) {
    $id_para_atualizar = intval($_POST['id_usu']);
    $novo_nivel = intval($_POST['id_nvl']);

    // Não permitir que o admin altere o próprio nível
    if ($id_para_atualizar == $_SESSION['id_usu']) {
        $feedback_message = "<p class='error-message'>Você não pode alterar seu próprio nível de acesso.</p>";
    } else {
        $stmt = $conn->prepare("UPDATE usuarios SET id_nvl = ? WHERE id_usu = ?");
        $stmt->bind_param("ii", $novo_nivel, $id_para_atualizar);
        if ($stmt->execute()) {
            $feedback_message = "<p class='success-message'>Nível do usuário atualizado com sucesso!</p>";
        } else {
            $feedback_message = "<p class='error-message'>Erro ao atualizar o nível.</p>";
        }
        $stmt->close();
    }
}

// Busca todos os níveis para o dropdown de edição
$niveis = $conn->query("SELECT * FROM niveis");

// Lógica de busca
$search_query = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
$sql = "SELECT u.id_usu, u.nome_usu, u.email_usu, u.imgperfil_usu, n.nome_nvl, u.id_nvl, u.datacriacao_usu 
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
        <h1>Gerenciar Usuários</h1>
        <form action="admin_users.php" method="GET" class="search-form">
            <input type="search" name="search" placeholder="Nome ou email..." value="<?php echo htmlspecialchars($search_query); ?>">
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
                    <th>Data de Criação</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($resultado && $resultado->num_rows > 0): ?>
                    <?php while ($usuario = $resultado->fetch_assoc()): ?>
                        <tr>
                            <td>
                                <div class="user-info">
                                    <img src="<?php echo htmlspecialchars($usuario['imgperfil_usu']); ?>" alt="Foto de Perfil">
                                    <span><?php echo htmlspecialchars($usuario['nome_usu']); ?></span>
                                </div>
                            </td>
                            <td><?php echo htmlspecialchars($usuario['email_usu']); ?></td>
                            <td>
                                <form action="admin_users.php" method="POST" style="display: flex; gap: 0.5rem;">
                                    <input type="hidden" name="id_usu" value="<?php echo $usuario['id_usu']; ?>">
                                    <select name="id_nvl" <?php if ($usuario['id_usu'] == $_SESSION['id_usu']) echo 'disabled'; ?>>
                                        <?php
                                        mysqli_data_seek($niveis, 0); // Reseta o ponteiro do resultado
                                        while ($nivel = $niveis->fetch_assoc()) {
                                            $selected = ($nivel['id_nvl'] == $usuario['id_nvl']) ? 'selected' : '';
                                            echo "<option value='{$nivel['id_nvl']}' {$selected}>{$nivel['nome_nvl']}</option>";
                                        }
                                        ?>
                                    </select>
                                    <button type="submit" name="update_level" class="btn btn-primary" <?php if ($usuario['id_usu'] == $_SESSION['id_usu']) echo 'disabled'; ?>>Salvar</button>
                                </form>
                            </td>
                            <td><?php echo date("d/m/Y H:i", strtotime($usuario['datacriacao_usu'])); ?></td>
                            <td>
                                <div class="actions">
                                    <?php
                                    // Verifica se o utilizador na linha é o administrador logado
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
                        <td colspan="5">Nenhum usuário encontrado.</td>
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