-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema sistema_restaurante
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema sistema_restaurante
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `sistema_restaurante` DEFAULT CHARACTER SET utf8 ;
-- -----------------------------------------------------
-- Schema sistema_restaurante
-- -----------------------------------------------------

USE `sistema_restaurante` ;

-- -----------------------------------------------------
-- Table `sistema_restaurante`.`usuario`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sistema_restaurante`.`usuario` (
  `id_usuario` INT NOT NULL AUTO_INCREMENT,
  `email_usuario` VARCHAR(150) NULL,
  `senha_usuario` CHAR(128) NULL,
  `celular_usuario` VARCHAR(13) NULL,
  `cpf_usuario` VARCHAR(11) NULL,
  `nome_usuario` VARCHAR(60) NULL,
  PRIMARY KEY (`id_usuario`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `sistema_restaurante`.`mesa`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sistema_restaurante`.`mesa` (
  `id_mesa` INT NOT NULL AUTO_INCREMENT,
  `numero_mesa` INT NULL,
  `capacidade_mesa` INT NULL,
  `status_mesa` ENUM('disponivel', 'ocupada', 'reservada') NULL,
  PRIMARY KEY (`id_mesa`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `sistema_restaurante`.`pedidos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sistema_restaurante`.`pedidos` (
  `id_pedidos` INT NOT NULL AUTO_INCREMENT,
  `status_pedidos` VARCHAR(45) NULL,
  `data_hora_pedidos` DATETIME NOT NULL,
  `total_pedidos` DOUBLE NOT NULL,
  `usuario_id_usuario` INT NOT NULL,
  `mesa_id_mesa` INT NOT NULL,
  PRIMARY KEY (`id_pedidos`),
  INDEX `fk_pedidos_usuario_idx` (`usuario_id_usuario` ASC),
  INDEX `fk_pedidos_mesa1_idx` (`mesa_id_mesa` ASC),
  CONSTRAINT `fk_pedidos_usuario`
    FOREIGN KEY (`usuario_id_usuario`)
    REFERENCES `sistema_restaurante`.`usuario` (`id_usuario`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_pedidos_mesa1`
    FOREIGN KEY (`mesa_id_mesa`)
    REFERENCES `sistema_restaurante`.`mesa` (`id_mesa`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `sistema_restaurante`.`itens_pedido`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sistema_restaurante`.`itens_pedido` (
  `id_itens_pedido` INT NOT NULL AUTO_INCREMENT,
  `quantidade` INT NULL,
  `preco_unitario` DOUBLE NULL,
  `pedidos_id_pedidos` INT NOT NULL,
  PRIMARY KEY (`id_itens_pedido`),
  INDEX `fk_itens_pedido_pedidos1_idx` (`pedidos_id_pedidos` ASC),
  CONSTRAINT `fk_itens_pedido_pedidos1`
    FOREIGN KEY (`pedidos_id_pedidos`)
    REFERENCES `sistema_restaurante`.`pedidos` (`id_pedidos`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `sistema_restaurante`.`reserva`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sistema_restaurante`.`reserva` (
  `id_reserva` INT NOT NULL AUTO_INCREMENT,
  `data_hora_reserva` DATETIME NULL,
  `status_reserva` VARCHAR(45) NULL,
  `usuario_id_usuario` INT NOT NULL,
  PRIMARY KEY (`id_reserva`),
  INDEX `fk_reserva_usuario1_idx` (`usuario_id_usuario` ASC),
  CONSTRAINT `fk_reserva_usuario1`
    FOREIGN KEY (`usuario_id_usuario`)
    REFERENCES `sistema_restaurante`.`usuario` (`id_usuario`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `sistema_restaurante`.`cardapio`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sistema_restaurante`.`cardapio` (
  `id_item` INT NOT NULL AUTO_INCREMENT,
  `nome_item` VARCHAR(100) NULL,
  `descricao_item` MEDIUMTEXT NULL,
  `preco_item` DECIMAL(10,2) NULL,
  PRIMARY KEY (`id_item`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `sistema_restaurante`.`categoria_itens`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sistema_restaurante`.`categoria_itens` (
  `nome_categoria_itens` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`nome_categoria_itens`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `sistema_restaurante`.`categoria_itens_has_cardapio`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sistema_restaurante`.`categoria_itens_has_cardapio` (
  `categoria_itens_nome_categoria_itens` VARCHAR(50) NOT NULL,
  `cardapio_id_item` INT NOT NULL,
  PRIMARY KEY (`categoria_itens_nome_categoria_itens`, `cardapio_id_item`),
  INDEX `fk_categoria_itens_has_cardapio_cardapio1_idx` (`cardapio_id_item` ASC),
  INDEX `fk_categoria_itens_has_cardapio_categoria_itens1_idx` (`categoria_itens_nome_categoria_itens` ASC) ,
  CONSTRAINT `fk_categoria_itens_has_cardapio_categoria_itens1`
    FOREIGN KEY (`categoria_itens_nome_categoria_itens`)
    REFERENCES `sistema_restaurante`.`categoria_itens` (`nome_categoria_itens`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_categoria_itens_has_cardapio_cardapio1`
    FOREIGN KEY (`cardapio_id_item`)
    REFERENCES `sistema_restaurante`.`cardapio` (`id_item`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `sistema_restaurante`.`carrinho`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sistema_restaurante`.`carrinho` (
  `cardapio_id_item` INT NOT NULL,
  `usuario_id_usuario` INT NOT NULL,
  `quantidade_item` INT NULL,
  `observacao_item` TEXT(150) NULL,
  PRIMARY KEY (`cardapio_id_item`, `usuario_id_usuario`),
  INDEX `fk_cardapio_has_usuario_usuario1_idx` (`usuario_id_usuario` ASC),
  INDEX `fk_cardapio_has_usuario_cardapio1_idx` (`cardapio_id_item` ASC),
  CONSTRAINT `fk_cardapio_has_usuario_cardapio1`
    FOREIGN KEY (`cardapio_id_item`)
    REFERENCES `sistema_restaurante`.`cardapio` (`id_item`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_cardapio_has_usuario_usuario1`
    FOREIGN KEY (`usuario_id_usuario`)
    REFERENCES `sistema_restaurante`.`usuario` (`id_usuario`)
    ON DELETE CASCADE
    ON UPDATE CASCADE) ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `sistema_restaurante`.`endereco` (
  `id_endereco` INT AUTO_INCREMENT PRIMARY KEY,
  `cep` VARCHAR(9) NOT NULL,
  `tipo_endereco` CHAR(1) NOT NULL, -- 'C' para comercial, 'R' para residencial (exemplo)
  `logradouro` VARCHAR(100) NOT NULL,
  `numero` INT NOT NULL,
  `complemento` VARCHAR(50) NULL,
  `bairro` VARCHAR(50) NOT NULL,
  `cidade` VARCHAR(50) NOT NULL,
  `unidade_federal` CHAR(2) NOT NULL
) ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `sistema_restaurante`.`endereco_has_usuario` (
  `endereco_id_endereco` INT NOT NULL,
  `usuario_id_usuario` INT NOT NULL,
  PRIMARY KEY (`endereco_id_endereco`, `usuario_id_usuario`),
  CONSTRAINT `fk_endereco_has_usuario_usuario1`
    FOREIGN KEY (`usuario_id_usuario`)
    REFERENCES `sistema_restaurante`.`usuario` (`id_usuario`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_endereco_has_usuario_endereco1`
    FOREIGN KEY (`endereco_id_endereco`)
    REFERENCES `sistema_restaurante`.`endereco` (`id_endereco`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
