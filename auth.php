<?php
/**
 * @var $connect $connect
 * @var $DBInteraction $DBInteraction
 */

session_start();
require_once ('DBFoo.php');
require_once('helpers.php');

$errors = [];
$email_or_pas = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	if (($email_error_empty = validateField('email')) !== true)
    {
        $errors['email'] = $email_error_empty;
    }
    else
    {
        if (($email_error_template = validateEmail()) !== true)
        {
            $errors['email'] = $email_error_template;
        }
    }

	if (($password_error_empty = validateField('password')) !== true)
    {
		$errors['password'] = $password_error_empty;
    }

    $email_or_pas = isEmailExist($connect);
    if (isset($errors['email'])) {
        $email_or_pas = false;
    }

	if (count($errors) == 0 && $email_or_pas == false) {
        $sql = "SELECT * FROM Users WHERE email = ?";
        $mysql_stmt = db_get_prepare_stmt($connect, $sql, [$_POST['email']]);
        mysqli_stmt_execute($mysql_stmt);
        $users = mysqli_stmt_get_result($mysql_stmt);
        $user = mysqli_fetch_assoc($users);

        if (verify_pas($user['password'])) {
            $_SESSION['username'] = $user['name'];
            $_SESSION['id'] = $user['id'];
		    header("Location: http://{$_SERVER['HTTP_HOST']}/index.php");
        }
        else {
            $email_or_pas = "Вы ввели неверный email/пароль";
        }
	}
}

$page_content = include_template('auth.php', [
    'errors' => $errors,
    'email_or_pas' => $email_or_pas,
]);
$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'title' => 'Дела в порядке',
    'session' => $_SESSION,
]);
print($layout_content);
?>
