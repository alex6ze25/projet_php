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

-- Table pour suivre la progression utilisateur (si elle n'existe pas déjà)
CREATE TABLE IF NOT EXISTS user_progression (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    module_id INT NOT NULL,
    is_completed BOOLEAN DEFAULT FALSE,
    score INT DEFAULT 0,
    completed_at DATETIME NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (module_id) REFERENCES modules(id) ON DELETE CASCADE,
    UNIQUE KEY unique_user_module (user_id, module_id)
);

-- Insérer le thème Cyberharcèlement
INSERT INTO themes (titre, description) VALUES (
    'Cyberharcèlement',
    'Comprenez les risques du cyberharcèlement et apprenez à réagir face aux situations en ligne.'
);

-- Récupérer l'ID du thème inséré (généralement 1 si c'est le premier)
SET @theme_id = LAST_INSERT_ID();

-- Si LAST_INSERT_ID() ne fonctionne pas, utilisez l'ID directement :
-- SET @theme_id = 1;

-- Insérer les modules pour le cyberharcèlement
INSERT INTO modules (theme_id, titre, type_contenu, contenu) VALUES
(@theme_id, 'Introduction au Cyberharcèlement', 'texte', 'Le cyberharcèlement est une forme de harcèlement qui se produit en ligne, via les smartphones, Internet et les réseaux sociaux. Il peut prendre diverses formes : messages menaçants, propagation de rumeurs, partage de photos ou vidéos humiliantes sans consentement.'),
(@theme_id, 'Les différentes formes de cyberharcèlement', 'texte', 'Le cyberharcèlement peut se manifester de plusieurs manières :
- Harcèlement sur les réseaux sociaux
- Usurpation d''identité numérique
- Diffusion de contenus privés sans consentement
- Création de faux profils
- Exclusion en ligne d''un groupe
- Envoi répété de messages insultants'),
(@theme_id, 'Conséquences et impacts', 'texte', 'Le cyberharcèlement peut avoir des conséquences graves :
- Détresse psychologique (anxiété, dépression)
- Baisse des résultats scolaires
- Isolement social
- Troubles du sommeil
- Perte d''estime de soi
- Dans les cas extrêmes, pensées suicidaires'),
(@theme_id, 'Comment agir et se protéger', 'texte', 'Face au cyberharcèlement, voici les bonnes pratiques :
- Ne pas répondre aux provocations
- Bloquer immédiatement le harceleur
- Sauvegarder les preuves (captures d''écran)
- Signaler le contenu sur la plateforme
- En parler à un adulte de confiance
- Contacter des associations spécialisées');

-- Insérer des quiz pour chaque module
INSERT INTO quiz (module_id, question, reponse_correcte) VALUES
(1, 'Qu''est-ce que le cyberharcèlement ?', 'Une forme de harcèlement qui se produit en ligne via les technologies numériques'),
(2, 'Citez deux formes de cyberharcèlement', 'Harcèlement sur réseaux sociaux et usurpation d''identité numérique'),
(3, 'Quelles sont les conséquences du cyberharcèlement ?', 'Détresse psychologique, baisse des résultats scolaires et isolement social'),
(4, 'Que faire en cas de cyberharcèlement ?', 'Bloquer le harceleur, sauvegarder les preuves et en parler à un adulte de confiance');