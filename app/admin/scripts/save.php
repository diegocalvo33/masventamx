<?php
	include 'connection.php';

	$object = json_decode( $_POST['data'] );
	$data = new stdClass;

	if ( $object->save == 'name' )
	{
		$query = "UPDATE users SET businessName = '$object->businessName' WHERE ID = '1'";
		$mysql->query( $query );
	}else if ( $object->save == 'address' )
	{
		$query = "UPDATE users SET 
			address = '$object->address',
			zipCode = '$object->zipCode',
			city = '$object->city',
			state = '$object->state'
		WHERE ID = '1'";
		$mysql->query( $query );
	}
?>
