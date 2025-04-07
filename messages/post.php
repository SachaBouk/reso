<?php
$post_id = $_GET["post"];

$connexion = mysqli_connect("localhost:25566", "root", "lecacaestcuit", "reso");
if (!$connexion) {
    die("Connection failed: " . mysqli_connect_error());
} else {
    $request = mysqli_query($connexion, "SELECT * FROM post 
                                                JOIN users ON post.user_id = users.user_id
                                                WHERE post_id = '$post_id'
                                                ");
    while ($posts = mysqli_fetch_assoc($request)) {
        echo "<div class='message'>";
        echo "<div class='title'>";
        echo "<a class='name' href='?pages=otherProfile&user={$posts["user_id"]}'><strong>" . $posts["publicName"] . "</strong></a>";
        echo "<p class='date'>" . $posts["date"] . "</p>";
        echo "</div>";
        echo "<p class='content'>" . $posts["content"] . "</p>";
        echo "</div>";
    }
}
?>

<form method="POST" action="">
    <input type="hidden" name="reply" value="reply">
    <input type="text" name="replyContent" id="replyContent" placeholder="Write your reply" required>
    <input type="submit" name="replyButton" id="reply" value="Reply">
</form>
<?php
$connexion = mysqli_connect("localhost:25566", "root", "lecacaestcuit", "reso");
if (!$connexion) {
    die("Connection failed: " . mysqli_connect_error());
} else {
    $request = mysqli_query($connexion, "SELECT reply.*, users.publicName 
                                         FROM reply 
                                         JOIN users ON reply.user_id = users.user_id
                                         WHERE post_id = '$post_id'
                                         ORDER BY reply.reply_id DESC");
    while ($posts = mysqli_fetch_assoc($request)) {
        echo "<div class='messageReply'>";
        echo "<div class='title'>";
        echo "<a class='name' href='?pages=otherProfile&user={$posts["user_id"]}'><strong>" . $posts["publicName"] . "</strong></a>";
        echo "<p class='date'>" . $posts["date"] . "</p>";
        echo "</div>";
        echo "<p class='content'>" . $posts["content"] . "</p>";
        echo "</div>";
    }
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['reply'])) {
        $content = $_POST['replyContent'];
        $user_id = $_SESSION['users'];
        $post_id = $_GET["post"];
        $postDate = date("Y-m-d H:i");

        $connexion = mysqli_connect("localhost:25566", "root", "lecacaestcuit", "reso");
        $result = mysqli_query($connexion, "INSERT INTO reply (content, user_id, post_id, date) VALUES ('$content', '$user_id','$post_id', '$postDate')");

        if ($result) {
            header("Location: " . $_SERVER['REQUEST_URI']);
            exit();
        } else {
            echo "<p>Erreur lors de la publication du message.</p>";
        }
    }
}
?>
