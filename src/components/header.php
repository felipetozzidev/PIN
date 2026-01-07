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
                <!-- <a class="nav-item d-none d-md-block me-2" href="#" title="Notificações">
                    <img src="../src/assets/icons/notification-line.svg" alt="Notificações" width="35px">
                </a> -->
                <?php if (isset($_SESSION['user_id'])): ?>
                    <div class="nav-item dropdown d-none d-md-block user_logged">
                        <a class="nav-link" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
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
                            <li><hr class="dropdown-divider"></li>
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
                <button class="navbar-toggler d-md-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>
        </div>
    </nav>

    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="offcanvas-titulo">Menu</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <nav class="navbar_lateral">
                <ul>
                    <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="select_item <?php echo ($currentPage === 'perfil') ? 'active' : ''; ?>" style="margin-bottom: 10px;">
                        <a href="perfil.php" class="nav-link" style="display: flex; align-items: center; gap: 15px;">
                            <?php
                            if (!empty($_SESSION['profile_image_url'])) {
                                echo '<img src="' . htmlspecialchars($_SESSION['profile_image_url']) . '" alt="Perfil" width="40" height="40" class="rounded-circle" style="border: 2px solid var(--primary);">';
                            } else {
                                echo '<img src="../src/assets/img/default-user.png" alt="Perfil" width="40" height="40" class="rounded-circle" style="border: 2px solid var(--primary);">';
                            }
                            ?>
                            <div style="display: flex; flex-direction: column; line-height: 1.2;">
                                <span style="font-weight: 700; font-size: 1rem;">Meu Perfil</span>
                                <span style="font-size: 0.85rem; opacity: 0.8;">Ver perfil completo</span>
                            </div>
                        </a>
                    </li>
                    <?php else: ?>
                    <li class="select_item">
                         <a href="login.php" class="nav-link">
                             <i class="ri-user-line"></i>
                             <p>Fazer Login / Cadastro</p>
                         </a>
                    </li>
                    <?php endif; ?>
                    
                    <li class="separador"><hr class="w-100"></li>

                    <li class="select_item <?php echo ($currentPage === 'inicio') ? 'active' : ''; ?>">
                        <a href="index.php" class="nav-link">
                            <i class="ri-home-2-line"></i>
                            <p>Início</p>
                        </a>
                    </li>
                    <li class="select_item <?php echo ($currentPage === 'comunidades') ? 'active' : ''; ?>">
                        <a href="comunidades.php" class="nav-link">
                            <i class="ri-group-line"></i>
                            <p>Comunidades</p>
                        </a>
                    </li>
                    
                    <li class="separador"><hr class="w-100"></li>

                    <?php
                    $isUserLoggedIn = isset($_SESSION['user_id']);
                    $isDropdownActive = ($currentPage === 'comunidade_view');
                    ?>
                    <li class="dropdown_item <?php echo $isDropdownActive ? 'active' : ''; ?>">
                        <a class="nav-link">
                            <p>Suas comunidades</p>
                        </a>
                        <ul class="dropdown_list">
                            <?php if ($isUserLoggedIn && isset($pdo)): ?>
                                <?php
                                try {
                                    $id_usuario_logado = $_SESSION['user_id'];
                                    $sql_suas_comunidades = "SELECT c.community_id, c.name FROM communities c JOIN user_communities uc ON c.community_id = uc.community_id WHERE uc.user_id = ? LIMIT 5";
                                    $stmt = $pdo->prepare($sql_suas_comunidades);
                                    $stmt->execute([$id_usuario_logado]);
                                    $suas_comunidades = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                    if ($suas_comunidades) {
                                        foreach ($suas_comunidades as $comunidade) {
                                            echo '<li><a href="perfil_comunidades.php?id=' . $comunidade['community_id'] . '" class="nav-link"><p>' . htmlspecialchars($comunidade['name']) . '</p></a></li>';
                                        }
                                    } else {
                                        echo '<li><p class="no-communities">Você não segue nenhuma comunidade.</p></li>';
                                    }
                                } catch (PDOException $e) {
                                    error_log("Erro ao buscar comunidades: " . $e->getMessage());
                                    echo '<li><p class="no-communities">Erro ao carregar.</p></li>';
                                }
                                ?>
                            <?php else: ?>
                                <li><a href="login.php" class="nav-link"><p>Faça login para ver suas comunidades.</p></a></li>
                            <?php endif; ?>
                        </ul>
                    </li>
                    
                    <li class="separador"><hr class="w-100"></li>

                    <li><a class="select_item" href="#"><i class="ri-settings-3-line"></i><p>Configurações</p></a></li>
                    
                    <?php if (isset($_SESSION['role_id']) && $_SESSION['role_id'] == 1): ?>
                        <li><a href="../modules/admin/admin.php" class="select_item"><i class="ri-list-settings-line"></i><p>Painel Admin</p></a></li>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li><a class="select_item" href="logout.php"><i class="ri-logout-box-line"></i><p>Sair</p></a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </div>