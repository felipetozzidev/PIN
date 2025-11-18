<?php
// Habilita a exibição de todos os erros para diagnóstico no ambiente local.
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . '/../src/components/modal_postagem.php');

// O header agora inicia a sessão e faz a conexão via PDO.
// A utilização de __DIR__ torna o caminho mais robusto e à prova de erros.
require_once(__DIR__ . '/../src/components/header.php');

// Pega o ID do usuário logado, ou 0 se for um visitante
$current_user_id = $_SESSION['user_id'] ?? 0;

// Lógica de busca e exibição de posts unificada
$search_query = isset($_GET['search']) ? trim($_GET['search']) : '';

// SQL APRIMORADO com os novos nomes e PDO
$sql = "SELECT 
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
            GROUP_CONCAT(DISTINCT pm.media_url SEPARATOR ';') as media_urls,
            (SELECT COUNT(*) FROM likes l WHERE l.post_id = p.post_id AND l.user_id = :current_user_id) AS user_has_liked,
            (SELECT COUNT(*) FROM reposts r WHERE r.post_id = p.post_id AND r.user_id = :current_user_id) AS user_has_reposted,
            (SELECT COUNT(*) FROM bookmarks b WHERE b.post_id = p.post_id AND b.user_id = :current_user_id) AS user_has_bookmarked,
            c.name as community_name,
            GROUP_CONCAT(DISTINCT t.name SEPARATOR ', ') as tags
        FROM posts AS p
        JOIN users AS u ON p.user_id = u.user_id
        LEFT JOIN post_media AS pm ON p.post_id = pm.post_id
        LEFT JOIN community_posts cp ON p.post_id = cp.post_id
        LEFT JOIN communities c ON cp.community_id = c.community_id
        LEFT JOIN post_tags pt ON p.post_id = pt.post_id
        LEFT JOIN tags t ON pt.tag_id = t.tag_id";

$where_clauses = ["p.type = 'padrao'", "p.status = 'ativo'"];
$params = [':current_user_id' => $current_user_id];

if (!empty($search_query)) {
    $where_clauses[] = "(p.content LIKE :search OR u.full_name LIKE :search OR c.name LIKE :search OR t.name LIKE :search)";
    $params[':search'] = "%" . $search_query . "%";
}

if (!empty($where_clauses)) {
    $sql .= " WHERE " . implode(' AND ', $where_clauses);
}

$sql .= " GROUP BY p.post_id ORDER BY p.created_at DESC LIMIT 20";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Busca as 5 comunidades mais populares (com mais membros)
$sql_comunidades = "SELECT 
                        c.name as community_name,
                        COUNT(uc.user_id) as total_membros
                    FROM communities c
                    LEFT JOIN user_communities uc ON c.community_id = uc.community_id
                    GROUP BY c.community_id
                    ORDER BY total_membros DESC
                    LIMIT 5";

$result_comunidades = $pdo->query($sql_comunidades);
?>
<!-- O restante do seu HTML continua aqui, sem alterações -->

<body data-is-logged-in="<?php echo ($current_user_id > 0) ? 'true' : 'false'; ?>">

    <main class="index-container" style="margin-top: 88px; margin-bottom: 44px">
        <?php require_once(__DIR__ . '/../src/components/nav_bar.php'); ?>

        <section class="main_container">
            <div class="main_content" data-pagina="pagina principal">
                <div class="destaques">
                    <h1 class="title">Mais vistos</h1>
                    <div class="cards">
                        <span>Mais vistos</span>
                    </div>
                </div>
                <div class="postagens_e_publicacoes">
                    <div class="postagens">
                        <h1 class="title">Postagens</h1>
                        <?php if ($posts && count($posts) > 0): ?>
                            <?php foreach ($posts as $post):
                                $user_has_liked = $post['user_has_liked'] > 0; ?>
                                <div class="post_container" data-post-url="post_view.php?id=<?php echo $post['post_id']; ?>">
                                    <header class="post_header">
                                        <div class="user_info" data-user-id="<?php echo $post['user_id']; ?>">
                                            <div class="user_icon">
                                                <img src="<?php echo htmlspecialchars($post['profile_image_url']); ?>"
                                                    alt="Foto de Perfil">
                                            </div>
                                            <div class="user-details">
                                                <span
                                                    class="user-name"><a href="perfil.php?id=<?php echo $post['user_id']; ?>"><?php echo htmlspecialchars($post['full_name']); ?></a></span><br>
                                                <span
                                                    class="user-tag">@<?php echo strtolower(explode(' ', $post['full_name'])[0]); ?></span>
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
                                    <section class="post_main">
                                        <p><?php echo nl2br(htmlspecialchars($post['content'])); ?></p>
                                        <?php
                                        $media_urls = !empty($post['media_urls']) ? explode(';', $post['media_urls']) : [];
                                        $media_count = count($media_urls);
                                        if ($media_count > 0): ?>
                                            <div class="post-media-grid" data-count="<?php echo $media_count; ?>">
                                                <?php foreach ($media_urls as $url): ?>
                                                    <img src="<?php echo htmlspecialchars($url); ?>" alt="Imagem do Post">
                                                <?php endforeach; ?>
                                            </div>
                                        <?php endif; ?>
                                    </section>
                                    <footer class="post_footer">
                                        <div class="post-stats-left">
                                            <?php $likedClass = ($post['user_has_liked'] > 0) ? 'liked' : ''; ?>
                                            <?php $heartIcon = ($post['user_has_liked'] > 0) ? 'ri-heart-fill' : 'ri-heart-line'; ?>
                                            <button class="post-icon like-btn <?php echo $likedClass; ?>" data-post-id="<?php echo $post['post_id']; ?>">
                                                <i class="<?php echo $heartIcon; ?>"></i>
                                                <span class="post-cont"><?php echo $post['like_count']; ?></span>
                                            </button>

                                            <a href="post_view.php?id=<?php echo $post['post_id']; ?>" class="post-icon reply-btn">
                                                <i class="ri-chat-3-line"></i>
                                                <span class="post-cont"><?php echo $post['reply_count']; ?></span>
                                            </a>
                                        </div>

                                        <div class="post-stats-right">
                                            <?php $repostClass = ($post['user_has_reposted'] > 0) ? 'reposted' : ''; ?>
                                            <button class="post-icon repost-btn <?php echo $repostClass; ?>" data-post-id="<?php echo $post['post_id']; ?>">
                                                <i class="ri-repeat-line"></i>
                                                <span class="post-cont"><?php echo $post['repost_count']; ?></span>
                                            </button>

                                            <?php $saveClass = ($post['user_has_bookmarked'] > 0) ? 'bookmarked' : ''; ?>
                                            <?php $saveIcon = ($post['user_has_bookmarked'] > 0) ? 'ri-bookmark-fill' : 'ri-bookmark-line'; ?>
                                            <button class="post-icon bookmark-btn <?php echo $saveClass; ?>" data-post-id="<?php echo $post['post_id']; ?>">
                                                <i class="<?php echo $saveIcon; ?>"></i>
                                                <span class="post-cont"><?php echo $post['bookmark_count']; ?></span>
                                            </button>

                                            <button class="post-icon report-btn" data-post-id="<?php echo $post['post_id']; ?>" title="Denunciar">
                                                <i class="ri-alarm-warning-line"></i>
                                            </button>
                                        </div>
                                    </footer>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="post-card">
                                <section class="post-main">
                                    <p>Ainda não há nenhuma postagem. Seja o primeiro a publicar!</p>
                                </section>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="comunidades_populares">
                        <h1 class="title">Comunidades Populares</h1>
                        <div class="community_cards_container">
                            <?php if ($result_comunidades && $result_comunidades->rowCount() > 0): ?>
                                <?php while ($comunidade = $result_comunidades->fetch(PDO::FETCH_ASSOC)): ?>
                                    <div class="community_card">
                                        <div class="community_icon"><i class="ri-group-line"></i></div>
                                        <p>
                                            <span
                                                class="community_name"><?php echo htmlspecialchars($comunidade['community_name']); ?></span>
                                            <span class="community_followers"><?php echo $comunidade['total_membros']; ?>
                                                seguidores</span>
                                        </p>
                                    </div>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <div class="community_card">
                                    <p>Nenhuma comunidade popular encontrada.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
        </section>
    </main>



    <div id="imageLightbox" class="lightbox">
        <span class="lightbox-close">&times;</span>
        <a class="lightbox-nav prev">&#10094;</a>
        <img class="lightbox-content" id="lightboxImage">
        <a class="lightbox-nav next">&#10095;</a>
    </div>

    <?php require_once(__DIR__ . '/../src/components/footer.php'); ?>