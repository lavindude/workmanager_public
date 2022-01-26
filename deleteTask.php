<?php    
    require_once "pdo.php";
    session_start();

    if (!isset($_SESSION['name'])) {
        header("Location: error.html");
        return;
    }

    $email = $_SESSION['name'];
    $tasks = '';
    $data = $pdo->query("SELECT tasks FROM users WHERE email='$email';");
    while ($row = $data->fetch(PDO::FETCH_ASSOC)) {
        $tasks = $row['tasks'];
    }

    $newTasks = substr($tasks, 0, strlen($tasks) - 2);
    $list1 = '{ "data" : [' . $newTasks . '] }';

    $task_list = json_decode($list1, true);

    $newList = '';
    for ($x = 0; $x < sizeof($task_list['data']); $x++) {
        if ($task_list['data'][$x][0] != htmlentities($_GET['task'])) {
            $push = '["'.$task_list['data'][$x][0].'", "'.$task_list['data'][$x][1].
            '", "'.$task_list['data'][$x][2].'", "'.$task_list['data'][$x][3].
            '"], ';
            $newList .= $push;
        }
    }

    $pdo->query("UPDATE users SET tasks = '$newList' WHERE email = '$email';");

    $_SESSION['success'] = 'Task information was successfully changed!';
    header("Location: dashboard.php");
    return;
?>