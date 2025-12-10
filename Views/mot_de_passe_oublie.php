<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mot de passe oublié - Stop Harcèlement</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Nunito', sans-serif; background: #f4f7f6; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .card { background: white; padding: 40px; border-radius: 15px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); width: 100%; max-width: 400px; text-align: center; }
        h2 { color: #333; margin-bottom: 10px; }
        p { color: #666; margin-bottom: 20px; }
        input { width: 100%; padding: 12px; margin: 15px 0; border: 1px solid #ddd; border-radius: 8px; box-sizing: border-box; }
        button { background: #7B68EE; color: white; border: none; padding: 12px; width: 100%; border-radius: 8px; font-weight: bold; cursor: pointer; transition: 0.3s; }
        button:hover { background: #6a5acd; }
        .back-link { display: block; margin-top: 20px; color: #666; text-decoration: none; font-size: 0.9em; }
        
        .alert { padding: 10px; border-radius: 5px; margin-bottom: 20px; font-size: 0.9em; }
        .alert.success { background: #e8f5e9; color: #2e7d32; border: 1px solid #c8e6c9; }
        .alert.error { background: #ffebee; color: #c62828; border: 1px solid #ffcdd2; }
    </style>
</head>
<body>
    <div class="card">
        <h2>Récupération</h2>
        <p>Entrez votre email pour recevoir le lien.</p>

        <?php if (!empty($message)): ?>
            <div class="alert <?php echo $messageType; ?>"><?php echo $message; ?></div>
        <?php endif; ?>

        <form method="POST">
            <input type="email" name="email" placeholder="Votre adresse email" required>
            <button type="submit">Envoyer le lien</button>
        </form>

        <a href="afficher_connexion.php" class="back-link">Retour à la connexion</a>
    </div>
</body>
</html>