-- -----------------------------------------------------
-- Table `mineco-db`.`grupos_categorias`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mineco-db`.`grupos_categorias` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nomegrupo` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `nomegrupo_UNIQUE` (`nomegrupo` ASC) VISIBLE)
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `mineco-db`.`categorias`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mineco-db`.`categorias` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nomecategoria` VARCHAR(100) NOT NULL,
  `grupo_id` INT NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `grupo_id` (`grupo_id` ASC) VISIBLE,
  UNIQUE INDEX `nomecategoria_UNIQUE` (`nomecategoria` ASC) VISIBLE,
  CONSTRAINT `categorias_ibfk_1`
    FOREIGN KEY (`grupo_id`)
    REFERENCES `mineco-db`.`grupos_categorias` (`id`)
    ON DELETE SET NULL)
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;

--
-- Despejando dados para a tabela `categorias`
--

INSERT INTO `categorias` (`nomecategoria`, `grupo_id`) VALUES
('Sem categoria', NULL),
('Transferência', NULL);

-- -----------------------------------------------------
-- Table `mineco-db`.`grupos_contas`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mineco-db`.`grupos_contas` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nomegrupo` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `nomegrupo_UNIQUE` (`nomegrupo` ASC) VISIBLE)
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `mineco-db`.`contas`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mineco-db`.`contas` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nomeconta` VARCHAR(100) NOT NULL,
  `grupo_id` INT NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `grupo_id` (`grupo_id` ASC) VISIBLE,
  UNIQUE INDEX `nomeconta_UNIQUE` (`nomeconta` ASC) VISIBLE,
  CONSTRAINT `contas_ibfk_1`
    FOREIGN KEY (`grupo_id`)
    REFERENCES `mineco-db`.`grupos_contas` (`id`)
    ON DELETE SET NULL)
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `mineco-db`.`movimento`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mineco-db`.`movimento` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `data_mov` DATE NOT NULL,
  `historico` VARCHAR(255) NOT NULL,
  `valor` DECIMAL(10,2) NOT NULL,
  `conta_id` INT NOT NULL,
  `categoria_id` INT NULL DEFAULT NULL,
  `tipo` VARCHAR(20) CHARACTER SET 'utf8mb4' COLLATE 'utf8mb4_0900_ai_ci' NULL DEFAULT NULL,
  `conta_destino_id` INT NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `conta_id` (`conta_id` ASC) VISIBLE,
  INDEX `categoria_id` (`categoria_id` ASC) VISIBLE,
  INDEX `movimento_ibfk_3` (`conta_destino_id` ASC) VISIBLE,
  CONSTRAINT `movimento_ibfk_1`
    FOREIGN KEY (`conta_id`)
    REFERENCES `mineco-db`.`contas` (`id`)
    ON DELETE CASCADE,
  CONSTRAINT `movimento_ibfk_2`
    FOREIGN KEY (`categoria_id`)
    REFERENCES `mineco-db`.`categorias` (`id`)
    ON DELETE SET NULL,
  CONSTRAINT `movimento_ibfk_3`
    FOREIGN KEY (`conta_destino_id`)
    REFERENCES `mineco-db`.`contas` (`id`)
    ON DELETE CASCADE
    ON UPDATE RESTRICT)
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `mineco-db`.`users`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mineco-db`.`users` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(50) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `username_UNIQUE` (`username` ASC) VISIBLE)
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;