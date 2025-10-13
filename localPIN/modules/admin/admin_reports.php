<?php
// O cabeçalho já inicia a sessão e faz a conexão via PDO
include('admin_header.php');
require_once('../../config/log_helper.php');

$feedback_message = '';
$admin_user_id = $_SESSION['user_id'];
$admin_user_name = $_SESSION['full_name'] ?? 'Administrador';

// Lógica para atualizar status ou deletar conteúdo
if (isset($_GET['action']) && isset($_GET['report_id'])) {
    $report_id = intval($_GET['report_id']);

    // Ação para marcar a denúncia como resolvida
    if ($_GET['action'] == 'resolve') {
        $stmt = $pdo->prepare("UPDATE reports SET status = 'resolvida' WHERE report_id = ?");
        if ($stmt->execute([$report_id])) {
            $feedback_message = "<p class='success-message'>Denúncia marcada como resolvida!</p>";
            logAction($pdo, 'Denúncia Resolvida', $admin_user_name, "Denúncia ID #{$report_id} foi marcada como resolvida.", $admin_user_id);
        } else {
            $feedback_message = "<p class='error-message'>Erro ao atualizar o status da denúncia.</p>";
        }
    }

    // Ação para deletar o conteúdo alvo e marcar a denúncia como resolvida
    if ($_GET['action'] == 'delete_content' && isset($_GET['target_type']) && isset($_GET['target_id'])) {
        $target_type = $_GET['target_type'];
        $target_id = intval($_GET['target_id']);

        $target_table = '';
        $target_column_id = '';

        // Mapeia o tipo de alvo para a tabela e coluna de forma segura
        switch ($target_type) {
            case 'post':
                $target_table = 'posts';
                $target_column_id = 'post_id';
                break;
            case 'user':
                $target_table = 'users';
                $target_column_id = 'user_id';
                break;
            case 'community':
                $target_table = 'communities';
                $target_column_id = 'community_id';
                break;
        }

        if ($target_table && $target_column_id) {
            try {
                $pdo->beginTransaction();

                $stmt_delete = $pdo->prepare("DELETE FROM $target_table WHERE $target_column_id = ?");
                $stmt_delete->execute([$target_id]);

                $stmt_resolve = $pdo->prepare("UPDATE reports SET status = 'resolvida' WHERE report_id = ?");
                $stmt_resolve->execute([$report_id]);

                $pdo->commit();

                $feedback_message = "<p class='success-message'>Conteúdo deletado e denúncia resolvida!</p>";
                $log_details = "Conteúdo do tipo '{$target_type}' (ID: #{$target_id}) foi deletado, resolvendo a denúncia ID #{$report_id}.";
                logAction($pdo, 'Conteúdo Deletado por Denúncia', $admin_user_name, $log_details, $admin_user_id);
            } catch (Exception $e) {
                $pdo->rollBack();
                $feedback_message = "<p class='error-message'>Erro ao deletar o conteúdo denunciado: " . $e->getMessage() . "</p>";
            }
        }
    }
}

// Lógica de filtro para exibir as denúncias
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'pendente';

$sql = "SELECT r.*, u.full_name as reporter_name 
        FROM reports r 
        LEFT JOIN users u ON r.reporter_id = u.user_id";

$params = [];
if ($filter == 'pendente') {
    $sql .= " WHERE r.status = ?";
    $params[] = 'pendente';
} elseif ($filter == 'resolvida') {
    $sql .= " WHERE r.status = ?";
    $params[] = 'resolvida';
}

$sql .= " ORDER BY r.reported_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$reports = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<main class="container">
    <div class="page-header">
        <h1>Denúncias</h1>
        <div class="filters">
            <a href="admin_reports.php?filter=pendente" class="btn <?php echo ($filter == 'pendente') ? 'btn-primary' : ''; ?>">Pendentes</a>
            <a href="admin_reports.php?filter=resolvida" class="btn <?php echo ($filter == 'resolvida') ? 'btn-primary' : ''; ?>">Resolvidas</a>
            <a href="admin_reports.php?filter=all" class="btn <?php echo ($filter == 'all') ? 'btn-primary' : ''; ?>">Todas</a>
        </div>
    </div>

    <?php echo $feedback_message; ?>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Data</th>
                    <th>Denunciante</th>
                    <th>Alvo</th>
                    <th>Motivo</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($reports && count($reports) > 0): ?>
                    <?php foreach ($reports as $report): ?>
                        <tr>
                            <td><?php echo date("d/m/Y H:i", strtotime($report['reported_at'])); ?></td>
                            <td><?php echo htmlspecialchars($report['reporter_name'] ?? 'Usuário Deletado'); ?></td>
                            <td>
                                <?php echo htmlspecialchars(ucfirst($report['target_type'])) . ' (ID: ' . $report['target_id'] . ')'; ?>
                            </td>
                            <td><?php echo nl2br(htmlspecialchars($report['reason'])); ?></td>
                            <td>
                                <span class="status-badge status-<?php echo htmlspecialchars($report['status']); ?>">
                                    <?php echo htmlspecialchars($report['status']); ?>
                                </span>
                            </td>
                            <td class="actions">
                                <?php if ($report['status'] == 'pendente'): ?>
                                    <a href="admin_reports.php?action=resolve&report_id=<?php echo $report['report_id']; ?>" class="btn btn-icon btn-edit" title="Marcar como Resolvida">
                                        <i class="ri-check-line"></i>
                                    </a>
                                    <a href="admin_reports.php?action=delete_content&report_id=<?php echo $report['report_id']; ?>&target_type=<?php echo $report['target_type']; ?>&target_id=<?php echo $report['target_id']; ?>" onclick="return confirm('Tem certeza que deseja DELETAR o conteúdo denunciado e resolver esta denúncia?');" class="btn btn-icon btn-delete" title="Deletar Conteúdo e Resolver">
                                        <i class="ri-delete-bin-2-line"></i>
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6">Nenhuma denúncia encontrada para este filtro.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>

<?php
include('admin_footer.php');
?>