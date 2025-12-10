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
    type_question ENUM('choix_multiple','vrai_faux','texte') DEFAULT 'choix_multiple',
    propositions JSON, -- Stocke les propositions A, B, C, D
    reponse_correcte VARCHAR(10) NOT NULL, -- Stocke la lettre de la bonne réponse (A, B, C, D)
    points INT DEFAULT 1,
    FOREIGN KEY (module_id) REFERENCES modules(id) ON DELETE CASCADE
);

-- Table réponses utilisateurs
CREATE TABLE quiz_reponses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    quiz_id INT NOT NULL,
    user_id INT NOT NULL,
    reponse TEXT NOT NULL,
    is_correct BOOLEAN DEFAULT FALSE,
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

-- Insérer les questions pour le module 1 (Introduction au Cyberharcèlement)
INSERT INTO quiz (module_id, question, type_question, propositions, reponse_correcte, points) VALUES
(1, 'Quelle caractéristique principale distingue le cyberharcèlement d''un simple conflit en ligne ?', 'choix_multiple', '["Le type de plateforme numérique utilisée", "L''utilisation d''un langage injurieux", "La nature répétée et intentionnelle des actes", "Le fait que les participants ne se connaissent pas"]', 'C', 1),

(1, 'Pourquoi le cyberharcèlement est-il particulièrement insidieux ?', 'choix_multiple', '["Il peut se poursuivre en continu, de jour comme de nuit", "Il est impossible de le signaler aux administrateurs des plateformes", "Il ne concerne que les plus jeunes et est ignoré par la loi", "Il est toujours anonyme, empêchant d''identifier l''auteur"]', 'A', 1),

(1, 'Parmi les conséquences suivantes, laquelle n''est pas directement citée dans la liste des effets du cyberharcèlement sur une victime ?', 'choix_multiple', '["Une perte de confiance en soi", "Des troubles du sommeil", "Des difficultés financières", "L''isolement social"]', 'C', 1),

(1, 'Quelle est la première action recommandée pour une personne victime de cyberharcèlement ?', 'choix_multiple', '["Répondre publiquement aux harceleurs pour se défendre", "Ignorer les messages en espérant que le harcèlement cesse", "Parler de la situation à un adulte de confiance", "Supprimer tous ses comptes sur les réseaux sociaux"]', 'C', 1),

(1, 'Selon la loi française, quelle est la sanction maximale pour un adulte coupable de cyberharcèlement ?', 'choix_multiple', '["Une suspension de l''accès à internet", "3 ans de prison et 45 000 € d''amende", "1 an de prison et 15 000 € d''amende", "Uniquement une amende de 45 000 €"]', 'B', 1),

(1, 'Lequel de ces scénarios est un exemple de cyberharcèlement ?', 'choix_multiple', '["Un ami publie une photo de vous peu flatteuse sans mauvaise intention", "L''exclusion répétée d''un camarade d''un groupe de discussion en ligne", "Recevoir un e-mail publicitaire non désiré", "Être en désaccord avec quelqu''un sur un forum de discussion"]', 'B', 1),

(1, 'D''après les statistiques citées, quelle affirmation est correcte ?', 'choix_multiple', '["Le cyberharcèlement ne concerne que les élèves au collège", "Moins de 10% des 18-25 ans ont été confrontés au cyberharcèlement", "La majorité des victimes de cyberharcèlement sont des garçons de moins de 15 ans", "Environ un jeune sur cinq en France déclare avoir déjà subi du cyberharcèlement"]', 'D', 1),

(1, 'Pourquoi la frontière entre vie privée et vie publique s''efface-t-elle dans le contexte du cyberharcèlement ?', 'choix_multiple', '["Car un contenu publié en ligne peut devenir viral et être vu par un très large public", "Car la loi oblige les victimes à rendre leur profil public pour porter plainte", "Car les harceleurs s''introduisent toujours dans le domicile de leurs victimes", "Car les victimes sont forcées de partager leurs mots de passe"]', 'A', 1),

(1, 'Quel est l''impact du cyberharcèlement sur l''entourage de la victime (famille, amis) ?', 'choix_multiple', '["Ils ne subissent aucune conséquence émotionnelle", "Ils ne sont jamais au courant de la situation", "Ils peuvent se sentir impuissants et ne pas savoir comment réagir", "Ils sont les seuls à pouvoir résoudre le problème"]', 'C', 1),

(1, 'Quel est le rôle d''un témoin face à une situation de cyberharcèlement ?', 'choix_multiple', '["Il n''a aucun rôle à jouer, seule la victime peut mettre fin à la situation", "Il peut et doit agir, notamment en signalant les contenus abusifs", "Il doit confronter directement l''harceleur sur la place publique", "Il doit rester neutre pour ne pas devenir une cible à son tour"]', 'B', 1);


-- =============================================
-- QUESTIONS POUR LE MODULE 2
-- (Les différentes formes de cyberharcèlement)
-- =============================================

INSERT INTO quiz (module_id, question, type_question, propositions, reponse_correcte, points) VALUES
(2, 'Lequel de ces éléments n''est pas l''un des trois critères principaux qui définissent un acte de cyberharcèlement ?', 'choix_multiple', '["La répétition des attaques.", "La publication d''une photo embarrassante.", "L''intention de nuire à la victime.", "Un rapport de force déséquilibré."]', 'B', 1),

(2, 'Quelle forme de cyberharcèlement consiste à créer un faux profil au nom d''une autre personne dans le but de la ridiculiser ?', 'choix_multiple', '["Le chantage", "La diffusion de rumeurs", "L''usurpation d''identité", "L''exclusion numérique"]', 'C', 1),

(2, 'Parmi les propositions suivantes, quel est le signe le plus probable qu''une personne subit du cyberharcèlement ?', 'choix_multiple', '["Elle parle ouvertement des messages qu''elle reçoit.", "Elle montre de l''anxiété après avoir utilisé son téléphone.", "Elle publie plus de contenu sur ses réseaux sociaux.", "Elle participe à de nouveaux groupes de discussion en ligne."]', 'B', 1),

(2, 'Quelle est la principale différence entre un simple conflit et une situation de cyberharcèlement ?', 'choix_multiple', '["Le cyberharcèlement implique toujours des menaces physiques, contrairement au conflit.", "Le conflit ne concerne que deux personnes, le cyberharcèlement implique toujours un groupe.", "Le conflit a lieu en face à face, le cyberharcèlement uniquement en ligne.", "Le conflit est ponctuel et réciproque, alors que le harcèlement est répété et déséquilibré."]', 'D', 1),

(2, 'Quelle action, même si elle semble anodine, peut encourager et amplifier un acte de cyberharcèlement ?', 'choix_multiple', '["Ignorer la publication.", "Signaler le contenu problématique.", "Bloquer l''auteur du harcèlement.", "Liker ou partager une publication humiliante."]', 'D', 1),

(2, 'D''après les statistiques citées, quelle est la proportion de familles en France qui a été confrontée au cyberharcèlement d''un enfant ?', 'choix_multiple', '["Près de 70 %", "Environ 18 %", "1 adolescent sur 8", "1 famille sur 4"]', 'D', 1);

-- =============================================
-- QUESTIONS POUR LE MODULE 3
-- (Conséquences et impacts)
-- =============================================

INSERT INTO quiz (module_id, question, type_question, propositions, reponse_correcte, points) VALUES
(3, 'Face à des messages de cyberharcèlement, quelle est la première réaction à éviter absolument pour une victime ?', 'choix_multiple', '["Faire des captures d''écran des messages.", "Répondre directement aux provocations.", "Bloquer l''auteur des messages.", "En parler à un adulte de confiance."]', 'B', 1),

(3, 'Quelle action est qualifiée d''essentielle dans le document pour garantir une intervention efficace contre le cyberharcèlement ?', 'choix_multiple', '["Conserver les preuves des messages ou publications.", "Porter plainte auprès de la police.", "Supprimer immédiatement les contenus blessants.", "Appeler le 3018 en premier lieu."]', 'A', 1),

(3, 'En tant que témoin de cyberharcèlement, quelle action est totalement déconseillée ?', 'choix_multiple', '["Envoyer un message de soutien à la victime.", "Partager le contenu humiliant, même pour le dénoncer.", "Signaler la publication aux modérateurs de la plateforme.", "Alerter un professeur ou un CPE."]', 'B', 1),

(3, 'L''une des raisons principales d''agir vite contre le cyberharcèlement est de :', 'choix_multiple', '["Forcer les plateformes à fermer immédiatement.", "Permettre à la victime de préparer sa vengeance.", "Identifier les auteurs pour les humilier publiquement.", "Limiter l''impact psychologique sur la victime."]', 'D', 1),

(3, 'Quel est le service spécifiquement désigné par le numéro 3018 en France ?', 'choix_multiple', '["Le service d''urgence médicale pour les crises d''angoisse.", "Une ligne d''écoute pour tous les types de harcèlement scolaire.", "Un service pour porter plainte directement par téléphone.", "La plateforme de signalement de la police nationale (Pharos)."]', 'B', 1),

(3, 'Quel est le message clé sur l''attitude à adopter face au cyberharcèlement ?', 'choix_multiple', '["Il faut se concentrer sur la punition des harceleurs avant tout.", "La technologie étant le problème, la seule solution est de se déconnecter.", "La solution est de ne pas rester seul, de conserver les preuves et d''en parler.", "Il faut ignorer la situation jusqu''à ce qu''elle disparaisse seule."]', 'C', 1);

CREATE TABLE avis_modules (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    module_id INT NOT NULL,
    note INT NOT NULL, -- Note de 1 à 5
    commentaire TEXT,
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (module_id) REFERENCES modules(id) ON DELETE CASCADE
);

-- =============================================
-- QUESTIONS POUR LE MODULE 4 (CORRIGÉ)
-- (Comment agir et se protéger)
-- =============================================

INSERT INTO quiz (module_id, question, type_question, propositions, reponse_correcte, points, feedback_succes, feedback_erreur) VALUES
(4, 'Selon le module, quelle est l''une des habitudes essentielles recommandées pour protéger sa vie privée en ligne ?', 'choix_multiple', '["Utiliser le même mot de passe partout pour ne pas l''oublier.", "Partager son numéro de téléphone uniquement avec des amis très proches.", "Utiliser des mots de passe sécurisés et garder ses comptes en mode privé.", "Accepter toutes les demandes d''amis pour montrer que l''on est sociable."]', 'C', 1, 
'Exactement ! Sécuriser ses accès et limiter la visibilité de son profil sont les bases de la protection.', 
'Indice : Pensez à la sécurité de vos clés d''accès et à qui peut voir vos informations.'),

(4, 'En France, quelle plateforme est spécifiquement gérée par la Police Nationale pour signaler des contenus illégaux sur Internet ?', 'choix_multiple', '["Le site Internet Sans Crainte", "Le numéro 3018", "Le service Net Ecoute", "La plateforme PHAROS"]', 'D', 1, 
'Bravo ! PHAROS est bien le portail officiel de signalement des contenus illicites.', 
'Indice : C''est un acronyme utilisé par les forces de l''ordre pour le signalement.'),

(4, 'Parmi les actions suivantes, laquelle est un exemple concret d''encouragement aux interactions positives en ligne mentionné dans le texte ?', 'choix_multiple', '["Ne jamais répondre aux commentaires, qu''ils soient positifs ou négatifs.", "Créer des groupes très fermés pour éviter tout contact avec des inconnus.", "Signaler les comportements toxiques et ne pas relayer les rumeurs.", "Participer aux ''drama'' pour comprendre tous les points de vue."]', 'C', 1, 
'Tout à fait. Briser la chaîne de la haine et utiliser les outils de signalement assainit l''espace numérique.', 
'Indice : L''action consiste à stopper la propagation du négatif.'),

(4, 'Quelle question fondamentale le module recommande-t-il de se poser avant de publier du contenu ?', 'choix_multiple', '["« Est-ce que ce que je publie peut blesser quelqu’un ? »", "« Est-ce que mes amis vont trouver cette publication amusante ? »", "« Est-ce que cette publication va devenir populaire ? »", "« Est-ce que je peux supprimer ce contenu plus tard si je regrette ? »"]', 'A', 1, 
'C''est cela. L''empathie est la clé : ne pas faire aux autres ce qu''on ne voudrait pas subir.', 
'Indice : Pensez à l''impact émotionnel que votre publication pourrait avoir sur une autre personne.'),

(4, 'Selon les chiffres clés du document, quelle est la preuve que la solidarité entre jeunes fonctionne face au cyberharcèlement ?', 'choix_multiple', '["Dans 1 cas sur 3, un jeune dit avoir déjà aidé un camarade victime.", "Plus de 70 % des contenus toxiques sont retirés en moins de 24h.", "Le 3018 reçoit plus de 20 000 signalements par an.", "Le cyberharcèlement est une expérience à laquelle la majorité des jeunes sont exposés."]', 'A', 1, 
'Exact ! L''entraide entre pairs est une ressource puissante et réelle.', 
'Indice : Cherchez la statistique qui parle de l''action positive des jeunes eux-mêmes.'),

(4, 'En cas de cyberharcèlement, qui, parmi le personnel d''un établissement scolaire, est mentionné comme une ressource d''aide ?', 'choix_multiple', '["Les autres élèves de la classe pour organiser une défense collective.", "L''infirmière scolaire, les professeurs ou les CPE.", "Personne, il faut gérer la situation seul pour ne pas l''aggraver.", "Uniquement le directeur ou la directrice de l''établissement."]', 'B', 1, 
'Bonne réponse. Ces adultes sont formés pour écouter et agir dans le cadre scolaire.', 
'Indice : Ce sont les adultes référents que vous croisez tous les jours au collège ou au lycée.'),

(4, 'Quelle est la raison principale pour laquelle il faut adopter un comportement responsable en ligne, d''après la conclusion du module ?', 'choix_multiple', '["Pour montrer aux adultes que l''on est capable de bien se comporter.", "Pour améliorer sa propre popularité et son image de marque personnelle.", "Pour éviter des sanctions de la part des modérateurs de plateformes.", "Pour se protéger soi-même, protéger les autres et rendre Internet plus sûr."]', 'D', 1, 
'Parfaitement. C''est une démarche citoyenne qui profite à toute la communauté.', 
'Indice : La bonne réponse englobe votre sécurité et celle des autres.');

CREATE TABLE IF NOT EXISTS certificats (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    theme_id INT NOT NULL,
    date_obtention DATETIME DEFAULT CURRENT_TIMESTAMP,
    code_unique VARCHAR(50), -- Pour vérifier l'authenticité plus tard
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (theme_id) REFERENCES themes(id) ON DELETE CASCADE
);