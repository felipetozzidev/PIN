<?php
require_once('../../config/conn.php');

// Define o cabeçalho da resposta como JSON
header('Content-Type: application/json');

// Garante que o usuário está logado
if (!isset($_SESSION['id_usu'])) {
    echo json_encode(['success' => false, 'error' => 'Usuário não autenticado.']);
    exit();
}

// Garante que o ID do post foi enviado
if (!isset($_POST['id_post'])) {
    echo json_encode(['success' => false, 'error' => 'ID do post não fornecido.']);
    exit();
}

$id_usu = $_SESSION['id_usu'];
$id_post = intval($_POST['id_post']);

// A transação garante que ambas as operações (na tabela 'likes' e 'posts') funcionem, ou nenhuma delas.
$conn->begin_transaction();

try {
    // Verifica se o usuário já curtiu este post
    $stmt_check = $conn->prepare("SELECT id_usu FROM likes WHERE id_usu = ? AND id_post = ?");
    $stmt_check->bind_param("ii", $id_usu, $id_post);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();
    $stmt_check->close();

    $liked = false;

    if ($result_check->num_rows > 0) {
        // Se já existe um like, o remove
        $stmt_delete = $conn->prepare("DELETE FROM likes WHERE id_usu = ? AND id_post = ?");
        $stmt_delete->bind_param("ii", $id_usu, $id_post);
        $stmt_delete->execute();
        $stmt_delete->close();

        // Decrementa o contador na tabela de posts
        $stmt_update = $conn->prepare("UPDATE posts SET cont_likes = GREATEST(0, cont_likes - 1) WHERE id_post = ?");
        $stmt_update->bind_param("i", $id_post);
        $stmt_update->execute();
        $stmt_update->close();

        $liked = false;
    } else {
        // Se não existe um like, adiciona um
        $stmt_insert = $conn->prepare("INSERT INTO likes (id_usu, id_post) VALUES (?, ?)");
        $stmt_insert->bind_param("ii", $id_usu, $id_post);
        $stmt_insert->execute();
        $stmt_insert->close();

        // Incrementa o contador na tabela de posts
        $stmt_update = $conn->prepare("UPDATE posts SET cont_likes = cont_likes + 1 WHERE id_post = ?");
        $stmt_update->bind_param("i", $id_post);
        $stmt_update->execute();
        $stmt_update->close();

        $liked = true;
    }

    // Busca o novo total de likes para retornar ao frontend
    $stmt_count = $conn->prepare("SELECT cont_likes FROM posts WHERE id_post = ?");
    $stmt_count->bind_param("i", $id_post);
    $stmt_count->execute();
    $result_count = $stmt_count->get_result()->fetch_assoc();
    $new_like_count = $result_count['cont_likes'];
    $stmt_count->close();

    // Se tudo deu certo, confirma as operações no banco de dados
    $conn->commit();

    // Retorna uma resposta de sucesso em formato json
    echo json_encode([
        'success' => true,
        'liked' => $liked,
        'new_like_count' => $new_like_count
    ]);
} catch (mysqli_sql_exception $exception) {
    // Se qualquer operação falhar, desfaz tudo
    $conn->rollback();
    echo json_encode(['success' => false, 'error' => 'Erro no banco de dados: ' . $exception->getMessage()]);
}

$conn->close();
