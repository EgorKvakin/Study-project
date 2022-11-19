<section class="content__side">
    <h2 class="content__side-heading">Проекты</h2>

    <nav class="main-navigation">
        <ul class="main-navigation__list">
            <?php foreach($projects as $project): ?>
                <li class="main-navigation__list-item <?php if (isset($_GET['project']) && $project["projId"] == $_GET['project']):?>main-navigation__list-item--active <?php endif; ?>">
                    <a class="main-navigation__list-item-link" href="<?php print("/" . $file_name . "?project=" . $project["projId"]); ?>"><?= $project['name'] ?></a>
                    <span class="main-navigation__list-item-count"><?php echo $project['count_tasks'] ?></span>
                </li>
            <?php endforeach; ?>
        </ul>
    </nav>

    <a class="button button--transparent button--plus content__side-button" href="add_project.php">Добавить проект</a>
</section>

<main class="content__main">
    <h2 class="content__main-heading">Список задач</h2>

    <form class="search-form" action="index.php" method="get" autocomplete="off">
        <input class="search-form__input" type="text" name="search_text" value="" placeholder="Поиск по задачам">
        <input class="search-form__submit" type="submit" name="" value="Искать">
    </form>

    <div class="tasks-controls">
    <nav class="tasks-switch">
            <a>Все задачи</a>
            <a>Повестка дня</a>
            <a>Завтра</a>
            <a>Просроченные</a>
        </nav>

        <label class="checkbox">
            <input class="checkbox__input visually-hidden show_completed" type="checkbox" <?php if ($show_complete_tasks === 1): ?>checked<?php endif; ?>>
            <span class="checkbox__text">Показывать выполненные</span>
        </label>
    </div>

    <table class="tasks">
        <?php foreach ($tasks as $task) : ?>
                <tr class="tasks__item task <?php if ($task['task_completed'] == true) : ?> task--completed <?php else : ?> <?php if (getDaysCount($task['date_expiration']) >= 0) : ?> task--important <?php endif; ?> <?php endif; ?>">
                    <td class="task__select">
                        <label class="checkbox task__checkbox">
                            <input class="checkbox__input visually-hidden task__checkbox" type="checkbox" value="<?= $task['id'] ?>" <?= $task['task_completed'] ? 'checked' : '' ?>> <!--onchange="changeTask(this.value)"-->
                            <span class="checkbox__text"><?= $task['name']; ?></span>
                        </label>
                    </td>
                    <td class="task__controls task__date"><a download href="<?= 'uploads/'.$task['file'] ?>"><?= mb_strimwidth($task['file'], 0, 12, '...') ?></a></td>
                    <td class="task__controls"><?= $task['date_expiration'] ?></td>
                    <td class="task__controls"><?= $task['category'] ?></td>
                </tr>
           
        <?php endforeach; ?>
    </table>
</main>
