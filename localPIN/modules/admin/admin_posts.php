<?php
// O cabeçalho já inicia a sessão e faz a conexão via PDO
require_once('admin_header.php');
require_once('../../config/log_helper.php');

$feedback_message = '';
$post_to_view_json = null;
$admin_user_id = $_SESSION['user_id'];
$admin_user_name = $_SESSION['full_name'] ?? 'Administrador';

// LÓGICA PARA ABRIR MODAL VIA URL
if (isset($_GET['view_post_id'])) {
    $post_id_to_view = intval($_GET['view_post_id']);
    $sql_single = "SELECT 
                        p.post_id, p.content, p.created_at, p.status, p.type, 
                        p.content_warning, p.like_count, p.reply_count, p.repost_count, p.bookmark_count,
                        u.full_name,
                        c.name as community_name,
                        GROUP_CONCAT(DISTINCT t.name SEPARATOR ', ') as tags,
                        (SELECT GROUP_CONCAT(pm.media_url SEPARATOR ';') FROM post_media pm WHERE pm.post_id = p.post_id) as media_urls
                    FROM posts p 
                    LEFT JOIN users u ON p.user_id = u.user_id
                    LEFT JOIN community_posts cp ON p.post_id = cp.post_id
                    LEFT JOIN communities c ON cp.community_id = c.community_id
                    LEFT JOIN post_tags pt ON p.post_id = pt.post_id
                    LEFT JOIN tags t ON pt.tag_id = t.tag_id
                    WHERE p.post_id = ?
                    GROUP BY p.post_id";

    $stmt_single = $pdo->prepare($sql_single);
    $stmt_single->execute([$post_id_to_view]);
    if ($post_to_view = $stmt_single->fetch(PDO::FETCH_ASSOC)) {
        $post_to_view['created_at_formatted'] = date("d/m/Y H:i", strtotime($post_to_view['created_at']));
        $post_to_view_json = json_encode($post_to_view);
    }
}

// Lógica para apagar post
if (isset($_GET['delete_id'])) {
    $id_to_delete = intval($_GET['delete_id']);
    $stmt = $pdo->prepare("DELETE FROM posts WHERE post_id = ?");
    if ($stmt->execute([$id_to_delete])) {
        $feedback_message = "<p class='success-message'>Post apagado com sucesso!</p>";
        logAction($pdo, 'Post Deletado', $admin_user_name, "Post (ID: #{$id_to_delete}) foi deletado pelo painel de administração.", $admin_user_id);
    } else {
        $feedback_message = "<p class='error-message'>Erro ao apagar o post.</p>";
    }
}

// Lógica de busca e filtros
$search_query = isset($_GET['search']) ? trim($_GET['search']) : '';
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';

$sql = "SELECT 
            p.post_id, p.content, p.created_at, p.status, p.type, 
            p.content_warning, p.like_count, p.reply_count, p.repost_count, p.bookmark_count,
            u.full_name,
            c.name as community_name,
            GROUP_CONCAT(DISTINCT t.name SEPARATOR ', ') as tags,
            (SELECT GROUP_CONCAT(pm.media_url SEPARATOR ';') FROM post_media pm WHERE pm.post_id = p.post_id) as media_urls
        FROM posts p 
        LEFT JOIN users u ON p.user_id = u.user_id
        LEFT JOIN community_posts cp ON p.post_id = cp.post_id
        LEFT JOIN communities c ON cp.community_id = c.community_id
        LEFT JOIN post_tags pt ON p.post_id = pt.post_id
        LEFT JOIN tags t ON pt.tag_id = t.tag_id
        ";

$where_clauses = [];
$params = [];

if (!empty($search_query)) {
    $where_clauses[] = "(p.content LIKE :search OR u.full_name LIKE :search OR c.name LIKE :search OR t.name LIKE :search)";
    $params[':search'] = "%$search_query%";
}

switch ($filter) {
    case 'warning':
        $where_clauses[] = "p.content_warning = 1";
        break;
    case 'default':
        $where_clauses[] = "p.type = 'padrao' AND p.content_warning = 0";
        break;
}

if (!empty($where_clauses)) {
    $sql .= " WHERE " . implode(' AND ', $where_clauses);
}

$sql .= " GROUP BY p.post_id ORDER BY p.post_id DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
        <a href="admin_posts.php?filter=default" class="btn <?php echo ($filter == 'default') ? 'btn-primary' : ''; ?>">Padrão</a>
        <a href="admin_posts.php?filter=warning" class="btn <?php echo ($filter == 'warning') ? 'btn-primary' : ''; ?>">Com Aviso</a>
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
                <?php if ($posts && count($posts) > 0): ?>
                    <?php foreach ($posts as $post): ?>
                        <tr>
                            <td><?php echo $post['post_id']; ?></td>
                            <td><?php echo htmlspecialchars($post['full_name'] ?? 'Apagado'); ?></td>
                            <td>
                                <i class="ri-heart-line" title="Likes"></i> <?php echo $post['like_count']; ?> |
                                <i class="ri-chat-3-line" title="Respostas"></i> <?php echo $post['reply_count']; ?>
                                <i class="ri-repeat-line" title="Reposts"></i> <?php echo $post['repost_count'] ?? 0; ?> |
                                <i class="ri-bookmark-line" title="Salvos"></i> <?php echo $post['bookmark_count'] ?? 0; ?>
                            </td>
                            <td>
                                <?php
                                $status_exibido = false;
                                if ($post['content_warning']): ?>
                                    <span class="status-badge status-warning">Aviso</span>
                                <?php $status_exibido = true;
                                endif; ?>
                                <?php if (!$status_exibido): ?>
                                    <span class="status-badge status-padrao">Padrão</span>
                                <?php endif; ?>
                            </td>
                            <td class="actions">
                                <button class="btn btn-icon btn-view" title="Ver Detalhes"
                                    data-post-id="<?php echo $post['post_id']; ?>"
                                    data-content="<?php echo htmlspecialchars($post['content']); ?>"
                                    data-author-name="<?php echo htmlspecialchars($post['full_name'] ?? 'Apagado'); ?>"
                                    data-created-at="<?php echo date("d/m/Y H:i", strtotime($post['created_at'])); ?>"
                                    data-community-name="<?php echo htmlspecialchars($post['community_name'] ?? 'N/A'); ?>"
                                    data-tags="<?php echo htmlspecialchars($post['tags'] ?? 'Nenhuma'); ?>"
                                    data-media-urls="<?php echo htmlspecialchars($post['media_urls'] ?? ''); ?>"
                                    data-type="<?php echo htmlspecialchars($post['type']); ?>"
                                    data-content-warning="<?php echo $post['content_warning']; ?>"
                                    data-like-count="<?php echo $post['like_count']; ?>"
                                    data-reply-count="<?php echo $post['reply_count']; ?>"
                                    data-repost-count="<?php echo $post['repost_count'] ?? 0; ?>"
                                    data-bookmark-count="<?php echo $post['bookmark_count'] ?? 0; ?>">
                                    <i class="ri-eye-line"></i>
                                </button>
                                <a href="admin_posts.php?delete_id=<?php echo $post['post_id']; ?>" onclick="return confirm('Tem a certeza que deseja apagar este post? Esta ação é irreversível.');" class="btn btn-icon btn-delete" title="Apagar">
                                    <i class="ri-delete-bin-line"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
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
                    <span><i class="ri-chat-3-line" title="Respostas"></i> <span id="modal-replies"></span></span>
                    <span><i class="ri-repeat-line" title="Reposts"></i> <span id="modal-reposts"></span></span>
                    <span><i class="ri-bookmark-line" title="Salvos"></i> <span id="modal-bookmarks"></span></span>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="imageLightbox" class="lightbox">
    <span class="lightbox-close">&times;</span>
    <a class="lightbox-nav prev">&#10094;</a>
    <img class="lightbox-content" id="lightboxImage">
    <a class="lightbox-nav next">&#10095;</a>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('postModal');
        const closeBtn = document.querySelector('.modal .close-btn');
        const viewBtns = document.querySelectorAll('.btn-view');

        function openPostModal(data) {
            document.getElementById('modal-post-id').textContent = data.postId;
            document.getElementById('modal-author').textContent = data.authorName;
            document.getElementById('modal-date').textContent = data.createdAt;
            document.getElementById('modal-community').textContent = data.communityName;
            document.getElementById('modal-content').textContent = data.content;
            document.getElementById('modal-likes').textContent = data.likeCount;
            document.getElementById('modal-replies').textContent = data.replyCount;
            document.getElementById('modal-reposts').textContent = data.repostCount;
            document.getElementById('modal-bookmarks').textContent = data.bookmarkCount;

            const statusContainer = document.getElementById('modal-status');
            statusContainer.innerHTML = '';
            if (data.contentWarning == '1') {
                statusContainer.innerHTML += `<span class="status-badge status-warning">Aviso de Conteúdo</span>`;
            } else {
                statusContainer.innerHTML = `<span class="status-badge status-padrao">Padrão</span>`;
            }

            const tagsContainer = document.getElementById('modal-tags');
            tagsContainer.innerHTML = '';
            if (data.tags && data.tags !== 'Nenhuma') {
                data.tags.split(', ').forEach(tag => {
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
            if (data.mediaUrls) {
                mediaContainer.style.display = 'grid';
                const mediaUrls = data.mediaUrls.split(';').filter(url => url);
                mediaContainer.dataset.count = mediaUrls.length;
                mediaUrls.forEach(url => {
                    const img = document.createElement('img');
                    img.src = url.replace('../', '../../');
                    mediaContainer.appendChild(img);
                });
            } else {
                mediaContainer.style.display = 'none';
            }
            modal.style.display = 'flex';
        }

        viewBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                openPostModal(this.dataset);
            });
        });

        closeBtn.onclick = () => modal.style.display = 'none';
        window.addEventListener('click', (event) => {
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        });

        <?php if (isset($post_to_view_json)): ?>
            const postDataFromUrl = <?php echo $post_to_view_json; ?>;
            // Adapta os nomes do PHP para os nomes do dataset do JS
            const adaptedData = {
                postId: postDataFromUrl.post_id,
                authorName: postDataFromUrl.full_name,
                createdAt: postDataFromUrl.created_at_formatted,
                communityName: postDataFromUrl.community_name,
                content: postDataFromUrl.content,
                likeCount: postDataFromUrl.like_count,
                replyCount: postDataFromUrl.reply_count,
                repostCount: postDataFromUrl.repost_count,
                bookmarkCount: postDataFromUrl.bookmark_count,
                contentWarning: postDataFromUrl.content_warning,
                tags: postDataFromUrl.tags,
                mediaUrls: postDataFromUrl.media_urls,
                type: postDataFromUrl.type
            };
            openPostModal(adaptedData);
        <?php endif; ?>
    });
</script>

<?php include 'admin_footer.php'; ?>