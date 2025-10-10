-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 04/08/2025 às 16:19
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `ifapoia2`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `audit_log`
--

CREATE TABLE `audit_log` (
  `id_log` int(11) NOT NULL,
  `id_usuario_acao` int(11) DEFAULT NULL,
  `tipo_evento` varchar(100) NOT NULL,
  `assunto_principal` varchar(255) NOT NULL,
  `detalhes_evento` varchar(255) NOT NULL,
  `data_evento` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `audit_log`
--

INSERT INTO `audit_log` (`id_log`, `id_usuario_acao`, `tipo_evento`, `assunto_principal`, `detalhes_evento`, `data_evento`) VALUES
(1, 1, 'Alteração de Cargo de Membro', 'Breno Silveira Domingues', 'Cargo do usuário \'Breno Silveira Domingues\' (ID: #1) alterado para \'admin\' na comunidade \'Comunidade Teste\' (ID: #1).', '2025-07-31 16:02:15'),
(2, 1, 'Edição de Comunidade', 'Breno Silveira Domingues', 'Comunidade \'Comunidade Teste\' (ID: #1) foi atualizada.', '2025-07-31 16:02:17'),
(3, 1, 'Alteração de Cargo', 'Breno Silveira Domingues', 'Cargo do usuário \'Breno Silveira Domingues\' (ID: #1) alterado para \'membro\' na comunidade \'Comunidade Teste\' (ID: #1).', '2025-07-31 16:04:31'),
(4, 1, 'Edição de Comunidade', 'Breno Silveira Domingues', 'Comunidade \'Comunidade Teste\' (ID: #1) foi atualizada.', '2025-07-31 16:04:34'),
(5, 1, 'Nível Atualizado', 'Breno Silveira Domingues', 'Nível \'Técnico-Adm\' (ID: #2) foi atualizado.', '2025-07-31 16:13:40'),
(6, 1, 'Post Deletado', 'Breno Silveira Domingues', 'Post (ID: #9) foi deletado pelo painel de administração.', '2025-07-31 16:19:12'),
(7, 1, 'Novo Post', 'Breno Silveira Domingues', 'Usuário criou o post ID #13.', '2025-07-31 16:21:46'),
(8, 1, 'Nova Tag', 'Breno Silveira Domingues', 'Tag \'teste de log\' (ID: #5) foi criada.', '2025-07-31 16:24:05'),
(9, 1, 'Tag Editada', 'Breno Silveira Domingues', 'Tag (ID: #5) foi atualizada para \'teste de logggg\'.', '2025-07-31 16:24:29'),
(10, 1, 'Nível de Usuário Alterado', 'Breno Silveira Domingues', 'Nível do usuário \'teste teste da silva\' (ID: #2) foi alterado para \'Discente\'.', '2025-07-31 16:29:34'),
(11, 1, 'Nível de Usuário Alterado', 'Breno Silveira Domingues', 'Nível do usuário \'teste teste da silva\' (ID: #2) foi alterado para \'Técnico-Adm\'.', '2025-07-31 16:29:49'),
(12, 1, 'Usuário Modificado', 'Breno Silveira Domingues', 'Nível do usuário \'teste teste da silva\' (ID: #2) foi alterado para \'Administrador\'.', '2025-07-31 16:31:23'),
(13, 1, 'Novo Grupo', 'Breno Silveira Domingues', 'Grupo \'teste\' (ID: #1) foi criado.', '2025-07-31 17:23:46'),
(14, 1, 'Usuário Modificado', 'Breno Silveira Domingues', 'Nível do usuário \'teste teste da silva\' (ID: #2) foi alterado para \'Técnico-Adm\'.', '2025-07-31 17:35:45'),
(15, 1, 'Usuário Modificado', 'Breno Silveira Domingues', 'Nível do usuário \'teste teste da silva\' (ID: #2) foi alterado para \'Administrador\'.', '2025-08-02 05:22:54'),
(16, 1, 'Usuário Modificado', 'Breno Silveira Domingues', 'Nível do usuário \'teste teste da silva\' (ID: #2) foi alterado para \'Técnico-Adm\'.', '2025-08-02 05:22:59'),
(17, 1, 'Usuário Modificado', 'Breno Silveira Domingues', 'Nível do usuário \'teste teste da silva\' (ID: #2) foi alterado para \'Técnico-Adm\'.', '2025-08-02 05:23:01'),
(18, 1, 'Novo Post', 'Breno Silveira Domingues', 'Usuário criou o post ID #14.', '2025-08-04 02:06:20');

-- --------------------------------------------------------

--
-- Estrutura para tabela `bookmarks`
--

CREATE TABLE `bookmarks` (
  `id_usu` int(11) NOT NULL,
  `id_post` int(11) NOT NULL,
  `data_salvo` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `comunidades`
--

CREATE TABLE `comunidades` (
  `id_com` int(11) NOT NULL,
  `nome_com` varchar(100) NOT NULL,
  `descricao_com` varchar(255) NOT NULL,
  `img_com` varchar(255) DEFAULT NULL,
  `id_criador` int(11) NOT NULL,
  `data_criacao` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `comunidades`
--

INSERT INTO `comunidades` (`id_com`, `nome_com`, `descricao_com`, `img_com`, `id_criador`, `data_criacao`) VALUES
(1, 'Comunidade Teste', 'teste teste teste 123 <3', NULL, 1, '2025-07-28 18:16:58'),
(2, 'Comunidade Teste 2', 'aaaaaaaaaaaa', NULL, 1, '2025-07-29 13:36:07'),
(3, 'Comunidade Teste 3', 'asdasdefefasda', NULL, 1, '2025-07-29 13:36:16'),
(4, 'Comunidade Teste 4', 'teste 4', NULL, 1, '2025-07-31 15:30:01');

-- --------------------------------------------------------

--
-- Estrutura para tabela `comunidades_posts`
--

CREATE TABLE `comunidades_posts` (
  `id_com` int(11) NOT NULL,
  `id_post` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `comunidades_posts`
--

INSERT INTO `comunidades_posts` (`id_com`, `id_post`) VALUES
(1, 6),
(1, 8),
(1, 10),
(2, 14),
(3, 3),
(3, 11),
(3, 12),
(4, 13);

-- --------------------------------------------------------

--
-- Estrutura para tabela `denuncias`
--

CREATE TABLE `denuncias` (
  `id_denn` int(11) NOT NULL,
  `id_denunciante` int(11) DEFAULT NULL,
  `tipoalvo_denn` enum('post','usuario','comunidade') NOT NULL,
  `idalvo_denn` int(11) NOT NULL,
  `motivo_denn` text NOT NULL,
  `status_denn` enum('pendente','em_analise','resolvida') NOT NULL DEFAULT 'pendente',
  `data_denn` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `grupos`
--

CREATE TABLE `grupos` (
  `id_gp` int(11) NOT NULL,
  `nome_gp` varchar(100) NOT NULL,
  `descricao_gp` varchar(255) DEFAULT NULL,
  `id_criador` int(11) NOT NULL,
  `data_criacao` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `grupos`
--

INSERT INTO `grupos` (`id_gp`, `nome_gp`, `descricao_gp`, `id_criador`, `data_criacao`) VALUES
(1, 'teste', 'teste testeeeeee', 1, '2025-07-31 17:23:46');

-- --------------------------------------------------------

--
-- Estrutura para tabela `likes`
--

CREATE TABLE `likes` (
  `id_usu` int(11) NOT NULL,
  `id_post` int(11) NOT NULL,
  `data_like` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `likes`
--

INSERT INTO `likes` (`id_usu`, `id_post`, `data_like`) VALUES
(1, 3, '2025-08-04 19:06:42'),
(1, 5, '2025-07-30 21:58:23'),
(1, 6, '2025-07-30 21:58:24'),
(1, 7, '2025-07-30 21:58:47'),
(1, 8, '2025-08-01 01:10:07'),
(1, 10, '2025-08-01 01:10:06'),
(1, 11, '2025-08-01 01:51:10'),
(1, 12, '2025-08-01 01:10:03'),
(1, 13, '2025-08-01 01:45:31'),
(1, 14, '2025-08-04 19:14:38');

-- --------------------------------------------------------

--
-- Estrutura para tabela `niveis`
--

CREATE TABLE `niveis` (
  `id_nvl` int(11) NOT NULL,
  `nome_nvl` varchar(100) NOT NULL,
  `descricao_nvl` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `niveis`
--

INSERT INTO `niveis` (`id_nvl`, `nome_nvl`, `descricao_nvl`) VALUES
(1, 'Administrador', 'Acesso total ao sistema e painel de administração.'),
(2, 'Técnico-Adm', 'Pode gerenciar posts e usuários em comunidades específicas.'),
(3, 'Docente', 'Pode gerenciar posts e usuários em comunidades específicas.'),
(4, 'Discente', 'Utilizador padrão com permissões básicas de postagem e interação.');

-- --------------------------------------------------------

--
-- Estrutura para tabela `posts`
--

CREATE TABLE `posts` (
  `id_post` int(11) NOT NULL,
  `id_usu` int(11) DEFAULT NULL,
  `conteudo_post` text NOT NULL,
  `tipo_post` enum('padrao','resposta','citacao') NOT NULL DEFAULT 'padrao',
  `id_post_pai` int(11) DEFAULT NULL,
  `stats_post` varchar(100) NOT NULL DEFAULT 'ativo',
  `aviso_conteudo` tinyint(1) NOT NULL DEFAULT 0,
  `data_post` timestamp NOT NULL DEFAULT current_timestamp(),
  `data_edicao` timestamp NULL DEFAULT NULL,
  `cont_visualizacoes` int(11) NOT NULL DEFAULT 0,
  `cont_respostas` int(11) NOT NULL DEFAULT 0,
  `cont_reposts` int(11) NOT NULL DEFAULT 0,
  `cont_citacoes` int(11) NOT NULL DEFAULT 0,
  `cont_likes` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `posts`
--

INSERT INTO `posts` (`id_post`, `id_usu`, `conteudo_post`, `tipo_post`, `id_post_pai`, `stats_post`, `aviso_conteudo`, `data_post`, `data_edicao`, `cont_visualizacoes`, `cont_respostas`, `cont_reposts`, `cont_citacoes`, `cont_likes`) VALUES
(3, 1, 'sfafefwedcwedxwe', 'padrao', NULL, 'ativo', 1, '2025-07-29 15:30:52', NULL, 0, 0, 0, 0, 1),
(5, 1, 'teste grande teste testando mega plavras grandes de teste teste grande teste testando mega plavras grandes de teste teste grande teste testando mega plavras grandes de teste teste grande teste testando mega plavras grandes de teste teste grande teste testando mega plavras grandes de teste teste grande teste testando mega plavras grandes de teste teste grande teste testando mega plavras grandes de teste teste grande teste testando mega plavras grandes de teste teste grande teste testando mega plavras grandes de teste teste grande teste testando mega plavras grandes de teste teste grande teste testando mega plavras grandes de teste teste grande teste testando mega plavras grandes de teste teste grande teste testando mega plavras grandes de teste teste grande teste testando mega plavras grandes de teste teste grande teste testando mega plavras grandes de teste teste grande teste testando mega plavras grandes de teste teste grande teste testando mega plavras grandes de teste teste grande teste testando mega plavras grandes de teste teste grande teste testando mega plavras grandes de teste teste grande teste testando mega plavras grandes de teste teste grande teste testando mega plavras grandes de teste', 'padrao', NULL, 'ativo', 0, '2025-07-30 02:51:09', NULL, 0, 0, 0, 0, 1),
(6, 1, 'tessssste', 'padrao', NULL, 'ativo', 0, '2025-07-30 03:04:44', NULL, 0, 0, 0, 0, 1),
(7, 1, 'asdasdweasdwa', 'padrao', NULL, 'ativo', 1, '2025-07-30 13:38:19', NULL, 0, 0, 0, 0, 1),
(8, 1, 'asdasdadsdweadcw', 'padrao', NULL, 'ativo', 0, '2025-07-31 02:57:41', NULL, 0, 0, 0, 0, 1),
(10, 1, 'wqrasdwqdqwdqw', 'padrao', NULL, 'ativo', 0, '2025-07-31 14:07:56', NULL, 0, 0, 0, 0, 1),
(11, 1, 'dasdasdasdffeqe', 'padrao', NULL, 'ativo', 1, '2025-07-31 15:02:51', NULL, 0, 0, 0, 0, 1),
(12, 1, 'dasdwedawdw', 'padrao', NULL, 'ativo', 0, '2025-07-31 16:17:33', NULL, 0, 0, 0, 0, 1),
(13, 1, 'dsadwdawda', 'padrao', NULL, 'ativo', 1, '2025-07-31 16:21:46', NULL, 0, 0, 0, 0, 1),
(14, 1, 'hgh3efghjbfhebwnfmbwemfehjfghjwejc', 'padrao', NULL, 'ativo', 0, '2025-08-04 02:06:20', NULL, 0, 0, 0, 0, 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `posts_tags`
--

CREATE TABLE `posts_tags` (
  `id_post` int(11) NOT NULL,
  `id_tag` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `posts_tags`
--

INSERT INTO `posts_tags` (`id_post`, `id_tag`) VALUES
(10, 1),
(10, 2),
(11, 4),
(12, 1),
(12, 2),
(12, 3),
(12, 4),
(13, 1),
(13, 2),
(13, 3),
(13, 4),
(14, 2);

-- --------------------------------------------------------

--
-- Estrutura para tabela `post_media`
--

CREATE TABLE `post_media` (
  `id_media` int(11) NOT NULL,
  `id_post` int(11) NOT NULL,
  `tipo_media` enum('imagem','video') NOT NULL,
  `url_media` varchar(255) NOT NULL,
  `ordem` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `post_media`
--

INSERT INTO `post_media` (`id_media`, `id_post`, `tipo_media`, `url_media`, `ordem`) VALUES
(6, 3, 'imagem', '../uploads/posts/6888e92c88efe1.43689993.gif', 0),
(7, 5, 'imagem', '../uploads/posts/6889889d607030.41316580.gif', 0),
(8, 5, 'imagem', '../uploads/posts/6889889d60ed42.24276692.gif', 1),
(9, 5, 'imagem', '../uploads/posts/6889889d612971.17887198.gif', 2),
(10, 5, 'imagem', '../uploads/posts/6889889d6753a3.13619552.png', 3),
(11, 6, 'imagem', '../uploads/posts/68898bcc246f92.24646030.jpg', 0),
(12, 6, 'imagem', '../uploads/posts/68898bcc2535e4.50885871.png', 1),
(13, 6, 'imagem', '../uploads/posts/68898bcc257ff7.59838182.png', 2),
(14, 11, 'imagem', '../uploads/posts/688b859ba34cf6.16568679.jpg', 0),
(15, 12, 'imagem', '../uploads/posts/688b971dbd6c18.69091062.jpg', 0),
(16, 14, 'imagem', '../uploads/posts/6890159c5ba543.97441525.jpg', 0);

-- --------------------------------------------------------

--
-- Estrutura para tabela `reposts`
--

CREATE TABLE `reposts` (
  `id_usu` int(11) NOT NULL,
  `id_post` int(11) NOT NULL,
  `data_repost` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tags`
--

CREATE TABLE `tags` (
  `id_tag` int(11) NOT NULL,
  `nome_tag` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `tags`
--

INSERT INTO `tags` (`id_tag`, `nome_tag`) VALUES
(4, 'ablablablaA'),
(5, 'teste de logggg'),
(2, 'teste2'),
(3, 'teste3'),
(1, 'testedsada');

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usu` int(11) NOT NULL,
  `id_nvl` int(11) DEFAULT 4,
  `nome_usu` varchar(100) NOT NULL,
  `email_usu` varchar(100) NOT NULL,
  `senha_usu` varchar(255) NOT NULL,
  `estado_usu` varchar(100) DEFAULT NULL,
  `campus_usu` varchar(100) DEFAULT NULL,
  `sexo_usu` varchar(100) DEFAULT NULL,
  `orsex_usu` varchar(100) DEFAULT NULL,
  `imgperfil_usu` varchar(255) DEFAULT '../src/assets/img/default-user.png',
  `imgcapa_usu` varchar(255) DEFAULT NULL,
  `descricao_usu` text DEFAULT NULL,
  `datacriacao_usu` timestamp NOT NULL DEFAULT current_timestamp(),
  `dataatualizacao_usu` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id_usu`, `id_nvl`, `nome_usu`, `email_usu`, `senha_usu`, `estado_usu`, `campus_usu`, `sexo_usu`, `orsex_usu`, `imgperfil_usu`, `imgcapa_usu`, `descricao_usu`, `datacriacao_usu`, `dataatualizacao_usu`) VALUES
(1, 1, 'Breno Silveira Domingues', 'breno.d@aluno.ifsp.edu.br', '$2y$10$1ojacbGjO1eJ07dQNOfgCO2jH6JHamvmdPHbAWYCqMFly2UbdO/8m', 'SP', 'IFSP Campus Piracicaba', 'M', 'hetero', '../src/assets/img/default-user.png', NULL, NULL, '2025-07-28 16:57:00', '2025-07-28 17:12:12'),
(2, 2, 'teste teste da silva', 'teste.t@ifsp.edu.br', '$2y$10$1ojacbGjO1eJ07dQNOfgCO2jH6JHamvmdPHbAWYCqMFly2UbdO/8m', 'SP', 'IFSP Campus Piracicaba', 'M', 'hetero', '../src/assets/img/default-user.png', NULL, NULL, '2025-07-28 18:15:46', '2025-08-02 05:22:59');

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios_comunidades`
--

CREATE TABLE `usuarios_comunidades` (
  `id_usu` int(11) NOT NULL,
  `id_com` int(11) NOT NULL,
  `tipo_membro` enum('membro','moderador','admin') NOT NULL DEFAULT 'membro',
  `data_entrada` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios_grupos`
--

CREATE TABLE `usuarios_grupos` (
  `id_usu` int(11) NOT NULL,
  `id_gp` int(11) NOT NULL,
  `data_entrada` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `usuarios_grupos`
--

INSERT INTO `usuarios_grupos` (`id_usu`, `id_gp`, `data_entrada`) VALUES
(1, 1, '2025-07-31 18:11:50');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `audit_log`
--
ALTER TABLE `audit_log`
  ADD PRIMARY KEY (`id_log`),
  ADD KEY `id_usuario_acao` (`id_usuario_acao`);

--
-- Índices de tabela `bookmarks`
--
ALTER TABLE `bookmarks`
  ADD PRIMARY KEY (`id_usu`,`id_post`),
  ADD KEY `fk_bookmarks_posts_idx` (`id_post`);

--
-- Índices de tabela `comunidades`
--
ALTER TABLE `comunidades`
  ADD PRIMARY KEY (`id_com`),
  ADD KEY `fk_comunidades_criador_idx` (`id_criador`);

--
-- Índices de tabela `comunidades_posts`
--
ALTER TABLE `comunidades_posts`
  ADD PRIMARY KEY (`id_com`,`id_post`),
  ADD KEY `fk_cp_posts_idx` (`id_post`);

--
-- Índices de tabela `denuncias`
--
ALTER TABLE `denuncias`
  ADD PRIMARY KEY (`id_denn`),
  ADD KEY `fk_denuncias_usuarios_idx` (`id_denunciante`);

--
-- Índices de tabela `grupos`
--
ALTER TABLE `grupos`
  ADD PRIMARY KEY (`id_gp`),
  ADD KEY `fk_grupos_criador_idx` (`id_criador`);

--
-- Índices de tabela `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`id_usu`,`id_post`),
  ADD KEY `fk_likes_posts_idx` (`id_post`);

--
-- Índices de tabela `niveis`
--
ALTER TABLE `niveis`
  ADD PRIMARY KEY (`id_nvl`),
  ADD UNIQUE KEY `nome_nvl_UNIQUE` (`nome_nvl`);

--
-- Índices de tabela `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id_post`),
  ADD KEY `fk_posts_usuarios_idx` (`id_usu`),
  ADD KEY `fk_posts_pai_idx` (`id_post_pai`);

--
-- Índices de tabela `posts_tags`
--
ALTER TABLE `posts_tags`
  ADD PRIMARY KEY (`id_post`,`id_tag`),
  ADD KEY `fk_pt_tags_idx` (`id_tag`);

--
-- Índices de tabela `post_media`
--
ALTER TABLE `post_media`
  ADD PRIMARY KEY (`id_media`),
  ADD KEY `fk_media_posts_idx` (`id_post`);

--
-- Índices de tabela `reposts`
--
ALTER TABLE `reposts`
  ADD PRIMARY KEY (`id_usu`,`id_post`),
  ADD KEY `fk_reposts_posts_idx` (`id_post`);

--
-- Índices de tabela `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`id_tag`),
  ADD UNIQUE KEY `nome_tag_UNIQUE` (`nome_tag`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usu`),
  ADD UNIQUE KEY `email_usu_UNIQUE` (`email_usu`),
  ADD KEY `fk_usuarios_niveis_idx` (`id_nvl`);

--
-- Índices de tabela `usuarios_comunidades`
--
ALTER TABLE `usuarios_comunidades`
  ADD PRIMARY KEY (`id_usu`,`id_com`),
  ADD KEY `fk_uc_comunidades_idx` (`id_com`);

--
-- Índices de tabela `usuarios_grupos`
--
ALTER TABLE `usuarios_grupos`
  ADD PRIMARY KEY (`id_usu`,`id_gp`),
  ADD KEY `fk_ug_grupos_idx` (`id_gp`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `audit_log`
--
ALTER TABLE `audit_log`
  MODIFY `id_log` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de tabela `comunidades`
--
ALTER TABLE `comunidades`
  MODIFY `id_com` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `denuncias`
--
ALTER TABLE `denuncias`
  MODIFY `id_denn` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `grupos`
--
ALTER TABLE `grupos`
  MODIFY `id_gp` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `posts`
--
ALTER TABLE `posts`
  MODIFY `id_post` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de tabela `post_media`
--
ALTER TABLE `post_media`
  MODIFY `id_media` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de tabela `tags`
--
ALTER TABLE `tags`
  MODIFY `id_tag` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usu` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `bookmarks`
--
ALTER TABLE `bookmarks`
  ADD CONSTRAINT `fk_bookmarks_posts` FOREIGN KEY (`id_post`) REFERENCES `posts` (`id_post`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_bookmarks_usuarios` FOREIGN KEY (`id_usu`) REFERENCES `usuarios` (`id_usu`) ON DELETE CASCADE;

--
-- Restrições para tabelas `comunidades`
--
ALTER TABLE `comunidades`
  ADD CONSTRAINT `fk_comunidades_criador` FOREIGN KEY (`id_criador`) REFERENCES `usuarios` (`id_usu`) ON DELETE CASCADE;

--
-- Restrições para tabelas `comunidades_posts`
--
ALTER TABLE `comunidades_posts`
  ADD CONSTRAINT `fk_cp_comunidades` FOREIGN KEY (`id_com`) REFERENCES `comunidades` (`id_com`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_cp_posts` FOREIGN KEY (`id_post`) REFERENCES `posts` (`id_post`) ON DELETE CASCADE;

--
-- Restrições para tabelas `denuncias`
--
ALTER TABLE `denuncias`
  ADD CONSTRAINT `fk_denuncias_usuarios` FOREIGN KEY (`id_denunciante`) REFERENCES `usuarios` (`id_usu`) ON DELETE SET NULL;

--
-- Restrições para tabelas `grupos`
--
ALTER TABLE `grupos`
  ADD CONSTRAINT `fk_grupos_criador` FOREIGN KEY (`id_criador`) REFERENCES `usuarios` (`id_usu`) ON DELETE CASCADE;

--
-- Restrições para tabelas `likes`
--
ALTER TABLE `likes`
  ADD CONSTRAINT `fk_likes_posts` FOREIGN KEY (`id_post`) REFERENCES `posts` (`id_post`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_likes_usuarios` FOREIGN KEY (`id_usu`) REFERENCES `usuarios` (`id_usu`) ON DELETE CASCADE;

--
-- Restrições para tabelas `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `fk_posts_pai` FOREIGN KEY (`id_post_pai`) REFERENCES `posts` (`id_post`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_posts_usuarios` FOREIGN KEY (`id_usu`) REFERENCES `usuarios` (`id_usu`) ON DELETE CASCADE;

--
-- Restrições para tabelas `posts_tags`
--
ALTER TABLE `posts_tags`
  ADD CONSTRAINT `fk_pt_posts` FOREIGN KEY (`id_post`) REFERENCES `posts` (`id_post`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_pt_tags` FOREIGN KEY (`id_tag`) REFERENCES `tags` (`id_tag`) ON DELETE CASCADE;

--
-- Restrições para tabelas `post_media`
--
ALTER TABLE `post_media`
  ADD CONSTRAINT `fk_media_posts` FOREIGN KEY (`id_post`) REFERENCES `posts` (`id_post`) ON DELETE CASCADE;

--
-- Restrições para tabelas `reposts`
--
ALTER TABLE `reposts`
  ADD CONSTRAINT `fk_reposts_posts` FOREIGN KEY (`id_post`) REFERENCES `posts` (`id_post`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_reposts_usuarios` FOREIGN KEY (`id_usu`) REFERENCES `usuarios` (`id_usu`) ON DELETE CASCADE;

--
-- Restrições para tabelas `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `fk_usuarios_niveis` FOREIGN KEY (`id_nvl`) REFERENCES `niveis` (`id_nvl`) ON DELETE SET NULL;

--
-- Restrições para tabelas `usuarios_comunidades`
--
ALTER TABLE `usuarios_comunidades`
  ADD CONSTRAINT `fk_uc_comunidades` FOREIGN KEY (`id_com`) REFERENCES `comunidades` (`id_com`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_uc_usuarios` FOREIGN KEY (`id_usu`) REFERENCES `usuarios` (`id_usu`) ON DELETE CASCADE;

--
-- Restrições para tabelas `usuarios_grupos`
--
ALTER TABLE `usuarios_grupos`
  ADD CONSTRAINT `fk_ug_grupos` FOREIGN KEY (`id_gp`) REFERENCES `grupos` (`id_gp`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_ug_usuarios` FOREIGN KEY (`id_usu`) REFERENCES `usuarios` (`id_usu`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
