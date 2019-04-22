CREATE DATABASE yeticave CHARACTER SET utf8 COLLATE utf8_general_ci;

USE yeticave;

CREATE TABLE category (
	id          INT AUTO_INCREMENT PRIMARY KEY,
	name        CHAR NOT NULL,
	symbol_code CHAR NOT NULL
);

CREATE TABLE users (
	id       INT AUTO_INCREMENT PRIMARY KEY,
	date_reg TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	email    CHAR UNIQUE NOT NULL,
	name     CHAR UNIQUE NOT NULL,
	password CHAR NOT NULL,
	avatar   CHAR,
	contacts CHAR NOT NULL
);

CREATE TABLE lot (
	id            INT AUTO_INCREMENT PRIMARY KEY,
	date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	name          CHAR NOT NULL,
	description   CHAR NOT NULL,
	img_url       CHAR NOT NULL,
	start_price   INT NOT NULL,
	end_date      TIMESTAMP NOT NULL,
	step          INT NOT NULL,
	author        INT,
	winner        INT,
	category      INT,
	FOREIGN KEY (author) REFERENCES users(id),
	FOREIGN KEY (winner) REFERENCES users(id),
	FOREIGN KEY (category) REFERENCES category(id)
);

CREATE TABLE bet (
	id            INT AUTO_INCREMENT PRIMARY KEY,
	date_creation TIMESTAMP NOT NULL,
	price         INT NOT NULL,
	user          INT,
	lot           INT,
	FOREIGN KEY (user) REFERENCES users(id),
	FOREIGN KEY (lot) REFERENCES lot(id)
);
