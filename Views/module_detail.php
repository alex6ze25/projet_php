<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($module['titre']); ?> - Plateforme E-learning</title>
    <link rel="stylesheet" href="../CSS/module_detail.css">
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
            <a href="../Controllers/afficher_modules.php?theme=cyberharcelement">Retour aux modules</a>
            <a href="../Views/profil.php">Mon Profil</a>
            <a href="../Controllers/deconnexion.php">Déconnexion</a>
        </div>
    </header>

    <main class="module-main">
        <div class="module-container">
            <!-- En-tête du module -->
            <div class="module-header">
                <nav class="breadcrumb">
                    <a href="../Controllers/afficher_home.php">Accueil</a> >
                    <a href="../Controllers/afficher_modules.php?theme=cyberharcelement">Cyberharcèlement</a> >
                    <span><?php echo htmlspecialchars($module['titre']); ?></span>
                </nav>
                <h2><?php echo htmlspecialchars($module['titre']); ?></h2>
                <div class="module-progress">
                    <span class="progress-label">Progression du module</span>
                    <div class="progress-steps">
                        <div class="step active" data-step="1">📖 Cours</div>
                        <div class="step" data-step="2">🎥 Vidéo</div>
                        <div class="step" data-step="3">❓ Quiz</div>
                    </div>
                </div>
            </div>

            <!-- DEBUG : Vérification PDF -->
            <?php
            $pdfPath = '../documents/' . $module['id'] . '.pdf';
            $pdfExists = file_exists($pdfPath);
            ?>
            
            <!-- Contenu du module (étape 1) -->
            <div class="module-content active" id="step-1">
                <div class="content-card">
                    <h3>📖 Contenu du cours</h3>
                    
                    <?php if ($pdfExists): ?>
                        <div class="pdf-container">
                            <embed 
                                src="../documents/<?php echo $module['id']; ?>.pdf" 
                                type="application/pdf" 
                                width="100%" 
                                height="600px"
                            >
                            <div class="pdf-fallback">
                                <p>📄 <a href="../documents/<?php echo $module['id']; ?>.pdf" target="_blank" class="download-link">
                                    Télécharger le PDF
                                </a></p>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="pdf-error">
                            <p>❌ Fichier PDF non trouvé : documents/<?php echo $module['id']; ?>.pdf</p>
                            <p>Chemin testé : <?php echo realpath($pdfPath); ?></p>
                            <div class="course-content">
                                <?php echo nl2br(htmlspecialchars($module['contenu'])); ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <div class="navigation-buttons">
                        <a href="../Controllers/afficher_modules.php?theme=cyberharcelement" class="nav-btn secondary">
                            Retour aux modules
                        </a>
                        <button class="nav-btn primary" onclick="showStep(2)">Continuer vers la vidéo →</button>
                    </div>
                </div>
            </div>

            <!-- Vidéo (étape 2) -->
            <div class="module-content" id="step-2">
                <div class="content-card">
                    <h3>🎥 Vidéo éducative</h3>
                    <div class="video-container">
                        <div class="video-placeholder">
                            <p>🎬 Vidéo sur le cyberharcèlement</p>
                            <p><small>Placeholder pour votre vidéo éducative</small></p>
                        </div>
                    </div>
                    <div class="video-info">
                        <p><strong>Conseil :</strong> Regardez attentivement cette vidéo avant de passer au quiz.</p>
                    </div>
                    <div class="navigation-buttons">
                        <button class="nav-btn secondary" onclick="showStep(1)">← Retour au cours</button>
                        <button class="nav-btn primary" onclick="showStep(3)">Passer au quiz →</button>
                    </div>
                </div>
            </div>

            <!-- Quiz (étape 3) - SIMPLIFIÉ SANS ERREUR -->
            <div class="module-content" id="step-3">
                <div class="content-card">
                    <h3>❓ Quiz de validation</h3>
                    <div class="quiz-container">
                        <div class="quiz-placeholder">
                            <p>🎯 Système de quiz à implémenter</p>
                            <p><small>Le quiz sera disponible prochainement</small></p>
                            <div class="navigation-buttons">
                                <button class="nav-btn secondary" onclick="showStep(2)">← Retour à la vidéo</button>
                                <button class="nav-btn success" onclick="completeModule()">✅ Terminer le module</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Indicateur de progression globale -->
            <div class="global-progress">
                <h4>Votre progression dans ce thème</h4>
                <div class="progress-info">
                    <span>Modules complétés: 0/4</span>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: 0%"></div>
                    </div>
                </div>
            </div>
        </div>
        
    </main>

    <script>
        // Navigation entre les étapes - VERSION UNIQUE
        function showStep(stepNumber) {
            console.log('Navigation vers étape:', stepNumber);
            
            // Masquer toutes les étapes
            document.querySelectorAll('.module-content').forEach(step => {
                step.classList.remove('active');
            });
            
            // Afficher l'étape sélectionnée
            const targetStep = document.getElementById('step-' + stepNumber);
            if (targetStep) {
                targetStep.classList.add('active');
            }
            
            // Mettre à jour les indicateurs de progression
            document.querySelectorAll('.progress-steps .step').forEach((step, index) => {
                if (index + 1 <= stepNumber) {
                    step.classList.add('active');
                } else {
                    step.classList.remove('active');
                }
            });
            
            // Scroll vers le haut
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
        
        function completeModule() {
            alert('Module terminé ! Vous pourrez bientôt passer au module suivant.');
            window.location.href = '../Controllers/afficher_modules.php?theme=cyberharcelement';
        }
        
        // Afficher la première étape au chargement
        document.addEventListener('DOMContentLoaded', function() {
            showStep(1);
        });
    </script>
</body>
</html>