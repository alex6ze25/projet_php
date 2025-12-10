<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Plateforme contre le harcèlement</title>
    <link rel="stylesheet" href="../CSS/connexion.css">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&display=swap" rel="stylesheet">

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
            <a href="../Controllers/afficher_inscription.php">S'inscrire</a>
            <a href="../Controllers/afficher_home.php">Accueil</a>
        </div>
    </header>

    <main>
        <div class="connexion-container">
            <div class="connexion-card">
                <h2>Connectez-vous</h2>
                <p class="subtitle">Accédez à votre espace personnel</p>

                <div style="text-align: center; margin-top: 20px; border-top: 1px solid #eee; padding-top: 20px;">
    <p style="color: #666; margin-bottom: 15px;">Ou connectez-vous avec</p>
    <a href="../Controllers/GoogleController.php" 
       style="display: inline-flex; align-items: center; gap: 10px; background: white; color: #333; border: 1px solid #ddd; padding: 10px 20px; border-radius: 50px; text-decoration: none; font-weight: bold; box-shadow: 0 2px 5px rgba(0,0,0,0.1); transition: all 0.2s;">
        <img src="https://upload.wikimedia.org/wikipedia/commons/5/53/Google_%22G%22_Logo.svg" alt="Google" style="width: 20px; height: 20px;">
        Google
    </a>
</div>
                
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
                    
                    <div style="text-align: right; margin-bottom: 15px;">
    <a href="mot_de_passe_oublie.php" style="color: #7B68EE; text-decoration: none; font-size: 0.9em;">Mot de passe oublié ?</a>
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