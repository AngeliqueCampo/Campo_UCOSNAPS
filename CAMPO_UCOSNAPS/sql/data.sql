CREATE TABLE albums (
    album_id INT(11) NOT NULL,
    album_name VARCHAR(255) NOT NULL,
    username VARCHAR(255) NOT NULL,
    date_created DATETIME NOT NULL,
    PRIMARY KEY (album_id)
);

CREATE TABLE photos (
    photo_id INT(11) NOT NULL,
    photo_name TEXT DEFAULT NULL,
    username VARCHAR(255) DEFAULT NULL,
    description VARCHAR(255) DEFAULT NULL,
    date_added TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
    album_id INT(11) DEFAULT NULL,
    PRIMARY KEY (photo_id)
);

CREATE TABLE user_accounts (
    user_id INT(11) NOT NULL,
    username VARCHAR(255) DEFAULT NULL,
    first_name VARCHAR(255) DEFAULT NULL,
    last_name VARCHAR(255) DEFAULT NULL,
    password TEXT DEFAULT NULL,
    date_added TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
    PRIMARY KEY (user_id)
);
