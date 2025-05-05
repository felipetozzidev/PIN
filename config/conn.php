<?php
    //Variaveis que armazenam os dados de conexão
    $localhost = "localhost";
    $user = "root";
    $passw = "";
    $banco = "IFApoia";

    //conexão
    $conn = mysqli_connect($localhost, $user, $passw, $banco);
    if(!$conn){
        die("Não conectado");
    }

    //Faz o teste da conexão
        date_default_timezone_set('Brazil/East');
        mysqli_query($conn, "SET NAMES 'utf8'");  

    ?>