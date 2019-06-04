 <section class="promo">
        <h2 class="promo__title">Нужен стафф для катки?</h2>
        <p class="promo__text">На нашем интернет-аукционе ты найдёшь самое эксклюзивное сноубордическое и горнолыжное снаряжение.</p>
        <ul class="promo__list">
            <!--заполните этот список из массива категорий-->

            <? foreach ($categories as $value): ?>
                <li class="promo__item promo__item--<?=$value['symbol_code'] ;?>">
                    <a class="promo__link" href="all-lots.php?category=<?=$value['id'];?>"><?=$value['name']; ?></a>
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
                                <span class="lot__amount"><?=get_lot_amount_text($value['id']); ?></span>
                                <span class="lot__cost"><?=isset($value['last_bet']) ? $value['last_bet'] : format_price($value['start_price']); ?></span>
                            </div>
                            <div class="lot__timer timer <?=end_sale_time($value['end_date']) <= 60 ? "timer--finishing" : ""; ?>">
                                <?=end_time($value['end_date']); ?>
                            </div>
                        </div>
                    </div>
                </li>
            <? endforeach; ?>

        </ul>
        <?php if ($pages_count > 1): ?>
            <ul class="pagination-list">
                <li class="pagination-item pagination-item-prev">
                    <?php if ($curent_page > 1) : ?>
                        <a href="index.php?page=<?=$curent_page - 1; ?>">Назад</a>
                    <?php endif; ?>
                </li>
                <?php foreach ($pages as $page) : ?>
                    <li class="pagination-item <?=(int)$page === (int)$curent_page ? "pagination-item-active" : "";?>">
                        <a href="index.php?page=<?=$page; ?>"><?=$page; ?></a>
                    </li>
                <?php endforeach; ?>
                <li class="pagination-item pagination-item-next">
                    <?php if ($curent_page < $pages_count) : ?>
                        <a href="index.php?page=<?=$curent_page + 1; ?>">Вперед</a>
                    <?php endif; ?>
                </li>
            </ul>
        <?php endif; ?>
    </section>
