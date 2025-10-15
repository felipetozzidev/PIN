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

<main>
    <?php require_once('../src/components/nav_bar.php'); ?>

    <section class="main_container">
        <div class="main_content" data-pagina="destaques">

            <h1 class="title">Destaques da Comunidade</h1>

            <div class="cardDestaque_container">
                <?php if ($popular_posts): ?>
                    <?php foreach ($popular_posts as $index => $post): ?>
                        <?php
                        $card_class = getCardClass($index);
                        $bg_style = !empty($post['post_image'])
                            ? 'style="background-image: url(' . htmlspecialchars(str_replace('../', './', $post['post_image'])) . ');"'
                            : '';
                        ?>
                        <a href="post_view.php?id=<?php echo $post['post_id']; ?>" class="cardDestaque <?php echo $card_class; ?>" <?php echo $bg_style; ?>>
                            <div class="card-overlay-content">
                                <p class="card-content-text"><?php echo htmlspecialchars(mb_strimwidth($post['content'], 0, 100, "...")); ?></p>
                                <div class="card-stats">
                                    <span><i class="ri-eye-line"></i> <?php echo $post['view_count']; ?></span>
                                    <span><i class="ri-heart-line"></i> <?php echo $post['like_count']; ?></span>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p style="text-align: center; width: 100%;">Ainda não há posts populares para exibir.</p>
                <?php endif; ?>
            </div>

        </div>
    </section>
</main>

<?php require_once('../src/components/footer.php'); ?>