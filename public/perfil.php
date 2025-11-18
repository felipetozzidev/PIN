<?php
// O header.php já inicia a sessão e faz a conexão com o banco de dados.
require_once(__DIR__ . '/../src/components/modal_postagem.php');
require_once('../src/components/header.php');

// Se o usuário não estiver logado, redireciona para o login.
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Pega o ID do perfil a ser visualizado (pela URL) ou do usuário logado (pela sessão).
$profile_user_id = $_GET['id'] ?? $_SESSION['user_id'];

// --- BUSCA DADOS DO USUÁRIO DO PERFIL ---
$stmt_user = $pdo->prepare("SELECT * FROM users WHERE user_id = ?");
$stmt_user->execute([$profile_user_id]);
$profile_user = $stmt_user->fetch(PDO::FETCH_ASSOC);

// Se o usuário não for encontrado, encerra a execução.
if (!$profile_user) {
    echo "<main class='container'><p class='error-message'>Usuário não encontrado.</p></main>";
    include("../src/components/footer.php");
    exit();
}

// --- BUSCA POSTS DO USUÁRIO DO PERFIL ---
$stmt_posts = $pdo->prepare("
    SELECT p.*, u.full_name, u.profile_image_url
    FROM posts p
    JOIN users u ON p.user_id = u.user_id
    WHERE p.user_id = ?
    ORDER BY p.created_at DESC
");
$stmt_posts->execute([$profile_user_id]);
$posts = $stmt_posts->fetchAll(PDO::FETCH_ASSOC);

?>

<!-- Adiciona a folha de estilo específica para esta página -->

<body>
    <main class="index-container main_perfil" style="margin-top: 88px; margin-bottom: 44px" data-pagina="user_profile">
        <?php require_once(__DIR__ . '/../src/components/nav_bar.php'); ?>

        <section class="main_container">
            <div class="main_content">
                <div class="profile_cover">
                    <img src="<?php echo htmlspecialchars($profile_user['cover_image_url']); ?>" class="cover_image <?php echo isset($profile_user['cover_image_url']) ? '' : 'd-none'; ?>">
                </div>
                <div class="profile_header">
                    <img src="<?php echo htmlspecialchars($profile_user['profile_image_url']); ?>" alt="Foto de Perfil" class="profile_image">
                    <div class="profile_details">
                        
                        <div class="profile_info">

                            <h2><?php echo htmlspecialchars($profile_user['full_name']); ?></h2>
                            <p><b>Contagem de seguidores em breve</b></p>
                            
                        </div>

                        <?php if ($profile_user_id == $_SESSION['user_id']): ?>
                            <div class="profile_options">
                                <a href="edit_profile.php" class="btn btn-secondary">Editar Perfil</a>
                            <button onclick="configButton()"><i class="ri-settings-3-fill"></i></button>

                            <script>
                                function configButton() {
                                    alert("Em breve, você poderá personalizar as configurações do seu perfil.");
                                }
                            </script>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="user_bio">
                    <p><?php echo htmlspecialchars($profile_user['bio']); ?></p>
                </div>
                <div class="profile_content">
                    <h3>Posts de <?php echo htmlspecialchars($profile_user['full_name']); ?></h3>
                    <hr>
                    <div class="postagens">
                        <?php if ($posts && count($posts) > 0): ?>
                            <?php foreach ($posts as $post): ?>
                                <div class="post_container" data-post-url="post_view.php?id=<?php echo $post['post_id']; ?>">
                                    <header class="post_header">
                                        <div class="user_info">
                                            <div class="user_icon">
                                                <img src="<?php echo htmlspecialchars($post['profile_image_url']); ?>"
                                                    alt="Foto de Perfil">
                                            </div>
                                            <div class="user-details">
                                                <span
                                                    class="user-name"><?php echo htmlspecialchars($post['full_name']); ?></span><br>
                                                <span
                                                    class="user-tag">@<?php echo strtolower(explode(' ', $post['full_name'])[0]); ?></span>
                                            </div>
                                        </div>
                                        <div class="post-date">
                                            <span><?php echo date('d/m/Y H:i', strtotime($post['created_at'])); ?></span>
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
                                            <div class="post-icon">
                                                <i class="ri-heart-line"></i>
                                                <span class="post-cont"><?php echo $post['like_count']; ?></span>
                                            </div>
                                            <div class="post-icon">
                                                <i class="ri-chat-3-line"></i>
                                                <span class="post-cont"><?php echo $post['reply_count']; ?></span>
                                            </div>
                                        </div>
                                    </footer>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p><?php echo htmlspecialchars($profile_user['full_name']); ?> ainda não fez nenhuma publicação.
                            </p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

        </section>

    </main>
    <?php require_once '../src/components/footer.php'; ?>
</body>