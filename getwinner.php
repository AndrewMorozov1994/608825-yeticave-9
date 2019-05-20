<?php
require_once('helpers.php');
require_once('functions.php');
require_once('vendor/autoload.php');

$link = create_link();

$transport = new Swift_SmtpTransport("phpdemo.ru", 25);
            $transport->setUsername("keks@phpdemo.ru");
            $transport->setPassword("htmlacademy");

            $mailer = new Swift_Mailer($transport);

            $logger = new Swift_Plugins_Loggers_ArrayLogger();
            $mailer->registerPlugin(new Swift_Plugins_LoggerPlugin($logger));

$sql = "SELECT l.id, l.end_date, l.winner, l.name FROM lot l
        WHERE l.end_date < NOW()
        AND l.winner IS NULL";

$result = db_fetch_data($link, $sql);
$close_lots = mysqli_fetch_all($result, MYSQLI_ASSOC);

if (!empty($close_lots)) {
    foreach ($close_lots as $lot) {
        $id = $lot['id'];

        $sql_max_bet = "SELECT b.id, b.price, b.user, b.lot, u.name, u.id as winner, u.email FROM bet b
                        JOIN users u ON b.user = u.id
                        WHERE b.lot = $id
                        ORDER BY b.price DESC LIMIT 1";
        $res = db_fetch_data($link, $sql_max_bet);
        $max_bet = mysqli_fetch_assoc($res);

        if(!empty($max_bet)) {
            $email = $max_bet['email'];
            $lot_id = $max_bet['lot'];
            $winner_id = $max_bet['winner'];

            $sql_upd = "UPDATE lot SET winner = $winner_id
                        WHERE lot.id = $lot_id";

            $res_upd = mysqli_query($link, $sql_upd);
            var_dump($res_upd);

            if (!$res_upd) {
                print('Ошибка MYSQL: ' . mysqli_error($link));
            }
            else {
                $message = new Swift_Message();
                $message->setSubject("Ваша ставка победила!");
                $message->setFrom(['keks@phpdemo.ru' => 'yeticave']);
                $message->setTo([$email]);

                $msg_content = include_template('mail.php', ['lot' => $max_bet,]);
                $message->setBody($msg_content, 'text/html');

                $result_mes = $mailer->send($message);
            }
        }
    }
}
?>
