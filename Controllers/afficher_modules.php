<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: afficher_inscription.php');
    exit;
}

require_once __DIR__ . '/../Models/ModuleModel.php';

class ModuleController {
    private $moduleModel;
    private $db;
    
    public function __construct() {
        $this->connectDB();
        $this->moduleModel = new ModuleModel($this->db);
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
    
    public function showModules($theme) {
        // Récupérer les modules du thème
        $modules = $this->moduleModel->getModulesByTheme($theme);
        $themeInfo = $this->moduleModel->getThemeInfo($theme);
        
        // Afficher la vue des modules
        require_once __DIR__ . '/../Views/modules.php';
    }
}

// Récupérer le thème depuis l'URL
$theme = $_GET['theme'] ?? 'cyberharcelement';

$controller = new ModuleController();
$controller->showModules($theme);
