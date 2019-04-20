<?php

require_once('helpers.php');

$is_auth = rand(0, 1);

$user_name = 'Андрей'; // укажите здесь ваше имя

$categories = ['Доски и лыжи', 'Крепления', 'Ботинки', 'Одежда', 'Инструменты', 'Разное'];

$adverts = [
    [
        'title' => '2014 Rossignol District Snowboard',
        'category' => 'Доски и лыжи',
        'price' => 10999,
        'url' => 'img/lot-1.jpg',
    ],
    [
        'title' => 'DC Ply Mens 2016/2017 Snowboard',
        'category' => 'Доски и лыжи',
        'price' => 159999,
        'url' => 'img/lot-2.jpg',
    ],
    [
        'title' => 'Крепления Union Contact Pro 2015 года размер L/XL',
        'category' => 'Крепления',
        'price' => 8000,
        'url' => 'img/lot-3.jpg',
    ],
    [
        'title' => 'Ботинки для сноуборда DC Mutiny Charocal',
        'category' => 'Ботинки',
        'price' => 10999,
        'url' => 'img/lot-4.jpg',
    ],
    [
        'title' => 'Куртка для сноуборда DC Mutiny Charocal',
        'category' => 'Одежда',
        'price' => 7500,
        'url' => 'img/lot-5.jpg',
    ],
    [
        'title' => 'Маска Oakley Canopy',
        'category' => 'Разное',
        'price' => 349.1,
        'url' => 'img/lot-6.jpg',
    ],
];

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

    return sprintf('%02d:%02d', $hours, $minutes);
}

function end_sale_time($end_date) {
    return $minutes = (strtotime($end_date) - strtotime('now')) / 60;
}

$content = include_template('index.php', [
    'categories' => $categories,
    'adverts' => $adverts,
]);

$layout_content = include_template('layout.php', [
    'page_title' => 'Главная',
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'page_content' => $content,
    'categories' => $categories,
]);

print($layout_content);

?>
