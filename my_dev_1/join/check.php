<?php 
session_start();
require('../db_connect.php');

if (!isset($_SESSION['join'])) {
	header('Location: index.php');
	exit();
}

if (!empty($_POST)) {
    $statement = $db->prepare('INSERT INTO members SET name=?, email=?, password=?, picture=?, created=NOW()');
    $statement->execute(array(
        $_SESSION['join']['name'],
        $_SESSION['join']['email'],
        sha1($_SESSION['join']['password']),
        $_SESSION['join']['image']
    ));

    unset($_SESSION['join']);

    header('Location: thanks.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>会員登録</title>
    <link rel="stylesheet" href="../css/reset.css">
    <link rel="stylesheet" href="../css/style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
</head>
<body>
    <header>
        <div class="container header_container">
            <h1 class="header_title">my_dev_1</h1>
            <h2 class="header_sub_title util-title">会員登録</h2>
        </div>
    </header>
    <main>
        <div class="join_wrapper">
            <form action="" class="join_form" method="POST">
                <input type="hidden" name="action" value="submit">
                <dl class="join_list">
                    <div class="join_item">
                        <dt class="join_name">ニックネーム<span>必須</span></dt>
                        <dd class="join_contents">
                            <?php print(htmlspecialchars($_SESSION['join']['name'], ENT_QUOTES)); ?>
                        </dd>
                    </div>
                    <div class="join_item">
                        <dt class="join_name">メールアドレス<span>必須</span></dt>
                        <dd class="join_contents">
                        <?php print(htmlspecialchars($_SESSION['join']['email'], ENT_QUOTES)); ?>
                        </dd>
                    </div>
                    <div class="join_item">
                        <dt class="join_name">パスワード<span>必須</span></dt>
                        <dd class="join_contents">
                            <p>表示しません</p>
                        </dd>
                    </div>
                    <div class="join_item">
                        <dt class="join_name">画像</dt>
                        <dd class="join_contents">
                            <div class="image_wrapper">
                                <img style="width:70px;hight:70px;" src="../member_picture/<?php print(htmlspecialchars($_SESSION['join']['image'], ENT_QUOTES)); ?>" alt="">
                            </div>
                        </dd>
                    </div>
                </dl><!-- /.join_contents_inner -->
                <div class="submit_btn_wrapper">
                    <input type="submit" value="登録する">
                </div>
            </form>
            <a href="index.php?action=rewrite">書き直す</a>
        </div>
    </main>
    <script src="../js/script.js"></script>
</body>
</html>