<?php
session_start();
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("location: index.php");
    exit;
}
if (isset($_REQUEST['alert'])) {
    echo"<script>alert('Please sign in before proceeding!')</script>";
}
include 'connection.php';
if (isset($_POST['login'])) {
    $sql = "SELECT * FROM user WHERE username = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $param_username);
        $param_username = $_POST['username'];
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        if ($row) {
            if (password_verify($_POST['password'], hex2bin($row['password']))) {
                $_SESSION["loggedin"] = true;
                $_SESSION["member_id"] = $row['id'];
                header("location: index.php");
                exit;
            } else {
                $error = "<span class='error'>The password or username you entered was not valid.</span>";
            }
        } else {
            $error = "<span class='error'>The password or username you entered was not valid.</span>";
        }
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>HSE Covid-19 reporting portal</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="generator" content="Jekyll v4.0.1">
        <link rel="canonical" href="https://getbootstrap.com/docs/4.5/examples/starter-template/">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
        <style>
            .error{
                color: red;
            }
        </style>
    </head>
    <body >
        <h1>HSE Covid-19 reporting portal</h1>
        <div class="col-4 ">
            <form class="form-signin" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <h1 class="h3 mb-3 font-weight-normal">Sign in</h1>
                <label for="inputEmail" class="sr-only">Username</label>
                <input type="text" name="username" class="form-control" placeholder="Username" required autofocus>                
                <label for="inputPassword" class="sr-only">Password</label>
                <input type="password" name="password" class="form-control" placeholder="Password" required>
                <?php if (isset($error)) echo "<p class='error'>".$error.'</p>' ?>
                <button class="btn btn-lg btn-primary btn-block" type="submit" name="login">Sign in</button>

                <div class="mt-1">
                    <div>Don't have an account? <a href="register.php">Click Here</a></div>      
                </div>
            </form>
        </div>

    </body>
</html>
