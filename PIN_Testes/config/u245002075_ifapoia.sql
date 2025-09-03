-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Tempo de geração: 22/07/2025 às 00:02
-- Versão do servidor: 10.11.10-MariaDB-log
-- Versão do PHP: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `u245002075_ifapoia`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `comentarios`
--

CREATE TABLE `comentarios` (
  `id_coment` int(11) NOT NULL,
  `id_post` int(11) DEFAULT NULL,
  `id_usu` int(11) DEFAULT NULL,
  `conteudo_coment` text NOT NULL,
  `data_coment` timestamp NOT NULL DEFAULT current_timestamp(),
  `id_coment_pai` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `comunidades`
--

CREATE TABLE `comunidades` (
  `id_com` int(11) NOT NULL,
  `nome_com` varchar(100) NOT NULL,
  `descricao_com` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `comunidades_posts`
--

CREATE TABLE `comunidades_posts` (
  `id_com` int(11) NOT NULL,
  `id_post` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `denuncias`
--

CREATE TABLE `denuncias` (
  `id_den` int(11) NOT NULL,
  `id_usu_denunciante` int(11) DEFAULT NULL,
  `alvo_den` varchar(100) NOT NULL,
  `idalvo_den` int(11) NOT NULL,
  `motivo_den` text NOT NULL,
  `data_den` timestamp NOT NULL DEFAULT current_timestamp(),
  `status_den` varchar(50) NOT NULL DEFAULT 'pendente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `grupos`
--

CREATE TABLE `grupos` (
  `id_gp` int(11) NOT NULL,
  `nome_gp` varchar(100) NOT NULL,
  `descricao_gp` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `likes`
--

CREATE TABLE `likes` (
  `id_usu` int(11) NOT NULL,
  `id_post` int(11) NOT NULL,
  `tipo_lk` enum('curtir','nao_curtir') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `niveis`
--

CREATE TABLE `niveis` (
  `id_nvl` int(11) NOT NULL,
  `nome_nvl` varchar(100) NOT NULL,
  `descricao_nvl` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `niveis`
--

INSERT INTO `niveis` (`id_nvl`, `nome_nvl`, `descricao_nvl`) VALUES
(1, 'Administrador', 'Acesso total ao sistema e painel de administração.'),
(2, 'Técnico Administrativo', 'Pode gerenciar posts e usuários em comunidades específicas.'),
(3, 'Docente', 'Pode gerenciar posts e usuários em comunidades específicas.'),
(4, 'Discente', 'Usuário padrão com permissões básicas de postagem e interação.');

-- --------------------------------------------------------

--
-- Estrutura para tabela `notificacoes`
--

CREATE TABLE `notificacoes` (
  `id_not` int(11) NOT NULL,
  `id_usu` int(11) DEFAULT NULL,
  `mensagem_not` varchar(255) NOT NULL,
  `tipo_not` varchar(100) NOT NULL,
  `link_not` varchar(255) DEFAULT NULL,
  `visto_not` tinyint(1) NOT NULL DEFAULT 0,
  `data_not` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `posts`
--

CREATE TABLE `posts` (
  `id_post` int(11) NOT NULL,
  `titulo_post` varchar(150) NOT NULL,
  `conteudo_post` text NOT NULL,
  `data_post` timestamp NOT NULL DEFAULT current_timestamp(),
  `id_usu` int(11) DEFAULT NULL,
  `stats_post` varchar(100) NOT NULL DEFAULT 'ativo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `posts_tags`
--

CREATE TABLE `posts_tags` (
  `id_post` int(11) NOT NULL,
  `id_tag` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tags`
--

CREATE TABLE `tags` (
  `id_tag` int(11) NOT NULL,
  `nome_tag` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usu` int(11) NOT NULL,
  `nome_usu` varchar(100) NOT NULL,
  `email_usu` varchar(100) NOT NULL,
  `senha_usu` varchar(255) NOT NULL,
  `estado_usu` varchar(100) NOT NULL,
  `campus_usu` varchar(100) NOT NULL,
  `sexo_usu` varchar(100) NOT NULL,
  `orsex_usu` varchar(100) NOT NULL,
  `imgperfil_usu` varchar(255) DEFAULT '../src/assets/img/default-user.png',
  `imgcapa_usu` varchar(255) NOT NULL,
  `descricao_usu` text DEFAULT NULL,
  `datacriacao_usu` timestamp NOT NULL DEFAULT current_timestamp(),
  `id_nvl` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id_usu`, `nome_usu`, `email_usu`, `senha_usu`, `estado_usu`, `campus_usu`, `sexo_usu`, `orsex_usu`, `imgperfil_usu`, `imgcapa_usu`, `descricao_usu`, `datacriacao_usu`, `id_nvl`) VALUES
(1, 'Felipe Tozzi Bertochi', 'felipe.bertochi@aluno.ifsp.edu.br', '$2y$10$jc.uyaeN1.972EkaZ9xj/.dGofacx8XG72dzMhEFM72V4mxVcP36.', 'SP', 'IFSP Campus Piracicaba', 'M', 'hetero', '../src/assets/img/default-user.png', '', '', '0000-00-00 00:00:00', NULL),
(2, 'RUAN GUSTAVO NOVELLO CORREA', 'ruan.gustavo@aluno.ifsp.edu.br', '$2y$10$9WUVt/Gd.BR0TSEF7mBteepWe6DG6YM.gNurN4Y0eoS3EFjnIxK7u', 'PB', 'IFPB Campus Areia', 'M', 'hetero', '../src/assets/img/default-user.png', '', '', '0000-00-00 00:00:00', NULL),
(3, 'Breno Silveira Domingues', 'breno.d@aluno.ifsp.edu.br', '$2y$10$e.94U4hZQdQ78UqiMcfU.udY2cpl6TywKw5BycXqGIPpSpmv/zrqG', 'SP', 'IFSP Campus Piracicaba', 'M', 'hetero', '../src/assets/img/default-user.png', '', '', '0000-00-00 00:00:00', NULL),
(4, 'Bruna Vitória Peron Lopes', 'bruna.peron@aluno.ifsp.edu.br', '$2y$10$1QkeHEaxITa8wg1d5MYHHOItSgU9RFirwawut6jnYjddhP4O.2CCO', 'SP', 'IFSP Campus Votuporanga', 'M', 'queer', '../src/assets/img/default-user.png', '', '', '0000-00-00 00:00:00', NULL),
(5, 'rafael  carlos francisco', 'carlos.rafael@aluno.ifsp.edu.br', '$2y$10$WEChiv2FFz3qd7VcCsKKrOmKDzp./tTnR9KhVysH7nBFX45/5iOyS', 'SP', 'IFSP Campus Piracicaba', 'M', 'outro', '../src/assets/img/default-user.png', '', '', '0000-00-00 00:00:00', NULL),
(6, 'Luiz Gustavo Pizara', 'luiz.pizara@aluno.ifsp.edu.br', '$2y$10$nkrHxdgXACVthlUxTJs3b.OrxqfHZMod6uPuJ64qTO2ROuPV8fJPm', 'SP', 'IFSP Campus Piracicaba', 'M', 'hetero', '../src/assets/img/default-user.png', '', '', '0000-00-00 00:00:00', NULL),
(7, 'Henry Germuts Paulo', 'henry.germuts@aluno.ifsp.edu.br', '$2y$10$mzS4WxOTokZ6k.8aN.ccxeyS/RBmW/9qqIql0e7f5yL9y3MeoGbqK', 'SP', 'IFSP Campus Piracicaba', 'M', 'bissex', '../src/assets/img/default-user.png', '', '', '0000-00-00 00:00:00', NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios_comunidades`
--

CREATE TABLE `usuarios_comunidades` (
  `id_usu` int(11) NOT NULL,
  `id_com` int(11) NOT NULL,
  `tipo_membro` varchar(50) NOT NULL DEFAULT 'membro',
  `dataenter_com` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios_grupos`
--

CREATE TABLE `usuarios_grupos` (
  `id_usu` int(11) NOT NULL,
  `id_gp` int(11) NOT NULL,
  `dataenter_gp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `comentarios`
--
ALTER TABLE `comentarios`
  ADD PRIMARY KEY (`id_coment`),
  ADD KEY `fk_comentarios_posts` (`id_post`),
  ADD KEY `fk_comentarios_usuarios` (`id_usu`),
  ADD KEY `fk_comentarios_pai` (`id_coment_pai`);

--
-- Índices de tabela `comunidades`
--
ALTER TABLE `comunidades`
  ADD PRIMARY KEY (`id_com`);

--
-- Índices de tabela `comunidades_posts`
--
ALTER TABLE `comunidades_posts`
  ADD PRIMARY KEY (`id_com`,`id_post`),
  ADD KEY `fk_comunidades_posts_posts` (`id_post`);

--
-- Índices de tabela `denuncias`
--
ALTER TABLE `denuncias`
  ADD PRIMARY KEY (`id_den`),
  ADD KEY `fk_denuncias_usuarios` (`id_usu_denunciante`);

--
-- Índices de tabela `grupos`
--
ALTER TABLE `grupos`
  ADD PRIMARY KEY (`id_gp`);

--
-- Índices de tabela `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`id_usu`,`id_post`),
  ADD KEY `fk_likes_posts` (`id_post`);

--
-- Índices de tabela `niveis`
--
ALTER TABLE `niveis`
  ADD PRIMARY KEY (`id_nvl`),
  ADD UNIQUE KEY `nome_nvl` (`nome_nvl`),
  ADD UNIQUE KEY `nome_nvl_2` (`nome_nvl`),
  ADD UNIQUE KEY `nome_nvl_3` (`nome_nvl`),
  ADD UNIQUE KEY `nome_nvl_4` (`nome_nvl`),
  ADD UNIQUE KEY `nome_nvl_5` (`nome_nvl`),
  ADD UNIQUE KEY `nome_nvl_6` (`nome_nvl`),
  ADD UNIQUE KEY `nome_nvl_7` (`nome_nvl`),
  ADD UNIQUE KEY `nome_nvl_8` (`nome_nvl`),
  ADD UNIQUE KEY `nome_nvl_9` (`nome_nvl`),
  ADD UNIQUE KEY `nome_nvl_10` (`nome_nvl`),
  ADD UNIQUE KEY `nome_nvl_11` (`nome_nvl`),
  ADD UNIQUE KEY `nome_nvl_12` (`nome_nvl`),
  ADD UNIQUE KEY `nome_nvl_13` (`nome_nvl`),
  ADD UNIQUE KEY `nome_nvl_14` (`nome_nvl`),
  ADD UNIQUE KEY `nome_nvl_15` (`nome_nvl`),
  ADD UNIQUE KEY `nome_nvl_16` (`nome_nvl`),
  ADD UNIQUE KEY `nome_nvl_17` (`nome_nvl`),
  ADD UNIQUE KEY `nome_nvl_18` (`nome_nvl`),
  ADD UNIQUE KEY `nome_nvl_19` (`nome_nvl`),
  ADD UNIQUE KEY `nome_nvl_20` (`nome_nvl`),
  ADD UNIQUE KEY `nome_nvl_21` (`nome_nvl`),
  ADD UNIQUE KEY `nome_nvl_22` (`nome_nvl`);

--
-- Índices de tabela `notificacoes`
--
ALTER TABLE `notificacoes`
  ADD PRIMARY KEY (`id_not`),
  ADD KEY `fk_notificacoes_usuarios` (`id_usu`);

--
-- Índices de tabela `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id_post`),
  ADD KEY `fk_posts_usuarios` (`id_usu`);

--
-- Índices de tabela `posts_tags`
--
ALTER TABLE `posts_tags`
  ADD PRIMARY KEY (`id_post`,`id_tag`),
  ADD KEY `fk_posts_tags_tags` (`id_tag`);

--
-- Índices de tabela `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`id_tag`),
  ADD UNIQUE KEY `nome_tag` (`nome_tag`),
  ADD UNIQUE KEY `nome_tag_2` (`nome_tag`),
  ADD UNIQUE KEY `nome_tag_3` (`nome_tag`),
  ADD UNIQUE KEY `nome_tag_4` (`nome_tag`),
  ADD UNIQUE KEY `nome_tag_5` (`nome_tag`),
  ADD UNIQUE KEY `nome_tag_6` (`nome_tag`),
  ADD UNIQUE KEY `nome_tag_7` (`nome_tag`),
  ADD UNIQUE KEY `nome_tag_8` (`nome_tag`),
  ADD UNIQUE KEY `nome_tag_9` (`nome_tag`),
  ADD UNIQUE KEY `nome_tag_10` (`nome_tag`),
  ADD UNIQUE KEY `nome_tag_11` (`nome_tag`),
  ADD UNIQUE KEY `nome_tag_12` (`nome_tag`),
  ADD UNIQUE KEY `nome_tag_13` (`nome_tag`),
  ADD UNIQUE KEY `nome_tag_14` (`nome_tag`),
  ADD UNIQUE KEY `nome_tag_15` (`nome_tag`),
  ADD UNIQUE KEY `nome_tag_16` (`nome_tag`),
  ADD UNIQUE KEY `nome_tag_17` (`nome_tag`),
  ADD UNIQUE KEY `nome_tag_18` (`nome_tag`),
  ADD UNIQUE KEY `nome_tag_19` (`nome_tag`),
  ADD UNIQUE KEY `nome_tag_20` (`nome_tag`),
  ADD UNIQUE KEY `nome_tag_21` (`nome_tag`),
  ADD UNIQUE KEY `nome_tag_22` (`nome_tag`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usu`),
  ADD UNIQUE KEY `email_usu` (`email_usu`),
  ADD KEY `fk_usuarios_niveis` (`id_nvl`);

--
-- Índices de tabela `usuarios_comunidades`
--
ALTER TABLE `usuarios_comunidades`
  ADD PRIMARY KEY (`id_usu`,`id_com`),
  ADD KEY `fk_usuarios_comunidades_comunidades` (`id_com`);

--
-- Índices de tabela `usuarios_grupos`
--
ALTER TABLE `usuarios_grupos`
  ADD PRIMARY KEY (`id_usu`,`id_gp`),
  ADD KEY `fk_usuarios_grupos_grupos` (`id_gp`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `comentarios`
--
ALTER TABLE `comentarios`
  MODIFY `id_coment` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `comunidades`
--
ALTER TABLE `comunidades`
  MODIFY `id_com` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `denuncias`
--
ALTER TABLE `denuncias`
  MODIFY `id_den` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `grupos`
--
ALTER TABLE `grupos`
  MODIFY `id_gp` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `notificacoes`
--
ALTER TABLE `notificacoes`
  MODIFY `id_not` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `posts`
--
ALTER TABLE `posts`
  MODIFY `id_post` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tags`
--
ALTER TABLE `tags`
  MODIFY `id_tag` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usu` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `comentarios`
--
ALTER TABLE `comentarios`
  ADD CONSTRAINT `fk_comentarios_pai` FOREIGN KEY (`id_coment_pai`) REFERENCES `comentarios` (`id_coment`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_comentarios_posts` FOREIGN KEY (`id_post`) REFERENCES `posts` (`id_post`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_comentarios_usuarios` FOREIGN KEY (`id_usu`) REFERENCES `usuarios` (`id_usu`) ON DELETE CASCADE;

--
-- Restrições para tabelas `comunidades_posts`
--
ALTER TABLE `comunidades_posts`
  ADD CONSTRAINT `fk_comunidades_posts_comunidades` FOREIGN KEY (`id_com`) REFERENCES `comunidades` (`id_com`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_comunidades_posts_posts` FOREIGN KEY (`id_post`) REFERENCES `posts` (`id_post`) ON DELETE CASCADE;

--
-- Restrições para tabelas `denuncias`
--
ALTER TABLE `denuncias`
  ADD CONSTRAINT `fk_denuncias_usuarios` FOREIGN KEY (`id_usu_denunciante`) REFERENCES `usuarios` (`id_usu`) ON DELETE SET NULL;

--
-- Restrições para tabelas `likes`
--
ALTER TABLE `likes`
  ADD CONSTRAINT `fk_likes_posts` FOREIGN KEY (`id_post`) REFERENCES `posts` (`id_post`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_likes_usuarios` FOREIGN KEY (`id_usu`) REFERENCES `usuarios` (`id_usu`) ON DELETE CASCADE;

--
-- Restrições para tabelas `notificacoes`
--
ALTER TABLE `notificacoes`
  ADD CONSTRAINT `fk_notificacoes_usuarios` FOREIGN KEY (`id_usu`) REFERENCES `usuarios` (`id_usu`) ON DELETE CASCADE;

--
-- Restrições para tabelas `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `fk_posts_usuarios` FOREIGN KEY (`id_usu`) REFERENCES `usuarios` (`id_usu`) ON DELETE CASCADE;

--
-- Restrições para tabelas `posts_tags`
--
ALTER TABLE `posts_tags`
  ADD CONSTRAINT `fk_posts_tags_posts` FOREIGN KEY (`id_post`) REFERENCES `posts` (`id_post`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_posts_tags_tags` FOREIGN KEY (`id_tag`) REFERENCES `tags` (`id_tag`) ON DELETE CASCADE;

--
-- Restrições para tabelas `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `fk_usuarios_niveis` FOREIGN KEY (`id_nvl`) REFERENCES `niveis` (`id_nvl`) ON DELETE SET NULL;

--
-- Restrições para tabelas `usuarios_comunidades`
--
ALTER TABLE `usuarios_comunidades`
  ADD CONSTRAINT `fk_usuarios_comunidades_comunidades` FOREIGN KEY (`id_com`) REFERENCES `comunidades` (`id_com`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_usuarios_comunidades_usuarios` FOREIGN KEY (`id_usu`) REFERENCES `usuarios` (`id_usu`) ON DELETE CASCADE;

--
-- Restrições para tabelas `usuarios_grupos`
--
ALTER TABLE `usuarios_grupos`
  ADD CONSTRAINT `fk_usuarios_grupos_grupos` FOREIGN KEY (`id_gp`) REFERENCES `grupos` (`id_gp`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_usuarios_grupos_usuarios` FOREIGN KEY (`id_usu`) REFERENCES `usuarios` (`id_usu`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
