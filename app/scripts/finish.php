<?php
    date_default_timezone_set( 'America/Mexico_City' );
    include 'connection.php';

    $dates = date( 'Y-m-d H:i:s' );
    $object = json_decode( $_POST['data'] );

    $query = "SELECT receipt FROM receipt ORDER BY receipt DESC LIMIT 1";
    $result = $mysql->query( $query );

    while ( $row = $result->fetch_row() )
    {
        $receipt = $row[0];
    }
    $receipt += 1;

    $query = "UPDATE receipt SET dates = '$dates', receipt = '$receipt' WHERE receipt = '0'";
    $mysql->query( $query );

    if ( $object->cash > 0 )
    {
        $query = "INSERT INTO cash(
            dates,
            receipt,
            client,
            concept,
            amount
        ) VALUES(
            '$dates',
            '$receipt',
            '$object->client',
            'Efectivo',
            '$object->cash'
        )";
        $mysql->query( $query );
    }

    if ( $object->card > 0 )
    {
        $query = "INSERT INTO cash(
            dates,
            receipt,
            client,
            concept,
            amount
        ) VALUES(
            '$dates',
            '$receipt',
            '$object->client',
            'Tarjeta',
            '$object->card'
        )";
        $mysql->query( $query );
    }

    if ( $object->due > 0 )
    {
        $query = "INSERT INTO cash(
            dates,
            receipt,
            client,
            concept,
            amount
        ) VALUES(
            '$dates',
            '$receipt',
            '$object->client',
            'Cambio',
            '$object->due'
        )";
        $mysql->query( $query );
    }

    $query = "INSERT INTO tickets(
        dates,
        receipt,
        client,
        amount
    ) VALUES(
        '$dates',
        '$receipt',
        '$object->client',
        '$object->amount'
    )";
    $mysql->query( $query );

    if ( $object->points !== 0 )
    {
        $query = "INSERT INTO points(
            dates,
            client,
            receipt,
            points
        ) VALUES(
            '$dates',
            '$object->client',
            '$receipt',
            '$object->points'
        )";
        $mysql->query( $query );
    }

    if ( !empty( $object->client ) )
    {
        $query = "SELECT email FROM clients WHERE number = '$object->client'";
        $data = $mysql->query( $query )->fetch_object()->email;
    }else
    {
        $data = '';
    }  

    echo json_encode( $data );
?>
