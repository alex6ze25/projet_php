<?php
require_once __DIR__ . '/../Models/UserModel.php';

class ConnexionController {
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
    
    public function processConnexion() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Nettoyage des données
            $email = trim($_POST['email']);
            $password = $_POST['password'];
            
            // Validation des données
            $errors = $this->validateConnexion($email, $password);
            
            if (empty($errors)) {
                $result = $this->userModel->verifyUser($email, $password);
                
                if ($result['success']) {
                    // Démarrer la session et stocker les infos utilisateur
                    session_start();
                    $_SESSION['user_id'] = $result['user']['id'];
                    $_SESSION['user_nom'] = $result['user']['nom'];
                    $_SESSION['user_prenom'] = $result['user']['prenom'];
                    $_SESSION['user_email'] = $result['user']['email'];
                    
                    // Redirection vers la page profil
                    header('Location: ../Controllers/ProfilController.php');
                    exit;
                } else {
                    $errors[] = $result['message'];
                }
            }
            
            // Si erreur, réafficher le formulaire avec les erreurs
            require_once __DIR__ . '/../Views/connexion.php';
        } else {
            // Si quelqu'un accède directement à ce fichier sans POST
            header('Location: afficher_connexion.php');
            exit;
        }
    }
    
    private function validateConnexion($email, $password) {
        $errors = [];
        
        if (empty($email)) {
            $errors[] = "L'email est obligatoire";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Format d'email invalide";
        }
        
        if (empty($password)) {
            $errors[] = "Le mot de passe est obligatoire";
        }
        
        return $errors;
    }
}

// Traitement de la connexion
$controller = new ConnexionController();
$controller->processConnexion();
