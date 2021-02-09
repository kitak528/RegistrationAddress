<?php
try {
    $db = new PDO('mysql:dbname=RegistrationAddress;host=localhost;charset=utf8mb4', 'root', '');
}   catch (PDOException $e) {
    echo "データベース接続エラー　：".$e->getMessage();
}
?>