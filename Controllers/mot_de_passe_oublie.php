<?php
session_start();
require_once __DIR__ . '/../Config/Database.php';
require_once __DIR__ . '/../Models/PasswordResetModel.php';
require_once __DIR__ . '/../Services/EmailService.php';

$message = '';
$messageType = ''; // pour la couleur (succès ou erreur)

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);

    if (!empty($email)) {
        $database = new Database();
        $db = $database->getConnection();
        
        $resetModel = new PasswordResetModel($db);
        $emailService = new EmailService();

        // 1. On génère le token
        $token = $resetModel->createToken($email);

        // 2. Si le token est créé (donc l'email existe en base), on envoie le mail
        if ($token) {
            if ($emailService->sendPasswordReset($email, $token)) {
                $message = "✅ Un email a été envoyé ! Vérifiez votre boîte de réception (et vos spams).";
                $messageType = "success";
            } else {
                $message = "❌ Erreur technique lors de l'envoi du mail.";
                $messageType = "error";
            }
        } else {
            // Pour la sécurité, on affiche le même message même si l'email n'existe pas
            // (Pour éviter que quelqu'un devine qui est inscrit)
            $message = "✅ Si cet email existe, un lien a été envoyé.";
            $messageType = "success";
        }
    }
}

require_once __DIR__ . '/../Views/mot_de_passe_oublie.php';