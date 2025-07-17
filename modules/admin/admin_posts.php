<?php
include 'admin_header.php';
include '../../config/conn.php';

// Lógica para deletar post
if (isset($_GET['delete_id'])) {
    $id_para_deletar = intval($_GET['delete_id']);
    $delete_sql = "DELETE FROM posts WHERE id_post = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param("i", $id_para_deletar);
    if ($stmt->execute()) {
        echo "<p class='success-message'>Post deletado com sucesso!</p>";
    } else {
        echo "<p class='error-message'>Erro ao deletar post: " . $conn->error . "</p>";
    }
    $stmt->close();
}

// Busca todos os posts com o nome do autor
$sql = "SELECT p.id_post, p.titulo_post, p.data_post, p.stats_post, u.nome_usu FROM posts p JOIN usuarios u ON p.id_usu = u.id_usu ORDER BY p.id_post DESC";
$resultado = $conn->query($sql);
?>

<main class="container">
    <h1>Gerenciar Posts</h1>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Título</th>
                <th>Autor</th>
                <th>Data</th>
                <th>Status</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($resultado->num_rows > 0): ?>
                <?php while ($post = $resultado->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($post['id_post']); ?></td>
                        <td><?php echo htmlspecialchars($post['titulo_post']); ?></td>
                        <td><?php echo htmlspecialchars($post['nome_usu']); ?></td>
                        <td><?php echo date("d/m/Y H:i", strtotime($post['data_post'])); ?></td>
                        <td><?php echo htmlspecialchars($post['stats_post']); ?></td>
                        <td>
                            <a href="gerenciar_posts.php?delete_id=<?php echo $post['id_post']; ?>" onclick="return confirm('Tem certeza que deseja deletar este post?');" class="btn-delete">Deletar</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6">Nenhum post encontrado.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</main>

<?php
include 'admin_footer.php';
$conn->close();
?>