<?php
    include 'connection.php';

    $object = json_decode( $_POST['data'] );
    $data = new stdClass;

    $query = "SELECT name, price, points FROM items WHERE SKU = '$object->SKU'";
    $result = $mysql->query( $query );

    if ( $result->num_rows > 0 )
    {
        while ( $row = $result->fetch_row() )
        {
            $data->concept = $row[0];
            $data->price = $row[1];
            $data->points = $row[2];
        }

        $query = "INSERT INTO receipt(
            control,
            SKU,
            quantity,
            concept,
            price,
            points
        ) VALUES(
            '$object->control',
            '$object->SKU',
            '1',
            '$data->concept',
            '$data->price',
            '$data->points'
        )";
        $mysql->query( $query );

        $data->response = true;
    }else
    {
        $data->response = false;
    }

    echo json_encode( $data );
?>
