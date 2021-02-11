<?php 
    header('Access-Control-Allow-Origin: *'); 
    header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
    header('Content-Type: application/json');

    
    include '../../../../../deployments/connect/connection_invoice.php'; 

    $registros = sqlsrv_query($conn, "SELECT OrganizationID FROM USUARIO_ORGANIZATION
                                     WHERE UsuarioID = $_GET[UsuarioID] 
                                    AND OrganizationDefault = $_GET[OrganizationDefault]");


    $res = [];
    if ($reg = sqlsrv_fetch_array($registros, SQLSRV_FETCH_ASSOC)){
      $res[] = $reg;
    }

    $organization_id = current($res[0]);
    echo $organization_id;
    /*$cad = json_encode($res);
    echo $cad;*/
?>