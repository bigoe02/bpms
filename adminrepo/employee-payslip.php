<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['bpmsaid']==0)) {
  header('location:logout.php');
  } else{    
  ?>
<!DOCTYPE HTML>
<html>
<head>
<title>BPMS | Add Services</title>

<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
<!-- Bootstrap Core CSS -->
<link href="css/bootstrap.css" rel='stylesheet' type='text/css' />
<!-- Custom CSS -->
<link href="css/style.css" rel='stylesheet' type='text/css' />
<!-- font CSS -->
<!-- font-awesome icons -->
<link href="css/font-awesome.css" rel="stylesheet"> 
<!-- //font-awesome icons -->
 <!-- js-->
<script src="js/jquery-1.11.1.min.js"></script>
<script src="js/modernizr.custom.js"></script>
<!--webfonts-->
<link href='//fonts.googleapis.com/css?family=Roboto+Condensed:400,300,300italic,400italic,700,700italic' rel='stylesheet' type='text/css'>
<!--//webfonts--> 
<!--animate-->
<link href="css/animate.css" rel="stylesheet" type="text/css" media="all">
<script src="js/wow.min.js"></script>
	<script>
		 new WOW().init();
	</script>
<!--//end-animate-->
<!-- Metis Menu -->
<script src="js/metisMenu.min.js"></script>
<script src="js/custom.js"></script>
<link href="css/custom.css" rel="stylesheet">
<link href="css/newcustom.css" rel="stylesheet">
<!--//Metis Menu -->
</head> 
<body class="cbp-spmenu-push">
	<div class="main-content">
		<!--left-fixed -navigation-->
		 <?php include_once('includes/sidebar.php');?>
		<!--left-fixed -navigation-->
		<!-- header-starts -->
	 <?php include_once('includes/header.php');?>
		<!-- //header-ends -->
		<!-- main content start-->
		<div id="page-wrapper">
			<div class="main-page">
            <div class="payslip-container">
            <h2>Salary for <?php echo htmlspecialchars($_GET['month']); ?>, <?php echo htmlspecialchars($_GET['year']); ?></h2>
        <div class="payslip-details">
            <div class="details-row">      

                    <?php
                        // Assuming you pass the employee ID in the URL
                        $employeeName = mysqli_real_escape_string($con, $_GET['employeeName']);
                        $selectedMonthName = mysqli_real_escape_string($con, $_GET['month']);
                        $selectedYear = mysqli_real_escape_string($con, $_GET['year']);
                        $selectedDay = mysqli_real_escape_string($con, $_GET['day']);
                        // Convert month name to numeric format
                        $dateTime = DateTime::createFromFormat('F', $selectedMonthName);
                        $selectedMonth = $dateTime ? $dateTime->format('m') : null; // Get numeric month (01 to 12)

                        // Check if the payroll month exists for the selected employee
                        $checkPayrollMonth = mysqli_query($con, "SELECT * FROM tblpayroll WHERE employeeName = '$employeeName' AND payroll_month = '$selectedYear-$selectedMonth-$selectedDay'");
                        
                        // If the payroll month exists, proceed to fetch the payroll details
                        if (mysqli_num_rows($checkPayrollMonth) > 0) {
                            // Update the SQL query to filter by the selected employee ID
                            $payrollList = mysqli_query($con, "SELECT 
                                tbladmin.employeeID,
                                tbladmin.AdminName,
                                tbladmin.role,
                                tbladmin.MobileNumber,
                                tbladmin.Email,
                                tbladmin.AdminRegdate,
                                tblpayroll.basic_salary, 
                                tblpayroll.commission, 
                                tblpayroll.allowances, 
                                tblpayroll.tax, 
                                tblpayroll.net_salary                                     
                                FROM tblpayroll 
                                JOIN tbladmin ON tblpayroll.employeeName = tbladmin.AdminName 
                                WHERE tbladmin.AdminName= '$employeeName' AND tblpayroll.payroll_month = '$selectedYear-$selectedMonth-$selectedDay'"); // Add the WHERE clause

                            
                        // Check if any results were returned
                        if (mysqli_num_rows($payrollList) > 0) {
                            while ($row = mysqli_fetch_array($payrollList)) {
                    ?>
                <div class="details-column">
                    <p><strong>Employee ID:</strong> <?php  echo $row['employeeID'];?></p>
                    <p><strong>Employee Name:</strong>  <?php  echo $row['AdminName'];?></p>
                    <p><strong>Designation:</strong>  <?php  echo $row['role'];?></p>
                    <p><strong>Mobile Number:</strong> <?php  echo $row['MobileNumber'];?></p>
                    <p><strong>Email:</strong>  <?php  echo $row['Email'];?></p>
                    <p><strong>Date of Joining:</strong>  <?php  echo $row['AdminRegdate'];?></p>
                </div>
    
            </div>
            <div class="salary-section">
                <h3>Earnings</h3>
                <table>
                    <tr>
                        <td>Basic Salary</td>
                        <td><?php  echo number_format($row['basic_salary'],2);?></td>
                    </tr>
                    <tr>
                        <td>Commission</td>
                        <td>₱<?php  echo number_format($row['commission'], 2);?></td>
                    </tr>
                    <tr>
                        <th>Total Earnings</th>
                        <th>₱<?php echo number_format($row['basic_salary'] + $row['commission'], 2);?></th>
                    </tr>
                </table>
                <h3>Benefits</h3>
                <table>
                    <tr>
                        <td>Allowances</td>
                        <td>₱<?php  echo number_format($row['allowances'], 2);?></td>
                    </tr>
                    <tr>
                        <td>Income Tax</td>
                        <td>₱<?php  echo number_format($row['tax'], 2);?></td>
                    </tr>
                    <tr>
                        <th>Total Benefits</th>
                        <th>₱<?php echo number_format($row['allowances'] + $row['tax'], 2);?></th>
                    </tr>
                </table>
                <div class="net-salary">
                    <p><strong>Net Salary Payable: ₱<?php  echo number_format($row['net_salary'], 2);?></strong></p>
                </div>
            </div>
            <?php
        }
    } else {
        echo "<p>No payroll details found for the selected employee.</p>";
    }
} else {
    echo "<p>No payroll month found for the selected employee.</p>";
}
?>
            <button>Generate PaySlip</button>
        </div>
    </div>
    
            </div>
			</div>
		</div>
		 <?php include_once('includes/footer.php');?>
	</div>
	<!-- Classie -->
		<script src="js/classie.js"></script>
		<script>
			var menuLeft = document.getElementById( 'cbp-spmenu-s1' ),
				showLeftPush = document.getElementById( 'showLeftPush' ),
				body = document.body;
				
			showLeftPush.onclick = function() {
				classie.toggle( this, 'active' );
				classie.toggle( body, 'cbp-spmenu-push-toright' );
				classie.toggle( menuLeft, 'cbp-spmenu-open' );
				disableOther( 'showLeftPush' );
			};
			
			function disableOther( button ) {
				if( button !== 'showLeftPush' ) {
					classie.toggle( showLeftPush, 'disabled' );
				}
			}


		</script>
	<!--scrolling js-->
	<script src="js/jquery.nicescroll.js"></script>
	<script src="js/scripts.js"></script>
	<!--//scrolling js-->
	<!-- Bootstrap Core JavaScript -->
   <script src="js/bootstrap.js"> </script>
</body>
</html>
<?php } ?>