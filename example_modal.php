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
    <title>Item Management</title>
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
        $("#cancel_edit").click(function(){
            window.open('','_parent',''); 
            window.close(); 
        });
        
        function createItem()
        {
            if ((document.getElementById("itemNames").value == "") || (document.getElementById("department").value == "none")){
                alert("Please complete info.");
            } else {
                <?php 
//                    $con = mysql_connect('localhost','eForm','1234');
//                    if (!$con) {
//                        die('Could not connect: ' . mysql_error());
//                    }
//
//                    mysql_select_db("hr_eexit", $con);
//
//                    $result = mysql_query("select count(*) FROM item");
//                    $row = mysql_fetch_array($result);

                    //$total = $row[0];
                    //echo "Total rows: " . $total;
                //echo "Total rows: "; 
 //               mysql_close($con);
                
//                    $connection = getConnection();
//                    $sqlInsert = "Insert into user (Id, Firstname, Lastname, Password, Department, Location, Role) VALUES ('".$username."','".$firstname."','".$lastname."','".$encrypPassword."','".$department."','".strtoupper($location)."','".$role."')";
                ?>  
            }
            
        }; 
              
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
        <div class="container" align="center">
                <form action="item.php" method="post" align="center">
                    Item ID : <input type="text" name="userId"> &nbsp;
                    Department :  <div id="includedDepartment"></div> &nbsp;     
                    <input class="btn btn-primary" type="submit" name="submit" value="Search">
                </form>
        </div>
        <br>
    </div>
    <div align="center">
        <input class="btn btn-info" id="createItemBtn" class="modal" name="create" value="Create" style="width:200px;">
    </div>
    <br>
    <!--<form action="/action_page.php" method="get" id="checkReq">-->
        <table border="1px">
            <tr>        
                <th>Item ID</th>
                <th>Item name</th>
                <th>Department</th>
                <th>Action</th>
            </tr>
            
             <?php 
                if(isset($_POST['submit'])){
                    
                    $itemId = $_POST['userId'];
                    $departmentId = $_POST['department'];
                    $status = $_POST['status'];
                    $sql = "SELECT * FROM item ";
                    
                    if (($itemId != '') || ($departmentId != 'none')) {
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
                        $sql .= $extention;
                    }    
                    $connection = mysqli_connect('localhost','eForm','1234','hr_eexit');
                    if($connection){
                        $result = $connection->query($sql);
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo "<tr id='1'>";
                                echo "<td>".$row['Id']."</td><td>".$row['Name']."</td><td>".$row['DepartmentId']."</td><td><button onclick=reqExit('".$row['Id']."')>Update</button>&nbsp;<button onclick=reqExit('".$row['Id']."')>Delete</button></td>";
                                echo "</tr>";  
                            }
                        }
                    }               
                } 
            ?>     
    </table>
    
    <!-- Create Item Modal -->
    <div id="createItemModal" class="modal">
        <!-- Modal content -->
        <div class="modal-content"> 
            <span class="close" align="right">&times;</span>
            <form action="createItem.php" method="get">
                <h5>Create Item</h5>
                <br>
                    Item Name : &nbsp; <input style="width: 400px; padding: 2px; border: 1px solid black" type="text" id="itemNames">
                    <br>
                    <br>
                    Department : 
                    <select id="department" class="custom-select" style="width:400px;">
                        <option value="none" selected>None</option>
                        <option value="D01">Commercial</option>
                        <option value="D02">Customer Service</option>
                        <option value="D03">Finance</option>
                        <option value="D04">Information Technology</option>
                        <option value="D05">Operations</option>
                    </select>
                <br>
                <br>
                <div align="right">
                    <input class="btn btn-success" onclick="createItem()" name="submit" value="Create" style="width:150px;">
                </div>         
            </form>
           
            
        </div>
    
    </div>
    <script>
        var modal = document.getElementById("createItemModal");

        // Get the button that opens the modal
        var btn = document.getElementById("createItemBtn");

        // Get the <span> element that closes the modal
        var span = document.getElementsByClassName("close")[0];

        // When the user clicks the button, open the modal 
        btn.onclick = function() {
            modal.style.display = "block";
        }

        // When the user clicks on <span> (x), close the modal
        span.onclick = function() {
            modal.style.display = "none";
        }

        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>
</body>
</html>