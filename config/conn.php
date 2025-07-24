<?php
<<<<<<< HEAD
//Variaveis que armazenam os dados de conexão
$host = "77.37.127.2";
$user = "u245002075_ifapoia_adm";
$passw = "|wleg$~8I6";
$banco = "u245002075_ifapoia";

//conexão
$conn = mysqli_connect($host, $user, $passw, $banco);
if (!$conn) {
    die("Não conectado");
}

//Faz o teste da conexão
// date_default_timezone_set('Brazil/East');
// mysqli_query($conn, "SET NAMES 'utf8'");

session_start();
=======
// Inicia a sessão se ainda não houver uma ativa.
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Configurações do banco de dados
$host = "77.37.127.2";
$username = "u245002075_ifapoia"; // ou seu utilizador do XAMPP
$passw = "Ifapoia@2024"; // ou sua senha do XAMPP
$db = "u245002075_ifapoia"; // ou o nome do seu banco local

// Cria a conexão
$conn = new mysqli($host, $username, $passw, $db);

// Verifica a conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Define o charset para UTF-8 para evitar problemas com acentuação
$conn->set_charset("utf8mb4");
>>>>>>> 7fc9a5a528a9cfe8cab2e3603e2c128b08acb1ba
