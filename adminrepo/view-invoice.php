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
<title>BPMS || View Invoice</title>

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
				<div class="tables" id="exampl">
					<h3 class="title1">Invoice Details</h3>
					
	<?php
	$invid=intval($_GET['invoiceid']);
	$ret = mysqli_query($con, "SELECT DISTINCT 
    DATE(tblinvoice.PostingDate) AS invoicedate,
    tbluser.FirstName,
    tbluser.LastName,
    tbluser.Email,
    tbluser.MobileNumber,
    tbluser.RegDate,
    tblinvoice.InvoiceAPTNumber
		FROM tblinvoice 
		JOIN tbluser ON tbluser.ID = tblinvoice.Userid 
		WHERE tblinvoice.BillingId = '$invid'");
$cnt=1;
while ($row=mysqli_fetch_array($ret)) {

?>				
				
					<div class="table-responsive bs-example widget-shadow">
						<h4>Invoice #<?php echo $invid;?></h4>
						<table class="table table-bordered" width="100%" border="1"> 
<tr>
<th colspan="6">Customer Details</th>	
</tr>
							 <tr> 
								<th>Name</th> 
								<td><?php echo $row['FirstName']?> <?php echo $row['LastName']?></td> 
								<th>Contact no.</th> 
								<td><?php echo $row['MobileNumber']?></td>
								<th>Email </th> 
								<td><?php echo $row['Email']?></td>
							</tr> 
							 <tr> 
								<th>Registration Date</th> 
								<td><?php echo $row['RegDate']?></td> 
								<th>Invoice Date</th> 
								<td ><?php echo $row['invoicedate']?></td> 
								<th>Appointment Number</th> 
								<td ><?php echo $row['InvoiceAPTNumber']?></td> 
							</tr> 
<?php }?>
</table> 
<table class="table table-bordered" width="100%" border="1"> 
<tr>
<th colspan="3">Services Details</th>	
</tr>
<tr>
<th>#</th>	
<th>Service</th>
<th>Cost</th>
</tr>

<?php
$ret=mysqli_query($con,"select 
tblservices.ServiceName,
tblservices.Cost  
	from  tblinvoice 
	join tblservices on tblservices.ID=tblinvoice.ServiceId 
	where tblinvoice.BillingId='$invid'");
$cnt=1;
while ($row=mysqli_fetch_array($ret)) {
	?>
<tr>
<th><?php echo $cnt;?></th>
<td><?php echo $row['ServiceName']?></td>	
<td><?php echo $subtotal=$row['Cost']?></td>
</tr>

<?php 
$cnt=$cnt+1;
$gtotal+=$subtotal;
} ?>


<?php
$aptid = intval($_GET['aptId']);
$ret = mysqli_query($con, "SELECT first_install, second_install, third_install FROM tblbook WHERE AptNumber ='$aptid'");
$cnt = 1;

while ($row = mysqli_fetch_array($ret)) {
    // Check if all installments have data
    if (!empty($row['first_install']) || !empty($row['second_install']) || !empty($row['third_install'])) {
        // Only display the installment rows if they have data
        if (!empty($row['first_install'])) {
            ?>
            <tr>
                <th><?php echo $cnt; ?></th>
                <td>First Installment:</td>    
                <td><?php echo $row['first_install']; ?></td>
            </tr>
            <?php
        }
        
        if (!empty($row['second_install'])) {
            ?>
            <tr>
                <th><?php echo $cnt; ?></th>
                <td>Second Installment:</td>
                <td><?php echo $row['second_install']; ?></td>
            </tr>
            <?php
        }

        if (!empty($row['third_install'])) {
            ?>
            <tr>
                <th><?php echo $cnt; ?></th>
                <td>Third Installment:</td>
                <td><?php echo $row['third_install']; ?></td>
            </tr>
            <?php
        }

        // Compute total of installments
        $total_installments = $row['first_install'] + $row['second_install'] + $row['third_install'];
        
        // Increment count
        $cnt = $cnt + 1; 
        $gtotal = $subtotal + $total_installments;
    }
		}
		?>

<tr>
<th colspan="2" style="text-align:center">Grand Total</th>
<th><?php echo $gtotal?></th>	

</tr>
</table>
  <p style="margin-top:1%"  align="center">
  <i class="fa fa-print fa-2x" style="cursor: pointer;"  OnClick="CallPrint(this.value)" ></i>
</p>

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
		</script>
	<!--scrolling js-->
	<script src="js/jquery.nicescroll.js"></script>
	<script src="js/scripts.js"></script>
	<!--//scrolling js-->
	<!-- Bootstrap Core JavaScript -->
	<script src="js/bootstrap.js"> </script>
	  <script>
function CallPrint(strid) {
var prtContent = document.getElementById("exampl");
var WinPrint = window.open('', '', 'left=0,top=0,width=800,height=900,toolbar=0,scrollbars=0,status=0');
WinPrint.document.write(prtContent.innerHTML);
WinPrint.document.close();
WinPrint.focus();
WinPrint.print();

}
</script>
</body>
</html>
<?php }  ?>