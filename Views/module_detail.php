<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($module['titre']); ?> - Plateforme E-learning</title>
    <link rel="stylesheet" href="../CSS/module_detail.css">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
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
            <a href="../Controllers/afficher_modules.php?theme=cyberharcelement"><i class="fas fa-arrow-left"></i> Retour aux modules</a>
            <a href="../Controllers/ProfilController.php"><i class="fas fa-user"></i> Mon Profil</a>
            <a href="../Controllers/deconnexion.php"><i class="fas fa-sign-out-alt"></i> Déconnexion</a>
        </div>
    </header>

    <main class="module-main">
        <div class="module-container">
            <!-- En-tête du module -->
            <div class="module-header">
                <nav class="breadcrumb">
                    <a href="../Controllers/afficher_home.php"><i class="fas fa-home"></i> Accueil</a> >
                    <a href="../Controllers/afficher_modules.php?theme=cyberharcelement">Cyberharcèlement</a> >
                    <span><?php echo htmlspecialchars($module['titre']); ?></span>
                </nav>
                <h2><?php echo htmlspecialchars($module['titre']); ?></h2>
                <div class="module-progress">
                    <span class="progress-label">Progression du module</span>
                    <div class="progress-steps">
                        <div class="step active" data-step="1"><i class="fas fa-book"></i> Cours</div>
                        <div class="step" data-step="2"><i class="fas fa-video"></i> Vidéo</div>
                        <div class="step" data-step="3"><i class="fas fa-question-circle"></i> Quiz</div>
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
                    <h3><i class="fas fa-book"></i> Contenu du cours</h3>
                    
                    <?php if ($pdfExists): ?>
                        <div class="pdf-container">
                            <embed 
                                src="../documents/<?php echo $module['id']; ?>.pdf" 
                                type="application/pdf" 
                                width="100%" 
                                height="600px"
                            >
                            <div class="pdf-fallback">
                                <p><i class="fas fa-file-pdf"></i> <a href="../documents/<?php echo $module['id']; ?>.pdf" target="_blank" class="download-link">
                                    Télécharger le PDF
                                </a></p>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="pdf-error">
                            <p><i class="fas fa-exclamation-triangle"></i> Fichier PDF non trouvé : documents/<?php echo $module['id']; ?>.pdf</p>
                            <p><i class="fas fa-search"></i> Chemin testé : <?php echo realpath($pdfPath); ?></p>
                            <div class="course-content">
                                <?php echo nl2br(htmlspecialchars($module['contenu'])); ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <div class="navigation-buttons">
                        <a href="../Controllers/afficher_modules.php?theme=cyberharcelement" class="nav-btn secondary">
                            <i class="fas fa-arrow-left"></i> Retour aux modules
                        </a>
                        <button class="nav-btn primary" onclick="showStep(2)">Continuer vers la vidéo <i class="fas fa-arrow-right"></i></button>
                    </div>
                </div>
            </div>

            <!-- Vidéo (étape 2) -->
<div class="module-content" id="step-2">
    <div class="content-card">
        <h3><i class="fas fa-video"></i> Vidéo éducative</h3>
        <div class="video-container">
            <video controls width="100%" poster="../videos/poster-<?php echo $module['id']; ?>.jpg">
                <source src="../videos/module-<?php echo $module['id']; ?>.mp4" type="video/mp4">
                <source src="../videos/module-<?php echo $module['id']; ?>.webm" type="video/webm">
                Votre navigateur ne supporte pas la lecture de vidéos.
                <a href="../videos/module-<?php echo $module['id']; ?>.mp4">Télécharger la vidéo</a>
            </video>
        </div>
        <div class="video-info">
            <p><strong><i class="fas fa-clipboard-list"></i> Titre :</strong> <?php echo htmlspecialchars($module['titre']); ?></p>
            <p><strong><i class="fas fa-clock"></i> Durée :</strong> Environ 3-5 minutes</p>
            <p><strong><i class="fas fa-lightbulb"></i> Conseil :</strong> Regardez attentivement cette vidéo avant de passer au quiz.</p>
        </div>
        <div class="navigation-buttons">
            <button class="nav-btn secondary" onclick="showStep(1)"><i class="fas fa-arrow-left"></i> Retour au cours</button>
            <button class="nav-btn primary" onclick="showStep(3)">Passer au quiz <i class="fas fa-arrow-right"></i></button>
        </div>
    </div>
</div>

           <!-- Quiz (étape 3) -->
<div class="module-content" id="step-3">
    <div class="content-card">
        <h3><i class="fas fa-question-circle"></i> Quiz de validation</h3>
        <div class="quiz-container">
            <div class="quiz-header">
    <p><i class="fas fa-info-circle"></i> Répondez à toutes les questions ci-dessous pour valider ce module. Score minimum requis : 70%</p>
    <div class="quiz-progress">
        <span id="quiz-progress-text"><i class="fas fa-list-ol"></i> Chargement des questions...</span>
        <div class="progress-bar">
            <div class="progress-fill" id="quiz-progress-bar" style="width: 0%"></div>
        </div>
    </div>
</div>

            <form id="quiz-form">
                <div id="questions-container">
                    <!-- Les questions seront chargées ici dynamiquement -->
                </div>

                <div class="quiz-navigation">
                    <button type="button" id="prev-btn" class="nav-btn secondary" style="display: none;">
                        <i class="fas fa-arrow-left"></i> Question précédente
                    </button>
                    <button type="button" id="next-btn" class="nav-btn primary">
                        Question suivante <i class="fas fa-arrow-right"></i>
                    </button>
                    <button type="button" id="submit-btn" class="nav-btn success" style="display: none;">
                        <i class="fas fa-paper-plane"></i> Soumettre le quiz
                    </button>
                </div>
            </form>

            <div id="quiz-results" style="display: none;">
                <!-- Les résultats seront affichés ici -->
            </div>
        </div>
    </div>
</div>
<div class="reviews-section">
                <div class="reviews-header">
                    <h3><i class="fas fa-comments"></i> Avis des utilisateurs</h3>
                    <div class="average-rating">
                        <span class="rating-number"><?php echo round($statsAvis['moyenne']); ?>/5</span>
                        <div class="stars">
                            <?php 
                            $stars = round($statsAvis['moyenne']);
                            for($i=1; $i<=5; $i++) {
                                echo $i <= $stars ? '<i class="fas fa-star filled"></i>' : '<i class="far fa-star"></i>';
                            }
                            ?>
                        </div>
                        <span class="total-reviews">(<?php echo $statsAvis['total']; ?> avis)</span>
                    </div>
                </div>

                <div class="reviews-list">
                    <?php if (!empty($avisList)): ?>
                        <?php foreach ($avisList as $avis): ?>
                            <div class="review-card">
                                <div class="review-avatar">
                                    <?php echo strtoupper(substr($avis['prenom'], 0, 1)); ?>
                                </div>
                                <div class="review-content">
                                    <div class="review-meta">
                                        <strong><?php echo htmlspecialchars($avis['prenom'] . ' ' . substr($avis['nom'], 0, 1) . '.'); ?></strong>
                                        <span class="review-date"><?php echo date('d/m/Y', strtotime($avis['date_creation'])); ?></span>
                                    </div>
                                    <div class="review-stars">
                                        <?php 
                                        for($i=1; $i<=5; $i++) {
                                            echo $i <= $avis['note'] ? '<i class="fas fa-star filled"></i>' : '<i class="far fa-star"></i>';
                                        }
                                        ?>
                                    </div>
                                    <?php if (!empty($avis['commentaire'])): ?>
                                        <p class="review-text"><?php echo nl2br(htmlspecialchars($avis['commentaire'])); ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="no-reviews">Soyez le premier à donner votre avis sur ce module !</p>
                    <?php endif; ?>
                </div>
            </div>
            <!-- Indicateur de progression globale -->
           <div class="global-progress">
                <h4><i class="fas fa-chart-line"></i> Votre progression dans ce thème</h4>
                <div class="progress-info">
                    <span>
                        <i class="fas fa-check-square"></i> 
                        Modules complétés: <?php echo $completedTheme . '/' . $totalTheme; ?>
                    </span>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: <?php echo $themeProgressPercent; ?>%"></div>
                    </div>
                    <span><?php echo $themeProgressPercent; ?>%</span>
                </div>
            </div>
        </div>
        
    </main>

 <script>
let quizQuestions = [];
let userAnswers = {};

// 1. Navigation entre les étapes (Cours -> Vidéo -> Quiz)
function showStep(stepNumber) {
    // Masquer toutes les étapes
    document.querySelectorAll('.module-content').forEach(step => {
        step.classList.remove('active');
        step.style.display = 'none'; // Force le masquage
    });
    
    // Afficher l'étape demandée
    const targetStep = document.getElementById('step-' + stepNumber);
    if (targetStep) {
        targetStep.classList.add('active');
        targetStep.style.display = 'block'; // Force l'affichage
    }
    
    // Mettre à jour la barre de progression visuelle (en haut)
    document.querySelectorAll('.progress-steps .step').forEach((step, index) => {
        if (index + 1 <= stepNumber) {
            step.classList.add('active');
        } else {
            step.classList.remove('active');
        }
    });
    
    // Si on va vers le Quiz (étape 3), on charge les questions
    if (stepNumber === 3) {
        if (quizQuestions.length === 0) {
            loadQuizQuestions();
        }
    }
    
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

// 2. Charger les questions depuis le serveur
async function loadQuizQuestions() {
    const container = document.getElementById('questions-container');
    const loadingMsg = document.getElementById('quiz-progress-text');
    
    try {
        const moduleId = <?php echo $module['id']; ?>;
        
        // Afficher un message de chargement
        loadingMsg.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Chargement des questions...';
        
        const response = await fetch(`../Controllers/quiz_controller.php?action=get_questions&module_id=${moduleId}`);
        const data = await response.json();

        if (data.error) throw new Error(data.error);
        if (!Array.isArray(data) || data.length === 0) throw new Error('Aucune question trouvée.');

        quizQuestions = data;
        
        // Mettre à jour l'interface une fois chargée
        loadingMsg.innerHTML = `<i class="fas fa-list-ol"></i> Total: ${quizQuestions.length} questions`;
        displayAllQuestions(); // Afficher toutes les questions
        
    } catch (error) {
        console.error('Erreur:', error);
        container.innerHTML = `
            <div class="quiz-error">
                <i class="fas fa-exclamation-triangle"></i>
                <p>Erreur: ${error.message}</p>
                <button class="nav-btn secondary" onclick="loadQuizQuestions()">Réessayer</button>
            </div>`;
    }
}

// 3. Afficher les questions (Mode liste complète)
function displayAllQuestions() {
    const container = document.getElementById('questions-container');
    
    const html = quizQuestions.map((q, index) => `
        <div class="question-card" id="q-card-${q.id}">
            <div class="question-header">
                <h4>Question ${index + 1}</h4>
                <p>${q.question}</p>
            </div>
            <div class="question-options">
                ${q.propositions.map((prop, pIndex) => {
                    const letter = String.fromCharCode(65 + pIndex); // A, B, C...
                    return `
                        <label class="option-label">
                            <input type="radio" name="question_${q.id}" value="${letter}" onchange="saveAnswer(${q.id}, '${letter}')">
                            <span class="option-text">
                                <span class="option-letter">${letter}</span>
                                ${prop}
                            </span>
                        </label>
                    `;
                }).join('')}
            </div>
        </div>
    `).join('');
    
    container.innerHTML = html;
    
    // Afficher le bouton de soumission
    document.getElementById('submit-btn').style.display = 'block';
    document.getElementById('prev-btn').style.display = 'none';
    document.getElementById('next-btn').style.display = 'none';
    
    // Mettre la barre de progression à 100% car tout est affiché
    document.getElementById('quiz-progress-bar').style.width = '100%';
}

// 4. Sauvegarder une réponse au clic
function saveAnswer(questionId, value) {
    userAnswers[questionId] = value;
}

// 5. Soumettre le Quiz
async function submitQuiz() {
    // Vérifier si toutes les questions ont une réponse
    if (Object.keys(userAnswers).length < quizQuestions.length) {
        alert("Veuillez répondre à toutes les questions avant de soumettre.");
        return;
    }

    try {
        const moduleId = <?php echo $module['id']; ?>;
        
        const response = await fetch('../Controllers/quiz_controller.php?action=submit_quiz', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                module_id: moduleId,
                reponses: userAnswers
            })
        });
        
        const result = await response.json();
        
        if (result.success !== undefined) {
            showQuizResults(result);
        } else {
            alert('Erreur: ' + (result.error || 'Inconnue'));
        }
        
    } catch (error) {
        console.error(error);
        alert('Erreur de communication avec le serveur.');
    }
}

// 6. Afficher les résultats et GÉRER LA SUITE
// 6. Afficher les résultats (Mise à jour avec Indices et Motivation)
function showQuizResults(result) {
    const form = document.getElementById('quiz-form');
    const resultsDiv = document.getElementById('quiz-results');
    
    form.style.display = 'none';
    resultsDiv.style.display = 'block';
    
    // Déterminer le message de motivation
    let motivationMessage = "";
    if (result.is_success) {
        motivationMessage = "Excellente performance ! Vous maîtrisez ce sujet. Continuez ainsi !";
    } else if (result.pourcentage >= 50) {
        motivationMessage = "Vous y êtes presque ! Relisez les points clés et réessayez, vous allez y arriver.";
    } else {
        motivationMessage = "Ne vous découragez pas. L'apprentissage demande du temps. Revoyez le cours et la vidéo tranquillement.";
    }

    // Boutons d'action (Logique changée selon ta demande)
    let actionButtons = '';
    if (result.is_success) {
        actionButtons = `
            <button class="nav-btn success" onclick="finishModule()">
                <i class="fas fa-check"></i> Retourner à la liste des modules
            </button>
        `;
    } else {
        actionButtons = `
            <div class="failure-actions" style="display: flex; gap: 10px; justify-content: center;">
                <button class="nav-btn primary" onclick="retryQuiz()">
                    <i class="fas fa-redo"></i> Réessayer le quiz
                </button>
                <button class="nav-btn secondary" onclick="showStep(1)">
                    <i class="fas fa-book-open"></i> Revoir le cours
                </button>
            </div>
        `;
    }
    
    // Construction du HTML des résultats détaillés
    const detailsHTML = result.results.map((item, index) => `
        <div class="result-item ${item.is_correct ? 'correct' : 'incorrect'}">
            <div class="result-question">
                <span class="question-number">Question ${index + 1}</span>
                <strong>${item.question}</strong>
            </div>
            
            <div class="result-feedback-section">
                <div class="user-choice">
                    <span class="label">Votre réponse :</span>
                    <span class="value ${item.is_correct ? 'text-success' : 'text-danger'}">
                        ${item.reponse_utilisateur} 
                        ${item.is_correct ? '<i class="fas fa-check"></i>' : '<i class="fas fa-times"></i>'}
                    </span>
                </div>

                <div class="feedback-message ${item.is_correct ? 'feedback-success' : 'feedback-hint'}">
                    ${item.is_correct 
                        ? `<i class="fas fa-star"></i> ${item.feedback}` 
                        : `<i class="fas fa-lightbulb"></i> ${item.feedback}`
                    }
                </div>
            </div>
        </div>
    `).join('');

    // HTML Final
    let html = `
        <div class="quiz-results-card ${result.is_success ? 'success' : 'failure'}">
            <div class="results-header">
                <h3>${result.is_success ? '🎉 Module Validé !' : '😕 Module non validé'}</h3>
                <div class="score-circle">
                    <span>${Math.round(result.pourcentage)}%</span>
                </div>
                <p class="motivation-text"><strong>${motivationMessage}</strong></p>
            </div>
            
            <div class="results-details-list">
                ${detailsHTML}
            </div>
            
            <div class="results-actions">
                ${actionButtons}
            </div>
        </div>
    `;
    
    resultsDiv.innerHTML = html;
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

// 7. Fonction appelée quand on clique sur "Retourner à la liste"
function finishModule() {
    // Redirection vers la liste pour mettre à jour l'affichage des cadenas
    // Le paramètre 'updated=true' force le navigateur à ne pas utiliser le cache parfois
    window.location.href = '../Controllers/afficher_modules.php?theme=cyberharcelement&refresh=true';
}

function retryQuiz() {
    userAnswers = {};
    document.getElementById('quiz-results').style.display = 'none';
    document.getElementById('quiz-form').style.display = 'block';
    document.querySelectorAll('input[type="radio"]').forEach(el => el.checked = false);
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

// Initialisation au chargement de la page
document.addEventListener('DOMContentLoaded', function() {
    // S'assurer que l'étape 1 est visible au début
    showStep(1);
    
    document.getElementById('submit-btn').addEventListener('click', submitQuiz);
});
</script>

    
</body>
</html>