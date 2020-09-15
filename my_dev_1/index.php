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

$posts = $db->query('SELECT m.name, m.picture, p.* FROM members m, posts p WHERE m.id=p.member_id ORDER BY p.created DESC');

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
                        <a href="input.php" class="menu_link">ツイートする</a>
                    </li>
                    <li class="menu_item">
                        <a href="logout.php" class="menu_link">ログアウト</a>
                    </li>
                </ul>
            </div>
        </div>
    </header>
    <main class="main">
        <div class="tweet_contents">
            <div class="tweet_list">
                <?php foreach ($posts as $post): ?>
                <div class="tweet_item">
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
                            <time><a href="view.php?id=<?php print(htmlspecialchars($post['id'], ENT_QUOTES)); ?>" class="tweet_date_link"><?php print(htmlspecialchars($post['created'], ENT_QUOTES)) ?></a></time>
                        </div>
                        <div class="twee_delete">
                            <?php if ($_SESSION['id'] === $post['member_id']): ?>
                            <a href="delete.php?id=<?php print(htmlspecialchars($post['id'], ENT_QUOTES)); ?>" class="tweet_delete_link">ツイートを削除する</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </main>
</body>
</html>