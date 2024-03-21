<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>People with Multiple Awards</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2>Find People with Multiple Awards for a Single Motion Picture</h2>
        <form action="" method="get">
            <div class="form-group">
                <label for="k">Minimum Number of Awards:</label>
                <input type="number" class="form-control" id="k" name="k" value="<?php echo isset($_GET['k']) ? intval($_GET['k']) : ''; ?>">
            </div>
            <button type="submit" class="btn btn-primary">Search</button>
        </form>
        <table class="table mt-3">
            <thead>
                <tr>
                    <th>Person Name</th>
                    <th>Motion Picture Name</th>
                    <th>Award Year</th>
                    <th>Award Count</th>
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

                    if (isset($_GET['k']) && $_GET['k'] != '') {
                        $k = intval($_GET['k']);
                        $stmt = $pdo->prepare("SELECT People.name AS person_name, MotionPicture.name AS motion_picture_name, Awards.award_year, COUNT(*) AS award_count 
                                               FROM Awards 
                                               JOIN People ON Awards.pid = People.id 
                                               JOIN MotionPicture ON Awards.mpid = MotionPicture.id 
                                               GROUP BY People.id, MotionPicture.id, Awards.award_year 
                                               HAVING COUNT(*) > :k");
                        $stmt->bindParam(':k', $k, PDO::PARAM_INT);
                        $stmt->execute();
                        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        if ($results) {
                            foreach ($results as $row) {
                                echo "<tr>
                                        <td>".htmlspecialchars($row['person_name'])."</td>
                                        <td>".htmlspecialchars($row['motion_picture_name'])."</td>
                                        <td>".htmlspecialchars($row['award_year'])."</td>
                                        <td>".htmlspecialchars($row['award_count'])."</td>
                                      </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='4'>No results found.</td></tr>";
                        }
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
