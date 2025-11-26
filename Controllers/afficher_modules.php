<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: afficher_inscription.php');
    exit;
}

require_once __DIR__ . '/../Models/ModuleModel.php';
require_once __DIR__ . '/../Models/ProgressionModel.php';

class ModuleController {
    private $moduleModel;
    private $progressionModel;
    private $db;
    
    public function __construct() {
        $this->connectDB();
        $this->moduleModel = new ModuleModel($this->db);
        $this->progressionModel = new ProgressionModel($this->db);
    }
    
    private function connectDB() {
        try {
            $this->db = new PDO(
                "mysql:host=localhost;dbname=elearning;charset=utf8mb4",
                "root",
                ""
            );
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            die("Erreur de connexion: " . $e->getMessage());
        }
    }
    
    public function handleRequest() {
        // Vérifier si on demande un module spécifique ou la liste
        if (isset($_GET['module_id'])) {
            $this->showModuleDetail($_GET['module_id']);
        } else {
            $theme = $_GET['theme'] ?? 'cyberharcelement';
            $this->showModulesList($theme);
        }
    }
    
    public function showModulesList($theme) {
        $userId = $_SESSION['user_id'];
        
        // Récupérer les modules du thème
        $modules = $this->moduleModel->getModulesByTheme($theme);
        $themeInfo = $this->moduleModel->getThemeInfo($theme);
        
        // Récupérer la progression de l'utilisateur
        $userProgress = [];
        if ($themeInfo) {
            $userProgress = $this->progressionModel->getUserProgressInTheme($userId, $themeInfo['id']);
        }
        
        // Afficher la vue des modules (liste)
        require_once __DIR__ . '/../Views/modules.php';
    }
    
    public function showModuleDetail($moduleId) {
        $userId = $_SESSION['user_id'];
        
        // Récupérer les infos du module
        $module = $this->moduleModel->getModuleWithTheme($moduleId);
        
        if (!$module) {
            header('Location: afficher_modules.php?theme=cyberharcelement');
            exit;
        }
        
        // Vérifier si l'utilisateur peut accéder à ce module
        if (!$this->canAccessModule($userId, $moduleId, $module['theme_id'])) {
            header('Location: afficher_modules.php?theme=cyberharcelement&message=module_bloque');
            exit;
        }
        
        // Marquer le module comme commencé
        $this->progressionModel->startModule($userId, $moduleId);
        
        // Afficher la vue du module détaillé
        require_once __DIR__ . '/../Views/module_detail.php';
    }
    
    private function canAccessModule($userId, $moduleId, $themeId) {
        // Le premier module est toujours accessible
        $firstModule = $this->moduleModel->getFirstModuleOfTheme($themeId);
        if ($firstModule && $firstModule['id'] == $moduleId) {
            return true;
        }
        
        // Vérifier si le module précédent est complété
        $previousModule = $this->moduleModel->getPreviousModule($themeId, $moduleId);
        if ($previousModule) {
            $progression = $this->progressionModel->getModuleProgression($userId, $previousModule['id']);
            return $progression && $progression['is_completed'];
        }
        
        return false;
    }
}

// Gérer la requête
$controller = new ModuleController();
$controller->handleRequest();
?>