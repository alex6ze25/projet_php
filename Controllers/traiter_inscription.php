<?php
require_once __DIR__ . '/../Config/Database.php';
require_once __DIR__ . '/../Models/UserModel.php';

class InscriptionController {
    private $userModel;
    private $db;
    
    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->userModel = new UserModel($this->db);
    }
    
    public function processRegistration() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = trim($_POST['nom']);
            $prenom = trim($_POST['prenom']);
            $email = trim($_POST['email']);
            $password = $_POST['password'];
            $confirm_password = $_POST['confirm_password'];
            
            $errors = $this->validateRegistration($nom, $prenom, $email, $password, $confirm_password);
            
            if (empty($errors)) {
                $userData = [
                    'nom' => $nom,
                    'prenom' => $prenom,
                    'email' => $email,
                    'password' => $password
                ];
                
                $result = $this->userModel->createUser($userData);
                
                if ($result['success']) {
                    // --- ENVOI DE L'EMAIL DE BIENVENUE ---
                    require_once __DIR__ . '/../Services/EmailService.php';
                    $emailService = new EmailService();
                    // On envoie à l'email saisi dans le formulaire ($email) et au prénom ($prenom)
                    $emailService->sendWelcomeEmail($email, $prenom);
                    // -------------------------------------

                    session_start();
                    $_SESSION['user_id'] = $result['user_id'];
                    session_start();
                    $_SESSION['user_id'] = $result['user_id'];
                    $_SESSION['user_nom'] = $nom;
                    $_SESSION['user_prenom'] = $prenom;
                    $_SESSION['user_email'] = $email;
                    
                    header('Location: ../Controllers/ProfilController.php');
                    exit;
                } else {
                    $errors[] = $result['message'];
                }
            }
            require_once __DIR__ . '/../Views/inscription.php';
        } else {
            header('Location: afficher_inscription.php');
            exit;
        }
    }
    
    private function validateRegistration($nom, $prenom, $email, $password, $confirm_password) {
        $errors = [];
        if (empty($nom)) $errors[] = "Le nom est obligatoire";
        if (empty($prenom)) $errors[] = "Le prénom est obligatoire";
        if (empty($email)) {
            $errors[] = "L'email est obligatoire";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Format d'email invalide";
        }
        if (empty($password)) {
            $errors[] = "Le mot de passe est obligatoire";
        } elseif (strlen($password) < 6) {
            $errors[] = "Le mot de passe doit contenir au moins 6 caractères";
        }
        if ($password !== $confirm_password) {
            $errors[] = "Les mots de passe ne correspondent pas";
        }
        return $errors;
    }
}

$controller = new InscriptionController();
$controller->processRegistration();