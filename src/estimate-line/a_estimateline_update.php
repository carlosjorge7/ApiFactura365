<?php
	header('Access-Control-Allow-Origin: *'); 
	header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
	header('Content-Type: application/json');
	
	$json = file_get_contents('php://input');
	$params = json_decode($json);
	
	include '../../../../../deployments/connect/connection_invoice.php'; 

    sqlsrv_query($conn,"EXEC p_estimateline_update '$params->Estimate_LineID',
                                            '$params->EstimateID',
											'$params->EstimateNumber',
											'$params->ProductID',
											'$params->ProductName',
											'$params->ProductDescription',
											'$params->Quantity',
											'$params->ProductPrice',
											'$params->Discount',
											'$params->ProductTax1Rate',
											'$params->ProductTax2Rate',
											'$params->ProductTotal' ;");

	class Result {}

	$response = new Result();
	$response->resultado = 'OK';
	$response->mensaje = 'linea de presupuesto modificada';
	
	echo json_encode($response);  
?>