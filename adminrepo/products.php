<?php
session_start();
// Check if the session variables are set and assign them to variables
$serviceID = isset($_SESSION['newServiceID']) ? $_SESSION['newServiceID'] : '';
$productID = isset($_SESSION['newProductID']) ? $_SESSION['newProductID'] : '';
// Clear the session variables after retrieving them
unset($_SESSION['newServiceID']);
unset($_SESSION['newProductID']);

error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['bpmsaid']==0)) {
  header('location:logout.php');
  } else{

if($_GET['delid']){
$pid=$_GET['delid'];
mysqli_query($con,"delete from tblinventory where prodID ='$pid'");
echo "<script>alert('Data Deleted');</script>";
echo "<script>window.location.href='products.php'</script>";
          }

if (isset($_POST['submit'])) {

    // Get the new data from the input fields
    $newINSERTproductID = $_POST['INSERTproductID'];
    $newINSERTproductname = $_POST['INSERTproductname'];
	$newINSERTserviceID = $_POST['INSERTserviceID'];
    $newINSERTpricestock = $_POST['INSERTpricestock'];
        // Specify the column(s) to insert into
        $insertnewproduct = "INSERT INTO tblinventory (prodID, product_name, category_id, product_price) VALUES (' $newINSERTproductID ','$newINSERTproductname' ,'$newINSERTserviceID ','$newINSERTpricestock')";

        // Check if the query was executed successfully
        if (!mysqli_query($con, $insertnewproduct)) {
            echo "Error: " . mysqli_error($con);
        } else {
            // Display a success message
            echo "<script>alert('PRODUCT has been added in the table.');</script>"; 
			echo "<script>window.location.href='products.php'</script>";
            
        }  }
          
  ?>

<!DOCTYPE html>
<html>



<head>
<title>BPMS || Inventory List</title>

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
				<div class="tables">
                <div class="form-grids row widget-shadow" data-example-id="basic-forms"> 
						<div class="form-title">
							<h4>ADD PRODUCT:</h4>
						</div>
						<div class="form-body">
							<form method="post" enctype="multipart/form-data">
								<p style="font-size:16px; color:red" align="center"> <?php if($msg){
                                echo $msg;
                            }  ?> </p>
                            <div class="form-group"> 
                                <label for="InsertserviceID">Service ID</label> 
								<select class="form-control" id="INSERTserviceID" name="INSERTserviceID" required="true">
                                    <?php
                                    $query = mysqli_query($con, "SELECT * FROM tblservices WHERE serviceID ");
                                    while ($row = mysqli_fetch_array($query)) {
										$selected = ($row['serviceID'] == $serviceID) ? 'selected' : '';
										echo "<option value='" . $row['serviceID'] . "' $selected>" . $row['serviceID'] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group"> 
                                <label for="INSERTproductID">New_Product_ID</label> 
								<select class="form-control" id="INSERTproductID" name="INSERTproductID" required="true">
                                    <?php
                                    $query = mysqli_query($con, "SELECT productID FROM tblsupplier ");
                                    while ($row = mysqli_fetch_array($query)) {
										$selected = ($row['productID'] == $productID) ? 'selected' : '';
										echo "<option value='" . $row['productID'] . "' $selected>" . $row['productID'] . "</option>";
									}
                                    ?>
                                </select>
                            </div>
							 <div class="form-group"> 
                                <label for="INSERTproductname">Product Name</label> 
                                <input type="text" class="form-control" id="INSERTproductname" name="INSERTproductname"  value="" required="true"> 
                            </div>
                            <div class="form-group"> 
                                <label for="INSERTpricestock">PricePerStock</label> 
                                <input type="text" class="form-control" id="INSERTpricestock" name="INSERTpricestock"  value="" required="true"></input> 
                            </div>
							  <button type="submit" name="submit" class="btn btn-primary">Insert</button> 
                            </form> 
						</div>
						
					</div>

                    <div class="table-responsive bs-example widget-shadow">
						<h4>Products:</h4>

                    <table class="table table-bordered"  id="leaveTable"  style="width:100%;"> 
						<thead> 
							<tr> 
								<th>#</th> 
								<th>Product Name</th> 
								<th>In/Out Stocks</th> 
								<th>PricePerStock</th>
								<th>Action</th> 
							</tr> 
						</thead> 
						<tbody>
                                    <?php
                                    $pet=mysqli_query($con,"select * from  tblinventory");
                                    $cnt=1;
                                    while ($row=mysqli_fetch_array($pet)) {

                                    ?>

						 <tr> 
							<th scope="row"><?php echo $cnt;?></th> 
						 <td><?php  echo $row['product_name'];?></td> 
						 <td><?php  echo $row['out_stocks'];?> / <?php  echo $row['In_stocks'];?></td>
						 <td><?php  echo $row['product_price'];?></td> 
						 <td>
						 	<a href="products-addstocks.php?addid=<?php echo $row['prodID'];?>" class="btn btn-primary">Add</a>
						 	<a href="products.php?delid=<?php echo $row['prodID'];?>" class="btn btn-danger" onClick="return confirm('Are you sure you want to delete?')">Delete</a>
						</td> 
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