<?php

require_once('globals.php');

const ACTION_SIGN_UP = 'sign_up';
const ACTION_SIGN_IN = 'sign_in';

session_start();

if (isset($_POST['action'])) {
    switch ($_POST['action']) {
        case ACTION_SIGN_UP:
            signUp();
            break;
        case ACTION_SIGN_IN:
            signIn();
            break;
    }
}

function signUp()
{
    unset($_SESSION['error']);
    if (empty($_POST['username']) || empty($_POST['password']) || empty($_POST['email'])) {
        $_SESSION['error'] = 'Имя пользователя, пароль или почта<br>не введены.';
        header("Location: logon.php?action=sign_up");
        exit();
    }

    $db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    $stmt = $db->prepare("select * from user where username = ?");
    $stmt->bind_param('s', $_POST['username']);
    $stmt->execute();
    $user = ($stmt->get_result())->fetch_assoc();
    if ($user) {
        $_SESSION['error'] = 'Пользователь с данным логином уже существует!';
        header("Location: logon.php?action=sign_up");
        exit();
    }

    if ($_POST['password'] != $_POST['password_confirm']) {
        $_SESSION['error'] = 'Пароли не совпадают.';
        exit();
    }

    $stmt = $db->prepare("insert into user (username, email, password, fullname) values (?, ?, ?, ?)");
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $stmt->bind_param('ssss',
        $_POST['username'],
        $_POST['email'],
        $password,
        $_POST['fullname']
    );
    $stmt->execute();

    if (!empty($stmt->insert_id)) {
        $_SESSION['message'] = "Пользователь с логином {$_POST['username']} успешно создан!";
        header("Location: index.php");
        exit();
    } else {
        $_SESSION['error'] = 'Возникла проблема при создании пользователя.<br>Пожалуйста, повторите попытку.';
        header("Location: logon.php?action=sign_up");
        exit();
    }
}

function signIn()
{
    unset($_SESSION['error']);
    if (empty($_POST['username']) && empty($_POST['password'])) {
        $_SESSION['error'] = 'Имя пользователя или пароль не введены.';
        header("Location: logon.php?action=sign_in");
        exit();
    }

    $db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    $stmt = $db->prepare("select * from user where username = ?");
    $stmt->bind_param('s', $_POST['username']);
    $stmt->execute();
    $user = ($stmt->get_result())->fetch_assoc();
    if (!$user) {
        $_SESSION['error'] = 'Пользователь с данным логином не найден.';
        header("Location: logon.php?action=sign_in");
        exit();
    }
    if (!password_verify($_POST['password'], $user['password'])) {
        $_SESSION['error'] = 'Введен неверный пароль.';
        header("Location: logon.php?action=sign_in");
        exit();
    }

    $_SESSION['loggedIn'] = true;
    $_SESSION['username'] = $user['username'];
    header("Location: index.php");
    exit();
}
