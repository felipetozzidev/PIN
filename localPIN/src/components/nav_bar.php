<?php
// Define a página atual como 'inicio' por padrão se não for definida antes.
if (!isset($currentPage)) {
    $currentPage = 'inicio';
}
?>
<section class="navbar_container">
    <nav class="navbar_lateral">
        <ul>
            <li class="select_item <?php echo ($currentPage === 'inicio') ? 'active' : ''; ?>">
                <a href="index.php" class="nav-link">
                    <i class="ri-home-2-line"></i>
                    <p>Início</p>
                </a>
            </li>
            <li class="select_item <?php echo ($currentPage === 'destaques') ? 'active' : ''; ?>">
                <a href="destaques.php" class="nav-link">
                    <i class="ri-fire-line"></i>
                    <p>Destaques</p>
                </a>
            </li>
            <li class="select_item <?php echo ($currentPage === 'comunidades') ? 'active' : ''; ?>">
                <a href="comunidades.php" class="nav-link">
                    <i class="ri-group-line"></i>
                    <p>Comunidades</p>
                </a>
            </li>
            <li class="separador">
                <hr class="w-100">
            </li>

            <?php
            // Verifica se o usuário está logado (usando a variável de sessão correta)
            $isUserLoggedIn = isset($_SESSION['user_id']);
            // Determina se o dropdown deve estar ativo (se a página atual for uma comunidade específica)
            $isDropdownActive = ($currentPage === 'comunidade_view'); // Usaremos 'comunidade_view' para páginas de comunidade
            ?>
            <li class="dropdown_item <?php echo $isDropdownActive ? 'active' : ''; ?>">
                <a class="nav-link">
                    <p>Suas comunidades</p>
                    <img src="../src/assets/icons/arrow_down.svg" alt="Abrir dropdown">
                </a>
                <ul class="dropdown_list">
                    <?php if ($isUserLoggedIn): ?>
                        <?php
                        // LÓGICA SEGURA PARA BUSCAR COMUNIDADES COM PDO
                        try {
                            $id_usuario_logado = $_SESSION['user_id'];
                            $sql_suas_comunidades = "SELECT c.community_id, c.name 
                                                     FROM communities c 
                                                     JOIN user_communities uc ON c.community_id = uc.community_id 
                                                     WHERE uc.user_id = ? 
                                                     LIMIT 5";
                            $stmt = $pdo->prepare($sql_suas_comunidades);
                            $stmt->execute([$id_usuario_logado]);
                            $suas_comunidades = $stmt->fetchAll(PDO::FETCH_ASSOC);

                            if ($suas_comunidades) {
                                foreach ($suas_comunidades as $comunidade) {
                                    // Adiciona 'active' se for a comunidade que o usuário está vendo
                                    $isCurrentCommunity = (isset($currentCommunityId) && $currentCommunityId == $comunidade['community_id']);
                                    echo '<li class="' . ($isCurrentCommunity ? 'active' : '') . '"><a href="comunidade_view.php?id=' . $comunidade['community_id'] . '" class="nav-link"><p>' . htmlspecialchars($comunidade['name']) . '</p></a></li>';
                                }
                            } else {
                                echo '<li><p class="no-communities">Você não segue nenhuma comunidade.</p></li>';
                            }
                        } catch (PDOException $e) {
                            // error_log("Erro ao buscar comunidades do usuário: " . $e->getMessage());
                            echo '<li><p class="no-communities">Erro ao carregar.</p></li>';
                        }
                        ?>
                    <?php else: ?>
                        <li><a href="login.php" class="nav-link">
                                <p>Faça login para ver suas comunidades.</p>
                            </a></li>
                    <?php endif; ?>
                </ul>
            </li>
        </ul>
    </nav>
</section>