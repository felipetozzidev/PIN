<?php
include("../src/components/header.php"); ?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="stylesheet" href="../src/assets/css/style.css"> -->
    <title>IFApoia | Comunidades</title>
</head>

<body>
    <main class="index-container">
        <?php include("../src/components/nav_bar.php"); ?>
        <section class="main_container">
            <div class="main_content" data-pagina="comunidades">
                <h1>Comunidades</h1>
                <div class="community_cards_container">
                    <?php
                    // Lógica para buscar comunidades do banco
                    $sql_comunidades = "SELECT c.nome_com, COUNT(uc.id_usu) as total_membros FROM comunidades c LEFT JOIN usuarios_comunidades uc ON c.id_com = uc.id_com GROUP BY c.id_com ORDER BY total_membros DESC";
                    $result_comunidades = $conn->query($sql_comunidades);
                    if ($result_comunidades && $result_comunidades->num_rows > 0) {
                        while ($comunidade = $result_comunidades->fetch_assoc()) {
                    ?>
                            <div class="community_card">
                                <div class="container_icon_text">
                                    <div class="community_icon"><img src="../src/assets/img/default-user.png" alt="" class="img_card img-fluid"></div>
                                    <div class="text_card">
                                        <p class="community_name"><?php echo htmlspecialchars($comunidade['nome_com']); ?></p>
                                        <span class="community_followers"><?php echo $comunidade['total_membros']; ?> seguidores</span>
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
</body>

</html>