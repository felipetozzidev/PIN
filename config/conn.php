<?php
// Inicia sessão apenas se não existir
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// --- CONFIGURAÇÕES DO BANCO DE DADOS LOCAL (XAMPP) ---
$host = '77.37.127.2';                 // Host padrão do XAMPP
$db = 'u245002075_ifapoia2';                   // O nome do seu banco de dados local
$user = 'u245002075_admin_ifapoia';                      // Usuário padrão do XAMPP
$pass = 's|8WRsV@|v';                          // Senha padrão do XAMPP (vazia)
$port = '3306';                      // Porta padrão do MySQL no XAMPP
$charset = 'utf8';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
?>