<?php
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


?>

<form action="" method="POST">
    <input type="hidden" name="followed_id" value="<?= htmlspecialchars($profile_id) ?>">
    <input type="submit" id="submit" name="submit" value="Suivre">
</form> 

<?php 

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    // Récupérer l'ID de l'utilisateur suivi
    $followed_id = $_POST['followedUser_id'];
    
    // Afficher l'ID pour vérification
    echo "Vous venez de suivre l'utilisateur avec l'ID: " . htmlspecialchars($followed_id);
}
?>



