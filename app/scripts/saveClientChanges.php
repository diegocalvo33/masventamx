<?php
    include 'connection.php';

    $object = json_decode( $_POST['data'] );

    $query = "UPDATE clients SET
        name = '$object->name',
        sex = '$object->sex',
        birthdate = '$object->birthdate',
        zipCode = '$object->zipCode',
        email = '$object->email',
        cellphone = '$object->cellphone'
    WHERE number = '$object->client'";
    $mysql->query( $query );
?>
