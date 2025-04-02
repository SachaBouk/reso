<?php
$connexion = mysqli_connect("localhost:25566", "root", "lecacaestcuit", "reso");

// Vérification de la connexion à la base de données
if (!$connexion) {
    die("Erreur de connexion à la base de données: " . mysqli_connect_error());
}

// Récupération de l'ID utilisateur depuis l'URL
$user_id = isset($_GET["user"]) ? intval($_GET["user"]) : 0;

// Requête pour obtenir les infos de l'utilisateur
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
    echo "<br>Cet utilisateur n'existe pas !";
}

// Vérification de connexion
$isLoggedIn = isset($_SESSION['users']) && !empty($_SESSION['users']);

// Traitement du formulaire de suivi
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    if (!$isLoggedIn) {
        echo "<div class='error'>Vous devez être connecté pour suivre un utilisateur</div>";
    } else {
        $follower_id = $_SESSION['users']; // ID de l'utilisateur connecté
        $followed_id = isset($_POST['followedUser_id']) ? intval($_POST['followedUser_id']) : 0;
        
        if ($followed_id > 0 && $follower_id != $followed_id) {
            // Vérifier si le suivi existe déjà
            $check_query = "SELECT * FROM follow WHERE followerUser_id = $follower_id  AND followedUser_id = $user_id";
            $check_stmt = mysqli_prepare($connexion, $check_query);
            mysqli_stmt_bind_param($check_stmt, "ii", $follower_id, $followed_id);
            mysqli_stmt_execute($check_stmt);
            $check_result = mysqli_stmt_get_result($check_stmt);
            
            if (mysqli_num_rows($check_result) > 0) {
                echo "<div class='error'>Vous suivez déjà cet utilisateur</div>";
            } else {
                // Insérer le nouveau suivi
                $insert_query = "INSERT INTO follow (followedUser_id, followedUser_id, follow_date) VALUES (?, ?, NOW())";
                $insert_stmt = mysqli_prepare($connexion, $insert_query);
                mysqli_stmt_bind_param($insert_stmt, "ii", $follower_id, $followed_id);
                
                if (mysqli_stmt_execute($insert_stmt)) {
                    echo "<div class='success'>Vous suivez maintenant cet utilisateur!</div>";
                    
                    // Mettre à jour le compteur de followers
                    $update_query = "UPDATE users SET followers = followers + 1 WHERE user_id = ?";
                    $update_stmt = mysqli_prepare($connexion, $update_query);
                    mysqli_stmt_bind_param($update_stmt, "i", $followed_id);
                    mysqli_stmt_execute($update_stmt);
                } else {
                    echo "<div class='error'>Erreur lors du suivi: " . mysqli_error($connexion) . "</div>";
                }
            }
        } else {
            echo "<div class='error'>ID invalide ou tentative de se suivre soi-même</div>";
        }
    }
}
?>

<form action="" method="POST">
    <input type="hidden" name="followed_id" value="<?= htmlspecialchars($user_id) ?>">
    <input type="submit" name="submit" value="Suivre" <?= !$isLoggedIn ? 'title="Connectez-vous pour suivre"' : '' ?>>
</form>