<?php
    header('Access-Control-Allow-Origin: *'); 
    header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
    header('Content-Type: application/json');
    
    include '../../../../../deployments/connect/connection_invoice.php'; 
    
    $query = sqlsrv_query($conn, "SELECT con.*, cli.CustomerName FROM CONTACT con
                                    LEFT JOIN CUSTOMER cli ON con.CustomerID = cli.CustomerID
                                    WHERE OrganizationID = $_GET[OrganizationID]
                                    ORDER BY con.FirstName");
    $res=[];
    while($row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC) ) {
        $res[] = $row;
    }
    
    $cad = json_encode($res);
    echo $cad;
?>
