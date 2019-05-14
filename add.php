<?php

require_once('helpers.php');
require_once('functions.php');

$is_auth = rand(0, 1);

$user_name = 'Андрей'; // укажите здесь ваше имя

$link = create_link();
$categories = get_categories($link);

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $lot = $_POST;

    $errors = lot_validity($lot);

    if (!empty($_FILES["lot-img"]["name"])) {

        $tmp_name = $_FILES["lot-img"]["tmp_name"];
        $path = $_FILES["lot-img"]["name"];
        $file_type = mime_content_type($tmp_name);

        if ($file_type !== "image/png" && $file_type !== "image/jpeg"){
            $errors["lot-img"] = "Изображение должно быть в формате png или jpeg";
        } else {
            move_uploaded_file($tmp_name, 'uploads/' . $path);
            $lot["lot-img"] = "uploads/" . $path;
        }
    }  else {
           $errors["lot-img"] = 'Вы не загрузили файл';
    };

    if (count($errors)) {
        $content = include_template('add.php', [
            'categories' => $categories,
            'errors' => $errors,
            'lot' => $lot,
        ]);

        $layout = include_template('layout.php', [
            'page_content' => $content,
            'is_auth' => $is_auth,
            'user_name' => $user_name,
            'categories' => $categories,
            'flatpickr' => '../css/flatpickr.min.css',
        ]);

        print($layout);

    } else {
        $category = get_category_name_by_id($link, $lot["category"]);
        $user = 1;

        $sql = 'INSERT INTO lot (category, author, name, lot_category, img_url, start_price, step, end_date, description)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)';

        $stmt = db_get_prepare_stmt($link, $sql, [
            $lot["category"],
            $user,
            $lot["lot-name"],
            $category,
            $lot["lot-img"],
            $lot["lot-rate"],
            $lot["lot-step"],
            $lot["lot-date"],
            $lot["message"],
        ]);

        $res = mysqli_stmt_execute($stmt);

        if ($res) {
            $lot_id = mysqli_insert_id($link);
            header("Location: lot.php?lot_id=" . $lot_id);
        };
    };

} else {
    $content = include_template('add.php', [
        'categories' => $categories,
    ]);

    $title = "Добавление нового лота";

    $layout = include_template('layout.php', [
        'page_content' => $content,
        'page_title' => $title,
        'is_auth' => $is_auth,
        'user_name' => $user_name,
        'categories' => $categories,
        'flatpickr' => '../css/flatpickr.min.css',
    ]);

    print($layout);
};
