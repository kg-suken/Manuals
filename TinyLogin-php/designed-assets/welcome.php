<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>マイページ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    <div class="card p-4">
        <h2 class="card-title">マイページ</h2>
        <p class="text-center">こんにちは <strong><?= htmlspecialchars($_SESSION['username']) ?></strong> さん</p>
        <div class="text-center">
            <a href="logout.php" class="btn btn-danger">ログアウト</a>
        </div>
    </div>
</div>

</body>
</html>
