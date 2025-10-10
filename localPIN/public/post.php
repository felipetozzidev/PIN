<?php

// Inclui os arquivos de configuração e helpers.
require_once('../config/conn.php');
require_once('../config/log_helper.php'); // Adicionado para usar a função de log

// SEGURANÇA: Verifica se o usuário está logado. Se não, redireciona para o login.
if (!isset($_SESSION['id_usu'])) {
    header("Location: login.php?error=login_required");
    exit();
}

$feedback_message = '';

// LÓGICA DE PROCESSAMENTO DO FORMULÁRIO
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Coleta e sanitiza os dados do formulário
    $id_usu = $_SESSION['id_usu'];
    $conteudo_post = trim($_POST['conteudo_post']);
    // id_com agora é opcional, pode ser 0
    $id_com = isset($_POST['id_com']) ? intval($_POST['id_com']) : 0;
    $tags_string = trim($_POST['tags']);
    $aviso_conteudo = isset($_POST['aviso_conteudo']) ? 1 : 0;

    // Validação: o conteúdo é obrigatório, mas a comunidade não
    if (empty($conteudo_post) && empty($_FILES['post_media']['name'][0])) {
        $feedback_message = "<p class='error-message'>Você precisa escrever algo ou enviar uma imagem.</p>";
    } else {
        $conn->begin_transaction();
        try {
            // 1. INSERE O POST NA TABELA `posts`
            $stmt_post = $conn->prepare("INSERT INTO posts (id_usu, conteudo_post, tipo_post, aviso_conteudo) VALUES (?, ?, 'padrao', ?)");
            $stmt_post->bind_param("isi", $id_usu, $conteudo_post, $aviso_conteudo);
            $stmt_post->execute();
            $id_post = $conn->insert_id;
            $stmt_post->close();

            // 2. ASSOCIA O POST À COMUNIDADE (SE UMA FOI ESCOLHIDA)
            if ($id_com > 0) {
                $stmt_com_post = $conn->prepare("INSERT INTO comunidades_posts (id_com, id_post) VALUES (?, ?)");
                $stmt_com_post->bind_param("ii", $id_com, $id_post);
                $stmt_com_post->execute();
                $stmt_com_post->close();
            }

            // 3. PROCESSA E ASSOCIA AS TAGS
            if (!empty($tags_string)) {
                $tags_array = array_map('trim', explode(',', $tags_string));
                foreach ($tags_array as $nome_tag) {
                    if (empty($nome_tag)) continue;
                    $stmt_find_tag = $conn->prepare("SELECT id_tag FROM tags WHERE nome_tag = ?");
                    $stmt_find_tag->bind_param("s", $nome_tag);
                    $stmt_find_tag->execute();
                    $result_tag = $stmt_find_tag->get_result();
                    if ($result_tag->num_rows > 0) {
                        $id_tag = $result_tag->fetch_assoc()['id_tag'];
                    } else {
                        $stmt_create_tag = $conn->prepare("INSERT INTO tags (nome_tag) VALUES (?)");
                        $stmt_create_tag->bind_param("s", $nome_tag);
                        $stmt_create_tag->execute();
                        $id_tag = $conn->insert_id;
                        $stmt_create_tag->close();
                    }
                    $stmt_find_tag->close();
                    $stmt_post_tag = $conn->prepare("INSERT INTO posts_tags (id_post, id_tag) VALUES (?, ?)");
                    $stmt_post_tag->bind_param("ii", $id_post, $id_tag);
                    $stmt_post_tag->execute();
                    $stmt_post_tag->close();
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
                            $stmt_media = $conn->prepare("INSERT INTO post_media (id_post, tipo_media, url_media, ordem) VALUES (?, 'imagem', ?, ?)");
                            $stmt_media->bind_param("isi", $id_post, $destination, $order);
                            $stmt_media->execute();
                            $stmt_media->close();
                            $order++;
                        }
                    }
                }
            }

            // 5. REGISTRA A ATIVIDADE NO LOG
            $user_name_for_log = $_SESSION['nome_usu'] ?? 'Usuário Desconhecido';
            log_activity($conn, 'Novo Post', $user_name_for_log, "Usuário criou o post ID #{$id_post}.");

            // Se tudo deu certo, confirma as alterações e redireciona
            $conn->commit();
            $_SESSION['feedback_message'] = "<p class='success-message'>Post publicado com sucesso!</p>";
            header("Location: index.php");
            exit();
        } catch (mysqli_sql_exception $exception) {
            $conn->rollback();
            $feedback_message = "<p class='error-message'>Ocorreu um erro ao publicar seu post: " . $exception->getMessage() . "</p>";
        }
    }
}

// Busca as comunidades para preencher o campo de seleção
$comunidades = $conn->query("SELECT id_com, nome_com FROM comunidades ORDER BY nome_com ASC");

// Busca as tags mais usadas para recomendação
$popular_tags_query = "SELECT t.nome_tag, COUNT(pt.id_post) AS tag_count 
                       FROM tags t 
                       JOIN posts_tags pt ON t.id_tag = pt.id_tag 
                       GROUP BY t.id_tag 
                       ORDER BY tag_count DESC 
                       LIMIT 10";
$popular_tags = $conn->query($popular_tags_query);

include('../src/components/header.php');
?>

<main class="">
    <div class="post-creation-card">
        <h1>Criar Nova Publicação</h1>
        <p>Compartilhe suas ideias, dúvidas ou novidades com a comunidade.</p>

        <?php echo $feedback_message; ?>

        <form action="post.php" method="POST" class="post-form" enctype="multipart/form-data">
            <div class="form-group">
                <label for="conteudo_post">Sua Mensagem</label>
                <textarea name="conteudo_post" id="conteudo_post" rows="6" placeholder="No que você está pensando, <?php echo htmlspecialchars($_SESSION['nome_usu']); ?>?"></textarea>
            </div>

            <div class="form-group col-12">
                <div class="form-group col-6">
                    <label for="id_com">Publicar em uma Comunidade (Opcional)</label>
                    <select name="id_com" id="id_com">
                        <option value="0">Nenhuma (Post Geral)</option>
                        <?php if ($comunidades && $comunidades->num_rows > 0): ?>
                            <?php while ($com = $comunidades->fetch_assoc()): ?>
                                <option value="<?php echo $com['id_com']; ?>"><?php echo htmlspecialchars($com['nome_com']); ?></option>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    </select>
                </div>

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
                        <?php if ($popular_tags && $popular_tags->num_rows > 0): ?>
                            <?php while ($tag = $popular_tags->fetch_assoc()): ?>
                                <button type="button" class="recommended-tag"><?php echo htmlspecialchars($tag['nome_tag']); ?></button>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="form-group">
                    <label for="post_media">Adicionar Imagens</label>
                    <input type="file" name="post_media[]" id="post_media" multiple accept="image/*" class="form-control">
                    <div id="image-preview"></div>
                </div>

                <div class="form-group tag-system">
                </div>

                <div class="form-group-checkbox">
                    <input type="checkbox" name="aviso_conteudo" id="aviso_conteudo" value="1">
                    <label for="aviso_conteudo">Marcar como conteúdo sensível (aviso de conteúdo)</label>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Publicar</button>
                </div>
        </form>
    </div>
</main>

<script>
    document.addEventListener('DOMContentLoaded', function() {
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

        tagInput.addEventListener('keyup', async (e) => {
            if (e.key === ',' || e.key === 'Enter') {
                e.preventDefault();
                addTag(tagInput.value.replace(/,/g, ''));
            } else {
                const query = tagInput.value.trim();
                if (query.length < 1) {
                    suggestionsContainer.style.display = 'none';
                    return;
                }
                const response = await fetch(`api_search_tags.php?query=${encodeURIComponent(query)}`);
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
            // Adiciona novos arquivos ao nosso "depósito"
            Array.from(imageInput.files).forEach(file => filesStore.items.add(file));
            // Atualiza o input real com os arquivos do depósito
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
            currentFiles.splice(index, 1); // Remove o arquivo pelo índice
            currentFiles.forEach(file => newFiles.items.add(file));

            // Atualiza o depósito e o input
            filesStore = newFiles;
            imageInput.files = filesStore.files;

            renderPreviews();
        }
    });
</script>

<?php
include('../src/components/footer.php');
$conn->close();
?>