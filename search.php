<?php

require_once('helpers.php');
require_once('functions.php');
session_start();

$user_name = set_user(); // укажите здесь ваше имя
$link = create_link();
$categories = get_categories($link);

$search = $_GET['search'] ?? '';
$curent_page = $_GET['page'] ?? 1;
$page_items = 3;
$offset = ($curent_page - 1) * $page_items;


if ($search) {
    $sql = "SELECT l.* FROM lot l
            WHERE MATCH (l.name, l.description) AGAINST(?)
            AND l.winner is NULL
            AND NOW() < l.end_date
            GROUP BY l.id DESC
            LIMIT $page_items
            OFFSET $offset";

    $stmt = db_get_prepare_stmt($link, $sql, [$search]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $lots = mysqli_fetch_all($result, MYSQLI_ASSOC);

    if ($lots) {

        $sql = "SELECT l.* FROM lot l
                WHERE MATCH (l.name, l.description) AGAINST(?)";
        $stmt = db_get_prepare_stmt($link, $sql, [$search]);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);

        $items_count = sizeof(mysqli_fetch_all($res, MYSQLI_ASSOC));
        $pages_count = ceil($items_count / $page_items);
        $pages = range(1, $pages_count);

        $title = 'Результаты поиска';
        $content = include_template('search.php', [
            'lot' => $lots,
            'categories' => $categories,
            'search' => $search,
            'pages' => $pages,
            'pages_count' => $pages_count,
            'curent_page' => $curent_page,
        ]);
    }
    else {
        $title = 'Поиск не дал результатов';
        $content = 'Ничего не найдено по вашему запросу';
    };
}
else {
    $title = 'Поиск';
    $content = 'Укажите ключевые слова';
};

$title = 'Результат поиска';

$layout = get_layout($content, $title, $user_name, $categories);
print($layout);

?>