<?php
require("./general.php");
session_start();

if (!empty($_POST)) {
    /* 入力情報の不備を検知 */
    if ($_POST['loginId'] === "") {
        $error['loginId'] = "blank";
    }
    if ($_POST['password'] === "") {
        $error['password'] = "blank";
    }

    /* ログインIDの重複を検知 */
    if (!isset($error)) {
        $member = $db->prepare('SELECT COUNT(*) as cnt FROM UserInfo WHERE loginId=?');
        $member->execute(array(
            $_POST['loginId']
        ));
        $record = $member->fetch();
        if ($record['cnt'] > 0) {
            $error['loginId'] = 'duplicate';
        }
    }

    /* エラーがなければ次のページへ */
    if (!isset($error)) {
        $_SESSION['join'] = $_POST;   // フォームの内容をセッションで保存
        header('Location: checkRegistrate.php');   // checkRegistrate.phpへ移動
        exit();
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
            <h1>アカウント作成</h1><br>

            <div class="control">
	            <label for="username">お名前を入力してください:</label><br>
	            <input id="username" type="text" name="username">
            </div><br>

            <div>
	            <label for="loginId">ログインID(10文字以内):</label><br>
	            <input id="loginId" type="text" name="loginId">
                <?php if (!empty($error["loginId"]) && $error['loginId'] === 'blank'): ?>
                    <p class="error">IDを入力してください</p>
                <?php elseif (!empty($error[""]) && $error['loginId'] === 'duplicate'): ?>
                    <p class="error">このIDはすでに登録済みです</p>
                <?php endif ?>
            </div><br>

            <div>
                <label for="password"> パスワードを登録してください(10文字以内)：</label><br>
	            <input id="password" type="text" name="password"></p>
                <?php if (!empty($error["password"]) && $error['password'] === 'blank'): ?>
                    <p class="error">パスワードを入力してください</p>
                <?php endif ?><br>
            </div>

            <div class="control">
                <button type="submit" class="btn">確認する</button>
            </div>
        </form>
    </div>
</body>
</html>