USE joffre;

DROP TABLE IF EXISTS Offers;
DROP TABLE IF EXISTS Products;
DROP TABLE IF EXISTS Users;

CREATE TABLE Users (
	user_id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
	username CHAR(50) UNIQUE NOT NULL,
	email CHAR(255) UNIQUE NOT NULL,
	hashed_password CHAR(255) NOT NULL
);

CREATE TABLE Products (
	id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
	user_id INT,
	name CHAR(50) NOT NULL,
	description VARCHAR(255),
	offers INT DEFAULT 0,
	FOREIGN KEY (user_id) REFERENCES Users(user_id)
);

CREATE TABLE Offers (
	id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
	product_id INT,
	product_quantity INT DEFAULT 1,
	offer_id INT,
	offer_quantity INT DEFAULT 1,
	user_id INT,
	FOREIGN KEY (product_id) REFERENCES Products(id),
	FOREIGN KEY (offer_id) REFERENCES Products(id),
	FOREIGN KEY (user_id) REFERENCES Users(user_id)
);

INSERT INTO Users (username, email, hashed_password) VALUES
	('user1', 'user1@mail.com', '$2y$10$LHrgwXD8Rei.e32ZUjvDaevbmM6MJhqJkN7.0TW2TmR0by3EGQDxu'),
	('user2', 'user2@mail.com', '$2y$10$LHrgwXD8Rei.e32ZUjvDaevbmM6MJhqJkN7.0TW2TmR0by3EGQDxu'),
	('user3', 'user3@mail.com', '$2y$10$LHrgwXD8Rei.e32ZUjvDaevbmM6MJhqJkN7.0TW2TmR0by3EGQDxu'),
	('user4', 'user4@mail.com', '$2y$10$LHrgwXD8Rei.e32ZUjvDaevbmM6MJhqJkN7.0TW2TmR0by3EGQDxu'),
	('user5', 'user5@mail.com', '$2y$10$LHrgwXD8Rei.e32ZUjvDaevbmM6MJhqJkN7.0TW2TmR0by3EGQDxu'),
	('admin', 'admin@mail.com', '$2y$10$LHrgwXD8Rei.e32ZUjvDaevbmM6MJhqJkN7.0TW2TmR0by3EGQDxu')
;

INSERT INTO Products (user_id, name, description) VALUES
	(1, 'baguette', '24-inch French baguette'),
	(1, 'sourdough', '1-pound sourdough loaf'),
	(1, 'croissant', 'crispy, buttery croissant'),
	(1, 'chocolate amandine', 'semi-sweet chocolate amandine topped with sliced almonds'),
	(2, 'choco-peano-proto shake', 'chocolate, peanut butter, banana, milk, protein'),
	(2, 'strawbo-almo-proto shake', 'strawberry, almond butter, banana, milk, protein'),
	(2, 'seedy chewy oaty proto shake', 'chia seeds, dates, oats, milk, banana, protein'),
	(2, 'berry cheesecake protein shake', 'mixed berries, cream cheese, banana, milk, protein'),
	(3, 'I Pee, Eh?', 'Canadian Indian Pale Ale'),
	(3, 'Frotch', 'Red Ale'),
	(3, 'Doomy Solid', 'Stout'),
	(3, 'Nontroversy', 'Blonde Ale'),
	(4, 'Scarf', 'Red, knitted, winter scarf'),
	(4, 'Gloves', 'Brown, crocheted, for autumn'),
	(4, 'Toque', 'Ivory, knitted, winter toque'),
	(4, 'Socks', 'Wool, winter socks'),
	(5, 'Wallet', 'leather wallet'),
	(5, 'Backpack', 'leather backpack'),
	(5, 'Purse', 'leather purse'),
	(5, 'Belt', 'leather belt')
;

INSERT INTO Offers (product_id, product_quantity, offer_id, offer_quantity, user_id) VALUES
	(5, 1, 10, 1, 3),
	(6, 1, 9, 1, 3),
	(13, 1, 3, 20, 1),
	(14, 1, 1, 8, 1),
	(3, 8, 7, 1, 2),
	(2, 5, 6, 2, 2),
	(18, 1, 16, 5, 4),
	(19, 1, 15, 3, 4),
	(17, 1, 9, 8, 3),
	(20, 1, 11, 8, 3)
;


DELIMITER &&

CREATE TRIGGER inc_Products AFTER INSERT ON Offers
FOR EACH ROW
BEGIN
	UPDATE Products p
	SET p.offers = p.offers + 1
	WHERE p.id = NEW.product_id;
END&&

DELIMITER ;

DELIMITER &&

CREATE TRIGGER dec_Products AFTER DELETE ON Offers
FOR EACH ROW
BEGIN
	UPDATE Products p
	SET p.offers = p.offers - 1
	WHERE p.id = product_id;
END&&

DELIMITER ;

