<nav class="navbar navbar-expand-md fiexd-top" id="nav">

    <!-- Logo à esquerda -->
    <div class="navbar-brand col-md-4">
        <a href="">
            <img src="../src/assets/img/Logotipo_antiga.png" alt="" class="logo">
        </a>
    </div>

    <!-- Busca no centro -->
    <div class="search navbar-nav col-md-4">
        <form class="d-flex" role="search" action="index.php" method="GET">
            <i class="ri-search-line"></i>
            <input class="me-2" type="search" name="search" placeholder="Buscar..." aria-label="Search">
        </form>
    </div>

    <!-- Botão mobile (substituir o actions) -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse"
        aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Links do menu collapse -->
    <div class="collapse navbar-collapse" id="navbarCollapse">
        <!-- Ações à direita -->
        <div class="actions navbar-nav col-md-4 d-flex justify-content-end">
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