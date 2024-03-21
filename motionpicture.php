<?php
// Start the session
session_start();

if(isset($_POST['name'])) {
    $_SESSION['search_name'] = $_POST['name'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Motion Pictures</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1 class="mt-5">Motion Pictures</h1>
        <p class="lead">List of all Motion Pictures:</p>
        <form method="post" action="motionpicture.php" class="mb-3">
            <div class="form-row">
                <div class="col">
                    <input type="text" class="form-control" name="name" placeholder="Title" value="<?php echo isset($_SESSION['search_name']) ? $_SESSION['search_name'] : '' ?>">
                </div>
                <div class="col">
                    <button type="submit" class="btn btn-primary">Filter</button>
                </div>
            </div>
        </form>

        <table class="table">
            <thead class="thead-light">
                <tr>
                    <th><a href="?sort=<?php echo isset($_GET['sort']) && $_GET['sort'] === 'id_desc' ? 'id_asc' : 'id_desc' ?>&name=<?php echo isset($_SESSION['search_name']) ? urlencode($_SESSION['search_name']) : '' ?>">ID</a></th>
                    <th><a href="?sort=<?php echo isset($_GET['sort']) && $_GET['sort'] === 'name_desc' ? 'name_asc' : 'name_desc' ?>&name=<?php echo isset($_SESSION['search_name']) ? urlencode($_SESSION['search_name']) : '' ?>">Name</a></th>
                    <th><a href="?sort=<?php echo isset($_GET['sort']) && $_GET['sort'] === 'rating_desc' ? 'rating_asc' : 'rating_desc' ?>&name=<?php echo isset($_SESSION['search_name']) ? urlencode($_SESSION['search_name']) : '' ?>">Rating</a></th>
                    <th><a href="?sort=<?php echo isset($_GET['sort']) && $_GET['sort'] === 'production_desc' ? 'production_asc' : 'production_desc' ?>&name=<?php echo isset($_SESSION['search_name']) ? urlencode($_SESSION['search_name']) : '' ?>">Production</a></th>
                    <th><a href="?sort=<?php echo isset($_GET['sort']) && $_GET['sort'] === 'budget_desc' ? 'budget_asc' : 'budget_desc' ?>&name=<?php echo isset($_SESSION['search_name']) ? urlencode($_SESSION['search_name']) : '' ?>">Budget</a></th>
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
                    $sort = isset($_GET['sort']) ? $_GET['sort'] : 'rating_desc';
                    $sort_arr = explode('_', $sort);
                    $sort_field = $sort_arr[0];
                    $sort_order = end($sort_arr);

                    $sql = "SELECT id, name, rating, production, budget FROM MotionPicture";
                    
                    if(isset($_SESSION['search_name']) && !empty($_SESSION['search_name'])) {
                        $sql .= " WHERE name LIKE :name";
                    }
                    
                    // Sort Order
                    $sql .= " ORDER BY $sort_field";
                    if ($sort_order === 'desc') {
                        $sql .= " DESC";
                    } else {
                        $sql .= " ASC";
                    }

                    $stmt = $conn->prepare($sql);
                    if(isset($_SESSION['search_name']) && !empty($_SESSION['search_name'])) {
                        $stmt->bindValue(':name', '%' . $_SESSION['search_name'] . '%', PDO::PARAM_STR);
                    }
                    $stmt->execute();

                    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($results as $row) {
                        echo "<tr>
                                <td>{$row['id']}</td>
                                <td>{$row['name']}</td>
                                <td>{$row['rating']}</td>
                                <td>{$row['production']}</td>
                                <td>{$row['budget']}</td>
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
