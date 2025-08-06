-- Bahay Ni Kuya TRIGGERS SCRIPT

-- ITDBADM S13 Group 7 Project
-- Jeremiah Ang, Charles Duelas, Justin Lee, Marcus Mendoza


-- 1. trg_after_order: Logs order completion in transaction_logs table
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
-- INSERT INTO orders(order_id, email, order_date, total_amount, currency_id)
-- VALUES(1, 'customer@gmail.com', CURDATE(), 4222000.00, 1)
-- ;

-- UPDATE orders
-- SET is_confirmed = 'Y'
-- WHERE order_id = 1;

-- SELECT * FROM transaction_log;



-- 2. trg_prevent_edit_sold: Blocks edits to sold properties
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
-- UPDATE properties
-- SET offer_type = 'Sold'
-- WHERE property_id = 1;

-- UPDATE properties
-- SET description = 'Testing the new trigger'
-- WHERE property_id = 1;



-- 3. [ADMIN PANEL] tg_archive_properties: Audits deleted property in a new property_archive table

USE bahaynikuya_db;
DELIMITER $$
CREATE TRIGGER tg_archive_properties
BEFORE DELETE ON properties
FOR EACH ROW
	BEGIN
		INSERT INTO property_archive(property_id, property_name, address, price, description)  
        VALUES(OLD.property_id, OLD.property_name, OLD.address, OLD.price, OLD.description);

		-- Delete order_items that reference the property
		DELETE FROM order_items WHERE property_id = OLD.property_id;

    END;
$$ DELIMITER ;

-- TESTING tg_archive_properties
-- USE bahaynikuya_db;
-- DELETE FROM properties WHERE property_id = 2;
-- SELECT * FROM property_archive;

-- USE bahaynikuya_db;
-- INSERT INTO properties(property_id, property_name, address, price, description, offer_type, photo)
-- VALUES
-- (2, "Mika Salamanca", "Tondo, Manila", 5000000.00, "Affordable property for sale in Tondo, Manila. Ideal for families or investors seeking a strategic location near schools, markets, and key city routes.", 'For Sale', "../assets/images/Mika_Salamanca.jpg")
-- ;

-- 4. trg_property_price_audit: Tracks all price changes of the properties
USE bahaynikuya_db;

DELIMITER $$
CREATE TRIGGER trg_property_price_audit
AFTER UPDATE ON properties
FOR EACH ROW
BEGIN
    IF OLD.price != NEW.price THEN
        INSERT INTO property_price_audit (property_id, old_price, new_price)
        VALUES (OLD.property_id, OLD.price, NEW.price);
    END IF;
END
$$ DELIMITER ;

-- TESTING trg_property_price_audit
-- USE bahaynikuya_db;

-- UPDATE properties
-- SET price = 2700000.00
-- WHERE property_id = 1;

-- SELECT * FROM property_price_audit;

-- 5. [ADMIN PANEL] trg_check_property_price: Do not create if new property price is less than 100000
USE bahaynikuya_db;

DELIMITER $$
CREATE TRIGGER trg_check_property_price
	BEFORE INSERT ON properties
	FOR EACH ROW
	BEGIN
		IF NEW.price < 100000 THEN 
        SIGNAL SQLSTATE '45000'
		SET MESSAGE_TEXT = "Property price is too low.";
	END IF;
END
$$ DELIMITER ;

-- TESTING trg_check_property_price
-- USE bahaynikuya_db;

-- INSERT INTO properties(property_name, address, price, description, offer_type, photo)
-- VALUES
-- ("Doofenshmirtz Evil Incoroprated", 
-- "Bonifacio Global City, Taguig", 
-- 9000.00, 
-- "Prime real estate in midtown Tri-State area.", 
-- 'For Sale', 
-- "../assets/images/Brent_Manalo.jpg");

-- 6. [ REGISTER ] tg_record_password_oninsert: Save previous passwords to prevent password reuse
USE bahaynikuya_db;
DELIMITER $$
CREATE TRIGGER tg_record_password_oninsert
AFTER INSERT ON users
FOR EACH ROW
	BEGIN
		INSERT INTO old_passwords(email, password_hash)  
        VALUES(NEW.email, NEW.password_hash);
    END
$$ DELIMITER ;

-- 7. [ PROFILE ] tg_record_password_onupdate: Save previous passwords to prevent password reuse
USE bahaynikuya_db;
DELIMITER $$
CREATE TRIGGER tg_record_password_onupdate
AFTER UPDATE ON users
FOR EACH ROW
	BEGIN
		INSERT INTO old_passwords(email, password_hash)  
        VALUES(OLD.email, NEW.password_hash);
    END
$$ DELIMITER ;