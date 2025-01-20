<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['bpmsaid']==0)) {
  header('location:logout.php');
  } else{
    if (isset($_GET['viewname'])) {
        $viewnameID = $_GET['viewname'];
        $query = mysqli_query($con, "SELECT * FROM tblservices WHERE serviceID = '$viewnameID'");
        $row = mysqli_fetch_array($query);

        $serviceName = $row['ServiceName'];
    }
  ?>
<!DOCTYPE HTML>
<html>
<head>
<title>BPMS || Manage Services</title>

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
<!-- DATATABLES -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
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
	
            <div class="form-grids row widget-shadow" data-example-id="basic-forms"> 
						<div class="form-title">
							<h4>CATEGORY NAME: <?php  echo $serviceName;?></h4>
						</div>
                <div class="form-body">
            <table class="table table-bordered" id="leaveTable"  style="width:100%;"> 
				<thead> 
					<tr> 
						<th>#</th> 
						<th>Product Name</th> 
						<th>Stocks Available</th>
						 <th>Date Delivered</th> 
						 <th>Supplier Name</th>
						</tr> 
					</thead> 
				<tbody>
                                    <?php
                                    $viewlist=mysqli_query($con," SELECT 
										tblinventory.product_name, 
										tblinventory.out_stocks, 
										added_stocks.date_added,
										tblsupplier.supplier_name
									FROM 
										tblservices
									INNER JOIN tblinventory 
										ON tblservices.serviceID = tblinventory.category_id  -- Join inventory with services by service ID
									INNER JOIN tblsupplier 
										ON tblinventory.prodID = tblsupplier.productID  -- Proper join for inventory and supplier based on productID
									
									INNER JOIN (
										SELECT 
											newstockID, 
											MAX(date_added) AS max_date_added
										FROM 
											added_stocks
										GROUP BY
											newstockID
									) added_stocks_max 
										ON tblinventory.prodID = added_stocks_max.newstockID  -- Join on the most recent stock added
									INNER JOIN added_stocks 
										ON tblinventory.prodID = added_stocks.newstockID 
										AND added_stocks.date_added = added_stocks_max.max_date_added
									WHERE 
										tblservices.ServiceName = '$serviceName';
                                        ");
                                    $cnt=1;
                                    while ($row=mysqli_fetch_array($viewlist)) {

                                    ?>

						 <tr> <th scope="row"><?php echo $cnt;?></th> 
                         <td><?php  echo $row['product_name'];?></td> 
                         <td><?php  echo $row['out_stocks'];?></td>
						 <td><?php  echo $row['date_added'];?></td>
                         <td><?php  echo $row['supplier_name'];?></td>
                         </tr>   
                            <?php $cnt=$cnt+1; }?>
                        </tbody> 
                    </table>
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

			$(document).ready(function() {
    $('#leaveTable').DataTable({
        "paging": true,
        "searching": true,
        "lengthChange": true,
        "info": true,
        "autoWidth": false
    });
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