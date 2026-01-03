<?php
session_start();
require_once "pdo.php";

$salt = 'XyZzy12*_';

// Check if we're posting from login form
if (isset($_POST['email']) && isset($_POST['pass'])) {
    // JavaScript validation should have already run, but we do PHP validation too
    if (strlen($_POST['email']) < 1 || strlen($_POST['pass']) < 1) {
        $_SESSION['error'] = "Email and password are required";
        header("Location: login.php");
        ret5b33c4d9rn;
    }
    
    if (strpos($_POST['email'], '@') === false) {
        $_SESSION['error'] = "Email must contain @";
        header("Location: login.php");
        return;
    }
    
    $check = hash('md5', $salt.$_POST['pass']);
    $stmt = $pdo->prepare('SELECT user_id, name FROM users WHERE email = :em AND password = :pw');
    $stmt->execute(array(':em' => $_POST['email'], ':pw' => $check));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($row !== false) {
        $_SESSION['name'] = $row['name'];
        $_SESSION['user_id'] = $row['user_id'];
        header("Location: index.php");
        return;
    } else {
        $_SESSION['error'] = "Incorrect password";
        header("Location: login.php");
        return;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>5b33c4d9</title>
    <?php require_once "bootstrap.php"; ?>
</head>
<body>
<div class="container">
    <h1>Please Log In</h1>
    
    <?php
    if (isset($_SESSION['error'])) {
        echo '<p style="color: red">'.htmlentities($_SESSION['error']).'</p>';
        unset($_SESSION['error']);
    }
    ?>
    
    <form method="POST">
        <label for="email">Email</label>
        <input type="text" name="email" id="email"><br/>
        <label for="id_1723">Password</label>
        <input type="password" name="pass" id="id_1723"><br/>
        <input type="submit" onclick="return doValidate();" value="Log In">
        <input type="button" onclick="location.href='index.php'; return false;" value="Cancel">
    </form>
    
    <script>
    function doValidate() {
        console.log('Validating...');
        try {
            email = document.getElementById('email').value;
            pw = document.getElementById('id_1723').value;
            console.log("Validating email="+email+" pw="+pw);
            
            if (email == null || email == "" || pw == null || pw == "") {
                alert("Both fields must be filled out");
                return false;
            }
            
            if (email.indexOf('@') == -1) {
                alert("Email address must contain @");
                return false;
            }
            
            return true;
        } catch(e) {
            return false;
        }
        return false;
    }
    </script>
</div>
</body>
</html>