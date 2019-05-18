<?php

require_once('helpers.php');
require_once('functions.php');
session_start();

$user_name = set_user(); // укажите здесь ваше имя
$link = create_link();
$categories = get_categories($link);

if (!isset($_SESSION['user'])) {
    header('HTTP/1.0 403 Forbidden');
    $content = "<h2 style='text-align: center;'>Вы вошли как незарегистрированный пользователь, <br> пожалуйста, выполните авторизацию</h2>";
    $title = 'Error';
    $layout = get_layout($content, $title, $user_name, $categories);
    print($layout);
    exit();
};

$user_id = $_SESSION['user']['id'];

$sql = "SELECT b.*, l.name AS lot_name, l.img_url, l.end_date, l.lot_category, u.contacts FROM bet b
        JOIN lot l ON b.lot = l.id
        JOIN users u ON u.id = l.author
        WHERE b.user = $user_id
        ORDER BY l.end_date ASC";

$bets = mysqli_fetch_all(db_fetch_data($link, $sql), MYSQLI_ASSOC);

$title = 'Мои ставки';
$content = include_template('my-bets.php', [
    'categories' => $categories,
    'title' => $title,
    'bets' => $bets,
]);
$layout = get_layout($content, $title, $user_name, $categories);
print($layout);

?>
