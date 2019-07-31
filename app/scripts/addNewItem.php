<?php
	include 'connection.php';

	$object = json_decode( $_POST['data'] );

	$query = "SELECT ID FROM items WHERE name = '$object->name'";
    $result = $mysql->query( $query );

    if ( $result->num_rows == 0 )
    {
		$query = "INSERT INTO items(
			category,
			SKU,
			name,
			price,
			points
		) VALUES(
			'$object->category',
			'$object->SKU',
			'$object->name',
			'$object->price',
			'$object->points'
		)";
		$mysql->query( $query );

		$data = true;
	}else
	{
		$data = false;
	}

	echo json_encode( $data );
?>
