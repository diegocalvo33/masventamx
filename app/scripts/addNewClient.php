<?php
    include 'connection.php';

    $object = json_decode( $_POST['data'] );

    $query = "INSERT INTO clients(
        number,
        name,
        sex,
        birthdate,
        zipCode,
        email,
        cellphone
    ) VALUES(
        '$object->client',
        '$object->name',
        '$object->sex',
        '$object->birthdate',
        '$object->zipCode',
        '$object->email',
        '$object->cellphone'
    )";
    $mysql->query( $query );
?>
