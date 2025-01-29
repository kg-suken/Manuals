<?php
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
    try {
        $stmt->execute(['username' => $username, 'password' => $password]);
        echo "登録完了！<a href='login.php'>ログインページへ</a>";
    } catch (PDOException $e) {
        echo "エラー: " . $e->getMessage();
    }
}
?>

<form method="POST">
    ユーザー名: <input type="text" name="username" required><br>
    パスワード: <input type="password" name="password" required><br>
    <input type="submit" value="登録">
</form>
