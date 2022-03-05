<?php
session_start();
include 'connection.php';
include 'functions.php';
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("location: index.php");
    exit;
}
if (isset($_POST['username'])) {
    $sql = "SELECT id FROM user WHERE username = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $param_username);
        $param_username = $_POST['username'];
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->fetch_assoc()) {
            $username_Err = 'Username has been registered.';
        } else {

            $input_username = $_POST['username'];
            $input_password = $_POST['password'];
            $input_fullname = $_POST['fullname'];
            $input_address = $_POST['address'];
            $input_dob = $_POST['dob'];
            $input_phoneNo = $_POST['phoneNo'];
            $input_img = $_FILES['img']['name'];

            $tmp_file = $_FILES['img']['tmp_name'];
            $img_destination = "Image/" . $_FILES['img']['name'];
            if (move_uploaded_file($tmp_file, $img_destination)) {
                $iv = random_bytes(16);
                $iv_hex = bin2hex($iv);
                $escaped_username = $conn->real_escape_string($_POST['username']);
                $hashed_pw = hash_data($input_password);
                $encrypted_fullname = encrypt_data($input_fullname, $hashed_pw, $iv);
                $encrypted_address = encrypt_data($input_address, $hashed_pw, $iv);
                $encrypted_dob = encrypt_data($input_dob, $hashed_pw, $iv);
                $encrypted_phoneNo = encrypt_data($input_phoneNo, $hashed_pw, $iv);
                $encrypted_img = encrypt_data($input_img, $hashed_pw, $iv);
                $sql = "INSERT INTO `user`(`username`, `password`, `iv`, `fullname`, `address`, `dob`, `phoneNo`, `img`) "
                        . "VALUES "
                        . "('$escaped_username','$hashed_pw','$iv_hex','$encrypted_fullname','$encrypted_address','$encrypted_dob','$encrypted_phoneNo','$encrypted_img')";

                if ($conn->query($sql) === TRUE) {
                    header("location: index.php");
                    exit;
                } else {
                    die('Error creating user: ' . $conn->error);
                }
            }
        }
    }
}
?>
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
    <body>       
        <a href="index.php" style="text-decoration: none"><h1 class="text-center">HSE Covid-19 reporting portal</h1></a>
        <div class="container">
            <form name="frmRegistration" method="post" action="register.php" align="center" enctype="multipart/form-data">
                <div class="form-table">
                    <div class="form-head">Register</div>
                     <div class="field-column">            
                        <label>Username</label>
                        <div>
                            <input type="text" class="input-box" placeholder="Full name" name="username" required value="<?php
                            if (!empty($_POST['username'])) {
                                echo $_POST['username'];
                            }
                            ?>">
                        </div>  
                        <p class="error"> <?php if(isset($username_Err)){echo $username_Err;}?> </p>
                    </div>

                    <div class="field-column">
                        <label>Password</label>
                        <div><input type="password" class="input-box" name="password" maxlength="20" required></div>

                    </div>

                    <div class="field-column">            
                        <label>Full Name</label>
                        <div>
                            <input type="text" class="input-box" placeholder="Full name" name="fullname" required value="<?php
                            if (!empty($_POST['fullname'])) {
                                echo $_POST['fullname'];
                            }
                            ?>">
                        </div>      
                    </div>

                    <div class="field-column">
                        <label>Address</label>
                        <div>
                            <input type="text" class="input-box" name="address" placeholder="address" required value="<?php
                            if (!empty($_POST['address'])) {
                                echo $_POST['address'];
                            }
                            ?>">
                        </div>
                    </div>

                    <div class="field-column">
                        <label>Date of Birth</label>
                        <div>
                            <input type="date" class="input-box" name="dob" placeholder="abcdefg@gmail.com" required value="<?php
                            if (!empty($_POST['dob'])) {
                                echo $_POST['dob'];
                            }
                            ?>">
                        </div>
                    </div>

                    <div class="field-column">
                        <label>Phone Number</label>
                        <div>
                            <input type="text" class="input-box" name="phoneNo" placeholder="0891234567" required value="<?php
                            if (!empty($_POST['phoneNo'])) {
                                echo $_POST['phoneNo'];
                            }
                            ?>">
                        </div>
                    </div>

                    <div class="field-column">
                        <label>Upload antigen-test image</label>
                        <div><input type="file" name="img" required ></div>                 
                    </div>
                    <br>
                    <div class="field-column">
                        <input type="submit" name="register"  value="Register">
                    </div>
                </div>          
            </form>
         </div>
    </body>
</html>



