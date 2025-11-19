<?php
// Garante que a sessão seja iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json');

// Caminhos absolutos para evitar erros
require_once __DIR__ . '/../../config/conn.php';
require_once __DIR__ . '/../../config/log_helper.php';

// Verifica se recebeu o ID e o Motivo
if (!isset($_SESSION['user_id']) || !isset($_POST['target_id']) || !isset($_POST['reason'])) {
    echo json_encode(['success' => false, 'message' => 'Dados incompletos.']);
    exit();
}

$reporter_id = $_SESSION['user_id'];
$target_id = intval($_POST['target_id']);
$reason = trim($_POST['reason']);
$target_type = $_POST['target_type'] ?? 'post';

if (empty($reason)) {
    echo json_encode(['success' => false, 'message' => 'O motivo da denúncia é obrigatório.']);
    exit();
}

try {
    // 1. Verifica se já denunciou esse mesmo item recentemente
    $stmt_check = $pdo->prepare("SELECT report_id FROM reports WHERE reporter_id = ? AND target_type = ? AND target_id = ? AND status = 'pendente'");
    $stmt_check->execute([$reporter_id, $target_type, $target_id]);

    if ($stmt_check->rowCount() > 0) {
        echo json_encode(['success' => false, 'message' => 'Você já possui uma denúncia em análise para este item.']);
        exit();
    }

    // 2. Insere na tabela 'reports'
    $pdo->beginTransaction();

    $sql = "INSERT INTO reports (reporter_id, target_type, target_id, reason, status, reported_at) VALUES (:reporter, :type, :id, :reason, 'pendente', NOW())";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':reporter' => $reporter_id,
        ':type' => $target_type,
        ':id' => $target_id,
        ':reason' => $reason
    ]);
    $report_id = $pdo->lastInsertId();

    // 3. Registra no Log (Audit Log)
    if (function_exists('logAction')) {
        $user_fullname = $_SESSION['full_name'] ?? 'Usuário';
        logAction($pdo, 'Nova Denúncia', $user_fullname, "Usuário denunciou o {$target_type} ID #{$target_id}. Motivo: {$reason}", $reporter_id);
    }

    $pdo->commit();

    echo json_encode(['success' => true, 'message' => 'Denúncia enviada com sucesso! Nossa equipe irá analisar.']);
} catch (PDOException $e) {
    $pdo->rollBack();
    // Retorna o erro real do banco para você ver no alert do JS
    echo json_encode(['success' => false, 'message' => 'Erro no banco de dados: ' . $e->getMessage()]);
}
