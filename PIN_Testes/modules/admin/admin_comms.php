<?php
require_once('../../config/conn.php');
include('admin_header.php');

$feedback_message = '';
$comunidade_para_editar = null;

// Lógica para Adicionar ou Editar Comunidade
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_comm'])) {
    $nome_com = trim($_POST['nome_com']);
    $descricao_com = trim($_POST['descricao_com']);
    $id_com = isset($_POST['id_com']) ? intval($_POST['id_com']) : 0;

    if (empty($nome_com) || empty($descricao_com)) {
        $feedback_message = "<p class='error-message'>Nome e descrição são obrigatórios.</p>";
    } else {
        if ($id_com > 0) { // Atualizar
            $stmt = $conn->prepare("UPDATE comunidades SET nome_com = ?, descricao_com = ? WHERE id_com = ?");
            $stmt->bind_param("ssi", $nome_com, $descricao_com, $id_com);
        } else { // Inserir
            $stmt = $conn->prepare("INSERT INTO comunidades (nome_com, descricao_com) VALUES (?, ?)");
            $stmt->bind_param("ss", $nome_com, $descricao_com);
        }

        if ($stmt->execute()) {
            $feedback_message = "<p class='success-message'>Comunidade salva com sucesso!</p>";
        } else {
            $feedback_message = "<p class='error-message'>Erro ao salvar comunidade.</p>";
        }
        $stmt->close();
    }
}

// Lógica para Deletar
if (isset($_GET['delete_id'])) {
    $id_para_deletar = intval($_GET['delete_id']);
    $stmt = $conn->prepare("DELETE FROM comunidades WHERE id_com = ?");
    $stmt->bind_param("i", $id_para_deletar);
    if ($stmt->execute()) {
        header("Location: admin_comms.php?deleted=true");
    } else {
        header("Location: admin_comms.php?error=true");
    }
    $stmt->close();
    exit();
}

// Mensagens de feedback via GET
if (isset($_GET['deleted'])) $feedback_message = "<p class='success-message'>Comunidade deletada com sucesso!</p>";
if (isset($_GET['error'])) $feedback_message = "<p class='error-message'>Ocorreu um erro.</p>";


// Busca dados da comunidade para edição
if (isset($_GET['edit_id'])) {
    $id_para_editar = intval($_GET['edit_id']);
    $stmt = $conn->prepare("SELECT * FROM comunidades WHERE id_com = ?");
    $stmt->bind_param("i", $id_para_editar);
    $stmt->execute();
    $result = $stmt->get_result();
    $comunidade_para_editar = $result->fetch_assoc();
    $stmt->close();
}

// Busca todas as comunidades com contagem de membros
$sql_lista = "SELECT c.*, COUNT(uc.id_usu) as total_membros 
              FROM comunidades c 
              LEFT JOIN usuarios_comunidades uc ON c.id_com = uc.id_com 
              GROUP BY c.id_com 
              ORDER BY c.id_com DESC";
$resultado = $conn->query($sql_lista);
?>

<main class="container">
    <h1>Gerenciar Comunidades</h1>
    <?php echo $feedback_message; ?>

    <div class="table-container">
        <h2>Lista de Comunidades</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Descrição</th>
                    <th>Membros</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($resultado && $resultado->num_rows > 0): ?>
                    <?php while ($com = $resultado->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $com['id_com']; ?></td>
                            <td><?php echo htmlspecialchars($com['nome_com']); ?></td>
                            <td><?php echo htmlspecialchars($com['descricao_com']); ?></td>
                            <td><?php echo $com['total_membros']; ?></td>
                            <td class="actions">
                                <a href="admin_comms.php?edit_id=<?php echo $com['id_com']; ?>" class="btn btn-icon btn-edit" title="Editar">
                                    <i class="ri-pencil-line"></i>
                                </a>
                                <a href="admin_comms.php?delete_id=<?php echo $com['id_com']; ?>" onclick="return confirm('Tem certeza? A comunidade e todas as suas associações serão removidas.');" class="btn btn-icon btn-delete" title="Deletar">
                                    <i class="ri-delete-bin-7-line"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">Nenhuma comunidade encontrada.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

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
                <textarea id="descricao_com" name="descricao_com" rows="3" required><?php echo htmlspecialchars($comunidade_para_editar['descricao_com'] ?? ''); ?></textarea>
            </div>
            <button type="submit" name="save_comm" class="btn btn-primary">
                <i class="ri-save-line"></i> Salvar Comunidade
            </button>
            <?php if ($comunidade_para_editar): ?>
                <a href="admin_comms.php" class="btn" style="background-color: #ccc;">Cancelar Edição</a>
            <?php endif; ?>
        </form>
    </div>
</main>

<?php
include('admin_footer.php');
$conn->close();
?>