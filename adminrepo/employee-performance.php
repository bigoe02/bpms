<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['bpmsaid']==0)) {
  header('location:logout.php');
  } else{     
	if (isset($_GET['viewid'])) {
		$viewid = $_GET['viewid'];
		////////FOR DOUGHNUT DATA

		$doughquery = "SELECT commission, payroll_month FROM tblpayroll WHERE employeeID = $viewid  ORDER BY payroll_month";
		$result = mysqli_query($con, $doughquery);
		$payrollData = array();
		while ($row = mysqli_fetch_assoc($result)) {
			$payrollData[] = $row;
		}

		$selectedYear = isset($_GET['year']) ? intval($_GET['year']) : date('Y'); 

		$months = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
		$employee_netSalary = array_fill(0, 12, 0);
	
		$barquery = "SELECT net_salary AS salary, MONTH(payroll_month) AS month 
              FROM tblpayroll 
              WHERE employeeID = $viewid AND YEAR(payroll_month) = $selectedYear 
              ORDER BY payroll_month";
		$result = mysqli_query($con, $barquery);
		
		while ($row = $result->fetch_assoc()) {
			$month_index = intval($row['month']-1); // MySQL MONTH function returns 1 for January, but array index starts from 0
			$employee_netSalary[$month_index] = intval($row['salary']);
		}

		// Query to fetch the employee name
		$nameQuery = "SELECT AdminName FROM tbladmin WHERE employeeID = $viewid";
		$nameResult = mysqli_query($con, $nameQuery);
		$employeeName = '';

		if ($row = mysqli_fetch_assoc($nameResult)) {
			$employeeName = $row['AdminName'];
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
<!-- CUSTOM -->
<link href="css/newstyle.css" rel="stylesheet">
<link href="css/custom.css" rel="stylesheet">
<link href="css/newcustom.css" rel="stylesheet">

<!-- DATATABLES -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<!-- APEX CHARTS -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
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
				<div class="forms">
				<div class="table-responsive bs-example widget-shadow" style="background-color: #00000000;" >

				<div class="card-container-performance">
				<?php
				if (isset($_GET['viewid'])) {
					$viewid = $_GET['viewid'];
					////////FOR DOUGHNUT DATA
			
					$logquery = "SELECT * FROM tbladmin  WHERE employeeID = $viewid GROUP BY employeeID";
					$result = mysqli_query($con, $logquery);
					while ($row = mysqli_fetch_assoc($result)) {
				?>
					<div class="card">
					<section class="work-logs">
					<h2>WORK LOG (<?php  echo $row['role'];?>)</h2>
					<p><?php echo date('F d, Y (l)'); ?></p>
					<div class="work-time" id="current-time"></div>
					<?php }}?>
					<p>Work Time</p>


					<?php
				if (isset($_GET['viewid'])) {
					$viewid = $_GET['viewid'];
					
			
					$logquery = "SELECT punchIN, punchOUT FROM tblattendance 
                  WHERE employeeID = $viewid 
                  AND (signIn = CURDATE() OR signOut = CURDATE()) 
                  GROUP BY employeeID";

					$result = mysqli_query($con, $logquery);
					while ($row = mysqli_fetch_assoc($result)) {

				?>
					<div class="times">
						<p class="clock-in-time">Clock In Time: </p><span> <?php 
							echo ($row['punchIN'] === '00:00:00') ? 'UNAVAILABLE' : date('h:i:s A', strtotime($row['punchIN']));
							?></span>
						<p class="clock-out-time">Clock Out Time: </p><span><?php 
							echo ($row['punchOUT'] === '00:00:00') ? 'N/A' : date('h:i:s A', strtotime($row['punchOUT']));
							?></span>
					</div>
					<?php }}?>
					</section>
					</div>
					
		<!-- RATINGS FOR EMPLOYEE -->
					<div class="card">
					<section id="ratings">
        <div class="ratings-summary">
            <div class="rating-value">
			<?php
			  $rating_average = 0; 
			  $rating_count = 0;
				if (isset($_GET['viewid'])) {
					$viewid = $_GET['viewid']; 
			$ratequery = "SELECT * FROM tbladmin WHERE employeeID = $viewid ";
			$rateresult = mysqli_query($con, $ratequery);
			while ($row = mysqli_fetch_assoc($rateresult)) {
				$rating = round($row['ratings'], 1);
				$rating_count = $row['rating_count'];
				if ($rating_count > 0) {
					$rating = round($row['ratings'], 1);
					$rating_average = round($rating / $rating_count, 1);
				} else {
					$rating_average = 0; // Set to 0 if there are no ratings
				}
					?>

					
                <h1><?php echo $rating_average ?><span>/5</span></h1>
                <div class="stars">
                   <?php  for ($i = 0; $i < 5; $i++) {
        if ($i < $rating_average ) {
            echo "<span>★</span>";
        } else {
            echo "<span>☆</span>";
        }
    }?>
                </div>
                <p><?php echo $rating_count; ?> Completed requests</p>
				<?php }}?>
            </div>
        </div>
    </section>
	</div>
					<div class="card">
						<?php
					if (isset($_GET['viewid'])) {
					$viewid = $_GET['viewid'];
					
			
					$logquery = "SELECT * FROM tbladmin  WHERE employeeID = $viewid";
					$result = mysqli_query($con, $logquery);
					while ($row = mysqli_fetch_assoc($result)) {
				?>
					<img src="images/imageEmployee/<?php echo htmlspecialchars($row['imageEmployee']); ?>" class="card-image">
					
						<div class="card-value"><?php  echo $row['AdminName'];?></div>
						<div class="card-subtitle"><?php  echo $row['employeeID'];?></div>
				
					</div>
					<?php }}?>
    			</div>

				<div class="row" style="display: flex; margin-top:0px;">
							<div class="col-left" style="flex: 1 0 50%; max-width: 100%; margin-right: 15px;">
									<div class="tables" style="width:100%;"> 
								<section class="payroll-management">    
										<?php
											$formType = 'form6'; // Set to form1 for this context
											include 'employee-payroll-form.php'; 
										?>
							</section>
							<br>
							<button type="button" class="btns btns--green "onclick="openModal()" style="width: 40%; ">
								<span class="btns__txt fa-solid fa-file-invoice-dollar"> My Payslip</span>
								<i class="btns__bg " aria-hidden="true"></i>
								<i class="btns__bg" aria-hidden="true"></i>
								<i class="btns__bg" aria-hidden="true"></i>
								<i class="btns__bg" aria-hidden="true"></i>
							</button>
							<!-- Modal Structure -->
<div id="payslipModal"class="modal" style="display:none;">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
		<!-- DAY -->
        <label for="Day"style="margin-right: 5px;">Day</label>
            <select id="yearDropdown" class="dayDropdown">
                <option value="">Select Day</option>
                <?php for ($day = 1; $day <= 31; $day++): ?>
                    <option value="<?php echo $day; ?>"><?php echo $day; ?></option>
                <?php endfor; ?>
            </select>
            <!-- YEAR -->
            <label for="Year"style="margin-right: 5px;">Year</label>
        <select id="yearDropdown" class="yearDropdown">
            <?php
                $currentYear = date('Y');
                for ($i = $currentYear; $i >= $currentYear - 12; $i--) {
                    echo "<option value='$i'>$i</option>";
                }
            ?>
        </select>
        <!-- MONTH -->
        <h2>Select Month</h2>
        <div id="monthButtons" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px;">
    <button class="month-button" onclick="fetchPayslip(1)">January</button>
    <button class="month-button" onclick="fetchPayslip(2)">February</button>
    <button class="month-button" onclick="fetchPayslip(3)">March</button>
    <button class="month-button" onclick="fetchPayslip(4)">April</button>
    <button class="month-button" onclick="fetchPayslip(5)">May</button>
    <button class="month-button" onclick="fetchPayslip(6)">June</button>
    <button class="month-button" onclick="fetchPayslip(7)">July</button>
    <button class="month-button" onclick="fetchPayslip(8)">August</button>
    <button class="month-button" onclick="fetchPayslip(9)">September</button>
    <button class="month-button" onclick="fetchPayslip(10)">October</button>
    <button class="month-button" onclick="fetchPayslip(11)">November</button>
    <button class="month-button" onclick="fetchPayslip(12)">December</button>
</div>
<style>
	.monthButtons {
    background-color: #f0f0f0; /* Default background color */
    border: none; /* Remove default border */
    padding: 10px; /* Add some padding */
    cursor: pointer; /* Change cursor to pointer */
    transition: background-color 0.3s; /* Smooth transition for background color */
}

.month-button:hover {
    background-color: #ECB659; /* Light yellow on hover */
}
</style>
		<section class="payroll-management">
                               
                               <h2>Payroll List</h2>
                               <table id="leaveTables"  style="width:100%;">
                                   <thead>
                                       <tr>
                                           <th>Employee Name</th>
                                           <th>Employee ID</th>
                                           <th>Month of Payroll</th>
                                       </tr>
                                   </thead>
                               
                                   <tbody>
                                   <?php     
                                    // Use the employeeName in your SQL query
                                    $payrollList = mysqli_query($con, "
                                        SELECT * FROM tblpayroll WHERE employeeID = '$viewid'");
                                
                                           while ($row=mysqli_fetch_array($payrollList)) {
                                        ?>
                                       <tr>
                                           <td><?php  echo $row['employeeName'];?></td>
                                           <td><?php  echo $row['employeeID'];?></td>
                                           <td><?php  echo $row['payroll_month'];?></td>
                                           

                                       </tr>
                                       <?php }?>
                                   </tbody>
                               </table>
                              
                       </section>
    </div>
</div>
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
					
    <section class="payroll-management"> 
        <div id="employeeBarChart">
		
		</div>
		<select id="baryear-filter" onchange="updateBarChart()">
                    <?php
                        $currentYear = date('Y');
                        for ($i = $currentYear; $i >= $currentYear - 12; $i--) {
                            echo "<option value='$i'>$i</option>";
                        }

                        
                    ?>
            </select>
		
    </section>
	


					
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

			// DATABLES FOR EMPLOYEE
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
			//EMPLOYEE PAYSLIP
			function openModal() {
    document.getElementById('payslipModal').style.display = 'block';
}

// Close modal when clicking the close button
document.querySelectorAll('.close').forEach(function(closeBtn) {
    closeBtn.addEventListener('click', function() {
        const modal = closeBtn.closest('.modal'); // Assuming each modal has a class 'modal'
        modal.style.display = 'none';
    });
});
// Close modal when clicking outside of it
window.onclick = function(event) {
    const modal = document.getElementById('payslipModal');
    if (event.target === modal) {
        modal.style.display = 'none';
    }
};
	//////////////////////////
	let selectedDay = '';
// Add event listener for day selection
document.querySelector('.dayDropdown').addEventListener('change', function(){
    selectedDay = this.value; // Get the selected day
});
// Add event listener for year selection
document.querySelector('.yearDropdown').addEventListener('change', function(){
    selectedYear = this.value; // Get the selected year
});

function fetchPayslip(month) {
	const employeeName = '<?php echo $employeeName; ?>';  // Get the employee ID from PHP
    const year = selectedYear; // Use the selected year

    // Array of month names
    const monthNames = [
        "January", "February", "March", "April", "May", "June",
        "July", "August", "September", "October", "November", "December"
    ];

    // Get the name of the month
    const monthName = monthNames[month - 1]; // month is 1-indexed

    // Optionally, you can log the month name for debugging
    console.log(`Fetching payslip for ${monthName} ${year}`);

    // Redirect to the employee slip page with month name as a parameter
    window.location.href = `employee-payslip.php?employeeName=${employeeName}&month=${monthName}&year=${year}&day=${selectedDay}`;
}


	// RUNNING TIME FOR LOG
function updateTime() {
    const now = new Date();
    let hours = now.getHours();
    const minutes = String(now.getMinutes()).padStart(2, '0');
    const seconds = String(now.getSeconds()).padStart(2, '0');
    
    // Determine AM or PM
    const ampm = hours >= 12 ? 'PM' : 'AM';
    
    // Convert to 12-hour format
    hours = hours % 12; // Convert hour to 12-hour format
    hours = hours ? String(hours).padStart(2, '0') : '12'; // the hour '0' should be '12'
    
    document.getElementById('current-time').textContent = `${hours}:${minutes}:${seconds} ${ampm}`;
}

// Update the time immediately and then every second
updateTime();
setInterval(updateTime, 1000);


			/////////////DOUGHNUT
			<?php if (isset($payrollData) && count($payrollData) >= 2): ?>
const labels = ['Current Month', 'Previous Month'];
const data = [
    <?php echo $payrollData[0]['commission'] ?? 0; ?>,
    <?php echo $payrollData[1]['commission'] ?? 0; ?>
];

const options = {
    chart: {
        type: 'donut',
        height: 350
    },
    labels: labels,
    series: data,
    title: {
        text: 'Commission Over The Month',
        align: 'center'
    },
    plotOptions: {
        pie: {
            donut: {
                size: '60%'
            }
        }
    },
    colors: ['#008FFB', '#00E396'],
    legend: {
        position: 'bottom',
        horizontalAlign: 'center'
    },
    responsive: [{
        breakpoint: 480,
        options: {
            chart: {
                height: 200
            },
            legend: {
                position: 'bottom',
                horizontalAlign: 'center'
            }
        }
    }]
};

const chart = new ApexCharts(document.querySelector("#salaryDoughnutChart"), options);
chart.render();
<?php else: ?>
document.querySelector("#salaryDoughnutChart").innerHTML = "<p>No payroll data available for this employee.</p>";
<?php endif; ?>
        // Data for the bar chart

   // Function to update the bar chart based on the selected year
   function updateBarChart() {
        const selectedYear = document.getElementById('baryear-filter').value;

        // Make an AJAX request to fetch the new data based on the selected year
        $.ajax({
            url: 'ajax.php', // This should be the PHP file that returns data based on the year
            type: 'GET',
            data: { year: selectedYear, employeeID: <?php echo $viewid; ?> }, // Pass the selected year and employee ID
            success: function(response) {
                const data = JSON.parse(response); // Assuming the response is JSON
                const newData = data.salaries; // This should be the new salary data for the selected year

                // Update the bar chart with the new data
                employeeBarChart.updateOptions({
                    series: [{
                        name: 'Employee Salaries',
                        data: newData.length > 0 ? newData : [0], // Ensure at least one data point exists
                    }]
                });
            },	
            error: function(error) {
                console.error("Error fetching data:", error);
			}
        });
    }

            const barOptions = {
                chart: {
					type: 'bar',
					height: 350,
					toolbar: {
					show: true,
					},
                },
                series: [{
                    name: 'Employee Salaries',
                    data:<?php echo json_encode($employee_netSalary); ?>,
                }],
                xaxis: {
                    categories: <?php echo json_encode($months);?>,
                },
                title: {
                    text: 'Your Net_Salaries Over The Month',
                    align: 'center'
                },
				colors: ['#246dec', '#cc3c43', '#367952', '#f5b74f', '#4f35a1'],
                plotOptions: {
                    bar: {
					distributed: true,
					borderRadius: 4,
					horizontal: false,
					columnWidth: '60%',
					},
                },
				dataLabels: {
					enabled: true,
				},
				legend: {
					show: false,
				},
				yaxis: {
				title: {
				text: 'Count',
				},
  },
            };
	
            // Create the bar chart
            const employeeBarChart = new ApexCharts(document.querySelector("#employeeBarChart"), barOptions);
            employeeBarChart.render();

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