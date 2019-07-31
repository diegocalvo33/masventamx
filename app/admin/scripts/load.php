<?php
	include 'connection.php';

	$object = json_decode( $_POST['data'] );
	$data = new stdClass;

	if ( $object->bring == 'name' )
	{
		$query = "SELECT businessName FROM users WHERE ID = '1'";
		$data->businessName = $mysql->query( $query )->fetch_object()->businessName;
	}else if ( $object->bring == 'address' )
	{
		$query = "SELECT address, zipCode, city, state FROM users WHERE ID = '1'";
		$result = $mysql->query( $query );
		while ( $row = $result->fetch_row() )
		{
			$data->address = $row[0];
			$data->zipCode = $row[1];
			$data->city = $row[2];
			$data->state = $row[3];
		}
	}

	echo json_encode( $data );
?>
