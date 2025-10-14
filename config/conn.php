<?php
// Garante que a sessão seja iniciada apenas uma vez.
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$host = 'srv1435.hstgr.io';     // Endereço do servidor do banco de dados.
$db_name = 'u245002075_ifapoia2';    // Nome do banco de dados.
$username = 'u245002075_admin_ifapoia';      // Nome de usuário para acesso ao banco.
$password = 's|8WRsV@|v';          // Senha para acesso ao banco.
$port = 65002;            // Porta de conexão. É importante especificar, pois pode variar.

// Opções do PDO para um comportamento mais seguro e previsível.
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,      // Lança exceções em caso de erros.
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,          // Retorna resultados como arrays associativos.
    PDO::ATTR_EMULATE_PREPARES   => false,                     // Usa prepared statements nativos do MySQL.
];

try {
    // Tenta criar a instância do PDO (a conexão com o banco).
    $pdo = new PDO($dsn, $db_user, $db_pass, $options);
} catch (\PDOException $e) {
    // Em caso de falha na conexão, exibe uma mensagem genérica para o usuário
    // e registra o erro real em um arquivo de log (ideal para produção).
    error_log("Erro de conexão com o banco de dados: " . $e->getMessage());
    die("Ocorreu um erro ao conectar ao servidor. Por favor, tente novamente mais tarde.");
}
