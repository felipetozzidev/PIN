<?php
require_once('../config/conn.php');
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Header IFApoia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
</head>

<body>
    <nav class="navbar navbar-expand-md fixed" id="nav">

        <div class="navbar-brand col-4">
            <a href="">
                <img src="../src/assets/img/Logotipo_antiga.png" alt="" class="logo">
            </a>
        </div>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse col-8" id="navbarCollapse">
            <div class="search navbar-nav col-6">
                <form class="d-flex" role="search" action="index.php" method="GET">
                    <i class="ri-search-line"></i>
                    <input class="me-2" type="search" name="search" placeholder="Buscar..." aria-label="Search">
                </form>
            </div>
            <div class="actions navbar-nav col-6 justify-content-end">
                <a class="nav-item" href="">
                    <img src="../src/assets/icons/add-large-line.svg" alt="" width="35px">
                </a>
                <a class="nav-item" href="">
                    <img src="../src/assets/icons/notification-line.svg" alt="" width="35px">
                </a>
                <?php if (isset($_SESSION['usuario_id'])): ?>
                    <div class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
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
                            <li><a class="dropdown-item" href="profile.php">Meu Perfil</a></li>
                            <li><a class="dropdown-item" href="settings.php">Configurações</a></li>
                            <li><a class="dropdown-item" href="../src/components/logout.php">Sair</a></li>
                        </ul>
                    </div>
                <?php else: ?>
                    <a class="nav-item" href="../src/components/login.php">
                        <img src="../src/assets/icons/icon_usuario.svg" alt="Ícone de Usuário" width="35">
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>