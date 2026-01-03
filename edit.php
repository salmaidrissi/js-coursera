<?php
session_start();
require_once "pdo.php";

if (!isset($_SESSION['name'])) {
    die('Not logged in');
}

if (!isset($_GET['profile_id'])) {
    $_SESSION['error'] = "Missing profile_id";
    header("Location: index.php");
    return;
}

// Make sure this profile belongs to the logged in user
$stmt = $pdo->prepare("SELECT * FROM Profile WHERE profile_id = :pid AND user_id = :uid");
$stmt->execute(array(
    ':pid' => $_GET['profile_id'],
    ':uid' => $_SESSION['user_id']
));
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if ($row === false) {
    $_SESSION['error'] = "Could not load profile";
    header("Location: index.php");
    return;
}

if (isset($_POST['first_name']) && isset($_POST['last_name']) && 
    isset($_POST['email']) && isset($_POST['headline']) && 
    isset($_POST['summary'])) {
    
    // Data validation
    if (strlen($_POST['first_name']) < 1 || strlen($_POST['last_name']) < 1 || 
        strlen($_POST['email']) < 1 || strlen($_POST['headline']) < 1 || 
        strlen($_POST['summary']) < 1) {
        $_SESSION['error'] = "All fields are required";
        header("Location: edit.php?profile_id=".$_POST['profile_id']);
        return;
    }
    
    if (strpos($_POST['email'], '@') === false) {
        $_SESSION['error'] = "Email address must contain @";
        header("Location: edit.php?profile_id=".$_POST['profile_id']);
        return;
    }
    
    // Update the profile
    $stmt = $pdo->prepare('UPDATE Profile SET 
        first_name = :fn, last_name = :ln, email = :em, 
        headline = :he, summary = :su 
        WHERE profile_id = :pid AND user_id = :uid');
    $stmt->execute(array(
        ':pid' => $_POST['profile_id'],
        ':uid' => $_SESSION['user_id'],
        ':fn' => $_POST['first_name'],
        ':ln' => $_POST['last_name'],
        ':em' => $_POST['email'],
        ':he' => $_POST['headline'],
        ':su' => $_POST['summary'])
    );
    
    $_SESSION['success'] = "Profile updated";
    header("Location: index.php");
    return;
}
?>
<!DOCTYPE html>
<html>
<head>
     <title>594aae7e's Profile Edit</title>
    <?php require_once "bootstrap.php"; ?>
</head>
<body>
<div class="container">
    <h1>Editing Profile for <?= htmlentities($_SESSION['name']) ?></h1>
    
    <?php
    if (isset($_SESSION['error'])) {
        echo '<p style="color: red">'.htmlentities($_SESSION['error']).'</p>';
        unset($_SESSION['error']);
    }
    ?>
    
    <form method="POST">
        <input type="hidden" name="profile_id" value="<?= $row['profile_id'] ?>">
        <p>First Name: <input type="text" name="first_name" size="60" 
            value="<?= htmlentities($row['first_name']) ?>"></p>
        <p>Last Name: <input type="text" name="last_name" size="60" 
            value="<?= htmlentities($row['last_name']) ?>"></p>
        <p>Email: <input type="text" name="email" size="30" 
            value="<?= htmlentities($row['email']) ?>"></p>
        <p>Headline: <br/><input type="text" name="headline" size="80" 
            value="<?= htmlentities($row['headline']) ?>"></p>
        <p>Summary: <br/><textarea name="summary" rows="8" cols="80"><?= 
            htmlentities($row['summary']) ?></textarea></p>
        <input type="submit" value="Save">
        <input type="button" onclick="location.href='index.php'; return false;" value="Cancel">
    </form>
</div>
</body>
</html>