<?php 
	header('Access-Control-Allow-Origin: *'); 
	header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
	header('Content-Type: application/json');

	include '../../../../../deployments/connect/connection_invoice.php'; 

	// Algunos casos van con comillas (proceso es de tipo string)
	sqlsrv_query($conn, "EXEC p_usuorg_id '$_GET[proceso]', '$_GET[OrganizationID]', '$_GET[Usuario_OrganizationID]';");
	
	class Result {}

	$response = new Result();
	$response->resultado = 'OK';
	$response->mensaje = 'usuario para organización borrado';

	echo json_encode($response); 
?>