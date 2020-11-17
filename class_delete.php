<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">

<?php
$cfgProgDir =  'phpSecurePages/';
include($cfgProgDir . "secure.php");

function sanity_check($id) {
	global $id;
	echo "<a onClick=\"javascript: return confirm('Please confirm deletion');\" href='class_delete.php?delete=".$id."'>Delete</a>"; //use double quotes for js inside php!
	echo "&nbsp;&nbsp;&nbsp;&nbsp;";
	echo "<a href='admin.php?admin_event_list'>Cancel</a>"; 

	
}

function delete($id) {

	global $id;
	
	// description date start_time end_time class_size signed_up
	// event_id event_date event_begin_time event_end_time event_name event_space event_signed_up 
	
	$data = file_get_contents('events.json');
	$event_data = json_decode($data);

	// returns the row with the original index. Not a new row that would have an index of 0
	$event_data_row = array_filter($event_data, function($arr) { global $id; return $arr->event_id == $id;});
	
	unset($event_data[key($event_data_row)]);
	
	$data = json_encode($event_data, JSON_PRETTY_PRINT);
	
	file_put_contents('events.json', $data);
	
	header('Location:admin.php?admin_event_list');
}

$param = $_SERVER['QUERY_STRING'];
$arr = explode("=", $param);

if (count($arr) > 1) {
    $param = $arr[0];
    $id = $arr[1];
}

if ($param == "sanity_check") {
    sanity_check($id);
}

if ($param == "delete") {
     delete($id);
   
} 
 


?>
</div>

</body>
</html>
