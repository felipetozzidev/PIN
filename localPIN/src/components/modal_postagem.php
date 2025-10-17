<?php
require_once('../config/log_helper.php'); // Adicionado para usar a função de log

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?error=login_required");
    exit();
}

$feedback_message = '';
$admin_user_name = $_SESSION['full_name'] ?? 'Usuário';

// LÓGICA DE PROCESSAMENTO DO FORMULÁRIO
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Coleta os dados do formulário
    $user_id = $_SESSION['user_id'];
    $content = trim($_POST['conteudo_post']);
    $community_id = isset($_POST['id_com']) ? intval($_POST['id_com']) : 0;
    $tags_string = trim($_POST['tags']);
    $content_warning = isset($_POST['aviso_conteudo']) ? 1 : 0;

    // Validação: o conteúdo ou uma imagem são obrigatórios
    if (empty($content) && empty($_FILES['post_media']['name'][0])) {
        $feedback_message = "<p class='error-message'>Você precisa escrever algo ou enviar uma imagem.</p>";
    } else {
        try {
            $pdo->beginTransaction();

            // 1. INSERE O POST NA TABELA `posts`
            $stmt_post = $pdo->prepare("INSERT INTO posts (user_id, content, type, content_warning) VALUES (?, ?, 'padrao', ?)");
            $stmt_post->execute([$user_id, $content, $content_warning]);
            $post_id = $pdo->lastInsertId();

            // 2. ASSOCIA O POST À COMUNIDADE (SE UMA FOI ESCOLHIDA)
            if ($community_id > 0) {
                $stmt_com_post = $pdo->prepare("INSERT INTO community_posts (community_id, post_id) VALUES (?, ?)");
                $stmt_com_post->execute([$community_id, $post_id]);
            }

            // 3. PROCESSA E ASSOCIA AS TAGS
            if (!empty($tags_string)) {
                $tags_array = array_map('trim', explode(',', $tags_string));
                foreach ($tags_array as $tag_name) {
                    if (empty($tag_name))
                        continue;

                    // Verifica se a tag já existe
                    $stmt_find_tag = $pdo->prepare("SELECT tag_id FROM tags WHERE name = ?");
                    $stmt_find_tag->execute([$tag_name]);
                    $tag = $stmt_find_tag->fetch();

                    if ($tag) {
                        $tag_id = $tag['tag_id'];
                    } else {
                        // Se não existir, cria a tag
                        $stmt_create_tag = $pdo->prepare("INSERT INTO tags (name) VALUES (?)");
                        $stmt_create_tag->execute([$tag_name]);
                        $tag_id = $pdo->lastInsertId();
                    }

                    // Associa o post à tag
                    $stmt_post_tag = $pdo->prepare("INSERT INTO post_tags (post_id, tag_id) VALUES (?, ?)");
                    $stmt_post_tag->execute([$post_id, $tag_id]);
                }
            }

            // 4. PROCESSA O UPLOAD DE IMAGENS
            if (isset($_FILES['post_media']) && !empty($_FILES['post_media']['name'][0])) {
                $upload_dir = '../uploads/posts/';
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }
                $order = 0;
                foreach ($_FILES['post_media']['name'] as $key => $name) {
                    if ($_FILES['post_media']['error'][$key] === UPLOAD_ERR_OK) {
                        $tmp_name = $_FILES['post_media']['tmp_name'][$key];
                        $file_ext = pathinfo($name, PATHINFO_EXTENSION);
                        $unique_name = uniqid('', true) . '.' . $file_ext;
                        $destination = $upload_dir . $unique_name;

                        if (move_uploaded_file($tmp_name, $destination)) {
                            $stmt_media = $pdo->prepare("INSERT INTO post_media (post_id, type, media_url, sort_order) VALUES (?, 'imagem', ?, ?)");
                            $stmt_media->execute([$post_id, $destination, $order]);
                            $order++;
                        }
                    }
                }
            }

            // 5. REGISTRA A ATIVIDADE NO LOG (CORRIGIDO)
            logAction($pdo, 'Novo Post', $admin_user_name, "Usuário criou o post ID #{$post_id}.", $user_id);

            // Se tudo deu certo, confirma as alterações e redireciona
            $pdo->commit();
            $_SESSION['feedback_message'] = "<p class='success-message'>Post publicado com sucesso!</p>";
            header("Location: index.php");
            exit();
        } catch (Exception $e) {
            $pdo->rollBack();
            $feedback_message = "<p class='error-message'>Ocorreu um erro ao publicar seu post: " . $e->getMessage() . "</p>";
        }
    }
}

// Busca as comunidades para preencher o campo de seleção
$communities_query = $pdo->query("SELECT community_id, name FROM communities ORDER BY name ASC");
$communities = $communities_query->fetchAll(PDO::FETCH_ASSOC);

// Busca as tags mais usadas para recomendação
$popular_tags_query = "SELECT t.name, COUNT(pt.post_id) AS tag_count 
                       FROM tags t 
                       JOIN post_tags pt ON t.tag_id = pt.tag_id 
                       GROUP BY t.tag_id 
                       ORDER BY tag_count DESC 
                       LIMIT 10";
$popular_tags = $pdo->query($popular_tags_query)->fetchAll(PDO::FETCH_ASSOC);

?>

<main class="modal_container">
    
    <section class="modal_body">
        <div class="form-group col-6">
            <label for="tag-input">Tags</label>
            <div class="tag-container">
                <div id="selected-tags"></div>
                <input type="text" id="tag-input" placeholder="Digite para buscar ou adicionar...">
            </div>
            <input type="hidden" name="tags" id="hidden-tags">
            <div id="tag-suggestions"></div>
            <div class="recommended-tags">
                <strong>Tags Populares:</strong>
                <?php if ($popular_tags): ?>
                    <?php foreach ($popular_tags as $tag): ?>
                        <button type="button" class="recommended-tag"><?php echo htmlspecialchars($tag['name']); ?></button>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>
</main>

<!-- <script>
    document.addEventListener('DOMContentLoaded', function () {
        // --- Sistema de Tags ---
        const tagInput = document.getElementById('tag-input');
        const selectedTagsContainer = document.getElementById('selected-tags');
        const hiddenTagsInput = document.getElementById('hidden-tags');
        const suggestionsContainer = document.getElementById('tag-suggestions');
        const recommendedTagsContainer = document.querySelector('.recommended-tags');
        let selectedTags = new Set();

        function updateHiddenInput() {
            hiddenTagsInput.value = Array.from(selectedTags).join(',');
        }

        function addTag(tagName) {
            tagName = tagName.trim().toLowerCase();
            if (tagName && !selectedTags.has(tagName)) {
                selectedTags.add(tagName);
                const tagPill = document.createElement('div');
                tagPill.className = 'tag-pill';
                tagPill.textContent = tagName;
                const removeBtn = document.createElement('span');
                removeBtn.className = 'remove-tag';
                removeBtn.textContent = 'x';
                removeBtn.onclick = () => {
                    selectedTags.delete(tagName);
                    tagPill.remove();
                    updateHiddenInput();
                };
                tagPill.appendChild(removeBtn);
                selectedTagsContainer.appendChild(tagPill);
                updateHiddenInput();
            }
            tagInput.value = '';
            suggestionsContainer.innerHTML = '';
            suggestionsContainer.style.display = 'none';
        }

        tagInput.addEventListener("keyup", async (e) => {
            if (e.key === ',' || e.key === 'Enter') {
                e.preventDefault();
                addTag(tagInput.value.replace(/,/g, ''));
            } else {
                const query = tagInput.value.trim();
                if (query.length < 1) {
                    suggestionsContainer.style.display = 'none';
                    return;
                }
                // NOTE: A API para buscar tags precisa ser criada ou ajustada se o nome mudou.
                // Assumindo que api_search_tags.php será criado/ajustado.
                const response = await fetch(`api/api_search_tags.php?query=${encodeURIComponent(query)}`);
                const suggestions = await response.json();
                suggestionsContainer.innerHTML = '';
                if (suggestions.length > 0) {
                    suggestions.forEach(tag => {
                        const item = document.createElement('div');
                        item.className = 'suggestion-item';
                        item.textContent = tag;
                        item.onclick = () => addTag(tag);
                        suggestionsContainer.appendChild(item);
                    });
                    suggestionsContainer.style.display = 'block';
                } else {
                    suggestionsContainer.style.display = 'none';
                }
            }
        });

        recommendedTagsContainer.addEventListener('click', (e) => {
            if (e.target.classList.contains('recommended-tag')) {
                addTag(e.target.textContent);
            }
        });

        document.addEventListener('click', (e) => {
            if (!tagInput.contains(e.target)) {
                suggestionsContainer.style.display = 'none';
            }
        });

        // --- Sistema de Preview de Imagens ---
        const imageInput = document.getElementById('post_media');
        const previewContainer = document.getElementById('image-preview');
        let filesStore = new DataTransfer();

        imageInput.addEventListener('change', () => {
            Array.from(imageInput.files).forEach(file => filesStore.items.add(file));
            imageInput.files = filesStore.files;
            renderPreviews();
        });

        function renderPreviews() {
            previewContainer.innerHTML = '';
            Array.from(filesStore.files).forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = (e) => {
                    const container = document.createElement('div');
                    container.className = 'preview-image-container';
                    const img = document.createElement('img');
                    img.className = 'preview-image';
                    img.src = e.target.result;
                    const removeBtn = document.createElement('div');
                    removeBtn.className = 'remove-image-btn';
                    removeBtn.textContent = 'x';
                    removeBtn.onclick = () => {
                        removeFile(index);
                    };
                    container.appendChild(img);
                    container.appendChild(removeBtn);
                    previewContainer.appendChild(container);
                };
                reader.readAsDataURL(file);
            });
        }

        function removeFile(index) {
            const newFiles = new DataTransfer();
            const currentFiles = Array.from(filesStore.files);
            currentFiles.splice(index, 1);
            currentFiles.forEach(file => newFiles.items.add(file));
            filesStore = newFiles;
            imageInput.files = filesStore.files;
            renderPreviews();
        }
    });
</script> -->