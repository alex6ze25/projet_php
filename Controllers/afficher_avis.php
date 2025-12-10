<?php
session_start();

// 1. Sécurité : Vérifier connexion
if (!isset($_SESSION['user_id'])) {
    header('Location: afficher_connexion.php');
    exit;
}

// 2. Vérifier si un ID de module est fourni
if (!isset($_GET['module_id'])) {
    header('Location: afficher_modules.php');
    exit;
}

require_once __DIR__ . '/../Config/Database.php';
require_once __DIR__ . '/../Models/ModuleModel.php';

// 3. Connexion DB et récupération des infos du module
$database = new Database();
$db = $database->getConnection();
$moduleModel = new ModuleModel($db);

$moduleId = intval($_GET['module_id']);
$module = $moduleModel->getModuleContent($moduleId);

// Si le module n'existe pas, on redirige
if (!$module) {
    header('Location: afficher_modules.php');
    exit;
}

// 4. Afficher la vue
require_once __DIR__ . '/../Views/donner_avis.php';