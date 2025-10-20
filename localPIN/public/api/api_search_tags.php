<?php
header('Content-Type: application/json');

// CORREÇÃO: Usando o caminho relativo correto e a conexão PDO
require_once(__DIR__ . '/../../config/conn.php'); 

$query = isset($_GET['query']) ? trim($_GET['query']) : '';
$suggestions = [];

try {
    // Se a busca estiver VAZIA, busca as tags mais populares com contagem de posts
    if (empty($query)) {
        $sql = "SELECT t.name, COUNT(pt.post_id) as post_count
                FROM tags t 
                JOIN post_tags pt ON t.tag_id = pt.tag_id 
                GROUP BY t.tag_id 
                ORDER BY post_count DESC 
                LIMIT 10";
        $stmt = $pdo->query($sql);
    } 
    // Se HOUVER uma busca, procura por tags semelhantes e sua contagem
    else {
        $search_term = '%' . $query . '%'; // Procura o termo em qualquer parte da tag
        $sql = "SELECT t.name, COUNT(pt.post_id) as post_count
                FROM tags t 
                LEFT JOIN post_tags pt ON t.tag_id = pt.tag_id 
                WHERE t.name LIKE ?
                GROUP BY t.tag_id
                ORDER BY post_count DESC, t.name ASC
                LIMIT 10";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$search_term]);
    }

    if ($stmt) {
        // Retorna um array de objetos, cada um com 'name' e 'post_count'
        $suggestions = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

} catch (Exception $e) {
    // Retorna um erro em formato JSON para depuração
    $suggestions = ['error' => $e->getMessage()];
}

echo json_encode($suggestions);
?>