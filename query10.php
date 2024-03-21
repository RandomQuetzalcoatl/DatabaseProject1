<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Top Thriller Movies in Boston</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2>Top 2 Rated Thriller Movies Shot Exclusively in Boston</h2>
        <table class="table mt-3">
            <thead>
                <tr>
                    <th>Movie Name</th>
                    <th>Rating</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $servername = "localhost";
                $username = "root";
                $password = "";
                $dbname = "COSI127b";

                try {
                    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    $stmt = $pdo->prepare("SELECT MotionPicture.name, MotionPicture.rating
                                           FROM MotionPicture 
                                           JOIN Location ON MotionPicture.id = Location.mpid
                                           JOIN Genre ON MotionPicture.id = Genre.mpid
                                           WHERE Genre.genre_name = 'Thriller' 
                                           AND Location.city = 'Boston' 
                                           AND (SELECT COUNT(*) FROM Location WHERE mpid = MotionPicture.id) = 1
                                           ORDER BY MotionPicture.rating DESC 
                                           LIMIT 2");
                    $stmt->execute();
                    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    if ($results) {
                        foreach ($results as $row) {
                            echo "<tr>
                                    <td>".htmlspecialchars($row['name'])."</td>
                                    <td>".htmlspecialchars($row['rating'])."</td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='2'>No results found.</td></tr>";
                    }
                } catch(PDOException $e) {
                    echo "Error: " . $e->getMessage();
                }
                $pdo = null; // Close connection
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
