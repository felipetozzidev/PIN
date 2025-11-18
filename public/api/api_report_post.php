<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json');
require_once('../../config/conn.php');

// Verifica se recebeu o ID e o Motivo
if (!isset($_SESSION['user_id']) || !isset($_POST['post_id']) || !isset($_POST['reason'])) {
    echo json_encode(['success' => false, 'error' => 'Dados incompletos.']);
    exit();
}

$reporter_id = $_SESSION['user_id'];
$post_id = intval($_POST['post_id']);
$reason = trim($_POST['reason']);

if (empty($reason)) {
    echo json_encode(['success' => false, 'error' => 'O motivo da denúncia é obrigatório.']);
    exit();
}

try {
    // Verifica se já denunciou esse mesmo post recentemente (evita spam)
    // A tabela 'reports' usa 'target_id' e 'target_type'
    $stmt_check = $pdo->prepare("SELECT report_id FROM reports WHERE reporter_id = ? AND target_type = 'post' AND target_id = ? AND status = 'pendente'");
    $stmt_check->execute([$reporter_id, $post_id]);

    if ($stmt_check->rowCount() > 0) {
        echo json_encode(['success' => false, 'error' => 'Você já possui uma denúncia em análise para este post.']);
        exit();
    }

    // Insere na tabela 'reports'
    $sql = "INSERT INTO reports (reporter_id, target_type, target_id, reason, status) VALUES (:reporter, 'post', :id, :reason, 'pendente')";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':reporter' => $reporter_id,
        ':id' => $post_id,
        ':reason' => $reason
    ]);

    echo json_encode(['success' => true, 'message' => 'Denúncia enviada. Nossa equipe irá analisar.']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'Erro ao salvar denúncia: ' . $e->getMessage()]);
}
