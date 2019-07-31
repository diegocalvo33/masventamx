<?php
	include 'connection.php';

	$object = json_decode( $_POST['data'] );
	$data = array();

	$query = "SELECT ID, end FROM cashCuts WHERE end BETWEEN '$object->start' AND '$object->end'";
	$result = $mysql->query( $query );

	while ( $row = $result->fetch_row() ) 
	{
		$a = $mysql->query( "SELECT start, end FROM cashCuts WHERE ID = '$row[0]'" );

		while ( $A = $a->fetch_row() ) 
		{
			$start = $A[0];
			$end = $A[1];
		}

		$query = "SELECT SUM( amount ) AS total FROM tickets WHERE dates BETWEEN '$start' AND '$end'";
		$amount = number_format( $mysql->query( $query )->fetch_object()->total, 2 );

		array_push( $data, array(
			'ID' => $row[0],
			'dates' => $row[1],
			'amount' => $amount
		));
	}

	echo json_encode( $data );
?>
