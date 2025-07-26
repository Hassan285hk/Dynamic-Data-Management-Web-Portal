<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mydatabase";

// Create connection
include 'db.php';

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$unit = isset($_GET['unit']) ? $_GET['unit'] : 'unit1';

if (!isset($_SESSION['deleted_rows'])) {
    $_SESSION['deleted_rows'] = [];
}

// Function to delete a row
if (isset($_POST['delete']) && isset($_POST['id']) && isset($_POST['unit'])) {
    $id = $_POST['id'];
    $unit = $_POST['unit'];
    if (!isset($_SESSION['deleted_rows'][$unit])) {
        $_SESSION['deleted_rows'][$unit] = [];
    }
    $_SESSION['deleted_rows'][$unit][] = $id;
}

// Function to save deletions
if (isset($_POST['save'])) {
    foreach ($_SESSION['deleted_rows'] as $unit => $ids) {
        foreach ($ids as $id) {
            $stmt = $conn->prepare("DELETE FROM $unit WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->close();
        }
    }
    $_SESSION['deleted_rows'] = [];
}

// Fetch data from the specified unit
$data = $conn->query("SELECT * FROM $unit");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Data for <?php echo ucfirst($unit); ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <style>
        /* General Styles */
        body {
            font-family: 'Roboto', sans-serif;
            background-color: var(--background-color);
            color: var(--text-color);
            transition: background-color 0.3s, color 0.3s;
        }

        .container {
            margin-top: 50px;
            transition: background-color 0.3s, color 0.3s;
        }

        .navbar {
            background-color: #388e3c;
            color: white;
            transition: background-color 0.3s;
        }

        .navbar a {
            color: white;
        }

        .table-responsive {
            margin-top: 30px;
        }

        .table th, .table td {
            vertical-align: middle;
        }

        .btn-delete {
            background-color: #dc3545;
            border: none;
            color: white;
            transition: background-color 0.3s;
        }

        .btn-delete:hover {
            background-color: #c82333;
        }

        .btn-save {
            margin-top: 20px;
            background-color: #28a745;
            border: none;
            transition: background-color 0.3s;
        }

        .btn-save:hover {
            background-color: #218838;
        }

        .btn-dashboard {
            background-color: #004d40;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            margin: 20px;
            transition: background-color 0.3s;
            position: fixed;
            bottom: 20px;
            left: 20px;
        }

        .btn-dashboard:hover {
            background-color: #002f2f;
        }

        .btn-toggle {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            margin: 20px;
            transition: background-color 0.3s;
            position: fixed;
            bottom: 20px;
            right: 20px;
        }

        .btn-toggle:hover {
            background-color: #2980b9;
        }

        /* Light Theme */
        :root {
            --background-color: #e9f7ef;
            --text-color: #2c3e50;
        }

        /* Dark Theme */
        body.dark-theme {
            --background-color: #121212;
            --text-color: #e0e0e0;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light">
        <a class="navbar-brand" href="#">All Data of <?php echo ucfirst($unit); ?></a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav mr-auto">
                <?php for ($i = 1; $i <= 11; $i++): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="unit1_all_data.php?unit=unit<?php echo $i; ?>">Unit <?php echo $i; ?></a>
                    </li>
                <?php endfor; ?>
            </ul>
            <a href="dashboard.php" class="btn btn-light">Back to Dashboard</a>
        </div>
    </nav>
    <div class="container">
        <h2>All Data of <?php echo ucfirst($unit); ?></h2>
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Father's Name</th>
                        <th>CNIC</th>
                        <th>Mobile No</th>
                        <th>Fees</th>
                        <th>Address</th>
                        <th>Computer No</th>
                        <th>Card No</th>
                        <th>Date of Issue</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $data->fetch_assoc()): ?>
                        <?php if (!in_array($row['id'], $_SESSION['deleted_rows'][$unit] ?? [])): ?>
                            <tr>
                                <td><?php echo $row['name']; ?></td>
                                <td><?php echo $row['father_name']; ?></td>
                                <td><?php echo $row['cnic']; ?></td>
                                <td><?php echo $row['mobile_no']; ?></td>
                                <td><?php echo $row['fees']; ?></td>
                                <td><?php echo $row['address']; ?></td>
                                <td><?php echo $row['computer_no']; ?></td>
                                <td><?php echo $row['card_no']; ?></td>
                                <td><?php echo $row['date_of_issue']; ?></td>
                                <td>
                                    <form method="post" action="unit1_all_data.php?unit=<?php echo $unit; ?>">
                                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                        <input type="hidden" name="unit" value="<?php echo $unit; ?>">
                                        <button type="submit" name="delete" class="btn btn-delete">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endif; ?>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <form method="post" action="unit1_all_data.php?unit=<?php echo $unit; ?>">
                <button type="submit" name="save" class="btn btn-save">Save</button>
            </form>
        </div>
        <a href="dashboard.php" class="btn-dashboard">Back to Dashboard</a>
        <button id="theme-toggle" class="btn-toggle">Toggle to Dark</button>
    </div>
    <script>
        // Theme Toggle Script
        document.addEventListener("DOMContentLoaded", function() {
            const themeToggle = document.getElementById('theme-toggle');
            const currentTheme = localStorage.getItem('theme') || 'light';

            if (currentTheme === 'dark') {
                document.body.classList.add('dark-theme');
                themeToggle.textContent = 'Toggle to Light';
            }

            themeToggle.addEventListener('click', function() {
                document.body.classList.toggle('dark-theme');
                const newTheme = document.body.classList.contains('dark-theme') ? 'dark' : 'light';
                localStorage.setItem('theme', newTheme);

                themeToggle.textContent = newTheme === 'dark' ? 'Toggle to Light' : 'Toggle to Dark';
            });
        });
    </script>
</body>
</html>

<?php
$conn->close();
?>
