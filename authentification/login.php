<div class="global-content">
    <div class="login-container">
    <form class="login" method="POST" action="?pages=login">
        <legend>SE CONNECTER</legend>
        <input type="email" name="email" placeholder="E-mail" required>
        <input type="password" name="password" placeholder="Mot de passe" required>
        <button type="submit">Se connecter</button>
        <p>je n'ai pas de compte ? <a href="?pages=register"><br><br>S'inscrire</a></p>
    </form>
    </div>
</div>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $connection = mysqli_connect("localhost:25566", "root", "lecacaestcuit", "reso");

    $email = $_POST['email'];
    $password = $_POST['password'];
    
    $request = mysqli_query($connection, "SELECT * FROM users WHERE mail = '$email'");
    if (mysqli_num_rows($request) > 0) {
        $user = mysqli_fetch_assoc($request);
        if ($password === $user['password']) {
            $_SESSION['users'] = $user['user_id'];
            header('Location: ?');
        } else {
            echo "<p>Mot de passe invalide</p>";
        }
    } else {
        echo "<p>E-mail invalide</p>";
    }
}
?>


<style>

.global-content {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 90vh;
}

.login-container {
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
    font-size: 1.9em;
    margin-bottom: 30px;
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
    border-radius: 72px;
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
    margin-top: 20px;
}



</style>