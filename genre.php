<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Genres</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1 class="mt-5">Genres</h1>
        <p class="lead">Filter by Genre:</p>

        <!-- Genre filter form -->
        <form method="post" action="genre.php" class="mb-3">
            <div class="form-group">
                <label for="genreText">Genre name:</label>
                <input type="text" class="form-control" id="genreText" name="genreText" placeholder="Enter genre name">
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="genres[]" value="Action" id="Action">
                <label class="form-check-label" for="Action">Action</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="genres[]" value="Drama" id="Drama">
                <label class="form-check-label" for="Drama">Drama</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="genres[]" value="Comedy" id="Comedy">
                <label class="form-check-label" for="Comedy">Comedy</label>
            </div>
            <!-- Add more genres as needed -->
            <button type="submit" class="btn btn-primary mt-3">Filter</button>
        </form>

        <h2 class="mt-5">List of Genres:</h2>
        <table class="table mt-3">
            <thead class="thead-light">
                <tr>
                    <th>MPID</th>
                    <th>Genre Name</th>
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

                    $sql = "SELECT mpid, genre_name FROM Genre WHERE 1=1";
                    $params = [];
                    if (!empty($_POST['genreText'])) {
                        $sql .= " AND genre_name LIKE :genreText";
                        $params[':genreText'] = "%" . $_POST['genreText'] . "%";
                    }
                    if (!empty($_POST['genres'])) {
                        $sql .= " AND genre_name IN ('" . implode("', '", array_map('htmlspecialchars', $_POST['genres'])) . "')";
                    }

                    $stmt = $conn->prepare($sql);
                    // Bind parameters
                    if (!empty($_POST['genreText'])) {
                        $stmt->bindParam(':genreText', $params[':genreText']);
                    }
                    $stmt->execute();
                    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    foreach ($results as $row) {
                        echo "<tr>
                                <td>" . htmlspecialchars($row['mpid']) . "</td>
                                <td>" . htmlspecialchars($row['genre_name']) . "</td>
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
