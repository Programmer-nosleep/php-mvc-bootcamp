CREATE TABLE users (
  userId int(11) unsigned NOT NULL AUTO_INCREMENT,
  email VARCHAR(255) NOT NULL,
  password VARCHAR(255) NOT NULL,
  fullname VARCHAR(255) DEFAULT NULL,
  ip VARCHAR(46) NOT NULL DEFAULT '127.0.0.1',
  UNIQUE KEY (email),
  PRIMARY KEY (userId)
) ENGINE = InnoDB CHARSET=utf8mb4;

CREATE TABLE payments (
  paymentId int(11) unsigned NOT NULL AUTO_INCREMENT,
  userId int(11) unsigned NOT NULL,
  paypalEmail VARCHAR(255) DEFAULT NULL,
  stripePublishableKey VARCHAR(55) DEFAULT NULL,
  stripePrivateKey VARCHAR(55) DEFAULT NULL,
  currency CHAR(3) NOT NULL DEFAULT 'USD',
  PRIMARY KEY (paymentId),
  UNIQUE KEY (userId),
  FOREIGN KEY (userId) REFERENCES users(userId)
) ENGINE = InnoDB CHARSET=utf8mb4;

CREATE TABLE items (
  itemId int(11) unsigned NOT NULL AUTO_INCREMENT,
  userId int(11) unsigned NOT NULL,
  idName varchar(50) NOT NULL,
  itemName varchar(100) NOT NULL,
  businessName varchar(100) DEFAULT NULL,
  summary text DEFAULT NULL,
  price decimal(8, 2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (itemId),
  UNIQUE KEY (idName),
  FOREIGN KEY (userId) REFERENCES users(userId)
) ENGINE = InnoDB CHARSET=utf8mb4;

CREATE TABLE transactions (
  transactionId int(11) unsigned NOT NULL AUTO_INCREMENT,
  orderId varchar(50) NOT NULL,
  idName varchar(50) NOT NULL,
  itemName varchar(100) NOT NULL,
  amount int(11) unsigned NOT NULL,
  currency char(3) NOT NULL DEFAULT 'IDR',
  status varchar(30) NOT NULL DEFAULT 'created',
  paymentType varchar(30) DEFAULT NULL,
  gateway varchar(20) NOT NULL DEFAULT 'midtrans',
  rawResponse text DEFAULT NULL,
  createdAt timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updatedAt timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (transactionId),
  UNIQUE KEY (orderId)
) ENGINE = InnoDB CHARSET=utf8mb4;
