CREATE DATABASE IF NOT EXISTS apilaravel;
USE apilaravel;

CREATE TABLE users(
    id int(255) auto_increment not null,
    role varchar(20),
    name varchar(255),
    surname varchar(255),
    password varchar(255),
    create_at datetime DEFAULT NULL,
    update_at datetime DEFAULT NULL,
    remember_token varchar(255),
    CONSTRAINT pk_users PRIMARY KEY(id)
)ENGINE=InnoDB;

CREATE TABLE cars(
    id int(255) auto_increment not null,
    user_id int(255) NOT null,
    title varchar(255),
    description text,
    price varchar(30),
    status varchar(30),
    create_at datetime DEFAULT NULL,
    update_at datetime DEFAULT NULL,
    CONSTRAINT pk_cars PRIMARY KEY(id),
    CONSTRAINT fk_cars_users FOREIGN KEY(user_id) REFERENCES users(id)
)ENGINE=InnoDB;

