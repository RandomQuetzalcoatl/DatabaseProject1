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
        <h2>Oldest and Youngest Actors to Recieve Award</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Age Award was Recieved</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $servername = "localhost";
                $username = "root";
                $password = "";
                $dbname = "COSI127b";

                $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

                $stmt = $pdo->prepare("SELECT p.name AS actor_name,
                a.award_year - YEAR(p.dob) AS age_received
         FROM People p
         JOIN Role r ON p.id = r.pid
         JOIN Awards a ON r.mpid = a.mpid AND r.pid = a.pid
         WHERE r.role_name = 'Actor'
         GROUP BY p.id
         ORDER BY age_received ASC;");
$stmt->execute();
$youngest = $stmt->fetch(PDO::FETCH_ASSOC);
$stmt2 = $pdo->prepare("SELECT p.name AS actor_name,
a.award_year - YEAR(p.dob) AS age_received
FROM People p
JOIN Role r ON p.id = r.pid
JOIN Awards a ON r.mpid = a.mpid AND r.pid = a.pid
WHERE r.role_name = 'Actor'
GROUP BY p.id
ORDER BY age_received DESC;");
$stmt2->execute();
$oldest = $stmt2->fetch(PDO::FETCH_ASSOC);

echo "<tr>
        <td>".htmlspecialchars($youngest['actor_name'])."</td>
        <td>".htmlspecialchars($youngest['age_received'])."</td>
      </tr>";

echo "<tr>
        <td>".htmlspecialchars($oldest['actor_name'])."</td>
        <td>".htmlspecialchars($oldest['age_received'])."</td>
      </tr>";
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
