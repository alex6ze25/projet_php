<?php
session_start();

// Afficher un message si l'utilisateur vient de se déconnecter
$message = '';
if (isset($_GET['message']) && $_GET['message'] === 'deconnecte') {
    $message = 'Vous avez été déconnecté avec succès.';
}

// Vérifier si l'utilisateur est connecté pour adapter l'affichage
$estConnecte = isset($_SESSION['user_id']);

require_once __DIR__ . '/../Views/home.php';
