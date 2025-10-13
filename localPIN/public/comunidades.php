<?php
// O header.php já inicia a sessão e faz a conexão via PDO
require_once("../src/components/header.php");

// Lógica para buscar comunidades do banco com os novos nomes
$sql_comunidades = "SELECT 
                        c.community_id, c.name, COUNT(uc.user_id) as total_members 
                    FROM communities c 
                    LEFT JOIN user_communities uc ON c.community_id = uc.community_id 
                    GROUP BY c.community_id 
                    ORDER BY total_members DESC";
$result_comunidades = $pdo->query($sql_comunidades);
?>

<!-- O HTML começa diretamente com o conteúdo da página -->
<main class="index-container">
    <?php include("../src/components/nav_bar.php"); ?>
    <section class="main_container">
        <div class="main_content" data-pagina="comunidades">
            <h1>Comunidades</h1>
            <div class="community_cards_container">
                <?php
                if ($result_comunidades && $result_comunidades->rowCount() > 0) {
                    while ($comunidade = $result_comunidades->fetch(PDO::FETCH_ASSOC)) {
                ?>
                        <div class="community_card">
                            <div class="container_icon_text">
                                <div class="community_icon"><img src="../src/assets/img/default-user.png" alt="Ícone da Comunidade" class="img_card img-fluid"></div>
                                <div class="text_card">
                                    <p class="community_name"><?php echo htmlspecialchars($comunidade['name']); ?></p>
                                    <span class="community_followers"><?php echo $comunidade['total_members']; ?> seguidores</span>
                                </div>
                            </div>
                            <div class="community_subscribe"><a href="#" class="button_follow">
                                    <p>Seguir</p>
                                </a></div>
                        </div>
                <?php
                    }
                } else {
                    echo "<p>Nenhuma comunidade encontrada.</p>";
                }
                ?>
            </div>
        </div>
    </section>
</main>
<?php include("../src/components/footer.php"); ?>