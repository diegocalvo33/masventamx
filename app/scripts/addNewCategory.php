<?php
	include 'connection.php';

	$object = json_decode( $_POST['data'] );

	$query = "SELECT ID FROM categories WHERE name = '$object->name'";
    $result = $mysql->query( $query );

    if ( $result->num_rows == 0 )
    {
		$query = "INSERT INTO categories( name ) VALUES( '$object->name' )";
		$mysql->query( $query );

		$data = true;
	}else
	{
		$data = false;
	}

	echo json_encode( $data );
?>
