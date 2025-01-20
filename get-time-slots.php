<?php
session_start();
include('includes/dbconnection.php');

// Query to get the count of appointments for each time slot
$say = $_POST['date'];
$query = mysqli_query($con, "SELECT AptTime, COUNT(*) as count FROM tblbook WHERE AptDate = '$say' AND Status ='Selected' GROUP BY AptTime");

// Initialize an array to store the count of appointments for each time slot
$timeSlots = array();

// Loop through the query results and populate the array
while ($row = mysqli_fetch_array($query)) {
    $timeSlots[$row['AptTime']] = $row['count'];
}

// Define the time slots and their corresponding labels
$timeSlotsData = array(
    '11:00:00' => '|| MORNING || 11:00 a.m to 01:00 p.m',
    '13:00:00' => '|| AFTERNOON || 01:00 p.m to 03:00 p.m',
    '15:00:00' => '|| AFTERNOON || 03:00 p.m to 05:00 p.m',
    '17:00:00' => '|| EVENING || 05:00 p.m to 07:00 p.m',
);

// Define the maximum capacity of each time slot
$maxCapacity = 5;

// Generate the datalist options with remaining slots
$datalistOptions = array();
foreach ($timeSlotsData as $time => $label) {
    $remainingSlots = $maxCapacity - (isset($timeSlots[$time]) ? $timeSlots[$time] : 0);
    $datalistOptions[] = array(
        'value' => $time,
        'label' => $label . ' (' . $remainingSlots . ' slots available)'
    );
}

// Output the time slots as JSON
echo json_encode($datalistOptions);
?>