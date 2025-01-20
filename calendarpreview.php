<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
function build_calendar($month, $year) {
    $mysqli = new mysqli('localhost', 'root', '', 'bpmsdb');
     $stmt = $mysqli->prepare("SELECT * FROM `tblbook` WHERE MONTH(`AptDate`) =? AND YEAR(`AptDate`) =? AND `Status` = 'Selected'");
    $stmt->bind_param('ss', $month, $year);
    $bookings = array();
    if($stmt->execute()){
        $result = $stmt->get_result();
        if($result->num_rows>0){
            while($row = $result->fetch_assoc()){
                $bookings[] = date('Y-m-d', strtotime($row['AptDate']));
            }
            
            $stmt->close();
        }
    }
    
    
     $daysOfWeek = array('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday');
     $firstDayOfMonth = mktime(0,0,0, (int)$month, 1, (int)$year);
     $numberDays = date('t',$firstDayOfMonth);
     $dateComponents = getdate($firstDayOfMonth);
     $monthName = $dateComponents['month'];
     $dayOfWeek = $dateComponents['wday'];


   

	 $calendar = "<table class='table table-bordered'>";
	 $calendar .= "<center><h2>$monthName $year</h2>";
     $calendar.= "<a class='btn btn-xs btn-success' href='?month=".date('m', mktime(0, 0, 0, (int)$month-1, 1, $year))."&year=".date('Y', mktime(0, 0, 0, (int)$month-1, 1, $year))."'>Previous Month</a> ";
     $calendar.= " <a class='btn btn-xs btn-danger' href='?month=".date('m')."&year=".date('Y')."'>Current Month</a> ";
     $calendar.= "<a class='btn btn-xs btn-primary' href='?month=".date('m', mktime(0, 0, 0, (int)$month+1, 1, $year))."&year=".date('Y', mktime(0, 0, 0, (int)$month+1, 1, $year))."'>Next Month</a></center><br>";
    
   
      $calendar .= "<tr class='calendar-animation'> ";
     foreach($daysOfWeek as $day) {
          $calendar .= "<th  class='header'>$day</th>";
     } 

     $currentDay = 1;
     $calendar .= "</tr><tr>";


     if ($dayOfWeek > 0) { 
         for($k=0;$k<$dayOfWeek;$k++){
                $calendar .= "<td  class='empty'></td>"; 

         }
     }
    
     $month = str_pad($month, 2, "0", STR_PAD_LEFT);
  
     while ($currentDay <= $numberDays) {
        if ($dayOfWeek == 7) {
            $dayOfWeek = 0;
            $calendar .= "</tr><tr>";
        }
        
        $currentDayRel = str_pad($currentDay, 2, "0", STR_PAD_LEFT);
        $date = "$year-$month-$currentDayRel";
        date_default_timezone_set('Asia/Manila');
        $today = $date == date('Y-m-d') ? "today" : "";
        $totalslots = 20;
                   // Check if the current day is a Monday (1 = Monday)
                   if (date('w', strtotime($date)) == 1) {
                    // It's Monday, so show Closed
                    $calendar .= "<td class='$today'><h4>$currentDay</h4> <button class='btn btn-danger btn-xs' disabled>Closed</button>";
                } else {
                    // Count the number of bookings for the current date
                    $bookingsCount = array_count_values($bookings)[$date] ?? 0;
                    
                    if ($date < date('Y-m-d')) {
                        $calendar .= "<td><h4>$currentDay</h4> <button class='btn btn-danger btn-xs' disabled>N/A</button>";
                    } elseif ($bookingsCount >= 20) {
                        $calendar .= "<td class='$today'><h4>$currentDay</h4><h5>$bookingsCount / $totalslots slots</h5> <button class='btn btn-danger btn-xs' disabled> <span class='glyphicon glyphicon-lock'></span>  Fully Booked</button> ";
                    } elseif (in_array($date, $bookings)) {
                        $calendar .= "<td class='$today'><h4>$currentDay</h4><h5>$bookingsCount  / $totalslots slots</h5> <button class='btn btn-danger btn-xs' disabled> <span class='glyphicon glyphicon-lock'></span> Booked</button>";
                    } else {
                        $calendar .= "<td class='$today'><h4>$currentDay</h4><h5>$bookingsCount / $totalslots slots</h5> <a class='btn btn-success btn-xs' disabled> <span class='glyphicon glyphicon-ok'></span> Available</a>";
                    }
    }
            
          $calendar .="</td>";
          $currentDay++;
          $dayOfWeek++;
     }

     if ($dayOfWeek != 7) { 
        $remainingDays = 7 - $dayOfWeek;
        for($l = 0; $l < $remainingDays; $l++){
            $calendar .= "<td class='empty'></td>"; 
        }
    }
     
     $calendar .= "</tr>";
     $calendar .= "</table>";
     echo $calendar;

}
    
?>

<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <title>Online booking system</title>
</head>
<style>
    .calendar-animation {
  animation: slide-in 1.0s ease-out;
}

@keyframes slide-in {
  0% {
    transform: translateY(100%);
    opacity: 0;
  }
  100% {
    transform: translateY(0);
    opacity: 1;
  }
}
body {
        color: white; /* Set the text color for the body to white */
        text-shadow: 1px 1px 5px black; 
    }
.goto{
 text-align: center;
    justify-content: center;
    margin-top: 20px;

}

h5 {
    background-color: gray;
    width: 100%;
    border-radius: 1em;
    text-align: center;

}

/*DESIGN CALENDAR*/

@media only screen and (max-width: 760px),
        (min-device-width: 802px) and (max-device-width: 1020px) {

            /* Force table to not be like tables anymore */
            table, thead, tbody, th, td, tr {
                display: block;
              
            }
            
            

            .empty {
                display: none;
            }

            /* Hide table headers (but not display: none;, for accessibility) */
            th {
                position: absolute;
                top: -9999px;
                left: -9999px;
            }

            tr {
                border: 3px solid #ccc;
                
            }

            td {
                /* Behave  like a "row" */
                border: none;
                border-bottom: 1px solid #eee;
                position: relative;
                padding-left: 50%;
             
            }


            /*
		Label the data
		*/
            td:nth-of-type(1):before {
                content: "Sunday";

            }
            td:nth-of-type(2):before {
                content: "Monday";
            }
            td:nth-of-type(3):before {
                content: "Tuesday";
            }
            td:nth-of-type(4):before {
                content: "Wednesday";
            }
            td:nth-of-type(5):before {
                content: "Thursday";
            }
            td:nth-of-type(6):before {
                content: "Friday";
            }
            td:nth-of-type(7):before {
                content: "Saturday";
                
            }


        }

        /* Smartphones (portrait and landscape) ----------- */

        @media only screen and (min-device-width: 320px) and (max-device-width: 480px) {
            body {
                padding: 0;
                margin: 0;
                
            }
        }

        /* iPads (portrait and landscape) ----------- */

        @media only screen and (min-device-width: 802px) and (max-device-width: 1020px) {
            body {
                width: 495px;
            }
        }

        @media (min-width:641px) {
            table {
                table-layout: fixed;
            }
            td {
                width: 33%;
            }
        }
        
        .row{
            margin-top: 20px;
        }
        
        .today{
            background-color: #FFC482;
            
        }
        #calendar-container {
    background-image: url('assets/images/basicblur.jpg');
    background-size: cover; /* This will ensure the image covers the entire container */
    background-position: center; /* This will center the image */
    background-repeat: no-repeat; /* This will prevent the image from repeating */
  

}
  
</style>
<body>
<div id="calendar-container" class="container alert alert-default" >
            <div class="row">
                <div class="col-md-12">
                    
            

                        <div clas="goto">
                        
                        
                        </div>
                        
                        
						<div id="calendar">
                        <?php
                            $dateComponents = getdate();
                            if(isset($_GET['month']) && isset($_GET['year'])){
                                $month = $_GET['month'];
                                $year = $_GET['year'];
                            }else{
                                $month = (int)$dateComponents['mon'];
                                $year = (int)$dateComponents['year'];
                            }
                            echo build_calendar($month, $year);
                        ?>
                     </div>
                </div>
            </div>
        </div>
<script>

    let btnBack =document.querySelector('.btn')

    btnBack.addEventListener('click', () => {
        window.history.back();
    })
</script> 
</body>

</html>
