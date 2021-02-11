<?php 
    header('Access-Control-Allow-Origin: *'); 
    header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
    header('Content-Type: application/json');
    
    include '../../../../../deployments/connect/connection_invoice.php'; 

    $registros = sqlsrv_query($conn, "SELECT * FROM CATEGORY WHERE CategoryID = $_GET[CategoryID]");
      
    $vec = [];
    if ($reg = sqlsrv_fetch_array($registros, SQLSRV_FETCH_ASSOC)){
      $vec[] = $reg;
    }
    
    $cad = json_encode($vec);
    echo $cad;
?>