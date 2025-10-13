<?php
// Garante que a sessão seja iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../../config/conn.php';
require_once '../../config/log_helper.php';

// Segurança: Verifica se o usuário está logado e se o método é POST
if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../login.php');
    exit;
}

// Valida e sanitiza os dados de entrada
$user_id = $_SESSION['user_id'];
$post_id = (int)($_POST['post_id'] ?? 0);
$content = trim($_POST['content'] ?? '');

// Garante que o post existe e o conteúdo não está vazio
if ($post_id > 0 && !empty($content)) {
    try {
        $pdo->beginTransaction();

        // 1. Insere o novo comentário na tabela 'comments'
        $stmt = $pdo->prepare(
            "INSERT INTO comments (post_id, user_id, content, created_at) VALUES (:post_id, :user_id, :content, NOW())"
        );
        $stmt->execute([
            ':post_id' => $post_id,
            ':user_id' => $user_id,
            ':content' => $content
        ]);
        $comment_id = $pdo->lastInsertId();

        // 2. Atualiza o contador de respostas no post original
        $stmt_update = $pdo->prepare("UPDATE posts SET reply_count = reply_count + 1 WHERE post_id = :post_id");
        $stmt_update->execute([':post_id' => $post_id]);

        // 3. Log da ação (com os parâmetros corretos)
        $user_fullname = $_SESSION['full_name'] ?? 'Usuário Desconhecido';
        logAction($pdo, 'Novo Comentário', $user_fullname, "Usuário comentou no post ID #{$post_id}. Comentário ID #{$comment_id}.", $user_id);

        // Se tudo deu certo, confirma a transação
        $pdo->commit();
    } catch (Exception $e) {
        $pdo->rollBack();
        // Em um ambiente de produção, seria ideal logar este erro em um arquivo de log.
        // Por exemplo: error_log("Erro ao processar comentário: " . $e->getMessage());
    }
}

// Redireciona de volta para a página do post, independentemente do resultado
header('Location: ../post_view.php?id=' . $post_id);
exit;
