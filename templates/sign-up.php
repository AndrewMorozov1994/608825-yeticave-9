<nav class="nav">
    <ul class="nav__list container">
        <? foreach ($categories as $value): ?>
            <li class="nav__item">
                <a href="all-lots.html"><?=htmlspecialchars($value['name']); ?></a>
            </li>
        <? endforeach; ?>
    </ul>
</nav>
<form class="form container <?=!empty($errors) ? "form--invalid" : ""; ?>" action="" method="post" autocomplete="off"> <!-- https://echo.htmlacademy.ru -->
    <h2>Регистрация нового аккаунта</h2>
    <div class="form__item <?=!empty($errors["email"]) ? "form__item--invalid" : ""; ?>"> <!-- form__item--invalid -->
        <label for="email">E-mail <sup>*</sup></label>
        <input id="email" type="text" name="email" placeholder="Введите e-mail" value="<?=isset($form["email"]) ? $form["email"] : ""; ?>">
        <span class="form__error"><?=!empty($errors["email"]) ? $errors["email"] : ""?></span>
    </div>
    <div class="form__item <?=!empty($errors) ? "form__item--invalid" : ""; ?>">
        <label for="password">Пароль <sup>*</sup></label>
        <input id="password" type="password" name="password" placeholder="Введите пароль">
        <span class="form__error">Введите пароль</span>
    </div>
    <div class="form__item <?=!empty($errors["name"]) ? "form__item--invalid" : ""; ?>">
        <label for="name">Имя <sup>*</sup></label>
        <input id="name" type="text" name="name" placeholder="Введите имя" value="<?=isset($form["name"]) ? $form["name"] : ""; ?>">
        <span class="form__error"><?=!empty($errors["name"]) ? $errors["name"] : ""?></span>
    </div>
    <div class="form__item <?=!empty($errors["message"]) ? "form__item--invalid" : ""; ?>">
        <label for="message">Контактные данные <sup>*</sup></label>
        <textarea id="message" name="message" placeholder="Напишите как с вами связаться"><?=isset($form["message"]) ? $form["message"] : ""; ?></textarea>
        <span class="form__error"><?=!empty($errors["message"]) ? $errors["message"] : ""?></span>
    </div>
    <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
    <button type="submit" class="button">Зарегистрироваться</button>
    <a class="text-link" href="#">Уже есть аккаунт</a>
</form>
