<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json');
require_once('../../config/conn.php');

if (!isset($_SESSION['user_id']) || !isset($_POST['post_id'])) {
    echo json_encode(['success' => false, 'error' => 'Usuário não autenticado.']);
    exit();
}

$user_id = $_SESSION['user_id'];
$post_id = intval($_POST['post_id']);

try {
    $pdo->beginTransaction();

    // 1. Verifica se já existe o repost na tabela 'reposts' (do seu dump)
    $stmt_check = $pdo->prepare("SELECT 1 FROM reposts WHERE user_id = :user_id AND post_id = :post_id");
    $stmt_check->execute(['user_id' => $user_id, 'post_id' => $post_id]);
    $exists = $stmt_check->fetch();

    $is_reposted = false;

    if ($exists) {
        // SE JÁ REPOSTOU: Remove (Undo)
        $stmt_delete = $pdo->prepare("DELETE FROM reposts WHERE user_id = :user_id AND post_id = :post_id");
        $stmt_delete->execute(['user_id' => $user_id, 'post_id' => $post_id]);

        // Decrementa o contador na tabela 'posts'
        $stmt_update = $pdo->prepare("UPDATE posts SET repost_count = GREATEST(0, repost_count - 1) WHERE post_id = :post_id");
        $stmt_update->execute(['post_id' => $post_id]);
    } else {
        // SE NÃO REPOSTOU: Adiciona
        $stmt_insert = $pdo->prepare("INSERT INTO reposts (user_id, post_id) VALUES (:user_id, :post_id)");
        $stmt_insert->execute(['user_id' => $user_id, 'post_id' => $post_id]);

        // Incrementa o contador na tabela 'posts'
        $stmt_update = $pdo->prepare("UPDATE posts SET repost_count = repost_count + 1 WHERE post_id = :post_id");
        $stmt_update->execute(['post_id' => $post_id]);

        $is_reposted = true;
    }

    // Retorna a nova contagem
    $stmt_count = $pdo->prepare("SELECT repost_count FROM posts WHERE post_id = :post_id");
    $stmt_count->execute(['post_id' => $post_id]);
    $new_count = $stmt_count->fetchColumn();

    $pdo->commit();

    echo json_encode([
        'success' => true,
        'reposted' => $is_reposted,
        'new_count' => $new_count
    ]);
} catch (PDOException $e) {
    $pdo->rollBack();
    echo json_encode(['success' => false, 'error' => 'Erro no banco: ' . $e->getMessage()]);
}
