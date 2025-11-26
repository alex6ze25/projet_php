<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modules - <?php echo htmlspecialchars($themeInfo['titre'] ?? 'Thème'); ?></title>
    <link rel="stylesheet" href="../CSS/modules.css">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <div class="logo">
            <a href="../Controllers/afficher_home.php">
                <img src="../Images/lg.png" alt="Logo Plateforme">
            </a>
        </div>
        <div class="site-title">
            <h1>Stop Harcèlement</h1>
        </div>
        <div class="auth-buttons">
            <a href="../Controllers/afficher_home.php">Accueil</a>
            <a href="../Views/profil.php">Mon Profil</a>
            <a href="../Controllers/deconnexion.php">Déconnexion</a>
        </div>
    </header>

    <main class="modules-main">
        <div class="modules-container">
            <!-- En-tête du thème -->
            <div class="theme-header">
                <h2><?php echo htmlspecialchars($themeInfo['titre'] ?? 'Cyberharcèlement'); ?></h2>
                <p class="theme-description"><?php echo htmlspecialchars($themeInfo['description'] ?? 'Comprenez les risques du cyberharcèlement et apprenez à réagir face aux situations en ligne.'); ?></p>
                <div class="progress-indicator">
                    <span class="progress-text">Progression: 0%</span>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: 0%"></div>
                    </div>
                </div>
            </div>

            <!-- Liste des modules -->
            <div class="modules-list">
                <?php if (!empty($modules)): ?>
                    <?php foreach ($modules as $index => $module): ?>
                        <div class="module-card" data-module-id="<?php echo $module['id']; ?>">
                            <div class="module-header">
                                <span class="module-number">Module <?php echo $index + 1; ?></span>
                                <span class="module-status pending">⏳ À commencer</span>
                            </div>
                            <h3 class="module-title"><?php echo htmlspecialchars($module['titre']); ?></h3>
                            <p class="module-description">
                                <?php 
                                $description = strip_tags($module['contenu']);
                                echo strlen($description) > 150 ? substr($description, 0, 150) . '...' : $description;
                                ?>
                            </p>
                            <!-- Dans la boucle des modules, modifiez le bouton -->
<div class="module-actions">
    <a href="afficher_modules.php?module_id=<?php echo $module['id']; ?>" class="start-btn">
        Commencer le module
    </a>
</div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="no-modules">
                        <p>📚 Aucun module disponible pour ce thème pour le moment.</p>
                        <a href="../Controllers/afficher_home.php" class="back-btn">Retour à l'accueil</a>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Navigation -->
            <div class="modules-navigation">
                <a href="../Controllers/afficher_home.php" class="nav-btn secondary">← Retour à l'accueil</a>
                <div class="navigation-info">
                    <span><?php echo count($modules); ?> modules disponibles</span>
                </div>
            </div>
        </div>
    </main>

    <script>
        function startModule(moduleId) {
            // Redirection vers la page du module
            window.location.href = `module_detail.php?module_id=${moduleId}`;
        }

        // Animation au chargement de la page
        document.addEventListener('DOMContentLoaded', function() {
            const moduleCards = document.querySelectorAll('.module-card');
            moduleCards.forEach((card, index) => {
                card.style.animationDelay = `${index * 0.1}s`;
                card.classList.add('fade-in');
            });
        });
    </script>
</body>
</html>