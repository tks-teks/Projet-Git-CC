-- SQL script to create necessary tables for the pharmacy dashboard project

-- Table for storing pharmacies
CREATE TABLE IF NOT EXISTS pharmacies (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    address VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Table for storing medications
CREATE TABLE IF NOT EXISTS medicaments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Table for managing stock levels
CREATE TABLE IF NOT EXISTS stock_pharmacie (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_pharmacie INT NOT NULL,
    id_medicament INT NOT NULL,
    quantite INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_pharmacie) REFERENCES pharmacies(id) ON DELETE CASCADE,
    FOREIGN KEY (id_medicament) REFERENCES medicaments(id) ON DELETE CASCADE
);