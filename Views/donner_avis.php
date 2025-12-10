<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Noter le module - <?php echo htmlspecialchars($module['titre']); ?></title>
    <link rel="stylesheet" href="../CSS/avis.css"> <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <header>
        <div class="logo">
            <a href="../Controllers/afficher_home.php">
                <img src="../Images/lg.png" alt="Logo">
            </a>
        </div>
        <div class="auth-buttons">
            <a href="../Controllers/afficher_modules.php?theme=cyberharcelement">Retour aux modules</a>
        </div>
    </header>

    <main class="avis-container">
        <div class="avis-card">
            <h2>Donnez votre avis</h2>
            <p class="subtitle">Module : <strong><?php echo htmlspecialchars($module['titre']); ?></strong></p>
            <p class="instruction">Votre retour nous aide à améliorer la plateforme.</p>

            <form action="../Controllers/traiter_avis.php" method="POST">
                <input type="hidden" name="module_id" value="<?php echo $module['id']; ?>">
                <input type="hidden" name="theme_redirect" value="<?php echo $_GET['theme'] ?? 'cyberharcelement'; ?>">
                
                <div class="star-rating">
                    <input type="radio" id="star5" name="note" value="5" required><label for="star5" title="Excellent">★</label>
                    <input type="radio" id="star4" name="note" value="4"><label for="star4" title="Très bon">★</label>
                    <input type="radio" id="star3" name="note" value="3"><label for="star3" title="Bien">★</label>
                    <input type="radio" id="star2" name="note" value="2"><label for="star2" title="Moyen">★</label>
                    <input type="radio" id="star1" name="note" value="1"><label for="star1" title="Mauvais">★</label>
                </div>
                
                <div class="form-group">
                    <label for="commentaire">Votre commentaire (optionnel) :</label>
                    <textarea name="commentaire" id="commentaire" class="avis-text" placeholder="Qu'avez-vous pensé de ce cours ?"></textarea>
                </div>
                
                <button type="submit" class="submit-btn">Envoyer mon avis</button>
                <a href="../Controllers/afficher_modules.php" class="cancel-link">Annuler</a>
            </form>
        </div>
    </main>
</body>
</html>