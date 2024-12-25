<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    
</head>
<body>
    <div class="container">
        <?php
        if(isset($_POST["login"])){
            $email = $_POST["email"];
            $password = $_POST["password"];
            require_once "database.php";
            $sql = "SELECT * From users WHERE email = '$email'";
            $result= mysqli_query($conn, $sql);
            $user = mysqli_fetch_array($result, MYSQLI_ASSOC);
            if ($user) {
                if (passwor_verify($password, $user["password"])) {
                    header("Location: index.php");
                    die();
                }else{
                    echo "<div class='alet alert-danger'>Password does not match</div>";
                }
            }else{
                echo "<div class='alet alert-danger'>Email does not match</div>";
            }
        }
        ?>
<?php
// Start session
session_start();

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get username and password from form
    $username = $_POST['email'];
    $password = $_POST['password'];

    // Dummy authentication (replace with database check in production)
    if ($username === 'admin' && $password === 'password') {
        // Set session variables
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $username;

        // Redirect to dashboard
        header("Location: index.php");
        exit;
    } else {
        // Invalid credentials message
        $error = "Invalid username or password.";
    }
}
?>

        <form action="login.php" method="post">
            <div class="form-group">
                <input type="email" placeholder="Enter Email" name="email:" class="form-control">
            </div>
            <div class="form-group">
                <input type="password" placeholder="password" name="password" class="form-control">
            </div>
            <div class="form-btn">
                <input type="submit" value="login" name="login" class="btn btn-primary">
            </div>
        </form>
    </div>
</body>
</html>