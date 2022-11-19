<?php

/**
 * @var $connect $connect
 * @var $DBInteraction $DBInteraction
 */


session_start();
require_once ('DBFoo.php');
require_once('helpers.php');

if (isset($_GET['task_id']) && isset($_GET['check'])) {
    $DBInteraction->update_tasks($_GET['task_id'], $_GET['check']);
}

$file_name = pathinfo(__FILE__, PATHINFO_BASENAME);

if (!isset($_SESSION['username']))
{
    redirect_guest($_SESSION);
}

$sql_projects = "SELECT Projects.id AS projId, Projects.name AS name, COUNT(Tasks.projectId) AS count_tasks FROM Tasks RIGHT JOIN Projects ON Projects.id = Tasks.ProjectId where authorId = ? GROUP BY Projects.id";
$rows_projects = $DBInteraction->get_projects_result($sql_projects, 'i', [$_SESSION['id']]);

$id = $_GET['project'] ?? null;
if($id){
    $sql = "SELECT Tasks.name AS name,Tasks.id AS id,  date_expiration, task_completed, file, projectId  as category FROM Tasks INNER JOIN Projects ON Projects.id = Tasks.projectId where authorId = ? and Tasks.projectId = ?";
    $tasks_result = mysqli_prepare($DBInteraction->connect ,$sql);
    mysqli_stmt_bind_param($tasks_result, 'ii', $_SESSION['id'],$id);
    mysqli_stmt_execute($tasks_result);
}else{
    $sql = "SELECT Tasks.name AS name,Tasks.id AS id,  date_expiration, task_completed, file, projectId  as category FROM Tasks INNER JOIN Projects ON Projects.id = Tasks.projectId where authorId = ?";
    $tasks_result = mysqli_prepare($DBInteraction->connect,$sql);
    mysqli_stmt_bind_param($tasks_result, 'i', $_SESSION['id']);
    mysqli_stmt_execute($tasks_result);
}
$result = mysqli_stmt_get_result($tasks_result);
$rows_tasks = mysqli_fetch_all($result,MYSQLI_ASSOC);

#$sql_tasks = "SELECT Tasks.name AS name,Tasks.id AS id,  date_expiration, task_completed, projectId  as category FROM Tasks INNER JOIN Projects ON Projects.id = Tasks.projectId where authorId = ?";
#$rows_tasks = $DBInteraction->get_tasks_result($sql_tasks, 'i', $_SESSION['id']);

$page_content = include_template('main.php', [
    'con' => $connect,
    'tasks' => $rows_tasks,
    'file_name' => $file_name,
    'projects' => $rows_projects
]);
$layout_content = include_template('layout.php', [
    'title' => 'Дела в порядке',
    'content' => $page_content,
    'session' => $_SESSION,
]);

print($layout_content);
