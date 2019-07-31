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

		$folio = 1;

		$query = "INSERT INTO cashCuts ( folio, start ) VALUES ( '$folio', '$dates' )";
		$mysql->query( $query );
    }else if ( empty( $end ) )
    {
        $data = true;
    }else
    {
        $data = false;

		$query = "SELECT folio FROM cashCuts ORDER BY folio DESC LIMIT 1";
		$result = $mysql->query( $query );

		while ( $row = $result->fetch_row() ) 
		{
			$folio = $row[0];
		}

		$folio += 1;

		$query = "INSERT INTO cashCuts ( folio, start ) VALUES ( '$folio', '$dates' )";
		$mysql->query( $query );
    }
?>
