    <?php 
        $application_path = "http://localhost:2222/eExit/";
    ?>

    <img id="banner"> 
    <div class="menu-wrap">
        
        <div class="dropdown">
            <button class="dropbtn">Employee Management</button>
            <div class="dropdown-content">
                <a href="<?php echo $application_path.'/index.php'; ?>">Employee</a>
                <a href="<?php echo $application_path.'/employee/processExit.php'; ?>" >Request Exit</a>
            </div>
        </div>
        
        <div class="dropdown">  
            <button class="dropbtn">User Management</button>
            <div class="dropdown-content">
                <a href="<?php echo $application_path.'/user/user.php'; ?>">User</a>
                <a href="<?php echo $application_path.'/user/addUser.php'; ?>">Add User</a>
            </div>
        </div>
    
        <div class="dropdown">
            <button class="dropbtn">Item Management</button>
            <div class="dropdown-content">
                <a href="<?php echo $application_path.'/item/item.php'; ?>">Item</a>
            </div>
        </div>

        <div class="dropdown">
            <button class="dropbtn">Account Management</button>
            <div class="dropdown-content">
                <a href="item/item.php">Change Password</a>
                <a href="auth/login.php">Logout</a>
            </div>
        </div>
        
    </div>
   