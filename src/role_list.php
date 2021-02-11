<?php

 /** http://localhost/php/CRUD_tecdinvoice/productos/p_listar_productos.php*/

 
 $headers =  getallheaders();
 session_id($headers['user_id']);
 session_start();
 

 if(!isset($_SESSION['user_id'])){
     echo "User failed";
 }
 else{
	
	$roco = $_GET['RoleCode'];
	
    $data = getRole(
					$roco
				);
    echo $data;
}
 
    function getRole(
					$roco
				){

        //include '../conexion.php';
        include '../../../../deployments/connect/connection_invoice.php'; 
        if($conn === false ) {
            die( print_r( sqlsrv_errors(), true));
        }
        
		$tsql_callSP = "EXEC p_role_list
				 '$roco' ;";
		$stmt = sqlsrv_query( $conn, $tsql_callSP); 
		
		$res = [];
		while( $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC) ) {
			$res[] = $row;
			}

		sqlsrv_free_stmt($stmt);
		return json_encode($res);
    }
?>