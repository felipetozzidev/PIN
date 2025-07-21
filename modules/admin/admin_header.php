<?php
session_start();

//Verifica se o usuário está logado e se tem o nível de administrador (assumindo que id_nvl = 1 para admin)
if (!isset($_SESSION['id_usu']) /*|| $_SESSION['id_nvl'] != 1*/) {
    // Se não for admin, redireciona para a página de login ou para a home
    header("Location: ../../public/login.php?erro=acesso_negado");
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel de Administração - IFApoia</title>
    <link rel="stylesheet" href="a../../src/assets/css/admin.css">
</head>

<body>
    <header class="admin-header">
        <div class="container">
            <div class="logo">
                <a href="admin.php">IFApoia Admin</a>
            </div>
            <nav class="admin-nav">
                <ul>
                    <li><a href="admin.php">Dashboard</a></li>
                    <li><a href="admin_users.php">Usuários</a></li>
                    <li><a href="admin_posts.php">Posts</a></li>
                    <li><a href="admin_comms.php">Comunidades</a></li>
                    <li><a href="admin_denns.php">Denúncias</a></li>
                    <li><a href="../../public/logout.php">Sair</a></li>
                </ul>
            </nav>
        </div>
    </header>