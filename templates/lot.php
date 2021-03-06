<section class="lot-item container">
    <h2><?=htmlspecialchars($lot['name']); ?></h2>
    <div class="lot-item__content">
        <div class="lot-item__left">
            <div class="lot-item__image">
                <img src="../<?=htmlspecialchars($lot['img_url']); ?>" width="730" height="548" alt="Сноуборд">
            </div>
            <p class="lot-item__category">Категория: <span><?=htmlspecialchars($lot['lot_category']); ?></span></p>
            <p class="lot-item__description"><?=htmlspecialchars($lot['description']); ?></p>
        </div>
        <div class="lot-item__right">
            <div class="lot-item__state">
                <div class="lot-item__timer timer <?=end_sale_time($lot['end_date']) <= 60 ? "timer--finishing" : ""; ?>">
                    <?=end_time($lot['end_date']); ?>
                </div>
                <div class="lot-item__cost-state">
                <div class="lot-item__rate">
                    <span class="lot-item__amount">Текущая цена</span>
                    <span class="lot-item__cost"><?=isset($lot['last_bet']) ? $lot['last_bet'] : htmlspecialchars($lot['start_price']); ?></span>
                </div>
                <div class="lot-item__min-cost">
                    Мин. ставка <span><?=isset($lot['last_bet']) ? $lot['last_bet'] + $lot['step'] : htmlspecialchars($lot['start_price'] + $lot['step']); ?></span>
                </div>
            </div>

            <?php if (isset($_SESSION["user"]))
                    if($bets ? (int)$_SESSION['user']['id'] !== $bets[0]['id'] : true && (int)$_SESSION['user']['id'] !== (int)$lot['author']) : ?>

                <form class="lot-item__form" action="" method="post" autocomplete="off">
                    <p class="lot-item__form-item form__item <?=isset($errors) ? 'form__item--invalid' : ""; ?>">
                        <label for="cost">Ваша ставка</label>
                        <input id="cost" type="text" name="cost" placeholder="<?=isset($lot['last_bet']) ? $lot['last_bet'] + $lot['step'] : htmlspecialchars($lot['start_price'] + $lot['step']); ?>">
                        <span class="form__error"><?=isset($errors) ? $errors['cost'] : ""; ?></span>
                    </p>
                    <button type="submit" class="button">Сделать ставку</button>
                </form>
            <?php endif; ?>

            </div>
            <div class="history">
                <h3>История ставок (<span><?=sizeof($bets);?></span>)</h3>
                <table class="history__list">
                <? foreach ($bets as $value): ?>
                    <tr class="history__item">
                        <td class="history__name"><?=htmlspecialchars($value['name']); ?></td>
                        <td class="history__price"><?=htmlspecialchars($value['price']); ?> р</td>
                        <td class="history__time"><?=get_back_time($value['date_creation']); ?></td>
                    </tr>
                <? endforeach; ?>

                </table>
            </div>
        </div>
    </div>
</section>
