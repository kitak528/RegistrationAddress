<?php
require("./general.php");
session_start();

$errorMessage = "";
if(isset($_POST["login"])) {
    if (!empty($_POST["loginId"]) && !empty($_POST["password"])) {
        $loginId = htmlspecialchars($_POST['loginId'], ENT_QUOTES);
        $pass = htmlspecialchars($_POST['password'], ENT_QUOTES);

        try {
            $sql = "SELECT * FROM UserInfo WHERE loginId = :loginId";
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':loginId', $loginId);
            $stmt->execute();
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
            error_log("[". date('Y-m-d H:i:s') . "] ".$e->getMessage(), 3, "./errorLog.log");
            die();
        }

         if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            if (password_verify($_POST['password'], $row['password'])) {
               // ※セッションに値を保存
                $_SESSION["loginId"] = $row['loginId'];
                //※main.phpにリダイレクト
                header("Location: doneLogin.php");
                exit;
            } else {
                //※パスワードが違ってた時の処理
                echo $error['password'] = "duplicate";
            }
        } else {
            //※ログイン名がなかった時の処理
            echo $error['password'] = "blank";
        }
    }

    if (!empty($_POST)) {
        /* 入力情報の不備を検知 */
        if ($_POST['loginId'] === "") {
            $error['loginId'] = "empty";
        }
        if ($_POST['password'] === "") {
            $error['password'] = "empty";
        }
    }
}
?>

<!DOCTYPE html>

<html lang="en">
<head>
  <title>RegistrationAddress</title>
  <link rel="stylesheet" href="stylesheet.css">
</head>
<?php
include('./header.php');
?>

<body>
    <div class="content">
        <form action="#" method="post">
            <h1>ログイン</h1><br>
            
            <div>
            <?php if (!empty($error["password"]) && $error['password'] === 'blank'): ?>
                <p class="error">ユーザー名かパスワードが間違っています。</p>
            <?php endif ?>
            </div>

            <div>
	            <label for="loginId">ログインID:</label><br>
	            <input id="loginId" type="text" name="loginId">
                <?php if (!empty($error["loginId"]) && $error['loginId'] === 'empty'): ?>
                    <p class="error">ログインIDを入力してください</p>
                <?php endif ?>
            </div><br>

            <div>
                <label for="password"> パスワード：</label><br>
	            <input id="password" type="text" name="password"></p>
                <?php if (!empty($error["password"]) && $error['password'] === 'empty'): ?>
                    <p class="error">パスワードを入力してください</p>
                <?php elseif (!empty($error["password"]) && $error['password'] === 'duplicate'): ?>
                    <p class="error">パスワードが間違っています</p>
                <?php endif ?>
            </div>

            <div class="control">
                <button type="submit" class="btn" name="login">ログインする</button>
            </div>
            
        </form>
    </div>
</body>
</html>