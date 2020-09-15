<?php
session_start();
require('db_connect.php');

$email = $_COOKIE['email'];

if (!empty($_POST)) {

    $email = $_POST['email'];

    if ($_POST['email'] !== '' && $_POST['password'] !== '') {
        $login = $db->prepare('SELECT * FROM members WHERE email=? AND password=?');
        $login->execute(array(
            $_POST['email'],
            sha1($_POST['password'])
        ));

        $member = $login->fetch();

        if ($member) {
            $_SESSION['id'] = $member['id'];
            $_SESSION['time'] = time();

            if ($_POST['save'] === 'on') {
                setcookie('email', $_POST['email'], time() + 60 * 60 * 24 * 14);
            }

            header('Location: index.php');
            exit();

        } else {
            $error['login'] = 'failed';
        }
        
    } else {
        $error['login'] = 'blank';
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>my_dev_1</title>
    <link rel="stylesheet" href="./css/reset.css">
    <link rel="stylesheet" href="./css/style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
</head>
<body>
    <header>
        <div class="container header_container">
            <h1 class="header_title">ログイン画面</h1>
            <h2 class="header_sub_title util-title">ログインする</h2>
        </div>
    </header>
    <main>
        <div class="login_inner">
            <div class="container">
                <p class="login_text">メールアドレスとパスワードを記入してログインしてください</p>
                <p class="login_text">入会手続きがまだの方はこちらから <a href="join/" class="login_link">入会手続きをする</a></p>
            </div>
        </div>
        <div class="join_wrapper">
            <form action="" class="join_form" method="POST">
                <dl class="join_list">
                    <div class="join_item">
                        <dt class="join_name">メールアドレス</dt>
                        <dd class="join_contents">
                            <input class="join_contents_input" type="email" name="email" value="<?php print(htmlspecialchars($email, ENT_QUOTES)); ?>">
                            <?php if ($error['login'] === 'blank'): ?>
                                <p class="error">メールアドレスとパスワードを記入してください</p>
                            <?php endif; ?>
                            <?php if ($error['login'] === 'failed'): ?>
                                <p class="error">ログインできませんでした。正しく記入してください</p>
                            <?php endif; ?>
                        </dd>
                    </div>
                    <div class="join_item">
                        <dt class="join_name">パスワード</dt>
                        <dd class="join_contents">
                            <input class="join_contents_input" type="password" name="password" value="<?php print(htmlspecialchars($_POST['password'], ENT_QUOTES)); ?>">
                        </dd>
                    </div>
                </dl><!-- /.join_contents_inner -->
                <div class="checkbox_wrapper">
                    <p class="checkbox">次回からは自動的にログインする</p><input type="checkbox" name="save" id="on">
                </div>
                <div class="submit_btn_wrapper">
                    <input class="submit_btn" type="submit" value="ログインする">
                </div>
            </form>

        </div><!-- /.join_contents -->
    </main>
</body>
</html>