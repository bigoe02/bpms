

<div class="sticky-header header-section ">
      <div class="header-left">
        <!--toggle button start-->
        <button id="showLeftPush"><i class="fa fa-bars"></i></button>
        <!--toggle button end-->
        <!--logo -->
        <div class="logo">
          <a href="dashboard.php">
            <h1> <img src="images\logo.jpg" alt="Your logo" title="Your logo" ></h1>
            <span>AdminPanel</span>
          </a>
        </div>
        <!--//logo-->
      
    <?php
    // Check if the current page is dashboard.php
    if (basename($_SERVER['PHP_SELF']) === 'dashboard.php') {
        ?>
        <button type="button" class="btns btns--red" onclick="openModal()" style="width: 15%; height: 20%;">
            <span class="btns__txt">
                <img src="images/admin_icon/attendanceicon.png" alt="User  Clock" style="width: 50px; height: 50px;"/>
            </span>
            <i class="btns__bg" aria-hidden="true"></i>
            <i class="btns__bg" aria-hidden="true"></i>
            <i class="btns__bg" aria-hidden="true"></i>
            <i class="btns__bg" aria-hidden="true"></i>
        </button>
        <?php
    }
    ?>
       
        <div class="clearfix"> </div>
      </div>
      <div class="header-right">

        <div class="profile_details_left"><!--notifications of menu start -->
        <ul class="nofitications-dropdown">
    <?php
    // Query to get new appointments
    $ret1 = mysqli_query($con, "SELECT tbluser.FirstName, tbluser.LastName, tblbook.ID as bid, tblbook.AptNumber FROM tblbook JOIN tbluser ON tbluser.ID = tblbook.UserID WHERE tblbook.Status IS NULL");
    $num = mysqli_num_rows($ret1);
    
    // Query to get low stock items
    $lowstockquery = mysqli_query($con, "SELECT COUNT(*) as low_stock_count, tblinventory.product_name FROM tblinventory WHERE out_stocks <= 10 GROUP BY tblinventory.product_name");
    $lowstocknum = mysqli_num_rows($lowstockquery);

       // Query to get unread Inquery
       $unreadquery = mysqli_query($con, "SELECT COUNT(*) as unread_inquiry, FirstName from tblcontact where IsRead is null GROUP BY FirstName");
       $unreadQuerys = mysqli_num_rows($unreadquery);
    ?>  
    <li class="dropdown head-dpdn">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-bell"></i><span class="badge blue"><?php echo ($num > 0 ? $num : 0) + ($lowstocknum > 0 ? $lowstocknum : 0) + ($unreadQuerys > 0 ? $unreadQuerys : 0); ?></span></a>
        
        <ul class="dropdown-menu" >
            <li>
                <div class="notification_header">
                    <h3>You have <?php echo $num; ?> new appointment(s) , <?php echo $lowstocknum; ?> low stock notification(s) and <?php echo $unreadQuerys; ?>  unread new queries</h3>
                </div>
            </li>
            <li>
            <div class="notification_desc">
    <?php if ($num > 0 || $lowstocknum > 0 || $unreadQuerys > 0) {
        // Display new appointments
        while ($result = mysqli_fetch_array($ret1)) {
    ?>
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <a class="dropdown-item" href="view-appointment.php?viewid=<?php echo $result['bid']; ?>">New appointment received from <?php echo $result['FirstName']; ?> <?php echo $result['LastName']; ?></a>
            <i class="fa-solid fa-calendar-plus" style="color:#FFC600; font-size: 20px;"></i>
        </div>
        <hr />
    <?php 
        }
        
        // Display low stock notifications
        while ($lowStockResult = mysqli_fetch_array($lowstockquery)) {
    ?>
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <a class="dropdown-item" href="products.php?viewid=<?php echo $lowStockResult['product_name']; ?>">Warning: The product "<?php echo $lowStockResult['product_name']; ?>" has low stock</a>
            <i class="fa-solid fa-box-archive" style="color:#E87461; font-size: 20px;"></i>
        </div>
        <hr />
    <?php 
        }
        
        // Display unread messages
        while ($unreadqueryResult = mysqli_fetch_array($unreadquery)) {
    ?>
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <a class="dropdown-item" href="unreadenq.php?viewid=<?php echo $unreadqueryResult['FirstName']; ?>"><?php echo $unreadqueryResult['FirstName']; ?> Has message you.</a>
            <i class="fa-solid fa-envelope" style="color:#2A628F; font-size: 20px;"></i>
        </div>
        <hr />
    <?php
        }
    } else { ?>
        <a class="dropdown-item" href="all-appointment.php">No New Appointment Received</a>
    <?php } ?>
</div>
                <div class="clearfix"></div>  
            </li>
            <li>
                <div class="notification_bottom">
                    <a href="new-appointment.php">See all notifications</a>
                </div> 
            </li>
        </ul>
    </li> 
</ul>
          <div class="clearfix"> </div>
        </div>
        <!--notification menu end -->
        <div class="profile_details">  
        <?php
$adid=$_SESSION['bpmsaid'];
$ret=mysqli_query($con,"select * from tbladmin where ID='$adid'");
$row=mysqli_fetch_array($ret);
$name=$row['AdminName'];
$role=$row['role'];
$image=$row['imageEmployee'];

?> 
          <ul>
            <li class="dropdown profile_details_drop">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                <div class="profile_img"> 
                  <span class="prfil-img"><img src="images/imageEmployee/<?php echo htmlspecialchars($image); ?>" alt="" width="50" height="50" style="border-radius: 30px;"></span>
                  <div class="user-name">
                    <p><?php echo $name; ?></p>
                    <span><?php echo $role; ?></span>
                  </div>
                  <i class="fa fa-angle-down lnr"></i>
                  <i class="fa fa-angle-up lnr"></i>
                  <div class="clearfix"></div>  
                </div>  
              </a>
              <ul class="dropdown-menu drp-mnu">
                <li> <a href="change-password.php"><i class="fa fa-cog"></i> Settings</a> </li> 
                <li> <a href="admin-profile.php"><i class="fa fa-user"></i> Profile</a> </li> 
                <li> <a href="../login.php"><i class="fa fa-sign-out"></i> Logout</a> </li>
              </ul>
            </li>
          </ul>
        </div>  
        <div class="clearfix"> </div> 
      </div>
      <div class="clearfix"> </div> 
    </div>