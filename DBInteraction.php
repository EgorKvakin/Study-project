<?php

require_once ('helpers.php');

// define("DB_HOST",     "127.0.0.1");
// define("DB_NAME",     "dszuhur-m4");
// define("DB_USER",     "root");
// define("DB_PASSWORD", "root");

//define("DB_HOST",     "wsr.ru");
//define("DB_NAME",     "jyjbazuj_m4");
//define("DB_USER",     "jyjbazuj");
//define("DB_PASSWORD", "sYu785");

 define("DB_HOST",     "127.0.0.1");
 define("DB_NAME",     "bruh");
 define("DB_USER",     "root");
 define("DB_PASSWORD", "");

class DBInteraction
{
    public $connect;



    public function __construct()
    {
        $this->connect = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        $this->connect->set_charset("utf8");

        if (!$this->connect)
        {
            print("Connecting error: " . mysqli_connect_error());
            exit();
        }
    }



    public function get_connect()
    {
        return $this->connect;
    }



    function get_projects_result($sql_string, $types, $params) {
        $stmt_proj = mysqli_prepare($this->connect, $sql_string);
        mysqli_stmt_bind_param($stmt_proj, $types, ...$params);
        mysqli_stmt_execute($stmt_proj);
        $res_projects = mysqli_stmt_get_result($stmt_proj);
        return mysqli_fetch_all($res_projects, MYSQLI_ASSOC);
    }


    public function add_user($user_info) : void
    {
        $sql = "INSERT INTO Users(email, name, password) VALUES (?, ?, ?)";
        $this->insert_query($sql, 'sss', $user_info);
    }


    public function add_task($task_info): void
    {
        if ($task_info[2] != "") {
            $sql= "INSERT INTO Tasks(task_completed, name, file, date_expiration, ProjectId) VALUES (false, ?, ?, ?, ?)";
            $this->insert_query($sql, 'sssi', $task_info);
        }
        else {
            $sql= "INSERT INTO Tasks(task_completed, name, file, ProjectId) VALUES (false, ?, ?, ?)";
            unset($task_info[2]);

            $this->insert_query($sql, 'ssi', $task_info);
        }
    }


    public function add_project($project_info): void
    {
        $sql_string = "INSERT INTO Projects(name, authorId) VALUES (?, ?)";
        $this->insert_query($sql_string, 'si', $project_info);
    }


    private function insert_query($sql, $types, $params) : void
    {
        $stmt_proj = mysqli_prepare($this->connect, $sql);
        mysqli_stmt_bind_param($stmt_proj, $types, ...$params);
        mysqli_stmt_execute($stmt_proj);
    }


    public function get_last_id() : int
    {
        return mysqli_insert_id($this->connect);
    }


    public function update_tasks($id, $value) {
        $sql_string = "UPDATE Tasks SET task_completed=? WHERE id=?";
        $stmt_proj = mysqli_prepare($this->connect, $sql_string);
        mysqli_stmt_bind_param($stmt_proj, 'ii', $value, $id);
        mysqli_stmt_execute($stmt_proj);
    }
}
