<?php
	include 'connection.php';

	$object = json_decode( $_POST['data'] );

	$query = "SELECT ID FROM categories WHERE name = '$object->newName'";
    $result = $mysql->query( $query );

    if ( $result->num_rows == 0 )
    {
		$query = "UPDATE categories SET name = '$object->newName' WHERE name = '$object->lastName'";
		$mysql->query( $query );

		$query = "UPDATE items SET category = '$object->newName' WHERE category = '$object->lastName'";
		$mysql->query( $query );

		$data = true;
	}else
	{
		$data = false;
	}

	echo json_encode( $data );
?>
