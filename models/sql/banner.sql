CREATE TABLE IF NOT EXISTS `banner` (
	id INT(12) PRIMARY KEY AUTO_INCREMENT,
  position VARCHAR(128),
	title VARCHAR(128),
	uniqid VARCHAR(128),
	url VARCHAR(256),
	status TINYINT(1),
	publish_date DATETIME,
	unpublish_date DATETIME
);
