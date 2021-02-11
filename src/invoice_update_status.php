<?php
   
    $headers =  getallheaders();
	session_id($headers['user_id']);
	session_start();

    if(!isset($_SESSION['user_id'])){
		echo "User failed";
	}
    else{
        $OrganizationID = $_GET['OrganizationID'];
        $InvoiceID = $_GET['InvoiceID'];
        $InvoiceStatus = $_GET['InvoiceStatus'];

        include './invoice_functions.php';
        $update = updateInvoiceStatus($OrganizationID, $InvoiceID, $InvoiceStatus);
	}
?>