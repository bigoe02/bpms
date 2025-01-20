<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['bpmsaid']==0)) {
  header('location:logout.php');
  } else{    
        // Get the current month and year FOR DOUGHNUT CHART
        $currentMonth = date('Y-m');
        $query = "SELECT employeeName, net_salary AS total_net_salary 
                  FROM tblpayroll 
                  WHERE payroll_month LIKE '$currentMonth%'
                  GROUP BY employeeName";
        $result = mysqli_query($con, $query);
    
        $payrollData = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $payrollData[] = [
                'name' => $row['employeeName'],
                'net_salary' => (float)$row['total_net_salary']
            ];
        }
         // Prepare data for the chart FOR DOUGHTNUT CHART
    $labels = json_encode(array_column($payrollData, 'name'));
    $data = json_encode(array_column($payrollData, 'net_salary'));


    if (isset($_POST['submit'])) {
        
         // Get the new data from the input fields
    $newPAYROLLEmployeeID = $_POST['Employee_ID'];
    $newPAYROLLEmployeename = $_POST['Employee_Name'];
    $newPAYROLLBasic_Salary = $_POST['Basic_Salary'];
    $newPAYROLLCommision = $_POST['Commission'];
    
    // Calculate total of basic salary and commission
    $newPAYROLLNet_Salary= $newPAYROLLBasic_Salary + $newPAYROLLCommision;

    

    // Specify the column(s) to insert into
    $insertnewpayroll = "INSERT INTO tblpayroll (employeeID,employeeName, 
        basic_salary, 
        commission, 
        net_salary, 
        payroll_month) VALUES ('$newPAYROLLEmployeeID','$newPAYROLLEmployeename',
        '$newPAYROLLBasic_Salary',
        '$newPAYROLLCommision',
        '$newPAYROLLNet_Salary',
        CURDATE())";
    
            // Check if the query was executed successfully
            if (!mysqli_query($con, $insertnewpayroll)) {
                echo "Error: " . mysqli_error($con);
            } else {
                // Display a success message
                echo "<script>alert('NEW PAYROLL has been added in the table.');</script>"; 
                echo "<script>window.location.href='employee-payroll.php'</script>";
                
            }  }

            if (isset($_POST['submitTaxes'])) {
                // Get the contributions from the form
                $sssContribution = $_POST['Taxes_SSS'];
                $philHealthContribution = $_POST['Taxes_PhilHealth'];
                $pagibigContribution = $_POST['Taxes_Pag-Ibig'];
                $payrollDate = $_POST['Taxes_Date']; // Assuming this is the date you want to filter by
            
                // Fetch net_salary from tblpayroll based on employee name and payroll month
                $employeeName = $_POST['Employee_Name']; // Ensure this is set from the form
                $query = "SELECT net_salary , payroll_month FROM tblpayroll WHERE employeeName = '$employeeName' AND payroll_month = '$payrollDate'";
                $result = mysqli_query($con, $query);
                
                if ($result) {
                    // Check if any row was returned
                    if (mysqli_num_rows($result) > 0) {
                        $row = mysqli_fetch_assoc($result);
                        $updateNet_Salary = $row['net_salary']; // Assign the fetched net_salary to the variable
                        
                        // Calculate total tax
                        $totalTax = $sssContribution + $philHealthContribution + $pagibigContribution;
            
                        // Subtract updateNet_Salary from totalTax
                        $adjustedTax =  $updateNet_Salary - $totalTax;
            
                        // Prepare the update query
                        $updateTaxQuery = "UPDATE tblpayroll 
                                           SET tax = '$totalTax', net_salary = '$adjustedTax'
                                           WHERE payroll_month = '$payrollDate' AND employeeName = '$employeeName'"; 
            
                        // Execute the update query
                        if (!mysqli_query($con, $updateTaxQuery)) {
                            echo "Error updating tax: " . mysqli_error($con);
                        } else {
                            echo "<script>alert('Tax updated successfully. Tax: $totalTax and Net Salary: $adjustedTax');</script>";
                        }
                    } else {
                        // If no rows were returned, the employee does not exist for the specified payroll date
                        echo "<script>alert('The payroll for this employee does not exist for the specified date.');</script>";
                    }
                } else {
                    echo "Error fetching net salary: " . mysqli_error($con);
                }
            }

            if (isset($_POST['submitAllowance'])) {
                // Get the contributions from the form
                $FoodAllowance = $_POST['Allowance_Food'];
                $DrinksAllowance = $_POST['Allowance_Drinks'];
                $AllowanceDate = $_POST['Allowance_Date']; // Assuming this is the date you want to filter by
            
                  // Fetch net_salary from tblpayroll based on employee name and payroll month
                  $employeeName = $_POST['Employee_Name']; // Ensure this is set from the form
                  $query = "SELECT net_salary , payroll_month FROM tblpayroll WHERE employeeName = '$employeeName' AND payroll_month = '$AllowanceDate'";
                  $result = mysqli_query($con, $query);
                  
                        if ($result) {
                            // Check if any row was returned
                    if (mysqli_num_rows($result) > 0) {
                            $row = mysqli_fetch_assoc($result);
                            $updateNet_Salary = $row['net_salary']; // Assign the fetched net_salary to the variable
                        // Calculate total tax
                        $totalAllowance = $DrinksAllowance + $FoodAllowance;
                    
                           // Subtract updateNet_Salary from totalTax
                           $adjustedAllowance =  $updateNet_Salary - $totalAllowance ;

                        // Prepare the update query
                        $updateAllowanceQuery = "UPDATE tblpayroll 
                                        SET allowances = ' $totalAllowance', net_salary = '$adjustedAllowance' 
                                        WHERE payroll_month = '$AllowanceDate' "; 
                    
                        // Execute the update query
                        if (!mysqli_query($con, $updateAllowanceQuery)) {
                            echo "Error updating Allowances: " . mysqli_error($con);
                        } else {
                            echo "<script>alert('Allowances updated successfully.tax $totalAllowance and net_salary $adjustedAllowance the previous salary is  $updateNet_Salary');</script>";
                            // Optionally, redirect or refresh the page
                        }
                    } else {
                        // If no rows were returned, the employee does not exist for the specified payroll date
                        echo "<script>alert('The payroll for this employee does not exist for the specified date.');</script>";
                    }
                } else {
                    echo "Error fetching net salary: " . mysqli_error($con);
                }
            }


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
<link href="css/newstyle.css" rel="stylesheet">
<!-- DATATABLES -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
<!-- ICONS-->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<!-- APEXCHARTS -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

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
				<div class="forms">

                    <!-- Green Button -->
                   	<!-- GREEN BUTTON -->
							<button id="openModalBtn" class="green-button">
								<i class="fa-solid fa-money-check"></i> Add Payroll
							</button>
                            <button id="openModalBtnTax" class="red-button">
								<i class="fas fa-funnel-dollar"></i> Add Taxes
							</button>
                            <button id="openModalBtnAllowances" class="yellow-button">
								<i class="fa-solid fa-user-minus"></i> Add Allowances
							</button>
                                <section class="payroll-management">
                               
                        <h2>Payroll Management</h2>
                        <table id="leaveTables"  style="width:100%;">
                            <thead>
                                <tr>
                                    <th>Employee</th>
                                    <th>Role</th>
                                    <th>Basic Salary</th>
                                    <th>Commission</th>
                                    <th>Allowances</th>
                                    <th>Tax</th>
                                    <th>Net Salary</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                        
                            <tbody>
                            <?php
                                    $payrollList = mysqli_query($con, "
                                        SELECT 
                                            tblpayroll.payrollcount, 
                                            tblpayroll.employeeName, 
                                            tblpayroll.basic_salary, 
                                            tblpayroll.commission, 
                                            tblpayroll.allowances, 
                                            tblpayroll.tax, 
                                            tblpayroll.net_salary, 
                                            tblpayroll.payroll_month,
                                            tbladmin.role 
                                        FROM tblpayroll 
                                        JOIN tbladmin ON tblpayroll.employeeName = tbladmin.AdminName 
                                        WHERE (tblpayroll.employeeName, tblpayroll.payroll_month) IN (
                                            SELECT employeeName, MAX(payroll_month) 
                                            FROM tblpayroll 
                                            GROUP BY employeeName
                                        )
                                        ORDER BY tblpayroll.payroll_month
                                    ");
                                    while ($row=mysqli_fetch_array($payrollList)) {
                                 ?>
                                <tr>
                                    <td><?php  echo $row['employeeName'];?></td>
                                    <td><?php  echo $row['role'];?></td>
                                    <td>₱<?php  echo number_format($row['basic_salary'], 2);?></td>
                                    <td>₱<?php  echo number_format($row['commission'], 2);?></td>
                                    <td><span class="tax">₱<?php  echo number_format($row['allowances'], 2);?></span></td>
                                    <td><span class="tax">₱<?php  echo number_format($row['tax'], 2);?></span></td>
                                    <td><span class="taxes">₱<?php  echo number_format($row['net_salary'], 2);?></span></td>
                                    <td><?php  echo $row['payroll_month'];?></td>
                                    
                                    <td>
                                    <i class="payslipBtn money-btn fa-solid fa-receipt" aria-hidden="true" 
                                    data-employee-id="<?php echo $row['employeeName']; ?>" id="employeeName">
                                    </i>
                                    <i class="edit-btn fa fa-pencil-square-o" aria-hidden="true"></i>
                                    <!--<i class="delete-btn fa fa-trash-o" aria-hidden="true"></i>---->
                                    </td>
                                </tr>
                                <?php }?>
                            </tbody>
                        </table>
                       
                </section>

                <div class="row" style="display: flex; margin-top:0px;">
				   <div class="col-left" style="flex: 1 0 50%; max-width: 100%; margin-right: 15px;">
                        <div class="tables" style="width:100%;"> 
                    <section class="payroll-management">    
                            <?php
                                $formType = 'form2'; // Set to form1 for this context
                                include 'employee-payroll-form.php'; 
                            ?>
                </section>
                            
                        </div>
                   </div>
                <!-- DOUGHNUT DISPLAY -->
                   <div class="col-right" style="flex: 2; ">
                        <div class="tables" style="width: 100%;">
                            <section class="payroll-management"> 
                            <div id="salaryDoughnutChart"></div>
                             </section>
                            
                        </div>
                    </div>

                </div>

<!-- ADD PayRoll Modal -->
<div id="payrollModal" class="modal">
    <div class="modal-content">
        <span class="close-btn">&times;</span>
        <h2>Add New Payroll</h2>
        <div class="modal-body">
            <?php
            $formType = 'form1'; // Set to form1 for this context
            include 'employee-payroll-form.php'; ?>
        </div>
    </div>
</div>
<!-- ADD Taxes Modal -->
<div id="taxModal" class="modal">
    <div class="modal-content">
        <span class="close-btn">&times;</span>
        <h2>Add Taxes</h2>
        <div class="modal-body">
            <?php
            $formType = 'form3'; // Set to form1 for this context
            include 'employee-payroll-form.php'; ?>
        </div>
    </div>
</div>

<!-- ADD Allowance Modal -->
<div id="allowanceModal" class="modal">
    <div class="modal-content">
        <span class="close-btn">&times;</span>
        <h2>Add Allowances</h2>
        <div class="modal-body">
            <?php
            $formType = 'form5'; // Set to form1 for this context
            include 'employee-payroll-form.php'; ?>
        </div>
    </div>
</div>



                <!-- PAYSLIP Modal -->
                <div id="payslipModal" class="modal">
                <?php

                            $formType = 'form4'; 

                            include 'employee-payroll-form.php'; ?>
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
		/////////////FOR DATATABLE SETTINGS
		$(document).ready(function() {
    $('#leaveTables').DataTable({
        "paging": true,
        "searching": true,
        "lengthChange": true,
        "info": true,
        "autoWidth": false,
        "pageLength": 3 , // Set the number of entries to show to 3
		"lengthChange": false
		
    });
});
$(document).ready(function() {
    $('#leaveTable').DataTable({
        "paging": true,
        "searching": true,
        "lengthChange": true,
        "info": true,
        "autoWidth": false,
        "pageLength": 5 , // Set the number of entries to show to 3
		"lengthChange": false
		
    });
});


 //// MODAL
// Function to open the modal
function openModal(modalId) {
    document.getElementById(modalId).style.display = 'block';
}

// Event listeners for each button
document.getElementById('openModalBtn').addEventListener('click', function() {
    openModal('payrollModal');
});

document.getElementById('openModalBtnTax').addEventListener('click', function() {
    openModal('taxModal'); // Assuming you have a modal with id 'taxModal'
});

document.getElementById('openModalBtnAllowances').addEventListener('click', function() {
    openModal('allowanceModal'); // Assuming you have a modal with id 'allowancesModal'
});

// Close modal when clicking the close button
document.querySelectorAll('.close-btn').forEach(function(closeBtn) {
    closeBtn.addEventListener('click', function() {
        const modal = closeBtn.closest('.modal'); // Assuming each modal has a class 'modal'
        modal.style.display = 'none';
    });
});

// Close modal when clicking outside of it
window.onclick = function(event) {
    const modals = document.querySelectorAll('.modal'); // Assuming each modal has a class 'modal'
    modals.forEach(function(modal) {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    });
};

// Variable to store the selected employee name
let selectedEmployeeName = '';

//PAYSLIP MODAL
document.querySelectorAll('.payslipBtn').forEach(function(button) {
    button.addEventListener('click', function() {
        selectedEmployeeName = this.getAttribute('data-employee-id'); // Get the employee name
        document.getElementById('payslipModal').style.display = 'block';
        // Construct the URL (replace 'your-url-here' with your actual URL)
        var url = 'employee-payroll.php?employeeName=' + encodeURIComponent(selectedEmployeeName) + '&showModal=true';
        
        // Redirect to the new URL
        window.location.href = url;
    });


});
document.querySelector('.payslipclose-btn').addEventListener('click', function() {
    document.getElementById('payslipModal').style.display = 'none';
});

// Close modal when clicking outside of it
window.onclick = function(event) {
    const modal = document.getElementById('payslipModal');
    if (event.target === modal) {
        modal.style.display = 'none';
    }
};


    // Function to get query parameters from the URL
    function getQueryParam(param) {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get(param);
    }

    // Check if the showModal parameter is present
    if (getQueryParam('showModal') === 'true') {
        selectedEmployeeName = getQueryParam('employeeName'); // Get employee name from URL
        document.getElementById('employeeIdDisplay').textContent = selectedEmployeeName; 
        document.getElementById('payslipModal').style.display = 'block'; // Assuming your modal has this ID
    }

//////////////////////////
let selectedDay = '';

// Add event listener for day selection
document.querySelector('.dayDropdown').addEventListener('change', function(){
    selectedDay = this.value; // Get the selected day
});

// Step 1: Populate the Year Dropdown
const yearDropdown = document.getElementById("yearDropdown");
const currentYear = new Date().getFullYear();
for (let i = 0; i < 10; i++) {
    const option = document.createElement("option");
    option.value = currentYear + i;
    option.text = currentYear + i;
    yearDropdown.add(option);
}

// Step 2: Generate Month Grid with Dynamic Year
const monthGrid = document.querySelector(".month-grid");
const months = [
    "January", "February", "March", "April", "May", "June", 
    "July", "August", "September", "October", "November", "December"
];

function populateMonthGrid(selectedYear) {
    // Clear any existing months
    monthGrid.innerHTML = "";
 

    // Populate months with the selected year
    months.forEach(month => {
        const monthElement = document.createElement("div");
        monthElement.className = "month";
        monthElement.textContent = `${month} ${selectedYear}`;
        monthElement.setAttribute("data-month", month);
        monthGrid.appendChild(monthElement);
        // Add click event to each month
        monthElement.addEventListener("click", () => {
            // Redirect to employee-payslip.php with selected month and year
            const selectedMonth = monthElement.getAttribute("data-month");
            const url = `employee-payslip.php?month=${selectedMonth}&year=${selectedYear}&day=${selectedDay}&employeeName=${encodeURIComponent(selectedEmployeeName)}`;
            window.location.href = url;
        });
    });
}

// Initialize month grid with the current year
populateMonthGrid(currentYear);

// Update month grid when year dropdown is changed
yearDropdown.addEventListener("change", (event) => {
    const selectedYear = event.target.value;
    populateMonthGrid(selectedYear);
});

/////////////DOUGHNUT
const labels = <?php echo $labels; ?>;
const data = <?php echo $data; ?>;

const options = {
    chart: {
        type: 'donut',
        height: 350
    },
    labels: labels,
    series: data,
    title: {
        text: 'Net Salary of Employees for this Month',
        align: 'center'
    },
    plotOptions: {
        pie: {
            donut: {
                size: '60%'
            }
        }
    },
    colors: ['#FF4560', '#008FFB', '#00E396', '#775DD0'],
    legend: {
        position: 'bottom', // Set legend position to bottom
        horizontalAlign: 'center' // Center the legend
    },
    responsive: [{
        breakpoint: 480,
        options: {
            chart: {
                height: 200
            },
            legend: {
                position: 'bottom',
                horizontalAlign: 'center' // Center the legend for smaller screens too
            }
        }
    }]
};

    const chart = new ApexCharts(document.querySelector("#salaryDoughnutChart"), options);
    chart.render();

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