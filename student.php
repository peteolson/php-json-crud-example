<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
 
<?php

function sort_by_event_date($a, $b) {
	return $a->event_date > $b->event_date;
}

function student_event_list() {
	
	
	$event_data = file_get_contents('events.json');
	$event_data = json_decode($event_data);
	usort($event_data, 'sort_by_event_date'); 

		
	foreach($event_data as $event_row) {
		if ($event_row->event_date >= date("Y-m-d")) {	
			echo '<pre><hr>';
			echo '<h3>'.$event_row->event_name.'</h3>';
			echo date("D - F j, Y", strtotime($event_row->event_date));
			echo '<br>';
			echo date('g:i A', strtotime($event_row->event_begin_time));
			echo ' to ';
			echo date('g:i A', strtotime($event_row->event_end_time));
			echo ' EST';
			echo '<br>';
			echo 'Event Space: '.$event_row->event_space;
			echo '</pre>';
			
			if ($event_row->event_space <= $event_row->event_signed_up) {
				echo 'Class is full!';
			} else {
				if ( ($event_row->event_space - $event_row->event_signed_up) == 1 ) {
					echo 'There is only '. ( $event_row->event_space - $event_row->event_signed_up ).' space left!';
				} else {
					echo 'There are '. ( $event_row->event_space - $event_row->event_signed_up ).' spaces available';
				}
				echo '<br>';
				echo '<br>';
				echo '<a href=\'class_signup.php?reserve='.$event_row->event_id.'\'>Get a spot in the class!</a>';
			}
			
			$event_begin_time = $event_row->event_begin_time;
			$event_date = $event_row->event_date;
		}
		
	}

	echo '<hr>';
	
}

$param = $_SERVER['QUERY_STRING'];
$arr = explode("=", $param);
if (count($arr) > 1) {
    $param = $arr[0];
}

if ($param == "reserve") {
    $id = $arr[1];
    header('Location:class_signup.php?reserve='.$id);
} 

if ($param == "class_signup") {
    $id = $arr[1];
    class_signup($id);    
} 

if ($param == "student_event_list") {
    student_event_list();
} 

	
?>
</div>
</body>
</html>
