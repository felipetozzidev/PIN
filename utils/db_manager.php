<?php
// Inicia a sessão para usar em verificações futuras, se necessário.
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// --- CONFIGURAÇÕES DE SEGURANÇA ---
define('DB_MANAGER_PASSWORD', 'iLn46MiY=|~q~M>9');

// 2. (Opcional) Restringir por IP. Adicione seu IP para segurança extra.
// Se seu IP for dinâmico, deixe o array vazio: []
$allowed_ips = []; // Ex: ['177.123.45.67', '189.34.56.78']

// --- LÓGICA DE AUTENTICAÇÃO ---
$is_authenticated = false;
$feedback_message = '';

// Verifica se o IP é permitido (se a lista não estiver vazia)
if (!empty($allowed_ips) && !in_array($_SERVER['REMOTE_ADDR'], $allowed_ips)) {
    header('HTTP/1.0 403 Forbidden');
    die('Acesso negado: seu endereço IP não está na lista de permissões.');
}

// Verifica se o formulário de login foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['password'])) {
    if (hash_equals(DB_MANAGER_PASSWORD, $_POST['password'])) {
        $_SESSION['db_manager_authenticated'] = true;
        $is_authenticated = true;
    } else {
        $feedback_message = '<p class="error">Senha incorreta!</p>';
    }
}

// Verifica se o usuário já está autenticado na sessão
if (isset($_SESSION['db_manager_authenticated']) && $_SESSION['db_manager_authenticated'] === true) {
    $is_authenticated = true;
}

// Lógica para executar o SQL se o usuário estiver autenticado
$sql_results = '';
if ($is_authenticated && $_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['sql_query'])) {
    try {
        require_once '../config/conn.php'; // Ajuste o caminho se necessário
        $sql_query = trim($_POST['sql_query']);

        // Inicia a transação
        $pdo->beginTransaction();

        // Executa a query. Use exec() pois pode conter múltiplos comandos.
        $affected_rows = $pdo->exec($sql_query);

        // Se tudo deu certo, confirma a transação
        $pdo->commit();

        $sql_results = "<div class='success'><strong>SUCESSO!</strong><br>O comando SQL foi executado.<br>Linhas afetadas (total): " . ($affected_rows !== false ? $affected_rows : 0) . "</div>";
        $sql_results .= "<pre>" . htmlspecialchars($sql_query) . "</pre>";
    } catch (PDOException $e) {
        // Se algo deu errado, desfaz a transação
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        $sql_results = "<div class='error'><strong>ERRO AO EXECUTAR SQL!</strong><br>A transação foi desfeita (nenhuma alteração foi salva).<br><strong>Mensagem:</strong> " . $e->getMessage() . "</div>";
        $sql_results .= "<pre>" . htmlspecialchars($sql_query) . "</pre>";
    }
}

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciador de Banco de Dados</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            background-color: #f4f5f7;
            color: #333;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .container {
            background-color: #fff;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 800px;
        }

        h1 {
            text-align: center;
            color: #489141ff;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        label {
            font-weight: bold;
        }

        input[type="password"],
        textarea {
            width: 100%;
            padding: 0.8rem;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 1rem;
            box-sizing: border-box;
        }

        textarea {
            min-height: 200px;
            font-family: monospace;
        }

        button {
            background-color: #305F2C;
            color: white;
            padding: 0.8rem 1.5rem;
            border: none;
            border-radius: 4px;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        button:hover {
            background-color: #3e7839ff;
        }

        .error {
            color: #d93025;
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            padding: 1rem;
            border-radius: 4px;
            text-align: center;
        }

        .success {
            color: #11471eff;
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            padding: 1rem;
            border-radius: 4px;
        }

        .results {
            margin-top: 2rem;
        }

        pre {
            background-color: #e9ecef;
            padding: 1rem;
            border-radius: 4px;
            white-space: pre-wrap;
            word-wrap: break-word;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Gerenciador de Banco de Dados</h1>

        <?php if ($is_authenticated): ?>
            <form action="" method="POST">
                <label for="sql_query">Comando SQL:</label>
                <textarea id="sql_query" name="sql_query" placeholder="-- Exemplo:
ALTER TABLE `tabela` ADD COLUMN `nova_coluna` VARCHAR(255) NULL;
DELETE FROM `logs` WHERE `data` < '2023-01-01';" required></textarea>
                <button type="submit">Executar</button>
            </form>

            <?php if (!empty($sql_results)): ?>
                <div class="results">
                    <h2>Resultados da Execução</h2>
                    <?php echo $sql_results; ?>
                </div>
            <?php endif; ?>

        <?php else: ?>
            <form action="" method="POST">
                <label for="password">Digite a senha para continuar:</label>
                <input type="password" id="password" name="password" required>
                <button type="submit">Acessar</button>
                <?php echo $feedback_message; ?>
            </form>
        <?php endif; ?>
    </div>
</body>

</html>