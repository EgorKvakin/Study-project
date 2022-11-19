<?php

function is_date_valid(string $date) : bool {
    $format_to_check = 'Y-m-d';
    $dateTimeObj = date_create_from_format($format_to_check, $date);

    return $dateTimeObj !== false && array_sum(date_get_last_errors()) === 0;
}


function db_get_prepare_stmt($link, $sql, $data = []) {
    $stmt = mysqli_prepare($link, $sql);

    if ($stmt === false) {
        $errorMsg = 'Не удалось инициализировать подготовленное выражение: ' . mysqli_error($link);
        die($errorMsg);
    }

    if ($data) {
        $types = '';
        $stmt_data = [];

        foreach ($data as $value) {
            $type = 's';

            if (is_int($value)) {
                $type = 'i';
            }
            else if (is_string($value)) {
                $type = 's';
            }
            else if (is_double($value)) {
                $type = 'd';
            }

            if ($type) {
                $types .= $type;
                $stmt_data[] = $value;
            }
        }

        $values = array_merge([$stmt, $types], $stmt_data);

        $func = 'mysqli_stmt_bind_param';
        $func(...$values);

        if (mysqli_errno($link) > 0) {
            $errorMsg = 'Не удалось связать подготовленное выражение с параметрами: ' . mysqli_error($link);
            die($errorMsg);
        }
    }

    return $stmt;
}

function get_noun_plural_form (int $number, string $one, string $two, string $many): string
{
    $number = (int) $number;
    $mod10 = $number % 10;
    $mod100 = $number % 100;

    switch (true) {
        case ($mod100 >= 11 && $mod100 <= 20):
            return $many;

        case ($mod10 > 5):
            return $many;

        case ($mod10 === 1):
            return $one;

        case ($mod10 >= 2 && $mod10 <= 4):
            return $two;

        default:
            return $many;
    }
}


function include_template($name, array $data = []) {
    $name = 'templates/' . $name;
    $result = '';

    if (!is_readable($name)) {
        return $result;
    }

    ob_start();
    extract($data);
    require $name;

    $result = ob_get_clean();

    return $result;
}


function getDaysCount($date) {
    if ($date != null) {
        $taskDate = date_create($date);
        $currentDate =  date_create(date('d-m-Y'));
        $difference = date_diff($taskDate, $currentDate);
        return $difference->format('%R%a') * 24;
    }
    else {
        return 245555;
    }
}


function validateField($name) {
    if (empty($_POST[$name]))
        return "Это поле должно быть заполнено";
    return true;
}


function validateId($projects, $name) {
    foreach ($projects as $project) {
        if (isset($_POST[$name]) && $_POST[$name] == $project['projId']) {
            return true;
        }
    }
    return "Проект должен быть выбран из существующих";
}


function verify_pas($pass) {
    return password_verify($_POST['password'],  $pass);
}

function validateDate($name) {
    if ($_POST[$name] == "") {
        return true;
    }
    $date = new DateTime($_POST[$name]);
    if ($_POST[$name] && is_date_valid($_POST[$name]) && $date->format('Y-m-d') >= date('Y-m-d', time()))
        return true;
    return "Дата должна быть формата ГГГГ-ММ-ДД и быть больше или равна нынешнему дню";
}


function validateFile($name) {
    if (isset($_FILES[$name]) && isset($_FILES["error"])) {
        if ($_FILES["error"] == UPLOAD_ERR_OK) {
            $file_size = $_FILES[$name]['size'];
            if ($file_size > 134217728)
                return "Размер файла не должен превышать 128 мегабайт";
        }
    }
    return true;
}


function file_move($file) {
    $file_dir_full = $_FILES[$file]['name'];
    if (move_uploaded_file($_FILES[$file]['tmp_name'], 'uploads/'.$file_dir_full))
        return $file_dir_full;
    else
        return false;
}


function validateEmail() {
    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
        return "Введите правильный формат email адреса";
    return true;
}


function isEmailExist($con) {
    $sql = "SELECT 1 FROM Users WHERE email = ?";
    $mysql_stmt = db_get_prepare_stmt($con, $sql, [$_POST['email']]);
    mysqli_stmt_execute($mysql_stmt);
    if (!$mysql_stmt->fetch())
        return true;
    return false;
}


function isProjectExist($con, $name_proj) {
    $sql = "SELECT 1 FROM Projects WHERE authorId = ? AND name = ?";
     $mysql_stmt = db_get_prepare_stmt($con, $sql, [$_SESSION['id'], $_POST[$name_proj]]);
     mysqli_stmt_execute($mysql_stmt);
     if ($mysql_stmt->fetch())
         return "Такой проект уже существует";
    return true;
}


function getPostVal($name) {
    return $_POST[$name] ?? "";
}


function redirect_guest($session) {
    $page_content = include_template('guest.php', []);
    $layout_content = include_template('layout.php', [
        'title' => 'напоминалка',
        'content' => $page_content,
        'session' => $session,
        'isGuest' => true,
    ]);
    print($layout_content);
    exit();
}


function add_where($where, $sql) {
    return $where . " AND " . $sql;
}


function getGetMas($data) {
    if (isset($_GET['project'])) {
        $queries['project'] = $_GET['project'];
    }

    if (isset($_GET['show_completed'])) {
        $queries['show_completed'] = $_GET['show_completed'];
    }

    if (count($data) != 0) {
        $queries['date'] = $data['date'];
    }
    return http_build_query($queries);
}
