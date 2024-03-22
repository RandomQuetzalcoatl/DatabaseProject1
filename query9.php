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
    <h2>People with Multiple Roles</h2>
        <form action="" method="get">
            <div class="form-group">
                <label for="rating">Minimum Rating:</label>
                <input type="number" class="form-control" id="rating" name="rating" step="0.1">
            </div>
            <button type="submit" class="btn btn-primary">Search</button>
        </form>
        <table class="table">
            <thead>
                <tr>
                        <th>Person Name</th>
                        <th>Motion Picture Name</th>
                        <th>Role Count</th>
                    </tr>
            </thead>
            <tbody>
                <?php
                $servername = "localhost";
                $username = "root";
                $password = "";
                $dbname = "COSI127b";

                $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

                if (isset($_GET['rating'])) {
                    $rating = $_GET['rating'];
                    $stmt = $pdo->prepare("SELECT p.name AS pname, m.name AS mname, COUNT(*) AS role_count
                                            FROM People p
                                            JOIN Role r ON p.id = r.pid
                                            JOIN MotionPicture m ON r.mpid = m.id
                                            WHERE m.rating > :rating
                                            GROUP BY p.id, m.id
                                            HAVING COUNT(*) > 1");
                    $stmt->bindParam(':rating', $rating);
                    $stmt->execute();
                    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($results as $row) {
                        echo "<tr>
                                <td>".htmlspecialchars($row['pname'])."</td>
                                <td>".htmlspecialchars($row['mname'])."</td>
                                <td>".htmlspecialchars($row['role_count'])."</td>
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
