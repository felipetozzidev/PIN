<<<<<<< HEAD
<?php 

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
</body>
</html>
=======
<?php
require_once("../../config/conn.php");
include("admin_header.php");

// --- Consultas para o Dashboard ---
$total_usuarios = $conn->query("SELECT COUNT(*) as total FROM usuarios")->fetch_assoc()['total'];
$total_posts = $conn->query("SELECT COUNT(*) as total FROM posts")->fetch_assoc()['total'];
$total_comunidades = $conn->query("SELECT COUNT(*) as total FROM comunidades")->fetch_assoc()['total'];
$total_denuncias = $conn->query("SELECT COUNT(*) as total FROM denuncias WHERE status_den = 'pendente'")->fetch_assoc()['total'];
$total_comentarios = $conn->query("SELECT COUNT(*) as total FROM comentarios")->fetch_assoc()['total'];
$total_likes = $conn->query("SELECT COUNT(*) as total FROM likes WHERE tipo_lk = 'curtir'")->fetch_assoc()['total'];

// Últimos 5 usuários cadastrados
$ultimos_usuarios = $conn->query("SELECT nome_usu, email_usu, datacriacao_usu FROM usuarios ORDER BY id_usu DESC LIMIT 5");
?>

<main class="container">
    <h1>Dashboard</h1>
    <p>Bem-vindo ao painel de administração do IFApoia.</p>

    <div class="dashboard-grid">
        <div class="dashboard-card">
            <h2><i class="ri-group-line"></i> Usuários Totais</h2>
            <p class="stat-number"><?php echo $total_usuarios; ?></p>
            <a href="admin_users.php" class="card-link">Gerenciar Usuários &rarr;</a>
        </div>
        <div class="dashboard-card">
            <h2><i class="ri-article-line"></i> Posts Criados</h2>
            <p class="stat-number"><?php echo $total_posts; ?></p>
            <a href="admin_posts.php" class="card-link">Gerenciar Posts &rarr;</a>
        </div>
        <div class="dashboard-card">
            <h2><i class="ri-message-3-line"></i> Comentários</h2>
            <p class="stat-number"><?php echo $total_comentarios; ?></p>
            <a href="admin_posts.php" class="card-link">Ver Posts &rarr;</a>
        </div>
        <div class="dashboard-card">
            <h2><i class="ri-thumb-up-line"></i> Likes</h2>
            <p class="stat-number"><?php echo $total_likes; ?></p>
            <a href="admin_posts.php" class="card-link">Ver Posts &rarr;</a>
        </div>
        <div class="dashboard-card">
            <h2><i class="ri-community-line"></i> Comunidades</h2>
            <p class="stat-number"><?php echo $total_comunidades; ?></p>
            <a href="admin_comms.php" class="card-link">Gerenciar Comunidades &rarr;</a>
        </div>
        <div class="dashboard-card">
            <h2><i class="ri-error-warning-line"></i> Denúncias Pendentes</h2>
            <p class="stat-number"><?php echo $total_denuncias; ?></p>
            <a href="admin_denns.php" class="card-link">Ver Denúncias &rarr;</a>
        </div>
    </div>

    <div class="table-container" style="margin-top: 2rem;">
        <h2>Últimos Usuários Cadastrados</h2>
        <table>
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Data de Cadastro</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($ultimos_usuarios->num_rows > 0): ?>
                    <?php while ($usuario = $ultimos_usuarios->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($usuario['nome_usu']); ?></td>
                            <td><?php echo htmlspecialchars($usuario['email_usu']); ?></td>
                            <td><?php echo date("d/m/Y H:i", strtotime($usuario['datacriacao_usu'])); ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3">Nenhum usuário encontrado.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>

<?php
include('admin_footer.php');
$conn->close();
?>
>>>>>>> 7fc9a5a528a9cfe8cab2e3603e2c128b08acb1ba
