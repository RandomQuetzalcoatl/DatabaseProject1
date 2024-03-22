<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Zipcode for Directors who shot there</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2>Search Zipcode for Directors who shot there</h2>
        <form action="" method="get">
            <div class="form-group">
            <label for="zip">Zipcode:</label>
            <input type="text" class="form-control" id="zip" name="zip">
            </div>
            <button type="submit" class="btn btn-primary">Search</button>
        </form>
        <table class="table">
            <thead>
                <tr>
                    <th>Directors</th>
                    <th>Series</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $servername = "localhost";
                $username = "root";
                $password = "";
                $dbname = "COSI127b";

                $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

                if (isset($_GET['zip'])) {
                    $zip = $_GET['zip'];
                    $stmt = $pdo->prepare("SELECT DISTINCT p.name AS dname, mp.name AS series_name
                    FROM People p
                    JOIN Role r ON p.id = r.pid
                    JOIN Series s ON r.mpid = s.mpid
                    JOIN Location l ON s.mpid = l.mpid
                    JOIN MotionPicture mp ON s.mpid = mp.id
                    WHERE l.zip = :zip AND r.role_name = 'Director';");
                    $stmt->bindParam(':zip', $zip);
                    $stmt->execute();
                    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($results as $row) {
                        echo "<tr>
                                <td>".htmlspecialchars($row['dname'])."</td>
                                <td>".htmlspecialchars($row['series_name'])."</td>
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
