<?php 
	function getConnection(){
        $connection = mysqli_connect('localhost','eForm','1234','hr_eexit');
            if($connection){
                return $connection;
            }
    }

    $connection = getConnection();
    if($connection){
        $sql = "UPDATE employee SET Status = 'Pending' WHERE Id = '".$_POST['empId']."'";
        if ($connection->query($sql) === TRUE) {
        	$sql = "SELECT * FROM employee WHERE Id like '".$_POST['empId']."'";
        	$result = $connection->query($sql);
            if ($result->num_rows > 0) {
            	$row = $result->fetch_assoc();
            	$sqlItem = "SELECT * FROM item";
            	$resultItem = $connection->query($sqlItem);
            	
            		if ($resultItem->num_rows > 0) {
            			while($rowItem = $resultItem->fetch_assoc()) {
            				$sqlInsert = "Insert into return_status (UserId, ItemId) VALUES ('".$row['Id']."','".$rowItem['Id']."')";
		                    if ($connection->query($sqlInsert) === TRUE) {
		                    	echo "<script>alert('Success');</script>";
                    			header('Refresh: 1 ' . $_SERVER['HTTP_REFERER']);
		                    } else {
		                    	echo "<script>alert('Error : ".str_replace("'", "", mysqli_error($connection))."');</script>";
                    			header('Refresh: 1 ' . $_SERVER['HTTP_REFERER']);
		                    }
            			}
            		}
            	
            	
			}
        }
    }
    $connection->close(); 
?>