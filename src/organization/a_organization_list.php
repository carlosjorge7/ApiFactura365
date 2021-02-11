<?php
    header('Access-Control-Allow-Origin: *'); 
    header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
    
    include '../../../../../deployments/connect/connection_invoice.php'; 

    //$query = sqlsrv_query($conn, "SELECT * FROM ORGANIZATION ORDER BY ORGANIZATION.OrganizationName");
    $query = sqlsrv_query($conn, "EXEC p_organization_list $_GET[UsuarioID]");
    $res=[];
    while($row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC) ) {
        $res[] = $row;
    }
    
    $cad = json_encode($res);
    echo $cad;
    //header('Content-Type: application/json');
?>
