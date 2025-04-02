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
            $allowedPages = ['profile', 'login', 'logout', 'register', 'follower', 'follow', 'otherProfile', 'post'];
            $page = $_GET['pages'];

            if (in_array($page, $allowedPages)) {
                if ($page === "profile" || $page === "follower" || $page === "follow" || $page === "otherProfile") {
                    include("contacts/" . $page . '.php');
                }
                if ($page === "login" || $page === "logout" || $page === "register") {
                    include("authentification/" . $page . '.php');
                }
                if ($page === "post") {
                    include("messages/" . $page . '.php');
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
                    echo "<div class='message'>";
                        echo "<br>" . $posts["content"] . " By : <a href='?pages=otherProfile&user={$posts["user_id"]}'>" . $posts["user_id"] . "</a>" . "<br>" . $posts["date"];
                        echo "<br><a href='?pages=post&post={$posts["post_id"]}'>Show more</a>";
                        if ($_SESSION['users'] == $posts['user_id']) {
                            echo "<form action='index.php' method='POST' style='display:inline;'>
                                    <input type='hidden' name='post_id' value='{$posts["post_id"]}'>
                                    <input type='submit' value='Supprimer'>
                                </form>";
                        }
                    echo "</div>";
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
    $connexion = mysqli_connect("localhost:25566", "root", "lecacaestcuit", "reso");
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['publication'])) {
            $content = $_POST['content'];
            $user_id = $_SESSION['users'];
            $postDate = date("Y-m-d H:i");

            $result = mysqli_query($connexion, "INSERT INTO post (content, user_id, date) VALUES ('$content', '$user_id', '$postDate')");
        } elseif (isset($_POST['post_id'])) {
            $post_id = $_POST['post_id'];

            $result = mysqli_query($connexion, "DELETE FROM post WHERE post_id = '$post_id' AND user_id = '$_SESSION[users]'");
        }
    }
    ?>
</body>

</html>