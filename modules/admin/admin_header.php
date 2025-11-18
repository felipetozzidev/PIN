<?php
// Garante que a sessão seja iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once('../../config/conn.php');

// CORREÇÃO: Verifica as novas variáveis de sessão ('user_id' e 'role_id')
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1) {
    header("Location: ../../public/login.php"); // Redireciona se não for admin
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
                    <!-- ATUALIZAÇÃO: Links para os novos nomes de arquivos -->
                    <li><a href="admin.php" class="<?php echo ($pagina_atual == 'admin.php') ? 'active' : ''; ?>">Dashboard</a></li>
                    <li><a href="admin_users.php" class="<?php echo ($pagina_atual == 'admin_users.php') ? 'active' : ''; ?>">Usuários</a></li>
                    <li><a href="admin_roles.php" class="<?php echo ($pagina_atual == 'admin_roles.php') ? 'active' : ''; ?>">Níveis</a></li>
                    <li><a href="admin_posts.php" class="<?php echo ($pagina_atual == 'admin_posts.php') ? 'active' : ''; ?>">Posts</a></li>
                    <li><a href="admin_tags.php" class="<?php echo ($pagina_atual == 'admin_tags.php') ? 'active' : ''; ?>">Tags</a></li>
                    <li><a href="admin_communities.php" class="<?php echo ($pagina_atual == 'admin_communities.php') ? 'active' : ''; ?>">Comunidades</a></li>
                    <li><a href="admin_groups.php" class="<?php echo ($pagina_atual == 'admin_groups.php') ? 'active' : ''; ?>">Grupos</a></li>
                    <li><a href="admin_reports.php" class="<?php echo ($pagina_atual == 'admin_reports.php') ? 'active' : ''; ?>">Reports</a></li>
                </ul>
            </nav>
            <div class="admin-profile">
                <div class="profile-dropdown">
                    <button class="profile-btn">
                        <!-- CORREÇÃO: Usa a nova variável de sessão 'full_name' -->
                        <span>Olá, <?php echo htmlspecialchars($_SESSION['full_name']); ?></span>
                        <i class="ri-arrow-down-s-line"></i>
                    </button>
                    <div class="dropdown-content-header">
                        <a href="../../public/"><i class="ri-arrow-go-back-line"></i> Voltar ao site</a>
                        <a href="../../public/logout.php"><i class="ri-logout-box-line"></i> Sair</a>
                    </div>
                </div>
            </div>
        </div>
    </header>