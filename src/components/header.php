<?php
require_once('../config/conn.php');
?>

<nav class="navbar navbar-expand-md fixed" id="nav">
    <div class="container-fluid">
        <div class="col-4">
            <a class="navbar-brand" href="">
                <img src="../src/assets/img/Logotipo_antiga.png" alt="" class="logo">
            </a>
        </div>
        <div class="col-4 navbar-nav d-flex justify-content-center d-none d-md-flex">
            <div class="search col-12">
                <form class="d-flex" role="search" action="index.php" method="GET">
                    <i class="ri-search-line"></i>
                    <input class="me-2" type="search" name="search" placeholder="Buscar..." aria-label="Search">
                </form>
            </div>
        </div>
        <div class="col-4 actions navbar-nav justify-content-end">
            <a class="nav-item d-none d-md-block me-2" href="">
                <img src="../src/assets/icons/add-large-line.svg" alt="" width="35px">
            </a>
            <a class="nav-item d-none d-md-block me-2" href="">
                <img src="../src/assets/icons/notification-line.svg" alt="" width="35px">
            </a>
            <?php if (isset($_SESSION['usuario_id'])): ?>
                <div class="nav-item dropdown d-none d-md-block">
                    <a class="nav-link dropdown-toggle" id="userDropdown" role="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        <?php
                        if (isset($_SESSION['imgperfil_usu'])) {
                            echo '<img src="' . htmlspecialchars($_SESSION['imgperfil_usu']) . '" alt="Foto de Perfil" width="35" height="35" class="">';
                        } else {
                            echo '<img src="../src/assets/icons/icon_usuario.svg" alt="Ícone de Usuário" width="30">';
                        }
                        ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                        <li class="dropdown-item-text">
                            <?php if (isset($_SESSION['nome_usuario'])): ?>
                                <?php
                                $nomeCompleto = $_SESSION['nome_usuario'];
                                $primeiroNome = explode(' ', trim($nomeCompleto))[0];
                                echo '<span> Olá, ' . htmlspecialchars($primeiroNome) . '!</span>';
                                ?>
                            <?php endif; ?>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="">Meu Perfil</a></li>
                        <li><a class="dropdown-item" href="../modules/admin/admin.php">Painel Admin</a></li>
                        <li><a class="dropdown-item" href="">Configurações</a></li>
                        <li><a class="dropdown-item" href="logout.php">Sair</a></li>
                    </ul>
                </div>
            <?php else: ?>
                <a class="nav-item d-none d-md-block" href="login.php">
                    <img src="../src/assets/icons/icon_usuario.svg" alt="Ícone de Usuário" width="35">
                </a>
            <?php endif; ?>
            <button class="navbar-toggler d-md-none" type="button" data-bs-toggle="offcanvas"
                data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
    </div>
</nav>

<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasNavbarLabel">Menu</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <div class="search navbar-nav col-12 mb-3">
            <form class="d-flex" role="search" action="index.php" method="GET">
                <i class="ri-search-line"></i>
                <input class="me-2" type="search" name="search" placeholder="Buscar..." aria-label="Search">
            </form>
        </div>
        <div class="actions navbar-nav col-12 justify-content-end mt-3">
            <a class="nav-item d-block d-md-none me-2" href="">
                <img src="../src/assets/icons/add-large-line.svg" alt="" width="35px">
            </a>
            <a class="nav-item d-block d-md-none me-2" href="">
                <img src="../src/assets/icons/notification-line.svg" alt="" width="35px">
            </a>
            <?php if (isset($_SESSION['usuario_id'])): ?>
                <div class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="userDropdownOffcanvas" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <?php
                        if (isset($_SESSION['imgperfil_usu'])) {
                            echo '<img src="' . htmlspecialchars($_SESSION['imgperfil_usu']) . '" alt="Foto de Perfil" width="35" height="35" class="">';
                        } else {
                            echo '<img src="../src/assets/icons/icon_usuario.svg" alt="Ícone de Usuário" width="30">';
                        }
                        ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdownOffcanvas">
                        <li class="dropdown-item-text">
                            <?php if (isset($_SESSION['nome_usuario'])): ?>
                                <?php
                                $nomeCompleto = $_SESSION['nome_usuario'];
                                $primeiroNome = explode(' ', trim($nomeCompleto))[0];
                                echo '<span> Olá, ' . htmlspecialchars($primeiroNome) . '!</span>';
                                ?>
                            <?php endif; ?>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="">Meu Perfil</a></li>
                        <li><a class="dropdown-item" href="../../modules/admin/admin.php">Painel Admin</a></li>
                        <li><a class="dropdown-item" href="">Configurações</a></li>
                        <li><a class="dropdown-item" href="logout.php">Sair</a></li>
                    </ul>
                </div>
            <?php else: ?>
                <a class="nav-item d-block d-md-none" href="login.php">
                    <img src="../src/assets/icons/icon_usuario.svg" alt="Ícone de Usuário" width="35">
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO"
    crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/gsap@3.13.0/dist/gsap.min.js"></script>