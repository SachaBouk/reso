<?php
session_start();
$connexion = mysqli_connect("localhost:25566","root","lecacaestcuit", "reso");
$user_id = $_GET["user"];

$query = "SELECT * FROM users WHERE user_id = '$user_id'";
$request = mysqli_query($connexion, $query);

if ($user = mysqli_fetch_assoc($request)) {
    echo "<br>Nom Public: " . htmlspecialchars($user["publicName"]);
    echo "<br>Nom d'utilisateur: " . htmlspecialchars($user["username"]);
    echo "<br>Date de création: " . htmlspecialchars($user["creationDate"]);
    echo "<br>Followers: " . htmlspecialchars($user["followers"]);
    echo "<br>Following: " . htmlspecialchars($user["following"]);
} else {
    echo "<br> Cet utilisateur n'existe pas !";
}
echo $_GET["user"];

$isLoggedIn = isset($_SESSION['user']) && !empty($_SESSION['user']);

echo "<pre>Session: "; print_r($_SESSION); echo "</pre>";
echo "État connexion: " . ($isLoggedIn ? 'Connecté' : 'Non connecté');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    if (!$isLoggedIn) {
        echo "<div class='error'>Vous devez être connecté pour suivre un utilisateur</div>";
    } else {
        $followed_id = $_POST['followed_id'];
        
        if (!empty($followed_id) && is_numeric($followed_id)) {
            echo "Suivi réussi! ID: " . htmlspecialchars($followed_id);
            
            followUser($_SESSION['user_id'], $followed_id);
            
        } else {
            echo "<div class='error'>ID invalide</div>";
        }
    }
}

?>

<form action="" method="POST">
    <input type="hidden" name="followed_id" value="<?= htmlspecialchars($profile_id ?? '') ?>">
    <input type="submit" name="submit" value="Suivre">
</form>


