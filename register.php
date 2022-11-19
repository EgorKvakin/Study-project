<?php
/**
 * @var $connect $connect
 * @var $DBInteraction $DBInteraction
 */

require_once ('DBFoo.php');
require_once ('helpers.php');

session_start();

$errors = [];
$emailError = "";
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
	if(($email_error_empty = validateField('email')) !== true)
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
	if (($name_error_empty = validateField('name')) !== true)
    {
        $errors['name'] = $name_error_empty;
    }


	if (count($errors) == 0)
    {
	    $emailError = isEmailExist($connect);
        if ($emailError != false) {
            $email = htmlspecialchars($_POST['email']);
            $name = htmlspecialchars($_POST['name']);
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

            $DBInteraction->add_user([ $email, $name, $password ]);
            $id = $DBInteraction->get_last_id();

            print('register '.$id);
            $_SESSION['username'] = $name;
            $_SESSION['id'] = $id;
            header("Location: http://{$_SERVER['HTTP_HOST']}/index.php");
        }
	}
}

$page_content = include_template('form_register.php', [
    'errors' => $errors,
	'emailError' => $emailError,
]);

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'title' => 'Дела в порядке',
    'session' => $_SESSION,
]);

print($layout_content);

