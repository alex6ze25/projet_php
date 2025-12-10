<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-learning - Accueil</title>
    <link rel="stylesheet" href="../CSS/home.css?v=<?php echo time(); ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <div class="logo">
            <a href="../Controllers/afficher_home.php">
                <img src="../Images/lg.png" alt="Logo E-learning">
            </a>
        </div>

       
        <div class="auth-buttons">
            <?php if (isset($_SESSION['user_id'])): ?>
                <!-- Si l'utilisateur est connecté -->
                <a href="../Controllers/ProfilController.php">Mon Profil</a>
                <a href="../Controllers/deconnexion.php">Déconnexion</a>
            <?php else: ?>
                <!-- Si l'utilisateur n'est pas connecté -->
                <a href="../Controllers/afficher_inscription.php">S'inscrire</a>
                <a href="../Controllers/afficher_connexion.php">Se connecter</a>
            <?php endif; ?>
        </div>
    </header>

    <!-- Message de déconnexion -->
    <?php if (isset($_GET['message']) && $_GET['message'] === 'deconnecte'): ?>
        <div class="message-deconnexion">
            <p>✅ Vous avez été déconnecté avec succès.</p>
        </div>
    <?php endif; ?>

    <main>
        <h2>Choisissez un thème pour commencer :</h2>

        <div class="themes">
            <button class="theme-card" onclick="window.location.href='theme.php?theme=harcelement_scolaire'">
                <img src="../Images/HSC.jpg" alt="Harcèlement scolaire">
                <h3>Harcèlement scolaire</h3>
                <p>Découvrez les modules et quiz pour mieux comprendre et prévenir le harcèlement scolaire.</p>
            </button>

           <button class="theme-card" onclick="window.location.href='../Controllers/afficher_modules.php?theme=cyberharcelement'">
               <img src="../Images/CH.jpg" alt="Cyberharcèlement">
               <h3>Cyberharcèlement</h3>
               <p>Comprenez les risques du cyberharcèlement et apprenez à réagir face aux situations en ligne.</p>
           </button>
        </div>
    </main>
    <!-- Pied de page -->
    <footer class="site-footer">
        <div class="footer-content">
            <div class="footer-section">
                <h4><i class="fas fa-graduation-cap"></i> E-learning</h4>
                <p>Plateforme éducative de prévention contre le harcèlement scolaire et le cyberharcèlement.</p>
            </div>
            
            <div class="footer-section">
                <h4><i class="fas fa-link"></i> Liens rapides</h4>
                <ul>
                    <li><a href="../Controllers/afficher_home.php"><i class="fas fa-home"></i> Accueil</a></li>
                    <li><a href="#"><i class="fas fa-info-circle"></i> À propos</a></li>
                    <li><a href="#"><i class="fas fa-envelope"></i> Contact</a></li>
                </ul>
            </div>
            
            <div class="footer-section">
                <h4><i class="fas fa-shield-alt"></i> Ressources</h4>
                <ul>
                    <li><a href="#"><i class="fas fa-book"></i> Guide de prévention</a></li>
                    <li><a href="#"><i class="fas fa-phone-alt"></i> Numéros d'urgence</a></li>
                    <li><a href="#"><i class="fas fa-download"></i> Documents utiles</a></li>
                </ul>
            </div>
            
            <div class="footer-section">
                <h4><i class="fas fa-legal"></i> Légal</h4>
                <ul>
                    <li><a href="#"><i class="fas fa-lock"></i> Confidentialité</a></li>
                    <li><a href="#"><i class="fas fa-file-contract"></i> Mentions légales</a></li>
                    <li><a href="#"><i class="fas fa-cookie"></i> Cookies</a></li>
                </ul>
            </div>
        </div>
        
        <div class="footer-bottom">
            <p>&copy; 2024 E-learning Platform. Tous droits réservés.</p>
        </div>
    </footer>
</body>
</html>