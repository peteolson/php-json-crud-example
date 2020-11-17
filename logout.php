<?PHP
	$logout = true;
	$cfgProgDir = 'phpSecurePages/';
    include($cfgProgDir . "secure.php");
    header('Location:admin.php?admin_event_list');
?>
