<?php
session_start();
?>
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
        <img src="./images/resoLogoWhite.png" alt="Reso" id="logo">
        <a href="index.php">Accueil</a>
        <?php if (!isset($_SESSION['users'])): ?>
            <a href="?pages=login">Login</a>
        <?php endif; ?>
        <?php if (isset($_SESSION['users'])): ?>
            <a href="?pages=profile">Profile</a>
            <a href="?pages=logout">Logout</a>
        <?php endif; ?>
    </div>
    <main>

        <?php
        $connexion = mysqli_connect("localhost:25566", "root", "lecacaestcuit", "reso");
        if (!$connexion) {
            die("Connection failed: " . mysqli_connect_error());
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_POST['publication'])) {
                $content = $_POST['content'];
                $user_id = $_SESSION['users'];
                $postDate = date("Y-m-d H:i:s");

                $result = mysqli_query($connexion, "INSERT INTO post (content, user_id, date) VALUES ('$content', '$user_id', '$postDate')");

                if ($result) {
                    header("Location: " . $_SERVER['PHP_SELF']);
                    exit();
                } else {
                    echo "<p>Erreur lors de la publication du message.</p>";
                }
            } elseif (isset($_POST['post_id'])) {
                $post_id = $_POST['post_id'];

                $result = mysqli_query($connexion, "DELETE FROM post WHERE post_id = '$post_id' AND user_id = '$_SESSION[users]'");
                header("Location: " . $_SERVER['PHP_SELF']);
                exit();
            }
        }

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
        ?>
            <form action="" method="POST">
                <input type="hidden" name="publication" value="1">
                <input type="text" id="content" name="content" placeholder="Que se pastis ?" maxlength="100" required>
                <input type="submit" id="publish" value="Publier">
            </form>

            <div id="postsContainer">
                <?php
                $request = mysqli_query($connexion, "SELECT post.*, users.publicName 
                                                     FROM post 
                                                     JOIN users ON post.user_id = users.user_id 
                                                     ORDER BY post.post_id DESC");
                while ($posts = mysqli_fetch_assoc($request)) {
                    echo "<div class='message'>";
                    echo "<div class='title'>";
                    echo "<a class='name' href='?pages=otherProfile&user={$posts["user_id"]}'><strong>" . $posts["publicName"] . "</strong></a>";
                    echo "<p class='date'>" . $posts["date"] . "</p>";
                    echo "</div>";
                    echo "<p class='content'>" . $posts["content"] . "</p>";
                    echo "<a class='more' href='?pages=post&post={$posts["post_id"]}'>Voir plus...</a>";
                    if ($_SESSION['users'] == $posts['user_id']) {
                        echo "<form action='index.php' method='POST' style='display:inline;'>
                                <input type='hidden' name='post_id' value='{$posts["post_id"]}'>
                                <input class='delete' type='submit' value='Supprimer'>
                              </form>";
                    }
                    echo "</div>";
                }
                ?>
            </div>
        <?php
        }
        ?>
    </main>
</body>

</html>