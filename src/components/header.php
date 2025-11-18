<?php
// Garante que a sessão seja iniciada em todas as páginas. DEVE ser a primeira coisa no script.
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Inclui a conexão com o banco de dados de forma mais robusta
require_once(__DIR__ . '/../../config/conn.php');

// Define a variável de busca para evitar erros em páginas que não a usam
$search_query = isset($_GET['search']) ? trim($_GET['search']) : '';
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IFApoia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="apple-touch-icon" sizes="180x180" href="../src/assets/icons/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../src/assets/icons/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../src/assets/icons/favicon/favicon-16x16.png">
    <link rel="manifest" href="../src/assets/icons/favicon/site.webmanifest">

</head>

<body>
    <nav class="navbar navbar-expand-md fixed" id="nav">
        <div class="container-fluid">
            <div class="col-4">
                <a class="navbar-brand" href="index.php">
                    <img src="../src/assets/img/Logotipo_antiga.png" alt="Logo IFApoia" class="logo">
                </a>
            </div>
            <div class="col-4 navbar-nav d-flex justify-content-center d-none d-md-flex">
                <div class="search col-12">
                    <form class="d-flex" role="search" action="index.php" method="GET">
                        <i class="ri-search-line"></i>
                        <input class="me-2" type="search" name="search" placeholder="Buscar..." aria-label="Search" value="<?php echo htmlspecialchars($search_query); ?>">
                    </form>
                </div>
            </div>
            <div class="col-4 actions navbar-nav justify-content-end">
                <a class="nav-item me-2 create_post" id="create_post" title="Criar Post">
                    <div>
                        <img src="../src/assets/icons/add-large-line.svg" alt="Criar Post" width="35px">
                        <span>Postar</span>
                    </div>
                </a>
                <a class="nav-item d-none d-md-block me-2" href="#" title="Notificações">
                    <img src="../src/assets/icons/notification-line.svg" alt="Notificações" width="35px">
                </a>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <div class="nav-item dropdown d-none d-md-block user_logged">
                        <a class="nav-link" id="userDropdown" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <?php
                            if (!empty($_SESSION['profile_image_url'])) {
                                echo '<img src="' . htmlspecialchars($_SESSION['profile_image_url']) . '" alt="Foto de Perfil" width="35" height="35" class="rounded-circle">';
                            } else {
                                echo '<img src="../src/assets/img/default-user.png" alt="Foto de Perfil Padrão" width="35" height="35" class="rounded-circle">';
                            }
                            ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li class="dropdown-item-text">
                                <?php if (isset($_SESSION['full_name'])): ?>
                                    <?php
                                    $nomeCompleto = $_SESSION['full_name'];
                                    $primeiroNome = explode(' ', trim($nomeCompleto))[0];
                                    echo '<span> Olá, ' . htmlspecialchars($primeiroNome) . '!</span>';
                                    ?>
                                <?php endif; ?>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="perfil.php">Meu Perfil</a></li>
                            <?php if (isset($_SESSION['role_id']) && $_SESSION['role_id'] == 1): ?>
                                <li><a class="dropdown-item" href="../modules/admin/admin.php">Painel Admin</a></li>
                            <?php endif; ?>
                            <li><a class="dropdown-item" href="#">Configurações</a></li>
                            <li><a class="dropdown-item" href="logout.php">Sair</a></li>
                        </ul> 
                    </div>
                <?php else: ?>
                    <a class="nav-item d-none d-md-block" href="login.php" title="Login / Cadastro">
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
            <!-- <div class="search navbar-nav col-12 mb-3">
                <form class="d-flex" role="search" action="index.php" method="GET">
                    <i class="ri-search-line"></i>
                    <input class="me-2" type="search" name="search" placeholder="Buscar..." aria-label="Search">
                </form>
            </div> -->
            <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                <li class="nav-item"><a class="nav-link" href="post.php">Criar Post</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Notificações</a></li>
                <!-- <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="offcanvasUserDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <?php echo htmlspecialchars(explode(' ', trim($_SESSION['full_name']))[0]); ?>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="offcanvasUserDropdown">
                            <li><a class="dropdown-item" href="perfil.php">Meu Perfil</a></li>
                            <?php if (isset($_SESSION['role_id']) && $_SESSION['role_id'] == 1): ?>
                                <li><a class="dropdown-item" href="../modules/admin/admin.php">Painel Admin</a></li>
                            <?php endif; ?>
                            <li><a class="dropdown-item" href="#">Configurações</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="logout.php">Sair</a></li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="login.php">Login / Cadastro</a></li>
                <?php endif; ?>
            </ul> -->
        </div>
    </div>
