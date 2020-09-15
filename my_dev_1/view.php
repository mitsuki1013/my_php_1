<?php
session_start();
require('db_connect.php');

// ログイン情報の抜き出し
if (isset($_SESSION['id'])) {
    $members = $db->prepare('SELECT * FROM members WHERE id=?');
    $members->execute(array($_SESSION['id']));
    $member = $members->fetch();

    // 投稿内容と、リプライ内容の抜き出し
    if (!empty($_REQUEST['id'])) {
        $posts = $db->prepare('SELECT m.name, m.picture, p.* FROM members m, posts p WHERE m.id=p.member_id AND p.id=?');
        $posts->execute(array($_REQUEST['id']));
        $post = $posts->fetch();

        $reply = $db->prepare('SELECT r.*, m.name, m.picture FROM reply r, members m, posts p WHERE r.reply_id=p.id AND r.reply_member_id=m.id AND p.id=?');
        $reply->execute(array(
        $_REQUEST['id']
        ));
    } else {
        header('Location: index.php');
        exit();
    }

} else {
    header('Location: login.php');
    exit();
}

// リプライの挿入
if (!empty($_POST)) {

    if ($_POST['reply'] !== '') {
        $reply = $db->prepare('INSERT INTO reply SET reply_message=?, reply_id=?, reply_member_id=?, created=NOW()');
        $reply->execute(array(
            $_POST['reply'],
            $_REQUEST['id'],
            $member['id']
        ));
        
        header('Location: view.php?id=' . $_REQUEST['id']);
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
    <header class="header">
        <div class="container header_container main_header">
            <div class="header_top">
                <h1 class="header_title">my_dev_1</h1>
                <h2 class="header_sub_title util-title">ようこそ <?php echo (htmlspecialchars($member['name'], ENT_QUOTES)); ?> さん</h2>
            </div>
            <div class="header_menu">
                <ul class="menu_list">
                    <li class="menu_item">
                        <a href="index.php" class="menu_link">ホームに戻る</a>
                    </li>
                    <li class="menu_item">
                        <a href="logout.php" class="menu_link">ログアウト</a>
                    </li>
                </ul>
            </div>
        </div>
    </header>
    <main class="main">
        <div class="tweet_contents view_contents">
            <div class="tweet_list view_tweet_list">
                <div class="tweet_item view_tweet_item">
                    <div class="tweet_head">
                        <div class="account_icon">
                            <img class="account_icon_image" src="./member_picture/<?php print(htmlspecialchars($post['picture'])) ?>" alt="<?php print(htmlspecialchars($post['name'], ENT_QUOTES)) ?>">
                        </div>
                        <div class="account_name">
                            <p class="account_name_contents"><?php print(htmlspecialchars($post['name'], ENT_QUOTES)) ?></p>
                        </div>
                    </div>
                    <div class="tweet_body">
                        <div class="tweet_contents_message">
                            <p class="tweet_message_text"><?php print(htmlspecialchars($post['message'], ENT_QUOTES)) ?></p>
                        </div>
                    </div>
                    <div class="tweet_footer">
                        <div class="tweet_date">
                            <time><a href="#" class="tweet_date_link"><?php print(htmlspecialchars($post['created'], ENT_QUOTES)) ?></a></time>
                        </div>
                        <div class="twee_delete">
                            <?php if ($_SESSION['id'] === $post['member_id']): ?>
                            <a href="delete.php?id=<?php print(htmlspecialchars($post['id'], ENT_QUOTES)); ?>" class="tweet_delete_link">ツイートを削除する</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="tweet_list">
            <?php foreach ($reply as $re): ?>
            <div class="tweet_item">
                <div class="tweet_head">
                    <div class="account_icon">
                        <img class="account_icon_image" src="./member_picture/<?php print(htmlspecialchars($re['picture'],ENT_QUOTES)) ?>" alt="<?php print(htmlspecialchars($re['name'], ENT_QUOTES)) ?>">
                    </div>
                    <div class="account_name">
                        <p class="account_name_contents"><?php print(htmlspecialchars($re['name'], ENT_QUOTES)) ?></p>
                    </div>
                </div>
                <div class="tweet_body">
                    <div class="tweet_contents_message">
                        <p class="tweet_message_text"><?php print(htmlspecialchars($re['reply_message'], ENT_QUOTES)) ?></p>
                    </div>
                </div>
                <div class="tweet_footer">
                    <div class="tweet_date">
                        <time><a href="#" class="tweet_date_link"><?php print(htmlspecialchars($re['created'], ENT_QUOTES)) ?></a></time>
                    </div>
                    <div class="twee_delete">
                        <?php if ($_SESSION['id'] === $re['reply_member_id']): ?>
                        <a href="delete.php?reply=<?php print(htmlspecialchars($re['id'], ENT_QUOTES)); ?>" class="tweet_delete_link">返信を削除する</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </main>
    <div class="reply">
        <form action="" class="reply_form" method="POST">
            <input class="reply_input" type="text" name="reply" placeholder="<?php print(htmlspecialchars($post['name'], ENT_QUOTES)) ?>さんに返信する" value="<?php print(htmlspecialchars($_POST['reply'], ENT_QUOTES)) ?>">
            <input class="reply_submit" type="submit" value="送信する">
        </form>
    </div>
</body>
</html>