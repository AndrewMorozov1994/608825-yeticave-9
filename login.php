<?php

require_once('helpers.php');
require_once('functions.php');

$link = create_link();
$categories = get_categories($link);

$content = include_template("login.php", [
    'categories' => $categories,
]);
$title = 'Вход';
$layout = get_layout($content, $title, $is_auth, $user_name, $categories);
print($layout);

?>
