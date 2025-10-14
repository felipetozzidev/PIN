<?php
// Garante que a sessão seja iniciada apenas uma vez.
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// --- SUGESTÃO DE MELHORIA: Usar variáveis de ambiente para as credenciais ---
// Em um ambiente de produção, o ideal seria carregar estas variáveis de um
// arquivo .env ou das configurações do servidor, para não expô-las no código.
// Exemplo:
// $host = getenv('DB_HOST');
// $db_name = getenv('DB_NAME');
// ... e assim por diante.

// Para fins de desenvolvimento, manteremos as variáveis como estão.
$host = 'localhost';
$db_name = 'u245002075_ifapoia2';
$username = 'u245002075_admin_ifapoia';
$password = 's|8WRsV@|v';
$charset = 'utf8'; // É uma boa prática definir o charset.

// Opções do PDO para um comportamento mais seguro e previsível.
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,      // Lança exceções em caso de erros.
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,          // Retorna resultados como arrays associativos.
    PDO::ATTR_EMULATE_PREPARES   => false,                     // Usa prepared statements nativos do MySQL.
    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
];

// DSN (Data Source Name) - String de conexão.
$dsn = "mysql:host=$host;dbname=$db_name;charset=$charset";

try {
    // Tenta criar a instância do PDO (a conexão com o banco).
    // CORREÇÃO: Usando as variáveis corretas ($username, $password).
    $pdo = new PDO($dsn, $username, $password, $options);
} catch (\PDOException $e) {
    // Em caso de falha na conexão, registra o erro real em um arquivo de log
    // e exibe uma mensagem genérica para o usuário.
    //error_log("Erro de conexão com o banco de dados: " . $e->getMessage());
    // Em um ambiente de produção, você pode querer redirecionar para uma página de erro.
    die("Erro de Conexão: " . $e->getMessage());
}

// A variável $pdo agora está disponível para ser usada em outros arquivos que incluírem este.
