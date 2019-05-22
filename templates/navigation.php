<nav class="nav">
    <ul class="nav__list container">
        <? foreach ($categories as $value): ?>

            <li class="nav__item <?=(int)$id === (int)$value['id'] ? "nav__item--current" : ""?>">
                <a href="all-lots.php?category=<?=$value['id'];?>"><?=htmlspecialchars($value['name']); ?></a>
            </li>

        <? endforeach; ?>
    </ul>
</nav>
