-- Supprimer la base si elle existe
DROP DATABASE IF EXISTS elearning;

-- Création de la base
CREATE DATABASE elearning CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE elearning;

-- Table utilisateurs
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    date_inscription DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Table thèmes
CREATE TABLE themes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(100) NOT NULL,
    description TEXT
);

-- Table modules
CREATE TABLE modules (
    id INT AUTO_INCREMENT PRIMARY KEY,
    theme_id INT NOT NULL,
    titre VARCHAR(100) NOT NULL,
    type_contenu ENUM('texte','video') NOT NULL,
    contenu TEXT,
    FOREIGN KEY (theme_id) REFERENCES themes(id) ON DELETE CASCADE
);

-- Table quiz
CREATE TABLE quiz (
    id INT AUTO_INCREMENT PRIMARY KEY,
    module_id INT NOT NULL,
    question TEXT NOT NULL,
    reponse_correcte TEXT NOT NULL,
    FOREIGN KEY (module_id) REFERENCES modules(id) ON DELETE CASCADE
);

-- Table réponses utilisateurs
CREATE TABLE quiz_reponses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    quiz_id INT NOT NULL,
    user_id INT NOT NULL,
    reponse TEXT NOT NULL,
    date_reponse DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (quiz_id) REFERENCES quiz(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Table progression
CREATE TABLE progression (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    module_id INT NOT NULL,
    status TINYINT(1) DEFAULT 0, -- 0 = non fait, 1 = fait
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (module_id) REFERENCES modules(id) ON DELETE CASCADE
);

-- Table certificats
CREATE TABLE certificats (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    theme_id INT NOT NULL,
    date_obtention DATETIME DEFAULT CURRENT_TIMESTAMP,
    fichier_certificat VARCHAR(255),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (theme_id) REFERENCES themes(id) ON DELETE CASCADE
);
