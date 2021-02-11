<?php 
	header('Access-Control-Allow-Origin: *'); 
	header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
	header('Content-Type: application/json');

	include '../../../../../deployments/connect/connection_invoice.php'; 

	sqlsrv_query($conn,"EXEC p_organization_id $_GET[proceso],
												$_GET[UsuarioID],
												$_GET[OrganizationID],
												$_GET[OrganizationStatusCode] ;");
	
	class Result {}
	$response = new Result();
	$response->resultado = 'OK';
	$response->mensaje = 'organizacion borrada';

	echo json_encode($response); 
?>