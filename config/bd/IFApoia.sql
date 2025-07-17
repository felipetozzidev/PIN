-- Cria o banco de dados se ele não existir, com o conjunto de caracteres e collation recomendados.
CREATE DATABASE IF NOT EXISTS IFApoia DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Seleciona o banco de dados para uso.
USE IFApoia;

-- Tabela para os níveis de acesso dos usuários (ex: Comum, Administrador).
CREATE TABLE niveis (
    id_nvl INT PRIMARY KEY AUTO_INCREMENT,
    nome_nvl VARCHAR(100) NOT NULL UNIQUE, -- Nome do nível deve ser único.
    descricao_nvl VARCHAR(255)
);

-- Tabela principal de usuários.
CREATE TABLE usuarios (
    id_usu INT PRIMARY KEY AUTO_INCREMENT,
    nome_usu VARCHAR(100) NOT NULL,
    email_usu VARCHAR(100) NOT NULL UNIQUE,
    senha_usu VARCHAR(255) NOT NULL, -- Aumentado para senhas com hash mais longos.
    estado_usu VARCHAR(100),
    campus_usu VARCHAR(100),
    sexo_usu VARCHAR(100),
    orsex_usu VARCHAR(100),
    imgperfil_usu VARCHAR(255) DEFAULT 'default-user.png',
    imgcapa_usu VARCHAR(255),
    descricao_usu TEXT, -- Alterado para TEXT para descrições mais longas.
    datacriacao_usu TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, -- Data e hora da criação.
    id_nvl INT,
    FOREIGN KEY (id_nvl) REFERENCES niveis(id_nvl) ON DELETE SET NULL -- Se o nível for deletado, o usuário fica sem nível.
);

-- Tabela para as postagens dos usuários.
CREATE TABLE posts (
    id_post INT PRIMARY KEY AUTO_INCREMENT,
    titulo_post VARCHAR(150) NOT NULL,
    conteudo_post TEXT NOT NULL,
    data_post TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    id_usu INT,
    stats_post VARCHAR(100) NOT NULL DEFAULT 'ativo', -- Ex: 'ativo', 'inativo', 'removido'
    FOREIGN KEY (id_usu) REFERENCES usuarios(id_usu) ON DELETE CASCADE -- Se o usuário for deletado, seus posts também serão.
);

-- Tabela para as tags (categorias) das postagens.
CREATE TABLE tags (
    id_tag INT PRIMARY KEY AUTO_INCREMENT,
    nome_tag VARCHAR(100) NOT NULL UNIQUE
);

-- Tabela de associação entre posts e tags (relação N:N).
CREATE TABLE posts_tags (
    id_post INT,
    id_tag INT,
    PRIMARY KEY (id_post, id_tag),
    FOREIGN KEY (id_post) REFERENCES posts(id_post) ON DELETE CASCADE,
    FOREIGN KEY (id_tag) REFERENCES tags(id_tag) ON DELETE CASCADE
);

-- Tabela de grupos.
CREATE TABLE grupos (
    id_gp INT PRIMARY KEY AUTO_INCREMENT,
    nome_gp VARCHAR(100) NOT NULL,
    descricao_gp VARCHAR(255)
);

-- Tabela de comunidades.
CREATE TABLE comunidades (
    id_com INT PRIMARY KEY AUTO_INCREMENT,
    nome_com VARCHAR(100) NOT NULL,
    descricao_com VARCHAR(255) NOT NULL
);

-- Tabela de associação entre usuários e grupos.
CREATE TABLE usuarios_grupos (
    id_usu INT,
    id_gp INT,
    dataenter_gp TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id_usu, id_gp),
    FOREIGN KEY (id_usu) REFERENCES usuarios(id_usu) ON DELETE CASCADE,
    FOREIGN KEY (id_gp) REFERENCES grupos(id_gp) ON DELETE CASCADE
);

-- Tabela de associação entre comunidades e posts.
CREATE TABLE comunidades_posts (
    id_com INT,
    id_post INT,
    PRIMARY KEY (id_com, id_post),
    FOREIGN KEY (id_com) REFERENCES comunidades(id_com) ON DELETE CASCADE,
    FOREIGN KEY (id_post) REFERENCES posts(id_post) ON DELETE CASCADE
);

-- Tabela de associação entre usuários e comunidades.
CREATE TABLE usuarios_comunidades (
    id_usu INT,
    id_com INT,
    tipo_membro VARCHAR(50) NOT NULL DEFAULT 'membro', -- Ex: 'membro', 'moderador', 'admin'
    dataenter_com TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id_usu, id_com),
    FOREIGN KEY (id_usu) REFERENCES usuarios(id_usu) ON DELETE CASCADE,
    FOREIGN KEY (id_com) REFERENCES comunidades(id_com) ON DELETE CASCADE
);

-- Tabela para registrar denúncias de conteúdo ou usuários.
CREATE TABLE denuncias (
    id_den INT PRIMARY KEY AUTO_INCREMENT,
    id_usu_denunciante INT,
    alvo_den VARCHAR(100) NOT NULL, -- Ex: 'post', 'usuario', 'comentario'
    idalvo_den INT NOT NULL,
    motivo_den TEXT NOT NULL,
    data_den TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    status_den VARCHAR(50) NOT NULL DEFAULT 'pendente', -- Ex: 'pendente', 'resolvido'
    FOREIGN KEY (id_usu_denunciante) REFERENCES usuarios(id_usu) ON DELETE SET NULL -- Mantém a denúncia mesmo se o denunciante for deletado.
);

-- Tabela de notificações para os usuários.
CREATE TABLE notificacoes (
    id_not INT PRIMARY KEY AUTO_INCREMENT,
    id_usu INT,
    mensagem_not VARCHAR(255) NOT NULL,
    tipo_not VARCHAR(100) NOT NULL, -- Ex: 'like', 'comentario', 'denuncia_aceita'
    link_not VARCHAR(255), -- Link para o conteúdo da notificação.
    visto_not BOOLEAN NOT NULL DEFAULT FALSE,
    data_not TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usu) REFERENCES usuarios(id_usu) ON DELETE CASCADE
);

-- Tabela de comentários em posts.
CREATE TABLE comentarios (
    id_coment INT PRIMARY KEY AUTO_INCREMENT,
    id_post INT,
    id_usu INT,
    conteudo_coment TEXT NOT NULL,
    data_coment TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    id_coment_pai INT,
    FOREIGN KEY (id_post) REFERENCES posts(id_post) ON DELETE CASCADE,
    FOREIGN KEY (id_usu) REFERENCES usuarios(id_usu) ON DELETE CASCADE,
    FOREIGN KEY (id_coment_pai) REFERENCES comentarios(id_coment) ON DELETE CASCADE
);

-- Tabela para likes e dislikes em posts.
CREATE TABLE likes (
    id_usu INT,
    id_post INT,
    tipo_lk ENUM('curtir', 'nao_curtir') NOT NULL,
    PRIMARY KEY (id_usu, id_post), -- Garante que um usuário só pode ter uma reação por post.
    FOREIGN KEY (id_usu) REFERENCES usuarios(id_usu) ON DELETE CASCADE,
    FOREIGN KEY (id_post) REFERENCES posts(id_post) ON DELETE CASCADE
);

-- Inserindo níveis de usuário padrão.
INSERT INTO niveis (nome_nvl, descricao_nvl) VALUES
('Administrador', 'Acesso total ao sistema e painel de administração.'),
('Moderador', 'Pode gerenciar posts e usuários em comunidades específicas.'),
('Comum', 'Usuário padrão com permissões básicas de postagem e interação.');

