<?php
// Pas de session_start() ici car c'est le contrôleur qui le fait
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Utilisateur - Plateforme contre le harcèlement</title>
    <link rel="stylesheet" href="../CSS/profil.css">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="manifest" href="../manifest.json">
<meta name="theme-color" content="#7B68EE">
<link rel="apple-touch-icon" href="../Images/lg.png">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
</head>
<body>
    <header>
        <div class="logo">
            <a href="../Controllers/afficher_home.php">
                <img src="../Images/lg.png" alt="Logo Plateforme">
            </a>
        </div>
       
        <div class="auth-buttons">
            <a href="../Controllers/afficher_home.php"><i class="fas fa-home"></i> Accueil</a>
            <a href="../Controllers/deconnexion.php"><i class="fas fa-sign-out-alt"></i> Déconnexion</a>
        </div>
    </header>

    <main class="profile-main">
        <div class="profile-container">
            <div class="welcome-section">
                <h2><i class="fas fa-hand-wave"></i> Bonjour, <?php echo htmlspecialchars($_SESSION['user_prenom']); ?> 👋</h2>
                <p>Bienvenue sur votre espace personnel</p>
            </div>

            <div class="profile-content">
                <aside class="profile-sidebar">
                    <nav class="profile-menu">
                        <ul>
                            <li class="menu-item active"><a href="#infos" class="menu-link"><span class="menu-icon"><i class="fas fa-user"></i></span>Informations</a></li>
                            <li class="menu-item"><a href="#avancement" class="menu-link"><span class="menu-icon"><i class="fas fa-chart-line"></i></span>Mon avancement</a></li>
                            <li class="menu-item"><a href="#certificats" class="menu-link"><span class="menu-icon"><i class="fas fa-certificate"></i></span>Mes Certificats</a></li>
                            <li class="menu-item"><a href="#parametres" class="menu-link"><span class="menu-icon"><i class="fas fa-cog"></i></span>Paramètres</a></li>
                            <li class="menu-item"><a href="../Controllers/deconnexion.php" class="menu-link logout"><span class="menu-icon"><i class="fas fa-door-open"></i></span>Déconnexion</a></li>
                        </ul>
                    </nav>
                </aside>

                <section class="profile-details">
                    <div id="infos" class="content-section active">
                        <h3><i class="fas fa-user"></i> Informations personnelles</h3>
                        <div class="info-card">
                            <div class="info-item"><label><i class="fas fa-id-card"></i> Nom complet</label><p><?php echo htmlspecialchars($_SESSION['user_prenom'] . ' ' . $_SESSION['user_nom']); ?></p></div>
                            <div class="info-item"><label><i class="fas fa-envelope"></i> Email</label><p><?php echo htmlspecialchars($_SESSION['user_email']); ?></p></div>
                            <div class="info-item"><label><i class="fas fa-calendar-alt"></i> Date d'inscription</label><p><?php echo date('d/m/Y'); ?></p></div>
                            <div class="info-item"><label><i class="fas fa-user-check"></i> Statut</label><p class="status-active"><i class="fas fa-check-circle"></i> Compte actif</p></div>
                        </div>
                    </div>

                    <div id="avancement" class="content-section">
                        <h3><i class="fas fa-chart-line"></i> Mon avancement</h3>
                        <div class="progress-card">
                            <div class="progress-stats">
                                <div class="stat-item"><span class="stat-number"><?php echo $completedModules; ?></span><span class="stat-label">Modules finis</span></div>
                                <div class="stat-item"><span class="stat-number"><?php echo $globalPercentage; ?>%</span><span class="stat-label">Progression</span></div>
                                <div class="stat-item"><span class="stat-number"><?php echo $certificates; ?></span><span class="stat-label">Certificats</span></div>
                            </div>
                            <div class="global-progress-bar-container" style="margin-top: 20px;">
                                <div style="background: #e0e0e0; border-radius: 10px; height: 10px; width: 100%;">
                                    <div style="background: #4a90e2; border-radius: 10px; height: 10px; width: <?php echo $globalPercentage; ?>%;"></div>
                                </div>
                            </div>
                            <div class="progress-actions">
                                <a href="../Controllers/afficher_modules.php?theme=cyberharcelement" class="progress-btn">Continuer à apprendre</a>
                            </div>
                        </div>
                    </div>

                    <div id="certificats" class="content-section">
                        <h3><i class="fas fa-certificate"></i> Mes Certificats</h3>
                        <?php if (!empty($mesCertificats)): ?>
                            <div class="certificats-list">
                                <?php foreach ($mesCertificats as $cert): ?>
                                    <div class="cert-item" style="display: flex; justify-content: space-between; align-items: center; background: white; padding: 20px; border-radius: 10px; margin-bottom: 15px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); border-left: 5px solid #27ae60;">
                                        <div>
                                            <strong style="display: block; font-size: 1.1em; color: #2c3e50; margin-bottom: 5px;">
                                                <?php echo htmlspecialchars($cert['theme_titre']); ?>
                                            </strong>
                                            <small style="color: #777;">
                                                <i class="fas fa-calendar-check"></i> Obtenu le <?php echo date('d/m/Y', strtotime($cert['date_obtention'])); ?>
                                            </small>
                                        </div>
                                        <a href="../Controllers/CertificatController.php?theme_id=<?php echo $cert['theme_id']; ?>" target="_blank" 
                                           class="nav-btn primary" style="background-color: #4a90e2; color: white; padding: 10px 25px; border-radius: 50px; text-decoration: none; font-weight: bold; transition: transform 0.2s; display: inline-flex; align-items: center; gap: 8px;">
                                            <i class="fas fa-eye"></i> Voir certification
                                        </a>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="info-card" style="text-align: center; padding: 40px;">
                                <i class="fas fa-award" style="font-size: 3em; color: #ddd; margin-bottom: 20px;"></i>
                                <p>Vous n'avez pas encore obtenu de certificat.</p>
                                <a href="../Controllers/afficher_modules.php" class="progress-btn" style="margin-top: 20px; display: inline-block;">Aller aux modules</a>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div id="suggestions" class="content-section">
                        <h3><i class="fas fa-lightbulb"></i> Boîte à idées</h3>
                        <?php if (isset($_GET['message']) && $_GET['message'] === 'suggestion_envoyee'): ?>
                            <div style="background-color: #d4edda; color: #155724; padding: 15px; margin-bottom: 20px; border-radius: 10px;">✅ Merci ! Votre suggestion a bien été envoyée.</div>
                        <?php endif; ?>
                        <div class="info-card">
                            <form action="../Controllers/traiter_suggestion.php" method="POST" class="suggestion-form">
                                <div class="form-group" style="margin-bottom: 15px;">
                                    <label for="sujet">Sujet</label>
                                    <select name="sujet" id="sujet" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px;">
                                        <option value="Amélioration">💡 Proposer une amélioration</option>
                                        <option value="Bug">🐛 Signaler un problème technique</option>
                                        <option value="Contenu">📚 Suggestion de nouveau cours</option>
                                        <option value="Autre">📝 Autre</option>
                                    </select>
                                </div>
                                <div class="form-group" style="margin-bottom: 20px;">
                                    <label for="message">Votre message</label>
                                    <textarea name="message" id="message" rows="4" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px; resize: none;"></textarea>
                                </div>
                                <button type="submit" class="nav-btn primary" style="width: 100%; border: none; cursor: pointer;">Envoyer</button>
                            </form>
                        </div>
                    </div>

                    <div id="parametres" class="content-section">
                        <h3><i class="fas fa-cog"></i> Paramètres</h3>
                        <div class="settings-card"><p>Options de compte...</p></div>
                    </div>
                </section>
            </div>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const menuLinks = document.querySelectorAll('.menu-link');
            const contentSections = document.querySelectorAll('.content-section');
            const menuItems = document.querySelectorAll('.menu-item');

            menuLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    if (this.getAttribute('href').startsWith('#')) {
                        e.preventDefault();
                        const targetId = this.getAttribute('href').substring(1);
                        contentSections.forEach(section => section.classList.remove('active'));
                        menuItems.forEach(item => item.classList.remove('active'));
                        const targetSection = document.getElementById(targetId);
                        if (targetSection) targetSection.classList.add('active');
                        this.parentElement.classList.add('active');
                    }
                });
            });
            if(window.location.hash) {
                const targetId = window.location.hash.substring(1);
                const targetLink = document.querySelector(`.menu-link[href="#${targetId}"]`);
                if (targetLink) targetLink.click();
            }
        });
    </script>
</body>
</html>