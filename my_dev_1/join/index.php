<?php
require('../db_connect.php');
session_start();

// $_POST変数に値が入ったとき(入力内容を確認するを押下した時)
if (!empty($_POST)) {
	if ($_POST['name'] === '') {
		$error['name'] = 'blank';
	} 
	if ($_POST['email'] === '') {
		$error['email'] = 'blank';
	} 
	if (strlen($_POST['password']) < 4) {
		$error['password'] = 'length';
	} 
	if ($_POST['password'] === '') {
		$error['password'] = 'blank';
    } 

    $fileName = $_FILES['image']['name'];

    if (!empty($fileName)) {
        $ext = substr($fileName, -3);

        if ($ext != 'png' && $ext != 'jpg' && $ext!= 'gif') {
            $error['image'] = 'type';
        }
    }

    if (empty($error)) {
        $members = $db->prepare('SELECT COUNT(*) AS cnt FROM members WHERE email=?');
        $members->execute(array($_POST['email']));
        $records = $members->fetch();
        if ($records['cnt'] > 0) {
            $error['email'] = 'duplicate';
        }
    }

    if (empty($error)) {
    $image = date('YmdHis') . $_FILES['image']['name'];
    move_uploaded_file($_FILES['image']['tmp_name'], '../member_picture/' . $image);

    $_SESSION['join'] = $_POST;
    $_SESSION['join']['image'] = $image;

    header('Location: check.php');
    exit();
    }

}



if ($_REQUEST['action'] == 'rewrite' && isset($_SESSION['join'])) {
    $_POST = $_SESSION['join'];
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
            <p class="join_wrapper_text">次のフォームに必要事項を記入してください</p>

            <form action="" class="join_form" enctype="multipart/form-data" method="POST">
                <dl class="join_list">
                    <div class="join_item">
                        <dt class="join_name">ニックネーム<span>必須</span></dt>
                        <dd class="join_contents">
                            <input class="join_contents_input" type="text" name="name" value="<?php print(htmlspecialchars($_POST['name'], ENT_QUOTES)); ?>">
                            <?php if ($error['name'] === 'blank'): ?>
                            <p class="error">ニックネームを入力してください</p>
                            <?php endif; ?>
                        </dd>
                    </div>
                    <div class="join_item">
                        <dt class="join_name">メールアドレス<span>必須</span></dt>
                        <dd class="join_contents">
                            <input class="join_contents_input" type="email" name="email" value="<?php print(htmlspecialchars($_POST['email'], ENT_QUOTES)); ?>">
                            <?php if ($error['email'] === 'blank'): ?>
                                <p class="error">メールアドレスを入力してください</p>
                            <?php endif; ?>
                            <?php if ($error['email'] === 'duplicate'): ?>
                                <p class="error">このメールアドレスはすでに使用されています。</p>
                            <?php endif; ?>
                        </dd>
                    </div>
                    <div class="join_item">
                        <dt class="join_name">パスワード<span>必須</span></dt>
                        <dd class="join_contents">
                            <input class="join_contents_input" type="password" name="password" value="<?php print(htmlspecialchars($_POST['password'], ENT_QUOTES)); ?>">
                            <?php if ($error['password'] === 'blank'): ?>
                            <p class="error">パスワードを入力してください</p>
                            <?php endif; ?>
                            <?php if ($error['password'] === 'length'): ?>
                            <p class="error">パスワードは4文字以上で入力してください</p>
                            <?php endif; ?>
                        </dd>
                    </div>
                    <div class="join_item">
                        <dt class="join_name">画像</dt>
                        <dd class="join_contents">
                            <input type="file" name="image" value="test">
                            <?php if ($error['image'] === 'type'): ?>
                            <p class="error">画像は、拡張子が「jpg」または「gif」または「png」のものを使用してください</p>
                            <?php endif; ?>
                            <?php if (!empty($error)): ?>
                                <p class="error">恐れ入りますが、もう一度指定し直してください</p>
                            <?php endif; ?>
                        </dd>
                    </div>
                </dl><!-- /.join_contents_inner -->
                <div class="submit_btn_wrapper">
                    <input class="submit_btn" type="submit" value="入力内容を確認する">
                </div>
            </form>

        </div><!-- /.join_contents -->
    </main>
    <script src="../js/script.js"></script>
</body>
</html>