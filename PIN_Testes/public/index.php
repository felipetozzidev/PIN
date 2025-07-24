<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projeto PIN</title>
<<<<<<< HEAD
    <link rel="stylesheet" href="../src/assets/css/style.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
=======
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="stylesheet" href="../src/assets/css/style.css">
>>>>>>> 7fc9a5a528a9cfe8cab2e3603e2c128b08acb1ba
</head>

<body>
    <?php include("../src/components/header.php"); ?>

    <main>
        <?php include("../src/components/nav_bar.php"); ?>

        <section class="main_container">
<<<<<<< HEAD
            <div class="destaques">
                <h1 class="title">Destaques</h1>
                <div class="cards">
                    <span>Destaques</span>
                </div>
            </div>
            <div class="postagens">
                <h1 class="title">Destaques</h1>

            </div>
        </section>
        <section class="publicacoes_recentes">

=======
            <div class="main_content">
                <div class="destaques">
                    <h1 class="title">Destaques</h1>
                    <div class="cards">
                        <span>Destaques</span>
                    </div>
                </div>
                <div class="postagens_e_publicacoes">
                    <div class="postagens">
                        <h1 class="title">Postagens</h1>
                        <div class="post_container">
                            <header class="post_header">
                                <div class="user_icon">
                                    <i class="ri-user-line"></i>
                                </div>
                                <p>
                                    <span class="user_name">NomeDoUsuario</span>
                                    <span class="user_tag">@username</span>
                                </p>
                            </header>
                            <hr class="post_divider">
                            <section class="post_main">
                                <p>
                                    Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum
                                    has been the industry's standard dummy text ever since the 1500s, when an unknown
                                    printer took a galley of type and scrambled it to make a type specimen book. It has
                                    survived not only five centuries, but also the leap into electronic typesetting,
                                    remaining essentially unchanged. It was popularised in the 1960s with the release of
                                    Letraset sheets containing Lorem Ipsum passages, and more recently with desktop
                                    publishing software like Aldus PageMaker including versions of Lorem Ipsum.
                                </p>
                                <div class="row">
                                    <div class="col img_post">
                                        <!-- Colocar verificação no php para que, se houver mais de uma imagem, ser inserido uma estrutura de carrossel -->
                                        <div class="carrossel_post container_img_post">
                                            <img src="..\src\assets\img\ifcampus.jpg" alt="Logo IFSP" class="img-fluid">
                                        </div>
                                    </div>
                                </div>
                            </section>
                            <footer class="post_footer">
    
                            </footer>
                        </div>
                        <div class="post_container">
                            <header class="post_header">
                                <div class="user_icon">
                                    <i class="ri-user-line"></i>
                                </div>
                                <p>
                                    <span class="user_name">NomeDoUsuario</span>
                                    <span class="user_tag">@username</span>
                                </p>
                            </header>
                            <hr class="post_divider">
                            <section class="post_main">
                                <p>
                                    Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum
                                    has been the industry's standard dummy text ever since the 1500s, when an unknown
                                    printer took a galley of type and scrambled it to make a type specimen book. It has
                                    survived not only five centuries, but also the leap into electronic typesetting,
                                    remaining essentially unchanged. It was popularised in the 1960s with the release of
                                    Letraset sheets containing Lorem Ipsum passages, and more recently with desktop
                                    publishing software like Aldus PageMaker including versions of Lorem Ipsum.
                                </p>
                                <div class="row">
                                    <div class="col img_post">
                                        <!-- Colocar verificação no php para que, se houver mais de uma imagem, ser inserido uma estrutura de carrossel -->
                                        <div class="carrossel_post container_img_post">
                                            <img src="..\src\assets\img\ifcampus.jpg" alt="Logo IFSP" class="img-fluid">
                                        </div>
                                    </div>
                                </div>
                            </section>
                            <footer class="post_footer">
    
                            </footer>
                        </div>
                    </div>
                    <div class="comunidades_populares">
                        <h1 class="title">Comunidades Populares</h1>
                        <div class="community_cards_container">
                            <div class="community_card">
                                <div class="community_icon">
                                    <i class="ri-group-line"></i>
                                </div>
                                <p>
                                    <span class="community_name">NomeDaComunidade1</span>
                                    <span class="community_followers">100.000 seguidores</span>
                                </p>
                            </div>
                            <hr class="community_divider">
                            <div class="community_card">
                                <div class="community_icon">
                                    <i class="ri-group-line"></i>
                                </div>
                                <p>
                                    <span class="community_name">NomeDaComunidade1</span>
                                    <span class="community_followers">100.000 seguidores</span>
                                </p>
                            </div>
                            <hr class="community_divider">
                            <div class="community_card">
                                <div class="community_icon">
                                    <i class="ri-group-line"></i>
                                </div>
                                <p>
                                    <span class="community_name">NomeDaComunidade1</span>
                                    <span class="community_followers">100.000 seguidores</span>
                                </p>
                            </div>
                            <hr class="community_divider">
                            <div class="community_card">
                                <div class="community_icon">
                                    <i class="ri-group-line"></i>
                                </div>
                                <p>
                                    <span class="community_name">NomeDaComunidade1</span>
                                    <span class="community_followers">100.000 seguidores</span>
                                </p>
                            </div>
                            <hr class="community_divider">
                            <div class="community_card">
                                <div class="community_icon">
                                    <i class="ri-group-line"></i>
                                </div>
                                <p>
                                    <span class="community_name">NomeDaComunidade1</span>
                                    <span class="community_followers">100.000 seguidores</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
>>>>>>> 7fc9a5a528a9cfe8cab2e3603e2c128b08acb1ba
        </section>
    </main>

    <?php include("../src/components/footer.php"); ?>
<<<<<<< HEAD



=======
>>>>>>> 7fc9a5a528a9cfe8cab2e3603e2c128b08acb1ba
    <script src="../src/assets/js/javascript.js"></script>
</body>

</html>