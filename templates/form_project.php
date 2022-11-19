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

<div class="content">
  <main class="content__main">
    <h2 class="content__main-heading">Добавление проекта</h2>

    <form class="form"  action="add_project.php" method="post" autocomplete="off">
      <div class="form__row">
        <?php $classname = isset($errors['name']) ? "form__input--error" : ""; ?>
        <label class="form__label" for="project_name">Название <sup>*</sup></label>

        <input class="form__input <?=$classname;?>" type="text" name="name" id="project_name" value="" placeholder="Введите название проекта">
        <p class="form__message"><?= $errors['name'] ?? "" ?></p>
      </div>

      <div class="form__row form__row--controls">
        <input class="button" type="submit" name="" value="Добавить">
      </div>
    </form>
  </main>
</div>
