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

$response = [];
$required_fields = ['id', 'action', 'unit', 'name', 'father_name', 'cnic', 'mobile_no', 'fees', 'address', 'computer_no', 'card_no', 'date_of_issue'];

foreach ($required_fields as $field) {
    if (!isset($_POST[$field])) {
        $response['status'] = 'error';
        $response['message'] = "Required data is missing. Missing field: $field. Received data: " . json_encode($_POST);
        echo json_encode($response);
        exit();
    }
}

$action = $_POST['action'];
$unit = $_POST['unit'];
$name = $_POST['name'];
$father_name = $_POST['father_name'];
$cnic = $_POST['cnic'];
$mobile_no = $_POST['mobile_no'];
$fees = $_POST['fees'];
$address = $_POST['address'];
$computer_no = $_POST['computer_no'];
$card_no = $_POST['card_no'];
$date_of_issue = $_POST['date_of_issue'];

// Ensure the $unit parameter is valid
$allowed_units = ['unit1', 'unit2', 'unit3', 'unit4', 'unit5', 'unit6', 'unit7', 'unit8', 'unit9', 'unit10', 'unit11'];
if (!in_array($unit, $allowed_units)) {
    $response['status'] = 'error';
    $response['message'] = "Invalid unit selection.";
    echo json_encode($response);
    exit();
}

// Function to check if member exists in any section
function check_member_exists($conn, $unit, $cnic) {
    $sections = ["legal_", "illegal_", "changing_"];
    foreach ($sections as $section) {
        $table = $section . $unit;
        $sql = "SELECT * FROM $table WHERE cnic = '$cnic'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            return ucfirst($section) . $unit; // Return the table where the member exists
        }
    }
    return false;
}

// Check if member already exists
$existing_section = check_member_exists($conn, $unit, $cnic);
if ($existing_section) {
    $response['status'] = 'error';
    $response['message'] = "The member is already in $existing_section section.";
    echo json_encode($response);
    exit();
}

// Determine the target table based on the action and unit
switch ($action) {
    case 'legal':
        $table = 'legal_' . $unit;
        break;
    case 'illegal':
        $table = 'illegal_' . $unit;
        break;
    case 'changing':
        $table = 'changing_' . $unit;
        break;
    default:
        $response['status'] = 'error';
        $response['message'] = "Invalid action.";
        echo json_encode($response);
        exit();
}

// Prepare and bind the SQL statement
$stmt = $conn->prepare("INSERT INTO $table (name, father_name, cnic, mobile_no, fees, address, computer_no, card_no, date_of_issue) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssssssss", $name, $father_name, $cnic, $mobile_no, $fees, $address, $computer_no, $card_no, $date_of_issue);

if ($stmt->execute() === TRUE) {
    $response['status'] = 'success';
    $response['message'] = "Data copied to $table table successfully.";
} else {
    $response['status'] = 'error';
    $response['message'] = "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
echo json_encode($response);
?>
