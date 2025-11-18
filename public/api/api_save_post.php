<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json');
require_once('../../config/conn.php');

if (!isset($_SESSION['user_id']) || !isset($_POST['post_id'])) {
    echo json_encode(['success' => false, 'error' => 'Dados inválidos.']);
    exit();
}

$user_id = $_SESSION['user_id'];
$post_id = intval($_POST['post_id']);

try {
    $pdo->beginTransaction();

    // 1. Verifica na tabela 'bookmarks'
    $stmt_check = $pdo->prepare("SELECT 1 FROM bookmarks WHERE user_id = :user_id AND post_id = :post_id");
    $stmt_check->execute(['user_id' => $user_id, 'post_id' => $post_id]);

    $is_saved = false;

    if ($stmt_check->fetch()) {
        // REMOVER DOS SALVOS
        $stmt_del = $pdo->prepare("DELETE FROM bookmarks WHERE user_id = :user_id AND post_id = :post_id");
        $stmt_del->execute(['user_id' => $user_id, 'post_id' => $post_id]);

        // Atualiza contador na tabela posts
        $stmt_upd = $pdo->prepare("UPDATE posts SET bookmark_count = GREATEST(0, bookmark_count - 1) WHERE post_id = :post_id");
        $stmt_upd->execute(['post_id' => $post_id]);
    } else {
        // ADICIONAR AOS SALVOS
        $stmt_ins = $pdo->prepare("INSERT INTO bookmarks (user_id, post_id) VALUES (:user_id, :post_id)");
        $stmt_ins->execute(['user_id' => $user_id, 'post_id' => $post_id]);

        // Atualiza contador na tabela posts
        $stmt_upd = $pdo->prepare("UPDATE posts SET bookmark_count = bookmark_count + 1 WHERE post_id = :post_id");
        $stmt_upd->execute(['post_id' => $post_id]);

        $is_saved = true;
    }

    // Busca nova contagem
    $stmt_count = $pdo->prepare("SELECT bookmark_count FROM posts WHERE post_id = :post_id");
    $stmt_count->execute(['post_id' => $post_id]);
    $new_count = $stmt_count->fetchColumn();

    $pdo->commit();

    echo json_encode([
        'success' => true,
        'bookmarked' => $is_saved, // Usado pelo JS para mudar a cor do ícone
        'new_count' => $new_count
    ]);
} catch (PDOException $e) {
    $pdo->rollBack();
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
