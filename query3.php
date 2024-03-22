<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movies Liked by User</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2>Movies Liked by User</h2>
        <form action="" method="get">
            <div class="form-group">
                <label for="email">User Email:</label>
                <input type="email" class="form-control" id="email" name="email" required>
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

                if (isset($_GET['email'])) {
                    $stmt = $pdo->prepare("SELECT m.name, m.rating, m.production, m.budget
                                           FROM MotionPicture m
                                           JOIN Likes l ON m.id = l.mpid
                                           JOIN User u ON l.uemail = u.email
                                           WHERE u.email = :email");
                    $stmt->bindParam(':email', $userEmail);
                    $userEmail = $_GET['email'];
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
