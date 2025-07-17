<?php
include 'admin_header.php';
include '../../config/conn.php';

// Lógica para Adicionar ou Editar Comunidade
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome_com = $_POST['nome_com'];
    $descricao_com = $_POST['descricao_com'];
    $id_com = isset($_POST['id_com']) ? intval($_POST['id_com']) : 0;

    if ($id_com > 0) { // Atualizar
        $sql = "UPDATE comunidades SET nome_com = ?, descricao_com = ? WHERE id_com = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $nome_com, $descricao_com, $id_com);
    } else { // Inserir
        $sql = "INSERT INTO comunidades (nome_com, descricao_com) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $nome_com, $descricao_com);
    }

    if ($stmt->execute()) {
        echo "<p class='success-message'>Comunidade salva com sucesso!</p>";
    } else {
        echo "<p class='error-message'>Erro ao salvar comunidade: " . $conn->error . "</p>";
    }
    $stmt->close();
}

// Lógica para Deletar
if (isset($_GET['delete_id'])) {
    $id_para_deletar = intval($_GET['delete_id']);
    $delete_sql = "DELETE FROM comunidades WHERE id_com = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param("i", $id_para_deletar);
    $stmt->execute();
    $stmt->close();
    header("Location: admin_comms.php"); // Redireciona para evitar reenvio
    exit();
}

// Busca dados da comunidade para edição
$comunidade_para_editar = null;
if (isset($_GET['edit_id'])) {
    $id_para_editar = intval($_GET['edit_id']);
    $edit_sql = "SELECT * FROM comunidades WHERE id_com = ?";
    $stmt = $conn->prepare($edit_sql);
    $stmt->bind_param("i", $id_para_editar);
    $stmt->execute();
    $result = $stmt->get_result();
    $comunidade_para_editar = $result->fetch_assoc();
    $stmt->close();
}

// Busca todas as comunidades
$sql_lista = "SELECT * FROM comunidades ORDER BY id_com DESC";
$resultado = $conn->query($sql_lista);
?>

<main class="container">
    <h1>Gerenciar Comunidades</h1>

    <div class="form-container">
        <h2><?php echo $comunidade_para_editar ? 'Editar' : 'Adicionar Nova'; ?> Comunidade</h2>
        <form action="admin_comms.php" method="POST">
            <?php if ($comunidade_para_editar): ?>
                <input type="hidden" name="id_com" value="<?php echo $comunidade_para_editar['id_com']; ?>">
            <?php endif; ?>
            <div class="form-group">
                <label for="nome_com">Nome da Comunidade</label>
                <input type="text" id="nome_com" name="nome_com" value="<?php echo htmlspecialchars($comunidade_para_editar['nome_com'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="descricao_com">Descrição</label>
                <textarea id="descricao_com" name="descricao_com" rows="4" required><?php echo htmlspecialchars($comunidade_para_editar['descricao_com'] ?? ''); ?></textarea>
            </div>
            <button type="submit" class="btn-submit">Salvar Comunidade</button>
        </form>
    </div>

    <h2>Lista de Comunidades</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Descrição</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($resultado->num_rows > 0): ?>
                <?php while ($com = $resultado->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $com['id_com']; ?></td>
                        <td><?php echo htmlspecialchars($com['nome_com']); ?></td>
                        <td><?php echo htmlspecialchars($com['descricao_com']); ?></td>
                        <td>
                            <a href="admin_comms.php?edit_id=<?php echo $com['id_com']; ?>" class="btn-edit">Editar</a>
                            <a href="admin_comms.php?delete_id=<?php echo $com['id_com']; ?>" onclick="return confirm('Tem certeza?');" class="btn-delete">Deletar</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">Nenhuma comunidade encontrada.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</main>

<?php
include 'admin_footer.php';
$conn->close();
?>