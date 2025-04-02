<?php
session_start(); // Nécessaire pour utiliser $_SESSION
$connexion = mysqli_connect("localhost:25566", "root", "lecacaestcuit", "reso");

// Vérification et sécurisation de l'ID utilisateur
$user_id = isset($_GET["user"]) ? intval($_GET["user"]) : 0;

$query = "SELECT * FROM users WHERE user_id = ?";
$stmt = mysqli_prepare($connexion, $query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$request = mysqli_stmt_get_result($stmt);

if ($user = mysqli_fetch_assoc($request)) {
    echo "<br>Nom Public: " . htmlspecialchars($user["publicName"]);
    echo "<br>Nom d'utilisateur: " . htmlspecialchars($user["username"]);
    echo "<br>Date de création: " . htmlspecialchars($user["creationDate"]);
    echo "<br>Followers: " . htmlspecialchars($user["followers"]);
    echo "<br>Following: " . htmlspecialchars($user["following"]);
} else {
    echo "<br> Cet utilisateur n'existe pas !";
}

// Vérification de la connexion
$isLoggedIn = isset($_SESSION['user']); // Assurez-vous que $_SESSION['user'] est bien défini lors de la connexion

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    if (!$isLoggedIn) {
        echo "<div style='color: red; margin: 10px 0;'>Vous devez être connecté pour suivre un utilisateur</div>";
    } else {
        $followed_id = isset($_POST['followed_id']) ? intval($_POST['followed_id']) : 0;
        
        if ($followed_id > 0) {
            // Ici vous devriez :
            // 1. Vérifier que l'utilisateur existe
            // 2. Vérifier qu'il ne s'agit pas de l'utilisateur courant
            // 3. Enregistrer le follow en base
            
            echo "<div style='color: green; margin: 10px 0;'>Vous venez de suivre l'utilisateur avec l'ID: " . htmlspecialchars($followed_id) . "</div>";
            
            // Exemple de requête d'insertion (à adapter):
            // $insert_query = "INSERT INTO follows (follower_id, followed_id) VALUES (?, ?)";
            // $stmt = mysqli_prepare($connexion, $insert_query);
            // mysqli_stmt_bind_param($stmt, "ii", $_SESSION['user_id'], $followed_id);
            // mysqli_stmt_execute($stmt);
        } else {
            echo "<div style='color: red; margin: 10px 0;'>ID d'utilisateur invalide</div>";
        }
    }
}
?>

<form action="" method="POST">
    <input type="hidden" name="followed_id" value="<?= htmlspecialchars($user_id) ?>">
    <input type="submit" id="submit" name="submit" value="Suivre" <?= !$isLoggedIn ? 'disabled style="opacity: 0.5; cursor: not-allowed;" title="Vous devez être connecté pour suivre"' : '' ?>>
</form>