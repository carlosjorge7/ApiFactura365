<?php
    header('Access-Control-Allow-Origin: *'); 
    header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
    header('Content-Type: application/json');
    
    $json = file_get_contents('php://input');
    $params = json_decode($json);
    
    include '../../../../../deployments/connect/connection_invoice.php'; 

    sqlsrv_query($conn, "EXEC p_product_update '$params->OrganizationID',
                                                '$params->ProductID',
                                                '$params->ProductName',
                                                '$params->ProductDescription',
                                                '$params->ProductPriceSale',
                                                '$params->ProductPriceCost',
                                                '$params->ProductTax1Rate',
                                                '$params->ProductTax2Rate',
                                                '$params->ProductTax3Rate',
                                                '$params->CategoryID',
                                                '$params->ProductType',
                                                '$params->ProductCode',
                                                '$params->Usageunit',
                                                '$params->ProductStatus';");
    
    class Result {}

    $response = new Result();
    $response->resultado = 'OK';
    $response->mensaje = 'producto modificado';
    
    echo json_encode($response);  
?>