<?php
include("../src/components/header.php");

// Validação inicial: Garante que um ID foi passado pela URL
if (!isset($_GET['id_post']) || !is_numeric($_GET['id_post'])) {
    // Se não houver ID, exibe uma mensagem de erro e encerra.
    echo "<main><div class='container'><p class='error-message'>Post não encontrado ou inválido.</p></div></main>";
    include("../src/components/footer.php");
    exit();
}

$id_post = intval($_GET['id_post']);
$current_user_id = isset($_SESSION['id_usu']) ? $_SESSION['id_usu'] : 0;

// --- BUSCA O POST PRINCIPAL ---
$sql_post = "SELECT 
                p.id_post, p.conteudo_post, p.data_post, p.cont_visualizacoes, p.cont_likes, p.cont_respostas,
                p.cont_reposts, p.cont_citacoes, u.id_usu, u.nome_usu, u.imgperfil_usu,
                GROUP_CONCAT(DISTINCT t.nome_tag SEPARATOR ', ') as tags,
                GROUP_CONCAT(DISTINCT pm.url_media SEPARATOR ';') as media_urls,
                (SELECT COUNT(*) FROM likes l WHERE l.id_post = p.id_post AND l.id_usu = ?) AS user_has_liked
            FROM posts AS p
            JOIN usuarios AS u ON p.id_usu = u.id_usu
            LEFT JOIN post_media AS pm ON p.id_post = pm.id_post
            LEFT JOIN posts_tags pt ON p.id_post = pt.id_post
            LEFT JOIN tags t ON pt.id_tag = t.id_tag
            WHERE p.id_post = ? AND p.stats_post = 'ativo'
            GROUP BY p.id_post";

$stmt_post = $conn->prepare($sql_post);
$stmt_post->bind_param("ii", $current_user_id, $id_post);
$stmt_post->execute();
$result_post = $stmt_post->get_result();
$post = $result_post->fetch_assoc();

// --- BUSCA OS COMENTÁRIOS DO POST ---
$sql_comments = "SELECT
                    p.id_post, p.conteudo_post, p.data_post, u.id_usu, u.nome_usu, u.imgperfil_usu,
                    p.cont_likes,
                    (SELECT COUNT(*) FROM likes l WHERE l.id_post = p.id_post AND l.id_usu = ?) AS user_has_liked
                FROM posts p
                JOIN usuarios u ON p.id_usu = u.id_usu
                WHERE p.id_post_pai = ? AND p.tipo_post = 'resposta'
                ORDER BY p.data_post ASC";

$stmt_comments = $conn->prepare($sql_comments);
$stmt_comments->bind_param("ii", $current_user_id, $id_post);
$stmt_comments->execute();
$result_comments = $stmt_comments->get_result();
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post de <?php echo htmlspecialchars($post['nome_usu'] ?? 'Usuário'); ?> - IFApoia</title>
    <link rel="stylesheet" href="../src/assets/css/style.css">
    <link rel="stylesheet" href="../src/assets/css/post_view.css">
</head>

<body data-is-logged-in="<?php echo ($current_user_id > 0) ? 'true' : 'false'; ?>">
    <main class="view-post-container">
        <?php if ($post): ?>
            <div class="post-card-full">
                <header class="post-header">
                    <div class="user-info" data-user-id=" <?php echo $post['id_usu']; ?>">
                        <div class="user-icon">
                            <img src="<?php echo htmlspecialchars($post['imgperfil_usu']); ?>" alt="Foto de Perfil">
                        </div>
                        <div class="user-details">
                            <span class="user-name"><?php echo htmlspecialchars($post['nome_usu']); ?></span><br>
                            <span class="user-tag">@<?php echo strtolower(explode(' ', $post['nome_usu'])[0]); ?></span>
                        </div>
                    </div>
                    <div class="post-info">
                        <div class="post-date">
                            <div class="post-hour">
                                <?php echo "" . date("H:i", strtotime(($post['data_post']))); ?>
                            </div>
                            <div class="post-calendar">
                                <?php echo date("d/m/Y", strtotime($post['data_post'])); ?>
                            </div>
                        </div>
                        <div class="post-views">
                            <i class="ri-bar-chart-grouped-line"></i>
                            <span><?php echo $post['cont_visualizacoes']; ?></span>
                        </div>
                    </div>
                </header>
                <section class="post-main">
                    <p><?php echo nl2br(htmlspecialchars($post['conteudo_post'])); ?></p>
                    <?php
                    $media_urls = !empty($post['media_urls']) ? explode(';', $post['media_urls']) : [];
                    if (count($media_urls) > 0): ?>
                        <div class="post-media-grid" data-count="<?php echo count($media_urls); ?>">
                            <?php foreach ($media_urls as $url): ?>
                                <div class="img_container">
                                    <img src="<?php echo htmlspecialchars($url); ?>" alt="Imagem do Post" class="img-fluid">
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </section>
                <footer class="post-footer">
                    <?php $user_has_liked_post = $post['user_has_liked'] > 0; ?>
                    <div class="post-stats-left">
                        <button class="post-icon like-btn <?php echo $user_has_liked_post ? 'liked' : ''; ?>" data-post-id="<?php echo $post['id_post']; ?>">
                            <i class="ri-heart-<?php echo $user_has_liked_post ? 'fill' : 'line'; ?>"></i>
                            <span class="post-cont"><?php echo $post['cont_likes']; ?></span>
                        </button>
                        <div class="post-icon">
                            <i class="ri-chat-3-line"></i>
                            <span class="post-cont"><?php echo $post['cont_respostas']; ?></span>
                        </div>
                    </div>
                    <div class="post-stats-right">
                        <div class="post-icon">
                            <i class="ri-repeat-line"></i>
                            <span class="post-cont"><?php echo $post['cont_reposts']; ?></span>
                        </div>
                        <div class="post-icon">
                            <i class="ri-chat-quote-line"></i>
                            <span class="post-cont"><?php echo $post['cont_citacoes']; ?></span>
                        </div>
                    </div>
                </footer>
            </div>

            <!-- Formulário para Comentar -->
            <?php if ($current_user_id > 0): ?>
                <div class="reply-form-container">
                    <form id="reply-form">
                        <textarea name="conteudo_post" id="reply-textarea" placeholder="Poste sua resposta" required></textarea>
                        <input type="hidden" name="id_post_pai" value="<?php echo $id_post; ?>">
                        <button type="submit" class="btn btn-primary">Responder</button>
                    </form>
                </div>
            <?php else: ?>
                <p class="login-prompt"><a href="login.php">Faça login</a> para responder a esta publicação.</p>
            <?php endif; ?>

            <!-- Seção de Comentários -->
            <div class="comments-section" id="comments-section">
                <?php if ($result_comments->num_rows > 0): ?>
                    <?php while ($comment = $result_comments->fetch_assoc()): ?>
                        <?php $user_has_liked_comment = $comment['user_has_liked'] > 0; ?>
                        <div class="comment-card">
                            <header class="post-header">
                                <div class="user-info" data-user-id="<?php echo $post['id_usu']; ?>">
                                    <div class=" user-icon">
                                        <img src="<?php echo htmlspecialchars($comment['imgperfil_usu']); ?>" alt="Foto de Perfil">
                                    </div>
                                    <div class="user-details">
                                        <span class="user-name"><?php echo htmlspecialchars($comment['nome_usu']); ?></span><br>
                                        <span class="user-tag">@<?php echo strtolower(explode(' ', $comment['nome_usu'])[0]); ?></span>
                                    </div>
                                </div>
                                <div class="post-date">
                                    <span><?php echo date("d/m/Y H:i", strtotime($comment['data_post'])); ?></span>
                                </div>
                            </header>
                            <section class="post-main">
                                <p><?php echo nl2br(htmlspecialchars($comment['conteudo_post'])); ?></p>
                            </section>
                            <footer class="post-footer">
                                <div class="post-stats-left">
                                    <button class="post-icon like-btn <?php echo $user_has_liked_comment ? 'liked' : ''; ?>" data-post-id="<?php echo $comment['id_post']; ?>">
                                        <i class="ri-heart-<?php echo $user_has_liked_comment ? 'fill' : 'line'; ?>"></i>
                                        <span class="post-cont"><?php echo $comment['cont_likes']; ?></span>
                                    </button>
                                </div>
                            </footer>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p id="no-comments-message">Ainda não há respostas. Seja o primeiro a comentar!</p>
                <?php endif; ?>
            </div>

        <?php else: ?>
            <p class="error-message">Post não encontrado. Ele pode ter sido removido.</p>
        <?php endif; ?>
    </main>

    <!-- Lightbox Modal para Visualização de Imagem -->
    <div id="imageLightbox" class="lightbox">
        <span class="lightbox-close">&times;</span>
        <a class="lightbox-nav prev">&#10094;</a>
        <img class="lightbox-content" id="lightboxImage">
        <a class="lightbox-nav next">&#10095;</a>
    </div>

    <?php include("../src/components/footer.php"); ?>

</body>

</html>