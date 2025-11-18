<?php
// Define a página atual se ainda não foi definida
$currentFile = basename($_SERVER['PHP_SELF']);
$currentPage = str_replace('.php', '', $currentFile);
// Trata o index.php como 'inicio' para a lógica da classe 'active'
if ($currentPage === 'index' || $currentPage === '') {
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
            $isUserLoggedIn = isset($_SESSION['user_id']);
            // O dropdown estará "ativo" (aberto) se o usuário estiver em uma página de comunidade específica
            $isDropdownActive = ($currentPage === 'comunidade_view');
            ?>
            <li class="dropdown_item <?php echo $isDropdownActive ? 'active' : ''; ?>">
                <a class="nav-link">
                    <p>Suas comunidades</p>
                    <!-- O icone é controlado por css porra nenhuma, deixa do jeito que tava pq ta dando problema -->
                </a>
                <ul class="dropdown_list">
                    <?php if ($isUserLoggedIn && isset($pdo)): ?>
                        <?php
                        try {
                            $id_usuario_logado = $_SESSION['user_id'];
                            // Corrigindo a consulta para usar os nomes de coluna do seu ifapoia.sql
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
                                    echo '<li><a href="comunidade_view.php?id=' . $comunidade['community_id'] . '" class="nav-link"><p>' . htmlspecialchars($comunidade['name']) . '</p></a></li>';
                                }
                            } else {
                                echo '<li><p class="no-communities">Você não segue nenhuma comunidade.</p></li>';
                            }
                        } catch (PDOException $e) {
                            error_log("Erro ao buscar comunidades: " . $e->getMessage());
                            echo '<li><p class="no-communities">Erro ao carregar.</p></li>';
                        }
                        ?>
                    <?php else: ?>
                        <li><a href="login.php" class="nav-link"><p>Faça login para ver suas comunidades.</p></a></li>
                    <?php endif; ?>
                </ul>
            </li>
        </ul>
    </nav>
</section>

