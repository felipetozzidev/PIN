<?php
// O cabeçalho já inicia a sessão e faz a conexão via PDO
include("admin_header.php");

// --- Consultas para os Cards do Dashboard ---

// Contar total de cada tabela usando PDO
$total_users = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$total_roles = $pdo->query("SELECT COUNT(*) FROM roles")->fetchColumn();
$total_posts = $pdo->query("SELECT COUNT(*) FROM posts")->fetchColumn();
$total_tags = $pdo->query("SELECT COUNT(*) FROM tags")->fetchColumn();
$total_communities = $pdo->query("SELECT COUNT(*) FROM communities")->fetchColumn();
$total_groups = $pdo->query("SELECT COUNT(*) FROM groups")->fetchColumn();
$total_reports = $pdo->query("SELECT COUNT(*) FROM reports WHERE status = 'pendente'")->fetchColumn();

// --- CONSULTA ATUALIZADA: Lê diretamente da tabela de log com os novos nomes ---
$sql_recent_activity = "
    SELECT 
        log.event_date,
        log.event_type,
        COALESCE(u.full_name, log.action_user_fullname) as event_subject,
        log.event_details
    FROM audit_log log
    LEFT JOIN users u ON log.action_user_id = u.user_id
    ORDER BY log.event_date DESC
    LIMIT 10;
";
$recent_activities_stmt = $pdo->query($sql_recent_activity);
$recent_activities = $recent_activities_stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<main class="container">
    <h1>Dashboard</h1>
    <p>Bem-vindo ao painel de administração do IFApoia.</p>

    <div class="dashboard-grid">
        <!-- Card de Usuários -->
        <div class="dashboard-card">
            <h2><i class="ri-group-line"></i> Usuários</h2>
            <p class="stat-number"><?php echo $total_users; ?></p>
            <a href="admin_users.php" class="card-link">Gerenciar &rarr;</a>
        </div>

        <!-- Card de Níveis -->
        <div class="dashboard-card">
            <h2><i class="ri-shield-star-line"></i> Níveis de Acesso</h2>
            <p class="stat-number"><?php echo $total_roles; ?></p>
            <a href="admin_roles.php" class="card-link">Gerenciar &rarr;</a>
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
            <p class="stat-number"><?php echo $total_communities; ?></p>
            <a href="admin_communities.php" class="card-link">Gerenciar &rarr;</a>
        </div>

        <!-- Card de Grupos -->
        <div class="dashboard-card">
            <h2><i class="ri-group-2-line"></i> Grupos</h2>
            <p class="stat-number"><?php echo $total_groups; ?></p>
            <a href="admin_groups.php" class="card-link">Gerenciar &rarr;</a>
        </div>

        <!-- Card de Denúncias -->
        <div class="dashboard-card">
            <h2><i class="ri-error-warning-line"></i> Denúncias</h2>
            <p class="stat-number"><?php echo $total_reports; ?></p>
            <a href="admin_reports.php" class="card-link">Gerenciar &rarr;</a>
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
                <?php if ($recent_activities && count($recent_activities) > 0): ?>
                    <?php foreach ($recent_activities as $activity): ?>
                        <tr>
                            <td><?php echo date("d/m/Y H:i", strtotime($activity['event_date'])); ?></td>
                            <td><span class="status-badge status-info"><?php echo htmlspecialchars($activity['event_type']); ?></span></td>
                            <td><?php echo htmlspecialchars($activity['event_subject'] ?? 'Sistema'); ?></td>
                            <td class="event-details"><?php echo htmlspecialchars($activity['event_details']); ?></td>
                        </tr>
                    <?php endforeach; ?>
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
?>