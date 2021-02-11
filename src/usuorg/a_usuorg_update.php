<?php
    header('Access-Control-Allow-Origin: *'); 
    header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
    header('Content-Type: application/json');
    
    $json = file_get_contents('php://input');
    $params = json_decode($json);
    
    include '../../../../../deployments/connect/connection_invoice.php'; 

    sqlsrv_query($conn,"EXEC p_usuorg_update '$params->Usuario_OrganizationID', '$params->OrganizationID', '$params->Usuario_OrganizationRoleCode';");

    class Result {}
    $response = new Result();
    $response->resultado = 'OK';
    $response->mensaje = 'usuario modificado para organización';
    
    
    echo json_encode($response);  
?>
