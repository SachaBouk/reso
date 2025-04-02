<form class="login" method="POST" action="?pages=login">
    <legend>SE CONNECTER</legend>
    <input type="email" name="email" placeholder="E-mail" required>
    <input type="password" name="password" placeholder="Mot de passe" required>
    <button type="submit">Se connecter</button>
    <p>je n'ai pas de compte? <a href="?pages=register">S'inscrire</a></p>
</form>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $connection = mysqli_connect("localhost:25566", "root", "lecacaestcuit", "reso");

    if (!$connection) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE mail = ?";
    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        if (password_verify($password, $row['password'])) {
            $_SESSION['users'] = $email;
            header('Location: ?');
            exit;
        } else {
            echo "Mauvais mot de passe.";
        }
    } else {
        echo "Mauvais email.";
    }

    mysqli_stmt_close($stmt);
    mysqli_close($connection);
}
?>