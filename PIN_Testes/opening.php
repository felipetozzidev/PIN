<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IFApoia - IFSP Piracicaba</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        ifsp: {
                            blue: '#003366',
                            green: '#00A859',
                            light: '#F5F7FA',
                            accent: '#FF6B35',
                        },
                    },
                    fontFamily: {
                        sans: ['Poppins', 'sans-serif'],
                    },
                    animation: {
                        'fade-in': 'fadeIn 1s ease-in-out',
                        'slide-up': 'slideUp 0.8s ease-out',
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': {
                                opacity: '0'
                            },
                            '100%': {
                                opacity: '1'
                            },
                        },
                        slideUp: {
                            '0%': {
                                transform: 'translateY(20px)',
                                opacity: '0'
                            },
                            '100%': {
                                transform: 'translateY(0)',
                                opacity: '1'
                            },
                        },
                    },
                },
            },
        }
    </script>

    <style type="text/css">
        .hero-section {
            background-image: linear-gradient(rgba(0, 51, 102, 0.8), rgba(0, 168, 89, 0.6)), url('https://images.unsplash.com/photo-1523050854058-8df90110c9f1?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        .course-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .testimonial-card {
            transition: all 0.3s ease;
        }

        .testimonial-card:hover {
            transform: scale(1.03);
        }

        @media (max-width: 768px) {
            .mobile-menu {
                display: none;
            }

            .mobile-menu.active {
                display: flex;
                flex-direction: column;
                position: absolute;
                top: 80px;
                left: 0;
                right: 0;
                background-color: white;
                padding: 2rem;
                box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
                z-index: 50;
            }
        }
    </style>
</head>

<body class="font-sans bg-ifsp-light text-gray-800">
    <!-- Header -->
    <header class="bg-white shadow-md fixed w-full z-50">
        <div class="container mx-auto px-4 py-3 flex justify-between items-center">
            <!-- Logo -->
            <div class="flex items-center">
                <img src="https://upload.wikimedia.org/wikipedia/commons/9/9a/Logo_IFSP.svg" alt="IFSP Logo" class="h-12">
                <span class="ml-3 text-2xl font-bold text-ifsp-blue">IFApoia</span>
            </div>

            <!-- Desktop Navigation -->
            <nav class="hidden md:flex space-x-8">
                <a href="#inicio" class="text-ifsp-blue hover:text-ifsp-green font-medium transition">Início</a>
                <a href="#sobre" class="text-ifsp-blue hover:text-ifsp-green font-medium transition">Sobre</a>
                <a href="#estrutura" class="text-ifsp-blue hover:text-ifsp-green font-medium transition">Estrutura</a>
                <a href="#cursos" class="text-ifsp-blue hover:text-ifsp-green font-medium transition">Cursos</a>
                <a href="#contato" class="text-ifsp-blue hover:text-ifsp-green font-medium transition">Contato</a>
            </nav>

            <!-- GitHub Button -->
            <a href="https://github.com/ifapoia" target="_blank" class="hidden md:flex items-center bg-ifsp-blue text-white px-4 py-2 rounded-lg hover:bg-ifsp-green transition">
                <i class="fab fa-github mr-2"></i> GitHub
            </a>

            <!-- Mobile Menu Button -->
            <button id="mobile-menu-button" class="md:hidden text-ifsp-blue">
                <i class="fas fa-bars text-2xl"></i>
            </button>
        </div>

        <!-- Mobile Navigation -->
        <div id="mobile-menu" class="mobile-menu">
            <a href="#inicio" class="py-2 text-ifsp-blue hover:text-ifsp-green">Início</a>
            <a href="#sobre" class="py-2 text-ifsp-blue hover:text-ifsp-green">Sobre</a>
            <a href="#estrutura" class="py-2 text-ifsp-blue hover:text-ifsp-green">Estrutura</a>
            <a href="#cursos" class="py-2 text-ifsp-blue hover:text-ifsp-green">Cursos</a>
            <a href="#contato" class="py-2 text-ifsp-blue hover:text-ifsp-green">Contato</a>
            <a href="https://github.com/ifapoia" target="_blank" class="mt-2 flex items-center justify-center bg-ifsp-blue text-white px-4 py-2 rounded-lg">
                <i class="fab fa-github mr-2"></i> GitHub
            </a>
        </div>
    </header>

    <!-- Hero Section -->
    <section id="inicio" class="hero-section pt-32 pb-20 md:pt-40 md:pb-28 text-center text-white">
        <div class="container mx-auto px-4">
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-4 animate-fade-in">Bem-vindo ao IFApoia</h1>
            <p class="text-xl md:text-2xl mb-8 max-w-2xl mx-auto animate-fade-in">Conectando você ao futuro da educação no IFSP Piracicaba.</p>
            <a href="#sobre" class="inline-block bg-ifsp-accent hover:bg-opacity-90 text-white font-semibold px-6 py-3 rounded-lg transition animate-slide-up">
                Saiba Mais
            </a>
        </div>
    </section>

    <!-- About Section -->
    <section id="sobre" class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-ifsp-blue mb-4">Sobre o Projeto</h2>
                <div class="w-20 h-1 bg-ifsp-green mx-auto"></div>
            </div>

            <div class="flex flex-col lg:flex-row items-center">
                <div class="lg:w-1/2 mb-8 lg:mb-0 lg:pr-8">
                    <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1471&q=80"
                        alt="Estudantes no IFSP"
                        class="rounded-lg shadow-lg w-full">
                </div>

                <div class="lg:w-1/2">
                    <h3 class="text-2xl font-semibold text-ifsp-blue mb-4">Nosso Objetivo</h3>
                    <p class="text-gray-700 mb-6">
                        O IFApoia tem como missão fornecer suporte acadêmico e pessoal aos estudantes do IFSP Piracicaba,
                        promovendo um ambiente educacional inclusivo e inovador. Nosso objetivo é criar uma ponte entre
                        os alunos e as oportunidades de desenvolvimento oferecidas pelo instituto.
                    </p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-ifsp-light p-4 rounded-lg">
                            <div class="flex items-center mb-2">
                                <div class="bg-ifsp-green text-white p-2 rounded-full mr-3">
                                    <i class="fas fa-users"></i>
                                </div>
                                <h4 class="font-semibold text-ifsp-blue">Apoio a Projetos</h4>
                            </div>
                            <p class="text-gray-700 text-sm">Incentivamos e financiamos projetos estudantis inovadores.</p>
                        </div>

                        <div class="bg-ifsp-light p-4 rounded-lg">
                            <div class="flex items-center mb-2">
                                <div class="bg-ifsp-green text-white p-2 rounded-full mr-3">
                                    <i class="fas fa-handshake"></i>
                                </div>
                                <h4 class="font-semibold text-ifsp-blue">Integração Comunitária</h4>
                            </div>
                            <p class="text-gray-700 text-sm">Promovemos a conexão entre estudantes, professores e a comunidade.</p>
                        </div>

                        <div class="bg-ifsp-light p-4 rounded-lg">
                            <div class="flex items-center mb-2">
                                <div class="bg-ifsp-green text-white p-2 rounded-full mr-3">
                                    <i class="fas fa-leaf"></i>
                                </div>
                                <h4 class="font-semibold text-ifsp-blue">Desenvolvimento Sustentável</h4>
                            </div>
                            <p class="text-gray-700 text-sm">Iniciativas alinhadas com os Objetivos de Desenvolvimento Sustentável da ONU.</p>
                        </div>

                        <div class="bg-ifsp-light p-4 rounded-lg">
                            <div class="flex items-center mb-2">
                                <div class="bg-ifsp-green text-white p-2 rounded-full mr-3">
                                    <i class="fas fa-graduation-cap"></i>
                                </div>
                                <h4 class="font-semibold text-ifsp-blue">Excelência Acadêmica</h4>
                            </div>
                            <p class="text-gray-700 text-sm">Suporte a pesquisas e trabalhos acadêmicos de alta qualidade.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Campus Structure -->
    <section id="estrutura" class="py-16 bg-ifsp-light">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-ifsp-blue mb-4">Estrutura do Campus</h2>
                <div class="w-20 h-1 bg-ifsp-green mx-auto"></div>
                <p class="text-gray-600 max-w-2xl mx-auto mt-4">Conheça nossas instalações modernas e equipadas para oferecer a melhor experiência educacional.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Lab Card -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden transition hover:shadow-lg">
                    <img src="https://images.unsplash.com/photo-1581094794329-c8112a89af12?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80"
                        alt="Laboratório"
                        class="w-full h-48 object-cover">
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-ifsp-blue mb-2">Laboratórios Modernos</h3>
                        <p class="text-gray-700">Equipados com tecnologia de ponta para aulas práticas e pesquisas acadêmicas.</p>
                    </div>
                </div>

                <!-- Library Card -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden transition hover:shadow-lg">
                    <img src="https://images.unsplash.com/photo-1589998059171-988d322dfe03?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80"
                        alt="Biblioteca"
                        class="w-full h-48 object-cover">
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-ifsp-blue mb-2">Bibliotecas Equipadas</h3>
                        <p class="text-gray-700">Acervo físico e digital completo com espaços para estudo individual e em grupo.</p>
                    </div>
                </div>

                <!-- Common Areas Card -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden transition hover:shadow-lg">
                    <img src="https://images.unsplash.com/photo-1541178735493-479c1a27ed24?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1471&q=80"
                        alt="Áreas de Convivência"
                        class="w-full h-48 object-cover">
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-ifsp-blue mb-2">Áreas de Convivência</h3>
                        <p class="text-gray-700">Espaços confortáveis para interação, descanso e atividades extracurriculares.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Courses Section -->
    <section id="cursos" class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-ifsp-blue mb-4">Cursos Oferecidos</h2>
                <div class="w-20 h-1 bg-ifsp-green mx-auto"></div>
                <p class="text-gray-600 max-w-2xl mx-auto mt-4">Conheça nossos cursos de qualidade reconhecida em diversas áreas do conhecimento.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- TI Course -->
                <div class="course-card bg-ifsp-light rounded-lg p-6 transition shadow-md hover:shadow-xl">
                    <div class="text-ifsp-blue text-4xl mb-4">
                        <i class="fas fa-laptop-code"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-ifsp-blue mb-2">Tecnologia da Informação</h3>
                    <p class="text-gray-700 mb-4">Formação em programação, redes e sistemas da informação.</p>
                    <a href="#" class="text-ifsp-green font-medium inline-flex items-center">
                        Saiba mais <i class="fas fa-chevron-right ml-2 text-sm"></i>
                    </a>
                </div>

                <!-- Engineering Course -->
                <div class="course-card bg-ifsp-light rounded-lg p-6 transition shadow-md hover:shadow-xl">
                    <div class="text-ifsp-blue text-4xl mb-4">
                        <i class="fas fa-cogs"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-ifsp-blue mb-2">Engenharias</h3>
                    <p class="text-gray-700 mb-4">Cursos de engenharia mecânica, elétrica e produção.</p>
                    <a href="#" class="text-ifsp-green font-medium inline-flex items-center">
                        Saiba mais <i class="fas fa-chevron-right ml-2 text-sm"></i>
                    </a>
                </div>

                <!-- Humanities Course -->
                <div class="course-card bg-ifsp-light rounded-lg p-6 transition shadow-md hover:shadow-xl">
                    <div class="text-ifsp-blue text-4xl mb-4">
                        <i class="fas fa-book"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-ifsp-blue mb-2">Ciências Humanas</h3>
                    <p class="text-gray-700 mb-4">Formação em administração, pedagogia e áreas afins.</p>
                    <a href="#" class="text-ifsp-green font-medium inline-flex items-center">
                        Saiba mais <i class="fas fa-chevron-right ml-2 text-sm"></i>
                    </a>
                </div>

                <!-- Other Courses -->
                <div class="course-card bg-ifsp-light rounded-lg p-6 transition shadow-md hover:shadow-xl">
                    <div class="text-ifsp-blue text-4xl mb-4">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-ifsp-blue mb-2">Outros Cursos</h3>
                    <p class="text-gray-700 mb-4">Diversos cursos técnicos e profissionalizantes.</p>
                    <a href="#" class="text-ifsp-green font-medium inline-flex items-center">
                        Saiba mais <i class="fas fa-chevron-right ml-2 text-sm"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section class="py-16 bg-ifsp-green text-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold mb-4">Depoimentos</h2>
                <div class="w-20 h-1 bg-white mx-auto"></div>
                <p class="max-w-2xl mx-auto mt-4 opacity-90">Veja o que nossos alunos e ex-alunos dizem sobre a experiência no IFSP Piracicaba.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Testimonial 1 -->
                <div class="testimonial-card bg-white bg-opacity-10 p-6 rounded-lg backdrop-blur-sm">
                    <div class="flex items-center mb-4">
                        <img src="https://randomuser.me/api/portraits/women/43.jpg" alt="Aluna" class="w-12 h-12 rounded-full mr-4">
                        <div>
                            <h4 class="font-semibold">Camila Silva</h4>
                            <p class="text-sm opacity-80">Téc. em Informática</p>
                        </div>
                    </div>
                    <p class="italic">"O IFApoia foi essencial para meu crescimento acadêmico. Os recursos e apoio oferecidos fizeram toda a diferença!"</p>
                    <div class="flex mt-4 text-yellow-300">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                </div>

                <!-- Testimonial 2 -->
                <div class="testimonial-card bg-white bg-opacity-10 p-6 rounded-lg backdrop-blur-sm">
                    <div class="flex items-center mb-4">
                        <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Aluno" class="w-12 h-12 rounded-full mr-4">
                        <div>
                            <h4 class="font-semibold">Ricardo Oliveira</h4>
                            <p class="text-sm opacity-80">Eng. Mecânica</p>
                        </div>
                    </div>
                    <p class="italic">"A estrutura do campus é incrível, e os professores são altamente qualificados. Recomendo para todos que buscam ensino de qualidade."</p>
                    <div class="flex mt-4 text-yellow-300">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                    </div>
                </div>

                <!-- Testimonial 3 -->
                <div class="testimonial-card bg-white bg-opacity-10 p-6 rounded-lg backdrop-blur-sm">
                    <div class="flex items-center mb-4">
                        <img src="https://randomuser.me/api/portraits/women/65.jpg" alt="Ex-aluna" class="w-12 h-12 rounded-full mr-4">
                        <div>
                            <h4 class="font-semibold">Ana Beatriz</h4>
                            <p class="text-sm opacity-80">Ex-aluna - ADM</p>
                        </div>
                    </div>
                    <p class="italic">"O IFSP me preparou não só academicamente, mas também como profissional e cidadã. Tenho muito orgulho de ter estudado aqui."</p>
                    <div class="flex mt-4 text-yellow-300">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contato" class="py-16 bg-ifsp-light">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-ifsp-blue mb-4">Entre em Contato</h2>
                <div class="w-20 h-1 bg-ifsp-green mx-auto"></div>
                <p class="text-gray-600 max-w-2xl mx-auto mt-4">Tem dúvidas ou sugestões? Entre em contato conosco pelo formulário ou pelas redes sociais.</p>
            </div>

            <div class="flex flex-col lg:flex-row gap-8">
                <!-- Contact Form -->
                <div class="lg:w-1/2">
                    <form class="bg-white p-6 rounded-lg shadow-md">
                        <div class="mb-4">
                            <label for="name" class="block text-gray-700 font-medium mb-2">Nome</label>
                            <input type="text" id="name" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-ifsp-green">
                        </div>

                        <div class="mb-4">
                            <label for="email" class="block text-gray-700 font-medium mb-2">E-mail</label>
                            <input type="email" id="email" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-ifsp-green">
                        </div>

                        <div class="mb-4">
                            <label for="message" class="block text-gray-700 font-medium mb-2">Mensagem</label>
                            <textarea id="message" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-ifsp-green"></textarea>
                        </div>

                        <button type="submit" class="w-full bg-ifsp-blue text-white py-3 rounded-lg font-semibold hover:bg-ifsp-green transition">
                            Enviar Mensagem
                        </button>
                    </form>
                </div>

                <!-- Contact Info -->
                <div class="lg:w-1/2">
                    <div class="bg-white p-6 rounded-lg shadow-md h-full">
                        <h3 class="text-xl font-semibold text-ifsp-blue mb-6">Informações de Contato</h3>

                        <div class="space-y-4">
                            <div class="flex items-start">
                                <div class="text-ifsp-blue text-xl mr-4 mt-1">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                                <div>
                                    <h4 class="font-medium text-ifsp-blue">Endereço</h4>
                                    <p class="text-gray-700">Rua Diácono Jair de Oliveira, 1005<br>Vila Rezende - Piracicaba/SP</p>
                                </div>
                            </div>

                            <div class="flex items-start">
                                <div class="text-ifsp-blue text-xl mr-4 mt-1">
                                    <i class="fas fa-phone"></i>
                                </div>
                                <div>
                                    <h4 class="font-medium text-ifsp-blue">Telefone</h4>
                                    <p class="text-gray-700">(19) 3412-4100</p>
                                </div>
                            </div>

                            <div class="flex items-start">
                                <div class="text-ifsp-blue text-xl mr-4 mt-1">
                                    <i class="fas fa-envelope"></i>
                                </div>
                                <div>
                                    <h4 class="font-medium text-ifsp-blue">E-mail</h4>
                                    <p class="text-gray-700">contato@ifsp.edu.br</p>
                                </div>
                            </div>

                            <div class="pt-4">
                                <h4 class="font-medium text-ifsp-blue mb-2">Redes Sociais</h4>
                                <div class="flex space-x-4">
                                    <a href="#" class="text-ifsp-blue hover:text-ifsp-green text-2xl">
                                        <i class="fab fa-facebook"></i>
                                    </a>
                                    <a href="#" class="text-ifsp-blue hover:text-ifsp-green text-2xl">
                                        <i class="fab fa-instagram"></i>
                                    </a>
                                    <a href="#" class="text-ifsp-blue hover:text-ifsp-green text-2xl">
                                        <i class="fab fa-linkedin"></i>
                                    </a>
                                    <a href="#" class="text-ifsp-blue hover:text-ifsp-green text-2xl">
                                        <i class="fab fa-youtube"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-ifsp-blue text-white py-8">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="mb-6 md:mb-0">
                    <div class="flex items-center">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/9/9a/Logo_IFSP.svg" alt="IFSP Logo" class="h-10">
                        <span class="ml-3 text-xl font-bold">IFApoia</span>
                    </div>
                    <p class="mt-2 text-sm opacity-80">Instituto Federal de Educação, Ciência e Tecnologia de São Paulo - Câmpus Piracicaba</p>
                </div>

                <div class="flex flex-col items-center md:items-end">
                    <div class="flex space-x-6 mb-4">
                        <a href="#" class="hover:text-ifsp-green transition">Política de Privacidade</a>
                        <a href="#" class="hover:text-ifsp-green transition">Termos de Uso</a>
                        <a href="#" class="hover:text-ifsp-green transition">Acessibilidade</a>
                    </div>
                    <p class="text-sm opacity-80">© 2023 IFApoia Team. Todos os direitos reservados.</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Back to Top Button -->
    <button id="back-to-top" class="fixed bottom-6 right-6 bg-ifsp-green text-white p-3 rounded-full shadow-lg opacity-0 invisible transition-all">
        <i class="fas fa-arrow-up"></i>
    </button>

    <!-- JavaScript -->
    <script>
        // Mobile Menu Toggle
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const mobileMenu = document.getElementById('mobile-menu');

        mobileMenuButton.addEventListener('click', () => {
            mobileMenu.classList.toggle('active');
        });

        // Close mobile menu when clicking on a link
        const mobileMenuLinks = mobileMenu.querySelectorAll('a');
        mobileMenuLinks.forEach(link => {
            link.addEventListener('click', () => {
                mobileMenu.classList.remove('active');
            });
        });

        // Back to Top Button
        const backToTopButton = document.getElementById('back-to-top');

        window.addEventListener('scroll', () => {
            if (window.pageYOffset > 300) {
                backToTopButton.classList.remove('invisible', 'opacity-0');
                backToTopButton.classList.add('opacity-100');
            } else {
                backToTopButton.classList.add('invisible', 'opacity-0');
                backToTopButton.classList.remove('opacity-100');
            }
        });

        backToTopButton.addEventListener('click', () => {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });

        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();

                const targetId = this.getAttribute('href');
                if (targetId === '#') return;

                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    const headerHeight = document.querySelector('header').offsetHeight;
                    const targetPosition = targetElement.offsetTop - headerHeight;

                    window.scrollTo({
                        top: targetPosition,
                        behavior: 'smooth'
                    });
                }
            });
        });
    </script>
</body>

</html>