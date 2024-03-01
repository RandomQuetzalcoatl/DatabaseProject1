<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Locations</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1 class="mt-5">Locations</h1>
        <p class="lead">Filter and List of all Filming Locations:</p>

        <!-- Filter Form -->
        <form method="post" action="locations.php" class="mb-3">
            <div class="form-row">
                <div class="col">
                    <input type="text" class="form-control" name="filterMPID" placeholder="MPID">
                </div>
                <div class="col">
                    <input type="text" class="form-control" name="filterZip" placeholder="Zip">
                </div>
                <div class="col">
                    <input type="text" class="form-control" name="filterCity" placeholder="City">
                </div>
                <div class="col">
                    <input type="text" class="form-control" name="filterCountry" placeholder="Country">
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
                    <th>Zip</th>
                    <th>City</th>
                    <th>Country</th>
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

                    // Construct SQL query based on filters
                    $sql = "SELECT mpid, zip, city, country FROM Location WHERE 1 = 1";
                    if (!empty($_POST['filterMPID'])) {
                        $sql .= " AND mpid LIKE :mpid";
                    }
                    if (!empty($_POST['filterZip'])) {
                        $sql .= " AND zip LIKE :zip";
                    }
                    if (!empty($_POST['filterCity'])) {
                        $sql .= " AND city LIKE :city";
                    }
                    if (!empty($_POST['filterCountry'])) {
                        $sql .= " AND country LIKE :country";
                    }

                    $stmt = $conn->prepare($sql);

                    // Binding parameters
                    if (!empty($_POST['filterMPID'])) {
                        $stmt->bindValue(':mpid', "%".$_POST['filterMPID']."%");
                    }
                    if (!empty($_POST['filterZip'])) {
                        $stmt->bindValue(':zip', "%".$_POST['filterZip']."%");
                    }
                    if (!empty($_POST['filterCity'])) {
                        $stmt->bindValue(':city', "%".$_POST['filterCity']."%");
                    }
                    if (!empty($_POST['filterCountry'])) {
                        $stmt->bindValue(':country', "%".$_POST['filterCountry']."%");
                    }

                    $stmt->execute();
                    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    foreach ($results as $row) {
                        echo "<tr>
                                <td>".htmlspecialchars($row['mpid'])."</td>
                                <td>".htmlspecialchars($row['zip'])."</td>
                                <td>".htmlspecialchars($row['city'])."</td>
                                <td>".htmlspecialchars($row['country'])."</td>
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
