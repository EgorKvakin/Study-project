<?php
/**
 * @var $connect $connect
 * @var $DBInteraction $DBInteraction
 */

require_once ('DBFoo.php');

$file_name = pathinfo(__FILE__, PATHINFO_BASENAME);
session_start();


$sql_projects = "SELECT Projects.id AS projId, Projects.name AS name, COUNT(Tasks.projectId) AS count_tasks FROM Tasks RIGHT JOIN Projects ON Projects.id = Tasks.ProjectId where authorId = ? GROUP BY Projects.id";
$rows_projects = $DBInteraction->get_projects_result($sql_projects, 'i', [$_SESSION['id']]);

if (count($rows_projects) == 0)
{
	header('Location: add_project.php');
}

require_once('helpers.php');

$errors = [];
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
	if (($error = validateField('name')) !== true)
    {
        $errors['name'] = $error;
    }
	if (($error = validateId($rows_projects, 'project')) !== true)
    {
        $errors['id'] = $error;
    }
	if (($error = validateDate('date')) !== true)
    {
        $errors['date'] = $error;
    }
	if (($error = validateFile('file')) !== true)
    {
        $errors['file'] = $error;
    }

	if (count($errors) == 0)
    {
		$file_dir = file_move('file');
		$name = htmlspecialchars($_POST['name']);
		$date = htmlspecialchars($_POST['date']);
		$project_id = htmlspecialchars($_POST['project']);

        $DBInteraction->add_task([ $name, $file_dir, $date, $project_id ]);

		header("Location: http://{$_SERVER['HTTP_HOST']}/index.php");
	}
}

$page_content = include_template('form_add_task.php', [
    'con' => $connect,
    'errors' => $errors,
    'projects' => $rows_projects,
    'file_name' => $file_name,
]);

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'title' => 'Дела в порядке',
    'session' => $_SESSION,
]);

print($layout_content);

