<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Roles</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1 class="mt-5">Roles</h1>
        <p class="lead">List of all Roles:</p>
        <table class="table">
            <thead class="thead-light">
                <tr>
                    <th>MPID</th>
                    <th>PID</th>
                    <th>Role Name</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Using your provided database connection details
                $servername = "localhost";
                $username = "root";
                $password = "";
                $dbname = "COSI127b";
                $roleFilter = isset($_GET['role_name']) ? $_GET['role_name'] : null;
                
                try {
                    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                
                    $sql = "SELECT People.pid, People.name, Role.role_name FROM People JOIN Role ON People.pid = Role.pid";
                    if ($roleFilter) {
                        $sql .= " WHERE Role.role_name = :roleFilter"; // Adjust based on your actual column names and structure
                    }
                
                    $stmt = $conn->prepare($sql);
                
                    if ($roleFilter) {
                        $stmt->bindParam(':roleFilter', $roleFilter, PDO::PARAM_STR);
                    }
                
                    $stmt->execute();
                
                    // Now, fetch and display the data as required
                    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($results as $row) {
                        echo "<tr>
                                <td>{$row['pid']}</td>
                                <td>{$row['name']}</td>
                                <td>{$row['role_name']}</td>
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
