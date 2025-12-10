<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: afficher_connexion.php');
    exit;
}

require_once __DIR__ . '/../Config/Database.php';
require_once __DIR__ . '/../Models/AvisModel.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $database = new Database();
    $db = $database->getConnection();
    $avisModel = new AvisModel($db);

    $userId = $_SESSION['user_id'];
    $moduleId = $_POST['module_id'];
    $note = intval($_POST['note']);
    $commentaire = trim($_POST['commentaire']);
    $theme = $_POST['theme_redirect'] ?? 'cyberharcelement'; // Pour savoir où rediriger

    if ($note >= 1 && $note <= 5) {
        $avisModel->soumettreAvis($userId, $moduleId, $note, $commentaire);
        // Redirection avec message de succès
        header("Location: afficher_modules.php?theme=$theme&message=avis_enregistre");
    } else {
        header("Location: afficher_modules.php?theme=$theme&error=note_invalide");
    }
    exit;
}