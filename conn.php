<?php
	$conn = new mysqli('localhost', 'root', '', 'pateros_dbx');

	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	}
	
?>