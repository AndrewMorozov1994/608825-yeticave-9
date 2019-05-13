<?php

require_once('helpers.php');

$is_auth = rand(0, 1);

$user_name = 'Андрей'; // укажите здесь ваше имя

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
    $result;
    $stmt = db_get_prepare_stmt($link, $sql, $data);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);

    return $res;
};

function getLotById($id) {
    $link = mysqli_connect('localhost', 'root', "", 'yeticave');
    mysqli_set_charset($link, 'utf8');

    if (!$link) {
        print('Ошибка подключения: ' . mysqli_connect_error());
    }
    else {
        $sql = 'SELECT c.name, l.id, l.name, l.description, l.lot_category, l.start_price, l.step, l.img_url FROM lot l
                JOIN category c ON l.category = c.id
                WHERE l.id = '. $id .'';

        $lotLink = mysqli_fetch_assoc(db_fetch_data($link, $sql));

        $sql = 'SELECT * FROM category';
        $categories = mysqli_fetch_all(db_fetch_data($link, $sql), MYSQLI_ASSOC);

        return ['categories' => $categories,
                'lot' => $lotLink,
        ];
    }
}

if (isset($_GET['lot_id'])) {
    $id = (int) $_GET['lot_id'];
    $lot = getLotById($id)['lot'];
    $categories = getLotById($id)['categories'];
};

if ($lot) {
    $content = include_template('lot.php', [
        'lot' => $lot,
        'categories' => $categories,
    ]);

    $title = $lot['name'];

} else {
    header('HTTP/1.1 404 Not found');
    $title = '404 Not found';

    $content = include_template('404.php', [
        'categories' => $categories,
    ]);
};

$layout = include_template('layout.php', [
    'page_content' => $content,
    'page_title' => $title,
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'categories' => $categories,
]);

print($layout);

?>

