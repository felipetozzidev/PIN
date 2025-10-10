<?php
require_once('../../config/conn.php');
require_once('../../config/log_helper.php');
include('admin_header.php');

$feedback_message = '';
$edit_mode = false;
$grupo_para_editar = null;
$members_list = [];
$admin_user_name = $_SESSION['nome_usu'] ?? 'Administrador';

// --- PROCESSAMENTO DE AÇÕES ---

// 1. ADICIONAR OU ATUALIZAR GRUPO (via POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_group'])) {
    $nome_gp = trim($_POST['nome_gp']);
    $descricao_gp = trim($_POST['descricao_gp']);
    $id_gp = isset($_POST['id_gp']) ? intval($_POST['id_gp']) : 0;

    if (empty($nome_gp)) {
        $feedback_message = "<p class='error-message'>O nome do grupo é obrigatório.</p>";
    } else {
        if ($id_gp > 0) { // Atualizar
            $stmt = $conn->prepare("UPDATE grupos SET nome_gp = ?, descricao_gp = ? WHERE id_gp = ?");
            $stmt->bind_param("ssi", $nome_gp, $descricao_gp, $id_gp);
            if ($stmt->execute()) {
                $feedback_message = "<p class='success-message'>Grupo atualizado com sucesso!</p>";
                log_activity($conn, 'Grupo Editado', $admin_user_name, "Grupo '{$nome_gp}' (ID: #{$id_gp}) foi atualizado.");
            } else {
                $feedback_message = "<p class='error-message'>Erro ao atualizar o grupo.</p>";
            }
        } else { // Inserir
            $id_criador = $_SESSION['id_usu'];
            $stmt = $conn->prepare("INSERT INTO grupos (nome_gp, descricao_gp, id_criador) VALUES (?, ?, ?)");
            $stmt->bind_param("ssi", $nome_gp, $descricao_gp, $id_criador);
            if ($stmt->execute()) {
                $new_id = $conn->insert_id;
                $feedback_message = "<p class='success-message'>Grupo criado com sucesso!</p>";
                log_activity($conn, 'Novo Grupo', $admin_user_name, "Grupo '{$nome_gp}' (ID: #{$new_id}) foi criado.");
            } else {
                $feedback_message = "<p class='error-message'>Erro ao criar o grupo.</p>";
            }
        }
        $stmt->close();
    }
}

// 2. AÇÕES VIA GET (DELETAR GRUPO, EDITAR, REMOVER MEMBRO)
if (isset($_GET['action'])) {
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;

    // Ação para deletar o grupo
    if ($_GET['action'] == 'delete' && $id > 0) {
        // Buscar nome do grupo antes de deletar
        $stmt_get_name = $conn->prepare("SELECT nome_gp FROM grupos WHERE id_gp = ?");
        $stmt_get_name->bind_param("i", $id);
        $stmt_get_name->execute();
        $group_data = $stmt_get_name->get_result()->fetch_assoc();
        $group_name_for_log = $group_data ? $group_data['nome_gp'] : 'Desconhecido';
        $stmt_get_name->close();

        $stmt = $conn->prepare("DELETE FROM grupos WHERE id_gp = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            $feedback_message = "<p class='success-message'>Grupo deletado com sucesso!</p>";
            log_activity($conn, 'Grupo Excluído', $admin_user_name, "Grupo '{$group_name_for_log}' (ID: #{$id}) foi excluído.");
        } else {
            $feedback_message = "<p class='error-message'>Erro ao deletar o grupo.</p>";
        }
        $stmt->close();
    }

    // Ação para entrar no modo de edição
    if ($_GET['action'] == 'edit' && $id > 0) {
        $edit_mode = true;
        $stmt_group = $conn->prepare("SELECT * FROM grupos WHERE id_gp = ?");
        $stmt_group->bind_param("i", $id);
        $stmt_group->execute();
        $grupo_para_editar = $stmt_group->get_result()->fetch_assoc();
        $stmt_group->close();

        // Busca lista de membros do grupo
        $stmt_members = $conn->prepare("
            SELECT u.id_usu, u.nome_usu, u.email_usu, u.imgperfil_usu, ug.data_entrada
            FROM usuarios_grupos ug
            JOIN usuarios u ON ug.id_usu = u.id_usu
            WHERE ug.id_gp = ?
            ORDER BY u.nome_usu ASC
        ");
        $stmt_members->bind_param("i", $id);
        $stmt_members->execute();
        $result_members = $stmt_members->get_result();
        while ($row = $result_members->fetch_assoc()) {
            $members_list[] = $row;
        }
        $stmt_members->close();
    }

    // Ação para remover um membro do grupo
    if ($_GET['action'] == 'remove_member' && isset($_GET['user_id']) && isset($_GET['group_id'])) {
        $user_id = intval($_GET['user_id']);
        $group_id = intval($_GET['group_id']);

        // Buscar nomes para o log
        $stmt_info = $conn->prepare("SELECT u.nome_usu, g.nome_gp FROM usuarios u, grupos g WHERE u.id_usu = ? AND g.id_gp = ?");
        $stmt_info->bind_param("ii", $user_id, $group_id);
        $stmt_info->execute();
        $info_data = $stmt_info->get_result()->fetch_assoc();
        $user_name_for_log = $info_data['nome_usu'] ?? 'ID ' . $user_id;
        $group_name_for_log = $info_data['nome_gp'] ?? 'ID ' . $group_id;
        $stmt_info->close();

        $stmt = $conn->prepare("DELETE FROM usuarios_grupos WHERE id_usu = ? AND id_gp = ?");
        $stmt->bind_param("ii", $user_id, $group_id);
        if ($stmt->execute()) {
            log_activity($conn, 'Membro Removido', $admin_user_name, "Usuário '{$user_name_for_log}' foi removido do grupo '{$group_name_for_log}'.");
            header("Location: admin_gps.php?action=edit&id=$group_id&feedback=member_removed");
            exit();
        } else {
            header("Location: admin_gps.php?action=edit&id=$group_id&feedback=error");
            exit();
        }
    }
}

if (isset($_GET['feedback'])) {
    if ($_GET['feedback'] == 'member_removed') {
        $feedback_message = "<p class='success-message'>Membro removido do grupo com sucesso!</p>";
    }
    if ($_GET['feedback'] == 'error') {
        $feedback_message = "<p class='error-message'>Ocorreu um erro ao processar a solicitação.</p>";
    }
}

// --- CONSULTA PARA EXIBIR A TABELA DE GRUPOS ---
$search_query = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
$sql = "SELECT g.id_gp, g.nome_gp, g.descricao_gp, g.data_criacao, u.nome_usu as nome_criador, COUNT(ug.id_usu) as total_membros
        FROM grupos g
        JOIN usuarios u ON g.id_criador = u.id_usu
        LEFT JOIN usuarios_grupos ug ON g.id_gp = ug.id_gp";
if (!empty($search_query)) {
    $sql .= " WHERE g.nome_gp LIKE '%$search_query%' OR g.descricao_gp LIKE '%$search_query%'";
}
$sql .= " GROUP BY g.id_gp ORDER BY g.data_criacao DESC";
$resultado = $conn->query($sql);
?>

<main class="container">
    <div class="page-header">
        <h1>Grupos</h1>
        <form action="admin_gps.php" method="GET" class="search-form">
            <input type="search" name="search" placeholder="Buscar por nome ou descrição..." value="<?php echo htmlspecialchars($search_query); ?>">
            <button type="submit" class="btn btn-primary"><i class="ri-search-line"></i></button>
        </form>
    </div>

    <?php echo $feedback_message; ?>

    <div class="table-container">
        <h2>Lista de Grupos</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome do Grupo</th>
                    <th>Criador</th>
                    <th>Membros</th>
                    <th>Data de Criação</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($resultado && $resultado->num_rows > 0): ?>
                    <?php while ($grupo = $resultado->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $grupo['id_gp']; ?></td>
                            <td><?php echo htmlspecialchars($grupo['nome_gp']); ?></td>
                            <td><?php echo htmlspecialchars($grupo['nome_criador']); ?></td>
                            <td><?php echo $grupo['total_membros']; ?></td>
                            <td><?php echo date("d/m/Y H:i", strtotime($grupo['data_criacao'])); ?></td>
                            <td class="actions">
                                <a href="admin_gps.php?action=edit&id=<?php echo $grupo['id_gp']; ?>#form-gp" class="btn btn-icon btn-edit" title="Editar Grupo">
                                    <i class="ri-pencil-line"></i>
                                </a>
                                <a href="admin_gps.php?action=delete&id=<?php echo $grupo['id_gp']; ?>" onclick="return confirm('Tem certeza que deseja deletar este grupo? Esta ação é irreversível.');" class="btn btn-icon btn-delete" title="Deletar Grupo">
                                    <i class="ri-delete-bin-line"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6">Nenhum grupo encontrado.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="form-container" id="form-gp">
        <h2><?php echo $edit_mode ? 'Editar Grupo' : 'Adicionar Novo Grupo'; ?></h2>
        <form action="admin_gps.php" method="POST">
            <?php if ($edit_mode): ?>
                <input type="hidden" name="id_gp" value="<?php echo $grupo_para_editar['id_gp']; ?>">
            <?php endif; ?>

            <div class="form-group">
                <label for="nome_gp">Nome do Grupo:</label>
                <input type="text" id="nome_gp" name="nome_gp" value="<?php echo htmlspecialchars($grupo_para_editar['nome_gp'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="descricao_gp">Descrição:</label>
                <textarea id="descricao_gp" name="descricao_gp" rows="3"><?php echo htmlspecialchars($grupo_para_editar['descricao_gp'] ?? ''); ?></textarea>
            </div>

            <?php if ($edit_mode && !empty($members_list)): ?>
                <div class="form-group">
                    <h2>Membros do Grupo "<?php echo htmlspecialchars($grupo_para_editar['nome_gp']); ?>"</h2>
                    <table>
                        <thead>
                            <tr>
                                <th>Membro</th>
                                <th>Email</th>
                                <th>Data de Entrada</th>
                                <th>Ação</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($members_list as $member): ?>
                                <tr>
                                    <td>
                                        <div class="user-info">
                                            <?php
                                            $db_path = $member['imgperfil_usu'];
                                            $image_path = $db_path;
                                            // Verifica se o caminho começa com '../' e o ajusta
                                            if (substr($db_path, 0, 3) === '../') {
                                                $image_path = '../../' . substr($db_path, 3);
                                            }
                                            ?>
                                            <img src="<?php echo htmlspecialchars($image_path); ?>" alt="Foto de Perfil">
                                            <span><?php echo htmlspecialchars($member['nome_usu']); ?></span>
                                        </div>
                                    </td>
                                    <td><?php echo htmlspecialchars($member['email_usu']); ?></td>
                                    <td><?php echo date("d/m/Y", strtotime($member['data_entrada'])); ?></td>
                                    <td class="actions">
                                        <a href="admin_gps.php?action=remove_member&user_id=<?php echo $member['id_usu']; ?>&group_id=<?php echo $grupo_para_editar['id_gp']; ?>" onclick="return confirm('Tem certeza que deseja remover este membro do grupo?');" class="btn btn-icon btn-delete" title="Remover Membro">
                                            <i class="ri-user-unfollow-line"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php elseif ($edit_mode): ?>
                <div class="form-group">
                    <h2>Membros do Grupo</h2>
                    <p>Este grupo ainda não possui membros.</p>
                </div>
            <?php endif; ?>

            <button type="submit" name="save_group" class="btn btn-primary"><?php echo $edit_mode ? 'Salvar Alterações' : 'Adicionar Grupo'; ?></button>
            <?php if ($edit_mode): ?>
                <a href="admin_gps.php" class="btn">Cancelar Edição</a>
            <?php endif; ?>
        </form>
    </div>
</main>

<?php
include('admin_footer.php');
$conn->close();
?>