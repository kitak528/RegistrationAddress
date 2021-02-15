<?php
require("./general.php");
session_start();

/* 会員登録の手続き以外のアクセスを飛ばす */
if (!isset($_SESSION['join'])) {
    header('Location: registrate.php');
    exit();
}

if (!empty($_POST['check'])) {
    // パスワードを暗号化
    $hash = password_hash($_SESSION['join']['password'], PASSWORD_BCRYPT);
 
    // 入力情報をデータベースに登録
    $statement = $db->prepare("INSERT INTO UserInfo SET username=?, loginId=?, password=?");
    $statement->execute(array(
        $_SESSION['join']['username'],
        $_SESSION['join']['loginId'],
        $hash
    ));
 
    unset($_SESSION['join']);   // セッションを破棄
    header('Location: doneRegistrate.php');   // doneRegistrate.phpへ移動
    exit();
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
        <form action="" method="POST">
            <input type="hidden" name="check" value="checked">
            <h1>入力情報の確認</h1>
            <p>ご入力情報に変更が必要な場合、下のボタンを押し、変更を行ってください。</p>
            <p>登録情報はあとから変更することもできます。</p>
            <?php if (!empty($error) && $error === "error"): ?>
                <p class="error">＊会員登録に失敗しました。</p>
            <?php endif ?>
            <hr>
 
            <div class="control">
                <p>お名前</p>
                <p><span class="fas fa-angle-double-right"></span> 
                <span class="check-info"><?php echo htmlspecialchars($_SESSION['join']['username'], ENT_QUOTES); ?></span></p>
            </div>
 
            <div class="control">
                <p>ログインID</p>
                <p><span class="fas fa-angle-double-right"></span> 
                <span class="check-info"><?php echo htmlspecialchars($_SESSION['join']['loginId'], ENT_QUOTES); ?></span></p>
            </div>
            
            <br>
            <a href="registrate.php" class="back-btn">変更する</a>
            <button type="submit" class="btn next-btn">登録する</button>
            <div class="clear"></div>
        </form>
    </div>
</body>