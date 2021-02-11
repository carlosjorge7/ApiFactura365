<?php
    
   /* header('Access-Control-Allow-Origin: *'); 
    header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
    
    $json = file_get_contents('php://input');
    $params = json_decode($json);
    
    include '../../../../../deployments/connect/connection_invoice.php'; 

    sqlsrv_query($conn, "UPDATE ORGANIZATION
                            SET OrganizationName = '$params->OrganizationName',
                                CurrencyCode = '$params->CurrencyCode',
                                LanguageIso = '$params->LanguageIso', 
                                Address = '$params->Address', 
                                Street2 = '$params->Street2',
                                City = '$params->City', 
                                State = '$params->State',
                                PostalCode = '$params->PostalCode',
                                OrganizationIdFiscal = '$params->OrganizationIdFiscal',
                                InvoiceRoot = '$params->InvoiceRoot',
                                EstimateRoot = '$params->EstimateRoot', 
                                OrganizationIBAN = '$params->OrganizationIBAN',
                                OrganizationStatusCode = '$params->OrganizationStatusCode',
                                TermsConditionsInvoice = '$params->TermsConditionsInvoice', 
                                LogoUrl = '$params->LogoUrl'
                            WHERE OrganizationID = $params->OrganizationID");
    
    class Result {}

    $response = new Result();
    $response->resultado = 'OK';
    $response->mensaje = 'datos modificados';
    
    header('Content-Type: application/json');
    echo json_encode($response);  */

    header('Access-Control-Allow-Origin: *'); 
	header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
	header('Content-Type: application/json');
	
	$json = file_get_contents('php://input');
	$params = json_decode($json);
	
	include '../../../../../deployments/connect/connection_invoice.php'; 

    $procedureSQL = "EXEC p_organization_update $params->OrganizationID,
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
                                            '$params->InvoiceLastNumber',
                                            '$params->EstimateRoot', 
                                            '$params->EstimateLastNumber',
                                            '$params->CreditRoot', 
                                            '$params->CreditLastNumber',
											'$params->OrganizationIBAN',
											'$params->OrganizationBank',
											'$params->OrganizationStatusCode',
											'$params->TermsConditionsInvoice', 
											'$params->LogoUrl';";
	
	$stmt = sqlsrv_query($conn, $procedureSQL);  

	class Result {}

	$response = new Result();
	$response->resultado = 'OK';
	$response->mensaje = 'organizacion actualizada';
	
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
