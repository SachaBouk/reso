<form class="login" method="POST" action="?pages=login">
    <legend>SE CONNECTER</legend>
    <input type="email" name="email" placeholder="E-mail" required>
    <input type="password" name="password" placeholder="Mot de passe" required>
    <button type="submit">Se connecter</button>
    <p>je n'ai pas de compte? <a href="?pages=register">S'inscrire</a></p>
</form>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $connection = mysqli_connect("http://91.162.115.85:25565", "root", "lecacaestcuit", "reso");

    $email = $_POST['email'];
    $password = $_POST['password'];
    $request = mysqli_query($connection, "SELECT * FROM users WHERE mail = '$email'");

    if (mysqli_num_rows($request) > 0) {
        $_SESSION['users'] = $email;
        header('Location: ?');
    }
}
?>