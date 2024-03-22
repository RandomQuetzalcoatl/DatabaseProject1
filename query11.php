<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Motion Picture</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2>Search Motion Picture</h2>
        <form action="" method="get">
        <div class="form-group">
                <label for="min_likes">Minimum Likes:</label>
                <input type="number" class="form-control" id="min_likes" name="min_likes">
            </div>
            <div class="form-group">
                <label for="max_age">Maximum Age:</label>
                <input type="number" class="form-control" id="max_age" name="max_age">
            </div>
            <button type="submit" class="btn btn-primary">Search</button>
        </form>

        <table class="table">
            <thead>
                <tr>
                    <th>Movie Name</th>
                    <th>Like Count</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $servername = "localhost";
                $username = "root";
                $password = "";
                $dbname = "COSI127b";

                $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

                if (isset($_GET['min_likes']) && isset($_GET['max_age'])) {
                    $min_likes = $_GET['min_likes'];
                    $max_age = $_GET['max_age'];
                    
                    $stmt = $pdo->prepare("SELECT m.name AS mname, COUNT(*) AS like_count
                                            FROM MotionPicture m
                                            JOIN Likes l ON m.id = l.mpid
                                            JOIN User u ON l.uemail = u.email
                                            WHERE u.age < :max_age
                                            GROUP BY m.id
                                            HAVING COUNT(*) > :min_likes");
                    $stmt->bindParam(':min_likes', $min_likes);
                    $stmt->bindParam(':max_age', $max_age);
                    $stmt->execute();
                    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($results as $row) {
                        echo "<tr>
                                <td>".htmlspecialchars($row['mname'])."</td>
                                <td>".htmlspecialchars($row['like_count'])."</td>
                              </tr>";
                    }
                }
                ?>
            </tbody>
        </table>
        <a href="index.php" class="btn btn-primary">Back to Dashboard</a>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
