// Espera o conteúdo da página carregar completamente antes de executar qualquer script.
document.addEventListener('DOMContentLoaded', function () {

    const isPublicPage = document.querySelector('.index-container, .view-post-container');
    if (isPublicPage) {

        const objetos = {
            navbar: document.querySelector(".navbar_container"),
            nav: document.querySelector("nav"),
            main: document.querySelector("main"),
            footer: document.querySelector("footer.pag_footer"), // Usar seletor genérico
            body: document.querySelector("body"),
            community_cards_container: document.querySelector("div.community_cards_container"),
            tamanhoCabecalho: function() { return this.nav ? this.nav.clientHeight : 0; },
            tamanhoBody: function () { return this.body ? this.body.clientHeight : 0; },
            paddingCabecalho: function() { return this.main ? getComputedStyle(this.main).paddingTop : '0px'; },
            tamanhoFooter: function() { return this.footer ? this.footer.clientHeight : 0; },
            dropdownItens: function() { return document.querySelectorAll(".dropdown_item"); }
        };

        // --- Ajustes de Layout ---
        if (objetos.main && objetos.nav) {
            objetos.main.style.marginTop = `${objetos.tamanhoCabecalho()}px`;
            if (objetos.footer) {
                objetos.main.style.marginBottom = `${objetos.tamanhoFooter()}px`;
            }
        }
        console.log(objetos.tamanhoCabecalho() + " + " + objetos.tamanhoBody() + " < " + window.screen.height);
        if(objetos.tamanhoCabecalho() + objetos.tamanhoBody() < window.screen.height){
            objetos.footer.style.position = "absolute";
            objetos.footer.style.bottom = "0px";
        }
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
                // Adiciona verificação para evitar erros em separadores <hr>
                if (tag_a_selection) {
                    const createIconDropdown = document.createElement("img");
                    createIconDropdown.setAttribute("src", "../src/assets/icons/arrow_down.svg");
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
    }

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
                document.getElementById('modal-post-id').textContent = this.dataset.id;
                document.getElementById('modal-author').textContent = this.dataset.author;
                document.getElementById('modal-date').textContent = this.dataset.date;
                document.getElementById('modal-community').textContent = this.dataset.community;
                document.getElementById('modal-content').textContent = this.dataset.content;
                document.getElementById('modal-likes').textContent = this.dataset.likes;
                document.getElementById('modal-respostas').textContent = this.dataset.respostas;
                document.getElementById('modal-reposts').textContent = this.dataset.reposts;
                document.getElementById('modal-citacoes').textContent = this.dataset.citacoes;

                const statusContainer = document.getElementById('modal-status');
                statusContainer.innerHTML = '';
                const tipo = this.dataset.tipo;
                if (tipo !== 'padrao') {
                    statusContainer.innerHTML += `<span class="status-badge status-info">${tipo.charAt(0).toUpperCase() + tipo.slice(1)} de #${this.dataset.pai}</span> `;
                }
                if (this.dataset.aviso === '1') {
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

        closeBtn.onclick = function () {
            postModal.style.display = 'none';
        }

        window.addEventListener('click', function (event) {
            if (event.target == postModal) {
                postModal.style.display = 'none';
            }
        });
    }

    // --- LÓGICA DE LIKES (para index.php e post_view.php) ---
    const likeButtons = document.querySelectorAll('.like-btn');
    if (likeButtons.length > 0) {
        const isUserLoggedIn = document.body.dataset.isLoggedIn === 'true';
        likeButtons.forEach(button => {
            button.addEventListener('click', function (event) {
                // Impede que o clique no botão de like acione o link do card pai.
                event.stopPropagation();

                if (!isUserLoggedIn) {
                    window.location.href = 'login.php';
                    return;
                }
                const postId = this.dataset.postId;
                const icon = this.querySelector('i');
                const countSpan = this.querySelector('.post-cont');
                const data = new URLSearchParams();
                data.append('id_post', postId);
                fetch('api_like_post.php', {
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
                                this.classList.add('liked');
                                icon.classList.remove('ri-heart-line');
                                icon.classList.add('ri-heart-fill');
                            } else {
                                this.classList.remove('liked');
                                icon.classList.remove('ri-heart-fill');
                                icon.classList.add('ri-heart-line');
                            }
                        } else {
                            console.error('Erro ao curtir:', data.error);
                        }
                    })
                    .catch(error => console.error('Erro na requisição:', error));
            });
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

        // Adiciona o listener de clique ao corpo do documento para delegar o evento
        document.body.addEventListener('click', function (event) {
            const clickedImage = event.target;
            if (clickedImage.tagName === 'IMG' && clickedImage.closest('.post-media-grid')) {
                const mediaGrid = clickedImage.closest('.post-media-grid');
                const allImages = mediaGrid.querySelectorAll('img');
                const imagesSrc = Array.from(allImages).map(img => img.src);
                const startIndex = imagesSrc.indexOf(clickedImage.src);
                openLightbox(imagesSrc, startIndex);
            }
        });
    }

    // --- HIERARQUIA DE CLIQUES (CORRIGIDO) ---
    document.body.addEventListener('click', function (event) {
        const target = event.target;

        // Elementos que têm ações próprias e não devem redirecionar o card.
        const isInteractive = target.closest('.like-btn, .post-media-grid, a, button');

        // // Click no .user_info (redireciona para o perfil)
        // const userInfo = target.closest('.user_info');
        // if (userInfo) {
        //     const userId = userInfo.dataset.userId;
        //     if (userId) {
        //         window.location.href = `perfil.php?id=${userId}`;
        //     }
        //     return; // Encerra a função aqui, pois a ação principal foi executada.
        // }

        // Ação: Clicou em um card de post no index.php
        const postCard = target.closest('.post_container[data-post-url]');
        if (postCard && !isInteractive) { // Só redireciona se não for um elemento interativo
            const postUrl = postCard.dataset.postUrl;
            if (postUrl) {
                window.location.href = postUrl;
            }
        }
    });
});