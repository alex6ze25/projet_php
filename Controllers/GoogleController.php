<?php
session_start();

// Charger les librairies Google
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../Config/Database.php';
require_once __DIR__ . '/../Models/GoogleUserModel.php';

// =============================================================
// CONFIGURATION (COLLE TES CLÉS ICI)
// =============================================================
$clientID = '1017687514738-hs6jjrg4he55923c808ci25d4ksnkaba.apps.googleusercontent.com'; 
$clientSecret = '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';
$redirectUri = 'http://localhost/PHP/Controllers/GoogleController.php';
$redirectUri = 'https://3623ab4e8e35.ngrok-free.app/php/Controllers/GoogleController.php';
// =============================================================

$client = new Google_Client();
$client->setClientId($clientID);
$client->setClientSecret($clientSecret);
$client->setRedirectUri($redirectUri);
$client->addScope("email");
$client->addScope("profile");

// 1. Retour de Google (Connexion réussie)
if (isset($_GET['code'])) {
    try {
        $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
        
        if (!isset($token['error'])) {
            $client->setAccessToken($token['access_token']);
            $google_oauth = new Google_Service_Oauth2($client);
            $google_account_info = $google_oauth->userinfo->get();
            
            // Infos récupérées de Google
            $userInfo = [
                'id' => $google_account_info->id,
                'email' => $google_account_info->email,
                'givenName' => $google_account_info->givenName,
                'familyName' => $google_account_info->familyName,
                'picture' => $google_account_info->picture
            ];

            // Traitement BDD
            $database = new Database();
            $db = $database->getConnection();
            $googleModel = new GoogleUserModel($db);

            $user = $googleModel->checkUserByGoogleId($userInfo['id']);

            if ($user) {
                // Cas 1 : Déjà inscrit via Google
                loginUser($user);
            } else {
                $existingEmail = $googleModel->checkUserByEmail($userInfo['email']);
                if ($existingEmail) {
                    // Cas 2 : Email existe déjà -> on lie le compte
                    $googleModel->linkGoogleToEmail($userInfo['email'], $userInfo['id'], $userInfo['picture']);
                    $user = $existingEmail;
                    loginUser($user);
                } else {
                    // Cas 3 : Inscription complète (Nouvel utilisateur)
                    $newUserId = $googleModel->createGoogleUser($userInfo);
                    $user = [
                        'id' => $newUserId,
                        'nom' => $userInfo['familyName'],
                        'prenom' => $userInfo['givenName'],
                        'email' => $userInfo['email']
                    ];

                    // --- ENVOI DE L'EMAIL DE BIENVENUE ---
                    require_once __DIR__ . '/../Services/EmailService.php';
                    $emailService = new EmailService();
                    $emailService->sendWelcomeEmail($user['email'], $user['prenom']);
                    // -------------------------------------

                    loginUser($user);
                }
            }
        }
    } catch (Exception $e) {
        echo "Erreur Google : " . $e->getMessage();
        exit;
    }
}

// Fonction de connexion
function loginUser($user) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_nom'] = $user['nom'];
    $_SESSION['user_prenom'] = $user['prenom'];
    $_SESSION['user_email'] = $user['email'];
    
    header('Location: ProfilController.php');
    exit;
}

// 2. Si pas de code, on redirige vers Google pour se connecter
if (!isset($_GET['code'])) {
    header('Location: ' . $client->createAuthUrl());
    exit;
}