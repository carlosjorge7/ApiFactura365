<?php
	header('Access-Control-Allow-Origin: *'); 
	header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
	header('Content-Type: application/json');
	
	$json = file_get_contents('php://input');
	$params = json_decode($json);
	
	include '../../../../../deployments/connect/connection_invoice.php'; 

	$procedureSQL = "EXEC p_organization_add '$params->UsuarioID',
											'$params->OrganizationName',
											'$params->CurrencyCode', 
											'$params->CountryIso',
											'$params->LanguageIso', 
											'$params->Address', 
											'$params->Street2',
											'$params->City', 
											'$params->State',
											'$params->PostalCode',
											'$params->OrganizationIdFiscal',
											'$params->InvoiceRoot',
											'$params->EstimateRoot', 
											'$params->CreditRoot', 
											'$params->OrganizationIBAN',
											'$params->OrganizationBank',
											'$params->OrganizationStatusCode',
											'$params->TermsConditionsInvoice', 
											'$params->LogoUrl';";
	
	$stmt = sqlsrv_query($conn, $procedureSQL);  

	class Result {}

	$response = new Result();
	$response->resultado = 'OK';
	$response->mensaje = 'organizacion grabada';
	
	if (!$stmt) {
		if (($errors = sqlsrv_errors()) != null) {
			foreach ($errors as $error) {
				echo 'KO'."<br />";
				echo "SQLSTATE: ".$error['SQLSTATE']."<br />";
				echo "CODE: ".$error['code']."<br />";
				echo "MESSAGE: ".$error['message']."<br />";
			}
		}
	} else {
		echo json_encode($response);
	}
?>