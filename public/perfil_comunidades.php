<?php
// Inclui apenas a conexão com o banco, que não gera saída de HTML.
require_once('../config/conn.php');

// Define a página para a lógica da sidebar saber que estamos em uma sub-página de comunidade.
$currentPage = 'comunidade_view';

// Validação do ID da comunidade na URL.
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: comunidades.php");
    exit;
}

$community_id = intval($_GET['id']);
$current_user_id = $_SESSION['user_id'] ?? 0;
// Variável para a nav_bar saber qual comunidade marcar como ativa na lista.
$currentCommunityId = $community_id;

try {
    // 1. INCREMENTA A CONTAGEM DE VISUALIZAÇÕES
    $sql_update_views = "UPDATE communities SET view_count = view_count + 1 WHERE community_id = ?";
    $stmt_update = $pdo->prepare($sql_update_views);
    $stmt_update->execute([$community_id]);

    // 2. BUSCA OS DETALHES DA COMUNIDADE - CORREÇÃO DE ESPAÇOS
    $sql_community = "SELECT c.*, (SELECT COUNT(*) FROM user_communities uc WHERE uc.community_id = c.community_id) as member_count 
                      FROM communities c 
                      WHERE c.community_id = ?";
    $stmt_community = $pdo->prepare($sql_community);
    $stmt_community->execute([$community_id]);
    $community = $stmt_community->fetch(PDO::FETCH_ASSOC);

    if (!$community) {
        header("Location: comunidades.php");
        exit;
    }

    // 3. BUSCA OS POSTS DA COMUNIDADE - CORREÇÃO DE ESPAÇOS
    $sql_posts = "SELECT 
                    p.*, 
                    u.full_name, 
                    u.profile_image_url,
                    (SELECT COUNT(*) FROM likes l WHERE l.post_id = p.post_id AND l.user_id = ?) AS user_has_liked
              FROM posts p
              JOIN users u ON p.user_id = u.user_id
              JOIN community_posts cp ON p.post_id = cp.post_id
              WHERE cp.community_id = ?
              ORDER BY p.created_at DESC";
    $stmt_posts = $pdo->prepare($sql_posts);
    $stmt_posts->execute([$current_user_id, $community_id]);
    $posts = $stmt_posts->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Erro na página de perfil de comunidade: " . $e->getMessage());
    header("Location: comunidades.php?error=1");
    exit;
}

require_once('../src/components/header.php');
?>

<main class="index-container" style="margin-top: 88px; margin-bottom: 44px">
    <?php require_once('../src/components/nav_bar.php'); ?>

    <section class="main_container">
        <div class="main_content" data-pagina="perfil-comunidade">

            <header class="community-header">
                <div class="community-banner">
                    <?php if (!empty($community['banner_picture'])): ?>
                        <img src="<?php echo htmlspecialchars($community['banner_picture']); ?>" alt="Banner da Comunidade">
                    <?php endif; ?>
                </div>
                <div class="community-info-bar">
                    <div class="community-details">
                        <img class="community-profile-pic" src="<?php echo htmlspecialchars($community['profile_picture'] ?? '../src/assets/img/default-community.png'); ?>" alt="">
                        <div class="community-name-members">
                            <h1><?php echo htmlspecialchars($community['name']); ?></h1>
                            <span><?php echo $community['member_count']; ?> membros</span>
                        </div>
                    </div>
                    <div class="community-actions">
                        <button class="join-button">Juntar-se</button>
                    </div>
                </div>
            </header>

            <div class="community-body-layout">

                <div class="feed-column">
                    <div class="post-sort-bar">
                        <button class="sort-button active"><i class="ri-fire-fill"></i> Populares</button>
                        <button class="sort-button"><i class="ri-time-line"></i> Recentes</button>
                    </div>

                    <div class="post-feed">
                        <?php if ($posts): ?>
                            <?php foreach ($posts as $post): ?>
                                <div class="post_container">
                                    <header class="post_header">
                                        <div class="user_info" data-user-id="<?php echo $post['user_id']; ?>">
                                            <div class="user_icon">
                                                <img src="<?php echo htmlspecialchars($post['profile_image_url']); ?>" alt="Foto de Perfil">
                                            </div>
                                            <div class="user-details">
                                                <span class="user-name"><a href="perfil.php?id=<?php echo $post['user_id']; ?>"><?php echo htmlspecialchars($post['full_name']); ?></a></span><br>
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
                                                <i class="ri-bar-chart-grouped-line"></i>
                                                <span><?php echo $post['view_count']; ?></span>
                                            </div>
                                        </div>
                                    </header>
                                    <section class="post_main">
                                        <a href="post_view.php?id=<?php echo $post['post_id']; ?>" class="post-link-content">
                                            <!-- <p><?php echo nl2br(htmlspecialchars(mb_strimwidth($post['content'], 0, 300, "..."))); ?></p> -->
                                        </a>
                                    </section>
                                    <footer class="post_footer">
                                        <?php $user_has_liked = $post['user_has_liked'] > 0; ?>
                                        <div class="post-stats-left">
                                            <button class="post-icon like-btn <?php echo $user_has_liked ? 'liked' : ''; ?>" data-post-id="<?php echo $post['post_id']; ?>">
                                                <i class="ri-heart-<?php echo $user_has_liked ? 'fill' : 'line'; ?>"></i>
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
                                                <span class="post-cont"><?php echo $post['repost_count'] ?? 0; ?></span>
                                            </div>
                                            <div class="post-icon">
                                                <i class="ri-chat-quote-line"></i>
                                                <span class="post-cont"><?php echo $post['bookmark_count'] ?? 0; ?></span>
                                            </div>
                                        </div>
                                    </footer>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="no-posts">Ainda não há posts nesta comunidade. Seja o primeiro a postar!</p>
                        <?php endif; ?>
                    </div>
                </div>

                <aside class="sidebar-column">
                    <div class="about-community-widget">
                        <h2>Sobre a Comunidade</h2>
                        <p><?php echo nl2br(htmlspecialchars($community['description'])); ?></p>
                        <ul>
                            <li><i class="ri-calendar-line"></i> Criada em <?php echo date('d/m/Y', strtotime($community['created_at'])); ?></li>
                            <li><i class="ri-eye-line"></i> <?php echo $community['view_count']; ?> visitas</li>
                        </ul>
                    </div>
                    <div class="moderators-widget">
                        <h2>Moderadores</h2>
                        <ul>
                            <li><a href="#">Admin 1</a></li>
                            <li><a href="#">Admin 2</a></li>
                        </ul>
                    </div>
                    <div class="recommended-widget">
                        <h2>Comunidades Recomendadas</h2>
                        <ul>
                            <li><a href="#">Comunidade A</a></li>
                            <li><a href="#">Comunidade B</a></li>
                        </ul>
                    </div>
                </aside>

            </div>

        </div>
    </section>
</main>
<?php require_once('../src/components/footer.php'); ?>