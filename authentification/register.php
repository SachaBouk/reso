<div class="global-content">
    <div class="register-container">
        <h2>Créer un compte</h2>

        <form action="" method="POST">
            <label for="lastName">Nom :</label>
            <input type="text" id="lastName" name="lastName" required>
            <br>
            <label for="name">Prénom :</label>
            <input type="text" id="name" name="name" required>
            <br>
            <label for="mail">Email :</label>
            <input type="email" id="mail" name="mail" required>
            <br>
            <label for="username">Pseudonyme :</label>
            <input type="text" id="username" name="username" required>
            <br>
            <label for="publicName">Nom Public :</label>
            <input type="text" id="publicName" name="publicName" required>
            <br>
            <label for="password">Mot de passe :</label>
            <input type="password" id="password" name="password" required>
            <br>
            <label for="confirm_password">Confirmer Mot de passe :</label>
            <input type="password" id="confirm_password" name="confirm_password" required>
            <br>
            <button type="submit">S'inscrire</button>
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

.global-content {
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
    width: 500px;
}

form{
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 20px;
}

h2{
    font-size: 1.9em;
    margin-bottom: 10px;
    color: black;
    font-weight: bold;
    text-align: center;
    margin-top: 0;
}

legend{
    font-size: 1.9em;
    margin-bottom: 20px;
    color: black;
    font-weight: bold;
    text-align: center;
    margin-top: 0;
}

input {
    width: 140%;
    padding: 10px;
    margin-top: 5px;
    border: 1px solid #ccc;
    border-radius: 72px;
    margin : 5px 0;
}

.input-group {
    margin: 5px 0;
    text-align: left;
}


label {
    display: block;
    font-weight: bold;
    text-align: left;
}

button {
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

button:hover {
    background: black;
}


</style>



