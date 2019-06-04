CREATE DATABASE yeticave CHARACTER SET utf8 COLLATE utf8_general_ci;

USE yeticave;

CREATE TABLE category (
	id          INT AUTO_INCREMENT PRIMARY KEY,
	name        VARCHAR(64) NOT NULL,
	symbol_code VARCHAR(64) NOT NULL
);

CREATE TABLE users (
	id       INT AUTO_INCREMENT PRIMARY KEY,
	date_reg TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	email    VARCHAR(128) UNIQUE NOT NULL,
	name     VARCHAR(128) NOT NULL,
	password VARCHAR(64) NOT NULL,
	avatar   VARCHAR(255),
	contacts TEXT NOT NULL
);

CREATE TABLE lot (
	id            INT AUTO_INCREMENT PRIMARY KEY,
	date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	name          VARCHAR(64) NOT NULL,
	description   TEXT NOT NULL,
  lot_category  VARCHAR(64),
	img_url       VARCHAR(255) NOT NULL,
	start_price   INT NOT NULL,
	end_date      TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	step          INT NOT NULL,
  last_bet      INT,
	author        INT,
	winner        INT,
	category      INT,
	FOREIGN KEY (author) REFERENCES users(id),
	FOREIGN KEY (winner) REFERENCES users(id),
	FOREIGN KEY (category) REFERENCES category(id)
);

CREATE TABLE bet (
	id            INT AUTO_INCREMENT PRIMARY KEY,
	date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	price         INT NOT NULL,
	user          INT,
	lot           INT,
	FOREIGN KEY (user) REFERENCES users(id),
	FOREIGN KEY (lot) REFERENCES lot(id)
);

CREATE FULLTEXT INDEX search ON lot(name, description)
