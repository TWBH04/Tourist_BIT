
CREATE DATABASE IF NOT EXISTS dayquest CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE dayquest;

DROP TABLE IF EXISTS template_place;
DROP TABLE IF EXISTS template;
DROP TABLE IF EXISTS place;
DROP TABLE IF EXISTS category;
DROP TABLE IF EXISTS admin;

CREATE TABLE admin (
  admin_id       INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  username       VARCHAR(50)  NOT NULL UNIQUE,
  password_hash  VARCHAR(255) NOT NULL,                 
  failed_attempts TINYINT UNSIGNED NOT NULL DEFAULT 0,  
  locked_until   DATETIME NULL                          
) ENGINE=InnoDB;

CREATE TABLE category (
  category_id    INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  category_name  VARCHAR(50) NOT NULL UNIQUE,
  is_outdoor     TINYINT(1) NOT NULL DEFAULT 0          
) ENGINE=InnoDB;

CREATE TABLE place (
  place_id       INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name           VARCHAR(120) NOT NULL,
  category_id    INT UNSIGNED NOT NULL,
  description    TEXT,
  opening_hours  VARCHAR(150),
  visit_duration SMALLINT UNSIGNED NOT NULL DEFAULT 60, 
  latitude       DECIMAL(9,6) NOT NULL,
  longitude      DECIMAL(9,6) NOT NULL,
  entrance_fee   VARCHAR(100),
  distance_from_panadura DECIMAL(5,1) NOT NULL,         
  travel_tips    TEXT,                                  
  accessibility_info TEXT,
  thumbnail      VARCHAR(255),                          
  CONSTRAINT fk_place_category FOREIGN KEY (category_id)
    REFERENCES category(category_id) ON UPDATE CASCADE ON DELETE RESTRICT,
  INDEX idx_place_distance (distance_from_panadura),
  FULLTEXT KEY ft_place_search (name, description)
) ENGINE=InnoDB;

CREATE TABLE template (
  template_id    INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  template_name  VARCHAR(80) NOT NULL UNIQUE
) ENGINE=InnoDB;

CREATE TABLE template_place (
  template_id INT UNSIGNED NOT NULL,
  place_id    INT UNSIGNED NOT NULL,
  order_index TINYINT UNSIGNED NOT NULL,
  PRIMARY KEY (template_id, place_id),
  CONSTRAINT fk_tp_template FOREIGN KEY (template_id) REFERENCES template(template_id) ON DELETE CASCADE,
  CONSTRAINT fk_tp_place    FOREIGN KEY (place_id)    REFERENCES place(place_id)       ON DELETE CASCADE
) ENGINE=InnoDB;
