<?php
	include 'connection.php';

	$tickets = array();

	$query = "SELECT receipt, client, amount FROM tickets";
	$result = $mysql->query( $query );

	while ( $row = $result->fetch_row() ) 
	{
		if ( $row[1] !== '' )
		{
			array_push( $tickets, array(
				'receipt' => $row[0],
				'client' => $row[1],
				'name' => $mysql->query( "SELECT name FROM clients WHERE number = '$row[1]'" )->fetch_object()->name,
				'amount' => $row[2]
			));
		}else
		{
			array_push( $tickets, array(
				'receipt' => $row[0],
				'client' => $row[1],
				'name' => 'Público en general',
				'amount' => $row[2]
			));	
		}

	}
	
	echo json_encode( $tickets );
?>