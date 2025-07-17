<?php
include 'admin_header.php';
include '../../config/conn.php';

// Lógica para deletar usuário
if (isset($_GET['delete_id'])) {
    $id_para_deletar = intval($_GET['delete_id']);
    $delete_sql = "DELETE FROM usuarios WHERE id_usu = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param("i", $id_para_deletar);
    if ($stmt->execute()) {
        echo "<p class='success-message'>Usuário deletado com sucesso!</p>";
    } else {
        echo "<p class='error-message'>Erro ao deletar usuário: " . $conn->error . "</p>";
    }
    $stmt->close();
}


// Busca todos os usuários
$sql = "SELECT u.id_usu, u.nome_usu, u.email_usu, n.nome_nvl, u.datacriacao_usu FROM usuarios u LEFT JOIN niveis n ON u.id_nvl = n.id_nvl ORDER BY u.id_usu DESC";
$resultado = $conn->query($sql);
?>

<main class="container">
    <h1>Gerenciar Usuários</h1>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Email</th>
                <th>Nível</th>
                <th>Data de Criação</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($resultado->num_rows > 0): ?>
                <?php while ($usuario = $resultado->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($usuario['id_usu']); ?></td>
                        <td><?php echo htmlspecialchars($usuario['nome_usu']); ?></td>
                        <td><?php echo htmlspecialchars($usuario['email_usu']); ?></td>
                        <td><?php echo htmlspecialchars($usuario['nome_nvl'] ?? 'N/A'); ?></td>
                        <td><?php echo date("d/m/Y H:i", strtotime($usuario['datacriacao_usu'])); ?></td>
                        <td>
                            <!-- Futuramente, links para editar -->
                            <a href="admin_users.php?delete_id=<?php echo $usuario['id_usu']; ?>" onclick="return confirm('Tem certeza que deseja deletar este usuário? Esta ação é irreversível.');" class="btn-delete">Deletar</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6">Nenhum usuário encontrado.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</main>

<?php
include 'admin_footer.php';
$conn->close();
?>