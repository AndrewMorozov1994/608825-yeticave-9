<?php
/**
 * Округляет введенное число до целого и добавляет к нему знак рубля
 *
 * @param int $input Введенное значение
 * @return string $result
 */
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

/**
 * Возвращает время до окончания лота в формате "часы : минуты"
 *
 * @param string $end_date Дата окончания лота
 * @return string "{$hours} : {$minutes}" Итоговое время
 */
function end_time($end_date) {
    $delta = strtotime($end_date) - strtotime('now');

    $hours = floor($delta / 3600);
    $minutes = floor(($delta - $hours * 3600) / 60);

    return "{$hours} : {$minutes}";
};

/**
 * Возвращает минуты до окончания лота
 *
 * @param string $end_date Дата окончания лота
 * @return int $minutes Итоговое время
 */
function end_sale_time($end_date) {
    return $minutes = (strtotime($end_date) - strtotime('now')) / 60;
};

/**
 * Создвет подключение к БД
 * Возвращает идентификатор подключения, либо в случае ошибки пперенапрвыляет на страницу с ошибкой 404
 *
 * @return object $link Идентификатор подключения
 */
function create_link() {
    $link = mysqli_connect("localhost", "root", "", "yeticave");
    mysqli_set_charset($link, "utf8");

    if ($link === false) {
        $content = include_template('404.php', [
            'error' => 'В настоящий момент страница недоступна',
        ]);
        $layout = include_template('layout.php', ['page_content' => $content,]);
        print($layout);
        die();
    }

    return $link;
};

function db_fetch_data($link, $sql, $data = []) {
    $stmt = db_get_prepare_stmt($link, $sql, $data);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);

    return $res;
};

/**
 * Делает запрос к БД и возвращает массив категорий
 *
 * @param object $link идентификатор соединения
 * @return array $categories массив категорий
 */
function get_categories($link) {
    $sql = 'SELECT * FROM category';
    return $categories = mysqli_fetch_all(db_fetch_data($link, $sql), MYSQLI_ASSOC);
};

/**
 * Получает имя категории по идентификатору давнной категории
 *
 * @param object $link идентификатор соединения
 * @param int $id идентификатор категории
 * @return string Имя категории
 */
function get_category_name_by_id($link, $id) {
    $sql = 'SELECT * FROM category c WHERE c.id = "'. $id .'"';
    $result = mysqli_query($link, $sql);
    return mysqli_fetch_array($result, MYSQLI_ASSOC)["name"];
};

/**
 * Возвращает false, если до окончания лота меньше 3600 секунд
 *            true, если больше 3600
 *
 * @param array $lot информация о лоте
 * @return bool
 */
function valid_end_date_time($lot) {
    $interval = strtotime($lot["lot-date"]) - strtotime('now');

    return $interval <= 3600 ? 0 : 1;
};

/**
 * Валидация добавляемого лота и возвращение массива с ошибками
 *
 * @param array $lot значения полей формы
 * @return array $errors ошибки валидации
 */
function lot_validity($lot) {
    $errors = [];
    $required = ["lot-name", "category", "message", "lot-rate", "lot-step", "lot-date"];

    foreach ($required as $key) {
        if (isset($lot[$key]) && trim($lot[$key]) === "") {
            $errors[$key] = "Заполните это поле";
        };
    };

    if (isset($lot['category']) && empty($lot['category'])) {
        $errors['category'] = 'Выберите категорию';
    }

    if (isset($lot['lot-name']) && strlen($lot['lot-name']) > 64) {
        $errors['lot-name'] = 'Допускается до 64 символов';
    }

    if (isset($lot['lot-date'])) {
        if (!valid_end_date_time($lot)) {
            $errors["lot-date"] = "Дата должна быть больше текущего времени на день";
        }

        if (!is_date_valid($lot["lot-date"])) {
            $errors["lot-date"] = "Укажите дату в формате ГГГГ-ММ-ДД";
        }
    }

    if (isset($lot['lot-rate'])) {
        if (!(is_numeric($lot["lot-rate"])) or (((int) $lot["lot-rate"]) <= 0)) {
            $errors["lot-rate"] = "Укажите число больше нуля";
        }
    }

    if (isset($lot['lot-step'])) {
        if (!(is_numeric($lot["lot-step"])) or (((int) $lot["lot-step"]) <= 0)) {
            $errors["lot-step"] = "Укажите число больше нуля";
        }
    }

    return $errors;
};

/**
 * Валидация ставки по лоту и возвращение массива с ошибками
 *
 * @param array $lot информация о данном лоте
 * @param integer $user_id Идентификатор авторизованного пользователя
 * @param array $active_bets массив существующих ставок по двнному лоту
 * @return array $errors ошибки валидации
 */
function step_validity($lot, $user_id, $active_bets) {
    $errors = [];
    $last_user_id = !empty($active_bets) ? $active_bets[0]['id'] : '';

    if (isset($_POST['cost'])) {
        if (empty($_POST['cost'])) {
            $errors['cost'] = 'Введите Вашу ставку';
        }

        if (!isset($_SESSION['user'])) {
            $errors['cost'] = 'Авторизуйтесь для добавления ставки';
        }

        if ((int)$lot['author'] === (int)$user_id) {
            $errors['cost'] = 'Вы не можете делать ставку на собственный лот';
        }

        if (strtotime($lot['end_date']) < strtotime('now')) {
            $errors['cost'] = 'Торги на данный лот закрыты';
        }

        if (!is_numeric($_POST['cost'])) {
            $errors['cost'] = 'Значение должно быть числом';
        }

        if ($_POST['cost'] < $lot['start_price'] + $lot['step']) {
            $errors['cost'] = 'Ставка не может быть меньше текущей стоимости';
        }

        if ((int)$last_user_id === (int)$user_id) {
            $errors['cost'] = 'Ваша ставка последняя';
        }
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

/**
 * Возвращает имя залогиненого юзера, если существует соссия и в ней присутствует это имя, иначе возвращает NULL
 *
 * @return string
 */
function set_user() {
    return $_SESSION['user']['name'] ?? null;
};

/**
 * Проверяет существует ли юзер в открытой сессии и перенаправляет на главную страницу
 *
 * Относится к страницам sign-up и login, для того чтобы залогиненый юзер не смог попасть на эти страницы
 */
function check_session() {
    if(isset($_SESSION['user'])) {
        header('Location: index.php');
        exit();
    };
};

/**
 * Получает все ставки по идентификатору лота
 *
 * @param int $id Идентификатор лота
 * @return array $bets Ставки по лоту
 */
function get_bets_by_lot($id) {
    $link = create_link();
    $sql = "SELECT u.name, b.lot, b.user, b.price FROM bet b
        JOIN users u ON b.user = u.id
        WHERE b.lot = $id";
    $result = mysqli_query($link, $sql);
    return $bets = mysqli_fetch_all($result, MYSQLI_ASSOC) ?? '';
}

/**
 * Получает текстовое представление количества ставок по лоту
 *
 * @param int $id Идентификатор лота
 * @return string $text количество ставок
 */
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

/**
 * Получает информацию о лоте по его идентификатору
 *
 * @param object $link подключение к БД
 * @param integer $id Идентификатор лота
 *
 * @return array [$categories, $lotLink] массив категорий и массив с инофрмацией о лоте
 */
function getLotById($link, $id) {

    if (!$link) {
        print('Ошибка подключения: ' . mysqli_connect_error());
    }
    else {
        $sql = 'SELECT c.name, l.id, l.author, l.name, l.description, l.lot_category, l.start_price, l.step, l.img_url, l.end_date, l.winner, l.last_bet FROM lot l
                JOIN category c ON l.category = c.id
                WHERE l.id = '. $id .'';

        $lotLink = mysqli_fetch_assoc(db_fetch_data($link, $sql));

        $categories = get_categories($link);

        return ['categories' => $categories,
                'lot' => $lotLink,
        ];
    }
}

?>
