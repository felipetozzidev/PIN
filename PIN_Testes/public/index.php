<?php
include("../src/components/header.php");

// Pega o ID do usuário logado, ou 0 se for um visitante
$current_user_id = isset($_SESSION['id_usu']) ? $_SESSION['id_usu'] : 0;

// Lógica 
$search_query = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';

$sql = "SELECT 
            p.id_post, p.conteudo_post, p.data_post, p.stats_post, p.tipo_post, 
            p.id_post_pai, p.aviso_conteudo,
            p.cont_likes, p.cont_respostas, p.cont_reposts, p.cont_citacoes,
            u.nome_usu, u.imgperfil_usu,
            c.nome_com,
            GROUP_CONCAT(DISTINCT t.nome_tag SEPARATOR ', ') as tags,
            (SELECT GROUP_CONCAT(pm.url_media SEPARATOR ';') FROM post_media pm WHERE pm.id_post = p.id_post) as media_urls
        FROM posts p 
        LEFT JOIN usuarios u ON p.id_usu = u.id_usu
        LEFT JOIN comunidades_posts cp ON p.id_post = cp.id_post
        LEFT JOIN comunidades c ON cp.id_com = c.id_com
        LEFT JOIN posts_tags pt ON p.id_post = pt.id_post
        LEFT JOIN tags t ON pt.id_tag = t.id_tag
        ";

$where_clauses = [];
if (!empty($search_query)) {
    $where_clauses[] = "(p.conteudo_post LIKE '%$search_query%' OR u.nome_usu LIKE '%$search_query%' OR c.nome_com LIKE '%$search_query%' OR t.nome_tag LIKE '%$search_query%')";
}

if (!empty($where_clauses)) {
    $sql .= " WHERE " . implode(' AND ', $where_clauses);
}

$sql .= " GROUP BY p.id_post ORDER BY p.id_post DESC";
$resultado = $conn->query($sql);

// SQL APRIMORADO: Adiciona u.id_usu para o link do perfil
$sql_posts = "SELECT 
                p.id_post,
                p.conteudo_post,
                p.data_post,
                p.cont_visualizacoes,
                p.cont_likes,
                p.cont_respostas,
                p.cont_reposts,
                p.cont_citacoes,
                u.id_usu,
                u.nome_usu,
                u.imgperfil_usu,
                GROUP_CONCAT(pm.url_media SEPARATOR ';') as media_urls,
                (SELECT COUNT(*) FROM likes l WHERE l.id_post = p.id_post AND l.id_usu = ?) AS user_has_liked
            FROM posts AS p
            JOIN usuarios AS u ON p.id_usu = u.id_usu
            LEFT JOIN post_media AS pm ON p.id_post = pm.id_post
            WHERE p.tipo_post = 'padrao' AND p.stats_post = 'ativo'
            GROUP BY p.id_post
            ORDER BY p.data_post DESC
            LIMIT 20";

// Usa prepared statements para segurança
$stmt_posts = $conn->prepare($sql_posts);
$stmt_posts->bind_param("i", $current_user_id);
$stmt_posts->execute();
$result_posts = $stmt_posts->get_result();


// Busca as 5 comunidades mais populares (com mais membros)
$sql_comunidades = "SELECT 
                        c.nome_com,
                        COUNT(uc.id_usu) as total_membros
                    FROM comunidades c
                    LEFT JOIN usuarios_comunidades uc ON c.id_com = uc.id_com
                    GROUP BY c.id_com
                    ORDER BY total_membros DESC
                    LIMIT 5";

$result_comunidades = $conn->query($sql_comunidades);
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IFApoia - Início</title>
</head>

<body data-is-logged-in="<?php echo ($current_user_id > 0) ? 'true' : 'false'; ?>">

    <main class="index-container">
        <!-- Coluna 1: Navbar Lateral -->
        <?php include("../src/components/nav_bar.php"); ?>

        <!-- Coluna 2: Conteúdo Principal -->
        <section class="main_container">
            <div class="main_content" data-pagina="pagina principal">
                <div class="destaques">
                    <h1 class="title">Destaques</h1>
                    <div class="cards">
                        <span>Destaques</span>
                    </div>
                </div>
                <div class="postagens_e_publicacoes">
                    <div class="postagens">
                        <h1 class="title">Postagens</h1>
                        <?php if ($resultado && $resultado->num_rows > 0): ?>
                            <?php while ($post = $resultado->fetch_assoc()):
                                $user_has_liked = $post['user_has_liked'] > 0; ?>
                                <div class="post_container" data-post-url="post_view.php?id_post=<?php echo $post['id_post']; ?>">
                                    <header class="post_header">
                                        <div class="user_info" data-user-id="<?php echo $post['id_usu']; ?>">
                                            <div class="user_icon">
                                                <img src="<?php echo htmlspecialchars($post['imgperfil_usu']); ?>"
                                                    alt="Foto de Perfil">
                                            </div>
                                            <div class="user-details">
                                                <span
                                                    class="user-name"><a href="perfil.php?id=<?php echo $post['id_usu']; ?>"><?php echo htmlspecialchars($post['nome_usu']); ?></a></span><br>
                                                <span
                                                    class="user-tag">@<?php echo strtolower(explode(' ', $post['nome_usu'])[0]); ?></span>
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
                                    <section class="post_main">
                                        <p><?php echo nl2br(htmlspecialchars($post['conteudo_post'])); ?></p>
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
                                            <button class="post-icon like-btn <?php echo $user_has_liked ? 'liked' : ''; ?>"
                                                data-post-id="<?php echo $post['id_post']; ?>">
                                                <i class="ri-heart-<?php echo $user_has_liked ? 'fill' : 'line'; ?>"></i>
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
                            <?php endwhile; ?>
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
                            <?php if ($result_comunidades && $result_comunidades->num_rows > 0): ?>
                                <?php while ($comunidade = $result_comunidades->fetch_assoc()): ?>
                                    <div class="community_card">
                                        <div class="community_icon"><i class="ri-group-line"></i></div>
                                        <p>
                                            <span
                                                class="community_name"><?php echo htmlspecialchars($comunidade['nome_com']); ?></span>
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