<?php
session_start();
require 'functions.php';

if (!isset($_SESSION['users'])) {
    die("Vous devez être connecté.");
}

$connexion = mysqli_connect("localhost:25566", "root", "lecacaestcuit", "reso");

$user_email = mysqli_real_escape_string($connexion, $_SESSION['users']);
$query = "SELECT id FROM users WHERE mail = '$user_email'";
$request = mysqli_query($connexion, $query);
$currentUser = mysqli_fetch_assoc($request);

if (!$currentUser) {
    die("Utilisateur introuvable.");
}

$current_user_id = $currentUser['id'];
$followed_id = intval($_POST['followed_id']);

if (isset($_POST['follow'])) {
    followUser($current_user_id, $followed_id, $connexion);
} elseif (isset($_POST['unfollow'])) {
    unfollowUser($current_user_id, $followed_id, $connexion);
}

header("Location: profile.php?id=" . $followed_id);
exit();
?>
