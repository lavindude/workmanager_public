<?php
    require_once "pdo.php";
    session_start();

    if (!isset($_SESSION['name'])) {
        header("Location: error.html");
        return;
    }

    $email = $_SESSION['name'];
    $classes = '';
    $data = $pdo->query("SELECT encoded_classes FROM users WHERE email='$email';");
    while ($row = $data->fetch(PDO::FETCH_ASSOC)) {
        $classes = $row['encoded_classes'];
    }

    $newClasses = substr($classes, 0, strlen($classes) - 2);
    $list1 = '{ "data" : [' . $newClasses . '] }';

    $class_list = json_decode($list1, true);

    if ((isset($_POST['classgrade'])) && (strlen(htmlentities($_POST['class']))) > 0 && (strlen(htmlentities($_POST['grade']))) > 0) {
        $newClass = htmlentities($_POST['class']);
        $newGrade = htmlentities($_POST['grade']);
        
        $newList = '';
        for ($x = 0; $x < sizeof($class_list['data']); $x++) {
            if ($class_list['data'][$x][0] != htmlentities($_GET['class'])) {
                $push = '["'.$class_list['data'][$x][0].'", "'.$class_list['data'][$x][1].'"], ';
                $newList .= $push;
            }

            else {
                $push = '["'.$newClass.'", "'.$newGrade.'"], ';
                $newList .= $push;
            }
        }

        $pdo->query("UPDATE users SET encoded_classes = '$newList' WHERE email = '$email';");

        $_SESSION['success'] = 'Class information was successfully changed!';
        header("Location: dashboard.php");
        return;
    }
    
    else if (isset($_POST['delete'])) {
        $newList = '';
        for ($x = 0; $x < sizeof($class_list['data']); $x++) {
            if ($class_list['data'][$x][0] != htmlentities($_GET['class'])) {
                $push = '["'.$class_list['data'][$x][0].'", "'.$class_list['data'][$x][1].'"], ';
                $newList .= $push;
            }
        }

        $pdo->query("UPDATE users SET encoded_classes = '$newList' WHERE email = '$email';");

        $_SESSION['success'] = 'Class information was successfully changed!';
        header("Location: dashboard.php");
        return;
    }

    else if (isset($_POST['cancel'])) {
        header("Location: dashboard.php");
        return;
    }

?>

<head>
    <title>Edit Class</title>
    <link rel="stylesheet" type="text/css" href="css/template.css">
</head>

<body>
    <form method="POST" id="forms">
        <label for="class&grade">Edit class name:</label>
        <?php 
            echo('<input type="text" name="class" id="class&grade" value="'.htmlentities($_GET['class']).'"><br/>');
        ?>

        <label for="class&grade">Edit the grade for that class:</label>
        <?php
            echo('<input type="text" name="grade" id="class&grade" value="'.htmlentities($_GET['percentage']).'"><br/>');
        ?>

        <input type="submit" name="classgrade" value="Save">
        <input type="submit" name="delete" value="Delete Class">
        <input type="submit" name="cancel" value="Cancel">
        

    </form>

</body>