<?php
    header('Access-Control-Allow-Origin: *'); 
    header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
    header('Content-Type: application/json');
    
    include '../../../../../deployments/connect/connection_invoice.php'; 
    
    // El listado de categorias ha de ser filtrado por OrganizationID

    $query = sqlsrv_query($conn, "SELECT PaymentMethodID AS id, PaymentMethod
                                    FROM PAYMENT_METHOD ");
    $res=[];
    while($row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC) ) {
        $res[] = $row;
    }
    
    $cad = json_encode($res);
    echo $cad;
?>
