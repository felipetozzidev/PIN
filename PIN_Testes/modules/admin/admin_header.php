<?php
if (!isset($_SESSION['id_usu']) || $_SESSION['id_nvl'] != 1) {
    // Se não for admin, redireciona para a página de login ou para a home
    header("Location: ../../public/index.php?erro=acesso_negado");
    exit();
}

$pagina_atual = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel de Administração - IFApoia</title>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="stylesheet" href="../../src/assets/css/admin.css">
</head>

<body>
    <header class="admin-header">
        <div class="container header-container">
            <div class="logo">
                <a href="admin.php"><i class="ri-shield-user-line"></i> IFApoia Admin</a>
            </div>
            <nav class="admin-nav">
                <ul>
                    <li><a href="admin.php" class="<?php echo ($pagina_atual == 'admin.php') ? 'active' : ''; ?>">Dashboard</a></li>
                    <li><a href="admin_users.php" class="<?php echo ($pagina_atual == 'admin_users.php') ? 'active' : ''; ?>">Usuários</a></li>
                    <li><a href="admin_posts.php" class="<?php echo ($pagina_atual == 'admin_posts.php') ? 'active' : ''; ?>">Posts</a></li>
                    <li><a href="admin_comms.php" class="<?php echo ($pagina_atual == 'admin_comms.php') ? 'active' : ''; ?>">Comunidades</a></li>
                    <li><a href="admin_denns.php" class="<?php echo ($pagina_atual == 'admin_denns.php') ? 'active' : ''; ?>">Denúncias</a></li>
                </ul>
            </nav>
            <div class="admin-profile">
                <div class="profile-dropdown">
                    <button class="profile-btn">
                        <span>Olá, <?php echo htmlspecialchars($_SESSION['nome_usu']); ?></span>
                        <i class="ri-arrow-down-s-line"></i>
                    </button>
                    <div class="dropdown-content">
                        <a href="../../public/index.php"><i class="ri-arrow-go-back-line"></i> Voltar</a>
                        <a href="../../public/logout.php"><i class="ri-logout-box-line"></i> Logout</a>
                    </div>
                </div>
            </div>
        </div>
    </header>