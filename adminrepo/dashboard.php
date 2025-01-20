<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['bpmsaid'] == 0)) {
	header('location:logout.php');
}else {
					// Get employee name from tbladmin
					$employeeNameQuery = "SELECT AdminName FROM tbladmin WHERE employeeID = '$employeeIdOut'";
					$employeeNameResult = mysqli_query($con, $employeeNameQuery);
					$employeeNameRow = mysqli_fetch_assoc($employeeNameResult);
					$employeeName = $employeeNameRow['AdminName'];

// Handle Sign In
if (isset($_POST['signIn'])) {
	$employeeId = $_POST['employeeId'];
	// Set timezone DATE to Philippines
	date_default_timezone_set('Asia/Manila');
	$currentDate = date('Y-m-d'); // Get today's date
	// Set timezone TIME to Philippines
	date_default_timezone_set('Asia/Manila');
	$currentTime = date('H:i:s'); // Get current time


	//   // Check if current time is after 10:00:00
	//   if ($currentTime >= '10:00:00') {
	//     echo "<script>alert('You cannot sign in after 10:00:00.');</script>";
	//     echo "<script>window.location.href = 'dashboard.php'</script>";
	//     exit; // Stop further execution
	// }

	// Check if EmployeeID exists in tbladmin
	$checkEmployeeQuery = "SELECT * FROM tbladmin WHERE employeeID = '$employeeId'";
	$checkEmployeeResult = mysqli_query($con, $checkEmployeeQuery);

	if (mysqli_num_rows($checkEmployeeResult) > 0) {

		// Check if the employee has already signed in today
		$checkSignInQuery = "SELECT * FROM tblattendance WHERE employeeID = '$employeeId' AND SignIn = '$currentDate'";
		$checkSignInResult = mysqli_query($con, $checkSignInQuery);

		if (mysqli_num_rows($checkSignInResult) > 0) {
			$alertErrorMessage = "<script>showAlert('Error', 'The employee is already signed in today. $employeeId or $currentDate', 'error');</script>";
			// Employee has already signed in today
			// echo "<script>alert('The employee is already signed in today. $employeeId or $currentDate');</script>";
			// echo "<script>window.location.href = 'dashboard.php'</script>"; 

		} else {
			// Insert into tblattendance with Active status
			$query = "INSERT INTO tblattendance (EmployeeID, SignIn, PunchIn, Status) VALUES ('$employeeId', '$currentDate', '$currentTime', 'Active')";
			mysqli_query($con, $query);

			$insertcomdeducsQuery = mysqli_query($con, "INSERT INTO tblcomdeducs (employeeID, employeeName, comdeducsDate) VALUES ('$employeeId','$employeeName',  '$currentDate')");
			$alertSuccessMessage .= "<script>showAlert('Success', 'New Attendance has been added to the attendance history', 'success');</script>";
			// echo "<script>alert('new Attendance has been added to the attendance history.');</script>"; 
			// echo "<script>window.location.href = 'dashboard.php'</script>";  
		}
	} else {
		$alertErrorMessage = "<script>showAlert('Error', 'Employee ID does not exist. Please check and try again.', 'error');</script>";
		// Employee ID does not exist, handle the error (e.g., show a message)
		// echo "<script>alert('Employee ID does not exist. Please check and try again.');</script>";
		// echo "<script>window.location.href = 'dashboard.php'</script>"; 
	}
}


// Handle Sign Out
if (isset($_POST['signOut'])) {
	$employeeIdOut = $_POST['employeeIdOut'];
	date_default_timezone_set('Asia/Manila');
	$currentDates = date('Y-m-d'); // Get today's date
	// Set timezone TIME to Philippines
	date_default_timezone_set('Asia/Manila');
	$currentTimes = date('H:i:s'); // Get current time

	// Check if the employee has signed in today
	$checkSignInQuery = "SELECT punchIN FROM tblattendance WHERE employeeID = '$employeeIdOut' AND signIn = '$currentDates' AND Status = 'Active'";
	$checkSignInResult = mysqli_query($con, $checkSignInQuery);

	if (mysqli_num_rows($checkSignInResult) > 0) {
		// Fetch the punch-in time
		$row = mysqli_fetch_assoc($checkSignInResult);
		$punchInTime = $row['punchIN'];

		// Calculate the time difference in seconds between punch-in time and current time
		$timeDifferenceInSeconds = strtotime($currentTimes) - strtotime($punchInTime);

		// Check if the time difference is greater than or equal to 8 hours (28800 seconds)
		if ($timeDifferenceInSeconds >= (8 * 60 * 60)) {
			// Update the tblattendance with sign-out information
			$updateQuery = "UPDATE tblattendance SET punchOUT = '$currentTimes', 
				signout = '$currentDates',
				 Status = 'Inactive' 
				 WHERE employeeID = '$employeeIdOut'
				AND signIn = '$currentDates' 
				AND Status = 'Active'";

			mysqli_query($con, $updateQuery);


			// Check if the employee signed in before 08:00:00
			if ($punchInTime < '07:00:00') {
				// Calculate overtime hours
				$overtimeHours = round(($timeDifferenceInSeconds - (8 * 60 * 60)) / 3600);// Convert seconds to hours
				$overtimePay = ceil($overtimeHours) * 90; // Round up to the nearest hour and calculate pay


				// Update overtime details in tblcomdeducs
				$insertOvertimeQuery = "UPDATE tblcomdeducs SET overtime = overtime + '$overtimePay' WHERE employeeName = '$employeeName' AND comdeducsDate = '$currentDates'";
				mysqli_query($con, $insertOvertimeQuery);
			}
			$alertSuccessMessage .= "<script>showAlert('Success', 'Sign out successful. Your Time out is $punchInTime plus your OverTime/Hr is $overtimeHours and Your TotalOverTimePay is $overtimePay', 'success');</script>";
			// echo "<script>alert('Sign out successful. Your Time out is $punchInTime plus your OverTime/Hr is $overtimeHours and Your TotalOverTimePay is $overtimePay');</script>";
			// echo "<script>window.location.href = 'dashboard.php'</script>";

		} else {
			// Calculate rendered hours
			$renderedHours = round($timeDifferenceInSeconds / 3600, 2); // Convert seconds to hours
			$totalAmount = $renderedHours * 81; // Calculate total amount
			$standardRate = 648; // Total rate for 8 hours

			// Calculate the deduction
			$deductionAmount = $standardRate - $totalAmount; // Deduction is the difference from the standard rate

			  // If the employee is late, update the minusdeduction in tblcomdeducs
			  if ($renderedHours < 8) {

					// Update tblattendance with sign-out information
			$updateQuery = "UPDATE tblattendance SET punchOUT = '$currentTimes', 
            signout = '$currentDates', 
            Status = 'Inactive' 
            WHERE employeeID = '$employeeIdOut'
            AND signIn = '$currentDates' 
            AND Status = 'Active'";
			mysqli_query($con, $updateQuery);

				// Update minusdeduction in tblcomdeducs
				$updateDeductionQuery = "UPDATE tblcomdeducs SET minusdeduction = minusdeduction + '$deductionAmount' WHERE employeeName = '$employeeName' AND comdeducsDate = '$currentDates'";
				mysqli_query($con, $updateDeductionQuery);
			}
			$alertSuccessMessage .= "<script>showAlert('Success', 'Sign out successful. YOUR ARE LATE. You worked for $renderedHours hours. Total amount for rendered hours is $totalAmount pesos. Total deduction is $deductionAmount.', 'success');</script>";
			// echo "<script>alert('Sign out successful. YOUR FUCKING LATE!! You worked for $renderedHours hours. Total amount for rendered hours is $totalAmount pesos.');</script>";
			// echo "<script>window.location.href = 'dashboard.php'</script>";
		}
	} else {
		$alertErrorMessage = "<script>showAlert('Error', 'The employee input is not existed or already sign out.', 'error');</script>";

		// Employee has not signed in today
		// echo "<script>alert('The employee input is not existed or already sign out.');</script>";
		// echo "<script>window.location.href = 'dashboard.php'</script>"; 
	}
}

// Fetch attendance history
$attendanceQuery = "SELECT * FROM tblattendance WHERE signIn = CURDATE()";
$attendanceResult = mysqli_query($con, $attendanceQuery);
?>
<!DOCTYPE HTML>
<html>

<head>
	<title>LJBC | Admin Dashboard</title>

	<script
		type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
	<!-- Bootstrap Core CSS -->
	<link href="css/bootstrap.css" rel='stylesheet' type='text/css' />
	<!-- Custom CSS -->
	<link href="css/style.css" rel='stylesheet' type='text/css' />
	<link href="css/newcustom.css" rel='stylesheet' type='text/css' />
	<!-- font CSS -->
	<!-- font-awesome icons -->
	<link href="css/font-awesome.css" rel="stylesheet">
	<!-- //font-awesome icons -->
	<!-- js-->
	<script src="js/jquery-1.11.1.min.js"></script>
	<script src="js/modernizr.custom.js"></script>
	<!--webfonts-->
	<link href='//fonts.googleapis.com/css?family=Roboto+Condensed:400,300,300italic,400italic,700,700italic'
		rel='stylesheet' type='text/css'>
	<!--//webfonts-->
	<!--animate-->
	<link href="css/animate.css" rel="stylesheet" type="text/css" media="all">
	<script src="js/wow.min.js"></script>
	<script>
		new WOW().init();
	</script>
	<!--//end-animate-->
	<!-- chart -->
	<script src="js/Chart.js"></script>
	<!-- //chart -->
	<!--Calender-->
	<link rel="stylesheet" href="css/clndr.css" type="text/css" />
	<script src="js/underscore-min.js" type="text/javascript"></script>
	<script src="js/moment-2.2.1.js" type="text/javascript"></script>
	<script src="js/clndr.js" type="text/javascript"></script>
	<script src="js/site.js" type="text/javascript"></script>
	<!--End Calender-->

	<!-- SWEETALERT -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<script src="js/alerts.js"></script>
	<!-- APEX CHARTS -->
	<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
	<!-- CUSTOM -->
	<link href="css/newstyle.css" rel="stylesheet">
	<!-- Metis Menu -->
	<script src="js/metisMenu.min.js"></script>
	<script src="js/custom.js"></script>
	<link href="css/custom.css" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
	<!--//Metis Menu -->
</head>
<style>
	.zoomable-icon {
		transition: transform 0.3s ease;
		/* Smooth transition for the transform */
		width: 50px;
		/* Set the initial width (adjust as needed) */
		height: auto;
		/* Maintain aspect ratio */
	}

	.zoomable-icon:hover {
		transform: scale(1.2);
		/* Scale the image to 120% on hover */
	}

	.cardss {
		width: 70%;
		/* Match the width of the #chart div */
		height: 100%;
		/* Match the height of the #chart div */

	}

	/* Styling for #chart div to look like a card */
	#chartst {
		color: black;
		width: 100%;
		height: 100%;
		/* Add styles for padding, border, background color, etc. to match the 'card' style */
		padding: 20px;
		/* border: 1px solid #ddd; */
		background-color: #f5f3f3;
		/* border-radius: 8px;
			box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); */
	}

	.cardss2 {
		/* ...your existing styles... */
		width: 30%;
		/* Match the width of the #chart div */
		height: 100%;
		/* Match the height of the #chart div */


	}

	/* Styling for #chart div to look like a card */
	#customerInsightsChart {
		color: black;
		width: 100%;
		height: 100%;
		/* Add styles for padding, border, background color, etc. to match the 'card' style */
		padding: 20px;
		margin-top: 170px;
		border: 1px solid #ddd;
		background-color: #f5f3f3;
		border-radius: 8px;
		box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);


	}

	.containercard {
		display: flex;
		flex-wrap: wrap;
		justify-content: space-between;
		height: 150px;
		/* Set a specific height for the container */
		margin-bottom: 10px;
	}

	.containercard .card {
		background: white;
		border-radius: 8px;
		box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
		margin-right: 10px;
		margin-top: 10px;
		padding: 20px;
		flex: 1 1 60px;
		text-align: center;
		height: 140px;
		/* Set a specific height for the other cards */
	}

	.containercard .card-header {
		display: flex;
		flex-direction: column;
		align-items: center;
		position: relative;
		/* Positioning context for the image */
		padding: 10px;
		height: 100px;
	}

	.containercard img {
		position: absolute;
		/* Position the image absolutely */
		top: 0;
		/* Align to the top */
		left: 0;
		/* Align to the left */
		width: 30%;
		height: 50%;
	}

	.containercard .card-title {
		font-size: 17px;
		font-weight: bold;
		color: black;
		position: absolute;
		top: 60px;
		/* Align to the top */
		left: 0;
		/* Align to the left */

	}
	.containercard .card-stocks {
		font-size: 17px;
		font-weight: bold;
		color: black;
		position: absolute;
		top: 60px;
		/* Align to the top */
		left: 22px;
		/* Align to the left */

	}
	.containercard .card-value {
		font-size: 25px;
		color: whitesmoke;

		position: absolute;
		top: 0;
		/* Align to the top */
		right: 0;
		/* Align to the left */
	}

	.containercard .card-subtitle {
		font-size: 14px;
		color: black;

		position: absolute;
		top: 90px;
		/* Align to the top */
		left: 0;
	}

	/* START DOUGHNUT */
	.containercard .card-money {
		background: white;
		border-radius: 8px;
		box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
		margin-top: 10px;
		padding: 20px;
		flex: 1 1 110px;
		text-align: center;
		height: 300px;
		/* Set a specific height for the card-money */
		position: relative;
	}

	.containercard #doughnutChart {

		position: absolute;
		top: 0;
		/* Align to the top */
		left: 0;
		width: 100%;
		/* Full width of the card */
		height: 100%;
		/* Full height of the card */
	}

	/* END DOUGHNUT */

	/* START RADIALBAR */
	.containercard .card-radial {
		background: #f5f3f3;
		border-radius: 8px;
		box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
		margin-top: 10px;

		flex: 1 1 110px;
		margin-right: 10px;
		text-align: center;
		height: 310px;
		/* Set a specific height for the card-money */
		position: relative;
	}

	.containercard #RadialBarchart {

		position: absolute;
		width: 100%;
		height: 100%;
		/* Set a specific height for the card-money */
		/* border-radius: 60px; */



	}

	/* END RADIALBAR */

	/* START BARCHART */
	.containercard .card-bar {
		background: #f5f3f3;
		border-radius: 8px;
		box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
		margin-top: 10px;

		flex: 1 1 110px;
		margin-right: 10px;
		text-align: center;
		height: 310px;
		/* Set a specific height for the card-money */
		position: relative;
	}

	.containercard #Barchart {

		position: absolute;
		width: 100%;
		height: 100%;
	}


	/* END BARCHART */
	/* .containercard .card-bottom {
	background: lightblue;
			border-radius: 8px;
			box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
			margin-top: 10px;
			margin-right: 10px;
			padding: 20px;
			flex: 1 1 160px; /* Allow this card to grow and shrink */
	/* text-align: center;
	height: 310px; /* Set a specific height for the card-money */

	/* }  */


	/*MONTH DROPDOWN FOR LINE CHART*/
	#linemonth-filter {
		margin: 0 auto;
		/* This will center the select element horizontally */
		display: block;
		/* This will make the select element take up the full width of its container */
		width: 30%;
		/* You can adjust the width as needed */
		font-size: 1.25em;
		/* This will make the text inside the select element bigger */
		text-align: center;
		border-radius: 20px;
		border-color: #f5f3f3;
	}
</style>

<body class="cbp-spmenu-push">
	<div class="main-content">

		<?php include_once('includes/sidebar.php'); ?>

		<?php include_once('includes/header.php'); ?>
		<!-- main content start-->
		<div id="page-wrapper" class="row calender widget-shadow">
			<div class="main-page">



				<!-- --------------CUSTOMER------------- -->
				<div class="col-md-4 widget">
					<?php $query1 = mysqli_query($con, "Select * from tbluser");
					$totalcust = mysqli_num_rows($query1);
					?>
					<div class="stats-left ">
						<h5>Total</h5>
						<h4>Customer</h4>
					</div>
					<div class="stats-right">
						<label> <?php echo $totalcust; ?></label>
					</div>
					<div class="clearfix"> </div>
				</div>
				<!--  -----------------SERVICES-------------  -->
				<div class="col-md-4 widget states-mdl">

					<?php $servicequery = mysqli_query($con, "Select * from  tblservices");
					$totalservices = mysqli_num_rows($servicequery);
					?>
					<div class="stats-left">
						<h5>Total</h5>
						<h4>Services</h4>
					</div>
					<div class="stats-right">
						<label> <?php echo $totalservices; ?></label>
					</div>
					<div class="clearfix"> </div>
				</div>
				<!-- -----------------PRODUCT------------------- -->
				<div class="col-md-4 widget states-last">
					<?php $productquery = mysqli_query($con, "Select * from  tblinventory");
					$totalproduct = mysqli_num_rows($productquery);
					?>
					<div class="stats-left">
						<h5>Total</h5>
						<h4>Product</h4>
					</div>
					<div class="stats-right">
						<label><?php echo $totalproduct; ?></label>
					</div>
					<div class="clearfix"> </div>
				</div>
				<div class="clearfix"> </div>

				<!-- -----------------------END PHASE 1--------------------------	 -->
				<?php
				/////////////////////////////YESTERDAY QUERY AND COMPUTATION
				$yesterdayquery = mysqli_query($con, "SELECT tblinvoice.ServiceId as ServiceId, 
						tblservices.Cost
						from tblinvoice 
						join tblservices  on tblservices.ID=tblinvoice.ServiceId WHERE date(PostingDate) = CURDATE()-1");
				while ($yesterdayrow = mysqli_fetch_array($yesterdayquery)) {
					$yesterdays_sale = $yesterdayrow['Cost'];
					$yesterdaysale += $yesterdays_sale;

				}

				//Yesterday's installment sale
				$yesterdayqueryInstallments = mysqli_query($con, "SELECT first_install, 
							second_install, third_install FROM tblbook WHERE date(InvpostingDate) = CURDATE()-1");

				$yesterdaytotalFirstInstall = 0;
				$yesterdaytotalSecondInstall = 0;
				$yesterdaytotalThirdInstall = 0;

				while ($row = mysqli_fetch_array($yesterdayqueryInstallments)) {
					$yesterdaytotalFirstInstall += $row['first_install'];
					$yesterdaytotalSecondInstall += $row['second_install'];
					$yesterdaytotalThirdInstall += $row['third_install'];
				}

				// Calculate total amount of installments
				$yesterdaysaleinstall = $yesterdaytotalFirstInstall + $yesterdaytotalSecondInstall + $yesterdaytotalThirdInstall;
				$completeyesterdaysale = $yesterdaysale + $yesterdaysaleinstall;

				/////////////////////////////TODAY SALES QUERY AND COMPUTATION
				
				$todayquery = mysqli_query($con, "select tblinvoice.ServiceId as ServiceId,
						tblservices.Cost
					   from tblinvoice 
					   join tblservices  on 
					   tblservices.ID=tblinvoice.ServiceId
						where date(PostingDate)=CURDATE();");
				$todysale = 0; // Initialize today's sales variable
				
				if (mysqli_num_rows($todayquery) > 0) {
					while ($todayrow = mysqli_fetch_array($todayquery)) {
						$todays_sale = $todayrow['Cost'];
						$todysale += $todays_sale;
					}
				} else {
					$todysale = 0; // Set the message if no sales are found
				}


				// Today's installment sale
				$TodayqueryInstallments = mysqli_query($con, "SELECT first_install, 
					   second_install, third_install FROM tblbook WHERE date(InvpostingDate) = CURDATE();");

				$todaytotalFirstInstall = 0;
				$todaytotalSecondInstall = 0;
				$todaytotalThirdInstall = 0;

				while ($today2row = mysqli_fetch_array($TodayqueryInstallments)) {
					$todaytotalFirstInstall += $today2row['first_install'];
					$todaytotalSecondInstall += $today2row['second_install'];
					$todaytotalThirdInstall += $today2row['third_install'];
				}

				// Calculate total amount of installments
				$todaytotalInstallmentsAmount = $todaytotalFirstInstall + $todaytotalSecondInstall + $todaytotalThirdInstall;
				$completetodaysale = $todysale + $todaytotalInstallmentsAmount;

				////////////////////////////////////////TOTAL SALES QUERY AND COMPUTATION
				$salesquery = mysqli_query($con, "select 
					   tblinvoice.ServiceId as ServiceId, 
					   tblservices.Cost
					   from tblinvoice 
						join tblservices  on tblservices.ID=tblinvoice.ServiceId");
				while ($salesrow = mysqli_fetch_array($salesquery)) {
					$total_sale = $salesrow['Cost'];
					$totalsale += $total_sale;

				}

				//allinstallment sale
				$salesqueryInstallments = mysqli_query($con, "SELECT * FROM tblbook;");

				$salestotalFirstInstall = 0;
				$salestotalSecondInstall = 0;
				$salestotalThirdInstall = 0;

				while ($sales2row = mysqli_fetch_array($salesqueryInstallments)) {
					$salestotalFirstInstall += $sales2row['first_install'];
					$salestotalSecondInstall += $sales2row['second_install'];
					$salestotalThirdInstall += $sales2row['third_install'];
				}

				// Calculate total amount of installments
				$overallsaleinstall = $salestotalFirstInstall + $salestotalSecondInstall + $salestotalThirdInstall;
				$completeoverallsale = $totalsale + $overallsaleinstall;
				?>
				<!-- --------------END COMPUTATION------------- -->

				<div class="containercard">
					<div class="card" style="background-color: #FAC05E;">
						<div class="card-header">
							<img src="images\admin_icon\salesicon.png" alt="icon" class="zoomable-icon" />
							<span
								class="card-title"><?php echo number_format($completeoverallsale, 2, '.', ','); ?>K</span>
							<?php
							//////////////////////////////QUERY AND COMPUTATION FOR TOTAL EXPENSES
							$query3 = mysqli_query($con, "SELECT 
SUM(basic_salary) AS total_basic_salary, 
SUM(commission) AS total_commission 
FROM 
tblpayroll 
-- WHERE 
-- MONTH(payroll_month) = MONTH(CURRENT_DATE) 
-- AND YEAR(payroll_month) = YEAR(CURRENT_DATE);
");

							$result = mysqli_fetch_assoc($query3); // Fetch the result as an associative array
							
							// Calculate the total
							$total_basic_salary = $result['total_basic_salary'] ? $result['total_basic_salary'] : 0; // Default to 0 if null
							$total_commission = $result['total_commission'] ? $result['total_commission'] : 0; // Default to 0 if null
							$total_expenses = $total_basic_salary + $total_commission; // Sum the totals
							
							//////////////////////////////////////////////////////COMPUTATION FOR TOTAL
							$oldTotalSales = $completeyesterdaysale; // Example old total sales
							$newIncomingSales = $completetodaysale; // Example new incoming sales
							
							// Calculate new total sales
							$newTotalSales = $oldTotalSales + $newIncomingSales;
							// Calculate percentage increase
							if ($newIncomingSales == 0) {
								echo "<span class='card-value'> 0%</span>";
								$icons = "images\admin_icon\decrease.png";
							} else {
								if ($oldTotalSales > 0) { // Avoid division by zero
							
									$n = 31; // Number of days
									$crg = pow(($newIncomingSales / $oldTotalSales), (1 / $n)) - 1;
									$percentageIncrease = $crg * 100; // Convert to percentage
									if ($percentageIncrease == -100) {

										$icons = "images\admin_icon\decrease.png";
										echo "<span class='card-value'> 0%</span>";
									} elseif ($percentageIncrease < 0 && $percentageIncrease != -100) {
										echo "<span class='card-value'>" . round($percentageIncrease) . "%</span>";
										$icons = "images\admin_icon\decrease.png";


									} else {

										$icons = "images\admin_icon\increase.png";
										echo "<span class='card-value'>" . round($percentageIncrease) . "%</span>";

									}

								} else {
									echo "<span class='card-value'>" . round($percentageIncrease) . "%</span>";
									$icons = "images\admin_icon\decrease.png";

								}

							}

							?>
							<span style="position: absolute; top: 1px; right: 50px; width:35px; height: 35px;">
								<img src="<?php echo $icons; ?>" alt="trend icon"
									style="position: absolute; top: -3px;  width:40px; height: 40px;" />
							</span> <span class='card-value'
								style="position: absolute; top: 30px; right: 2px;font-size:16px;">Today</span>
							<span class="card-subtitle">Total Sales</span>
						</div>
					</div>

					<div class="card" style="background-color: #FAC05E;">
						<div class="card-header">
							<img src="images\admin_icon\expensesicon.png" alt="icon" class="zoomable-icon" />

							<span class="card-title"><?php echo number_format($total_expenses, 2, '.', ','); ?>K</span>
							<?php


							// Fetch expenses for the previous month
							$queryPreviousMonth = "SELECT SUM(basic_salary) AS total_basic_salary, SUM(commission) AS total_commission 
                       FROM tblpayroll 
                       WHERE MONTH(payroll_month) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH) 
                       AND YEAR(payroll_month) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH)";
							$resultPreviousMonth = mysqli_query($con, $queryPreviousMonth);
							$rowPreviousMonth = mysqli_fetch_assoc($resultPreviousMonth);

							$previousMonthExpenses = ($rowPreviousMonth['total_basic_salary'] ? $rowPreviousMonth['total_basic_salary'] : 0) +
								($rowPreviousMonth['total_commission'] ? $rowPreviousMonth['total_commission'] : 0);

							// Fetch expenses for the current month
							$queryCurrentMonth = "SELECT SUM(basic_salary) AS total_basic_salary, SUM(commission) AS total_commission 
                      FROM tblpayroll 
                      WHERE MONTH(payroll_month) = MONTH(CURRENT_DATE) 
                      AND YEAR(payroll_month) = YEAR(CURRENT_DATE)";
							$resultCurrentMonth = mysqli_query($con, $queryCurrentMonth);
							$rowCurrentMonth = mysqli_fetch_assoc($resultCurrentMonth);

							$currentMonthExpenses = ($rowCurrentMonth['total_basic_salary'] ? $rowCurrentMonth['total_basic_salary'] : 0) +
								($rowCurrentMonth['total_commission'] ? $rowCurrentMonth['total_commission'] : 0);




							if ($previousMonthExpenses > 0) { // Avoid division by zero
								// $percentageIncrease = (($currentMonthExpenses - $previousMonthExpenses) / $previousMonthExpenses) * 100;
							
								$n = 31; // Number of days
								$crg = pow(($currentMonthExpenses / $previousMonthExpenses), (1 / $n)) - 1; // Modified formula
								$Expensespercentage = $crg * 100; // Convert to percentage
							
								if ($Expensespercentage == -100) {

									$iconexp = "images\admin_icon\increase.png";
									echo "<span class='card-value'> 0%</span>";
								} elseif ($Expensespercentage < 0 && $Expensespercentage != -100) {
									echo "<span class='card-value'>" . round($Expensespercentage) . "%</span>";
									$iconexp = "images\admin_icon\decrease.png";

								} else {

									$iconexp = "images\admin_icon\increase.png";
									echo "<span class='card-value'>" . round($Expensespercentage) . "%</span>";
								}
							}
							?>


							<span style="position: absolute; top: 1px; right: 50px; width:35px; height: 35px;">
								<img src="<?php echo $iconexp; ?>" alt="trend icon"
									style="position: absolute; top: -3px;  width:40px; height: 40px;" />
							</span>

							<span class='card-value'
								style="position: absolute; top: 30px; right: 2px;font-size:16px;">Today</span>
							<span class="card-subtitle">Total Expenses</span>
						</div>
					</div>

					<div class="card" style="background-color: #FAC05E;">
						<div class="card-header">

							<?php
							$query2 = mysqli_query($con, "Select * from tblbook");
							$totalappointment = mysqli_num_rows($query2);
							?>

							<img src="images/admin_icon/bookicon.png" alt="icon" class="zoomable-icon" />
							<span class="card-title"><?php echo $totalappointment; ?></span>

							<?php
							$resultYesterday = mysqli_query($con, "SELECT COUNT(*) AS total_bookings_yesterday 
                   FROM tblbook 
                   WHERE DATE(BookingDate) = CURDATE() - INTERVAL 2 DAY OR DATE(BookingDate) = CURDATE() - INTERVAL 1 DAY");
							$rowYesterday = mysqli_fetch_assoc($resultYesterday);
							$totalBookingsYesterday = isset($rowYesterday['total_bookings_yesterday']) ? $rowYesterday['total_bookings_yesterday'] : 0;

							// Fetch total bookings for today
							$resultToday = mysqli_query($con, "SELECT COUNT(*) AS total_bookings_today 
               FROM tblbook 
               WHERE DATE(BookingDate) = CURDATE() - INTERVAL 1 DAY OR  DATE(BookingDate) = CURDATE() ");
							$rowToday = mysqli_fetch_assoc($resultToday);
							// Set total bookings to 0 if no records found, otherwise use the count
							$totalBookingsToday = isset($rowToday['total_bookings_today']) ? $rowToday['total_bookings_today'] : 0;

							if ($totalBookingsToday == 0) {

								echo "<span class='card-value'> 0%</span>";
								$iconapp = "images\admin_icon\decrease.png";

							} else {
								// Calculate percentage increase
								if ($totalBookingsYesterday > 0) { // Avoid division by zero
									$n = 31; // Number of days
									$crg = pow(($totalBookingsToday / $totalBookingsYesterday), (1 / $n)) - 1; // Modified formula
									$Bookingpercentage = $crg * 100; // C
							
									// Determine which icon to display
									if ($Bookingpercentage == -100) {

										echo "<span class='card-value'> 0%</span>";

										$iconapp = "images/admin_icon/decrease.png"; // Handle the case where Bookingpercentage
							
									} elseif ($Bookingpercentage < 0 && $Bookingpercentage != -100) {
										echo "<span class='card-value'>" . round($Bookingpercentage) . "%</span>";
										$iconapp = "images\admin_icon\decrease.png";


									} else {
										$iconapp = "images/admin_icon/increase.png";
										echo "<span class='card-value' style='color:whitesmoke;'>" . round($Bookingpercentage) . "%</span>";
									}
								} else {
									$Bookingpercentage = 0; // Ensure Bookingpercentage is set to 0
									$iconapp = "images/admin_icon/decrease.png"; // Handle the case where Bookingpercentage is exactly 0
									echo "<span class='card-value' style='color:whitesmoke;'>" . round($Bookingpercentage) . "%</span>";
								}
							}


							?>

							<span style="position: absolute; top: 1px; right: 50px; width:35px; height: 35px;">
								<img src="<?php echo $iconapp; ?>" alt="trend icon"
									style="position: absolute; top: -3px;  width:40px; height: 40px;" />
							</span>
							<span class='card-value'
								style="position: absolute; top: 30px; right: 2px;font-size:16px;">Today</span>
							<span class="card-subtitle">Total Appointment</span>
						</div>
					</div>




					<div class="card-money" style="background-color: #f5f3f3;">
						<h4 style="text-align: center; ">Best Selling Services</h4>
						<div class="card-header">
							<?php
							// Fetch services from tblbook
							$query = "SELECT BookService, COUNT(*) as service_count 
			FROM tblbook 
			GROUP BY BookService 
			ORDER BY service_count DESC 
			LIMIT 3";
							$result = mysqli_query($con, $query);

							$serviceCounts = [];
							while ($row = mysqli_fetch_assoc($result)) {
								$services = explode(',', $row['BookService']);
								foreach ($services as $service) {
									$service = trim($service); // Remove any extra spaces
									if (array_key_exists($service, $serviceCounts)) {
										$serviceCounts[$service]++;
									} else {
										$serviceCounts[$service] = 1;
									}
								}
							}

							// Prepare data for the chart
							$chartLabels = array_keys($serviceCounts);
							$chartData = array_values($serviceCounts);
							?>

							<div id="doughnutChart"></div> <!-- Doughnut chart container -->

							<script>
								document.addEventListener('DOMContentLoaded', function () {
									// Prepare data for the chart
									const labels = <?php echo json_encode($chartLabels); ?>; // Service names
									const data = <?php echo json_encode($chartData); ?>; // Service counts

									// Options for the ApexCharts doughnut chart
									var options = {
										chart: {
											type: 'donut',
											height: 250
										},
										series: data,
										labels: labels,
										legend: {
											position: 'bottom',
											horizontalAlign: 'center'
										},
										plotOptions: {
											pie: {
												donut: {
													size: '40%'
												}
											}
										},
										responsive: [{
											breakpoint: 480,
											options: {
												chart: {
													width: 200
												},
												legend: {
													position: 'bottom',
													horizontalAlign: 'center'
												}
											}
										}]
									};

									// Render the chart
									var chart = new ApexCharts(document.querySelector("#doughnutChart"), options);
									chart.render();
								});
							</script>
						</div>

					</div>

				</div>

				<!-- LINE CHART FOR SALES -->
				<section class="status-cards">
					<div class="cardss">
						<?php

						$currentDate = date('Y-m-d'); // Get the current date
						$query = "SELECT 
    DAY(tblinvoice.PostingDate) AS day,
    SUM(COALESCE(tblservices.Cost, 0)) AS totalCost,
    SUM(COALESCE(tblbook.first_install,0)) AS FirstInstall,
    SUM(COALESCE(tblbook.second_install,0)) AS SecondInstall,
    SUM(COALESCE(tblbook.third_install,0)) AS ThirdInstall  
FROM 
    tblinvoice
LEFT JOIN 
    tblservices ON tblservices.ID = tblinvoice.ServiceId 
LEFT JOIN 
    tblbook ON tblbook.APTNumber = tblinvoice.InvoiceAPTNumber 
    AND DATE(tblbook.InvpostingDate) = DATE(tblinvoice.PostingDate)
WHERE 
    DATE(tblinvoice.PostingDate) = '$currentDate'  -- Filter for current date
GROUP BY 
    YEAR(tblinvoice.PostingDate), MONTH(tblinvoice.PostingDate), DAY(tblinvoice.PostingDate) 
ORDER BY 
    YEAR(tblinvoice.PostingDate), MONTH(tblinvoice.PostingDate), DAY(tblinvoice.PostingDate);
";
						$result = mysqli_query($con, $query);



						$salesData = [];
						$daysInMonth = [];

						// Initialize sales data for each day of the month to 0
						for ($i = 1; $i <= 31; $i++) {
							$salesData[$i] = 0; // Default to 0
						}

						// Fetch results and populate salesData
						while ($row = mysqli_fetch_assoc($result)) {
							$day = (int) $row['day'];
							$salesData[$day] = (float) $row['totalCost'] + (float) $row['FirstInstall']
								+ (float) $row['ThirdInstall'] + (float) $row['SecondInstall']; // Added first_install to the salesData
						
						}
						// Prepare data for the chart
						$salesDataArray = array_values($salesData); // Get values for the chart
// $totalSalesAmount = array_sum($salesDataArray);
						$daysInMonth = range(1, 31); // Days of the month
						?>

						<div style="background-color: #f5f3f3;">
							<div id="chartst"></div>
							<select id="linemonth-filter" onchange="updateLineChart()">
								<?php

								$currentMonth = (int) date('m'); // Current month as an integer
								echo "<option value='$currentMonth '>SELECT MONTH</option>"; // Show current month as first option
								
								for ($i = 0; $i < 12; $i++) {
									$month = (12 - $i + 12) % 12; // Calculate month correctly
									$month = $month === 0 ? 12 : $month; // Adjust to show December as 12 instead of 0
									// Output the month, starting from December
									echo "<option value='$month'>" . date('F', mktime(0, 0, 0, $month, 1)) . "</option>"; // Use date function to get month name
								}
								?>
							</select>
						</div>
						<script>
							// Call updateLineChart on page load to fetch current day's data
							document.addEventListener("DOMContentLoaded", function () {
								updateLineChart(); // Fetch data for the current day
							});
							function updateLineChart() {
								const selectedmonth = document.getElementById('linemonth-filter').value;

								// Make an AJAX request to fetch the new data based on the selected month
								$.ajax({
									url: 'ajax.php', // This should be the PHP file that returns data based on the month
									type: 'GET',
									data: { month: selectedmonth }, // Pass the selected month
									success: function (response) {
										const data = JSON.parse(response); // Parse the JSON response

										// Prepare the days of the month for the x-axis
										const daysInMonth = Array.from({ length: data.length }, (_, i) => i + 1);

										// Update the line chart with the new data
										linechart.updateSeries([{
											name: 'Total Sales',
											data: data,
										}]);

										// Update the x-axis categories
										linechart.updateOptions({
											xaxis: {
												categories: daysInMonth,
											}
										});
									},
									error: function (xhr, status, error) {
										console.error('AJAX Error:', error);
									}
								});
							}

							// Use PHP to pass the data to JavaScript
							const salesData = <?php echo json_encode($salesDataArray); ?>;
							const daysInMonth = Array.from({ length: salesData.length }, (_, i) => i + 1);
							// Options for the ApexCharts line chart
							var options = {
								chart: {
									height: 350,
									type: 'line',
									zoom: {
										enabled: false
									}
								},

								series: [{
									name: 'Total Sales',
									data: salesData
								}],
								stroke: {
									curve: 'smooth'
								},
								title: {
									text: 'Total Sales for Each Day this Year',
									align: 'center'

								},
								grid: {
									row: {
										colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
										opacity: 0.5
									},
								},
								xaxis: {
									categories: daysInMonth,
									title: {
										text: 'Days of the Month'
									}
								},
								yaxis: {
									title: {
										text: 'Total Sales'
									}
								},

							};

							// Render the chart
							var linechart = new ApexCharts(document.querySelector("#chartst "), options);
							linechart.render();


						</script>
						<div class="containercard">
							<div class="card-radial">
								<div class="card-header">

									<?php
									// Assuming you have a database connection established
									$radialquery = "SELECT AdminName, SUM(ratings) as total_rating FROM tbladmin GROUP BY AdminName";
									$radialresult = mysqli_query($con, $radialquery);

									$radialseries = [];
									$radiallabels = [];
									while ($row = mysqli_fetch_assoc($radialresult)) {
										$radialseries[] = (int) $row['total_rating']; // Total ratings
										$radiallabels[] = $row['AdminName']; // Employee names
									}

									// Convert PHP arrays to JavaScript arrays
									$series_json = json_encode($radialseries);
									$labels_json = json_encode($radiallabels);
									?>

									<div id="RadialBarchart"></div>
									<h4 style="text-align: center; margin-top: 260px;">Employee Ratings</h4>
									<script>
										document.addEventListener('DOMContentLoaded', function () {
											var options = {
												series: <?php echo $series_json; ?>,
												chart: {
													height: 280,
													type: 'radialBar',
												},
												plotOptions: {
													radialBar: {
														offsetY: 0,
														startAngle: 0,
														endAngle: 180,
														hollow: {
															margin: 5,
															size: '30%',
															background: 'transparent',
														},
														dataLabels: {
															name: {
																show: true,
															},
															value: {
																show: true,
															}
														},
														barLabels: {
															enabled: true,
															useSeriesColors: true,
															offsetX: -8,
															fontSize: '16px',
															formatter: function (seriesName, opts) {
																return seriesName + ":  " + opts.w.globals.series[opts.seriesIndex]
															},
														},
													}
												},
												colors: ['#003F91', '#573280', '#FF521B', '#780116', '#198457'],
												labels: <?php echo $labels_json; ?>,
												responsive: [{
													breakpoint: 480,
													options: {
														legend: {
															show: false
														}
													}
												}]
											};

											var chart = new ApexCharts(document.querySelector("#RadialBarchart"), options);
											chart.render();
										});
									</script>
								</div>
							</div>
							<div class="card-bar">
								<div class="card-header">

									<?php
									// Assuming you have a database connection established
									$barquery = "SELECT 
    COUNT(CASE WHEN feedback = 'excellent' THEN 1 END) AS total_excellent,
    COUNT(CASE WHEN feedback = 'poor' THEN 1 END) AS total_poor,
    COUNT(CASE WHEN feedback = 'neutral' THEN 1 END) AS total_neutral,
    COUNT(CASE WHEN feedback = 'good' THEN 1 END) AS total_good
	FROM poll";
									$barresult = mysqli_query($con, $barquery);

									$barseries = [];
									while ($row = mysqli_fetch_assoc($barresult)) {
										$barseries[] = (int) $row['total_excellent']; // Total ratings
										$barseries[] = (int) $row['total_poor']; // Total ratings
										$barseries[] = (int) $row['total_neutral']; // Total ratings
										$barseries[] = (int) $row['total_good']; // Total ratings
									}

									// Convert PHP arrays to JavaScript arrays
									$barseries_json = json_encode($barseries);
									?>

									<div id="Barchart"></div>
									<h4 style="text-align: center; margin-top: 5px;">Service Feedback</h4>
									<script>
										var options = {
											series: [{
												name: 'Feedback Count',
												data: <?php echo $barseries_json; ?>
											}],
											chart: {
												type: 'bar',
												height: 280,
												stacked: false,
											},
											stroke: {
												width: 1,
												colors: ['#fff']
											},
											grid: {
												row: {
													colors: ['#fff', 'transparent'], // takes an array which will be repeated on columns
													opacity: 0.5
												},
											},
											dataLabels: {
												formatter: (val) => {
													return val; // Show the actual count
												}
											},
											plotOptions: {
												bar: {
													horizontal: false,
													colors: {
														ranges: [
															{ from: 0, to: 1, color: '#F87666' }, // Adjust the ranges as needed
															{ from: 2, to: 3, color: '#5F4BB6' },
															{ from: 4, to: 5, color: '#F9A03F' },
															{ from: 6, to: 7, color: '#21FA90' }
														]
													}
												}
											},
											xaxis: {
												categories: [
													'Excellent',
													'Good',
													'Neutral',
													'Poor'
												],
											},
											yaxis: {
												title: {
													text: 'Total Count'
												}
											}
										};

										var chart = new ApexCharts(document.querySelector("#Barchart"), options);
										chart.render();

									</script>
								</div>
							</div>

						</div>
					</div>
					<div class="cardss2">

						<?php

						$insightsql = "SELECT 
MONTH(BookingDate) AS month,
COUNT(DISTINCT CASE WHEN 
    YEAR(BookingDate) = YEAR(CURDATE()) 
    AND MONTH(BookingDate) = MONTH(CURDATE())
    AND UserID NOT IN 
    (SELECT UserID FROM tblbook WHERE YEAR(BookingDate) = YEAR(CURDATE()) AND MONTH(BookingDate) < MONTH(CURDATE()))
    AND (SELECT COUNT(*) FROM tblbook WHERE UserID = b.UserID AND YEAR(BookingDate) = YEAR(CURDATE())) = 1 
THEN UserID END) AS new_customers,

COUNT(DISTINCT CASE WHEN 
    UserID IN 
    (SELECT UserID FROM tblbook WHERE YEAR(BookingDate) = YEAR(CURDATE()) AND MONTH(BookingDate) <= MONTH(CURDATE())) 
THEN UserID END) AS returning_customers
FROM 
tblbook b
WHERE 
YEAR(BookingDate) = YEAR(CURDATE())
GROUP BY 
MONTH(BookingDate)
ORDER BY 
month;";

						$insightresult = mysqli_query($con, $insightsql);
						$newCustomers = array_fill(0, 12, 0);
						$returningCustomers = array_fill(0, 12, 0);

						if ($insightresult->num_rows > 0) {
							while ($row = $insightresult->fetch_assoc()) { // Use $insightresult instead of $result
								$newCustomers[$row['month'] - 1] = (int) $row['new_customers'];
								$returningCustomers[$row['month'] - 1] = (int) $row['returning_customers'];
							}
						}

						?>
						<div id="customerInsightsChart"></div>
						<script>
							// Assign PHP arrays to JavaScript variables
							const customerInsightsData = [
								{
									name: "New Customers",
									data: <?php echo json_encode($newCustomers); ?>
								},
								{
									name: "Returning Customers",
									data: <?php echo json_encode($returningCustomers); ?>
								}
							];

							// Initialize ApexCharts
							var options = {
								series: customerInsightsData,
								chart: {
									type: 'area',
									height: 350,
									toolbar: {
										show: false
									},
								},
								dataLabels: {
									enabled: false
								},
								title: {
									text: "Customer Insights",
									align: "center"
								},
								stroke: {
									curve: 'smooth'
								},
								xaxis: {
									type: 'category',
									categories: [
										"Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
									]
								},
								yaxis: {
									title: {
										text: "Customer Count"
									}
								},
								tooltip: {
									x: {
										format: 'MMM'
									}
								}
							};

							var chart = new ApexCharts(document.querySelector("#customerInsightsChart"), options);
							chart.render();
						</script>


						<div class="containercard">
							<div class="card" style="background-color: #FAC05E;">
								<div class="card-header" style="position: relative; ">
									<?php

									$inventoryDownQuery = "SELECT product_name, out_stocks 
					FROM tblinventory 
					WHERE out_stocks = (SELECT MIN(out_stocks) FROM tblinventory)
				";
									$inventoryResult = mysqli_query($con, $inventoryDownQuery);
									$row = mysqli_fetch_assoc($inventoryResult);
									$lowestStock = $row['out_stocks'];
									$productlowestStock = $row['product_name'];


									$inventoryUpQuery = "SELECT product_name, out_stocks 
					FROM tblinventory 
					WHERE out_stocks = (SELECT MAX(out_stocks) FROM tblinventory)
				";
									$inventoryUpResult = mysqli_query($con, $inventoryUpQuery);
									$row = mysqli_fetch_assoc($inventoryUpResult);
									$highStock = $row['out_stocks'];
									$producthighestStock = $row['product_name'];


									if ($lowestStock || $highStock) {
										$lowesticon = "images\admin_icon\arrowdown.png";
										$highesticon = "images\admin_icon\arrowup.png";
									}
									?>
									<img src="images/admin_icon/inventorylogo.png" alt="icon" class="zoomable-icon"
										style="width:60%;" />
									<span class="card-title"><?php echo $lowestStock; ?>, </span>
									<span class="card-stocks"><?php echo $productlowestStock; ?></span>
									<span class="card-subtitle">Lowest Stocks</span>
									<div>
										<span style="position: absolute; 
		 top: 5px; right: 5px; width:35px; height: 35px;"><img src="<?php echo $lowesticon; ?>" alt="trend icon"
												class="trend-icondown" style="width:35px; height: 35px;" /></span>
									</div>
								</div>
							</div>

							<div class="card" style="background-color: #FAC05E;">
								<div class="card-header">
									<img src="images/admin_icon/inventorylogo.png" alt="icon" class="zoomable-icon"
										style="width:60%;" />
									<span class="card-title"><?php echo $highStock; ?>, </span>
									<!-- Icon positioned to the top right -->
									<span style="position: absolute; top: 5px; right: 5px; width:35px; height: 35px;">
										<img src="<?php echo $highesticon; ?>" alt="trend icon"
											style="position: absolute; top: 5px; right: 5px; width:35px; height: 35px;" />
									</span>
									<span class="card-stocks"><?php echo $producthighestStock; ?></span>
									<span class="card-subtitle">Highest Stocks</span>

								</div>
							</div>
						</div>

					</div>


				</section>
				<?php
				// Query to get today's attendance records
				$query1 = mysqli_query($con, "SELECT * FROM tblattendance WHERE signIn = CURDATE()");

				$punctualCount = 0;
				$lateCount = 0;
				$totalAttendance = 0;

				// Loop through the results
				while ($row = mysqli_fetch_array($query1)) {
					$totalAttendance++; // Increment total attendance count
				
					// Check the punchIN time
					$punchInTime = $row['punchIN']; // Assuming 'punchIN' is the column name for the sign-in time
				
					// Check if the user is punctual or late
					if ($punchInTime <= '08:00:00') {
						$punctualCount++; // Increment punctual count
					} else {
						$lateCount++; // Increment late count
					}
				}

				// Output the results
				?>

				<!-- MODAL FOR ATTENDANCE -->
				<div id="payslipModal" class="modal" style="display:none;">
					<div class="modal-content" style=" width: 80%; max-width: 1000px;">
						<span class="close" style=" font-size: 50px;">&times;</span>

						<section class="status-cards" style="margin-top: 50px;">
							<div class="card punctual">
								<span>Punctual Today</span>
								<strong><?php echo $punctualCount; ?></strong>
							</div>
							<div class="card late">
								<span>Late Today</span>
								<strong><?php echo $lateCount; ?></strong>
							</div>
							<div class="card attendance">
								<span>Today's Attendance</span>
								<strong><?php echo $totalAttendance; ?></strong>
							</div>
							<?php if (isset($alertErrorMessage))
								echo $alertErrorMessage; ?>
							<?php if (isset($alertSuccessMessage))
								echo $alertSuccessMessage; ?>
						</section>

						<section class="time-book">
							<div class="time-entry">
								<h2>Employee Time-Book</h2>
								<form method="POST" action="">
									<label for="employeeId">Enter Employee ID to Punch IN</label>
									<input type="text" name="employeeId" id="employeeId"
										placeholder="type employee id here..." required>
									<button type="submit" name="signIn" class="sign-in">Sign In</button>
								</form>
							</div>
							<div class="time-entry">
								<h2>Employee Time-Book</h2>
								<form method="POST" action="">
									<label for="employeeIdOut">Enter Employee ID to Punch OUT</label>
									<input type="text" name="employeeIdOut" id="employeeIdOut"
										placeholder="type employee id here..." required>
									<button type="submit" name="signOut" class="sign-out">Sign Out</button>
								</form>
							</div>
						</section>
						<section class="attendance-history">
							<h2>Today Attendance History</h2>
							<table>
								<thead>
									<tr>
										<th>SN</th>
										<th>EMPLOYEE ID</th>
										<th>SIGN IN DATE</th>
										<th>SIGN IN TIME</th>
										<th>SIGN OUT TIME</th>
										<th>STATUS</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$sn = 1; // Serial number
									while ($row = mysqli_fetch_array($attendanceResult)) {
										echo "<tr>
								<td>#{$sn}</td>
								<td>{$row['employeeID']}</td>
								<td>{$row['signIn']}</td>
								<td>{$row['punchIN']}</td>
								<td>{$row['punchOUT']}</td>
								<td>{$row['Status']}</td>
							</tr>";
										$sn++;
									}
									?>
								</tbody>
							</table>
						</section>
					</div>
				</div>

			</div>
		</div>


		<!--footer-->
		<?php include_once('includes/footer.php'); ?>
		<!--//footer-->
	</div>
	<!-- Classie -->
	<script src="js/classie.js"></script>
	<script>
		var menuLeft = document.getElementById('cbp-spmenu-s1'),
			showLeftPush = document.getElementById('showLeftPush'),
			body = document.body;

		showLeftPush.onclick = function () {
			classie.toggle(this, 'active');
			classie.toggle(body, 'cbp-spmenu-push-toright');
			classie.toggle(menuLeft, 'cbp-spmenu-open');
			disableOther('showLeftPush');
		};


		function disableOther(button) {
			if (button !== 'showLeftPush') {
				classie.toggle(showLeftPush, 'disabled');
			}
		}


		/////////////Modal
		//EMPLOYEE PAYSLIP
		function openModal() {
			document.getElementById('payslipModal').style.display = 'block';
		}

		// Close modal when clicking the close button
		document.querySelectorAll('.close').forEach(function (closeBtn) {
			closeBtn.addEventListener('click', function () {
				const modal = closeBtn.closest('.modal'); // Assuming each modal has a class 'modal'
				modal.style.display = 'none';
			});
		});
		// Close modal when clicking outside of it
		window.onclick = function (event) {
			const modal = document.getElementById('payslipModal');
			if (event.target === modal) {
				modal.style.display = 'none';
			}
		};
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