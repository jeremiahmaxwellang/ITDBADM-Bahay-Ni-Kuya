-- Bahay Ni Kuya STORED PROCEDURES SCRIPT

-- ITDBADM S13 Group 7 Project
-- Jeremiah Ang, Charles Duelas, Justin Lee, Marcus Mendoza

-- List of all procedures
-- SHOW PROCEDURE STATUS WHERE Db = 'bahaynikuya_db';

USE bahaynikuya_db;

DELIMITER $$

CREATE PROCEDURE sp_convert_currency (
    IN prop_id INT,
    IN target_currency VARCHAR(3),
    OUT converted_price DECIMAL(12,2)
)
BEGIN
    DECLARE base_price DECIMAL(12,2);
    DECLARE rate_to_usd DECIMAL(10,6);
    DECLARE rate_target DECIMAL(10,6);

    -- Get PHP price from properties table
    SELECT price INTO base_price FROM properties WHERE property_id = prop_id LIMIT 1;

    -- Get PHP to USD rate
    SELECT exchange_rate_to_usd INTO rate_to_usd 
    FROM currencies WHERE currency_code = 'PHP' LIMIT 1;

    -- Get target currency to USD rate
    SELECT exchange_rate_to_usd INTO rate_target 
    FROM currencies WHERE currency_code = target_currency LIMIT 1;

    -- (base_price / rate_to_usd) * rate_target
    IF base_price IS NULL OR rate_to_usd IS NULL OR rate_target IS NULL THEN
        SET converted_price = NULL; -- error indicator
    ELSE
        SET converted_price = (base_price / rate_to_usd) * rate_target;
    END IF;
END
$$ DELIMITER ;


-- 2. sp_search_properties: filter by price range
USE bahaynikuya_db;

DELIMITER $$

CREATE PROCEDURE sp_search_properties(
    IN loc VARCHAR(255),
    IN min_price DECIMAL(12,2),
    IN max_price DECIMAL(12,2)
)
BEGIN
    SELECT * FROM properties
    WHERE (address LIKE CONCAT('%', loc, '%') OR loc = '')
      AND price BETWEEN min_price AND max_price;
END

$$ DELIMITER ;


-- 3. sp_place_order: Creates an order
USE bahaynikuya_db;

DELIMITER $$

CREATE PROCEDURE sp_place_order(
    IN p_email VARCHAR(254),
    IN p_total_amount DECIMAL(10,2),
    IN p_currency_id INT,
    OUT p_order_id INT  -- output parameter to get the new order ID
)
BEGIN
    INSERT INTO orders(email, order_date, total_amount, currency_id)
    VALUES (p_email, CURDATE(), p_total_amount, p_currency_id);

    -- Get the last inserted order_id
    SET p_order_id = LAST_INSERT_ID();
END

$$ DELIMITER ;


-- 4. sp_add_order_item
USE bahaynikuya_db;

DELIMITER $$

CREATE PROCEDURE sp_add_order_item(
    IN p_order_id INT,
    IN p_property_id INT,
    IN p_quantity INT
)
BEGIN
    INSERT INTO order_items(order_id, property_id, quantity)
    VALUES (p_order_id, p_property_id, p_quantity);
END

$$ DELIMITER ;


-- 5. sp_cancel_order: Cancels order and restores property status
USE bahaynikuya_db;

DELIMITER $$

CREATE PROCEDURE sp_cancel_order(
    IN p_order_id INT,
    IN p_property_id INT
)
BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Error occurred, transaction rolled back';
    END;

    START TRANSACTION;

    -- Delete order items for this order
    DELETE FROM order_items WHERE order_id = p_order_id;

    -- Delete order record
    DELETE FROM orders WHERE order_id = p_order_id;

    -- Restore property to 'For Sale' if it was 'Sold'
    UPDATE properties 
    SET offer_type = 'For Sale' 
    WHERE property_id = p_property_id AND offer_type = 'Sold';

    COMMIT;
END

$$ DELIMITER ;


-- 6. sp_update_property: Admin edits property details 
USE bahaynikuya_db;

DELIMITER $$

CREATE PROCEDURE sp_update_property(
    IN p_property_id INT,
    IN p_property_name TEXT,
    IN p_address TEXT,
    IN p_price DECIMAL(10,2),
    IN p_description TEXT,
    IN p_photo VARCHAR(260)
)
BEGIN
    -- Update the property details 
    UPDATE properties
    SET 
        property_name = p_property_name,
        address = p_address,
        price = p_price,
        description = p_description,
        photo = p_photo
    WHERE property_id = p_property_id;
    
END

$$ DELIMITER ;


-- 7. sp_add_property: Admin adds new property
USE bahaynikuya_db;

DELIMITER $$

CREATE PROCEDURE sp_add_property(
    IN p_property_name TEXT,
    IN p_address TEXT,
    IN p_price DECIMAL(10,2),
    IN p_description TEXT,
    IN p_photo VARCHAR(260)
)
BEGIN
    -- Insert new property
    INSERT INTO properties(property_name, address, price, description, offer_type, photo) 
    VALUES (p_property_name, p_address, p_price, p_description, 'For Sale', p_photo);
    
END

$$ DELIMITER ;


-- 8. sp_add_user: Insert new user
USE bahaynikuya_db;

DELIMITER $$

CREATE PROCEDURE sp_add_user(
    IN u_email VARCHAR(254),
    IN u_first_name TEXT,
    IN u_last_name TEXT,
    IN u_password_hash TEXT
)
BEGIN
    INSERT INTO users(email, first_name, last_name, password_hash, role) 
    VALUES (u_email, u_first_name, u_last_name, u_password_hash, 'C');
    
END

$$ DELIMITER ;



-- 9. sp_latest_order: Get the latest unconfirmed order of the user
USE bahaynikuya_db;

DELIMITER $$

CREATE PROCEDURE sp_latest_order(
    IN o_email VARCHAR(254),
    OUT o_order_id INT
)
BEGIN
    SELECT o.order_id INTO o_order_id
    FROM orders o
    LEFT JOIN transaction_log t ON o.order_id = t.order_id
    WHERE o.email = o_email AND o.is_confirmed = 'N'
    ORDER BY o.order_date DESC
    LIMIT 1;
END 
$$ DELIMITER ;

-- 10. sp_record_password: Save previous passwords to prevent password reuse
--     CALL this whenever password is changed
DELIMITER $$

CREATE PROCEDURE sp_record_password(
    IN my_email VARCHAR(254),
    IN my_password_hash TEXT
)
BEGIN
    INSERT INTO old_passwords(email, password_hash)  
    VALUES(my_email, my_password_hash);
END 
$$ DELIMITER ;


-- 11. [LOGS] sp_log_event: Log to event_logs table: all successful and failed user logins, input validation failures, access control failure
-- this_type:   ENUM('I', 'A', 'C')
-- this_result: ENUM('Success', 'Fail')

DELIMITER $$
CREATE PROCEDURE sp_log_event(
    IN this_type VARCHAR(1),
    IN this_user_email VARCHAR(254),
    IN this_result VARCHAR(10) 
)
BEGIN
    INSERT INTO event_logs(type, user_email, result)  
    VALUES(this_type, this_user_email, this_result);
END
$$ DELIMITER ;