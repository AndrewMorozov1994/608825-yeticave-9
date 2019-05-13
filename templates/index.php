    <section class="promo">
        <h2 class="promo__title">Нужен стафф для катки?</h2>
        <p class="promo__text">На нашем интернет-аукционе ты найдёшь самое эксклюзивное сноубордическое и горнолыжное снаряжение.</p>
        <ul class="promo__list">
            <!--заполните этот список из массива категорий-->

            <? foreach ($categories as $value): ?>
                <li class="promo__item promo__item--<?=$value['symbol_code'] ;?>">
                    <a class="promo__link" href="pages/all-lots.html"><?=$value['name']; ?></a>
                </li>
            <? endforeach; ?>

        </ul>
    </section>
    <section class="lots">
        <div class="lots__header">
            <h2>Открытые лоты</h2>
        </div>
        <ul class="lots__list">
            <!--заполните этот список из массива с товарами-->

            <? foreach ($lots as $value): ?>
                <li class="lots__item lot">
                    <div class="lot__image">
                        <img src=<?=htmlspecialchars($value['img_url']); ?> width="350" height="260" alt="">
                    </div>
                    <div class="lot__info">
                        <span class="lot__category"><?=htmlspecialchars($value['lot_category']); ?></span>
                        <h3 class="lot__title"><a class="text-link" href="lot.php?lot_id=<?=htmlspecialchars($value['id']); ?>"><?=htmlspecialchars($value['name']); ?></a></h3>
                        <div class="lot__state">
                            <div class="lot__rate">
                                <span class="lot__amount">Стартовая цена</span>
                                <span class="lot__cost"><?=format_price($value['start_price']); ?></span>
                            </div>
                            <div class="lot__timer timer <?=end_sale_time('tomorrow') <= 60 ? "timer--finishing" : ""; ?>">
                                <?=end_time('tomorrow'); ?>
                            </div>
                        </div>
                    </div>
                </li>
            <? endforeach; ?>

        </ul>
    </section>
