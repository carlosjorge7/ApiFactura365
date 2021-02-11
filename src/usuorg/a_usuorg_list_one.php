<?php 
    header('Access-Control-Allow-Origin: *'); 
    header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
    header('Content-Type: application/json');
    
    include '../../../../../deployments/connect/connection_invoice.php'; 

    $registros = sqlsrv_query($conn, "SELECT uo.*, u.Email FROM USUARIO_ORGANIZATION uo
                                      LEFT JOIN USUARIO u ON uo.UsuarioID = u.UsuarioID
                                      WHERE Usuario_OrganizationID = $_GET[Usuario_OrganizationID]");
      
    if ($reg = sqlsrv_fetch_array($registros, SQLSRV_FETCH_ASSOC)){
      $vec[] = $reg;
    }

    $cad = json_encode($vec);
    echo $cad;
?>