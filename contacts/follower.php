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
