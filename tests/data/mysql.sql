/**
 * This is the database schema for testing MySQL support of ARFixture
 *
 * The database setup in config.php is required to perform then relevant tests:
 */

DROP TABLE IF EXISTS `customer` CASCADE;

CREATE TABLE `customer` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `email` varchar(128) NOT NULL,
    `name` varchar(128) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
