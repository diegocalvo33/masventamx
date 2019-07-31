<?php
	include 'connection.php';

	$items = array();
	$stock = array();

	$query = "SELECT SKU, name FROM items";
	$result = $mysql->query( $query );

	while ( $row = $result->fetch_row() ) 
	{
		array_push( $items, array(
			'SKU' => $row[0],
			'name' => $row[1]
		));
	}

	for ( $i = 0; $i < count( $items ); $i++ )
	{
		$c = $items[$i]['SKU'];
		$query = "SELECT SUM( entrances ) AS total FROM stock WHERE SKU = '$c'";
		$income = floatval( $mysql->query( $query )->fetch_object()->total );

		$output = 0;
		$query = "SELECT quantity FROM receipt WHERE SKU = '$c'";
		$result = $mysql->query( $query );

		while ( $row = $result->fetch_row() )
		{
			$output += $row[0];
		}

		array_push( $stock, array(
			'SKU' => $items[$i]['SKU'],
			'name' => $items[$i]['name'],
			'stock' => $income - $output 
		));
	}
	
	echo json_encode( $stock );
?>
