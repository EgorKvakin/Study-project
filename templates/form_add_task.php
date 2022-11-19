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
<h2 class="content__main-heading">Добавление задачи</h2>
<form class="form" action="add.php" method="post" autocomplete="off" enctype="multipart/form-data">
  <div class="form__row">
    <?php $classname = isset($errors['name']) ? "form__input--error" : ""; ?>
    <label class="form__label" for="name">Название <sup>*</sup></label>

    <input class="form__input <?=$classname;?>" type="text" name="name" id="name" value="<?=getPostVal('name')?>" placeholder="Введите название">
    <p class="form__message"><?= $errors['name'] ?? ""; ?></p>
  </div>

  <div class="form__row">
    <?php $classname = isset($errors['id']) ? "form__input--error" : ""; ?>
    <label class="form__label" for="project">Проект <sup>*</sup></label>

    <select class="form__input form__input--select <?=$classname;?>" name="project" id="project">
    <option hidden value="null" disabled selected></option>
    <?php
        foreach($projects as $key => $project) {
            if (isset($_GET['project'])) {
                $id = $_GET['project'];
            }
            elseif (getPostVal('project') != '') {
                $id = getPostVal('project');
            }
            else {
                $id = null;
            }

            print($id.' '.$key);
            $isSelected = (($id == $project['projId']) ? 'selected ' : "");
            print("<option $isSelected value={$project['projId']}>{$project['name']}</option>");
        }
      ?>
    </select>
    <p class="form__message"><?= $errors['id'] ?? ""; ?></p>
  </div>

  <div class="form__row">
    <?php $classname = isset($errors['date']) ? "form__input--error" : ""; ?>
    <label class="form__label" for="date">Дата выполнения</label>

    <input class="form__input form__input--date <?=$classname;?>" type="text" name="date" id="date" value="<?=getPostVal('date')?>" placeholder="Введите дату в формате ГГГГ-ММ-ДД">
    <p class="form__message"><?= $errors['date'] ?? ""; ?></p>
  </div>

  <div class="form__row">
    <?php $classname = isset($errors['file']) ? "form__input--error" : ""; ?>
    <label class="form__label" for="file">Файл</label>

    <div class="form__input-file">
      <input class="visually-hidden" type="file" name="file" id="file" value="<?=getPostVal('file')?>">

      <label class="button button--transparent <?=$classname;?>" for="file">
        <span>Выберите файл</span>
      </label>
      <p class="form__message"><?= $errors['file'] ?? ""; ?></p>
    </div>
  </div>

  <div class="form__row form__row--controls">
      <p <?= count($errors) == 0 ? "hidden" : "" ?> class="error-message">Пожалуйста, исправьте ошибки в форме</p>
      <input class="button" type="submit" name="" value="Добавить">
  </div>
</form>
</main>
