<?php
	date_default_timezone_set( 'America/Mexico_City' );
	include 'connection.php';

	$dates = date( 'Y-m-d H:i:s' );

    $query = "SELECT end FROM cashCuts ORDER BY ID DESC LIMIT 1";
    $result = $mysql->query( $query );

    while ( $row = $result->fetch_row() ) 
    {
        $end = $row[0];
    }

    if ( $result->num_rows == 0 )
    {
        $data = false;
    }else if ( empty( $end ) )
    {
        $data = true;

		$query = "SELECT folio FROM cashCuts ORDER BY folio DESC LIMIT 1";
		$result = $mysql->query( $query );

		while ( $row = $result->fetch_row() ) 
		{
			$folio = $row[0];
		}

		$query = "UPDATE cashCuts SET end = '$dates' WHERE folio = '$folio'";
		$mysql->query( $query );
    }else
    {
        $data = false;
    }
?>
