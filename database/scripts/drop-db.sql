USE bahaynikuya_db;

-- Generate DROP statements for all tables
SELECT CONCAT('DROP TABLE IF EXISTS `', table_name, '`;')
FROM information_schema.tables
WHERE table_schema = 'bahaynikuya_db';