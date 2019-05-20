<?php

require_once('helpers.php');
require_once('functions.php');
require 'getwinner.php';
session_start();

$user_name = set_user(); // укажите здесь ваше имя
$link = create_link();

if ($link == false) {
    print("Ошибка подключения: " . mysqli_connect_error());
} else {
    // Link up;
    $categories = get_categories($link);

    $sql = "SELECT l.name, l.id, l.start_price, l.lot_category, l.end_date,  l.img_url, c.name AS category_name
            FROM lot AS l
            INNER JOIN category AS c ON l.category = c.id
            WHERE l.end_date > NOW()
            ORDER BY l.date_creation DESC";

    $lots = mysqli_fetch_all(db_fetch_data($link, $sql), MYSQLI_ASSOC);
};

$main_class = "container";

$content = include_template('index.php', [
    'categories' => $categories,
    'lots' => $lots,
]);

$layout_content = include_template('layout.php', [
    'page_title' => 'Главная',
    'user_name' => $user_name,
    'page_content' => $content,
    'categories' => $categories,
    'main_class' => $main_class,
]);

print($layout_content);

?>
