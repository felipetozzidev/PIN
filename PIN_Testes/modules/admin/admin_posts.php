<?php
require_once('../../config/conn.php');
include('admin_header.php');

$feedback_message = '';

// Lógica para deletar post
if (isset($_GET['delete_id'])) {
    $id_para_deletar = intval($_GET['delete_id']);
    $stmt = $conn->prepare("DELETE FROM posts WHERE id_post = ?");
    $stmt->bind_param("i", $id_para_deletar);
    if ($stmt->execute()) {
        $feedback_message = "<p class='success-message'>Post deletado com sucesso!</p>";
    } else {
        $feedback_message = "<p class='error-message'>Erro ao deletar post.</p>";
    }
    $stmt->close();
}

// Lógica de busca
$search_query = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
$sql = "SELECT p.id_post, p.titulo_post, p.conteudo_post, p.data_post, p.stats_post, u.nome_usu 
        FROM posts p 
        JOIN usuarios u ON p.id_usu = u.id_usu";
if (!empty($search_query)) {
    $sql .= " WHERE p.titulo_post LIKE '%$search_query%' OR u.nome_usu LIKE '%$search_query%'";
}
$sql .= " ORDER BY p.id_post DESC";
$resultado = $conn->query($sql);
?>

<main class="container">
    <div class="page-header">
        <h1>Gerenciar Posts</h1>
        <form action="admin_posts.php" method="GET" class="search-form">
            <input type="search" name="search" placeholder="Buscar por título ou autor..." value="<?php echo htmlspecialchars($search_query); ?>">
            <button type="submit" class="btn btn-primary"><i class="ri-search-line"></i></button>
        </form>
    </div>

    <?php echo $feedback_message; ?>

    <div class="table-container">
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
                <?php if ($resultado && $resultado->num_rows > 0): ?>
                    <?php while ($post = $resultado->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $post['id_post']; ?></td>
                            <td><?php echo htmlspecialchars($post['titulo_post']); ?></td>
                            <td><?php echo htmlspecialchars($post['nome_usu']); ?></td>
                            <td><?php echo date("d/m/Y H:i", strtotime($post['data_post'])); ?></td>
                            <td>
                                <span class="status-badge status-<?php echo htmlspecialchars($post['stats_post']); ?>">
                                    <?php echo htmlspecialchars($post['stats_post']); ?>
                                </span>
                            </td>
                            <td class="actions">
                                <a href="#" class="btn btn-icon btn-view" title="Ver Conteúdo" onclick="alert('<?php echo htmlspecialchars(addslashes($post['conteudo_post'])); ?>')">
                                    <i class="ri-eye-line"></i>
                                </a>
                                <a href="admin_posts.php?delete_id=<?php echo $post['id_post']; ?>" onclick="return confirm('Tem certeza que deseja deletar este post?');" class="btn btn-icon btn-delete" title="Deletar Post">
                                    <i class="ri-delete-bin-line"></i>
                                </a>
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
    </div>
</main>

<?php
include('admin_footer.php');
$conn->close();
?>