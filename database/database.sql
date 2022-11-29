-- Active: 1668920595276@@127.0.0.1@3306@zomato

----------------------------------------------------------

------------------------- Zomato -------------------------

----------------------------------------------------------

CREATE DATABASE IF NOT EXISTS zomato;

----------------------------------------------------------

-- Users Table

----------------------------------------------------------

DROP TABLE users;

CREATE TABLE
    users (
        user_id VARCHAR(36) NOT NULL DEFAULT (UUID()),
        email VARCHAR(255) NOT NULL,
        password VARCHAR(255) NOT NULL,
        role TINYINT NOT NULL DEFAULT 0,
        created_at TIMESTAMP NOT NULL DEFAULT NOW(),
        updated_at TIMESTAMP NOT NULL DEFAULT NOW() ON UPDATE NOW(),
        CONSTRAINT pk_users PRIMARY KEY (user_id)
    );