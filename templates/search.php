    <div class="container">
      <section class="lots">
        <h2>Результаты поиска по запросу «<span><?=htmlspecialchars($search); ?></span>»</h2>
        <ul class="lots__list">
        <?php foreach ($lot as $value): ?>
            <li class="lots__item lot">
                <div class="lot__image">
                    <img src="<?=htmlspecialchars($value['img_url']); ?>" width="350" height="260" alt="">
                </div>
                <div class="lot__info">
                    <span class="lot__category"><?=htmlspecialchars($value['lot_category']); ?></span>
                    <h3 class="lot__title">
                        <a class="text-link" href="lot.php?lot_id=<?=htmlspecialchars($value['id']); ?>">
                            <?=htmlspecialchars($value['name']); ?>
                        </a>
                    </h3>
                    <div class="lot__state">
                        <div class="lot__rate">
                            <span class="lot__amount"><?=get_lot_amount_text($value['id']); ?></span>
                            <span class="lot__cost"><?=htmlspecialchars($value['start_price']); ?></span>
                        </div>
                        <div class="lot__timer timer<?=end_sale_time($lot['end_date']) <= 60 ? "timer--finishing" : ""; ?>">
                            <?=end_time($value['end_date']); ?>
                        </div>
                    </div>
                </div>
            </li>
        <?php endforeach; ?>
        </ul>
      </section>
        <?php if ($pages_count > 1): ?>
            <ul class="pagination-list">
                <li class="pagination-item pagination-item-prev">
                    <?php if ($curent_page > 1) : ?>
                        <a href="search.php?search=<?=htmlspecialchars($search); ?>&page=<?=$curent_page - 1; ?>">Назад</a>
                    <?php endif; ?>
                </li>
                <?php foreach ($pages as $page) : ?>
                    <li class="pagination-item <?=(int)$page === (int)$curent_page ? "pagination-item-active" : "";?>">
                        <a href="search.php?search=<?=htmlspecialchars($search); ?>&page=<?=$page; ?>"><?=$page; ?></a>
                    </li>
                <?php endforeach; ?>
                <li class="pagination-item pagination-item-next">
                    <?php if ($curent_page < $pages_count) : ?>
                        <a href="search.php?search=<?=htmlspecialchars($search); ?>&page=<?=$curent_page + 1; ?>">Вперед</a>
                    <?php endif; ?>
                </li>
            </ul>
        <?php endif; ?>
    </div>
