<?php
require 'db.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
    try {
        $stmt->execute(['username' => $username, 'password' => $password]);
        $message = "<div class='alert alert-success'>登録完了！ <a href='login.php'>ログインはこちら</a></div>";
    } catch (PDOException $e) {
        $message = "<div class='alert alert-danger'>エラー: " . $e->getMessage() . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>新規登録</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    <div class="card p-4">
        <h2 class="card-title">新規登録</h2>
        <?= $message ?>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">ユーザー名</label>
                <input type="text" name="username" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">パスワード</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-custom w-100">登録</button>
        </form>
        <div class="text-center mt-3">
            <a href="login.php">すでに登録済み？ログイン</a>
        </div>
    </div>
</div>

</body>
</html>
