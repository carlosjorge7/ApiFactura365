<?php
    header('Access-Control-Allow-Origin: *'); 
    header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
    header('Content-Type: application/json');
    
    $json = file_get_contents('php://input');
    $params = json_decode($json);
    
    include '../../../../../deployments/connect/connection_invoice.php'; 

    sqlsrv_query($conn, "EXEC p_contact_add '$params->CustomerID',
                                            '$params->FirstName',
                                            '$params->LastName',
                                            '$params->Email',
                                            '$params->MobilePhone',
                                            '$params->SkypeIdentity',
                                            '$params->Department' ; ");
    class Result {}
    $response = new Result();
    $response->resultado = 'OK';
    $response->mensaje = 'contacto grabado';
    
    echo json_encode($response);  
?>
