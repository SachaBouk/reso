<form class="login" method="POST" action="?pages=login">
    <legend>SE CONNECTER</legend>
    <input type="email" name="email" placeholder="E-mail" required>
    <input type="password" name="password" placeholder="Mot de passe" required>
    <button type="submit">Se connecter</button>
    <p>je n'ai pas de compte? <a href="?pages=register">S'inscrire</a></p>
</form>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $connection = mysqli_connect("gobeliparichert.mysql.db", "gobeliparichert", "Campusdigital74", "gobeliparichert");

    $email = $_POST['email'];
    $password = $_POST['password'];
    
    $request = mysqli_query($connection, "SELECT * FROM rs_users WHERE mail = '$email'");
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