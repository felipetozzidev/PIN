CREATE database pin;
USE pin;

CREATE TABLE niveis (
    id_nvl INT PRIMARY KEY AUTO_INCREMENT,
    nome_nvl VARCHAR(100) NOT NULL,
    descricao_nvl VARCHAR(255) NOT NULL
);

CREATE TABLE usuarios (
    id_usu INT PRIMARY KEY AUTO_INCREMENT,
    nome_usu VARCHAR(100) NOT NULL,
    email_usu VARCHAR(100) NOT NULL UNIQUE,
    senha_usu VARCHAR(100) NOT NULL,
    estado_usu VARCHAR(100) NOT NULL,
    campus_usu VARCHAR(100) NOT NULL,
    sexo_usu VARCHAR(100) NOT NULL,
    imgcapa_usu VARCHAR(255),
    descricao_usu VARCHAR(255),
    datacriacao_usu DATE NOT NULL,
    id_nvl INT,
    FOREIGN KEY (id_nvl) REFERENCES niveis(id_nvl)
);

CREATE TABLE posts (
    id_post INT PRIMARY KEY AUTO_INCREMENT,
    titulo_post VARCHAR(150) NOT NULL,
    conteudo_post TEXT NOT NULL,
    data_post DATE NOT NULL,
    id_usu INT,
    stats_post VARCHAR(100) NOT NULL,
    FOREIGN KEY (id_usu) REFERENCES usuarios(id_usu)
);

CREATE TABLE tags (
    id_tag INT PRIMARY KEY AUTO_INCREMENT,
    nome_tag VARCHAR(100) NOT NULL
);

CREATE TABLE posts_tags (
    id_post INT,
    id_tag INT,
    PRIMARY KEY (id_post, id_tag),
    FOREIGN KEY (id_post) REFERENCES posts(id_post),
    FOREIGN KEY (id_tag) REFERENCES tags(id_tag)
);

CREATE TABLE grupos (
    id_gp INT PRIMARY KEY AUTO_INCREMENT,
    nome_gp VARCHAR(100),
    descricao_gp VARCHAR(255)
);

CREATE TABLE comunidades (
    id_com INT PRIMARY KEY AUTO_INCREMENT,
    nome_com VARCHAR(100) NOT NULL,
    descricao_com VARCHAR(255) NOT NULL
);

CREATE TABLE usuarios_grupos (
    id_usu INT,
    id_gp INT,
    dataenter_gp DATE NOT NULL,
    PRIMARY KEY (id_usu, id_gp),
    FOREIGN KEY (id_usu) REFERENCES usuarios(id_usu),
    FOREIGN KEY (id_gp) REFERENCES grupos(id_gp)
);

CREATE TABLE comunidades_posts (
    id_com INT,
    id_post INT,
    PRIMARY KEY (id_com, id_post),
    FOREIGN KEY (id_com) REFERENCES comunidades(id_com),
    FOREIGN KEY (id_post) REFERENCES posts(id_post)
);

CREATE TABLE usuarios_comunidades (
    id_usu INT,
    id_com INT,
    tipo_membro VARCHAR(50) NOT NULL,
    dataenter_com DATE NOT NULL,
    PRIMARY KEY (id_usu, id_com),
    FOREIGN KEY (id_usu) REFERENCES usuarios(id_usu),
    FOREIGN KEY (id_com) REFERENCES comunidades(id_com)
);

CREATE TABLE denuncias (
    id_den INT PRIMARY KEY AUTO_INCREMENT,
    id_usu INT,
    alvo_den VARCHAR(100) NOT NULL,
    idalvo_den INT NOT NULL,
    motivo_den VARCHAR(255) NOT NULL,
    data_den DATE NOT NULL,
    FOREIGN KEY (id_usu) REFERENCES usuarios(id_usu)
);

CREATE TABLE notificacoes (
    id_not INT PRIMARY KEY AUTO_INCREMENT,
    id_usu INT,
    mensagem_not VARCHAR(255) NOT NULL,
    tipo_not VARCHAR(100) NOT NULL,
    visto_not BOOLEAN NOT NULL,
    data_not DATE NOT NULL,
    FOREIGN KEY (id_usu) REFERENCES usuarios(id_usu)
);

CREATE TABLE comentarios (
    id_coment INT PRIMARY KEY AUTO_INCREMENT,
    id_post INT,
    id_usu INT,
    conteudo_coment TEXT NOT NULL,
    data_coment DATE NOT NULL,
    id_coment_pai INT,
    FOREIGN KEY (id_post) REFERENCES posts(id_post),
    FOREIGN KEY (id_usu) REFERENCES usuarios(id_usu),
    FOREIGN KEY (id_coment_pai) REFERENCES comentarios(id_coment)
);

CREATE TABLE likes (
    id_lk INT PRIMARY KEY AUTO_INCREMENT,
    id_usu INT,
    id_post INT,
    tipo_lk ENUM('curtir', 'nao_curtir') NOT NULL,
    FOREIGN KEY (id_usu) REFERENCES usuarios(id_usu),
    FOREIGN KEY (id_post) REFERENCES posts(id_post)
);
