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

if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM users WHERE id=$id");
    $message = "<p style='color: green;'>Employee Data Deleted successfully!</p>";
}


// Handle update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {
    $id = intval($_POST['id']); // <-- add this line
    $name = trim($_POST['name']);
    $surname = trim($_POST['surname']);
    $email = trim($_POST['email']);
    $position = trim($_POST['position']);
    $date = date("Y/m/d", strtotime($_POST['date']));
    $department = trim($_POST['department']);
    
    if (!empty($name) && !empty($surname) && !empty($email) && !empty($position) && !empty($date) && !empty($department)) {
        $stmt = $conn->prepare("UPDATE users SET name=?, surname=?, email=?, position=?, date=?, department=? WHERE id=?");
        $stmt->bind_param("ssssssi", $name, $surname, $email, $position, $date, $department, $id);
        if ($stmt->execute()) {
            $message = "<p style='color: green;'>Updated successfully!</p>";
        } else {
            $message = "<p style='color: red;'>Error updating statement: " . $stmt->error . "</p>";
        }
        $stmt->close();
    }
}


// Fetch results
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$query = "SELECT * FROM users";
if (!empty($search)) {
    $query .= " WHERE name LIKE '%$search%'  OR Position LIKE '%$search%' OR department LIKE '%$search%'";
}
$query .= " ORDER BY department, Position, name";
$users = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee List</title>
    <link rel="icon" type="image/x-icon" href="image/logo.svg">
    <link rel="stylesheet" href="style.css">
</head>
<body >
<div class="table-box" id ="table">
         <form action="" method="POST">

            <h2>Employee List</h2>
            <center style="color: #333;"> <p1>Hi <span><?=$_SESSION['name'];?></span> you can <span>Update, Delete or View profiles</span> <br> you can also use <span>search</span> to narrow your search down faster </p1> </center><br>
            <?php if (!empty($message)) echo $message; ?>
            <table >
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Surname</th>
                    <th>Email</th>
                    <th>Position</th>
                    <th>StartDate</th>
                    <th>Department</th>
                    <th>Buttons</th>
                </tr>
                <?php while ($row = $users->fetch_assoc()) { ?>
<tr>
    <form method="post">
        <td><input type="text" name="id" value="<?php echo $row['id']; ?>" readonly></td>
        <td><input type="text" name="name" value="<?php echo htmlspecialchars($row['name']); ?>" required></td>
        <td><input type="text" name="surname" value="<?php echo htmlspecialchars($row['surname']); ?>" required></td>
        <td><input type="email" name="email" value="<?php echo htmlspecialchars($row['email']); ?>" required></td>
        <td><input type="text" name="position" value="<?php echo htmlspecialchars($row['position']); ?>" required></td>
        <td><input type="date" name="date" value="<?php echo htmlspecialchars($row['date']); ?>" required></td>
        <td><select name="department">
              <option value="<?php echo htmlspecialchars($row['department']); ?>"><?php echo htmlspecialchars($row['department']); ?></option>
              <option value="Administrative ">Administrative </option>
              <option value="Human Resources">Human Resourses</option>
              <option value="Sales">Sales</option>
              <option value="Marketing">Marketing</option>
              <option value="Accounting">Accounting</option>
              <option value="I.T">I.T</option>
            </select ></td>
        <td class="action-buttons">
        <a href="profile.php?id=<?php echo $row['id']; ?>">
            <button type="button">Profile</button>
               </a>
            <button type="submit" name="update">Update</button>
            <a href="?delete=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure?')">
                <button type="button">Delete</button>
            </a>
           
        </td>
    </form>
</tr>
<?php } ?>

                
            </table>
           
     <h2>Search Results</h2>
    <form method="get">
        <input type="text" name="search" placeholder="Search by department, Position, or name" value="<?php echo htmlspecialchars($search); ?>">
        <button type="submit">Search</button>
    </form>
    <p>Go back to Home Page? <a href="HumanResources_page.php">Back</a></p>
    <p> <a href="logout.php">Logout</a></p>
     </div>
     
            
     
    
</body>
</html>