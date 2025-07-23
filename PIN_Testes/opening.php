<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>IFApoia - IFSP Piracicaba</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.6.0/fonts/remixicon.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/3.2.1/anime.min.js"></script>
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
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-color: var(--background);
            font-family: 'Inter', sans-serif;
            line-height: 1.6;
            color: var(--text-color);
            overflow-x: hidden;
            /* Previne o scroll horizontal */
            position: relative;
            /* Permite o uso de position: absolute no navbar */
        }

        #nav {
            position: absolute;
            z-index: 1000;
            /* Garante que a navbar fique acima de outros elementos */
            left: 0;
            right: 0;
            margin: 10px 50px 0 50px;
            background: transparent;

            &>.container-fluid {

                &>.col-6 .navbar-brand {
                    color: var(--white) !important;
                    text-decoration: none;
                    filter: drop-shadow(1px 1px 0 rgba(0, 0, 0, 0.4));
                    position: relative;

                    &>img {
                        width: 35px;
                    }

                    &>img:hover {
                        transform: scale(1.05);
                        transition: var(--transition);
                    }
                }


                .btn-acess {
                    background: none;
                    color: white;
                    border: none;
                    font-weight: 600;
                    border-radius: var(--border-radius);
                    transition: all 0.3s;
                    position: relative;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    overflow: hidden;
                }

                .btn-acess .text {
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    transition: var(--transition);
                    font-size: 1.2rem;

                    &>i {
                        font-size: 20px;
                    }
                }

                .btn-acess .text.hover {
                    position: absolute;
                    opacity: 0;
                    pointer-events: none;
                }

                .btn-acess:hover .text.normal {
                    opacity: 0;
                }

                .btn-acess:hover .text.hover {
                    opacity: 1;
                }

                .btn-acess:before {
                    content: "";
                    position: absolute;
                    width: 135px;
                    height: 120%;
                    z-index: 10;
                    background-color: rgb(228, 228, 228);
                    filter: blur(10px);
                    top: 50%;
                    transform: skewX(30deg) translate(-150%, -50%);
                    transition: all 0.5s;
                }

                .btn-acess:hover {
                    background-color: var(--secondaryButton);
                    color: var(--white);
                    padding: 0.5rem 0.8rem;
                    box-shadow: 0 2px 0 2px rgb(9, 75, 15);
                    transform: scale(1.05);
                }

                .btn-acess:hover::before {
                    transform: translate(-50%, -50%);
                    transform: skewX(30deg) translate(150%, -50%);
                    transition-delay: 0.1s;
                }

                .btn-acess:active {
                    transform: scale(0.9);
                }
            }

        }

        .hero {
            background: linear-gradient(135deg, rgba(46, 125, 50, 0.7) 0%, rgba(48, 95, 44, 0.88) 100%), url('src/assets/img/ifcampus.jpg');
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
            color: white;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
            padding: 8rem 2rem;
            text-align: center;
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
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
        }

        .hero p {
            font-size: 1.3rem;
            margin-bottom: 2rem;
            font-weight: 300;
        }

        .hero .btn-more {
            background: rgb(150, 197, 132, 0.4);
            color: white;
            text-decoration: none;
            border: 3px solid var(--accent);
            padding: 1.1rem;
            font-size: 1rem;
            font-weight: 500;
            border-radius: var(--border-radius);
            transition: var(--transition);
        }

        .hero .btn-more:hover {
            padding: 1.1rem;
            font-size: 1.2rem;
            font-weight: 700;
            box-shadow: 0px 0px 15px inset rgba(48, 95, 44, 1);
            border: 3px solid #8cc974;
        }

        .hero .btn-more:active {
            padding: 0.9rem;
            font-size: 1rem;
            font-weight: 600;
            box-shadow: none;
            border: 3px solid var(--accent);
        }

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
        #ns1 {
            padding: 6rem 0;
            background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        }

        #ns1 ul {
            list-style: none;
            padding: 0;
        }

        #ns1 li {
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

        /*#ns1 li:hover {
            transform: translateX(10px);
            box-shadow: var(--shadow-medium);
            border-left-color: var(--accent);
        }

        #ns1 li::before {
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

        #ns1 li:hover::before {
            background: var(--accent);
            transform: translateY(-50%) scale(1.1);
        }*/

        /* Enhanced Structure Section */
        #ns2 {
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
        #ns3 {
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
        #ns4 {
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

        /* Scrollbar Styles */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: var(--background);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--primary);
            border-radius: 3px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--secondaryButton);
        }
    </style>
</head>

<body>
    <nav class="animate__animated animate__slideInDown animate__fast navbar navbar-expand-md" id="nav">
        <div class="container-fluid">
            <div class="col-6">
                <a class="navbar-brand" href="ifapoia.com.br">
                    <img src="src/assets/img/Logotipo_antiga2.png" alt="Logo IFApoia">
                </a>
            </div>
            <div class="col-6 d-flex justify-content-end">
                <a class="btn btn-acess" href="#" target="_blank">
                    <span class="text normal">
                        <i class="ri-login-box-line me-2"></i>Acesse IFApoia</span>
                    <span class="text hover">
                        <i class="ri-user-smile-line me-2"></i>Entre agora!</span>
                </a>
            </div>
        </div>
    </nav>


    <section id="home" class="hero">
        <div class="hero-content">
            <h1 class="animate__animated animate__fadeInUp animate__fast">Conheça o IFApoia</h1>
            <p class="animate__animated animate__fadeInUp">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam eget ligula facilisis, faucibus ipsum ut, fringilla neque. Pellentesque in ultricies libero. Phasellus ac ante gravida, congue lacus vestibulum, posuere ante.</p>
            <a class="animate__animated animate__fadeIn animate__delay-1s btn-more" href="#ns1">
                <i class="ri-arrow-down-line me-2"></i>Descobrir mais
            </a>
        </div>
    </section>

    <section id="ns1" class="">
        <div class="container">
            <h2 class="section-title">ns1</h2>
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

    <section id="ns2" class="">
        <div class="container">
            <h2 class="section-title">ns2</h2>
            <div class="row row-cols-1 row-cols-md-3 g-4">
                <div class="col">
                    <div class="structure-card">
                        <img src="https://images.pexels.com/photos/2280571/pexels-photo-2280571.jpeg?auto=compress&cs=tinysrgb&w=400" class="img-fluid" alt="Laboratório">
                        <h4 class="mt-3 mb-3" style="color: var(--primary); font-weight: 600;">Laboratórios Modernos</h4>
                        <p>Laboratórios modernos e equipados com tecnologia de ponta para diversas áreas do conhecimento, proporcionando experiência prática aos estudantes.</p>
                    </div>
                </div>
                <div class="col">
                    <div class="structure-card">
                        <img src="https://images.pexels.com/photos/2041540/pexels-photo-2041540.jpeg?auto=compress&cs=tinysrgb&w=400" class="img-fluid" alt="Biblioteca">
                        <h4 class="mt-3 mb-3" style="color: var(--primary); font-weight: 600;">Biblioteca Digital</h4>
                        <p>Bibliotecas com acervo atualizado, espaços de estudo colaborativo e recursos digitais para pesquisa acadêmica avançada.</p>
                    </div>
                </div>
                <div class="col">
                    <div class="structure-card">
                        <img src="https://images.pexels.com/photos/1438072/pexels-photo-1438072.jpeg?auto=compress&cs=tinysrgb&w=400" class="img-fluid" alt="Convivência">
                        <h4 class="mt-3 mb-3" style="color: var(--primary); font-weight: 600;">Espaços de Convivência</h4>
                        <p>Áreas de convivência modernas que promovem a integração entre os alunos e facilitam o networking acadêmico.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="ns3" class="">
        <div class="container">
            <h2 class="section-title">ns3</h2>
            <div class="accordion" id="ns3Accordion">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingComp">
                        <button class="accordion-button collapsed" type="button"
                            data-bs-toggle="collapse" data-bs-target="#collapseComp"
                            aria-expanded="false" aria-controls="collapseComp">
                            <i class="ri-terminal-window-line me-3"></i>Engenharia da Computação
                        </button>
                    </h2>
                    <div id="collapseComp" class="accordion-collapse collapse"
                        aria-labelledby="headingComp" data-bs-parent="#ns3Accordion">
                        <div class="accordion-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong><i class="ri-time-line me-2"></i>Duração:</strong> 10 semestres</p>
                                    <p><strong><i class="ri-graduation-cap-line me-2"></i>Modalidade:</strong> Presencial</p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong><i class="ri-article-line me-2"></i>Titulação:</strong> Bacharel</p>
                                    <p><strong><i class="ri-calendar-2-line me-2"></i>Período:</strong> Integral</p>
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
                            <i class="ri-database-2-line me-3"></i>Tecnologia em Análise e Desenvolvimento de Sistemas
                        </button>
                    </h2>
                    <div id="collapseInfo" class="accordion-collapse collapse"
                        aria-labelledby="headingInfo" data-bs-parent="#ns3Accordion">
                        <div class="accordion-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong><i class="ri-time-line me-2"></i>Duração:</strong> 6 semestres</p>
                                    <p><strong><i class="ri-graduation-cap-line me-2"></i>Modalidade:</strong> Presencial</p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong><i class="ri-article-line me-2"></i>Titulação:</strong> Tecnólogo</p>
                                    <p><strong><i class="ri-calendar-2-line me-2"></i>Período:</strong> Noturno</p>
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
                            <i class="ri-home-gear-line me-3"></i>Engenharia Mecânica
                        </button>
                    </h2>
                    <div id="collapseMec" class="accordion-collapse collapse"
                        aria-labelledby="headingMec" data-bs-parent="#ns3Accordion">
                        <div class="accordion-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong><i class="ri-time-line me-2"></i>Duração:</strong> 10 semestres</p>
                                    <p><strong><i class="ri-graduation-cap-line me-2"></i>Modalidade:</strong> Presencial</p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong><i class="ri-article-line me-2"></i>Titulação:</strong> Bacharel</p>
                                    <p><strong><i class="ri-calendar-2-line me-2"></i>Período:</strong> Integral</p>
                                </div>
                            </div>
                            <p><strong>Perfil Profissional:</strong> Projetar, desenvolver e otimizar sistemas mecânicos, máquinas industriais e processos de manufatura com foco em sustentabilidade.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="testimonial-section">
        <div class="container">
            <h2 class="section-title text-center">Depoimentos</h2>
            <div id="carrouselDepoimentos" class="carousel slide" data-bs-ride="carousel">
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
                            <p class="mb-0">"Os professores são incríveis e sempre apoiam nossos projetos. A infrans2 é de primeira qualidade e as oportunidades são infinitas."</p>
                            <footer class="blockquote-footer mt-3">
                                <strong>Lucas Santos</strong>, Aluno de Análise e Desenvolvimento de Sistemas
                            </footer>
                        </blockquote>
                    </div>
                    <div class="carousel-item">
                        <blockquote class="blockquote text-center">
                            <p class="mb-0">"A experiência no IFSP me proporcionou não apenas conhecimento técnico, mas também uma visão ampla 1 inovação e sustentabilidade."</p>
                            <footer class="blockquote-footer mt-3">
                                <strong>Maria Oliveira</strong>, Ex-aluna de Engenharia Mecânica
                            </footer>
                        </blockquote>
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carrouselDepoimentos" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Anterior</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carrouselDepoimentos" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Próximo</span>
                </button>
            </div>
        </div>
    </section>

    <section id="ns4" class="">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <h2 class="section-title">ns4</h2>
                    <form id="contactForm">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nome" class="form-label">
                                    <i class="fas fa-user me-2"></i>Nome Completo
                                </label>
                                <input type="text" class="form-control" id="nome" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">
                                    <i class="fas fa-envelope me-2"></i>E-mail
                                </label>
                                <input type="email" class="form-control" id="email" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="assunto" class="form-label">
                                <i class="fas fa-tag me-2"></i>Assunto
                            </label>
                            <select class="form-control" id="assunto" required>
                                <option value="">Selecione um assunto</option>
                                <option value="informacoes">Informações 1 cursos</option>
                                <option value="inscricoes">Processo de inscrição</option>
                                <option value="campus">ns2 do campus</option>
                                <option value="outros">Outros assuntos</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="mensagem" class="form-label">
                                <i class="fas fa-comment me-2"></i>Mensagem
                            </label>
                            <textarea class="form-control" id="mensagem" rows="5" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-acess btn-lg">
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
        // carousel automático
        const carousel = document.querySelector('#carrouselDepoimentos');
        if (carousel) {
            const carouselInstance = new bootstrap.Carousel(carousel, {
                interval: 5000,
                wrap: true
            });
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

        // Smooth scroll para o botão .btn-more com anime.js
        document.addEventListener('DOMContentLoaded', function() {
            const btnMore = document.querySelector('.hero .btn-more');

            if (btnMore) {
                btnMore.addEventListener('click', function(e) {
                    e.preventDefault(); // Previne o comportamento padrão do link
                    const targetSection = document.querySelector(this.getAttribute('href'));
                    if (targetSection) {
                        const targetPosition = targetSection.offsetTop;
                        console.log('Seção alvo encontrada:', targetSection); // Adicionado para verificar a seção
                        console.log('Posição alvo:', targetPosition); // Adicionado para verificar a posição
                        window.anime({
                            targets: 'html, body',
                            scrollTop: targetPosition,
                            duration: 100, // Duração da animação em milissegundos
                            easing: 'easeInOutQuad' // Tipo de easing para a animação
                        });
                    }
                    console.log('Animação anime.js iniciada.'); // Adicionado para verificar se o anime é chamado
                });
            }
        });

        console.log('🎓 IFApoia Website loaded successfully!');
    </script>
</body>

</html>