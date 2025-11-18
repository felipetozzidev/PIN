<?php
// Função para registrar atividades no log de auditoria
function logAction($pdo, $event_type, $user_fullname, $details, $user_id = null)
{
    try {
        $stmt = $pdo->prepare(
            "INSERT INTO audit_log (event_type, action_user_fullname, event_details, action_user_id) 
             VALUES (:event_type, :user_fullname, :details, :user_id)"
        );
        $stmt->execute([
            ':event_type' => $event_type,
            ':user_fullname' => $user_fullname,
            ':details' => $details,
            ':user_id' => $user_id
        ]);
    } catch (PDOException $e) {
        // error_log('Erro ao registrar log de auditoria: ' . $e->getMessage());
    }
}
