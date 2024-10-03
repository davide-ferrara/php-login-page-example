CREATE TABLE users (
	user_id INT PRIMARY KEY AUTO_INCREMENT,
	username varchar(255),
    	password_hash CHAR(60)
);

INSERT INTO users (username, password_hash) VALUES ('admin', 'admin');

