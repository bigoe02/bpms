<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['bpmsaid']==0)) {
  header('location:logout.php');
  } else{    

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
            <section class="payroll-management">
                               
                               <h2>Payroll Management</h2>
                               <table>
                                   <thead>
                                       <tr>
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
                                   <?php
                                           $leave = mysqli_query($con, "SELECT tblleave.*,
                                            tbladmin.AdminName,
                                             tbladmin.employeeID 
                                                FROM tblleave 
                                                JOIN tbladmin ON tblleave.employeeName = tbladmin.AdminName;
                                           ");
                                           while ($row=mysqli_fetch_array($leave)) {
                                        ?>
                                   <tbody>
                                       <tr>
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
                                           <td> <i  class="leaveBtn money-btn" aria-hidden="true" 
                                           data-employee-id="<?php echo $row['employeeName']; ?>"
                                           data-leave-type="<?php echo $row['leave_type']; ?>" 
                                           data-leave-status="<?php echo ($row['leave_status'] == 1) ? 'pending' : 
                                           (($row['leave_status'] == 2) ? 'approved' : 'rejected'); ?>">
                                           View</i>
                                           <a href="employee-leave.php?delid=<?php echo $row['leaveID'];?>" class="btn btn-danger fa fa-trash-o" 
                                           onClick="return confirm('Are you sure you want to delete?')"></a></td>
                                       </tr>
                                      
                                   </tbody>
                                   <?php }?>
                               </table>
                       </section>

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