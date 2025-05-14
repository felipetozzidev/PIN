<?php
require_once('../../config/conn.php');
session_start(); // Inicia a sessão no início do script

$login_error = "";
$signup_success = "";

if (isset($_POST['login'])) {
  $email = mysqli_real_escape_string($dbc, trim($_POST['email']));
  $senha = mysqli_real_escape_string($dbc, trim($_POST['senha']));

  // Verifica na tabela cadcli
  $query = "SELECT id, senha FROM cadcli WHERE email = '$email'";
  $result = mysqli_query($dbc, $query);

  if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $senha_banco = $row['senha'];

    if (password_verify($senha, $senha_banco)) {
      $_SESSION['usuario_id'] = $row['id'];
      $_SESSION['tipo_usuario'] = 'cliente'; // Define o tipo de usuário
      $login_success = true;
    } else {
      $login_error = "Senha Incorreta";
    }
  } else {
    // Verifica na tabela cadesc (escritores)
    $query = "SELECT id, senha FROM cadesc WHERE email = '$email'";
    $result = mysqli_query($dbc, $query);

    if (mysqli_num_rows($result) > 0) {
      $row = mysqli_fetch_assoc($result);
      $senha_banco = $row['senha'];

      if (password_verify($senha, $senha_banco)) {
        $_SESSION['usuario_id'] = $row['id'];
        $_SESSION['tipo_usuario'] = 'escritor'; // Define o tipo de usuário
        $login_success = true;
      } else {
        $login_error = "Senha Incorreta";
      }
    } else {
      $login_error = "Email não encontrado";
    }
  }
}

if (isset($_POST['signup'])) {
  $nome = mysqli_real_escape_string($dbc, trim($_POST['nome']));
  $sobrenome = mysqli_real_escape_string($dbc, trim($_POST['sobrenome']));
  $cpf = mysqli_real_escape_string($dbc, trim($_POST['cpf']));
  $rg = mysqli_real_escape_string($dbc, trim($_POST['rg']));
  $sexo = mysqli_real_escape_string($dbc, trim($_POST['sexo']));
  $email = mysqli_real_escape_string($dbc, trim($_POST['email']));
  $senha = mysqli_real_escape_string($dbc, trim($_POST['senha']));
  $end_nome = mysqli_real_escape_string($dbc, trim($_POST['end_nome']));
  $end_num = mysqli_real_escape_string($dbc, trim($_POST['end_num']));
  $end_comp = mysqli_real_escape_string($dbc, trim($_POST['end_comp']));
  $cep = mysqli_real_escape_string($dbc, trim($_POST['cep']));
  $bairro = mysqli_real_escape_string($dbc, trim($_POST['bairro']));
  $cidade = mysqli_real_escape_string($dbc, trim($_POST['cidade']));
  $uf = mysqli_real_escape_string($dbc, trim($_POST['uf']));
  $escritor = isset($_POST['escritor']) ? 1 : 0; // Verifica se o checkbox foi marcado
  $senha_criptografada = password_hash($senha, PASSWORD_DEFAULT);

  // Define a tabela correta com base no checkbox
  $tabela = $escritor ? 'cadesc' : 'cadcli';

  $query = "INSERT INTO $tabela (nome, sobrenome, cpf, rg, sexo, email, senha, end_nome, end_num, end_comp, cep, bairro, cidade, uf)
              VALUES ('$nome', '$sobrenome', '$cpf', '$rg', '$sexo', '$email', '$senha_criptografada', '$end_nome', '$end_num', '$end_comp',
              '$cep', '$bairro', '$cidade', '$uf')";

  if (mysqli_query($dbc, $query)) {
    $signup_success = "Cadastro realizado com sucesso. Faça login!";
  } else {
    $login_error = "Erro ao cadastrar: " . mysqli_error($dbc);
  }
}
?>

<!doctype html>
<html lang="pt-br">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login e Cadastro</title>

  <link rel="stylesheet" href="../src/assets/css/style.css">
    
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
  <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">

  <!-- SweetAlert2 CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">   

  <style>
    :root {
      --primary: #305F2C;
      --accent: #96C584;
      --secondary: #A0BF9F;
      --background: #EEFFEE;
      --border-color: #d6dce4;
      --text-color: #2d3748;
      --text-muted: #718096;
      --white: #ffffff;
    }

    /* Estilos personalizados para o SweetAlert2 */
    .swal2-popup {
      background: var(--background);
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
      background: linear-gradient(135deg, var(--primary), var(--secondary)) !important;
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

    html,
    body {
      height: 100%;
      margin: 0;
      padding: 0;
    }

    body {
      background-image: url('../assets/img/ifcampus.jpg');
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      background-attachment: fixed;
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: 'Inter', sans-serif;
      min-height: 100vh;
    }

    .form-signin {
      width: 100%;
      max-width: 420px;
      padding: 2.5rem;
      background: var(--white);
      border-radius: 16px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
      margin: 2rem;
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

    .form-floating {
      margin-bottom: 1rem;
    }

    .form-floating input,
    .form-control,
    select.form-control {
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

    .form-check-label {
      vertical-align: middle;
      margin-left: 5px;
      line-height: 1.5;
    }

    .form-check {
      display: flex;
      align-items: center;
      justify-content: flex-start;
    }

    .form-check-input {
      border: 2px solid var(--primary);
      vertical-align: middle;
      margin-top: 0;
      margin-bottom: 0;
    }

    .btn-primary {
      background: linear-gradient(135deg, var(--primary), var(--secondary));
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

    /* Modal Styles */
    .modal-content {
      border-radius: 16px;
      border: none;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }

    .modal-header {
      background: linear-gradient(135deg, var(--primary), var(--secondary));
      color: var(--white);
      border-radius: 16px 16px 0 0;
      padding: 1.5rem;
    }

    .modal-title {
      font-weight: 600;
    }

    .modal-body {
      padding: 2rem;
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

    @media (max-width: 576px) {
      .form-signin {
        margin: 1rem;
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
  </style>
</head>

<body class="text-center">
  <div class="back-button" onclick="window.history.back()">
    <i class="fas fa-arrow-left"></i>
  </div>

  <main class="form-signin animate__animated animate__fadeIn">
    <div class="logo-container">
      <img src="" alt="Logo">
      <h1>Faça login já!</h1>
    </div>

    <form method="POST" action="login_cadastro.php">
      <div class="form-floating">
        <input type="email" class="form-control" id="floatingInput" placeholder="name@example.com" name="email" required>
        <label for="floatingInput">Email</label>
      </div>
      <div class="form-floating">
        <input type="password" class="form-control" id="floatingPassword" placeholder="Password" name="senha" required>
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

  <!-- Modal de Cadastro -->
  <div class="modal fade" id="signupModal" tabindex="-1" aria-labelledby="signupModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="signupModalLabel">Cadastro | Nova conta</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form method="POST" action="login_cadastro.php">
            <!-- Primeira linha -->
            <div class="row mb-3">
              <div class="col-md-6">
                <label for="nome" class="form-label">Nome</label>
                <input type="text" class="form-control" id="nome" name="nome" required>
              </div>
              <div class="col-md-6">
                <label for="sobrenome" class="form-label">Sobrenome</label>
                <input type="text" class="form-control" id="sobrenome" name="sobrenome" required>
              </div>
            </div>

            <!-- Segunda linha -->
            <div class="row mb-3">
              <div class="col-md-4">
                <label for="sexo" class="form-label">Sexo</label>
                <select class="form-control" id="sexo" name="sexo" required>
                  <option value="" disabled selected>Clique para selecionar</option>
                  <option value="M">Masculino</option>
                  <option value="F">Feminino</option>
                </select>
              </div>
            </div>

            <!-- Terceira linha -->
            <div class="row mb-3">
              <div class="col-md-6">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
              </div>
              <div class="col-md-6">
                <label for="senha" class="form-label">Senha</label>
                <input type="password" class="form-control" id="senha" name="senha" required>
              </div>
            </div>

            <!-- Quarta linha -->
            <div class="row mb-3">
              <div class="col-md-6">
                <label for="end_nome" class="form-label">Endereço</label>
                <input type="text" class="form-control" id="end_nome" name="end_nome" required>
              </div>
              <div class="col-md-3">
                <label for="end_num" class="form-label">Número</label>
                <input type="text" class="form-control" id="end_num" name="end_num" required>
              </div>
              <div class="col-md-3">
                <label for="end_comp" class="form-label">Complemento</label>
                <input type="text" class="form-control" id="end_comp" name="end_comp">
              </div>
            </div>

            <!-- Quinta linha -->
            <div class="row mb-3">
              <div class="col-md-3">
                <label for="cidade" class="form-label">Cidade</label>
                <input type="text" class="form-control" id="cidade" name="cidade" required>
              </div>
              <div class="col-md-3">
                <label for="uf" class="form-label">Estado</label>
                <select class="form-control" id="uf" name="uf" required>
                  <option value="" disabled selected>Selecione</option>
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
            </div>

            <div class="row mb-3">
              <div class="col-md-12">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" id="" name="">
                  <label class="form-check-label" for="">
                    Sou um servidor público atuante no câmpus.
                  </label>
                </div>
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

  <!-- Bootstrap JavaScript -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

  <!-- SweetAlert2 JS -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Verifica se há uma mensagem de erro de login
      <?php if ($login_error): ?>
        Swal.fire({
          title: 'Erro!',
          text: '<?= $login_error ?>',
          icon: 'error',
          confirmButtonText: 'OK'
        });
      <?php endif; ?>

      // Verifica se o login foi bem-sucedido
      <?php if (isset($login_success) && $login_success): ?>
        Swal.fire({
          title: 'Login bem-sucedido!',
          text: 'Você será redirecionado em 3 segundos.',
          icon: 'success',
          timer: 3000, // Timer de 3 segundos
          timerProgressBar: true,
          showConfirmButton: false
        }).then(() => {
          window.location.href = '../index.php'; // Redireciona para a página inicial
        });
      <?php endif; ?>

      // Verifica se o cadastro foi bem-sucedido
      <?php if ($signup_success): ?>
        Swal.fire({
          title: 'Cadastro bem-sucedido!',
          text: '<?= $signup_success ?>',
          icon: 'success',
          confirmButtonText: 'OK'
        });
      <?php endif; ?>
    });
  </script>
</body>

</html>