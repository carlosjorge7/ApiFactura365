<?php
	header('Access-Control-Allow-Origin: *'); 
	header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
	header('Content-Type: application/json');
	
	$json = file_get_contents('php://input');
	$params = json_decode($json);
	
	include '../../../../../deployments/connect/connection_invoice.php'; 

	sqlsrv_query($conn,"EXEC p_invoice_add_random '$params->Random',
											'$params->OrganizationID',
											'$params->CustomerID',
											'$params->InvoiceDate',
											'$params->InvoiceNumber',
											'$params->InvoiceType',
											'$params->InvoiceStatus',
											'$params->DueDate',
											'$params->PurchaseOrder',
											'$params->Notes',
											'$params->TermsConditions',
											'$params->SubTotal',
                                            '$params->Total',
                                            '$params->Salesperson',
                                            '$params->RecurringID';");

	class Result {}

	$response = new Result();
	$response->resultado = 'OK';
	$response->mensaje = 'factura grabada';
	
	echo json_encode($response);  
?>