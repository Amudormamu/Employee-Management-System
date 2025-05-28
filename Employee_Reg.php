<?php 
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit;
}
$conn = new mysqli('localhost', 'root', '', 'employee_dp');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$message = "";


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add'])) {
    $name = trim($_POST['name']);
    $surname = trim($_POST['surname']);
    $email = trim($_POST['email']);
    $position = trim($_POST['position']);
    $date = date("Y/m/d", strtotime($_POST['date']));
    $department = trim($_POST['department']);

    if (!empty($name) && !empty($surname) && !empty($email) && !empty($position) && !empty($date) && !empty($department)) {

        // Check if email already exists
        $check_stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $check_stmt->bind_param("s", $email);
        $check_stmt->execute();
        $check_stmt->store_result();

        if ($check_stmt->num_rows > 0) {
            $message = "<p style='color: red;'>Error: Email already exists .</p>";
        } else {
            $stmt = $conn->prepare("INSERT INTO users (name, surname, email, position, date, department) VALUES (?, ?, ?, ?, ?, ?)");
            if ($stmt) {
                $stmt->bind_param("ssssss", $name, $surname, $email, $position, $date, $department);
                if ($stmt->execute()) {
                    $message = "<p style='color: green;'>Employee added successfully!</p>";
                } else {
                    $message = "<p style='color: red;'>Error executing statement: " . $stmt->error . "</p>";
                }
                $stmt->close();
            } else {
                $message = "<p style='color: red;'>Database error: " . $conn->error . "</p>";
            }
        }
        $check_stmt->close();

    } else {
        $message = "<p style='color: red;'>Please fill in all fields.</p>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Registration</title>
    <link rel="icon" type="image/x-icon" href="image/logo.svg">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="form-box" id ="emp_register1-form">
         <form action="" method="POST">
            <h2>Employee Registration</h2>
            <?php if (!empty($message)) echo $message; ?>
            <input type="text" name="name" placeholder="Name"required>
            <input type="text" name="surname" placeholder="Surname"required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="text" name="position"placeholder="Position"required>
            <input type="date" name="date"required>   
            <select name="department">
              <option value="--Select Role--">--Select Department--</option>
              <option value="Administrative ">Administrative </option>
              <option value="Human Resources">Human Resourses</option>
              <option value="Sales">Sales</option>
              <option value="Marketing">Marketing</option>
              <option value="Accounting">Accounting</option>
              <option value="I.T">I.T</option>
            </select >
            <button type="submit" name="add">Register</button>
            <p>Want to go back? <a href="HumanResources_page.php">back</a></p>
            <p> <a href="logout.php">Logout</a></p>
         </form>
        
    
</body>
</html>