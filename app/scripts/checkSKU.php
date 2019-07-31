<?php
    include 'connection.php';

    $object = json_decode( $_POST['data'] );
    $data = new stdClass;
    $data->categories = array();

    if ( $object->action == 'new' )
    {
        $query = "SELECT ID FROM items WHERE SKU = '$object->SKU'";
        $result = $mysql->query( $query );

        if ( $result->num_rows == 0 )
        {
            $data->response = true;

            $query = "SELECT name FROM categories";
            $result = $mysql->query( $query );

            while ( $row = $result->fetch_row() ) 
            {
                array_push( $data->categories, $row[0] );
            }
        }else
        {
            $data->response = false;
        }
    }else
    {
        $query = "SELECT ID FROM items WHERE SKU = '$object->SKU'";
        $result = $mysql->query( $query );

        if ( $result->num_rows > 0 )
        {
            $data->response = true;

            $query = "SELECT name FROM categories";
            $result = $mysql->query( $query );

            while ( $row = $result->fetch_row() ) 
            {
                array_push( $data->categories, $row[0] );
            }

            $query = "SELECT category, name, price, points FROM items WHERE SKU = '$object->SKU'";
            $result = $mysql->query( $query );

            while ( $row = $result->fetch_row() ) 
            {
                $data->category = $row[0];
                $data->name = $row[1];
                $data->price = $row[2];
                $data->points = $row[3];
            }
        }else
        {
            $data->response = false;
        }
    }

    echo json_encode( $data );
?>
