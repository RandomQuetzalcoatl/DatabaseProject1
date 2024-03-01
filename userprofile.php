<?php
session_start(); // Start the session at the beginning of the script.

// Check if the user is logged in, if not then redirect to login page
if (!isset($_SESSION["user_email"])) {
    header("location: login.php");
    exit;
}

// Prepare database connection variables
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "COSI127b";
$userEmail = $_SESSION["user_email"]; // Get the user's email from the session.

try {
    // Create database connection
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch user information
    $stmtUser = $conn->prepare("SELECT * FROM User WHERE email = :email");
    $stmtUser->bindParam(':email', $userEmail);
    $stmtUser->execute();
    $userInfo = $stmtUser->fetch(PDO::FETCH_ASSOC);

    // Fetch user's liked movies
    $stmtLikes = $conn->prepare("SELECT MotionPicture.mpid, MotionPicture.name FROM Likes JOIN MotionPicture ON Likes.mpid = MotionPicture.mpid WHERE Likes.email = :email");
    $stmtLikes->bindParam(':email', $userEmail);
    $stmtLikes->execute();
    $userLikes = $stmtLikes->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1 class="mt-5">User Profile</h1>
        <?php if ($userInfo): ?>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($userInfo['email']); ?></p>
            <p><strong>Name:</strong> <?php echo htmlspecialchars($userInfo['name']); ?></p>
            <p><strong>Age:</strong> <?php echo htmlspecialchars($userInfo['age']); ?></p>
            <!-- Add more user details here -->
        <?php endif; ?>

        <h2>Liked Motion Pictures</h2>
        <table class="table">
            <thead class="thead-light">
                <tr>
                    <th>Motion Picture ID</th>
                    <th>Name</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($userLikes as $like): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($like['mpid']); ?></td>
                        <td><?php echo htmlspecialchars($like['name']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <a href="index.php" class="btn btn-primary">Back to Dashboard</a>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
