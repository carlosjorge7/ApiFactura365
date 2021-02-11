<?php
    header('Access-Control-Allow-Origin: *'); 
    header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
    header('Content-Type: application/json');
    
    $json = file_get_contents('php://input');
	$params = json_decode($json);


    include '../../../deployments/connect/connection_invoice.php'; 

	$procedureSQL = "EXEC p_usuario_add '$params->OrganizationName',
										'$params->Email',
										'$params->UsuarioName',
										'$params->UsuarioLastName',
										'$params->Password',
										'$params->ReferOrganizationID';";

	$user = sqlsrv_query($conn, "SELECT Email, UsuarioName FROM USUARIO 
									WHERE Email = '$params->Email' AND UsuarioName = '$params->UsuarioName' ");
	
	$res = [];     
	if ($reg = sqlsrv_fetch_array($user, SQLSRV_FETCH_ASSOC)){
		$res[] = $reg;
	}

    $stmt = sqlsrv_query($conn, $procedureSQL);  

    class Result {}

    $response = new Result();
    $response->response = 'OK';
	$response->mensaje = 'usuario grabado';

	$response_err = new Result();
    $response_err->response_err = 'KO';
    $response_err->mensaje_err = 'El usuario ya existe';
    
	if (!$stmt) {
		echo json_encode($response_err);
	} 
	else {
		/*$UserName = current($res[2]);
		$response->nickName = $UserName;*/
        echo json_encode($response);
		// Recibo un correo electronico a mail
		/*include './src/send_welcome.php';
		$welcome = sendWelcomeMailGun($params->Email, $params->UsuarioName);*/
		//$welcome = sendWelcomeSendGrid($mail, $nomb);
	}


?>