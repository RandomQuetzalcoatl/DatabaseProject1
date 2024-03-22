<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actors with same Birthday</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2>Actors with same Birthday</h2>
        <h4>(Specifically Actors, so not including Gal Gadot Michael Caine January 29)</h4>
        <table class="table">
            <thead>
                <tr>
                    <th>Actor 1</th>
                    <th>Actor 2</th>
                    <th>Birthday</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $servername = "localhost";
                $username = "root";
                $password = "";
                $dbname = "COSI127b";

                $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                //For all people
                /*$stmt = $pdo->prepare("SELECT p1.name AS actor1_name, p2.name AS actor2_name, DATE_FORMAT(p1.dob, '%M %e') AS birthday
                    FROM People p1, People p2
                    WHERE p1.dob = p2.dob AND p1.id < p2.id");
                $stmt->execute();*/ 

                //Only actors
                $stmt = $pdo->prepare("SELECT DISTINCT p1.name AS actor1_name, p2.name AS actor2_name, DATE_FORMAT(p1.dob, '%M %e') AS birthday
                            FROM People p1
                            JOIN People p2 ON p1.dob = p2.dob AND p1.id < p2.id
                            JOIN Role r1 ON p1.id = r1.pid
                            JOIN Role r2 ON p2.id = r2.pid
                            WHERE r1.role_name = 'Actor' AND r2.role_name = 'Actor'");
                $stmt->execute();

                $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($results as $row) {
                    echo "<tr>
                            <td>".htmlspecialchars($row['actor1_name'])."</td>
                            <td>".htmlspecialchars($row['actor2_name'])."</td>
                            <td>".htmlspecialchars($row['birthday'])."</td>
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