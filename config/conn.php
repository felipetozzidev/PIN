<?php
//Variaveis que armazenam os dados de conexão
$host = "localhost";
$user = "root";
$passw = "";
$banco = "ifapoia";

//conexão
$conn = mysqli_connect($host, $user, $passw, $banco);
if (!$conn) {
    die("Não conectado" . mysqli_connect_error());
}

//Faz o teste da conexão
// date_default_timezone_set('Brazil/East');
// mysqli_query($conn, "SET NAMES 'utf8'");

session_start();
