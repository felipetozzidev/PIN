<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json');

// Caminhos absolutos para evitar erros de "arquivo não encontrado"
require_once __DIR__ . '/../../config/conn.php';
require_once __DIR__ . '/../../config/log_helper.php';

// Verifica login
if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Não autorizado.']);
    exit;
}

$user_id = $_SESSION['user_id'];
$post_id = (int)($_POST['post_id'] ?? 0);
$content = trim($_POST['content'] ?? '');

if ($post_id > 0 && !empty($content)) {
    try {
        $pdo->beginTransaction();

        // 1. Insere comentário
        $stmt = $pdo->prepare("INSERT INTO comments (post_id, user_id, content, created_at) VALUES (:post_id, :user_id, :content, NOW())");
        $stmt->execute([':post_id' => $post_id, ':user_id' => $user_id, ':content' => $content]);
        $comment_id = $pdo->lastInsertId();

        // 2. Atualiza contador
        $stmt_upd = $pdo->prepare("UPDATE posts SET reply_count = reply_count + 1 WHERE post_id = :post_id");
        $stmt_upd->execute([':post_id' => $post_id]);

        // 3. Log (Verifique se a função logAction existe no log_helper.php)
        if (function_exists('logAction')) {
            $user_fullname = $_SESSION['full_name'] ?? 'Usuário';
            logAction($pdo, 'Novo Comentário', $user_fullname, "Comentário ID #{$comment_id} no post #{$post_id}", $user_id);
        }

        $pdo->commit();
        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        $pdo->rollBack();
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Conteúdo vazio.']);
}
