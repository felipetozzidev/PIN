<?php
// O header já inicia a sessão, faz a conexão e abre o <body>
require_once(__DIR__ . '/../src/components/modal_postagem.php');
require_once('../src/components/header.php');

// Validação do ID do post a partir do parâmetro 'id' na URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<main class='container'><p class='error-message'>Post não encontrado ou inválido.</p></main>";
    include("../src/components/footer.php");
    exit();
}

$post_id = intval($_GET['id']);
$current_user_id = $_SESSION['user_id'] ?? 0;

// LÓGICA PARA VISUALIZAÇÃO ÚNICA
if (isset($_SESSION['user_id']) && isset($post_id)) {
    $current_user_id = $_SESSION['user_id'];

    // A chave UNIQUE no banco de dados (post_id, user_id) impede inserções duplicadas.
    $sql_view = "INSERT IGNORE INTO post_views (post_id, user_id) VALUES (?, ?)";
    $stmt_view = $pdo->prepare($sql_view);
    $stmt_view->execute([$post_id, $current_user_id]);

    // Se a inserção foi bem-sucedida, atualiza a contagem de visualizações na tabela de posts.
    if ($stmt_view->rowCount() > 0) {
        $sql_update_count = "UPDATE posts SET view_count = view_count + 1 WHERE post_id = ?";
        $stmt_update_count = $pdo->prepare($sql_update_count);
        $stmt_update_count->execute([$post_id]);
    }
}

// --- BUSCA O POST PRINCIPAL ---
$sql_post = "SELECT 
                p.post_id, p.content, p.created_at, p.view_count, p.like_count, p.reply_count,
                u.user_id, u.full_name, u.profile_image_url,
                GROUP_CONCAT(DISTINCT t.name SEPARATOR ', ') as tags,
                GROUP_CONCAT(DISTINCT pm.media_url SEPARATOR ';') as media_urls,
                (SELECT COUNT(*) FROM likes l WHERE l.post_id = p.post_id AND l.user_id = ?) AS user_has_liked,
                (SELECT COUNT(*) FROM reposts r WHERE r.post_id = p.post_id AND r.user_id = ?) AS user_has_reposted,
        (SELECT COUNT(*) FROM bookmarks b WHERE b.post_id = p.post_id AND b.user_id = ?) AS user_has_bookmarked
             FROM posts AS p
             JOIN users AS u ON p.user_id = u.user_id
             LEFT JOIN post_media AS pm ON p.post_id = pm.post_id
             LEFT JOIN post_tags pt ON p.post_id = pt.post_id
             LEFT JOIN tags t ON pt.tag_id = t.tag_id
             WHERE p.post_id = ? AND p.status = 'ativo'
             GROUP BY p.post_id";

$stmt_post = $pdo->prepare($sql_post);
$stmt_post->execute([$current_user_id, $current_user_id, $current_user_id, $post_id]);
$post = $stmt_post->fetch(PDO::FETCH_ASSOC);

// Se o post não for encontrado, exibe uma mensagem de erro.
if (!$post) {
    echo "<main class='container'><p class='error-message'>Post não encontrado ou foi removido.</p></main>";
    include("../src/components/footer.php");
    exit();
}

// --- BUSCA OS COMENTÁRIOS DO POST ---
$sql_comments = "SELECT
                    c.comment_id, c.content, c.created_at, u.user_id, u.full_name, u.profile_image_url
                 FROM comments c
                 JOIN users u ON c.user_id = u.user_id
                 WHERE c.post_id = ?
                 ORDER BY c.created_at ASC";

$stmt_comments = $pdo->prepare($sql_comments);
$stmt_comments->execute([$post_id]);
$comments = $stmt_comments->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Adiciona a folha de estilo específica para esta página -->
<link rel="stylesheet" href="../src/assets/css/post_view.css">

<main class="view-post-container" data-is-logged-in="<?php echo ($current_user_id > 0) ? 'true' : 'false'; ?>">
    <?php if ($post): ?>
        <section class="main_container">
            <div class="main_content">
                <div class="post-card-full">
                    <header class="post-header">
                        <div class="user-info" data-user-id="<?php echo $post['user_id']; ?>">
                            <div class="user-icon">
                                <a href="perfil.php?id=<?php echo $post['user_id']; ?>"><img src="<?php echo htmlspecialchars($post['profile_image_url']); ?>" alt="Foto de Perfil"></a>
                            </div>
                            <div class="user-details">
                                <a href="perfil.php?id=<?php echo $post['user_id']; ?>" class="user-name-link"><span class="user-name"><?php echo htmlspecialchars($post['full_name']); ?></span></a><br>
                                <span class="user-tag">@<?php echo strtolower(explode(' ', $post['full_name'])[0]); ?></span>
                            </div>
                        </div>
                        <div class="post-info">
                            <div class="post-date">
                                <div class="post-hour"><?php echo date("H:i", strtotime($post['created_at'])); ?></div>
                                <div class="post-calendar"><?php echo date("d/m/Y", strtotime($post['created_at'])); ?></div>
                            </div>
                            <div class="post-views"><i class="ri-eye-line"></i><span><?php echo $post['view_count']; ?></span></div>
                        </div>
                    </header>
                    <section class="post-main">
                        <p><?php echo nl2br(htmlspecialchars($post['content'])); ?></p>
                        <?php $media_urls = !empty($post['media_urls']) ? explode(';', $post['media_urls']) : []; ?>
                        <?php if (count($media_urls) > 0): ?>
                            <div class="post-media-grid" data-count="<?php echo count($media_urls); ?>">
                                <?php foreach ($media_urls as $url): ?>
                                    <div class="img_container"><img src="<?php echo htmlspecialchars($url); ?>" alt="Imagem do Post" class="img-fluid"></div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </section>
                    <footer class="post-footer">
                        <?php $user_has_liked_post = $post['user_has_liked'] > 0; ?>
                        <div class="post-stats-left">
                            <button class="post-icon like-btn <?php echo $user_has_liked_post ? 'liked' : ''; ?>" data-post-id="<?php echo $post['post_id']; ?>">
                                <i class="ri-heart-<?php echo $user_has_liked_post ? 'fill' : 'line'; ?>"></i>
                                <span class="post-cont"><?php echo $post['like_count']; ?></span>
                            </button>
                            <div class="post-icon">
                                <i class="ri-chat-3-line"></i>
                                <span class="post-cont"><?php echo $post['reply_count']; ?></span>
                            </div>
                        </div>
                        <div class="post-stats-right">
                            <div class="post-icon">
                                <i class="ri-repeat-line"></i>
                                <span class="post-cont">0</span>
                            </div>
                            <div class="post-icon">
                                <i class="ri-chat-quote-line"></i>
                                <span class="post-cont">0</span>
                            </div>
                        </div>
                    </footer>
                </div>

                <!-- Formulário para Comentar -->
                <?php if ($current_user_id > 0): ?>
                    <div class="reply-form-container">
                        <form id="reply-form" action="api/api_reply_post.php" method="POST">
                            <textarea name="content" id="reply-textarea" placeholder="Responder..." required></textarea>
                            <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
                            <button type="submit" class="btn btn-primary">Publicar resposta</button>
                        </form>
                    </div>
                <?php else: ?>
                    <p class="login-prompt"><a href="login.php">Faça login</a> para responder a esta publicação.</p>
                <?php endif; ?>

                <!-- Seção de Comentários -->
                <div class="comments-section" id="comments-section">
                    <?php if (count($comments) > 0): ?>
                        <?php foreach ($comments as $comment): ?>
                            <div class="comment-card">
                                <header class="post-header">
                                    <div class="user-info" data-user-id="<?php echo $comment['user_id']; ?>">
                                        <div class="user-icon">
                                            <a href="perfil.php?id=<?php echo $comment['user_id']; ?>"><img src="<?php echo htmlspecialchars($comment['profile_image_url']); ?>" alt="Foto de Perfil"></a>
                                        </div>
                                        <div class="user-details">
                                            <a href="perfil.php?id=<?php echo $comment['user_id']; ?>" class="user-name-link"><span class="user-name"><?php echo htmlspecialchars($comment['full_name']); ?></span></a><br>
                                            <span class="user-tag">@<?php echo strtolower(explode(' ', $comment['full_name'])[0]); ?></span>
                                        </div>
                                    </div>
                                    <div class="post-date"><span><?php echo date("d/m/Y H:i", strtotime($comment['created_at'])); ?></span></div>
                                </header>
                                <section class="post-main">
                                    <p><?php echo nl2br(htmlspecialchars($comment['content'])); ?></p>
                                </section>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p id="no-comments-message">Ainda não há respostas. Seja o primeiro a comentar!</p>
                    <?php endif; ?>
                </div>
            </div>
        </section>
    <?php else: ?>
        <p class="error-message">Post não encontrado. Ele pode ter sido removido.</p>
    <?php endif; ?>
</main>

<div id="imageLightbox" class="lightbox">
    <span class="lightbox-close">&times;</span>
    <a class="lightbox-nav prev">&#10094;</a>
    <img class="lightbox-content" id="lightboxImage">
    <a class="lightbox-nav next">&#10095;</a>
</div>

<?php include("../src/components/footer.php"); ?>