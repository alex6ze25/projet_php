<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - Plateforme contre le harcèlement</title>
    <link rel="stylesheet" href="../CSS/inscription.css">
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
        <div class="site-title">
        </div>
        <div class="auth-buttons">
            <a href="../Controllers/afficher_connexion.php">Se connecter</a> <!-- Corrigé le lien -->
        </div>
    </header>

    <main>
        <div class="registration-container">
            <div class="registration-card">
                <h2>Rejoignez notre communauté</h2>
                <p class="subtitle">Ensemble, luttons contre le harcèlement</p>

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
                        Inscription réussie ! Vous pouvez maintenant vous connecter.
                    </div>
                <?php endif; ?>

                <!-- CORRECTION ICI : Supprimer le </form> prématuré -->
                <form method="POST" action="../Controllers/traiter_inscription.php" class="registration-form">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="prenom">Prénom *</label>
                            <input type="text" id="prenom" name="prenom" 
                                   value="<?php echo isset($_POST['prenom']) ? htmlspecialchars($_POST['prenom']) : ''; ?>" 
                                   required>
                        </div>
                        
                        <div class="form-group">
                            <label for="nom">Nom *</label>
                            <input type="text" id="nom" name="nom" 
                                   value="<?php echo isset($_POST['nom']) ? htmlspecialchars($_POST['nom']) : ''; ?>" 
                                   required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email *</label>
                        <input type="email" id="email" name="email" 
                               value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" 
                               required>
                    </div>
                    
                    <div class="form-group">
                        <label for="password">Mot de passe *</label>
                        <input type="password" id="password" name="password" 
                               minlength="6" required>
                        <small>Minimum 6 caractères</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="confirm_password">Confirmer le mot de passe *</label>
                        <input type="password" id="confirm_password" name="confirm_password" required>
                    </div>
                    
                    <button type="submit" class="submit-btn">Créer mon compte</button>
                </form> <!-- La balise fermante est maintenant à la bonne place -->
                
                <div class="login-redirect">
                    <p>Déjà membre ? <a href="../Controllers/afficher_connexion.php">Connectez-vous ici</a></p>
                </div>
            </div>
        </div>
    </main>
</body>
</html>