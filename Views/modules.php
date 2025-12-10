<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modules - <?php echo htmlspecialchars($themeInfo['titre'] ?? 'Thème'); ?></title>
    <link rel="stylesheet" href="../CSS/modules.css">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
            <a href="../Controllers/ProfilController.php">Mon Profil</a>
            <a href="../Controllers/deconnexion.php">Déconnexion</a>
        </div>
    </header>

    <main class="modules-main">
        <div class="modules-container">
            <div class="theme-header">
                <h2><?php echo htmlspecialchars($themeInfo['titre'] ?? 'Cyberharcèlement'); ?></h2>
                <p class="theme-description"><?php echo htmlspecialchars($themeInfo['description'] ?? 'Description du thème'); ?></p>
                <div class="progress-indicator">
                    <span class="progress-text">Progression : <?php echo $progressionPercent; ?>%</span>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: <?php echo $progressionPercent; ?>%"></div>
                    </div>
                </div>
                <?php if ($progressionPercent >= 100): ?>
                <div class="certificat-section" style="text-align: center; margin-top: 35px; animation: popIn 0.6s ease;">
                    <div style="background: linear-gradient(135deg, #d4fc79 0%, #96e6a1 100%); padding: 30px; border-radius: 20px; display: inline-block; box-shadow: 0 10px 20px rgba(150, 230, 161, 0.3); max-width: 600px;">
                        
                        <div style="font-size: 3em; margin-bottom: 10px;">🎓</div>
                        
                        <h3 style="color: #2d3436; margin-bottom: 10px; font-size: 1.8em;">Félicitations !</h3>
                        
                        <p style="margin-bottom: 20px; color: #444; font-size: 1.1em; line-height: 1.5;">
                            Vous avez validé l'intégralité du parcours <strong><?php echo htmlspecialchars($themeInfo['titre']); ?></strong>.<br>
                            Votre certificat de réussite a été généré et vous attend dans votre espace personnel.
                        </p>
                        
                        <a href="../Controllers/ProfilController.php#certificats-list" 
                           style="background: white; color: #27ae60; padding: 12px 30px; border-radius: 50px; text-decoration: none; font-weight: bold; font-size: 1.1em; display: inline-flex; align-items: center; gap: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); transition: transform 0.2s;">
                           <i class="fas fa-user-circle"></i> Voir mon certificat dans mon profil
                        </a>
                    </div>
                </div>
            <?php endif; ?>
            </div>

            <?php if (isset($_GET['message']) && $_GET['message'] === 'avis_enregistre'): ?>
                <div style="background-color: #d4edda; color: #155724; padding: 15px; margin-bottom: 20px; border-radius: 10px; border: 1px solid #c3e6cb;">
                    ✅ Merci ! Votre avis a bien été enregistré.
                </div>
            <?php endif; ?>

            <div class="modules-list">
                <?php if (!empty($modules)): ?>
                    <?php foreach ($modules as $index => $module): ?>
                        
                        <?php
                            // Logique d'affichage du statut
                            $statusClass = 'pending';
                            $statusText = '⏳ À commencer';
                            $btnText = 'Commencer';
                            $btnClass = 'start-btn';

                            if ($module['status'] === 'completed') {
                                $statusClass = 'completed';
                                $statusText = '✅ Terminé';
                                $btnText = 'Revoir le module';
                                $btnClass = 'nav-btn secondary';
                            } elseif ($module['status'] === 'in_progress') {
                                $statusClass = 'in-progress';
                                $statusText = '▶️ En cours';
                                $btnText = 'Continuer';
                                $btnClass = 'nav-btn primary';
                            }
                        ?>

                        <div class="module-card" data-module-id="<?php echo $module['id']; ?>">
                            <div class="module-header">
                                <span class="module-number">Module <?php echo $index + 1; ?></span>
                                <span class="module-status <?php echo $statusClass; ?>">
                                    <?php echo $statusText; ?>
                                </span>
                            </div>
                            <h3 class="module-title"><?php echo htmlspecialchars($module['titre']); ?></h3>
                            <p class="module-description">
                                <?php 
                                $description = strip_tags($module['contenu']);
                                echo strlen($description) > 150 ? substr($description, 0, 150) . '...' : $description;
                                ?>
                            </p>
                            
                            <div class="module-actions">
                                <a href="afficher_modules.php?module_id=<?php echo $module['id']; ?>" class="<?php echo $btnClass; ?>">
                                    <?php echo $btnText; ?>
                                </a>
                                
                                <?php if ($module['status'] === 'completed'): ?>
                                    <a href="../Controllers/afficher_avis.php?module_id=<?php echo $module['id']; ?>&theme=<?php echo $_GET['theme'] ?? 'cyberharcelement'; ?>" 
                                       class="nav-btn warning" 
                                       style="margin-left: 10px; background-color: #ffc107; color: #333; text-decoration: none; padding: 10px 20px; border-radius: 25px; display: inline-block;">
                                        <i class="fas fa-star"></i> Noter
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="no-modules">
                        <p>📚 Aucun module disponible.</p>
                    </div>
                <?php endif; ?>
            </div>

            <div class="modules-navigation">
                <a href="../Controllers/afficher_home.php" class="nav-btn secondary">← Retour à l'accueil</a>
            </div>
        </div>
    </main>

    <script>
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