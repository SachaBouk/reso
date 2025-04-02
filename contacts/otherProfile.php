<?php
session_start();
$connexion = mysqli_connect("localhost:25566","root","lecacaestcuit", "reso");

// Récupération de l'ID utilisateur depuis l'URL
$user_id = isset($_GET["user"]) ? intval($_GET["user"]) : 0;

// Requête sécurisée avec prepared statement
$query = "SELECT * FROM users WHERE user_id = ?";
$stmt = mysqli_prepare($connexion, $query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($user = mysqli_fetch_assoc($result)) {
    echo "<br>Nom Public: " . htmlspecialchars($user["publicName"]);
    echo "<br>Nom d'utilisateur: " . htmlspecialchars($user["username"]);
    echo "<br>Date de création: " . htmlspecialchars($user["creationDate"]);
    echo "<br>Followers: " . htmlspecialchars($user["followers"]);
    echo "<br>Following: " . htmlspecialchars($user["following"]);
} else {
    echo "<br> Cet utilisateur n'existe pas !";
}

echo "<br>ID dans l'URL: " . htmlspecialchars($user_id);

$isLoggedIn = isset($_SESSION['users']) && !empty($_SESSION['users']);

echo "<br>État connexion: " . ($isLoggedIn ? 'Connecté (ID: '.$_SESSION['users'].')' : 'Non connecté');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    if (!$isLoggedIn) {
        echo "<div class='error'>Vous devez être connecté pour suivre un utilisateur</div>";
    } else {
        // On récupère l'ID depuis le champ hidden, pas depuis $_POST['user']
        $followed_id = isset($_POST['followed_id']) ? intval($_POST['followed_id']) : 0;
        
        if ($followed_id > 0) {
            echo "<div class='success'>Suivi réussi! ID: " . htmlspecialchars($followed_id) . "</div>";
            
            
        } else {
            echo "<div class='error'>ID invalide</div>";
        }
    }
}
?>

<form action="" method="POST">
    <input type="hidden" name="followed_id" value="<?= htmlspecialchars($user_id) ?>">
    <input type="submit" name="submit" value="Suivre">
</form>