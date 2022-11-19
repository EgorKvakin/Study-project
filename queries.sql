INSERT INTO Users(name, email, password)
VALUES ("Pavel", "mr.pavel@gmail.com", "123");



INSERT INTO Projects(name, authorId)
VALUES ("Входящие", 1),
("Работа", 1),
("Домашние дела", 1);


INSERT INTO Tasks(name, date_create, task_completed, term, ProjectId)
VALUES ('Собеседование в IT компании', NOW(), false, '01.12.2019', (SELECT id from Projects WHERE name='Работа')),
('Купить корм для кота', NOW(), false, null, (SELECT id from Projects WHERE name='Домашние дела')),
('Встреча с другом', NOW(), false, '22.12.2019', (SELECT id from Projects WHERE name='Входящие')),
('Встреча с котом', NOW(), false, '22.12.2012', (SELECT id from Projects WHERE name='Входящие')),
('Встреча с другом', NOW(), false, '22.11.2022', (SELECT id from Projects WHERE name='Входящие')),
('Встреча с коллегой', NOW(), false, '22.10.2022', (SELECT id from Projects WHERE name='Работа'));

-- ("Домашние дела", (SELECT id from Users WHERE name='Alexey')),
-- ("Авто", (SELECT id from Users WHERE name='Alexey')),
-- ("Учеба", (SELECT id from Users WHERE name="Igor\'"));

-- INSERT INTO Users
-- SET name="Igor\'", date_reg = NOW(), password = "root";

-- INSERT INTO Users
-- SET name="Alexey", date_reg = NOW(), password = "root";

-- ('Выполнить тестовое задание', NOW(), false, '21.09.2022', (SELECT id from Projects WHERE name='Работа'), (SELECT id from Users WHERE name='Alexey')),
-- ('Встреча с другом', NOW(), false, '23.12.2019', (SELECT id from Projects WHERE name='Входящие'), (SELECT id from Users WHERE name='Alexey')),
-- ('Сделать задание первого раздела', NOW(), true, '22.09.2021', (SELECT id from Projects WHERE name='Учеба'), (SELECT id from Users WHERE name="Igor\'")),
-- ('Заказать пиццу', NOW(), false, null, (SELECT id from Projects WHERE authorId=(SELECT id from Users WHERE name="Igor\'")), (SELECT id from Users WHERE name="Igor\'"));



SELECT name from Projects where authorId=(SELECT id from Users where name='Pavel');
SELECT name from Tasks where projectId=(SELECT id from Projects where name='Работа');
UPDATE Tasks SET task_completed=true WHERE id=(SELECT id WHERE name='Заказать пиццу');
UPDATE Tasks SET name="сделать 355 заданий по экономике" WHERE id=2;
