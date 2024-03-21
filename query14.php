<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Top 5 Movies by Cast Size</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2>Top 5 Movies with the Largest Casts</h2>
        <table class="table mt-3">
            <thead>
                <tr>
                    <th>Movie Name</th>
                    <th>People Count</th>
                    <th>Role Count</th>
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

                    $stmt = $pdo->prepare("SELECT MotionPicture.name AS movie_name, COUNT(DISTINCT Role.pid) AS people_count, COUNT(*) AS role_count 
                                           FROM MotionPicture 
                                           JOIN Role ON MotionPicture.id = Role.mpid 
                                           GROUP BY MotionPicture.id 
                                           ORDER BY COUNT(DISTINCT Role.pid) DESC, COUNT(*) DESC 
                                           LIMIT 5");
                    $stmt->execute();
                    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    if ($results) {
                        foreach ($results as $row) {
                            echo "<tr>
                                    <td>".htmlspecialchars($row['movie_name'])."</td>
                                    <td>".htmlspecialchars($row['people_count'])."</td>
                                    <td>".htmlspecialchars($row['role_count'])."</td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='3'>No results found.</td></tr>";
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
