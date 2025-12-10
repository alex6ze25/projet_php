<?php
session_start();

if (!isset($_SESSION['user_id']) || !isset($_GET['theme_id'])) {
    header('Location: afficher_home.php');
    exit;
}

require_once __DIR__ . '/../Config/Database.php';
require_once __DIR__ . '/../Models/CertificatModel.php';
require_once __DIR__ . '/../Models/ModuleModel.php';

class CertificatController {
    private $db;
    private $certificatModel;
    private $moduleModel;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->certificatModel = new CertificatModel($this->db);
        $this->moduleModel = new ModuleModel($this->db);
    }

    public function generer() {
        $userId = $_SESSION['user_id'];
        $themeId = intval($_GET['theme_id']);

        // 1. Vérifier si l'utilisateur a fini tous les modules
        if ($this->certificatModel->verifierEligibilite($userId, $themeId)) {
            
            // 2. Enregistrer le certificat en base
            $this->certificatModel->genererCertificat($userId, $themeId);
            
            // 3. Récupérer les infos pour l'affichage
            $themeInfo = $this->moduleModel->getThemeInfoById($themeId); // On doit ajouter cette méthode ou utiliser celle existante
            $userNom = $_SESSION['user_nom'];
            $userPrenom = $_SESSION['user_prenom'];
            $date = date('d/m/Y');

            // 4. Afficher la vue
            require_once __DIR__ . '/../Views/certificat.php';
        } else {
            // Pas fini ? Retour aux modules !
            header("Location: afficher_modules.php?error=pas_fini");
        }
    }
}

// Petite méthode manquante dans ModuleModel ? On triche ici ou tu l'ajoutes dans ModuleModel :
// Ajoute juste ça dans ModuleModel.php si ce n'est pas le cas :
// public function getThemeInfoById($id) { ... SELECT * FROM themes WHERE id = :id ... }
// SINON, pour aller vite, on récupère le titre via une requête simple ici :
// (Note: l'idéal est de l'avoir dans le Model, je suppose que tu l'as déjà via getThemeInfo)

$controller = new CertificatController();
$controller->generer();