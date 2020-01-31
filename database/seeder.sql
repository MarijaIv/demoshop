USE demoshop;

DELETE
FROM statistics;
DELETE
FROM admin;
DELETE
FROM product;
DELETE
FROM category;

INSERT INTO statistics (home_view_count)
VALUES (5);

INSERT INTO admin (username, password)
VALUES ('admin', 'sifra123'),
       ('petar', 'sifra123'),
       ('marija', 'sifra123');

INSERT INTO product(category_id, sku, title, brand, price, short_description, description, image, enabled, featured,
                    view_count)
VALUES (2, 'ASD-AG-AF', 'Asus Game Master', 'Asus', 179499, 'Intel I7 16Gb SSD 128',
        'Useful in all situations. No limits. Fast 100%.', LOAD_FILE('/home/marija/images/asus game master.jpg'), 1, 1,
        4),
       (3, 'ASD-AG-AA', 'Soho 1 ThinkPad', 'Lenovo', 59999, 'Intel I3 8Gb 1Tb', 'Useful for office.',
        LOAD_FILE('/home/marija/images/soho 2 thinkpad.jpg'), 1, 1, 10),
       (3, 'ASD-AG-AD', 'Soho 3 ThinkPad', 'Lenovo', 69999, 'Intel I3 8Gb 1Tb', 'Useful for office.',
        LOAD_FILE('/home/marija/images/soho 2 thinkpad.jpg'), 1, 0, 8),
       (3, 'ASD-AG-AS', 'Soho 2 ThinkPad', 'Lenovo', 39999, 'Intel I3 8Gb 1Tb', 'Useful for office.',
        LOAD_FILE('/home/marija/images/soho 2 thinkpad.jpg'), DEFAULT, DEFAULT, DEFAULT),
       (2, 'ASD-AG-AG', 'Soho 4 ThinkPad', 'Lenovo', 29999, 'Intel I3 8Gb 1Tb',
        'Useful in all situations. No limits. Fast 100%.', LOAD_FILE('/home/marija/images/soho 2 thinkpad.jpg'), 0, 0,
        2);

INSERT INTO category(parent_id, code, title, description)
VALUES (DEFAULT, 'LAP1', 'Laptops', 'Laptop category'),
       (1, 'GAM1', 'Gaming', 'Gaming laptops'),
       (1, 'OFF1', 'Office', 'Office laptops'),
       (1, 'MUL1', 'Miltimedia', 'Multimedia laptops'),
       (DEFAULT, 'PRO1', 'Processors', 'Processor category');