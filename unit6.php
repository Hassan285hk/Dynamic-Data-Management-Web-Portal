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
    <title>Unit 6</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 20px;
            max-width: 1200px;
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
        }
        .btn-search:hover {
            background-color: #2e7d32;
        }
        .btn-legal, .btn-illegal, .btn-changing {
            width: 100%;
            margin-top: 10px;
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
        .message-box {
            display: none; 
            margin-bottom: 20px;
            padding: 10px;
            border: 1px solid #28a745;
            background-color: #d4edda;
            color: #155724;
        }
        .navbar {
            background-color: #388e3c;
            color: white;
        }
        .navbar a {
            color: white;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light">
        <a class="navbar-brand" href="#">Unit 6</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="show/view_data.php?unit=unit6&action=legal">Legal</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="show/view_data.php?unit=unit6&action=illegal">Illegal</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="show/view_data.php?unit=unit6&action=changing">Changing</a>
                </li>
            </ul>
            <a href="dashboard.php" class="btn btn-light">Back to Dashboard</a>
        </div>
    </nav>
    <div class="container">
        <div id="messageBox" class="message-box"></div>
        <div class="d-flex">
            <div class="search-form">
                <h2>Unit 6</h2>
                <form id="searchForm" action="unit6.php" method="get">
                    <div class="form-group">
                        <label for="cnic">Search by CNIC:</label>
                        <input type="text" class="form-control" id="cnic" name="cnic" required>
                    </div>
                    <button type="submit" class="btn btn-success btn-search">Search</button>
                </form>
                <!-- Button to access all data for Unit 3 -->
                <a href="unit1_all_data.php?unit=unit6" class="btn btn-info mt-6">View All Data of Unit 6</a>
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

                    $stmt = $conn->prepare("SELECT id, name, father_name, cnic, mobile_no, fees, address, computer_no, card_no, date_of_issue FROM unit6 WHERE cnic = ?");
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
                                <input type='hidden' name='unit' value='unit6'>
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
        });
    </script>
</body>
</html>
