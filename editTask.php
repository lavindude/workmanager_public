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

    if ((isset($_POST['taskinfo'])) && (strlen(htmlentities($_POST['task']))) > 0 && 
    (strlen(htmlentities($_POST['tduedate']))) > 0) {
        $newTask = htmlentities($_POST['task']);
        $newClass = htmlentities($_POST['dropdown1']);
        $newNotes = htmlentities($_POST['tnotes']);
        $newDate = htmlentities($_POST['tduedate']);
        
        $newList = '';
        for ($x = 0; $x < sizeof($task_list['data']); $x++) {
            if ($task_list['data'][$x][0] != htmlentities($_GET['task'])) {
                $push = '["'.$task_list['data'][$x][0].'", "'.$task_list['data'][$x][1].
                '", "'.$task_list['data'][$x][2].'", "'.$task_list['data'][$x][3].
                '"], ';
                $newList .= $push;
            }

            else {
                $push = '["'.$newTask.'", "'.$newClass.'", "'.$newNotes.'", "'.$newDate.'"], ';
                $newList .= $push;
            }
        }

        $pdo->query("UPDATE users SET tasks = '$newList' WHERE email = '$email';");

        $_SESSION['success'] = 'Task information was successfully changed!';
        header("Location: dashboard.php");
        return;
    }
    
    else if (isset($_POST['delete'])) {
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
    }

    else if (isset($_POST['cancel'])) {
        header("Location: dashboard.php");
        return;
    }

?>

<head>
    <title>Edit Task</title>
    <link rel="stylesheet" type="text/css" href="css/template.css">
</head>

<body>
    <form method="POST" id="forms">
    <label for="task">Task:</label>
        <?php
            echo('<input type="text" name="task" id="task" value="'.htmlentities($_GET['task']).'"><br/>')
        ?>
        
        <label for="dropdown1">Class:</label>

        <?php
            echo '<select name = "dropdown1">';
            $selected = htmlentities($_GET['class']);
            echo '<option value = "'.$selected.'" selected>'.$selected.'</option>';
            for ($x = 0; $x < sizeof($_SESSION['classes']); $x++) {
                $current = $_SESSION['classes'][$x];
                echo "<option value = \"$current\">$current</option>";
            }

            echo '</select>';

        ?>
        
        <br/>
        <label for="tnotes">Notes:</label>
        <?php
            echo('<input type="text" name="tnotes" id="tnotes" value="'.htmlentities($_GET['notes']).'"><br/>');
        ?>
        
        <label for="tduedate">Due Date:</label>
        <?php
            echo('<input type="date" name="tduedate" id="tduedate" value='.htmlentities($_GET['duedate']).'><br/>');
        ?>
        
        <input type="submit" name="taskinfo" value="Save"> 
        <input type="submit" name="delete" value="Delete Task">
        <input type="submit" name="cancel" value="Cancel">

    </form>

</body>