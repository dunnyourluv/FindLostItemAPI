-- Find lost item database mysql script

CREATE DATABASE findLostItem CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE findLostItem;

CREATE TABLE `users` (
  `uuid`  VARCHAR(36) NOT NULL PRIMARY KEY,
  `username`  VARCHAR(16) NOT NULL UNIQUE,
  `password`  VARCHAR(255) NOT NULL,
  `email`   VARCHAR(255) NOT NULL,
  `is_admin` BOOLEAN DEFAULT 0,
  `avatar` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE `posts` (
  `uuid` VARCHAR(36) NOT NULL PRIMARY KEY,
  `user_uuid` VARCHAR(36) NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `content` TEXT NOT NULL,
  `status` VARCHAR(16) NOT NULL DEFAULT 'pending',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_uuid`) REFERENCES `users` (`uuid`) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE `post_images` (
  `uuid` VARCHAR(36) NOT NULL PRIMARY KEY,
  `post_uuid` VARCHAR(36) NOT NULL,
  `image` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`post_uuid`) REFERENCES `posts` (`uuid`) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE `post_location`(
  `uuid` VARCHAR(36) NOT NULL PRIMARY KEY,
  `post_uuid` VARCHAR(36) NOT NULL,
  `address` TEXT NOT NULL,
  `lat` DECIMAL(10, 8) NOT NULL,
  `lng` DECIMAL(11, 8) NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`post_uuid`) REFERENCES `posts` (`uuid`) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE `post_topic` (
  `uuid` VARCHAR(36) NOT NULL PRIMARY KEY,
  `post_uuid` VARCHAR(36) NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`post_uuid`) REFERENCES `posts` (`uuid`) ON DELETE CASCADE ON UPDATE CASCADE
);
