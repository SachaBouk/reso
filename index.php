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
        <a href="?pages=register">register</a>
        <?php
        session_start();

        if (isset($_SESSION['users'])) {
            echo $_SESSION['users'];
        } else {
            echo 'Invalide';
        }

        if (isset($_GET['pages'])) {
            $allowedPages = ['profile', 'login', 'logout', 'register'];
            $page = $_GET['pages'];

            if (in_array($page, $allowedPages)) {
                if ($page === "profile") {
                    include("contacts/" . $page . '.php');
                } elseif ($page === "login" || $page === "logout" || $page === "register") {
                    include("authentification/" . $page . '.php');
                }
            } else {
                echo "<h1>Page non autorisée</h1>";
            }
        } else {
            echo "<h1>Accueil</h1>";
        }
        ?>
    </main>
</body>

</html>