CREATE DATABASE IF NOT EXISTS engineer_test CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE engineer_test;


CREATE TABLE IF NOT EXISTS company
(
    id         BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    name       VARCHAR(255)    NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_company_name (name)
) ENGINE = InnoDB;



CREATE TABLE IF NOT EXISTS employee
(
    id         BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    company_id BIGINT UNSIGNED NOT NULL,
    name       VARCHAR(255)    NOT NULL,
    email      VARCHAR(255)    NOT NULL,
    salary     INT             NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_employee_email (email),
    KEY idx_employee_company_id (company_id),
    CONSTRAINT fk_employee_company
    FOREIGN KEY (company_id)
    REFERENCES company (id) ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE = InnoDB;