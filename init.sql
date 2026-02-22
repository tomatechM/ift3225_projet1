USE joffre;

DROP TABLE IF EXISTS Offers;
DROP TABLE IF EXISTS Products;
DROP TABLE IF EXISTS Users;

CREATE TABLE Users (
	user_id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
	username CHAR(50) UNIQUE NOT NULL,
	email CHAR(255) UNIQUE NOT NULL,
	hashed_password CHAR(255) NOT NULL,
	admin BOOLEAN DEFAULT FALSE
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

	CONSTRAINT fk_offers_product
	FOREIGN KEY (product_id) REFERENCES Products(id) ON DELETE CASCADE,
	CONSTRAINT fk_offers_offer
	FOREIGN KEY (offer_id) REFERENCES Products(id) ON DELETE CASCADE,
	FOREIGN KEY (user_id) REFERENCES Users(user_id)
);

INSERT INTO Users (username, email, hashed_password) VALUES
	('user1', 'user1@mail.com', '$2y$10$LHrgwXD8Rei.e32ZUjvDaevbmM6MJhqJkN7.0TW2TmR0by3EGQDxu'),
	('user2', 'user2@mail.com', '$2y$10$LHrgwXD8Rei.e32ZUjvDaevbmM6MJhqJkN7.0TW2TmR0by3EGQDxu'),
	('user3', 'user3@mail.com', '$2y$10$LHrgwXD8Rei.e32ZUjvDaevbmM6MJhqJkN7.0TW2TmR0by3EGQDxu'),
	('user4', 'user4@mail.com', '$2y$10$LHrgwXD8Rei.e32ZUjvDaevbmM6MJhqJkN7.0TW2TmR0by3EGQDxu'),
	('user5', 'user5@mail.com', '$2y$10$LHrgwXD8Rei.e32ZUjvDaevbmM6MJhqJkN7.0TW2TmR0by3EGQDxu')
;
INSERT INTO Users (username, email, hashed_password, admin) VALUES
	('admin', 'admin@mail.com', '$2y$10$LHrgwXD8Rei.e32ZUjvDaevbmM6MJhqJkN7.0TW2TmR0by3EGQDxu', TRUE)
;

INSERT INTO Products (user_id, name, description) VALUES
	(1, 'baguette', '24-inch French baguette'),
	(1, 'sourdough', '1-pound sourdough loaf'),
	(1, 'croissant', 'crispy, buttery croissant'),
	(1, 'chocolate amandine', 'semi-sweet chocolate amandine topped with sliced almonds'),
	(1, 'multigrain loaf', 'hearty multigrain bread with flax and sunflower seeds'),
	(1, 'ciabatta', 'rustic Italian ciabatta with airy crumb'),
	(1, 'pain de campagne', 'country-style French round loaf'),
	(1, 'olive focaccia', 'focaccia topped with Kalamata olives and rosemary'),
	(1, 'brioche', 'rich, eggy brioche loaf'),
	(1, 'cinnamon roll', 'spiraled cinnamon roll with cream cheese icing'),
	(1, 'almond croissant', 'croissant filled with almond cream and dusted with sugar'),
	(1, 'pain au chocolat', 'flaky pastry with dark chocolate baton'),
	(1, 'blueberry muffin', 'moist muffin with fresh blueberries'),
	(1, 'lemon tart', 'shortcrust pastry filled with tangy lemon curd'),
	(1, 'apple turnover', 'puff pastry filled with cinnamon apples'),
	(1, 'pecan pie slice', 'slice of classic pecan pie'),
	(1, 'cheese danish', 'pastry with sweet cream cheese filling'),
	(1, 'garlic knot', 'soft knot brushed with garlic butter'),
	(1, 'bagel sesame', 'chewy sesame seed bagel'),
	(1, 'bagel everything', 'everything-spiced New York–style bagel'),
	(2, 'choco-peano-proto shake', 'chocolate, peanut butter, banana, milk, protein'),
	(2, 'strawbo-almo-proto shake', 'strawberry, almond butter, banana, milk, protein'),
	(2, 'seedy chewy oaty proto shake', 'chia seeds, dates, oats, milk, banana, protein'),
	(2, 'berry cheesecake protein shake', 'mixed berries, cream cheese, banana, milk, protein'),
	(2, 'matcha muscle shake', 'matcha green tea, vanilla protein, almond milk'),
	(2, 'espresso bulk shake', 'espresso shot, chocolate protein, oat milk'),
	(2, 'mango lassi protein shake', 'mango, yogurt, honey, vanilla protein'),
	(2, 'tropical recovery shake', 'pineapple, coconut milk, banana, protein'),
	(2, 'mint chip protein shake', 'mint extract, dark chocolate chips, vanilla protein'),
	(2, 'salted caramel gain shake', 'caramel syrup, whey protein, milk'),
	(2, 'oreo mass builder', 'oreo cookies, chocolate protein, milk'),
	(2, 'pumpkin spice bulk shake', 'pumpkin puree, cinnamon, vanilla protein'),
	(2, 'blueberry oat shake', 'blueberries, oats, milk, protein'),
	(2, 'peanut mocha power shake', 'peanut butter, cocoa, espresso, protein'),
	(2, 'banana cream pie shake', 'banana, graham crumbs, vanilla protein'),
	(2, 'cherry chocolate blast', 'cherries, dark cocoa, protein'),
	(2, 'maple pecan protein shake', 'maple syrup, pecans, milk, protein'),
	(2, 'cinnamon toast crunch shake', 'cereal, milk, vanilla protein'),
	(2, 'vanilla almond lean shake', 'vanilla protein, almond milk, ice'),
	(2, 'raspberry white chocolate shake', 'raspberries, white chocolate chips, protein'),
	(3, 'I Pee, Eh?', 'Canadian Indian Pale Ale'),
	(3, 'Frotch', 'Red Ale'),
	(3, 'Doomy Solid', 'Stout'),
	(3, 'Nontroversy', 'Blonde Ale'),
	(3, 'Hopocalypse', 'Double dry-hopped IPA'),
	(3, 'Maple Moon', 'Maple-infused amber ale'),
	(3, 'Beaver Brown', 'Nutty brown ale'),
	(3, 'Frostbite Lager', 'Crisp winter lager'),
	(3, 'Polar Pils', 'Czech-style pilsner'),
	(3, 'Great White Wheat', 'Unfiltered wheat ale'),
	(3, 'Caribou Cream', 'Smooth cream ale'),
	(3, 'Black Current Event', 'Blackcurrant sour ale'),
	(3, 'Timberwolf Porter', 'Robust coffee porter'),
	(3, 'Aurora Saison', 'Farmhouse saison with citrus notes'),
	(3, 'Boreal Baltic', 'Baltic porter with chocolate undertones'),
	(3, 'Smoked Moose', 'Smoked rauchbier'),
	(3, 'Ice Breaker IPA', 'West Coast IPA with pine aroma'),
	(3, 'Prairie Gold', 'Golden ale with floral hops'),
	(3, 'Midnight Maple Stout', 'Stout aged on maple wood'),
	(3, 'Loon Light', 'Light-bodied session ale'),
	(4, 'Scarf', 'Red, knitted, winter scarf'),
	(4, 'Gloves', 'Brown, crocheted, for autumn'),
	(4, 'Toque', 'Ivory, knitted, winter toque'),
	(4, 'Socks', 'Wool, winter socks'),
	(4, 'Chunky blanket', 'Hand-knit chunky wool blanket'),
	(4, 'Cardigan', 'Long-sleeve knitted cardigan'),
	(4, 'Beanie', 'Soft ribbed knit beanie'),
	(4, 'Mittens', 'Fleece-lined knit mittens'),
	(4, 'Infinity scarf', 'Circular scarf in soft acrylic yarn'),
	(4, 'Cable sweater', 'Cable-knit wool sweater'),
	(4, 'Poncho', 'Oversized knitted poncho'),
	(4, 'Headband', 'Knitted ear-warmer headband'),
	(4, 'Fingerless gloves', 'Knitted fingerless gloves'),
	(4, 'Knit vest', 'Sleeveless knit vest'),
	(4, 'Wool shawl', 'Lightweight wool shawl'),
	(4, 'Slippers', 'Hand-knit indoor slippers'),
	(4, 'Baby booties', 'Small knit baby booties'),
	(4, 'Leg warmers', 'Stretch knit leg warmers'),
	(4, 'Throw pillow cover', 'Knitted decorative pillow cover'),
	(4, 'Knit hat with pom', 'Winter hat with faux fur pom-pom'),
	(5, 'Wallet', 'leather wallet'),
	(5, 'Backpack', 'leather backpack'),
	(5, 'Purse', 'leather purse'),
	(5, 'Belt', 'leather belt'),
	(5, 'Messenger bag', 'Crossbody leather messenger bag'),
	(5, 'Card holder', 'Slim leather card holder'),
	(5, 'Passport holder', 'Leather passport holder'),
	(5, 'Laptop sleeve', 'Padded leather laptop sleeve'),
	(5, 'Keychain', 'Hand-stitched leather keychain'),
	(5, 'Watch strap', 'Replacement leather watch strap'),
	(5, 'Duffel bag', 'Large leather travel duffel'),
	(5, 'Notebook cover', 'Leather-bound notebook cover'),
	(5, 'Sunglasses case', 'Hard leather sunglasses case'),
	(5, 'Briefcase', 'Professional leather briefcase'),
	(5, 'Coin pouch', 'Small zippered leather coin pouch'),
	(5, 'Phone case', 'Custom leather phone case'),
	(5, 'Tool roll', 'Roll-up leather tool organizer'),
	(5, 'Camera strap', 'Adjustable leather camera strap'),
	(5, 'Apron', 'Heavy-duty leather workshop apron'),
	(5, 'Luggage tag', 'Personalized leather luggage tag')

;

INSERT INTO Offers (product_id, product_quantity, offer_id, offer_quantity, user_id) VALUES
-- USER 1 offers bakery (1-20) for other categories (21-100)
(81, 1, 1, 6, 1),    -- leather wallet for 6 baguettes
(82, 1, 2, 4, 1),    -- leather backpack for 4 sourdough
(83, 1, 3, 10, 1),   -- purse for 10 croissants
(84, 1, 4, 6, 1),    -- belt for 6 chocolate amandines
(61, 1, 5, 3, 1),    -- scarf for 3 multigrain loaves
(62, 1, 6, 3, 1),    -- gloves for 3 ciabatta
(63, 1, 7, 2, 1),    -- toque for 2 pain de campagne
(64, 1, 8, 3, 1),    -- socks for 3 olive focaccia
(41, 6, 9, 1, 1),    -- 6 beers for 1 brioche
(42, 6, 10, 1, 1),   -- 6 beers for 1 cinnamon roll
(43, 6, 11, 1, 1),   -- 6 beers for 1 almond croissant
(44, 6, 12, 1, 1),   -- 6 beers for 1 pain au chocolat
(21, 2, 13, 2, 1),   -- 2 shakes for 2 blueberry muffins
(22, 2, 14, 1, 1),   -- 2 shakes for 1 lemon tart
(23, 2, 15, 2, 1),   -- 2 shakes for 2 apple turnovers
(24, 2, 16, 2, 1),   -- 2 shakes for 2 pecan pie slices
(25, 2, 17, 2, 1),   -- 2 shakes for 2 cheese danish
(26, 2, 18, 4, 1),   -- 2 shakes for 4 garlic knots
(27, 2, 19, 4, 1),   -- 2 shakes for 4 sesame bagels
(28, 2, 20, 4, 1),   -- 2 shakes for 4 everything bagels

-- USER 2 offers shakes (21-40) for bakery/beer/knit/leather
(1, 4, 21, 1, 2),
(2, 3, 22, 1, 2),
(3, 6, 23, 1, 2),
(4, 4, 24, 1, 2),
(5, 2, 25, 1, 2),
(6, 2, 26, 1, 2),
(7, 2, 27, 1, 2),
(8, 2, 28, 1, 2),
(41, 6, 29, 1, 2),
(42, 6, 30, 1, 2),
(43, 6, 31, 1, 2),
(44, 6, 32, 1, 2),
(61, 1, 33, 1, 2),
(62, 1, 34, 1, 2),
(63, 1, 35, 1, 2),
(64, 1, 36, 1, 2),
(81, 1, 37, 1, 2),
(83, 1, 38, 1, 2),
(85, 1, 39, 1, 2),
(90, 1, 40, 1, 2),

-- USER 3 offers beer (41-60) for bakery/shakes/knit/leather
(1, 6, 41, 1, 3),
(2, 6, 42, 1, 3),
(3, 6, 43, 1, 3),
(4, 6, 44, 1, 3),
(21, 2, 45, 1, 3),
(22, 2, 46, 1, 3),
(23, 2, 47, 1, 3),
(24, 2, 48, 1, 3),
(61, 1, 49, 2, 3),
(62, 1, 50, 2, 3),
(63, 1, 51, 2, 3),
(64, 1, 52, 2, 3),
(81, 1, 53, 2, 3),
(82, 1, 54, 2, 3),
(83, 1, 55, 2, 3),
(84, 1, 56, 2, 3),
(91, 1, 57, 3, 3),
(92, 1, 58, 3, 3),
(99, 1, 59, 3, 3),
(100, 1, 60, 3, 3),

-- USER 4 offers knitwear (61-80) for bakery/shakes/beer/leather
(1, 3, 61, 1, 4),
(2, 2, 62, 1, 4),
(3, 4, 63, 1, 4),
(4, 3, 64, 1, 4),
(21, 1, 65, 1, 4),
(22, 1, 66, 1, 4),
(23, 1, 67, 1, 4),
(24, 1, 68, 1, 4),
(41, 4, 69, 1, 4),
(42, 4, 70, 1, 4),
(43, 4, 71, 1, 4),
(44, 4, 72, 1, 4),
(81, 1, 73, 1, 4),
(82, 1, 74, 1, 4),
(83, 1, 75, 1, 4),
(84, 1, 76, 1, 4),
(85, 1, 77, 1, 4),
(86, 1, 78, 1, 4),
(87, 1, 79, 1, 4),
(90, 1, 80, 1, 4),

-- USER 5 offers leather goods (81-100) for bakery/shakes/beer/knitwear
(1, 6, 81, 1, 5),
(2, 5, 82, 1, 5),
(3, 8, 83, 1, 5),
(4, 6, 84, 1, 5),
(21, 2, 85, 1, 5),
(22, 2, 86, 1, 5),
(23, 2, 87, 1, 5),
(24, 2, 88, 1, 5),
(41, 6, 89, 1, 5),
(42, 6, 90, 1, 5),
(43, 6, 91, 1, 5),
(44, 6, 92, 1, 5),
(61, 1, 93, 1, 5),
(62, 1, 94, 1, 5),
(63, 1, 95, 1, 5),
(64, 1, 96, 1, 5),
(65, 1, 97, 1, 5),
(66, 1, 98, 1, 5),
(69, 1, 99, 1, 5),
(73, 1, 100, 1, 5)
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


