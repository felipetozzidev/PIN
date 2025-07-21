<?php include("../src/components/header.php"); ?>

<main>
    <?php include("../src/components/nav_bar.php"); ?>

    <section class="main_container">
        <div class="main_content" data-pagina="comunidades">
            <h1>Comunidades</h1>

            <div class="community_cards_container">
                <?php 
                    for ($i = 0; $i < 50; $i++) { //Para configurar quando chegar no PHP, basta colocar o código de paginação aqui               
                ?>
                <div class="community_card">
                    <div class="container_icon_text">
                        <div class="community_icon">
                            <img src="../src/assets/img/default-user.png" alt="" class="img_card img-fluid">
                        </div>
                        <div class="text_card">
                            <p class="community_name">Comunidade</p>
                            <span class="community_followers">100.000 seguidores</span>
                        </div>
                    </div>
                    <div class="community_subscribe">
                        <a href="" class="button_follow">
                            <p>Seguir</p>
                        </a>
                    </div>
                </div>
                <?php 
                    }
                ?>
            </div>
            <section class="paginacao_container">
                    <div class="paginacao">
                        <?php

                            $ultima_pagina = 50;
                            $pagina_atual = $_GET['pagina'] ?? 1;

                            for ($i = $pagina_atual - 2; $i < $ultima_pagina; $i++) { 
                                if ($i - $pagina_atual < 5 || $ultima_pagina == $i + 1) {
                                    if($ultima_pagina - 1 == $i) {
                                        echo '<span class="paginacao_link">...</span>';
                                    }
                                    if ($pagina_atual == $i) {
                                    echo '<a class="paginacao_link marcado">'.$i.'</a>';
                                    } else{
                                        echo '<a href="?pagina='.$i.'" class="paginacao_link">'.$i.'</a>';
                                    }
                                }
                            }
                        ?>
                    </div>
                </section>
        </div>
    </section>

</main>
    <?php  include("../src/components/footer.php"); ?>