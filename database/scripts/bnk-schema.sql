-- Bahay Ni Kuya CREATE SCHEMA Script

-- ITDBADM S13 Group 7 Project
-- Jeremiah Ang, Charles Duelas, Justin Lee, Marcus Mendoza

-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema bahaynikuya_db
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `bahaynikuya_db` DEFAULT CHARACTER SET utf8 ;
USE `bahaynikuya_db` ;

-- -----------------------------------------------------
-- Table `bahaynikuya_db`.`security_questions`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bahaynikuya_db`.`security_questions` (
  `question_id` INT NOT NULL,
  `question` TEXT NULL,
  PRIMARY KEY (`question_id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `bahaynikuya_db`.`users`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bahaynikuya_db`.`users` (
  `email` VARCHAR(255) NOT NULL,
  `first_name` TEXT NULL,
  `last_name` TEXT NULL,
  `password_hash` TEXT NULL,
  `created_at` DATE NULL,
  `role` ENUM('C', 'A', 'S') NULL,
  `question_id` INT NULL,
  `question_answer` TEXT NULL,
  PRIMARY KEY (`email`),
  INDEX `fk_users_security_questions1_idx` (`question_id` ASC) ,
  CONSTRAINT `fk_users_security_questions1`
    FOREIGN KEY (`question_id`)
    REFERENCES `bahaynikuya_db`.`security_questions` (`question_id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `bahaynikuya_db`.`currencies`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bahaynikuya_db`.`currencies` (
  `currency_id` INT NOT NULL,
  `currency_code` VARCHAR(3) NULL,
  `symbol` VARCHAR(2) NULL,
  `exchange_rate_to_usd` DECIMAL(10,2) NULL,
  PRIMARY KEY (`currency_id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `bahaynikuya_db`.`orders`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bahaynikuya_db`.`orders` (
  `order_id` INT NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(255) NULL,
  `order_date` DATE NULL,
  `total_amount` DECIMAL(10,2) NULL,
  `currency_id` INT NULL,
  `is_confirmed` ENUM('Y', 'N') NULL DEFAULT 'N',
  PRIMARY KEY (`order_id`),
  INDEX `fk_orders_currencies1_idx` (`currency_id` ASC) ,
  INDEX `fk_orders_users1_idx` (`email` ASC) ,
  CONSTRAINT `fk_orders_currencies1`
    FOREIGN KEY (`currency_id`)
    REFERENCES `bahaynikuya_db`.`currencies` (`currency_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_orders_users1`
    FOREIGN KEY (`email`)
    REFERENCES `bahaynikuya_db`.`users` (`email`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `bahaynikuya_db`.`properties`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bahaynikuya_db`.`properties` (
  `property_id` INT NOT NULL AUTO_INCREMENT,
  `property_name` TEXT NULL,
  `address` TEXT NULL,
  `price` DECIMAL(10,2) NULL,
  `description` TEXT NULL,
  `offer_type` ENUM('For Sale', 'Sold') NULL,
  `photo` VARCHAR(260) NULL,
  PRIMARY KEY (`property_id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `bahaynikuya_db`.`order_items`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bahaynikuya_db`.`order_items` (
  `order_item_id` INT NOT NULL AUTO_INCREMENT,
  `order_id` INT NULL,
  `property_id` INT NULL,
  `quantity` INT NULL,
  PRIMARY KEY (`order_item_id`),
  INDEX `fk_order_items_orders1_idx` (`order_id` ASC) ,
  INDEX `fk_order_items_properties1_idx` (`property_id` ASC) ,
  CONSTRAINT `fk_order_items_orders1`
    FOREIGN KEY (`order_id`)
    REFERENCES `bahaynikuya_db`.`orders` (`order_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_order_items_properties1`
    FOREIGN KEY (`property_id`)
    REFERENCES `bahaynikuya_db`.`properties` (`property_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `bahaynikuya_db`.`transaction_log`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bahaynikuya_db`.`transaction_log` (
  `transaction_id` INT NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `order_id` INT NULL,
  `payment_method` VARCHAR(45) NULL,
  `payment_status` ENUM('PAID', 'UNPAID') NULL,
  `amount` DECIMAL(10,2) NULL,
  `timestamp` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`transaction_id`),
  INDEX `fk_transaction_log_orders1_idx` (`order_id` ASC) ,
  CONSTRAINT `fk_transaction_log_orders1`
    FOREIGN KEY (`order_id`)
    REFERENCES `bahaynikuya_db`.`orders` (`order_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `bahaynikuya_db`.`property_price_audit`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bahaynikuya_db`.`property_price_audit` (
  `audit_id` INT NOT NULL AUTO_INCREMENT,
  `property_id` INT NULL,
  `old_price` DECIMAL(10,2) NULL,
  `new_price` DECIMAL(10,2) NULL,
  `change_date` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`audit_id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `bahaynikuya_db`.`property_archive`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bahaynikuya_db`.`property_archive` (
  `property_id` INT NOT NULL,
  `property_name` TEXT NULL,
  `address` TEXT NULL,
  `price` DECIMAL(10,2) NULL,
  `description` TEXT NULL,
  `deleted_on` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`property_id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `bahaynikuya_db`.`old_passwords`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bahaynikuya_db`.`old_passwords` (
  `password_id` INT NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(255) NOT NULL,
  `password_hash` TEXT NULL,
  `password_created` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`password_id`),
  CONSTRAINT `fk_old_passwords_users1`
    FOREIGN KEY (`email`)
    REFERENCES `bahaynikuya_db`.`users` (`email`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `bahaynikuya_db`.`server_logs`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bahaynikuya_db`.`server_logs` (
  `log_id` INT NOT NULL AUTO_INCREMENT,
  `type` TEXT NULL,
  `datetime` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `user` TEXT NULL,
  `result` ENUM('Success', 'Failure') NULL,
  PRIMARY KEY (`log_id`))
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;