<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Users</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1 class="mt-5">All Users</h1>

        <!-- Search and filter form -->
        <form method="post" action="user.php" class="mb-3">
            <div class="form-group">
                <label for="searchName">Search by name:</label>
                <input type="text" class="form-control" id="searchName" name="searchName" placeholder="Enter name">
            </div>
            <div class="form-row">
                <div class="col">
                    <label for="minAge">Minimum Age:</label>
                    <input type="number" class="form-control" id="minAge" name="minAge" placeholder="Min Age">
                </div>
                <div class="col">
                    <label for="maxAge">Maximum Age:</label>
                    <input type="number" class="form-control" id="maxAge" name="maxAge" placeholder="Max Age">
                </div>
            </div>
            <button type="submit" class="btn btn-primary mt-2">Filter</button>
        </form>

        <table class="table mt-3">
            <thead class="thead-light">
                <tr>
                    <th>Email</th>
                    <th>Name</th>
                    <th>Age</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Database connection details
                $servername = "localhost";
                $username = "root";
                $password = "";
                $dbname = "COSI127b";

                try {
                    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    // Construct SQL query based on search and age filters
                    $sql = "SELECT email, name, age FROM User WHERE 1 = 1";
                    $params = [];
                    if (!empty($_POST['searchName'])) {
                        $sql .= " AND name LIKE :searchName";
                        $params[':searchName'] = "%" . $_POST['searchName'] . "%";
                    }
                    if (!empty($_POST['minAge'])) {
                        $sql .= " AND age >= :minAge";
                        $params[':minAge'] = $_POST['minAge'];
                    }
                    if (!empty($_POST['maxAge'])) {
                        $sql .= " AND age <= :maxAge";
                        $params[':maxAge'] = $_POST['maxAge'];
                    }

                    $stmt = $conn->prepare($sql);
                    // Bind parameters
                    foreach ($params as $key => $value) {
                        $stmt->bindValue($key, $value);
                    }
                    $stmt->execute();

                    // Fetch and display results
                    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($results as $row) {
                        echo "<tr>
                                <td>" . htmlspecialchars($row['email']) . "</td>
                                <td>" . htmlspecialchars($row['name']) . "</td>
                                <td>" . htmlspecialchars($row['age']) . "</td>
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

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
