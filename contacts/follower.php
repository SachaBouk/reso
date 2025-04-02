<?php 
// session_start();
// require 'index.php'; // Connexion à la base de données

// if (!isset($_SESSION['user_id'])) {
//     header("Location: login.php");
//     exit();
// }

function getFollowers($user_id, $pdo) {
    $sql = "SELECT u.id, u.username FROM follows f 
            JOIN users u ON f.followerUser_id = u.id 
            WHERE f.followerUser_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$user_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$user_id = $_SESSION['user_id']; 
$followers = getFollowers($user_id, $pdo);
 
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Followers</title>
</head>
<body>
    <h2>Liste de mes followers</h2>
    <ul>
        <?php if (empty($followers)): ?>
            <p>Vous n'avez pas encore de followers.</p>
        <?php else: ?>
            <?php foreach ($followers as $follower): ?>
                <li>
                    <a href="profile.php?id=<?= htmlspecialchars($follower['id']) ?>">
                        <?= htmlspecialchars($follower['username']) ?>
                    </a>
                </li>
            <?php endforeach; ?>
        <?php endif; ?>
    </ul>
</body>
</html>
