<?php
// Inicia a sessão se ainda não houver uma ativa.
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Configurações do banco de dados
$host = "localhost";
$username = "root"; // ou seu utilizador do XAMPP
$passw = ""; // ou sua senha do XAMPP
$db = "ifapoia"; // ou o nome do seu banco local

// Cria a conexão
$conn = new mysqli($host, $username, $passw, $db);

// Verifica a conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Define o charset para UTF-8 para evitar problemas com acentuação
$conn->set_charset("utf8mb4");
