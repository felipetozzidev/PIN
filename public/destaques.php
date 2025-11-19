<?php
$currentPage = 'destaques';
// Inclui a conexão e o cabeçalho padrão da página
require_once('../config/conn.php');
require_once(__DIR__ . '/../src/components/modal_postagem.php');
require_once('../src/components/header.php');

// Busca os 10 posts mais populares, priorizando views e depois likes.
$sql = "SELECT 
            p.post_id, 
            p.content, 
            p.view_count, 
            p.like_count,
            (SELECT pm.media_url FROM post_media pm WHERE pm.post_id = p.post_id ORDER BY pm.media_id ASC LIMIT 1) as post_image
        FROM posts p
        ORDER BY p.like_count DESC, p.view_count DESC
        LIMIT 10";

$stmt = $pdo->query($sql);
$popular_posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Função para determinar a classe de tamanho do card
function getCardClass($index)
{
    // Padrão predefinido para os 5 posts mais populares
    if ($index == 0) return 'card-grande';  // 2 colunas, 1 linha
    if ($index == 1) return 'card-pequeno'; // 1 coluna, 1 linha (completa a primeira fileira)
    if ($index == 2) return 'card-medio';   // 1 coluna, 2 linhas (alto)
    if ($index == 3) return 'card-pequeno'; // 1 coluna, 1 linha
    if ($index == 4) return 'card-pequeno'; // 1 coluna, 1 linha

    // Para os posts restantes, podemos alternar para manter o visual dinâmico
    // Isso é opcional, mas deixa o grid mais interessante
    switch (($index - 5) % 4) {
        case 0:
            return 'card-grande';
        case 1:
        case 2:
        case 3:
            return 'card-pequeno';
        default:
            return 'card-pequeno';
    }
}
?>

<body>
    <main class="index-container" style="margin-top: 88px; margin-bottom: 44px">
        <?php include_once("../src/components/nav_bar.php"); ?>
        <section class="main_container">
            <div class="main_content" data-pagina="destaques">
                <h1 class="title">Destaques da Comunidade</h1>
                <div class="cardDestaque_container">
                    <?php foreach ($popular_posts as $key => $post):
                        // Prepara o estilo do card: imagem de fundo ou cor sólida
                        $style = !empty($post['post_image'])
                            ? 'background-image: url(' . htmlspecialchars($post['post_image']) . ');'
                            : 'background-color: var(--secondary);';
                    ?>

                        <a href="post_view.php?id=<?php echo $post['post_id']; ?>"
                            class="cardDestaque <?php echo getCardClass($key); ?>"
                            style="<?php echo $style; ?>">

                            <div class="card-overlay-content">
                                <p class="card-content-text">
                                    <?php echo htmlspecialchars(mb_strimwidth($post['content'], 0, 100, "...")); ?>
                                </p>
                                <div class="card-stats">
                                    <span><i class="ri-heart-fill"></i> <?php echo $post['like_count']; ?></span>
                                    <span><i class="ri-eye-fill"></i> <?php echo $post['view_count']; ?></span>
                                </div>
                            </div>
                        </a>

                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    </main>

    <?php require_once(__DIR__ . '/../src/components/modal_postagem_html.php'); ?>
    <?php include_once("../src/components/footer.php"); ?>
</body>

</html>