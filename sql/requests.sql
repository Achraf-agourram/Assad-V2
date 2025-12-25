CREATE DATABASE zoo_assad;

CREATE TABLE utilisateurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    role ENUM('visiteur', 'guide', 'admin') NOT NULL,
    motpasse_hash VARCHAR(255) NOT NULL,
    pays VARCHAR(100),
    statut_compte BOOLEAN DEFAULT TRUE,
    role_approuve BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE habitats (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    typeclimat VARCHAR(100),
    description TEXT,
    zonezoo VARCHAR(100)
);

CREATE TABLE animaux (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    espece VARCHAR(100),
    alimentation VARCHAR(50),
    image VARCHAR(255),
    paysorigine VARCHAR(100),
    descriptioncourte TEXT,
    nb_consultations INT DEFAULT 0,
    id_habitat INT NOT NULL,

    FOREIGN KEY (id_habitat)
    REFERENCES habitats(id)
    ON DELETE RESTRICT
    ON UPDATE CASCADE
);

CREATE TABLE visitesguidees (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(150) NOT NULL,
    description TEXT,
    dateheure DATETIME NOT NULL,
    langue VARCHAR(50) NOT NULL,
    capacite_max INT NOT NULL,
    duree INT NOT NULL,
    prix DECIMAL(8,2) DEFAULT 0.00,
    statut ENUM('active', 'annulee') DEFAULT 'active',
    id_guide INT NOT NULL,

    FOREIGN KEY (id_guide)
    REFERENCES utilisateurs(id)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);

CREATE TABLE etapesvisite (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titreetape VARCHAR(150) NOT NULL,
    descriptionetape TEXT,
    ordreetape INT NOT NULL,
    id_visite INT NOT NULL,

    FOREIGN KEY (id_visite)
    REFERENCES visitesguidees(id)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);

CREATE TABLE reservations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nbpersonnes INT NOT NULL,
    datereservation DATETIME DEFAULT CURRENT_TIMESTAMP,
    id_visite INT NOT NULL,
    id_utilisateur INT NOT NULL,

    FOREIGN KEY (id_visite)
    REFERENCES visitesguidees(id)
    ON DELETE CASCADE
    ON UPDATE CASCADE,

    FOREIGN KEY (id_utilisateur)
    REFERENCES utilisateurs(id)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);

CREATE TABLE commentaires (
    id INT AUTO_INCREMENT PRIMARY KEY,
    note INT CHECK (note BETWEEN 1 AND 5),
    texte TEXT,
    date_commentaire DATETIME DEFAULT CURRENT_TIMESTAMP,
    id_visite INT NOT NULL,
    id_utilisateur INT NOT NULL,


    FOREIGN KEY (id_visite)
    REFERENCES visitesguidees(id)
    ON DELETE CASCADE
    ON UPDATE CASCADE,

    FOREIGN KEY (id_utilisateur)
    REFERENCES utilisateurs(id)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);

INSERT INTO utilisateurs (nom, email, role, motpasse_hash, statut_compte, role_approuve)
VALUES (
    'admin',
    'admin@gmail.ma',
    'admin',
    '$2y$10$XXXXXXXXXXXXXXXXXXXXXXXXXXXXXX',
    TRUE,
    TRUE
);