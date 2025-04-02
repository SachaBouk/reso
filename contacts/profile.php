<?php
session_start();
require 'config.php'; // Connexion BDD
require 'functions.php'; // Fonctions follows

if (!isset($_SESSION['users'])) {
    echo "Vous n'êtes pas connecté.";
    exit();
}

$connexion = mysqli_connect("localhost:25566", "root", "lecacaestcuit", "reso");
$user_email = mysqli_real_escape_string($connexion, $_SESSION['users']);

// Récupérer les infos de l'utilisateur connecté
$query = "SELECT * FROM users WHERE mail = '$user_email'";
$request = mysqli_query($connexion, $query);
$currentUser = mysqli_fetch_assoc($request);

// Récupérer les infos du profil visité
if (!isset($_GET['id'])) {
    echo "Profil introuvable.";
    exit();
}
$profile_id = intval($_GET['id']);

$query = "SELECT * FROM users WHERE id = $profile_id";
$request = mysqli_query($connexion, $query);
$profileUser = mysqli_fetch_assoc($request);

if (!$profileUser) {
    echo "Utilisateur introuvable.";
    exit();
}

// Vérifier si l'utilisateur suit déjà ce profil
$isFollowing = isFollowing($currentUser['id'], $profile_id, $connexion);

// Affichage du profil
echo "<h2>Profil de " . htmlspecialchars($profileUser["username"]) . "</h2>";
echo "<p>Nom Public: " . htmlspecialchars($profileUser["publicName"]) . "</p>";
echo "<p>Date de création: " . htmlspecialchars($profileUser["creationDate"]) . "</p>";
echo "<p>Followers: " . htmlspecialchars($profileUser["followers"]) . "</p>";
echo "<p>Following: " . htmlspecialchars($profileUser["following"]) . "</p>";

// Bouton Suivre / Ne plus suivre
if ($currentUser['id'] !== $profile_id) {
    echo '<form method="POST" action="follow_action.php">';
    echo '<input type="hidden" name="followed_id" value="' . $profile_id . '">';
    if ($isFollowing) {
        echo '<button type="submit" name="unfollow">Ne plus suivre</button>';
    } else {
        echo '<button type="submit" name="follow">Suivre</button>';
    }
    echo '</form>';
}
?>
