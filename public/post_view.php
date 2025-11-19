<?php

$currentPage = '';

// Habilita erros para facilitar debug se necessário
ini_set('display_errors', 1);
error_reporting(E_ALL);

// O modal deve ser o primeiro para processar formulários antes de qualquer HTML
require_once(__DIR__ . '/../src/components/modal_postagem.php');
require_once('../src/components/header.php');

// Validação do ID do post
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<main class='index-container'><section class='main_container'><div class='main_content'><p class='error-message'>Post não encontrado ou inválido.</p></div></section></main>";
    include("../src/components/footer.php");
    exit();
}

$post_id = intval($_GET['id']);
$current_user_id = $_SESSION['user_id'] ?? 0;

// LÓGICA PARA VISUALIZAÇÃO ÚNICA
if (isset($_SESSION['user_id']) && isset($post_id)) {
    $sql_view = "INSERT IGNORE INTO post_views (post_id, user_id) VALUES (?, ?)";
    $stmt_view = $pdo->prepare($sql_view);
    $stmt_view->execute([$post_id, $current_user_id]);

    if ($stmt_view->rowCount() > 0) {
        $sql_update_count = "UPDATE posts SET view_count = view_count + 1 WHERE post_id = ?";
        $stmt_update_count = $pdo->prepare($sql_update_count);
        $stmt_update_count->execute([$post_id]);
    }
}

// --- BUSCA O POST PRINCIPAL ---
$sql_post = "SELECT 
                p.post_id, 
                p.content, 
                p.created_at, 
                p.view_count, 
                p.like_count, 
                p.reply_count,
                p.repost_count,
                p.bookmark_count,
                u.user_id, 
                u.full_name, 
                u.profile_image_url,
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

// --- BUSCA OS COMENTÁRIOS ---
$sql_comments = "SELECT
                    c.comment_id, c.content, c.created_at, u.user_id, u.full_name, u.profile_image_url
                 FROM comments c
                 JOIN users u ON c.user_id = u.user_id
                 WHERE c.post_id = ?
                 ORDER BY c.created_at ASC";

$stmt_comments = $pdo->prepare($sql_comments);
$stmt_comments->execute([$post_id]);
$comments = $stmt_comments->fetchAll(PDO::FETCH_ASSOC);

// --- BUSCA MOTIVOS DE DENÚNCIA ---
$sql_reasons = "SELECT description FROM report_reasons ORDER BY description ASC";
$report_reasons = $pdo->query($sql_reasons)->fetchAll(PDO::FETCH_ASSOC);
?>

<link rel="stylesheet" href="../src/assets/css/post_view.css">

<main class="index-container">

    <?php require_once(__DIR__ . '/../src/components/nav_bar.php'); ?>

    <section class="main_container">
        <div class="main_content" data-pagina="post_view">

            <?php if ($post): ?>
                <div class="post-card-full">
                    <header class="post-header">
                        <div class="user-info" data-user-id="<?php echo $post['user_id']; ?>">
                            <div class="user-icon">
                                <a href="perfil.php?id=<?php echo $post['user_id']; ?>">
                                    <img src="<?php echo htmlspecialchars($post['profile_image_url']); ?>" alt="Foto de Perfil">
                                </a>
                            </div>
                            <div class="user-details">
                                <a href="perfil.php?id=<?php echo $post['user_id']; ?>" class="user-name-link">
                                    <span class="user-name"><?php echo htmlspecialchars($post['full_name']); ?></span>
                                </a>
                                <span class="user-tag">@<?php echo strtolower(explode(' ', $post['full_name'])[0]); ?></span>
                            </div>
                        </div>
                        <div class="post-info">
                            <div class="post-date">
                                <div class="post-hour">
                                    <?php echo date("H:i", strtotime(($post['created_at']))); ?>
                                </div>
                                <div class="post-calendar">
                                    <?php echo date("d/m/Y", strtotime($post['created_at'])); ?>
                                </div>
                            </div>
                            <div class="post-views">
                                <i class="ri-eye-line"></i>
                                <span><?php echo $post['view_count']; ?></span>
                            </div>
                        </div>
                    </header>

                    <section class="post-main">
                        <div class="post-text">
                            <?php echo nl2br(htmlspecialchars($post['content'])); ?>
                        </div>

                        <?php $media_urls = !empty($post['media_urls']) ? explode(';', $post['media_urls']) : []; ?>
                        <?php if (count($media_urls) > 0): ?>
                            <div class="post-media-grid" data-count="<?php echo count($media_urls); ?>">
                                <?php foreach ($media_urls as $url): ?>
                                    <img src="<?php echo htmlspecialchars($url); ?>" alt="Imagem do Post" class="img-fluid">
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($post['tags'])): ?>
                            <div class="post-tags">
                                <?php
                                $tags = explode(', ', $post['tags']);
                                foreach ($tags as $tag): ?>
                                    <span class="hashtag">#<?php echo htmlspecialchars($tag); ?></span>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </section>

                    <footer class="post-footer">
                        <div class="post-stats-left">
                            <?php $likedClass = ($post['user_has_liked'] > 0) ? 'liked' : ''; ?>
                            <?php $heartIcon = ($post['user_has_liked'] > 0) ? 'ri-heart-fill' : 'ri-heart-line'; ?>
                            <button class="post-btn like-btn <?php echo $likedClass; ?>" data-post-id="<?php echo $post['post_id']; ?>">
                                <i class="<?php echo $heartIcon; ?>"></i>
                                <span class="post-cont"><?php echo $post['like_count']; ?></span>
                            </button>

                            <div class="post-btn reply-indicator">
                                <i class="ri-chat-3-line"></i>
                                <span class="post-cont"><?php echo $post['reply_count']; ?></span>
                            </div>
                        </div>

                        <div class="post-stats-right">
                            <?php $repostClass = ($post['user_has_reposted'] > 0) ? 'reposted' : ''; ?>
                            <button class="post-btn repost-btn <?php echo $repostClass; ?>" data-post-id="<?php echo $post['post_id']; ?>" title="Repostar">
                                <i class="ri-repeat-line"></i>
                                <span class="post-cont"><?php echo $post['repost_count']; ?></span>
                            </button>

                            <?php $saveClass = ($post['user_has_bookmarked'] > 0) ? 'bookmarked' : ''; ?>
                            <?php $saveIcon = ($post['user_has_bookmarked'] > 0) ? 'ri-bookmark-fill' : 'ri-bookmark-line'; ?>
                            <button class="post-btn bookmark-btn <?php echo $saveClass; ?>" data-post-id="<?php echo $post['post_id']; ?>" title="Salvar">
                                <i class="<?php echo $saveIcon; ?>"></i>
                                <span class="post-cont"><?php echo $post['bookmark_count']; ?></span>
                            </button>

                            <button class="post-btn report-btn" data-post-id="<?php echo $post['post_id']; ?>" title="Denunciar">
                                <i class="ri-alarm-warning-line"></i>
                            </button>
                        </div>
                    </footer>
                </div>

                <div class="comments-wrapper">
                    <h3>Respostas</h3>

                    <?php if ($current_user_id > 0): ?>
                        <div class="reply-form-container">
                            <form id="reply-form" action="api/api_reply_post.php" method="POST">
                                <div class="reply-input-group">
                                    <div class="user-avatar-small">
                                        <img src="<?php echo $_SESSION['profile_image_url'] ?? '../src/assets/img/default-user.png'; ?>" alt="Eu">
                                    </div>
                                    <textarea name="content" id="reply-textarea" placeholder="Poste sua resposta..." required></textarea>
                                </div>
                                <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
                                <div class="form-actions">
                                    <button type="submit" class="btn-reply-submit">Responder</button>
                                </div>
                            </form>
                        </div>
                    <?php else: ?>
                        <div class="login-prompt">
                            <p><a href="login.php">Faça login</a> para responder a esta publicação.</p>
                        </div>
                    <?php endif; ?>

                    <div class="comments-list" id="comments-section">
                        <?php if (count($comments) > 0): ?>
                            <?php foreach ($comments as $comment): ?>
                                <div class="comment-card">
                                    <div class="comment-avatar">
                                        <a href="perfil.php?id=<?php echo $comment['user_id']; ?>">
                                            <img src="<?php echo htmlspecialchars($comment['profile_image_url']); ?>" alt="Avatar">
                                        </a>
                                    </div>
                                    <div class="comment-body">
                                        <div class="comment-header">
                                            <a href="perfil.php?id=<?php echo $comment['user_id']; ?>" class="comment-author">
                                                <?php echo htmlspecialchars($comment['full_name']); ?>
                                            </a>
                                            <span class="comment-date"><?php echo date("d/m/Y H:i", strtotime($comment['created_at'])); ?></span>
                                        </div>
                                        <div class="comment-text">
                                            <?php echo nl2br(htmlspecialchars($comment['content'])); ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="no-comments">
                                <p>Seja o primeiro a comentar!</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

            <?php else: ?>
                <div class="error-state">
                    <h2>Post não encontrado</h2>
                    <p>Este conteúdo pode ter sido removido ou não existe.</p>
                    <a href="index.php" class="btn-back">Voltar para o início</a>
                </div>
            <?php endif; ?>
        </div>
    </section>
</main>

<div id="imageLightbox" class="lightbox">
    <span class="lightbox-close">&times;</span>
    <a class="lightbox-nav prev">&#10094;</a>
    <img class="lightbox-content" id="lightboxImage">
    <a class="lightbox-nav next">&#10095;</a>
</div>

<div class="report-modal-overlay" id="reportModal">
    <div class="report-modal-content">
        <i class="ri-close-line modal-close-btn" id="closeReportModal"></i>
        <h2 class="modal-title">Denunciar Publicação</h2>
        <p class="modal-subtitle">Denúncias são anônimas e analisadas pela moderação.</p>

        <form id="reportForm">
            <input type="hidden" id="reportPostId" value="">

            <div class="form-group">
                <label for="reportReason" class="form-label">Motivo Principal *</label>
                <select id="reportReason" class="form-select" required>
                    <option value="">Selecione um motivo...</option>
                    <?php if (!empty($report_reasons)): ?>
                        <?php foreach ($report_reasons as $reason): ?>
                            <option value="<?php echo htmlspecialchars($reason['description']); ?>">
                                <?php echo htmlspecialchars($reason['description']); ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>

            <div class="form-group mt-3">
                <label for="reportDetails" class="form-label">Detalhes (Opcional)</label>
                <textarea id="reportDetails" rows="3" class="form-textarea" placeholder="Descreva o problema..."></textarea>
            </div>

            <button type="submit" class="btn btn-danger-primary mt-3 w-100" id="submitReportBtn">Enviar Denúncia</button>
        </form>
    </div>
</div>

<?php include("../src/components/footer.php"); ?>