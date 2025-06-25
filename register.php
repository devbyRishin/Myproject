<?php
header('Content-Type: application/json');

// Step 1: Only allow POST requests
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
    exit;
}

// Step 2: Collect form data
$event = $_POST['event'] ?? '';
$college = $_POST['college-name'] ?? '';
$branch = $_POST['branch'] ?? '';

$name1 = $_POST['name1'] ?? '';
$email1 = $_POST['email1'] ?? '';
$phone1 = $_POST['phone1'] ?? '';

$name2 = $_POST['name2'] ?? '';
$email2 = $_POST['email2'] ?? '';
$phone2 = $_POST['phone2'] ?? '';

$name3 = $_POST['name3'] ?? '';
$email3 = $_POST['email3'] ?? '';
$phone3 = $_POST['phone3'] ?? '';

// Step 3: Validate required fields
if (empty($event) || empty($college) || empty($branch) || empty($name1) || empty($email1)) {
    echo json_encode(["status" => "error", "message" => "Please fill in all required fields (Team Leader)."]);
    exit;
}

// Step 4: Map branch to database
$departmentDatabases = [
    "Computer Eng. Dept." => "computer_eng",
    "Civil Eng." => "civil_eng",
    "Chemical Eng." => "chemical_eng",
    "Electrical Eng." => "electrical_eng",
    "Mechanical Eng." => "mechanical_eng"
];

if (!isset($departmentDatabases[$branch])) {
    echo json_encode(["status" => "error", "message" => "Invalid department selected."]);
    exit;
}

$dbname = $departmentDatabases[$branch];

// Step 5: Connect to the correct department database
$conn = new mysqli("localhost", "root", "", $dbname);
if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "Database connection failed: " . $conn->connect_error]);
    exit;
}

// Step 6: Use original event name directly as table name (quoted with backticks)
$table = $conn->real_escape_string($event);

// Optional: check if table exists
$tableCheck = $conn->query("SHOW TABLES LIKE '$table'");
if ($tableCheck->num_rows == 0) {
    echo json_encode(["status" => "error", "message" => "Event table '$table' not found in '$dbname' database."]);
    exit;
}

// Step 7: Prepare SQL insert
$sql = "INSERT INTO `$table` (
    college, name1, email1, phone1,
    name2, email2, phone2,
    name3, email3, phone3
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo json_encode(["status" => "error", "message" => "Failed to prepare SQL: " . $conn->error]);
    exit;
}

$stmt->bind_param(
    "ssssssssss",
    $college, $name1, $email1, $phone1,
    $name2, $email2, $phone2,
    $name3, $email3, $phone3
);

// Step 8: Execute and return result
if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "✅ Registration successful for '$event'."]);
} else {
    echo json_encode(["status" => "error", "message" => "Insert failed: " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
