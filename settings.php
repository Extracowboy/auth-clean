<?php

include('globals.php');

session_start();

if (!loggedIn()) {
    header("Location: index.php");
    exit();
}

$fullname = '';
$error = '';
$success = '';

$db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
$stmt = $db->prepare("select * from user where username = ?");
$stmt->bind_param('s', $_SESSION['username']);
$stmt->execute();
$user = ($stmt->get_result())->fetch_assoc();
if ($user) {
    if (!empty($_POST)) {
        $updated = false;
        if ($_POST['fullname'] != $user['fullname']) {
            $stmt = $db->prepare("update user set fullname = ? where username = ?");
            $stmt->bind_param('ss',
                $_POST['fullname'],
                $user['username']
            );
            $stmt->execute();
            $updated = true;
        }
        if (!empty($_POST['password']) && !password_verify($_POST['password'], $user['password'])) {
            $stmt = $db->prepare("update user set password = ? where username = ?");
            $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
            $stmt->bind_param('ss',
                $password,
                $user['username']
            );
            $stmt->execute();
            $updated = true;
        }
        $success = $updated ? 'Данные успешно обновлены.' : 'Данные не изменялись.';
        $fullname = $_POST['fullname'];
    } else {
        $fullname = $user['fullname'];
    }
}

?>

<script>
    function updateProfile() {
        var $password = document.getElementById('id_password').value;
        if ($password.length !== 0 && $password !== document.getElementById('id_password_confirm').value) {
            setError('Пароли не совпадают.');
            return;
        }
        document.forms['settings'].submit();
    }

    function setError(text) {
        document.getElementById('id_error').innerHTML = text;
    }
</script>

<link rel="stylesheet" type="text/css" href="style.css">

<form name="settings" action="" method="POST">
    <div class="center_block">
        <div class="nav-block">
            <a href="index.php" style="text-decoration: none;">< назад</a>
        </div>
        <div>
            <span>Параметры учетной записи</span>
        </div>
        <div class="settings_row">
            <p id="id_error" class="error"><?= $error ?></p>
            <p id="id_success" class="success"><?= $success ?></p>
        </div>
        <div class="settings_row">
            <div class="input_title">Пароль</div>
            <input type="password" name="password" id="id_password" autocomplete="off">
        </div>
        <div class="settings_row">
            <div class="input_title">Повторите пароль</div>
            <input type="password" name="password_confirm" id="id_password_confirm" autocomplete="off">
        </div>
        <div class="settings_row">
            <div class="input_title">ФИО</div>
            <input type="text" name="fullname" id="id_fullname" value="<?= $fullname ?>">
        </div>
        <div class="settings_row">
            <button type="button" onclick="updateProfile()">Сохранить</button>
        </div>
</form>
