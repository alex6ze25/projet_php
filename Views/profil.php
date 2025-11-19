<?php
session_start();
// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: ../Controllers/afficher_inscription.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Utilisateur - Plateforme contre le harcèlement</title>
    <link rel="stylesheet" href="../CSS/profil.css">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <div class="logo">
            <a href="../Controllers/afficher_home.php">
                <img src="../Images/lg.png" alt="Logo Plateforme">
            </a>
        </div>
       
        <div class="auth-buttons">
            <a href="../Controllers/afficher_home.php">Accueil</a>
            <a href="../Controllers/deconnexion.php">Déconnexion</a>
        </div>
    </header>

    <main class="profile-main">
        <div class="profile-container">
            <!-- Section Bienvenue -->
            <div class="welcome-section">
                <h2>Bonjour, <?php echo htmlspecialchars($_SESSION['user_prenom']); ?> ! 👋</h2>
                <p>Bienvenue sur votre espace personnel</p>
            </div>

            <div class="profile-content">
                <!-- Menu latéral -->
                <aside class="profile-sidebar">
                    <nav class="profile-menu">
                        <ul>
                            <li class="menu-item active">
                                <a href="#infos" class="menu-link">
                                    <span class="menu-icon">👤</span>
                                    Informations personnelles
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="#parametres" class="menu-link">
                                    <span class="menu-icon">⚙️</span>
                                    Paramètres du compte
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="#avancement" class="menu-link">
                                    <span class="menu-icon">📊</span>
                                    Mon avancement
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="../Controllers/deconnexion.php" class="menu-link logout">
                                    <span class="menu-icon">🚪</span>
                                    Déconnexion
                                </a>
                            </li>
                        </ul>
                    </nav>
                </aside>

                <!-- Contenu principal -->
                <section class="profile-details">
                    <!-- Section Informations personnelles (active par défaut) -->
                    <div id="infos" class="content-section active">
                        <h3>Informations personnelles</h3>
                        <div class="info-card">
                            <div class="info-item">
                                <label>Nom complet</label>
                                <p><?php echo htmlspecialchars($_SESSION['user_prenom'] . ' ' . $_SESSION['user_nom']); ?></p>
                            </div>
                            <div class="info-item">
                                <label>Email</label>
                                <p><?php echo htmlspecialchars($_SESSION['user_email']); ?></p>
                            </div>
                            <div class="info-item">
                                <label>Date d'inscription</label>
                                <p><?php echo date('d/m/Y'); ?></p>
                            </div>
                            <div class="info-item">
                                <label>Statut</label>
                                <p class="status-active">✅ Compte actif</p>
                            </div>
                        </div>
                    </div>

                    <!-- Section Paramètres -->
                    <div id="parametres" class="content-section">
                        <h3>Paramètres du compte</h3>
                        <div class="settings-card">
                            <p>Cette section vous permet de modifier vos paramètres.</p>
                            <div class="settings-options">
                                <button class="settings-btn">Modifier l'email</button>
                                <button class="settings-btn">Changer le mot de passe</button>
                                <button class="settings-btn">Notifications</button>
                            </div>
                        </div>
                    </div>

                    <!-- Section Avancement -->
                    <div id="avancement" class="content-section">
                        <h3>Mon avancement</h3>
                        <div class="progress-card">
                            <p>Suivez votre progression dans les modules.</p>
                            <div class="progress-stats">
                                <div class="stat-item">
                                    <span class="stat-number">0</span>
                                    <span class="stat-label">Modules complétés</span>
                                </div>
                                <div class="stat-item">
                                    <span class="stat-number">0%</span>
                                    <span class="stat-label">Progression globale</span>
                                </div>
                                <div class="stat-item">
                                    <span class="stat-number">0</span>
                                    <span class="stat-label">Certificats obtenus</span>
                                </div>
                            </div>
                            <div class="progress-actions">
                                <a href="../Controllers/afficher_home.php" class="progress-btn">
                                    Continuer à apprendre ›
                                </a>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </main>

    <script>
        // Navigation entre les sections
        document.addEventListener('DOMContentLoaded', function() {
            const menuLinks = document.querySelectorAll('.menu-link');
            const contentSections = document.querySelectorAll('.content-section');
            const menuItems = document.querySelectorAll('.menu-item');

            menuLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    if (this.getAttribute('href').startsWith('#')) {
                        e.preventDefault();
                        
                        const targetId = this.getAttribute('href').substring(1);
                        
                        // Masquer toutes les sections
                        contentSections.forEach(section => {
                            section.classList.remove('active');
                        });
                        
                        // Afficher la section cible
                        document.getElementById(targetId).classList.add('active');
                        
                        // Mettre à jour le menu actif
                        menuItems.forEach(item => {
                            item.classList.remove('active');
                        });
                        this.parentElement.classList.add('active');
                    }
                });
            });
        });
    </script>
</body>
</html>