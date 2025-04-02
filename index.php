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
        <a href="index.php">Accueil</a>
        <a href="?pages=login">Login</a>
        <a href="?pages=profile">Profile</a>
        <a href="?pages=logout">Logout</a>
        <a href="?pages=register">register</a>
        <?php
        session_start();

        if (isset($_GET['pages'])) {
            $allowedPages = ['profile', 'login', 'logout', 'register', 'follower', 'follow', 'otherProfile'];
            $page = $_GET['pages'];

            if (in_array($page, $allowedPages)) {
                if ($page === "profile" || $page === "follower" || $page === "follow" || $page === "otherProfile") {
                    include("contacts/" . $page . '.php');
                }
                if ($page === "login" || $page === "logout" || $page === "register") {
                    include("authentification/" . $page . '.php');
                }
            } else {
                echo "<h1>Page non autorisée</h1>";
            }
        } else {
            $connexion = mysqli_connect("localhost:25566", "root", "lecacaestcuit", "reso");
            if (!$connexion) {
                die("Connection failed: " . mysqli_connect_error());
            } else {
                $request = mysqli_query($connexion, "SELECT * FROM post");
                while ($posts = mysqli_fetch_assoc($request)) {
                    echo "<br>" . $posts["content"] . " By : <a href='?pages=otherProfile&user={$posts["user_id"]}'>" . $posts["user_id"] . "</a>" . "<br>" . $posts["date"];
                }
            }
        }
        ?>
    </main>
    <form action="" method="POST">
        <input type="hidden" name="publication" value="1">
        <input type="text" id="content" name="content" placeholder="Que se pastis ?" required>
        <input type="submit" value="Publier">
    </form>
    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['publication'])) {
            $content = $_POST['content'];
            $user_id = $_SESSION['users'];
            $postDate = date("Y-m-d H:i");

            $connexion = mysqli_connect("localhost:25566", "root", "lecacaestcuit", "reso");
            $result = mysqli_query($connexion, "INSERT INTO post (content, user_id, date) VALUES ('$content', '$user_id', '$postDate')");
        }
    }
    ?>
</body>

</html>