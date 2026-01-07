INSERT INTO categories (id, name)
VALUES (1, 'Akcja'),
       (2, 'Komedia'),
       (3, 'Dramat'),
       (4, 'Sci-Fi'),
       (5, 'Dokument');

INSERT INTO platforms (id, name)
VALUES (1, 'Netflix'),
       (2, 'HBO Max'),
       (3, 'Amazon Prime Video'),
       (4, 'Disney+'),
       (5, 'Apple TV+');

INSERT INTO titles (id, title, description, kind, year)
VALUES (1, 'Cicha Noc', 'Polski dramat rodzinny', 'movie', 2017),
       (2, 'Incepcja', 'Thriller sci-fi o snach', 'movie', 2010),
       (3, 'The Office', 'Serial komediowy o pracy w biurze', 'series', 2005),
       (4, 'Breaking Bad', 'Nauczyciel chemii zostaje producentem narkotyków', 'series', 2008),
       (5, 'Planeta Ziemia', 'Seria dokumentalna BBC', 'series', 2006),
       (6, 'John Wick', 'Były płatny zabójca wraca do gry', 'movie', 2014),
       (7, 'Forrest Gump', 'Historia niezwykłego człowieka', 'movie', 1994),
       (8, 'Stranger Things', 'Serial sci-fi osadzony w latach 80', 'series', 2016),
       (9, 'Friends', 'Kultowy serial komediowy o grupie przyjaciół', 'series', 1994),
       (10, 'Interstellar', 'Podróż w kosmosie w poszukiwaniu nowego domu', 'movie', 2014);


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

