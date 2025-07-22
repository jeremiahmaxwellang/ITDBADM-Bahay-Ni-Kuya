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

-- -----------------------------------------------------
-- Schema bahaynikuya_db
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `bahaynikuya_db` DEFAULT CHARACTER SET utf8 ;
USE `bahaynikuya_db` ;

-- -----------------------------------------------------
-- Table `bahaynikuya_db`.`users`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bahaynikuya_db`.`users` (
  `email` VARCHAR(100) NOT NULL,
  `first_name` VARCHAR(100) NULL,
  `last_name` VARCHAR(100) NULL,
  `password_hash` VARCHAR(200) NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `role` ENUM('C', 'A', 'S') NULL,
  PRIMARY KEY (`email`))
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
  `email` VARCHAR(100) NULL,
  `order_date` DATE NULL,
  `total_amount` DECIMAL(10,2) NULL,
  `currency_id` INT NULL,
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

-- NEW is_confirmed column
ALTER TABLE `bahaynikuya_db`.`orders` 
ADD `is_confirmed` ENUM('Y', 'N') DEFAULT 'N';


-- -----------------------------------------------------
-- Table `bahaynikuya_db`.`properties`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bahaynikuya_db`.`properties` (
  `property_id` INT AUTO_INCREMENT NOT NULL,
  `property_name` VARCHAR(100) NULL,
  `address` VARCHAR(300) NULL,
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
  `transaction_id` INT NOT NULL,
  `order_id` INT NULL,
  `payment_method` VARCHAR(45) NULL,
  `payment_status` ENUM('PAID', 'UNPAID') NULL,
  `amount` DECIMAL(10,2) NULL,
  `timestamp` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`transaction_id`),
  INDEX `fk_transaction_log_orders1_idx` (`order_id` ASC) ,
  CONSTRAINT `fk_transaction_log_orders1`
    FOREIGN KEY (`order_id`)
    REFERENCES `bahaynikuya_db`.`orders` (`order_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

ALTER TABLE `bahaynikuya_db`.`transaction_log` 
MODIFY transaction_id INT AUTO_INCREMENT NOT NULL;

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
