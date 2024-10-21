
-- Création de la base de données BottleOnSea
CREATE DATABASE IF NOT EXISTS bottleonsea;

-- Utilisation de la base de données
USE bottleonsea;

-- Création de la table `annonces` pour stocker les annonces et les clés PGP
CREATE TABLE IF NOT EXISTS annonces (
    id INT AUTO_INCREMENT PRIMARY KEY,
    announcement TEXT NOT NULL,
    pgp_key TEXT NOT NULL,
    signature TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
