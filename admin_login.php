<?php
session_start();

// Connect to the main admin database (e.g., 'techfest') where admin info is stored
$conn = new mysqli("localhost", "root", "", "techfest");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle POST request
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        echo "Missing credentials";
        exit;
    }

    // Fetch admin by email
    $stmt = $conn->prepare("SELECT id, name, department, event, password FROM admin WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($id, $name, $department, $event, $hashed_password);
        $stmt->fetch();

        // Verify password
        if (password_verify($password, $hashed_password)) {
            $_SESSION['admin_id'] = $id;
            $_SESSION['admin_name'] = $name;
            $_SESSION['admin_department'] = $department;
            $_SESSION['admin_event'] = $event;

            echo "success";
        } else {
            echo "Invalid password";
        }
    } else {
        echo "Admin not found";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request";
}
?>
