<?php  
	header('Access-Control-Allow-Origin: *'); 
	header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
	header('Content-Type: application/json');

	include '../../../../../deployments/connect/connection_invoice.php'; 

	sqlsrv_query($conn,"DELETE FROM INVOICE WHERE InvoiceID = $_GET[InvoiceID]
						DELETE FROM INVOICE_LINE WHERE InvoiceID = $_GET[InvoiceID]");
	
	class Result {}

	$response = new Result();
	$response->resultado = 'OK';
	$response->mensaje = 'factura borrada';

	echo json_encode($response); 
?>