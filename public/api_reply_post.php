<?php
require_once('../config/conn.php');
header('Content-Type: application/json');

// Segurança: Verifica se o usuário está logado e se os dados foram enviados
if (!isset($_SESSION['id_usu'])) {
    echo json_encode(['success' => false, 'error' => 'Usuário não autenticado.']);
    exit();
}
if (!isset($_POST['id_post_pai']) || !isset($_POST['conteudo_post'])) {
    echo json_encode(['success' => false, 'error' => 'Dados incompletos.']);
    exit();
}

$id_usu = $_SESSION['id_usu'];
$id_post_pai = intval($_POST['id_post_pai']);
$conteudo_post = trim($_POST['conteudo_post']);

if (empty($conteudo_post)) {
    echo json_encode(['success' => false, 'error' => 'A resposta não pode estar vazia.']);
    exit();
}

$conn->begin_transaction();

try {
    // 1. Insere o novo post (a resposta)
    $stmt_insert = $conn->prepare("INSERT INTO posts (id_usu, conteudo_post, tipo_post, id_post_pai) VALUES (?, ?, 'resposta', ?)");
    $stmt_insert->bind_param("isi", $id_usu, $conteudo_post, $id_post_pai);
    $stmt_insert->execute();
    $new_comment_id = $conn->insert_id;
    $stmt_insert->close();

    // 2. Atualiza o contador de respostas no post pai
    $stmt_update = $conn->prepare("UPDATE posts SET cont_respostas = cont_respostas + 1 WHERE id_post = ?");
    $stmt_update->bind_param("i", $id_post_pai);
    $stmt_update->execute();
    $stmt_update->close();

    // Confirma a transação
    $conn->commit();

    // Prepara os dados para retornar ao frontend
    $response_data = [
        'success' => true,
        'comment' => [
            'id_post' => $new_comment_id,
            'conteudo_post' => htmlspecialchars($conteudo_post),
            'data_post' => date("H:i · d/m/Y"),
            'nome_usu' => $_SESSION['nome_usu'],
            'imgperfil_usu' => $_SESSION['imgperfil_usu'] // Assumindo que você salva isso na sessão
        ]
    ];
    echo json_encode($response_data);
} catch (mysqli_sql_exception $exception) {
    $conn->rollback();
    echo json_encode(['success' => false, 'error' => 'Erro no banco de dados: ' . $exception->getMessage()]);
}

$conn->close();
