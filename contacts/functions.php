<?php 

function isFollowing($follower_id, $followed_id, $connexion) {
    $sql = "SELECT COUNT(*) FROM follows WHERE follower_id = ? AND followed_id = ?";
    $stmt = mysqli_prepare($connexion, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $follower_id, $followed_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $count);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
    return $count > 0;
}

function followUser($follower_id, $followed_id, $connexion) {
    $sql = "INSERT INTO follows (follower_id, followed_id) VALUES (?, ?)";
    $stmt = mysqli_prepare($connexion, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $follower_id, $followed_id);
    $result = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $result;
}

function unfollowUser($follower_id, $followed_id, $connexion) {
    $sql = "DELETE FROM follows WHERE follower_id = ? AND followed_id = ?";
    $stmt = mysqli_prepare($connexion, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $follower_id, $followed_id);
    $result = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $result;
}


?>
