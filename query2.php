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
                <label for="name">Motion Picture Name:</label>
                <input type="text" class="form-control" id="name" name="name">
            </div>
            <button type="submit" class="btn btn-primary">Search</button>
        </form>
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Rating</th>
                    <th>Production</th>
                    <th>Budget</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $servername = "localhost";
                $username = "root";
                $password = "";
                $dbname = "COSI127b";

                $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

                if (isset($_GET['name'])) {
                    $stmt = $pdo->prepare("SELECT name, rating, production, budget FROM MotionPicture WHERE name = :name");
                    $stmt->bindParam(':name', $searchName);
                    $searchName = $_GET['name'];
                    $stmt->execute();
                    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($results as $row) {
                        echo "<tr>
                                <td>".htmlspecialchars($row['name'])."</td>
                                <td>".htmlspecialchars($row['rating'])."</td>
                                <td>".htmlspecialchars($row['production'])."</td>
                                <td>".htmlspecialchars($row['budget'])."</td>
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
