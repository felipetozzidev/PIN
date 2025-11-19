<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json');

// Caminhos absolutos seguros
require_once __DIR__ . '/../../config/conn.php';

if (!isset($_SESSION['user_id']) || !isset($_POST['post_id'])) {
    echo json_encode(['success' => false, 'error' => 'Não autorizado ou dados faltando.']);
    exit();
}

$user_id = $_SESSION['user_id'];
$post_id = intval($_POST['post_id']);

try {
    $pdo->beginTransaction();

    // Verifica se já existe (Tabela 'reposts')
    $stmt_check = $pdo->prepare("SELECT 1 FROM reposts WHERE user_id = ? AND post_id = ?");
    $stmt_check->execute([$user_id, $post_id]);

    $reposted = false;

    if ($stmt_check->fetch()) {
        // Remove
        $stmt_del = $pdo->prepare("DELETE FROM reposts WHERE user_id = ? AND post_id = ?");
        $stmt_del->execute([$user_id, $post_id]);

        $stmt_upd = $pdo->prepare("UPDATE posts SET repost_count = GREATEST(0, repost_count - 1) WHERE post_id = ?");
        $stmt_upd->execute([$post_id]);
    } else {
        // Adiciona
        $stmt_ins = $pdo->prepare("INSERT INTO reposts (user_id, post_id) VALUES (?, ?)");
        $stmt_ins->execute([$user_id, $post_id]);

        $stmt_upd = $pdo->prepare("UPDATE posts SET repost_count = repost_count + 1 WHERE post_id = ?");
        $stmt_upd->execute([$post_id]);

        $reposted = true;
    }

    $stmt_cnt = $pdo->prepare("SELECT repost_count FROM posts WHERE post_id = ?");
    $stmt_cnt->execute([$post_id]);
    $new_count = $stmt_cnt->fetchColumn();

    $pdo->commit();

    echo json_encode(['success' => true, 'reposted' => $reposted, 'new_count' => $new_count]);
} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
