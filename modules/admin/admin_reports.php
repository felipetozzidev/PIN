<?php
include('admin_header.php');
require_once('../../config/log_helper.php');

$feedback_message = '';
$admin_user_id = $_SESSION['user_id'];
$admin_user_name = $_SESSION['full_name'] ?? 'Administrador';

// --- PROCESSAMENTO DO FORMULÁRIO DE RESOLUÇÃO ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] == 'process_report') {
    $report_id = intval($_POST['report_id']);
    $decision = $_POST['decision']; // 'keep' ou 'delete'
    $resolution_note = trim($_POST['resolution_note']);
    $target_type = $_POST['target_type'];
    $target_id = intval($_POST['target_id']);

    if (empty($resolution_note)) {
        $feedback_message = "<p class='error-message'>A nota de resolução é obrigatória.</p>";
    } else {
        try {
            $pdo->beginTransaction();

            // 1. Se a decisão for DELETAR, remove o conteúdo
            if ($decision == 'delete') {
                $target_table = '';
                $target_column_id = '';

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

                if ($target_table) {
                    $stmt_del = $pdo->prepare("DELETE FROM $target_table WHERE $target_column_id = ?");
                    $stmt_del->execute([$target_id]);
                }
            }

            // 2. Atualiza a denúncia com os detalhes da resolução
            $stmt_update = $pdo->prepare("
                UPDATE reports 
                SET status = 'resolvida', 
                    resolution_note = ?, 
                    resolved_at = NOW(), 
                    resolved_by = ? 
                WHERE report_id = ?
            ");
            $stmt_update->execute([$resolution_note, $admin_user_id, $report_id]);

            $pdo->commit();

            // Logs
            $action_type = ($decision == 'delete') ? 'Denúncia: Conteúdo Deletado' : 'Denúncia: Conteúdo Mantido';
            logAction($pdo, $action_type, $admin_user_name, "Denúncia #{$report_id} resolvida. Nota: {$resolution_note}", $admin_user_id);

            $feedback_message = "<p class='success-message'>Denúncia processada com sucesso!</p>";
        } catch (Exception $e) {
            $pdo->rollBack();
            $feedback_message = "<p class='error-message'>Erro ao processar: " . $e->getMessage() . "</p>";
        }
    }
}

// Filtros
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'pendente';

$sql = "SELECT r.*, 
               u_reporter.full_name as reporter_name,
               u_admin.full_name as admin_name
        FROM reports r 
        LEFT JOIN users u_reporter ON r.reporter_id = u_reporter.user_id
        LEFT JOIN users u_admin ON r.resolved_by = u_admin.user_id";

$params = [];
if ($filter == 'pendente') {
    $sql .= " WHERE r.status = 'pendente'";
} elseif ($filter == 'resolvida') {
    $sql .= " WHERE r.status = 'resolvida'";
}

$sql .= " ORDER BY r.reported_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$reports = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<main class="container">
    <div class="page-header">
        <h1>Central de Denúncias</h1>
        <div class="filters">
            <a href="admin_reports.php?filter=pendente" class="btn <?php echo ($filter == 'pendente') ? 'btn-primary' : 'btn-outline'; ?>">Pendentes</a>
            <a href="admin_reports.php?filter=resolvida" class="btn <?php echo ($filter == 'resolvida') ? 'btn-primary' : 'btn-outline'; ?>">Resolvidas</a>
            <a href="admin_reports.php?filter=all" class="btn <?php echo ($filter == 'all') ? 'btn-primary' : 'btn-outline'; ?>">Todas</a>
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
                    <th>Motivo da Denúncia</th>
                    <th>Resolução</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($reports && count($reports) > 0): ?>
                    <?php foreach ($reports as $report): ?>
                        <tr>
                            <td><?php echo date("d/m/Y H:i", strtotime($report['reported_at'])); ?></td>
                            <td>
                                <?php if ($report['reporter_name']): ?>
                                    <a href="../../public/perfil.php?id=<?php echo $report['reporter_id']; ?>" target="_blank">
                                        <?php echo htmlspecialchars($report['reporter_name']); ?>
                                    </a>
                                <?php else: ?>
                                    <span class="text-muted">Usuário Removido</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php
                                $targetUrl = '#';
                                $icon = 'ri-file-list-line';
                                if ($report['target_type'] == 'post') {
                                    $targetUrl = "../../public/post_view.php?id=" . $report['target_id'];
                                    $icon = 'ri-article-line';
                                } elseif ($report['target_type'] == 'user') {
                                    $targetUrl = "../../public/perfil.php?id=" . $report['target_id'];
                                    $icon = 'ri-user-line';
                                }
                                ?>
                                <a href="<?php echo $targetUrl; ?>" target="_blank" class="btn-link">
                                    <i class="<?php echo $icon; ?>"></i> <?php echo ucfirst($report['target_type']) . ' #' . $report['target_id']; ?>
                                </a>
                            </td>
                            <td>
                                <?php
                                $parts = explode(' | Detalhes: ', $report['reason']);
                                echo "<strong>" . htmlspecialchars($parts[0]) . "</strong>";
                                if (isset($parts[1])) echo "<br><small>" . htmlspecialchars($parts[1]) . "</small>";
                                ?>
                            </td>

                            <td>
                                <?php if ($report['status'] == 'resolvida'): ?>
                                    <div class="resolution-details">
                                        <strong>Por:</strong> <?php echo htmlspecialchars($report['admin_name']); ?><br>
                                        <strong>Nota:</strong> <?php echo htmlspecialchars($report['resolution_note']); ?>
                                    </div>
                                <?php else: ?>
                                    <span class="status-badge status-pendente">Pendente</span>
                                <?php endif; ?>
                            </td>

                            <td class="actions">
                                <?php if ($report['status'] == 'pendente'): ?>
                                    <button onclick="openProcessModal(<?php echo htmlspecialchars(json_encode($report)); ?>)" class="btn btn-small btn-primary" title="Processar Denúncia">
                                        <i class="ri-scales-3-line"></i> Processar
                                    </button>
                                <?php else: ?>
                                    <button class="btn btn-small btn-disabled" disabled><i class="ri-check-double-line"></i></button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" style="text-align:center; padding: 2rem;">Nenhuma denúncia encontrada.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>

<div class="admin-modal-overlay" id="processModal">
    <div class="admin-modal">
        <h2>Processar Denúncia #<span id="modalReportIdDisplay"></span></h2>
        <p id="modalReportReason"></p>

        <form method="POST" action="admin_reports.php">
            <input type="hidden" name="action" value="process_report">
            <input type="hidden" name="report_id" id="modalReportId">
            <input type="hidden" name="target_type" id="modalTargetType">
            <input type="hidden" name="target_id" id="modalTargetId">

            <div class="form-group">
                <label>Decisão Administrativa:</label>
                <div class="radio-group">
                    <label class="radio-option maintain">
                        <input type="radio" name="decision" value="keep" checked>
                        <span>Manter Conteúdo (Improcedente)</span>
                    </label>
                    <label class="radio-option delete">
                        <input type="radio" name="decision" value="delete">
                        <span">Deletar Conteúdo (Procedente)</span>
                    </label>
                </div>
            </div>

            <div class="form-group">
                <label for="resolutionNote">Nota de Resolução (Obrigatório):</label>
                <textarea name="resolution_note" id="resolutionNote" rows="4" class="form-control" placeholder="Explique o motivo da decisão (ex: 'Conteúdo viola a regra X' ou 'Não viola as diretrizes')..." required></textarea>
            </div>

            <div class="modal-actions">
                <button type="button" class="btn btn-cancel" onclick="closeProcessModal()">Cancelar</button>
                <button type="submit" class="btn btn-primary">Confirmar Resolução</button>
            </div>
        </form>
    </div>
</div>

<script>
    const modal = document.getElementById('processModal');
    const modalReportIdDisplay = document.getElementById('modalReportIdDisplay');
    const modalReportReason = document.getElementById('modalReportReason');
    const modalReportId = document.getElementById('modalReportId');
    const modalTargetType = document.getElementById('modalTargetType');
    const modalTargetId = document.getElementById('modalTargetId');

    function openProcessModal(report) {
        modalReportIdDisplay.textContent = report.report_id;
        modalReportReason.textContent = "Motivo: " + report.reason;

        modalReportId.value = report.report_id;
        modalTargetType.value = report.target_type;
        modalTargetId.value = report.target_id;

        modal.classList.add('active');
    }

    function closeProcessModal() {
        modal.classList.remove('active');
    }

    // Fecha ao clicar fora
    modal.addEventListener('click', (e) => {
        if (e.target === modal) closeProcessModal();
    });
</script>

<?php include('admin_footer.php'); ?>