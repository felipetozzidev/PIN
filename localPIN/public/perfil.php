<?php
    require_once("../config/conn.php");
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil</title>
</head>
<body>
    <?php include_once("../src/components/header.php"); ?>

    <div class="index-container">
        <?php include("../src/components/nav_bar.php"); ?>
    </div>

    <!-- Lightbox para visualização de imagem -->
    <div id="imageLightbox" class="lightbox">
        <span class="lightbox-close">&times;</span>
        <a class="lightbox-nav prev">&#10094;</a>
        <img class="lightbox-content" id="lightboxImage">
        <a class="lightbox-nav next">&#10095;</a>
    </div>

    <?php include("../src/components/footer.php"); ?>
</body>
</html>