<?php

require_once('helpers.php');
require_once('functions.php');

session_start();
check_session();

$user_name = set_user();
$link = create_link();
$categories = get_categories($link);

if (!empty($_POST)){
	$required = ['email', 'password'];
	$errors = [];
	foreach ($required as $field) {
	    if (empty($_POST[$field])) {
	        $errors[$field] = 'Это поле надо заполнить';
        }
    };

    $email = mysqli_real_escape_string($link, $_POST['email']);
    $sql = "SELECT * FROM users u
            WHERE u.email = '$email'";
    $res = mysqli_query($link, $sql);
    $user = $res ? mysqli_fetch_array($res, MYSQLI_ASSOC) : null;

    if (!sizeof($errors) && $user) {
		if (password_verify($_POST['password'], $user['password'])) {
			$_SESSION['user'] = $user;
		}
		else {
			$errors['password'] = 'Неверный пароль';
		}
	}
	else {
		$errors['email'] = 'Такой пользователь не найден';
    };

    if (sizeof($errors)) {
		$content = include_template('login.php', [
            'errors' => $errors,
            'categories' => $categories,
        ]);
        $title = "Вход";
        $layout = get_layout($content, $title, $user_name, $categories);
        print($layout);
    }
    else {
        $_SESSION['user'] = $user;
		header('Location: index.php');
		exit();
	};

}
else {
    $content = include_template('login.php', [
        'categories' => $categories,
    ]);

    $title = 'Вход';
    $layout = get_layout($content, $title, $user_name, $categories);
    print($layout);
};
?>
