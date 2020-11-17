<?php

$id = $_GET['reserve'];

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

<h3>Class Signup</h3>

<div class="container">
  <form id="class_signup" method="post" action="class_signup.php?class_signup='.$id.'">
	  
    <label for="name">Name</label>
    <input type="text" id="name" name="name" placeholder="Your name..">


    <input type="submit" value="Submit">
    
  </form>
</div>

</body>
</html>
';
}

$event_data = file_get_contents('events.json');
$event_data = json_decode($event_data);

function row_number($id) {
	
	global $event_data;

	for ($i=0; $i < count($event_data); $i++) {
		$row = $event_data[$i];
		if ($row->event_id == $id) {
			return $i;
		}
	}
}

$event_row = $event_data[row_number($id)];


echo "</pre>";
echo '<pre><hr>';
echo '<h3>'.$event_row->event_name.'</h3>';
echo '<br>';
echo date("D - F j, Y", strtotime($event_row->event_date));
echo '<br>';
echo date('g:i A', strtotime($event_row->event_begin_time));
echo ' to ';
echo date('g:i A', strtotime($event_row->event_end_time));
echo ' EST';
echo '<br>';
echo 'Event Space: '.$event_row->event_space;
echo '</pre>';

function class_signup($id) {
	
	global $id;
	$data_array = file_get_contents('students.json');
	$data_array = json_decode($data_array, true);
	$add_arr = array(
	'students_id' => uniqid(time()),
	'students_event_id' => $id,
	'students_timestamp' => date('Y-m-d'),
	'students_name' => $_POST['name']
	);
	
	$data_array[] = $add_arr;
 
	$data = json_encode($data_array, JSON_PRETTY_PRINT);
	file_put_contents('students.json', $data);
	
	class_space($id);
}

function class_space($id) {
// increment the event_signed_up field + 1	
	global $event_row;
	
	$event_data = file_get_contents('events.json');
	$event_data = json_decode($event_data);

	$event_row = $event_data[row_number($id)];
	$event_signed_up = ($event_row->event_signed_up + 1);
	echo "class space ".$event_signed_up;
	
	$update_arr = array(
	'event_id'=>$event_row->event_id,
	'event_date'=>$event_row->event_date,
	'event_begin_time'=>$event_row->event_begin_time,
	'event_end_time'=>$event_row->event_end_time,
	'event_name'=>$event_row->event_name,
	'event_space'=>$event_row->event_space,
	'event_signed_up'=>$event_signed_up
	);
 
	$event_data[row_number($id)] = $update_arr;
 
	$data = json_encode($event_data, JSON_PRETTY_PRINT);
	file_put_contents('events.json', $data);

	header('Location:student.php?student_event_list');

}
$param = $_SERVER['QUERY_STRING'];
$arr = explode("=", $param);

if (count($arr) > 1) {
    $param = $arr[0];
    $id = $arr[1];
}


if ($param == "reserve") {
    show_form($id);
} 

if ($param == "class_signup") {
    class_signup($id);
} 

?>
