<?php
    header('Access-Control-Allow-Origin: *'); 
	header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
	header('Content-Type: application/json');
        
    $json = file_get_contents('php://input');
    $params = json_decode($json);
        
    include '../../../deployments/connect/connection_invoice.php'; 

	$user = sqlsrv_query($conn, "EXEC p_login '$params->Email', '$params->Password' ;");
        
    $res = [];     
	if ($reg = sqlsrv_fetch_array($user, SQLSRV_FETCH_ASSOC)){
		$res[] = $reg;
	}

	class Result {}

	$response = new Result();
	$response->resultado = 'OK';
	$response->mensaje = 'usuario correcto';
	$response->token = -1;

	$response1 = new Result();
	$response1->resultado1 = 'KO';
	$response1->mensaje1 = 'usuario incorrecto';
  
  
	if($res <> []) {
		$usuario_id = current($res[0]);
		$response->token = $usuario_id;
		echo json_encode($response);  
		session_id($usuario_id);
		session_start();
		$_SESSION['user_id'] = $usuario_id;
	}
	else{
		echo json_encode($response1);  
	}
	//sqlsrv_free_stmt($user);
	//echo json_encode($res);
?>
