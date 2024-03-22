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
        <h2> Motion Pictures With Higher Rating than Average Comedy Rating</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Rating</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $servername = "localhost";
                $username = "root";
                $password = "";
                $dbname = "COSI127b";

                $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

                $stmt = $pdo->prepare("SELECT m.name as mname, m.rating
                FROM MotionPicture m
                WHERE m.rating > (
                    SELECT AVG(rating)
                    FROM MotionPicture
                    WHERE id IN (
                        SELECT mpid
                        FROM Genre
                        WHERE genre_name = 'Comedy'
                    )
                )
                ORDER BY m.rating DESC");
                $stmt->execute();
                $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($results as $row) {
                    echo "<tr>
                            <td>".htmlspecialchars($row['mname'])."</td>
                            <td>".htmlspecialchars($row['rating'])."</td>
                        </tr>";
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
