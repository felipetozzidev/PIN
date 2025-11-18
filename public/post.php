<main class="">
    <div class="post-creation-card">
        <h1>Criar Nova Publicação</h1>
        <p>Compartilhe suas ideias, dúvidas ou novidades com a comunidade.</p>

        <?php echo $feedback_message; ?>

        <form action="post.php" method="POST" class="post-form" enctype="multipart/form-data">
            <div class="form-group">
                <label for="conteudo_post">Sua Mensagem</label>
                <textarea name="conteudo_post" id="conteudo_post" rows="6" placeholder="No que você está pensando, <?php echo htmlspecialchars($_SESSION['full_name']); ?>?"></textarea>
            </div>

            <div class="form-group col-12">
                <div class="form-group col-6">
                    <label for="id_com">Publicar em uma Comunidade (Opcional)</label>
                    <select name="id_com" id="id_com">
                        <option value="0">Nenhuma (Post Geral)</option>
                        <?php if ($communities): ?>
                            <?php foreach ($communities as $com): ?>
                                <option value="<?php echo $com['community_id']; ?>"><?php echo htmlspecialchars($com['name']); ?></option>
                            <?php endforeach; ?>
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
                        <?php if ($popular_tags): ?>
                            <?php foreach ($popular_tags as $tag): ?>
                                <button type="button" class="recommended-tag"><?php echo htmlspecialchars($tag['name']); ?></button>
                            <?php endforeach; ?>
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


<?php
include('../src/components/footer.php');
?>