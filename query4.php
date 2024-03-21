<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Motion Pictures by Location</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2>Search Motion Pictures by Shooting Location</h2>
        <form action="" method="get">
            <div class="form-group">
                <label for="country">Country:</label>
                <input type="text" class="form-control" id="country" name="country" value="<?php echo isset($_GET['country']) ? htmlspecialchars($_GET['country']) : ''; ?>">
            </div>
            <button type="submit" class="btn btn-primary">Search</button>
        </form>
        <ul class="list-group mt-3">
            <?php
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "COSI127b";

            try {
                $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                if (isset($_GET['country']) && $_GET['country'] != '') {
                    $stmt = $pdo->prepare("SELECT DISTINCT MotionPicture.name FROM MotionPicture INNER JOIN Location ON MotionPicture.id = Location.mpid WHERE Location.country = :country");
                    $country = $_GET['country'];
                    $stmt->bindParam(':country', $country);
                    $stmt->execute();
                    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    if ($results) {
                        foreach ($results as $row) {
                            echo "<li class='list-group-item'>".htmlspecialchars($row['name'])."</li>";
                        }
                    } else {
                        echo "<li class='list-group-item'>No motion pictures found.</li>";
                    }
                }
            } catch(PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
            $pdo = null; // Close connection
            ?>
        </ul>
        <a href="index.php" class="btn btn-primary">Back to Dashboard</a>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
