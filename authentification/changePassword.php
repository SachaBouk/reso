<form method="post" action="">
    <input name="oldPassword" type="password" required>
    <input name="newPassword" type="password" required>
    <input name="confirmNewPassword" type="password" required>
    <input name="submit" type="submit">
</form>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $oldPassword = htmlspecialchars(hash("sha256", $_POST["oldPassword"]));
    $newPassword = htmlspecialchars(hash("sha256", $_POST["newPassword"]));
    $confirmNewPassword = htmlspecialchars(hash("sha256", $_POST["confirmNewPassword"]));

    $connexion = mysqli_connect("localhost:25566", "root", "lecacaestcuit", "reso");
    if (!$connexion) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $user_id = mysqli_real_escape_string($connexion, $_SESSION['users']);

    $query = "SELECT password FROM users WHERE user_id = '$user_id'";
    $request = mysqli_query($connexion, $query);

    if ($user = mysqli_fetch_assoc($request)) {
        if ($oldPassword == $user["password"]) {
            if ($newPassword == $confirmNewPassword) {
                if ($newPassword == $oldPassword) {
                    echo "Ceci est deja votre mot de passe !";
                } else {
                    $result = mysqli_query($connexion, "UPDATE users SET password = '$newPassword' WHERE user_id = '$user_id'");
                }
            } else {
                echo "Les deux mots de passe ne correspondent pas";
            }
        } else {
            echo "L'ancien mot de passe n'est pas correct";
        }
    }
}
?>