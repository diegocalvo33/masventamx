<?php
    include 'connection.php';

    $object = json_decode( $_POST['data'] );
    $data = new stdClass;

    $query = "SELECT ID FROM clients WHERE number = '$object->client'";
    $result = $mysql->query( $query );

    if ( $result->num_rows > 0 )
    {
        $data->response = true;

        $query = "SELECT SUM( points ) AS total FROM points WHERE client = '$object->client'";
        $data->balance = number_format( $mysql->query( $query )->fetch_object()->total, 2 );
    }else
    {
        $data->response = false;
    }

    echo json_encode( $data );
?>
