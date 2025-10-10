<?php
require_once('../../config/conn.php');
require_once('../../config/log_helper.php');
include('admin_header.php');

$feedback_message = '';
$post_to_view_json = null;

// LÓGICA PARA ABRIR MODAL VIA URL
if (isset($_GET['view_post_id'])) {
    $post_id_to_view = intval($_GET['view_post_id']);

    $sql_single = "SELECT 
                        p.id_post, p.conteudo_post, p.data_post, p.stats_post, p.tipo_post, 
                        p.id_post_pai, p.aviso_conteudo,
                        p.cont_likes, p.cont_respostas, p.cont_reposts, p.cont_citacoes,
                        u.nome_usu,
                        c.nome_com,
                        GROUP_CONCAT(DISTINCT t.nome_tag SEPARATOR ', ') as tags,
                        (SELECT GROUP_CONCAT(pm.url_media SEPARATOR ';') FROM post_media pm WHERE pm.id_post = p.id_post) as media_urls
                    FROM posts p 
                    LEFT JOIN usuarios u ON p.id_usu = u.id_usu
                    LEFT JOIN comunidades_posts cp ON p.id_post = cp.id_post
                    LEFT JOIN comunidades c ON cp.id_com = c.id_com
                    LEFT JOIN posts_tags pt ON p.id_post = pt.id_post
                    LEFT JOIN tags t ON pt.id_tag = t.id_tag
                    WHERE p.id_post = ?
                    GROUP BY p.id_post";

    $stmt_single = $conn->prepare($sql_single);
    $stmt_single->bind_param("i", $post_id_to_view);
    $stmt_single->execute();
    $result_single = $stmt_single->get_result();
    if ($result_single->num_rows > 0) {
        $post_to_view = $result_single->fetch_assoc();
        $post_to_view['data_post_formatted'] = date("d/m/Y H:i", strtotime($post_to_view['data_post']));
        $post_to_view_json = json_encode($post_to_view);
    }
    $stmt_single->close();
}

// Lógica para apagar post
if (isset($_GET['delete_id'])) {
    $id_para_deletar = intval($_GET['delete_id']);
    $admin_user_name = $_SESSION['nome_usu'] ?? 'Administrador';

    $stmt = $conn->prepare("DELETE FROM posts WHERE id_post = ?");
    $stmt->bind_param("i", $id_para_deletar);
    if ($stmt->execute()) {
        $feedback_message = "<p class='success-message'>Post apagado com sucesso!</p>";
        log_activity($conn, 'Post Deletado', $admin_user_name, "Post (ID: #{$id_para_deletar}) foi deletado pelo painel de administração.");
    } else {
        $feedback_message = "<p class='error-message'>Erro ao apagar o post.</p>";
    }
    $stmt->close();
}

// Lógica de busca e filtros
$search_query = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';

$sql = "SELECT 
            p.id_post, p.conteudo_post, p.data_post, p.stats_post, p.tipo_post, 
            p.id_post_pai, p.aviso_conteudo,
            p.cont_likes, p.cont_respostas, p.cont_reposts, p.cont_citacoes,
            u.nome_usu,
            c.nome_com,
            GROUP_CONCAT(DISTINCT t.nome_tag SEPARATOR ', ') as tags,
            (SELECT GROUP_CONCAT(pm.url_media SEPARATOR ';') FROM post_media pm WHERE pm.id_post = p.id_post) as media_urls
        FROM posts p 
        LEFT JOIN usuarios u ON p.id_usu = u.id_usu
        LEFT JOIN comunidades_posts cp ON p.id_post = cp.id_post
        LEFT JOIN comunidades c ON cp.id_com = c.id_com
        LEFT JOIN posts_tags pt ON p.id_post = pt.id_post
        LEFT JOIN tags t ON pt.id_tag = t.id_tag
        ";

$where_clauses = [];
if (!empty($search_query)) {
    $where_clauses[] = "(p.conteudo_post LIKE '%$search_query%' OR u.nome_usu LIKE '%$search_query%' OR c.nome_com LIKE '%$search_query%' OR t.nome_tag LIKE '%$search_query%')";
}

switch ($filter) {
    case 'respostas':
        $where_clauses[] = "p.tipo_post = 'resposta'";
        break;
    case 'citacoes':
        $where_clauses[] = "p.tipo_post = 'citacao'";
        break;
    case 'aviso':
        $where_clauses[] = "p.aviso_conteudo = 1";
        break;
    case 'padrao':
        $where_clauses[] = "p.tipo_post = 'padrao' AND p.aviso_conteudo = 0";
        break;
}

if (!empty($where_clauses)) {
    $sql .= " WHERE " . implode(' AND ', $where_clauses);
}

$sql .= " GROUP BY p.id_post ORDER BY p.id_post DESC";
$resultado = $conn->query($sql);
?>

<main class="container">
    <div class="page-header">
        <h1>Posts</h1>
        <form action="admin_posts.php" method="GET" class="search-form">
            <input type="hidden" name="filter" value="<?php echo htmlspecialchars($filter); ?>">
            <input type="search" name="search" placeholder="Buscar por conteúdo, autor, comunidade, tag..." value="<?php echo htmlspecialchars($search_query); ?>">
            <button type="submit" class="btn btn-primary"><i class="ri-search-line"></i></button>
        </form>
    </div>

    <div class="filters">
        <a href="admin_posts.php?filter=all" class="btn <?php echo ($filter == 'all') ? 'btn-primary' : ''; ?>">Todos</a>
        <a href="admin_posts.php?filter=padrao" class="btn <?php echo ($filter == 'padrao') ? 'btn-primary' : ''; ?>">Padrão</a>
        <a href="admin_posts.php?filter=respostas" class="btn <?php echo ($filter == 'respostas') ? 'btn-primary' : ''; ?>">Respostas</a>
        <a href="admin_posts.php?filter=citacoes" class="btn <?php echo ($filter == 'citacoes') ? 'btn-primary' : ''; ?>">Citações</a>
        <a href="admin_posts.php?filter=aviso" class="btn <?php echo ($filter == 'aviso') ? 'btn-primary' : ''; ?>">Com Aviso</a>
    </div>

    <?php echo $feedback_message; ?>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Autor</th>
                    <th>Contadores</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($resultado && $resultado->num_rows > 0): ?>
                    <?php while ($post = $resultado->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $post['id_post']; ?></td>
                            <td><?php echo htmlspecialchars($post['nome_usu'] ?? 'Apagado'); ?></td>
                            <td>
                                <i class="ri-heart-line" title="Likes"></i> <?php echo $post['cont_likes']; ?> |
                                <i class="ri-chat-3-line" title="Respostas"></i> <?php echo $post['cont_respostas']; ?> |
                                <i class="ri-repeat-line" title="Reposts"></i> <?php echo $post['cont_reposts']; ?> |
                                <i class="ri-chat-quote-line" title="Citações"></i> <?php echo $post['cont_citacoes']; ?>
                            </td>
                            <td>
                                <?php
                                $status_exibido = false;
                                if ($post['tipo_post'] != 'padrao'): ?>
                                    <span class="status-badge status-info"><?php echo ucfirst($post['tipo_post']); ?> de #<?php echo $post['id_post_pai']; ?></span>
                                <?php $status_exibido = true;
                                endif; ?>
                                <?php if ($post['aviso_conteudo']): ?>
                                    <span class="status-badge status-warning">Aviso</span>
                                <?php $status_exibido = true;
                                endif; ?>
                                <?php if (!$status_exibido): ?>
                                    <span class="status-badge status-padrao">Padrão</span>
                                <?php endif; ?>
                            </td>
                            <td class="actions">
                                <button class="btn btn-icon btn-view" title="Ver Detalhes"
                                    data-id="<?php echo $post['id_post']; ?>"
                                    data-content="<?php echo htmlspecialchars($post['conteudo_post']); ?>"
                                    data-author="<?php echo htmlspecialchars($post['nome_usu'] ?? 'Apagado'); ?>"
                                    data-date="<?php echo date("d/m/Y H:i", strtotime($post['data_post'])); ?>"
                                    data-community="<?php echo htmlspecialchars($post['nome_com'] ?? 'N/A'); ?>"
                                    data-tags="<?php echo htmlspecialchars($post['tags'] ?? 'Nenhuma'); ?>"
                                    data-media="<?php echo htmlspecialchars($post['media_urls'] ?? ''); ?>"
                                    data-tipo="<?php echo htmlspecialchars($post['tipo_post']); ?>"
                                    data-pai="<?php echo htmlspecialchars($post['id_post_pai'] ?? 'N/A'); ?>"
                                    data-aviso="<?php echo $post['aviso_conteudo']; ?>"
                                    data-likes="<?php echo $post['cont_likes']; ?>"
                                    data-respostas="<?php echo $post['cont_respostas']; ?>"
                                    data-reposts="<?php echo $post['cont_reposts']; ?>"
                                    data-citacoes="<?php echo $post['cont_citacoes']; ?>">
                                    <i class="ri-eye-line"></i>
                                </button>
                                <a href="admin_posts.php?delete_id=<?php echo $post['id_post']; ?>" onclick="return confirm('Tem a certeza que deseja apagar este post? Esta ação é irreversível.');" class="btn btn-icon btn-delete" title="Apagar">
                                    <i class="ri-delete-bin-line"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">Nenhum post encontrado para os critérios selecionados.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>

<!-- Modal para Detalhes do Post -->
<div id="postModal" class="modal">
    <div class="modal-content">
        <span class="close-btn">&times;</span>
        <div class="modal-header">
            <h2>Detalhes do Post #<span id="modal-post-id"></span></h2>
        </div>
        <div class="modal-body">
            <div class="modal-details-grid">
                <div><strong>Autor:</strong> <span id="modal-author"></span></div>
                <div><strong>Data:</strong> <span id="modal-date"></span></div>
                <div><strong>Comunidade:</strong> <span id="modal-community"></span></div>
                <div><strong>Status:</strong> <span id="modal-status"></span></div>
            </div>
            <p><strong>Conteúdo:</strong></p>
            <p id="modal-content"></p>
            <div class="modal-media" id="modal-media"></div>
            <div class="modal-footer-details">
                <div class="tag-list">
                    <strong>Tags:</strong> <span id="modal-tags"></span>
                </div>
                <div class="stats-list">
                    <span><i class="ri-heart-line" title="Likes"></i> <span id="modal-likes"></span></span>
                    <span><i class="ri-chat-3-line" title="Respostas"></i> <span id="modal-respostas"></span></span>
                    <span><i class="ri-repeat-line" title="Reposts"></i> <span id="modal-reposts"></span></span>
                    <span><i class="ri-chat-quote-line" title="Citações"></i> <span id="modal-citacoes"></span></span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Lightbox Modal para Visualização de Imagem (HTML ATUALIZADO) -->
<div id="imageLightbox" class="lightbox">
    <span class="lightbox-close">&times;</span>
    <a class="lightbox-nav prev">&#10094;</a>
    <img class="lightbox-content" id="lightboxImage">
    <a class="lightbox-nav next">&#10095;</a>
</div>

<!-- Script para o Modal e Lightbox -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- Lógica do Modal de Detalhes ---
        const modal = document.getElementById('postModal');
        const closeBtn = document.querySelector('.modal .close-btn');
        const viewBtns = document.querySelectorAll('.btn-view');

        function openPostModal(data) {
            // (código para preencher o modal, sem alterações)
            document.getElementById('modal-post-id').textContent = data.id_post || data.id;
            document.getElementById('modal-author').textContent = data.nome_usu || data.author || 'Apagado';
            document.getElementById('modal-date').textContent = data.data_post_formatted || data.date;
            document.getElementById('modal-community').textContent = data.nome_com || data.community || 'N/A';
            document.getElementById('modal-content').textContent = data.conteudo_post || data.content;
            document.getElementById('modal-likes').textContent = data.cont_likes;
            document.getElementById('modal-respostas').textContent = data.cont_respostas;
            document.getElementById('modal-reposts').textContent = data.cont_reposts;
            document.getElementById('modal-citacoes').textContent = data.cont_citacoes;
            const statusContainer = document.getElementById('modal-status');
            statusContainer.innerHTML = '';
            const tipo = data.tipo_post || data.tipo;
            if (tipo !== 'padrao') {
                statusContainer.innerHTML += `<span class="status-badge status-info">${tipo.charAt(0).toUpperCase() + tipo.slice(1)} de #${data.id_post_pai || data.pai}</span> `;
            }
            if (data.aviso_conteudo == '1') {
                statusContainer.innerHTML += `<span class="status-badge status-warning">Aviso de Conteúdo</span>`;
            }
            if (statusContainer.innerHTML === '') {
                statusContainer.innerHTML = `<span class="status-badge status-padrao">Padrão</span>`;
            }
            const tagsContainer = document.getElementById('modal-tags');
            tagsContainer.innerHTML = '';
            const tagsData = data.tags || 'Nenhuma';
            if (tagsData && tagsData !== 'Nenhuma') {
                const tags = tagsData.split(', ');
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
            const mediaData = data.media_urls || data.media;
            if (mediaData) {
                mediaContainer.style.display = 'grid';
                const mediaUrls = mediaData.split(';').filter(url => url);
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
            modal.style.display = 'flex';
        }

        viewBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const postData = {
                    id: this.dataset.id,
                    content: this.dataset.content,
                    author: this.dataset.author,
                    date: this.dataset.date,
                    community: this.dataset.community,
                    tags: this.dataset.tags,
                    media: this.dataset.media,
                    tipo: this.dataset.tipo,
                    pai: this.dataset.pai,
                    aviso_conteudo: this.dataset.aviso,
                    cont_likes: this.dataset.likes,
                    cont_respostas: this.dataset.respostas,
                    cont_reposts: this.dataset.reposts,
                    cont_citacoes: this.dataset.citacoes
                };
                openPostModal(postData);
            });
        });

        closeBtn.onclick = () => modal.style.display = 'none';
        window.addEventListener('click', (event) => {
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        });

        // --- LÓGICA DO LIGHTBOX (JAVASCRIPT ATUALIZADO) ---
        const lightbox = document.getElementById('imageLightbox');
        const lightboxImg = document.getElementById('lightboxImage');
        const lightboxClose = document.querySelector('.lightbox-close');
        const detailsMediaContainer = document.getElementById('modal-media');
        const prevBtn = document.querySelector('.lightbox-nav.prev');
        const nextBtn = document.querySelector('.lightbox-nav.next');

        let currentImages = [];
        let currentIndex = 0;

        detailsMediaContainer.addEventListener('click', function(event) {
            if (event.target.tagName === 'IMG') {
                const allImages = detailsMediaContainer.querySelectorAll('img');
                currentImages = Array.from(allImages).map(img => img.src);
                currentIndex = currentImages.indexOf(event.target.src);
                openLightbox();
            }
        });

        function openLightbox() {
            if (currentImages.length === 0) return;
            lightboxImg.src = currentImages[currentIndex];
            lightbox.style.display = 'flex';
            if (currentImages.length > 1) {
                prevBtn.style.display = 'flex';
                nextBtn.style.display = 'flex';
            } else {
                prevBtn.style.display = 'none';
                nextBtn.style.display = 'none';
            }
        }

        function closeLightbox() {
            lightbox.style.display = 'none';
        }

        function showNextImage() {
            if (currentImages.length > 1) {
                currentIndex = (currentIndex + 1) % currentImages.length;
                lightboxImg.src = currentImages[currentIndex];
            }
        }

        function showPrevImage() {
            if (currentImages.length > 1) {
                currentIndex = (currentIndex - 1 + currentImages.length) % currentImages.length;
                lightboxImg.src = currentImages[currentIndex];
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

        // --- Lógica para abrir modal via URL ---
        <?php if (isset($post_to_view_json)): ?>
            const postDataFromUrl = <?php echo $post_to_view_json; ?>;
            openPostModal(postDataFromUrl);
        <?php endif; ?>
    });
</script>

<?php
include 'admin_footer.php';
$conn->close();
?>