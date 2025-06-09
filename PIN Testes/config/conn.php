<?php
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
