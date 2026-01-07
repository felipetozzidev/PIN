<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Inclui configurações se ainda não foram incluídas
require_once(__DIR__ . '/../../config/conn.php');
require_once(__DIR__ . '/../../config/log_helper.php');

$feedback_message = '';
$admin_user_name = $_SESSION['full_name'] ?? 'Usuário';

// LÓGICA DE PROCESSAMENTO DO FORMULÁRIO
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'create_post') {
    
    $user_id = $_SESSION['user_id'];
    $content = trim($_POST['content']);
    $tags_string = trim($_POST['tags']);

    if (empty($content) && empty($_FILES['post_media']['name'][0])) {
        $_SESSION['feedback_message'] = "<p class='error-message'>Você precisa escrever algo ou enviar uma imagem.</p>";
    } else {
        try {
            $pdo->beginTransaction();

            // 1. INSERE O POST
            $stmt_post = $pdo->prepare("INSERT INTO posts (user_id, content, type) VALUES (?, ?, 'padrao')");
            $stmt_post->execute([$user_id, $content]);
            $post_id = $pdo->lastInsertId();

            // 2. PROCESSA TAGS
            if (!empty($tags_string)) {
                $tags_array = array_map('trim', explode(',', $tags_string));
                foreach ($tags_array as $tag_name) {
                    if (empty($tag_name)) continue;
                    $tag_name = ltrim($tag_name, '#');

                    $stmt_find_tag = $pdo->prepare("SELECT tag_id FROM tags WHERE name = ?");
                    $stmt_find_tag->execute([$tag_name]);
                    $tag = $stmt_find_tag->fetch();

                    if ($tag) {
                        $tag_id = $tag['tag_id'];
                    } else {
                        $stmt_create_tag = $pdo->prepare("INSERT INTO tags (name) VALUES (?)");
                        $stmt_create_tag->execute([$tag_name]);
                        $tag_id = $pdo->lastInsertId();
                    }

                    $stmt_post_tag = $pdo->prepare("INSERT INTO post_tags (post_id, tag_id) VALUES (?, ?)");
                    $stmt_post_tag->execute([$post_id, $tag_id]);
                }
            }

            // 3. UPLOAD DE MÍDIA
            if (isset($_FILES['post_media']) && !empty($_FILES['post_media']['name'][0])) {
                $upload_dir = '../uploads/posts/';
                if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
                
                $order = 0;
                foreach ($_FILES['post_media']['name'] as $key => $name) {
                    if ($_FILES['post_media']['error'][$key] === UPLOAD_ERR_OK) {
                        $tmp = $_FILES['post_media']['tmp_name'][$key];
                        $ext = pathinfo($name, PATHINFO_EXTENSION);
                        $new_name = uniqid('', true) . '.' . $ext;
                        
                        if (move_uploaded_file($tmp, $upload_dir . $new_name)) {
                            $stmt_media = $pdo->prepare("INSERT INTO post_media (post_id, type, media_url, sort_order) VALUES (?, 'imagem', ?, ?)");
                            $stmt_media->execute([$post_id, $upload_dir . $new_name, $order]);
                            $order++;
                        }
                    }
                }
            }
            
            if (function_exists('logAction')) {
                logAction($pdo, 'Novo Post', $admin_user_name, "Usuário criou o post ID #{$post_id}.", $user_id);
            }

            $pdo->commit();
            $_SESSION['feedback_message'] = "<p class='success-message'>Post publicado com sucesso!</p>";
            
            // REDIRECIONAMENTO (Agora funciona pois não há HTML antes)
            header("Location: index.php");
            exit();

        } catch (Exception $e) {
            $pdo->rollBack();
            $_SESSION['feedback_message'] = "<p class='error-message'>Ocorreu um erro: " . $e->getMessage() . "</p>";
        }
    }
}