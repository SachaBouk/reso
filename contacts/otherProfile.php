<?php
session_start();

$connexion = mysqli_connect("gobeliparichert.mysql.db", "gobeliparichert", "Campusdigital74", "gobeliparichert");
if (!$connexion) {
    die("Erreur de connexion: " . mysqli_connect_error());
}

$profile_id = isset($_GET['user']) ? intval($_GET['user']) : 0;

// Requête pour obtenir les infos de l'utilisateur
$user_query = "SELECT * FROM rs_users WHERE user_id = ?";
$user_stmt = mysqli_prepare($connexion, $user_query);
mysqli_stmt_bind_param($user_stmt, "i", $profile_id);
mysqli_stmt_execute($user_stmt);
$user_result = mysqli_stmt_get_result($user_stmt);

if ($user = mysqli_fetch_assoc($user_result)) {
    echo "<div class='global-content'>
            <div class='register-container'>";
    echo "<br>Nom Public : <strong>" . htmlspecialchars($user["publicName"]) . "</strong>";
    echo "<br>Nom d'utilisateur : <strong>" . htmlspecialchars($user["username"]) . "</strong>";
    echo "<br>Date de création : <strong>" . htmlspecialchars($user["creationDate"]) . "</strong>";
    echo "<br>Followers : <strong>" . htmlspecialchars($user["followers"]) . "</strong>";
    echo "<br>Following : <strong>" . htmlspecialchars($user["following"]) . "</strong>";

} else {
    echo "<br>Utilisateur introuvable";
}

$current_user_id = isset($_SESSION['users']) ? $_SESSION['users'] : null;
$isLoggedIn = !is_null($current_user_id);

// Vérifier si l'utilisateur est déjà suivi
$check_query = "SELECT * FROM rs_follow WHERE followerUser_id = ? AND followedUser_id = ?";
$check_stmt = mysqli_prepare($connexion, $check_query);
mysqli_stmt_bind_param($check_stmt, "ii", $current_user_id, $profile_id);
mysqli_stmt_execute($check_stmt);
$check_result = mysqli_stmt_get_result($check_stmt);

$isFollowing = mysqli_num_rows($check_result) > 0;

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
            $check_query = "SELECT * FROM rs_follow WHERE followerUser_id = ? AND followedUser_id = ?";
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
                    $insert_query = "INSERT INTO rs_follow (followerUser_id, followedUser_id) VALUES (?, ?)";
                    $insert_stmt = mysqli_prepare($connexion, $insert_query);
                    mysqli_stmt_bind_param($insert_stmt, "ii", $current_user_id, $followed_id);
                    mysqli_stmt_execute($insert_stmt);
                    
                    // 2. Augmenter le nombre de followers pour l'utilisateur suivi
                    $update_followers = "UPDATE rs_users SET followers = followers + 1 WHERE user_id = ?";
                    $update_followers_stmt = mysqli_prepare($connexion, $update_followers);
                    mysqli_stmt_bind_param($update_followers_stmt, "i", $followed_id);
                    mysqli_stmt_execute($update_followers_stmt);
                    
                    // 3. Augmenter le nombre de following pour l'utilisateur qui suit
                    $update_following = "UPDATE rs_users SET following = following + 1 WHERE user_id = ?";
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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['unfollow'])) {
    if ($isFollowing) {
        // Commencer une transaction
        mysqli_begin_transaction($connexion);

        try {
            // 1. Supprimer la relation de suivi
            $delete_query = "DELETE FROM rs_follow WHERE followerUser_id = ? AND followedUser_id = ?";
            $delete_stmt = mysqli_prepare($connexion, $delete_query);
            mysqli_stmt_bind_param($delete_stmt, "ii", $current_user_id, $profile_id);
            mysqli_stmt_execute($delete_stmt);

            // 2. Réduire le nombre de followers pour l'utilisateur suivi
            $update_followers = "UPDATE rs_users SET followers = followers - 1 WHERE user_id = ?";
            $update_followers_stmt = mysqli_prepare($connexion, $update_followers);
            mysqli_stmt_bind_param($update_followers_stmt, "i", $profile_id);
            mysqli_stmt_execute($update_followers_stmt);

            // 3. Réduire le nombre de following pour l'utilisateur qui suit
            $update_following = "UPDATE rs_users SET following = following - 1 WHERE user_id = ?";
            $update_following_stmt = mysqli_prepare($connexion, $update_following);
            mysqli_stmt_bind_param($update_following_stmt, "i", $current_user_id);
            mysqli_stmt_execute($update_following_stmt);

            // Valider la transaction
            mysqli_commit($connexion);

            echo "<div class='success'>Vous ne suivez plus cet utilisateur!</div>";

            // Rafraîchir les données affichées
            header("Refresh:0");
        } catch (Exception $e) {
            // Annuler la transaction en cas d'erreur
            mysqli_rollback($connexion);
            echo "<div class='error'>Erreur: " . $e->getMessage() . "</div>";
        }
    } else {
        echo "<div class='error'>Vous ne suivez pas cet utilisateur</div>";
    }
}

if ($isFollowing) {
    echo "<form method='POST' action=''>
            <input type='hidden' name='unfollow' value='1'>
            <input class='button' type='submit' value='Ne plus suivre'>
          </form>";
} else {
    echo "<form method='POST' action=''>
            <input type='hidden' name='followed_id' value='" . htmlspecialchars($profile_id) . "'>
            <input class='button' type='submit' name='submit' value='Suivre'>
          </form>";
}
?>
</div>
</div>
<style>
    .button {
        background: black;
        color: white;
        border: none;
        padding: 10px;
        width: 100%;
        margin-top: 5px;
        cursor: pointer;
        border-radius: 5px;
        font-size: 1.2em;
    }
</style>