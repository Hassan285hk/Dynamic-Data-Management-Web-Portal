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
$data_counts = [];

foreach ($units as $unit) {
    // Using prepared statements for better security
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM `$unit`");
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $data_counts[$unit]['total'] = $row['count'];
    
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM `legal_$unit`");
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $data_counts[$unit]['legal'] = $row['count'];
    
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM `illegal_$unit`");
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $data_counts[$unit]['illegal'] = $row['count'];
    
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM `changing_$unit`");
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $data_counts[$unit]['changing'] = $row['count'];
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Live Data Count</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="container text-center">
        <h2>Live Data Count for Each Unit</h2>
        <canvas id="liveChart"></canvas>
        <script>
            var ctx = document.getElementById('liveChart').getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: <?php echo json_encode(array_keys($data_counts)); ?>,
                    datasets: [
                        {
                            label: 'Total Entries',
                            data: <?php echo json_encode(array_column($data_counts, 'total')); ?>,
                            backgroundColor: 'rgba(54, 162, 235, 0.5)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'Legal',
                            data: <?php echo json_encode(array_column($data_counts, 'legal')); ?>,
                            backgroundColor: 'rgba(75, 192, 192, 0.5)',
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'Illegal',
                            data: <?php echo json_encode(array_column($data_counts, 'illegal')); ?>,
                            backgroundColor: 'rgba(255, 99, 132, 0.5)',
                            borderColor: 'rgba(255, 99, 132, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'Changing',
                            data: <?php echo json_encode(array_column($data_counts, 'changing')); ?>,
                            backgroundColor: 'rgba(255, 206, 86, 0.5)',
                            borderColor: 'rgba(255, 206, 86, 1)',
                            borderWidth: 1
                        }
                    ]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        </script>
        <a href="dashboard.php" class="btn btn-primary mt-3">Back to Dashboard</a>
    </div>
</body>
</html>
