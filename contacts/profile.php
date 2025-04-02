<?php
if (isset($_SESSION['users'])) {
    echo $_SESSION['users'];
} else {
    echo "Vous n'êtes pas connecter.";
}

echo "<a href='?pages=follower'>followers</a>";
echo "<a href='?pages=follow'>follows</a>";

$connexion = mysqli_connect("localhost:25566","root","lecacaestcuit", "reso");
if (!$connexion) {
    die("Connection failed: " . mysqli_connect_error());
}else{
    $request = mysqli_query($connexion, "SELECT * FROM users");
    while ($users = mysqli_fetch_assoc($request)) {
        $user = $users["user_id"];
        echo "<br>".$users["publicName"];
        echo "<br>".$users["username"];
        echo "<br>".$users["creationDate"];
        echo "<br>".$users["followers"]." ".$users["following"];
    }
}
?>