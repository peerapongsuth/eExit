<!DOCTYPE html>
<html lang="en">
<head>
<?php
    function getConnection(){
        $connection = mysqli_connect('localhost','eForm','1234','hr_eexit');
            if($connection){
                return $connection;
            }
    }
?>
    <meta charset="UTF-8">
    <title>HR-eExitForm</title>
    <link rel="stylesheet" type="text/css" href="../css/style.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css">
    <script src="../lib/jquery-3.2.1.min.js"></script>
    <script>
        $(function(){
            $("#includedContent").load("../menu.php"); 
        });
        $(function(){
            $("#includedDepartment").load("../department.php"); 
        });
    </script>
</head>
<body>
    <div id="includedContent"></div>
    <br>
    <div class="container" align="center">
        <div class="col-sm-6">
            <form action="addUser.php" method="post">
                <div class="form-goup">
                    ID <input type="text" name="username" class="form-control">      
                </div>
                <div class="form-goup">
                    Password <input type="password" name="password" class="form-control">      
                </div>
                <div class="form-goup">
                    Confirm - password <input type="password" name="repassword" class="form-control">      
                </div>
                <div class="form-goup">
                    Firstname <input type="text" name="firstname" class="form-control">      
                </div>
                <div class="form-goup">
                    Lastname <input type="text" name="lastname" class="form-control">      
                </div>
                <div class="form-goup">
                    Email <input type="text" name="email" class="form-control">      
                </div>
                Department
                <select name="department" class="form-control">
                    <option value="none" selected>None</option>
                    <?php
                        $connection = getConnection();
                        $result = $connection->query("SELECT * FROM department");
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo '<option value="'.$row['Id'].'">'.$row['DepartmentName'].'</option>';
                            }
                        }
                        $connection->close();   
                    ?>
                </select>
                <div class="form-goup">
                    Location <input type="text" name="location" class="form-control">      
                </div>
                Role
                <select name="role" class="form-control">
                    <option value="none" selected>None</option>
                    <option value="admin">Admin</option>
                    <option value="user">User</option>
                </select> <br>
                <input class="btn btn-primary" type="submit" name="submit" value="Submit">
            </form>
        </div>
    </div> 
</body>
</html>

<?php 
    if(isset($_POST['submit'])){  
        
        $username = strtoupper($_POST['username']);
        $password = $_POST['password'];
        $repassword = $_POST['repassword'];
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $email = $_POST['email'];
        $department = $_POST['department'];
        $location = strtoupper($_POST['location']);
        $role = strtoupper($_POST['role']);

        if (($username == '') || ($firstname == '') || ($lastname == '') || ($location == '')) {
            echo "<script>alert('Please complete information')</script>";
        } else {
            if (strcmp($department, "none") == 0) {
                echo "<script>alert('Please select user department')</script>";
            }
        
            if (strcmp($role, "none") == 0) {
                echo "<script>alert('Please select user role')</script>";
            }
        
            if (strcmp($password, $repassword) !== 0) {
                echo "<script>alert('Password and confirm password not match')</script>";
            }
        
            $encrypPassword = md5($password);
            $connection = getConnection();
            if($connection){
                $sqlInsert = "Insert into user (Id, Firstname, Lastname, Password, DepartmentId, Location, Role, Email) VALUES ('".$username."','".$firstname."','".$lastname."','".$encrypPassword."','".$department."','".$location."','".$role."','".$email."')";
                if ($connection->query($sqlInsert) === TRUE) {
                    echo "<script>alert('Insert Complete')</script>";
                    $connection->close();  
                }else{
                    echo "<script>alert('Insert Fail')</script>";
                    $connection->close();  
                }
            } else {
                echo "<script>alert('Connection lost')</script>";
                $connection->close();  
            }
        } 
    }
?>