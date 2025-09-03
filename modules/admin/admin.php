<?php
// Inclui o cabeçalho do painel de administração.
require_once("../../config/conn.php");
include("admin_header.php");

// --- Consultas para os Cards do Dashboard ---

// Contar total de cada tabela
$total_usuarios = $conn->query("SELECT COUNT(*) as total FROM usuarios")->fetch_assoc()['total'];
$total_niveis = $conn->query("SELECT COUNT(*) as total FROM niveis")->fetch_assoc()['total'];
$total_posts = $conn->query("SELECT COUNT(*) as total FROM posts")->fetch_assoc()['total'];
$total_tags = $conn->query("SELECT COUNT(*) as total FROM tags")->fetch_assoc()['total'];
$total_comunidades = $conn->query("SELECT COUNT(*) as total FROM comunidades")->fetch_assoc()['total'];
$total_grupos = $conn->query("SELECT COUNT(*) as total FROM grupos")->fetch_assoc()['total'];
$total_denuncias = $conn->query("SELECT COUNT(*) as total FROM denuncias WHERE status_denn = 'pendente'")->fetch_assoc()['total'];

// --- CONSULTA ATUALIZADA: Lê diretamente da tabela de log ---
$sql_recent_activity = "
    SELECT 
        log.data_evento as event_date,
        log.tipo_evento as event_type,
        COALESCE(u.nome_usu, log.assunto_principal) as event_subject,
        log.detalhes_evento as event_details
    FROM audit_log log
    LEFT JOIN usuarios u ON log.id_usuario_acao = u.id_usu
    ORDER BY log.data_evento DESC
    LIMIT 10;
";
$recent_activities = $conn->query($sql_recent_activity);
?>

<main class="container">
    <h1>Dashboard</h1>
    <p>Bem-vindo ao painel de administração do IFApoia.</p>

    <div class="dashboard-grid">
        <!-- Card de Usuários -->
        <div class="dashboard-card">
            <h2><i class="ri-group-line"></i> Usuários</h2>
            <p class="stat-number"><?php echo $total_usuarios; ?></p>
            <a href="admin_users.php" class="card-link">Gerenciar &rarr;</a>
        </div>

        <!-- Card de Níveis -->
        <div class="dashboard-card">
            <h2><i class="ri-shield-star-line"></i> Níveis de Acesso</h2>
            <p class="stat-number"><?php echo $total_niveis; ?></p>
            <a href="admin_niveis.php" class="card-link">Gerenciar &rarr;</a>
        </div>

        <!-- Card de Posts -->
        <div class="dashboard-card">
            <h2><i class="ri-article-line"></i> Posts</h2>
            <p class="stat-number"><?php echo $total_posts; ?></p>
            <a href="admin_posts.php" class="card-link">Gerenciar &rarr;</a>
        </div>

        <!-- Card de Tags -->
        <div class="dashboard-card">
            <h2><i class="ri-price-tag-3-line"></i> Tags</h2>
            <p class="stat-number"><?php echo $total_tags; ?></p>
            <a href="admin_tags.php" class="card-link">Gerenciar &rarr;</a>
        </div>

        <!-- Card de Comunidades -->
        <div class="dashboard-card">
            <h2><i class="ri-community-line"></i> Comunidades</h2>
            <p class="stat-number"><?php echo $total_comunidades; ?></p>
            <a href="admin_comms.php" class="card-link">Gerenciar &rarr;</a>
        </div>

        <!-- Card de Grupos -->
        <div class="dashboard-card">
            <h2><i class="ri-group-2-line"></i> Grupos</h2>
            <p class="stat-number"><?php echo $total_grupos; ?></p>
            <a href="admin_gps.php" class="card-link">Gerenciar &rarr;</a>
        </div>

        <!-- Card de Denúncias -->
        <div class="dashboard-card">
            <h2><i class="ri-error-warning-line"></i> Denúncias</h2>
            <p class="stat-number"><?php echo $total_denuncias; ?></p>
            <a href="admin_denns.php" class="card-link">Gerenciar &rarr;</a>
        </div>
    </div>

    <h1>Atividades Recentes</h1>
    <div class="table-container" style="margin-top: 2rem;">
        <table>
            <thead>
                <tr>
                    <th>Data</th>
                    <th>Tipo de Evento</th>
                    <th>Realizado Por</th>
                    <th>Detalhes</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($recent_activities && $recent_activities->num_rows > 0): ?>
                    <?php while ($activity = $recent_activities->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo date("d/m/Y H:i", strtotime($activity['event_date'])); ?></td>
                            <td><span class="status-badge status-info"><?php echo htmlspecialchars($activity['event_type']); ?></span></td>
                            <td><?php echo htmlspecialchars($activity['event_subject'] ?? 'Sistema'); ?></td>
                            <td class="event-details"><?php echo htmlspecialchars($activity['event_details']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4">Nenhuma atividade recente encontrada.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>

<?php
// Inclui o rodapé do painel de administração.
include 'admin_footer.php';
$conn->close();
?>