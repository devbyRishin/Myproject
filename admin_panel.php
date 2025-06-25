<?php
session_start();

if (!isset($_SESSION['admin_department']) || !isset($_SESSION['admin_event'])) {
    echo "Unauthorized access. Please login.";
    exit;
}

$departmentInput = $_SESSION['admin_department'];
$eventInput = $_SESSION['admin_event'];

$deptMap = [
    "computer eng." => "Computer_Eng_Dept",
    "Computer Engineering" => "Computer_Eng_Dept",
    "Computer_Eng_Dept" => "Computer_Eng_Dept",
    "civil eng." => "Civil_Eng",
    "Civil Engineering" => "Civil_Eng",
    "Civil_Eng" => "Civil_Eng",
    "electrical eng." => "Electrical_Eng",
    "Electrical Engineering" => "Electrical_Eng",
    "Electrical_Eng" => "Electrical_Eng",
    "chemical eng." => "Chemical_Eng",
    "Chemical Engineering" => "Chemical_Eng",
    "Chemical_Eng" => "Chemical_Eng",
    "mechanical eng." => "Mechanical_Eng",
    "Mechanical Engineering" => "Mechanical_Eng",
    "Mechanical_Eng" => "Mechanical_Eng"
];

$departmentDB = $deptMap[$departmentInput] ?? $departmentInput;
$eventTable = $eventInput;

$conn = new mysqli("localhost", "root", "", $departmentDB);
if ($conn->connect_error) {
    die("Database connection error: " . $conn->connect_error);
}

$query = "SELECT * FROM `$eventTable`";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel - <?= htmlspecialchars($eventTable) ?></title>
    <style>
        body {
            font-family: Arial;
            background: #f4f4f4;
            padding: 20px;
        }

        h2, h3 {
            color: #333;
        }

        .print-btn {
            background: #4CAF50;
            color: white;
            padding: 10px 16px;
            border: none;
            border-radius: 5px;
            margin-bottom: 20px;
            cursor: pointer;
        }

        @media print {
            .print-btn {
                display: none;
            }
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        th, td {
            padding: 10px;
            border: 1px solid #ccc;
            font-size: 14px;
        }

        th {
            background: #667eea;
            color: white;
        }

        tr:nth-child(even) {
            background: #f9f9f9;
        }
    </style>
</head>
<body>

<h2>Welcome, <?= htmlspecialchars($_SESSION['admin_name']) ?></h2>
<h3>Department: <?= htmlspecialchars($departmentDB) ?> | Event: <?= htmlspecialchars($eventTable) ?></h3>

<!-- ✅ Print Button -->
<button class="print-btn" onclick="window.print()">🖨️ Print / Save as PDF</button>

<?php if ($result && $result->num_rows > 0): ?>
    <table>
        <thead>
        <tr>
            <th>ID</th>
            <th>College</th>
            <th>Name 1</th><th>Email 1</th><th>Phone 1</th>
            <th>Name 2</th><th>Email 2</th><th>Phone 2</th>
            <th>Name 3</th><th>Email 3</th><th>Phone 3</th>
        </tr>
        </thead>
        <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id'] ?? '' ?></td>
                <td><?= $row['college'] ?? '—' ?></td>
                <td><?= $row['name1'] ?? '' ?></td><td><?= $row['email1'] ?? '' ?></td><td><?= $row['phone1'] ?? '' ?></td>
                <td><?= $row['name2'] ?? '' ?></td><td><?= $row['email2'] ?? '' ?></td><td><?= $row['phone2'] ?? '' ?></td>
                <td><?= $row['name3'] ?? '' ?></td><td><?= $row['email3'] ?? '' ?></td><td><?= $row['phone3'] ?? '' ?></td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>No registrations found for this event.</p>
<?php endif; ?>

</body>
</html>
