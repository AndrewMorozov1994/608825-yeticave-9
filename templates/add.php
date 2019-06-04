    <form class="form form--add-lot container <?=!empty($errors) ? "form--invalid" : "" ; ?>" action="add.php" enctype="multipart/form-data" method="post"> <!-- form--invalid -->
      <h2>Добавление лота</h2>
      <div class="form__container-two">
        <div class="form__item <?=!empty($errors["lot-name"]) ? "form__item--invalid" : ""?>"> <!-- form__item--invalid -->
          <label for="lot-name">Наименование <sup>*</sup></label>
          <input id="lot-name" type="text" name="lot-name" placeholder="Введите наименование лота" value="<?=isset($lot['lot-name']) ? htmlspecialchars($lot['lot-name']) : ""; ?>">
          <span class="form__error"><?=$errors["lot-name"]; ?></span>
        </div>
        <div class="form__item">
          <label for="category">Категория <sup>*</sup></label>
          <select id="category" name="category">
          <?php foreach ($categories as $value): ?>
              <option <?=isset($lot['category']) && $lot['category'] === $value['id'] ? "selected" : "" ;?> value="<?=$value['id'] ?>"><?=$value['name']; ?></option>
          <?php endforeach; ?>
          </select>
          <span class="form__error">Выберите категорию</span>
        </div>
      </div>
      <div class="form__item form__item--wide <?=!empty($errors["message"]) ? "form__item--invalid" : ""?>">
        <label for="message">Описание <sup>*</sup></label>
        <textarea id="message" name="message" placeholder="Напишите описание лота"><?=isset($lot['message']) ? htmlspecialchars($lot['message']) : "" ;?></textarea>
        <span class="form__error"><?=$errors["message"]; ?></span>
      </div>
      <div class="form__item form__item--file">
        <label>Изображение <sup>*</sup></label>
        <div class="form__input-file <?=!empty($errors["lot-img"]) ? "form__item--invalid" : ""?>">
          <input class="visually-hidden" name="lot-img" type="file" id="lot-img">
          <label for="lot-img" <?=!empty($errors["lot-img"]) ? "style='border: 1px solid red'" : ""?>>
            Добавить
          </label>
          <span class="form__error"><?=$errors["lot-img"]; ?></span>
        </div>
      </div>
      <div class="form__container-three">
        <div class="form__item form__item--small <?=!empty($errors["lot-rate"]) ? "form__item--invalid" : ""?>">
          <label for="lot-rate">Начальная цена <sup>*</sup></label>
          <input id="lot-rate" type="text" name="lot-rate" placeholder="0" value="<?=isset($lot['lot-rate']) ? $lot['lot-rate'] : ""; ?>">
          <span class="form__error"><?=$errors["lot-rate"]; ?></span>
        </div>
        <div class="form__item form__item--small <?=!empty($errors["lot-step"]) ? "form__item--invalid" : ""?>">
          <label for="lot-step">Шаг ставки <sup>*</sup></label>
          <input id="lot-step" type="text" name="lot-step" placeholder="0" value="<?=isset($lot['lot-step']) ? $lot['lot-step'] : ""; ?>">
          <span class="form__error"><?=$errors["lot-step"]; ?></span>
        </div>
        <div class="form__item <?=!empty($errors["lot-date"]) ? "form__item--invalid" : ""?>">
          <label for="lot-date">Дата окончания торгов <sup>*</sup></label>
          <input class="form__input-date" id="lot-date" type="text" name="lot-date" placeholder="Введите дату в формате ГГГГ-ММ-ДД" value="<?=isset($lot['lot-date']) ? $lot['lot-date'] : ""; ?>">
          <span class="form__error"><?=$errors["lot-date"]; ?></span>
        </div>
      </div>
      <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
      <button type="submit" class="button">Добавить лот</button>
    </form>
