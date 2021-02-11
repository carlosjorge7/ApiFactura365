<?php  
	header('Access-Control-Allow-Origin: *'); 
	header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
	header('Content-Type: application/json');

	include '../../../../../deployments/connect/connection_invoice.php'; 

	sqlsrv_query($conn,"DELETE FROM INVOICE_LINE WHERE Invoice_LineID = $_GET[Invoice_LineID]");
	
	class Result {}

	$response = new Result();
	$response->resultado = 'OK';
	$response->mensaje = 'linea de factura borrada';

	echo json_encode($response); 
?>