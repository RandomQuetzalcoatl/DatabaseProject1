<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>American Producers</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2>Find Producers from USA with Specific Box Office and Budget</h2>
        <form action="" method="get">
            <div class="form-group">
                <label for="boxOffice">Minimum Box Office Collection ($):</label>
                <input type="number" class="form-control" id="boxOffice" name="boxOffice" value="<?php echo isset($_GET['boxOffice']) ? htmlspecialchars($_GET['boxOffice']) : ''; ?>">
            </div>
            <div class="form-group">
                <label for="budget">Maximum Budget ($):</label>
                <input type="number" class="form-control" id="budget" name="budget" value="<?php echo isset($_GET['budget']) ? htmlspecialchars($_GET['budget']) : ''; ?>">
            </div>
            <button type="submit" class="btn btn-primary">Search</button>
        </form>
        <table class="table mt-3">
            <thead>
                <tr>
                    <th>Producer Name</th>
                    <th>Movie Name</th>
                    <th>Box Office Collection</th>
                    <th>Budget</th>
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

                    $boxOffice = isset($_GET['boxOffice']) ? (int)$_GET['boxOffice'] : 0;
                    $budget = isset($_GET['budget']) ? (int)$_GET['budget'] : PHP_INT_MAX;

                    $stmt = $pdo->prepare("SELECT People.name AS producer_name, MotionPicture.name AS movie_name, Movie.boxoffice_collection, MotionPicture.budget 
                                           FROM People 
                                           INNER JOIN Role ON People.id = Role.pid 
                                           INNER JOIN MotionPicture ON Role.mpid = MotionPicture.id 
                                           INNER JOIN Movie ON MotionPicture.id = Movie.mpid 
                                           WHERE People.nationality = 'USA' AND Role.role_name = 'Producer' 
                                           AND Movie.boxoffice_collection >= :boxOffice AND MotionPicture.budget <= :budget");
                    $stmt->bindParam(':boxOffice', $boxOffice, PDO::PARAM_INT);
                    $stmt->bindParam(':budget', $budget, PDO::PARAM_INT);
                    $stmt->execute();
                    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    if ($results) {
                        foreach ($results as $row) {
                            echo "<tr>
                                    <td>".htmlspecialchars($row['producer_name'])."</td>
                                    <td>".htmlspecialchars($row['movie_name'])."</td>
                                    <td>$".htmlspecialchars(number_format($row['boxoffice_collection']))."</td>
                                    <td>$".htmlspecialchars(number_format($row['budget']))."</td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4'>No results found.</td></tr>";
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
