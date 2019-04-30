INSERT INTO category (name, symbol_code)
VALUES ('Доски и лыжи', 'boards'), ('Крепления', 'attachment'), ('Ботинки', 'boots'), ('Одежда', 'clothing'),
       ('Инструмент', 'tools'), ('Разное', 'other');

INSERT INTO users (name, password, email, contacts, avatar)
VALUES ('username1', 'userpassword1', 'user1@mail.ru', 'No contact', 'userimage1.jpg'),
       ('username2', 'userpassword2', 'user2@yandex.com', 'No contact', 'userimage2.png'),
       ('username3', 'userpassword3', 'user3@gmail.com', 'No contact', 'userimage3.png');

INSERT INTO lot (name, description, category, img_url, start_price, step, end_date, author)
VALUES ('2014 Rossignol District Snowboard', 'Доски и лыжи', 1, 'img/lot-1.jpg', '10999', '100', '2019-05-30', 1),
       ('DC Ply Mens 2016/2017 Snowboard', 'Доски и лыжи', 1, 'img/lot-2.jpg', '159999', '100', '2019-05-30', 2),
       ('Крепления Union Contact Pro 2015 года размер L/XL', 'Крепления', 2, 'img/lot-3.jpg', '8000', '100', '2019-05-30', 3),
       ('Ботинки для сноуборда DC Mutiny Charocal', 'Ботинки', 3, 'img/lot-4.jpg', '10999', '100', '2019-06-30', 2),
       ('Куртка для сноуборда DC Mutiny Charocal', 'Одежда', 4, 'img/lot-5.jpg', '7500', '100', '2019-05-30', 1),
       ('Маска Oakley Canopy', 'Разное', 5, 'img/lot-6.jpg', '350', '100', '2019-05-30', 3);

INSERT INTO bet (price, user, lot)
VALUES (160099, 1, 2), (8100, 1, 3);

/* Получение категорий */
SELECT * FROM category;

/* Получение новых лотов */
SELECT l.name, l.start_price, l.img_url, c.name AS category_name
  FROM lot AS l
  INNER JOIN category AS c ON l.category = c.id
  WHERE l.end_date > NOW()
  ORDER BY l.date_creation DESC
  LIMIT 5;

/* Обновление лота по идентификатору*/
UPDATE lot SET name = 'new_name' WHERE id = 1;

/* Получение лота по ID */
SELECT l.name, c.name AS category_name FROM lot AS l
  INNER JOIN category AS c ON l.category = c.id
  WHERE l.id = 1;

/* Получить самые свежие ставки по ID */
SELECT * FROM bet AS b
  INNER JOIN lot AS l ON b.lot = l.id
  WHERE l.id = 2
  ORDER BY b.date_creation DESC;
