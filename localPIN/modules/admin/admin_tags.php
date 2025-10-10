<?php
require_once('../../config/conn.php');
require_once('../../config/log_helper.php'); // Adicionado para usar a função de log
include('admin_header.php');

$feedback_message = '';
$edit_mode = false;
$tag_para_editar = ['id_tag' => '', 'nome_tag' => ''];
$posts_with_tag = [];

// --- LÓGICA PARA PROCESSAR AÇÕES (POST e GET) ---
$admin_user_name = $_SESSION['nome_usu'] ?? 'Administrador';

// 1. AÇÃO DE ADICIONAR OU ATUALIZAR (via POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome_tag = trim($_POST['nome_tag']);

    if (!empty($nome_tag)) {
        if (isset($_POST['id_tag']) && !empty($_POST['id_tag'])) {
            $id_tag = intval($_POST['id_tag']);
            $stmt = $conn->prepare("UPDATE tags SET nome_tag = ? WHERE id_tag = ?");
            $stmt->bind_param("si", $nome_tag, $id_tag);
            if ($stmt->execute()) {
                $feedback_message = "<p class='success-message'>Tag atualizada com sucesso!</p>";
                log_activity($conn, 'Tag Editada', $admin_user_name, "Tag (ID: #{$id_tag}) foi atualizada para '{$nome_tag}'.");
            } else {
                $feedback_message = "<p class='error-message'>Erro ao atualizar a tag. Ela já pode existir.</p>";
            }
            $stmt->close();
        } else {
            $stmt = $conn->prepare("INSERT INTO tags (nome_tag) VALUES (?)");
            $stmt->bind_param("s", $nome_tag);
            if ($stmt->execute()) {
                $new_id = $conn->insert_id;
                $feedback_message = "<p class='success-message'>Tag adicionada com sucesso!</p>";
                log_activity($conn, 'Nova Tag', $admin_user_name, "Tag '{$nome_tag}' (ID: #{$new_id}) foi criada.");
            } else {
                $feedback_message = "<p class='error-message'>Erro ao adicionar a tag. Ela já pode existir.</p>";
            }
            $stmt->close();
        }
    } else {
        $feedback_message = "<p class='error-message'>O nome da tag não pode estar vazio.</p>";
    }
}

// 2. AÇÃO DE DELETAR OU ENTRAR EM MODO DE EDIÇÃO (via GET)
if (isset($_GET['action'])) {
    $id_tag = intval($_GET['id']);

    if ($_GET['action'] == 'delete') {
        // Buscar nome da tag antes de deletar para o log
        $stmt_get_name = $conn->prepare("SELECT nome_tag FROM tags WHERE id_tag = ?");
        $stmt_get_name->bind_param("i", $id_tag);
        $stmt_get_name->execute();
        $result_get_name = $stmt_get_name->get_result();
        $tag_data = $result_get_name->fetch_assoc();
        $tag_name_for_log = $tag_data ? $tag_data['nome_tag'] : 'Desconhecida';
        $stmt_get_name->close();

        $stmt_delete = $conn->prepare("DELETE FROM tags WHERE id_tag = ?");
        $stmt_delete->bind_param("i", $id_tag);
        if ($stmt_delete->execute()) {
            $feedback_message = "<p class='success-message'>Tag deletada com sucesso!</p>";
            log_activity($conn, 'Tag Excluída', $admin_user_name, "Tag '{$tag_name_for_log}' (ID: #{$id_tag}) foi excluída.");
        } else {
            $feedback_message = "<p class='error-message'>Erro ao deletar a tag.</p>";
        }
        $stmt_delete->close();
    }

    if ($_GET['action'] == 'edit') {
        $edit_mode = true;
        // Busca detalhes da tag
        $stmt = $conn->prepare("SELECT * FROM tags WHERE id_tag = ?");
        $stmt->bind_param("i", $id_tag);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $tag_para_editar = $result->fetch_assoc();
        }
        $stmt->close();

        // Busca posts que usam esta tag
        $stmt_posts = $conn->prepare("
            SELECT p.id_post, p.conteudo_post, u.nome_usu, u.imgperfil_usu
            FROM posts_tags pt
            JOIN posts p ON pt.id_post = p.id_post
            LEFT JOIN usuarios u ON p.id_usu = u.id_usu
            WHERE pt.id_tag = ?
            ORDER BY p.data_post DESC
        ");
        $stmt_posts->bind_param("i", $id_tag);
        $stmt_posts->execute();
        $result_posts = $stmt_posts->get_result();
        while ($row = $result_posts->fetch_assoc()) {
            $posts_with_tag[] = $row;
        }
        $stmt_posts->close();
    }
}

// --- CONSULTA PARA EXIBIR A TABELA DE TAGS ---
$search_query = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
$sql = "SELECT t.id_tag, t.nome_tag, COUNT(pt.id_post) as total_posts 
        FROM tags t 
        LEFT JOIN posts_tags pt ON t.id_tag = pt.id_tag";
if (!empty($search_query)) {
    $sql .= " WHERE t.nome_tag LIKE '%$search_query%'";
}
$sql .= " GROUP BY t.id_tag ORDER BY total_posts DESC, t.nome_tag ASC";
$resultado = $conn->query($sql);
?>

<main class="container">
    <div class="page-header">
        <h1>Tags</h1>
        <form action="admin_tags.php" method="GET" class="search-form">
            <input type="search" name="search" placeholder="Buscar por nome da tag..." value="<?php echo htmlspecialchars($search_query); ?>">
            <button type="submit" class="btn btn-primary"><i class="ri-search-line"></i></button>
        </form>
    </div>

    <?php echo $feedback_message; ?>

    <div class="table-container">
        <h2>Lista de Tags</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome da Tag</th>
                    <th>Nº de Posts</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($resultado && $resultado->num_rows > 0): ?>
                    <?php while ($tag = $resultado->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $tag['id_tag']; ?></td>
                            <td><span class="status-badge tag"><?php echo htmlspecialchars($tag['nome_tag']); ?></span></td>
                            <td><?php echo $tag['total_posts']; ?></td>
                            <td class="actions">
                                <a href="admin_tags.php?action=edit&id=<?php echo $tag['id_tag']; ?>#form-tag" class="btn btn-icon btn-edit" title="Editar Tag">
                                    <i class="ri-pencil-line"></i>
                                </a>
                                <a href="admin_tags.php?action=delete&id=<?php echo $tag['id_tag']; ?>" onclick="return confirm('Tem certeza que deseja deletar esta tag? Todas as suas associações com posts serão removidas.');" class="btn btn-icon btn-delete" title="Deletar Tag">
                                    <i class="ri-delete-bin-line"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4">Nenhuma tag encontrada.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="form-container" id="form-tag">
        <h2><?php echo $edit_mode ? 'Editar Tag' : 'Adicionar Nova Tag'; ?></h2>
        <form action="admin_tags.php" method="POST">
            <?php if ($edit_mode): ?>
                <input type="hidden" name="id_tag" value="<?php echo $tag_para_editar['id_tag']; ?>">
            <?php endif; ?>

            <div class="form-group">
                <label for="nome_tag">Nome da Tag:</label>
                <input type="text" id="nome_tag" name="nome_tag" value="<?php echo htmlspecialchars($tag_para_editar['nome_tag']); ?>" required>
            </div>

            <!-- Seção de Posts com a Tag (só aparece no modo de edição) -->
            <?php if ($edit_mode): ?>
                <div class="form-group">
                    <h2>Posts com a tag "<?php echo htmlspecialchars($tag_para_editar['nome_tag']); ?>"</h2>
                    <table>
                        <thead>
                            <tr>
                                <th>Autor</th>
                                <th>Conteúdo (início)</th>
                                <th>Ação</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($posts_with_tag)): ?>
                                <?php foreach ($posts_with_tag as $post): ?>
                                    <tr>
                                        <td>
                                            <div class="user-info">
                                                <?php
                                                $db_path = $post['imgperfil_usu'];
                                                $image_path = (substr($db_path, 0, 3) === '../') ? '../../' . substr($db_path, 3) : $db_path;
                                                ?>
                                                <img src="<?php echo htmlspecialchars($image_path); ?>" alt="Foto de Perfil">
                                                <span><?php echo htmlspecialchars($post['nome_usu'] ?? 'Apagado'); ?></span>
                                            </div>
                                        </td>
                                        <td><?php echo htmlspecialchars(substr($post['conteudo_post'], 0, 70)) . '...'; ?></td>
                                        <td class="actions">
                                            <a href="admin_posts.php?view_post_id=<?php echo $post['id_post']; ?>" class="btn btn-primary" target="_blank">
                                                <i class="ri-eye-line"></i> Ver Post
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="3">Nenhum post encontrado com esta tag.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
            <button type="submit" class="btn btn-primary"><?php echo $edit_mode ? 'Salvar Alterações' : 'Adicionar Tag'; ?></button>
            <?php if ($edit_mode): ?>
                <a href="admin_tags.php" class="btn">Cancelar Edição</a>
            <?php endif; ?>
        </form>
    </div>
</main>

<?php
include('admin_footer.php');
$conn->close();
?>