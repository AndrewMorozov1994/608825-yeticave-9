<?php

require_once('helpers.php');
require_once('functions.php');
session_start();

$user_name = set_user(); // укажите здесь ваше имя

function getLotById($id) {
    $link = create_link();

    if (!$link) {
        print('Ошибка подключения: ' . mysqli_connect_error());
    }
    else {
        $sql = 'SELECT c.name, l.id, l.name, l.description, l.lot_category, l.start_price, l.step, l.img_url, l.end_date FROM lot l
                JOIN category c ON l.category = c.id
                WHERE l.id = '. $id .'';

        $lotLink = mysqli_fetch_assoc(db_fetch_data($link, $sql));

        $categories = get_categories($link);

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
    $link = create_link();
    $title = '404 Not found';
    $sql = 'SELECT * FROM lot';
    $lots = mysqli_fetch_all(db_fetch_data($link, $sql), MYSQLI_ASSOC);

    $content = include_template('404.php', [
        'categories' => $categories,
        'lots' => $lots,
    ]);
};

$layout = get_layout($content, $title, $user_name, $categories);
print($layout);

?>
