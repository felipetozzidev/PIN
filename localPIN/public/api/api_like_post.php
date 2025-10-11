<?php
// Garante que a sessão seja iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json');

require_once('../../config/conn.php');

// Garante que o usuário está logado e que o ID do post foi enviado
if (!isset($_SESSION['user_id']) || !isset($_POST['post_id'])) {
    echo json_encode(['success' => false, 'error' => 'Usuário não autenticado ou post_id não fornecido.']);
    exit();
}

$user_id = $_SESSION['user_id'];
$post_id = intval($_POST['post_id']);

try {
    // A transação garante que todas as operações funcionem ou nenhuma delas.
    $pdo->beginTransaction();

    // Verifica se o usuário já curtiu este post
    $stmt_check = $pdo->prepare("SELECT user_id FROM likes WHERE user_id = :user_id AND post_id = :post_id");
    $stmt_check->execute(['user_id' => $user_id, 'post_id' => $post_id]);

    $liked = false;

    if ($stmt_check->fetch()) {
        // Se já existe um like, o remove
        $stmt_delete = $pdo->prepare("DELETE FROM likes WHERE user_id = :user_id AND post_id = :post_id");
        $stmt_delete->execute(['user_id' => $user_id, 'post_id' => $post_id]);

        // Decrementa o contador na tabela de posts, garantindo que não seja negativo
        $stmt_update = $pdo->prepare("UPDATE posts SET like_count = GREATEST(0, like_count - 1) WHERE post_id = :post_id");
        $stmt_update->execute(['post_id' => $post_id]);

        $liked = false;
    } else {
        // Se não existe um like, adiciona um
        $stmt_insert = $pdo->prepare("INSERT INTO likes (user_id, post_id) VALUES (:user_id, :post_id)");
        $stmt_insert->execute(['user_id' => $user_id, 'post_id' => $post_id]);

        // Incrementa o contador na tabela de posts
        $stmt_update = $pdo->prepare("UPDATE posts SET like_count = like_count + 1 WHERE post_id = :post_id");
        $stmt_update->execute(['post_id' => $post_id]);

        $liked = true;
    }

    // Busca o novo total de likes para retornar ao frontend
    $stmt_count = $pdo->prepare("SELECT like_count FROM posts WHERE post_id = :post_id");
    $stmt_count->execute(['post_id' => $post_id]);
    $result_count = $stmt_count->fetch(PDO::FETCH_ASSOC);
    $new_like_count = $result_count['like_count'];

    // Se tudo deu certo, confirma as operações no banco de dados
    $pdo->commit();

    // Retorna uma resposta de sucesso em formato json
    echo json_encode([
        'success' => true,
        'liked' => $liked,
        'new_like_count' => $new_like_count
    ]);
} catch (PDOException $e) {
    // Se qualquer operação falhar, desfaz tudo
    $pdo->rollBack();
    echo json_encode(['success' => false, 'error' => 'Erro no banco de dados: ' . $e->getMessage()]);
}
