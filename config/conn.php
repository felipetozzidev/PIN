<?php
session_start();

//Variaveis que armazenam os dados de conexão
$host = "srv1435.hstgr.io";
$user = "u245002075_ifapoia_adm_2";
$passw = "KzV[sCsD4Mv~";
$banco = "u245002075_ifapoia2";

//conexão
$conn = mysqli_connect($host, $user, $passw, $banco);
if (!$conn) {
    die("Não conectado");
}

//Faz o teste da conexão
// date_default_timezone_set('Brazil/East');
// mysqli_query($conn, "SET NAMES 'utf8'");
