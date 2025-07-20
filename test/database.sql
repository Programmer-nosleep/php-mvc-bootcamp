CREATE TABLE users (
  userId int(11) unsigned NOT NULL AUTO_INCREMENT,
  email VARCHAR(255) NOT NULL,
  password VARCHAR(255) NOT NULL,
  fullname VARCHAR(255) DEFAULT NULL,
  ip VARCHAR(46) NOT NULL DEFAULT '127.0.0.1',
  UNIQUE KEY (email),
  PRIMARY KEY (userId),
) ENGINE = InnoDB CHARSET=utf8mb4;

CREATE TABLE payment (
  paymentId int(11) unsigned NOT NULL AUTO_INCREMENT,
  userId int(11) unsigned NOT NULL,
  paypalEmail VARCHAR(255) DEFAULT NULL,
  stripePublishableKey VARCHAR(55) DEFAULT NULL,
  stripePrivateKey VARCHAR(55) DEFAULT NULL,
  currency CHAR(3) NOT NULL 'USD',
  PRIMARY KEY (paymentId),
  UNIQUE KEY (userId),
  FOREIGN KEY (userId) REFERENCES users(userId),
) ENGINE = InnoDB CHARSET=utf8mb4;