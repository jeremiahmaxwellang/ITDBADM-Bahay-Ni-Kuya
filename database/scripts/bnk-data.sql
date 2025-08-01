-- Bahay Ni Kuya INSERT DATA SCRIPT

-- ITDBADM S13 Group 7 Project
-- Jeremiah Ang, Charles Duelas, Justin Lee, Marcus Mendoza

USE bahaynikuya_db;

-- INSERT USERS
INSERT INTO users(email, first_name, last_name, password_hash, role)
VALUES 
-- admin user account
("admin@gmail.com", "Admin", "Man", "adminpassword", 'A'),

("customer@gmail.com", "Custom", "Murr", "customerpassword", 'C'),
("kent@gmail.com", "Clark", "Kent", "superman", 'C'),
("lane@gmail.com", "Lois", "Lane", "lanepassword", 'C'),
("mendoza@gmail.com", "Marcus", "Mendoza", "mendozapassword", 'C'),
("duelas@gmail.com", "Charles", "Duelas", "duelaspassword", 'C'),
("lee@gmail.com", "Justin", "Lee", "leepassword", 'C'),

-- staff user account
("staff@gmail.com", "Staff", "Guy", "staffpassword", 'S');

-- INSERT CURRENCIES
INSERT INTO currencies(currency_id, currency_code, symbol, exchange_rate_to_usd)
VALUES
(1, "PHP", "₱", 57.18),
(2, "USD", "$", 1),
(3, "EUR", "€", 1.16)
;


-- INSERT PROPERTIES
INSERT INTO properties(property_id, property_name, address, price, description, offer_type, photo)
VALUES
(1, "Brent Manalo", "Bonifacio Global City, Taguig", 1500000.00, "Prime property for sale in the heart of BGC, Taguig. Perfect for investment or upscale living in Metro Manila’s premier business and lifestyle district.", 'For Sale', "../assets/images/Brent_Manalo.jpg"),
(2, "Mika Salamanca", "Tondo, Manila", 5000000.00, "Affordable property for sale in Tondo, Manila. Ideal for families or investors seeking a strategic location near schools, markets, and key city routes.", 'For Sale', "../assets/images/Mika_Salamanca.jpg"),
(3, "Ralph De Leon", "Intramuros, Manila", 4222000.00, "Historic property for sale in Intramuros, Manila. Offering timeless charm and a unique opportunity to own real estate within the city's iconic walled district.", 'For Sale', "../assets/images/Ralph.jpg"),
(4, "Esnyr", "Binondo, Manila", 10585000.00, "Prime property for sale in Binondo, Manila. Located in the world’s oldest Chinatown, perfect for commercial or residential use with high foot traffic and rich cultural appeal.", 'For Sale', "../assets/images/esnyr.jpg"),
(5, "Charlie", "Calamba, Laguna", 2000000.00, "Spacious property for sale in Calamba, Laguna, ideal for residential or vacation use and located in a peaceful community near hot springs, resorts, and key city amenities.", 'For Sale', "../assets/images/charlie.jpg"),
(6, "Klarisse", "Cavite", 1999999.99, "Affordable property for sale in Cavite, perfect for families or investors looking for a growing community with easy access to Metro Manila and nearby commercial hubs.", 'Sold', "../assets/images/klarisse.jpg")
;