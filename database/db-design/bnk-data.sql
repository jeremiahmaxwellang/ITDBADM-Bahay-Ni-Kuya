-- Bahay Ni Kuya INSERT DATA SCRIPT

-- ITDBADM S13 Group 7 Project
-- Jeremiah Ang, Charles Duelas, Justin Lee, Marcus Mendoza

USE bahaynikuya_db;

-- INSERT USERS
INSERT INTO users(email, first_name, last_name, password_hash, role)
VALUES 
("admin@gmail.com", "Admin", "Man", "adminpassword", 'A'),

("customer@gmail.com", "Custom", "Murr", "customerpassword", 'C'),
("kent@gmail.com", "Clark", "Kent", "superman", 'C'),
("lane@gmail.com", "Lois", "Lane", "lanepassword", 'C'),
("mendoza@gmail.com", "Marcus", "Mendoza", "mendozapassword", 'C'),
("duelas@gmail.com", "Charles", "Duelas", "duelaspassword", 'C'),
("lee@gmail.com", "Justin", "Lee", "leepassword", 'C'),

("staff@gmail.com", "Staff", "Guy", "staffpassword", 'S');

-- INSERT CURRENCIES
INSERT INTO currencies(currency_id, currency_code, symbol, exchange_rate_to_usd)
VALUES
(1, "PHP", "₱", 57.18),
(2, "USD", "$", 1),
(3, "EUR", "€", 1.16)
;


-- INSERT PROPERTIES
INSERT INTO properties(property_name, address, price, description, offer_type, photo)
VALUES
("name", "address", 3599000.00, "describe", 'For Sale', "pbb_house.jpg"),
("name", "address", 5000000.00, "describe", 'For Sale', "pbb_house.jpg"),
("name", "address", 4222000.00, "describe", 'For Sale', "pbb_house.jpg"),
("name", "address", 2790000.00, "describe", 'For Sale', "pbb_house.jpg"),
("name", "address", 10585000.00, "describe", 'For Sale', "pbb_house.jpg"),
("name", "address", 2000000.00, "describe", 'For Sale', "pbb_house.jpg"),
("name", "address", 1999999.99, "describe", 'Sold', "pbb_house.jpg")
;