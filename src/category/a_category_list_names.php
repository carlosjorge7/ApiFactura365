<?php
    header('Access-Control-Allow-Origin: *'); 
    header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
    //header('Content-Type: application/json');
    
    include '../../../../../deployments/connect/connection_invoice.php'; 

    $query = sqlsrv_query($conn, "SELECT CategoryID AS id, CategoryName
                                FROM CATEGORY 
                                WHERE OrganizationID = $_GET[OrganizationID]");
                                
    $res=[];
    while($row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC) ) {
        $res[] = $row;
    }
    
    $cad = json_encode($res);
    echo $cad;
?>
