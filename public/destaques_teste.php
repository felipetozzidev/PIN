<?php
$currentPage = 'destaques';
// Inclui a conexão e o cabeçalho padrão da página
require_once('../config/conn.php');
require_once('../src/components/header.php');

// Busca os 10 posts mais populares, priorizando views e depois likes.
$sql = "SELECT 
            p.post_id, 
            p.content, 
            p.view_count, 
            p.like_count,
            (SELECT pm.media_url FROM post_media pm WHERE pm.post_id = p.post_id ORDER BY pm.media_id ASC LIMIT 1) as post_image
        FROM posts p
        ORDER BY p.view_count DESC, p.like_count DESC
        LIMIT 10";

$stmt = $pdo->query($sql);
$popular_posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Função para determinar a classe de tamanho do card
function getCardClass($index)
{
    if ($index < 2) { // Os 2 posts mais populares
        return 'card-grande';
    } elseif ($index < 5) { // Do 3º ao 5º
        return 'card-medio';
    } else { // O restante
        return 'card-pequeno';
    }
}
?>
<body>
    <main class="index-container">
        <?php include_once("../src/components/nav_bar.php"); ?>
        <section class="main_container">
            <div class="main_content">
                <div class="destaques">
                    <h1 class="title">Destaques da Comunidade</h1>
                    <div class="cardDestaque_container">
                        <?php foreach ($popular_posts as $key => $post):?>
    
                            <a class="cardDestaque <?php echo getCardClass($key); ?>">
                                <div class="cardDestaque_image">
                                    <img src="<?php echo $post['post_image']; ?>" alt="Imagem do Post">
                                </div>
                                <div class="cardDestaque_content">
                                    <p class="cardDestaque_content_text">
                                        <?php echo $post['content']; ?>
                                    </p>
                                </div>
                            </a>
    
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <?php include_once("../src/components/footer.php"); ?>
</body>
</html>