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
        $(function(){
            $("#includedDepartment").load("../department.php"); 
        });
        $("#cancel_edit").click(function(){
            window.open('','_parent',''); 
            window.close(); 
        });

        function doSubmit(){    
            if (document.getElementById("itemName").value == '' || document.getElementById("department").value == 'none') {
                alert("Please Complete Information.");
                return false;
            }
        }
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
                    header("Location: http://localhost:2222/eExit//login.php");
                    exit();
                }
            }
            $connection->close();
        }else{
            header("Location: http://localhost:2222/eExit//login.php");
            exit();
        }    
    ?>
    
    <div id="includedContent"></div>
     <div class="container">
        <br>
        <form action="item.php" method="post" align="center">
            Item ID : <input type="text" name="itemId"> &nbsp;
            Department : 
            <select name="department" class="custom-select">
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
            </select> &nbsp;
            <input class="btn btn-primary" type="submit" name="submit" value="Search">
        </form>
        <br>
        <div align="center">
            <input class="btn btn-info" id="createItemBtn" class="modal" name="create" value="Create" style="width:200px;">
        </div>
        <br>
    </div>
    <!--<form action="/action_page.php" method="get" id="checkReq">-->
        <table border="1px">
            <tr>        
                <th>Item ID</th>
                <th>Item Name</th>
                <th>Action</th>
            </tr>
            <?php 
                if(isset($_POST['submit'])){
                    $itemId = $_POST['itemId'];
                    $departmentId = $_POST['department'];
                    $sql = "SELECT * FROM item ";
                    
                    if (($itemId != '') || ($departmentId != 'none')) {
                        $isFirst = true;
                        $extention = "WHERE ";
                        if ($itemId != '') {
                            $extention .= "id like '".$itemId."' ";
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
                    $connection = getConnection();
                    if($connection){    
                        $result = $connection->query($sql);
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                $status = "work";
                                if ($row['ExitDate'] != NULL){
                                    $status = "exit";
                                }
                                echo "<tr id='row1'>";
                                echo "<td>".$row['Id']."</td><td>".$row['Name']."</td><td><button onclick=reqExit('".$row['Id']."')>Update</button><button onclick=reqExit('".$row['Id']."')>Delete</button></td>";
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

     <!-- Create Item Modal -->
    <div id="createItemModal" class="modal">
        <!-- Modal content -->
        <div class="modal-content"> 
            <span class="close" align="right">&times;</span>
            <form id="createItem" action="createItem.php" method="post" onSubmit="return doSubmit();">
                <h5>Create Item</h5>
                <br>
                    Item Name : &nbsp; <input style="width: 400px; padding: 2px; border: 1px solid black" type="text" name="itemName" id="itemName">
                    <br>
                    <br>
                    Department : 
                    <select id="department" name="department" class="custom-select" style="width:400px;">
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
                <br>
                <br>
                <div align="right">
                    <input class="btn btn-success" type="submit" name="create" value="Create Item" style="width:150px;">
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
