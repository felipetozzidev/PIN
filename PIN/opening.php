<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>IFApoia - IFSP Piracicaba</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --background: #EEFFEE;
            --backgroundContrast: #e7f7e78b;
            --primary: #305F2C;
            --secondary: #A0BF9F;
            --secondaryButton: #2e7d32;
            --accent: #96c584;
            --text-color: #2d3748;
            --text-muted: #718096;
            --white: #ffffff;
            --light-gray: #f8f9fa;
            --shadow-light: 0 2px 10px rgba(0, 0, 0, 0.08);
            --shadow-medium: 0 4px 20px rgba(0, 0, 0, 0.12);
            --shadow-heavy: 0 8px 30px rgba(0, 0, 0, 0.15);
            --border-radius: 12px;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * {
            box-sizing: border-box;
        }

        body {
            background-color: var(--background);
            font-family: 'Inter', 'Segoe UI', sans-serif;
            scroll-behavior: smooth;
            line-height: 1.6;
            color: var(--text-color);
            overflow-x: hidden;
        }

        .navbar {
            background: transparent !important;
            box-shadow: var(--shadow-light);
            border-bottom: 1px solid rgba(27, 94, 32, 0.1);
            transition: var(--transition);
            padding: 1rem 0;
            position: fixed;
            width: 100%;
            z-index: 1000;

        }

        .navbar.scrolled {
            box-shadow: var(--shadow-medium);
            padding: 0.5rem 0;
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.8rem;
            color: var(--primary) !important;
            text-decoration: none;
            position: relative;
        }

        .navbar-brand:hover {
            transform: translateY(-1px);
            transition: var(--transition);
        }

        .nav-link {
            font-weight: 500;
            color: var(--text-color) !important;
            padding: 0.5rem 1rem !important;
            margin: 0 0.25rem;
            border-radius: 8px;
            position: relative;
            transition: var(--transition);
        }

        .nav-link:hover {
            color: var(--primary) !important;
            background: rgba(27, 94, 32, 0.05);
            transform: translateY(-1px);
        }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 0;
            height: 2px;
            background: var(--primary);
            transform: translateX(-50%);
            transition: var(--transition);
        }

        .nav-link:hover::after {
            width: 80%;
        }

        .btn-green {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondaryButton) 100%);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-light);
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }

        .btn-green::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.52), transparent);
            transition: var(--transition);
        }

        .btn-green:hover {
            background: linear-gradient(135deg, var(--secondaryButton) 0%, var(--primary) 100%);
            transform: translateY(-2px);
            box-shadow: var(--shadow-medium);
            color: white;
        }

        .btn-green:hover::before {
            left: 100%;
        }

        .btn-green:active {
            transform: translateY(0);
        }

        .hero {
            background: linear-gradient(135deg, rgba(27, 94, 32, 0.9) 0%, rgba(46, 125, 50, 0.8) 100%),
                url('https://images.pexels.com/photos/256490/pexels-photo-256490.jpeg?auto=compress&cs=tinysrgb&w=1600') center/cover no-repeat;
            color: white;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
            padding: 8rem 2rem;
            text-align: center;
            position: relative;
            overflow: hidden;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, rgba(27, 94, 32, 0.1) 25%, transparent 25%, transparent 75%, rgba(27, 94, 32, 0.1) 75%);
            background-size: 20px 20px;
            animation: backgroundMove 20s linear infinite;
        }

        @keyframes backgroundMove {
            0% {
                transform: translateX(0) translateY(0);
            }

            100% {
                transform: translateX(20px) translateY(20px);
            }
        }

        .hero-content {
            position: relative;
            z-index: 2;
            max-width: 800px;
            margin: 0 auto;
        }

        .hero h1 {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            animation: fadeInUp 1s ease-out;
        }

        .hero p {
            font-size: 1.3rem;
            margin-bottom: 2rem;
            font-weight: 300;
            animation: fadeInUp 1s ease-out 0.2s both;
        }

        .hero .btn {
            animation: fadeInUp 1s ease-out 0.4s both;
            font-size: 1.1rem;
            padding: 1rem 2rem;
        }

        /* Enhanced Section Titles */
        .section-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 2rem;
            position: relative;
            display: inline-block;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 0;
            width: 60px;
            height: 4px;
            background: linear-gradient(90deg, var(--primary) 0%, var(--accent) 100%);
            border-radius: 2px;
        }

        /* Enhanced About Section */
        #sobre {
            padding: 6rem 0;
            background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        }

        #sobre ul {
            list-style: none;
            padding: 0;
        }

        #sobre li {
            padding: 1rem 0;
            border-left: 4px solid var(--primary);
            padding-left: 2rem;
            margin: 1rem 0;
            background: white;
            border-radius: 0 var(--border-radius) var(--border-radius) 0;
            box-shadow: var(--shadow-light);
            transition: var(--transition);
            position: relative;
        }

        #sobre li:hover {
            transform: translateX(10px);
            box-shadow: var(--shadow-medium);
            border-left-color: var(--accent);
        }

        #sobre li::before {
            content: '✓';
            position: absolute;
            left: -12px;
            top: 50%;
            transform: translateY(-50%);
            background: var(--primary);
            color: white;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            font-weight: bold;
            transition: var(--transition);
        }

        #sobre li:hover::before {
            background: var(--accent);
            transform: translateY(-50%) scale(1.1);
        }

        /* Enhanced Structure Section */
        #estrutura {
            padding: 6rem 0;
            background: var(--light-gray);
        }

        .structure-card {
            background: white;
            border-radius: var(--border-radius);
            padding: 2rem;
            box-shadow: var(--shadow-light);
            transition: var(--transition);
            height: 100%;
            position: relative;
            overflow: hidden;
        }

        .structure-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary) 0%, var(--accent) 100%);
        }

        .structure-card:hover {
            transform: translateY(-10px);
            box-shadow: var(--shadow-heavy);
        }

        .structure-card img {
            border-radius: var(--border-radius);
            margin-bottom: 1.5rem;
            transition: var(--transition);
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .structure-card:hover img {
            transform: scale(1.05);
        }

        .structure-card p {
            color: var(--text-muted);
            font-weight: 500;
        }

        /* Enhanced Courses Section */
        #cursos {
            padding: 6rem 0;
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
        }

        .accordion-item {
            border: none;
            margin-bottom: 1.5rem;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-light);
            overflow: hidden;
            transition: var(--transition);
        }

        .accordion-item:hover {
            box-shadow: var(--shadow-medium);
            transform: translateY(-2px);
        }

        .accordion-button {
            background: white;
            border: none;
            color: var(--text-color);
            font-weight: 600;
            font-size: 1.1rem;
            padding: 1.5rem 2rem;
            transition: var(--transition);
        }

        .accordion-button:not(.collapsed) {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondaryButton) 100%);
            color: white;
            box-shadow: none;
        }

        .accordion-button:focus {
            box-shadow: 0 0 0 0.25rem rgba(27, 94, 32, 0.25);
        }

        .accordion-button::after {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%23666'%3e%3cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3e%3c/svg%3e");
            transition: var(--transition);
        }

        .accordion-button:not(.collapsed)::after {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%23ffffff'%3e%3cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3e%3c/svg%3e");
            transform: rotate(180deg);
        }

        .accordion-body {
            background: #f8f9fa;
            padding: 2rem;
            border-top: 2px solid rgba(27, 94, 32, 0.1);
        }

        /* Enhanced Testimonials */
        .testimonial-section {
            padding: 6rem 0;
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondaryButton) 100%);
            color: white;
        }

        .testimonial-section .section-title {
            color: white;
        }

        .testimonial-section .section-title::after {
            background: linear-gradient(90deg, white 0%, var(--accent) 100%);
        }

        .carousel-item {
            padding: 3rem 2rem;
        }

        .blockquote {
            font-size: 1.3rem;
            font-weight: 300;
            line-height: 1.8;
            position: relative;
        }

        .blockquote::before,
        .blockquote::after {
            content: '"';
            font-size: 4rem;
            color: rgba(255, 255, 255, 0.3);
            position: absolute;
            font-family: serif;
        }

        .blockquote::before {
            top: -20px;
            left: -20px;
        }

        .blockquote::after {
            bottom: -60px;
            right: -20px;
        }

        .blockquote-footer {
            color: rgba(255, 255, 255, 0.8);
            font-weight: 500;
            margin-top: 2rem;
        }

        .carousel-control-prev,
        .carousel-control-next {
            width: 5%;
            background: rgba(255, 255, 255, 0.1);
            border-radius: var(--border-radius);
            margin: 2rem;
            transition: var(--transition);
        }

        .carousel-control-prev:hover,
        .carousel-control-next:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        /* Enhanced Contact Section */
        #contato {
            padding: 6rem 0;
            background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        }

        .form-control {
            border: 2px solid #e9ecef;
            border-radius: var(--border-radius);
            padding: 1rem;
            font-size: 1rem;
            transition: var(--transition);
            background: white;
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.25rem rgba(27, 94, 32, 0.15);
            background: white;
        }

        .form-label {
            font-weight: 600;
            color: var(--text-color);
            margin-bottom: 0.75rem;
        }

        .contact-info {
            background: white;
            padding: 2rem;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-light);
            margin-top: 3rem;
        }

        .contact-info p {
            margin-bottom: 1rem;
            font-weight: 500;
        }

        .social-links a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 50px;
            height: 50px;
            background: var(--primary);
            color: white;
            border-radius: 50%;
            margin: 0 0.5rem;
            transition: var(--transition);
            text-decoration: none;
        }

        .social-links a:hover {
            background: var(--accent);
            transform: translateY(-3px);
            box-shadow: var(--shadow-medium);
        }

        /* Enhanced Footer */
        footer {
            background: linear-gradient(135deg, var(--text-color) 0%, #34495e 100%);
            color: white;
            padding: 3rem 0;
        }

        footer a {
            color: var(--secondary);
            text-decoration: none;
            transition: var(--transition);
        }

        footer a:hover {
            color: var(--accent);
            text-decoration: underline;
        }

        /* Enhanced Animations */
        .fade-in {
            opacity: 0;
            transform: translateY(50px);
            transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .fade-in.visible {
            opacity: 1;
            transform: translateY(0);
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }
        }

        /* Loading Animation */
        .loading-spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255, 255, 255, .3);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* Responsive Enhancements */
        @media (max-width: 768px) {
            .hero h1 {
                font-size: 2.5rem;
            }

            .hero p {
                font-size: 1.1rem;
            }

            .section-title {
                font-size: 2rem;
            }

            .structure-card {
                margin-bottom: 2rem;
            }

            .blockquote {
                font-size: 1.1rem;
            }

            .blockquote::before,
            .blockquote::after {
                font-size: 3rem;
            }
        }

        /* Accessibility Improvements */
        @media (prefers-reduced-motion: reduce) {
            * {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }
        }

        /* Focus Styles */
        .btn-green:focus,
        .nav-link:focus {
            outline: 2px solid var(--accent);
            outline-offset: 2px;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: var(--primary);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--secondaryButton);
        }
    </style>
</head>

<body>
    <!-- Cabeçalho -->
    <nav class="navbar navbar-expand-lg fixed" id="navbar">
        <div class="container">
            <a class="navbar-brand" href="#inicio">IFApoia</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link" href="#inicio">Início</a></li>
                    <li class="nav-item"><a class="nav-link" href="#sobre">Sobre o Projeto</a></li>
                    <li class="nav-item"><a class="nav-link" href="#estrutura">Estrutura do Campus</a></li>
                    <li class="nav-item"><a class="nav-link" href="#cursos">Cursos Oferecidos</a></li>
                    <li class="nav-item"><a class="nav-link" href="#contato">Contato</a></li>
                </ul>
                <a class="btn btn-green" href="https://github.com/ifapoia" target="_blank">
                    <i class="fab fa-github me-2"></i>Ver no GitHub
                </a>
            </div>
        </div>
    </nav>

    <!-- Hero -->
    <section id="inicio" class="hero">
        <div class="hero-content">
            <h1>Bem-vindo ao IFApoia</h1>
            <p>Conectando você ao futuro da educação no IFSP Piracicaba com excelência e inovação.</p>
            <a href="#sobre" class="btn btn-green">
                <i class="fas fa-arrow-down me-2"></i>Descobrir Mais
            </a>
        </div>
    </section>

    <!-- Sobre o Projeto -->
    <section id="sobre" class="fade-in">
        <div class="container">
            <h2 class="section-title">Sobre o Projeto</h2>
            <div class="row">
                <div class="col-lg-8">
                    <p class="lead">O IFApoia tem como objetivo principal apoiar estudantes e promover iniciativas educacionais que integrem a comunidade e estimulem o desenvolvimento sustentável, criando um ambiente de aprendizado colaborativo e inovador.</p>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-md-12">
                    <ul>
                        <li><strong>Apoio a projetos estudantis</strong> - Incentivamos a criação e desenvolvimento de projetos inovadores</li>
                        <li><strong>Integração com a comunidade</strong> - Fortalecemos os laços entre instituição e sociedade</li>
                        <li><strong>Desenvolvimento sustentável</strong> - Promovemos práticas ambientalmente responsáveis</li>
                        <li><strong>Inovação tecnológica</strong> - Estimulamos o uso de tecnologias emergentes</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Estrutura do Campus -->
    <section id="estrutura" class="fade-in">
        <div class="container">
            <h2 class="section-title">Estrutura do Campus</h2>
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="structure-card">
                        <img src="https://images.pexels.com/photos/2280571/pexels-photo-2280571.jpeg?auto=compress&cs=tinysrgb&w=400" class="img-fluid" alt="Laboratório">
                        <h4 class="mt-3 mb-3" style="color: var(--primary); font-weight: 600;">Laboratórios Modernos</h4>
                        <p>Laboratórios modernos e equipados com tecnologia de ponta para diversas áreas do conhecimento, proporcionando experiência prática aos estudantes.</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="structure-card">
                        <img src="https://images.pexels.com/photos/2041540/pexels-photo-2041540.jpeg?auto=compress&cs=tinysrgb&w=400" class="img-fluid" alt="Biblioteca">
                        <h4 class="mt-3 mb-3" style="color: var(--primary); font-weight: 600;">Biblioteca Digital</h4>
                        <p>Bibliotecas com acervo atualizado, espaços de estudo colaborativo e recursos digitais para pesquisa acadêmica avançada.</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="structure-card">
                        <img src="https://images.pexels.com/photos/1438072/pexels-photo-1438072.jpeg?auto=compress&cs=tinysrgb&w=400" class="img-fluid" alt="Convivência">
                        <h4 class="mt-3 mb-3" style="color: var(--primary); font-weight: 600;">Espaços de Convivência</h4>
                        <p>Áreas de convivência modernas que promovem a integração entre os alunos e facilitam o networking acadêmico.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Cursos Oferecidos -->
    <section id="cursos" class="fade-in">
        <div class="container">
            <h2 class="section-title">Cursos Oferecidos</h2>
            <div class="accordion" id="cursosAccordion">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingComp">
                        <button class="accordion-button collapsed" type="button"
                            data-bs-toggle="collapse" data-bs-target="#collapseComp"
                            aria-expanded="false" aria-controls="collapseComp">
                            <i class="fas fa-laptop-code me-3"></i>Engenharia da Computação
                        </button>
                    </h2>
                    <div id="collapseComp" class="accordion-collapse collapse"
                        aria-labelledby="headingComp" data-bs-parent="#cursosAccordion">
                        <div class="accordion-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong><i class="fas fa-clock me-2"></i>Duração:</strong> 10 semestres</p>
                                    <p><strong><i class="fas fa-user-graduate me-2"></i>Modalidade:</strong> Presencial</p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong><i class="fas fa-certificate me-2"></i>Titulação:</strong> Bacharel</p>
                                    <p><strong><i class="fas fa-calendar me-2"></i>Período:</strong> Integral</p>
                                </div>
                            </div>
                            <p><strong>Perfil Profissional:</strong> Planejar, projetar e desenvolver sistemas embarcados, redes de computadores e soluções de segurança digital para diferentes setores da indústria.</p>
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingInfo">
                        <button class="accordion-button collapsed" type="button"
                            data-bs-toggle="collapse" data-bs-target="#collapseInfo"
                            aria-expanded="false" aria-controls="collapseInfo">
                            <i class="fas fa-database me-3"></i>Tecnologia em Análise e Desenvolvimento de Sistemas
                        </button>
                    </h2>
                    <div id="collapseInfo" class="accordion-collapse collapse"
                        aria-labelledby="headingInfo" data-bs-parent="#cursosAccordion">
                        <div class="accordion-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong><i class="fas fa-clock me-2"></i>Duração:</strong> 6 semestres</p>
                                    <p><strong><i class="fas fa-user-graduate me-2"></i>Modalidade:</strong> Presencial</p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong><i class="fas fa-certificate me-2"></i>Titulação:</strong> Tecnólogo</p>
                                    <p><strong><i class="fas fa-calendar me-2"></i>Período:</strong> Noturno</p>
                                </div>
                            </div>
                            <p><strong>Perfil Profissional:</strong> Desenvolver sistemas de informação, aplicações web e mobile, gerenciar bancos de dados e implementar soluções tecnológicas inovadoras.</p>
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingMec">
                        <button class="accordion-button collapsed" type="button"
                            data-bs-toggle="collapse" data-bs-target="#collapseMec"
                            aria-expanded="false" aria-controls="collapseMec">
                            <i class="fas fa-cogs me-3"></i>Engenharia Mecânica
                        </button>
                    </h2>
                    <div id="collapseMec" class="accordion-collapse collapse"
                        aria-labelledby="headingMec" data-bs-parent="#cursosAccordion">
                        <div class="accordion-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong><i class="fas fa-clock me-2"></i>Duração:</strong> 10 semestres</p>
                                    <p><strong><i class="fas fa-user-graduate me-2"></i>Modalidade:</strong> Presencial</p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong><i class="fas fa-certificate me-2"></i>Titulação:</strong> Bacharel</p>
                                    <p><strong><i class="fas fa-calendar me-2"></i>Período:</strong> Integral</p>
                                </div>
                            </div>
                            <p><strong>Perfil Profissional:</strong> Projetar, desenvolver e otimizar sistemas mecânicos, máquinas industriais e processos de manufatura com foco em sustentabilidade.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Depoimentos -->
    <section class="testimonial-section fade-in">
        <div class="container">
            <h2 class="section-title text-center">Depoimentos</h2>
            <div id="carouselDepoimentos" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <blockquote class="blockquote text-center">
                            <p class="mb-0">"O IFSP transformou completamente minha trajetória profissional. Os professores são excepcionais e o ambiente acadêmico é verdadeiramente inspirador."</p>
                            <footer class="blockquote-footer mt-3">
                                <strong>Ana Silva</strong>, Ex-aluna de Engenharia da Computação
                            </footer>
                        </blockquote>
                    </div>
                    <div class="carousel-item">
                        <blockquote class="blockquote text-center">
                            <p class="mb-0">"Os professores são incríveis e sempre apoiam nossos projetos. A infraestrutura é de primeira qualidade e as oportunidades são infinitas."</p>
                            <footer class="blockquote-footer mt-3">
                                <strong>Lucas Santos</strong>, Aluno de Análise e Desenvolvimento de Sistemas
                            </footer>
                        </blockquote>
                    </div>
                    <div class="carousel-item">
                        <blockquote class="blockquote text-center">
                            <p class="mb-0">"A experiência no IFSP me proporcionou não apenas conhecimento técnico, mas também uma visão ampla sobre inovação e sustentabilidade."</p>
                            <footer class="blockquote-footer mt-3">
                                <strong>Maria Oliveira</strong>, Ex-aluna de Engenharia Mecânica
                            </footer>
                        </blockquote>
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselDepoimentos" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Anterior</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselDepoimentos" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Próximo</span>
                </button>
            </div>
        </div>
    </section>

    <!-- Contato -->
    <section id="contato" class="fade-in">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <h2 class="section-title">Entre em Contato</h2>
                    <form id="contactForm">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label for="nome" class="form-label">
                                        <i class="fas fa-user me-2"></i>Nome Completo
                                    </label>
                                    <input type="text" class="form-control" id="nome" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label for="email" class="form-label">
                                        <i class="fas fa-envelope me-2"></i>E-mail
                                    </label>
                                    <input type="email" class="form-control" id="email" required>
                                </div>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label for="assunto" class="form-label">
                                <i class="fas fa-tag me-2"></i>Assunto
                            </label>
                            <select class="form-control" id="assunto" required>
                                <option value="">Selecione um assunto</option>
                                <option value="informacoes">Informações sobre cursos</option>
                                <option value="inscricoes">Processo de inscrição</option>
                                <option value="campus">Estrutura do campus</option>
                                <option value="outros">Outros assuntos</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="mensagem" class="form-label">
                                <i class="fas fa-comment me-2"></i>Mensagem
                            </label>
                            <textarea class="form-control" id="mensagem" rows="5" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-green btn-lg">
                            <i class="fas fa-paper-plane me-2"></i>Enviar Mensagem
                        </button>
                    </form>
                </div>
                <div class="col-lg-4">
                    <div class="contact-info">
                        <h4 style="color: var(--primary); margin-bottom: 2rem;">
                            <i class="fas fa-info-circle me-2"></i>Informações de Contato
                        </h4>
                        <p>
                            <i class="fas fa-map-marker-alt me-3" style="color: var(--primary);"></i>
                            <strong>Endereço:</strong><br>
                            Av. IFSP, 123 - Centro<br>
                            Piracicaba, SP - CEP: 13400-000
                        </p>
                        <p>
                            <i class="fas fa-phone me-3" style="color: var(--primary);"></i>
                            <strong>Telefone:</strong><br>
                            (19) 1234-5678
                        </p>
                        <p>
                            <i class="fas fa-envelope me-3" style="color: var(--primary);"></i>
                            <strong>Email:</strong><br>
                            contato@ifsp.edu.br
                        </p>
                        <hr>
                        <p><strong>Siga-nos nas redes sociais:</strong></p>
                        <div class="social-links">
                            <a href="#" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                            <a href="#" title="Instagram"><i class="fab fa-instagram"></i></a>
                            <a href="#" title="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                            <a href="#" title="YouTube"><i class="fab fa-youtube"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Rodapé -->
    <footer class="text-center">
        <div class="container">
            <div class="row">
                <div class="col-md-8 mx-auto">
                    <p class="mb-3">
                        <strong>Links Rápidos:</strong>
                        <a href="#" class="ms-2">Política de Privacidade</a> |
                        <a href="#" class="ms-2">Termos de Uso</a> |
                        <a href="#" class="ms-2">Acessibilidade</a> |
                        <a href="#" class="ms-2">Ouvidoria</a>
                    </p>
                    <p class="mb-0">
                        © 2024 IFApoia - Instituto Federal de São Paulo, Campus Piracicaba.<br>
                        Desenvolvido com <i class="fas fa-heart" style="color: #e74c3c;"></i> pela <strong>IFApoia Team</strong>
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Enhanced scroll animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver(entries => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        }, observerOptions);

        document.querySelectorAll('.fade-in').forEach(el => observer.observe(el));

        // Enhanced navbar scroll effect
        window.addEventListener('scroll', () => {
            const navbar = document.getElementById('navbar');
            if (window.scrollY > 100) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // Smooth scroll for navigation links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Enhanced form submission with validation
        document.getElementById('contactForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;

            // Show loading state
            submitBtn.innerHTML = '<div class="loading-spinner me-2"></div>Enviando...';
            submitBtn.disabled = true;

            // Simulate form submission
            setTimeout(() => {
                alert('Mensagem enviada com sucesso! Entraremos em contato em breve.');
                this.reset();
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }, 2000);
        });

        // Add hover effects to cards
        document.querySelectorAll('.structure-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-10px) scale(1.02)';
            });

            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) scale(1)';
            });
        });

        // Auto-advance carousel
        const carousel = document.querySelector('#carouselDepoimentos');
        if (carousel) {
            const carouselInstance = new bootstrap.Carousel(carousel, {
                interval: 5000,
                wrap: true
            });
        }

        // Add typing effect to hero text (optional enhancement)
        function typeWriter(element, text, speed = 50) {
            let i = 0;
            element.innerHTML = '';

            function type() {
                if (i < text.length) {
                    element.innerHTML += text.charAt(i);
                    i++;
                    setTimeout(type, speed);
                }
            }
            type();
        }

        // Lazy loading for images
        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        img.src = img.dataset.src;
                        img.classList.remove('lazy');
                        imageObserver.unobserve(img);
                    }
                });
            });

            document.querySelectorAll('img[data-src]').forEach(img => {
                imageObserver.observe(img);
            });
        }

        // Add parallax effect to hero section
        window.addEventListener('scroll', () => {
            const scrolled = window.pageYOffset;
            const hero = document.querySelector('.hero');
            if (hero) {
                hero.style.transform = `translateY(${scrolled * 0.5}px)`;
            }
        });

        console.log('🎓 IFApoia Website loaded successfully!');
    </script>
</body>

</html>