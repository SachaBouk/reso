<?php
if (isset($_SESSION['users'])) {
    echo "";
} else {
    echo "";
}

$connexion = mysqli_connect("localhost:25566", "root", "lecacaestcuit", "reso");
$user_id = mysqli_real_escape_string($connexion, $_SESSION['users']);

$query = "SELECT * FROM users WHERE user_id = '$user_id'";
$request = mysqli_query($connexion, $query);

if ($user = mysqli_fetch_assoc($request)) {
    echo "<div class='global-content'>
            <div class='register-container'>";
    echo "<br>Nom Public : <strong>" . htmlspecialchars($user["publicName"]) . "</strong>";
    echo "<br>Nom d'utilisateur : <strong>" . htmlspecialchars($user["username"]) . "</strong>";
    echo "<br>Date de création : <strong>" . htmlspecialchars($user["creationDate"]) . "</strong>";
    echo "<br>Followers : <strong>" . htmlspecialchars($user["followers"]) . "</strong>";
    echo "<br>Following : <strong>" . htmlspecialchars($user["following"]) . "</strong>";
    echo "</div>
            </div>";

} else {
    echo "<div class='global-content'>
            <div class='register-container'>";
    echo "<br><strong>Veuillez vous connecter pour accéder à votre compte.</strong>";
    echo "</div>
            </div>";
}
?>