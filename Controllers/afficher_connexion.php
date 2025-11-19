<?php
session_start();

// Si l'utilisateur est déjà connecté, rediriger vers le profil
if (isset($_SESSION['user_id'])) {
    header('Location: ../Views/profil.php');
    exit;
}

// Afficher la vue de connexion
require_once __DIR__ . '/../Views/connexion.php';
