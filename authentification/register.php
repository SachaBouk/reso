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
$connection = mysqli_connect("gobeliparichert.mysql.db", "gobeliparichert", "Campusdigital74", "gobeliparichert");
 
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
        $query = "SELECT * FROM rs_users WHERE mail = '$mail'";
        $result = mysqli_query($connection, $query);
 
        if (mysqli_num_rows($result) > 0) {
            echo "Cet email est déjà utilisé. Vous êtes peut-être déjà inscrit. <br>";
            echo '<a href="?pages=login"><button>Se connecter</button></a>';
        } else {
            $query = "INSERT INTO rs_users (mail, name, lastName, username, publicName, password, creationDate) VALUES ('$mail', '$name', '$lastname', '$username', '$publicName', '$password', '$creationDate')";
 
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


<style>

.Content {
    display: flex;
    justify-content: center;
    align-items: center;
}

.register-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    background: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    text-align: center;
    width: 350px;
}

form{
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 20px;
}

legend{
    font-size: 1.5em;
    margin-bottom: 20px;
    color: black;
    font-weight: bold;
    text-align: center;
    margin-top: 0;
}

input {
    width: 100%;
    padding: 10px;
    margin-top: 5px;
    border: 1px solid #ccc;
    border-radius: 5px;
    margin : 20px 0;
}

.input-group {
    margin: 10px 0;
    text-align: left;
}

button {
    background: black;
    color: white;
    border: none;
    padding: 10px;
    width: 100%;
    margin-top: 10px;
    cursor: pointer;
    border-radius: 5px;
    font-size: 1em;
    margin: 20px 0;
}

button:hover {
    background: black;
}

p{
    margin: 10px 0;
}



</style>



