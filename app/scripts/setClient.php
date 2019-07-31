<?php
    include 'connection.php';

    $object = json_decode( $_POST['data'] );
    $data = new stdClass;

    $query = "SELECT name FROM clients WHERE number = '$object->client'";
    $result = $mysql->query( $query );

    if ( $result->num_rows > 0 )
    {
        $data->name = $result->fetch_object()->name;

        $query = "SELECT SUM( points ) AS points FROM points WHERE client = '$object->client'";
        $points = $mysql->query( $query )->fetch_object()->points;

        if ( $points > 0 )
        {
            $data->points = $points;
        }else
        {
            $data->points = 'No tiene puntos.';
        }

        $data->response = true;
    }else
    {
        $data->response = false;
    }

    echo json_encode( $data );
?>
