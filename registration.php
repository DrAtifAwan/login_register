<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="container">
    <?php
if (isset($_POST["submit"])) {
    $fullName = $_POST["username"] ?? '';
    $email = $_POST["email"] ?? '';
    $password = $_POST["password"] ?? '';
    $passwordRepeat = $_POST["repeat_password"] ?? '';
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    $errors = array();

    // 📝 **1. Basic Validation**
    if (empty($fullName) || empty($email) || empty($password) || empty($passwordRepeat)) {
        array_push($errors, "All fields are required");
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        array_push($errors, "Invalid email format");
    }
    if (strlen($password) < 8) {
        array_push($errors, "Password must be at least 8 characters long");
    }
    if ($password !== $passwordRepeat) {
        array_push($errors, "Passwords do not match");
    }

    // 📝 **2. Check for Duplicate Email**
    require_once "database.php";

    $checkEmailSQL = "SELECT * FROM users WHERE email = ?";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $checkEmailSQL)) {
        die("SQL Error: " . mysqli_error($conn));
    } else {
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            array_push($errors, "Email is already registered. Please use another email.");
        }
    }

    mysqli_stmt_close($stmt);

    // 📝 **3. Display Errors or Proceed with Registration**
    if (count($errors) > 0) {
        foreach ($errors as $error) {
            echo "<div class='alert alert-danger'>$error</div>";
        }
    } else {
        // 📝 **4. Register the User**
        $insertSQL = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        $stmt = mysqli_stmt_init($conn);

        if (mysqli_stmt_prepare($stmt, $insertSQL)) {
            mysqli_stmt_bind_param($stmt, "sss", $fullName, $email, $passwordHash);
            mysqli_stmt_execute($stmt);
            echo "<div class='alert alert-success'>You are registered successfully.</div>";
        } else {
            die("SQL Error: " . mysqli_error($conn));
        }

        mysqli_stmt_close($stmt);
        mysqli_close($conn);
    }
}
?>
                <form action="registration.php" method="post">
            <div class="form-group">
                <input type="text" class="form-control" name="username" placeholder="Enter your name">
            </div>
            <div class="form-group">
                <input type="email" class="form-control" name="email" placeholder="Email">
            </div>
            <div class="form-group">
                <div class="input-group">
                    <input type="password" class="form-control" name="password" id="password" placeholder="Password:">
                    <button type="button" class="btn btn-outline-secondary" id="togglePassword"></button>
                    <button type="reset" class="btn-reset">Reset</button>
                </div>
            </div>
            <div class="form-group">
                <div class="input-group">
                    <input type="password" class="form-control" name="repeat_password" id="repeat_password" placeholder="Repeat Password:">
                    <button type="button" class="btn btn-outline-secondary" id="toggleRepeatPassword"></button>
                </div>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Register" name="submit">
            </div>
        </form>
    </div>
    <script>
        document.getElementById('togglePassword').addEventListener('click', function () {
            const passwordField = document.getElementById('password');
            const type = passwordField.type === 'password' ? 'text' : 'password';
            passwordField.type = type;
            this.textContent = type === 'password' ? 'Show' : 'Hide';
        });
        document.getElementById('toggleRepeatPassword').addEventListener('click', function () {
            const repeatPasswordField = document.getElementById('repeat_password');
            const type = repeatPasswordField.type === 'password' ? 'text' : 'password';
            repeatPasswordField.type = type;
            this.textContent = type === 'password' ? 'Show' : 'Hide';
        });
    </script>
 </div>
 <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redirect to Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .message-container {
            background: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            text-align: center;
        }

        .message-container p {
            font-size: 16px;
            margin-bottom: 10px;
        }

        .message-container a {
            color: #007bff;
            text-decoration: none;
            font-weight: bold;
        }

        .message-container a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Custom Login Button</title>
  <style>
/* Message Container Styling */
.message-container {
    text-align: center; /* Text ko center mein rakhega */
    margin-top: 20px; /* Form se distance banayega */
    padding: 10px;
    background: rgba(255, 255, 255, 0.6); /* Transparent background */
    border-radius: 5px; /* Rounded corners */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Light shadow */
}

/* Login Button Styling */
.message-container .login-btn {
    display: inline-block; /* Inline-block se button align rahega */
    margin-top: 10px;
    background: #007bff; /* Blue background */
    color: #fff; /* White text */
    padding: 8px 15px;
    border-radius: 5px;
    text-decoration: none; /* Underline hatayega */
    font-weight: bold;
}

.message-container .login-btn i {
    margin-right: 5px;
}
  </style>
</head>
<body>
  <!-- Message Section -->
  <div class="message-container">
    <p>Already have an account?</p>
    <a href="login.php" class="login-btn">
      <i>🔒</i> Login
    </a>
  </div>
</body>
</html>



</body>
</html>