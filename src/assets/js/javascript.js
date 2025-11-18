// Espera o conteúdo da página carregar completamente antes de executar qualquer script.
document.addEventListener('DOMContentLoaded', function () {

    const isPublicPage = document.querySelector('.index-container, .view-post-container, .profile-container');
    if (isPublicPage) {

        const objetos = {
            navbar: document.querySelector(".navbar_container"),
            nav: document.querySelector("nav"),
            main: document.querySelector("main"),
            main_perfil: document.querySelector("main[data-pagina='user_profile']"),
            footer: document.querySelector("footer.pag_footer"),
            body: document.querySelector("body"),
            community_cards_container: document.querySelector("div.community_cards_container"),
            tamanhoCabecalho: function () { return this.nav ? this.nav.clientHeight : 0; },
            tamanhoBody: function () { return this.body ? this.body.clientHeight : 0; },
            paddingCabecalho: function () { return this.main ? getComputedStyle(this.main).paddingTop : '0px'; },
            tamanhoFooter: function () { return this.footer ? this.footer.clientHeight : 0; },
            dropdownItens: function () { return document.querySelectorAll(".dropdown_item"); },
            tamanhoMain: function () { return this.main ? this.main.clientHeight : 0; },
            ifcelular: function () { return window.screen.width < 768; },
            // --- Adicionando os seletores do modal aqui ---
            createPostButton: document.querySelector('#create_post'),
            modalContainer: document.querySelector('main.modal_container')
        };

        // --- Ajustes de Layout ---
        if (objetos.main && objetos.nav) {
            objetos.main.style.marginTop = `${objetos.tamanhoCabecalho()}px`;
            if (objetos.footer) {
                objetos.main.style.marginBottom = `${objetos.tamanhoFooter()}px`;
            }
        }

        if(objetos.ifcelular()) {
            document.querySelector("body > main > section.main_container > div > div.profile_header > div > div.profile_options > a").innerHTML = "<i class='ri-edit-line'></i>";
        }

        // --- Correção do Erro do Console (Página de Perfil) ---
        if (objetos.main_perfil) {
            // Verifica se footer e nav existem antes de calcular
            if (objetos.footer && objetos.nav) {
                const espacoDisponivel = objetos.tamanhoBody() - objetos.tamanhoFooter() - objetos.tamanhoCabecalho();
                if (espacoDisponivel > objetos.tamanhoMain()) {
                    const contentDiv = objetos.main_perfil.querySelector("section.main_container > div.main_content");
                    if (contentDiv) {
                        contentDiv.classList.add("fixo");
                    }
                }
            }
        }


        // if (objetos.footer && (objetos.tamanhoCabecalho() + objetos.tamanhoBody() < window.screen.height)) {
        //     objetos.footer.style.position = "absolute";
        //     objetos.footer.style.bottom = "0px";
        // }
        if (objetos.community_cards_container && objetos.nav) {
            objetos.community_cards_container.style.top = `calc(${objetos.tamanhoCabecalho()}px + 20px)`;
        }
        if (objetos.navbar && objetos.nav && objetos.footer && objetos.main) {
            objetos.navbar.style.height = `calc(100% - ${objetos.tamanhoCabecalho()}px - ${objetos.tamanhoFooter()}px - ${objetos.paddingCabecalho()})`;
        }

        // --- Dropdown arrows (automação) ---
        const dropdownItens = objetos.dropdownItens();
        if (dropdownItens.length > 0) {
            dropdownItens.forEach(element => {
                const tag_a_selection = element.querySelector("a");
                if (tag_a_selection) {
                    const createIconDropdown = document.createElement("img");
                    createIconDropdown.setAttribute("src", "../../src/assets/icons/arrow_down.svg");
                    tag_a_selection.appendChild(createIconDropdown);
                    element.style.height = `${tag_a_selection.clientHeight}px`;

                    tag_a_selection.addEventListener("click", () => {
                        element.classList.toggle("active");
                        if (element.classList.contains("active")) {
                            element.style.height = `${tag_a_selection.clientHeight + element.querySelector("ul.dropdown_list").clientHeight}px`;
                        } else {
                            element.style.height = `${tag_a_selection.clientHeight}px`;
                        }
                    });
                }
            });
        }

        // --- LÓGICA DE ABERTURA DO MODAL ---
        if (objetos.createPostButton && objetos.modalContainer) {
            objetos.createPostButton.addEventListener('click', function (event) {
                event.preventDefault(); // Previne que o link <a> navegue
                objetos.modalContainer.classList.add('active');
                document.body.style.overflow = 'hidden';
            });
        }

    } // Fim do if (isPublicPage)

    // --- DROPDOWNS DAS PÁGINAS ADMIN ---
    const adminDropdowns = document.querySelectorAll('.profile-dropdown');
    if (adminDropdowns.length > 0) {
        adminDropdowns.forEach(dropdown => {
            const button = dropdown.querySelector('button');
            if (button) {
                button.addEventListener('click', function (event) {
                    event.preventDefault();
                    event.stopPropagation();
                    const isActive = dropdown.classList.contains('is-active');
                    adminDropdowns.forEach(d => {
                        if (d !== dropdown) {
                            d.classList.remove('is-active');
                        }
                    });
                    dropdown.classList.toggle('is-active');
                });
            }
        });
        window.addEventListener('click', function () {
            adminDropdowns.forEach(dropdown => {
                dropdown.classList.remove('is-active');
            });
        });
    }

    // --- MODAL DE DETALHES DOS POSTS (admin_posts.php) ---
    const postModal = document.getElementById('postModal');
    if (postModal) {
        const closeBtn = postModal.querySelector('.close-btn');
        const viewBtns = document.querySelectorAll('.btn-view');

        viewBtns.forEach(btn => {
            btn.addEventListener('click', function () {
                document.getElementById('modal-post-id').textContent = this.dataset.postId;
                document.getElementById('modal-author').textContent = this.dataset.authorName;
                document.getElementById('modal-date').textContent = this.dataset.createdAt;
                document.getElementById('modal-community').textContent = this.dataset.communityName;
                document.getElementById('modal-content').textContent = this.dataset.content;
                document.getElementById('modal-likes').textContent = this.dataset.likeCount;
                document.getElementById('modal-replies').textContent = this.dataset.replyCount;
                document.getElementById('modal-reposts').textContent = this.dataset.repostCount;
                document.getElementById('modal-quotes').textContent = this.dataset.quoteCount;

                const statusContainer = document.getElementById('modal-status');
                statusContainer.innerHTML = '';
                const type = this.dataset.type;
                if (type !== 'padrao') {
                    statusContainer.innerHTML += `<span class="status-badge status-info">${type.charAt(0).toUpperCase() + type.slice(1)} de #${this.dataset.parentId}</span> `;
                }
                if (this.dataset.contentWarning === '1') {
                    statusContainer.innerHTML += `<span class="status-badge status-warning">Aviso de Conteúdo</span>`;
                }
                if (statusContainer.innerHTML === '') {
                    statusContainer.innerHTML = `<span class="status-badge status-padrao">Padrão</span>`;
                }

                const tagsContainer = document.getElementById('modal-tags');
                tagsContainer.innerHTML = '';
                if (this.dataset.tags && this.dataset.tags !== 'Nenhuma') {
                    const tags = this.dataset.tags.split(', ');
                    tags.forEach(tag => {
                        const tagEl = document.createElement('span');
                        tagEl.className = 'tag';
                        tagEl.textContent = tag;
                        tagsContainer.appendChild(tagEl);
                    });
                } else {
                    tagsContainer.textContent = 'Nenhuma';
                }

                const mediaContainer = document.getElementById('modal-media');
                mediaContainer.innerHTML = '';
                if (this.dataset.media) {
                    mediaContainer.style.display = 'grid';
                    const mediaUrls = this.dataset.media.split(';').filter(url => url);
                    mediaContainer.dataset.count = mediaUrls.length;
                    mediaUrls.forEach(url => {
                        const img = document.createElement('img');
                        img.src = url.replace('../', '../../');
                        mediaContainer.appendChild(img);
                    });
                } else {
                    mediaContainer.style.display = 'none';
                    mediaContainer.dataset.count = 0;
                }

                postModal.style.display = 'flex';
            });
        });

        if (closeBtn) {
            closeBtn.onclick = function () {
                postModal.style.display = 'none';
            }
        }

        window.addEventListener('click', function (event) {
            if (event.target == postModal) {
                postModal.style.display = 'none';
            }
        });
    }

    // --- LÓGICA DO LIGHTBOX PARA MÍDIAS ---
    const lightbox = document.getElementById('imageLightbox');
    let openLightboxFunction = null; // Variável para guardar a função

    if (lightbox) {
        const lightboxImg = lightbox.querySelector('.lightbox-content');
        const lightboxClose = lightbox.querySelector('.lightbox-close');
        const prevBtn = lightbox.querySelector('.lightbox-nav.prev');
        const nextBtn = lightbox.querySelector('.lightbox-nav.next');
        let currentImages = [];
        let currentIndex = 0;

        function openLightbox(images, startIndex) {
            if (!images || images.length === 0) return;
            currentImages = images;
            currentIndex = startIndex;
            updateLightboxImage();
            lightbox.style.display = 'flex';
            if (currentImages.length > 1) {
                prevBtn.style.display = 'flex';
                nextBtn.style.display = 'flex';
            } else {
                prevBtn.style.display = 'none';
                nextBtn.style.display = 'none';
            }
        }
        openLightboxFunction = openLightbox; // Exporta a função para uso global

        function closeLightbox() { lightbox.style.display = 'none'; }
        function updateLightboxImage() { lightboxImg.src = currentImages[currentIndex]; }
        function showNextImage() {
            if (currentImages.length > 1) {
                currentIndex = (currentIndex + 1) % currentImages.length;
                updateLightboxImage();
            }
        }
        function showPrevImage() {
            if (currentImages.length > 1) {
                currentIndex = (currentIndex - 1 + currentImages.length) % currentImages.length;
                updateLightboxImage();
            }
        }

        lightboxClose.onclick = closeLightbox;
        prevBtn.onclick = showPrevImage;
        nextBtn.onclick = showNextImage;
        lightbox.addEventListener('click', (e) => {
            if (e.target === lightbox) closeLightbox();
        });
        document.addEventListener('keydown', (e) => {
            if (lightbox.style.display === 'flex') {
                if (e.key === 'ArrowRight') showNextImage();
                else if (e.key === 'ArrowLeft') showPrevImage();
                else if (e.key === 'Escape') closeLightbox();
            }
        });
    }

    // --- GERENCIADOR DE CLIQUES GERAL (DELEGAÇÃO DE EVENTOS) ---
    document.body.addEventListener('click', function (event) {
        const target = event.target;

        // 1. LÓGICA DE LIKES
        const likeButton = target.closest('.like-btn');
        if (likeButton) {
            event.preventDefault();
            event.stopPropagation();

            // Verifica se há o atributo de login no body (opcional, mas boa prática)
            // const isUserLoggedIn = document.body.dataset.isLoggedIn === 'true';
            // if (!isUserLoggedIn) { window.location.href = 'login.php'; return; }

            const postId = likeButton.dataset.postId;
            const icon = likeButton.querySelector('i');
            const countSpan = likeButton.querySelector('.post-cont');

            const data = new URLSearchParams();
            data.append('post_id', postId);

            fetch('api/api_like_post.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: data
            })
                .then(response => {
                    if (!response.ok) throw new Error('Erro na resposta da rede.');
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        countSpan.textContent = data.new_like_count;
                        if (data.liked) {
                            likeButton.classList.add('liked');
                            icon.classList.remove('ri-heart-line');
                            icon.classList.add('ri-heart-fill');
                        } else {
                            likeButton.classList.remove('liked');
                            icon.classList.remove('ri-heart-fill');
                            icon.classList.add('ri-heart-line');
                        }
                    } else {
                        console.error('Erro ao curtir:', data.error);
                    }
                })
                .catch(error => console.error('Erro na requisição:', error));
            return; // Encerra
        }

        // 2. LÓGICA DE REPOSTS
        const repostButton = target.closest('.repost-btn');
        if (repostButton) {
            event.preventDefault();
            event.stopPropagation();

            const postId = repostButton.dataset.postId;
            const icon = repostButton.querySelector('i');
            const countSpan = repostButton.querySelector('.post-cont');

            fetch('api/api_repost_post.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `post_id=${postId}`
            })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        countSpan.textContent = data.new_count;
                        if (data.reposted) {
                            repostButton.classList.add('reposted');
                            // Opcional: mudar cor ou ícone
                        } else {
                            repostButton.classList.remove('reposted');
                        }
                    } else {
                        console.error('Erro ao repostar:', data.error);
                    }
                });
            return;
        }

        // 3. LÓGICA DE SALVAR POSTS
        const bookmarkButton = target.closest('.bookmark-btn');
        if (bookmarkButton) {
            event.preventDefault();
            event.stopPropagation();

            const postId = bookmarkButton.dataset.postId;
            const icon = bookmarkButton.querySelector('i');
            const countSpan = bookmarkButton.querySelector('.post-cont');

            fetch('api/api_save_post.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `post_id=${postId}`
            })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        countSpan.textContent = data.new_count;
                        if (data.bookmarked) {
                            bookmarkButton.classList.add('bookmarked');
                            icon.classList.remove('ri-bookmark-line');
                            icon.classList.add('ri-bookmark-fill');
                        } else {
                            bookmarkButton.classList.remove('bookmarked');
                            icon.classList.remove('ri-bookmark-fill');
                            icon.classList.add('ri-bookmark-line');
                        }
                    } else {
                        console.error('Erro ao salvar:', data.error);
                    }
                });
            return;
        }

        // 4. LÓGICA DE DENÚNCIAS
        const reportButton = target.closest('.report-btn');
        if (reportButton) {
            event.preventDefault();
            event.stopPropagation();

            const postId = reportButton.dataset.postId;
            const motivo = prompt("Por favor, informe o motivo da denúncia:");

            if (motivo && motivo.trim() !== "") {
                fetch('api/api_report_post.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `post_id=${postId}&reason=${encodeURIComponent(motivo)}`
                })
                    .then(r => r.json())
                    .then(data => {
                        if (data.success) {
                            alert("Denúncia enviada com sucesso! Nossa equipe irá analisar.");
                        } else {
                            alert("Erro: " + data.error);
                        }
                    })
                    .catch(err => console.error("Erro na denúncia:", err));
            }
            return;
        }

        // 5. LÓGICA DO LIGHTBOX
        const clickedImage = target.closest('.post-media-grid img');
        if (clickedImage) {
            const mediaGrid = clickedImage.closest('.post-media-grid');
            const allImages = mediaGrid.querySelectorAll('img');
            const imagesSrc = Array.from(allImages).map(img => img.src);
            const startIndex = imagesSrc.indexOf(clickedImage.src);

            // Reutiliza a função openLightbox se ela existir
            if (openLightboxFunction) {
                openLightboxFunction(imagesSrc, startIndex);
            }
            return; // Encerra após abrir o lightbox
        }

        // 6. HIERARQUIA DE CLIQUES PARA NAVEGAÇÃO (POST CARD)
        const postCard = target.closest('.post_container[data-post-url]');
        // A condição a seguir já previne o clique em botões, links, etc.
        const isInteractive = target.closest('a, button, .post-media-grid');

        if (postCard && !isInteractive) {
            const postUrl = postCard.dataset.postUrl;
            if (postUrl) {
                window.location.href = postUrl;
            }
        }
    });

    // --- LÓGICA DE ENVIO DE COMENTÁRIOS (AJAX) ---
    const replyForm = document.getElementById('reply-form');

    if (replyForm) {
        replyForm.addEventListener('submit', function (event) {
            event.preventDefault(); // IMPEDE o envio padrão do formulário (que mostra o JSON)

            const submitButton = replyForm.querySelector('button[type="submit"]');
            const contentInput = replyForm.querySelector('textarea[name="content"]');
            const originalText = submitButton.innerText;

            if (contentInput.value.trim() === "") {
                alert("O comentário não pode ser vazio.");
                return;
            }

            // 1. Feedback visual de carregamento
            submitButton.disabled = true;
            submitButton.innerText = "Enviando...";

            const formData = new FormData(replyForm);

            fetch('api/api_reply_post.php', {
                method: 'POST',
                body: formData
            })
                .then(response => {
                    // Verifica se a resposta foi bem-sucedida (status 200-299)
                    if (!response.ok) {
                        throw new Error('Erro na rede ou no servidor.');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // 2. Se deu certo, recarrega a página para exibir o novo comentário
                        window.location.reload();
                    } else {
                        alert("Erro ao comentar: " + (data.error || "Erro desconhecido"));
                        submitButton.disabled = false;
                        submitButton.innerText = originalText;
                    }
                })
                .catch(error => {
                    console.error("Erro na requisição:", error);
                    alert("Ocorreu um erro ao enviar o comentário. Tente novamente.");
                    submitButton.disabled = false;
                    submitButton.innerText = originalText;
                });
        });
    }

});