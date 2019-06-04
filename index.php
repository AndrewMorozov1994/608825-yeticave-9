<?php

require_once('helpers.php');
require_once('functions.php');
require 'getwinner.php';
session_start();

$user_name = set_user(); // укажите здесь ваше имя
$link = create_link();

$curent_page = $_GET['page'] ?? 1;
$page_items = 6;
$offset = ($curent_page - 1) * $page_items;


    $categories = get_categories($link);
    $nav = include_template('navigation.php',[
        'categories' => $categories,
        'id' => '',
    ]);

    $sql = "SELECT l.name, l.id, l.start_price, l.lot_category, l.end_date, l.last_bet, l.img_url, c.name AS category_name
            FROM lot AS l
            INNER JOIN category AS c ON l.category = c.id
            WHERE l.end_date > NOW()
            ORDER BY l.date_creation DESC
            LIMIT $page_items
            OFFSET $offset";

    $lots = mysqli_fetch_all(db_fetch_data($link, $sql), MYSQLI_ASSOC);

    if ($lots) {
        $sql = "SELECT l.* FROM lot l
                WHERE l.end_date > NOW()";
        $res = db_fetch_data($link, $sql);

        $items_count = sizeof(mysqli_fetch_all($res, MYSQLI_ASSOC));
        $pages_count = ceil($items_count / $page_items);
        $pages = range(1, $pages_count);
    }


$main_class = "container";

$content = include_template('index.php', [
    'categories' => $categories,
    'lots' => $lots,
    'pages' => $pages,
    'pages_count' => $pages_count,
    'curent_page' => $curent_page,
    'link' => $link,
]);

$layout_content = include_template('layout.php', [
    'page_title' => 'Главная',
    'user_name' => $user_name,
    'page_content' => $content,
    'nav' => $nav,
    'main_class' => $main_class,
]);

print($layout_content);

?>
