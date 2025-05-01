<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Header IFApoia</title>
</head>

<body>
    <nav class="navbar navbar-expand-md fixed" id="nav">

        <!-- Logo à esquerda -->
        <div class="navbar-brand col-4">
            <a href="">
                <img src="../src/assets/img/Logotipo_antiga.png" alt="" class="logo">
            </a>
        </div>

        <!-- Botão responsividade -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Links do menu collapse -->
        <div class="collapse navbar-collapse col-8" id="navbarCollapse">
            <!-- Busca no centro -->
            <div class="search navbar-nav col-6">
                <form class="d-flex" role="search" action="index.php" method="GET">
                    <i class="ri-search-line"></i>
                    <input class="me-2" type="search" name="search" placeholder="Buscar..." aria-label="Search">
                </form>
            </div>
            <!-- Ações à direita -->
            <div class="actions navbar-nav col-6">
                <!-- Novo Post -->
                <a class="nav-item" href="">
                    <img src="../src/assets/icons/add-large-line.svg" alt="" width="30px"> <!-- <span>Novo Post</span> -->
                </a>
                <!-- Notificação -->
                <a class="nav-item" href="">
                    <img src="../src/assets/icons/notification-line.svg" alt="" width="30px">
                </a>
                <!-- Usuário -->
                <a class="nav-item" href="">
                    <img src="../src/assets/icons/icon_usuario.svg" alt="" width="30px">
                </a>
            </div>
        </div>


    </nav>
</body>

</html>