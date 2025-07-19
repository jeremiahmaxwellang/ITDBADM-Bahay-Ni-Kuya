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

    -- Now, convert PHP to USD, then to target: 
    -- (base_price / rate_to_usd) * rate_target
    IF base_price IS NULL OR rate_to_usd IS NULL OR rate_target IS NULL THEN
        SET converted_price = NULL; -- error indicator
    ELSE
        SET converted_price = (base_price / rate_to_usd) * rate_target;
    END IF;
END

$$ DELIMITER ;

DELIMITER $$

CREATE PROCEDURE sp_search_properties(
    IN loc VARCHAR(255),
    IN min_price DECIMAL(12,2),
    IN max_price DECIMAL(12,2)
)
BEGIN
    SELECT * FROM properties
    WHERE (address LIKE CONCAT('%', loc, '%') OR loc = '')
      AND price BETWEEN min_price AND max_price
      AND offer_type = 'For Sale';  -- Only list available properties
END

$$ DELIMITER ;

DELIMITER $$

CREATE PROCEDURE sp_place_order(
    IN p_email VARCHAR(100),
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

DELIMITER $$

CREATE PROCEDURE sp_update_property(
    IN p_property_id INT,
    IN p_property_name VARCHAR(255),
    IN p_address VARCHAR(255),
    IN p_price DECIMAL(15,2),
    IN p_description TEXT,
    IN p_offer_type VARCHAR(50)
)
BEGIN
    -- Update the property details except photo
    UPDATE property
    SET 
        property_name = p_property_name,
        address = p_address,
        price = p_price,
        description = p_description,
        offer_type = p_offer_type
    WHERE property_id = p_property_id;
    
END

$$ DELIMITER ;
