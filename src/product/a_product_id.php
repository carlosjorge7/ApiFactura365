<?php  
	header('Access-Control-Allow-Origin: *'); 
	header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
	header('Content-Type: application/json');

	include '../../../../../deployments/connect/connection_invoice.php'; 

	sqlsrv_query($conn,"EXEC p_product_id $_GET[proceso], $_GET[OrganizationID], $_GET[ProductID] ;");
	
	class Result {}

	$response = new Result();
	$response->resultado = 'OK';
	$response->mensaje = 'producto borrado';

	echo json_encode($response); 
?>