<?php
    include 'connection.php';

    $data = new stdClass;
    $data->items = array();
    $data->total = 0;
    $data->points = 0;

    $query = "SELECT control, quantity, concept, price, points FROM receipt WHERE receipt = '0'";
    $result = $mysql->query( $query );

    while ( $row = $result->fetch_row() )
    {
        array_push( $data->items, array(
            'control' => $row[0],
            'quantity' => $row[1],
            'concept' => $row[2],
            'price' => $row[3],
            'points' => $row[4]
        ));

        $data->total += $row[1] * $row[3];
        $data->points += $row[1] * $row[4];
    }

    $query = "SELECT end FROM cashCuts ORDER BY ID DESC LIMIT 1";
    $result = $mysql->query( $query );

    while ( $row = $result->fetch_row() ) 
    {
        $end = $row[0];
    }

    if ( $result->num_rows == 0 )
    {
        $data->started = false;
    }else if ( empty( $end ) )
    {
        $data->started = true;
    }else
    {
        $data->started = false;
    }

    echo json_encode( $data );
?>
