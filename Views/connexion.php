<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Plateforme contre le harcèlement</title>
    <link rel="stylesheet" href="../CSS/connexion.css">
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
            <a href="../Controllers/afficher_inscription.php">S'inscrire</a>
            <a href="../Controllers/afficher_home.php">Accueil</a>
        </div>
    </header>

    <main>
        <div class="connexion-container">
            <div class="connexion-card">
                <h2>Connectez-vous</h2>
                <p class="subtitle">Accédez à votre espace personnel</p>
                
                <?php if (!empty($errors)): ?>
                    <div class="error-message">
                        <?php foreach ($errors as $error): ?>
                            <p><?php echo htmlspecialchars($error); ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                
                <?php if (isset($_GET['message']) && $_GET['message'] === 'inscription_reussie'): ?>
                    <div class="success-message">
                        ✅ Inscription réussie ! Vous pouvez maintenant vous connecter.
                    </div>
                <?php endif; ?>

                <form method="POST" action="../Controllers/traiter_connexion.php" class="connexion-form">
                    <div class="form-group">
                        <label for="email">Email *</label>
                        <input type="email" id="email" name="email" 
                               value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" 
                               required>
                    </div>
                    
                    <div class="form-group">
                        <label for="password">Mot de passe *</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                    
                    <button type="submit" class="submit-btn">Se connecter</button>
                </form>
                
                <div class="inscription-redirect">
                    <p>Pas encore de compte ? <a href="../Controllers/afficher_inscription.php">Créez-en un ici</a></p>
                </div>
            </div>
        </div>
    </main>
</body>
</html>