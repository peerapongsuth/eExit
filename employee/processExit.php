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
                    header("Location: http://localhost:2222/eExit/login.php");
                    exit();
                }
            }
            $connection->close();
        }else{
            header("Location: http://localhost:2222/eExit/login.php");
            exit();
        }    
    ?>
    
    <div id="includedContent"></div>
     <div class="container">
        <br>
        <form action="processExit.php" method="get" align="center">
            Employee ID : <input type="text" name="empId"> &nbsp;
            <input class="btn btn-primary" type="submit" name="submit" value="Search">
        </form>
        <br>
    </div>
    <!-- <div class="title-text">Employee ID : <input class="input-text" type="text" size="30" value="" /></div> <br>
    <div class="title-text">Department : <input class="input-text" type="text" size="30" value="" /></div> <br>
    <div class="title-text">Hire Date : <input class="input-text" type="text" size="30" value="" /></div> <br>
    <div class="title-text">Email : <input class="input-text" type="text" size="30" value="" /></div> <br>
    <div class="title-text">Supervisor : <input class="input-text" type="text" size="30" value="" /></div> <br> -->
        <table border="1px">
            <tr>        
                <th>Item ID</th>
                <th>Item Name</th>
                <th>Received</th>
                <th>Returned</th>
            </tr>

            <?php 
                if(isset($_GET['submit'])){                   
                    $empId = $_GET['empId'];
                    $connection = mysqli_connect('localhost','eForm','1234','hr_eexit');
                    if($connection){
                        $sqlEmp = "SELECT employee.Id as Id, employee.Firstname as Firstname, employee.Lastname as Lastname, employee.Title as Title, department_emp.DepartmentName as dname, employee.Email as Email, employee.SupervisorEmail as SupervisorEmail FROM employee INNER JOIN department_emp ON department_emp.Id = employee.DepartmentId WHERE employee.Id like '".$empId."'";
                        $resultEmp = $connection->query($sqlEmp);
                        $rowEmp = $resultEmp->fetch_assoc();
                        echo "<div class='container'><strong>Employee ID : </strong><input class='input-text' type='text' size='30' value=' ".$empId."' disabled/>";
                        echo " <strong>Firstname : </strong><input class='input-text' type='text' size='30' value=' ".$rowEmp['Firstname']."' disabled/>";
                        echo " <strong>Lastname : </strong><input class='input-text' type='text' size='30' value=' ".$rowEmp['Lastname']."' disabled/></div> <br>";
                        echo " <div class='container'><strong>Title : </strong><input class='input-text' type='text' size='30' value=' ".$rowEmp['Title']."' disabled/>";
                        echo " <strong>Department : </strong><input class='input-text' type='text' size='30' value=' ".$rowEmp['dname']."' disabled/>";
                        echo " <strong>Email : </strong><input class='input-text' type='text' size='30' value=' ".$rowEmp['Email']."' disabled/> </div> <br>";
                        echo " <div class='container'> <strong>Supervisor Email : </strong><input class='input-text' type='text' size='30' value=' ".$rowEmp['SupervisorEmail']."' disabled/></div> <br>";
                        $sql = "SELECT UserId,ItemId,item.Name as Name,Received,Returned FROM return_status INNER JOIN item ON return_status.ItemId=item.Id WHERE UserId = '".$empId."'";  
                        $result = $connection->query($sql); 
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo "<tr id='row1'>";
                                echo "<td>".$row['ItemId']."</td><td>".$row['Name']."</td><td>".$row['Received']."</td><td>".$row['Returned']."</td>";
                                echo "</tr>";                        
                            }
                        }
                    }
                    $connection->close();           
                } 
            ?>
        </table>
        <br>
        <input name="request" type="submit" value="Request" hidden="hidden">
</body>
</html>
