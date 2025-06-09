<?php
session_start();
session_destroy();
header("Location: ../../public/index.php"); // Redireciona para a página inicial
exit();
