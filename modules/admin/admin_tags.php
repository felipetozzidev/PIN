<?php
// O cabeçalho já inicia a sessão e faz a conexão via PDO
include('admin_header.php');
require_once('../../config/log_helper.php');

// Inicializa variáveis
$feedback_message = '';
$edit_mode = false;
$tag_to_edit = ['tag_id' => '', 'name' => ''];
$posts_with_tag = [];
$admin_user_id = $_SESSION['user_id'];
$admin_user_name = $_SESSION['full_name'] ?? 'Administrador';

// --- LÓGICA PARA PROCESSAR AÇÕES (POST e GET) ---

// 1. AÇÃO DE ADICIONAR OU ATUALIZAR (via POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $tag_id = isset($_POST['tag_id']) ? intval($_POST['tag_id']) : 0;

    if (!empty($name)) {
        if ($tag_id > 0) { // Atualizar
            $stmt = $pdo->prepare("UPDATE tags SET name = ? WHERE tag_id = ?");
            if ($stmt->execute([$name, $tag_id])) {
                $feedback_message = "<p class='success-message'>Tag atualizada com sucesso!</p>";
                logAction($pdo, 'Tag Editada', $admin_user_name, "Tag (ID: #{$tag_id}) foi atualizada para '{$name}'.", $admin_user_id);
            } else {
                $feedback_message = "<p class='error-message'>Erro ao atualizar a tag. Ela já pode existir.</p>";
            }
        } else { // Adicionar
            $stmt = $pdo->prepare("INSERT INTO tags (name) VALUES (?)");
            if ($stmt->execute([$name])) {
                $new_id = $pdo->lastInsertId();
                $feedback_message = "<p class='success-message'>Tag adicionada com sucesso!</p>";
                logAction($pdo, 'Nova Tag', $admin_user_name, "Tag '{$name}' (ID: #{$new_id}) foi criada.", $admin_user_id);
            } else {
                $feedback_message = "<p class='error-message'>Erro ao adicionar a tag. Ela já pode existir.</p>";
            }
        }
    } else {
        $feedback_message = "<p class='error-message'>O nome da tag não pode estar vazio.</p>";
    }
}

// 2. AÇÃO DE DELETAR OU ENTRAR EM MODO DE EDIÇÃO (via GET)
if (isset($_GET['action']) && isset($_GET['id'])) {
    $tag_id = intval($_GET['id']);

    if ($_GET['action'] == 'delete') {
        $stmt_get_name = $pdo->prepare("SELECT name FROM tags WHERE tag_id = ?");
        $stmt_get_name->execute([$tag_id]);
        $tag_data = $stmt_get_name->fetch();
        $tag_name_for_log = $tag_data ? $tag_data['name'] : 'Desconhecida';

        $stmt_delete = $pdo->prepare("DELETE FROM tags WHERE tag_id = ?");
        if ($stmt_delete->execute([$tag_id])) {
            $feedback_message = "<p class='success-message'>Tag deletada com sucesso!</p>";
            logAction($pdo, 'Tag Excluída', $admin_user_name, "Tag '{$tag_name_for_log}' (ID: #{$tag_id}) foi excluída.", $admin_user_id);
        } else {
            $feedback_message = "<p class='error-message'>Erro ao deletar a tag.</p>";
        }
    }

    if ($_GET['action'] == 'edit') {
        $edit_mode = true;
        $stmt = $pdo->prepare("SELECT * FROM tags WHERE tag_id = ?");
        $stmt->execute([$tag_id]);
        $tag_to_edit = $stmt->fetch(PDO::FETCH_ASSOC);

        $stmt_posts = $pdo->prepare("
            SELECT p.post_id, p.content, u.full_name, u.profile_image_url
            FROM post_tags pt
            JOIN posts p ON pt.post_id = p.post_id
            LEFT JOIN users u ON p.user_id = u.user_id
            WHERE pt.tag_id = ?
            ORDER BY p.created_at DESC
        ");
        $stmt_posts->execute([$tag_id]);
        $posts_with_tag = $stmt_posts->fetchAll(PDO::FETCH_ASSOC);
    }
}

// --- CONSULTA PARA EXIBIR A TABELA DE TAGS ---
$search_query = isset($_GET['search']) ? trim($_GET['search']) : '';
$sql = "SELECT t.tag_id, t.name, COUNT(pt.post_id) as total_posts 
        FROM tags t 
        LEFT JOIN post_tags pt ON t.tag_id = pt.tag_id";
$params = [];
if (!empty($search_query)) {
    $sql .= " WHERE t.name LIKE ?";
    $params[] = "%$search_query%";
}
$sql .= " GROUP BY t.tag_id ORDER BY total_posts DESC, t.name ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$tags_result = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
                <?php if ($tags_result && count($tags_result) > 0): ?>
                    <?php foreach ($tags_result as $tag): ?>
                        <tr>
                            <td><?php echo $tag['tag_id']; ?></td>
                            <td><span class="status-badge tag"><?php echo htmlspecialchars($tag['name']); ?></span></td>
                            <td><?php echo $tag['total_posts']; ?></td>
                            <td class="actions">
                                <a href="admin_tags.php?action=edit&id=<?php echo $tag['tag_id']; ?>#form-tag" class="btn btn-icon btn-edit" title="Editar Tag">
                                    <i class="ri-pencil-line"></i>
                                </a>
                                <a href="admin_tags.php?action=delete&id=<?php echo $tag['tag_id']; ?>" onclick="return confirm('Tem certeza que deseja deletar esta tag? Todas as suas associações com posts serão removidas.');" class="btn btn-icon btn-delete" title="Deletar Tag">
                                    <i class="ri-delete-bin-line"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
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
                <input type="hidden" name="tag_id" value="<?php echo $tag_to_edit['tag_id']; ?>">
            <?php endif; ?>

            <div class="form-group">
                <label for="name">Nome da Tag:</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($tag_to_edit['name']); ?>" required>
            </div>

            <?php if ($edit_mode && !empty($posts_with_tag)): ?>
                <div class="form-group">
                    <h2>Posts com a tag "<?php echo htmlspecialchars($tag_to_edit['name']); ?>"</h2>
                    <table>
                        <thead>
                            <tr>
                                <th>Autor</th>
                                <th>Conteúdo (início)</th>
                                <th>Ação</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($posts_with_tag as $post): ?>
                                <tr>
                                    <td>
                                        <div class="user-info">
                                            <?php
                                            $db_path = $post['profile_image_url'];
                                            $image_path = (substr($db_path, 0, 3) === '../') ? '../../' . substr($db_path, 3) : $db_path;
                                            ?>
                                            <img src="<?php echo htmlspecialchars($image_path); ?>" alt="Foto de Perfil">
                                            <span><?php echo htmlspecialchars($post['full_name'] ?? 'Apagado'); ?></span>
                                        </div>
                                    </td>
                                    <td><?php echo htmlspecialchars(substr($post['content'], 0, 70)) . '...'; ?></td>
                                    <td class="actions">
                                        <a href="admin_posts.php?view_post_id=<?php echo $post['post_id']; ?>" class="btn btn-primary" target="_blank">
                                            <i class="ri-eye-line"></i> Ver Post
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php elseif ($edit_mode): ?>
                <div class="form-group">
                    <h2>Posts com a tag "<?php echo htmlspecialchars($tag_to_edit['name']); ?>"</h2>
                    <p>Nenhum post encontrado com esta tag.</p>
                </div>
            <?php endif; ?>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary"><?php echo $edit_mode ? 'Salvar Alterações' : 'Adicionar Tag'; ?></button>
                <?php if ($edit_mode): ?>
                    <a href="admin_tags.php" class="btn">Cancelar Edição</a>
                <?php endif; ?>
            </div>
        </form>
    </div>
</main>

<?php
include('admin_footer.php');
?>