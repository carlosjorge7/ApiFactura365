<?php  
	
	$headers =  getallheaders();
	session_id($headers['user_id']);
	session_start();

	$proceso = $_REQUEST['proceso'];
	$ConfigField = $_REQUEST['ConfigField'];
	$ConfigVersionNumber = $_REQUEST['ConfigVersionNumber'];
	$ConfigVersionFile = $_REQUEST['ConfigVersionFile'];
	
	if ( isset( $_SESSION['user_id'] ) ) {
		
		include './recurring_functions.php';
		if ($proceso === 'load') {
			$data = loadConfig($proceso, $ConfigField, $ConfigVersionNumber , $ConfigVersionFile);
			echo $data;
		}  elseif ($proceso === 'update'){
			$data = updateConfig($proceso, $ConfigField, $ConfigVersionNumber , $ConfigVersionFile);
			echo $data;
		}

	} else {

		echo 'ERROR, necesitas logarte';

	}

	//funcion para leer
	function loadConfig(
		$proceso, $ConfigField, $ConfigVersionNumber , $ConfigVersionFile
		) {
	include '../../../../deployments/connect/connection_invoice.php';
	if($conn === false ) {
		die(print_r(sqlsrv_errors(), true));
	}
	//llamamos al procedure // Solo los varchar requieren comillas simples
	$tsql_callSP = "EXEC p_config_id 
			'$proceso' , '$ConfigField', '$ConfigVersionNumber', '$ConfigVersionFile';";
	$stmt = sqlsrv_query( $conn, $tsql_callSP); 

	$res = [];
		while( $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC) ) {
			$res[] = $row;
			}

	sqlsrv_free_stmt($stmt);
	return json_encode($res);

	}

	function updateConfig(
		$proceso, $ConfigField, $ConfigVersionNumber , $ConfigVersionFile
		){
		include '../../../../deployments/connect/connection_invoice.php'; 
		if($conn === false ) {
			die(print_r(sqlsrv_errors(), true));
		}
		$procedureSQL = "EXEC p_config_id 
				'$proceso' , '$ConfigField', '$ConfigVersionNumber', '$ConfigVersionFile';";
		$stmt = sqlsrv_query($conn, $procedureSQL);
		
		$stmt = sqlsrv_free_stmt($stmt);
		
		if($stmt === false) {  
			echo "ERROR. Query could not be executed.\n";  
			die(print_r(sqlsrv_errors(), true));  
		}
		sqlsrv_close($conn);  
	}

?>