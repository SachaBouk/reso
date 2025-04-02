<?php
session_start(); // Ne pas oublier cette ligne au tout début
$connexion = mysqli_connect("localhost:25566", "root", "lecacaestcuit", "reso");

// Vérification connexion DB
if (!$connexion) {
    die("Erreur de connexion: " . mysqli_connect_error());
}

// Récupération ID utilisateur
$profile_id = isset($_GET["user"]) ? intval($_GET["user"]) : 0;

// Requête utilisateur
$query = "SELECT * FROM users WHERE user_id = ?";
$stmt = mysqli_prepare($connexion, $query);
mysqli_stmt_bind_param($stmt, "i", $profile_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($user = mysqli_fetch_assoc($result)) {
    echo "<br>Nom Public: " . htmlspecialchars($user["publicName"]);
    echo "<br>Nom d'utilisateur: " . htmlspecialchars($user["username"]);
    echo "<br>Date de création: " . htmlspecialchars($user["creationDate"]);
    echo "<br>Followers: " . htmlspecialchars($user["followers"]);
    echo "<br>Following: " . htmlspecialchars($user["following"]);
} else {
    echo "<br>Utilisateur introuvable";
}

// Vérification connexion utilisateur
$isLoggedIn = isset($_SESSION['users']) && !empty($_SESSION['users']);
$current_user_id = $isLoggedIn ? $_SESSION['users'] : null;

// Traitement formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    if (!$isLoggedIn) {
        echo "<div class='error'>Connectez-vous pour suivre</div>";
    } else {
        $followed_id = isset($_POST['followed_id']) ? intval($_POST['followed_id']) : 0;
        
        if ($followed_id <= 0) {
            echo "<div class='error'>ID invalide</div>";
        } elseif ($current_user_id == $followed_id) {
            echo "<div class='error'>Vous ne pouvez pas vous suivre vous-même</div>";
        } else {
            // Vérification si déjà suivi
            $check_sql = "SELECT * FROM follow WHERE followerUser_id = ? AND followedUser_id = ?";
            $check_stmt = mysqli_prepare($connexion, $check_sql);
            mysqli_stmt_bind_param($check_stmt, "ii", $current_user_id, $followed_id);
            mysqli_stmt_execute($check_stmt);
            
            if (mysqli_stmt_num_rows($check_stmt) > 0) {
                echo "<div class='error'>Déjà suivi</div>";
            } else {
                // Insertion du follow
                $insert_sql = "INSERT INTO follow (followerUser_id, followedUser_id, follow_date) VALUES (?, ?, NOW())";
                $insert_stmt = mysqli_prepare($connexion, $insert_sql);
                mysqli_stmt_bind_param($insert_stmt, "ii", $current_user_id, $followed_id);
                
                if (mysqli_stmt_execute($insert_stmt)) {
                    echo "<div class='success'>Suivi réussi!</div>";
                    
                    // Mise à jour compteur
                    $update_sql = "UPDATE users SET followers = followers + 1 WHERE user_id = ?";
                    $update_stmt = mysqli_prepare($connexion, $update_sql);
                    mysqli_stmt_bind_param($update_stmt, "i", $followed_id);
                    mysqli_stmt_execute($update_stmt);
                } else {
                    echo "<div class='error'>Erreur: " . mysqli_error($connexion) . "</div>";
                }
            }
        }
    }
}
?>

<form method="POST">
    <input type="hidden" name="followed_id" value="<?= $profile_id ?>">
    <input type="submit" name="submit" value="Suivre" <?= !$isLoggedIn ? 'disabled' : '' ?>>
</form>