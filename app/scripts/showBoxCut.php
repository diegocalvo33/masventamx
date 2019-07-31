<?php
	include 'connection.php';

	$object = json_decode( $_POST['data'] );
	$data = new stdClass;
	$data->receipts = array();
	$data->items = array();
	$data->cardPayments = array();

	$query = "SELECT start, end FROM cashCuts WHERE ID = '$object->ID'";
	$result = $mysql->query( $query );

	while ( $row = $result->fetch_row() ) 
	{
		$start = $row[0];
		$end = $row[1];
	}

	$query = "SELECT receipt, client, amount FROM tickets WHERE dates BETWEEN '$start' AND '$end'";
	$result = $mysql->query( $query );

	while ( $row = $result->fetch_row() ) 
	{
		if ( $row[1] == '' )
		{
			$client = 'Público en general';
		}else
		{
			$query = "SELECT name FROM clients WHERE number = '$row[1]'";
			$client = $mysql->query( $query )->fetch_object()->name;
		}

		array_push( $data->receipts, array(
			'receipt' => $row[0],
			'client' => $client,
			'amount' => $row[2]
		));
	}

	$query = "SELECT SKU, name FROM items";
	$result = $mysql->query( $query );

	while ( $row = $result->fetch_row() ) 
	{
		$query = "SELECT SUM( quantity ) AS total FROM receipt WHERE SKU = '$row[0]' AND dates BETWEEN '$start' AND '$end'";
		$quantity = $mysql->query( $query )->fetch_object()->total;

		if ( $quantity > 0 )
		{
			array_push( $data->items, array(
				'concept' => $row[1],
				'quantity' => $quantity
 			));
		}
	}

	$query = "SELECT SUM( amount ) AS total FROM cash WHERE concept = 'Efectivo' AND dates BETWEEN '$start' AND '$end'";
	$data->cash = $mysql->query( $query )->fetch_object()->total;

	$query = "SELECT SUM( amount ) AS total FROM cash WHERE concept = 'Tarjeta' AND dates BETWEEN '$start' AND '$end'";
	$data->card = number_format( $mysql->query( $query )->fetch_object()->total, 2 );

	$query = "SELECT SUM( amount ) AS total FROM cash WHERE concept = 'Cambio' AND dates BETWEEN '$start' AND '$end'";
	$data->due = $mysql->query( $query )->fetch_object()->total;

	$data->cash = number_format( $data->cash - $data->due, 2 );

	// Card list
	$query = "SELECT client, receipt, amount FROM cash WHERE concept = 'Tarjeta' AND dates BETWEEN '$start' AND '$end'";
	$result = $mysql->query( $query );

	while ( $row = $result->fetch_row() ) 
	{
		$query = "SELECT name FROM clients WHERE number = '$row[0]'";
		$client = $mysql->query( $query )->fetch_object()->name;

		if ( $client == '' )
		{
			$client = 'Público en general';
		}

		array_push( $data->cardPayments, array(
			'receipt' => $row[1],
			'client' => $client,
			'amount' => $row[2]
		));
	}

	$query = "SELECT SUM( amount ) AS total FROM tickets WHERE dates BETWEEN '$start' AND '$end'";
	$data->netSells = $mysql->query( $query )->fetch_object()->total;

	$data->pointsUsed = 0;

	$query = "SELECT points FROM points WHERE dates BETWEEN '$start' AND '$end'";
	$result = $mysql->query( $query );

	while ( $row = $result->fetch_row() ) 
	{
		if ( $row[0] < 0 )
		{
			$data->pointsUsed += $row[0];
		}
	}

	$data->sells = $data->netSells + abs( $data->pointsUsed );

	// Points list
	$data->receiptWithPointsTable = array();

	$query = "SELECT client, receipt, points FROM points WHERE dates BETWEEN '$start' AND '$end'";
	$result = $mysql->query( $query );

	while ( $row = $result->fetch_row() ) 
	{
		if ( $row[2] < 0 )
		{
			$query = "SELECT name FROM clients WHERE number = '$row[0]'";
			$client = $mysql->query( $query )->fetch_object()->name;

			if ( $client == '' )
			{
				$client = 'Público en general';
			}

			array_push( $data->receiptWithPointsTable, array(
				'receipt' => $row[1],
				'client' => $client,
				'points' => abs( $row[2] )
			));
		}
	}

	echo json_encode( $data );
?>
