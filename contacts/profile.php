<?php
$connexion = mysqli_connect("localhost:25566","root","lecacaestcuit", "reso");
if (!$connexion) {
    die("Connection failed: " . mysqli_connect_error());
}else{
    $request = mysqli_query($connexion, "SELECT * FROM users");
    while ($users = mysqli_fetch_assoc($request)) {
        $user = $users["user_id"];
        echo "<br>".$users["username"];
    }
}
?>