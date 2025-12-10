<?php
session_start();

// Vérifier si connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: afficher_connexion.php');
    exit;
}

require_once __DIR__ . '/../Config/Database.php';
require_once __DIR__ . '/../Models/ProgressionModel.php';
require_once __DIR__ . '/../Models/CertificatModel.php';
require_once __DIR__ . '/../Models/ModuleModel.php'; // AJOUT IMPORTANT

class ProfilController {
    private $progressionModel;
    private $certificatModel;
    private $moduleModel; // AJOUT
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->progressionModel = new ProgressionModel($this->db);
        $this->certificatModel = new CertificatModel($this->db);
        $this->moduleModel = new ModuleModel($this->db); // AJOUT
    }

    public function showProfil() {
        $userId = $_SESSION['user_id'];

        // --- NOUVEAU : VÉRIFICATION AUTO DES CERTIFICATS ---
        // On récupère tous les thèmes pour vérifier si un certificat doit être généré
        $themes = $this->moduleModel->getAllThemes();
        
        foreach ($themes as $theme) {
            // Si l'utilisateur a tout fini (100%) pour ce thème
            if ($this->certificatModel->verifierEligibilite($userId, $theme['id'])) {
                // On essaie de le générer (la méthode genererCertificat vérifie déjà les doublons)
                $this->certificatModel->genererCertificat($userId, $theme['id']);
            }
        }
        // ---------------------------------------------------
        
        // Récupérer les stats globales
        $stats = $this->progressionModel->getGlobalStats($userId);

        $completedModules = $stats['completed_modules'];
        $globalPercentage = $stats['global_percentage'];
        $certificates = $stats['certificates'];

        // Récupérer la liste des certificats à afficher
        $mesCertificats = $this->certificatModel->getCertificatsUser($userId);

        require_once __DIR__ . '/../Views/profil.php';
    }
}

$controller = new ProfilController();
$controller->showProfil();