<?php
// Define o tipo de conteúdo da resposta como JSON
header('Content-Type: application/json');

// Inclui o arquivo de conexão com o banco de dados.
require_once('../config/conn.php');

// Pega o termo de busca da URL (query string)
$query = isset($_GET['query']) ? trim($_GET['query']) : '';

$suggestions = [];

if (strlen($query) > 0) {
    // Prepara a query para buscar tags que comecem com o termo digitado
    // O uso de '%' permite a busca por correspondência parcial (ex: 'ph' encontra 'php')
    $search_term = $query . '%';

    $stmt = $conn->prepare("SELECT nome_tag FROM tags WHERE nome_tag LIKE ? LIMIT 10");
    $stmt->bind_param("s", $search_term);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $suggestions[] = $row['nome_tag'];
        }
    }
    $stmt->close();
}

// Retorna as sugestões como um array JSON
echo json_encode($suggestions);

$conn->close();
