<?php

require_once('helpers.php');
require_once('functions.php');

$link = create_link();
$categories = get_categories($link);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $form = $_POST;
    $required_fields = ["email", "password", "name", "message"];
    $errors = [];

    foreach ($required_fields as $field) {
        if (empty($form[$field])) {
            $errors[$field] = 'Заполните это поле';
        };
    };

    if (!filter_var($form["email"], FILTER_VALIDATE_EMAIL)) {
        $errors["email"] = "Указан некорректный адрес";
    };

    if(empty($errors)) {
        $email = mysqli_real_escape_string($link, $form['email']);
        $sql = "SELECT id FROM users WHERE email = '$email'";
        $res = mysqli_query($link, $sql);

        if(mysqli_num_rows($res) > 0) {
            $errors["email"] = 'Пользователь с указанным адресом уже существует';
        }
        else {
            $password = password_hash($form["password"], PASSWORD_DEFAULT);

            $sql = "INSERT INTO users (name, password, email, contacts, avatar)
                    VALUES (?, ?, ?, ?, ?)";
            $stmt = db_get_prepare_stmt($link, $sql, [
                $form["name"],
                $password,
                $form["email"],
                $form["message"],
                NULL,
            ]);

            $res = mysqli_stmt_execute($stmt);
        };

        if ($res && empty($errors)) {
            header("Location: login.php");
            exit();
        }
        else {
            $content = include_template("sign-up.php", [
                'categories' => $categories,
                'errors' => $errors,
                'form' => $form,
            ]);
            $title = 'Error';
            $layout = get_layout($content, $title, $is_auth, $user_name, $categories);
            print($layout);
        };
    }
    else {
        $content = include_template("sign-up.php", [
            'categories' => $categories,
            'errors' => $errors,
            'form' => $form,
        ]);

        $title = 'Error';
        $layout = get_layout($content, $title, $is_auth, $user_name, $categories);
        print($layout);
    };

}
else {
    $content = include_template("sign-up.php", [
        'categories' => $categories,
    ]);

    $title = 'Регистрация';
    $layout = get_layout($content, $title, $is_auth, $user_name, $categories);
    print($layout);
};

?>
