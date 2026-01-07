<?php

$currentPage = 'perfil';

require_once(__DIR__ . '/../src/components/modal_postagem.php');
require_once('../src/components/header.php');

// Verifica login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$current_user_id = $_SESSION['user_id'];
$profile_user_id = isset($_GET['id']) ? intval($_GET['id']) : $current_user_id;
$is_own_profile = ($profile_user_id === $current_user_id);
$current_tab = isset($_GET['tab']) ? $_GET['tab'] : 'posts';

// --- LÓGICA DE ATUALIZAÇÃO DE PERFIL (POST) ---
if ($is_own_profile && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_profile') {
    try {
        $pdo->beginTransaction();

        $full_name = trim($_POST['full_name']);
        $bio = trim($_POST['bio']);
        $state = trim($_POST['state']);
        $campus = trim($_POST['campus']);
        $gender = trim($_POST['gender']);
        $sexual_orientation = trim($_POST['sexual_orientation']);

        $stmt_update = $pdo->prepare("UPDATE users SET full_name = ?, bio = ?, state = ?, campus = ?, gender = ?, sexual_orientation = ? WHERE user_id = ?");
        $stmt_update->execute([$full_name, $bio, $state, $campus, $gender, $sexual_orientation, $current_user_id]);

        $_SESSION['full_name'] = $full_name;

        // Upload de Foto de Perfil
        if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
            $ext = pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION);
            $new_name = 'profile_' . $current_user_id . '_' . time() . '.' . $ext;
            $upload_dir = '../uploads/avatars/';
            if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

            if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $upload_dir . $new_name)) {
                $path_db = '../uploads/avatars/' . $new_name;
                $stmt_img = $pdo->prepare("UPDATE users SET profile_image_url = ? WHERE user_id = ?");
                $stmt_img->execute([$path_db, $current_user_id]);
                $_SESSION['profile_image_url'] = $path_db;
            }
        }

        // Upload de Capa
        if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
            $ext = pathinfo($_FILES['cover_image']['name'], PATHINFO_EXTENSION);
            $new_name = 'cover_' . $current_user_id . '_' . time() . '.' . $ext;
            $upload_dir = '../uploads/covers/';
            if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

            if (move_uploaded_file($_FILES['cover_image']['tmp_name'], $upload_dir . $new_name)) {
                $path_db = '../uploads/covers/' . $new_name;
                $stmt_cov = $pdo->prepare("UPDATE users SET cover_image_url = ? WHERE user_id = ?");
                $stmt_cov->execute([$path_db, $current_user_id]);
            }
        }

        $pdo->commit();
        header("Location: perfil.php");
        exit;
    } catch (Exception $e) {
        $pdo->rollBack();
    }
}

// --- BUSCA DADOS DO USUÁRIO ---
$stmt_user = $pdo->prepare("SELECT * FROM users WHERE user_id = ?");
$stmt_user->execute([$profile_user_id]);
$profile_user = $stmt_user->fetch(PDO::FETCH_ASSOC);

// --- BUSCA OS MOTIVOS DE DENÚNCIA (PARA O MODAL) ---
$sql_reasons = "SELECT description FROM report_reasons ORDER BY description ASC";
$report_reasons = $pdo->query($sql_reasons)->fetchAll(PDO::FETCH_ASSOC);

if (!$profile_user) {
    echo "<main class='index-container'><p class='error-message'>Usuário não encontrado.</p></main>";
    include("../src/components/footer.php");
    exit();
}

// --- CONTAGENS ---
$followers_count = 0;
$stmt_post_count = $pdo->prepare("SELECT COUNT(*) FROM posts WHERE user_id = ? AND type = 'padrao'");
$stmt_post_count->execute([$profile_user_id]);
$total_posts = $stmt_post_count->fetchColumn();

// --- LÓGICA DE BUSCA DE POSTS ---
$posts = [];
$params = [];
$sql = "";

// CORREÇÃO 1: Adicionado GROUP_CONCAT para media_urls
$sql_select_std = "p.*, u.full_name, u.profile_image_url,
    GROUP_CONCAT(DISTINCT pm.media_url SEPARATOR ';') as media_urls,
    (SELECT COUNT(*) FROM likes l_sub WHERE l_sub.post_id = p.post_id AND l_sub.user_id = ?) AS user_has_liked,
    (SELECT COUNT(*) FROM reposts r_sub WHERE r_sub.post_id = p.post_id AND r_sub.user_id = ?) AS user_has_reposted,
    (SELECT COUNT(*) FROM bookmarks b_sub WHERE b_sub.post_id = p.post_id AND b_sub.user_id = ?) AS user_has_bookmarked";

// CORREÇÃO 2: Adicionado LEFT JOIN post_media e GROUP BY em todos os cases
switch ($current_tab) {
    case 'reposts':
        $sql = "SELECT $sql_select_std 
                FROM reposts r 
                JOIN posts p ON r.post_id = p.post_id 
                JOIN users u ON p.user_id = u.user_id 
                LEFT JOIN post_media pm ON p.post_id = pm.post_id
                WHERE r.user_id = ? 
                GROUP BY p.post_id
                ORDER BY r.reposted_at DESC";
        $params = [$current_user_id, $current_user_id, $current_user_id, $profile_user_id];
        break;

    case 'likes':
        if (!$is_own_profile) {
            $posts = [];
            break;
        }
        $sql = "SELECT p.*, u.full_name, u.profile_image_url,
                GROUP_CONCAT(DISTINCT pm.media_url SEPARATOR ';') as media_urls,
                1 AS user_has_liked,
                (SELECT COUNT(*) FROM reposts r_sub WHERE r_sub.post_id = p.post_id AND r_sub.user_id = ?) AS user_has_reposted,
                (SELECT COUNT(*) FROM bookmarks b_sub WHERE b_sub.post_id = p.post_id AND b_sub.user_id = ?) AS user_has_bookmarked
                FROM likes l 
                JOIN posts p ON l.post_id = p.post_id 
                JOIN users u ON p.user_id = u.user_id 
                LEFT JOIN post_media pm ON p.post_id = pm.post_id
                WHERE l.user_id = ? 
                GROUP BY p.post_id
                ORDER BY l.liked_at DESC";
        $params = [$current_user_id, $current_user_id, $profile_user_id];
        break;

    case 'saved':
        if (!$is_own_profile) {
            $posts = [];
            break;
        }
        $sql = "SELECT p.*, u.full_name, u.profile_image_url,
                GROUP_CONCAT(DISTINCT pm.media_url SEPARATOR ';') as media_urls,
                (SELECT COUNT(*) FROM likes l_sub WHERE l_sub.post_id = p.post_id AND l_sub.user_id = ?) AS user_has_liked,
                (SELECT COUNT(*) FROM reposts r_sub WHERE r_sub.post_id = p.post_id AND r_sub.user_id = ?) AS user_has_reposted,
                1 AS user_has_bookmarked
                FROM bookmarks b 
                JOIN posts p ON b.post_id = p.post_id 
                JOIN users u ON p.user_id = u.user_id 
                LEFT JOIN post_media pm ON p.post_id = pm.post_id
                WHERE b.user_id = ? 
                GROUP BY p.post_id
                ORDER BY b.saved_at DESC";
        $params = [$current_user_id, $current_user_id, $profile_user_id];
        break;

    case 'posts':
    default:
        $sql = "SELECT $sql_select_std 
                FROM posts p 
                JOIN users u ON p.user_id = u.user_id 
                LEFT JOIN post_media pm ON p.post_id = pm.post_id
                WHERE p.user_id = ? AND p.type = 'padrao' 
                GROUP BY p.post_id
                ORDER BY p.created_at DESC";
        $params = [$current_user_id, $current_user_id, $current_user_id, $profile_user_id];
        break;
}

if (!empty($sql)) {
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<link rel="stylesheet" href="../src/assets/css/profile.css">

<main class="index-container">
    <?php require_once('../src/components/nav_bar.php'); ?>

    <section class="main_container">
        <div class="main_content" data-pagina="user_profile">

            <div class="profile_cover">
                <?php if (!empty($profile_user['cover_image_url'])): ?>
                    <img src="<?php echo htmlspecialchars($profile_user['cover_image_url']); ?>" alt="Capa" class="cover_image">
                <?php endif; ?>
            </div>

            <div class="profile_header_container">
                <div class="profile_header">
                    <div class="profile_image_wrapper">
                        <img src="<?php echo htmlspecialchars($profile_user['profile_image_url'] ?? '../src/assets/img/default-user.png'); ?>" alt="Foto de Perfil" class="profile_image">
                    </div>

                    <div class="profile_details">
                        <div class="profile_info">
                            <div class="name_and_actions">
                                <h1 class="profile_name"><?php echo htmlspecialchars($profile_user['full_name']); ?></h1>

                                <div class="profile_options">
                                    <?php if ($is_own_profile): ?>
                                        <button class="btn-edit-profile" id="openEditProfileModal">
                                            <i class="ri-pencil-line"></i> Editar perfil
                                        </button>
                                    <?php else: ?>
                                        <button class="btn-follow">Seguir</button>
                                        <button class="btn-message"><i class="ri-chat-3-line"></i></button>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <span class="profile_username">@<?php echo strtolower(explode(' ', $profile_user['full_name'])[0]); ?></span>

                            <div class="profile_stats">
                                <span><strong><?php echo $total_posts; ?></strong> publicações</span>
                                <span><strong><?php echo $followers_count; ?></strong> seguidores</span>
                                <span><strong>0</strong> seguindo</span>
                            </div>

                            <?php if (!empty($profile_user['bio'])): ?>
                                <div class="user_bio">
                                    <p><?php echo nl2br(htmlspecialchars($profile_user['bio'])); ?></p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <nav class="profile_tabs">
                    <a href="?id=<?php echo $profile_user_id; ?>&tab=posts" class="tab-link <?php echo ($current_tab == 'posts') ? 'active' : ''; ?>">
                        <i class="ri-grid-fill"></i> <span>PUBLICAÇÕES</span>
                    </a>
                    <a href="?id=<?php echo $profile_user_id; ?>&tab=reposts" class="tab-link <?php echo ($current_tab == 'reposts') ? 'active' : ''; ?>">
                        <i class="ri-repeat-line"></i> <span>REPOSTS</span>
                    </a>

                    <?php if ($is_own_profile): ?>
                        <a href="?id=<?php echo $profile_user_id; ?>&tab=likes" class="tab-link <?php echo ($current_tab == 'likes') ? 'active' : ''; ?>">
                            <i class="ri-heart-line"></i> <span>CURTIDOS</span>
                        </a>
                        <a href="?id=<?php echo $profile_user_id; ?>&tab=saved" class="tab-link <?php echo ($current_tab == 'saved') ? 'active' : ''; ?>">
                            <i class="ri-bookmark-line"></i> <span>SALVOS</span>
                        </a>
                    <?php endif; ?>
                </nav>
            </div>

            <div class="profile_content">
                <div class="posts_grid_layout">
                    <?php if (count($posts) > 0): ?>
                        <?php foreach ($posts as $post):
                            $user_has_liked = isset($post['user_has_liked']) && $post['user_has_liked'] > 0;
                        ?>
                            <div class="post_container" data-post-url="post_view.php?id=<?php echo $post['post_id']; ?>">
                                <header class="post_header">
                                    <div class="user_info" data-user-id="<?php echo $post['user_id']; ?>">
                                        <div class="user_icon">
                                            <img src="<?php echo htmlspecialchars($post['profile_image_url']); ?>" alt="Foto">
                                        </div>
                                        <div class="user-details">
                                            <span class="user-name"><?php echo htmlspecialchars($post['full_name']); ?></span>
                                            <span class="user-tag">@<?php echo strtolower(explode(' ', $post['full_name'])[0]); ?></span>
                                        </div>
                                    </div>
                                    <div class="post-info">
                                        <div class="post-date"><?php echo date("d/m/Y", strtotime($post['created_at'])); ?></div>
                                    </div>
                                </header>
                                <section class="post_main">
                                    <p class="post-text"><?php echo nl2br(htmlspecialchars($post['content'])); ?></p>

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
                                        <?php $likedClass = ($user_has_liked) ? 'liked' : ''; ?>
                                        <?php $heartIcon = ($user_has_liked) ? 'ri-heart-fill' : 'ri-heart-line'; ?>
                                        <button class="post-btn like-btn <?php echo $likedClass; ?>" data-post-id="<?php echo $post['post_id']; ?>">
                                            <i class="<?php echo $heartIcon; ?>"></i>
                                            <span class="post-cont"><?php echo $post['like_count']; ?></span>
                                        </button>
                                        <div class="post-btn">
                                            <i class="ri-chat-3-line"></i>
                                            <span class="post-cont"><?php echo $post['reply_count']; ?></span>
                                        </div>
                                    </div>
                                    <div class="post-stats-right">
                                        <?php $repostClass = ($post['user_has_reposted'] > 0) ? 'reposted' : ''; ?>
                                        <button class="post-btn repost-btn <?php echo $repostClass; ?>" data-post-id="<?php echo $post['post_id']; ?>">
                                            <i class="ri-repeat-line"></i>
                                            <span class="post-cont"><?php echo $post['repost_count']; ?></span>
                                        </button>
                                        <?php $saveClass = ($post['user_has_bookmarked'] > 0) ? 'bookmarked' : ''; ?>
                                        <?php $saveIcon = ($post['user_has_bookmarked'] > 0) ? 'ri-bookmark-fill' : 'ri-bookmark-line'; ?>
                                        <button class="post-btn bookmark-btn <?php echo $saveClass; ?>" data-post-id="<?php echo $post['post_id']; ?>">
                                            <i class="<?php echo $saveIcon; ?>"></i>
                                        </button>
                                        <button class="post-btn report-btn" data-post-id="<?php echo $post['post_id']; ?>" title="Denunciar">
                                            <i class="ri-alarm-warning-line"></i>
                                        </button>
                                    </div>
                                </footer>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="no-posts-state">
                            <div class="icon-placeholder"><i class="ri-camera-off-line"></i></div>
                            <h3>Ainda não há publicações</h3>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </section>
</main>

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

<?php if ($is_own_profile): ?>
    <div class="edit-profile-modal-overlay" id="editProfileModal">
        <div class="edit-profile-content">
            <div class="modal-header">
                <h2>Editar Perfil</h2>
                <i class="ri-close-line close-edit-modal" id="closeEditProfile"></i>
            </div>
            <form action="perfil.php" method="POST" enctype="multipart/form-data" class="edit-profile-form">
                <input type="hidden" name="action" value="update_profile">

                <div class="images-upload-section">
                    <div class="form-group">
                        <label>Foto de Capa</label>
                        <input type="file" name="cover_image" accept="image/*" class="form-input">
                    </div>
                    <div class="form-group">
                        <label>Foto de Perfil</label>
                        <input type="file" name="profile_image" accept="image/*" class="form-input">
                    </div>
                </div>

                <div class="form-group">
                    <label>Nome Completo</label>
                    <input type="text" name="full_name" class="form-input" value="<?php echo htmlspecialchars($profile_user['full_name']); ?>" required>
                </div>

                <div class="form-group">
                    <label>Biografia</label>
                    <textarea name="bio" class="form-textarea" rows="3"><?php echo htmlspecialchars($profile_user['bio'] ?? ''); ?></textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Estado (UF)</label>
                        <select class="form-select" id="uf" name="state">
                            <option value="" disabled <?php echo empty($profile_user['state']) ? 'selected' : ''; ?>>Selecione seu Estado</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Campus</label>
                        <select class="form-select" id="campus" name="campus">
                            <option value="" disabled <?php echo empty($profile_user['campus']) ? 'selected' : ''; ?>>Selecione seu Campus</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Sexo Biológico</label>
                        <select class="form-select" name="gender">
                            <option value="" disabled>Selecione</option>
                            <option value="M" <?php echo ($profile_user['gender'] == 'M') ? 'selected' : ''; ?>>Masculino</option>
                            <option value="F" <?php echo ($profile_user['gender'] == 'F') ? 'selected' : ''; ?>>Feminino</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Orientação Sexual</label>
                        <select class="form-select" name="sexual_orientation">
                            <option value="" disabled>Selecione</option>
                            <option value="hetero" <?php echo ($profile_user['sexual_orientation'] == 'hetero') ? 'selected' : ''; ?>>Heterossexual</option>
                            <option value="homo" <?php echo ($profile_user['sexual_orientation'] == 'homo') ? 'selected' : ''; ?>>Homossexual</option>
                            <option value="bissex" <?php echo ($profile_user['sexual_orientation'] == 'bissex') ? 'selected' : ''; ?>>Bissexual</option>
                            <option value="assex" <?php echo ($profile_user['sexual_orientation'] == 'assex') ? 'selected' : ''; ?>>Assexual</option>
                            <option value="pansex" <?php echo ($profile_user['sexual_orientation'] == 'pansex') ? 'selected' : ''; ?>>Pansexual</option>
                            <option value="queer" <?php echo ($profile_user['sexual_orientation'] == 'queer') ? 'selected' : ''; ?>>Queer</option>
                            <option value="outro" <?php echo ($profile_user['sexual_orientation'] == 'outro') ? 'selected' : ''; ?>>Outro</option>
                        </select>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-save-profile">Salvar Alterações</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Controle do Modal
        const editModal = document.getElementById('editProfileModal');
        const openBtn = document.getElementById('openEditProfileModal');
        const closeBtn = document.getElementById('closeEditProfile');

        if (openBtn && editModal) {
            openBtn.addEventListener('click', () => {
                editModal.classList.add('active');
                document.body.style.overflow = 'hidden';
            });
        }

        if (closeBtn && editModal) {
            closeBtn.addEventListener('click', () => {
                editModal.classList.remove('active');
                document.body.style.overflow = 'auto';
            });
        }

        if (editModal) {
            editModal.addEventListener('click', (e) => {
                if (e.target === editModal) {
                    editModal.classList.remove('active');
                    document.body.style.overflow = 'auto';
                }
            });
        }

        // Lógica de Estados e Campus (IDÊNTICA AO LOGIN)
        document.addEventListener('DOMContentLoaded', function() {
            const estadoSelect = document.getElementById('uf');
            const campusSelect = document.getElementById('campus');

            // Dados dos Campi (Copiado do login.php)
            const campiPorEstado = {
                "AC": ["IFAC Campus Rio Branco", "IFAC Campus Cruzeiro do Sul", "IFAC Campus Sena Madureira", "IFAC Campus Tarauacá", "IFAC Campus Xapuri"],
                "AL": ["IFAL Campus Maceió", "IFAL Campus Arapiraca", "IFAL Campus Marechal Deodoro", "IFAL Campus Palmeira dos Índios", "IFAL Campus Satuba", "IFAL Campus Santana do Ipanema", "IFAL Campus Penedo", "IFAL Campus Murici", "IFAL Campus Batalha"],
                "AP": ["IFAP Campus Macapá", "IFAP Campus Santana", "IFAP Campus Laranjal do Jari", "IFAP Campus Oiapoque"],
                "AM": ["IFAM Campus Manaus Centro", "IFAM Campus Manaus Zona Leste", "IFAM Campus Manaus Industrial", "IFAM Campus Tabatinga", "IFAM Campus Coari", "IFAM Campus Maués", "IFAM Campus Parintins", "IFAM Campus Tefé", "IFAM Campus Borba", "IFAM Campus Eirunepé", "IFAM Campus Humaitá", "IFAM Campus Lábrea", "IFAM Campus Presidente Figueiredo", "IFAM Campus São Gabriel da Cachoeira"],
                "BA": ["IFBA Campus Salvador", "IFBA Campus Barreiras", "IFBA Campus Eunápolis", "IFBA Campus Feira de Santana", "IFBA Campus Ilhéus", "IFBA Campus Irecê", "IFBA Campus Itabuna", "IFBA Campus Jequié", "IFBA Campus Paulo Afonso", "IFBA Campus Santo Antônio de Jesus", "IFBA Campus Seabra", "IFBA Campus Valença", "IFBA Campus Vitória da Conquista", "IFBA Campus Alagoinhas", "IFBA Campus Jacobina", "IFBA Campus Simões Filho", "IFBA Campus Teixeira de Freitas", "IFBA Campus Guanambi", "IFBA Campus Brumado", "IFBA Campus Euclides da Cunha"],
                "CE": ["IFCE Campus Fortaleza", "IFCE Campus Acaraú", "IFCE Campus Aracati", "IFCE Campus Canindé", "IFCE Campus Cedro", "IFCE Campus Crateús", "IFCE Campus Crato", "IFCE Campus Iguatu", "IFCE Campus Itapipoca", "IFCE Campus Jaguaribe", "IFCE Campus Juazeiro do Norte", "IFCE Campus Limoeiro do Norte", "IFCE Campus Maracanaú", "IFCE Campus Morada Nova", "IFCE Campus Pacajus", "IFCE Campus Quixadá", "IFCE Campus Sobral", "IFCE Campus Tauá", "IFCE Campus Tianguá", "IFCE Campus Ubajara", "IFCE Campus Umirim", "IFCE Campus Camocim", "IFCE Campus Barbalha", "IFCE Campus Paracuru"],
                "DF": ["IFB Campus Brasília", "IFB Campus Ceilândia", "IFB Campus Estrutural", "IFB Campus Gama", "IFB Campus Itapoã", "IFB Campus Planaltina", "IFB Campus Riacho Fundo", "IFB Campus Samambaia", "IFB Campus São Sebastião", "IFB Campus Taguatinga"],
                "ES": ["IFES Campus Vitória", "IFES Campus Alegre", "IFES Campus Aracruz", "IFES Campus Cachoeiro de Itapemirim", "IFES Campus Cariacica", "IFES Campus Colatina", "IFES Campus Guarapari", "IFES Campus Ibatiba", "IFES Campus Linhares", "IFES Campus Montanha", "IFES Campus Nova Venécia", "IFES Campus Piúma", "IFES Campus Santa Teresa", "IFES Campus São Mateus", "IFES Campus Serra", "IFES Campus Venda Nova do Imigrante", "IFES Campus Vila Velha"],
                "GO": ["IFG Campus Goiânia", "IFG Campus Aparecida de Goiânia", "IFG Campus Catalão", "IFG Campus Formosa", "IFG Campus Inhumas", "IFG Campus Itumbiara", "IFG Campus Jataí", "IFG Campus Luziânia", "IFG Campus Rio Verde", "IFG Campus Senador Canedo", "IFG Campus Uruaçu", "IFG Campus Águas Lindas de Goiás", "IFG Campus Cidade de Goiás", "IFG Campus Goianésia", "IFG Campus Iporá"],
                "MA": ["IFMA Campus São Luís - Monte Castelo", "IFMA Campus Açailândia", "IFMA Campus Alcântara", "IFMA Campus Barra do Corda", "IFMA Campus Barão de Grajaú", "IFMA Campus Carolina", "IFMA Campus Caxias", "IFMA Campus Codó", "IFMA Campus Coelho Neto", "IFMA Campus Coroatá", "IFMA Campus Grajaú", "IFMA Campus Humberto de Campos", "IFMA Campus Imperatriz", "IFMA Campus Itapecuru-Mirim", "IFMA Campus João Lisboa", "IFMA Campus Pedreiras", "IFMA Campus Pinheiro", "IFMA Campus Presidente Dutra", "IFMA Campus Santa Inês", "IFMA Campus São João dos Patos", "IFMA Campus São Raimundo das Mangabeiras", "IFMA Campus Timon", "IFMA Campus Viana", "IFMA Campus Zé Doca"],
                "MT": ["IFMT Campus Cuiabá", "IFMT Campus Alta Floresta", "IFMT Campus Barra do Garças", "IFMT Campus Cáceres", "IFMT Campus Campo Novo do Parecis", "IFMT Campus Confresa", "IFMT Campus Juína", "IFMT Campus Lucas do Rio Verde", "IFMT Campus Pontes e Lacerda", "IFMT Campus Primavera do Leste", "IFMT Campus Rondonópolis", "IFMT Campus São Vicente", "IFMT Campus Sorriso", "IFMT Campus Tangará da Serra"],
                "MS": ["IFMS Campus Campo Grande", "IFMS Campus Aquidauana", "IFMS Campus Corumbá", "IFMS Campus Coxim", "IFMS Campus Dourados", "IFMS Campus Jardim", "IFMS Campus Naviraí", "IFMS Campus Nova Andradina", "IFMS Campus Ponta Porã", "IFMS Campus Três Lagoas"],
                "MG": ["IFMG Campus Bambuí", "IFMG Campus Betim", "IFMG Campus Congonhas", "IFMG Campus Conselheiro Lafaiete", "IFMG Campus Formiga", "IFMG Campus Governador Valadares", "IFMG Campus Itabirito", "IFMG Campus Itaúna", "IFMG Campus João Monlevade", "IFMG Campus Mariana", "IFMG Campus Ouro Branco", "IFMG Campus Ouro Preto", "IFMG Campus Piumhi", "IFMG Campus Ponte Nova", "IFMG Campus Reitoria", "IFMG Campus Sabará", "IFMG Campus Salinas", "IFMG Campus São João Evangelista", "IFMG Campus Teófilo Otoni", "IFMG Campus Três Corações"],
                "PA": ["IFPA Campus Belém", "IFPA Campus Abaetetuba", "IFPA Campus Altamira", "IFPA Campus Ananindeua", "IFPA Campus Bragança", "IFPA Campus Breves", "IFPA Campus Cametá", "IFPA Campus Canaã dos Carajás", "IFPA Campus Castanhal", "IFPA Campus Conceição do Araguaia", "IFPA Campus Itaituba", "IFPA Campus Marabá", "IFPA Campus Marituba", "IFPA Campus Óbidos", "IFPA Campus Paragominas", "IFPA Campus Parauapebas", "IFPA Campus Santarém", "IFPA Campus Tucuruí"],
                "PB": ["IFPB Campus João Pessoa", "IFPB Campus Cabedelo", "IFPB Campus Cajazeiras", "IFPB Campus Campina Grande", "IFPB Campus Guarabira", "IFPB Campus Itabaiana", "IFPB Campus Itaporanga", "IFPB Campus Jacareí", "IFPB Campus Mangabeira", "IFPB Campus Monteiro", "IFPB Campus Patos", "IFPB Campus Picuí", "IFPB Campus Princesa Isabel", "IFPB Campus Santa Rita", "IFPB Campus Soledade", "IFPB Campus Cabedelo - Centro", "IFPB Campus Areia", "IFPB Campus Pedras de Fogo", "IFPB Campus Esperança", "IFPB Campus Catolé do Rocha", "IFPB Campus Santa Luzia", "IFPB Campus Sousa - Unidade São Gonçalo", "IFPB Campus Sousa - Unidade Sede"],
                "PR": ["IFPR Campus Curitiba", "IFPR Campus Assis Chateaubriand", "IFPR Campus Astorga", "IFPR Campus Barracão", "IFPR Campus Campo Largo", "IFPR Campus Capanema", "IFPR Campus Cascavel", "IFPR Campus Colombo", "IFPR Campus Coronel Vivida", "IFPR Campus Foz do Iguaçu", "IFPR Campus Goioerê", "IFPR Campus Ibaiti", "IFPR Campus Irati", "IFPR Campus Ivaiporã", "IFPR Campus Jacarezinho", "IFPR Campus Jaguariaíva", "IFPR Campus Londrina", "IFPR Campus Irati", "IFPR Campus Ivaiporã", "IFPR Campus Jacarezinho", "IFPR Campus Jaguariaíva", "IFPR Campus Londrina", "IFPR Campus Marechal Cândido Rondon", "IFPR Campus Maringá", "IFPR Campus Palmas", "IFPR Campus Paranaguá", "IFPR Campus Paranavaí", "IFPR Campus Pinhais", "IFPR Campus Pitanga", "IFPR Campus Quedas do Iguaçu", "IFPR Campus Rio Negro", "IFPR Campus Telêmaco Borba", "IFPR Campus Toledo", "IFPR Campus Umuarama", "IFPR Campus União da Vitória", "IFPR Campus Arapongas", "IFPR Campus Ponta Grossa", "IFPR Campus Pitanga"],
                "PE": ["IFPE Campus Recife", "IFPE Campus Abreu e Lima", "IFPE Campus Afogados da Ingazeira", "IFPE Campus Barreiros", "IFPE Campus Belo Jardim", "IFPE Campus Cabo de Santo Agostinho", "IFPE Campus Caruaru", "IFPE Campus Garanhuns", "IFPE Campus Igarassu", "IFPE Campus Ipojuca", "IFPE Campus Jaboatão dos Guararapes", "IFPE Campus Limoeiro", "IFPE Campus Olinda", "IFPE Campus Palmares", "IFPE Campus Paulista", "IFPE Campus Pesqueira", "IFPE Campus Petrolina", "IFPE Campus Petrolina Zona Rural", "IFPE Campus Salgueiro", "IFPE Campus Santa Cruz do Capibaribe", "IFPE Campus Vitória de Santo Antão"],
                "PI": ["IFPI Campus Teresina Central", "IFPI Campus Angical", "IFPI Campus Campo Maior", "IFPI Campus Cocal", "IFPI Campus Corrente", "IFPI Campus Floriano", "IFPI Campus Oeiras", "IFPI Campus Parnaíba", "IFPI Campus Paulistana", "IFPI Campus Picos", "IFPI Campus Piripiri", "IFPI Campus São João do Piauí", "IFPI Campus São Raimundo Nonato", "IFPI Campus Uruçuí", "IFPI Campus Valença do Piauí", "IFPI Campus Dirceu Arcoverde", "IFPI Campus José de Freitas", "IFPI Campus Pio IX"],
                "RJ": ["IFRJ Campus Rio de Janeiro", "IFRJ Campus Arraial do Cabo", "IFRJ Campus Belford Roxo", "IFRJ Campus Duque de Caxias", "IFRJ Campus Engenheiro Paulo de Frontin", "IFRJ Campus Itaboraí", "IFRJ Campus Nilópolis", "IFRJ Campus Niterói", "IFRJ Campus Paracambi", "IFRJ Campus Pinheiral", "IFRJ Campus Realengo", "IFRJ Campus Resende", "IFRJ Campus Rio Bonito", "IFRJ Campus São Gonçalo", "IFRJ Campus Volta Redonda"],
                "RN": ["IFRN Campus Natal-Central", "IFRN Campus Apodi", "IFRN Campus Caicó", "IFRN Campus Canguaretama", "IFRN Campus Currais Novos", "IFRN Campus Ipanguaçu", "IFRN Campus João Câmara", "IFRN Campus Jucurutu", "IFRN Campus Macau", "IFRN Campus Mossoró", "IFRN Campus Nova Cruz", "IFRN Campus Parelhas", "IFRN Campus Pau dos Ferros", "IFRN Campus Santa Cruz", "IFRN Campus São Gonçalo do Amarante", "IFRN Campus Ceará-Mirim", "IFRN Campus Extremoz", "IFRN Campus Guamaré", "IFRN Campus Lajes", "IFRN Campus Parnamirim", "IFRN Campus Natal - Cidade Alta", "IFRN Campus Natal - Zona Leste", "IFRN Campus Natal - Zona Norte", "IFRN Campus São Paulo do Potengi"],
                "RS": ["IFRS Campus Bento Gonçalves", "IFRS Campus Canoas", "IFRS Campus Caxias do Sul", "IFRS Campus Erechim", "IFRS Campus Farroupilha", "IFRS Campus Feliz", "IFRS Campus Ibirubá", "IFRS Campus Osório", "IFRS Campus Passo Fundo", "IFRS Campus Porto Alegre", "IFRS Campus Restinga", "IFRS Campus Rio Grande", "IFRS Campus Rolante", "IFRS Campus Sertão", "IFRS Campus Vacaria", "IFRS Campus Veranópolis", "IF Farroupilha Campus Alegrete", "IF Farroupilha Campus Frederico Westphalen", "IF Farroupilha Campus Jaguari", "IF Farroupilha Campus Júlio de Castilhos", "IF Farroupilha Campus Panambi", "IF Farroupilha Campus Santa Rosa", "IF Farroupilha Campus Santo Ângelo", "IF Farroupilha Campus Santo Augusto", "IF Farroupilha Unidade São Borja"],
                "RO": ["IFRO Campus Porto Velho", "IFRO Campus Ariquemes", "IFRO Campus Cacoal", "IFRO Campus Colorado do Oeste", "IFRO Campus Guajará-Mirim", "IFRO Campus Ji-Paraná", "IFRO Campus Jaru", "IFRO Campus Presidente Médici", "IFRO Campus Vilhena", "IFRO Campus Porto Velho Zona Norte", "IFRO Campus Porto Velho Calama", "IFRO Campus Avançado São Miguel do Guaporé"],
                "RR": ["IFRR Campus Boa Vista", "IFRR Campus Novo Paraíso"],
                "SC": ["IFSC Campus Florianópolis", "IFSC Campus Araranguá", "IFSC Campus Canoinhas", "IFSC Campus Chapecó", "IFSC Campus Criciúma", "IFSC Campus Gaspar", "IFSC Campus Itajaí", "IFSC Campus Jaraguá do Sul", "IFSC Campus Joinville", "IFSC Campus Lages", "IFSC Campus Mafra", "IFSC Campus Palhoça", "IFSC Campus São Carlos", "IFSC Campus São Miguel do Oeste", "IFSC Campus Tubarão", "IFSC Campus Urupema", "IFSC Campus Xanxerê"],
                "SE": ["IFS Campus Aracaju", "IFS Campus Lagarto", "IFS Campus Itabaiana", "IFS Campus Glória", "IFS Campus Propriá", "IFS Campus São Cristóvão"],
                "SP": ["IFSP Campus Araraquara", "IFSP Campus Avaré", "IFSP Campus Barretos", "IFSP Campus Bauru", "IFSP Campus Birigui", "IFSP Campus Boituva", "IFSP Campus Bragança Paulista", "IFSP Campus Campinas", "IFSP Campus Campos do Jordão", "IFSP Campus Capivari", "IFSP Campus Caraguatatuba", "IFSP Campus Catanduva", "IFSP Campus Cubatão", "IFSP Campus Guarulhos", "IFSP Campus Hortolândia", "IFSP Campus Ilha Solteira", "IFSP Campus Itapetininga", "IFSP Campus Itaquaquecetuba", "IFSP Campus Jacareí", "IFSP Campus Jundiaí", "IFSP Campus Matão", "IFSP Campus Miracatu", "IFSP Campus Piracicaba", "IFSP Campus Pirituba", "IFSP Campus Presidente Epitácio", "IFSP Campus Presidente Prudente", "IFSP Campus Registro", "IFSP Campus Rio Claro", "IFSP Campus Salto", "IFSP Campus São Carlos", "IFSP Campus São João da Boa Vista", "IFSP Campus São José do Rio Preto", "IFSP Campus São José dos Campos", "IFSP Campus São Miguel Paulista", "IFSP Campus São Paulo", "IFSP Campus São Roque", "IFSP Campus Sertãozinho", "IFSP Campus Sorocaba", "IFSP Campus Suzano", "IFSP Campus Tupã", "IFSP Campus Votuporanga"],
                "TO": ["IFTO Campus Palmas", "IFTO Campus Araguaína", "IFTO Campus Araguatins", "IFTO Campus Colinas do Tocantins", "IFTO Campus Dianópolis", "IFTO Campus Gurupi", "IFTO Campus Itaporã do Tocantins", "IFTO Campus Miracema do Tocantins", "IFTO Campus Paraíso do Tocantins", "IFTO Campus Porto Nacional", "IFTO Campus Tocantinópolis"]
            };

            // Preenche o Select de Estados
            for (let estado in campiPorEstado) {
                let option = document.createElement('option');
                option.value = estado;
                option.text = estado;
                if (estado === "<?php echo $profile_user['state']; ?>") {
                    option.selected = true;
                }
                estadoSelect.appendChild(option);
            }

            // Função para atualizar os campi
            function atualizarCampi() {
                const estado = estadoSelect.value;
                campusSelect.innerHTML = '<option value="" disabled selected>Selecione seu Campus</option>';
                campusSelect.disabled = true;

                if (estado && campiPorEstado[estado]) {
                    campiPorEstado[estado].forEach(campus => {
                        let option = document.createElement('option');
                        option.value = campus;
                        // Remove o prefixo para ficar mais limpo
                        const parts = campus.split('Campus ');
                        option.text = parts.length > 1 ? parts[1] : campus;

                        if (campus === "<?php echo $profile_user['campus']; ?>") {
                            option.selected = true;
                        }
                        campusSelect.appendChild(option);
                    });
                    campusSelect.disabled = false;
                }
            }

            // Evento de mudança
            estadoSelect.addEventListener('change', atualizarCampi);

            // Inicializa se já tiver estado salvo
            if ("<?php echo $profile_user['state']; ?>") {
                atualizarCampi();
            }
        });
    </script>
<?php endif; ?>

<?php require_once(__DIR__ . '/../src/components/modal_postagem_html.php'); ?>
<?php require_once '../src/components/footer.php'; ?>