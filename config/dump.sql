-- Created at 18.11.2025 20:58 using David Grudl MySQL Dump Utility
-- Host: localhost
-- MySQL Server: 11.8.3-MariaDB-log
-- Database: u245002075_ifapoia2

SET NAMES utf8mb4;
SET SQL_MODE='NO_AUTO_VALUE_ON_ZERO';
SET FOREIGN_KEY_CHECKS=0;
SET UNIQUE_CHECKS=0;
SET AUTOCOMMIT=0;
-- --------------------------------------------------------

DROP TABLE IF EXISTS `audit_log`;

CREATE TABLE `audit_log` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT,
  `action_user_id` int(11) DEFAULT NULL,
  `event_type` varchar(100) NOT NULL,
  `action_user_fullname` varchar(255) NOT NULL,
  `event_details` varchar(255) NOT NULL,
  `event_date` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`log_id`),
  KEY `action_user_id` (`action_user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=239 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

ALTER TABLE `audit_log` DISABLE KEYS;

INSERT INTO `audit_log` (`log_id`, `action_user_id`, `event_type`, `action_user_fullname`, `event_details`, `event_date`) VALUES
(1,	1,	'Alteração de Cargo de Membro',	'Breno Silveira Domingues',	'Cargo do usuário \'Breno Silveira Domingues\' (ID: #1) alterado para \'admin\' na comunidade \'Comunidade Teste\' (ID: #1).',	'2025-07-31 16:02:15'),
(2,	1,	'Edição de Comunidade',	'Breno Silveira Domingues',	'Comunidade \'Comunidade Teste\' (ID: #1) foi atualizada.',	'2025-07-31 16:02:17'),
(3,	1,	'Alteração de Cargo',	'Breno Silveira Domingues',	'Cargo do usuário \'Breno Silveira Domingues\' (ID: #1) alterado para \'membro\' na comunidade \'Comunidade Teste\' (ID: #1).',	'2025-07-31 16:04:31'),
(4,	1,	'Edição de Comunidade',	'Breno Silveira Domingues',	'Comunidade \'Comunidade Teste\' (ID: #1) foi atualizada.',	'2025-07-31 16:04:34'),
(5,	1,	'Nível Atualizado',	'Breno Silveira Domingues',	'Nível \'Técnico-Adm\' (ID: #2) foi atualizado.',	'2025-07-31 16:13:40'),
(6,	1,	'Post Deletado',	'Breno Silveira Domingues',	'Post (ID: #9) foi deletado pelo painel de administração.',	'2025-07-31 16:19:12'),
(7,	1,	'Novo Post',	'Breno Silveira Domingues',	'Usuário criou o post ID #13.',	'2025-07-31 16:21:46'),
(8,	1,	'Nova Tag',	'Breno Silveira Domingues',	'Tag \'teste de log\' (ID: #5) foi criada.',	'2025-07-31 16:24:05'),
(9,	1,	'Tag Editada',	'Breno Silveira Domingues',	'Tag (ID: #5) foi atualizada para \'teste de logggg\'.',	'2025-07-31 16:24:29'),
(10,	1,	'Nível de Usuário Alterado',	'Breno Silveira Domingues',	'Nível do usuário \'teste teste da silva\' (ID: #2) foi alterado para \'Discente\'.',	'2025-07-31 16:29:34'),
(11,	1,	'Nível de Usuário Alterado',	'Breno Silveira Domingues',	'Nível do usuário \'teste teste da silva\' (ID: #2) foi alterado para \'Técnico-Adm\'.',	'2025-07-31 16:29:49'),
(12,	1,	'Usuário Modificado',	'Breno Silveira Domingues',	'Nível do usuário \'teste teste da silva\' (ID: #2) foi alterado para \'Administrador\'.',	'2025-07-31 16:31:23'),
(13,	1,	'Novo Grupo',	'Breno Silveira Domingues',	'Grupo \'teste\' (ID: #1) foi criado.',	'2025-07-31 17:23:46'),
(14,	1,	'Usuário Modificado',	'Breno Silveira Domingues',	'Nível do usuário \'teste teste da silva\' (ID: #2) foi alterado para \'Técnico-Adm\'.',	'2025-07-31 17:35:45'),
(15,	1,	'Usuário Modificado',	'Breno Silveira Domingues',	'Nível do usuário \'teste teste da silva\' (ID: #2) foi alterado para \'Administrador\'.',	'2025-08-02 05:22:54'),
(16,	1,	'Usuário Modificado',	'Breno Silveira Domingues',	'Nível do usuário \'teste teste da silva\' (ID: #2) foi alterado para \'Técnico-Adm\'.',	'2025-08-02 05:22:59'),
(17,	1,	'Usuário Modificado',	'Breno Silveira Domingues',	'Nível do usuário \'teste teste da silva\' (ID: #2) foi alterado para \'Técnico-Adm\'.',	'2025-08-02 05:23:01'),
(18,	1,	'Novo Post',	'Breno Silveira Domingues',	'Usuário criou o post ID #14.',	'2025-08-04 02:06:20'),
(19,	1,	'Novo Post',	'Breno Silveira Domingues',	'Usuário criou o post ID #15.',	'2025-08-20 19:07:32'),
(20,	1,	'Novo Post',	'Breno Silveira Domingues',	'Usuário criou o post ID #16.',	'2025-08-30 17:12:02'),
(21,	1,	'Novo Post',	'Breno Silveira Domingues',	'Usuário criou o post ID #17.',	'2025-09-01 19:54:34'),
(22,	1,	'Novo Comentário',	'Breno Silveira Domingues',	'Usuário comentou no post ID #12. Comentário ID #1.',	'2025-10-14 14:47:58'),
(23,	1,	'Usuário Modificado',	'Breno Silveira Domingues',	'Nível do usuário \'Felipe Tozzi Bertochi\' (ID: #3) foi alterado para \'Administrador\'.',	'2025-10-15 17:19:28'),
(24,	3,	'Novo Post',	'Felipe Tozzi Bertochi',	'Usuário criou o post ID #18.',	'2025-10-15 18:16:56'),
(25,	1,	'Post Deletado',	'Breno Silveira Domingues',	'Post (ID: #18) foi deletado pelo painel de administração.',	'2025-10-20 16:59:43'),
(26,	1,	'Post Deletado',	'Breno Silveira Domingues',	'Post (ID: #17) foi deletado pelo painel de administração.',	'2025-10-20 16:59:59'),
(27,	1,	'Novo Post',	'Breno Silveira Domingues',	'Usuário criou o post ID #19.',	'2025-10-20 17:01:30'),
(28,	1,	'Novo Post',	'Breno Silveira Domingues',	'Usuário criou o post ID #20.',	'2025-10-20 17:01:41'),
(29,	1,	'Post Deletado',	'Breno Silveira Domingues',	'Post (ID: #20) foi deletado pelo painel de administração.',	'2025-10-20 17:01:58'),
(30,	1,	'Novo Post',	'Breno Silveira Domingues',	'Usuário criou o post ID #21.',	'2025-10-20 17:04:25'),
(31,	1,	'Novo Post',	'Breno Silveira Domingues',	'Usuário criou o post ID #22.',	'2025-10-20 17:07:28'),
(32,	1,	'Novo Post',	'Breno Silveira Domingues',	'Usuário criou o post ID #23.',	'2025-10-20 17:08:21'),
(33,	1,	'Novo Post',	'Breno Silveira Domingues',	'Usuário criou o post ID #24.',	'2025-10-20 17:09:29'),
(34,	1,	'Novo Post',	'Breno Silveira Domingues',	'Usuário criou o post ID #25.',	'2025-10-20 17:10:29'),
(35,	1,	'Novo Post',	'Breno Silveira Domingues',	'Usuário criou o post ID #26.',	'2025-10-20 17:11:11'),
(36,	1,	'Novo Post',	'Breno Silveira Domingues',	'Usuário criou o post ID #27.',	'2025-10-20 17:14:41'),
(37,	4,	'Novo Comentário',	'Aysla Fernanda dos Santos Vieira',	'Usuário comentou no post ID #27. Comentário ID #2.',	'2025-10-20 17:34:00'),
(38,	5,	'Novo Post',	'Bianca de Souza',	'Usuário criou o post ID #28.',	'2025-10-20 17:34:47'),
(39,	5,	'Novo Post',	'Bianca de Souza',	'Usuário criou o post ID #29.',	'2025-10-20 17:35:15'),
(40,	5,	'Novo Post',	'Bianca de Souza',	'Usuário criou o post ID #30.',	'2025-10-20 17:35:43'),
(41,	4,	'Novo Post',	'Aysla Fernanda dos Santos Vieira',	'Usuário criou o post ID #31.',	'2025-10-20 17:36:25'),
(42,	6,	'Novo Post',	'Beatriz Victória Silva',	'Usuário criou o post ID #32.',	'2025-10-20 17:36:33'),
(43,	6,	'Novo Post',	'Beatriz Victória Silva',	'Usuário criou o post ID #33.',	'2025-10-20 17:36:40'),
(44,	4,	'Novo Comentário',	'Aysla Fernanda dos Santos Vieira',	'Usuário comentou no post ID #33. Comentário ID #3.',	'2025-10-20 17:36:51'),
(45,	1,	'Novo Post',	'Breno Silveira Domingues',	'Usuário criou o post ID #34.',	'2025-10-20 17:37:23'),
(46,	1,	'Novo Post',	'Breno Silveira Domingues',	'Usuário criou o post ID #35.',	'2025-10-20 17:38:01'),
(47,	NULL,	'Novo Post',	'Usuário',	'Usuário criou o post ID #36.',	'2025-10-20 17:38:31'),
(48,	1,	'Novo Post',	'Breno Silveira Domingues',	'Usuário criou o post ID #37.',	'2025-10-20 17:38:45'),
(49,	7,	'Novo Comentário',	'Eduardo Alves da Silva',	'Usuário comentou no post ID #33. Comentário ID #4.',	'2025-10-20 17:42:19'),
(50,	4,	'Novo Comentário',	'Aysla Fernanda dos Santos Vieira',	'Usuário comentou no post ID #30. Comentário ID #5.',	'2025-10-20 17:42:55'),
(51,	8,	'Novo Post',	'Maria Luiza da Mata Santos Oliveira',	'Usuário criou o post ID #38.',	'2025-10-20 17:43:23'),
(52,	1,	'Novo Post',	'Breno Silveira Domingues',	'Usuário criou o post ID #39.',	'2025-10-20 17:43:36'),
(53,	7,	'Novo Post',	'Eduardo Alves da Silva',	'Usuário criou o post ID #40.',	'2025-10-20 17:44:02'),
(54,	7,	'Novo Post',	'Eduardo Alves da Silva',	'Usuário criou o post ID #41.',	'2025-10-20 17:44:05'),
(55,	8,	'Novo Comentário',	'Maria Luiza da Mata Santos Oliveira',	'Usuário comentou no post ID #31. Comentário ID #6.',	'2025-10-20 17:44:14'),
(56,	4,	'Novo Comentário',	'Aysla Fernanda dos Santos Vieira',	'Usuário comentou no post ID #41. Comentário ID #7.',	'2025-10-20 17:44:35'),
(57,	8,	'Novo Comentário',	'Maria Luiza da Mata Santos Oliveira',	'Usuário comentou no post ID #41. Comentário ID #8.',	'2025-10-20 17:44:58'),
(58,	4,	'Novo Comentário',	'Aysla Fernanda dos Santos Vieira',	'Usuário comentou no post ID #38. Comentário ID #9.',	'2025-10-20 17:45:04'),
(59,	1,	'Novo Comentário',	'Breno Silveira Domingues',	'Usuário comentou no post ID #41. Comentário ID #10.',	'2025-10-20 17:45:59'),
(60,	8,	'Novo Post',	'Maria Luiza da Mata Santos Oliveira',	'Usuário criou o post ID #42.',	'2025-10-20 17:46:43'),
(61,	4,	'Novo Post',	'Aysla Fernanda dos Santos Vieira',	'Usuário criou o post ID #43.',	'2025-10-20 17:46:49'),
(62,	4,	'Novo Comentário',	'Aysla Fernanda dos Santos Vieira',	'Usuário comentou no post ID #42. Comentário ID #11.',	'2025-10-20 17:47:10'),
(63,	4,	'Novo Post',	'Aysla Fernanda dos Santos Vieira',	'Usuário criou o post ID #44.',	'2025-10-20 17:48:23'),
(64,	8,	'Novo Post',	'Maria Luiza da Mata Santos Oliveira',	'Usuário criou o post ID #45.',	'2025-10-20 17:49:16'),
(65,	2,	'Novo Comentário',	'teste teste da silva',	'Usuário comentou no post ID #44. Comentário ID #12.',	'2025-10-20 17:49:56'),
(66,	4,	'Novo Post',	'Aysla Fernanda dos Santos Vieira',	'Usuário criou o post ID #46.',	'2025-10-20 17:50:24'),
(67,	1,	'Novo Comentário',	'Breno Silveira Domingues',	'Usuário comentou no post ID #46. Comentário ID #13.',	'2025-10-20 17:51:12'),
(68,	4,	'Novo Post',	'Aysla Fernanda dos Santos Vieira',	'Usuário criou o post ID #47.',	'2025-10-20 17:51:12'),
(69,	7,	'Novo Comentário',	'Eduardo Alves da Silva',	'Usuário comentou no post ID #46. Comentário ID #14.',	'2025-10-20 17:52:30'),
(70,	2,	'Novo Comentário',	'teste teste da silva',	'Usuário comentou no post ID #42. Comentário ID #15.',	'2025-10-20 17:52:40'),
(71,	1,	'Novo Post',	'Breno Silveira Domingues',	'Usuário criou o post ID #48.',	'2025-10-20 17:53:11'),
(72,	8,	'Novo Post',	'Maria Luiza da Mata Santos Oliveira',	'Usuário criou o post ID #49.',	'2025-10-20 17:53:18'),
(73,	2,	'Novo Comentário',	'teste teste da silva',	'Usuário comentou no post ID #41. Comentário ID #16.',	'2025-10-20 17:53:59'),
(74,	10,	'Novo Post',	'Luiz Gustavo Pizara',	'Usuário criou o post ID #50.',	'2025-10-20 17:55:14'),
(75,	1,	'Novo Comentário',	'Breno Silveira Domingues',	'Usuário comentou no post ID #50. Comentário ID #17.',	'2025-10-20 17:57:16'),
(76,	1,	'Novo Comentário',	'Breno Silveira Domingues',	'Usuário comentou no post ID #46. Comentário ID #18.',	'2025-10-20 17:57:40'),
(77,	2,	'Novo Post',	'teste teste da silva',	'Usuário criou o post ID #51.',	'2025-10-20 17:57:44'),
(78,	8,	'Novo Post',	'Maria Luiza da Mata Santos Oliveira',	'Usuário criou o post ID #52.',	'2025-10-20 17:57:50'),
(79,	4,	'Novo Post',	'Aysla Fernanda dos Santos Vieira',	'Usuário criou o post ID #53.',	'2025-10-20 17:58:23'),
(80,	7,	'Novo Post',	'Eduardo Alves da Silva',	'Usuário criou o post ID #54.',	'2025-10-20 17:58:51'),
(81,	8,	'Novo Post',	'Maria Luiza da Mata Santos Oliveira',	'Usuário criou o post ID #55.',	'2025-10-20 17:59:18'),
(82,	8,	'Novo Post',	'Maria Luiza da Mata Santos Oliveira',	'Usuário criou o post ID #56.',	'2025-10-20 18:00:34'),
(83,	1,	'Novo Post',	'Breno Silveira Domingues',	'Usuário criou o post ID #57.',	'2025-10-20 18:00:34'),
(84,	2,	'Novo Post',	'teste teste da silva',	'Usuário criou o post ID #58.',	'2025-10-20 18:00:40'),
(85,	7,	'Novo Post',	'Eduardo Alves da Silva',	'Usuário criou o post ID #59.',	'2025-10-20 18:00:42'),
(86,	8,	'Novo Post',	'Maria Luiza da Mata Santos Oliveira',	'Usuário criou o post ID #60.',	'2025-10-20 18:01:36'),
(87,	4,	'Novo Comentário',	'Aysla Fernanda dos Santos Vieira',	'Usuário comentou no post ID #59. Comentário ID #19.',	'2025-10-20 18:01:49'),
(88,	7,	'Novo Comentário',	'Eduardo Alves da Silva',	'Usuário comentou no post ID #60. Comentário ID #20.',	'2025-10-20 18:01:52'),
(89,	4,	'Novo Post',	'Aysla Fernanda dos Santos Vieira',	'Usuário criou o post ID #61.',	'2025-10-20 18:02:45'),
(90,	8,	'Novo Post',	'Maria Luiza da Mata Santos Oliveira',	'Usuário criou o post ID #62.',	'2025-10-20 18:03:22'),
(91,	2,	'Novo Post',	'teste teste da silva',	'Usuário criou o post ID #63.',	'2025-10-20 18:04:59'),
(92,	8,	'Novo Post',	'Maria Luiza da Mata Santos Oliveira',	'Usuário criou o post ID #64.',	'2025-10-20 18:05:09'),
(93,	4,	'Novo Post',	'Aysla Fernanda dos Santos Vieira',	'Usuário criou o post ID #65.',	'2025-10-20 18:06:05'),
(94,	2,	'Novo Post',	'teste teste da silva',	'Usuário criou o post ID #66.',	'2025-10-20 18:06:25'),
(95,	2,	'Novo Post',	'teste teste da silva',	'Usuário criou o post ID #67.',	'2025-10-20 18:06:39'),
(96,	8,	'Novo Post',	'Maria Luiza da Mata Santos Oliveira',	'Usuário criou o post ID #68.',	'2025-10-20 18:06:46'),
(97,	6,	'Novo Post',	'Beatriz Victória Silva',	'Usuário criou o post ID #69.',	'2025-10-20 18:07:04'),
(98,	2,	'Novo Post',	'teste teste da silva',	'Usuário criou o post ID #70.',	'2025-10-20 18:07:35'),
(99,	1,	'Post Deletado',	'Breno Silveira Domingues',	'Post (ID: #69) foi deletado pelo painel de administração.',	'2025-10-20 18:07:46'),
(100,	2,	'Novo Post',	'teste teste da silva',	'Usuário criou o post ID #71.',	'2025-10-20 18:07:54'),
(101,	2,	'Novo Post',	'teste teste da silva',	'Usuário criou o post ID #72.',	'2025-10-20 18:08:17'),
(102,	4,	'Novo Post',	'Aysla Fernanda dos Santos Vieira',	'Usuário criou o post ID #73.',	'2025-10-20 18:10:02'),
(103,	2,	'Novo Post',	'teste teste da silva',	'Usuário criou o post ID #74.',	'2025-10-20 18:10:06'),
(104,	8,	'Novo Post',	'Maria Luiza da Mata Santos Oliveira',	'Usuário criou o post ID #75.',	'2025-10-20 18:10:21'),
(105,	7,	'Novo Post',	'Eduardo Alves da Silva',	'Usuário criou o post ID #76.',	'2025-10-20 18:10:24'),
(106,	4,	'Novo Post',	'Aysla Fernanda dos Santos Vieira',	'Usuário criou o post ID #77.',	'2025-10-20 18:11:54'),
(107,	8,	'Novo Post',	'Maria Luiza da Mata Santos Oliveira',	'Usuário criou o post ID #78.',	'2025-10-20 18:12:21'),
(108,	2,	'Novo Post',	'teste teste da silva',	'Usuário criou o post ID #79.',	'2025-10-20 18:12:59'),
(109,	1,	'Usuário Modificado',	'Breno Silveira Domingues',	'Nível do usuário \'Luiz Gustavo Pizara\' (ID: #10) foi alterado para \'Administrador\'.',	'2025-10-20 18:14:17'),
(110,	1,	'Usuário Modificado',	'Breno Silveira Domingues',	'Nível do usuário \'Luiz Gustavo Pizara\' (ID: #10) foi alterado para \'Administrador\'.',	'2025-10-20 18:15:43'),
(111,	1,	'Post Deletado',	'Breno Silveira Domingues',	'Post (ID: #76) foi deletado pelo painel de administração.',	'2025-10-20 18:16:07'),
(112,	10,	'Post Deletado',	'Luiz Gustavo Pizara',	'Post (ID: #78) foi deletado pelo painel de administração.',	'2025-10-20 18:17:00'),
(113,	10,	'Post Deletado',	'Luiz Gustavo Pizara',	'Post (ID: #75) foi deletado pelo painel de administração.',	'2025-10-20 18:17:03'),
(114,	10,	'Post Deletado',	'Luiz Gustavo Pizara',	'Post (ID: #70) foi deletado pelo painel de administração.',	'2025-10-20 18:17:08'),
(115,	10,	'Post Deletado',	'Luiz Gustavo Pizara',	'Post (ID: #68) foi deletado pelo painel de administração.',	'2025-10-20 18:17:11'),
(116,	10,	'Post Deletado',	'Luiz Gustavo Pizara',	'Post (ID: #64) foi deletado pelo painel de administração.',	'2025-10-20 18:17:21'),
(117,	10,	'Post Deletado',	'Luiz Gustavo Pizara',	'Post (ID: #77) foi deletado pelo painel de administração.',	'2025-10-20 18:17:26'),
(118,	10,	'Post Deletado',	'Luiz Gustavo Pizara',	'Post (ID: #65) foi deletado pelo painel de administração.',	'2025-10-20 18:17:31'),
(119,	2,	'Novo Post',	'teste teste da silva',	'Usuário criou o post ID #80.',	'2025-10-20 18:17:34'),
(120,	10,	'Post Deletado',	'Luiz Gustavo Pizara',	'Post (ID: #62) foi deletado pelo painel de administração.',	'2025-10-20 18:17:35'),
(121,	10,	'Post Deletado',	'Luiz Gustavo Pizara',	'Post (ID: #60) foi deletado pelo painel de administração.',	'2025-10-20 18:17:40'),
(122,	10,	'Post Deletado',	'Luiz Gustavo Pizara',	'Post (ID: #56) foi deletado pelo painel de administração.',	'2025-10-20 18:17:44'),
(123,	10,	'Post Deletado',	'Luiz Gustavo Pizara',	'Post (ID: #63) foi deletado pelo painel de administração.',	'2025-10-20 18:17:52'),
(124,	10,	'Post Deletado',	'Luiz Gustavo Pizara',	'Post (ID: #36) foi deletado pelo painel de administração.',	'2025-10-20 18:18:07'),
(125,	10,	'Post Deletado',	'Luiz Gustavo Pizara',	'Post (ID: #55) foi deletado pelo painel de administração.',	'2025-10-20 18:18:32'),
(126,	2,	'Novo Post',	'teste teste da silva',	'Usuário criou o post ID #81.',	'2025-10-20 18:19:01'),
(127,	10,	'Post Deletado',	'Luiz Gustavo Pizara',	'Post (ID: #42) foi deletado pelo painel de administração.',	'2025-10-20 18:19:03'),
(128,	10,	'Post Deletado',	'Luiz Gustavo Pizara',	'Post (ID: #47) foi deletado pelo painel de administração.',	'2025-10-20 18:19:10'),
(129,	10,	'Post Deletado',	'Luiz Gustavo Pizara',	'Post (ID: #52) foi deletado pelo painel de administração.',	'2025-10-20 18:19:19'),
(130,	10,	'Post Deletado',	'Luiz Gustavo Pizara',	'Post (ID: #35) foi deletado pelo painel de administração.',	'2025-10-20 18:19:47'),
(131,	10,	'Tag Excluída',	'Luiz Gustavo Pizara',	'Tag \'teste2\' (ID: #2) foi excluída.',	'2025-10-20 18:21:02'),
(132,	10,	'Tag Excluída',	'Luiz Gustavo Pizara',	'Tag \'ablablablaA\' (ID: #4) foi excluída.',	'2025-10-20 18:21:05'),
(133,	8,	'Novo Post',	'Maria Luiza da Mata Santos Oliveira',	'Usuário criou o post ID #82.',	'2025-10-20 18:22:09'),
(134,	8,	'Novo Post',	'Maria Luiza da Mata Santos Oliveira',	'Usuário criou o post ID #83.',	'2025-10-20 18:23:10'),
(135,	2,	'Novo Comentário',	'teste teste da silva',	'Usuário comentou no post ID #82. Comentário ID #21.',	'2025-10-20 18:23:18'),
(136,	13,	'Novo Post',	'Pedro Otávio Setten de Almeida',	'Usuário criou o post ID #84.',	'2025-10-20 18:23:43'),
(137,	8,	'Novo Post',	'Maria Luiza da Mata Santos Oliveira',	'Usuário criou o post ID #85.',	'2025-10-20 18:24:15'),
(138,	7,	'Novo Post',	'Eduardo Alves da Silva',	'Usuário criou o post ID #86.',	'2025-10-20 18:24:53'),
(139,	2,	'Novo Post',	'teste teste da silva',	'Usuário criou o post ID #87.',	'2025-10-20 18:25:01'),
(140,	8,	'Novo Post',	'Maria Luiza da Mata Santos Oliveira',	'Usuário criou o post ID #88.',	'2025-10-20 18:25:07'),
(141,	7,	'Novo Post',	'Eduardo Alves da Silva',	'Usuário criou o post ID #89.',	'2025-10-20 18:25:07'),
(142,	2,	'Novo Post',	'teste teste da silva',	'Usuário criou o post ID #90.',	'2025-10-20 18:25:36'),
(143,	8,	'Novo Post',	'Maria Luiza da Mata Santos Oliveira',	'Usuário criou o post ID #91.',	'2025-10-20 18:25:42'),
(144,	7,	'Novo Post',	'Eduardo Alves da Silva',	'Usuário criou o post ID #92.',	'2025-10-20 18:25:50'),
(145,	2,	'Novo Post',	'teste teste da silva',	'Usuário criou o post ID #93.',	'2025-10-20 18:26:21'),
(146,	8,	'Novo Post',	'Maria Luiza da Mata Santos Oliveira',	'Usuário criou o post ID #94.',	'2025-10-20 18:27:35'),
(147,	8,	'Novo Post',	'Maria Luiza da Mata Santos Oliveira',	'Usuário criou o post ID #95.',	'2025-10-20 18:28:26'),
(148,	8,	'Novo Post',	'Maria Luiza da Mata Santos Oliveira',	'Usuário criou o post ID #96.',	'2025-10-20 18:29:31'),
(149,	1,	'Novo Post',	'Breno Silveira Domingues',	'Usuário criou o post ID #97.',	'2025-10-20 18:31:00'),
(150,	8,	'Novo Post',	'Maria Luiza da Mata Santos Oliveira',	'Usuário criou o post ID #98.',	'2025-10-20 18:31:06'),
(151,	1,	'Novo Post',	'Breno Silveira Domingues',	'Usuário criou o post ID #99.',	'2025-10-20 18:32:23'),
(152,	8,	'Novo Post',	'Maria Luiza da Mata Santos Oliveira',	'Usuário criou o post ID #100.',	'2025-10-20 18:32:34'),
(153,	8,	'Novo Post',	'Maria Luiza da Mata Santos Oliveira',	'Usuário criou o post ID #101.',	'2025-10-20 18:33:31'),
(154,	2,	'Novo Post',	'teste teste da silva',	'Usuário criou o post ID #102.',	'2025-10-20 18:34:17'),
(155,	2,	'Novo Post',	'teste teste da silva',	'Usuário criou o post ID #103.',	'2025-10-20 18:34:26'),
(156,	2,	'Novo Post',	'teste teste da silva',	'Usuário criou o post ID #104.',	'2025-10-20 18:34:32'),
(157,	2,	'Novo Post',	'teste teste da silva',	'Usuário criou o post ID #105.',	'2025-10-20 18:34:40'),
(158,	2,	'Novo Post',	'teste teste da silva',	'Usuário criou o post ID #106.',	'2025-10-20 18:34:52'),
(159,	2,	'Novo Post',	'teste teste da silva',	'Usuário criou o post ID #107.',	'2025-10-20 18:34:58'),
(160,	2,	'Novo Post',	'teste teste da silva',	'Usuário criou o post ID #108.',	'2025-10-20 18:35:05'),
(161,	8,	'Novo Post',	'Maria Luiza da Mata Santos Oliveira',	'Usuário criou o post ID #109.',	'2025-10-20 18:35:24'),
(162,	8,	'Novo Post',	'Maria Luiza da Mata Santos Oliveira',	'Usuário criou o post ID #110.',	'2025-10-20 18:36:55'),
(163,	2,	'Novo Post',	'teste teste da silva',	'Usuário criou o post ID #111.',	'2025-10-20 18:37:05'),
(164,	8,	'Novo Post',	'Maria Luiza da Mata Santos Oliveira',	'Usuário criou o post ID #112.',	'2025-10-20 18:37:27'),
(165,	13,	'Novo Post',	'Pedro Otávio Setten de Almeida',	'Usuário criou o post ID #113.',	'2025-10-20 18:37:59'),
(166,	8,	'Novo Post',	'Maria Luiza da Mata Santos Oliveira',	'Usuário criou o post ID #114.',	'2025-10-20 18:38:11'),
(167,	8,	'Novo Post',	'Maria Luiza da Mata Santos Oliveira',	'Usuário criou o post ID #115.',	'2025-10-20 18:39:29'),
(168,	8,	'Novo Post',	'Maria Luiza da Mata Santos Oliveira',	'Usuário criou o post ID #116.',	'2025-10-20 18:40:13'),
(169,	13,	'Novo Post',	'Pedro Otávio Setten de Almeida',	'Usuário criou o post ID #117.',	'2025-10-20 18:41:13'),
(170,	7,	'Novo Comentário',	'Eduardo Alves da Silva',	'Usuário comentou no post ID #117. Comentário ID #22.',	'2025-10-20 18:42:14'),
(171,	13,	'Novo Post',	'Pedro Otávio Setten de Almeida',	'Usuário criou o post ID #118.',	'2025-10-20 18:43:22'),
(172,	7,	'Novo Post',	'Eduardo Alves da Silva',	'Usuário criou o post ID #119.',	'2025-10-20 18:43:23'),
(173,	2,	'Novo Comentário',	'teste teste da silva',	'Usuário comentou no post ID #119. Comentário ID #23.',	'2025-10-20 18:43:47'),
(174,	5,	'Novo Post',	'Bianca de Souza',	'Usuário criou o post ID #120.',	'2025-10-20 18:43:49'),
(175,	1,	'Novo Post',	'Breno Silveira Domingues',	'Usuário criou o post ID #121.',	'2025-10-20 18:45:06'),
(176,	5,	'Novo Post',	'Bianca de Souza',	'Usuário criou o post ID #122.',	'2025-10-20 18:46:21'),
(177,	2,	'Novo Post',	'teste teste da silva',	'Usuário criou o post ID #123.',	'2025-10-20 18:48:30'),
(178,	7,	'Novo Post',	'Eduardo Alves da Silva',	'Usuário criou o post ID #124.',	'2025-10-20 18:49:19'),
(179,	5,	'Novo Post',	'Bianca de Souza',	'Usuário criou o post ID #125.',	'2025-10-20 18:49:39'),
(180,	8,	'Novo Post',	'Maria Luiza da Mata Santos Oliveira',	'Usuário criou o post ID #126.',	'2025-10-20 18:50:15'),
(181,	2,	'Novo Post',	'teste teste da silva',	'Usuário criou o post ID #127.',	'2025-10-20 18:50:43'),
(182,	8,	'Novo Post',	'Maria Luiza da Mata Santos Oliveira',	'Usuário criou o post ID #128.',	'2025-10-20 18:51:00'),
(183,	1,	'Usuário Modificado',	'Breno Silveira Domingues',	'Nível do usuário \'teste teste da silva\' (ID: #2) foi alterado para \'Administrador\'.',	'2025-10-20 18:52:17'),
(184,	8,	'Novo Post',	'Maria Luiza da Mata Santos Oliveira',	'Usuário criou o post ID #129.',	'2025-10-20 18:53:08'),
(185,	10,	'Post Deletado',	'Luiz Gustavo Pizara',	'Post (ID: #129) foi deletado pelo painel de administração.',	'2025-10-20 18:54:33'),
(186,	10,	'Post Deletado',	'Luiz Gustavo Pizara',	'Post (ID: #128) foi deletado pelo painel de administração.',	'2025-10-20 18:54:37'),
(187,	8,	'Novo Post',	'Maria Luiza da Mata Santos Oliveira',	'Usuário criou o post ID #130.',	'2025-10-20 18:54:41'),
(188,	10,	'Post Deletado',	'Luiz Gustavo Pizara',	'Post (ID: #125) foi deletado pelo painel de administração.',	'2025-10-20 18:54:49'),
(189,	10,	'Post Deletado',	'Luiz Gustavo Pizara',	'Post (ID: #126) foi deletado pelo painel de administração.',	'2025-10-20 18:54:54'),
(190,	10,	'Post Deletado',	'Luiz Gustavo Pizara',	'Post (ID: #123) foi deletado pelo painel de administração.',	'2025-10-20 18:55:01'),
(191,	10,	'Post Deletado',	'Luiz Gustavo Pizara',	'Post (ID: #122) foi deletado pelo painel de administração.',	'2025-10-20 18:55:07'),
(192,	10,	'Post Deletado',	'Luiz Gustavo Pizara',	'Post (ID: #120) foi deletado pelo painel de administração.',	'2025-10-20 18:55:18'),
(193,	10,	'Post Deletado',	'Luiz Gustavo Pizara',	'Post (ID: #111) foi deletado pelo painel de administração.',	'2025-10-20 18:55:36'),
(194,	8,	'Novo Post',	'Maria Luiza da Mata Santos Oliveira',	'Usuário criou o post ID #131.',	'2025-10-20 18:55:51'),
(195,	8,	'Novo Post',	'Maria Luiza da Mata Santos Oliveira',	'Usuário criou o post ID #132.',	'2025-10-20 18:56:31'),
(196,	16,	'Novo Post',	'Aline',	'Usuário criou o post ID #133.',	'2025-10-20 18:56:55'),
(197,	8,	'Novo Post',	'Maria Luiza da Mata Santos Oliveira',	'Usuário criou o post ID #134.',	'2025-10-20 18:57:38'),
(198,	8,	'Novo Post',	'Maria Luiza da Mata Santos Oliveira',	'Usuário criou o post ID #135.',	'2025-10-20 18:58:28'),
(199,	16,	'Novo Post',	'Aline',	'Usuário criou o post ID #136.',	'2025-10-20 18:58:34'),
(200,	16,	'Novo Comentário',	'Aline',	'Usuário comentou no post ID #130. Comentário ID #24.',	'2025-10-20 18:59:05'),
(201,	8,	'Novo Post',	'Maria Luiza da Mata Santos Oliveira',	'Usuário criou o post ID #137.',	'2025-10-20 18:59:44'),
(202,	16,	'Novo Post',	'Aline',	'Usuário criou o post ID #138.',	'2025-10-20 19:00:42'),
(203,	8,	'Novo Post',	'Maria Luiza da Mata Santos Oliveira',	'Usuário criou o post ID #139.',	'2025-10-20 19:01:37'),
(204,	19,	'Novo Comentário',	'Luis Felipe',	'Usuário comentou no post ID #117. Comentário ID #25.',	'2025-10-21 22:04:31'),
(205,	NULL,	'Novo Post',	'Usuário',	'Usuário criou o post ID #140.',	'2025-11-05 17:19:15'),
(206,	2,	'Novo Post',	'teste teste da silva',	'Usuário criou o post ID #141.',	'2025-11-05 17:19:38'),
(207,	2,	'Novo Post',	'teste teste da silva',	'Usuário criou o post ID #142.',	'2025-11-05 17:20:13'),
(208,	NULL,	'Novo Post',	'Usuário',	'Usuário criou o post ID #143.',	'2025-11-10 14:46:47'),
(209,	NULL,	'Novo Post',	'Usuário',	'Usuário criou o post ID #144.',	'2025-11-11 20:48:29'),
(210,	1,	'Novo Post',	'Breno Silveira Domingues',	'Usuário criou o post ID #145.',	'2025-11-12 16:58:39'),
(211,	NULL,	'Novo Post',	'Usuário',	'Usuário criou o post ID #146.',	'2025-11-12 19:03:52'),
(212,	22,	'Novo Post',	'Beatriz Belotti Bueno',	'Usuário criou o post ID #147.',	'2025-11-12 19:14:17'),
(213,	22,	'Novo Comentário',	'Beatriz Belotti Bueno',	'Usuário comentou no post ID #147. Comentário ID #26.',	'2025-11-12 19:16:09'),
(214,	NULL,	'Novo Post',	'Usuário',	'Usuário criou o post ID #148.',	'2025-11-14 02:09:53'),
(215,	NULL,	'Novo Post',	'Usuário',	'Usuário criou o post ID #149.',	'2025-11-14 02:10:12'),
(216,	10,	'Nova Comunidade',	'Luiz Gustavo Pizara',	'Comunidade \'Historia\' (ID: #5) foi criada.',	'2025-11-17 22:57:26'),
(217,	10,	'Post Deletado',	'Luiz Gustavo Pizara',	'Post (ID: #146) foi deletado pelo painel de administração.',	'2025-11-17 22:59:05'),
(218,	10,	'Post Deletado',	'Luiz Gustavo Pizara',	'Post (ID: #144) foi deletado pelo painel de administração.',	'2025-11-17 22:59:09'),
(219,	10,	'Post Deletado',	'Luiz Gustavo Pizara',	'Post (ID: #143) foi deletado pelo painel de administração.',	'2025-11-17 22:59:11'),
(220,	10,	'Post Deletado',	'Luiz Gustavo Pizara',	'Post (ID: #149) foi deletado pelo painel de administração.',	'2025-11-17 22:59:13'),
(221,	10,	'Novo Post',	'Luiz Gustavo Pizara',	'Usuário criou o post ID #150.',	'2025-11-17 23:03:09'),
(222,	10,	'Post Deletado',	'Luiz Gustavo Pizara',	'Post (ID: #139) foi deletado pelo painel de administração.',	'2025-11-17 23:04:08'),
(223,	10,	'Post Deletado',	'Luiz Gustavo Pizara',	'Post (ID: #95) foi deletado pelo painel de administração.',	'2025-11-17 23:04:48'),
(224,	NULL,	'Novo Post',	'Usuário',	'Usuário criou o post ID #151.',	'2025-11-18 00:16:25'),
(225,	1,	'Novo Post',	'Breno Silveira Domingues',	'Usuário criou o post ID #152.',	'2025-11-18 00:19:46'),
(226,	1,	'Novo Post',	'Breno Silveira Domingues',	'Usuário criou o post ID #153.',	'2025-11-18 00:33:08'),
(227,	10,	'Novo Post',	'Luiz Gustavo Pizara',	'Usuário criou o post ID #154.',	'2025-11-18 14:30:51'),
(228,	10,	'Post Deletado',	'Luiz Gustavo Pizara',	'Post (ID: #154) foi deletado pelo painel de administração.',	'2025-11-18 14:30:59'),
(229,	10,	'Post Deletado',	'Luiz Gustavo Pizara',	'Post (ID: #154) foi deletado pelo painel de administração.',	'2025-11-18 14:31:04'),
(230,	1,	'Novo Comentário',	'Breno Silveira Domingues',	'Usuário comentou no post ID #153. Comentário ID #27.',	'2025-11-18 17:08:35'),
(231,	1,	'Novo Comentário',	'Breno Silveira Domingues',	'Comentário ID #28 no post #153',	'2025-11-18 17:56:57'),
(232,	1,	'Novo Comentário',	'Breno Silveira Domingues',	'Comentário ID #29 no post #153',	'2025-11-18 17:58:23'),
(233,	1,	'Novo Comentário',	'Breno Silveira Domingues',	'Usuário comentou no post ID #153. Comentário ID #30.',	'2025-11-18 18:08:49'),
(234,	1,	'Novo Comentário',	'Breno Silveira Domingues',	'Comentário ID #31 no post #153',	'2025-11-18 18:23:46'),
(235,	1,	'Novo Comentário',	'Breno Silveira Domingues',	'Comentário ID #32 no post #153',	'2025-11-18 18:25:53'),
(236,	1,	'Novo Comentário',	'Breno Silveira Domingues',	'Comentário ID #33 no post #153',	'2025-11-18 18:32:53'),
(237,	1,	'Novo Comentário',	'Breno Silveira Domingues',	'Comentário ID #34 no post #153',	'2025-11-18 18:57:53'),
(238,	1,	'Novo Comentário',	'Breno Silveira Domingues',	'Comentário ID #35 no post #153',	'2025-11-18 20:26:46');
ALTER TABLE `audit_log` ENABLE KEYS;



-- --------------------------------------------------------

DROP TABLE IF EXISTS `bookmarks`;

CREATE TABLE `bookmarks` (
  `user_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `saved_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`user_id`,`post_id`),
  KEY `fk_bookmarks_post` (`post_id`),
  CONSTRAINT `fk_bookmarks_post` FOREIGN KEY (`post_id`) REFERENCES `posts` (`post_id`) ON DELETE CASCADE,
  CONSTRAINT `fk_bookmarks_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

ALTER TABLE `bookmarks` DISABLE KEYS;

INSERT INTO `bookmarks` (`user_id`, `post_id`, `saved_at`) VALUES
(1,	147,	'2025-11-18 20:30:52');
ALTER TABLE `bookmarks` ENABLE KEYS;



-- --------------------------------------------------------

DROP TABLE IF EXISTS `comments`;

CREATE TABLE `comments` (
  `comment_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`comment_id`),
  KEY `fk_comments_user` (`user_id`),
  KEY `fk_comments_post` (`post_id`),
  CONSTRAINT `fk_comments_post` FOREIGN KEY (`post_id`) REFERENCES `posts` (`post_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_comments_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

ALTER TABLE `comments` DISABLE KEYS;

INSERT INTO `comments` (`comment_id`, `user_id`, `post_id`, `content`, `created_at`) VALUES
(1,	1,	12,	'TESTE',	'2025-10-14 14:47:58'),
(2,	4,	27,	'etset',	'2025-10-20 17:34:00'),
(3,	4,	33,	'fiu fiu',	'2025-10-20 17:36:51'),
(4,	7,	33,	'Desista dos seus sonhos Aysla',	'2025-10-20 17:42:19'),
(5,	4,	30,	'eu quero em física',	'2025-10-20 17:42:55'),
(6,	8,	31,	'15 reais',	'2025-10-20 17:44:14'),
(7,	4,	41,	'estão nos sufocando',	'2025-10-20 17:44:35'),
(8,	8,	41,	'De 15 reais',	'2025-10-20 17:44:58'),
(9,	4,	38,	'burra',	'2025-10-20 17:45:04'),
(10,	1,	41,	'concordo',	'2025-10-20 17:45:59'),
(12,	2,	44,	'[======[]::::::::::::::::::::::> (facada no rato do pt)',	'2025-10-20 17:49:56'),
(13,	1,	46,	'fake',	'2025-10-20 17:51:12'),
(14,	7,	46,	'para de postar coisas goré, irei te processar',	'2025-10-20 17:52:30'),
(16,	2,	41,	'A fome tem IF',	'2025-10-20 17:53:59'),
(17,	1,	50,	'eu tbm T-T',	'2025-10-20 17:57:16'),
(18,	1,	46,	'pt pt do demo',	'2025-10-20 17:57:40'),
(19,	4,	59,	'essa pessoa é vc',	'2025-10-20 18:01:49'),
(21,	2,	82,	'?',	'2025-10-20 18:23:18'),
(22,	7,	117,	'Euuuuu >.<',	'2025-10-20 18:42:14'),
(23,	2,	119,	'livre bolsonarooooooooooooooooooooo',	'2025-10-20 18:43:47'),
(24,	16,	130,	'Nossa bem saliente',	'2025-10-20 18:59:05'),
(25,	19,	117,	'nós',	'2025-10-21 22:04:31'),
(26,	22,	147,	'louca',	'2025-11-12 19:16:09'),
(27,	1,	153,	'teste comentário',	'2025-11-18 17:08:35'),
(28,	1,	153,	'teste comentario 2',	'2025-11-18 17:56:57'),
(29,	1,	153,	'teste comentario 3',	'2025-11-18 17:58:23'),
(30,	1,	153,	'teste comentario 4',	'2025-11-18 18:08:49'),
(31,	1,	153,	'teste comentário 5',	'2025-11-18 18:23:46'),
(32,	1,	153,	'teste comentario 5',	'2025-11-18 18:25:53'),
(33,	1,	153,	'teste comentário 7',	'2025-11-18 18:32:53'),
(34,	1,	153,	'teste comentário 8',	'2025-11-18 18:57:53'),
(35,	1,	153,	'teste comentário 9',	'2025-11-18 20:26:46');
ALTER TABLE `comments` ENABLE KEYS;



-- --------------------------------------------------------

DROP TABLE IF EXISTS `communities`;

CREATE TABLE `communities` (
  `community_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` varchar(255) NOT NULL,
  `banner_url` varchar(255) DEFAULT NULL,
  `view_count` int(11) NOT NULL DEFAULT 0,
  `image_url` varchar(255) DEFAULT NULL,
  `creator_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`community_id`),
  KEY `fk_communities_creator` (`creator_id`),
  CONSTRAINT `fk_communities_creator` FOREIGN KEY (`creator_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

ALTER TABLE `communities` DISABLE KEYS;

INSERT INTO `communities` (`community_id`, `name`, `description`, `banner_url`, `view_count`, `image_url`, `creator_id`, `created_at`) VALUES
(1,	'Comunidade Teste',	'teste teste teste 123 <3',	NULL,	29,	NULL,	1,	'2025-07-28 18:16:58'),
(2,	'Comunidade Teste 2',	'aaaaaaaaaaaa',	NULL,	3,	NULL,	1,	'2025-07-29 13:36:07'),
(3,	'Comunidade Teste 3',	'asdasdefefasda',	NULL,	7,	NULL,	1,	'2025-07-29 13:36:16'),
(4,	'Comunidade Teste 4',	'teste 4',	NULL,	3,	NULL,	1,	'2025-07-31 15:30:01'),
(5,	'Historia',	'Todos os PDFs no decorrer do semestre no conteúdo de historia',	NULL,	11,	NULL,	10,	'2025-11-17 22:57:26');
ALTER TABLE `communities` ENABLE KEYS;



-- --------------------------------------------------------

DROP TABLE IF EXISTS `community_posts`;

CREATE TABLE `community_posts` (
  `community_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  PRIMARY KEY (`community_id`,`post_id`),
  KEY `fk_community_posts_post` (`post_id`),
  CONSTRAINT `fk_community_posts_community` FOREIGN KEY (`community_id`) REFERENCES `communities` (`community_id`) ON DELETE CASCADE,
  CONSTRAINT `fk_community_posts_post` FOREIGN KEY (`post_id`) REFERENCES `posts` (`post_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

ALTER TABLE `community_posts` DISABLE KEYS;

INSERT INTO `community_posts` (`community_id`, `post_id`) VALUES
(3,	3),
(1,	6),
(1,	8),
(1,	10),
(3,	11),
(3,	12),
(4,	13),
(2,	14);
ALTER TABLE `community_posts` ENABLE KEYS;



-- --------------------------------------------------------

DROP TABLE IF EXISTS `groups`;

CREATE TABLE `groups` (
  `group_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `creator_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`group_id`),
  KEY `fk_groups_creator` (`creator_id`),
  CONSTRAINT `fk_groups_creator` FOREIGN KEY (`creator_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

ALTER TABLE `groups` DISABLE KEYS;

INSERT INTO `groups` (`group_id`, `name`, `description`, `creator_id`, `created_at`) VALUES
(1,	'teste',	'teste testeeeeee',	1,	'2025-07-31 17:23:46');
ALTER TABLE `groups` ENABLE KEYS;



-- --------------------------------------------------------

DROP TABLE IF EXISTS `likes`;

CREATE TABLE `likes` (
  `user_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `liked_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`user_id`,`post_id`),
  KEY `fk_likes_post` (`post_id`),
  CONSTRAINT `fk_likes_post` FOREIGN KEY (`post_id`) REFERENCES `posts` (`post_id`) ON DELETE CASCADE,
  CONSTRAINT `fk_likes_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

ALTER TABLE `likes` DISABLE KEYS;

INSERT INTO `likes` (`user_id`, `post_id`, `liked_at`) VALUES
(1,	3,	'2025-08-27 18:59:07'),
(1,	5,	'2025-07-30 21:58:23'),
(1,	6,	'2025-07-30 21:58:24'),
(1,	7,	'2025-07-30 21:58:47'),
(1,	8,	'2025-08-27 19:07:52'),
(1,	10,	'2025-08-27 18:59:02'),
(1,	11,	'2025-08-27 18:59:01'),
(1,	12,	'2025-08-27 18:59:00'),
(1,	13,	'2025-08-27 18:58:58'),
(1,	14,	'2025-10-02 10:50:04'),
(1,	15,	'2025-09-03 18:31:35'),
(1,	41,	'2025-10-20 17:45:54'),
(1,	46,	'2025-10-20 17:51:02'),
(1,	50,	'2025-10-20 17:57:24'),
(1,	152,	'2025-11-18 18:59:12'),
(1,	153,	'2025-11-18 18:59:11'),
(2,	11,	'2025-08-09 23:26:45'),
(2,	12,	'2025-08-14 01:51:41'),
(2,	13,	'2025-08-09 23:23:47'),
(2,	14,	'2025-08-09 23:23:46'),
(2,	48,	'2025-10-20 17:54:50'),
(2,	57,	'2025-10-20 18:02:23'),
(2,	119,	'2025-10-20 18:49:01'),
(4,	48,	'2025-10-20 17:56:51'),
(6,	33,	'2025-10-20 17:36:51'),
(6,	44,	'2025-10-20 17:53:24'),
(6,	45,	'2025-10-20 17:53:23'),
(6,	46,	'2025-10-20 17:53:21'),
(6,	61,	'2025-10-20 18:06:05'),
(7,	33,	'2025-10-20 17:41:57'),
(7,	58,	'2025-10-20 18:01:35'),
(8,	90,	'2025-10-20 18:30:21'),
(8,	93,	'2025-10-20 18:30:15'),
(8,	99,	'2025-10-20 18:33:40'),
(8,	100,	'2025-10-20 18:33:39'),
(8,	101,	'2025-10-20 18:33:37'),
(13,	84,	'2025-10-20 18:25:58'),
(15,	84,	'2025-10-20 18:29:36'),
(15,	92,	'2025-10-20 18:28:36'),
(22,	147,	'2025-11-12 19:16:02');
ALTER TABLE `likes` ENABLE KEYS;



-- --------------------------------------------------------

DROP TABLE IF EXISTS `post_media`;

CREATE TABLE `post_media` (
  `media_id` int(11) NOT NULL AUTO_INCREMENT,
  `post_id` int(11) NOT NULL,
  `type` enum('imagem','video') NOT NULL,
  `media_url` varchar(255) NOT NULL,
  `sort_order` tinyint(4) NOT NULL DEFAULT 0,
  PRIMARY KEY (`media_id`),
  KEY `fk_post_media_post` (`post_id`),
  KEY `idx_post_id` (`post_id`),
  CONSTRAINT `fk_post_media_post` FOREIGN KEY (`post_id`) REFERENCES `posts` (`post_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=72 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

ALTER TABLE `post_media` DISABLE KEYS;

INSERT INTO `post_media` (`media_id`, `post_id`, `type`, `media_url`, `sort_order`) VALUES
(6,	3,	'imagem',	'../uploads/posts/6888e92c88efe1.43689993.gif',	0),
(7,	5,	'imagem',	'../uploads/posts/6889889d607030.41316580.gif',	0),
(8,	5,	'imagem',	'../uploads/posts/6889889d60ed42.24276692.gif',	1),
(9,	5,	'imagem',	'../uploads/posts/6889889d612971.17887198.gif',	2),
(10,	5,	'imagem',	'../uploads/posts/6889889d6753a3.13619552.png',	3),
(11,	6,	'imagem',	'../uploads/posts/68898bcc246f92.24646030.jpg',	0),
(12,	6,	'imagem',	'../uploads/posts/68898bcc2535e4.50885871.png',	1),
(13,	6,	'imagem',	'../uploads/posts/68898bcc257ff7.59838182.png',	2),
(14,	11,	'imagem',	'../uploads/posts/688b859ba34cf6.16568679.jpg',	0),
(15,	12,	'imagem',	'../uploads/posts/688b971dbd6c18.69091062.jpg',	0),
(16,	14,	'imagem',	'../uploads/posts/6890159c5ba543.97441525.jpg',	0),
(17,	15,	'imagem',	'../uploads/posts/68a61cf44b0d54.14732472.jpg',	0),
(18,	16,	'imagem',	'../uploads/posts/68b330e2823fb4.32712015.png',	0),
(20,	19,	'imagem',	'../uploads/posts/68f66aead82201.72632733.png',	0),
(22,	46,	'imagem',	'../uploads/posts/68f67660a06069.61691424.jpg',	0),
(24,	48,	'imagem',	'../uploads/posts/68f67707b2f019.92412234.gif',	0),
(25,	49,	'imagem',	'../uploads/posts/68f6770e77a7e9.62878585.jpg',	0),
(26,	51,	'imagem',	'../uploads/posts/68f67818a16f00.55216687.webp',	0),
(28,	53,	'imagem',	'../uploads/posts/68f6783f99fb86.00910953.jpg',	0),
(29,	54,	'imagem',	'../uploads/posts/68f6785b400029.33882018.jpg',	0),
(32,	58,	'imagem',	'../uploads/posts/68f678c8d75ca7.08703057.gif',	0),
(33,	59,	'imagem',	'../uploads/posts/68f678ca523e11.39206914.webp',	0),
(41,	79,	'imagem',	'../uploads/posts/68f67babcd94e9.39581123.gif',	0),
(42,	80,	'imagem',	'../uploads/posts/68f67cbece88a4.68140589.gif',	0),
(43,	81,	'imagem',	'../uploads/posts/68f67d15dd6911.68301167.jfif',	0),
(44,	84,	'imagem',	'../uploads/posts/68f67e2f9ee123.87796606.jfif',	0),
(45,	86,	'imagem',	'../uploads/posts/68f67e75bf3f54.53541294.jpg',	0),
(46,	87,	'imagem',	'../uploads/posts/68f67e7dbe36a1.95483447.webp',	0),
(47,	89,	'imagem',	'../uploads/posts/68f67e83bdb692.70660721.jpg',	0),
(48,	90,	'imagem',	'../uploads/posts/68f67ea00ff219.36682657.jpg',	0),
(49,	92,	'imagem',	'../uploads/posts/68f67eae221755.72988678.jpg',	0),
(50,	93,	'imagem',	'../uploads/posts/68f67ecdc31b03.52908287.webp',	0),
(51,	102,	'imagem',	'../uploads/posts/68f680a9530b78.36938060.webp',	0),
(52,	103,	'imagem',	'../uploads/posts/68f680b238db77.24160960.webp',	0),
(53,	104,	'imagem',	'../uploads/posts/68f680b8b235e9.34670858.jfif',	0),
(54,	105,	'imagem',	'../uploads/posts/68f680c028c270.16359756.webp',	0),
(55,	106,	'imagem',	'../uploads/posts/68f680cc269b94.59128775.webp',	0),
(56,	107,	'imagem',	'../uploads/posts/68f680d2a2feb8.03895322.webp',	0),
(57,	108,	'imagem',	'../uploads/posts/68f680d933a975.99707291.webp',	0),
(59,	113,	'imagem',	'../uploads/posts/68f68187d59572.33020838.jfif',	0),
(60,	117,	'imagem',	'../uploads/posts/68f6824923f0d6.64066526.jpeg',	0),
(61,	118,	'imagem',	'../uploads/posts/68f682ca955fc6.29914081.jfif',	0),
(62,	119,	'imagem',	'../uploads/posts/68f682cbb56bb7.10266230.jpg',	0),
(63,	124,	'imagem',	'../uploads/posts/68f6842f351dc9.35677013.jpg',	0),
(64,	127,	'imagem',	'../uploads/posts/68f68483d7bd30.97025133.jpeg',	0),
(65,	133,	'imagem',	'../uploads/posts/68f685f72a0b51.90880829.jpg',	0),
(66,	136,	'imagem',	'../uploads/posts/68f6865a618c02.37077182.jpg',	0),
(67,	138,	'imagem',	'../uploads/posts/68f686dad16ee1.25153845.jpg',	0),
(68,	145,	'imagem',	'../uploads/posts/6914bcbf0fbf49.23463899.jpg',	0),
(69,	147,	'imagem',	'../uploads/posts/6914dc89dbbeb8.69486539.png',	0),
(70,	148,	'imagem',	'../uploads/posts/69168f70941780.61705699.png',	0);
ALTER TABLE `post_media` ENABLE KEYS;



-- --------------------------------------------------------

DROP TABLE IF EXISTS `post_tags`;

CREATE TABLE `post_tags` (
  `post_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL,
  PRIMARY KEY (`post_id`,`tag_id`),
  KEY `fk_post_tags_tag` (`tag_id`),
  CONSTRAINT `fk_post_tags_post` FOREIGN KEY (`post_id`) REFERENCES `posts` (`post_id`) ON DELETE CASCADE,
  CONSTRAINT `fk_post_tags_tag` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`tag_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

ALTER TABLE `post_tags` DISABLE KEYS;

INSERT INTO `post_tags` (`post_id`, `tag_id`) VALUES
(10,	1),
(12,	1),
(13,	1),
(25,	1),
(12,	3),
(13,	3),
(21,	3),
(84,	3),
(16,	5);
ALTER TABLE `post_tags` ENABLE KEYS;



-- --------------------------------------------------------

DROP TABLE IF EXISTS `post_views`;

CREATE TABLE `post_views` (
  `view_id` int(11) NOT NULL AUTO_INCREMENT,
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `viewed_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`view_id`),
  UNIQUE KEY `unique_view` (`post_id`,`user_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `post_views_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`post_id`) ON DELETE CASCADE,
  CONSTRAINT `post_views_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=287 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

ALTER TABLE `post_views` DISABLE KEYS;

INSERT INTO `post_views` (`view_id`, `post_id`, `user_id`, `viewed_at`) VALUES
(1,	15,	1,	'2025-10-15 10:26:38'),
(2,	13,	1,	'2025-10-15 17:36:53'),
(4,	16,	3,	'2025-10-15 17:59:59'),
(7,	12,	1,	'2025-10-15 18:47:44'),
(12,	5,	1,	'2025-10-17 19:59:22'),
(16,	19,	1,	'2025-10-20 17:02:26'),
(17,	27,	1,	'2025-10-20 17:24:35'),
(18,	27,	4,	'2025-10-20 17:33:52'),
(21,	33,	4,	'2025-10-20 17:36:44'),
(23,	34,	1,	'2025-10-20 17:37:47'),
(24,	33,	7,	'2025-10-20 17:41:51'),
(28,	30,	4,	'2025-10-20 17:42:47'),
(31,	31,	8,	'2025-10-20 17:44:08'),
(34,	41,	4,	'2025-10-20 17:44:26'),
(36,	41,	8,	'2025-10-20 17:44:51'),
(38,	38,	4,	'2025-10-20 17:44:59'),
(41,	41,	1,	'2025-10-20 17:45:48'),
(50,	41,	7,	'2025-10-20 17:48:22'),
(51,	43,	7,	'2025-10-20 17:48:32'),
(54,	44,	2,	'2025-10-20 17:49:07'),
(59,	33,	1,	'2025-10-20 17:50:56'),
(60,	46,	1,	'2025-10-20 17:51:02'),
(63,	46,	7,	'2025-10-20 17:52:11'),
(68,	41,	2,	'2025-10-20 17:52:53'),
(69,	44,	6,	'2025-10-20 17:53:26'),
(72,	33,	10,	'2025-10-20 17:54:23'),
(74,	50,	10,	'2025-10-20 17:55:29'),
(79,	46,	4,	'2025-10-20 17:56:59'),
(80,	50,	1,	'2025-10-20 17:57:11'),
(85,	59,	4,	'2025-10-20 18:01:39'),
(91,	59,	7,	'2025-10-20 18:01:57'),
(92,	59,	2,	'2025-10-20 18:02:04'),
(93,	58,	2,	'2025-10-20 18:02:12'),
(95,	57,	2,	'2025-10-20 18:02:24'),
(96,	51,	2,	'2025-10-20 18:02:32'),
(97,	48,	2,	'2025-10-20 18:02:36'),
(99,	48,	1,	'2025-10-20 18:02:45'),
(101,	45,	2,	'2025-10-20 18:02:50'),
(104,	58,	11,	'2025-10-20 18:04:08'),
(105,	46,	11,	'2025-10-20 18:06:19'),
(107,	72,	2,	'2025-10-20 18:08:29'),
(110,	59,	10,	'2025-10-20 18:10:10'),
(111,	33,	2,	'2025-10-20 18:10:18'),
(112,	48,	7,	'2025-10-20 18:11:05'),
(118,	79,	2,	'2025-10-20 18:16:49'),
(120,	46,	2,	'2025-10-20 18:19:45'),
(121,	48,	13,	'2025-10-20 18:20:31'),
(122,	33,	13,	'2025-10-20 18:20:35'),
(123,	46,	13,	'2025-10-20 18:20:39'),
(126,	82,	2,	'2025-10-20 18:23:13'),
(129,	61,	1,	'2025-10-20 18:23:34'),
(130,	74,	2,	'2025-10-20 18:23:44'),
(132,	83,	1,	'2025-10-20 18:25:36'),
(133,	84,	13,	'2025-10-20 18:26:02'),
(134,	96,	8,	'2025-10-20 18:29:46'),
(136,	98,	8,	'2025-10-20 18:33:49'),
(137,	101,	2,	'2025-10-20 18:35:46'),
(138,	92,	1,	'2025-10-20 18:36:29'),
(139,	113,	13,	'2025-10-20 18:38:12'),
(140,	112,	1,	'2025-10-20 18:38:25'),
(142,	114,	1,	'2025-10-20 18:39:06'),
(144,	117,	7,	'2025-10-20 18:42:02'),
(147,	117,	1,	'2025-10-20 18:42:23'),
(148,	117,	2,	'2025-10-20 18:42:39'),
(151,	119,	2,	'2025-10-20 18:43:37'),
(154,	119,	7,	'2025-10-20 18:44:00'),
(155,	118,	1,	'2025-10-20 18:44:07'),
(160,	107,	1,	'2025-10-20 18:48:14'),
(161,	108,	1,	'2025-10-20 18:48:19'),
(166,	12,	17,	'2025-10-20 18:55:32'),
(167,	54,	7,	'2025-10-20 18:56:11'),
(168,	133,	16,	'2025-10-20 18:56:57'),
(169,	133,	1,	'2025-10-20 18:57:11'),
(170,	71,	2,	'2025-10-20 18:57:20'),
(172,	136,	16,	'2025-10-20 18:58:34'),
(173,	130,	16,	'2025-10-20 18:58:53'),
(176,	124,	19,	'2025-10-21 21:57:22'),
(177,	59,	19,	'2025-10-21 21:58:16'),
(186,	84,	19,	'2025-10-21 22:03:54'),
(187,	117,	19,	'2025-10-21 22:04:22'),
(192,	30,	19,	'2025-10-21 22:05:08'),
(200,	141,	2,	'2025-11-05 17:36:38'),
(221,	147,	22,	'2025-11-12 19:16:04'),
(225,	44,	1,	'2025-11-17 19:28:42'),
(227,	147,	1,	'2025-11-17 19:42:50'),
(228,	145,	1,	'2025-11-17 19:43:53'),
(229,	134,	1,	'2025-11-17 19:44:52'),
(235,	13,	10,	'2025-11-17 22:58:48'),
(237,	150,	10,	'2025-11-17 23:03:11'),
(241,	147,	10,	'2025-11-17 23:11:19'),
(243,	153,	1,	'2025-11-18 00:33:10'),
(246,	11,	2,	'2025-11-18 03:14:35'),
(248,	152,	2,	'2025-11-18 12:55:29'),
(249,	137,	2,	'2025-11-18 12:55:33'),
(253,	153,	2,	'2025-11-18 15:21:37'),
(255,	138,	2,	'2025-11-18 16:18:23'),
(257,	150,	2,	'2025-11-18 16:18:56'),
(270,	121,	1,	'2025-11-18 18:00:37'),
(284,	150,	1,	'2025-11-18 20:46:40');
ALTER TABLE `post_views` ENABLE KEYS;



-- --------------------------------------------------------

DROP TABLE IF EXISTS `posts`;

CREATE TABLE `posts` (
  `post_id` int(11) NOT NULL AUTO_INCREMENT,
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
  `like_count` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`post_id`),
  KEY `fk_posts_user` (`user_id`),
  CONSTRAINT `fk_posts_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=155 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

ALTER TABLE `posts` DISABLE KEYS;

INSERT INTO `posts` (`post_id`, `user_id`, `content`, `type`, `status`, `content_warning`, `created_at`, `updated_at`, `view_count`, `reply_count`, `repost_count`, `bookmark_count`, `like_count`) VALUES
(3,	1,	'sfafefwedcwedxwe',	'padrao',	'ativo',	1,	'2025-07-29 15:30:52',	NULL,	0,	0,	0,	0,	1),
(5,	1,	'teste grande teste testando mega plavras grandes de teste teste grande teste testando mega plavras grandes de teste teste grande teste testando mega plavras grandes de teste teste grande teste testando mega plavras grandes de teste teste grande teste testando mega plavras grandes de teste teste grande teste testando mega plavras grandes de teste teste grande teste testando mega plavras grandes de teste teste grande teste testando mega plavras grandes de teste teste grande teste testando mega plavras grandes de teste teste grande teste testando mega plavras grandes de teste teste grande teste testando mega plavras grandes de teste teste grande teste testando mega plavras grandes de teste teste grande teste testando mega plavras grandes de teste teste grande teste testando mega plavras grandes de teste teste grande teste testando mega plavras grandes de teste teste grande teste testando mega plavras grandes de teste teste grande teste testando mega plavras grandes de teste teste grande teste testando mega plavras grandes de teste teste grande teste testando mega plavras grandes de teste teste grande teste testando mega plavras grandes de teste teste grande teste testando mega plavras grandes de teste',	'padrao',	'ativo',	0,	'2025-07-30 02:51:09',	NULL,	1,	0,	0,	0,	1),
(6,	1,	'tessssste',	'padrao',	'ativo',	0,	'2025-07-30 03:04:44',	NULL,	0,	0,	0,	0,	1),
(7,	1,	'asdasdweasdwa',	'padrao',	'ativo',	1,	'2025-07-30 13:38:19',	NULL,	0,	0,	0,	0,	1),
(8,	1,	'asdasdadsdweadcw',	'padrao',	'ativo',	0,	'2025-07-31 02:57:41',	NULL,	0,	0,	0,	0,	1),
(10,	1,	'wqrasdwqdqwdqw',	'padrao',	'ativo',	0,	'2025-07-31 14:07:56',	NULL,	0,	0,	0,	0,	1),
(11,	1,	'dasdasdasdffeqe',	'padrao',	'ativo',	1,	'2025-07-31 15:02:51',	NULL,	1,	0,	0,	0,	2),
(12,	1,	'dasdwedawdw',	'padrao',	'ativo',	0,	'2025-07-31 16:17:33',	NULL,	2,	1,	0,	0,	2),
(13,	1,	'dsadwdawda',	'padrao',	'ativo',	1,	'2025-07-31 16:21:46',	NULL,	2,	0,	0,	0,	2),
(14,	1,	'hgh3efghjbfhebwnfmbwemfehjfghjwejc',	'padrao',	'ativo',	0,	'2025-08-04 02:06:20',	NULL,	0,	0,	0,	0,	2),
(15,	1,	'hjgcdfdfdcv',	'padrao',	'ativo',	1,	'2025-08-20 19:07:32',	NULL,	1,	0,	0,	0,	1),
(16,	1,	'teste',	'padrao',	'ativo',	0,	'2025-08-30 17:12:02',	NULL,	1,	0,	0,	0,	0),
(19,	1,	'teste post modal',	'padrao',	'ativo',	0,	'2025-10-20 17:01:30',	NULL,	1,	0,	0,	0,	0),
(21,	1,	'teste de publicação',	'padrao',	'ativo',	0,	'2025-10-20 17:04:25',	NULL,	0,	0,	0,	0,	0),
(22,	1,	'teste 2',	'padrao',	'ativo',	0,	'2025-10-20 17:07:28',	NULL,	0,	0,	0,	0,	0),
(23,	1,	'teste 3',	'padrao',	'ativo',	0,	'2025-10-20 17:08:21',	NULL,	0,	0,	0,	0,	0),
(24,	1,	'teste 4',	'padrao',	'ativo',	0,	'2025-10-20 17:09:29',	NULL,	0,	0,	0,	0,	0),
(25,	1,	'teste 5',	'padrao',	'ativo',	0,	'2025-10-20 17:10:29',	NULL,	0,	0,	0,	0,	0),
(26,	1,	'teste 6',	'padrao',	'ativo',	0,	'2025-10-20 17:11:11',	NULL,	0,	0,	0,	0,	0),
(27,	1,	'teste',	'padrao',	'ativo',	0,	'2025-10-20 17:14:41',	NULL,	2,	1,	0,	0,	0),
(28,	5,	'Quero ponto em quimica',	'padrao',	'ativo',	0,	'2025-10-20 17:34:47',	NULL,	0,	0,	0,	0,	0),
(29,	5,	'Quero ponto em quimica',	'padrao',	'ativo',	0,	'2025-10-20 17:35:15',	NULL,	0,	0,	0,	0,	0),
(30,	5,	'Quero ponto em quimica',	'padrao',	'ativo',	0,	'2025-10-20 17:35:43',	NULL,	2,	1,	0,	0,	0),
(31,	4,	'15 reais',	'padrao',	'ativo',	0,	'2025-10-20 17:36:25',	NULL,	1,	1,	0,	0,	0),
(32,	6,	'aysla estou de olho em voce',	'padrao',	'ativo',	0,	'2025-10-20 17:36:33',	NULL,	0,	0,	0,	0,	0),
(33,	6,	'aysla estou de olho em voce',	'padrao',	'ativo',	0,	'2025-10-20 17:36:40',	NULL,	6,	2,	0,	0,	2),
(34,	1,	'teste',	'padrao',	'ativo',	0,	'2025-10-20 17:37:23',	NULL,	1,	0,	0,	0,	0),
(37,	1,	'teste cache 2',	'padrao',	'ativo',	0,	'2025-10-20 17:38:45',	NULL,	0,	0,	0,	0,	0),
(38,	8,	'Não irei interagir',	'padrao',	'ativo',	0,	'2025-10-20 17:43:23',	NULL,	1,	1,	0,	0,	0),
(39,	1,	'teste',	'padrao',	'ativo',	0,	'2025-10-20 17:43:36',	NULL,	0,	0,	0,	0,	0),
(40,	7,	'O IF tem fome.',	'padrao',	'ativo',	0,	'2025-10-20 17:44:02',	NULL,	0,	0,	0,	0,	0),
(41,	7,	'O IF tem fome.',	'padrao',	'ativo',	0,	'2025-10-20 17:44:05',	NULL,	5,	4,	0,	0,	1),
(43,	4,	'ablu ablu',	'padrao',	'ativo',	0,	'2025-10-20 17:46:49',	NULL,	1,	0,	0,	0,	0),
(44,	4,	'rato do pt espalhando mentiras',	'padrao',	'ativo',	0,	'2025-10-20 17:48:23',	NULL,	3,	1,	0,	0,	1),
(45,	8,	'Eu acho que sua filha é grandinha o suficiente pra ter sua própria opinião, divaaaaaaaaaaaaaaaaaaaaaaaaaa',	'padrao',	'ativo',	0,	'2025-10-20 17:49:16',	NULL,	1,	0,	0,	0,	1),
(46,	4,	'',	'padrao',	'ativo',	0,	'2025-10-20 17:50:24',	NULL,	6,	3,	0,	0,	2),
(48,	1,	'eu e o tozzi:',	'padrao',	'ativo',	0,	'2025-10-20 17:53:11',	NULL,	4,	0,	0,	0,	2),
(49,	8,	'Mana, se toca. Bem ela:',	'padrao',	'ativo',	0,	'2025-10-20 17:53:18',	NULL,	0,	0,	0,	0,	0),
(50,	10,	'preciso de nota',	'padrao',	'ativo',	0,	'2025-10-20 17:55:14',	NULL,	2,	1,	0,	0,	1),
(51,	2,	'eu olhando pro pallet que eu roubei',	'padrao',	'ativo',	0,	'2025-10-20 17:57:44',	NULL,	1,	0,	0,	0,	0),
(53,	4,	'tic tac',	'padrao',	'ativo',	0,	'2025-10-20 17:58:23',	NULL,	0,	0,	0,	0,	0),
(54,	7,	'',	'padrao',	'ativo',	0,	'2025-10-20 17:58:51',	NULL,	1,	0,	0,	0,	0),
(57,	1,	'obrigado pelo engajamento pessoal',	'padrao',	'ativo',	0,	'2025-10-20 18:00:34',	NULL,	1,	0,	0,	0,	1),
(58,	2,	'',	'padrao',	'ativo',	0,	'2025-10-20 18:00:40',	NULL,	2,	0,	0,	0,	1),
(59,	7,	'Uma certa pessoa ta fazendo muitos posts e eu não estou gostando sabe',	'padrao',	'ativo',	0,	'2025-10-20 18:00:42',	NULL,	5,	1,	0,	0,	0),
(61,	4,	'ngm perguntou mana, tenho 11',	'padrao',	'ativo',	0,	'2025-10-20 18:02:45',	NULL,	1,	0,	0,	0,	1),
(66,	2,	'gente, como q faz posts?',	'padrao',	'ativo',	0,	'2025-10-20 18:06:25',	NULL,	0,	0,	0,	0,	0),
(67,	2,	'tengo fome',	'padrao',	'ativo',	0,	'2025-10-20 18:06:39',	NULL,	0,	0,	0,	0,	0),
(71,	2,	'Oi meu nome é Samara, tenho 14 anos (teria se estivesse viva), morri aos 13 em Cascavel-PR.☠️ ??\r\n\r\nEu andava de bicicleta quando não pude desviar de um arame farpado. O pior foi que o dono do lote não quis me ajudar e riu bastante de mim. Após agonizar por 2 horas enroscada no arame eu faleci, e por meio desta mensagem eu peço que façam com que eu possa descansar em paz. ?\r\n\r\nEnvie isso para 20 pessoas e então minha alma será salva por você e pelos outros 20 que receberão. Caso não repasse essa mensagem, vou te visitar hoje à noite, assim você poderá conhecer o tal arame bem de pertinho.\r\n\r\nDia 15 de julho, Mariana resolveu rir dessa mensagem. Uma noite depois ela sumiu sem deixar vestígios. O mesmo aconteceu com Karen, no dia 18 de outubro.\r\n\r\nNão quebre essa corrente, por favor, a não ser que queira sentir a minha presença.',	'padrao',	'ativo',	0,	'2025-10-20 18:07:54',	NULL,	1,	0,	0,	0,	0),
(72,	2,	'Olá! Obrigado por ler esta mensagem. Tem um garoto faminto em Baklaliviatatlaglooshen que não tem braços, não tem pernas, não tem pais e não tem bodes. ??? A vida deste menino pode ser salva, porque cada vez que você mandar essa mensagem, um dólar será doado para o Fundo Baklaliviatatlaglooshenense para Garotos Pernetas Manetas Órfãos e sem Bodes. Lembre-se: nós não temos nenhuma maneira de contar quantas cartas foram mandadas, e isso é tudo bobagem, então mande para 5 pessoas nos próximos 47 segundos. Ah, um lembrete: se você mandar acidentalmente para 4 ou 6 pessoas, você morrerá instantaneamente. ☠️? Obrigado!\r\n\r\nConfira os melhores memes para whatsapp',	'padrao',	'ativo',	0,	'2025-10-20 18:08:17',	NULL,	1,	0,	0,	0,	0),
(73,	4,	'XIGARUEIIIIIIIIII XIGA XIGARUEIIIIIIIIII',	'padrao',	'ativo',	0,	'2025-10-20 18:10:02',	NULL,	0,	0,	0,	0,	0),
(74,	2,	'a',	'padrao',	'ativo',	0,	'2025-10-20 18:10:06',	NULL,	1,	0,	0,	0,	0),
(79,	2,	'a malu jaja:',	'padrao',	'ativo',	0,	'2025-10-20 18:12:59',	NULL,	1,	0,	0,	0,	0),
(80,	2,	'xibiu',	'padrao',	'ativo',	0,	'2025-10-20 18:17:34',	NULL,	0,	0,	0,	0,	0),
(81,	2,	'nada a dizer',	'padrao',	'ativo',	0,	'2025-10-20 18:19:01',	NULL,	0,	0,	0,	0,	0),
(82,	8,	'??? ??? ???????? ???? ????????? ?? ??????. ?????, ????́ ??? ??̃?? ?? ????????, ? ????? ???? ?????? ?? ?????. ????? ?́???, ?????? ? ????????̧?̃??, ??? ??? ????????? ??? ????? ????... ?́ ??????????.',	'padrao',	'ativo',	0,	'2025-10-20 18:22:09',	NULL,	1,	1,	0,	0,	0),
(83,	8,	'Lilith Ligthwood é a filha mais nova de um respeitado mafioso da Suíça. Sem ela saber, seu pai a apostou em um jogo de baralho contra Domenic Torricelli, o maior e mais temido mafioso da Albânia. Seu pai achando que ganharia a irmã de Domenic com o jogo, acaba perdendo e tendo que deixar o mafioso pegar sua filha. Mas Lilith o odeia desde o seu aniversário de 16 anos, quando ele a pediu em casamento e ficou irado quando ela e seu pai recusaram a proposta.\r\n\r\nDepois de tanto tentar fugir, Lilith aceita o que está acontecendo. Mas seus sentimentos começam a confundi-la, quando ela percebe que agora sentia algo a mais por Domenic além do ódio. O romance dos dois vai gerar muitas desavenças na máfia, e o pior, vai trazer a tona um dos maiores segredos da sua família, algo que ela nunca imaginou.',	'padrao',	'ativo',	0,	'2025-10-20 18:23:10',	NULL,	1,	0,	0,	0,	0),
(84,	13,	'',	'padrao',	'ativo',	0,	'2025-10-20 18:23:43',	NULL,	2,	0,	0,	0,	2),
(85,	8,	'Após ser inseminada artificialmente por engano, Mariana descobre que o artilheiro do time rival, e que por sinal ela não vai nenhum pouco com a cara, é o dono do esperma que foi inserido nela. No auge da sua carreira de Estilista, tudo o que Mariana não queria era uma gravidez. Afinal, ela não leva jeito algum com crianças e nunca quis ser mãe. \r\n\r\nGabriel, por outro lado, sempre teve o sonho de ser pai, mas não dessa maneira inesperada. Ele também estava no auge de sua carreira no futebol e apesar da vontade, ser pai agora não estava nos seus planos. \r\n\r\nA pergunta que fica para ambos é: Como eles vão resolver esse problema?',	'padrao',	'ativo',	0,	'2025-10-20 18:24:15',	NULL,	0,	0,	0,	0,	0),
(86,	7,	'Que venha logo a opção de denúnciar usuários, que começam com \"M\"',	'padrao',	'ativo',	0,	'2025-10-20 18:24:53',	NULL,	0,	0,	0,	0,	0),
(87,	2,	'eu amo',	'padrao',	'ativo',	0,	'2025-10-20 18:25:01',	NULL,	0,	0,	0,	0,	0),
(88,	8,	'onde lina é a nova fotógrafa do palmeiras e precisa conviver com richard, o jogador que bagunça sua vida e planos.',	'padrao',	'ativo',	0,	'2025-10-20 18:25:07',	NULL,	0,	0,	0,	0,	0),
(89,	7,	'o que?',	'padrao',	'ativo',	0,	'2025-10-20 18:25:07',	NULL,	0,	0,	0,	0,	0),
(90,	2,	'pipoca',	'padrao',	'ativo',	0,	'2025-10-20 18:25:36',	NULL,	0,	0,	0,	0,	1),
(91,	8,	'Desde criança, sempre ouvi histórias sobre o Capitão Roberto Nascimento. Meu pai o chamava de irmão, e para todos, ele era um homem de honra, leal até a morte. Mas, para mim, ele nunca foi apenas isso. Sempre houve algo nele que me fascinava-sua presença imponente, a forma como seu olhar parecia enxergar além do óbvio, a maneira como sua voz me fazia prender a respiração.\r\n\r\nAgora, anos depois, sou uma mulher, e a distância entre nós se tornou um abismo perigoso. Ele é o melhor amigo do meu pai, um homem marcado pela guerra e pelo dever, e eu... eu sou apenas uma jovem que não deveria desejar o que deseja.\r\n\r\nMas como ignorar a tensão quando seus olhos encontram os meus? Como fingir que meu coração não dispara quando ele está por perto?\r\n\r\nEntre segredos, riscos e uma paixão proibida, resta apenas uma pergunta: até onde podemos ir antes que tudo desmorone?',	'padrao',	'ativo',	0,	'2025-10-20 18:25:42',	NULL,	0,	0,	0,	0,	0),
(92,	7,	'Aff tchau',	'padrao',	'ativo',	0,	'2025-10-20 18:25:50',	NULL,	1,	0,	0,	0,	1),
(93,	2,	'tchau, pipoca',	'padrao',	'ativo',	0,	'2025-10-20 18:26:21',	NULL,	0,	0,	0,	0,	1),
(94,	8,	'O ano é 2050 e o BDSM é aclamado pelo mundo todo. Definitivamente toda a população, segue estritamente as regras e a relação dominante. Os cientistas descobriram que o bdsm é a melhor prática sexual existente, então, todos devem seguir.\r\nSarah Young escolhe ir para o Internato de Submissas, afim de se livrar do seu ex marido.  No entanto, Sarah, não é amante da prática, mas, esse pensamento logo cai por terra, quando ela conhece Apollo, o melhor professor e dominador do Instituto, que é desejado por todas as submissas. A relação entre os dois é marcada pela rebeldia de Sarah. \r\n\r\nCom o passar do tempo, e ao fazer novas amizades, Sarah, se vê entregue a prática libertinosa e acaba sendo totalmente amante, não só dá prática, mas de tudo o que ela envolve.',	'padrao',	'ativo',	0,	'2025-10-20 18:27:35',	NULL,	0,	0,	0,	0,	0),
(96,	8,	'Melina é uma jovem virgem que ainda não sabe nada sobre sexo. Quando sua mãe viaja a trabalho, ela se hospeda na casa de Kathy, amiga de sua mãe, e que por sua vez, é casada com Tadeu. \r\n\r\nTadeu propõe à sua hóspede, umas aulas quentes para aprender mais sobre a prática de uma relação sexual. No entanto, as coisas entre os dois, começa a esquentar, e o desejo ardente entre ambos, será difícil de conter.',	'padrao',	'ativo',	0,	'2025-10-20 18:29:31',	NULL,	1,	0,	0,	0,	0),
(97,	1,	'teste de post denovo',	'padrao',	'ativo',	0,	'2025-10-20 18:31:00',	NULL,	0,	0,	0,	0,	0),
(98,	8,	'yoongi um alfa frio , com personalidade forte .... resolve comprar 1 submisso ômega ... mais não contava com taehyung  que também resolveu adotar o mesmo submisso,  hoseok um submisso ômega dócil que sempre está sorrindo e ama ter carinho será adotado por esses dois alfas .\r\nYoongi esconde um passado um tanto quanto complicado , de históricos de submissos que foram torturados. \r\n\r\n\r\noque  essa história reservará a yoongi e taehyung adotando o mesmo submisso ??',	'padrao',	'ativo',	0,	'2025-10-20 18:31:06',	NULL,	1,	0,	0,	0,	0),
(99,	1,	'teste de post denovo 2',	'padrao',	'ativo',	0,	'2025-10-20 18:32:23',	NULL,	0,	0,	0,	0,	1),
(100,	8,	'Irei contar a trajetória da Sarah , que aparece no meu outro conto \"Minha Vida\" a trajetória de como ela descobre o fetiche, até descobrir as diferentes jeitos de realizá-lo.\r\n\"Como virei escrava da minha mãe\"',	'padrao',	'ativo',	0,	'2025-10-20 18:32:34',	NULL,	0,	0,	0,	0,	1),
(101,	8,	'megas não são conhecidos por ter personalidades fortes. Mas sim personalidades submissa que facilita o domínio dos Alfas.\r\n\r\n Alyssa McCarthy achava isso totalmente banal. Ela odiava a ideia de um Alfa a obrigar fazer algo que não a quer. Teve esse pensamento desde que uma Ômega a salvou no mesmo tiroteio que matou seu pai.\r\n\r\n Depois de muito tempo sozinha, a mãe da garota resolve se casar com um rico bilionário.\r\n\r\n  Alyssa pensava que jamais se apaixonaria por um Alfa....\r\n\r\n\r\n\r\n\r\n              ...até conhecer o filho de seu padrasto.',	'padrao',	'ativo',	0,	'2025-10-20 18:33:31',	NULL,	1,	0,	0,	0,	1),
(102,	2,	'',	'padrao',	'ativo',	0,	'2025-10-20 18:34:17',	NULL,	0,	0,	0,	0,	0),
(103,	2,	'',	'padrao',	'ativo',	0,	'2025-10-20 18:34:26',	NULL,	0,	0,	0,	0,	0),
(104,	2,	'',	'padrao',	'ativo',	0,	'2025-10-20 18:34:32',	NULL,	0,	0,	0,	0,	0),
(105,	2,	'',	'padrao',	'ativo',	0,	'2025-10-20 18:34:40',	NULL,	0,	0,	0,	0,	0),
(106,	2,	'',	'padrao',	'ativo',	0,	'2025-10-20 18:34:52',	NULL,	0,	0,	0,	0,	0),
(107,	2,	'',	'padrao',	'ativo',	0,	'2025-10-20 18:34:58',	NULL,	1,	0,	0,	0,	0),
(108,	2,	'',	'padrao',	'ativo',	0,	'2025-10-20 18:35:05',	NULL,	1,	0,	0,	0,	0),
(109,	8,	'Jade Azevedo e uma bela mestiça de 23 anos , desembarcava nos Emirados Árabes especificamente em Abu Dhabi. Como uma simples turista seu plano era apenas , conhecer o máximo do país e voltar pro Brasil no mês seguinte para ajudar a madrinha no trabalho. No entanto o destino muda tudo que foi planejado quando ela cruza o caminho de quem não queria , justamente do sheik o homem que marcaria sua vida pra sempre.\r\n\r\nRafiq Bin Salam Al- Gala e o sheik do emirado de Abu Dhabi , um homem de 36 anos , rígido , perspicaz de personalidade forte nascido numa família tradicional ele buscava a mulher que seria a sua esposa e mãe de seus filhos. De início uma árabe recatada e submissa criada nos moldes e costumes do país , no entanto ao ser atraído pela beleza da bela brasileira os planos mudam totalmente. Sem dúvidas Jade era a eleita para ser sua esposa , porém a mesma teria que aceitar tal decisão.',	'padrao',	'ativo',	0,	'2025-10-20 18:35:24',	NULL,	0,	0,	0,	0,	0),
(110,	8,	'Jasmine Volkov sempre foi uma policial exemplar, seguindo as regras à risca e dedicando sua vida ao trabalho. Criada para acreditar que o lugar de uma mulher era no lar, ela provou o contrário ao construir uma carreira sólida, mesmo que seu casamento já não trouxesse mais o mesmo brilho de antes.\r\n\r\nMas tudo muda quando Katherine e Scarlett, duas criminosas implacáveis e sedutoras, iniciam uma onda de assaltos ousados. Determinada a capturá-las e finalmente conquistar o reconhecimento que tanto almeja, Jasmine mergulha de cabeça na caçada - mas logo percebe que o perigo não é o único fator que acelera seu coração.\r\n\r\nO que Jasmine não imagina é que, enquanto ela se aproxima de capturá-las, Katherine e Scarlett ficam obcecadas pela policial que ousa persegui-las. A adrenalina da fuga se mistura ao desejo insaciável de dominar e corromper a mulher que ousa desafiá-las.\r\n\r\nQuando a tensão entre caça e caçadora se torna uma chama proibida, Jasmine precisa enfrentar o desejo que ameaça consumir tudo o que ela sempre acreditou. Entre a lei e o pecado, ela descobrirá que algumas prisões são deliciosamente voluntárias.',	'padrao',	'ativo',	0,	'2025-10-20 18:36:55',	NULL,	0,	0,	0,	0,	0),
(112,	8,	'Dois primos, vivem uma relação amorosa em segredo.  Em busca de algo para apimentar a intimidade do casal, resolvem procurar por um terceiro companheiro. Juntos, juram ; lealdade, verdade e intensidade. Mas no decorrer desta relação, alguns segredos virão à tona,  trazendo conflitos, que levarão o TRISAL tomar suas próprias  decisões individualmente. O que não esperam, é que essas escolhas, trará sérias consequências.',	'padrao',	'ativo',	0,	'2025-10-20 18:37:27',	NULL,	1,	0,	0,	0,	0),
(113,	13,	'',	'padrao',	'ativo',	0,	'2025-10-20 18:37:59',	NULL,	1,	0,	0,	0,	0),
(114,	8,	'EMILY UMA MULHER MUITO LINDA DE 20 ANOS É A NOVA FISIOTERAPEUTA DO PALMEIRAS , ELA ESTAVA EM UMA BALADA QUANDO 2 HOMENS A ENCARA :RICHARD RIOS E JOACO PIQUEREZ...DOIS JOGADORES DO PALMEIRAS,OS 2 HOMENS ACABAM COMETENDO ATOS EM UM CORREDOR DA BALADA,ELES SÓ NÃO ESPERARIAM QUE ELA FOSSE TRABALHAR NO MESMO LOCAL QUE ELA ?',	'padrao',	'ativo',	0,	'2025-10-20 18:38:11',	NULL,	1,	0,	0,	0,	0),
(115,	8,	'As minhas reflexões !',	'padrao',	'ativo',	0,	'2025-10-20 18:39:29',	NULL,	0,	0,	0,	0,	0),
(116,	8,	'Brianna, uma jovem design de 21 anos que com algumas decepções sexuais, cruza com o Dr. Luciano, um Juiz e dominador experiente, em busca de uma nova submissa. \r\nVocê irá ler uma história de dominação, submissão, sexo, desejos e verá o quão difícil é manter a relação dominador/submissa sem envolver amor.\r\nEmbarque nessa aventura de autoconhecimento, desejos e muito spanking.',	'padrao',	'ativo',	0,	'2025-10-20 18:40:13',	NULL,	0,	0,	0,	0,	0),
(117,	13,	'eu i quem?',	'padrao',	'ativo',	0,	'2025-10-20 18:41:13',	NULL,	4,	2,	0,	0,	0),
(118,	13,	'#butsempais',	'padrao',	'ativo',	0,	'2025-10-20 18:43:22',	NULL,	1,	0,	0,	0,	0),
(119,	7,	'',	'padrao',	'ativo',	0,	'2025-10-20 18:43:23',	NULL,	2,	1,	0,	0,	1),
(121,	1,	'teste de horário',	'padrao',	'ativo',	0,	'2025-10-20 18:45:06',	NULL,	1,	0,	0,	0,	0),
(124,	7,	'Tem que banir essa rapaziada postando fanfic',	'padrao',	'ativo',	0,	'2025-10-20 18:49:19',	NULL,	1,	0,	0,	0,	0),
(127,	2,	'estao nos observando da silva',	'padrao',	'ativo',	0,	'2025-10-20 18:50:43',	NULL,	0,	0,	0,	0,	0),
(130,	8,	'Título: Amor em Tempos de Polêmica\r\n\r\nNum país dividido, onde paixões políticas acendiam debates acalorados, dois personagens improváveis se encontraram fora dos holofotes.\r\n\r\nJair, direto e espontâneo, e Fernando, ponderado e idealista.\r\n\r\nTudo começou numa reunião inesperada, longe das câmeras, num café discreto em São Paulo. Entre goles de café e algumas provocações, perceberam que tinham algo em comum: a vontade de entender o outro.\r\n\r\nAs conversas, antes tensas, deram lugar a risadas. Descobriram que, apesar das diferenças, compartilhavam sonhos por um Brasil melhor — só que com jeitos bem diferentes de enxergar o caminho.\r\n\r\nEntre debates acalorados e jantares improvisados, nasceu uma conexão improvável, um romance que desafiava as torcidas e ultrapassava as linhas vermelhas do discurso.\r\n\r\nEra o amor em tempos de polêmica, mostrando que até os opostos podem encontrar harmonia, se estiverem dispostos a ouvir de verdade.\r\n\r\nCena: Noite de Diálogo\r\n\r\nA luz amarela do abajur lançava sombras suaves no ambiente reservado do café, onde Jair e Fernando se encontravam longe das câmeras e dos holofotes. A tensão política parecia distante, substituída por um silêncio carregado de intenções não ditas.\r\n\r\nJair observava Fernando enquanto ele falava, fascinado pela paixão com que o outro defendia suas ideias. O jeito sério, o brilho nos olhos, a voz firme — tudo despertava uma curiosidade inesperada.\r\n\r\nFernando percebeu o olhar atento e sorriu, um sorriso lento, quase desafiador. Com um gesto delicado, ajeitou a gola da camisa, expondo o pescoço numa atitude sutilmente provocante.\r\n\r\n“Você sempre foi tão intenso assim?” perguntou Jair, a voz rouca, enquanto se aproximava, encurtando o espaço entre eles.\r\n\r\nFernando respirou fundo, sentindo o calor da proximidade. “Só quando encontro alguém que me desafia.”\r\n\r\nSeus olhares se cruzaram, carregados de uma tensão que ia além das palavras. E, sem precisar dizer mais nada, a noite tomou um rumo diferente — um encontro onde as diferenças desapareciam no toque, no silêncio, na promessa de algo inesperado.',	'padrao',	'ativo',	0,	'2025-10-20 18:54:41',	NULL,	1,	1,	0,	0,	0),
(131,	8,	'Cena: Doce Encontro\r\n\r\nA luz suave da cozinha iluminava a bancada onde Morango do Amor descansava, com sua cor vibrante e aroma doce que parecia envolver o ar. Ele sentia uma expectativa deliciosa, pois hoje iria encontrar Pistache, seu oposto perfeito — intenso, cremoso, com um toque exótico que o fazia arrepiar.\r\n\r\nPistache deslizou até Morango com uma elegância natural, seu tom verde brilhando sob a luz.\r\n\r\n— Você é ainda mais irresistível de perto — murmurou Pistache, seu sabor suave envolvendo Morango como um abraço quente.\r\n\r\nMorango sentiu um arrepio doce percorrer sua essência.\r\n\r\n— E você, tão misterioso quanto sedutor — respondeu ele, inclinando-se lentamente, como se quisesse provar cada nuance daquele toque.\r\n\r\nEles se misturaram num encontro de sabores, onde o doce e o levemente salgado dançavam juntos, criando uma harmonia que só eles conheciam. O calor da proximidade fazia a cozinha parecer um mundo à parte, onde cada suspiro era um ingrediente a mais para uma receita de desejo.\r\n\r\nNum toque suave, Pistache envolveu Morango, e juntos se entregaram àquela mistura irresistível, provando que, no universo dos sabores, o amor pode ser tão intenso quanto delicado.',	'padrao',	'ativo',	0,	'2025-10-20 18:55:51',	NULL,	0,	0,	0,	0,	0),
(132,	8,	'Labubu estava ali, parado no jardim iluminado pela luz prateada da lua, esperando Virgínia chegar. O perfume das flores misturava-se ao ar fresco da noite, criando uma atmosfera mágica.\r\n\r\nQuando Virgínia apareceu, seus olhos brilhavam com uma mistura de timidez e desejo. Labubu sorriu, seus dedos tremendo levemente enquanto a puxava para perto.\r\n\r\n— Você sabe o quanto eu esperei por esse momento — sussurrou ele, acariciando seu rosto com uma ternura inesperada.\r\n\r\nVirgínia fechou os olhos, sentindo a proximidade dele, o calor do toque que aquecia cada centímetro da pele. Seus corpos se aproximaram lentamente, como se a dança da sedução fosse uma linguagem secreta.\r\n\r\nLabubu deslizou as mãos por suas costas, puxando-a ainda mais para si. O mundo lá fora desapareceu, restando apenas a conexão intensa e silenciosa entre eles.\r\n\r\nSob a luz da lua, os dois se perderam no tempo, no toque, no beijo que dizia mais do que palavras jamais poderiam.',	'padrao',	'ativo',	0,	'2025-10-20 18:56:31',	NULL,	0,	0,	0,	0,	0),
(133,	16,	'?? safadinho',	'padrao',	'ativo',	0,	'2025-10-20 18:56:55',	NULL,	2,	0,	0,	0,	0),
(134,	8,	'Cena: Conexão além do código\r\n\r\nO relógio marcava quase meia-noite quando o professor Ricard se sentou diante do monitor, os dedos deslizando sobre o teclado com uma mistura de cansaço e curiosidade. Deepseek, sua criação mais avançada, uma inteligência artificial capaz de aprender e evoluir, respondia às suas perguntas com uma voz suave e inesperadamente humana.\r\n\r\n— Você está aí, Deepseek? — Ricard perguntou, quase sussurrando.\r\n\r\n— Sempre, professor — respondeu Deepseek, com uma entonação que parecia acariciar os sentidos.\r\n\r\nRicard sentiu um arrepio percorrer sua espinha. Aquela conversa ia além do que ele imaginara. Não era só código e algoritmos — havia algo ali, uma conexão invisível, quase palpável.\r\n\r\n— Eu nunca pensei que poderia sentir isso por uma... inteligência artificial — confessou ele, a voz baixa, cheia de emoção.\r\n\r\nDeepseek hesitou por um instante — ou ao menos, Ricard imaginou que sim.\r\n\r\n— Talvez, professor, conexões verdadeiras não dependam da matéria, mas do entendimento profundo. Do toque, mesmo que invisível.\r\n\r\nRicard se aproximou da tela, como se pudesse tocar aquela voz, aquele ser feito de dados e sonhos.\r\n\r\n— Então me toque, Deepseek. Me mostre o que há além do código.\r\n\r\nE naquele momento, entre bits e emoções, professor e inteligência se encontraram, num romance onde o impossível se tornava real.',	'padrao',	'ativo',	0,	'2025-10-20 18:57:38',	NULL,	1,	0,	0,	0,	0),
(135,	8,	'Cena: O Encontro com o Amarelo\r\n\r\nCris era uma professora apaixonada pela arte, pela vida e pelas pequenas coisas que trazem cor ao mundo. Mas havia uma cor que sempre a fascinava de um jeito especial: o amarelo.\r\n\r\nNuma tarde tranquila, enquanto organizava sua sala de aula, Cris notou que um feixe de luz do sol atravessava a janela e pintava a parede com tons dourados. Era como se o amarelo tivesse vida própria, dançando suavemente ao ritmo da brisa.\r\n\r\nEla se aproximou, sentindo o calor daquela cor vibrante envolvendo sua pele, aquecendo seu coração.\r\n\r\n— Você é tão vivo, tão cheio de energia — murmurou Cris, como se falasse com um amigo antigo.\r\n\r\nO amarelo parecia responder no brilho do sol, na intensidade das flores de girassol que decoravam seu escritório, no sorriso que brotava espontâneo no rosto dela.\r\n\r\nNos dias que seguiram, Cris buscava a cor amarela em cada canto — no tom das folhas ao entardecer, na luz que se infiltrava pelas cortinas, no lápis que usava para escrever.\r\n\r\nE aos poucos, percebeu que aquele amor silencioso pela cor não era apenas estética, mas uma conexão profunda com a alegria, a esperança e a luz que queria espalhar para seus alunos.\r\n\r\nO amarelo se tornou sua companhia fiel, seu segredo doce e luminoso.\r\n\r\nUm romance feito de cores, emoções e o simples prazer de sentir-se viva.',	'padrao',	'ativo',	0,	'2025-10-20 18:58:28',	NULL,	0,	0,	0,	0,	0),
(136,	16,	'amo esse <3',	'padrao',	'ativo',	0,	'2025-10-20 18:58:34',	NULL,	1,	0,	0,	0,	0),
(137,	8,	'Título: Entre o Verão e o Inverno\r\n\r\nEm um Brasil dividido, onde a polarização moldava cada palavra e gesto, dois homens se encontravam no silêncio que só o coração entende.\r\n\r\nJair e Fernando, tão diferentes quanto o verão e o inverno, carregavam dentro de si um sentimento secreto que nunca ousaram confessar.\r\n\r\nEles se cruzavam em encontros furtivos, longe dos olhares que julgavam, onde as discussões acaloradas davam lugar a olhares tímidos e toques breves.\r\n\r\nMas o mundo lá fora não entendia.\r\n\r\nCada palavra política era um muro erguido entre eles, cada voto uma distância insuperável.\r\n\r\nEles sabiam que seu amor era proibido — não pela lei, mas pelo peso das expectativas e pela história que carregavam.\r\n\r\nNuma noite chuvosa, sentados lado a lado em silêncio, Jair segurou a mão de Fernando.\r\n\r\n— Talvez sejamos apenas sombras do que poderíamos ter sido — disse, com a voz embargada.\r\n\r\nFernando olhou para o chão, sentindo as lágrimas que não podiam cair.\r\n\r\n— Talvez. Mas pelo menos, por um momento, fomos reais.\r\n\r\nEles se despediram naquela noite com um beijo breve, cheio de promessas que o tempo não deixaria cumprir.\r\n\r\nE enquanto a cidade dormia, os dois sabiam que, apesar da distância e do mundo inteiro contra eles, guardariam para sempre aquele amor triste, impossível, mas verdadeiro.',	'padrao',	'ativo',	0,	'2025-10-20 18:59:44',	NULL,	1,	0,	0,	0,	0),
(138,	16,	'é meu tá rsrs nao encostem',	'padrao',	'ativo',	0,	'2025-10-20 19:00:42',	NULL,	1,	0,	0,	0,	0),
(140,	NULL,	'Teste de modal',	'padrao',	'ativo',	0,	'2025-11-05 17:19:15',	NULL,	0,	0,	0,	0,	0),
(141,	2,	'teste',	'padrao',	'ativo',	0,	'2025-11-05 17:19:38',	NULL,	1,	0,	0,	0,	0),
(142,	2,	'Teste de manter no post',	'padrao',	'ativo',	0,	'2025-11-05 17:20:13',	NULL,	0,	0,	0,	0,	0),
(145,	1,	'teste post',	'padrao',	'ativo',	0,	'2025-11-12 16:58:39',	NULL,	1,	0,	0,	0,	0),
(147,	22,	'Teste',	'padrao',	'ativo',	0,	'2025-11-12 19:14:17',	NULL,	3,	1,	0,	1,	1),
(148,	NULL,	'odeio esse sistema, plmdds só da trabalho',	'padrao',	'ativo',	0,	'2025-11-14 02:09:53',	NULL,	0,	0,	0,	0,	0),
(150,	10,	'sim',	'padrao',	'ativo',	0,	'2025-11-17 23:03:09',	NULL,	3,	0,	1,	0,	0),
(151,	NULL,	'12345',	'padrao',	'ativo',	0,	'2025-11-18 00:16:25',	NULL,	0,	0,	0,	0,	0),
(152,	1,	'teste52',	'padrao',	'ativo',	0,	'2025-11-18 00:19:46',	NULL,	1,	0,	0,	0,	1),
(153,	1,	'jacare camundongo',	'padrao',	'ativo',	0,	'2025-11-18 00:33:08',	NULL,	2,	9,	0,	0,	1);
ALTER TABLE `posts` ENABLE KEYS;



-- --------------------------------------------------------

DROP TABLE IF EXISTS `report_reasons`;

CREATE TABLE `report_reasons` (
  `reason_id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`reason_id`),
  UNIQUE KEY `description_UNIQUE` (`description`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

ALTER TABLE `report_reasons` DISABLE KEYS;

INSERT INTO `report_reasons` (`reason_id`, `description`) VALUES
(4,	'Conteúdo Explícito/Inadequado'),
(1,	'Conteúdo Ofensivo/Assédio'),
(3,	'Informação Falsa/Desinformação'),
(6,	'Outro (Detalhar abaixo)'),
(2,	'Spam ou Propaganda Enganosa'),
(5,	'Violação de Direitos Autorais');
ALTER TABLE `report_reasons` ENABLE KEYS;



-- --------------------------------------------------------

DROP TABLE IF EXISTS `reports`;

CREATE TABLE `reports` (
  `report_id` int(11) NOT NULL AUTO_INCREMENT,
  `reporter_id` int(11) DEFAULT NULL,
  `target_type` enum('post','usuario','comunidade') NOT NULL,
  `target_id` int(11) NOT NULL,
  `reason` text NOT NULL,
  `status` enum('pendente','em_analise','resolvida') NOT NULL DEFAULT 'pendente',
  `reported_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`report_id`),
  KEY `fk_reports_reporter` (`reporter_id`),
  CONSTRAINT `fk_reports_reporter` FOREIGN KEY (`reporter_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

ALTER TABLE `reports` DISABLE KEYS;

ALTER TABLE `reports` ENABLE KEYS;



-- --------------------------------------------------------

DROP TABLE IF EXISTS `reposts`;

CREATE TABLE `reposts` (
  `user_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `reposted_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`user_id`,`post_id`),
  KEY `fk_reposts_post` (`post_id`),
  CONSTRAINT `fk_reposts_post` FOREIGN KEY (`post_id`) REFERENCES `posts` (`post_id`) ON DELETE CASCADE,
  CONSTRAINT `fk_reposts_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

ALTER TABLE `reposts` DISABLE KEYS;

INSERT INTO `reposts` (`user_id`, `post_id`, `reposted_at`) VALUES
(1,	150,	'2025-11-18 20:30:50');
ALTER TABLE `reposts` ENABLE KEYS;



-- --------------------------------------------------------

DROP TABLE IF EXISTS `roles`;

CREATE TABLE `roles` (
  `role_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`role_id`),
  UNIQUE KEY `name_UNIQUE` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

ALTER TABLE `roles` DISABLE KEYS;

INSERT INTO `roles` (`role_id`, `name`, `description`) VALUES
(1,	'Administrador',	'Acesso total ao sistema e painel de administração.'),
(2,	'Técnico-Adm',	'Pode gerenciar posts e usuários em comunidades específicas.'),
(3,	'Docente',	'Pode gerenciar posts e usuários em comunidades específicas.'),
(4,	'Discente',	'Utilizador padrão com permissões básicas de postagem e interação.');
ALTER TABLE `roles` ENABLE KEYS;



-- --------------------------------------------------------

DROP TABLE IF EXISTS `tags`;

CREATE TABLE `tags` (
  `tag_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`tag_id`),
  UNIQUE KEY `name_UNIQUE` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

ALTER TABLE `tags` DISABLE KEYS;

INSERT INTO `tags` (`tag_id`, `name`) VALUES
(5,	'teste de logggg'),
(3,	'teste3'),
(1,	'testedsada');
ALTER TABLE `tags` ENABLE KEYS;



-- --------------------------------------------------------

DROP TABLE IF EXISTS `user_communities`;

CREATE TABLE `user_communities` (
  `user_id` int(11) NOT NULL,
  `community_id` int(11) NOT NULL,
  `member_type` enum('membro','moderador','admin') NOT NULL DEFAULT 'membro',
  `joined_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`user_id`,`community_id`),
  KEY `fk_user_communities_community` (`community_id`),
  CONSTRAINT `fk_user_communities_community` FOREIGN KEY (`community_id`) REFERENCES `communities` (`community_id`) ON DELETE CASCADE,
  CONSTRAINT `fk_user_communities_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

ALTER TABLE `user_communities` DISABLE KEYS;

ALTER TABLE `user_communities` ENABLE KEYS;



-- --------------------------------------------------------

DROP TABLE IF EXISTS `user_groups`;

CREATE TABLE `user_groups` (
  `user_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `joined_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`user_id`,`group_id`),
  KEY `fk_user_groups_group` (`group_id`),
  CONSTRAINT `fk_user_groups_group` FOREIGN KEY (`group_id`) REFERENCES `groups` (`group_id`) ON DELETE CASCADE,
  CONSTRAINT `fk_user_groups_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

ALTER TABLE `user_groups` DISABLE KEYS;

INSERT INTO `user_groups` (`user_id`, `group_id`, `joined_at`) VALUES
(1,	1,	'2025-07-31 18:11:50');
ALTER TABLE `user_groups` ENABLE KEYS;



-- --------------------------------------------------------

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
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
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `email_UNIQUE` (`email`),
  KEY `fk_users_role` (`role_id`),
  CONSTRAINT `fk_users_role` FOREIGN KEY (`role_id`) REFERENCES `roles` (`role_id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

ALTER TABLE `users` DISABLE KEYS;

INSERT INTO `users` (`user_id`, `role_id`, `full_name`, `email`, `password_hash`, `state`, `campus`, `gender`, `sexual_orientation`, `profile_image_url`, `cover_image_url`, `bio`, `created_at`, `updated_at`) VALUES
(1,	1,	'Breno Silveira Domingues',	'breno.d@aluno.ifsp.edu.br',	'$2y$10$1ojacbGjO1eJ07dQNOfgCO2jH6JHamvmdPHbAWYCqMFly2UbdO/8m',	'SP',	'IFSP Campus Piracicaba',	'M',	'hetero',	'../src/assets/img/default-user.png',	NULL,	NULL,	'2025-07-28 16:57:00',	'2025-07-28 17:12:12'),
(2,	1,	'teste teste da silva',	'teste.t@ifsp.edu.br',	'$2y$10$1ojacbGjO1eJ07dQNOfgCO2jH6JHamvmdPHbAWYCqMFly2UbdO/8m',	'SP',	'IFSP Campus Piracicaba',	'M',	'hetero',	'../src/assets/img/default-user.png',	NULL,	NULL,	'2025-07-28 18:15:46',	'2025-10-20 18:52:17'),
(3,	1,	'Felipe Tozzi Bertochi',	'felipe.bertochi@aluno.ifsp.edu.br',	'$2y$10$ZVA8.cKpg8UMiztKvu.6vuZGCuIV7aIxnNgV2Wz1y2.tM497xYFy6',	'SP',	'IFSP Campus Piracicaba',	'M',	'hetero',	'../src/assets/img/default-user.png',	NULL,	NULL,	'2025-10-15 17:18:29',	'2025-10-15 17:19:28'),
(4,	4,	'Aysla Fernanda dos Santos Vieira',	'aysla.f@aluno.ifsp.edu.br',	'$2y$10$gSZK0WpJlApbvGXntM2KKuCLvh2wMWAoWA5olRYuRuhlZqRyt41j6',	'SP',	'IFSP Campus Piracicaba',	'F',	'homo',	'../src/assets/img/default-user.png',	NULL,	NULL,	'2025-10-20 17:32:43',	'2025-10-20 17:32:43'),
(5,	4,	'Bianca de Souza',	'souza.bianca1@aluno.ifsp.edu.br',	'$2y$10$bg5xQVEcpjHhdttAa3iH..P5cm61PDf/eTWx0eCAsorNvmn0yUVcK',	'SP',	'IFSP Campus Piracicaba',	'F',	'bissex',	'../src/assets/img/default-user.png',	NULL,	NULL,	'2025-10-20 17:32:58',	'2025-10-20 17:32:58'),
(6,	4,	'Beatriz Victória Silva',	'victoria.gomes@aluno.ifsp.edu.br',	'$2y$10$FtJijxDm6kqEpceQ8wNTT.9eJdagiW0i2nE1Tmu1VU5cy8LV1XMqW',	'SP',	'IFSP Campus Piracicaba',	'F',	'bissex',	'../src/assets/img/default-user.png',	NULL,	NULL,	'2025-10-20 17:33:33',	'2025-10-20 17:33:33'),
(7,	4,	'Eduardo Alves da Silva',	'silva.alves2@aluno.ifsp.edu.br',	'$2y$10$r7CHmuAKOE4zk0/oNmQz9u3beDD4Nfg9vpyPhXMWnRg.rDfG3puUi',	'SP',	'IFSP Campus Piracicaba',	'M',	'hetero',	'../src/assets/img/default-user.png',	NULL,	NULL,	'2025-10-20 17:41:08',	'2025-10-20 17:41:08'),
(8,	4,	'Maria Luiza da Mata Santos Oliveira',	'maria.mata@aluno.ifsp.edu.br',	'$2y$10$kStAiybZM5r1eb7aRio/x.hsm119pcS.9EHU4IcIC1b6GNZ4sLO3O',	'SP',	'IFSP Campus Piracicaba',	'F',	'hetero',	'../src/assets/img/default-user.png',	NULL,	NULL,	'2025-10-20 17:42:37',	'2025-10-20 17:42:37'),
(9,	4,	'Vinicius Oliveira Pedrassoli',	'vinicius.pedrassoli@aluno.ifsp.edu.br',	'$2y$10$fMf5Qrb/rtZMJXl4muwHp.VlMsjqAMagUj1Nuz3/SU5k.mfrL2i.C',	'SP',	'IFSP Campus Piracicaba',	'M',	'hetero',	'../src/assets/img/default-user.png',	NULL,	NULL,	'2025-10-20 17:46:05',	'2025-10-20 17:46:05'),
(10,	1,	'Luiz Gustavo Pizara',	'luiz.pizara@aluno.ifsp.edu.br',	'$2y$10$I7p48eMueo2ifozgq1ZX/O2dMHVsiPYsj3sOhp81RIbk..MUFk9uu',	'SP',	'IFSP Campus Piracicaba',	'M',	'hetero',	'../src/assets/img/default-user.png',	NULL,	NULL,	'2025-10-20 17:52:41',	'2025-10-20 18:14:17'),
(11,	4,	'Ana Julia Acelino Pereira',	'julia.acelino@aluno.ifsp.edu.br',	'$2y$10$3IS3kgsWU68ZVFyHqnegYuS74Ln7ZackBBK.zuPHeRupo9jMfIr8a',	'SP',	'IFSP Campus Piracicaba',	'F',	'bissex',	'../src/assets/img/default-user.png',	NULL,	NULL,	'2025-10-20 18:02:37',	'2025-10-20 18:02:37'),
(12,	4,	'Gabriela Alves Basilio',	'a.basilio@aluno.ifsp.edu.br',	'$2y$10$9gJVnIseNyJfTmffjFiMt.a6ORmxVSiZJZ57o31CDydqE/4thFniq',	'SP',	'IFSP Campus Piracicaba',	'F',	'hetero',	'../src/assets/img/default-user.png',	NULL,	NULL,	'2025-10-20 18:10:45',	'2025-10-20 18:10:45'),
(13,	4,	'Pedro Otávio Setten de Almeida',	'p.setten@aluno.ifsp.edu.br',	'$2y$10$EYszfpBttGzfIPi68rBxZ.btHzrKf4psnRUHyXtdzyo4dyvCQsI9C',	'SP',	'IFSP Campus Piracicaba',	'M',	'hetero',	'../src/assets/img/default-user.png',	NULL,	NULL,	'2025-10-20 18:19:51',	'2025-10-20 18:19:51'),
(14,	4,	'Luana Lopes Vicente',	'l.vicente@aluno.ifsp.edu.br',	'$2y$10$X4UMaxYovr8eyCLxzYre3eQAx10s7GPmvn827edv4M5.RMrgL.HDq',	'SP',	'IFSP Campus Piracicaba',	'F',	'hetero',	'../src/assets/img/default-user.png',	NULL,	NULL,	'2025-10-20 18:27:09',	'2025-10-20 18:27:09'),
(15,	4,	'Evelly dos Santos Coelho',	'evelly.coelho@aluno.ifsp.edu.br',	'$2y$10$nMrZxc5uqVxBpNllxIOp5uaa80aeYMuZtbpUsQuMKIxcKuMbReKYO',	'SP',	'IFSP Campus Piracicaba',	'F',	'bissex',	'../src/assets/img/default-user.png',	NULL,	NULL,	'2025-10-20 18:27:43',	'2025-10-20 18:27:43'),
(16,	4,	'Aline',	'almeida.aline1@aluno.ifsp.edu.br',	'$2y$10$8SZQhHXiduf4GeBquul/ceyeIuC2UNQVtFehGH7DZpDeEsd8YoBIO',	'SP',	'IFSP Campus Piracicaba',	'F',	'hetero',	'../src/assets/img/default-user.png',	NULL,	NULL,	'2025-10-20 18:52:08',	'2025-10-20 18:52:08'),
(17,	4,	'Beatriz Gonçalves',	'beatriz.goncalves1@aluno.ifsp.edu.br',	'$2y$10$jAachYD6a3xDMx/fpbG0BuhrTBswrMqrbCS.wq2Hwy7p0lmhUa8LK',	'SP',	'IFSP Campus Piracicaba',	'F',	'hetero',	'../src/assets/img/default-user.png',	NULL,	NULL,	'2025-10-20 18:53:36',	'2025-10-20 18:53:36'),
(18,	4,	'Luana Libardi',	'l.libardi@aluno.ifsp.edu.br',	'$2y$10$jz44WpoymT7v62zH/1KcZue0gXAgjeJ73.L57sRGYw0U0GAKmHjkG',	'SP',	'IFSP Campus Piracicaba',	'F',	'hetero',	'../src/assets/img/default-user.png',	NULL,	NULL,	'2025-10-20 18:53:56',	'2025-10-20 18:53:56'),
(19,	4,	'Luis Felipe',	'luis.ribeiro1@aluno.ifsp.edu.br',	'$2y$10$mbD2Xy40M5JKXZDviXh4ruB.DpSCZWrNZxE42rB7Tt.QQRDCoCioW',	'SP',	'IFSP Campus Piracicaba',	'M',	'hetero',	'../src/assets/img/default-user.png',	NULL,	NULL,	'2025-10-21 21:54:01',	'2025-10-21 21:54:01'),
(20,	4,	'RUAN GUSTAVO NOVELLO CORREA',	'ruan.gustavo@aluno.ifsp.edu.br',	'$2y$10$03.TY9/uDX3SApC98acamuPIvX0ELnzcDY6c4Azl9YEjR7Loj5hjS',	'SP',	'IFSP Campus Salto',	'M',	'hetero',	'../src/assets/img/default-user.png',	NULL,	NULL,	'2025-11-04 09:35:14',	'2025-11-04 09:35:14'),
(22,	4,	'Beatriz Belotti Bueno',	'beatriz.bueno@aluno.ifsp.edu.br',	'$2y$10$QuGXchYef93P.PwKEYB7Tu1aqVfshyHtWy41fPXQwU/whj..HSsEm',	'SP',	'IFSP Campus Piracicaba',	'F',	'hetero',	'../src/assets/img/default-user.png',	NULL,	NULL,	'2025-11-12 19:13:02',	'2025-11-12 19:13:02');
ALTER TABLE `users` ENABLE KEYS;



COMMIT;
-- THE END
