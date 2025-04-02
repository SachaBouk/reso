<?php
session_start();

$connexion = mysqli_connect("localhost:25566", "root", "lecacaestcuit", "reso");
if (!$connexion) {
    die("Erreur de connexion: " . mysqli_connect_error());
}

$profile_id = isset($_GET['user']) ? intval($_GET['user']) : 0;

// Requête pour obtenir les infos de l'utilisateur
$user_query = "SELECT * FROM users WHERE user_id = ?";
$user_stmt = mysqli_prepare($connexion, $user_query);
mysqli_stmt_bind_param($user_stmt, "i", $profile_id);
mysqli_stmt_execute($user_stmt);
$user_result = mysqli_stmt_get_result($user_stmt);

if ($user = mysqli_fetch_assoc($user_result)) {
    echo "<br>Nom Public: " . htmlspecialchars($user["publicName"]);
    echo "<br>Nom d'utilisateur: " . htmlspecialchars($user["username"]);
    echo "<br>Date de création: " . htmlspecialchars($user["creationDate"]);
    echo "<br>Followers: " . htmlspecialchars($user["followers"]);
    echo "<br>Following: " . htmlspecialchars($user["following"]);
} else {
    echo "<br>Utilisateur introuvable";
}

$current_user_id = isset($_SESSION['users']) ? $_SESSION['users'] : null;
$isLoggedIn = !is_null($current_user_id);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    if (!$isLoggedIn) {
        echo "<div class='error'>Connectez-vous pour suivre</div>";
    } else {
        $followed_id = $profile_id;
        
        if ($followed_id <= 0) {
            echo "<div class='error'>ID utilisateur invalide</div>";
        } elseif ($current_user_id == $followed_id) {
            echo "<div class='error'>Vous ne pouvez pas vous suivre vous-même</div>";
        } else {
            // Vérifier si l'utilisateur est déjà suivi
            $check_query = "SELECT * FROM follow WHERE followerUser_id = ? AND followedUser_id = ?";
            $check_stmt = mysqli_prepare($connexion, $check_query);
            mysqli_stmt_bind_param($check_stmt, "ii", $current_user_id, $followed_id);
            mysqli_stmt_execute($check_stmt);
            $check_result = mysqli_stmt_get_result($check_stmt);
            
            if (mysqli_num_rows($check_result) > 0) {
                echo "<div class='error'>Vous suivez déjà cet utilisateur</div>";
            } else {
                // Commencer une transaction
                mysqli_begin_transaction($connexion);
                
                try {
                    // 1. Ajouter la relation de suivi
                    $insert_query = "INSERT INTO follow (followerUser_id, followedUser_id) VALUES (?, ?)";
                    $insert_stmt = mysqli_prepare($connexion, $insert_query);
                    mysqli_stmt_bind_param($insert_stmt, "ii", $current_user_id, $followed_id);
                    mysqli_stmt_execute($insert_stmt);
                    
                    // 2. Augmenter le nombre de followers pour l'utilisateur suivi
                    $update_followers = "UPDATE users SET followers = followers + 1 WHERE user_id = ?";
                    $update_followers_stmt = mysqli_prepare($connexion, $update_followers);
                    mysqli_stmt_bind_param($update_followers_stmt, "i", $followed_id);
                    mysqli_stmt_execute($update_followers_stmt);
                    
                    // 3. Augmenter le nombre de following pour l'utilisateur qui suit
                    $update_following = "UPDATE users SET following = following + 1 WHERE user_id = ?";
                    $update_following_stmt = mysqli_prepare($connexion, $update_following);
                    mysqli_stmt_bind_param($update_following_stmt, "i", $current_user_id);
                    mysqli_stmt_execute($update_following_stmt);
                    
                    // Valider la transaction
                    mysqli_commit($connexion);
                    
                    echo "<div class='success'>Vous suivez maintenant cet utilisateur!</div>";
                    
                    // Rafraîchir les données affichées
                    header("Refresh:0");
                    
                } catch (Exception $e) {
                    // Annuler la transaction en cas d'erreur
                    mysqli_rollback($connexion);
                    echo "<div class='error'>Erreur: " . $e->getMessage() . "</div>";
                }
            }
        }
    }
}
?>

<form method="POST" action="">
    <input type="hidden" name="followed_id" value="<?= htmlspecialchars($profile_id) ?>">
    <input type="submit" name="submit" value="Suivre" <?= !$isLoggedIn ? 'disabled' : '' ?>>
</form>