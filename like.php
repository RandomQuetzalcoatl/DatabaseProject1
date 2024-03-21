<?php
session_start(); // Start the session at the beginning of the script.

// Check if the user is logged in, if not then redirect to login page
if(!isset($_SESSION["user_email"])){
    header("location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Liked Motion Pictures</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1 class="mt-5">Your Liked Motion Pictures</h1>
        <table class="table">
            <thead class="thead-light">
                <tr>
                    <th>Motion Picture ID</th>
                    <th>Motion Picture Name</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Database connection details
                $servername = "localhost";
                $username = "root";
                $password = "";
                $dbname = "COSI127b";
                $userEmail = $_SESSION["user_email"]; // Get the user's email from the session.

                try {
                    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    // SQL query to fetch likes for the logged-in user
                    $stmt = $conn->prepare("SELECT MotionPicture.id, MotionPicture.name FROM Likes JOIN MotionPicture ON Likes.mpid = MotionPicture.id WHERE Likes.email = :email");
                    $stmt->bindParam(':email', $userEmail);
                    $stmt->execute();

                    // Set the resulting array to associative
                    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($results as $row) {
                        echo "<tr>
                                <td>" . htmlspecialchars($row['mpid']) . "</td>
                                <td>" . htmlspecialchars($row['name']) . "</td>
                              </tr>";
                    }
                } catch(PDOException $e) {
                    echo "Error: " . $e->getMessage();
                }
                $conn = null; // Close connection
                ?>
            </tbody>
        </table>
        <a href="index.php" class="btn btn-primary">Back to Dashboard</a>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
