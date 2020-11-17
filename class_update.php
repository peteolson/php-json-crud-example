<?php

$cfgProgDir =  'phpSecurePages/';
include($cfgProgDir . "secure.php");

function load_form($id) {
global $id;

$event_data = file_get_contents('events.json');
$event_data = json_decode($event_data);
$event_data_row = array_filter($event_data, function($arr) { global $id; return $arr->event_id == $id;});

echo '
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">

  <form id="class_signup" method="post" action="class_update.php?class_update='.$id.'">
<!--`event_id`, `event_date`, `event_begin_time`, `event_end_time`, `event_name`, `event_space`, 
 `event_signed_up`, `event_thumbnail`-->	  
    <label for="description">Description</label> 
	 <input type="text" id="description" name="description" value="'.$event_data_row[key($event_data_row)]->event_name.'">
	 
	
	 <label for="date">Date</label>
	 <br>
	 
    <input type="date" id="date" name="date" value="'.$event_data_row[key($event_data_row)]->event_date.'">
	<br>
	<br>
	
	 <label for="start_time">Start Time</label>
	 <br>
    <input type="time" id="start_time" name="start_time" value="'.$event_data_row[key($event_data_row)]->event_begin_time.'">
	<br>
	<br>

    <label for="end_time">End Time</label>
	<br>
    <input type="time" id="end_time" name="end_time" value="'.$event_data_row[key($event_data_row)]->event_end_time.'">
	<br>
	<br>
    
    <label for="class_size">Class Size</label>
    <input size="3" type="text" id="class_size" name="class_size" value="'.$event_data_row[key($event_data_row)]->event_space.'">
    
    <label for="signed_up">Signed Up</label>
    <input size="3" type="text" id="signed_up" name="signed_up" value="'.$event_data_row[key($event_data_row)]->event_signed_up.'">

	
    <input type="submit" value="Update">
    
  </form>
 
</div>

</body>
</html>
';

}

function class_update($id) {
	
	// description date start_time end_time class_size signed_up
	// event_id event_date event_begin_time event_end_time event_name event_space event_signed_up 
	
	$data = file_get_contents('events.json');
	$event_data = json_decode($data);

	// returns the row with the original index. Not a new row that would have an index of 0
	$event_data_row = array_filter($event_data, function($arr) { global $id; return $arr->event_id == $id;});
	
	$update_arr = array(
	'event_id' => $id,
	'event_date' => $_POST['date'],
	'event_begin_time' => $_POST['start_time'],
	'event_end_time' => $_POST['end_time'],
	'event_name' => $_POST['description'],
	'event_space' => $_POST['class_size'],
	'event_signed_up' => $event_data[key($event_data_row)]->event_signed_up
	);
	
	$event_data[key($event_data_row)] = $update_arr;
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

if ($param == "class_update") {
    class_update($id);
} 


if ($param == "load_form") {
    load_form($id);
} 
?>
