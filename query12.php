<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actors in Both Marvel and Warner Bros Productions</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2>Actors in Both Marvel and Warner Bros Productions</h2>
        <table class="table mt-3">
            <thead>
                <tr>
                    <th>Actor Name</th>
                    <th>Motion Picture Name</th>
                    <th>Production</th>
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

                    $stmt = $pdo->prepare("SELECT DISTINCT p1.name AS actor_name, mp1.name AS movie_name, mp1.production 
                                           FROM People p1 
                                           JOIN Role r1 ON p1.id = r1.pid 
                                           JOIN MotionPicture mp1 ON r1.mpid = mp1.id 
                                           WHERE mp1.production IN ('Marvel', 'Warner Bros') 
                                           AND EXISTS (
                                               SELECT * FROM People p2 
                                               JOIN Role r2 ON p2.id = r2.pid 
                                               JOIN MotionPicture mp2 ON r2.mpid = mp2.id 
                                               WHERE p2.id = p1.id AND mp2.production IN ('Marvel', 'Warner Bros') 
                                               AND mp2.production != mp1.production
                                           ) 
                                           ORDER BY p1.name, mp1.production");
                    $stmt->execute();
                    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    if ($results) {
                        foreach ($results as $row) {
                            echo "<tr>
                                    <td>".htmlspecialchars($row['actor_name'])."</td>
                                    <td>".htmlspecialchars($row['movie_name'])."</td>
                                    <td>".htmlspecialchars($row['production'])."</td>
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
