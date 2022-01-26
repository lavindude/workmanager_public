<?php
    require_once "pdo.php";
    session_start();

    if (!isset($_SESSION['name'])) {
        header("Location: error.html");
        return;
    }

    print('Are you sure you want to delete ' . $_SESSION['name'] . "?");
    echo '</br> </br>';

    $email = $_SESSION['name'];
    $data = $pdo->query("SELECT password FROM users WHERE email='$email';");
    $password = '';
    while ($row = $data->fetch(PDO::FETCH_ASSOC)) {
        $password = $row['password'];
    }

    if (isset($_POST['yes'])) {
        if (htmlentities($_POST['pass']) == $password) {
            $pdo->query("DELETE FROM users WHERE email = '$email';");
            unset($_SESSION['name']);
            echo '<script> alert("Account successfully deleted!"); location.href = "login.php";</script>';
        }
    }

    else if (isset($_POST['no'])) {
        header("Location: login.php");
        return;
    }

?>

<head>
    <title>Confirm Delete</title>
    <link rel="stylesheet" type="text/css" href="css/template.css">
</head>

<body>
    <form method="POST">
        <label for="pass">Enter to password to confirm:</label>
        <input type="password" name="pass" id="pass"><br/>
        <input type="submit" name="yes" value="Yes">
        <input type="submit" name="no" value="No">
    </form>
</body>