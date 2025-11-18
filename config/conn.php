<?php
// Garante que a sessão seja iniciada apenas uma vez.
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

date_default_timezone_set('America/Sao_Paulo');

// --- CONFIGURAÇÕES DO BANCO DE DADOS LOCAL (XAMPP) ---
$db_host = '77.37.127.2';                 // Host padrão do XAMPP
$db_name = 'u245002075_ifapoia2';                   // O nome do seu banco de dados local
$db_user = 'u245002075_admin_ifapoia';                      // Usuário padrão do XAMPP
$db_pass = 's|8WRsV@|v';                          // Senha padrão do XAMPP (vazia)
$db_port = '3306';                      // Porta padrão do MySQL no XAMPP
$charset = 'utf8';

// Data Source Name (DSN) para a conexão PDO.
$dsn = "mysql:host=$db_host;port=$db_port;dbname=$db_name;charset=$charset";

// Opções do PDO para um comportamento mais seguro e previsível.
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    // Tenta criar a instância do PDO (a conexão com o banco).
    $pdo = new PDO($dsn, $db_user, $db_pass, $options);
    $pdo->exec("SET time_zone='-03:00'");
} catch (\PDOException $e) {
    // Falha na conexão, exibe uma mensagem de erro detalhada (seguro para ambiente local).
    die("Erro de conexão com o banco de dados local: " . $e->getMessage());
}
