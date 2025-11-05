<?php
$currentPage = 'comunidades';
// O header.php já inicia a sessão e faz a conexão via PDO
require_once('../config/conn.php');
require_once("../src/components/header.php");

// Lógica para buscar comunidades, incluindo a imagem de perfil
try {
    // CORREÇÃO: Removidos caracteres invisíveis da consulta SQL.
    $sql_comunidades = "SELECT 
                            c.community_id, 
                            c.name, 
                            c.image_url,
                            COUNT(uc.user_id) as total_members 
                        FROM communities c 
                        LEFT JOIN user_communities uc ON c.community_id = uc.community_id 
                        GROUP BY c.community_id, c.name, c.image_url
                        ORDER BY total_members DESC";
    $result_comunidades = $pdo->query($sql_comunidades);
} catch (PDOException $e) {
    // Em caso de erro, exibe uma mensagem amigável e registra o erro real.
    error_log("Erro ao buscar comunidades: " . $e->getMessage());
    $result_comunidades = false; // Garante que a variável existe para a verificação abaixo
}
?>

<main class="index-container" style="margin-top: 88px; margin-bottom: 44px">
    <?php require_once("../src/components/nav_bar.php"); ?>
    <section class="main_container">
        <div class="main_content" data-pagina="comunidades">
            <h1 class="title">Comunidades</h1>
            <div class="community_cards_container">
                <?php
                if ($result_comunidades && $result_comunidades->rowCount() > 0) {
                    while ($comunidade = $result_comunidades->fetch(PDO::FETCH_ASSOC)) {
                ?>
                        <a href="perfil_comunidades.php?id=<?php echo $comunidade['community_id']; ?>" class="community_card">
                            <div class="container_icon_text">
                                <img src="<?php echo htmlspecialchars($comunidade['image_url'] ?? '../src/assets/img/default-user.png'); ?>" alt="Ícone da Comunidade" class="community_icon">
                                <div class="text_card">
                                    <p class="community_name"><?php echo htmlspecialchars($comunidade['name']); ?></p>
                                    <span class="community_followers"><?php echo $comunidade['total_members']; ?> membros</span>
                                </div>
                            </div>
                            <div class="community_subscribe">
                                <span class="button_follow">
                                    <p>Ver</p>
                                </span>
                            </div>
                        </a>
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
<?php require_once("../src/components/footer.php"); ?>