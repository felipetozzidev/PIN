<?php

// Incluir o autoload do Composer e a conexão com o banco de dados
require ('../vendor/autoload.php');
require_once('../config/conn.php');

// Importar classes necessárias do PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Inicializar variáveis
$login_error = "";
$signup_success = "";
$verification_needed = false;
$verification_error = "";

if (isset($_POST['login'])) {
  $email = mysqli_real_escape_string($conn, trim($_POST['email']));
  $senha = mysqli_real_escape_string($conn, trim($_POST['senha']));

  // Verifica se o email termina com os domínios permitidos (IF + siglas de estados com IF)
  if (!preg_match('/@(if(AC|AL|AP|AM|BA|CE|DF|ES|GO|MA|MT|MS|MG|PA|PB|PR|PE|PI|RJ|RN|RS|RO|RR|SC|SE|SP|TO)\.edu\.br|aluno\.if(AC|AL|AP|AM|BA|CE|DF|ES|GO|MA|MT|MS|MG|PA|PB|PR|PE|PI|RJ|RN|RS|RO|RR|SC|SE|SP|TO)\.edu\.br)$/i', $email)) {
    $login_error = "Apenas emails institucionais (IF) são permitidos";
  } else {
    // Verifica na tabela usuarios usando prepared statement
    $query = "SELECT id_usu, senha_usu, nome_usu, imgperfil_usu FROM usuarios WHERE email_usu = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verifica se o email existe no banco de dados
    if (mysqli_num_rows($result) > 0) {
      $row = mysqli_fetch_assoc($result);
      $senha_banco = $row['senha_usu']; // Obtém a senha criptografada do banco de dados

      // Verifica se a senha informada corresponde à senha armazenada no banco de dados
      if (password_verify($senha, $senha_banco)) {
        $_SESSION['usuario_id'] = $row['id_usu']; // Salva o ID do usuário na sessão
        $_SESSION['nome_usuario'] = $row['nome_usu']; // Salva o nome do usuário na sessão
        $_SESSION['imgperfil_usu'] = $row['imgperfil_usu']; // Salva o caminho da foto de perfil na sessão
        $login_success = true;
      } else {
        $login_error = "Sua senha está incorreta";
      }
    } else {
      $login_error = "Seu email não foi encontrado";
    }
    $stmt->close();
  }
}

if (isset($_POST['signup'])) {
  $nome = mysqli_real_escape_string($conn, trim($_POST['nome']));
  $email = mysqli_real_escape_string($conn, trim($_POST['email']));
  $senha = mysqli_real_escape_string($conn, trim($_POST['senha']));
  $conf_senha = mysqli_real_escape_string($conn, trim($_POST['conf-senha']));
  $estado = mysqli_real_escape_string($conn, trim($_POST['uf']));             // Usando o campo de estado do formulário
  $campus = mysqli_real_escape_string($conn, trim($_POST['campus']));         // Campo "campus" selecionado no formulário
  $sexo = mysqli_real_escape_string($conn, trim($_POST['sexo']));
  $orsex = mysqli_real_escape_string($conn, trim($_POST['orsex']));           // Nova informação: orientação sexual
  $senha_criptografada = password_hash($senha, PASSWORD_DEFAULT);
  $data_criacao = date("d-m-Y");                                              // Define a data de criação

  // Verifica se as senhas coincidem
  if ($senha !== $conf_senha) {
    $login_error = "As senhas não coincidem.";
  }
  // Verifica se o email termina com os domínios permitidos (IF + siglas de estados com IF)
  elseif (!preg_match('/@(if(AC|AL|AP|AM|BA|CE|DF|ES|GO|MA|MT|MS|MG|PA|PB|PR|PE|PI|RJ|RN|RS|RO|RR|SC|SE|SP|TO)\.edu\.br|aluno\.if(AC|AL|AP|AM|BA|CE|DF|ES|GO|MA|MT|MS|MG|PA|PB|PR|PE|PI|RJ|RN|RS|RO|RR|SC|SE|SP|TO)\.edu\.br)$/i', $email)) {
    $login_error = "Apenas emails institucionais (IF) são permitidos";
  } else {
    // Gera o código de verificação alfanumérico de 6 caracteres
    $verification_code = strtoupper(bin2hex(random_bytes(3)));

    // Armazena os dados do cadastro e o código na sessão
    $_SESSION['signup_data'] = [
      'nome' => $nome,
      'email' => $email,
      'senha' => $senha_criptografada,
      'estado' => $estado,
      'campus' => $campus,
      'sexo' => $sexo,
      'orsex' => $orsex,
      'datacriacao' => $data_criacao,
      'verification_code' => $verification_code
    ];

    // Envia o email de verificação usando PHPMailer
    $mail = new PHPMailer(true);
    $mail->CharSet = 'UTF-8'; // Define o charset para UTF-8

    try {
      // Configurações do servidor SMTP do Gmail
      $mail->SMTPDebug = 0;                                 // Habilita o debug para desenvolvimento (0 para desabilitar em produção)
      $mail->isSMTP();                                      // Define o uso de SMTP
      $mail->SMTPAuth = true;                               // Habilita a autenticação SMTP

      // Desabilita a verificação de certificado SSL (não recomendado para produção)
      /*$mail->SMTPOptions = array(
        'ssl' => array(
          'verify_peer' => false,
          'verify_peer_name' => false,
          'allow_self_signed' => true
        )
      );*/

      $mail->Host = 'smtp.gmail.com';                       // Servidor SMTP do Gmail
      $mail->Username = 'brenosilveiradomingues@gmail.com'; // Username do email
      $mail->Password = 'cqtddhaewkwhtqhr';                 // Senha de app do email usado para enviar
      $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;   // Habilita a encriptação TLS; `PHPMailer::ENCRYPTION_SMTPS` também pode ser usado
      $mail->Port = 587;                                    // Porta TCP para conectar com o servidor


      $to = $email; // Email do destinatário

      // Remetente e Destinatário
      $mail->setFrom('brenosilveiradomingues@gmail.com', 'IFApoia');    // Seu endereço de email e nome
      $mail->addAddress($to, $nome);                                    // Email e nome do destinatário

      // Conteúdo do Email
      $mail->isHTML(true); // Define o formato do email para HTML
      $mail->Subject = 'Código de verificação de Email - Rede IFApoia';
      $mail->Body    = '<div style="font-family: Arial, sans-serif; color: #333; margin: 0 auto; max-width: 600px;">
      <div style="background-color: #f8f9fa; padding: 20px 0; text-align: center;">
        <img src="../assets/img/Logotipo_antiga.png" alt="IFApoia" style="max-width: 150px; height: auto;">
      </div>
      <div style="padding: 20px;">
        <h2 style="color: #305F2C;">Bem-vindo(a) ao IFApoia!</h2>
        <p style="font-size: 16px;">Obrigado por se cadastrar no IFApoia. Para completar seu cadastro, por favor, utilize o seguinte código de verificação:</p>
        <div style="background-color: #A0BF9F; color: #fff; text-align: center; padding: 15px; border-radius: 5px; margin: 20px 0;">
          <strong style="font-size: 30px;">' . $verification_code . '</strong>
        </div>
        <p style="font-size: 16px;">Insira este código na página de cadastro para verificar seu email e começar a usar o IFApoia.</p>
        <p style="font-size: 16px;">Se você não se cadastrou no IFApoia, por favor, ignore este email.</p>
      </div>
      <div style="background-color: #f8f9fa; padding: 10px; text-align: center; font-size: 14px; color: #777;">
        <p>Atenciosamente,<br>A Equipe IFApoia</p>
      </div>
    </div>';

      // Texto alternativo para clientes de email que não suportam HTML
      $mail->AltBody = "Bem-vindo(a) ao IFApoia!\r\n\r\nObrigado por se cadastrar no IFApoia. Para completar seu cadastro, utilize o seguinte código de verificação:\r\n\r\n" . $verification_code . "\r\n\r\nInsira este código na página de cadastro para verificar seu email e começar a usar o IFApoia.\r\n\r\nSe você não se cadastrou no IFApoia, por favor, ignore este email.\r\n\r\nAtenciosamente,\r\nA Equipe IFApoia";

      $mail->send();
      $verification_needed = true;
    } catch (Exception $e) {
      $login_error = "Erro ao enviar o email de verificação: {$mail->ErrorInfo}";
      echo '<script>console.log("' . $login_error . '");</script>'; // Adicione esta linha para logar no console
    }
  }
}

// Verifica se o usuário está tentando verificar o email
if (isset($_POST['verify_email'])) {
  if (isset($_SESSION['signup_data'])) {
    $stored_code = $_SESSION['signup_data']['verification_code'];
    $entered_code = $_POST['verification_code'];
    $email = $_SESSION['signup_data']['email']; // Recupera o email da sessão

    // Verifica se o campo de código de verificação está vazio
    if (empty($entered_code)) {
      $verification_error = "Campo vazio, insira o código de verificação";
      $verification_needed = true; // Mantém o modal aberto se o código estiver vazio

      // Verifica se o código tem 6 caracteres alfanuméricos
    } elseif (!preg_match('/^[A-Z0-9]{6}$/', $entered_code)) {
      $verification_error = "Código de verificação inválido!";
      $verification_needed = true; // Mantém o modal aberto se o código for inválido

      // Verifica se o código inserido corresponde ao código armazenado na sessão
    } elseif ($stored_code == $entered_code) {
      $signup_data = $_SESSION['signup_data'];
      // Usar prepared statement para a inserção
      $query = "INSERT INTO usuarios (nome_usu, email_usu, senha_usu, estado_usu, campus_usu, sexo_usu, orsex_usu, datacriacao_usu)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

      $stmt = $conn->prepare($query);
      $stmt->bind_param(
        "ssssssss",
        $signup_data['nome'],
        $signup_data['email'],
        $signup_data['senha'],
        $signup_data['estado'],
        $signup_data['campus'],
        $signup_data['sexo'],
        $signup_data['orsex'],
        $signup_data['datacriacao']
      );

      // Executa a consulta de inserção
      if ($stmt->execute()) {
        $signup_success = "Cadastro realizado com sucesso. Faça login!";
        unset($_SESSION['signup_data']); // Limpa os dados da sessão após o cadastro
      } else {
        $verification_error = "Erro ao finalizar o cadastro: " . $stmt->error;
        $verification_needed = true; // Mantém o modal aberto em caso de erro
      }

      $stmt->close();
    } else {
      $verification_error = "Código de verificação incorreto!";
      $verification_needed = true; // Mantém o modal aberto em caso de erro
    }
  } else if (!isset($_SESSION['signup_data'])) {
    $verification_error = "Sessão de cadastro expirou.";
    $verification_needed = true; // Mantém o modal aberto em caso de erro
  }
}
?>

<!doctype html>
<html lang="pt-br">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>IFApoia | Login e Cadastro</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <!--<link rel="stylesheet" href="../assets/css/style.css">-->

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
      background-image: url('../src/assets/img/ifcampus.jpg');
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      background-attachment: fixed;
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: 'Inter', sans-serif;
      padding: 2rem;
    }

    .form-signin {
      display: flexbox;
      box-sizing: border-box;
      width: 100%;
      max-width: 420px;
      padding: 2.5rem;
      background: var(--white);
      border-radius: 16px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
      transition: all 0.3s ease;
    }

    .form-signin:hover {
      transform: translateY(3px);
      box-shadow: 0 12px 30px rgba(0, 0, 0, 0.5);
    }

    .back-button {
      position: fixed;
      top: 20px;
      left: 20px;
      z-index: 1000;
      background: rgba(255, 255, 255, 0.9);
      border-radius: 50%;
      width: 40px;
      height: 40px;
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
      transition: all 0.3s ease;
      cursor: pointer;
    }

    .back-button i {
      color: var(--primary);
      font-size: 1.25rem;
    }

    .back-button:hover {
      transform: scale(1.05);
      background: var(--white);
    }

    .logo-container {
      text-align: center;
      margin-bottom: 2rem;
    }

    .logo-container img {
      max-width: 150px;
      height: auto;
      margin-bottom: 1.5rem;
    }

    h1 {
      color: var(--text-color);
      font-weight: 600;
      margin-bottom: 1.5rem;
      font-size: 1.75rem;
    }

    .form-floating input,
    .form-control,
    select.form-control {
      position: relative;
      /* Relative necessário para implementar o botão .passwordView */
      border: 2px solid var(--border-color);
      border-radius: 8px;
      padding: 1rem 0.75rem;
      height: calc(3.5rem + 2px);
      font-size: 1rem;
      transition: all 0.3s ease;
      color: var(--text-color);
    }

    .form-floating input:focus,
    .form-control:focus,
    select.form-control:focus {
      border-color: var(--primary);
      box-shadow: 0 0 0 4px rgba(98, 28, 212, 0.1);
    }

    .form-floating label {
      padding: 1rem 0.75rem;
      color: var(--text-muted);
    }

    select.form-control {
      appearance: none;
      background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='%23718096' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
      background-repeat: no-repeat;
      background-position: right 0.75rem center;
      background-size: 1em;
      padding-right: 2.5rem;
    }

    select.form-control option:first-child {
      color: var(--text-muted);
    }

    select.form-control option {
      color: var(--text-color);
    }

    .passwordView {
      border: none;
      background-color: transparent;
      position: absolute;
      /* Absolute para definir o posicionamento do botão via css */
      top: 50%;
      right: 1rem;
      transform: translateY(-50%);
      width: 30px;
      height: 30px;
      padding: 0;
      display: flex;
      align-items: center;
      justify-content: center;
      z-index: 2;
      /* Z-index para garantir que o botão fique acima do input */

      &>i {
        font-size: 1.2rem;
        color: var(--text-muted);
      }
    }

    .btn-primary {
      background: linear-gradient(135deg, var(--primary), var(--secondaryButton));
      border: none;
      padding: 0.8rem 1.5rem;
      font-weight: 600;
      border-radius: 8px;
      width: 100%;
      margin-top: 1rem;
      transition: all 0.3s ease, background 0.5s ease;
      background-size: 200% 200%;
    }

    .btn-primary:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(98, 28, 212, 0.2);
      background-position: right center;
    }

    .signup-link {
      margin-top: 2rem;
      text-align: center;
      color: var(--text-muted);
    }

    .signup-link a {
      color: var(--primary);
      text-decoration: none;
      font-weight: 500;
      transition: color 0.3s ease;
    }

    .signup-link a:hover {
      color: var(--secondary);
    }

    .alert {
      border-radius: 8px;
      margin-bottom: 1.5rem;
      padding: 1rem;
    }

    /* Estilos do modal de cadastro */

    .modal-content {
      border-radius: 16px;
      border: none;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }

    .modal-header {
      background: linear-gradient(135deg, var(--primary), var(--secondaryButton));
      color: var(--white);
      border-radius: 16px 16px 0 0;
      padding: 1.5rem;
    }

    .modal-title {
      font-weight: 700;
    }

    .modal-body {
      padding: 3rem 3rem;
    }

    .modal-dialog {
      max-width: 800px;
      margin: 1.75rem auto;
    }

    .modal-body .form-label {
      text-align: left;
      display: block;
      margin-bottom: 0.5rem;
      color: var(--text-color);
      font-weight: 500;
    }

    /* Igual ao .passwordView, mas para o modal de cadastro */
    .passwordViewModal {
      border: none;
      background-color: transparent;
      position: absolute;
      top: 50%;
      right: 1.1rem;
      transform: translateY(-50%);
      width: 30px;
      height: 30px;
      padding: 0;
      display: flex;
      align-items: center;
      justify-content: center;
      z-index: 2;

      &>i {
        color: var(--text-muted);
        font-size: 1rem;
      }
    }

    .form-floating .labelsenha,
    .form-floating .labelconf-senha {
      padding: 1rem 1.5rem;
    }

    .btn-close {
      color: var(--white);
      opacity: 0.8;
      transition: opacity 0.3s ease;
      filter: brightness(0) invert(1);
    }

    .btn-close:hover {
      opacity: 1;
    }

    .btn-close:focus {
      box-shadow: none;
    }

    /* Estilos do modal de verificação */

    .modal#verificationModal .modal-content {
      border-radius: 10px;
      box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
    }

    .modal#verificationModal .modal-header {
      border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    }

    .modal#verificationModal .modal-title {
      font-weight: 600;
    }

    .modal#verificationModal .modal-body {
      padding: 20px;
    }

    .modal#verificationModal .form-label {
      font-weight: 500;
      color: var(--text-color);
    }

    .modal#verificationModal .form-control {
      border: 1px solid var(--border-color);
      border-radius: 5px;
      padding: 10px;
    }

    .modal#verificationModal .form-control:focus {
      border-color: var(--primary);
      box-shadow: 0 0 0 0.25rem rgba(var(--primary-rgb), 0.25);
    }

    .modal#verificationModal .btn-close-white {
      filter: invert(1) grayscale(100%) brightness(200%);
    }

    @media (max-width: 576px) {
      .form-signin {
        margin: auto;
        padding: 1.5rem;
      }

      .back-button {
        top: 10px;
        left: 10px;
      }

      .modal-dialog {
        margin: 0.5rem;
      }

      .modal-body {
        padding: 1rem;
      }
    }

    @media (max-height: 800px) {
      body {
        align-items: flex-start;
        padding: 1rem 0;
      }

      .form-signin {
        margin: 1rem auto;
      }
    }

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
</head>

<body class="text-center">
  <div class="back-button" onclick="window.location.href='index.php';">
    <i class="ri-arrow-left-line"></i>
  </div>

  <main class="form-signin">
    <div class="logo-container">
      <img src="../src/assets/img/Logotipo_antiga.png" width="60px" alt="Logo">
      <h1>Acesse sua conta</h1>
    </div>

    <form method="POST" action="login.php">
      <div class="form-floating mb-2">
        <input type="email" class="form-control" id="floatingInput" placeholder="Email" name="email" required>
        <label for="floatingInput">Email</label>
      </div>
      <div class="form-floating mb-2">
        <input type="password" class="form-control" id="floatingPassword" placeholder="Senha" name="senha" required>
        <button class="passwordView"><i class="ri-eye-line"></i></button>
        <label for="floatingPassword">Senha</label>
      </div>

      <button class="btn btn-primary" type="submit" name="login">
        Entrar <i class="fas fa-arrow-right ms-2"></i>
      </button>

      <p class="signup-link">
        Não tem uma conta? <a href="#signupModal" data-bs-toggle="modal">Cadastre-se</a>
      </p>
    </form>
  </main>

  <div class="modal fade" id="signupModal" tabindex="-1" aria-labelledby="signupModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="signupModalLabel">Nova conta</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form method="POST" action="login.php">
            <div class="form-floating mb-3">
              <input type="text" class="form-control" id="nome" name="nome" placeholder="Nome Completo" required>
              <label for="nome">Nome Completo</label>
            </div>

            <div class="row mb-2">
              <div class="col-md-6 col-sm-6 mb-2">
                <select class="form-control" id="sexo" name="sexo" required>
                  <option value="" disabled selected>Sexo Biológico</option>
                  <option value="M">Masculino</option>
                  <option value="F">Feminino</option>
                </select>
              </div>
              <div class="col-md-6 col-sm-6 mb-2">
                <select class="form-control" id="orsex" name="orsex" required>
                  <option value="" disabled selected>Orientação Sexual</option>
                  <option value="hetero">Heterossexual</option>
                  <option value="homo">Homossexual</option>
                  <option value="bissex">Bissexual</option>
                  <option value="assex">Assexual</option>
                  <option value="pansex">Pansexual</option>
                  <option value="queer">Queer</option>
                  <option value="outro">Outro</option>
                </select>
              </div>
            </div>

            <div class="form-floating mb-3">
              <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
              <label for="email">Email</label>
              <span class="d-flex text-muted">Apenas emails institucionais são permitidos (@if**.edu.br ou @aluno.if**.edu.br)</span>
            </div>
            <div class="row mb-2 form-floating">
              <div class="col-md-6 col-sm-6 mb-2 form-floating">
                <input type="password" class="form-control" id="senha" name="senha" placeholder="Senha" required>
                <button class="passwordViewModal"><i class="ri-eye-line"></i></button>
                <label class="labelsenha" for="senha">Senha</label>
              </div>
              <div class="col-md-6 col-sm-6 mb-2 form-floating">
                <input type="password" class="form-control" id="conf-senha" name="conf-senha" placeholder="Confirmar Senha" required>
                <button class="passwordViewModal"><i class="ri-eye-line"></i></button>
                <label class="labelconf-senha" for="conf-senha">Confirmar Senha</label>
              </div>
            </div>

            <div class="row mb-2">
              <div class="col-md-6 col-sm-6 mb-2">
                <select class="form-control" id="uf" name="uf" required>
                  <option value="" disabled selected>Selecione seu Estado</option>
                  <option value="AC">Acre</option>
                  <option value="AL">Alagoas</option>
                  <option value="AP">Amapá</option>
                  <option value="AM">Amazonas</option>
                  <option value="BA">Bahia</option>
                  <option value="CE">Ceará</option>
                  <option value="DF">Distrito Federal</option>
                  <option value="ES">Espírito Santo</option>
                  <option value="GO">Goiás</option>
                  <option value="MA">Maranhão</option>
                  <option value="MT">Mato Grosso</option>
                  <option value="MS">Mato Grosso do Sul</option>
                  <option value="MG">Minas Gerais</option>
                  <option value="PA">Pará</option>
                  <option value="PB">Paraíba</option>
                  <option value="PR">Paraná</option>
                  <option value="PE">Pernambuco</option>
                  <option value="PI">Piauí</option>
                  <option value="RJ">Rio de Janeiro</option>
                  <option value="RN">Rio Grande do Norte</option>
                  <option value="RS">Rio Grande do Sul</option>
                  <option value="RO">Rondônia</option>
                  <option value="RR">Roraima</option>
                  <option value="SC">Santa Catarina</option>
                  <option value="SP">São Paulo</option>
                  <option value="SE">Sergipe</option>
                  <option value="TO">Tocantins</option>
                </select>
              </div>
              <div class="col-md-6 col-sm-6 mb-2">
                <select class="form-control" id="campus" name="campus" required disabled>
                  <option value="" disabled selected>Selecione seu Campus</option>
                </select>
              </div>
            </div>

            <button type="submit" class="btn btn-primary" name="signup">
              Criar sua conta <i class="fas fa-user-plus ms-2"></i>
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade <?php if ($verification_needed) echo 'show'; ?>" id="verificationModal" tabindex="-1"
    aria-labelledby="verificationModalLabel" aria-hidden="<?php if ($verification_needed) echo 'false';
                                                          else echo 'true'; ?>"
    style="<?php if ($verification_needed) echo 'display: block;'; ?>">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title" id="verificationModalLabel">Verificação de Email</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <?php
          function obfuscateEmail($email)
          {
            $parts = explode('@', $email);
            $localPart = $parts[0];
            $domainPart = $parts[1] ?? ''; // Usar ?? para definir um valor padrão caso $parts[1] não exista
            $obfuscatedLocalPart = substr($localPart, 0, 2) . str_repeat('*', max(0, strlen($localPart) - 4)) . substr($localPart, -2);
            return '<strong class="">' . $obfuscatedLocalPart . '@' . $domainPart . '</strong>';
          }
          ?>
          <p class="mb-3">Um código de verificação de 6 caracteres foi enviado para o email
            <?php echo obfuscateEmail($email); ?>.
            Por favor, insira o código abaixo para completar o seu cadastro.</p>
          <form method="POST" action="login.php">
            <div class="mb-3">
              <label for="verification_code" class="form-label">Código de Verificação</label>
              <input type="text" class="form-control" id="verification_code" name="verification_code" required
                minlength="6" maxlength="6">
            </div>
            <button type="submit" class="btn btn-primary w-100" name="verify_email">Verificar Email</button>
          </form>
          <p class="mt-3 text-muted"><small>Se você não solicitou este cadastro, ignore esta mensagem.</small></p>
        </div>
      </div>
    </div>
  </div>

  <script>
    // Listener para tornar a senha visível ou invisível
    document.addEventListener('DOMContentLoaded', function() {
      const passwordViewButtons = document.querySelectorAll('.passwordView, .passwordViewModal'); // Seleciona os botões de ver senhas
      passwordViewButtons.forEach(button => {
        button.addEventListener('click', function(event) {
          event.preventDefault(); // Previne o comportamento padrão do botão
          // Encontra todos os inputs de senha e confirmação de senha dentro do modal
          const form = this.closest('form'); // Encontra o formulário mais próximo do botão clicado
          const senhaInput = form ? form.querySelector('input[name="senha"]') : null;
          const confSenhaInput = form ? form.querySelector('input[name="conf-senha"]') : null;

          // Se ambos os campos existem e estão preenchidos, alterna ambos juntos
          if (senhaInput && confSenhaInput && senhaInput.value && confSenhaInput.value) {
            const isSenha = senhaInput.type === 'password';
            senhaInput.type = isSenha ? 'text' : 'password';
            confSenhaInput.type = isSenha ? 'text' : 'password';
            // Atualiza todos os botões do formulário/modal
            const allButtons = form.querySelectorAll('.passwordView, .passwordViewModal');
            allButtons.forEach(btn => {
              btn.innerHTML = isSenha ?
                '<i class="ri-eye-close-line"></i>' :
                '<i class="ri-eye-line"></i>';
            });
          } else {
            // Alterna apenas o campo relacionado ao botão clicado
            const input = this.previousElementSibling || this.parentElement.previousElementSibling;
            if (input && input.type === 'password') {
              input.type = 'text';
              this.innerHTML = '<i class="ri-eye-close-line"></i>';
            } else if (input) {
              input.type = 'password';
              this.innerHTML = '<i class="ri-eye-line"></i>';
            }
          }
        });
      });
    });


    // Listener para atualizar os campi com base no estado selecionado
    document.addEventListener('DOMContentLoaded', function() {
      const estadoSelect = document.getElementById('uf');
      const campusSelect = document.getElementById('campus');

      // Array com os campi dos Institutos Federais por estado
      const campiPorEstado = {
        "AC": ["IFAC Campus Rio Branco", "IFAC Campus Cruzeiro do Sul", "IFAC Campus Sena Madureira", "IFAC Campus Tarauacá", "IFAC Campus Xapuri"],
        "AL": ["IFAL Campus Maceió", "IFAL Campus Arapiraca", "IFAL Campus Marechal Deodoro", "IFAL Campus Palmeira dos Índios", "IFAL Campus Satuba", "IFAL Campus Santana do Ipanema", "IFAL Campus Penedo", "IFAL Campus Murici", "IFAL Campus Batalha"],
        "AP": ["IFAP Campus Macapá", "IFAP Campus Santana", "IFAP Campus Laranjal do Jari", "IFAP Campus Oiapoque"],
        "AM": ["IFAM Campus Manaus Centro", "IFAM Campus Manaus Zona Leste", "IFAM Campus Manaus Industrial", "IFAM Campus Tabatinga", "IFAM Campus Coari", "IFAM Campus Maués", "IFAM Campus Parintins", "IFAM Campus Tefé", "IFAM Campus Borba", "IFAM Campus Eirunepé", "IFAM Campus Humaitá", "IFAM Campus Lábrea", "IFAM Campus Presidente Figueiredo", "IFAM Campus São Gabriel da Cachoeira"],
        "BA": ["IFBA Campus Salvador", "IFBA Campus Barreiras", "IFBA Campus Eunápolis", "IFBA Campus Feira de Santana", "IFBA Campus Ilhéus", "IFBA Campus Irecê", "IFBA Campus Itabuna", "IFBA Campus Jequié", "IFBA Campus Paulo Afonso", "IFBA Campus Santo Antônio de Jesus", "IFBA Campus Seabra", "IFBA Campus Valença", "IFBA Campus Vitória da Conquista", "IFBA Campus Alagoinhas", "IFBA Campus Jacobina", "IFBA Campus Simões Filho", "IFBA Campus Teixeira de Freitas", "IFBA Campus Guanambi", "IFBA Campus Brumado", "IFBA Campus Euclides da Cunha"],
        "CE": ["IFCE Campus Fortaleza", "IFCE Campus Acaraú", "IFCE Campus Aracati", "IFCE Campus Canindé", "IFCE Campus Cedro", "IFCE Campus Crateús", "IFCE Campus Crato", "IFCE Campus Iguatu", "IFCE Campus Itapipoca", "IFCE Campus Jaguaribe", "IFCE Campus Juazeiro do Norte", "IFCE Campus Limoeiro do Norte", "IFCE Campus Maracanaú", "IFCE Campus Morada Nova", "IFCE Campus Pacajus", "IFCE Campus Quixadá", "IFCE Campus Sobral", "IFCE Campus Tauá", "IFCE Campus Tianguá", "IFCE Campus Ubajara", "IFCE Campus Umirim", "IFCE Campus Camocim", "IFCE Campus Barbalha", "IFCE Campus Paracuru"],
        "DF": ["IFB Campus Brasília", "IFB Campus Ceilândia", "IFB Campus Estrutural", "IFB Campus Gama", "IFB Campus Itapoã", "IFB Campus Planaltina", "IFB Campus Riacho Fundo", "IFB Campus Samambaia", "IFB Campus São Sebastião", "IFB Campus Taguatinga"],
        "ES": ["IFES Campus Vitória", "IFES Campus Alegre", "IFES Campus Aracruz", "IFES Campus Cachoeiro de Itapemirim", "IFES Campus Cariacica", "IFES Campus Colatina", "IFES Campus Guarapari", "IFES Campus Ibatiba", "IFES Campus Linhares", "IFES Campus Montanha", "IFES Campus Nova Venécia", "IFES Campus Piúma", "IFES Campus Santa Teresa", "IFES Campus São Mateus", "IFES Campus Serra", "IFES Campus Venda Nova do Imigrante", "IFES Campus Vila Velha"],
        "GO": ["IFG Campus Goiânia", "IFG Campus Aparecida de Goiânia", "IFG Campus Catalão", "IFG Campus Formosa", "IFG Campus Inhumas", "IFG Campus Itumbiara", "IFG Campus Jataí", "IFG Campus Luziânia", "IFG Campus Rio Verde", "IFG Campus Senador Canedo", "IFG Campus Uruaçu", "IFG Campus Águas Lindas de Goiás", "IFG Campus Cidade de Goiás", "IFG Campus Goianésia", "IFG Campus Iporá"],
        "MA": ["IFMA Campus São Luís - Monte Castelo", "IFMA Campus Açailândia", "IFMA Campus Alcântara", "IFMA Campus Barra do Corda", "IFMA Campus Barão de Grajaú", "IFMA Campus Carolina", "IFMA Campus Caxias", "IFMA Campus Codó", "IFMA Campus Coelho Neto", "IFMA Campus Coroatá", "IFMA Campus Grajaú", "IFMA Campus Humberto de Campos", "IFMA Campus Imperatriz", "IFMA Campus Itapecuru-Mirim", "IFMA Campus João Lisboa", "IFMA Campus Pedreiras", "IFMA Campus Pinheiro", "IFMA Campus Presidente Dutra", "IFMA Campus Santa Inês", "IFMA Campus São João dos Patos", "IFMA Campus São Raimundo das Mangabeiras", "IFMA Campus Timon", "IFMA Campus Viana", "IFMA Campus Zé Doca"],
        "MT": ["IFMT Campus Cuiabá", "IFMT Campus Alta Floresta", "IFMT Campus Barra do Garças", "IFMT Campus Cáceres", "IFMT Campus Campo Novo do Parecis", "IFMT Campus Confresa", "IFMT Campus Juína", "IFMT Campus Lucas do Rio Verde", "IFMT Campus Pontes e Lacerda", "IFMT Campus Primavera do Leste", "IFMT Campus Rondonópolis", "IFMT Campus São Vicente", "IFMT Campus Sorriso", "IFMT Campus Tangará da Serra"],
        "MS": ["IFMS Campus Campo Grande", "IFMS Campus Aquidauana", "IFMS Campus Corumbá", "IFMS Campus Coxim", "IFMS Campus Dourados", "IFMS Campus Jardim", "IFMS Campus Naviraí", "IFMS Campus Nova Andradina", "IFMS Campus Ponta Porã", "IFMS Campus Três Lagoas"],
        "MG": ["IFMG Campus Bambuí", "IFMG Campus Betim", "IFMG Campus Congonhas", "IFMG Campus Conselheiro Lafaiete", "IFMG Campus Formiga", "IFMG Campus Governador Valadares", "IFMG Campus Itabirito", "IFMG Campus Itaúna", "IFMG Campus João Monlevade", "IFMG Campus Mariana", "IFMG Campus Ouro Branco", "IFMG Campus Ouro Preto", "IFMG Campus Piumhi", "IFMG Campus Ponte Nova", "IFMG Campus Reitoria", "IFMG Campus Sabará", "IFMG Campus Salinas", "IFMG Campus São João Evangelista", "IFMG Campus Teófilo Otoni", "IFMG Campus Três Corações"],
        "PA": ["IFPA Campus Belém", "IFPA Campus Abaetetuba", "IFPA Campus Altamira", "IFPA Campus Ananindeua", "IFPA Campus Bragança", "IFPA Campus Breves", "IFPA Campus Cametá", "IFPA Campus Canaã dos Carajás", "IFPA Campus Castanhal", "IFPA Campus Conceição do Araguaia", "IFPA Campus Itaituba", "IFPA Campus Marabá", "IFPA Campus Marituba", "IFPA Campus Óbidos", "IFPA Campus Paragominas", "IFPA Campus Parauapebas", "IFPA Campus Santarém", "IFPA Campus Tucuruí"],
        "PB": ["IFPB Campus João Pessoa", "IFPB Campus Cabedelo", "IFPB Campus Cajazeiras", "IFPB Campus Campina Grande", "IFPB Campus Guarabira", "IFPB Campus Itabaiana", "IFPB Campus Itaporanga", "IFPB Campus Jacareí", "IFPB Campus Mangabeira", "IFPB Campus Monteiro", "IFPB Campus Patos", "IFPB Campus Picuí", "IFPB Campus Princesa Isabel", "IFPB Campus Santa Rita", "IFPB Campus Soledade", "IFPB Campus Cabedelo - Centro", "IFPB Campus Areia", "IFPB Campus Pedras de Fogo", "IFPB Campus Esperança", "IFPB Campus Catolé do Rocha", "IFPB Campus Santa Luzia", "IFPB Campus Sousa - Unidade São Gonçalo", "IFPB Campus Sousa - Unidade Sede"],
        "PR": ["IFPR Campus Curitiba", "IFPR Campus Assis Chateaubriand", "IFPR Campus Astorga", "IFPR Campus Barracão", "IFPR Campus Campo Largo", "IFPR Campus Capanema", "IFPR Campus Cascavel", "IFPR Campus Colombo", "IFPR Campus Coronel Vivida", "IFPR Campus Foz do Iguaçu", "IFPR Campus Goioerê", "IFPR Campus Ibaiti", "IFPR Campus Irati", "IFPR Campus Ivaiporã", "IFPR Campus Jacarezinho", "IFPR Campus Jaguariaíva", "IFPR Campus Londrina", "IFPR Campus Irati", "IFPR Campus Ivaiporã", "IFPR Campus Jacarezinho", "IFPR Campus Jaguariaíva", "IFPR Campus Londrina", "IFPR Campus Marechal Cândido Rondon", "IFPR Campus Maringá", "IFPR Campus Palmas", "IFPR Campus Paranaguá", "IFPR Campus Paranavaí", "IFPR Campus Pinhais", "IFPR Campus Pitanga", "IFPR Campus Quedas do Iguaçu", "IFPR Campus Rio Negro", "IFPR Campus Telêmaco Borba", "IFPR Campus Toledo", "IFPR Campus Umuarama", "IFPR Campus União da Vitória", "IFPR Campus Arapongas", "IFPR Campus Ponta Grossa", "IFPR Campus Pitanga"],
        "PE": ["IFPE Campus Recife", "IFPE Campus Abreu e Lima", "IFPE Campus Afogados da Ingazeira", "IFPE Campus Barreiros", "IFPE Campus Belo Jardim", "IFPE Campus Cabo de Santo Agostinho", "IFPE Campus Caruaru", "IFPE Campus Garanhuns", "IFPE Campus Igarassu", "IFPE Campus Ipojuca", "IFPE Campus Jaboatão dos Guararapes", "IFPE Campus Limoeiro", "IFPE Campus Olinda", "IFPE Campus Palmares", "IFPE Campus Paulista", "IFPE Campus Pesqueira", "IFPE Campus Petrolina", "IFPE Campus Petrolina Zona Rural", "IFPE Campus Salgueiro", "IFPE Campus Santa Cruz do Capibaribe", "IFPE Campus Vitória de Santo Antão"],
        "PI": ["IFPI Campus Teresina Central", "IFPI Campus Angical", "IFPI Campus Campo Maior", "IFPI Campus Cocal", "IFPI Campus Corrente", "IFPI Campus Floriano", "IFPI Campus Oeiras", "IFPI Campus Parnaíba", "IFPI Campus Paulistana", "IFPI Campus Picos", "IFPI Campus Piripiri", "IFPI Campus São João do Piauí", "IFPI Campus São Raimundo Nonato", "IFPI Campus Uruçuí", "IFPI Campus Valença do Piauí", "IFPI Campus Dirceu Arcoverde", "IFPI Campus José de Freitas", "IFPI Campus Pio IX"],
        "RJ": ["IFRJ Campus Rio de Janeiro", "IFRJ Campus Arraial do Cabo", "IFRJ Campus Belford Roxo", "IFRJ Campus Duque de Caxias", "IFRJ Campus Engenheiro Paulo de Frontin", "IFRJ Campus Itaboraí", "IFRJ Campus Nilópolis", "IFRJ Campus Niterói", "IFRJ Campus Paracambi", "IFRJ Campus Pinheiral", "IFRJ Campus Realengo", "IFRJ Campus Resende", "IFRJ Campus Rio Bonito", "IFRJ Campus São Gonçalo", "IFRJ Campus Volta Redonda"],
        "RN": ["IFRN Campus Natal-Central", "IFRN Campus Apodi", "IFRN Campus Caicó", "IFRN Campus Canguaretama", "IFRN Campus Currais Novos", "IFRN Campus Ipanguaçu", "IFRN Campus João Câmara", "IFRN Campus Jucurutu", "IFRN Campus Macau", "IFRN Campus Mossoró", "IFRN Campus Nova Cruz", "IFRN Campus Parelhas", "IFRN Campus Pau dos Ferros", "IFRN Campus Santa Cruz", "IFRN Campus São Gonçalo do Amarante", "IFRN Campus Ceará-Mirim", "IFRN Campus Extremoz", "IFRN Campus Guamaré", "IFRN Campus Lajes", "IFRN Campus Parnamirim", "IFRN Campus Natal - Cidade Alta", "IFRN Campus Natal - Zona Leste", "IFRN Campus Natal - Zona Norte", "IFRN Campus São Paulo do Potengi"],
        "RS": ["IFRS Campus Bento Gonçalves", "IFRS Campus Canoas", "IFRS Campus Caxias do Sul", "IFRS Campus Erechim", "IFRS Campus Farroupilha", "IFRS Campus Feliz", "IFRS Campus Ibirubá", "IFRS Campus Osório", "IFRS Campus Passo Fundo", "IFRS Campus Porto Alegre", "IFRS Campus Restinga", "IFRS Campus Rio Grande", "IFRS Campus Rolante", "IFRS Campus Sertão", "IFRS Campus Vacaria", "IFRS Campus Veranópolis", "IF Farroupilha Campus Alegrete", "IF Farroupilha Campus Frederico Westphalen", "IF Farroupilha Campus Jaguari", "IF Farroupilha Campus Júlio de Castilhos", "IF Farroupilha Campus Panambi", "IF Farroupilha Campus Santa Rosa", "IF Farroupilha Campus Santo Ângelo", "IF Farroupilha Campus Santo Augusto", "IF Farroupilha Unidade São Borja"],
        "RO": ["IFRO Campus Porto Velho", "IFRO Campus Ariquemes", "IFRO Campus Cacoal", "IFRO Campus Colorado do Oeste", "IFRO Campus Guajará-Mirim", "IFRO Campus Ji-Paraná", "IFRO Campus Jaru", "IFRO Campus Presidente Médici", "IFRO Campus Vilhena", "IFRO Campus Porto Velho Zona Norte", "IFRO Campus Porto Velho Calama", "IFRO Campus Avançado São Miguel do Guaporé"],
        "RR": ["IFRR Campus Boa Vista", "IFRR Campus Novo Paraíso"],
        "SC": ["IFSC Campus Florianópolis", "IFSC Campus Araranguá", "IFSC Campus Canoinhas", "IFSC Campus Chapecó", "IFSC Campus Criciúma", "IFSC Campus Gaspar", "IFSC Campus Itajaí", "IFSC Campus Jaraguá do Sul", "IFSC Campus Joinville", "IFSC Campus Lages", "IFSC Campus Mafra", "IFSC Campus Palhoça", "IFSC Campus São Carlos", "IFSC Campus São Miguel do Oeste", "IFSC Campus Tubarão", "IFSC Campus Urupema", "IFSC Campus Xanxerê"],
        "SE": ["IFS Campus Aracaju", "IFS Campus Lagarto", "IFS Campus Itabaiana", "IFS Campus Glória", "IFS Campus Propriá", "IFS Campus São Cristóvão"],
        "SP": ["IFSP Campus Araraquara", "IFSP Campus Avaré", "IFSP Campus Barretos", "IFSP Campus Bauru", "IFSP Campus Birigui", "IFSP Campus Boituva", "IFSP Campus Bragança Paulista", "IFSP Campus Campinas", "IFSP Campus Campos do Jordão", "IFSP Campus Capivari", "IFSP Campus Caraguatatuba", "IFSP Campus Catanduva", "IFSP Campus Cubatão", "IFSP Campus Guarulhos", "IFSP Campus Hortolândia", "IFSP Campus Ilha Solteira", "IFSP Campus Itapetininga", "IFSP Campus Itaquaquecetuba", "IFSP Campus Jacareí", "IFSP Campus Jundiaí", "IFSP Campus Matão", "IFSP Campus Miracatu", "IFSP Campus Piracicaba", "IFSP Campus Pirituba", "IFSP Campus Presidente Epitácio", "IFSP Campus Presidente Prudente", "IFSP Campus Registro", "IFSP Campus Rio Claro", "IFSP Campus Salto", "IFSP Campus São Carlos", "IFSP Campus São João da Boa Vista", "IFSP Campus São José do Rio Preto", "IFSP Campus São José dos Campos", "IFSP Campus São Miguel Paulista", "IFSP Campus São Paulo", "IFSP Campus São Roque", "IFSP Campus Sertãozinho", "IFSP Campus Sorocaba", "IFSP Campus Suzano", "IFSP Campus Tupã", "IFSP Campus Votuporanga"],
        "TO": ["IFTO Campus Palmas", "IFTO Campus Araguaína", "IFTO Campus Araguatins", "IFTO Campus Colinas do Tocantins", "IFTO Campus Dianópolis", "IFTO Campus Gurupi", "IFTO Campus Itaporã do Tocantins", "IFTO Campus Miracema do Tocantins", "IFTO Campus Paraíso do Tocantins", "IFTO Campus Porto Nacional", "IFTO Campus Tocantinópolis"],
      };

      // Preenche o select de campus com os campi do primeiro estado
      estadoSelect.addEventListener('change', function() {
        const selectedEstado = this.value; // Obtém o estado selecionado
        // Limpa o select de campus
        campusSelect.innerHTML = '<option value="" disabled selected>Selecione seu Campus</option>';
        campusSelect.disabled = true;

        // Verifica se o estado selecionado tem campi definidos
        if (campiPorEstado[selectedEstado]) {
          campiPorEstado[selectedEstado].forEach(function(campus) {
            const option = document.createElement('option'); // Cria um novo elemento option
            option.value = campus; // Define o valor do option como o nome do campus
            // Divide o nome do campus para exibir apenas a parte após "Campus "
            const parts = campus.split('Campus ');
            option.textContent = parts[1] ? parts[1] : campus;
            campusSelect.appendChild(option);
          });
          campusSelect.disabled = false;
        }
      });
    });
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <script>
    // Verifica se o DOM está completamente carregado
    document.addEventListener('DOMContentLoaded', function() {
      const verificationModal = document.getElementById('verificationModal'); // Obtém o modal de verificação
      const verificationForm = verificationModal.querySelector('form'); // Obtém o formulário dentro do modal
      const verificationCodeInput = document.getElementById('verification_code'); // Obtém o input do código de verificação
      const verificationModalCloseButton = verificationModal.querySelector('.btn-close'); // Obtém o botão de fechar do modal

      // Caso o código esteja incorreto (após o envio e retorno do PHP)
      <?php if ($verification_error): ?>
        Swal.fire({
          position: 'top',
          icon: 'error',
          title: '<?= $verification_error ?>',
          toast: true,
          showConfirmButton: false,
          timer: 3000
        });
      <?php endif; ?>

      // Adiciona um listener para o evento de submit do formulário de verificação
      if (verificationForm) {
        verificationForm.addEventListener('submit', function(event) { // Impede o envio se o código estiver vazio/inválido
          if (verificationCodeInput.value.trim() === '') { // Verifica se o campo está vazio
            event.preventDefault(); // Impede o envio do formulário
            Swal.fire({
              position: 'top',
              icon: 'error',
              title: 'Campo vazio!',
              text: 'Nada inserido. Por favor, insira o código de verificação',
              toast: true,
              showConfirmButton: false,
              timer: 3000,
              customClass: {
                icon: 'swal2.icon',
                title: 'swal2.title'
              }
            });
            return;
          }

          // Regex para validar o código de verificação
          const codeRegex = /^[A-Z0-9]{6}$/;
          // Verifica se o código de verificação corresponde ao padrão esperado
          // (6 caracteres alfanuméricos em caixa alta)
          if (!codeRegex.test(verificationCodeInput.value)) {
            event.preventDefault();
            Swal.fire({
              position: 'top',
              icon: 'error',
              title: 'Código Inválido!',
              text: 'O código de verificação deve conter 6 caracteres alfanuméricos',
              toast: true,
              showConfirmButton: false,
              timer: 3000,
              customClass: {
                icon: 'swal2.icon',
                title: 'swal2.title'
              }
            });
            return;
          }
        });
      }

      // Forçar o uso de letras em caixa alta no input e permitir apenas números e letras
      if (verificationCodeInput) {
        verificationCodeInput.addEventListener('input', function() {
          this.value = this.value.toUpperCase().replace(/[^A-Z0-9]/g, ''); // Converte para caixa alta e remove caracteres não alfanuméricos
        });
      }

      // Listener para o botão de fechar do modal de verificação
      if (verificationModalCloseButton) {
        verificationModalCloseButton.addEventListener('click', function() {
          Swal.fire({
            icon: 'info',
            iconColor: '#203f1d',
            title: 'Verificação cancelada',
            text: 'Você pode solicitar um novo código se necessário',
            timer: 2500,
            timerProgressBar: true,
            showConfirmButton: false
          });
        });
      }

      // Verifica se há uma mensagem de erro de login
      <?php if ($login_error): ?>
        Swal.fire({
          title: 'Houve um erro!',
          text: '<?= $login_error ?>',
          icon: 'error',
          confirmButtonText: 'OK'
        });
      <?php endif; ?>

      // Verifica se o login foi bem-sucedido
      <?php if (isset($login_success) && $login_success): ?>
        Swal.fire({
          title: 'Login concluído com sucesso!',
          text: 'Redirecionando...',
          icon: 'success',
          iconColor: '#203f1d',
          timer: 3000, // Timer de 3 segundos
          showConfirmButton: false
        }).then(() => {
          window.location.href = '../../public/index.php'; // Redireciona para a página inicial
        });
      <?php endif; ?>

      // Verifica se o cadastro foi bem-sucedido
      <?php if ($signup_success): ?>
        Swal.fire({
          title: 'Cadastro completo!',
          text: '<?= $signup_success ?>',
          icon: 'success',
          iconColor: '#203f1d',
          timer: 2000, // Timer de 2 segundos
          timerProgressBar: true,
          showConfirmButton: false
        });
      <?php endif; ?>

      <?php if ($verification_needed): ?>
        const signupModal = new bootstrap.Modal(document.getElementById('signupModal'));
        signupModal.hide();
        const verificationModalInstance = new bootstrap.Modal(document.getElementById('verificationModal'), {
          backdrop: 'static', // Impede que o modal seja fechado ao clicar fora
          keyboard: false // Impede que o modal seja fechado ao pressionar a tecla Esc
        });
        verificationModalInstance.show();
      <?php endif; ?>
    });
  </script>

      <style>
    /* Estilos existentes */

    .swal2.icon {
      font-size: 3em !important;
      /* Ajuste o tamanho do ícone conforme necessário */
    }

    .swal2.title {
      font-size: 3em !important;
      /* Ajuste o tamanho do título conforme necessário */
    }
  </style>
</body>

</html>