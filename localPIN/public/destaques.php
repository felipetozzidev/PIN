
<body>
    <main class="index-container">
        <?php require_once('../src/components/nav_bar.php'); ?>


        <section class="index-container main_container">
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
                            <a href="post_view.php?id=<?php echo $post['post_id']; ?>"
                                class="cardDestaque <?php echo $card_class; ?>" <?php echo $bg_style; ?>>
                                <div class="card-overlay-content">
                                    <p class="card-content-text">
                                        <?php echo htmlspecialchars(mb_strimwidth($post['content'], 0, 100, "...")); ?></p>
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
        <?php require_once(__DIR__ . '/../src/components/footer.php'); ?>
        </div>


    </main>
</body>