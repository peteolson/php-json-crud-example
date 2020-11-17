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

function sort_by_event_date($a, $b) {
	return $a->event_date > $b->event_date;
}

function sort_by_students_name($a, $b) {
	return $a->students_name > $b->students_name;
}


function admin_event_list() {
	
	echo '<a href=\'student.php?student_event_list\'>Student Page</a>';
	echo '<br><br>';
	echo '<a href=\'add_class.php\'>Add New Class</a>';	
	echo '<br><br>';
	echo '<a href=\'logout.php\'>Logout</a>';
	echo '<br><br>';

// {"students_id":"16054399675fb111dfe9b70","students_event_id":"1","students_timestamp":"2020-11-08 14:56:44","students_name":"Pete"},
// {"event_id":"1","event_date":"2020-11-09","event_begin_time":"09:00:00","event_end_time":"09:45:00","event_name":"Not Your Granny's Chair Yoga","event_space":"5","event_signed_up":"5"},

	$student_data = file_get_contents('students.json');
	$student_data = json_decode($student_data);
	
	usort($student_data, 'sort_by_students_name');
	
	$event_data = file_get_contents('events.json');
	$event_data = json_decode($event_data);

	usort($event_data, 'sort_by_event_date'); 
	
	foreach($event_data as $event_row) {
		if ($event_row->event_date >= date("Y-m-d")) {
					
			echo '<pre><hr>'.
			'<h3>'.$event_row->event_name.'</h3>'.
			date("D - F j, Y", strtotime($event_row->event_date)).
			' :: '.
			date('g:i A', strtotime($event_row->event_begin_time)).
			' to '.
			date('g:i A', strtotime($event_row->event_end_time)).
			'<br>'.
			'Event Space: '.$event_row->event_space.
			'<br>'.
			'Event Space Available: '.($event_row->event_space - $event_row->event_signed_up).
			'<br>'.
			'<a href=\'class_update.php?load_form='.$event_row->event_id.'\'>Edit Class</a>'.
			'<br>'.
			'<a href=\'class_delete.php?sanity_check='.$event_row->event_id.'\'>Delete Class</a>'.
			'</pre>';
			
			foreach($student_data as $student_row) {
			if ($student_row->students_event_id == $event_row->event_id ) {
				echo $student_row->students_name.'<br>';

			}
		}

		
		}
	}
				
	echo '<hr>';
		
	
}

admin_event_list();
	
?>
</div>
</body>
</html>
