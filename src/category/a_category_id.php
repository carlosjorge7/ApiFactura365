<?php 
	header('Access-Control-Allow-Origin: *'); 
	header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
	header('Content-Type: application/json');

	include '../../../../../deployments/connect/connection_invoice.php'; 

	sqlsrv_query($conn,"DELETE FROM CATEGORY WHERE CategoryID = $_GET[CategoryID]");
	/*sqlsrv_query($conn,"EXEC p_category_id $_GET[proceso], $_GET[OrganizationID], $_GET[CategoryID]");*/
	
	class Result {}

	$response = new Result();
	$response->resultado = 'OK';
	$response->mensaje = 'categoria borrada';

	echo json_encode($response); 
?>