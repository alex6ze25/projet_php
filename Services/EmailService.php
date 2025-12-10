<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require_once __DIR__ . '/../vendor/autoload.php';

class EmailService {
    private $mail;

    public function __construct() {
        $this->mail = new PHPMailer(true);
        
        try {
            // --- CONFIGURATION GMAIL ---
            $this->mail->isSMTP();
            $this->mail->Host       = 'smtp.gmail.com';
            $this->mail->SMTPAuth   = true;
            
            // 
            $this->mail->Username   = 'tknremember@gmail.com'; 
            $this->mail->Password   = 'xodt rhwk iogk mcrf'; // Le code de 16 lettres
            // 

            $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $this->mail->Port       = 587;
            
            // Encodage
            $this->mail->CharSet = 'UTF-8';
            $this->mail->setFrom($this->mail->Username, 'Plateforme Stop Harcèlement');
            
        } catch (Exception $e) {
            error_log("Erreur de configuration mailer : " . $e->getMessage());
        }
    }

    // 1. MAIL DE BIENVENUE
    public function sendWelcomeEmail($toEmail, $prenom) {
        try {
            $this->mail->clearAddresses(); 
            $this->mail->addAddress($toEmail);

            $this->mail->isHTML(true);
            $this->mail->Subject = 'Bienvenue sur la plateforme Stop Harcèlement !';
            
            $body = "
            <div style='font-family: Arial, sans-serif; color: #333; max-width: 600px; margin: auto; border: 1px solid #ddd; border-radius: 10px; overflow: hidden;'>
                <div style='background-color: #7B68EE; padding: 20px; text-align: center;'>
                    <h1 style='color: white; margin: 0;'>Bienvenue $prenom ! 👋</h1>
                </div>
                <div style='padding: 20px;'>
                    <p>Nous sommes ravis de vous compter parmi nous.</p>
                    <p>Votre compte a été créé avec succès.</p>
                    <br>
                    <div style='text-align: center;'>
                        <a href='http://localhost/php/Controllers/afficher_connexion.php' style='background-color: #27ae60; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; font-weight: bold;'>Accéder à mon espace</a>
                    </div>
                </div>
            </div>";

            $this->mail->Body = $body;
            $this->mail->AltBody = "Bienvenue $prenom ! Votre compte est activé.";

            $this->mail->send();
            return true;
        } catch (Exception $e) {
            error_log("Erreur mail bienvenue : {$this->mail->ErrorInfo}");
            return false;
        }
    }

    // 2. MAIL DE RÉINITIALISATION MOT DE PASSE (C'est celle-ci qui manquait !)
    public function sendPasswordReset($toEmail, $token) {
        try {
            $this->mail->clearAddresses();
            $this->mail->addAddress($toEmail);

            $this->mail->isHTML(true);
            $this->mail->Subject = 'Réinitialisation de votre mot de passe';

            // Lien vers la page de changement (localhost/php/...)
            // Assure-toi que le dossier 'php' correspond bien au nom de ton dossier dans htdocs
            $link = "http://localhost/php/Controllers/reset_password.php?token=" . $token;

            $body = "
            <div style='font-family: Arial, sans-serif; color: #333; max-width: 600px; margin: auto; border: 1px solid #ddd; border-radius: 10px; overflow: hidden;'>
                <div style='background-color: #d9534f; padding: 20px; text-align: center;'>
                    <h2 style='color: white; margin: 0;'>Mot de passe oublié ?</h2>
                </div>
                <div style='padding: 20px;'>
                    <p>Une demande de réinitialisation a été faite pour votre compte.</p>
                    <p>Si c'est bien vous, cliquez ci-dessous (lien valable 1h) :</p>
                    <br>
                    <div style='text-align: center;'>
                        <a href='$link' style='background-color: #d9534f; color: white; padding: 12px 25px; text-decoration: none; border-radius: 50px; font-weight: bold;'>Réinitialiser mon mot de passe</a>
                    </div>
                    <br>
                    <p style='font-size: 0.9em; color: #666;'>Si vous n'avez rien demandé, ignorez cet email.</p>
                </div>
            </div>";

            $this->mail->Body = $body;
            $this->mail->AltBody = "Cliquez sur ce lien pour réinitialiser : $link";

            $this->mail->send();
            return true;
        } catch (Exception $e) {
            error_log("Erreur mail reset : {$this->mail->ErrorInfo}");
            return false;
        }
    }
}