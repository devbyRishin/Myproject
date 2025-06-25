<?php
$conn = new mysqli("localhost", "root", "", "techfest");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $department = $_POST['department'] ?? '';
    $event = $_POST['event'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($name && $email && $department && $event && $password) {
        // Check for existing admin
        $check = $conn->prepare("SELECT id FROM admin WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            echo "Email already registered";
            exit;
        }

        // Hash password
        $hashed = password_hash($password, PASSWORD_DEFAULT);

        // Insert new admin
        $stmt = $conn->prepare("INSERT INTO admin (name, email, department, event, password) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $name, $email, $department, $event, $hashed);

        if ($stmt->execute()) {
            echo "success";
        } else {
            echo "Database error";
        }
    } else {
        echo "Missing fields";
    }
}
?>
