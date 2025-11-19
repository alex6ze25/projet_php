<?php
require_once __DIR__ . '/../Models/UserModel.php';

class InscriptionController {
    private $userModel;
    private $db;
    
    public function __construct() {
        $this->connectDB();
        $this->userModel = new UserModel($this->db);
    }
    
    private function connectDB() {
        try {
            $this->db = new PDO(
                "mysql:host=localhost;dbname=elearning;charset=utf8mb4",
                "root",  // Votre username
                ""       // Votre password
            );
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            die("Erreur de connexion: " . $e->getMessage());
        }
    }
    
    public function processRegistration() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Nettoyage des données
            $nom = trim($_POST['nom']);
            $prenom = trim($_POST['prenom']);
            $email = trim($_POST['email']);
            $password = $_POST['password'];
            $confirm_password = $_POST['confirm_password'];
            
            // Validation des données
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
                    // Démarrer la session et stocker les infos utilisateur
                    session_start();
                    $_SESSION['user_id'] = $result['user_id'];
                    $_SESSION['user_nom'] = $nom;
                    $_SESSION['user_prenom'] = $prenom;
                    $_SESSION['user_email'] = $email;
                    
                    // Redirection vers la page profil
                    header('Location: ../Views/profil.php');
                    exit;
                } else {
                    $errors[] = $result['message'];
                }
            }
            
            // Si erreur, réafficher le formulaire avec les erreurs
            require_once __DIR__ . '/../Views/inscription.php';
        } else {
            // Si quelqu'un accède directement à ce fichier sans POST
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

// Traitement de l'inscription
$controller = new InscriptionController();
$controller->processRegistration();
