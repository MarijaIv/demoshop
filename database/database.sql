CREATE SCHEMA demoshop;

USE demoshop;

CREATE TABLE statistics
(
    id              INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    home_view_count INT          NOT NULL DEFAULT 0
);

CREATE TABLE admin
(
    id       INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(30)  NOT NULL,
    password VARCHAR(32)  NOT NULL
);


CREATE TABLE category
(
    id          INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    parent_id   INT UNSIGNED DEFAULT NULL,
    code        VARCHAR(30)  NOT NULL UNIQUE KEY,
    title       VARCHAR(30)  NOT NULL,
    description VARCHAR(255) NOT NULL,
    FOREIGN KEY (parent_id) REFERENCES category (id)
        ON DELETE RESTRICT ON UPDATE CASCADE
);

CREATE TABLE product
(
    id                INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    category_id       INT UNSIGNED NOT NULL,
    sku               VARCHAR(30)  NOT NULL UNIQUE KEY,
    title             VARCHAR(30)  NOT NULL,
    brand             VARCHAR(30)  NOT NULL,
    price             FLOAT        NOT NULL,
    short_description VARCHAR(255) NOT NULL,
    description       VARCHAR(255) NOT NULL,
    image             BLOB,
    enabled           BOOLEAN      NOT NULL DEFAULT 0,
    featured          BOOLEAN      NOT NULL DEFAULT 0,
    view_count        INT UNSIGNED NOT NULL DEFAULT 0,
    FOREIGN KEY (category_id) REFERENCES category (id)
        ON DELETE RESTRICT ON UPDATE CASCADE
);

