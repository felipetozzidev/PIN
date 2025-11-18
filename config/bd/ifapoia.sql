-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 15/10/2025 às 16:53
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
-- Banco de dados: `ifapoia`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `audit_log`
--

CREATE TABLE `audit_log` (
  `log_id` int(11) NOT NULL,
  `action_user_id` int(11) DEFAULT NULL,
  `event_type` varchar(100) NOT NULL,
  `action_user_fullname` varchar(255) NOT NULL,
  `event_details` varchar(255) NOT NULL,
  `event_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Despejando dados para a tabela `audit_log`
--

INSERT INTO `audit_log` (`log_id`, `action_user_id`, `event_type`, `action_user_fullname`, `event_details`, `event_date`) VALUES
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
(18, 1, 'Novo Post', 'Breno Silveira Domingues', 'Usuário criou o post ID #14.', '2025-08-04 02:06:20'),
(19, 1, 'Novo Post', 'Breno Silveira Domingues', 'Usuário criou o post ID #15.', '2025-08-20 19:07:32'),
(20, 1, 'Novo Post', 'Breno Silveira Domingues', 'Usuário criou o post ID #16.', '2025-08-30 17:12:02'),
(21, 1, 'Novo Post', 'Breno Silveira Domingues', 'Usuário criou o post ID #17.', '2025-09-01 19:54:34'),
(22, 0, 'Breno Silveira Domingues', 'Usuário comentou no post ID #17. Comentário ID #1.', '1', '2025-10-11 01:49:05'),
(23, 0, 'Breno Silveira Domingues', 'Usuário criou o post ID #18.', '1', '2025-10-11 02:12:39'),
(24, 0, 'Breno Silveira Domingues', 'Usuário criou o post ID #19.', '1', '2025-10-11 02:13:29'),
(25, 0, 'Breno Silveira Domingues', 'Usuário criou o post ID #20.', '1', '2025-10-11 02:39:09'),
(26, 1, 'Novo Post', 'Breno Silveira Domingues', 'Usuário criou o post ID #21.', '2025-10-11 03:00:26'),
(27, 1, 'Post Deletado', 'Breno Silveira Domingues', 'Post (ID: #17) foi deletado pelo painel de administração.', '2025-10-11 03:22:23');

-- --------------------------------------------------------

--
-- Estrutura para tabela `bookmarks`
--

CREATE TABLE `bookmarks` (
  `user_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `saved_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `comments`
--

CREATE TABLE `comments` (
  `comment_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `communities`
--

CREATE TABLE `communities` (
  `community_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` varchar(255) NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `creator_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Despejando dados para a tabela `communities`
--

INSERT INTO `communities` (`community_id`, `name`, `description`, `image_url`, `creator_id`, `created_at`) VALUES
(1, 'Comunidade Teste', 'teste teste teste 123 <3', NULL, 1, '2025-07-28 18:16:58'),
(2, 'Comunidade Teste 2', 'aaaaaaaaaaaa', NULL, 1, '2025-07-29 13:36:07'),
(3, 'Comunidade Teste 3', 'asdasdefefasda', NULL, 1, '2025-07-29 13:36:16'),
(4, 'Comunidade Teste 4', 'teste 4', NULL, 1, '2025-07-31 15:30:01');

-- --------------------------------------------------------

--
-- Estrutura para tabela `community_posts`
--

CREATE TABLE `community_posts` (
  `community_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Despejando dados para a tabela `community_posts`
--

INSERT INTO `community_posts` (`community_id`, `post_id`) VALUES
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
-- Estrutura para tabela `groups`
--

CREATE TABLE `groups` (
  `group_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `creator_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Despejando dados para a tabela `groups`
--

INSERT INTO `groups` (`group_id`, `name`, `description`, `creator_id`, `created_at`) VALUES
(1, 'teste', 'teste testeeeeee', 1, '2025-07-31 17:23:46');

-- --------------------------------------------------------

--
-- Estrutura para tabela `likes`
--

CREATE TABLE `likes` (
  `user_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `liked_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Despejando dados para a tabela `likes`
--

INSERT INTO `likes` (`user_id`, `post_id`, `liked_at`) VALUES
(1, 3, '2025-08-27 18:59:07'),
(1, 5, '2025-07-30 21:58:23'),
(1, 6, '2025-07-30 21:58:24'),
(1, 7, '2025-07-30 21:58:47'),
(1, 8, '2025-08-27 19:07:52'),
(1, 10, '2025-08-27 18:59:02'),
(1, 11, '2025-08-27 18:59:01'),
(1, 12, '2025-08-27 18:59:00'),
(1, 13, '2025-08-27 18:58:58'),
(1, 15, '2025-09-03 18:31:35'),
(1, 16, '2025-10-11 00:41:11'),
(1, 18, '2025-10-11 02:12:55'),
(1, 20, '2025-10-11 03:00:43'),
(1, 21, '2025-10-11 03:00:35'),
(2, 11, '2025-08-09 23:26:45'),
(2, 12, '2025-08-14 01:51:41'),
(2, 13, '2025-08-09 23:23:47'),
(2, 14, '2025-08-09 23:23:46');

-- --------------------------------------------------------

--
-- Estrutura para tabela `posts`
--

CREATE TABLE `posts` (
  `post_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `content` text NOT NULL,
  `type` enum('padrao','resposta','citacao') NOT NULL DEFAULT 'padrao',
  `status` varchar(100) NOT NULL DEFAULT 'ativo',
  `content_warning` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `view_count` int(11) NOT NULL DEFAULT 0,
  `reply_count` int(11) NOT NULL DEFAULT 0,
  `repost_count` int(11) NOT NULL DEFAULT 0,
  `bookmark_count` int(11) NOT NULL DEFAULT 0,
  `like_count` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Despejando dados para a tabela `posts`
--

INSERT INTO `posts` (`post_id`, `user_id`, `content`, `type`, `status`, `content_warning`, `created_at`, `updated_at`, `view_count`, `reply_count`, `repost_count`, `bookmark_count`, `like_count`) VALUES
(3, 1, 'sfafefwedcwedxwe', 'padrao', 'ativo', 1, '2025-07-29 15:30:52', NULL, 0, 0, 0, 0, 1),
(5, 1, 'teste grande teste testando mega plavras grandes de teste teste grande teste testando mega plavras grandes de teste teste grande teste testando mega plavras grandes de teste teste grande teste testando mega plavras grandes de teste teste grande teste testando mega plavras grandes de teste teste grande teste testando mega plavras grandes de teste teste grande teste testando mega plavras grandes de teste teste grande teste testando mega plavras grandes de teste teste grande teste testando mega plavras grandes de teste teste grande teste testando mega plavras grandes de teste teste grande teste testando mega plavras grandes de teste teste grande teste testando mega plavras grandes de teste teste grande teste testando mega plavras grandes de teste teste grande teste testando mega plavras grandes de teste teste grande teste testando mega plavras grandes de teste teste grande teste testando mega plavras grandes de teste teste grande teste testando mega plavras grandes de teste teste grande teste testando mega plavras grandes de teste teste grande teste testando mega plavras grandes de teste teste grande teste testando mega plavras grandes de teste teste grande teste testando mega plavras grandes de teste', 'padrao', 'ativo', 0, '2025-07-30 02:51:09', NULL, 0, 0, 0, 0, 1),
(6, 1, 'tessssste', 'padrao', 'ativo', 0, '2025-07-30 03:04:44', NULL, 0, 0, 0, 0, 1),
(7, 1, 'asdasdweasdwa', 'padrao', 'ativo', 1, '2025-07-30 13:38:19', NULL, 0, 0, 0, 0, 1),
(8, 1, 'asdasdadsdweadcw', 'padrao', 'ativo', 0, '2025-07-31 02:57:41', NULL, 0, 0, 0, 0, 1),
(10, 1, 'wqrasdwqdqwdqw', 'padrao', 'ativo', 0, '2025-07-31 14:07:56', NULL, 0, 0, 0, 0, 1),
(11, 1, 'dasdasdasdffeqe', 'padrao', 'ativo', 1, '2025-07-31 15:02:51', NULL, 0, 0, 0, 0, 2),
(12, 1, 'dasdwedawdw', 'padrao', 'ativo', 0, '2025-07-31 16:17:33', NULL, 0, 0, 0, 0, 2),
(13, 1, 'dsadwdawda', 'padrao', 'ativo', 1, '2025-07-31 16:21:46', NULL, 0, 0, 0, 0, 2),
(14, 1, 'hgh3efghjbfhebwnfmbwemfehjfghjwejc', 'padrao', 'ativo', 0, '2025-08-04 02:06:20', NULL, 0, 0, 0, 0, 1),
(15, 1, 'hjgcdfdfdcv', 'padrao', 'ativo', 1, '2025-08-20 19:07:32', NULL, 0, 0, 0, 0, 1),
(16, 1, 'teste', 'padrao', 'ativo', 0, '2025-08-30 17:12:02', NULL, 0, 0, 0, 0, 1),
(18, 1, 'teste post', 'padrao', 'ativo', 0, '2025-10-11 02:12:39', NULL, 0, 0, 0, 0, 1),
(19, 1, 'teste teste grid de imagens', 'padrao', 'ativo', 0, '2025-10-11 02:13:29', NULL, 0, 0, 0, 0, 0),
(20, 1, 'grid de imagens', 'padrao', 'ativo', 0, '2025-10-11 02:39:02', NULL, 0, 0, 0, 0, 1),
(21, 1, 'teste audit_log', 'padrao', 'ativo', 1, '2025-10-11 03:00:26', NULL, 0, 0, 0, 0, 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `post_media`
--

CREATE TABLE `post_media` (
  `media_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `type` enum('imagem','video') NOT NULL,
  `media_url` varchar(255) NOT NULL,
  `sort_order` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Despejando dados para a tabela `post_media`
--

INSERT INTO `post_media` (`media_id`, `post_id`, `type`, `media_url`, `sort_order`) VALUES
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
(16, 14, 'imagem', '../uploads/posts/6890159c5ba543.97441525.jpg', 0),
(17, 15, 'imagem', '../uploads/posts/68a61cf44b0d54.14732472.jpg', 0),
(18, 16, 'imagem', '../uploads/posts/68b330e2823fb4.32712015.png', 0),
(19, 18, 'imagem', '../uploads/posts/68e9bd17753787.44943330.jpg', 0),
(20, 19, 'imagem', '../uploads/posts/68e9bd49f01798.83550738.jpg', 0),
(21, 20, 'imagem', '../uploads/posts/68e9c34b408174.41820120.png', 0),
(22, 20, 'imagem', '../uploads/posts/68e9c34b8b0114.55814664.jpg', 1),
(23, 20, 'imagem', '../uploads/posts/68e9c34b9d7964.05540885.png', 2);

-- --------------------------------------------------------

--
-- Estrutura para tabela `post_tags`
--

CREATE TABLE `post_tags` (
  `post_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Despejando dados para a tabela `post_tags`
--

INSERT INTO `post_tags` (`post_id`, `tag_id`) VALUES
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
(14, 2),
(15, 2),
(16, 5),
(18, 2),
(19, 3),
(19, 4),
(20, 3);

-- --------------------------------------------------------

--
-- Estrutura para tabela `post_views`
--

CREATE TABLE `post_views` (
  `view_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `viewed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `reports`
--

CREATE TABLE `reports` (
  `report_id` int(11) NOT NULL,
  `reporter_id` int(11) DEFAULT NULL,
  `target_type` enum('post','usuario','comunidade') NOT NULL,
  `target_id` int(11) NOT NULL,
  `reason` text NOT NULL,
  `status` enum('pendente','em_analise','resolvida') NOT NULL DEFAULT 'pendente',
  `reported_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `reposts`
--

CREATE TABLE `reposts` (
  `user_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `reposted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `roles`
--

CREATE TABLE `roles` (
  `role_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Despejando dados para a tabela `roles`
--

INSERT INTO `roles` (`role_id`, `name`, `description`) VALUES
(1, 'Administrador', 'Acesso total ao sistema e painel de administração.'),
(2, 'Técnico-Adm', 'Pode gerenciar posts e usuários em comunidades específicas.'),
(3, 'Docente', 'Pode gerenciar posts e usuários em comunidades específicas.'),
(4, 'Discente', 'Utilizador padrão com permissões básicas de postagem e interação.');

-- --------------------------------------------------------

--
-- Estrutura para tabela `tags`
--

CREATE TABLE `tags` (
  `tag_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Despejando dados para a tabela `tags`
--

INSERT INTO `tags` (`tag_id`, `name`) VALUES
(4, 'ablablablaA'),
(5, 'teste de logggg'),
(2, 'teste2'),
(3, 'teste3'),
(1, 'testedsada');

-- --------------------------------------------------------

--
-- Estrutura para tabela `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `role_id` int(11) DEFAULT 4,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `state` varchar(100) DEFAULT NULL,
  `campus` varchar(100) DEFAULT NULL,
  `gender` varchar(100) DEFAULT NULL,
  `sexual_orientation` varchar(100) DEFAULT NULL,
  `profile_image_url` varchar(255) DEFAULT '../src/assets/img/default-user.png',
  `cover_image_url` varchar(255) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Despejando dados para a tabela `users`
--

INSERT INTO `users` (`user_id`, `role_id`, `full_name`, `email`, `password_hash`, `state`, `campus`, `gender`, `sexual_orientation`, `profile_image_url`, `cover_image_url`, `bio`, `created_at`, `updated_at`) VALUES
(1, 1, 'Breno Silveira Domingues', 'breno.d@aluno.ifsp.edu.br', '$2y$10$1ojacbGjO1eJ07dQNOfgCO2jH6JHamvmdPHbAWYCqMFly2UbdO/8m', 'SP', 'IFSP Campus Piracicaba', 'M', 'hetero', '../src/assets/img/default-user.png', NULL, NULL, '2025-07-28 16:57:00', '2025-07-28 17:12:12'),
(2, 2, 'teste teste da silva', 'teste.t@ifsp.edu.br', '$2y$10$1ojacbGjO1eJ07dQNOfgCO2jH6JHamvmdPHbAWYCqMFly2UbdO/8m', 'SP', 'IFSP Campus Piracicaba', 'M', 'hetero', '../src/assets/img/default-user.png', NULL, NULL, '2025-07-28 18:15:46', '2025-08-02 05:22:59');

-- --------------------------------------------------------

--
-- Estrutura para tabela `user_communities`
--

CREATE TABLE `user_communities` (
  `user_id` int(11) NOT NULL,
  `community_id` int(11) NOT NULL,
  `member_type` enum('membro','moderador','admin') NOT NULL DEFAULT 'membro',
  `joined_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `user_groups`
--

CREATE TABLE `user_groups` (
  `user_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `joined_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Despejando dados para a tabela `user_groups`
--

INSERT INTO `user_groups` (`user_id`, `group_id`, `joined_at`) VALUES
(1, 1, '2025-07-31 18:11:50');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `audit_log`
--
ALTER TABLE `audit_log`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `action_user_id` (`action_user_id`);

--
-- Índices de tabela `bookmarks`
--
ALTER TABLE `bookmarks`
  ADD PRIMARY KEY (`user_id`,`post_id`),
  ADD KEY `fk_bookmarks_post` (`post_id`);

--
-- Índices de tabela `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`comment_id`),
  ADD KEY `fk_comments_user` (`user_id`),
  ADD KEY `fk_comments_post` (`post_id`);

--
-- Índices de tabela `communities`
--
ALTER TABLE `communities`
  ADD PRIMARY KEY (`community_id`),
  ADD KEY `fk_communities_creator` (`creator_id`),
  ADD KEY `idx_name` (`name`);

--
-- Índices de tabela `community_posts`
--
ALTER TABLE `community_posts`
  ADD PRIMARY KEY (`community_id`,`post_id`),
  ADD KEY `fk_community_posts_post` (`post_id`);

--
-- Índices de tabela `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`group_id`),
  ADD KEY `fk_groups_creator` (`creator_id`);

--
-- Índices de tabela `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`user_id`,`post_id`),
  ADD KEY `fk_likes_post` (`post_id`);

--
-- Índices de tabela `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`post_id`),
  ADD KEY `fk_posts_user` (`user_id`);

--
-- Índices de tabela `post_media`
--
ALTER TABLE `post_media`
  ADD PRIMARY KEY (`media_id`),
  ADD KEY `fk_post_media_post` (`post_id`),
  ADD KEY `idx_post_id` (`post_id`);

--
-- Índices de tabela `post_tags`
--
ALTER TABLE `post_tags`
  ADD PRIMARY KEY (`post_id`,`tag_id`),
  ADD KEY `fk_post_tags_tag` (`tag_id`);

--
-- Índices de tabela `post_views`
--
ALTER TABLE `post_views`
  ADD PRIMARY KEY (`view_id`),
  ADD UNIQUE KEY `unique_view` (`post_id`,`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Índices de tabela `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`report_id`),
  ADD KEY `fk_reports_reporter` (`reporter_id`);

--
-- Índices de tabela `reposts`
--
ALTER TABLE `reposts`
  ADD PRIMARY KEY (`user_id`,`post_id`),
  ADD KEY `fk_reposts_post` (`post_id`);

--
-- Índices de tabela `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`role_id`),
  ADD UNIQUE KEY `name_UNIQUE` (`name`);

--
-- Índices de tabela `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`tag_id`),
  ADD UNIQUE KEY `name_UNIQUE` (`name`),
  ADD KEY `idx_name` (`name`);

--
-- Índices de tabela `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email_UNIQUE` (`email`),
  ADD KEY `fk_users_role` (`role_id`),
  ADD KEY `idx_full_name` (`full_name`);

--
-- Índices de tabela `user_communities`
--
ALTER TABLE `user_communities`
  ADD PRIMARY KEY (`user_id`,`community_id`),
  ADD KEY `fk_user_communities_community` (`community_id`);

--
-- Índices de tabela `user_groups`
--
ALTER TABLE `user_groups`
  ADD PRIMARY KEY (`user_id`,`group_id`),
  ADD KEY `fk_user_groups_group` (`group_id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `audit_log`
--
ALTER TABLE `audit_log`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT de tabela `comments`
--
ALTER TABLE `comments`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `communities`
--
ALTER TABLE `communities`
  MODIFY `community_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `groups`
--
ALTER TABLE `groups`
  MODIFY `group_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `posts`
--
ALTER TABLE `posts`
  MODIFY `post_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de tabela `post_media`
--
ALTER TABLE `post_media`
  MODIFY `media_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT de tabela `post_views`
--
ALTER TABLE `post_views`
  MODIFY `view_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `reports`
--
ALTER TABLE `reports`
  MODIFY `report_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tags`
--
ALTER TABLE `tags`
  MODIFY `tag_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `bookmarks`
--
ALTER TABLE `bookmarks`
  ADD CONSTRAINT `fk_bookmarks_post` FOREIGN KEY (`post_id`) REFERENCES `posts` (`post_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_bookmarks_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `fk_comments_post` FOREIGN KEY (`post_id`) REFERENCES `posts` (`post_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_comments_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para tabelas `communities`
--
ALTER TABLE `communities`
  ADD CONSTRAINT `fk_communities_creator` FOREIGN KEY (`creator_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `community_posts`
--
ALTER TABLE `community_posts`
  ADD CONSTRAINT `fk_community_posts_community` FOREIGN KEY (`community_id`) REFERENCES `communities` (`community_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_community_posts_post` FOREIGN KEY (`post_id`) REFERENCES `posts` (`post_id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `groups`
--
ALTER TABLE `groups`
  ADD CONSTRAINT `fk_groups_creator` FOREIGN KEY (`creator_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `likes`
--
ALTER TABLE `likes`
  ADD CONSTRAINT `fk_likes_post` FOREIGN KEY (`post_id`) REFERENCES `posts` (`post_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_likes_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `fk_posts_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `post_media`
--
ALTER TABLE `post_media`
  ADD CONSTRAINT `fk_post_media_post` FOREIGN KEY (`post_id`) REFERENCES `posts` (`post_id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `post_tags`
--
ALTER TABLE `post_tags`
  ADD CONSTRAINT `fk_post_tags_post` FOREIGN KEY (`post_id`) REFERENCES `posts` (`post_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_post_tags_tag` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`tag_id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `post_views`
--
ALTER TABLE `post_views`
  ADD CONSTRAINT `post_views_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`post_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `post_views_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `reports`
--
ALTER TABLE `reports`
  ADD CONSTRAINT `fk_reports_reporter` FOREIGN KEY (`reporter_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL;

--
-- Restrições para tabelas `reposts`
--
ALTER TABLE `reposts`
  ADD CONSTRAINT `fk_reposts_post` FOREIGN KEY (`post_id`) REFERENCES `posts` (`post_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_reposts_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_users_role` FOREIGN KEY (`role_id`) REFERENCES `roles` (`role_id`) ON DELETE SET NULL;

--
-- Restrições para tabelas `user_communities`
--
ALTER TABLE `user_communities`
  ADD CONSTRAINT `fk_user_communities_community` FOREIGN KEY (`community_id`) REFERENCES `communities` (`community_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_user_communities_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `user_groups`
--
ALTER TABLE `user_groups`
  ADD CONSTRAINT `fk_user_groups_group` FOREIGN KEY (`group_id`) REFERENCES `groups` (`group_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_user_groups_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
