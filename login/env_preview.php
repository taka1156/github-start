<?php

session_start();

require './tools/console_out.php';
require './tools/tools.php';
require './tools/env_parser.php';

// !!!!　練習以外では表示禁止(DB情報を漏らすことになる)
$result = env_parser(read_env_file());
console_log($result);
// !!!!　練習以外では表示禁止(DB情報を漏らすことになる)

?>


<!DOCTYPE HTML>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>DBの設定確認</title>
    <link rel="stylesheet" href="./css/style.css">
</head>

<body>
    <?php if ($result) : ?>
        <div class="form-frame">
            <?php foreach ($result as $key => $val) : ?>
                <p><?php echo h($key); ?> : <?php echo h($val); ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</body>

</html>
