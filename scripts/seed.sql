-- SQL script to seed the database with initial data for the pharmacy dashboard

-- Insert initial pharmacies
INSERT INTO pharmacies (name, address) VALUES 
('Pharmacie Centrale', '123 Rue de la Santé, Paris'),
('Pharmacie de la Gare', '456 Avenue des Champs, Lyon'),
('Pharmacie du Marché', '789 Boulevard de la République, Marseille');

-- Insert initial medications
INSERT INTO medicaments (nom) VALUES 
('Paracétamol'),
('Ibuprofène'),
('Amoxicilline'),
('Aspirine'),
('Loratadine');

-- Insert initial stock for pharmacies
INSERT INTO stock_pharmacie (id_pharmacie, id_medicament, quantite) VALUES 
(1, 1, 50),  -- Pharmacie Centrale, Paracétamol
(1, 2, 30),  -- Pharmacie Centrale, Ibuprofène
(2, 1, 20),  -- Pharmacie de la Gare, Paracétamol
(2, 3, 15),  -- Pharmacie de la Gare, Amoxicilline
(3, 4, 25),  -- Pharmacie du Marché, Aspirine
(3, 5, 10);  -- Pharmacie du Marché, Loratadine