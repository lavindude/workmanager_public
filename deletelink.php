<?php
    require_once "pdo.php";
    session_start();

    $email = $_SESSION['name'];
    $links = '';
    $data = $pdo->query("SELECT links FROM users WHERE email='$email';");
    while ($row = $data->fetch(PDO::FETCH_ASSOC)) {
        $links = $row['links'];
    }

    $newLinks = substr($links, 0, strlen($links) - 2);
    $list1 = '{ "data" : [' . $newLinks . '] }';

    $link_list = json_decode($list1, true);
    
    $newList = '';
    for ($x = 0; $x < sizeof($link_list['data']); $x++) {
        if ($link_list['data'][$x][1] != htmlentities($_GET['link'])) {
            $push = '["'.$link_list['data'][$x][0].'", "'.$link_list['data'][$x][1].'"], ';
            $newList .= $push;
        }
    }

    $pdo->query("UPDATE users SET links = '$newList' WHERE email = '$email';");

    $_SESSION['success'] = '<span style="color:#000000";>Link information was successfully changed!</span>';
    header("Location: dashboard.php");
    return;

?>

<head>
    <link rel="stylesheet" type="text/css" href="css/template.css">
</head>
