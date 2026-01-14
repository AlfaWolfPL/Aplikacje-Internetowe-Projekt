INSERT INTO categories (name)
VALUES ('Akcja'),
       ('Komedia'),
       ('Dramat'),
       ('Sci-Fi'),
       ('Dokument');

INSERT INTO platforms (name)
VALUES ('Netflix'),
       ('HBO Max'),
       ('Amazon Prime Video'),
       ('Disney+'),
       ('Apple TV+');

INSERT INTO titles (title, description, kind, year)
VALUES ('Cicha Noc', 'Polski dramat rodzinny', 'movie', 2017),
       ('Incepcja', 'Thriller sci-fi o snach', 'movie', 2010),
       ('The Office', 'Serial komediowy o pracy w biurze', 'series', 2005),
       ('Breaking Bad', 'Nauczyciel chemii zostaje producentem narkotyków', 'series', 2008),
       ('Planeta Ziemia', 'Seria dokumentalna BBC', 'series', 2006),
       ('John Wick', 'Były płatny zabójca wraca do gry', 'movie', 2014),
       ('Forrest Gump', 'Historia niezwykłego człowieka', 'movie', 1994),
       ('Stranger Things', 'Serial sci-fi osadzony w latach 80', 'series', 2016),
       ('Friends', 'Kultowy serial komediowy o grupie przyjaciół', 'series', 1994),
       ('Interstellar', 'Podróż w kosmosie w poszukiwaniu nowego domu', 'movie', 2014);


INSERT INTO title_categories (title_id, category_id)
VALUES (1, 3),
       (2, 1),
       (2, 4),
       (3, 2),
       (4, 3),
       (4, 1),
       (5, 5),
       (6, 1),
       (7, 3),
       (8, 4),
       (9, 2),
       (10, 4),
       (10, 3);


INSERT INTO title_platforms (title_id, platform_id)
VALUES (1, 2),
       (2, 1),
       (3, 3),
       (4, 2),
       (5, 1),
       (6, 3),
       (7, 5),
       (8, 1),
       (9, 4),
       (10, 5);

