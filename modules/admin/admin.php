<?php
// Inclui o cabeçalho do painel de administração, que já contém a verificação de sessão.
include 'admin_header.php';
include '../../config/conn.php'; // Inclui a conexão com o banco

// --- Consultas para o Dashboard ---

// Contar total de usuários
$sql_usuarios = "SELECT COUNT(*) as total FROM usuarios";
$res_usuarios = $conn->query($sql_usuarios);
$total_usuarios = $res_usuarios->fetch_assoc()['total'];

// Contar total de posts
$sql_posts = "SELECT COUNT(*) as total FROM posts";
$res_posts = $conn->query($sql_posts);
$total_posts = $res_posts->fetch_assoc()['total'];

// Contar total de comunidades
$sql_comunidades = "SELECT COUNT(*) as total FROM comunidades";
$res_comunidades = $conn->query($sql_comunidades);
$total_comunidades = $res_comunidades->fetch_assoc()['total'];

// Contar denúncias pendentes
$sql_denuncias = "SELECT COUNT(*) as total FROM denuncias WHERE status_den = 'pendente'";
$res_denuncias = $conn->query($sql_denuncias);
$total_denuncias = $res_denuncias->fetch_assoc()['total'];

?>

<main class="container">
    <h1>Dashboard</h1>
    <p>Bem-vindo ao painel de administração do IFApoia.</p>

    <div class="dashboard-grid">
        <div class="dashboard-card">
            <h2>Usuários</h2>
            <p><?php echo $total_usuarios; ?></p>
            <a href="admin_users.php">Gerenciar</a>
        </div>
        <div class="dashboard-card">
            <h2>Posts</h2>
            <p><?php echo $total_posts; ?></p>
            <a href="admin_posts.php">Gerenciar</a>
        </div>
        <div class="dashboard-card">
            <h2>Comunidades</h2>
            <p><?php echo $total_comunidades; ?></p>
            <a href="admin_comms.php">Gerenciar</a>
        </div>
        <div class="dashboard-card">
            <h2>Denúncias Pendentes</h2>
            <p><?php echo $total_denuncias; ?></p>
            <a href="admin_denns.php">Gerenciar</a>
        </div>
    </div>
</main>

<?php
// Inclui o rodapé do painel de administração.
include 'admin_footer.php';
?>