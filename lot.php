<?php

require_once('helpers.php');
require_once('functions.php');
session_start();

$user_name = set_user(); // укажите здесь ваше имя
$link = create_link();

function getLotById($link, $id) {

    if (!$link) {
        print('Ошибка подключения: ' . mysqli_connect_error());
    }
    else {
        $sql = 'SELECT c.name, l.id, l.author, l.name, l.description, l.lot_category, l.start_price, l.step, l.img_url, l.end_date, l.winner FROM lot l
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
    $id = mysqli_real_escape_string($link, (int)$_GET['lot_id']);

    $sql = "SELECT b.price, b.date_creation, u.name, u.id FROM bet b
            JOIN users u ON b.user = u.id
            WHERE b.lot = '$id'
            ORDER BY b.price DESC";

    $active_bets = mysqli_fetch_all(db_fetch_data($link, $sql), MYSQLI_ASSOC);


    $lot = getLotById($link, $id)['lot'];
    $categories = getLotById($link, $id)['categories'];
};

$nav = include_template('navigation.php',[
    'categories' => $categories,
    'id' => '',
]);

if ($lot) {
    $content = include_template('lot.php', [
        'lot' => $lot,
        'categories' => $categories,
        'bets' => $active_bets,
    ]);

    $title = $lot['name'];

} else {
    header('HTTP/1.1 404 Not found');
    $title = '404 Страница не найдена';
    $sql = 'SELECT * FROM lot';
    $lots = mysqli_fetch_all(db_fetch_data($link, $sql), MYSQLI_ASSOC);

    $content = include_template('404.php', [
        'categories' => $categories,
        'lots' => $lots,
        'error' => 'Данной страницы не существует на сайте.',
    ]);
};

if (!empty($_POST)) {
    $user_id = $_SESSION['user']['id'];
    $errors = step_validity($lot, $user_id, $active_bets);

    if(!empty($errors)) {
        $content = include_template('lot.php', [
            'errors' => $errors,
            'categories' => $categories,
            'lot' => $lot,
            'bets' => $active_bets,
        ]);
        $title = 'Ввод ставки';
    }
    else {

        $sql = "INSERT INTO bet (price, user, lot)
                VALUES (?, ?, ?)";

        $stmt = db_get_prepare_stmt($link, $sql, [
            $_POST['cost'],
            $user_id,
            $id,
        ]);

        $res = mysqli_stmt_execute($stmt);
        if($res) {
            $new_price = mysqli_real_escape_string($link, $_POST['cost']);
            $sql = "UPDATE lot SET start_price = '$new_price' WHERE id = '$id'";
            $result = mysqli_query($link, $sql);
            if($result) {
                header("Location: lot.php?lot_id=" . $id);
                exit();
            }

        };
    }
};

$layout = get_layout($content, $title, $user_name, $nav);
print($layout);

?>
