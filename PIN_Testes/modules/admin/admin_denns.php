<?php
require_once('../../config/conn.php');
include('admin_header.php');

$feedback_message = '';

// Lógica para atualizar status ou deletar conteúdo
if (isset($_GET['action'])) {
    $id_den = intval($_GET['id_den']);

    if ($_GET['action'] == 'resolve') {
        $stmt = $conn->prepare("UPDATE denuncias SET status_den = 'resolvido' WHERE id_den = ?");
        $stmt->bind_param("i", $id_den);
        if ($stmt->execute()) {
            $feedback_message = "<p class='success-message'>Denúncia marcada como resolvida!</p>";
        }
        $stmt->close();
    }

    if ($_GET['action'] == 'delete_content') {
        $alvo = $_GET['alvo_den'];
        $id_alvo = intval($_GET['idalvo_den']);

        $tabela_alvo = '';
        $coluna_id_alvo = '';

        if ($alvo == 'post') {
            $tabela_alvo = 'posts';
            $coluna_id_alvo = 'id_post';
        } elseif ($alvo == 'usuario') {
            $tabela_alvo = 'usuarios';
            $coluna_id_alvo = 'id_usu';
        } elseif ($alvo == 'comentario') {
            $tabela_alvo = 'comentarios';
            $coluna_id_alvo = 'id_coment';
        }

        if ($tabela_alvo && $coluna_id_alvo) {
            $stmt_delete = $conn->prepare("DELETE FROM $tabela_alvo WHERE $coluna_id_alvo = ?");
            $stmt_delete->bind_param("i", $id_alvo);
            if ($stmt_delete->execute()) {
                // Após deletar, marca a denúncia como resolvida
                $stmt_resolve = $conn->prepare("UPDATE denuncias SET status_den = 'resolvido' WHERE id_den = ?");
                $stmt_resolve->bind_param("i", $id_den);
                $stmt_resolve->execute();
                $stmt_resolve->close();
                $feedback_message = "<p class='success-message'>Conteúdo deletado e denúncia resolvida!</p>";
            }
            $stmt_delete->close();
        }
    }
}


// Lógica de filtro
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'pendente';
$sql = "SELECT d.*, u.nome_usu as nome_denunciante 
        FROM denuncias d 
        LEFT JOIN usuarios u ON d.id_usu_denunciante = u.id_usu";
if ($filter == 'pendente') {
    $sql .= " WHERE d.status_den = 'pendente'";
} elseif ($filter == 'resolvido') {
    $sql .= " WHERE d.status_den = 'resolvido'";
}
$sql .= " ORDER BY d.data_den DESC";
$resultado = $conn->query($sql);
?>

<main class="container">
    <div class="page-header">
        <h1>Gerenciar Denúncias</h1>
        <div class="filters">
            <a href="admin_denns.php?filter=pendente" class="btn <?php echo ($filter == 'pendente') ? 'btn-primary' : ''; ?>">Pendentes</a>
            <a href="admin_denns.php?filter=resolvido" class="btn <?php echo ($filter == 'resolvido') ? 'btn-primary' : ''; ?>">Resolvidas</a>
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
                            <td><?php echo date("d/m/Y H:i", strtotime($denuncia['data_den'])); ?></td>
                            <td><?php echo htmlspecialchars($denuncia['nome_denunciante'] ?? 'Usuário Deletado'); ?></td>
                            <td>
                                <?php echo htmlspecialchars(ucfirst($denuncia['alvo_den'])) . ' (ID: ' . $denuncia['idalvo_den'] . ')'; ?>
                            </td>
                            <td><?php echo nl2br(htmlspecialchars($denuncia['motivo_den'])); ?></td>
                            <td>
                                <span class="status-badge status-<?php echo htmlspecialchars($denuncia['status_den']); ?>">
                                    <?php echo htmlspecialchars($denuncia['status_den']); ?>
                                </span>
                            </td>
                            <td class="actions">
                                <?php if ($denuncia['status_den'] == 'pendente'): ?>
                                    <a href="admin_denns.php?action=resolve&id_den=<?php echo $denuncia['id_den']; ?>" class="btn btn-icon btn-edit" title="Marcar como Resolvida">
                                        <i class="ri-check-line"></i>
                                    </a>
                                    <a href="admin_denns.php?action=delete_content&id_den=<?php echo $denuncia['id_den']; ?>&alvo_den=<?php echo $denuncia['alvo_den']; ?>&idalvo_den=<?php echo $denuncia['idalvo_den']; ?>" onclick="return confirm('Tem certeza que deseja DELETAR o conteúdo denunciado e resolver esta denúncia?');" class="btn btn-icon btn-delete" title="Deletar Conteúdo e Resolver">
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