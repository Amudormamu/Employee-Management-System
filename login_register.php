<?php
session_start();
require_once 'config.php';

if (isset($_POST['register'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    if ($role != 'Human Resources') {
        $_SESSION['register_error'] = 'Please select a valid role.';
        $_SESSION['active_form'] = 'register';
    } else {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $checkEmail = $conn->query("SELECT email FROM users WHERE email = '$email'");

        if ($checkEmail->num_rows > 0) {
            $_SESSION['register_error'] = 'Email already registered!';
            $_SESSION['active_form'] = 'register';
        } else {
            $conn->query("INSERT INTO users (name, email, password, role) VALUES ('$name', '$email', '$password', '$role')");
            $_SESSION['register_error'] ="<p style='color: green;'> Registered successfully!</p>" ;
            $_SESSION['active_form'] = 'register';
        }
    }

    header("Location: register.php");
    exit();
}

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $result = $conn->query("SELECT * FROM users WHERE email = '$email'");
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['name'] = $user['name'];
            $_SESSION['email'] = $user['email'];

            if ($user['role'] === '') {
                header("Location: register.php");
            } else {
                header("Location: HumanResources_page.php");
            }
            exit();
        }
    }

    $_SESSION['login_error'] = 'Incorrect email or password';
    $_SESSION['active_form'] = 'login';
    header("Location: index.php");
    exit();
}
?>
