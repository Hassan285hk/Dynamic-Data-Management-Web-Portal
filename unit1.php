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
    <title>Unit 1</title>
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
            margin-top: 20px;
            max-width: 1200px;
            transition: background-color 0.3s, color 0.3s;
        }
        
        .search-form, .member-details {
            width: 45%;
        }

        .search-form {
            border-right: 1px solid #dee2e6;
            padding-right: 20px;
            margin-right: 20px;
        }

        .btn-search {
            background-color: #388e3c;
            border: none;
            transition: background-color 0.3s;
        }

        .btn-search:hover {
            background-color: #2e7d32;
        }

        .btn-legal, .btn-illegal, .btn-changing {
            width: 100%;
            margin-top: 10px;
            transition: background-color 0.3s;
        }

        .btn-legal {
            background-color: #28a745;
            border: none;
        }

        .btn-illegal {
            background-color: #dc3545;
            border: none;
        }

        .btn-changing {
            background-color: #fd7e14;
            border: none;
        }

        .btn-legal:hover {
            background-color: #218838;
        }

        .btn-illegal:hover {
            background-color: #c82333;
        }

        .btn-changing:hover {
            background-color: #e36b05;
        }

        .message-box {
            display: none;
            margin-bottom: 20px;
            padding: 10px;
            border: 1px solid #28a745;
            background-color: #d4edda;
            color: #155724;
        }

        .navbar {
            background-color: var(--navbar-background);
            color: white;
            transition: background-color 0.3s;
        }

        .navbar a {
            color: white;
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
            --background-color: #f8f9fa;
            --text-color: #333;
            --navbar-background: #388e3c;
        }

        /* Dark Theme */
        body.dark-theme {
            --background-color: #121212;
            --text-color: #e0e0e0;
            --navbar-background: #1c1c1c;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light">
        <a class="navbar-brand" href="#">Unit 1</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="show/view_data.php?unit=unit1&action=legal">Legal</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="show/view_data.php?unit=unit1&action=illegal">Illegal</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="show/view_data.php?unit=unit1&action=changing">Changing</a>
                </li>
            </ul>
            <a href="dashboard.php" class="btn btn-light">Back to Dashboard</a>
        </div>
    </nav>
    <div class="container">
        <div id="messageBox" class="message-box"></div>
        <div class="d-flex">
            <div class="search-form">
                <h2>Unit 1</h2>
                <form id="searchForm" action="unit1.php" method="get">
                    <div class="form-group">
                        <label for="cnic">Search by CNIC:</label>
                        <input type="text" class="form-control" id="cnic" name="cnic" required>
                    </div>
                    <button type="submit" class="btn btn-success btn-search">Search</button>
                </form>
                <!-- Button to access all data for Unit 1 -->
                <a href="unit1_all_data.php?unit=unit1" class="btn btn-info mt-3">View All Data of Unit 1</a>
            </div>
            <div class="member-details">
                <?php
                if (isset($_GET['cnic'])) {
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

                    $cnic = $_GET['cnic'];

                    $stmt = $conn->prepare("SELECT id, name, father_name, cnic, mobile_no, fees, address, computer_no, card_no, date_of_issue FROM unit1 WHERE cnic = ?");
                    $stmt->bind_param("s", $cnic);
                    $stmt->execute();
                    $stmt->store_result();
                    
                    if ($stmt->num_rows > 0) {
                        $stmt->bind_result($id, $name, $father_name, $cnic, $mobile_no, $fees, $address, $computer_no, $card_no, $date_of_issue);
                        $stmt->fetch();
                        echo "<p>Name: $name</p>";
                        echo "<p>Father's Name: $father_name</p>";
                        echo "<p>CNIC: $cnic</p>";
                        echo "<p>Mobile No: $mobile_no</p>";
                        echo "<p>Fees: $fees</p>";
                        echo "<p>Address: $address</p>";
                        echo "<p>Computer No: $computer_no</p>";
                        echo "<p>Card No: $card_no</p>";
                        echo "<p>Date of Issue: $date_of_issue</p>";

                        echo "
                        <div class='result-buttons'>
                            <form id='copyDataForm' action='copy_data.php' method='post'>
                                <input type='hidden' name='id' value='$id'>
                                <input type='hidden' name='name' value='$name'>
                                <input type='hidden' name='father_name' value='$father_name'>
                                <input type='hidden' name='cnic' value='$cnic'>
                                <input type='hidden' name='mobile_no' value='$mobile_no'>
                                <input type='hidden' name='fees' value='$fees'>
                                <input type='hidden' name='address' value='$address'>
                                <input type='hidden' name='computer_no' value='$computer_no'>
                                <input type='hidden' name='card_no' value='$card_no'>
                                <input type='hidden' name='date_of_issue' value='$date_of_issue'>
                                <input type='hidden' name='unit' value='unit1'>
                                <button type='submit' name='action' value='legal' class='btn btn-legal'>Legal</button>
                                <button type='submit' name='action' value='illegal' class='btn btn-illegal'>Illegal</button>
                                <button type='submit' name='action' value='changing' class='btn btn-changing'>Changing</button>
                            </form>
                        </div>";
                    } else {
                        echo "<p>No user found with this CNIC.</p>";
                    }
                    $stmt->close();
                    $conn->close();
                }
                ?>
            </div>
        </div>
        <a href="dashboard.php" class="btn-dashboard">Back to Dashboard</a>
        <button id="theme-toggle" class="btn-toggle">Toggle to Dark</button>
    </div>

    <script>
        function showMessage(message) {
            var messageBox = document.getElementById('messageBox');
            messageBox.innerHTML = message;
            messageBox.style.display = 'block';
        }

        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('#copyDataForm button').forEach(function(button) {
                button.addEventListener('click', function(event) {
                    event.preventDefault();
                    var action = button.value;

                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', 'copy_data.php', true);
                    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

                    xhr.onreadystatechange = function() {
                        if (xhr.readyState === 4 && xhr.status === 200) {
                            var response = JSON.parse(xhr.responseText);
                            if (response.status === 'success') {
                                showMessage(response.message + " Press OK.");
                            } else {
                                showMessage(response.message);
                            }
                        }
                    };

                    var formData = new FormData(document.getElementById('copyDataForm'));
                    formData.append('action', action);
                    xhr.send(new URLSearchParams(formData).toString());
                });
            });

            // Theme Toggle Script
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
