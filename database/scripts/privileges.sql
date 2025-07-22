-- Bahay Ni Kuya PRIVILEGES SCRIPT

-- ITDBADM S13 Group 7 Project
-- Jeremiah Ang, Charles Duelas, Justin Lee, Marcus Mendoza

-- Grant privileges for Admin and Staff

-- Creating an ADMIN user and granting FULL ACCESS to the db
CREATE USER 'admin'@'%' IDENTIFIED BY 'adminpassword';
GRANT ALL PRIVILEGES ON *.* TO 'admin'@'%' WITH GRANT OPTION;
-- FLUSH PRIVILEGES; -- not working on myphpadmin

-- Create STAFF user
CREATE USER 'staff'@'%' IDENTIFIED BY 'staffpassword';
-- Grant partial access to STAFF (read-only access to properties, orders, and order_items)
GRANT SELECT ON bahaynikuya_db.properties TO 'staff'@'%';
GRANT SELECT ON bahaynikuya_db.orders TO 'staff'@'%';
GRANT SELECT ON bahaynikuya_db.order_items TO 'staff'@'%';


-- FLUSH PRIVILEGES; -- not working on myphpadmin

-- See list of users created in MYSQL
SELECT user, host FROM mysql.user;

-- Show the privileges for each user
SHOW GRANTS FOR 'admin'@'%'; 
SHOW GRANTS FOR 'staff'@'%'; 

-- IN CASE OF ERROR: FLUSH PRIVILEGES;
-- MySQL said: Documentation
-- #1030 - Got error 176 "Read page with wrong checksum" from storage engine Aria

-- 1. Repair Aria Tables
REPAIR TABLE mysql.host USE_FRM;
REPAIR TABLE mysql.user USE_FRM;