<?php  
	header('Access-Control-Allow-Origin: *'); 
	header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
	header('Content-Type: application/json');

	include '../../../../../deployments/connect/connection_invoice.php'; 

	sqlsrv_query($conn,"DELETE FROM ESTIMATE WHERE EstimateID = $_GET[EstimateID]
						DELETE FROM ESTIMATE_LINE WHERE EstimateID = $_GET[EstimateID]");
	
	class Result {}

	$response = new Result();
	$response->resultado = 'OK';
	$response->mensaje = 'presupuesto borrado';

	echo json_encode($response); 
?>