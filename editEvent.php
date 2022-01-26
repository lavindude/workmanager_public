<?php
    require_once "pdo.php";
    session_start();

    if (!isset($_SESSION['name'])) {
        header("Location: error.html");
        return;
    }

    $email = $_SESSION['name'];
    $events = '';
    $data = $pdo->query("SELECT events FROM users WHERE email='$email';");
    while ($row = $data->fetch(PDO::FETCH_ASSOC)) {
        $events = $row['events'];
    }

    $newEvents = substr($events, 0, strlen($events) - 2);
    $list1 = '{ "data" : [' . $newEvents . '] }';

    $event_list = json_decode($list1, true);

    if ((isset($_POST['eventinfo'])) && (strlen(htmlentities($_POST['event']))) > 0 && 
    (strlen(htmlentities($_POST['eduedate']))) > 0) {
        $newEvent = htmlentities($_POST['event']);
        $newClass = htmlentities($_POST['dropdown1']);
        $newNotes = htmlentities($_POST['enotes']);
        $newDate = htmlentities($_POST['eduedate']);
        
        $newList = '';
        for ($x = 0; $x < sizeof($event_list['data']); $x++) {
            if ($event_list['data'][$x][0] != htmlentities($_GET['event'])) {
                $push = '["'.$event_list['data'][$x][0].'", "'.$event_list['data'][$x][1].
                '", "'.$event_list['data'][$x][2].'", "'.$event_list['data'][$x][3].
                '"], ';
                $newList .= $push;
            }

            else {
                $push = '["'.$newEvent.'", "'.$newClass.'", "'.$newNotes.'", "'.$newDate.'"], ';
                $newList .= $push;
            }
        }

        $pdo->query("UPDATE users SET events = '$newList' WHERE email = '$email';");

        $_SESSION['success'] = 'Event information was successfully changed!';
        header("Location: dashboard.php");
        return;
    }
    
    else if (isset($_POST['delete'])) {
        $newList = '';
        for ($x = 0; $x < sizeof($event_list['data']); $x++) {
            if ($event_list['data'][$x][0] != htmlentities($_GET['event'])) {
                $push = '["'.$event_list['data'][$x][0].'", "'.$event_list['data'][$x][1].
                '", "'.$event_list['data'][$x][2].'", "'.$event_list['data'][$x][3].
                '"], ';
                $newList .= $push;
            }
        }

        $pdo->query("UPDATE users SET events = '$newList' WHERE email = '$email';");

        $_SESSION['success'] = 'Event information was successfully changed!';
        header("Location: dashboard.php");
        return;
    }

    else if (isset($_POST['cancel'])) {
        header("Location: dashboard.php");
        return;
    }

?>

<head>
    <title>Edit Event</title>
    <link rel="stylesheet" type="text/css" href="css/template.css">
</head>

<body>
    <form method="POST" id="forms">
    <label for="event">Event:</label>
        <?php
            echo('<input type="text" name="event" id="event" value="'.htmlentities($_GET['event']).'"><br/>')
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
        <label for="enotes">Notes:</label>
        <?php
            echo('<input type="text" name="enotes" id="enotes" value="'.htmlentities($_GET['notes']).'"><br/>');
        ?>
        
        <label for="eduedate">Due Date:</label>
        <?php
            echo('<input type="date" name="eduedate" id="eduedate" value='.htmlentities($_GET['duedate']).'><br/>');
        ?>
        
        <input type="submit" name="eventinfo" value="Save"> 
        <input type="submit" name="delete" value="Delete Event">
        <input type="submit" name="cancel" value="Cancel">

    </form>

</body>