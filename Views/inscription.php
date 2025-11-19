<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - Plateforme contre le harcèlement</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 500px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #2c3e50;
            text-align: center;
            margin-bottom: 30px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            color: #34495e;
            font-weight: bold;
        }
        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            box-sizing: border-box;
        }
        .btn {
            background-color: #3498db;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
        }
        .btn:hover {
            background-color: #2980b9;
        }
        .error {
            color: #e74c3c;
            background-color: #fadbd8;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .success {
            color: #27ae60;
            background-color: #d5f4e6;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .login-link {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Rejoignez la lutte contre le harcèlement</h1>
        
        <?php if (!empty($errors)): ?>
            <div class="error">
                <?php foreach ($errors as $error): ?>
                    <p><?php echo htmlspecialchars($error); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_GET['message']) && $_GET['message'] === 'inscription_reussie'): ?>
            <div class="success">
                Inscription réussie ! Vous pouvez maintenant vous connecter.
            </div>
        <?php endif; ?>
        
        <form action="index.php?action=register" method="POST">
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
            
            <button type="submit" class="btn">S'inscrire</button>
        </form>
        
        <div class="login-link">
            <p>Déjà inscrit ? <a href="index.php?action=login">Se connecter</a></p>
        </div>
    </div>
</body>
</html>