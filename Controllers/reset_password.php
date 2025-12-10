<?php
session_start();
require_once __DIR__ . '/../Config/Database.php';
require_once __DIR__ . '/../Models/PasswordResetModel.php';

$database = new Database();
$db = $database->getConnection();
$resetModel = new PasswordResetModel($db);

$error = '';
// On récupère le token soit dans l'URL (GET) soit dans le formulaire (POST)
$token = $_GET['token'] ?? $_POST['token'] ?? '';

// 1. Vérifier si le token est valide (existe et pas expiré)
$request = $resetModel->verifyToken($token);

if (!$request) {
    die("Ce lien est invalide ou a expiré. Veuillez refaire une demande.");
}

// 2. Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];

    if (strlen($password) < 6) {
        $error = "Le mot de passe doit faire au moins 6 caractères.";
    } elseif ($password !== $confirm) {
        $error = "Les mots de passe ne correspondent pas.";
    } else {
        // Tout est bon, on met à jour !
        if ($resetModel->updateUserPassword($request['email'], $password)) {
            // On supprime le token pour qu'il ne serve qu'une fois
            $resetModel->deleteToken($request['email']);
            
            // Redirection vers connexion avec message
            header('Location: afficher_connexion.php?message=password_updated');
            exit;
        } else {
            $error = "Erreur technique lors de la mise à jour.";
        }
    }
}

require_once __DIR__ . '/../Views/reset_password.php';