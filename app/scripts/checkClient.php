<?php
    include 'connection.php';

    $object = json_decode( $_POST['data'] );
    $data = new stdClass;

    if ( $object->action == 'new' )
    {
        $query = "SELECT ID FROM clients WHERE number = '$object->client'";
        $result = $mysql->query( $query );

        if ( $result->num_rows == 0 )
        {
            $data->response = true;
        }else
        {
            $data->response = false;
        }
    }else
    {
        $query = "SELECT ID FROM clients WHERE number = '$object->client'";
        $result = $mysql->query( $query );

        if ( $result->num_rows > 0 )
        {
            $data->response = true;

            $query = "SELECT name, sex, birthdate, zipCode, email, cellphone FROM clients WHERE number = '$object->client'";
            $result = $mysql->query( $query );

            while ( $row = $result->fetch_row() ) 
            {
                $data->name = $row[0];
                $data->sex = $row[1];
                $data->birthdate = $row[2];
                $data->zipCode = $row[3];
                $data->email = $row[4];
                $data->cellphone = $row[5];
            }
        }else
        {
            $data->response = false;
        }
    }

    echo json_encode( $data );
?>
