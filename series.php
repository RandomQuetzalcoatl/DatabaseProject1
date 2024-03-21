<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Series</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1 class="mt-5">Series</h1>
        <p class="lead">List of all Series and their Season Counts:</p>
        <form method="post" action="series.php" class="mb-3">
            <div class="form-row">
                <div class="col">
                    <input type="text" class="form-control" name="name" placeholder="Title" value="<?php echo isset($_POST['name']) ? $_POST['name'] : (isset($_GET['name']) ? htmlspecialchars($_GET['name']) : ''); ?>">
                </div>
                <div class="col">
                    <button type="submit" class="btn btn-primary">Filter</button>
                </div>
            </div>
        </form>
        <table class="table">
            <thead class="thead-light">
                <tr>
                    <th><a href="?sort=<?php echo isset($_GET['sort']) && $_GET['sort'] === 'mpid_desc' ? 'mpid_asc' : 'mpid_desc'; ?>&name=<?php echo isset($_POST['name']) ? urlencode($_POST['name']) : (isset($_GET['name']) ? $_GET['name'] : ''); ?>">MPID</a></th>
                    <th><a href="?sort=<?php echo isset($_GET['sort']) && $_GET['sort'] === 'name_desc' ? 'name_asc' : 'name_desc'; ?>&name=<?php echo isset($_POST['name']) ? urlencode($_POST['name']) : (isset($_GET['name']) ? $_GET['name'] : ''); ?>">Name</a></th>
                    <th>Season Count</th>
                     <!--<th><a href="?sort=<?php echo isset($_GET['sort']) && $_GET['sort'] === 'season_count_desc' ? 'season_count_asc' : 'season_count_desc'; ?>&name=<?php echo isset($_POST['name']) ? urlencode($_POST['name']) : (isset($_GET['name']) ? $_GET['name'] : ''); ?>">Season Count</a></th> -->
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
                    $sort = isset($_GET['sort']) ? $_GET['sort'] : 'mpid_desc';
                    $sort_arr = explode('_', $sort);
                    $sort_field = $sort_arr[0];
                    $sort_order = end($sort_arr);

                    $sql = "SELECT Series.mpid, MotionPicture.name, Series.season_count FROM Series INNER JOIN MotionPicture ON Series.mpid = MotionPicture.id";
                    
                    // Adding search condition
                    if(isset($_POST['name']) && !empty($_POST['name'])) {
                        $sql .= " WHERE MotionPicture.name LIKE :name";
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
                                <td>{$row['mpid']}</td>
                                <td>{$row['name']}</td>
                                <td>{$row['season_count']}</td>
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
