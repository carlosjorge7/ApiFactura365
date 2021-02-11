<?php
	header('Access-Control-Allow-Origin: *'); 
	header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
	header('Content-Type: application/json');
	
	$json = file_get_contents('php://input');
	$params = json_decode($json);
	
	include '../../../../../deployments/connect/connection_invoice.php'; 

    sqlsrv_query($conn,"EXEC p_payment_update '$params->OrganizationID',
                                                '$params->PaymentID',
                                                '$params->InvoiceID',
                                                '$params->CustomerID',
                                                '$params->PaymentDate',
                                                '$params->PaymentMethodID',
                                                '$params->PaymentDescription',
                                                '$params->PaymentAmount',
                                                '$params->PaymentReference' ;");

	class Result {}

	$response = new Result();
	$response->resultado = 'OK';
	$response->mensaje = 'pago modificado';
	
	echo json_encode($response);  
?>