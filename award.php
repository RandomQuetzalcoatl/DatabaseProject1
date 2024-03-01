<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Awards</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1 class="mt-5">Awards</h1>
        <p class="lead">Filter Awards:</p>

        <!-- Filter Form -->
        <form method="post" action="award.php" class="mb-3">
            <div class="form-row">
                <div class="col">
                    <input type="text" class="form-control" name="awardName" placeholder="Award Name">
                </div>
                <div class="col">
                    <input type="number" class="form-control" name="awardYear" placeholder="Award Year">
                </div>
                <div class="col">
                    <button type="submit" class="btn btn-primary">Filter</button>
                </div>
            </div>
        </form>

        <table class="table">
            <thead class="thead-light">
                <tr>
                    <th>MPID</th>
                    <th>PID</th>
                    <th>Award Name</th>
                    <th>Award Year</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Using your provided database connection details
                $servername = "localhost";
                $username = "root";
                $password = "";
                $dbname = "COSI127b";

                try {
                    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    $sql = "SELECT mpid, pid, award_name, award_year FROM Awards WHERE 1=1";
                    if (!empty($_POST['awardName'])) {
                        $sql .= " AND award_name LIKE :awardName";
                    }
                    if (!empty($_POST['awardYear'])) {
                        $sql .= " AND award_year = :awardYear";
                    }

                    $stmt = $conn->prepare($sql);
                    if (!empty($_POST['awardName'])) {
                        $stmt->bindValue(':awardName', "%".$_POST['awardName']."%");
                    }
                    if (!empty($_POST['awardYear'])) {
                        $stmt->bindValue(':awardYear', $_POST['awardYear']);
                    }

                    $stmt->execute();
                    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    foreach ($results as $row) {
                        echo "<tr>
                                <td>".htmlspecialchars($row['mpid'])."</td>
                                <td>".htmlspecialchars($row['pid'])."</td>
                                <td>".htmlspecialchars($row['award_name'])."</td>
                                <td>".htmlspecialchars($row['award_year'])."</td>
                              </tr>";
                    }
                } catch(PDOException $e) {
                    echo "Error: " . $e->getMessage();
                }
                $conn = null; // Close connection
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
