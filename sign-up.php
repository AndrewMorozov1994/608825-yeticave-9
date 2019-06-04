<?php

require_once('helpers.php');
require_once('functions.php');
session_start();
check_session();

$link = create_link();
$categories = get_categories($link);
$user_name = set_user();

$nav = include_template('navigation.php',[
    'categories' => $categories,
    'id' => '',
]);

if ($_SERVER["REQUEST_METHOD"] === 'POST') {
    $form = $_POST;
    $required_fields = ['email', 'password', 'name', 'message'];
    $errors = [];

    foreach ($required_fields as $field) {
        if (isset($_POST[$field]) && trim($_POST[$field]) === "") {
            $errors[$field] = 'Заполните это поле';
        }
    }

    if (isset($_POST['email'])) {
        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Указан некорректный адрес';
        };

        if (strlen($_POST['email']) > 128) {
            $errors['email'] = 'Допускается до 128 символов';
        }
    }

    if (isset($_POST['name'])) {
        if (strlen($_POST['name']) > 128) {
            $errors['name'] = 'Имя должно содержать до 128 символов';
        }
    }

    if (isset($_POST['message'])) {
        if (strlen($_POST['message']) > 255) {
            $errors['message'] = "Допускается не больше 255 символов";
        }
    }

    if(empty($errors)) {
        $email = mysqli_real_escape_string($link, $_POST['email']);
        $sql = "SELECT id FROM users WHERE email = '$email'";
        $res = mysqli_query($link, $sql);

        if(mysqli_num_rows($res) > 0) {
            $errors['email'] = 'Пользователь с указанным адресом уже существует';
        }
        else {
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

            $sql = 'INSERT INTO users (name, password, email, contacts, avatar)
                    VALUES (?, ?, ?, ?, ?)';
            $stmt = db_get_prepare_stmt($link, $sql, [
                $_POST['name'],
                $password,
                $_POST['email'],
                $_POST['message'],
                NULL,
            ]);

            $res = mysqli_stmt_execute($stmt);
        };

        if ($res && empty($errors)) {
            header('Location: login.php');
            exit();
        }
        else {
            $content = include_template('sign-up.php', [
                'categories' => $categories,
                'errors' => $errors,
                'form' => $form,
            ]);
            $title = 'Error';
        };
    }
    else {
        $content = include_template('sign-up.php', [
            'categories' => $categories,
            'errors' => $errors,
            'form' => $form,
        ]);

        $title = 'Error';
    };

}
else {
    $content = include_template('sign-up.php', [
        'categories' => $categories,
    ]);

    $title = 'Регистрация';
}

$layout = get_layout($content, $title, $user_name, $nav);
print($layout);

?>
