<?php

ini_set('display_errors', true);
error_reporting(E_ALL);

//セッションスタート。
session_start();

//DBとClassの読み込み。
require 'database.php';
require dirname(__FILE__) . '/add_class.php';

$err=[]; //エラーメッセージ格納配列。

//ログインインスタンス生成。
$user1=new Login();
$user1->firstLogin(); //新規登録用メソッド。
    
//各ゲッターを変数に代入。
    $mail = $user1->getMail();
    $password = $user1->getPassword();
    $password_conf = $user1->getPassword_conf();

    //メアド、パスワードの入力チェック。未入力ならそれぞれエラーが代入される。
    $err['mail'] = $user1->checkInput($mail, 'メールアドレス');
    $err['pass'] = $user1->checkInput($password, 'パスワード');

    //確認用パスワードが一致しているか調べ、異なればエラーが変数に代入される。
    $err['same'] = $user1->samePass($password, $password_conf);

    //メアドの正規表現。誤っていればエラーが返される。
    $err['mail_check'] = $user1->mailCheck($mail);
    //エラーが飛ばされていれば、それを配列から取り出して表示。
    if (isset($err)) {
        $user1->errCheck($err);
    } elseif (count($err)===0) {
        // DB接続
        $pdo = connect();

        // ステートメント
        $stmt = $pdo->prepare('INSERT INTO `User` (`id`, `user_name`, `password`) VALUES (null, ?, ?)');

        // パラメータ設定
        $params = [];
        $params[] = $mail;
        $params[] = password_hash($password, PASSWORD_DEFAULT);

        // SQL実行
        $success = $stmt->execute($params);
    
}
?>
<!DOCTYPE HTML>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title></title>
        <style type="text/css">
            .error {
                color: red;
            }
        </style>
    </head>
    <body>
        <?php if (count($err) > 0) : ?>
            <?php foreach ($err as $e): ?>
                <p class="error"><?php echo h($e); ?></p>
            <?php endforeach; ?>
        <?php endif; ?>

        <?php if (isset($success) && $success) : ?>
            <p>登録に成功しました。</p>
            <p><a href="index.php">こちらからログインしてください。</a></p>
        <?php else: ?>
            <form action="" method="post">
                <p>
                    <label for="user_name">ユーザー名</label>
                    <input id="user_id" name="mail" type="text" />
                </p>
                <p>
                    <label for="">パスワード</label>
                    <input id="password" name="password" type="password" />
                </p>
                <p>
                    <label for="">確認用パスワード</label>
                    <input id="password_conf" name="password_conf" type="password" />
                </p>
                <p>
                    <button type="submit">ログイン</button>
                </p>
                <p>
                    <a href="adduser.php">新規ユーザー登録</a>
                </p>
            </form>
        <?php endif; ?>
    </body>
</html>