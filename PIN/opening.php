<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="Página principal do IFApoia - Conectando o IFSP Piracicaba com a comunidade." />
    <title>IFApoia - IFSP Piracicaba</title>
    <link rel="apple-touch-icon" sizes="180x180" href="/PIN/src/assets/icons/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/PIN/src/assets/icons/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/PIN/src/assets/icons/favicon/favicon-16x16.png">
    <link rel="shortcut icon" type="image/x-icon" sizes="192x192" href="/PIN/src/assets/icons/favicon/favicon.ico">
    <link rel="manifest" href="/PIN/src/assets/icons/favicon/site.webmanifest">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="stylesheet" href="/PIN/src/assets/css/style.css">
    <style>
        :root {
            --cor-primaria: #305f2c;
            --cor-secundaria: #a0bf9f;
            --cor-acento: #96c584;
            --cor-fundo: #eeffee;
            --cor-contraste: #e7f7e78b;
            --cor-texto: #2d3748;
            --cor-texto-sec: #718096;
            --branco: #ffffff;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--cor-fundo);
            color: var(--cor-texto);
        }

        header {
            background-color: var(--cor-primaria);
        }

        .navbar-brand,
        .nav-link,
        footer {
            color: var(--branco) !important;
        }

        .hero {
            background: url('https://prc.ifsp.edu.br/images/fachada-campus.jpg') center/cover no-repeat;
            color: var(--branco);
            text-shadow: 2px 2px 6px #000;
            height: 90vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        .hero h1 {
            font-size: 3.5rem;
        }

        section {
            padding: 60px 0;
        }

        .card {
            transition: transform 0.3s;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        footer {
            background-color: var(--cor-primaria);
            padding: 30px 0;
            text-align: center;
        }
    </style>
</head>

<body>
    <!-- Header -->
    <nav class="navbar navbar-expand-md fixed" id="nav">
        <div class="container-fluid">
            <div class="col-4">
                <a class="navbar-brand" href="">
                    <img src="../src/assets/img/Logotipo_antiga.png" alt="" class="logo">

                </a>
            </div>
            <div class="col-4 navbar-nav d-flex justify-content-center d-none d-md-flex">

            </div>
            <div class="col-4 actions navbar-nav justify-content-end">

                               
            </div>
        </div>
    </nav>

    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="offcanvasNavbarLabel">Menu</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>      
        </div>
        <div class="offcanvas-body">

        </div>
    </div>


    <!-- Hero Section -->
    <section class="hero">
        <div class="container" data-aos="fade-up">
            <h1>Bem-vindo ao IFApoia</h1>
            <p class="lead">Conectando o IFSP Piracicaba com a comunidade</p>
            <a href="#sobre" class="btn btn-light mt-4">Saiba mais</a>
        </div>
    </section>

    <!-- Sobre o Projeto -->
    <section id="sobre" class="bg-light">
        <div class="container" data-aos="fade-right">
            <h2 class="text-center mb-4">Sobre o IFApoia</h2>
            <p class="text-center">O IFApoia é uma iniciativa que visa integrar os estudantes, docentes e a comunidade ao redor do IFSP Piracicaba, promovendo ações de apoio mútuo, visibilidade institucional e colaboração acadêmica.</p>
        </div>
    </section>

    <!-- Cursos -->
    <section id="cursos">
        <div class="container" data-aos="fade-up">
            <h2 class="text-center mb-5">Cursos Disponíveis</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card p-3">
                        <h5>Engenharia de Computação</h5>
                        <p>Formação sólida em software, hardware e sistemas embarcados.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card p-3">
                        <h5>Engenharia Elétrica</h5>
                        <p>Ênfase em eletrônica, potência, automação e sistemas de controle.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card p-3">
                        <h5>Engenharia Mecânica</h5>
                        <p>Projetos industriais, termodinâmica, mecânica dos sólidos e mais.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card p-3">
                        <h5>Licenciatura em Física</h5>
                        <p>Formação de professores com sólida base científica e pedagógica.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card p-3">
                        <h5>Tecnologia em Automação Industrial</h5>
                        <p>Automação de processos industriais com foco em inovação tecnológica.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card p-3">
                        <h5>Técnico em Mecânica</h5>
                        <p>Curso técnico integrado para alunos do ensino médio (concomitante).</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Infraestrutura -->
    <section id="infraestrutura" class="bg-light">
        <div class="container" data-aos="fade-left">
            <h2 class="text-center mb-4">Nossa Infraestrutura</h2>
            <ul>
                <li>Três blocos com salas amplas e laboratórios especializados.</li>
                <li>Biblioteca equipada com acervo técnico e espaços de estudo.</li>
                <li>Laboratórios de informática, física, elétrica, mecânica e automação.</li>
                <li>Ambientes de convivência estudantil e áreas verdes.</li>
            </ul>
        </div>
    </section>

    <!-- Contato -->
    <section id="contato">
        <div class="container" data-aos="fade-up">
            <h2 class="text-center mb-4">Entre em Contato</h2>
            <form class="row g-3">
                <div class="col-md-6">
                    <input type="text" class="form-control" placeholder="Seu nome" required />
                </div>
                <div class="col-md-6">
                    <input type="email" class="form-control" placeholder="Seu email" required />
                </div>
                <div class="col-12">
                    <textarea class="form-control" rows="4" placeholder="Sua mensagem"></textarea>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-success">Enviar</button>
                </div>
            </form>
        </div>
    </section>

    <!-- Rodapé -->
    <footer>
        <div class="container">
            <p>&copy; 2025 IFApoia - IFSP Piracicaba. Todos os direitos reservados</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init();
    </script>
</body>

</html>