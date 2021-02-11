<?php
   
    $headers =  getallheaders();
	session_id($headers['user_id']);
	session_start();

    if(!isset($_SESSION['user_id'])){
		echo "User failed";
	}
    else{
        $OrganizationID = $_GET['OrganizationID'];
        $EstimateID = $_GET['EstimateID'];
        $EstimateStatus = $_GET['EstimateStatus'];

        include './estimate_functions.php';
        $update = updateEstimateStatus($OrganizationID, $EstimateID, $EstimateStatus);
	}
?>