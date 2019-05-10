<?php

require_once('helpers.php');

$is_auth = rand(0, 1);

$user_name = 'Андрей'; // укажите здесь ваше имя

function format_price($input) {
    $output = ceil($input) ;
    $result ;

    if ($output < 1000) {
        $result = $output . '  &#8381' ;
    } else {
        $result = number_format($output, 0, '', ' ') . '  &#8381' ;
    }

    return $result ;
};

function end_time($end_date) {
    $delta = strtotime($end_date) - strtotime('now');

    $hours = floor($delta / 3600);
    $minutes = floor(($delta - $hours * 3600) / 60);

    return "{$hours} : {$minutes}";
};

function end_sale_time($end_date) {
    return $minutes = (strtotime($end_date) - strtotime('now')) / 60;
};

function db_fetch_data($link, $sql, $data = []) {
    $result = [];
    $stmt = db_get_prepare_stmt($link, $sql, $data);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    if ($res) {
    $result = mysqli_fetch_all($res, MYSQLI_ASSOC);
    }
    return $result;
};

$link = mysqli_connect("localhost", "root", "", "yeticave");

mysqli_set_charset($link, "utf8");

if ($link == false) {
    print("Ошибка подключения: " . mysqli_connect_error());
} else {
    // Link up;
    $sql = "SELECT * FROM category";
    $categories = db_fetch_data($link, $sql);

    $sql = "SELECT l.name, l.id, l.start_price, l.lot_category, l.img_url, c.name AS category_name
            FROM lot AS l
            INNER JOIN category AS c ON l.category = c.id
            WHERE l.end_date > NOW()
            ORDER BY l.date_creation DESC";
            
    $lots = db_fetch_data($link, $sql);
};

$main_class = "container";

$content = include_template('index.php', [
    'categories' => $categories,
    'lots' => $lots,
]);

$layout_content = include_template('layout.php', [
    'page_title' => 'Главная',
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'page_content' => $content,
    'categories' => $categories,
    'main_class' => $main_class,
]);

print($layout_content);

?>
