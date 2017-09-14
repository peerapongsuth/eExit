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
    <script type="text/javascript"> 
        $(function(){
            $("#includedContent").load("../menu.php"); 
        });
    </script>
</head>
<body>
    <?php
        if(!empty($_COOKIE['session'])) {
            $connection = getConnection();
            if($connection){
                $sql = "SELECT * FROM session WHERE Id like '".$_COOKIE["session"]."'";
                $result = $connection->query($sql);
                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    setcookie("userId", $row['UserId'], time()+3600);
                } else {
                    header("Location: http://localhost:2222/start_demo/mysql/login.php");
                    exit();
                }
            }
            $connection->close();
        }else{
            header("Location: http://localhost:2222/start_demo/mysql/login.php");
            exit();
        }    
    ?>
    
    <div id="includedContent"></div>
     <div class="container">
        <br>
        <form action="index.php" method="post" align="center">
            Employee ID : <input type="text" name="empId"> &nbsp;
            <input class="btn btn-primary" type="submit" name="submit" value="Search">
        </form>
        <br>
    </div>
    <div class="title-text">Employee ID : <input class="input-text" type="text" size="30" value="" /></div> <br>
    <div class="title-text">Department : <input class="input-text" type="text" size="30" value="" /></div> <br>
    <div class="title-text">Hire Date : <input class="input-text" type="text" size="30" value="" /></div> <br>
    <div class="title-text">Email : <input class="input-text" type="text" size="30" value="" /></div> <br>
    <div class="title-text">Supervisor : <input class="input-text" type="text" size="30" value="" /></div> <br>
        <table border="1px">
            <tr>        
                <th>Item ID</th>
                <th>Item Name</th>
                <th>Received</th>
                <th>Returned</th>
            </tr>

            <?php 
                //sdfsdfsdf
            ?>
            <!-- <?php 
                if(isset($_POST['submit'])){                   
                    $empId = $_POST['empId'];
                    $connection = mysqli_connect('localhost','eForm','1234','hr_eexit');
                    if($connection){
                        $sql = "SELECT UserId,ItemId,item.Name FROM `return_status` INNER JOIN item ON return_status.ItemId=item.Id WHERE UserId = ".$empId;  
                        $result = $connection->query($sql);
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo "<tr id='row1'>";
                                echo "<td>".$row['Id']."</td><td>".$row['Firstname']."</td><td>".$row['Lastname']."</td><td>".$row['Location']."</td><td>".$row['SupervisorId']."</td><td>".$row['Email']."</td><td>".$row['Status']."</td><td><button onclick=reqExit('".$row['Id']."')";
                                    if($row['Status'] != 'Active'){echo ' disabled';} 
                                echo ">Request Exit</button></td>";
                                echo "</tr>";                        
                            }
                        }
                    }
                    $connection->close();           
                } 
            ?> -->
        </table>
        <br>
        <input name="request" type="submit" value="Request" hidden="hidden">
</body>
</html>
