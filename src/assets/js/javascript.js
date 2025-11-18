// Espera o conteúdo da página carregar completamente antes de executar qualquer script.
document.addEventListener('DOMContentLoaded', function () {

    const isPublicPage = document.querySelector('.index-container, .view-post-container, .profile-container');
    if (isPublicPage) {

        const objetos = {
            navbar: document.querySelector(".navbar_container"),
            nav: document.querySelector("nav"),
            main: document.querySelector("main"),
            main_perfil: document.querySelector("main.main_perfil"),
            footer: document.querySelector("footer.pag_footer"),
            navbar_mobile: document.querySelector("nav.navbar_mobile"),
            body: document.querySelector("body"),
            community_cards_container: document.querySelector("div.community_cards_container"),
            tamanhoCabecalho: function () { return this.nav ? this.nav.clientHeight : 0; },
            tamanhoBody: function () { return this.body ? this.body.clientHeight : 0; },
            paddingCabecalho: function () { return this.main ? getComputedStyle(this.main).paddingTop : '0px'; },
            tamanhoFooter: function () { return this.footer ? this.footer.clientHeight : 0; },
            dropdownItens: function () { return document.querySelectorAll(".dropdown_item"); },
            tamanhoMain: function () { return this.main ? this.main.clientHeight : 0; },
            // --- Adicionando os seletores do modal aqui ---
            createPostButton: document.querySelector('#create_post'),     // <-- MUDANÇA AQUI
            modalContainer: document.querySelector('section.modal_container') // <-- MUDANÇA AQUI
        };

        // --- código simples para centralização reponsiva do botao de criação de postagem ---


        window.addEventListener('resize', function () {
            if (objetos.navbar_mobile && objetos.createPostButton) {
                let larguraNavbarMobile = objetos.navbar_mobile.offsetWidth;
                let larguraBotao = objetos.createPostButton.offsetWidth;
                objetos.createPostButton.style.left = `calc(${larguraNavbarMobile / 2}px - ${larguraBotao / 2}px)`;
                objetos.createPostButton.style.transform = "translateX(0)";
            }
        });


        // --- Ajustes de Layout ---
        if (objetos.main && objetos.nav) {
            objetos.main.style.marginTop = `${objetos.tamanhoCabecalho()}px`;
            if (objetos.footer) {
                objetos.main.style.marginBottom = `${objetos.tamanhoFooter()}px`;
            }
        }

        if (objetos.main_perfil) {
            console.log(objetos.footer && objetos.nav && objetos.tamanhoBody() - objetos.tamanhoFooter() - objetos.tamanhoCabecalho() > objetos.tamanhoMain());

            if (objetos.footer && objetos.nav && objetos.tamanhoBody() - objetos.tamanhoFooter() - objetos.tamanhoCabecalho() > objetos.tamanhoMain()) {
                console.log(objetos.main_perfil.querySelector("section.main_container > div.main_content"));

                objetos.main_perfil.querySelector("section.main_container > div.main_content").classList.add("fixo");
            }
        }


        // if (objetos.footer && (objetos.tamanhoCabecalho() + objetos.tamanhoBody() < window.screen.height)) {
        //     objetos.footer.style.position = "relative";
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

        // --- LÓGICA DE ABERTURA DO MODAL (Agora no lugar certo) ---
        if (objetos.createPostButton && objetos.modalContainer) { // <-- MUDANÇA AQUI
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
        const likeButton = target.closest('.like-btn');

        // --- LÓGICA DE LIKES ---
        if (likeButton) {
            event.preventDefault();
            event.stopPropagation();

            const isUserLoggedIn = document.body.dataset.isLoggedIn === 'true';
            if (!isUserLoggedIn) {
                window.location.href = 'login.php';
                return;
            }

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
            return; // Encerra após a ação de like
        }

        // --- LÓGICA DO LIGHTBOX ---
        const clickedImage = target.closest('.post-media-grid img');
        if (clickedImage) {
            const mediaGrid = clickedImage.closest('.post-media-grid');
            const allImages = mediaGrid.querySelectorAll('img');
            const imagesSrc = Array.from(allImages).map(img => img.src);
            const startIndex = imagesSrc.indexOf(clickedImage.src);

            // Reutiliza a função openLightbox se ela existir
            if (typeof openLightbox === 'function') {
                openLightbox(imagesSrc, startIndex);
            }
            return; // Encerra após abrir o lightbox
        }

        // --- HIERARQUIA DE CLIQUES PARA NAVEGAÇÃO ---
        const postCard = target.closest('.post_container[data-post-url]');
        // A condição a seguir já previne o clique em botões, links, etc.
        const isInteractive = target.closest('a, button');

        if (postCard && !isInteractive) {
            const postUrl = postCard.dataset.postUrl;
            if (postUrl) {
                window.location.href = postUrl;
            }
        }
    });

    // --- BLOCOS DE CÓDIGO DUPLICADOS REMOVIDOS ---
    // Os blocos de código que estavam aqui (linhas 329-348 do seu arquivo original)
    // foram removidos para evitar conflitos. A lógica correta foi movida
    // para dentro do objeto 'objetos' e do 'if (isPublicPage)' no topo do arquivo.

});