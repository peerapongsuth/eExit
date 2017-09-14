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
    <link rel="stylesheet" type="text/css" href="./css/style.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css">
    <script src="jquery.js"></script> 
    <script src="./lib/jquery-3.2.1.min.js"></script>
    <script type="text/javascript">
        function reqExit(empID)
        {
            var answer = confirm("Request Exit for "+empID);
            if (answer) {
                var empId = empID;
                $.post("http://localhost:2222/eExit/employee/requestExit.php", { empId: empID} );
            } else {
                return false;
            } 
        } 
        $(function(){
            $("#includedContent").load("menu.php"); 
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
        <form action="index.php" method="post" align="center">
            Employee ID : <input type="text" name="userId"> &nbsp;
            Department : 
            <select name="department" class="custom-select">
                <option value="none" selected>None</option>
                <?php
                    $connection = getConnection();
                    $result = $connection->query("SELECT * FROM department_emp");
                    if ($result->num_rows > 0) {
                       while($row = $result->fetch_assoc()) {
                            echo '<option value="'.$row['Id'].'">'.$row['DepartmentName'].'</option>';
                        }
                    }
                    $connection->close();   
                ?>
            </select> &nbsp;
            Status :
            <select name="status" class="custom-select">
                <option value="none" selected>None</option>
                <option value="Active">Active</option>
                <option value="Pending">Pending</option>
                <option value="Exit">Exit</option>
            </select> &nbsp;
            <input class="btn btn-primary" type="submit" name="submit" value="Search">
        </form>
        <br>
    </div>
    <!--<form action="/action_page.php" method="get" id="checkReq">-->
        <table border="1px">
            <tr>        
                <th>Emp ID</th>
                <th>Firstname</th>
                <th>Lastname</th>
                <th>Location</th>
                <th>SupervisorID</th>
                <th>Email</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            <?php 
                if(isset($_POST['submit'])){
                    
                    $empId = $_POST['userId'];
                    $departmentId = $_POST['department'];
                    $status = $_POST['status'];
                    $sql = "SELECT * FROM employee ";
                    
                    if (($empId != '') || ($departmentId != 'none') || ($status != 'none')) {
                        $isFirst = true;
                        $extention = "WHERE ";
                        if ($empId != '') {
                            $extention .= "id like '".$empId."' ";
                            $isFirst = false;
                        }
                        if ($departmentId != 'none') {
                            if (!$isFirst) {
                                $extention .= "AND ";
                            }
                            $extention .= "DepartmentId like '".$departmentId."' ";
                        }
                        if ($status != 'none') {
                            if (!$isFirst) {
                                $extention .= "AND ";
                            }
                            $extention .= "Status like '".$status."' ";
                        }
                        $sql .= $extention;
                    }
                           
                    $connection = mysqli_connect('localhost','eForm','1234','hr_eexit');
                    if($connection){
                        
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
            ?>
        </table>
        <br>
        <input name="request" type="submit" value="Request" hidden="hidden">
    <!--<</form>-->
</body>
</html>
