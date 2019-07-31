<?php
	include 'connection.php';

	$object = json_decode( $_POST['data'] );

	$query = "SELECT ID FROM items WHERE name = '$object->name'";
    $result = $mysql->query( $query );

    if ( $result->num_rows > 0 )
    {
    	$query = "UPDATE items SET
   			category = '$object->category',
			name = '$object->name',
			price = '$object->price',
			points = '$object->points'
		WHERE SKU = '$object->SKU'";
		$mysql->query( $query );

		$data = true;
	}else
	{
		$data = false;
	}

	echo json_encode( $data );
?>
