<?php

/**
 * @var $connect $connect
 * @var $DBInteraction $DBInteraction
 */

session_start();

require_once('DBFoo.php');
require_once('helpers.php');

if (!isset($_SESSION['username']))
{
    redirect_guest($_SESSION);
}

$file_name = pathinfo(__FILE__, PATHINFO_BASENAME);

$sql_projects = "SELECT Projects.id AS projId, Projects.name AS name, COUNT(Tasks.projectId) AS count_tasks FROM Tasks RIGHT JOIN Projects ON Projects.id = Tasks.ProjectId where authorId = ? GROUP BY Projects.id";
$rows_projects = $DBInteraction->get_projects_result($sql_projects, 'i', [$_SESSION['id']]);

$errors = [];
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
	if (($error = validateField('name')) !== true)
    {
        $errors['name'] = $error;
    }
    print(isProjectExist($connect, 'name'));
    if (($error = isProjectExist($connect, 'name')) !== true)
    {
        $errors['name'] = $error;
    }
    if (count($errors) == 0)
    {
		$name = htmlspecialchars($_POST['name']);
        $DBInteraction->add_project([ $_POST['name'], $_SESSION['id'] ]);
        $sql_string = "SELECT MAX(id) FROM Projects";
	    $res_projects = mysqli_query($connect, $sql_string);
        $id_projects = mysqli_fetch_all($res_projects);

		header("Location: http://{$_SERVER['HTTP_HOST']}/add.php?project={$id_projects[0][0]}");
    }
}

$page_content = include_template('form_project.php', [
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
