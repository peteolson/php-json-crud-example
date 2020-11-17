<?php

function show_form() {
global $id;
echo '
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
  <form id="class_signup" method="post" action="add_class.php?add_class">

    <label for="description">Description</label>
	 <input type="text" id="description" name="description">
	  <br>
	 <label for="date">Date</label>
	 <br>
	 
    <input type="date" id="date" name="date">
	<br>
	<br>
	
	 <label for="start_time">Start Time</label>
	 <br>
    <input type="time" id="start_time" name="start_time">
	<br>
	<br>

    <label for="end_time">End Time</label>
	<br>
    <input type="time" id="end_time" name="end_time" >
	<br>
	<br>
    
    <label for="class_size">Class Size</label>
    <input size="3" type="text" id="class_size" name="class_size">

    <input type="submit" value="Submit">
    
  </form>
</div>

</body>
</html>
';
}

function class_signup() {
	// insert the student data
	header('Location:index.php?student_event_list');
}

function add_class() {
	// {"event_id":"1","event_date":"2020-11-09","event_begin_time":"09:00:00","event_end_time":"09:45:00","event_name":"Not Your Granny's Chair Yoga","event_space":"5","event_signed_up":"5"},
	// description, date, start_time, end_time, class_size

	$data_array = file_get_contents('events.json');
	$data_array = json_decode($data_array, true);
	
	$add_arr = array(
	'event_id' => uniqid(time()),
	'event_date' => $_POST['date'],          
	'event_begin_time' => $_POST['start_time'],
	'event_end_time' => $_POST['end_time'],
	'event_name' => $_POST['description'],
	'event_space' => $_POST['class_size'],
	'event_signed_up'  => 0
	
	);
	
	$data_array[] = $add_arr;
 
	$data = json_encode($data_array, JSON_PRETTY_PRINT);
	file_put_contents('events.json', $data);
	
		
	header('Location:admin.php?admin_event_list');

	
}


$param = $_SERVER['QUERY_STRING'];
$arr = explode("=", $param);

if (count($arr) > 1) {
    $param = $arr[0];
    $id = $arr[1];
}


if ($param == "add_class") {
    add_class();
} 

show_form();


?>
