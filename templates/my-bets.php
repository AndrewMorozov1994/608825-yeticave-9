    <section class="rates container">
      <h2>Мои ставки</h2>
      <table class="rates__list">
        <? foreach ($bets as $value): ?>
            <? if (strtotime($value['end_date']) > strtotime('now')) : ?>
                <tr class="rates__item"> <!-- rates__item--win   rates__item--end -->
            <? elseif ($value['winner'] == $user_id) : ?>
                <tr class="rates__item rates__item--win">
            <? else : ?>
                <tr class="rates__item rates__item--end">
            <? endif; ?>
                <td class="rates__info">
                    <div class="rates__img">
                        <img src="<?=htmlspecialchars($value['img_url']);?>" width="54" height="40" alt="Сноуборд">
                    </div>
                    <div>
                        <h3 class="rates__title"><a href="lot.php?lot_id=<?=htmlspecialchars($value['lot']);?>"><?=htmlspecialchars($value['lot_name']);?></a></h3>

                        <? if ($value['winner'] == $user_id) : ?>
                            <p><?=htmlspecialchars($value['contacts']);?></p>
                        <? endif; ?>

                    </div>
                </td>
                <td class="rates__category">
                    <?=htmlspecialchars($value['lot_category']);?>
                </td>
                <td class="rates__timer">
                <? if (strtotime($value['end_date']) > strtotime('now')) : ?>
                    <div class="timer <?=end_sale_time($value['end_date']) <= 60 ? "timer--finishing" : ""; ?>">
                        <?=end_time($value['end_date']); ?>
                    </div> <!-- timer--end   timer--win -->
                <? elseif ($value['winner'] == $user_id) : ?>
                    <div class="timer timer--win">
                        Ставка выиграла
                    </div>
                <? else : ?>
                    <div class="timer timer--end">
                        Торги окончены
                    </div>
                <? endif; ?>
                </td>
                <td class="rates__price">
                    <?=$value['price'];?> р
                </td>
                <td class="rates__time">
                    <?=get_back_time($value['date_creation']); ?>
                </td>
            </tr>
        <? endforeach; ?>
      </table>
    </section>
  </main>
