<?php
session_start();
require_once "pdo.php";

// Flash message handling
if (isset($_SESSION['error'])) {
    $error = $_SESSION['error'];
    unset($_SESSION['error']);
}
if (isset($_SESSION['success'])) {
    $success = $_SESSION['success'];
    unset($_SESSION['success']);
}

// Fetch all profiles
$stmt = $pdo->query(
    "SELECT Profile.profile_id, Profile.first_name, 
            Profile.last_name, Profile.headline, 
            Profile.user_id, users.name 
     FROM Profile 
     JOIN users ON Profile.user_id = users.user_id"
);
$profiles = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    
    <title>5b33c4d9</title>
    <?php require_once "bootstrap.php"; ?>
   
</head>
<body>
<div class="container">

<h1>Profiles</h1>

<?php
if (isset($error)) {
    echo '<p style="color:red;">'.htmlentities($error).'</p>';
}
if (isset($success)) {
    echo '<p style="color:green;">'.htmlentities($success).'</p>';
}

if (!isset($_SESSION['name'])) {
    echo '<p><a href="login.php">Please log in</a></p>';
} else {
    echo '<p><a href="logout.php">Logout</a></p>';
}
?>

<?php if (count($profiles) > 0) : ?>
<table border="1">
    <tr>
        <th>Name</th>
        <th>Headline</th>
        <?php if (isset($_SESSION['user_id'])) : ?>
            <th>Action</th>
        <?php endif; ?>
    </tr>

    <?php foreach ($profiles as $profile) : ?>
    <tr>
        <td>
            <a href="view.php?profile_id=<?= $profile['profile_id'] ?>">
                <?= htmlentities($profile['first_name'].' '.$profile['last_name']) ?>
            </a>
        </td>
        <td><?= htmlentities($profile['headline']) ?></td>

        <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $profile['user_id']) : ?>
        <td>
            <a href="edit.php?profile_id=<?= $profile['profile_id'] ?>">Edit</a> /
            <a href="delete.php?profile_id=<?= $profile['profile_id'] ?>">Delete</a>
        </td>
        <?php elseif (isset($_SESSION['user_id'])) : ?>
        <td></td>
        <?php endif; ?>
    </tr>
    <?php endforeach; ?>
</table>
<?php else : ?>
<p>No profiles found</p>
<?php endif; ?>

<?php if (isset($_SESSION['name'])) : ?>
<p><a href="add.php">Add New Entry</a></p>
<?php endif; ?>

</div>
</body>
</html>
