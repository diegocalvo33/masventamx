<?php
	include 'connection.php';

	$object = json_decode( $_POST['data'] );

    $query = "SELECT dates, receipt, amount FROM tickets ORDER BY ID DESC LIMIT 1";
    $result = $mysql->query( $query );
    while ( $row = $result->fetch_row() ) 
    {
        $dates = $row[0];
        $receipt = $row[1];
        $amount = $row[2];
    }

    $cash = 0;
    $card = 0;
    $due = 0;

    $query = "SELECT concept, amount FROM cash WHERE receipt = '$receipt'";
    $result = $mysql->query( $query );
    while ( $row = $result->fetch_row() ) 
    {
        if ( $row[0] == 'Efectivo' )
        {
            $cash = $row[1];
        }else if ( $row[0] == 'Tarjeta' )
        {
            $card = $row[1];
        }else if ( $row[0] == 'Cambio' )
        {
            $due = $row[1];
        }
    }

    $points = 0;
    $query = "SELECT points FROM points WHERE receipt = '$receipt'";
    $result = $mysql->query( $query );
    while ( $row = $result->fetch_row() ) 
    {
        $points = $row[0];
    }

    $query = "SELECT businessName, address, zipCode, city, state FROM users WHERE ID = '1'";
    $result = $mysql->query( $query );
    while ( $row = $result->fetch_row() )
    {
        $businessName = $row[0];
        $address = $row[1];
        $zipCode = $row[2];
        $city = $row[3];
        $state = $row[4];
    }

	$to = $object->email;
	$subject = "Tu recibo de compra por $" . $amount;
	$message = '        <div style="background-color: #eeeeee; font-family: sans-serif;">
            <div style="background-color: white; margin: 0 auto; width: 360px; padding: 1rem; text-align: center;">
                <img src="https://dctprime.com/mas-venta/logo1.png" alt="logo">
                <h2>'.$businessName.'</h2>
                <div style="display: none;">
                    <p><b>
                        <span>Generación Impulsora, S.A. De C.V.</span><br>
                        <span>GIM1506041D0</span>
                    </b></p>
                    <p><b>Régimen fiscal</b></p>
                    <p>601 - Régimen general de Ley personas morales</p>
                    <p><b>Domicilio fiscal</b></p>
                    <p>
                        <span>Privada Covadonga #21</span><br>
                        <span>Colonia Covadonga, C.P. 29030</span><br>
                        <span>Tuxtla Gutiérrez, Chiapas, México</span>
                    </p>
                </div>
                <p><b>Expedido en</b></p>
                <p>
                    <span>'.$address.'</span><br>
                    <span>'.$zipCode.'</span><br>
                    <span>'.$city.', '.$state.'</span>
                </p>
                <p><b>Fecha de expedición</b></p>
                <p>'.$dates.'</p>
                <p><b>Folio</b></p>
                <p>'.$receipt.'</p>
                <table>
                    <thead>
                        <tr>
                            <th>Cantidad</th>
                            <th>Concepto</th>
                            <th>Precio unitario</th>
                            <th>Importe</th>
                        </tr>
                    </thead>
                    <tbody>';
                    $query = "SELECT quantity, concept, price FROM receipt WHERE receipt = '$receipt'";
                    $result = $mysql->query( $query );
                    while ( $row = $result->fetch_row() ) 
                    {
                        $message .= '
                        <tr>
                            <td>'.number_format( $row[0], 0 ).'</td>
                            <td>'.$row[1].'</td>
                            <td>'.number_format( $row[2], 2 ).'</td>
                            <td>'.number_format( $row[2], 2 ).'</td>
                        </tr>';
                    }
                    $message .= '</tbody>
                </table>
                <table style="text-align: right; font-size: 1.5rem;">
                    <tbody>
                        <tr style="display: none;">
                            <th>SUBTOTAL</th>
                            <th>'.$amount.'</th>
                        </tr>
                        <tr style="display: none;">
                            <th>IVA</th>
                            <th>0.00</th>
                        </tr>
                        <tr>
                            <th>TOTAL</th>
                            <th>'.$amount.'</th>
                        </tr>
                    </tbody>
                </table>
                <p><b>Puntos generados por tu compra '.number_format( $points, 2 ).'</b></p>
                <p style="display: none;">(trescientos setenta y cinco pesos 00/20 M.N.)</p>
                <p><b>Método de pago</b></p>
                <p>En una sola exhibición</p>
                <p><b>Forma de pago</b></p>
                <p>Efectivo - '.number_format( $cash, 2 ).'</p>
                <p>Tarjeta - '.number_format( $card, 2 ).'</p>
                <p>Cambio - '.number_format( $due, 2 ).'</p>
            </div>
        </div>';
	$headers = "MIME-Version: 1.0" . "\r\n";
	$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
	$headers .= 'From: +ventamx <no-reply@mas-venta.mx>' . "\r\n";
    mail( $to, $subject , $message, $headers );
?>
