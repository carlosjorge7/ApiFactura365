<?php
	header('Access-Control-Allow-Origin: *'); 
	header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
	header('Content-Type: application/json');
	
	$json = file_get_contents('php://input');
	$params = json_decode($json);
	
	include '../../../../../deployments/connect/connection_invoice.php'; 

	sqlsrv_query($conn, "EXEC p_customer_add '$params->OrganizationID',
											'$params->StatusCode',
											'$params->IdFiscal',
											'$params->CustomerName',
											'$params->DisplayName',
											'$params->Email',
											'$params->Phone',
											'$params->CreditLimit',
											'$params->TermDays',
											'$params->CurrencyCode',
											'$params->CustomerNotes',
											'$params->Website',
											'$params->BillingAddress',
											'$params->BillingStreet2',
											'$params->BillingCity',
											'$params->BillingState',
											'$params->BillingCountryIso',
											'$params->BillingCode',
											'$params->ShippingAddress',
											'$params->ShippingStreet2',
											'$params->ShippingCity',
											'$params->ShippingState',
											'$params->ShippingCountryIso',
											'$params->ShippingCode';");

	class Result {}
	$response = new Result();
	$response->resultado = 'OK';
	$response->mensaje = 'cliente grabado';
	
	echo json_encode($response);  
?>
