<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['bpmsaid']==0)) {
  header('location:logout.php');
  } else{    
    if (isset($_POST['submit'])) {
    // Get form data
            $newleave_employeename = $_POST['leave-employeeName'];
            $newleave_subject = $_POST['leave_subject'];
            $newleave_dates = $_POST['leave_dates'];
            $newleave_message = $_POST['leave_message'];
            $newleave_type = $_POST['leave_type'];
            $newleave_status = 1; // default status

           // Specify the column(s) to insert into
           $insertnewleaverequest = "INSERT INTO tblleave (employeeName,leave_subject, leave_date, leave_message, leave_type, leave_status) 
            VALUES ('$newleave_employeename','$newleave_subject', '$newleave_dates', '$newleave_message', '$newleave_type', '$newleave_status')";
                
           // Check if the query was executed successfully
           if (!mysqli_query($con, $insertnewleaverequest)) {
               echo "Error: " . mysqli_error($con);
           } else {
               // Display a success message
               echo "<script>alert('EMPLOYEE REQUEST a LEAVE. Check the table');</script>"; 
               echo "<script>window.location.href='employee-leaverequest.php'</script>";
               
           }  
        }

           if($_GET['delid']){
            $sid=$_GET['delid'];
            mysqli_query($con,"delete from tblleave where leaveID ='$sid'");
            echo "<script>alert('Data Deleted');</script>";
            echo "<script>window.location.href='employee-leave.php'</script>";
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
<!-- DATATABLES -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
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
            <h3 class="title1">Leave List</h3>
            <div class="row rows" style="display: flex;">
                <div class="col-left" style="flex: 1; margin-right:20px; ">
                    <div class="tables" style="width:100%;">
                        <div class="table-responsive bs-example widget-shadow" style="border-radius: 8px;">
                        <form method="POST" action="employee-leaverequest.php">
                            <label for="leave-employeeName">Employee Name</label>
                            <select class="form-control" id="leave-employeeName" name="leave-employeeName" required="true">
                                    <?php
                                    $query = mysqli_query($con, "SELECT * FROM tbladmin");
                                    while ($row = mysqli_fetch_array($query)) {
                                        echo "<option value='" . $row['AdminName'] ."'>" . $row['AdminName'] . "</option>";
                                    }
                                    ?>
                                </select>
                               
                            <label for="leave-subject">Leave Subject</label>
                                <input type="text" id="leave-subject" name="leave_subject" placeholder="Enter leave subject" required>

                                <label for="leave-dates">Leave Dates (YYYY/MM/DD)</label>
                                <input type="text" id="leave-dates" name="leave_dates" placeholder="YYYY/MM/DD" required>
                               

                                <label for="leave-message">Leave Message</label>
                                <textarea id="leave-message" name="leave_message" placeholder="Enter your message" required></textarea>

                                <label for="leave-type">Leave Type</label>
                                <select id="leave-type" name="leave_type" required>
                                    <option value="">Please make a choice</option>
                                    <option value="sick">Sick Leave</option>
                                    <option value="casual">Casual Leave</option>
                                    <option value="vacation">Vacation Leave</option>
                                </select>

                                <button class="apply-button" type="submit" name="submit">Apply for Leave</button>
                        
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-right" style="flex: 1;">
						<div class="tables" style="width:100%;">
							<div class="table-responsive bs-example widget-shadow" style="border-radius: 8px;">
                                <div class="table-section">
                                    <table id="leaveTable"  style="width:100%;">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>SUBJECT</th>
                                                <th>DATES</th>
                                                <th>MESSAGE</th>
                                                <th>TYPE</th>
                                                <th>STATUS</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php
								$ret=mysqli_query($con,"SELECT * FROM tblleave ORDER BY leaveID DESC; ");
								$cnt=1;
								while ($row=mysqli_fetch_array($ret)) {
								?>
                                            <tr>
                                                <th scope="row"><?php echo $cnt;?></th> 
                                                <td><?php  echo $row['leave_subject'];?></td>
                                                <td><?php  echo $row['leave_date'];?></td>
                                                <td><?php  echo $row['leave_message'];?></td>
                                                <td><?php  echo $row['leave_type'];?></td>
                                                <?php if($row['leave_status'] == 1): ?>
                                                    <td class="text-center"><span class="badge badge-warning">PENDING</span></td>
                                                    <?php elseif($row['leave_status'] == 2):?>
                                                    <td class="text-center"><span class="badge badge-success">APPROVED</span></td>
                                                    <?php elseif($row['leave_status'] == 3):?>
                                                        <td class="text-center"><span class="badge badge-danger">REJECTED</span></td>
                                                <?php endif; ?>
                                            </tr>
                                            <?php $cnt=$cnt+1; }?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                    <?php if (strlen($_SESSION['bpmsaid']==1)) {?>

                            <div class=" bs-example widget-shadow" style="border-radius: 8px; height: 50%;">
                           
                            <select id="baryear-filter" onchange="updateBarChart()">
                                <?php
                                    $currentYear = date('Y');
                                    for ($i = $currentYear; $i >= $currentYear - 12; $i--) {
                                        echo "<option value='$i'>$i</option>";
                                    }
                                ?>
                            </select>
                            <?php 
                                              // RANGE BAR DATA
                        // Prepare the query to get leave data
                        $showleave = "SELECT employeeName, leave_status FROM tblleave";
                        $resultbarleave = mysqli_query($con, $showleave);

                    $dataleave = [];
                    while ($row = $resultbarleave->fetch_assoc()) {
                    $employeeName = $row['employeeName'];
                    $status = $row['leave_status'];

                    // Initialize the dataleave structure if it doesn't exist
                    if (!isset($dataleave[$employeeName])) {
                    $dataleave[$employeeName] = [
                        'Approved' => 0,
                        'Rejected' => 0,
                        'Pending' => 0,
                    ];
                    }

                    // Increment the count based on the status
                    if ($status == 1) {
                    $dataleave[$employeeName]['Pending']++;
                    } elseif ($status == 2) {
                    $dataleave[$employeeName]['Approved']++;
                    } elseif ($status == 3) {
                    $dataleave[$employeeName]['Rejected']++;
                    }
                    } ?>  
                       <div id="leaveChart" ></div>
                       <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="main-page">
            <?php if (strlen($_SESSION['bpmsaid']==1)) {?>
            <section class="payroll-management">
                               
                               <h2>Leave Requests</h2>
                               <table id="leaveTables"  style="width:100%;">
                                   <thead>
                                       <tr>
                                            <th>#</th>
                                           <th>Employee ID</th>
                                           <th>Name</th>
                                           <th>Subject</th>
                                           <th>Dates</th>
                                           <th>Message</th>
                                           <th>Type</th>
                                           <th>Status</th>
                                           <th>Actions</th>
                                       </tr>
                                   </thead>
                                   <tbody>
                                   <?php
                                           $leave = mysqli_query($con, "SELECT tblleave.*,
                                            tbladmin.AdminName,
                                             tbladmin.employeeID 
                                                FROM tblleave 
                                                JOIN tbladmin ON tblleave.employeeName = tbladmin.AdminName;
                                           "); $cnt=1;
                                           while ($row=mysqli_fetch_array($leave)) {
                                        ?>
                                  
                                       <tr>
                                           <th scope="row"><?php echo $cnt;?></th> 
                                           <td><?php  echo $row['employeeID'];?></td>
                                           <td><?php  echo $row['employeeName'];?></td>
                                           <td><?php  echo $row['leave_subject'];?></td>
                                           <td><?php  echo $row['leave_date'];?></td>
                                           <td><?php  echo $row['leave_message'];?></span></td>
                                           <td><?php  echo $row['leave_type'];?></span></td>
                                           <?php if($row['leave_status'] == 1): ?>
                                                    <td class="text-center"><span class="badge badge-warning">PENDING</span></td>
                                                    <?php elseif($row['leave_status'] == 2):?>
                                                    <td class="text-center"><span class="badge badge-success">APPROVED</span></td>
                                                    <?php elseif($row['leave_status'] == 3):?>
                                                        <td class="text-center"><span class="badge badge-danger">REJECTED</span></td>
                                            <?php endif; ?>
                                           <td> <i  class="leaveBtn money-btn fa fa-eye" aria-hidden="true" 
                                           data-employee-id="<?php echo $row['employeeName']; ?>"
                                           data-leave-type="<?php echo $row['leave_type']; ?>" 
                                           data-leave-status="<?php echo ($row['leave_status'] == 1) ? 'pending' : 
                                           (($row['leave_status'] == 2) ? 'approved' : 'rejected'); ?>">
                                           </i>
                                     
                                        </td>
                                       </tr>
                                       <?php $cnt++; } ?>
                                   </tbody>
                               </table>
                       </section>
                       <?php }?>
                       <!-- LEAVE ACTION Modal -->
                    <div id="leaveAction" class="modal">
                        <div class="modal-content">
                            <span class="payslipclose-btn">&times;</span>
                            <div style="display: flex; align-items: center;">
                            <h2>Leave Details</h2>
                                <p>Employee Name: <span id="employeeIdDisplay"></span></p>
                                <p>Leave Type: <span id="leaveTypeDisplay"></span></p>
                                <p>Leave Status: <span id="leaveStatusDisplay"></span></p>
                                <div>
                                    <button id="btnApproved" onclick="updateLeaveStatus('approved')">Approved</button>
                                    <button id="btnRejected" onclick="updateLeaveStatus('rejected')">Rejected</button>
                                </div>
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

            		/////////////FOR DATATABLE SETTINGS
		$(document).ready(function() {
    $('#leaveTable').DataTable({
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
    $('#leaveTables').DataTable({
        "paging": true,
        "searching": true,
        "lengthChange": true,
        "info": true,
        "autoWidth": false,
        "pageLength": 3, // Set the number of entries to show to 3
		"lengthChange": false
		
    });
});


          //////// MODAL FOR LEAVE ACTION
            //PAYSLIP MODAL
            document.querySelectorAll('.leaveBtn').forEach(function(button) {
         button.addEventListener('click', function() {
        selectedEmployeeName = this.getAttribute('data-employee-id'); // Get the employee name
        selectedLeaveType = this.getAttribute('data-leave-type'); // Get the leave type
        selectedLeaveStatus = this.getAttribute('data-leave-status'); // Get the leave status
        
        document.getElementById('employeeIdDisplay').textContent = selectedEmployeeName; 
        document.getElementById('leaveTypeDisplay').textContent = selectedLeaveType; 
        document.getElementById('leaveStatusDisplay').textContent = selectedLeaveStatus; 
        
        document.getElementById('leaveAction').style.display = 'block';
            });
        });
        ////////// EXIT BUTTON IN MODAL
        document.querySelector('.payslipclose-btn').addEventListener('click', function() {
            document.getElementById('leaveAction').style.display = 'none';
        });

        // Close modal when clicking outside of it
        window.onclick = function(event) {
            const modal = document.getElementById('leaveAction');
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        };
        ///////////////////////
        function updateLeaveStatus(status) {
            const employeeName = document.getElementById('employeeIdDisplay').textContent;
            const leaveType = document.getElementById('leaveTypeDisplay').textContent;

            // Send an AJAX request to update the leave status
            $.ajax({
                url: 'update_leave_status.php', // PHP script to handle the update
                type: 'POST',
                data: {
                    employeeName: employeeName,
                    leaveType: leaveType,
                    leaveStatus: status
                },
                success: function(response) {
                    // Handle the response from the server
                    alert(response); // You can show a success message or refresh the table
                    document.getElementById('leaveAction').style.display = 'none'; // Close the modal
                    location.reload(); // Optionally reload the page to see the updates
                },
                error: function() {
                    alert('Error updating leave status.'); // Handle any errors
                }
            });
        }


            ////RANGE BAR CHARTS
            function updateBarChart() {
                const year = $('#baryear-filter').val();


                if (year) {
                    const leave_date_linking = year;
                    // Log the leave_date_linking to the console
                    console.log("Leave Date Linking:", leave_date_linking);
                    // Use jQuery AJAX to send a request to the server
                    $.ajax({
                        url: 'ajax.php',
                        type: 'GET',
                        data: { leave_date_linking: leave_date_linking },
                        success: function(response) {
                const data = JSON.parse(response);
                
                // Prepare arrays to hold the chart data
                const employeeNames = Object.keys(data);
                const approvedCounts = [];
                const pendingCounts = [];
                const rejectedCounts = [];

                // Loop through each employee to get their counts
                employeeNames.forEach(employee => {
                    approvedCounts.push(data[employee].approved);
                    pendingCounts.push(data[employee].pending);
                    rejectedCounts.push(data[employee].rejected);
                });

                // Debugging output
                console.log("Employee Names:", employeeNames);
                console.log("Approved Counts:", approvedCounts);
                console.log("Pending Counts:", pendingCounts);
                console.log("Rejected Counts:", rejectedCounts);

                // Update the bar chart with the new data
                leavebarchart.updateOptions({
                    series: [
                        {
                            name: 'Approved',
                            data: approvedCounts // Data for approved leave requests
                        },
                        {
                            name: 'Pending',
                            data: pendingCounts // Data for pending leave requests
                        },
                        {
                            name: 'Rejected',
                            data: rejectedCounts // Data for rejected leave requests
                        }
                    ],
                    xaxis: { 
                        categories: employeeNames // Set the employee names as categories on the x-axis
                    },
                    colors: ['#28a745', '#ffc107', '#dc3545'] 
                });
            },
                        error: function(xhr, status, error) {
                            console.error("AJAX Error: " + status + error);
                        }
                    });
                }
            }
            // Call updateBarChart initially to load the data
updateBarChart();
// Set an interval to update the chart every 5 minutes (300000 milliseconds)
setInterval(updateBarChart, 300000);
// Assume you have a function to update the chart
function updateChart(data) {
    // Logic to update the chart with the new data
    console.log("Chart data updated:", data);
}

// Add event listeners to the dropdowns using jQuery
$('#baryear-filter').on('change', updateBarChart);


const employeeData = <?php echo json_encode($dataleave); ?>;

const seriesData = [];
const categories = Object.keys(employeeData);

// Prepare series data for the chart
seriesData.push({
    name: 'Approved',
    data: categories.map(employee => employeeData[employee]['Approved'])
});
seriesData.push({
    name: 'Rejected',
    data: categories.map(employee => employeeData[employee]['Rejected'])
});
seriesData.push({
    name: 'Pending',
    data: categories.map(employee => employeeData[employee]['Pending'])
});

console.log('Employee Data:', employeeData);
console.log('Series Data:', seriesData);
const maxRequests = seriesData.length > 0 ? Math.max(...seriesData.map(emp => Math.max(...emp.data))) : 0;
var options = {
        chart: {
            type: 'bar',
            height: 300,
        
            stacked: true,
            toolbar: {
                show: false
            }
        },
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: '55%',
                endingShape: 'rounded'
            }
        },
        dataLabels: {
            enabled: false
        },
        series: seriesData,
        xaxis: {
            categories: categories,
            title: {
                text: 'Leave Status'
            }
        },
        yaxis: {
            title: {
                text: 'Number of Requests'
            },
            min: 0,
            max: maxRequests + 1 
        },
        tooltip: {
            shared: true,
            intersect: false,
        },
        legend: {
            position: 'top',
            horizontalAlign: 'left',
            floating: false,
            offsetY: 0,
            offsetX: 0
        }
    };

    var leavebarchart = new ApexCharts(document.querySelector("#leaveChart"), options);
    leavebarchart.render();
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