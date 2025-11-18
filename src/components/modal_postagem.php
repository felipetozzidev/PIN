<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once(__DIR__ . '/../../config/conn.php');
require_once(__DIR__ . '/../../config/log_helper.php');

$feedback_message = '';
$admin_user_name = $_SESSION['full_name'] ?? 'Usuário';

// LÓGICA DE PROCESSAMENTO DO FORMULÁRIO
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Coleta os dados do formulário
    $user_id = $_SESSION['user_id'];
    $content = trim($_POST['content']); // Campo do seu textarea
    $tags_string = trim($_POST['tags']); // Campo hidden das tags
    // Os campos community_id e content_warning não existem no seu formulário, então foram removidos.

    // Validação
    if (empty($content) && empty($_FILES['post_media']['name'][0])) {
        $feedback_message = "<p class='error-message'>Você precisa escrever algo ou enviar uma imagem.</p>";
    } else {
        try {
            $pdo->beginTransaction();

            // 1. INSERE O POST NA TABELA `posts`
            // (Removido 'content_warning' pois não existe no form)
            $stmt_post = $pdo->prepare("INSERT INTO posts (user_id, content, type) VALUES (?, ?, 'padrao')");
            $stmt_post->execute([$user_id, $content]);
            $post_id = $pdo->lastInsertId();

            // 3. PROCESSA E ASSOCIA AS TAGS
            if (!empty($tags_string)) {
                $tags_array = array_map('trim', explode(',', $tags_string));
                foreach ($tags_array as $tag_name) {
                    if (empty($tag_name))
                        continue;

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

            // 4. PROCESSA O UPLOAD DE IMAGENS
            // (Usando o name 'post_media[]')
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

            logAction($pdo, 'Novo Post', $admin_user_name, "Usuário criou o post ID #{$post_id}.", $user_id);

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

// Busca as tags mais usadas para recomendação
$popular_tags_query = "SELECT t.name, COUNT(pt.post_id) AS tag_count 
                       FROM tags t 
                       JOIN post_tags pt ON t.tag_id = pt.tag_id 
                       GROUP BY t.tag_id 
                       ORDER BY tag_count DESC 
                       LIMIT 10";
$popular_tags = $pdo->query($popular_tags_query)->fetchAll(PDO::FETCH_ASSOC);

?>

<section class="modal_container">

    <form action="index.php" method="POST" class="modal_body" enctype="multipart/form-data" id="modal_body">
        <i class="ri-close-fill" id="close_modal"></i>
        <div class="main_content">
            <img src="<?php
            if (isset($_SESSION['profile_image_url'])) {
                echo $_SESSION['profile_image_url'];
            } else {
                echo '../src/assets/img/default-user.png';
            }
            ?>" alt="">
            <textarea name="content" id="content" rows="6" placeholder="No que você está pensando, <?php
            if (isset($_SESSION['full_name'])) {
                echo htmlspecialchars($_SESSION['full_name']);
            } else {
                echo 'Usuário';
            }
            ?>?"></textarea>
        </div>

        <div id="image-preview"></div>

        <hr>

        <div class="post_footer">
            <div class="footer_left">
                <div class="post_media">
                    <label for="post_media">
                        <i class="ri-image-add-fill"></i>
                    </label>
                    <input type="file" name="post_media[]" id="post_media" multiple accept="image/*"
                        class="form-control">
                </div>
                <div class="post_tag">
                    <div class="form-group col-6">
                        <label for="tag-input">Tags:</label>
                        <div class="tag-container">
                            <div id="selected-tags"></div>
                            <input type="text" id="tag-input" placeholder="Digite para buscar ou adicionar...">
                        </div>
                        <input type="hidden" name="tags" id="hidden-tags">
                        <div id="tag-suggestions"></div>
                        <div class="recommended-tags"></div>
                    </div>
                </div>
            </div>
            <div class="footer_right">
                <button type="submit" class="publicar">Publicar</button>
            </div>
    </form>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function () {

        // --- Lógica de ABRIR E FECHAR o modal ---
        const createPostButton = document.querySelector('#create_post');
        const modalContainer = document.querySelector('section.modal_container');
        const closeModalButton = document.querySelector('#close_modal');
        const modalBody = document.querySelector('form.modal_body'); // Referência ao corpo do modal com scroll

        // Função para fechar o modal
        function hideModal() {
            if (modalContainer) {
                modalContainer.classList.remove('active');
                document.body.style.overflow = 'auto';
                // Garante que a caixa de sugestões também feche
                if (suggestionsContainer) {
                    suggestionsContainer.style.display = 'none';
                }
            }
        }

        // Adiciona os "escutadores" de evento
        if (createPostButton && modalContainer) {
            createPostButton.addEventListener('click', function (event) {
                event.preventDefault();
                modalContainer.classList.add('active');
                document.body.style.overflow = 'hidden';
            });
        }
        if (closeModalButton) {
            closeModalButton.addEventListener('click', hideModal);
        }
        if (modalContainer) {
            modalContainer.addEventListener('click', (event) => {
                // Fecha o modal se o clique for no fundo escuro
                if (event.target === modalContainer) {
                    hideModal();
                }
            });
        }

        // --- Sistema de Tags com POSICIONAMENTO CORRIGIDO ---
        const tagInput = document.getElementById('tag-input');
        const tagContainer = document.querySelector('.tag-container');
        const selectedTagsContainer = document.getElementById('selected-tags');
        const hiddenTagsInput = document.getElementById('hidden-tags');
        const suggestionsContainer = document.getElementById('tag-suggestions');
        let selectedTags = new Set();

        if (tagInput && tagContainer && hiddenTagsInput && suggestionsContainer) {

            // Move a caixa de sugestões para o body para escapar do overflow do modal
            document.body.appendChild(suggestionsContainer);

            // Função para calcular a posição da caixa de sugestões com base na janela
            function updateSuggestionsPosition() {
                const containerRect = tagContainer.getBoundingClientRect();

                // Posições são calculadas em relação à janela (viewport)
                suggestionsContainer.style.top = `${containerRect.bottom + 5}px`; // 5px de espaço
                suggestionsContainer.style.left = `${containerRect.left}px`;
                suggestionsContainer.style.width = `${containerRect.width}px`;
            }

            // Função para buscar tags na API
            async function fetchTags(query = '') {
                try {
                    const response = await fetch(`api/api_search_tags.php?query=${encodeURIComponent(query)}`);
                    if (!response.ok) return [];
                    return await response.json();
                } catch (error) {
                    console.error("Erro ao buscar tags:", error);
                    return [];
                }
            }

            // Função para exibir as sugestões no formato do Instagram
            function displaySuggestions(tags) {
                suggestionsContainer.innerHTML = '';
                if (!Array.isArray(tags) || tags.length === 0) {
                    suggestionsContainer.style.display = 'none';
                    return;
                }
                tags.forEach(tag => {
                    const item = document.createElement('div');
                    item.className = 'suggestion-item';

                    const tagName = document.createElement('span');
                    tagName.className = 'suggestion-name';
                    tagName.textContent = tag.name;

                    const tagCount = document.createElement('span');
                    tagCount.className = 'suggestion-count';
                    const count = parseInt(tag.post_count);
                    tagCount.textContent = count > 1 ? `${count} posts` : `${count} post`;

                    item.appendChild(tagName);
                    item.appendChild(tagCount);

                    item.addEventListener('click', () => {
                        addTag(tag.name);
                    });
                    suggestionsContainer.appendChild(item);
                });

                updateSuggestionsPosition();
                suggestionsContainer.style.display = 'block';
            }

            // Mostra as tags populares ao focar no input
            tagInput.addEventListener('focus', async () => {
                const popularTags = await fetchTags('');
                displaySuggestions(popularTags);
            });

            // Busca por tags enquanto o usuário digita
            tagInput.addEventListener('input', async () => {
                const query = tagInput.value.trim();
                const suggestedTags = await fetchTags(query);
                displaySuggestions(suggestedTags);
            });

            // Adiciona a tag ao pressionar Enter
            tagInput.addEventListener("keydown", (e) => {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    addTag(tagInput.value);
                }
            });

            // Esconde a caixa se clicar fora do campo de input
            document.addEventListener('click', (e) => {
                if (!tagContainer.contains(e.target)) {
                    suggestionsContainer.style.display = 'none';
                }
            });

            // Esconde as sugestões se o usuário rolar o modal
            if (modalBody) {
                modalBody.addEventListener('scroll', () => {
                    suggestionsContainer.style.display = 'none';
                });
            }
            // Recalcula a posição se a janela for redimensionada
            window.addEventListener('resize', () => {
                if (suggestionsContainer.style.display === 'block') {
                    updateSuggestionsPosition();
                }
            });

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
                suggestionsContainer.style.display = 'none';
            }
        }

        // --- Sistema de Preview de Imagens ---
        const imageInput = document.getElementById('post_media');
        const previewContainer = document.getElementById('image-preview');
        let filesStore = new DataTransfer();

        if (imageInput && previewContainer) {
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
        }
    });
</script>