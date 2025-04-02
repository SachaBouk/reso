<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Accueil</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div id="barreLateral">
        <img src="./images/X.png" alt="X" id="logo">
    </div>
    <main>
        <a href="?pages=login">Login</a>
        <a href="?pages=profile">Profile</a>
        <a href="?pages=logout">Logout</a>
        <a href="?pages=register">Register</a>

        <?php
        session_start();
        if (isset($_SESSION['users'])) {
            echo '<form action="" method="POST" class="post-form">
                    <textarea name="message" placeholder="Quoi de neuf ?" required></textarea>
                    <button type="submit">Poster</button>
                  </form>';
        } else {
            echo "<p>Veuillez vous connecter pour poster un message.</p>";
        }

        $connection = mysqli_connect("localhost", "root", "lecacaestcuit", "reso");
        if (!$connection) {
            die("Erreur de connexion : " . mysqli_connect_error());
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['message'])) {
            $message = htmlspecialchars($_POST['message']);
            if (isset($_SESSION['user_id'])) {
                $user_id = $_SESSION['user_id'];
                $query = "INSERT INTO post (user_id, message) VALUES ('$user_id', '$message')";
                if (mysqli_query($connection, $query)) {
                    echo "<p>Message posté avec succès !</p>";
                } else {
                    echo "<p>Erreur lors de l'envoi du message : " . mysqli_error($connection) . "</p>";
                }
            } else {
                echo "<p>Erreur : utilisateur non connecté.</p>";
            }
        }

        $result = mysqli_query($connection, "SELECT * FROM post ORDER BY creation_date DESC");
        if (mysqli_num_rows($result) > 0) {
            echo '<div class="posts">';
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<div class="post">';
                echo '<p><strong>Utilisateur ' . $row['user_id'] . ' :</strong></p>';
                echo '<p>' . htmlspecialchars($row['message']) . '</p>';
                echo '<p><small>Posté le ' . $row['creation_date'] . '</small></p>';
                echo '</div>';
            }
            echo '</div>';
        } else {
            echo "<p>Aucun message pour le moment.</p>";
        }

        mysqli_close($connection);
        ?>
    </main>
</body>

</html>