<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: afficher_inscription.php');
    exit;
}

require_once __DIR__ . '/../Config/Database.php';
require_once __DIR__ . '/../Models/ModuleModel.php';
require_once __DIR__ . '/../Models/ProgressionModel.php';

class ModuleController {
    private $moduleModel;
    private $progressionModel;
    private $db;
    
    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->moduleModel = new ModuleModel($this->db);
        $this->progressionModel = new ProgressionModel($this->db);
    }
    
    public function handleRequest() {
        if (isset($_GET['module_id'])) {
            $this->showModuleDetail($_GET['module_id']);
        } else {
            $theme = $_GET['theme'] ?? 'cyberharcelement';
            $this->showModulesList($theme);
        }
    }
    
   public function showModulesList($theme) {
        $userId = $_SESSION['user_id'];
        
        // Récupérer les infos du thème
        $themeInfo = $this->moduleModel->getThemeInfo($theme);
        if (!$themeInfo) { echo "Thème introuvable"; exit; }

        // Récupérer la liste des modules AVEC la progression
        $modules = $this->progressionModel->getUserProgressInTheme($userId, $themeInfo['id']);
        
        // Calcul du pourcentage global du thème
        $totalModules = count($modules);
        $completedCount = 0;
        foreach ($modules as $m) {
            if ($m['status'] === 'completed') $completedCount++;
        }
        $progressionPercent = ($totalModules > 0) ? round(($completedCount / $totalModules) * 100) : 0;
        
        // --- NOUVEAU : GÉNÉRATION AUTOMATIQUE DU CERTIFICAT ---
        // Si 100%, on s'assure que le certificat est créé en base
        if ($progressionPercent >= 100) {
            require_once __DIR__ . '/../Models/CertificatModel.php';
            $certModel = new CertificatModel($this->db);
            // Cette fonction vérifie d'elle-même si le certificat existe déjà avant de le créer
            $certModel->genererCertificat($userId, $themeInfo['id']);
        }
        // ------------------------------------------------------

        // Passer les variables à la vue
        require_once __DIR__ . '/../Views/modules.php';
    }
    
    public function showModuleDetail($moduleId) {
        $userId = $_SESSION['user_id'];
        
        // 1. Récupérer le module
        $module = $this->moduleModel->getModuleWithTheme($moduleId);
        
        if (!$module) {
            header('Location: afficher_modules.php?theme=cyberharcelement');
            exit;
        }
        
        // 2. Vérification d'accès
        if (!$this->canAccessModule($userId, $moduleId, $module['theme_id'])) {
            header('Location: afficher_modules.php?theme=cyberharcelement&message=module_bloque');
            exit;
        }
        
        // 3. Progression
        $this->progressionModel->startModule($userId, $moduleId);
        
        // 4. Calculs de progression du thème
        $modulesTheme = $this->progressionModel->getUserProgressInTheme($userId, $module['theme_id']);
        $totalTheme = count($modulesTheme);
        $completedTheme = 0;
        foreach ($modulesTheme as $m) {
            if ($m['status'] === 'completed') $completedTheme++;
        }
        $themeProgressPercent = ($totalTheme > 0) ? round(($completedTheme / $totalTheme) * 100) : 0;

        // --- NOUVEAU : RÉCUPÉRATION DES AVIS ---
        require_once __DIR__ . '/../Models/AvisModel.php';
        $avisModel = new AvisModel($this->db);
        $avisList = $avisModel->getAvisByModule($moduleId);
        $statsAvis = $avisModel->getMoyenne($moduleId);
        // ---------------------------------------
        
        require_once __DIR__ . '/../Views/module_detail.php';
    }
    private function canAccessModule($userId, $moduleId, $themeId) {
        // Le premier module est toujours accessible
        $firstModule = $this->moduleModel->getFirstModuleOfTheme($themeId);
        if ($firstModule && $firstModule['id'] == $moduleId) return true;
        
        // Vérifier le module précédent
        $previousModule = $this->moduleModel->getPreviousModule($themeId, $moduleId);
        if ($previousModule) {
            $progression = $this->progressionModel->getModuleProgression($userId, $previousModule['id']);
            return $progression && $progression['is_completed'] == 1;
        }
        return false;
    }
}

$controller = new ModuleController();
$controller->handleRequest();