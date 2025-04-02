<?php
if (isset($_SESSION['users'])) {
    echo $_SESSION['users'];
} else {
    echo "Vous n'êtes pas connecter.";
}

echo "<a href='?pages=follower'>followers</a>";
echo "<a href='?pages=follow'>follows</a>";

$connexion = mysqli_connect("localhost:25566","root","lecacaestcuit", "reso");
$user_id = mysqli_real_escape_string($connexion, $_SESSION['users']);

$query = "SELECT * FROM users WHERE user_id = '$user_id'";
$request = mysqli_query($connexion, $query);

if ($user = mysqli_fetch_assoc($request)) {
    echo "<br>Nom Public: " . htmlspecialchars($user["publicName"]);
    echo "<br>Nom d'utilisateur: " . htmlspecialchars($user["username"]);
    echo "<br>Date de création: " . htmlspecialchars($user["creationDate"]);
    echo "<br>Followers: " . htmlspecialchars($user["followers"]);
    echo "<br>Following: " . htmlspecialchars($user["following"]);
} else {
    echo "<br>Veuillez vous connecter pour accéder à votre compte.";
}
?>