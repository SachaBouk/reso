<div class="Content">
<div class="register-container ">
<h2>Créer un compte</h2>
 
<form action="" method="POST">
    <label for="lastName">lastname :</label>
    <input type="text" id="lastName" name="lastName" required>
    <br>
    <label for="name">name :</label>
    <input type="text" id="name" name="name" required>
    <br>
    <label for="mail">mail :</label>
    <input type="email" id="mail" name="mail" required>
    <br>
    <label for="username">username :</label>
    <input type="text" id="username" name="username" required>
    <br>
    <label for="publicName">publicName :</label>
    <input type="text" id="publicName" name="publicName" required>
    <br>
    <label for="password">password :</label>
    <input type="password" id="password" name="password" required>
    <br>
    <label for="confirm_password">Confirm password :</label>
    <input type="password" id="confirm_password" name="confirm_password" required>
    <br>
    <button type="submit">Subscribe</button>
</form>
</div>
</div>
<?php
$connection = mysqli_connect("localhost:25566", "root", "lecacaestcuit", "reso");
 
if (!$connection) {
    die("Connexion impossible : " . mysqli_connect_error());
}
 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $lastname = htmlspecialchars($_POST["lastName"]);
    $name = htmlspecialchars($_POST["name"]);
    $mail = htmlspecialchars($_POST["mail"]);
    $username = htmlspecialchars($_POST["username"]);
    $publicName = htmlspecialchars($_POST["publicName"]);
    $password = htmlspecialchars($_POST["password"]);
    $confirm_password = htmlspecialchars($_POST["confirm_password"]);
    $creationDate = date("Y-m-d");
 
    if ($password === $confirm_password) {
        $query = "SELECT * FROM users WHERE mail = '$mail'";
        $result = mysqli_query($connection, $query);
 
        if (mysqli_num_rows($result) > 0) {
            echo "Cet email est déjà utilisé. Vous êtes peut-être déjà inscrit. <br>";
            echo '<a href="?pages=login"><button>Se connecter</button></a>';
        } else {
            $query = "INSERT INTO users (mail, name, lastName, username, publicName, password, creationDate) VALUES ('$mail', '$name', '$lastname', '$username', '$publicName', '$password', '$creationDate')";
 
            if (mysqli_query($connection, $query)) {
                echo "Compte créé avec succès !";
            } else {
                echo "Erreur lors de l'inscription : " . mysqli_error($connection);
            }
        }
    } else {
        echo "Les mots de passe ne correspondent pas.";
    }
}
 
mysqli_close($connection);
?>




