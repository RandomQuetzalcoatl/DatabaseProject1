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
        <form method="get" action="role.php" class="mb-3">
            <div class="form-row">
                <div class="col">
                    <input type="text" class="form-control" name="role_name" placeholder="Role Name" value="<?php echo isset($_GET['role_name']) ? htmlspecialchars($_GET['role_name']) : ''; ?>">
                </div>
                <div class="col">
                    <button type="submit" class="btn btn-primary">Filter</button>
                </div>
            </div>
        </form>
        <table class="table">
            <thead class="thead-light">
                <tr>
                    <th><a href="?sort=<?php echo isset($_GET['sort']) && $_GET['sort'] === 'mpid_desc' ? 'mpid_asc' : 'mpid_desc'; ?>&role_name=<?php echo isset($_GET['role_name']) ? urlencode($_GET['role_name']) : ''; ?>">MPID</a></th>
                    <th><a href="?sort=<?php echo isset($_GET['sort']) && $_GET['sort'] === 'pid_desc' ? 'pid_asc' : 'pid_desc'; ?>&role_name=<?php echo isset($_GET['role_name']) ? urlencode($_GET['role_name']) : ''; ?>">PID</a></th>
                    <th><a href="?sort=<?php echo isset($_GET['sort']) && $_GET['sort'] === 'role_name_desc' ? 'role_name_asc' : 'role_name_desc'; ?>&role_name=<?php echo isset($_GET['role_name']) ? urlencode($_GET['role_name']) : ''; ?>">Role Name</a></th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Using your provided database connection details
                $servername = "localhost";
                $username = "root";
                $password = "new_password";
                $dbname = "COSI127b";

                try {
                    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $sort = isset($_GET['sort']) ? $_GET['sort'] : 'mpid_desc';
                    $sort_arr = explode('_', $sort);
                    $sort_field = $sort_arr[0];
                    $sort_order = end($sort_arr);

                    $sql = "SELECT People.pid, People.name, Role.role_name FROM People JOIN Role ON People.pid = Role.pid";
                    
                    // Adding search condition
                    if(isset($_GET['role_name']) && !empty($_GET['role_name'])) {
                        $sql .= " WHERE Role.role_name LIKE :role_name";
                    }
                    
                    // Adding order by condition
                    if($sort_field!="role") {
                    $sql .= " ORDER BY $sort_field";
                } else {
                    $sql .= " ORDER BY role_name";
                }
                    if ($sort_order === 'desc') {
                        $sql .= " DESC";
                    } else {
                        $sql .= " ASC";
                    }

                    $stmt = $conn->prepare($sql);
                    // Binding search parameter if exists
                    if(isset($_GET['role_name']) && !empty($_GET['role_name'])) {
                        $stmt->bindValue(':role_name', '%' . $_GET['role_name'] . '%', PDO::PARAM_STR);
                    }
                    $stmt->execute();

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
