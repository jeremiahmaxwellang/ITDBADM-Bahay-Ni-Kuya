USE bahaynikuya_db;


-- -----------------------------------------------------
-- Added July 20, 2025
-- -----------------------------------------------------

-- trg_after_order: Logs order completion in transaction_logs table
USE bahaynikuya_db;

DELIMITER $$
CREATE TRIGGER trg_after_order
AFTER UPDATE ON orders
FOR EACH ROW
BEGIN
    IF OLD.is_confirmed != NEW.is_confirmed THEN
        INSERT INTO transaction_log (order_id, payment_status, amount)
        VALUES (OLD.order_id, 'UNPAID', OLD.total_amount);
    END IF;
END
$$ DELIMITER ;

-- TESTING trg_after_order
INSERT INTO orders(order_id, email, order_date, total_amount, currency_id)
VALUES(1, 'customer@gmail.com', CURDATE(), 4222000.00, 1)
;

UPDATE orders
SET is_confirmed = 'Y'
WHERE order_id = 1;

SELECT * FROM transaction_log;


-- NOT WORKING: sp_property_sold: Call this to update offer_type to "Sold" after purchased

-- USE bahaynikuya_db;

-- DELIMITER $$
-- CREATE PROCEDURE sp_property_sold(
--     IN my_order_id INT
-- )
-- BEGIN
--     UPDATE properties
--     SET offer_type = 'Sold'
--     WHERE property_id IN (
--         SELECT  property_id
--         FROM    order_items
--         WHERE   order_id = OLD.order_id
--     );
-- END
-- $$ DELIMITER ;

-- -- TESTING sp_property_sold
-- INSERT INTO orders(order_id, email, order_date, total_amount, currency_id)
-- VALUES(2, 'kent@gmail.com', CURDATE(), 4222000.00, 1)
-- ;

-- INSERT INTO order_items(order_item_id, order_id, property_id, quantity)
-- VALUES(1, 2, 5, 1)
-- ;

-- INSERT INTO order_items(order_item_id, order_id, property_id, quantity)
-- VALUES(2, 2, 4, 1)
-- ;

-- CALL sp_property_sold(2);




-- trg_prevent_edit_sold: Blocks edits to sold properties
USE bahaynikuya_db;

DELIMITER $$
CREATE TRIGGER trg_prevent_edit_sold
	BEFORE UPDATE ON properties
	FOR EACH ROW
	BEGIN
		IF OLD.offer_type = 'Sold' THEN 
        SIGNAL SQLSTATE '45000'
		SET MESSAGE_TEXT = "Unable to edit. Property has been sold.";
	END IF;
END
$$ DELIMITER ;

-- TESTING trg_prevent_edit_sold
UPDATE properties
SET offer_type = 'Sold'
WHERE property_id = 1;

UPDATE properties
SET description = 'Testing the new trigger'
WHERE property_id = 1;