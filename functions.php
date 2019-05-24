<?php

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

function create_link() {
    $link = mysqli_connect("localhost", "root", "", "yeticave");
    mysqli_set_charset($link, "utf8");

    if ($link == false) {
        $content = include_template('404.php', [
            'error' => 'В настоящий момент страница недоступна',
        ]);
        $layout = include_template('layout.php', ['page_content' => $content,]);
        print($layout);
        die();
    }
    else {
        return $link;
    }
};

function db_fetch_data($link, $sql, $data = []) {
    $stmt = db_get_prepare_stmt($link, $sql, $data);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);

    return $res;
};

function get_categories($link) {

    $sql = 'SELECT * FROM category';
    return $categories = mysqli_fetch_all(db_fetch_data($link, $sql), MYSQLI_ASSOC);
};

function get_category_name_by_id($link, $id) {
    $sql = 'SELECT * FROM category c WHERE c.id = "'. $id .'"';
    $result = mysqli_query($link, $sql);
    return mysqli_fetch_array($result, MYSQLI_ASSOC)["name"];
};

function valid_end_date_time($lot) {
    $interval = strtotime($lot["lot-date"]) - strtotime('now');

    return $interval <= 86400 ? 0 : 1;
};

function lot_validity($lot) {
    $errors = [];
    $required = ["lot-name", "category", "message", "lot-rate", "lot-step", "lot-date"];

    foreach ($required as $key) {
        if (empty($lot[$key])) {
            $errors[$key] = "Заполните это поле";
        };
    };

    if (!valid_end_date_time($lot)) {
        $errors["lot-date"] = "Дата должна быть больше текущего времени на день";
    };

    if (!is_date_valid($lot["lot-date"])) {
        $errors["lot-date"] = "Укажите дату в формате ГГГГ-ММ-ДД";
    };

    if (!(is_numeric($lot["lot-rate"])) or (((int) $lot["lot-rate"]) <= 0)) {
        $errors["lot-rate"] = "Укажите число больше нуля";
    };

    if (!(is_numeric($lot["lot-step"])) or (((int) $lot["lot-step"]) <= 0)) {
        $errors["lot-step"] = "Укажите число больше нуля";
    };

    return $errors;
};

function step_validity($lot, $user_id, $active_bets) {
    $errors = [];
    $last_user_id = !empty($active_bets) ? $active_bets[0]['id'] : '';

    if(empty($_POST['cost'])) {
        $errors['cost'] = 'Введите Вашу ставку';
    }

    if (!is_numeric($_POST['cost'])) {
        $errors['cost'] = 'Значение должно быть числом';
    }

    if ($_POST['cost'] < $lot['start_price'] + $lot['step']) {
        $errors['cost'] = 'Ставка не может быть меньше текущей стоимости';
    };

    if ((int)$last_user_id === (int)$user_id) {
        $errors['cost'] = 'Ваша ставка последняя';
    }

    return $errors;
}

function get_layout($content, $title, $user_name, $nav) {
    return $layout = include_template('layout.php', [
        'page_content' => $content,
        'page_title' => $title,
        'user_name' => $user_name,
        'nav' => $nav,
    ]);
};

function set_user() {
    return $_SESSION['user']['name'] ?? null;
};

function check_session() {
    if(isset($_SESSION['user'])) {
        header('Location: index.php');
        exit();
    };
};

function get_bets_by_lot($id) {
    $link = create_link();
    $sql = "SELECT u.name, b.lot, b.user, b.price FROM bet b
        JOIN users u ON b.user = u.id
        WHERE b.lot = $id";
    $result = mysqli_query($link, $sql);
    return $bets = mysqli_fetch_all($result, MYSQLI_ASSOC) ?? '';
}

function get_lot_amount_text($id) {
    $text;
    $bets = get_bets_by_lot($id);
    if ($bets) {
      $text = sizeof($bets) . " " .
        get_noun_plural_form(sizeof($bets), "ставка", "ставки", "ставок");
    } else {
      $text = "Стартовая цена";
    }
    return $text;
  }
?>
