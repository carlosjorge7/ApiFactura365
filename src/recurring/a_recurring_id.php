<?php 
	header('Access-Control-Allow-Origin: *'); 
	header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
	header('Content-Type: application/json');

	include '../../../../../deployments/connect/connection_invoice.php'; 

	sqlsrv_query($conn,"DELETE INVOICE WHERE InvoiceID = $_GET[InvoiceID] 
                        DELETE INVOICE_LINE WHERE InvoiceID = $_GET[InvoiceID] 
                        DELETE RECURRING WHERE RecurringID = $_GET[RecurringID] ");
	
	class Result {}

	$response = new Result();
	$response->resultado = 'OK';
	$response->mensaje = 'cabecera recurrente borrada';

	echo json_encode($response); 
?>