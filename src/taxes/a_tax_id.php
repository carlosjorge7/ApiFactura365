<?php 
	header('Access-Control-Allow-Origin: *'); 
	header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
	header('Content-Type: application/json');

	include '../../../../../deployments/connect/connection_invoice.php'; 

	sqlsrv_query($conn,"DELETE FROM TAX WHERE TaxID = $_GET[TaxID]");
	
	class Result {}

	$response = new Result();
	$response->resultado = 'OK';
	$response->mensaje = 'impuesto borrado';

	echo json_encode($response); 
?>