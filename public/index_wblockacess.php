<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    echo '<!DOCTYPE html>
    <html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Rede IFApoia</title>
        <link rel="stylesheet" href="../src/assets/css/style.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
        <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    </head>
    <style>

    /* Estilos personalizados para o SweetAlert2 */
    .swal2-popup {
      background: var(--white);
      border-radius: 15px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      color: var(--text-color);
    }

    .swal2-title {
      color: var(--primary);
      font-size: 1.5rem;
      font-weight: 700;
    }

    .swal2-content {
      color: var(--text-muted);
      font-size: 1rem;
    }

    .swal2-confirm {
      background: linear-gradient(135deg, var(--primary), var(--secondaryButton)) !important;
      border: none !important;
      border-radius: 8px !important;
      padding: 0.75rem 1.5rem !important;
      font-size: 1rem !important;
      font-weight: 500 !important;
      transition: all 0.3s ease !important;
    }

    .swal2-confirm:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(98, 28, 212, 0.2) !important;
    }

    .swal2-cancel {
      background: var(--background) !important;
      border: 1px solid var(--border-color) !important;
      border-radius: 8px !important;
      padding: 0.75rem 1.5rem !important;
      font-size: 1rem !important;
      font-weight: 500 !important;
      color: var(--text-color) !important;
      transition: all 0.3s ease !important;
    }

    .swal2-cancel:hover {
      background: var(--background) !important;
      border-color: var(--primary) !important;
      color: var(--primary) !important;
    }
    </style>
    <body>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            Swal.fire({
              title: "Acesso Negado!",
              text: "Você precisa estar logado para acessar a Rede IFApoia.",
              icon: "warning",
              iconColor: "#f27474",
              timer: 5000,
              timerProgressBar: true,
              showConfirmButton: false,
              willClose: () => {
                window.location.href = "/PIN/src/components/login.php";
              }
            });
        </script>
    </body>
    </html>';
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
       
    <meta charset="UTF-8">
       
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Projeto PIN</title>
       
    <link rel="stylesheet" href="../src/assets/css/style.css">

       
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
       
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
</head>

<body>
        <?php include("../src/components/header.php"); ?>

        <main>
                <?php include("../src/components/nav_bar.php"); ?>

                <section class="main_container">
                        <div class="destaques">
                                <h1 class="title">Destaques</h1>
                                <div class="cards">
                                        <span>Destaques</span>
                                    </div>
                            </div>
                        <div class="postagens">
                                <h1 class="title">Destaques</h1>

                            </div>
                    </section>
                <section class="publicacoes_recentes">

                    </section>
            </main>

        <?php include("../src/components/footer.php"); ?>


        <script src="../src/assets/js/javascript.js"></script>
</body>

</html>