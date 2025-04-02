<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Accueil</title>
</head>
<body>
    <a href="?pages=login">Login</a>
    <a href="?pages=profile">Profile</a>
    <a href="?pages=logout">Logout</a>
    <?php
    if(isset ($_GET['pages'])) {
        if ($_GET['pages'] == "profile") {
            include ("contacts/".$_GET['pages'].'.php');
        }
        if ($_GET['pages'] == "logout" || $_GET['pages'] == "login") {
            include ("authentification/".$_GET['pages'].'.php');
        }
    }
    else {
        echo "<h1>Accueil</h1>";
    }
        ?>
</body>
</html>