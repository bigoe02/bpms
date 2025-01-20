<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['bpmsaid']==0)) {
  header('location:logout.php');
  } else{

	if (isset($_POST['submit'])) {
        
		// Get the new data from the input fields
		$newINSERTEmployeeID = $_POST['INSERTEmployeeID'];
		$newINSERTEmployeename = $_POST['INSERTEmployeename'];
		$newINSERTRole = $_POST['INSERTRole'];
		$newINSERTPhone = $_POST['INSERTPhone'];
		$newINSERTEmail = $_POST['INSERTEmail'];
		$newINSERTUsername = $_POST['INSERTUsername'];
		$newINSERTPassword = $_POST['INSERTPassword'];
		// Hash the password
		$hashedPassword = md5($newINSERTPassword); // Use password_hash() for better security
        //IMAGE INSERT
        $image=$_POST['image'];
        $image=$_FILES["image"]["name"];
            // get the image extension
            $extension = substr($image,strlen($image)-4,strlen($image));
            // allowed extensions
            $allowed_extensions = array(".jpg","jpeg",".png");
                    // Validation for allowed extensions .in_array() function searches an array for a specific value.
                    if(!in_array($extension,$allowed_extensions))
                    {
                    echo "<script>alert('Invalid format. Only jpg / jpeg/ png format allowed');</script>";
                    }
                    else
                    {
                                //rename the image file
                            $newimage=md5($image).time().$extension;
                            // Code for move image into directory
                            move_uploaded_file($_FILES["image"]["tmp_name"],"images/imageEmployee/".$newimage);
                    // Specify the column(s) to insert into
                    $insertnewemployee= "INSERT INTO tbladmin (employeeID, AdminName, role, UserName, MobileNumber, Email, Password, imageEmployee, AdminRegdate) 
                    VALUES (' $newINSERTEmployeeID ',' $newINSERTEmployeename  ',' $newINSERTRole ','$newINSERTUsername','$newINSERTPhone ' ,'$newINSERTEmail  ','$hashedPassword','$newimage',  NOW())";
            
                    // Check if the query was executed successfully
                    if (!mysqli_query($con, $insertnewemployee)) {
                        echo "Error: " . mysqli_error($con);
                    } else {
                        // Display a success message
                        echo "<script>alert('EMPLOYEE has been added in the table.');</script>"; 
                        echo "<script>window.location.href='employee-list.php'</script>";
                        
                    }  
                }
    }
  ?>
<!DOCTYPE HTML>
<html>
<head>
<title>BPMS || Customer List</title>

<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
<!-- Bootstrap Core CSS -->
<link href="css/bootstrap.css" rel='stylesheet' type='text/css' />
<!-- Custom CSS -->
<link href="css/style.css" rel='stylesheet' type='text/css' />
<link href="css/newcustom.css" rel="stylesheet" type='text/css' >
<link href="css/newstyle.css" rel="stylesheet"type='text/css'>
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
<link href="css/custom.css" rel="stylesheet" type='text/css' >
<!-- DATATABLES -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<!-- APEX CHARTS -->
	<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/apexcharts/3.35.3/apexcharts.min.js"></script>-->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
				<div class="tables">
				<h3 class="title1">Employee List</h3>
					<div class="table-responsive bs-example widget-shadow">
						
							<!-- GREEN BUTTON -->
							<button id="openModalBtn" class="green-button">
								<i class="fas fa-user"></i> Add Employee
							</button>
							<br>				
						<table class="table table-bordered" id="leaveTable"  style="width:100%;"> 
						<thead> 
							<tr> 
								<th>#</th> 
								<th>Name</th> 
								<th>Mobile Number</th>
								<th>Email</th>
								<th>RegistrationDate</th>
								<th>Action</th> 
							</tr> 
						</thead> 
						
							<tbody>
								<?php
								$ret=mysqli_query($con,"select * from  tbladmin");
								$cnt=1;
								while ($row=mysqli_fetch_array($ret)) {
								?>

									<tr> 
										<th scope="row"><?php echo $cnt;?></th> 
										<td><?php  echo $row['AdminName'];?></td> 
										<td><?php  echo $row['MobileNumber'];?></td>
										<td><?php  echo $row['Email'];?></td>
										<td><span class="badge badge-primary"><?php  echo $row['AdminRegdate'];?></span></td> 
									
										<td><a href="employee-performance.php?viewid=<?php echo $row['employeeID'];?>" class="btn btn-primary">View Performance</a></td> 
									</tr>   
							<?php $cnt=$cnt+1; }?>
							</tbody> 
						</table> 
					</div>
				</div>
			
				<div class="row" style="display: flex; margin-top:0px;">
				<div class="col-left" style="flex: 1 0 50%; max-width: 100%; margin-right: 15px;">
				<div class="tables" style="width:100%;">
					
			
    <button type="button" class="btns btns--green" id="openSalariesModalBtn">
        <span class="btns__txt">Employees Salaries</span>
        <i class="btns__bg" aria-hidden="true"></i>
        <i class="btns__bg" aria-hidden="true"></i>
        <i class="btns__bg" aria-hidden="true"></i>
        <i class="btns__bg" aria-hidden="true"></i>
    </button>
    <button type="button" class="btns btns--red" id="openDeductionModalBtn">
        <span class="btns__txt">Employees Deduction</span>
        <i class="btns__bg" aria-hidden="true"></i>
        <i class="btns__bg" aria-hidden="true"></i>
        <i class="btns__bg" aria-hidden="true"></i>
        <i class="btns__bg" aria-hidden="true"></i>
    </button>
    <button type="button" class="btns btns--yellow" id="openCommissionModalBtn">
        <span class="btns__txt">Employees Commission</span>
        <i class="btns__bg" aria-hidden="true"></i>
        <i class="btns__bg" aria-hidden="true"></i>
        <i class="btns__bg" aria-hidden="true"></i>
        <i class="btns__bg" aria-hidden="true"></i>
    </button>


					<div class="table-responsive widget-shadow" style="height: 100vh; margin-top: 10px;">
					<div class="attendance-section">
        <h2>Employee Attendance History</h2>
        
		<form method="POST" action="">
			<div class="search-bar">
			<label >Start Date</label>
			<input type="text" placeholder="dd/mm/yyyy" name="startDate" class="date-input" id="startDate">
			<label >End Date</label>
			<input type="text" placeholder="dd/mm/yyyy" name="endDate" class="date-input" id="endDate">
			</div>
			<button class="search-button" type="submit" name="search">Search</button>
		</form>

		<?php
    // Check if the search form has been submitted
    if (isset($_POST['search']) && !empty($_POST['startDate']) && !empty($_POST['endDate'])) {
        // Format dates for SQL query
        $startDateFormatted = date("Y-m-d", strtotime(str_replace('-', '-', $_POST['startDate'])));
        $endDateFormatted = date("Y-m-d", strtotime(str_replace('-', '-', $_POST['endDate'])));

        // Update query to filter by date range
        $attendanceQuery = mysqli_query($con, "
            SELECT 
                tblattendance.employeeID,
                tblattendance.signIn,
                tblattendance.punchIN,
                tblattendance.punchOUT,
                tbladmin.AdminName 
            FROM 
                tblattendance 
            JOIN 
                tbladmin 
            ON 
                tblattendance.employeeID = tbladmin.employeeID 
            WHERE 
                tblattendance.signIn BETWEEN '$startDateFormatted' AND '$endDateFormatted'
            ORDER BY 
                tblattendance.signIn DESC;
        ");
        // Displaying the selected date range
        echo "<p>Showing Employee Attendance History from <strong>{$_POST['startDate']}</strong> to <strong>{$_POST['endDate']}</strong></p>";
    } else {
        // Default query to show all records if no date range is provided
        $attendanceQuery = mysqli_query($con, "
            SELECT 
                tblattendance.employeeID,
                tblattendance.signIn,
                tblattendance.punchIN,
                tblattendance.punchOUT,
                tbladmin.AdminName 
            FROM 
                tblattendance 
            JOIN 
                tbladmin 
            ON 
                tblattendance.employeeID = tbladmin.employeeID 
            ORDER BY 
                tblattendance.signIn DESC;
        ");
        echo "<p>Showing All Employee Attendance History</p>";
    }
    ?>

    <table class="table table-bordered" id="leaveTables"  style="width:100%;">
        <thead>
            <tr>
                <th>#</th>
                <th>Employee ID</th>
                <th>FullName</th>
                <th>Date</th>
                <th>Punch In</th>
                <th>Punch Out</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $sn = 1; // Serial number
        while ($attendanceRow = mysqli_fetch_array($attendanceQuery)) {
            ?>
            <tr>
                <td><?php echo $sn; ?></td>
                <td><?php echo $attendanceRow['employeeID']; ?></td>
                <td><?php echo $attendanceRow['AdminName']; ?></td>
                <td><?php echo $attendanceRow['signIn']; ?></td>
                <td><?php echo $attendanceRow['punchIN']; ?></td>
                <td><?php echo $attendanceRow['punchOUT']; ?></td>
            </tr>
            <?php
            $sn++;
        }
        ?>
                </tbody>
            </table>
       
    </div>
					</div>
				</div>
				</div>
				
			
				<div class="col-right" style="flex: 2; ">
    <div class="tables" style="width: 100%;">
        <div class="table-responsive bs-example widget-shadow">

		<?php
// Assuming you have a connection to the database in $con

// Step 1: Get the latest payroll month for each employee
$latestPayrollQuery = "
    SELECT 
        tbladmin.AdminName,
        MAX(tblpayroll.payroll_month) AS latest_month
    FROM 
        tbladmin
    LEFT JOIN 
        tblpayroll ON tbladmin.AdminName = tblpayroll.employeeName
    GROUP BY 
        tbladmin.AdminName
";
$latestPayrollResult = mysqli_query($con, $latestPayrollQuery);

// Prepare an array to hold the latest payroll months
$latestPayrollMonths = [];
while ($row = mysqli_fetch_assoc($latestPayrollResult)) {
    $latestPayrollMonths[$row['AdminName']] = $row['latest_month'];
}

// Step 2: Calculate the total basic salary for the latest payroll month for each employee
$totalBasicSalary = 0;
$employeeSalaries = [];

$employeeTaxes = []; 
$totalTaxes = 0;

$employeeCommission = []; 
$totalcommission = 0;


// Loop through each employee and get their salary for the latest payroll month
foreach ($latestPayrollMonths as $employeeName => $payrollMonth) {
    $salaryQuery = "
        SELECT 
            SUM(basic_salary) AS total_basic_salary 
        FROM 
            tblpayroll 
        WHERE 
            employeeName = '$employeeName'
            AND payroll_month = '$payrollMonth'
    ";
    $salaryResult = mysqli_query($con, $salaryQuery);
    $salaryRow = mysqli_fetch_assoc($salaryResult);
    
    $basicSalary = $salaryRow['total_basic_salary'] ?? 0; // Use 0 if no salary found
    $employeeSalaries[$employeeName] = $basicSalary;
    $totalBasicSalary += $basicSalary; // Sum up the total salary

	///////////////////////////////////////////////////
// Query for total taxes
$taxQuery = "
SELECT 
	SUM(tax) AS total_tax 
FROM 
	tblpayroll 
WHERE 
	employeeName = '$employeeName'
	AND payroll_month = '$payrollMonth'
";
$taxResult = mysqli_query($con, $taxQuery);
$taxRow = mysqli_fetch_assoc($taxResult);

$taxAmount = $taxRow['total_tax'] ?? 0; // Use 0 if no tax found
$employeeTaxes[$employeeName] = $taxAmount;
$totalTaxes += $taxAmount; // Sum up the total taxes


	///////////////////////////////////////////////////
// Query for total commission
$commissionQuery = "
SELECT 
	SUM(commission) AS total_commission 
FROM 
	tblpayroll 
WHERE 
	employeeName = '$employeeName'
	AND payroll_month = '$payrollMonth'
";
$commissionResult = mysqli_query($con, $commissionQuery);
$commissionRow = mysqli_fetch_assoc($commissionResult);

$commissionAmount = $commissionRow['total_commission'] ?? 0; // Use 0 if no tax found
$employeeCommission[$employeeName] = $commissionAmount;
$totalcommission += $commissionAmount; // Sum up the total taxes

	
}
?>

<div class="dashboard">

<div class="card salary">
        <div class="progress-circle" id="progressCircle" data-progress="0"></div>
        <div class="amount" id="totalBasicSalaryAmount"><?php echo $totalBasicSalary; ?></div>
        <div class="label">Total Basic Salary</div>
        <div class="subtext">Latest Payroll Month: <?php echo date('Y-m-d', strtotime(max($latestPayrollMonths))); ?></div>
    </div>

<!-- Modal Structure -->
<div id="salariesModal" class="modal">
    <div class="modal-content">
        <span class="close-btn">&times;</span>
        <div class="employee-salaries">
            <h3>Employee Salaries for This Month</h3>
            <ul>
                <?php foreach ($employeeSalaries as $employee => $salary) { ?>
                    <li><?php echo $employee . ': ' . $salary; ?></li>
                <?php } ?>
            </ul>
        </div>
    </div>
</div>



				<div class="card deduction" id="deductionCard">
				<div class="progress-circle" id="progressCircleDeduction" data-progress="0"></div>
					<div class="amount" id="totalDeductionAmount"><?php echo $totalTaxes; ?></div>
					<div class="label">Employee Deduction</div>
					<div class="subtext">Latest Payroll Month: <?php echo date('Y-m-d', strtotime(max($latestPayrollMonths))); ?></div>
				</div>

				<div id="deductionModal" class="modal">
					<div class="modal-content">
						<span class="close-btn">&times;</span>
							<div class="employee-salaries">
								<h3>Employee Taxes This Month</h3>
							<ul>
								<?php foreach ($employeeTaxes as $employee => $taxes) { ?>
									<li><?php echo $employee . ': ' . $taxes; ?></li>
								<?php } ?>
							</ul>
						</div>
					</div>
				</div>

				<div class="card commission"  id="commissionCard">
				<div class="progress-circle" id="progressCircleCommission" data-progress="0"></div>
				<div class="amount" id="totalCommissionAmount"><?php echo $totalcommission; ?></div>
					<div class="label">Commission</div>
					<div class="subtext">Latest Payroll Month: <?php echo date('Y-m-d', strtotime(max($latestPayrollMonths))); ?></div>
				</div>

						<div id="commissionModal" class="modal">
							<div class="modal-content">
										<span class="close-btn">&times;</span>
								<div class="employee-salaries">
									<h3>Employee Commission This Month</h3>
									<ul>
										<?php foreach ($employeeCommission as $employee => $commission) { ?>
											<li><?php echo $employee . ': ' . $commission; ?></li>
										<?php } ?>
									</ul>
								</div>
							</div>
						</div>
                        
				</div>
			</div>
		</div>
	</div>
</div>
				
	<!--ADD EMPLOYEE MODAL-->
	<div id="AddEmployeeModal" class="modal">
    <div class="modal-content">
        <span class="close-btn">&times;</span>
        <h2>Add New Employee</h2>
        <div class="modal-body">
            <form method="POST" action="employee-list.php" enctype="multipart/form-data"> <!-- Ensure the method is POST -->
               
				<div class="form-group">
                    <label for="Employee_ID">Employee ID</label>
                    <input type="text" class="form-control" id="INSERTEmployeeID" name="INSERTEmployeeID"  value="202400" required="true"> 
                </div>
                <div class="form-group">
                    <label for="Employee_Name">Employee_Name</label>
                    <input type="text" class="form-control" id="INSERTEmployeename" name="INSERTEmployeename"  value="" required="true"> 
                </div>
				<div class="form-group">
                    <label for="Role">Role</label>
                    <input type="text" class="form-control" id="INSERTRole" name="INSERTRole"  value="" required="true"> 
                </div>
                <div class="form-group"> 
					<label for="INSERTPhone">Mobile Number</label> 
					<input type="text" class="form-control" id="INSERTPhone" name="INSERTPhone"  value="" required="true"></input> 
				</div>
				<div class="form-group"> 
					<label for="INSERTEmail">Email</label> 
					<input type="text" class="form-control" id="INSERTEmail" name="INSERTEmail"  value="" required="true"></input> 
				</div>
				<div class="form-group"> 
					<label for="INSERTUsername">Username</label> 
					<input type="text" class="form-control" id="INSERTUsername" name="INSERTUsername"  value="" required="true"></input> 
				</div>
				<div class="form-group"> 
					<label for="INSERTPassword">Password</label> 
					<input type="password" class="form-control" id="INSERTPassword" name="INSERTPassword"  placeholder="password" required="true"></input> 
				</div>
                <div class="form-group"> 
                <label for="INSERTImageEmployee">Images</label>
                     <input type="file" class="form-control" id="image" name="image" value="" required="true">
				</div>

                <button type="submit" name="submit" class="submit-buttonEmployee">Submit</button> <!-- Name the button -->
            </form>
        </div>
    </div>
</div>

			 
		</div>
	</div>
		<!--footer-->
		 <?php include_once('includes/footer.php');?>
        <!--//footer-->
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


			 //// MODAL
// Function to handle modal open and close
function setupModal(buttonId, modalId) {
    // Open Modal
    document.getElementById(buttonId).addEventListener('click', function() {
        document.getElementById(modalId).style.display = 'block';
    });

    // Close Modal
    document.querySelector(`#${modalId} .close-btn`).addEventListener('click', function() {
        document.getElementById(modalId).style.display = 'none';
    });

    // Close modal when clicking outside of it
    window.onclick = function(event) {
        const modal = document.getElementById(modalId);
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    };
}

// Setup modals
setupModal('openModalBtn', 'AddEmployeeModal');
setupModal('openSalariesModalBtn', 'salariesModal');
setupModal('openDeductionModalBtn', 'deductionModal');
setupModal('openCommissionModalBtn', 'commissionModal');


		/////////////FOR DATATABLE SETTINGS
		$(document).ready(function() {
    $('#leaveTable').DataTable({
        "paging": true,
        "searching": true,
        "lengthChange": true,
        "info": true,
        "autoWidth": false,
        "pageLength": 6 , // Set the number of entries to show to 3
		"lengthChange": false
		
    });
});

$(document).ready(function() {
    $('#leaveTables').DataTable({
        "paging": true,
        "searching": true,
        "lengthChange": true,
        "info": true,
        "autoWidth": false,
        "pageLength": 5 , // Set the number of entries to show to 3
		"lengthChange": false
		
    });
});

document.addEventListener("DOMContentLoaded", function() {
	
	////////////////FOR SALARY
    const totalBasicSalary = <?php echo $totalBasicSalary; ?>; // Get the PHP variable
    const targetValue = 100000; // Target value for 100%
    const progressCircle = document.getElementById('progressCircle');
    const amountDisplay = document.getElementById('totalBasicSalaryAmount');

    // Calculate the percentage
    const percentage = Math.min(Math.round((totalBasicSalary / targetValue) * 100), 100);

    // Set the data-progress attribute for the progress circle
    progressCircle.setAttribute('data-progress', percentage);

	 // Update the CSS rotation based on the percentage
	 progressCircle.style.setProperty('--rotate', `${(percentage / 100) * 360}deg`);
	 
    // Display the total basic salary amount
    amountDisplay.innerText = `₱${totalBasicSalary.toLocaleString()}`;


	 /////////////////FOR DEDUCTION
	const totalTaxes = <?php echo $totalTaxes; ?>; // Get the PHP variable for total taxes
    const targetValueDeduction = 5000; // Target value for 100% in deductions (you can adjust this value)
    const progressCircleDeduction = document.getElementById('progressCircleDeduction'); // New ID for deduction progress circle
    const amountDisplayDeduction = document.getElementById('totalDeductionAmount'); // New ID for deduction amount display

    // Calculate the percentage for deduction
    const percentageDeduction = Math.min(Math.round((totalTaxes / targetValueDeduction) * 100, 100)); // Cap at 100%

    // Set the data-progress attribute for the deduction progress circle
    progressCircleDeduction.setAttribute('data-progress', percentageDeduction);

    // Update the CSS rotation based on the percentage for deduction
    progressCircleDeduction.style.setProperty('--rotate', `${(percentageDeduction / 100) * 360}deg`);

    // Display the total deduction amount
    amountDisplayDeduction.innerText = `₱${totalTaxes.toLocaleString()}`;


	/////////////////FOR COMMISSION
	const totalcommission = <?php echo $totalcommission; ?>; // Get the PHP variable for total taxes
    const targetValueCommission = 10000; // Target value for 100% in deductions (you can adjust this value)
    const progressCircleCommission = document.getElementById('progressCircleCommission'); // New ID for deduction progress circle
    const totalDisplayCommission= document.getElementById('totalCommissionAmount'); // New ID for deduction amount display

    // Calculate the percentage for deduction
    const percentageCommission = Math.min(Math.round((totalcommission  / targetValueCommission) * 100, 100)); // Cap at 100%

    // Set the data-progress attribute for the deduction progress circle
    progressCircleCommission.setAttribute('data-progress', percentageCommission);

    // Update the CSS rotation based on the percentage for deduction
    progressCircleCommission.style.setProperty('--rotate', `${(percentageCommission / 100) * 360}deg`);

    // Display the total deduction amount
    totalDisplayCommission.innerText = `₱${totalcommission.toLocaleString()}`;
});
		</script>
	<!--scrolling js-->
	<script src="js/jquery.nicescroll.js"></script>
	<script src="js/scripts.js"></script>
	<!--//scrolling js-->
	<!-- Bootstrap Core JavaScript -->
	<script src="js/bootstrap.js"> </script>
</body>
</html>
<?php }  ?>