<?php

require_once('helpers.php');
require_once('functions.php');

session_start();
$link = create_link();
$user_name = set_user();
$categories = get_categories($link);

$curent_page = $_GET['page'] ?? 1;
$page_items = 1;
$offset = ($curent_page - 1) * $page_items;

if(isset($_GET['category'])) {
    $category_id = (int)$_GET['category'];

    $sql = "SELECT l.* FROM lot l
            WHERE l.category = $category_id
            AND l.end_date > NOW()
            LIMIT $page_items
            OFFSET $offset";

    $all_lots = mysqli_fetch_all(db_fetch_data($link, $sql), MYSQLI_ASSOC);

    $nav = include_template('navigation.php',[
        'categories' => $categories,
        'id' => $category_id,
    ]);
};

if ($all_lots) {

    $sql = "SELECT l.* FROM lot l
            WHERE l.category = $category_id
            AND l.end_date > NOW()";

    $res = db_fetch_data($link, $sql);
    $items_count = sizeof(mysqli_fetch_all($res, MYSQLI_ASSOC));
    $pages_count = ceil($items_count / $page_items);
    $pages = range(1, $pages_count);

    $content = include_template('all-lots.php', [
        'lots' => $all_lots,
        'categories' => $categories,
        'id' => $category_id,
        'pages' => $pages,
        'pages_count' => $pages_count,
        'curent_page' => $curent_page,
    ]);

    $title = $all_lots[0]['lot_category'];
}
else {

    $content = include_template('all-lots.php', [
        'categories' => $categories,
        'id' => $category_id,
        'errors' => 'В данной категории лоты отсутствуют',
    ]);
}

$layout = get_layout($content, $title, $user_name, $nav);
print($layout);
?>
