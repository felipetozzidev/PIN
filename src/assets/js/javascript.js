// Espera o conteúdo da página carregar completamente antes de executar qualquer script.
document.addEventListener('DOMContentLoaded', function () {

    const isPublicPage = document.querySelector('.index-container, .view-post-container, .profile-container');
    if (isPublicPage) {

        const objetos = {
            navbar: document.querySelector(".navbar_container"),
            nav: document.querySelector("nav"),
            main: document.querySelector("main"),
            navbarMobile: document.querySelector("nav.navbar_mobile"),
            main_perfil: document.querySelector("main.main_perfil"), // Correção seletor perfil
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
            ifcelular: function () { return window.screen.width < 768; },
            tamanhoNavbarCelular: function () { return window.screen.width < 768 ? this.navbarMobile.clientHeight : 0; },
            // --- Adicionando os seletores do modal aqui ---
            createPostButton: document.querySelector('#create_post'),
            modalContainer: document.querySelector('main.modal_container')
        };

        // --- Ajustes de Layout ---
        if (objetos.main && objetos.nav) {
            objetos.main.style.marginTop = `${objetos.tamanhoCabecalho()}px`;
            if (objetos.tamanhoFooter() > 0) {
                objetos.main.style.marginBottom = `${objetos.tamanhoFooter()}px`;
            } else if (objetos.tamanhoNavbarCelular() > 0) {
                objetos.main.style.marginBottom = `${objetos.tamanhoNavbarCelular() + 10}px`;
            }
        }

        if (objetos.ifcelular() && document.querySelector("main.main_perfil")) {
            document.querySelector("body > main > section.main_container > div > div.profile_header > div > div.profile_options > a").innerHTML = "<i class='ri-edit-line'></i>";
        }

        // --- Correção do Erro do Console (Página de Perfil) ---
        if (objetos.main_perfil) {
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
        if (objetos.ifcelular()) {
            console.log(objetos.tamanhoNavbarCelular());

            objetos.main.marginBottom = `${objetos.tamanhoNavbarCelular()}px`;
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

        // --- LÓGICA DE ABERTURA DO MODAL DE POSTAGEM ---
        if (objetos.createPostButton && objetos.modalContainer) {
            objetos.createPostButton.addEventListener('click', function (event) {
                event.preventDefault();
                objetos.modalContainer.classList.add('active');
                document.body.style.overflow = 'hidden';
            });
        }
    } // Fim do if (isPublicPage)

    // --- LÓGICA DO MENU MOBILE ---
    const btnMobile = document.getElementById('btn-mobile');
    function toggleMenu(event) {
        if (event.type === 'touchstart') event.preventDefault();
        const nav = document.getElementById('nav');
        nav.classList.toggle('active');
        const active = nav.classList.contains('active');
        event.currentTarget.setAttribute('aria-expanded', active);
        if (active) {
            event.currentTarget.setAttribute('aria-label', 'Fechar Menu');
        } else {
            event.currentTarget.setAttribute('aria-label', 'Abrir Menu');
        }
    }
    if (btnMobile) {
        btnMobile.addEventListener('click', toggleMenu);
        btnMobile.addEventListener('touchstart', toggleMenu);
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

    // --- LÓGICA DO LIGHTBOX PARA MÍDIAS ---
    const lightbox = document.getElementById('imageLightbox');
    let openLightboxFunction = null;

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
        openLightboxFunction = openLightbox;

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
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        countSpan.textContent = data.new_like_count;
                        if (data.liked) {
                            likeButton.classList.add('liked');
                            icon.className = 'ri-heart-fill';
                        } else {
                            likeButton.classList.remove('liked');
                            icon.className = 'ri-heart-line';
                        }
                    }
                })
                .catch(error => console.error('Erro:', error));
            return;
        }

        // 2. LÓGICA DE REPOST (NOVO)
        const repostButton = target.closest('.repost-btn');
        if (repostButton) {
            event.preventDefault();
            event.stopPropagation();

            const postId = repostButton.dataset.postId;
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
                        } else {
                            repostButton.classList.remove('reposted');
                        }
                    }
                });
            return;
        }

        // 3. LÓGICA DE SALVAR (BOOKMARK) (NOVO)
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
                            icon.className = 'ri-bookmark-fill';
                        } else {
                            bookmarkButton.classList.remove('bookmarked');
                            icon.className = 'ri-bookmark-line';
                        }
                    }
                });
            return;
        }

        // 4. LÓGICA DE DENÚNCIA (ABERTURA DO MODAL) (NOVO)
        const reportButton = target.closest('.report-btn');
        if (reportButton) {
            event.preventDefault();
            event.stopPropagation();

            const postId = reportButton.dataset.postId;
            const reportModal = document.getElementById('reportModal');
            const reportPostIdInput = document.getElementById('reportPostId');

            if (reportModal && reportPostIdInput) {
                reportPostIdInput.value = postId; // Define o ID do post no campo oculto
                reportModal.classList.add('active'); // Abre o modal
                document.body.style.overflow = 'hidden';
            } else {
                console.error("Modal de denúncia não encontrado.");
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

            if (openLightboxFunction) {
                openLightboxFunction(imagesSrc, startIndex);
            }
            return;
        }

        // 6. HIERARQUIA DE CLIQUES PARA NAVEGAÇÃO
        const postCard = target.closest('.post_container[data-post-url]');
        const isInteractive = target.closest('a, button, .post-media-grid');

        if (postCard && !isInteractive) {
            window.location.href = postCard.dataset.postUrl;
        }
    });

    // --- LÓGICA DO MODAL DE DENÚNCIA (SUBMISSÃO VIA AJAX) (NOVO) ---
    const reportModal = document.getElementById('reportModal');
    const reportForm = document.getElementById('reportForm');
    const closeReportModalBtn = document.getElementById('closeReportModal');

    function closeReportModal() {
        reportModal.classList.remove('active');
        document.body.style.overflow = 'auto';
        reportForm.reset();
    }

    if (reportModal && reportForm && closeReportModalBtn) {
        closeReportModalBtn.addEventListener('click', closeReportModal);

        reportModal.addEventListener('click', (e) => {
            if (e.target.id === 'reportModal') {
                closeReportModal();
            }
        });

        reportForm.addEventListener('submit', function (e) {
            e.preventDefault();

            const reasonSelect = document.getElementById('reportReason');
            const reasonDetails = document.getElementById('reportDetails');
            const submitBtn = document.getElementById('submitReportBtn');
            const postId = document.getElementById('reportPostId').value;

            const selectedReason = reasonSelect.value;
            const detailedReason = reasonDetails.value.trim();

            if (!selectedReason) {
                alert("Selecione um motivo."); return;
            }

            let finalReason = selectedReason;
            if (detailedReason) finalReason += ` | Detalhes: ${detailedReason}`;

            submitBtn.disabled = true;
            submitBtn.innerText = 'Enviando...';

            fetch('api/api_report_post.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `target_id=${postId}&target_type=post&reason=${encodeURIComponent(finalReason)}`
            })
                .then(r => r.json())
                .then(data => {
                    alert(data.message);
                    if (data.success) closeReportModal();
                    submitBtn.disabled = false;
                    submitBtn.innerText = 'Enviar Denúncia';
                })
                .catch(() => {
                    alert("Erro ao enviar.");
                    submitBtn.disabled = false;
                    submitBtn.innerText = 'Enviar Denúncia';
                });
        });
    }
});