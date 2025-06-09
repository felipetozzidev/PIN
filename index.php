<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Rede IFApoia</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="apple-touch-icon" sizes="180x180" href="/src/assets/icons/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/src/assets/icons/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/src/assets/icons/favicon/favicon-16x16.png">
    <link rel="shortcut icon" type="image/x-icon" sizes="192x192" href="/src/assets/icons/favicon/favicon.ico">
    <link rel="manifest" href="/src/assets/icons/favicon/site.webmanifest">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
    <style>
        :root {
            --background: #EEFFEE;
            --backgroundContrast: #e7f7e78b;
            --primary: #305F2C;
            --secondaryButton: #203f1d;
            --secondary: #A0BF9F;
            --accent: #96c584;
            --border-color: #d6dce4;
            --text-color: #2d3748;
            --text-muted: #718096;
            --white: #ffffff;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html,
        body {
            margin: 0;
            padding: 0;
            min-height: 100vh;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--background);
            color: var(--text-color);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }

        .background-decor {
            opacity: 0.15;
        }

        .container {
            display: flex;
            flex-direction: column;
            background-color: var(--backgroundContrast);
            border: 2px solid var(--border-color);
            border-radius: 24px;
            padding: 50px 40px;
            box-sizing: border-box;
            max-width: 600px;
            width: 100%;
            text-align: center;
            box-shadow: 0 10px 25px rgba(48, 95, 44, 0.15);
        }

        /* From Uiverse.io by SouravBandyopadhyay */
        .hourglassBackground {
            position: relative;
            background-color: var(--accent);
            height: 130px;
            width: 130px;
            border-radius: 50%;
            margin: 30px auto;
        }

        .hourglassContainer {
            position: absolute;
            top: 30px;
            left: 40px;
            width: 50px;
            height: 70px;
            -webkit-animation: hourglassRotate 2s ease-in 0s infinite;
            animation: hourglassRotate 2s ease-in 0s infinite;
            transform-style: preserve-3d;
            perspective: 1000px;
        }

        .hourglassContainer div,
        .hourglassContainer div:before,
        .hourglassContainer div:after {
            transform-style: preserve-3d;
        }

        @-webkit-keyframes hourglassRotate {
            0% {
                transform: rotateX(0deg);
            }

            50% {
                transform: rotateX(180deg);
            }

            100% {
                transform: rotateX(180deg);
            }
        }

        @keyframes hourglassRotate {
            0% {
                transform: rotateX(0deg);
            }

            50% {
                transform: rotateX(180deg);
            }

            100% {
                transform: rotateX(180deg);
            }
        }

        .hourglassCapTop {
            top: 0;
        }

        .hourglassCapTop:before {
            top: -25px;
        }

        .hourglassCapTop:after {
            top: -20px;
        }

        .hourglassCapBottom {
            bottom: 0;
        }

        .hourglassCapBottom:before {
            bottom: -25px;
        }

        .hourglassCapBottom:after {
            bottom: -20px;
        }

        .hourglassGlassTop {
            transform: rotateX(90deg);
            position: absolute;
            top: -16px;
            left: 3px;
            border-radius: 50%;
            width: 44px;
            height: 44px;
            background-color: var(--backgroundContrast);
        }

        .hourglassGlass {
            perspective: 100px;
            position: absolute;
            top: 32px;
            left: 20px;
            width: 10px;
            height: 6px;
            background-color: var(--background);
            opacity: 0.5;
        }

        .hourglassGlass:before,
        .hourglassGlass:after {
            content: '';
            display: block;
            position: absolute;
            background-color: var(--backgroundContrast);
            left: -17px;
            width: 44px;
            height: 28px;
        }

        .hourglassGlass:before {
            top: -27px;
            border-radius: 0 0 25px 25px;
        }

        .hourglassGlass:after {
            bottom: -27px;
            border-radius: 25px 25px 0 0;
        }

        .hourglassCurves:before,
        .hourglassCurves:after {
            content: '';
            display: block;
            position: absolute;
            top: 32px;
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background-color: var(--secondaryButton);
            animation: hideCurves 2s ease-in 0s infinite;
        }

        .hourglassCurves:before {
            left: 15px;
        }

        .hourglassCurves:after {
            left: 29px;
        }

        @-webkit-keyframes hideCurves {
            0% {
                opacity: 1;
            }

            25% {
                opacity: 0;
            }

            30% {
                opacity: 0;
            }

            40% {
                opacity: 1;
            }

            100% {
                opacity: 1;
            }
        }

        @keyframes hideCurves {
            0% {
                opacity: 1;
            }

            25% {
                opacity: 0;
            }

            30% {
                opacity: 0;
            }

            40% {
                opacity: 1;
            }

            100% {
                opacity: 1;
            }
        }

        .hourglassSandStream:before {
            content: '';
            display: block;
            position: absolute;
            left: 24px;
            width: 3px;
            background-color: var(--secondaryButton);
            -webkit-animation: sandStream1 2s ease-in 0s infinite;
            animation: sandStream1 2s ease-in 0s infinite;
        }

        .hourglassSandStream:after {
            content: '';
            display: block;
            position: absolute;
            top: 36px;
            left: 19px;
            border-left: 6px solid transparent;
            border-right: 6px solid transparent;
            border-bottom: 6px solid var(--secondaryButton);
            animation: sandStream2 2s ease-in 0s infinite;
        }

        @-webkit-keyframes sandStream1 {
            0% {
                height: 0;
                top: 35px;
            }

            50% {
                height: 0;
                top: 45px;
            }

            60% {
                height: 35px;
                top: 8px;
            }

            85% {
                height: 35px;
                top: 8px;
            }

            100% {
                height: 0;
                top: 8px;
            }
        }

        @keyframes sandStream1 {
            0% {
                height: 0;
                top: 35px;
            }

            50% {
                height: 0;
                top: 45px;
            }

            60% {
                height: 35px;
                top: 8px;
            }

            85% {
                height: 35px;
                top: 8px;
            }

            100% {
                height: 0;
                top: 8px;
            }
        }

        @-webkit-keyframes sandStream2 {
            0% {
                opacity: 0;
            }

            50% {
                opacity: 0;
            }

            51% {
                opacity: 1;
            }

            90% {
                opacity: 1;
            }

            91% {
                opacity: 0;
            }

            100% {
                opacity: 0;
            }
        }

        @keyframes sandStream2 {
            0% {
                opacity: 0;
            }

            50% {
                opacity: 0;
            }

            51% {
                opacity: 1;
            }

            90% {
                opacity: 1;
            }

            91% {
                opacity: 0;
            }

            100% {
                opacity: 0;
            }
        }

        .hourglassSand:before,
        .hourglassSand:after {
            content: '';
            display: block;
            position: absolute;
            left: 6px;
            background-color: var(--secondaryButton);
            perspective: 500px;
        }

        .hourglassSand:before {
            top: 8px;
            width: 39px;
            border-radius: 3px 3px 30px 30px;
            animation: sandFillup 2s ease-in 0s infinite;
        }

        .hourglassSand:after {
            border-radius: 30px 30px 3px 3px;
            animation: sandDeplete 2s ease-in 0s infinite;
        }

        @-webkit-keyframes sandFillup {
            0% {
                opacity: 0;
                height: 0;
            }

            60% {
                opacity: 1;
                height: 0;
            }

            100% {
                opacity: 1;
                height: 17px;
            }
        }

        @keyframes sandFillup {
            0% {
                opacity: 0;
                height: 0;
            }

            60% {
                opacity: 1;
                height: 0;
            }

            100% {
                opacity: 1;
                height: 17px;
            }
        }

        @-webkit-keyframes sandDeplete {
            0% {
                opacity: 0;
                top: 45px;
                height: 17px;
                width: 38px;
                left: 6px;
            }

            1% {
                opacity: 1;
                top: 45px;
                height: 17px;
                width: 38px;
                left: 6px;
            }

            24% {
                opacity: 1;
                top: 45px;
                height: 17px;
                width: 38px;
                left: 6px;
            }

            25% {
                opacity: 1;
                top: 41px;
                height: 17px;
                width: 38px;
                left: 6px;
            }

            50% {
                opacity: 1;
                top: 41px;
                height: 17px;
                width: 38px;
                left: 6px;
            }

            90% {
                opacity: 1;
                top: 41px;
                height: 0;
                width: 10px;
                left: 20px;
            }
        }

        @keyframes sandDeplete {
            0% {
                opacity: 0;
                top: 45px;
                height: 17px;
                width: 38px;
                left: 6px;
            }

            1% {
                opacity: 1;
                top: 45px;
                height: 17px;
                width: 38px;
                left: 6px;
            }

            24% {
                opacity: 1;
                top: 45px;
                height: 17px;
                width: 38px;
                left: 6px;
            }

            25% {
                opacity: 1;
                top: 41px;
                height: 17px;
                width: 38px;
                left: 6px;
            }

            50% {
                opacity: 1;
                top: 41px;
                height: 17px;
                width: 38px;
                left: 6px;
            }

            90% {
                opacity: 1;
                top: 41px;
                height: 0;
                width: 10px;
                left: 20px;
            }
        }

        h1 {
            font-size: 2.5rem;
            color: var(--primary);
            margin-bottom: 15px;
            font-weight: 700;
        }

        p {
            font-size: 1.2rem !important;
            color: var(--text-muted);
            margin-bottom: 30px;
            line-height: 1.6;
        }

        span {
            font-size: 12px;
            color: var(--text-muted);
        }

        @media (max-width: 600px) {
            .container {
                padding: 40px 30px;
            }

            h1 {
                font-size: 1.5rem;
            }

            p {
                font-size: 0.9rem !important;
            }

            span {
                font-size: 8px;
            }
        }

        @media (max-width: 350px) {
            h1 {
                font-size: 1.2rem;
            }

            span {
                font-size: 5px;
            }
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="hourglassBackground">
            <div class="hourglassContainer">
                <div class="hourglassCurves"></div>
                <div class="hourglassCapTop"></div>
                <div class="hourglassGlassTop"></div>
                <div class="hourglassSand"></div>
                <div class="hourglassSandStream"></div>
                <div class="hourglassCapBottom"></div>
                <div class="hourglassGlass"></div>
            </div>
        </div>
        <h1>SITE EM DESENVOLVIMENTO</h1>
        <p class="lead">Estamos cuidando dos últimos detalhes para oferecer uma experiência incrível. Muito em breve, esta página estará disponível com tudo o que você merece.</p>
        <span class="">Para acompanhar o andamento do nosso projeto, acesse <a href="https://github.com/felipetozzidev/PIN">aqui</a> nossa documentação </span>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>