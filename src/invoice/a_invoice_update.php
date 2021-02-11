<?php
	header('Access-Control-Allow-Origin: *'); 
	header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
	header('Content-Type: application/json');
	
	$json = file_get_contents('php://input');
	$params = json_decode($json);
	
	include '../../../../../deployments/connect/connection_invoice.php'; 

    // El orden importa, ha de tener el mismo que Angular en los formularios
    sqlsrv_query($conn,"EXEC p_invoice_update '$params->OrganizationID',
                                            '$params->InvoiceID',
											'$params->CustomerID',
                                            '$params->InvoiceNumber',
                                            '$params->InvoiceDate',
											'$params->DueDate',
											'$params->PurchaseOrder',
											'$params->Notes',
											'$params->TermsConditions',
											'$params->SubTotal',
                                            '$params->Total',
                                            '$params->Salesperson';");

	class Result {}

	$response = new Result();
	$response->resultado = 'OK';
	$response->mensaje = 'factura modificada';
	
	echo json_encode($response);  
?>