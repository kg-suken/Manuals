<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

echo "こんにちは " . htmlspecialchars($_SESSION['username']) . " さん";
?>

<br><a href="logout.php">ログアウト</a>
