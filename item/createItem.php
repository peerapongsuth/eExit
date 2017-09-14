<?php     
    if(isset($_POST['create'])){    
        function getConnection(){
            $connection = mysqli_connect('localhost','eForm','1234','hr_eexit');
                if($connection){
                    return $connection;
                }
        }
        $connection = getConnection();
        if($connection){    
            $itemName = $_POST['itemName'];
            $departmentId = $_POST['department'];

            $sql = "SELECT count(*) as count FROM item WHERE DepartmentId = '".$departmentId."'";
            $count = 0;
            $result = $connection->query($sql);
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                   $count = $row['count']; 
                }
                $itemId = "";
                if($count < 10){
                    $itemId = "00" . ($count+1);
                } else if ($count < 100){
                    $itemId = "0" . ($count+1);
                } else {
                    $itemId = "" . ($count+1);
                }
                
                switch ($departmentId) {
                    case 'D01':
                        $itemId = "CO" . $itemId;
                        break;
                    case 'D02':
                        $itemId = "CS" . $itemId;
                        break;
                    case 'D03':
                        $itemId = "FI" . $itemId;
                        break;
                    case 'D04':
                        $itemId = "HR" . $itemId;
                        break;
                    case 'D05':
                        $itemId = "IT" . $itemId;
                        break;
                    case 'D06':
                        $itemId = "OP" . $itemId;
                        break;
                    case 'D07':
                        $itemId = "AD" . $itemId;
                        break;     
                    case 'D08':
                        $itemId = "AL" . $itemId;
                        break;      
                }
   
                $sql = "INSERT INTO item (Id, Name, DepartmentId) VALUES ('".$itemId."', '".$itemName."', '".$departmentId."');";
                if ($connection->query($sql) === TRUE) {
                    echo "<script>alert('Success');</script>";
                    header('Refresh: 1 ' . $_SERVER['HTTP_REFERER']);
                } else {
                    echo "<script>alert('Error : ".str_replace("'", "", mysqli_error($connection))."');</script>";
                    header('Refresh: 1 ' . $_SERVER['HTTP_REFERER']);
                }
            }
        }
    }
?>