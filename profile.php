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

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$result = $conn->query("SELECT * FROM users WHERE id = $id");
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Employee Profile</title>
    <link rel="icon" type="image/x-icon" href="image/logo.svg">
    <link rel="stylesheet" href="style.css">
    
</head>

<body>
    <div class="container" >
    <div class="form-box">
        
        <h2>Employee Profile</h2>
        <?php if ($user): ?>
           
            <p1><strong>Name:</strong> <?php echo $user['name']; ?></p1><br>
            <p1><strong>Surname:</strong> <?php echo $user['surname']; ?></p1><br>
            <p1><strong>Email:</strong> <?php echo $user['email']; ?></p1><br>
            <p1><strong>Position:</strong> <?php echo $user['position']; ?></p1><br>
            <p1><strong>Start Date:</strong> <?php echo $user['date']; ?></p1><br>
            <p1><strong>Department:</strong> <?php echo $user['department']; ?></p1><br>
        <?php else: ?>
            <p1>User not found.</p1>
        <?php endif; ?>
       <br><a href="list_employee.php"><button>Back to Employee List</button></a>
       <p>Go back to Home Page? <a href="HumanResources_page.php">Back</a></p>
       <p> <a href="logout.php">Logout</a></p>
    </div>
    </div>
</body>
</html>
