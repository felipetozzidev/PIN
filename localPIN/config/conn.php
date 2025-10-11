<?php

$host = '127.0.0.1';     // Endereço do servidor do banco de dados.
$db_name = 'ifapoia';    // Nome do banco de dados.
$username = 'root';      // Nome de usuário para acesso ao banco.
$password = '';          // Senha para acesso ao banco.
$port = 3306;            // Porta de conexão. É importante especificar, pois pode variar.

try {
    // Cria a string de conexão (DSN - Data Source Name)
    $dsn = "mysql:host=$host;port=$port;dbname=$db_name;charset=utf8";

    // Cria uma nova instância de PDO para a conexão
    $pdo = new PDO($dsn, $username, $password);

    // Define o modo de erro do PDO para exceção, para que erros de SQL sejam lançados
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Em caso de falha na conexão, exibe uma mensagem de erro genérica e termina o script.
    // Em produção, é recomendado logar o erro em um arquivo em vez de exibi-lo na tela.
    // error_log("Erro de conexão: " . $e->getMessage());
    die("Não foi possível conectar ao banco de dados. Por favor, tente mais tarde.");
}
