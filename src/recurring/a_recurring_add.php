<?php
    header('Access-Control-Allow-Origin: *'); 
    header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
    header('Content-Type: application/json');
    
    $json = file_get_contents('php://input');
    $params = json_decode($json);
    
    include '../../../../../deployments/connect/connection_invoice.php'; 

    sqlsrv_query($conn, "EXEC p_recurring_add '$params->OrganizationID',
                                            '$params->InvoiceID',
                                            '$params->RecurringTypeCode',
                                            '$params->RecurringName',
                                            '$params->StartDate',
                                            '$params->EndDate',
                                            '$params->RepeatEvery',
                                            '$params->Frecuency',
                                            '$params->TermDays',
                                            '$params->CreateAction',
                                            '$params->CreateEmail',
                                            '$params->RecurringStatus';");
    class Result {}
    $response = new Result();
    $response->resultado = 'OK';
    $response->mensaje = 'cabecera recurrente grabada';
    
    echo json_encode($response);  
?>
