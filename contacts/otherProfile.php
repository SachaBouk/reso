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

<?php
$isLoggedIn = isset($_SESSION['user']);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    if (!$isLoggedIn) {
        // Afficher une erreur si non connecté
        echo "<div class='error'>Vous devez être connecté pour suivre un utilisateur</div>";
    } else {
        // Récupérer l'ID de l'utilisateur suivi (depuis POST, pas GET)
        $followed_id = $_POST['followedUser_id'];
        
        // Valider l'ID (optionnel mais recommandé)
        if (!empty($followed_id) && is_numeric($followed_id)) {
            // Afficher l'ID pour vérification
            echo "Vous venez de suivre l'utilisateur avec l'ID: " . htmlspecialchars($followed_id);
            
            // Ici vous pourriez appeler une fonction pour enregistrer le follow
            // followUser($_SESSION['user_id'], $followed_id);
        } else {
            echo "<div class='error'>ID d'utilisateur invalide</div>";
        }
    }
}
?>

<form action="" method="POST">
    <input type="hidden" name="followed_id" value="<?= htmlspecialchars($profile_id) ?>">
    <input type="submit" id="submit" name="submit" value="Suivre">
</form>



