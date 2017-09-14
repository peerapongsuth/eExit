<?php  
    if(isset($_POST['submit'])){
        $username = $_POST['username'];
        $password = $_POST['password'];
        
        if($username && $password){
            $connection = mysqli_connect('localhost','eForm','1234','hr_eexit');
            if($connection){
                $sql = "SELECT * FROM user WHERE id like '".$username."'";
                $result = $connection->query($sql);
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        $encrypPassword = md5($password);
                        if($encrypPassword != $row['Password']){
                            echo "<script>alert('Invalid Username or Credential')</script>";        
                        }
                        else {
                            if(removeExistSession($connection, $username)){
                                $tz_object = new DateTimeZone('Asia/Bangkok');
                                $time = new DateTime();
                                $time->setTimezone($tz_object);
                                $createAt = new DateTime();
                                $createAt->setTimezone($tz_object);
                                $minutes_to_add = 15;
                                $time->add(new DateInterval('PT' . $minutes_to_add . 'M'));                       
                                $Session = md5(uniqid(rand(), true));
                                $createAt = $createAt->format('Y-m-d H:i');
                                $time = $time->format('Y-m-d H:i');
                                $sqlInsert = "Insert into Session (Id, UserId, Expire, CreateAt) VALUES ('".$Session."','".$row['Id']."','".$time."','".$createAt."')";
                                if ($connection->query($sqlInsert) === TRUE) {
                                    $connection->close();
                                    setcookie("session", $Session, time()+3600);  /* expire in 1 hour */
                                    header("Location: http://localhost:2222/start_demo/mysql/index.php");
                                    exit();
                                }
                                $connection->close(); 
                            }  
                        } 
                    }
                } else {
                    echo "0 results";
                }
            }else{
                echo "fail";
            }
        }else {
            echo "please insert username or password";
        }
    }

function removeExistSession($connection, $username){
    $sql = "DELETE FROM session WHERE UserId = '".$username."'";
    if ($connection->query($sql) === TRUE) {
        return true;
    } else {
        return false;
    }
}

function getCurrentTime(){
    $tz_object = new DateTimeZone('Asia/Bangkok');
    $time = new DateTime();
    $time->setTimezone($tz_object);
    return $time;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>HR-eExitForm</title>
    <link rel="stylesheet" type="text/css" href="./css/style.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous">
    <script type="text/javascript">
        function removeSession(){
            document.cookie = "userId" + '=;expires=Thu, 01 Jan 1970 00:00:01 GMT;';
            document.cookie = "session" + '=;expires=Thu, 01 Jan 1970 00:00:01 GMT;';
        }
    </script>
</head>
<body onload="removeSession()">
    <img id="banner">
    <br><br><br><br>
    <div class="container" align="center" >
       <h2>HR-eExit System</h2>
       <br>
        <div class="col-sm-6">
            <form action="/login.php" method="post">
                <div class="form-goup">
                    Username <input type="text" name="username" class="form-control">      
                </div>
                <div class="form-goup">
                    Password <input type="password" name="password" class="form-control">      
                </div><br>
                <input class="btn btn-primary" type="submit" name="submit" value="Submit">
            </form>
        </div>
    </div> 
</body>
</html>