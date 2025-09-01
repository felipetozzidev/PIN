<section class="navbar_container">
    <nav class="navbar_lateral">
        <ul>
            <li class="select_item">
                <a href="index.php" class="nav-link">
                    <i class="ri-home-2-line"></i>
                    <p>Início</p>
                </a>
            </li>
            <li class="select_item">
                <a href="destaques.php" class="nav-link">
                    <i class="ri-fire-line"></i>
                    <p>Destaques</p>
                </a>
            </li>
            <li class="select_item">
                <a href="comunidades.php" class="nav-link">
                    <i class="ri-group-line"></i>
                    <p>Comunidades</p>
                </a>
            </li>
            <li class="separador">
                <hr class="w-100">
            </li>

            <?php if (!isset($_SESSION['id_usu'])): ?>
                <li class="dropdown_item">
                    <a class="nav-link">
                        <p>Suas comunidades</p>
                    </a>
                    <ul class="dropdown_list">
                        <li><a href="./login.php" class="nav-link">
                            <p>Entre para acessar suas comunidades.</p>
                        </a></li>
                    </ul>
                </li>
            <?php endif; ?>
            <?php if (isset($_SESSION['id_usu'])): ?>
               <li class="dropdown_item">
                    <a class="nav-link">
                        <p>Suas comunidades</p>
                    </a>

                    <ul class="dropdown_list">
                        

                <?php
                // Lógica para buscar as comunidades do utilizador
                #$id_usuario_logado = $_SESSION['id_usu'];
                $id_usuario_logado = 1;
                $sql_suas_comunidades = "SELECT c.nome_com FROM comunidades c JOIN usuarios_comunidades uc ON c.id_com = uc.id_com WHERE uc.id_usu = $id_usuario_logado LIMIT 5";
                $result_suas_comunidades = $conn->query($sql_suas_comunidades);
                if ($result_suas_comunidades && $result_suas_comunidades->num_rows > 0) {
                    while ($comunidade = $result_suas_comunidades->fetch_assoc()) {
                        echo '<li><a href="#" class="nav-link"><p>' . htmlspecialchars($comunidade['nome_com']) . '</p></a></li>';
                    }
                } else {
                    echo '<li class=""><p style="padding: 10px; font-size: 0.8rem; color: #666; cursor: default;">Você não segue nenhuma comunidade.</p></li>';
                }
                ?>
            <?php endif; ?>
        </ul>
    </nav>
  
</section>

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
                    <i class="ri-group-line"></i>
                    
                </a>
            </li>
           
    </nav>

