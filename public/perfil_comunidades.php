<?php
require_once('../config/conn.php');
require_once('../src/components/header.php');

// 1. OBTER E VALIDAR O ID DA COMUNIDADE
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    // Redireciona se o ID for inválido
    header("Location: comunidades.php");
    exit();
}
$community_id = intval($_GET['id']);

// 2. BUSCAR DADOS DA COMUNIDADE
try {
    $sql_community = "SELECT 
                        c.*,
                        (SELECT COUNT(*) FROM user_communities uc WHERE uc.community_id = c.community_id) as member_count
                      FROM communities c 
                      WHERE c.community_id = ?";
    $stmt_community = $pdo->prepare($sql_community);
    $stmt_community->execute([$community_id]);
    $community = $stmt_community->fetch(PDO::FETCH_ASSOC);

    // Se a comunidade não for encontrada, redireciona
    if (!$community) {
        header("Location: comunidades.php");
        exit();
    }
    
    // Atualiza a contagem de visitas (views)
    $pdo->prepare("UPDATE communities SET view_count = view_count + 1 WHERE community_id = ?")->execute([$community_id]);

    // 3. VERIFICAR SE O USUÁRIO É MEMBRO
    $is_member = false;
    if (isset($_SESSION['user_id'])) {
        $sql_is_member = "SELECT 1 FROM user_communities WHERE user_id = ? AND community_id = ?";
        $stmt_is_member = $pdo->prepare($sql_is_member);
        $stmt_is_member->execute([$_SESSION['user_id'], $community_id]);
        $is_member = $stmt_is_member->fetchColumn() !== false;
    }

    // 4. BUSCAR POSTS DA COMUNIDADE (com ordenação)
    $sort_order = 'p.created_at DESC'; // Padrão: mais novos
    if (isset($_GET['sort'])) {
        if ($_GET['sort'] === 'top') {
            $sort_order = 'p.like_count DESC, p.view_count DESC';
        }
    }

    $sql_posts = "SELECT p.*, u.full_name, u.profile_picture 
                  FROM posts p
                  JOIN users u ON p.user_id = u.user_id
                  JOIN community_posts cp ON p.post_id = cp.post_id
                  WHERE cp.community_id = ?
                  ORDER BY $sort_order";
    $stmt_posts = $pdo->prepare($sql_posts);
    $stmt_posts->execute([$community_id]);
    $posts = $stmt_posts->fetchAll(PDO::FETCH_ASSOC);

} catch (Exception $e) {
    // Em caso de erro, exibe uma mensagem amigável e registra o erro real.
    error_log("Erro na página da comunidade: " . $e->getMessage());
    die("Ocorreu um erro ao carregar a comunidade.");
}

?>
<!-- Link para a nova folha de estilo -->
<link rel="stylesheet" href="../src/assets/css/perfil_comunidade.css">

<main>
    <?php require_once('../src/components/nav_bar.php'); ?>

    <section class="main_container">
        <div class="community-profile-page">
            
            <!-- SEÇÃO DO HEADER DA COMUNIDADE (BANNER E INFO) -->
            <header class="community-header">
                <div class="community-banner" style="background-image: url('<?php echo htmlspecialchars($community['banner_url'] ?? '../src/assets/img/default-banner.png'); ?>');"></div>
                <div class="community-info-bar">
                    <div class="community-details">
                        <img src="<?php echo htmlspecialchars($community['profile_picture'] ?? '../src/assets/img/default-community.png'); ?>" alt="Ícone da Comunidade" class="community-icon">
                        <div class="community-name-members">
                            <h1><?php echo htmlspecialchars($community['name']); ?></h1>
                            <span><?php echo $community['member_count']; ?> membros</span>
                        </div>
                    </div>
                    <div class="community-actions">
                        <a href="join_community.php?id=<?php echo $community_id; ?>" class="join-button <?php echo $is_member ? 'joined' : ''; ?>">
                            <?php echo $is_member ? 'Inscrito' : 'Inscrever-se'; ?>
                        </a>
                    </div>
                </div>
            </header>

            <!-- CONTEÚDO PRINCIPAL (FEED E SIDEBAR) -->
            <div class="community-body">
                
                <!-- Coluna de Posts (Feed) -->
                <div class="feed-column">
                    <div class="sort-options">
                        <a href="?id=<?php echo $community_id; ?>&sort=new" class="<?php echo (!isset($_GET['sort']) || $_GET['sort'] === 'new') ? 'active' : ''; ?>">
                            <i class="ri-star-line"></i> Novos
                        </a>
                        <a href="?id=<?php echo $community_id; ?>&sort=top" class="<?php echo (isset($_GET['sort']) && $_GET['sort'] === 'top') ? 'active' : ''; ?>">
                            <i class="ri-fire-line"></i> Em Alta
                        </a>
                    </div>

                    <div class="post-feed">
                        <?php if ($posts): ?>
                            <?php foreach ($posts as $post): ?>
                                <div class="post_container" onclick="window.location.href='post_view.php?id=<?php echo $post['post_id']; ?>';">
                                    <header class="post_header">
                                        <div class="user_info">
                                            <img src="<?php echo htmlspecialchars($post['profile_picture'] ?? '../src/assets/img/default-user.png'); ?>" alt="Foto de Perfil" class="user_icon_post">
                                            <p class="user-name"><?php echo htmlspecialchars($post['full_name']); ?></p>
                                        </div>
                                        <span class="post-time"><?php echo date('d/m/Y', strtotime($post['created_at'])); ?></span>
                                    </header>
                                    <section class="post_main">
                                        <h3><?php echo htmlspecialchars(mb_strimwidth($post['content'], 0, 150, "...")); ?></h3>
                                    </section>
                                    <footer class="post_footer">
                                        <div>
                                            <span><i class="ri-heart-line"></i> <?php echo $post['like_count']; ?></span>
                                            <span><i class="ri-chat-3-line"></i> <?php echo $post['reply_count']; ?></span>
                                        </div>
                                    </footer>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="no-posts">Ainda não há posts nesta comunidade. Seja o primeiro a postar!</p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Coluna da Sidebar -->
                <aside class="sidebar-column">
                    <div class="about-widget">
                        <h4>Sobre <?php echo htmlspecialchars($community['name']); ?></h4>
                        <p><?php echo htmlspecialchars($community['description']); ?></p>
                        <ul>
                            <li><i class="ri-calendar-line"></i> Criada em <?php echo date('d/m/Y', strtotime($community['created_at'])); ?></li>
                            <li><i class="ri-eye-line"></i> <?php echo $community['view_count']; ?> visitas</li>
                        </ul>
                    </div>
                    <div class="admins-widget">
                        <h4>Administradores</h4>
                        <!-- Lógica para buscar e exibir administradores aqui -->
                    </div>
                     <div class="recommended-widget">
                        <h4>Comunidades Recomendadas</h4>
                        <!-- Lógica para buscar e exibir comunidades recomendadas aqui -->
                    </div>
                </aside>
            </div>
        </div>
    </section>
</main>
<?php require_once('../src/components/footer.php'); ?>
