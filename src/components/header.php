<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Header IFApoia</title>
</head>

<body>
    <nav class="navbar navbar-expand-md fixed" id="nav">

        <div class="navbar-brand col-4">
            <a href="">
                <img src="../src/assets/img/Logotipo_antiga.png" alt="" class="logo">
            </a>
        </div>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse col-8" id="navbarCollapse">
            <div class="search navbar-nav col-6">
                <form class="d-flex" role="search" action="index.php" method="GET">
                    <i class="ri-search-line"></i>
                    <input class="me-2" type="search" name="search" placeholder="Buscar..." aria-label="Search">
                </form>
            </div>
            <div class="actions navbar-nav col-6">
                <a class="nav-item" href="">
                    <img src="../src/assets/icons/add-large-line.svg" alt="" width="30px"> </a>
                <a class="nav-item" href="">
                    <img src="../src/assets/icons/notification-line.svg" alt="" width="30px">
                </a>
                <a class="nav-item" href="../src/components/login.php">
                    <?php
                    if (isset($_SESSION['nome_usuario'])) {
                        $nomeCompleto = $_SESSION['nome_usuario'];
                        $primeiroNome = explode(' ', trim($nomeCompleto))[0];
                        echo '<span class="me-2">' . htmlspecialchars($primeiroNome) . '</span>';
                    }
                    ?>
                    <img src="../src/assets/icons/icon_usuario.svg" alt="" width="30px">
                </a>
            </div>
        </div>


    </nav>
</body>

</html>