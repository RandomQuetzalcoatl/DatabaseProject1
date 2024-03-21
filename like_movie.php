<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Like a Movie</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1 class="mt-5">Like a Movie</h1>
        <form action="like_movie.php" method="POST">
            <div class="form-group">
                <label for="userEmail">Your Email:</label>
                <input type="email" class="form-control" id="userEmail" name="userEmail" required>
            </div>
            <div class="form-group">
                <label for="movieSelect">Select Movie:</label>
                <select class="form-control" id="movieSelect" name="movieId">
                    <?php
                    // Database connection
                    $servername = "localhost";
                    $username = "root";
                    $password = "";
                    $dbname = "COSI127b";

                    try {
                        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                        $stmt = $conn->prepare("SELECT id, name FROM MotionPicture");
                        $stmt->execute();
                        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        foreach ($results as $row) {
                            echo "<option value='{$row['mpid']}'>{$row['name']}</option>";
                        }
                    } catch(PDOException $e) {
                        echo "Error: " . $e->getMessage();
                    }
                    ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary" name="likeMovie">Like Movie</button>
        </form>
    </div>

    <?php
    if (isset($_POST['likeMovie'])) {
        $userEmail = $_POST['userEmail'];
        $movieId = $_POST['movieId'];

        try {
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Check if the user already liked the movie
            $checkStmt = $conn->prepare("SELECT * FROM Likes WHERE uemail = :email AND mpid = :mpid");
            $checkStmt->execute(['email' => $userEmail, 'mpid' => $movieId]);
            $alreadyLiked = $checkStmt->fetch();

            if (!$alreadyLiked) {
                // Insert new like
                $likeStmt = $conn->prepare("INSERT INTO Likes (uemail, mpid) VALUES (:email, :mpid)");
                $likeStmt->execute(['email' => $userEmail, 'mpid' => $movieId]);
                echo "<div class='alert alert-success' role='alert'>You liked the movie successfully!</div>";
            } else {
                echo "<div class='alert alert-info' role='alert'>You've already liked this movie.</div>";
            }
        } catch(PDOException $e) {
            echo "<div class='alert alert-danger' role='alert'>Error: " . $e->getMessage() . "</div>";
        }
    }
    ?>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
