<?php
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

$units = ['unit1', 'unit2', 'unit3', 'unit4', 'unit5', 'unit6', 'unit7', 'unit8', 'unit9', 'unit10', 'unit11'];
$cnic = isset($_POST['cnic']) ? $_POST['cnic'] : '';
$unit_found = '';

if ($cnic) {
    foreach ($units as $unit) {
        $stmt = $conn->prepare("SELECT id FROM $unit WHERE cnic = ?");
        $stmt->bind_param("s", $cnic);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            $unit_found = $unit;
            break;
        }
        $stmt->close();
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Member</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <style>
        /* General Styles */
        body {
            font-family: 'Roboto', sans-serif;
            background-color: var(--background-color);
            color: var(--text-color);
            transition: background-color 0.3s, color 0.3s;
        }

        .container {
            margin-top: 20px;
            max-width: 600px;
            transition: background-color 0.3s, color 0.3s;
        }

        .search-form {
            margin-bottom: 20px;
        }

        .btn-search {
            background-color: #388e3c;
            border: none;
            transition: background-color 0.3s;
        }

        .btn-search:hover {
            background-color: #2e7d32;
        }

        .navbar {
            background-color: #388e3c;
            color: white;
            margin-bottom: 20px;
            transition: background-color 0.3s;
        }

        .navbar a {
            color: white;
        }

        .result {
            display: none;
        }

        .result.show {
            display: block;
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
            right: 20px;
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
            left: 20px;
        }

        .btn-toggle:hover {
            background-color: #2980b9;
        }

        /* Light Theme */
        :root {
            --background-color: #f8f9fa;
            --text-color: #333;
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
        <a class="navbar-brand" href="#">Search Member</a>
    </nav>
    <div class="container">
        <h2 class="text-center animate__animated animate__fadeInDown">Search Member in All Units</h2>
        <form class="search-form animate__animated animate__fadeInUp" action="search_member.php" method="post">
            <div class="form-group">
                <label for="cnic">Search by CNIC:</label>
                <input type="text" class="form-control" id="cnic" name="cnic" value="<?php echo htmlspecialchars($cnic); ?>" required>
            </div>
            <button type="submit" class="btn btn-success btn-search">Search</button>
        </form>
        <?php if ($unit_found): ?>
            <div class="result show animate__animated animate__fadeIn">
                <h3 class="text-center">Member Found:</h3>
                <p class="text-center">The member is in <strong><?php echo ucfirst($unit_found); ?></strong>.</p>
            </div>
        <?php elseif ($cnic): ?>
            <div class="result show animate__animated animate__fadeIn">
                <p class="text-center">No member found with this CNIC in any unit.</p>
            </div>
        <?php endif; ?>
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
