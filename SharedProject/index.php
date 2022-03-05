<?php
session_start();
include 'connection.php';
include 'functions.php';
if (isset($_SESSION['loggedin'])) {
    $sql = "SELECT * from user where id = {$_SESSION['member_id']}";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $key = $row['password'];
        $iv = $row['iv'];
        $fullname = decrypt_data($row['fullname'], $key, $iv);
        $address = decrypt_data($row['address'],$key, $iv);
        $dob = decrypt_data($row['dob'], $key, $iv);
        $phoneNo = decrypt_data($row['phoneNo'],$key ,$iv);
        $img = decrypt_data($row['img'], $key, $iv);
    }
    
    if(isset($_POST['closecontact']))
    {
        $encrypted_name = encrypt_data($_POST['ccname'], $row['password'], hex2bin($row['iv']));
        $encrypted_phone = encrypt_data($_POST['ccphone'], $row['password'], hex2bin($row['iv']));
        $sql = "INSERT INTO `closecontact`(`assoc_id`, `fullname`, `phoneNo`) VALUES ('{$row['id']}','$encrypted_name','$encrypted_phone')";
        if ($conn->query($sql) === TRUE) {
            echo '<script>alert("New close contact added!")</script>';
        } else {
            die('Error adding close contact' . $conn->error);
        }
    }
    
    
    $sql = "SELECT * from closecontact where assoc_id = {$_SESSION['member_id']}";
    $result = $conn->query($sql);
    $numrow = $result->num_rows;
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
    </head>
    
    <body>       
        <a href="index.php" style="text-decoration: none"><h1 class="text-center">HSE Covid-19 reporting portal</h1></a>
        <div class="container text-center">
            <div <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin']) {
                echo"hidden";
            } ?>>
                <a href="login.php"><button type="submit">Login</button></a>
                <a href="register.php"><button>Register</button></a>
            </div>
            <br>           
            <div <?php if (!isset($_SESSION['loggedin'])) {
                echo"hidden";
            } ?>>                
                <div>
                    <table class="table table-borderless">
                        <thead>
                            <tr><th > <h3>User's Details</h3></th></tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th>Fullname: </th>
                                <td><?php echo $fullname; ?></td>
                            </tr>

                            <tr>
                                <th>Address: </th>
                                <td><?php echo $address; ?></td>
                            </tr>

                            <tr>
                                <th>Date of Birth: </th>
                                <td><?php echo $dob; ?></td>
                            </tr>

                            <tr>
                                <th>Phone Number: </th>
                                <td><?php echo $phoneNo; ?></td>
                            </tr>

                            <tr>
                                <th>Antigen Test Result: </th>
                                <td><?php echo "<img width='500' height='500' src='Image/" . $img . "'/>"; ?></td>
                            </tr>

                            <tr>
                                <th><h3>Close Contact List: </h3></th>                               
                            </tr>

                            <?php
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr><th>Name:</th>"
                                . "<td>"
                                . decrypt_data($row['fullname'], $key, $iv)
                                . "</td>"
                                . "<th>Phone Number :</th>"
                                . "<td>"
                                . decrypt_data($row['phoneNo'], $key, $iv)
                                . "</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <br/>
                <form method="post" action="index.php" align="center">
                    <div class="form-table">
                        <div class="form-head"><h2>Add Close Contact</h2></div>

                        <div class="field-column">            
                            <label>Full Name: </label>
                            <div>
                                <input type="text" class="input-box" placeholder="Full name" name="ccname" required>
                            </div>      
                        </div>
                        <div class="field-column">
                            <label>Phone Number: </label>
                            <div>
                                <input type="text" class="input-box" name="ccphone" placeholder="0891234567" required>
                            </div>
                        </div>
                        <br/>
                         <div class="field-column">
                            <input type="submit" name="closecontact" value="Add">
                        </div>
                    </div>
                </form>
                <br/>
                <a href="logout.php"><button class="btn-danger btn-md"type="submit">Logout</button></a>
            </div> 
        </div>
    </body>
</html>
