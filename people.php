<?php
// Start the session
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>People</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1 class="mt-5">People</h1>
        <p class="lead">List of all People in the Database:</p>
        <form method="post" action="people.php" class="mb-3">
            <div class="form-row">
                <div class="col">
                    <input type="text" class="form-control" name="name" placeholder="Name" value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : (isset($_GET['name']) ? urldecode($_GET['name']) : ''); ?>">
                </div>
                <div class="col">
                    <button type="submit" class="btn btn-primary">Search</button>
                </div>
            </div>
        </form>
        <table class="table">
            <thead class="thead-light">
                <tr>
                    <th><a href="?sort=<?php echo isset($_GET['sort']) && $_GET['sort'] === 'pid_desc' ? 'pid_asc' : 'pid_desc' ?>&name=<?php echo isset($_POST['name']) ? urlencode($_POST['name']) : (isset($_GET['name']) ? $_GET['name'] : ''); ?>">PID</a></th>
                    <th><a href="?sort=<?php echo isset($_GET['sort']) && $_GET['sort'] === 'name_desc' ? 'name_asc' : 'name_desc' ?>&name=<?php echo isset($_POST['name']) ? urlencode($_POST['name']) : (isset($_GET['name']) ? $_GET['name'] : ''); ?>">Name</a></th>
                    <th><a href="?sort=<?php echo isset($_GET['sort']) && $_GET['sort'] === 'nationality_desc' ? 'nationality_asc' : 'nationality_desc' ?>&name=<?php echo isset($_POST['name']) ? urlencode($_POST['name']) : (isset($_GET['name']) ? $_GET['name'] : ''); ?>">Nationality</a></th>
                    <th><a href="?sort=<?php echo isset($_GET['sort']) && $_GET['sort'] === 'dob_desc' ? 'dob_asc' : 'dob_desc' ?>&name=<?php echo isset($_POST['name']) ? urlencode($_POST['name']) : (isset($_GET['name']) ? $_GET['name'] : ''); ?>">Date of Birth</a></th>
                    <th><a href="?sort=<?php echo isset($_GET['sort']) && $_GET['sort'] === 'gender_desc' ? 'gender_asc' : 'gender_desc' ?>&name=<?php echo isset($_POST['name']) ? urlencode($_POST['name']) : (isset($_GET['name']) ? $_GET['name'] : ''); ?>">Gender</a></th>
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
                    $sort = isset($_GET['sort']) ? $_GET['sort'] : 'pid_desc';
                    $sort_arr = explode('_', $sort);
                    $sort_field = $sort_arr[0];
                    $sort_order = end($sort_arr);

                    $sql = "SELECT pid, name, nationality, dob, gender FROM People";
                    
                    // Adding search condition
                    if(isset($_POST['name']) && !empty($_POST['name'])) {
                        $sql .= " WHERE name LIKE :name";
                    }
                    
                    // Adding order by condition
                    $sql .= " ORDER BY $sort_field";
                    if ($sort_order === 'desc') {
                        $sql .= " DESC";
                    } else {
                        $sql .= " ASC";
                    }

                    $stmt = $conn->prepare($sql);
                    // Binding search parameter if exists
                    if(isset($_POST['name']) && !empty($_POST['name'])) {
                        $stmt->bindValue(':name', '%' . $_POST['name'] . '%', PDO::PARAM_STR);
                    }
                    $stmt->execute();

                    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($results as $row) {
                        echo "<tr>
                                <td>{$row['pid']}</td>
                                <td>{$row['name']}</td>
                                <td>{$row['nationality']}</td>
                                <td>{$row['dob']}</td>
                                <td>{$row['gender']}</td>
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
