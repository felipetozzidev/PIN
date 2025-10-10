<?php
// config/log_helper.php

/**
 * Registra uma atividade no log de auditoria do sistema.
 *
 * @param mysqli $conn A conexão com o banco de dados.
 * @param string $tipo_evento O tipo de evento (ex: 'Denúncia Resolvida', 'Nível Alterado').
 * @param string $assunto_principal O sujeito da ação (ex: o nome do usuário que realizou a ação).
 * @param string $detalhes_evento Uma descrição detalhada da ação (ex: 'Status alterado para Resolvida no Post ID #15').
 */
function log_activity($conn, $tipo_evento, $assunto_principal, $detalhes_evento)
{
    // Pega o ID do usuário da sessão, se ele estiver logado.
    $id_usuario_acao = isset($_SESSION['id_usu']) ? $_SESSION['id_usu'] : null;

    $stmt = $conn->prepare(
        "INSERT INTO audit_log (id_usuario_acao, tipo_evento, assunto_principal, detalhes_evento) VALUES (?, ?, ?, ?)"
    );
    // O tipo 'isss' corresponde a: integer, string, string, string
    $stmt->bind_param("isss", $id_usuario_acao, $tipo_evento, $assunto_principal, $detalhes_evento);
    $stmt->execute();
    $stmt->close();
}
