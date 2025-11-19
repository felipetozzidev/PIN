<link rel="stylesheet" href="../src/assets/css/style.css">

<main class="modal_container" id="postModalContainer">
    <form action="index.php" method="POST" class="modal_body" enctype="multipart/form-data">
        <input type="hidden" name="action" value="create_post">

        <i class="ri-close-fill" id="close_modal_btn"></i>
        
        <div class="main_content">
            <img src="<?php echo $_SESSION['profile_image_url'] ?? '../src/assets/img/default-user.png'; ?>" alt="User">
            <textarea name="content" id="content" rows="5" placeholder="No que você está pensando, <?php echo htmlspecialchars(explode(' ', $_SESSION['full_name'] ?? 'Usuário')[0]); ?>?"></textarea>
        </div>

        <div id="image-preview"></div> 
        <hr>
        
        <div class="post_footer">
            <div class="footer_left">
                <div class="post_media">
                    <label for="post_media" title="Adicionar imagem">
                        <i class="ri-image-add-fill"></i>
                    </label>
                    <input type="file" name="post_media[]" id="post_media" multiple accept="image/*" style="display: none;">
                </div>

                <div class="post_tag" style="flex-grow: 1; position: relative;">
                    <div class="tag-wrapper"> 
                        <div class="tag-container">
                            <div id="selected-tags"></div>
                            <input type="text" id="tag-input" placeholder="Tags..." autocomplete="off">
                        </div>
                        <input type="hidden" name="tags" id="hidden-tags">
                        
                        <div id="tag-suggestions" class="suggestions-box"></div>
                    </div>
                </div>
            </div>
            
            <div class="footer_right">
                <button type="submit" class="publicar">Publicar</button>
            </div>
        </div>
    </form>
</main>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('postModalContainer');
        const closeBtn = document.getElementById('close_modal_btn');
        const openBtn = document.getElementById('create_post'); // Botão no Header

        // Abrir Modal
        if(openBtn && modal) {
            openBtn.addEventListener('click', (e) => {
                e.preventDefault();
                modal.classList.add('active');
                document.body.style.overflow = 'hidden';
            });
        }

        // Fechar Modal
        function closeModal() {
            if(modal) {
                modal.classList.remove('active');
                document.body.style.overflow = 'auto';
            }
        }

        if(closeBtn) closeBtn.addEventListener('click', closeModal);
        
        if(modal) {
            modal.addEventListener('click', (e) => {
                if(e.target === modal) closeModal();
            });
        }

        // --- LÓGICA DE TAGS ---
        const tagInput = document.getElementById('tag-input');
        const suggestionsBox = document.getElementById('tag-suggestions');
        const hiddenInput = document.getElementById('hidden-tags');
        const selectedContainer = document.getElementById('selected-tags');
        let tags = new Set();

        if(tagInput) {
            tagInput.addEventListener('input', async function() {
                const query = this.value.trim();
                if(query.length > 0) {
                    try {
                        const res = await fetch(`api/api_search_tags.php?query=${query}`);
                        const data = await res.json();
                        
                        suggestionsBox.innerHTML = '';
                        let hasMatch = false;

                        data.forEach(tag => {
                            if(tag.name.toLowerCase() === query.toLowerCase()) hasMatch = true;
                            addSuggestion(tag.name, tag.post_count + ' posts');
                        });

                        if(!hasMatch) {
                            addSuggestion(query, 'Criar nova tag', true);
                        }
                        
                        suggestionsBox.style.display = 'block';
                    } catch(e) { console.error(e); }
                } else {
                    suggestionsBox.style.display = 'none';
                }
            });

            function addSuggestion(name, info, isNew = false) {
                const div = document.createElement('div');
                div.className = 'suggestion-item';
                div.innerHTML = `<span>#${name}</span> <small style="${isNew ? 'color:var(--primary);font-weight:bold' : ''}">${info}</small>`;
                div.onclick = () => selectTag(name);
                suggestionsBox.appendChild(div);
            }

            function selectTag(name) {
                const cleanName = name.replace('#', '');
                if(!tags.has(cleanName)) {
                    tags.add(cleanName);
                    renderTags();
                }
                tagInput.value = '';
                suggestionsBox.style.display = 'none';
            }

            function renderTags() {
                selectedContainer.innerHTML = '';
                tags.forEach(tag => {
                    const span = document.createElement('span');
                    span.className = 'tag-pill'; // Classe do style.css
                    span.innerHTML = `#${tag} <i class="ri-close-line" onclick="removeTag('${tag}')" style="cursor:pointer;margin-left:5px;"></i>`;
                    selectedContainer.appendChild(span);
                });
                hiddenInput.value = Array.from(tags).join(',');
            }

            // Função global para remover tag
            window.removeTag = function(tag) {
                tags.delete(tag);
                renderTags();
            }
        }

        // --- PREVIEW DE IMAGENS ---
        const imageInput = document.getElementById('post_media');
        const previewContainer = document.getElementById('image-preview');
        // Usando DataTransfer para manipular os arquivos do input
        let filesStore = new DataTransfer();

        if(imageInput && previewContainer) {
            imageInput.addEventListener('change', function() {
                // Adiciona novos arquivos ao store
                Array.from(this.files).forEach(file => filesStore.items.add(file));
                // Atualiza o input com todos os arquivos acumulados
                this.files = filesStore.files;
                renderPreviews();
            });

            function renderPreviews() {
                previewContainer.innerHTML = '';
                Array.from(filesStore.files).forEach((file, index) => {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const container = document.createElement('div');
                        container.className = 'preview-image-container'; // Classe do style.css

                        const img = document.createElement('img');
                        img.className = 'preview-image';
                        img.src = e.target.result;

                        const removeBtn = document.createElement('div');
                        removeBtn.className = 'remove-image-btn';
                        removeBtn.textContent = 'x';
                        
                        removeBtn.onclick = function() {
                            // Remove do store
                            filesStore.items.remove(index);
                            // Atualiza o input real
                            imageInput.files = filesStore.files;
                            // Re-renderiza
                            renderPreviews();
                        };

                        container.appendChild(img);
                        container.appendChild(removeBtn);
                        previewContainer.appendChild(container);
                    }
                    reader.readAsDataURL(file);
                });
            }
        }
    });
</script>