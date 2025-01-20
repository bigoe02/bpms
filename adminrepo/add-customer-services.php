<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['bpmsaid']==0)) {
  header('location:logout.php');
  } else{
    if(isset($_POST['submit'])){

        $uid = intval($_GET['addid']); 
        $invoiceid = mt_rand(100000000, 999999999);
        $sid = $_POST['sids']; 
        $install = $_POST['INSERTInstallment'];
        $totalCost = 0; // Initialize total cost
  $invoiceAPT = $_POST['invoiceAPTNumber']; 
         // Retrieve the employee name from the form where it will get the employeeID and employeeName
         list($employeeID, $employeeName) = explode(',', $_POST['Employee_Name']); // Split the value
 
    if(!$sid){

            // Retrieve the selected installment
            $selectedInstallment = $_POST['installments'];
            switch ($selectedInstallment) {
                case '1st':
                    $installmentColumn = 'first_install';
                    break;
                case '2nd':
                    $installmentColumn = 'second_install';
                    break;
                case '3rd':
                    $installmentColumn = 'third_install';
                    break;
                default:
                    die("Invalid installment selected.");
            }

            $installmentQuery = mysqli_query($con, "SELECT * FROM tblbook WHERE ID = $uid  AND $installmentColumn IS NOT NULL");
            if (mysqli_num_rows($installmentQuery) > 0) {
                echo '<script>alert("You have already installment for this selection.")</script>';
            } else{
            $insertInstallment = $_POST['INSERTInstallment'];

            $UpdateinstallmentQuery = mysqli_query($con, "UPDATE tblbook SET $installmentColumn = $insertInstallment WHERE APTNumber = '$invoiceAPT' ");
                echo '<script>alert("Installment updated successfully.")</script>';
            }
                   // Add to total cost
                   $totalCost += $install;
    

                    
                   $ret = mysqli_query($con, "INSERT INTO tblinvoice (Userid, ServiceId, BillingId, InvoiceAPTNumber) 
                    VALUES ('$uid', '$svid', '$invoiceid', '$invoiceAPT');");
                    $updateInvPostingDateQuery = mysqli_query($con, "UPDATE tblbook SET invpostingDate =  NOW() WHERE AptNumber = '$invoiceAPT'");

    } else {
                // Loop through selected service IDs
                for($i = 0; $i < count($sid); $i++){ 
                    $svid = $sid[$i];
                    
                    // Fetch the cost of the selected service
                    $serviceQuery = mysqli_query($con, "SELECT Cost, serviceID FROM tblservices WHERE ID = '$svid'");
                    $serviceRow = mysqli_fetch_array($serviceQuery);
                    $serviceCost = $serviceRow['Cost'];
                    $categoryId = $serviceRow['serviceID'];
                    // Add to total cost
                    $totalCost += $serviceCost;
            
    
                    $ret = mysqli_query($con, "INSERT INTO tblinvoice (Userid, ServiceId, BillingId, InvoiceAPTNumber) 
                    VALUES ('$uid', '$svid', '$invoiceid', '$invoiceAPT');");
                    
                    $updateInvPostingDateQuery = mysqli_query($con, "UPDATE tblbook SET invpostingDate =  NOW() WHERE AptNumber = '$invoiceAPT'");

                    // Update out_stocks in tblinventory
            $updateInventoryQuery = mysqli_query($con, "UPDATE tblinventory SET out_stocks = out_stocks - 1 WHERE category_id = '$categoryId'");
         
                

                    }
            }
               


            // Calculate employee commission (5% of total cost)
            $employeeCommission = $totalCost * 0.05;
            // Get the current date
            $currentDate = date('Y-m-d'); 
            // Check if the record exists
            $checkQuery = mysqli_query($con, "SELECT * FROM tblcomdeducs WHERE employeeName = '$employeeName' AND comdeducsDate = '$currentDate'");

            if (mysqli_num_rows($checkQuery) > 0) {
                // Record exists, update the commission
                $updateCommissionQuery = mysqli_query($con, "UPDATE tblcomdeducs SET pluscommission = pluscommission + '$employeeCommission' WHERE employeeName = '$employeeName' AND comdeducsDate = '$currentDate'");

                if ($updateCommissionQuery) {
                    echo '<script>alert("Commission updated successfully.")</script>';
                } else {
                    echo '<script>alert("Error updating commission: ' . mysqli_error($con) . '")</script>';
                }
            } else {
                // Record does not exist, insert a new record
                $insertCommissionQuery = mysqli_query($con, "INSERT INTO tblcomdeducs (employeeID, employeeName, pluscommission, comdeducsDate) VALUES ('$employeeID','$employeeName', '$employeeCommission', '$currentDate')");

                if ($insertCommissionQuery) {
                    echo '<script>alert("Commission recorded successfully.")</script>';
                } else {
                    echo '<script>alert("Error recording commission: ' . mysqli_error($con) . '")</script>';
                }
            }

            echo '<script>alert("Invoice created successfully. Invoice number is ' . $invoiceid . '. Total cost: ' . $totalCost . '. Employee commission: ' . $employeeCommission . '")</script>'; 
            echo "<script>window.location.href ='invoices.php'</script>"; 
    } 

 


  ?>
<!DOCTYPE HTML>
<html>
<head>
<title>BPMS || Assign Services</title>

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
			<div class="row" style="display: flex;">
            <div class="col-left" style="flex: 1; margin-right: 10px;">
			<div class="tables">
				<!-- SEARCH BAR-->
			<i class="fa fa-search fa-2x" aria-hidden="true"></i>
					<input type="text"  id="searchInput" onkeyup="searchTable()" placeholder="Search for services..."
					 style="width: 90%; font-size: 16px; padding: 12px 20px 12px 40px; border: 1px solid #ddd;">
					 
                     <form method="post">
					 <div class="table-responsive bs-example widget-shadow">
                     <div class="form-group">
                    <label for="Employee_Name">Employee Handler</label>
                    <select class="form-control" id="Employee_Name" name="Employee_Name" required="true" style="height: 40px;">
                                <?php
                                $query = mysqli_query($con, "SELECT AdminName, employeeID FROM tbladmin");
                                while ($row = mysqli_fetch_array($query)) {
                                    echo "<option value='" . $row['employeeID'] . "," . $row['AdminName'] ."'>" . $row['AdminName'] . "</option>";
                                }
                                ?>
                    </select>
                    
                </div>
                     <div class="form-group"> 
                                <label for="invoiceAPTNumber">Appointment Number</label> 
                                <input type="text" class="form-control" id="invoiceAPTNumber" name="invoiceAPTNumber"  value="" required="true" readonly> 
                    </div>
                    <div class="form-group">
                    <label for="InstallmentService_Name">INSTALLMENT SERVICE:</label>
                    <select class="form-control" id="InstallmentService_Name" name="InstallmentService_Name" style="height: 40px;">
                                <?php
                                $query = mysqli_query($con, "SELECT * FROM tblservices WHERE ID >= 18");
                                while ($row = mysqli_fetch_array($query)) {
                                    echo "<option value='" . $row['ServiceName'] ."'>" . $row['ServiceName'] . "</option>";
                                }
                                ?>
                    </select>
                </div>
                <label for="installments">Select Installment:</label>
                        <select id="installments" name="installments">
                            <option value="1st">1st Installment</option>
                            <option value="2nd">2nd Installment</option>
                            <option value="3rd">3rd Installment</option>
                        </select>
                    <div class="form-group">
                    <label for="INSERTInstallment">Installment Price:</label>
                    <input type="text" class="form-control" id="INSERTInstallment" name="INSERTInstallment"  value="">
                     
                </div>
					<h4>Assign Services:</h4>
					
					
			    <table id="serviceTable1" class="table table-bordered"  style=" display: block; height: 80vh; overflow-y: auto;"> 
					<thead> 
					<tr> 
						<th>#</th> 
						<th>Service Name</th> 
						<th>Service Price</th> 
						<th>Action</th> 
					</tr> 
					</thead> 
					
				<tbody>
					<?php
					$ret=mysqli_query($con,"select * from  tblservices WHERE ID >=5");
					$cnt=1;
					while ($row=mysqli_fetch_array($ret)) {

					?>

					<tr> 
						<th scope="row"><?php echo $cnt;?></th> 
						<td><?php  echo $row['ServiceName'];?></td> 
						<td><?php  echo $row['Cost'];?></td> 
						<td><input type="checkbox" name="sids[]" value="<?php  echo $row['ID'];?>" ></td> 
                    </tr>   
					<?php 
					$cnt=$cnt+1;
					}?>
				</tbody> 
			</table> 
            <center> <button type="submit" name="submit" class="btn btn-primary" style="margin-bottom: 10px;">Submit</button></center>		
				</form>
				</div>
			</div>
		</div>


		<div class="col-right" style="flex: 1; margin-top: 15px;">
            <div class="tables" style="width: 90%;">
                <?php
				 $userId = $_GET['addid'];
                 // Fetch the user's name
                    $userQuery = mysqli_query($con, "SELECT FirstName, LastName FROM tbluser WHERE ID = '$userId'");
                    $user = mysqli_fetch_array($userQuery);
                    $firstName = $user['FirstName'];
                    $lastName = $user['LastName'];

				?>
                    <h3 class="title1"><?php echo $firstName; ?> <?php echo $lastName; ?>  Appointment</h3>
            <div id="serviceTable2" class="table-responsive bs-example widget-shadow" >
                <h4>New Appointment:</h4>
                <table class="table table-bordered" style=" display: block; height: 70vh; overflow-y: auto; ">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Appointment Number</th>
                            <th>Name</th>
                            <th>Appointment Date</th>
                            <th>Appointment Time</th>
                            <th>Book Service</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <?php
        $usersId = $_GET['addid'];
        $ret = mysqli_query($con, "SELECT
            tbluser.FirstName,
            tbluser.LastName,
            tbluser.Email,
            tblbook.ID as bid,
            tblbook.AptNumber,
            tblbook.AptDate,
            tblbook.AptTime,
            tblbook.BookService,
            tblbook.BookingDate,
            tblbook.Status
            FROM tblbook 
            JOIN tbluser ON tbluser.ID = tblbook.UserID 
            WHERE tblbook.Status = 'Selected' 
            AND NOT EXISTS (
                SELECT 1 
                FROM tblinvoice 
                WHERE tblinvoice.InvoiceAPTNumber = tblbook.AptNumber
            ) AND tbluser.ID = '$usersId' ORDER BY tblbook.ID DESC;");

        $cnt = 1;
        $hasRecords = false; // Flag to check if there are any records
       
        while ($row = mysqli_fetch_array($ret)) {
             $hasRecords = true; // Set the flag to true if there are records
            ?>
        
                    <tbody>
                        <tr onclick="populateAppointmentNumber('<?php echo $row['AptNumber']; ?>', this)">
                            <th scope="row"><?php echo $cnt; ?></th>
                            <td><?php echo $row['AptNumber']; ?></td>
                            <td><?php echo $row['FirstName']; ?> <?php echo $row['LastName']; ?></td>
                            <td><?php echo $row['AptDate']; ?></td>
                            <td><?php echo $row['AptTime']; ?></td>
                            <td><?php
                               $services = explode(',', $row['BookService']);
                                echo implode(', ', array_map('trim', $services)); // Join the services with a comma and space
                                ?>
                            </td>
                            <td><a href="view-appointment.php?viewid=<?php echo $row['bid']; ?>" class="btn btn-primary">View</a>
                                <a href="accepted-appointment.php?delid=<?php echo $row['bid']; ?>" class="btn btn-danger" onClick="return confirm('Are you sure you want to delete?')">Delete</a></td>
                        </tr>
                        
                        <?php
                        $cnt = $cnt + 1;
                    }
                    if (!$hasRecords) {
                        // Display the table structure even if there are no records	
                    ?>
                        <tr>
                            <td colspan="7" style="text-align: center;">No appointments available.</td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
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


			function searchTable() {
            var input, filter, tables, tr, td, i, txtValue;
            input = document.getElementById("searchInput");
            filter = input.value.toUpperCase();
            tables = document.querySelectorAll("table"); // Select all tables

            for (let table of tables) {
                tr = table.getElementsByTagName("tr");
                for (i = 0; i < tr.length; i++) {
                    td = tr[i].getElementsByTagName("td")[0]; // Change index to search specific column
                    if (td) {
                        txtValue = td.textContent || td.innerText;
                        if (txtValue.toUpperCase().indexOf(filter) > -1) {
                            tr[i].style.display = "";
                        } else {
                            tr[i].style.display = "none";
                        }
                    }
                }
            }
        }
        

        let lastClickedRow = null; // Variable to store the last clicked row

        function populateAppointmentNumber(appointmentNumber, row) {
    // Set the value in the input field
    document.getElementById("invoiceAPTNumber").value = appointmentNumber;

    // Remove highlight from the last clicked row
    if (lastClickedRow) {
        lastClickedRow.classList.remove('highlight');
    }

    // Add highlight to the current clicked row
    row.classList.add('highlight');

    // Update the last clicked row
    lastClickedRow = row;

    // Get the services from the clicked row
    const services = row.cells[5].innerText; // Assuming the Book Service is in the 6th column (index 5)
    const serviceArray = services.split(',').map(service => service.trim()); // Split and trim the services

    // Uncheck all checkboxes first
    const checkboxes = document.querySelectorAll('input[name="sids[]"]');
    checkboxes.forEach(checkbox => {
        checkbox.checked = false; // Uncheck all checkboxes
    });

    // Check the corresponding checkboxes based on the services
    serviceArray.forEach(service => {
        checkboxes.forEach(checkbox => {
            const serviceName = checkbox.closest('tr').cells[1].innerText; // Get the service name from the Assign Services table
            if (serviceName === service) {
                checkbox.checked = true; // Check the checkbox if the service matches
            }
        });
    });
}

    
		</script>
	<!--scrolling js-->
	<script src="js/jquery.nicescroll.js"></script>
	<script src="js/scripts.js"></script>
	<!--//scrolling js-->
	<!-- Bootstrap Core JavaScript -->
	<script src="js/bootstrap.js"> </script>
    
    <style>
    .highlight {
    background-color: #acc3d6; /* Light blue background */
    }
    </style>

</body>
</html>
<?php }  ?>