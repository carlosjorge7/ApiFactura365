<?php
    header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
    header('Content-Type: application/json');
    
    $json = file_get_contents('php://input'); // RECIBE EL JSON DE ANGULAR
    
    $params = json_decode($json); // DECODIFICA EL JSON Y LO GUARADA EN LA VARIABLE
    
    $nombre = $params->nombre;
    $nombreArchivo = $params->nombreArchivo;
    $archivo = $params->base64textString;
    $archivo = base64_decode($archivo);

    include '../../../deployments/connect/connection_invoice.php'; 

	/*sqlsrv_query($conn, "INSERT INTO ORGANIZATION VALUES
									WHERE Email = '$params->Email' AND UsuarioName = '$params->UsuarioName' ");*/
    
    $filePath = $_SERVER['DOCUMENT_ROOT']."/tecinvoice/rest_api_invoice/logoOrg/".$nombreArchivo;
    //$filePath = 'https://almacentecnofun.blob.core.windows.net/invoice-images/'.$nombreArchivo;
    
    file_put_contents($filePath, $archivo);
    //file_put_contents($filePath, $archivo);
    
    
    class Result {}
    // GENERA LOS DATOS DE RESPUESTA
    $response = new Result();
    
    $response->resultado = 'OK';
    $response->mensaje = 'SE SUBIO EXITOSAMENTE';
    
    echo json_encode($response); // MUESTRA EL JSON GENERADO */
?>