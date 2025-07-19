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

