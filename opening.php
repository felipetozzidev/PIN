<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>IFApoia - Conheça o IFSP Piracicaba</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        /* ==========================================================================
           1. VARIÁVEIS GLOBAIS E RESET
           ========================================================================== */
        :root {
            --background: #EEFFEE;
            --backgroundContrast: #e7f7e7;
            --primary: #305F2C;
            --secondary: #A0BF9F;
            --secondaryButton: #2e7d32;
            --accent: #96c584;
            --text-color: #2d3748;
            --text-muted: #5a687c;
            --white: #ffffff;
            --light-gray: #f9fbf9;
            --shadow: 0 5px 25px rgba(0, 0, 0, 0.07);
            --border-radius: 12px;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
            scroll-padding-top: 80px;
            /* Ajusta a rolagem suave para a altura do header */
        }

        body {
            background-color: var(--white);
            font-family: 'Inter', sans-serif;
            line-height: 1.7;
            color: var(--text-color);
            overflow-x: hidden;
        }

        section {
            padding: 6rem 0;
            overflow: hidden;
        }

        .section-eyebrow {
            text-transform: uppercase;
            font-weight: 700;
            color: var(--primary);
            letter-spacing: 0.5px;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }

        .section-title {
            font-size: 2.8rem;
            font-weight: 800;
            color: var(--text-color);
            margin-bottom: 1rem;
            position: relative;
            line-height: 1.2;
        }

        .section-subtitle {
            font-size: 1.15rem;
            color: var(--text-muted);
            max-width: 700px;
            margin: 0 auto 3rem auto;
        }

        /* Classe de animação para o JS */
        .fade-in-section {
            opacity: 0;
            transform: translateY(30px);
            transition: opacity 0.6s ease-out, transform 0.6s ease-out;
        }

        .fade-in-section.is-visible {
            opacity: 1;
            transform: translateY(0);
        }

        /* ==========================================================================
           2. BARRA DE NAVEGAÇÃO (HEADER)
           ========================================================================== */
        #main-nav {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1000;
            background: transparent;
            padding: 1.25rem 0;
            transition: padding 0.3s ease, background-color 0.3s ease, box-shadow 0.3s ease;
        }

        #main-nav.navbar-scrolled {
            background: var(--white);
            box-shadow: var(--shadow);
            padding: 0.75rem 0;
        }

        #main-nav .navbar-brand .logo {
            width: auto;
            max-height: 60px;
            filter: drop-shadow(1px 1px 0 rgba(0, 0, 0, 0.4));
            transition: var(--transition);
        }

        #main-nav .navbar-collapse {
            justify-content: center;
        }

        #main-nav .navbar-collapse .nav-item {
            align-content: center;
        }

        #main-nav .nav-link {
            font-weight: 600;
            color: rgba(255, 255, 255, 0.85);
            margin: 0 0.25rem;
            padding: 0.5rem 0.75rem;
            border-radius: 8px;
            transition: var(--transition);
        }

        #main-nav.navbar-scrolled .nav-link {
            color: var(--text-muted);
        }

        #main-nav .nav-link:hover,
        #main-nav.navbar-scrolled .nav-link:hover {
            color: var(--primary);
            background-color: var(--backgroundContrast);
        }

        #main-nav.navbar-scrolled .nav-link.active {
            color: var(--primary);
            border-bottom: 2px solid var(--primary);
            border-radius: 0;
        }

        #main-nav .nav-link.active {
            color: var(--white);
        }

        #main-nav .btn-login {
            font-weight: 600;
            background-color: var(--white);
            color: var(--primary);
            border: 2px solid var(--white);
            padding: 0.6rem 1.25rem;
            border-radius: 50px;
            transition: var(--transition);
            text-decoration: none;
        }

        #main-nav.navbar-scrolled .btn-login {
            background-color: var(--primary);
            color: var(--white);
            border-color: var(--primary);
        }

        #main-nav .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        #main-nav.navbar-scrolled .btn-login:hover {
            background-color: var(--secondaryButton);
            border-color: var(--secondaryButton);
        }

        #main-nav .navbar-toggler {
            border-color: rgba(255, 255, 255, 0.3);
        }

        #main-nav .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba(255,255,255,0.8)' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }

        #main-nav.navbar-scrolled .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba(48,95,44,0.8)' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }

        @media (max-width: 991px) {
            #main-nav .navbar-collapse {
                background-color: var(--white);
                border-radius: var(--border-radius);
                padding: 1rem;
                margin-top: 0.5rem;
                box-shadow: var(--shadow);
            }

            #main-nav .nav-link,
            #main-nav.navbar-scrolled .nav-link,
            #main-nav .nav-link:hover,
            #main-nav.navbar-scrolled .nav-link:hover,
            #main-nav.navbar-scrolled .nav-link.active {
                color: var(--text-color);
            }

            #main-nav .nav-link:hover {
                background-color: var(--backgroundContrast);
            }

            #main-nav.navbar-scrolled .navbar-brand {
                color: var(--white);
            }

            #main-nav.navbar-scrolled.scrolled-true .navbar-brand {
                color: var(--primary);
            }

            #main-nav .nav-link.active {
                border-bottom: none;
                color: var(--primary);
                font-weight: 700;
            }
        }

        /* ==========================================================================
           3. SEÇÃO HERO (TOPO - Apresenta o IFApoia)
           ========================================================================== */
        .hero {
            background: linear-gradient(135deg, rgba(46, 125, 50, 0.8) 0%, rgba(48, 95, 44, 0.9) 100%), url('src/assets/img/ifcampus.jpg');
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
            color: white;
            padding: 14rem 0 10rem 0;
            text-align: center;
            min-height: 95vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .hero-content {
            max-width: 800px;
        }

        .hero h1 {
            font-size: 3.2rem;
            font-weight: 800;
            margin-bottom: 1.5rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
            line-height: 1.2;
        }

        .hero p.lead {
            font-size: 1.25rem;
            font-weight: 300;
            max-width: 600px;
            margin: 0 auto 2rem auto;
            color: rgba(255, 255, 255, 0.9);
        }

        .hero .btn-group .btn {
            font-weight: 600;
            padding: 0.8rem 1.8rem;
            border-radius: 50px;
            transition: var(--transition);
        }

        .hero .btn-primary {
            background-color: var(--white);
            color: var(--primary);
            border-color: var(--white);
        }

        .hero .btn-primary:hover {
            background-color: transparent;
            color: var(--white);
            border-color: var(--white);
        }

        /* ==========================================================================
           4. SEÇÕES DE CONTEÚDO
           ========================================================================== */

        /* Seção Sobre o Projeto (Problema e Solução) */
        #sobre-projeto {
            background-color: var(--light-gray);
        }

        .feature-card {
            background: var(--white);
            padding: 2.5rem;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            height: 100%;
            border-top: 4px solid var(--primary);
            transition: var(--transition);
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        .feature-card i {
            font-size: 3rem;
            color: var(--primary);
            margin-bottom: 1.5rem;
            display: block;
        }

        .feature-card h3 {
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--text-color);
            margin-bottom: 0.75rem;
        }

        .feature-card p {
            color: var(--text-muted);
            font-size: 0.95rem;
        }

        /* ==========================================================================
           4.1. SEÇÃO EQUIPE
           ========================================================================== */
        #equipe {
            background-color: var(--white);
        }

        .team-card {
            background: var(--light-gray);
            border-radius: var(--border-radius);
            padding: 2rem 1.5rem;
            text-align: center;
            border: 1px solid var(--backgroundContrast);
            transition: var(--transition);
            height: 100%;
        }

        .team-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow);
            border-color: var(--white);
        }

        .team-img {
            width: 130px;
            height: 130px;
            border-radius: 50%;
            object-fit: cover;
            margin: 0 auto 1.5rem auto;
            border: 4px solid var(--primary);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .team-name {
            font-weight: 700;
            font-size: 1.15rem;
            color: var(--text-color);
            margin-bottom: 0.25rem;
        }

        .team-role {
            font-weight: 500;
            font-size: 0.7rem;
            color: var(--primary);
            text-transform: uppercase;
        }

        /* ==========================================================================
           4.2. SEÇÃO REDE INTERNA
           ========================================================================== */
        #rede-interna {
            background-color: var(--light-gray);
        }

        .video-wrapper {
            position: relative;
            padding-bottom: 56.25%;
            /* 16:9 aspect ratio */
            height: 0;
            overflow: hidden;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            background-color: #000;
        }

        .video-wrapper iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }

        #rede-interna .carousel-item {
            height: 500px;
            /* Mesma altura dos labs */
            background-color: var(--light-gray);
        }

        #rede-interna .carousel-item img {
            object-fit: cover;
            object-position: top;
            /* Mostra o topo dos screenshots */
            width: 100%;
            height: 100%;
        }

        #rede-interna .carousel-caption {
            background: linear-gradient(to top, rgba(0, 0, 0, 0.7) 0%, rgba(0, 0, 0, 0) 100%);
            bottom: 0;
            left: 0;
            right: 0;
            padding: 1.5rem;
        }

        /* Seção Sobre o Campus */
        .campus-info-list {
            list-style: none;
            padding-left: 0;
            margin-top: 2rem;
        }

        .campus-info-list li {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .campus-info-list i {
            font-size: 1.5rem;
            color: var(--primary);
            margin-top: 5px;
        }

        .campus-info-list h5 {
            font-weight: 700;
            margin-bottom: 0.25rem;
        }

        .campus-info-list p {
            margin-bottom: 0;
            color: var(--text-muted);
        }

        /* Seção Cursos */
        #cursos {
            background-color: var(--background);
        }

        /* ==========================================================================
           4.5. SEÇÃO CURSOS (ESTILOS DO ACCORDION)
           ========================================================================== */

        /* Nível 1: Categoria (Graduação, Técnicos, etc.) */
        .accordion-item {
            background-color: var(--white);
            border: none;
            border-radius: var(--border-radius) !important;
            margin-bottom: 1.5rem;
            box-shadow: var(--shadow);
            overflow: hidden;
            transition: var(--transition);
        }

        .accordion-item:hover {
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
        }

        .accordion-button {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--white);
            background-color: var(--primary);
            padding: 1.5rem 2rem;
            border: none;
        }

        .accordion-button:focus {
            box-shadow: 0 0 0 3px rgba(48, 95, 44, 0.2);
            z-index: 5;
        }

        .accordion-button:not(.collapsed) {
            color: var(--white);
            background-color: var(--secondaryButton);
            box-shadow: none;
        }

        /* Customiza o ícone de seta */
        .accordion-button::after {
            font-family: 'RemixIcon';
            content: '\EA4E';
            background-image: none !important;
            font-size: 2rem;
            font-weight: normal;
            color: rgba(255, 255, 255, 0.7);
            transform: rotate(0deg);
            transition: var(--transition);
        }

        .accordion-button:not(.collapsed)::after {
            background-image: none !important;
            color: var(--white);
            transform: rotate(-180deg);
        }

        /* Nível 2: Curso Específico (Eng. Mecânica, etc.) */
        .course-accordion {
            padding: 1rem 1rem 0 1rem;
            background-color: var(--light-gray);
        }

        .course-accordion-item {
            background-color: var(--white);
            border: 1px solid var(--backgroundContrast);
            border-radius: 8px !important;
            margin-bottom: 1rem;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }

        .course-accordion-item .accordion-button {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--text-color);
            background-color: var(--white);
            padding: 1rem 1.5rem;
        }

        .course-accordion-item .accordion-button:not(.collapsed) {
            color: var(--primary);
            background-color: var(--backgroundContrast);
        }

        .course-accordion-item .accordion-button::after {
            font-family: 'RemixIcon';
            content: '\EA4E';
            background-image: none !important;
            color: var(--text-muted);
            font-size: 1.5rem;
            transform: rotate(0deg);
            transition: var(--transition);
        }

        .course-accordion-item .accordion-button:not(.collapsed)::after {
            background-image: none !important;
            color: var(--primary);
            transform: rotate(-180deg);
        }

        .course-accordion-item .accordion-body {
            padding: 1.5rem;
            padding-top: 1rem;
            border-top: 1px solid var(--backgroundContrast);
        }

        .course-details {
            font-size: 0.95rem;
            color: var(--text-muted);
        }

        .course-details p {
            margin-bottom: 1rem;
        }

        .course-details ul {
            list-style: none;
            padding-left: 0;
        }

        .course-details li {
            position: relative;
            padding-left: 28px;
            margin-bottom: 0.5rem;
        }

        .course-details li::before {
            font-family: 'RemixIcon';
            font-weight: bold;
            position: absolute;
            left: 0;
            top: 1px;
            color: var(--primary);
        }

        .course-details li.detail-period::before {
            content: '\EEA4';
        }

        /* ri-time-line */
        .course-details li.detail-duration::before {
            content: '\E9B5';
        }

        /* ri-calendar-2-line */
        .course-details li.detail-hours::before {
            content: '\E909';
        }

        /* ri-hourglass-line */
        .course-details li.detail-intake::before {
            content: '\EA86';
        }

        /* ri-user-add-line */
        .course-details li.detail-coordinator::before {
            content: '\EA84';
        }

        /* ri-user-star-line */
        .course-details li.detail-target::before {
            content: '\EBFB';
        }

        /* ri-group-line */
        .course-details li.detail-vacancies::before {
            content: '\E9B4';
        }

        /* ri-calendar-check-line */

        /* ==========================================================================
           4.6. SEÇÃO LABORATÓRIOS
           ========================================================================== */
        #laboratorios .nav-tabs {
            border-bottom: 2px solid var(--backgroundContrast);
            margin-bottom: 2rem;
        }

        #laboratorios .nav-tabs .nav-link {
            border: none;
            border-bottom: 2px solid transparent;
            color: var(--text-muted);
            font-weight: 600;
            font-size: 1.2rem;
            padding: 0.75rem 1.5rem;
        }

        #laboratorios .nav-tabs .nav-link.active {
            color: var(--primary);
            border-bottom: 2px solid var(--primary);
            background-color: transparent;
        }

        #laboratorios .carousel-item {
            height: 500px;
            /* Altura fixa para o carousel */
            background-color: var(--light-gray);
        }

        #laboratorios .carousel-item img {
            object-fit: cover;
            width: 100%;
            height: 100%;
        }

        #laboratorios .carousel-caption {
            background: linear-gradient(to top, rgba(0, 0, 0, 0.7) 0%, rgba(0, 0, 0, 0) 100%);
            bottom: 0;
            left: 0;
            right: 0;
            padding: 1.5rem;
        }

        .lab-list {
            list-style: none;
            padding-left: 0;
            font-size: 0.95rem;
            color: var(--text-muted);
        }

        .lab-list li {
            position: relative;
            padding-left: 25px;
            margin-bottom: 0.5rem;
        }

        .lab-list li::before {
            content: '\E9F4';
            /* ri-flask-line */
            font-family: 'RemixIcon';
            position: absolute;
            left: 0;
            top: 2px;
            color: var(--primary);
        }

        .lab-list.informatica li::before {
            content: '\E9DE';
            /* ri-computer-line */
        }

        /* ==========================================================================
           (Continuação das seções)
           ========================================================================== */

        /* Seção Pilares */
        #pilares {
            background-color: var(--light-gray);
        }

        /* Seção Vida Estudantil */
        .student-life-card {
            background-color: var(--background);
            border: 1px solid var(--backgroundContrast);
            padding: 2rem;
            border-radius: var(--border-radius);
            height: 100%;
        }

        .student-life-card h4 {
            color: var(--primary);
            font-weight: 700;
        }

        .student-life-card ul {
            padding-left: 1.25rem;
            color: var(--text-muted);
        }

        .student-life-card li {
            margin-bottom: 0.5rem;
        }

        /* ==========================================================================
           4.7. SEÇÃO SUPORTE AO ESTUDANTE (NOVO)
           ========================================================================== */
        #suporte-estudante {
            background-color: var(--background);
        }

        .support-card {
            background: var(--white);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            height: 100%;
            transition: var(--transition);
            overflow: hidden;
            /* Para a imagem */
            display: flex;
            flex-direction: column;
        }

        .support-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        .support-card-img {
            width: 100%;
            height: 220px;
            object-fit: cover;
        }

        .support-card-content {
            padding: 2rem;
            display: flex;
            flex-direction: column;
            flex-grow: 1;
            /* Faz o conteúdo preencher o card */
        }

        .support-card-content h3 {
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 0.75rem;
        }

        .support-card-content p {
            color: var(--text-muted);
            font-size: 0.95rem;
            margin-bottom: 1rem;
            /* Aumentado o espaço */
            flex-grow: 1;
            /* Empurra a lista para baixo */
        }

        .support-card-content ul {
            padding-left: 1.25rem;
            color: var(--text-muted);
            font-size: 0.95rem;
        }

        .support-card-content li {
            margin-bottom: 0.5rem;
        }

        /* Seção Qualidade */
        #qualidade {
            background-color: var(--primary);
            color: var(--white);
        }

        #qualidade .section-title {
            color: var(--white);
        }

        #qualidade .section-subtitle {
            color: rgba(255, 255, 255, 0.8);
        }

        .quality-card {
            text-align: center;
            padding: 1rem;
        }

        .quality-card i {
            font-size: 3rem;
            color: var(--accent);
            margin-bottom: 1rem;
        }

        .quality-card h5 {
            font-weight: 700;
            color: var(--white);
            margin-bottom: 0.5rem;
        }

        .quality-card p {
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.95rem;
        }

        /* ==========================================================================
           5. RODAPÉ (FOOTER)
           ========================================================================== */
        footer {
            background-color: var(--text-color);
            color: rgba(255, 255, 255, 0.8);
            padding: 4rem 0 2rem 0;
        }

        footer h5 {
            color: var(--white);
            font-weight: 600;
            margin-bottom: 1rem;
        }

        footer .list-unstyled li {
            margin-bottom: 0.5rem;
        }

        footer .list-unstyled a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: var(--transition);
        }

        footer .list-unstyled a:hover {
            color: var(--white);
            text-decoration: underline;
        }

        footer .social-icons a {
            color: var(--white);
            font-size: 1.5rem;
            margin-right: 1rem;
            transition: var(--transition);
        }

        footer .social-icons a:hover {
            color: var(--accent);
            transform: translateY(-2px);
        }

        footer .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, 0.2);
            padding-top: 2rem;
            margin-top: 2rem;
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.6);
        }

        /* ==========================================================================
           6. RESPONSIVIDADE
           ========================================================================== */
        @media (max-width: 768px) {
            .hero h1 {
                font-size: 2.5rem;
            }

            .hero p.lead {
                font-size: 1.1rem;
            }

            .section-title {
                font-size: 2.2rem;
            }

            .feature-card,
            .student-life-card,
            .support-card {
                margin-bottom: 1.5rem;
            }

            .course-category {
                margin-bottom: 0;
            }

            .course-category .accordion-item {
                margin-bottom: 1rem;
            }

            #laboratorios .carousel-item {
                height: 300px;
                /* Altura menor em telas pequenas */
            }

            #laboratorios .nav-tabs .nav-link {
                font-size: 1rem;
                padding: 0.75rem 1rem;
            }
        }
    </style>
</head>

<body data-bs-spy="scroll" data-bs-target="#main-nav" data-bs-offset="90" tabindex="0">
    <header>
        <nav class="navbar navbar-expand-lg fixed-top" id="main-nav">
            <div class="container">
                <a class="navbar-brand" href="#inicio">
                    <img src="../src/assets/img/Logotipo_antiga.png" alt="Logo IFApoia" class="logo">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav mx-auto">
                        <li class="nav-item"><a class="nav-link" href="#sobre-projeto">Sobre</a></li>
                        <li class="nav-item"><a class="nav-link" href="#rede-interna">Rede</a></li>
                        <li class="nav-item"><a class="nav-link" href="#equipe">Equipe</a></li>
                        <li class="nav-item"><a class="nav-link" href="#sobre-campus">Nosso Campus</a></li>
                        <li class="nav-item"><a class="nav-link" href="#cursos">Cursos</a></li>
                        <li class="nav-item"><a class="nav-link" href="#laboratorios">Laboratórios</a></li>
                        <li class="nav-item"><a class="nav-link" href="#pilares">Pilares</a></li>
                        <li class="nav-item"><a class="nav-link" href="#vida-estudantil">Vida Estudantil</a></li>
                    </ul>
                    <div class="d-flex">
                        <a href="index.php" class="btn btn-login ms-lg-3">Acessar Plataforma</a>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <main>
        <section class="hero" id="inicio">
            <div class="container hero-content">
                <h1>IFApoia</h1>
                <p class="lead">Um projeto que visa integrar conhecimentos em torno de um problema específico, buscando soluções para a ausência de canais de comunicação eficazes, o que dificulta a disseminação de informações acadêmicas e institucionais.</p>
                <div class="btn-group mt-4" role="group">
                    <a href="#sobre-projeto" class="btn btn-primary mx-2">Saiba Mais</a>
                    <a href="index.php" class="btn btn-outline-light mx-2">Acessar a Plataforma</a>
                </div>
            </div>
        </section>

        <section id="sobre-projeto" class="fade-in-section">
            <div class="container">
                <div class="text-center">
                    <h2 class="section-title">O Problema e Nossa Solução</h2>
                    <p class="section-subtitle">Identificamos uma lacuna na comunicação que impacta a integração e o acesso da comunidade interna e externa. O IFApoia nasce para resolver isso.</p>
                </div>
                <div class="row g-4">
                    <div class="col-lg-4 col-md-6">
                        <div class="feature-card">
                            <i class="ri-team-line"></i>
                            <h3>Rede de Apoio Interna</h3>
                            <p>Para docentes, discentes e servidores, o IFApoia é um ambiente digital colaborativo para fortalecer a comunicação e a integração acadêmica.</p>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="feature-card">
                            <i class="ri-megaphone-line"></i>
                            <h3>Canal Oficial Externo</h3>
                            <p>Para a comunidade externa, a plataforma centraliza informações sobre a instituição, projetos e oportunidades para futuros alunos e interessados.</p>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-12">
                        <div class="feature-card">
                            <i class="ri-shield-check-line"></i>
                            <h3>Foco na Integração Social</h3>
                            <p>Buscamos contribuir para a integração social, com especial atenção a públicos socioeconomicamente vulneráveis, garantindo o acesso à informação.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="rede-interna" class="fade-in-section">
            <div class="container">
                <div class="text-center">
                    <span class="section-eyebrow">Exclusivo para Membros</span>
                    <h2 class="section-title">Explore a Rede Interna</h2>
                    <p class="section-subtitle">Uma plataforma social e acadêmica completa, inspirada nas melhores ferramentas digitais, feita sob medida para a comunidade do IFSP.</p>
                </div>

                <div class="row g-4 mb-5">
                    <div class="col-lg-4 col-md-6">
                        <div class="feature-card">
                            <i class="ri-discuss-line"></i>
                            <h3>Feed e Postagens</h3>
                            <p>Um feed dinâmico onde alunos e professores podem postar atualizações, compartilhar arquivos, criar enquetes e tirar dúvidas em tempo real.</p>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="feature-card">
                            <i class="ri-group-2-line"></i>
                            <h3>Comunidades Temáticas</h3>
                            <p>Crie e participe de comunidades (inspiradas no Reddit) para cada curso, matéria, projeto de extensão, ou grupos de interesse e hobbies.</p>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-12">
                        <div class="feature-card">
                            <i class="ri-user-line"></i>
                            <h3>Perfis e Conexões</h3>
                            <p>Cada membro possui um perfil (inspirado no Facebook) que facilita a conexão entre alunos de diferentes turmas, professores e servidores.</p>
                        </div>
                    </div>
                </div>

                <div class="row justify-content-center mb-5">
                    <div class="col-lg-10">
                        <h3 class="text-center fw-bold mb-4">Tour Rápido pela Plataforma</h3>
                        <div class="video-wrapper rounded-3">
                            <iframe src="https://www.youtube.com/embed/dQw4w9WgXcQ" title="Tour em Vídeo do IFApoia" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                        </div>
                    </div>
                </div>

                <div class="row justify-content-center">
                    <div class="col-lg-10">
                        <h3 class="text-center fw-bold mb-4">Galeria de Funcionalidades</h3>
                        <div id="carouselPlataforma" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-indicators">
                                <button type="button" data-bs-target="#carouselPlataforma" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                                <button type="button" data-bs-target="#carouselPlataforma" data-bs-slide-to="1" aria-label="Slide 2"></button>
                                <button type="button" data-bs-target="#carouselPlataforma" data-bs-slide-to="2" aria-label="Slide 3"></button>
                            </div>
                            <div class="carousel-inner rounded-3">
                                <div class="carousel-item active">
                                    <img src="Printscreen+do+Feed+Principal" class="d-block w-100" alt="Feed Principal do IFApoia">
                                    <div class="carousel-caption d-none d-md-block">
                                        <h5>Feed Principal</h5>
                                        <p>Veja as últimas postagens de quem você segue e das suas comunidades.</p>
                                    </div>
                                </div>
                                <div class="carousel-item">
                                    <img src="src/assets/img/labs/C/CPrintscreen+da+Página+da+Comunidade" class="d-block w-100" alt="Página de Comunidade">
                                    <div class="carousel-caption d-none d-md-block">
                                        <h5>Página da Comunidade</h5>
                                        <p>Cada comunidade tem seu próprio feed, moderadores e lista de membros.</p>
                                    </div>
                                </div>
                                <div class="carousel-item">
                                    <img src="https://via.placeholder.com/800x500/96c584/2d3748?text=Printscreen+do+Modal+de+Postagem" class="d-block w-100" alt="Modal de Postagem">
                                    <div class="carousel-caption d-none d-md-block">
                                        <h5>Criação de Posts</h5>
                                        <p>Interface simples para criar posts com texto, imagens e tags.</p>
                                    </div>
                                </div>
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#carouselPlataforma" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#carouselPlataforma" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </section>
        <section id="equipe" class="fade-in-section">
            <div class="container">
                <div class="text-center">
                    <h2 class="section-title">Nossa Equipe</h2>
                    <p class="section-subtitle">Somos estudantes do 4º ano do Ensino Médio Técnico de Informática, os desenvolvedores do IFApoia, sob orientação dos professores Anderson Belgamo, Eliana Righi, Janaina Aragão e Luis Grim.</p>
                </div>
                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-5 g-4 justify-content-center">

                    <div class="col">
                        <div class="team-card">
                            <img src="https://via.placeholder.com/130/305F2C/FFFFFF?text=BS" alt="Foto Breno" class="team-img">
                            <h5 class="team-name">Breno Silveira Domingues</h5>
                            <span class="team-role">Programação</span>
                        </div>
                    </div>
                    <div class="col">
                        <div class="team-card">
                            <img src="https://via.placeholder.com/130/305F2C/FFFFFF?text=FT" alt="Foto Felipe" class="team-img">
                            <h5 class="team-name">Felipe Tozzi Bertochi</h5>
                            <span class="team-role">Programação</span>
                        </div>
                    </div>
                    <div class="col">
                        <div class="team-card">
                            <img src="src/assets/img/developers_images/luiz_gustavo_pizara.jpeg" alt="Foto Luiz Gustavo" class="team-img">
                            <h5 class="team-name">Luiz Gustavo Pizara</h5>
                            <span class="team-role">Programação e Dados</span>
                        </div>
                    </div>
                    <div class="col">
                        <div class="team-card">
                            <img src="https://via.placeholder.com/130/305F2C/FFFFFF?text=RC" alt="Foto Rafael" class="team-img">
                            <h5 class="team-name">Rafael Carlos Francisco</h5>
                            <span class="team-role">Programação e Dados</span>
                        </div>
                    </div>

                    <div class="col">
                        <div class="team-card">
                            <img src="src/assets/img/developers_images/beatriz_belotti_bueno.jpeg" alt="Foto Beatriz" class="team-img">
                            <h5 class="team-name">Beatriz Belotti Bueno</h5>
                            <span class="team-role">Design e Dados</span>
                        </div>
                    </div>
                    <div class="col">
                        <div class="team-card">
                            <img src="https://via.placeholder.com/130/305F2C/FFFFFF?text=GG" alt="Foto Gabriele" class="team-img">
                            <h5 class="team-name">Gabriele Gazzi Martins Lopes</h5>
                            <span class="team-role">Design e Dados</span>
                        </div>
                    </div>
                    <div class="col">
                        <div class="team-card">
                            <img src="https://via.placeholder.com/130/305F2C/FFFFFF?text=GG" alt="Foto Giovanna" class="team-img">
                            <h5 class="team-name">Giovanna Guadagnini Ferraz</h5>
                            <span class="team-role">Design e Dados</span>
                        </div>
                    </div>

                    <div class="col">
                        <div class="team-card">
                            <img src="https://via.placeholder.com/130/305F2C/FFFFFF?text=LA" alt="Foto Lucas" class="team-img">
                            <h5 class="team-name">Lucas Alexandre S. Soares</h5>
                            <span class="team-role">Informação</span>
                        </div>
                    </div>
                    <div class="col">
                        <div class="team-card">
                            <img src="httpsa" alt="Foto Luiz Felipe" class="team-img">
                            <h5 class="team-name">Luiz Felipe F. Nascimento</h5>
                            <span class="team-role">Informação</span>
                        </div>
                    </div>
                    <div class="col">
                        <div class="team-card">
                            <img src="https://via.placeholder.com/130/305F2C/FFFFFF?text=RG" alt="Foto Ruan" class="team-img">
                            <h5 class="team-name">Ruan Gustavo Novello Correa</h5>
                            <span class="team-role">Informação</span>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <section id="sobre-campus" class="fade-in-section">
            <div class="container">
                <div class="row align-items-center g-5">
                    <div class="col-lg-6">
                        <img src="src/assets/img/ifcampus.jpg" alt="Foto do Campus Piracicaba" class="img-fluid rounded-3" style="box-shadow: var(--shadow);">
                    </div>
                    <div class="col-lg-6">
                        <span class="section-eyebrow">IFSP</span>
                        <h2 class="section-title mt-2">Campus Piracicaba</h2>
                        <p class="text-muted">Fundado em 1909 como Escola de Aprendizes Artífices, o IFSP se tornou Instituto Federal em 2008. O campus Piracicaba, implantado no mesmo ano, está estrategicamente localizado no Parque Tecnológico da cidade.</p>
                        <ul class="campus-info-list">
                            <li>
                                <i class="ri-map-pin-2-line"></i>
                                <div>
                                    <h5>Localização</h5>
                                    <p>Rua Diácono Jair de Oliveira, 1.005 - Bairro Santa Rosa, Piracicaba/SP.</p>
                                </div>
                            </li>
                            <li>
                                <i class="ri-building-4-line"></i>
                                <div>
                                    <h5>Infraestrutura Moderna</h5>
                                    <p>Com 3.763,80 m² de área construída, o campus possui três blocos: administrativo, salas de aula e laboratórios equipados.</p>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <section id="cursos" class="fade-in-section">
            <div class="container">
                <div class="text-center">
                    <h2 class="section-title">Cursos Oferecidos no Campus</h2>
                    <p class="section-subtitle">Formação pública, gratuita e de qualidade, alinhada às necessidades da região, do Ensino Médio à Pós-Graduação.</p>
                </div>

                <div class="accordion" id="accordionCategorias">

                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingGraduacao">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseGraduacao" aria-expanded="true" aria-controls="collapseGraduacao">
                                Cursos Superiores de Graduação
                            </button>
                        </h2>
                        <div id="collapseGraduacao" class="accordion-collapse collapse show" aria-labelledby="headingGraduacao" data-bs-parent="#accordionCategorias">
                            <div class="accordion-body course-accordion">
                                <div class="accordion" id="accordionGraduacao">

                                    <div class="course-accordion-item">
                                        <h2 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseEngMecanica">Engenharia Mecânica</button></h2>
                                        <div id="collapseEngMecanica" class="accordion-collapse collapse" data-bs-parent="#accordionGraduacao">
                                            <div class="accordion-body course-details">
                                                <p><strong>Objetivo:</strong> Forma professionals aptos a projetar, desenvolver e otimizar sistemas mecânicos complexos.</p>
                                                <ul>
                                                    <li class="detail-period"><strong>Período:</strong> Integral</li>
                                                    <li class="detail-duration"><strong>Duração:</strong> 10 Semestres</li>
                                                    <li class="detail-intake"><strong>Ingresso:</strong> Via SISU</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="course-accordion-item">
                                        <h2 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseEngEletrica">Engenharia Elétrica</button></h2>
                                        <div id="collapseEngEletrica" class="accordion-collapse collapse" data-bs-parent="#accordionGraduacao">
                                            <div class="accordion-body course-details">
                                                <p><strong>Objetivo:</strong> Prepara engenheiros para atuar com sistemas elétricos, eletrônicos e de automação industrial.</p>
                                                <ul>
                                                    <li class="detail-period"><strong>Período:</strong> Integral</li>
                                                    <li class="detail-duration"><strong>Duração:</strong> 10 Semestres</li>
                                                    <li class="detail-intake"><strong>Ingresso:</strong> Via SISU</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="course-accordion-item">
                                        <h2 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseEngComp">Engenharia de Computação</button></h2>
                                        <div id="collapseEngComp" class="accordion-collapse collapse" data-bs-parent="#accordionGraduacao">
                                            <div class="accordion-body course-details">
                                                <p><strong>Objetivo:</strong> Foca no desenvolvimento de hardware e software, integrando conhecimentos de eletrônica e informática.</p>
                                                <ul>
                                                    <li class="detail-period"><strong>Período:</strong> Integral</li>
                                                    <li class="detail-duration"><strong>Duração:</strong> 10 Semestres</li>
                                                    <li class="detail-intake"><strong>Ingresso:</strong> Via SISU</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="course-accordion-item">
                                        <h2 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseAutomacao">Tecnologia em Automação Industrial</button></h2>
                                        <div id="collapseAutomacao" class="accordion-collapse collapse" data-bs-parent="#accordionGraduacao">
                                            <div class="accordion-body course-details">
                                                <p><strong>Objetivo:</strong> Capacita tecnólogos para atuar na automação de processos industriais, com foco em robótica, controle e instrumentação.</p>
                                                <ul>
                                                    <li class="detail-period"><strong>Período:</strong> Noturno</li>
                                                    <li class="detail-duration"><strong>Duração:</strong> 6 Semestres</li>
                                                    <li class="detail-intake"><strong>Ingresso:</strong> Via SISU</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="course-accordion-item">
                                        <h2 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTads">Tecnologia em Análise e Desenvolvimento de Sistemas</button></h2>
                                        <div id="collapseTads" class="accordion-collapse collapse" data-bs-parent="#accordionGraduacao">
                                            <div class="accordion-body course-details">
                                                <p><strong>Objetivo:</strong> Forma profissionais para criar, gerenciar e manter sistemas de software.</p>
                                                <p class="mt-2"><strong><i class="ri-error-warning-line"></i> Atenção:</strong> Não há mais abertura de novas turmas para este curso.</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="course-accordion-item">
                                        <h2 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFisica">Licenciatura em Física</button></h2>
                                        <div id="collapseFisica" class="accordion-collapse collapse" data-bs-parent="#accordionGraduacao">
                                            <div class="accordion-body course-details">
                                                <p><strong>Objetivo:</strong> Prepara futuros professores de Física para o ensino fundamental e médio.</p>
                                                <ul>
                                                    <li class="detail-period"><strong>Período:</strong> Noturno</li>
                                                    <li class="detail-duration"><strong>Duração:</strong> 8 Semestres</li>
                                                    <li class="detail-intake"><strong>Ingresso:</strong> Via SISU</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingTecnicos">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTecnicos" aria-expanded="false" aria-controls="collapseTecnicos">
                                Cursos Técnicos
                            </button>
                        </h2>
                        <div id="collapseTecnicos" class="accordion-collapse collapse" aria-labelledby="headingTecnicos" data-bs-parent="#accordionCategorias">
                            <div class="accordion-body course-accordion">
                                <div class="accordion" id="accordionTecnicos">

                                    <div class="course-accordion-item">
                                        <h2 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTecMecanica">Técnico em Mecânica (Concomitante/Subsequente)</button></h2>
                                        <div id="collapseTecMecanica" class="accordion-collapse collapse" data-bs-parent="#accordionTecnicos">
                                            <div class="accordion-body course-details">
                                                <p><strong>Objetivo:</strong> Formação técnica sólida para o setor industrial mecânico. Capacita o aluno para fabricar peças e componentes mecânicos e executar manutenção de máquinas.</p>
                                                <ul>
                                                    <li class="detail-period"><strong>Período:</strong> Noturno</li>
                                                    <li class="detail-duration"><strong>Duração:</strong> 4 Semestres</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="course-accordion-item">
                                        <h2 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTecInfo">Técnico em Informática (Integrado ao E.M.)</button></h2>
                                        <div id="collapseTecInfo" class="accordion-collapse collapse" data-bs-parent="#accordionTecnicos">
                                            <div class="accordion-body course-details">
                                                <p><strong>Objetivo:</strong> Combina o ensino médio com uma formação técnica em informática, preparando profissionais para desenvolver softwares e sistemas.</p>
                                                <ul>
                                                    <li class="detail-period"><strong>Período:</strong> Integral</li>
                                                    <li class="detail-duration"><strong>Duração:</strong> 4 Anos</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="course-accordion-item">
                                        <h2 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTecAuto">Técnico em Manutenção Automotiva (Integrado ao E.M.)</button></h2>
                                        <div id="collapseTecAuto" class="accordion-collapse collapse" data-bs-parent="#accordionTecnicos">
                                            <div class="accordion-body course-details">
                                                <p><strong>Objetivo:</strong> Prepara técnicos especializados na manutenção de veículos automotores, atuando em montadoras, concessionárias e oficinas.</p>
                                                <ul>
                                                    <li class="detail-period"><strong>Período:</strong> Integral</li>
                                                    <li class="detail-duration"><strong>Duração:</strong> 4 Anos</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingPos">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePos" aria-expanded="false" aria-controls="collapsePos">
                                Pós-Graduação
                            </button>
                        </h2>
                        <div id="collapsePos" class="accordion-collapse collapse" aria-labelledby="headingPos" data-bs-parent="#accordionCategorias">
                            <div class="accordion-body course-accordion">
                                <div class="accordion" id="accordionPos">

                                    <div class="course-accordion-item">
                                        <h2 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePosDH">Especialização em Educação em Direitos Humanos</button></h2>
                                        <div id="collapsePosDH" class="accordion-collapse collapse" data-bs-parent="#accordionPos">
                                            <div class="accordion-body course-details">
                                                <p><strong>Objetivo:</strong> Formar docentes e educadores com competências na área dos direitos humanos, desenvolvendo domínio dos conceitos fundamentais (filosóficos, sociológicos e legais) e da trajetória histórica da construção da democracia.</p>
                                                <ul>
                                                    <li class="detail-target"><strong>Público-alvo:</strong> Prioridade para docentes de escolas públicas, educadores sociais e operadores do Direito.</li>
                                                    <li class="detail-duration"><strong>Duração:</strong> 3 Semestres</li>
                                                    <li class="detail-vacancies"><strong>Vagas:</strong> 20</li>
                                                    <li class="detail-hours"><strong>Carga Horária:</strong> 447 horas</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <section id="laboratorios" class="fade-in-section">
            <div class="container">
                <div class="text-center">
                    <h2 class="section-title">Laboratórios e Infraestrutura</h2>
                    <p class="section-subtitle">O campus é dividido em blocos, com dezenas de laboratórios de ponta para aulas práticas e desenvolvimento de pesquisas.</p>
                </div>

                <ul class="nav nav-tabs justify-content-center" id="labTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="info-tab" data-bs-toggle="tab" data-bs-target="#info-tab-pane" type="button" role="tab" aria-controls="info-tab-pane" aria-selected="false">Bloco B</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="industria-tab" data-bs-toggle="tab" data-bs-target="#industria-tab-pane" type="button" role="tab" aria-controls="industria-tab-pane" aria-selected="true">Bloco C</button>
                    </li>
                </ul>

                <div class="tab-content" id="labTabsContent">

                    <div class="tab-pane fade show active" id="info-tab-pane" role="tabpanel" aria-labelledby="info-tab" tabindex="0">
                        <div class="row g-4 mt-4">
                            <div class="col-lg-10 offset-lg-1">
                                <div id="carouselInformatica" class="carousel slide" data-bs-ride="carousel">
                                    <div class="carousel-indicators">
                                        <button type="button" data-bs-target="#carouselInformatica" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                                        <button type="button" data-bs-target="#carouselInformatica" data-bs-slide-to="1" aria-label="Slide 2"></button>
                                        <button type="button" data-bs-target="#carouselInformatica" data-bs-slide-to="2" aria-label="Slide 3"></button>
                                        <button type="button" data-bs-target="#carouselInformatica" data-bs-slide-to="3" aria-label="Slide 4"></button>
                                    </div>
                                    <div class="carousel-inner rounded-3">
                                        <div class="carousel-item active">
                                            <img src="src/assets/img/labs/B/B09/b09.jpg" class="d-block w-100" alt="Laboratório de Informática 1">
                                            <div class="carousel-caption d-none d-md-block">
                                                <h5>Laboratório de Informática B09 (i7, 16Gb)</h5>
                                            </div>
                                        </div>
                                        <div class="carousel-item">
                                            <img src="src/assets/img/labs/B/B13/b13.jpg" class="d-block w-100" alt="Laboratório de Informática 2">
                                            <div class="carousel-caption d-none d-md-block">
                                                <h5>Laboratório de Informática B13 (i3, 4Gb)</h5>
                                            </div>
                                        </div>
                                        <div class="carousel-item">
                                            <img src="src/assets/img/labs/B/B20_21/b20_21.jpg" class="d-block w-100" alt="Laboratório de Informática 3">
                                            <div class="carousel-caption d-none d-md-block">
                                                <h5>Laboratório de Informática B20/B21 (Core 2 Duo)</h5>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="carousel-item">
                                        <img src="src/assets/img/labs/B/B29/b29.jpg" class="d-block w-100" alt="Laboratório de Informática 5">
                                        <div class="carousel-caption d-none d-md-block">
                                            <h5>Laboratório de Informática B29 (i5, 6Gb)</h5>
                                        </div>
                                    </div>
                                </div>
                                <button class="carousel-control-prev" type="button" data-bs-target="#carouselInformatica" data-bs-slide="prev"><span class="carousel-control-prev-icon" aria-hidden="true"></span><span class="visually-hidden">Anterior</span></button>
                                <button class="carousel-control-next" type="button" data-bs-target="#carouselInformatica" data-bs-slide="next"><span class="carousel-control-next-icon" aria-hidden="true"></span><span class="visually-hidden">Próximo</span></button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="industria-tab-pane" role="tabpanel" aria-labelledby="industria-tab" tabindex="0">
                    <div class="row g-4 mt-4">
                        <div class="col-lg-10 offset-lg-1">
                            <div id="carouselIndustria" class="carousel slide" data-bs-ride="carousel">
                                <div class="carousel-indicators">
                                    <button type="button" data-bs-target="#carouselIndustria" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                                    <button type="button" data-bs-target="#carouselIndustria" data-bs-slide-to="1" aria-label="Slide 2"></button>
                                    <button type="button" data-bs-target="#carouselIndustria" data-bs-slide-to="2" aria-label="Slide 3"></button>
                                    <button type="button" data-bs-target="#carouselIndustria" data-bs-slide-to="3" aria-label="Slide 4"></button>
                                    <button type="button" data-bs-target="#carouselIndustria" data-bs-slide-to="4" aria-label="Slide 5"></button>
                                    <button type="button" data-bs-target="#carouselIndustria" data-bs-slide-to="5" aria-label="Slide 6"></button>
                                    <button type="button" data-bs-target="#carouselIndustria" data-bs-slide-to="6" aria-label="Slide 7"></button>
                                    <button type="button" data-bs-target="#carouselIndustria" data-bs-slide-to="7" aria-label="Slide 8"></button>
                                    <button type="button" data-bs-target="#carouselIndustria" data-bs-slide-to="8" aria-label="Slide 9"></button>
                                    <button type="button" data-bs-target="#carouselIndustria" data-bs-slide-to="9" aria-label="Slide 10"></button>
                                    <button type="button" data-bs-target="#carouselIndustria" data-bs-slide-to="10" aria-label="Slide 11"></button>
                                    <button type="button" data-bs-target="#carouselIndustria" data-bs-slide-to="11" aria-label="Slide 12"></button>
                                    <button type="button" data-bs-target="#carouselIndustria" data-bs-slide-to="12" aria-label="Slide 13"></button>
                                    <button type="button" data-bs-target="#carouselIndustria" data-bs-slide-to="13" aria-label="Slide 14"></button>
                                    <button type="button" data-bs-target="#carouselIndustria" data-bs-slide-to="14" aria-label="Slide 15"></button>
                                    <button type="button" data-bs-target="#carouselIndustria" data-bs-slide-to="15" aria-label="Slide 16"></button>
                                </div>
                                <div class="carousel-inner rounded-3">
                                    <div class="carousel-item active">
                                        <img src="src/assets/img/labs/C/C01/c01.jpg" class="d-block w-100" alt="Lab C01">
                                        <div class="carousel-caption d-none d-md-block">
                                            <h5>Laboratório de Máquinas Térmicas (C01)</h5>
                                        </div>
                                    </div>
                                    <div class="carousel-item">
                                        <img src="src/assets/img/labs/C/C02/c02.jpg" class="d-block w-100" alt="Lab C02">
                                        <div class="carousel-caption d-none d-md-block">
                                            <h5>Laboratório de CAD/CAM e Hidráulica (C02)</h5>
                                        </div>
                                    </div>
                                    <div class="carousel-item">
                                        <img src="src/assets/img/labs/C/C03/c03.jpg" class="d-block w-100" alt="Lab C03">
                                        <div class="carousel-caption d-none d-md-block">
                                            <h5>Laboratório de Vibrações Mecânicas (C03)</h5>
                                        </div>
                                    </div>
                                    <div class="carousel-item">
                                        <img src="src/assets/img/labs/C/C05/c05.jpg" class="d-block w-100" alt="Lab C05">
                                        <div class="carousel-caption d-none d-md-block">
                                            <h5>Laboratório de Processo de Fabricação - Usinagem (C05)</h5>
                                        </div>
                                    </div>
                                    <div class="carousel-item">
                                        <img src="src/assets/img/labs/C/C07/c07.jpg" class="d-block w-100" alt="Lab C07">
                                        <div class="carousel-caption d-none d-md-block">
                                            <h5>CLINP - Clube de Invenções (C07)</h5>
                                        </div>
                                    </div>
                                    <div class="carousel-item">
                                        <img src="src/assets/img/labs/C/C08_09/c08_09.jpg" alt="Lab C09">
                                        <div class="carousel-caption d-none d-md-block">
                                            <h5>Laboratório de Soldagem e Conformação (C08/C09)</h5>
                                        </div>
                                    </div>
                                    <div class="carousel-item">
                                        <img src="src/assets/img/labs/C/C10/c10.jpg" class="d-block w-100" alt="Lab C10">
                                        <div class="carousel-caption d-none d-md-block">
                                            <h5>Laboratório de Química e Metalografia (C10)</h5>
                                        </div>
                                    </div>
                                    <div class="carousel-item">
                                        <img src="src/assets/img/labs/C/C11/c11.jpg" class="d-block w-100" alt="Lab C11">
                                        <div class="carousel-caption d-none d-md-block">
                                            <h5>Laboratório de Física Básica (C11)</h5>
                                        </div>
                                    </div>
                                    <div class="carousel-item">
                                        <img src="src/assets/img/labs/C/C12/c12.jpg" class="d-block w-100" alt="Lab C12">
                                        <div class="carousel-caption d-none d-md-block">
                                            <h5>Laboratório de Instrumentação para Ensino de Física (C12)</h5>
                                        </div>
                                    </div>
                                    <div class="carousel-item">
                                        <img src="src/assets/img/labs/C/C13/c13.jpg" class="d-block w-100" alt="Lab C13">
                                        <div class="carousel-caption d-none d-md-block">
                                            <h5>Laboratório de Sistemas Digitais (C13)</h5>
                                        </div>
                                    </div>
                                    <div class="carousel-item">
                                        <img src="src/assets/img/labs/C/C14/c14.jpg" class="d-block w-100" alt="Lab C14">
                                        <div class="carousel-caption d-none d-md-block">
                                            <h5>Laboratório de Eletricidade e Eletrônica 1 (C14)</h5>
                                        </div>
                                    </div>
                                    <div class="carousel-item">
                                        <img src="src/assets/img/labs/C/C16/c16.jpg" alt="Lab C16">
                                        <div class="carousel-caption d-none d-md-block">
                                            <h5>Laboratório de Eletricidade e Eletrônica 2 (C16)</h5>
                                        </div>
                                    </div>
                                    <div class="carousel-item">
                                        <img src="src/assets/img/labs/C/C17/c17.jpg" class="d-block w-100" alt="Lab C17">
                                        <div class="carousel-caption d-none d-md-block">
                                            <h5>Laboratório de Projetos (C17)</h5>
                                        </div>
                                    </div>
                                    <div class="carousel-item">
                                        <img src="src/assets/img/labs/C/C18/c18.jpg" class="d-block w-100" alt="Lab C18">
                                        <div class="carousel-caption d-none d-md-block">
                                            <h5>Laboratório de Computação (C18)</h5>
                                        </div>
                                    </div>
                                    <div class="carousel-item">
                                        <img src="src/assets/img/labs/C/C19/c19.jpg" alt="Lab C19">
                                        <div class="carousel-caption d-none d-md-block">
                                            <h5>Laboratório de Automação e Manufatura (C19)</h5>
                                        </div>
                                    </div>
                                    <div class="carousel-item">
                                        <img src="src/assets/img/labs/C/C20/c20.jpg" class="d-block w-100" alt="Lab C20">
                                        <div class="carousel-caption d-none d-md-block">
                                            <h5>Laboratório de Energia e Máquinas (C20)</h5>
                                        </div>
                                    </div>
                                </div>
                                <button class="carousel-control-prev" type="button" data-bs-target="#carouselIndustria" data-bs-slide="prev"><span class="carousel-control-prev-icon" aria-hidden="true"></span><span class="visually-hidden">Anterior</span></button>
                                <button class="carousel-control-next" type="button" data-bs-target="#carouselIndustria" data-bs-slide="next"><span class="carousel-control-next-icon" aria-hidden="true"></span><span class="visually-hidden">Próximo</span></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </div>
        </section>

        <section id="pilares" class="fade-in-section">
            <div class="container">
                <div class="text-center">
                    <h2 class="section-title">Os 3 Pilares do IFSP</h2>
                    <p class="section-subtitle">Nossa atuação é baseada na tríade que conecta o saber à sociedade, garantindo uma formação completa e cidadã.</p>
                </div>
                <div class="row g-4">
                    <div class="col-lg-4 col-md-6">
                        <div class="feature-card">
                            <i class="ri-book-open-line"></i>
                            <h3>Ensino</h3>
                            <p>Formação integral e diversificada, incluindo cursos Técnicos (Integrados, Concomitantes, Subsequentes), Superiores (Bacharelado, Tecnologia, Licenciatura) e Pós-Graduação.</p>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="feature-card">
                            <i class="ri-flask-line"></i>
                            <h3>Pesquisa e Inovação</h3>
                            <p>Geração de conhecimento com aplicação prática. Incentivamos a Iniciação Científica, o desenvolvimento de patentes e parcerias estratégicas com empresas.</p>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-12">
                        <div class="feature-card">
                            <i class="ri-earth-line"></i>
                            <h3>Extensão</h3>
                            <p>A ponte direta com a sociedade. Oferecemos cursos de extensão à comunidade, eventos, feiras de ciências e apoio na busca de estágios através da CEX.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="suporte-estudante" class="fade-in-section">
            <div class="container">
                <div class="text-center">
                    <h2 class="section-title">Suporte ao Estudante</h2>
                    <p class="section-subtitle">No IFSP, o aluno tem uma rede completa de apoio para garantir seu sucesso acadêmico e desenvolvimento pessoal.</p>
                </div>
                <div class="row g-4">
                    <div class="col-lg-6">
                        <div class="support-card">
                            <img src="https://via.placeholder.com/600x400/e7f7e7/305F2C?text=Foto+CSP+ou+NAPNE" alt="Coordenadoria Sociopedagógica" class="support-card-img">
                            <div class="support-card-content">
                                <h3>Coordenadoria Sociopedagógica (CSP)</h3>
                                <p>Equipe multiprofissional (Assistente Social, Pedagogo, Psicólogo) que acompanha docentes e discentes, visando garantir o acesso e a permanência no ensino público e de qualidade.</p>
                                <ul>
                                    <li>Gerencia o <strong>NAPNE</strong> (Apoio a Pessoas com Necessidades Específicas).</li>
                                    <li>Promove o desenvolvimento pleno do processo educativo.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="support-card">
                            <img src="https://via.placeholder.com/600x400/e7f7e7/305F2C?text=Foto+Assistência+Estudantil" alt="Assistência Estudantil" class="support-card-img">
                            <div class="support-card-content">
                                <h3>Assistência Estudantil</h3>
                                <p>Programa de Auxílio Permanência focado em estudantes em vulnerabilidade social. Fornece subsídios para ajudar na frequência das aulas através de auxílios como:</p>
                                <ul>
                                    <li>Auxílio Alimentação (R$ 250,00)</li>
                                    <li>Auxílio Transporte (R$ 150,00 - R$ 350,00)</li>
                                    <li>Auxílio Moradia (R$ 480,00)</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="support-card">
                            <img src="https://via.placeholder.com/600x400/e7f7e7/305F2C?text=Foto+CAE+(Monitoria)" alt="Coordenadoria de Apoio ao Ensino" class="support-card-img">
                            <div class="support-card-content">
                                <h3>Apoio ao Ensino (CAE)</h3>
                                <p>Setor que auxilia as atividades do corpo discente e docente. É o principal responsável por organizar e gerenciar programas de reforço acadêmico para fortalecer o aprendizado.</p>
                                <ul>
                                    <li>Organização de <strong>Monitorias</strong>.</li>
                                    <li>Programas de <strong>Nivelamento</strong>.</li>
                                    <li>Gestão do Regimento Disciplinar Discente.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="support-card">
                            <img src="https://via.placeholder.com/600x400/e7f7e7/305F2C?text=Foto+CRA+(Secretaria)" alt="Coordenadoria de Registros Acadêmicos" class="support-card-img">
                            <div class="support-card-content">
                                <h3>Registros Acadêmicos (CRA)</h3>
                                <p>A "secretaria" do campus. É responsável por controlar, verificar, registrar e arquivar toda a documentação da vida acadêmica do aluno, desde o seu ingresso até a conclusão.</p>
                                <ul>
                                    <li>Emissão de Atestados e Históricos.</li>
                                    <li>Processos de Trancamento e Matrícula.</li>
                                    <li>Expedição de Diplomas e Certificados.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="qualidade" class="fade-in-section">
            <div class="container text-center">
                <h2 class="section-title">Qualidade Comprovada</h2>
                <p class="section-subtitle">Nossa excelência é atestada por avaliações oficiais e pelo sucesso de nossos alunos e egressos</p>
                <div class="row g-4 mt-5">
                    <div class="col-md-4">
                        <div class="quality-card">
                            <i class="ri-government-line"></i>
                            <h5>Avaliação do MEC</h5>
                            <p>O IFSP é atestado por avaliações do MEC, com altos Índices Gerais de Cursos (IGC) e Conceitos de Curso (CC)</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="quality-card">
                            <i class="ri-trophy-line"></i>
                            <h5>Prêmios e Olimpíadas</h5>
                            <p>Alunos e professores conquistam prêmios em olimpíadas científicas, feiras de inovação e competições nacionais e internacionais</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="quality-card">
                            <i class="ri-briefcase-4-line"></i>
                            <h5>Alta Empregabilidade</h5>
                            <p>Nossos egressos possuem alta taxa de empregabilidade, sendo reconhecidos e procurados pelo mercado de trabalho</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </main>

    <footer>
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-5 col-md-6">
                    <h5>IFApoia</h5>
                    <p>Instituto Federal de Educação, Ciência e Tecnologia de São Paulo - Câmpus Piracicaba.</p>
                    <p class="mt-3"><strong>Endereço:</strong><br>Rua Diácono Jair de Oliveira, 1.005 - Bairro Santa Rosa, Piracicaba/SP. CEP: 13.414-155.</p>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h5>Navegação</h5>
                    <ul class="list-unstyled">
                        <li><a href="#inicio">Home</a></li>
                        <li><a href="#sobre-projeto">O Projeto</a></li>
                        <li><a href="#rede-interna">Rede</a></li>
                        <li><a href="#equipe">Equipe</a></li>
                        <li><a href="#sobre-campus">Nosso Campus</a></li>
                        <li><a href="#cursos">Cursos</a></li>
                        <li><a href="#laboratorios">Laboratórios</a></li>
                        <li><a href="#pilares">Pilares</a></li>
                        <li><a href="#suporte-estudante">Suporte</a></li>
                        <li><a href="#qualidade">Qualidade</a></li>
                    </ul>
                </div>
                <div class="col-lg-4 col-md-12">
                    <h5>Siga-nos</h5>
                    <p>Conecte-se conosco nas redes sociais oficiais.</p>
                    <div class="social-icons">
                        <a href="#"><i class="ri-facebook-box-fill"></i></a>
                        <a href="#"><i class="ri-instagram-fill"></i></a>
                        <a href="#"><i class="ri-youtube-fill"></i></a>
                        <a href="#"><i class="ri-linkedin-box-fill"></i></a>
                    </div>
                </div>
            </div>
            <div class="footer-bottom text-center">
                <p>&copy; <?php echo date("Y"); ?> IFApoia - IFSP Piracicaba. Todos os direitos reservados.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const nav = document.getElementById('main-nav');
            const sections = document.querySelectorAll('.fade-in-section');
            const navLinks = document.querySelectorAll('#main-nav .nav-link');
            const allSections = document.querySelectorAll('main section[id]');

            // 1. Efeito da Navbar ao rolar
            const handleScroll = () => {
                if (window.scrollY > 50) {
                    nav.classList.add('navbar-scrolled');
                    nav.classList.add('scrolled-true');
                } else {
                    nav.classList.remove('navbar-scrolled');
                    nav.classList.remove('scrolled-true');
                }

                // 2. Lógica do "Scroll Spy" (Marcar link ativo)
                let current = '';
                allSections.forEach(section => {
                    const sectionTop = section.offsetTop;
                    if (window.scrollY >= sectionTop - 100) { // 100px de offset
                        current = section.getAttribute('id');
                    }
                });

                navLinks.forEach(link => {
                    link.classList.remove('active');
                    if (link.getAttribute('href') === `#${current}`) {
                        link.classList.add('active');
                    }
                });
            };
            window.addEventListener('scroll', handleScroll);
            handleScroll(); // Executa uma vez no carregamento

            // 3. Animação "Fade-in" ao rolar
            const observerOptions = {
                root: null,
                rootMargin: '0px',
                threshold: 0.1
            };

            const observer = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-visible');
                        observer.unobserve(entry.target);
                    }
                });
            }, observerOptions);

            sections.forEach(section => {
                observer.observe(section);
            });

            // 4. Fechar menu mobile ao clicar no link
            const navCollapse = document.querySelector('.navbar-collapse');
            const bsCollapse = new bootstrap.Collapse(navCollapse, {
                toggle: false
            });

            navLinks.forEach(link => {
                link.addEventListener('click', () => {
                    if (navCollapse.classList.contains('show')) {
                        bsCollapse.hide();
                    }
                });
            });
        });
    </script>
</body>

</html>