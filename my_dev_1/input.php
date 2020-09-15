<?php 
session_start();

require('db_connect.php');

$members = $db->prepare('SELECT name FROM members WHERE id=?');
$members->execute(array($_SESSION['id']));
$member = $members->fetch();

if (isset($_SESSION['id']) && $_SESSION['time'] + 3600 > time()) {
    $_SESSION['time'] + time();

    $members = $db->prepare('SELECT * FROM members WHERE id=?');
    $members->execute(array($_SESSION['id']));
    $member = $members->fetch();
} else {
    header('Location: login.php');
    exit();
}

if (!empty($_POST)) {

    if ($_POST['message'] !== '') {
        $posts = $db->prepare('INSERT INTO posts SET member_id=?, message=?, created=NOW()');
        $posts->execute(array(
            $member['id'],
            $_POST['message']
        ));

        header('Location: index.php');
        exit();
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
        <div class="container header_container main_header">
            <div class="header_top">
                <h1 class="header_title">my_dev_1</h1>
                <h2 class="header_sub_title util-title">ようこそ <?php echo (htmlspecialchars($member['name'], ENT_QUOTES)); ?> さん</h2>
            </div>
            <div class="header_menu">
                <ul class="menu_list">
                    <li class="menu_item">
                        <a href="index.php" class="menu_link">ホームへ戻る</a>
                    </li>
                    <li class="menu_item">
                        <a href="logout.php" class="menu_link">ログアウト</a>
                    </li>
                </ul>
            </div>
        </div>
    </header>
    <main>
        <div class="input_contents">
            <form action="" class="input_from" method="POST">
                <textarea name="message" id="" cols="30" rows="10">
                    <?php print(htmlspecialchars($_POST['message'], ENT_QUOTES)); ?>
                </textarea>
                <div class="submit_wrapper">
                    <input class="input_submit" type="submit" value="ツイートする">
                </div>
            </form>
        </div>
    </main>