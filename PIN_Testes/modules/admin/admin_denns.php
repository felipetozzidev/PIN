<?php
require_once('../../config/conn.php');
require_once('../../config/log_helper.php'); // Adicionado para usar a função de log
include('admin_header.php');

$feedback_message = '';

// Lógica para atualizar status ou deletar conteúdo
if (isset($_GET['action']) && isset($_GET['id_denn'])) {
    $id_denn = intval($_GET['id_denn']);
    $admin_user_name = $_SESSION['nome_usu'] ?? 'Administrador';

    // Ação para marcar a denúncia como resolvida
    if ($_GET['action'] == 'resolve') {
        $stmt = $conn->prepare("UPDATE denuncias SET status_denn = 'resolvida' WHERE id_denn = ?");
        $stmt->bind_param("i", $id_denn);
        if ($stmt->execute()) {
            $feedback_message = "<p class='success-message'>Denúncia marcada como resolvida!</p>";
            log_activity($conn, 'Denúncia Resolvida', $admin_user_name, "Denúncia ID #{$id_denn} foi marcada como resolvida.");
        } else {
            $feedback_message = "<p class='error-message'>Erro ao atualizar o status da denúncia.</p>";
        }
        $stmt->close();
    }

    // Ação para deletar o conteúdo alvo e marcar a denúncia como resolvida
    if ($_GET['action'] == 'delete_content') {
        $alvo = $_GET['tipoalvo_denn'];
        $id_alvo = intval($_GET['idalvo_denn']);

        $tabela_alvo = '';
        $coluna_id_alvo = '';

        // Mapeia o tipo de alvo para a tabela e coluna correspondente no banco de dados
        if ($alvo == 'post') {
            $tabela_alvo = 'posts';
            $coluna_id_alvo = 'id_post';
        } elseif ($alvo == 'usuario') {
            $tabela_alvo = 'usuarios';
            $coluna_id_alvo = 'id_usu';
        } elseif ($alvo == 'comunidade') {
            $tabela_alvo = 'comunidades';
            $coluna_id_alvo = 'id_com'; // CORRIGIDO: de 'id_comu' para 'id_com'
        }

        if ($tabela_alvo && $coluna_id_alvo) {
            $stmt_delete = $conn->prepare("DELETE FROM $tabela_alvo WHERE $coluna_id_alvo = ?");
            $stmt_delete->bind_param("i", $id_alvo);

            if ($stmt_delete->execute()) {
                $stmt_resolve = $conn->prepare("UPDATE denuncias SET status_denn = 'resolvida' WHERE id_denn = ?");
                $stmt_resolve->bind_param("i", $id_denn);
                $stmt_resolve->execute();
                $stmt_resolve->close();

                $feedback_message = "<p class='success-message'>Conteúdo deletado e denúncia resolvida!</p>";
                $log_details = "Conteúdo do tipo '{$alvo}' (ID: #{$id_alvo}) foi deletado, resolvendo a denúncia ID #{$id_denn}.";
                log_activity($conn, 'Conteúdo Deletado por Denúncia', $admin_user_name, $log_details);
            } else {
                $feedback_message = "<p class='error-message'>Erro ao deletar o conteúdo denunciado.</p>";
            }
            $stmt_delete->close();
        }
    }
}

// Lógica de filtro para exibir as denúncias
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'pendente';

$sql = "SELECT d.*, u.nome_usu as nome_denunciante 
        FROM denuncias d 
        LEFT JOIN usuarios u ON d.id_denunciante = u.id_usu";

if ($filter == 'pendente') {
    $sql .= " WHERE d.status_denn = 'pendente'";
} elseif ($filter == 'resolvida') {
    $sql .= " WHERE d.status_denn = 'resolvida'";
}

$sql .= " ORDER BY d.data_denn DESC";
$resultado = $conn->query($sql);
?>

<main class="container">
    <div class="page-header">
        <h1>Denúncias</h1>
        <div class="filters">
            <a href="admin_denns.php?filter=pendente" class="btn <?php echo ($filter == 'pendente') ? 'btn-primary' : ''; ?>">Pendentes</a>
            <a href="admin_denns.php?filter=resolvida" class="btn <?php echo ($filter == 'resolvida') ? 'btn-primary' : ''; ?>">Resolvidas</a>
            <a href="admin_denns.php?filter=all" class="btn <?php echo ($filter == 'all') ? 'btn-primary' : ''; ?>">Todas</a>
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
                <?php if ($resultado && $resultado->num_rows > 0): ?>
                    <?php while ($denuncia = $resultado->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo date("d/m/Y H:i", strtotime($denuncia['data_denn'])); ?></td>
                            <td><?php echo htmlspecialchars($denuncia['nome_denunciante'] ?? 'Usuário Deletado'); ?></td>
                            <td>
                                <?php echo htmlspecialchars(ucfirst($denuncia['tipoalvo_denn'])) . ' (ID: ' . $denuncia['idalvo_denn'] . ')'; ?>
                            </td>
                            <td><?php echo nl2br(htmlspecialchars($denuncia['motivo_denn'])); ?></td>
                            <td>
                                <span class="status-badge status-<?php echo htmlspecialchars($denuncia['status_denn']); ?>">
                                    <?php echo htmlspecialchars($denuncia['status_denn']); ?>
                                </span>
                            </td>
                            <td class="actions">
                                <?php if ($denuncia['status_denn'] == 'pendente'): ?>
                                    <a href="admin_denns.php?action=resolve&id_denn=<?php echo $denuncia['id_denn']; ?>" class="btn btn-icon btn-edit" title="Marcar como Resolvida">
                                        <i class="ri-check-line"></i>
                                    </a>
                                    <a href="admin_denns.php?action=delete_content&id_denn=<?php echo $denuncia['id_denn']; ?>&tipoalvo_denn=<?php echo $denuncia['tipoalvo_denn']; ?>&idalvo_denn=<?php echo $denuncia['idalvo_denn']; ?>" onclick="return confirm('Tem certeza que deseja DELETAR o conteúdo denunciado e resolver esta denúncia?');" class="btn btn-icon btn-delete" title="Deletar Conteúdo e Resolver">
                                        <i class="ri-delete-bin-2-line"></i>
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
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
$conn->close();
?>