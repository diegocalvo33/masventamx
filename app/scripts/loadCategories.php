<?php
	include 'connection.php';

	$data = array();

	$query = "SELECT name FROM categories";
	$result = $mysql->query( $query );

	while ( $row = $result->fetch_row() ) 
	{
		array_push( $data, $row[0] );
	}

	echo json_encode( $data );
?>
