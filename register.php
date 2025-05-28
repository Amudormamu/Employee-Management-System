<?php
session_start();

$errors = [
    'register' => $_SESSION['register_error'] ?? ''
];
$activeForm = $_SESSION['active_form'] ?? 'register';

function showError($error) {
    return !empty($error) ? "<p class='error-message'>$error</p>" : '';
}

function isActiveForm($formName, $activeForm) {
    return $formName === $activeForm ? 'active' : '';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register</title>
  <link rel="icon" type="image/x-icon" href="image/logo.svg">
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="container">
    <div class="form-box <?= isActiveForm('register', $activeForm); ?>" id="register-form">
      <form action="login_register.php" method="post">
        <h2>Register</h2>
        <?= showError($errors['register']); ?>
        <input type="text" name="name" placeholder="Name" required>
        <input type="email" name="email" placeholder="Email" required>
        
        <div class="password-wrapper">
        <input type="password" name="password" placeholder="Password" id="myInput" required>
        <img src="image/eye_closed.svg" id="togglePassword">
        </div>

        <select name="role" required>
          <option disabled selected>--Select Role--</option>
          <option value="Human Resources">Human Resources</option>
        </select>
        <button type="submit" name="register">Register</button>
        <p>Already have an account? <a href="index.php">Login</a></p>
      </form>
    </div>
  </div>
  <script src="main.js"></script>
</body>
</html>

<?php session_unset(); ?>
