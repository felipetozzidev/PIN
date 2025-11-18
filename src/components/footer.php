<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
<link rel="stylesheet" href="../src/assets/css/style.css">
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
            <li class="select_item">
                <a href="comunidades.php" class="nav-link">
                    <i class=""></i>
                </a>
            </li>
            <li class="select_item">
                <a href="comunidades.php" class="nav-link">
                    <i class="ri-group-line"></i>
                    
                </a>
            </li>
           <li class="select_item">
               <a href="<?php if (isset($_SESSION['user_id'])):?>perfil.php<?php else: ?>login.php <?php endif; ?>" class="nav-link">
                   <i class="ri-user-line"></i>
               </a>
           </li>
    </nav>



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="../src/assets/js/javascript.js"></script>

</body>

</html>