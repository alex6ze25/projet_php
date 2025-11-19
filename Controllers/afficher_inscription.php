<?php
// Ce contrôleur affiche seulement le formulaire d'inscription
session_start();

// Vérifier si l'utilisateur est déjà connecté
if (isset($_SESSION['user_id'])) {
    header('Location: ../Views/profil.php');
    exit;
}

// Afficher la vue d'inscription
require_once __DIR__ . '/../Views/inscription.php';
