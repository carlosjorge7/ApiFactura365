<?php 
    header('Access-Control-Allow-Origin: *'); 
    header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
    header('Content-Type: application/json');
    
    include '../../../../../deployments/connect/connection_invoice.php'; 

    $registros = sqlsrv_query($conn, "SELECT * FROM ESTIMATE_LINE WHERE Estimate_LineID = $_GET[Estimate_LineID]");
      
    if ($reg = sqlsrv_fetch_array($registros, SQLSRV_FETCH_ASSOC)){
      $vec[] = $reg;
    }

    $cad = json_encode($vec);
    echo $cad;
?>