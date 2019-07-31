<?php
    include 'connection.php';

    $object = json_decode( $_POST['data'] );

    $query = "DELETE FROM receipt WHERE control = '$object->control'";
    $mysql->query( $query );
?>
