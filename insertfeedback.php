<?php 
ob_start(); 
require 'C:\xampp\htdocs\Aesthetic_Management_System\bpms\includes\dbconnection.php';

$view = $_POST['view'];
$name = $_POST['name'];
$comments = $_POST['comments'];
$email = $_POST['email'];
$num = $_POST['num'];
$employee = $_POST['employee']; // Get the selected AdminName
$rating = $_POST['rating'];

$ratequery = mysqli_query($con, "UPDATE tbladmin SET ratings = (ratings + '$rating') / (rating_count + 1), rating_count = rating_count + 1 WHERE AdminName = '$employee'");

$query = mysqli_query($con, "INSERT INTO `poll`(`id`, `name`, `email`, `phone`, `feedback`, `suggestions`) VALUES ('','$name','$email','$num','$view','$comments')");
echo '<script>alert("Thank You..! Your Feedback is Valuable to Us"); location.replace(document.referrer);</script>';



/*$query1 = "SELECT * FROM `staff` WHERE email = '$email' AND password = '$password'";
$result_query1 = mysqli_query($conn, $query1);
$count_query1 = mysqli_num_rows($result_query1);
/*if ($count_query1 != 0) 
	{
    header("Location: controller/staff.php");
	exit();
	} 
else {
		    $query2 = "SELECT * FROM `admin` WHERE email = '$email' AND password = '$password'";
			$result_query2 = mysqli_query($conn, $query2);
			$count_query2 = mysqli_num_rows($result_query2);
			$count_query2 = mysqli_num_rows($result_query2);
			if ($count_query2!=0) 
			{
		   		header("Location: controller/admin.php");
				exit();
		   	} 		
		   	else
		   	 {
		   	 	echo '<script>alert("Incorrect Credentials Entered"); location.replace(document.referrer);</script>';

			 }

	}

//echo $email;
//echo '</br>';
//echo $password;



ob_end_flush();*/
?>