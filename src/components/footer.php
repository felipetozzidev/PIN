<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
<link rel="stylesheet" href="../src/assets/css/style.css">

<link rel="stylesheet" href="../src/assets/css/accessibility.css">

<footer class="pag_footer">
    <div>
        <div class="row">
            <div class="col">
                <p>&#169; Todos os direitos reservados | IFApoia <?php echo date("Y") ?></p>
            </div>
        </div>
    </div>
</footer>

<nav class="navbar_mobile">
    <ul>
        <li class="select_item">
            <a href="index.php" class="nav-link">
                <i class="ri-home-2-line"></i>
            </a>
        </li>
        <li class="select_item">
            <a href="destaques.php" class="nav-link">
                <i class="ri-fire-line"></i>
            </a>
        </li>
    </ul>
</nav>

<?php
// Caminho absoluto para o arquivo do widget
$widget_file = __DIR__ . '/accessibility_widget.php';

// Só inclui se o arquivo realmente existir
if (file_exists($widget_file)) {
    include($widget_file);
} else {
    // Em ambiente de desenvolvimento, avisa que o arquivo falta (opcional)
    // echo "";
}
?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
<script src="../src/assets/js/javascript.js"></script>
<script src="../src/assets/js/accessibility.js"></script>

</body>
</html>