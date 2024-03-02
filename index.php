<?php
// Start the session
session_start();

// Check if the user is logged in, otherwise redirect to login page
$userLoggedIn = isset($_SESSION['user_email']);

// Database connection details
$servername = "localhost";
$username = "root";
$password = "new_password";
$dbname = "COSI127b";

// Connect to database
try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // If user is logged in, fetch their likes
    $userLikes = [];
    if ($userLoggedIn) {
        $stmt = $conn->prepare("SELECT MotionPicture.name FROM Likes JOIN MotionPicture ON Likes.mpid = MotionPicture.mpid WHERE Likes.email = ?");
        $stmt->execute([$_SESSION['user_email']]);
        $userLikes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
     <!-- Bootstrap CSS -->
     <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <!-- Bootstrap JS dependencies -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>COSI 127b</title>
</head>
<body>
    <div class="container">
        <!-- Welcome message -->
        <?php if ($userLoggedIn): ?>
            <h1 class="mt-5">Welcome, <?php echo htmlspecialchars($_SESSION['user_email']); ?></h1>
        <?php else: ?>
            <h1 class="mt-5">Welcome, Guest</h1>
            <p>Please <a href="login.php">login</a> to see your liked movies.</p>
        <?php endif; ?>
    <div class="container">
        <h1 class="mt-5">Database Management Dashboard</h1>
        <p class="lead">Select a category to view or manage:</p>
        <div class="list-group">
            <a href="like.php" class="list-group-item list-group-item-action">Show my Likes</a>
            <a href="userprofile.php" class="list-group-item list-group-item-action">User Profile</a>
            <a href="user.php" class="list-group-item list-group-item-action">Show all Users (Temporary exist, won't exist for users)</a>
            <a href="motionpicture.php?sort=name_asc.php" class="list-group-item list-group-item-action">View all Motion Pictures</a>
            <a href="movie.php" class="list-group-item list-group-item-action">View all Movies</a>
            <a href="series.php" class="list-group-item list-group-item-action">View all Series</a>
            <a href="people.php" class="list-group-item list-group-item-action">View all People</a>
            <a href="role.php" class="list-group-item list-group-item-action">View by Roles</a>
            <a href="award.php" class="list-group-item list-group-item-action">View by Awards</a>
            <a href="genre.php" class="list-group-item list-group-item-action">View by Genres</a>
            <a href="location.php" class="list-group-item list-group-item-action">View by Locations</a>
        </div>
        <!-- Login/Register Button -->
        <div class="mt-4">
            <a href="login.php" class="btn btn-primary">Login/Register</a>
            <a href="movie.php" class="btn btn-primary">View all Movies</a>
            <a href="role.php?role_name=Actor" class="btn btn-primary">View all Actors</a>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
