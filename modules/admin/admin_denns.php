<?php
include 'admin_header.php';
include '../../config/conn.php';

// Lógica para resolver denúncia
if (isset($_GET['resolve_id'])) {
    $id_para_resolver = intval($_GET['resolve_id']);
    $update_sql = "UPDATE denuncias SET status_den = 'resolvido' WHERE id_den = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("i", $id_para_resolver);
    if ($stmt->execute()) {
        echo "<p class='success-message'>Denúncia marcada como resolvida!</p>";
    } else {
        echo "<p class='error-message'>Erro ao atualizar denúncia.</p>";
    }
    $stmt->close();
}

// Busca todas as denúncias
$sql = "SELECT d.*, u.nome_usu as nome_denunciante FROM denuncias d LEFT JOIN usuarios u ON d.id_usu_denunciante = u.id_usu ORDER BY d.status_den, d.data_den DESC";
$resultado = $conn->query($sql);
?>

<main class="container">
    <h1>Gerenciar Denúncias</h1>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Data</th>
                <th>Denunciante</th>
                <th>Alvo</th>
                <th>ID Alvo</th>
                <th>Motivo</th>
                <th>Status</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($resultado->num_rows > 0): ?>
                <?php while ($denuncia = $resultado->fetch_assoc()): ?>
                    <tr class="<?php echo $denuncia['status_den'] == 'pendente' ? 'pendente' : ''; ?>">
                        <td><?php echo $denuncia['id_den']; ?></td>
                        <td><?php echo date("d/m/Y H:i", strtotime($denuncia['data_den'])); ?></td>
                        <td><?php echo htmlspecialchars($denuncia['nome_denunciante'] ?? 'Anônimo'); ?></td>
                        <td><?php echo htmlspecialchars($denuncia['alvo_den']); ?></td>
                        <td><?php echo htmlspecialchars($denuncia['idalvo_den']); ?></td>
                        <td><?php echo nl2br(htmlspecialchars($denuncia['motivo_den'])); ?></td>
                        <td><?php echo htmlspecialchars($denuncia['status_den']); ?></td>
                        <td>
                            <?php if ($denuncia['status_den'] == 'pendente'): ?>
                                <a href="admin_denns.php?resolve_id=<?php echo $denuncia['id_den']; ?>" class="btn-edit">Marcar como Resolvida</a>
                            <?php else: ?>
                                Resolvida
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="8">Nenhuma denúncia encontrada.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</main>

<?php
include 'admin_footer.php';
$conn->close();
?>