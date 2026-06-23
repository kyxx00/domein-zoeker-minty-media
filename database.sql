CREATE DATABASE IF NOT EXISTS domein_zoeker;
USE domein_zoeker;

-- Tabel voor bestellingen
CREATE TABLE IF NOT EXISTS bestellingen (
    id INT AUTO_INCREMENT PRIMARY KEY,
    naam VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    subtotaal DECIMAL(10,2) NOT NULL,
    btw DECIMAL(10,2) NOT NULL,
    totaal DECIMAL(10,2) NOT NULL,
    aangemaakt_op DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Tabel voor de domeinen die bij een bestelling horen
CREATE TABLE IF NOT EXISTS bestelling_domeinen (
    id INT AUTO_INCREMENT PRIMARY KEY,
    bestelling_id INT NOT NULL,
    domein VARCHAR(255) NOT NULL,
    prijs DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (bestelling_id) REFERENCES bestellingen(id)
);