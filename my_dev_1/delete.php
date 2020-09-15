<?php
session_start();

require('db_connect.php');

if (!empty($_REQUEST['reply'])) {
    if (isset($_SESSION['id'])) {
    $reply = $_REQUEST['reply'];

    $reply_messages = $db->prepare('SELECT * FROM reply WHERE id=?');
    $reply_messages->execute(array($reply));
    $reply_message = $reply_messages->fetch();

    if ($reply_message['reply_member_id'] = $_SESSION['id']) {
        $reply_del = $db->prepare('DELETE FROM reply WHERE id=?');
        $reply_del->execute(array($reply));

        header('Location: view.php?id=' . $reply_message['reply_id']);
        exit();
    }
    }
}

if (isset($_SESSION['id'])) {
    $id = $_REQUEST['id'];

    $messages = $db->prepare('SELECT * FROM posts WHERE id=?');
    $messages->execute(array($id));
    $message = $messages->fetch();

    if ($message['member_id'] = $_SESSION['id']) {
        $del = $db->prepare('DELETE FROM posts WHERE id=?');
        $del->execute(array($id));

        header('Location: index.php');
        exit();
    }
}







?>