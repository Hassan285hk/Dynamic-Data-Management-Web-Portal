<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <style>
        /* General Styles */
        body {
            font-family: 'Roboto', sans-serif;
            background-color: var(--background-color);
            color: var(--text-color);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            padding: 0;
            transition: background-color 0.3s, color 0.3s;
        }

        .container {
            max-width: 600px;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            transition: background-color 0.3s, color 0.3s;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: var(--heading-color);
        }

        .btn-unit {
            margin: 10px;
            width: 150px; /* Adjusted width to fit "Search Member" on one line */
            background-color: var(--button-background);
            color: white;
            border: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .btn-unit:hover {
            background-color: var(--button-hover);
        }

        .btn-primary {
            background-color: #00796b;
            border-color: #00796b;
            border-radius: 30px;
            transition: background-color 0.3s, border-color 0.3s;
        }

        .btn-primary:hover {
            background-color: #004d40;
            border-color: #004d40;
        }

        /* Light Theme */
        :root {
            --background-color: #f8f9fa;
            --text-color: #333;
            --heading-color: #3498db;
            --button-background: #2ecc71;
            --button-hover: #27ae60;
        }

        /* Dark Theme */
        body.dark-theme {
            --background-color: #121212;
            --text-color: #e0e0e0;
            --heading-color: #1db954;
        }

        body.dark-theme .container {
            background-color: #1c1c1c;
            color: #e0e0e0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2><b>Welcome, QKS Member!</b></h2>
        <a href="live_count.php" class="btn btn-info btn-unit">Live Count</a>
        <a href="search_member.php" class="btn btn-warning btn-unit">Search Member</a>
        <div class="buttons">
            <?php for ($i = 1; $i <= 11; $i++): ?>
                <a href="unit<?php echo $i; ?>.php" class="btn btn-success btn-unit">Unit <?php echo $i; ?></a>
            <?php endfor; ?>
        </div>
        <button id="theme-toggle" class="btn btn-primary mt-3">Toggle to Dark</button>
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
