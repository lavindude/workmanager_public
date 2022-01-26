<?php
    require_once "pdo.php";
    session_start();

// VIEW -------------------------------------------------------------------
    if (!isset($_SESSION['name'])) {
        header("Location: error.html");
        return;
    }    

    echo '<p id="greet" style="font-size: 30px;">Hello '. $_SESSION['name'] . '!</p>'; 
    echo "<br>"; 

    if (isset($_SESSION['classes'])) {
        unset($_SESSION['classes']);
    }

?>

<form method="POST">
    <!--Logout:-->
    <input type="submit" name="Logout" value="Logout">

</form>

<?php
    $classes = '';
    $tasks = '';
    $events = '';
    $links = '';
    $calendar = '';
    $email = $_SESSION['name'];

    $data = $pdo->query("SELECT encoded_classes FROM users WHERE email='$email';");
    while ($row = $data->fetch(PDO::FETCH_ASSOC)) {
        $classes = $row['encoded_classes'];
    }

    $data = $pdo->query("SELECT tasks FROM users WHERE email='$email';");
    while ($row = $data->fetch(PDO::FETCH_ASSOC)) {
        $tasks = $row['tasks'];
    }

    $data = $pdo->query("SELECT events FROM users WHERE email='$email';");
    while ($row = $data->fetch(PDO::FETCH_ASSOC)) {
        $events = $row['events'];
    }

    $data = $pdo->query("SELECT links FROM users WHERE email='$email';");
    while ($row = $data->fetch(PDO::FETCH_ASSOC)) {
        $links = $row['links'];
    }

    $data = $pdo->query("SELECT calendar_link FROM users WHERE email='$email';");
    while ($row = $data->fetch(PDO::FETCH_ASSOC)) {
        $calendar = $row['calendar_link'];
    }

    $_SESSION['classes'] = array();

    //view of classes
    if ($classes != '') {
        echo('<div class="class_view">');
        echo('<table id="ct" class="class-table"');

        echo('<tr>');
        echo('<th>Class</th>');
        echo('<th>Current Grade</th>');
        echo('</tr>');    

        $newClasses = substr($classes, 0, strlen($classes) - 2);
        $list1 = '{ "data" : [' . $newClasses . '] }';

        $class_list = json_decode($list1, true);
        for ($x = 0; $x < sizeof($class_list['data']); $x++) {
            echo '<tr>';
            echo('<td>' . $class_list['data'][$x][0] . '</td>');
            array_push($_SESSION['classes'], $class_list['data'][$x][0]);
            echo('<td>' . $class_list['data'][$x][1] . '%</td>');
            echo('<td>');
            echo('<a href="editClass.php?class='.$class_list['data'][$x][0].
            '&percentage='.$class_list['data'][$x][1].'">Edit</a>');
            echo('</td>');
            echo '</tr>';
        }

        echo('</table>');

        echo('</div>');
        echo '</br>';
    }

    if ($tasks != '') {
        //view of tasks
        echo('<div class="task_view">');

        //display everything:
        echo('<table id="tt" class="task-table">');

        echo('<tr>');
        echo('<th>Task</th>');
        echo('<th>Class</th>');
        echo('<th>Notes</th>');
        echo('<th>Due Date</th>');
        echo('</tr>');    

        $newTasks = substr($tasks, 0, strlen($tasks) - 2);
        $list2 = '{ "data" : [' . $newTasks . '] }';

        $task_list = json_decode($list2, true);

        //date sorting algorithm: ---------
        $ordered = FALSE;
        while (!$ordered) {
            shuffle($task_list['data']);
            $ordered = TRUE; //momentarily
            for ($i = 0; $i < sizeof($task_list['data']) - 1; $i++) {
                $time1 = strtotime($task_list['data'][$i][3]);
                $time2 = strtotime($task_list['data'][$i+1][3]);
                if ($time1 > $time2) {
                    $ordered = FALSE; //switch back to False
                }
            }
        }

        //-------------------------------

        for ($x = 0; $x < sizeof($task_list['data']); $x++) {
            echo '<tr>';
            echo('<td>' . $task_list['data'][$x][0] . '</td>');
            echo('<td>' . $task_list['data'][$x][1] . '</td>');
            echo('<td>' . $task_list['data'][$x][2] . '</td>');
            echo('<td>' . $task_list['data'][$x][3] . '</td>');
            echo('<td> <a href="editTask.php?task='.$task_list['data'][$x][0].
            '&class='.$task_list['data'][$x][1].'&notes='.$task_list['data'][$x][2].
            '&duedate='.$task_list['data'][$x][3].
            '">Edit</a> </td>');
            $addedString = 'task='.$task_list['data'][$x][0].
            '&class='.$task_list['data'][$x][1].'&notes='.$task_list['data'][$x][2].
            '&duedate='.$task_list['data'][$x][3];
            echo('<td><input type="checkbox" onclick="location.href=\'deleteTask.php?'.$addedString.'\'"></td>');
            echo '</tr>';
        }

        echo('</table>');

        echo('</div>');
        echo '</br>';
    }

    if ($events != '') {
        //view of events
        echo('<div class="event_view">');

        //display everything:
        echo('<table id="et" class="event-table">');

        echo('<tr>');
        echo('<th>Event</th>');
        echo('<th>Class</th>');
        echo('<th>Notes</th>');
        echo('<th>Date</th>');
        echo('</tr>');    

        $newEvents = substr($events, 0, strlen($events) - 2);
        $list3 = '{ "data" : [' . $newEvents . '] }';

        $event_list = json_decode($list3, true);

        //date sorting algorithm: ---------
        $ordered = FALSE;
        while (!$ordered) {
            shuffle($event_list['data']);
            $ordered = TRUE; //momentarily
            for ($i = 0; $i < sizeof($event_list['data']) - 1; $i++) {
                $time1 = strtotime($event_list['data'][$i][3]);
                $time2 = strtotime($event_list['data'][$i+1][3]);
                if ($time1 > $time2) {
                    $ordered = FALSE; //switch back to False
                }
            }
        }

        //-------------------------------

        for ($x = 0; $x < sizeof($event_list['data']); $x++) {
            echo '<tr>';
            echo('<td>' . $event_list['data'][$x][0] . '</td>');
            echo('<td>' . $event_list['data'][$x][1] . '</td>');
            echo('<td>' . $event_list['data'][$x][2] . '</td>');
            echo('<td>' . $event_list['data'][$x][3] . '</td>');
            echo('<td> <a href="editEvent.php?event='.$event_list['data'][$x][0].
            '&class='.$event_list['data'][$x][1].'&notes='.$event_list['data'][$x][2].
            '&duedate='.$event_list['data'][$x][3].
            '">Edit</a> </td>');
            echo '</tr>';
        }


        echo('</table>');

        echo('</div>');
    }

    if ($links != '') {
        //view of links
        echo('<div class="link_view">');

        //display everything:
        echo('<table id="lt" class="link-table"');

        echo('<tr>');
        echo('<th>Notes</th>');
        echo('<th>Link</th>');
        echo('</tr>');    

        $newLinks = substr($links, 0, strlen($links) - 2);
        $list4 = '{ "data" : [' . $newLinks . '] }';

        $link_list = json_decode($list4, true);

        for ($x = 0; $x < sizeof($link_list['data']); $x++) {
            echo '<tr>';
            echo('<td>' . $link_list['data'][$x][0] . '</td>');
            $href = $link_list['data'][$x][1];
            echo('<td> <a href="'.$href.'">' . $link_list['data'][$x][1] . '</a> </td>');
            echo('<td><a href=deletelink.php?link='.$href.'>Delete</a></td>');
            echo '</tr>';
        }

        echo('</table>');

        echo('</div>');
    }

    if (isset($_SESSION['error'])) {
        echo('<script>alert("'.$_SESSION['error'].'");</script>');
        unset($_SESSION['error']);
    }

    if (isset($_SESSION['success'])) {
        unset($_SESSION['success']);
    }
// VIEW ----------------------------------------------------------------------- ^

// EDIT PROFILE ---------------------------------------------------------------------

    //logout:
    if (isset($_POST['Logout'])) {
        header("Location: login.php");
        return;
    }


    //allows user to edit their classes
    if (isset($_POST['classgrade'])) {
        if (strlen(htmlentities($_POST['class'])) > 0 && 
        strlen(htmlentities($_POST['grade'])) > 0) {
            if (strlen(htmlentities($_POST['class'])) > 57) {
                $_SESSION['error'] = 'Class name is too long.';
                header("Location: dashboard.php");
                return;
            }
            
            $addClass = "\"" . htmlentities($_POST['class']) . "\"";
            $addGrade = "\"" . htmlentities($_POST['grade']) . "\"";
            $add1 = "[$addClass, $addGrade], ";
            
            //if there are no classes for that user yet
            if ($classes == NULL) {
                $pdo->query("UPDATE users SET encoded_classes = '$add1' WHERE email = '$email';");
            }
    
            //if there are existing classes for that user already
            else {
                $newAdd1 = $classes . $add1;
                $pdo->query("UPDATE users SET encoded_classes = '$newAdd1' WHERE email = '$email';");
            }

            //if the grade is not numeric:
            if (!is_numeric(htmlentities($_POST['grade']))) {
                $_SESSION['error'] = 'Grade has to be numeric.';
                header("Location: dashboard.php");
                return;
            } 

            $_SESSION['success'] = 'Class information was added successfully!';
            header("Location: dashboard.php");
            return;
        }

        else {
            $_SESSION['error'] = 'Not all information was filled out.';
            header("Location: dashboard.php");
            return;
        }
        
    }

    //allows user to edit their tasks 
    if (isset($_POST['taskinfo'])) {
        if (strlen(htmlentities($_POST['task'])) > 0 && strlen(htmlentities($_POST['tduedate'])) > 0) {
            $addTask = "\"" . htmlentities($_POST['task']) . "\"";

            $addClass = "\"" . htmlentities($_POST['dropdown1']) . "\"";
            $addNotes = "\"" . htmlentities($_POST['tnotes']) . "\"";
            $addDate = "\"" . htmlentities($_POST['tduedate']) . "\"";
            $add2 = "[$addTask, $addClass, $addNotes, $addDate], ";

            //if there are no tasks for the user yet
            if ($tasks == NULL) {
                $pdo->query("UPDATE users SET tasks = '$add2' WHERE email = '$email';");
            }

            //if there are existing tasks for the user
            else {
                $newAdd2 = $tasks . $add2;
                $pdo->query("UPDATE users SET tasks = '$newAdd2' WHERE email = '$email';");
            }

            $_SESSION['success'] = 'Task Information was added succesfully!';
            header("Location: dashboard.php");
            return;
        }

        else {
            $_SESSION['error'] = 'Not all information was filled out.';
            header("Location: dashboard.php");
            return;
        }
    }

    //allows user to edit their events
    if (isset($_POST['eventinfo'])) {
        if (strlen(htmlentities($_POST['event'])) > 0 && strlen(htmlentities($_POST['eduedate'])) > 0) {
            $addEvent = "\"" . htmlentities($_POST['event']) . "\"";
            $addClass = "\"" . htmlentities($_POST['dropdown2']) . "\"";
            $addNotes = "\"" . htmlentities($_POST['enotes']) . "\"";
            $addDate = "\"" . htmlentities($_POST['eduedate']) . "\"";
            $add3 = "[$addEvent, $addClass, $addNotes, $addDate], ";
            
            //if there are no events for the user yet
            if ($events == NULL) {
                $pdo->query("UPDATE users SET events = '$add3' WHERE email = '$email';");
            }

            //if there are existing events for the user
            else {
                $newAdd3 = $events . $add3;
                $pdo->query("UPDATE users SET events = '$newAdd3' WHERE email = '$email';");
            }

            $_SESSION['success'] = 'Event Information was added succesfully!';
            header("Location: dashboard.php");
            return;
        }

        else {
            $_SESSION['error'] = 'Not all information was filled out.';
            header("Location: dashboard.php");
            return;
        }
    }

    //allows user to edit their zoom links
    if (isset($_POST['link_stuff'])) {
        if (strlen(htmlentities($_POST['link_info'])) > 0 && strlen(htmlentities($_POST['link'])) > 0) {
            $addInfo = "\"" . htmlentities($_POST['link_info']) . "\"";
            $addLink = "\"" . htmlentities($_POST['link']) . "\"";
            $add4 = "[$addInfo, $addLink], ";
            
            //if there are no events for the user yet
            if ($links == NULL) {
                $pdo->query("UPDATE users SET links = '$add4' WHERE email = '$email';");
            }

            //if there are existing events for the user
            else {
                $newAdd4 = $links . $add4;
                $pdo->query("UPDATE users SET links = '$newAdd4' WHERE email = '$email';");
            }

            $_SESSION['success'] = 'Link Information was added succesfully!';
            header("Location: dashboard.php");
            return;
        }

        else {
            $_SESSION['error'] = 'Not all information was filled out.';
            header("Location: dashboard.php");
            return;
        }
    }

    //allows user to edit their google calendar link
    if (isset($_POST['url_stuff'])) {
        if (strlen(htmlentities($_POST['url'])) > 0) {
            $addInfo = htmlentities($_POST['url']);
            
            $pdo->query("UPDATE users SET calendar_link = '$addInfo' WHERE email = '$email';");

            $_SESSION['success'] = 'Google Calendar Link was added succesfully!';
            header("Location: dashboard.php");
            return;
        }

        else {
            $_SESSION['error'] = 'Not all information was filled out.';
            header("Location: dashboard.php");
            return;
        }
    }

    if (isset($_POST['url_delete'])) {
        $pdo->query("UPDATE users SET calendar_link = NULL WHERE email = '$email';");
        header("Location: dashboard.php");
        return;
    }

// EDIT PROFILE ---------------------------------------------------------------------

?>

<div class="basics">
<head>
    <link rel="stylesheet" type="text/css" href="css/dash.css">
    <title>Dashboard</title>
</head>
</div>

<br>
<br>
<body>
    <div class="form" id="forms">
    <form method="POST">
        <!--Classinfo: -->  
        <label for="class&grade">Enter a class:</label>
        <input type="text" name="class" id="class&grade1" style="1px solid orange; background: orange;"><br/>
        <label for="class&grade">Enter the grade for that class:</label>
        <input type="text" name="grade" id="class&grade2" style="1px solid orange; background: orange;"><br/>
        <input type="submit" name="classgrade" value="Submit" style="font-family: cursive;"><br/><br/>

        <!--Tasks: -->
        <label for="task">Task:</label>
        <input type="text" name="task" id="task" style="1px solid orange; background: orange;"><br/>

        <label for="dropdown1">Class:</label>

        <?php
            echo '<select name = "dropdown1" style="1px solid orange; background: orange;">';
            echo '<option value = "N/A" selected>N/A</option>';
            for ($x = 0; $x < sizeof($_SESSION['classes']); $x++) {
                $current = $_SESSION['classes'][$x];
                echo "<option value = \"$current\">$current</option>";
            }

            echo '</select>';

        ?>
        
        <br/>
        <label for="tnotes">Notes:</label>
        <input type="text" name="tnotes" id="tnotes" style="1px solid orange; background: orange;"><br/>
        <label for="tduedate">Due Date:</label>
        <input type="date" name="tduedate" id="tduedate" style="1px solid orange; background: orange;"><br/>
        <input type="submit" name="taskinfo" value="Submit" style="font-family: cursive;"> <br/><br/>

        <!--Events: -->
        <label for="event">Event:</label>
        <input type="text" name="event" id="event" style="1px solid orange; background: orange;"><br/>
        <label for="dropdown2">Class:</label>

        <?php
            echo '<select name = "dropdown2" style="outline: 1px solid orange; background: orange;">';
            echo '<option value = "N/A" selected>N/A</option>';
            for ($x = 0; $x < sizeof($_SESSION['classes']); $x++) {
                $current = $_SESSION['classes'][$x];
                echo "<option value = \"$current\">$current</option>";
            }
            
            echo '</select>';

        ?>

        <br/>
        <label for="enotes">Notes:</label>
        <input type="text" name="enotes" id="enotes" style="outline: 1px solid orange; background: orange;"><br/>
        <label for="eduedate">Date:</label>
        <input type="date" name="eduedate" id="eduedate" style="1px solid orange; background: orange;"><br/>
        <input type="submit" name="eventinfo" value="Submit" style="font-family: cursive;"> <br/><br/>

        <!--Links: -->  
        <label for="link_info">Notes about the Website Link:</label>
        <input type="text" name="link_info" id="link_info" style="1px solid orange; background: orange;"><br/>
        <label for="link">Link:</label>
        <input type="text" name="link" id="link" style="1px solid orange; background: orange;"><br/>
        <input type="submit" name="link_stuff" value="Submit" style="font-family: cursive;"><br/><br/>
    
        <!-- Google calendar link: -->
        <label for="url">Google Calendar shared URL:</label>
        <input type="text" name="url" id="url" style="1px solid orange; background: orange;"><br/>
        <input type="submit" name="url_stuff" value="Submit" style="font-family: cursive;">
        <input type="submit" name="url_delete" value="Delete Existing Calendar URL" style="font-family: cursive;"><br/><br/>

    </form>
    </div>

    <script src="js/main.js"></script>

    <?php
        //display Google Calendar:
        if (strlen($calendar) > 0) {
            echo '<iframe src="'.$calendar.'" frameborder="0" scrolling="no" class="cal"></iframe>';
        }
    ?>
</body>