<?php
    require_once "pdo.php";
    session_start();
    if (isset($_SESSION['name'])) {
        unset($_SESSION['name']);
    }

    if (isset($_POST['cancel'])) {
        header("Location: index.html");
        return;
    }

// LOGIN ----------------------------------------------------------------------------

    if (isset($_POST['log_in'])) {
        if (isset($_POST['email']) && isset($_POST['pass'])) {
            //username and password are required
            if (strlen(htmlentities($_POST['email'])) < 1 || strlen(htmlentities($_POST['pass'])) < 1) {
                $_SESSION['error'] = "Username and password are required";
                header("Location: login.php");
                return;
            } 

            $email = htmlentities($_POST['email']);
            $data = $pdo->query("SELECT password FROM users WHERE email='$email';");
            $password = '';
            while ($row = $data->fetch(PDO::FETCH_ASSOC)) {
                $password = $row['password'];
            }

            //check if password matches username in the database (aka a correct password)
            if (htmlentities($_POST['pass']) == $password) {
                $_SESSION['name'] = $email;
                unset($_SESSION['error']);
                header("Location: dashboard.php");
                return;
            } 
            
            else {
                $_SESSION['error'] = "Incorrect password";
                error_log("Login fail ".$email);
                header("Location: login.php");
                return;
            }

        }
    }

// LOGIN ----------------------------------------------------------------------------^



// CREATE ACCOUNT ----------------------------------------------------------------------------

    //add account to database if account is being created
    else if (isset($_POST['create_account'])) {
        if (strlen(htmlentities($_POST['email'])) < 1 || strlen(htmlentities($_POST['pass'])) < 1) {
            $_SESSION['error'] = "Please enter a username and a password.";
            header("Location: login.php");
            return;
        }

        else {
            //check if email is already in the database
            $email = htmlentities($_POST['email']);
            $check = '';
            $data = $pdo->query("SELECT email FROM users WHERE email='$email';");
            while ($row = $data->fetch(PDO::FETCH_ASSOC)) {
                $check = $row['email'];
            }

            if (isset($_POST['email']) && isset($_POST['pass'])) {
                if ($check != $email) {
                    $password = htmlentities($_POST['pass']);
                    
                    $pdo->query("INSERT INTO users (email, password) VALUES ('$email', '$password');");
                    $_SESSION['success'] = "Account successfully created! Now login with your new account.";
                }

                else {
                    $_SESSION['error'] = "Username is already used. Please use a different username.";
                }
                
            }
        }
        
    }

// CREATE ACCOUNT ----------------------------------------------------------------------------^



// DELETE ACCOUNT ----------------------------------------------------------------------------

    //if deleting an account
    else if (isset($_POST['delete'])) {
        if (strlen(htmlentities($_POST['email'])) < 1 || strlen(htmlentities($_POST['pass'])) < 1) {
            $_SESSION['error'] = "Please enter a username and a password.";
            header("Location: login.php");
            return;
        }

        $email = htmlentities($_POST['email']);
        $data = $pdo->query("SELECT password FROM users WHERE email='$email';");
        $password = '';
        while ($row = $data->fetch(PDO::FETCH_ASSOC)) {
            $password = $row['password'];
        }

        //check if password matches username in the database 
        if (htmlentities($_POST['pass']) == $password) {
            $_SESSION['name'] = $email;
            unset($_SESSION['error']);
            header("Location: delete.php");
            return;
        } 
        
        else {
            $_SESSION['error'] = "Incorrect password";
            header("Location: login.php");
            return;
        }

    }

// DELETE ACCOUNT ----------------------------------------------------------------------------
?>

<head>
    <title>Login</title>
    <h1 id="head1">Please Login</h1>
    <link rel="stylesheet" type="text/css" href="css/template.css">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
</head>

<?php
    //error/success messages
    if (isset($_SESSION['error'])) {
        echo('<script>alert("'.$_SESSION['error'].'")</script>');
        unset($_SESSION['error']);
    }

    else if (isset($_SESSION['success'])) {
        echo('<script>alert("'.$_SESSION['success'].'")</script>');
        unset($_SESSION['success']);
    }
?>

<body>
<form method="POST" id="forms">
    <label for="nam">Username</label>
    <input type="text" name="email" id="nam"><br/>
    <label for="id_1723">Password</label>
    <input type="password" name="pass" id="id_1723"><br/>
    <input type="submit" name="log_in" value="Log In" id="lg">
    <input type="submit" name="create_account" value="Create Account">
    <input type="submit" name="delete" value="Delete Account">
    <input type="submit" name="cancel" value="Cancel" id = can>
</form>

<script language="javascript" src="js/login.js"></script>


<p style="background-color: black; color: white; position: absolute; bottom: 0;">© Work Management 2021</p>

</body>