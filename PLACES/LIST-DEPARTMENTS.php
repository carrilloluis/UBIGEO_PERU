<?php
/**
  * @author 	Luis Carrillo Gutiérrez, Ing.
  * @date 		04.JULIO.2023
  * @version 	0.0.1.0
  * @about		Módulo de CÓDIGOS de UBIGEO [RENIEC] (servicio para FRONTEND) / Listado de DEPARTAMENTOS
  */

function LIST_DEPARTMENTS()
{
	# $startTime = getMicrotime();
	/**
	 * Establece una conexión con la Base de Datos
	 */
    
	try {
		$conn = new PDO ( DSN_DB, USER_DB, PASSWORD_DB );
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	} catch(Exception $errorConnection) {
		http_response_code(500);
		die(json_encode( [ 'msg' => $errorConnection->getMessage() ] ));
		# return ;
	}
    
	/**
	* __Cadena de texto con la solicitud de datos__ (SQL String QUERY)
	*/
	$strQuery = "SELECT DISTINCT SUBSTR(`Id`, 1, 2) AS code, `Department` AS title FROM `Survey_Places` WHERE LENGTH(`Id`) = 6 ORDER BY `Id`"; 
    # GROUP BY `Id`
   
	/**
	 * Realiza la __Solicitud de datos__ (SQL QUERY)
	 */
	try {
		$stmt = $conn->prepare($strQuery);
		$stmt->execute();
	} catch(Exception $errorQuery) {
		http_response_code(500);
		die(json_encode( [ 'msg' => $errorQuery->getMessage() ] ));
		# return ;
	}

	/**
	 * Genera el __Conjunto de datos resultantes__ (SQL Resultset)
	 */
	$rs = $stmt->fetchAll(PDO::FETCH_ASSOC);
	$sizeResultSet = @intval(count($rs));

	/**
	 * Cierra la conexión a la Base de Datos
	 */
	$conn = null;
	unset($conn);

	# $endTime = getMicrotime();
	/**
	 * Genera una respuesta JSON, con las cabeceras correspondientes [status=200 (Ok), CORS=enabled]
	 */
	http_response_code(200); 
    # header("Access-Control-Allow-Origin: *");
	header('Content-Type: application/json; charset="UTF-8"');
    # "delay" => ($endTime - $startTime),
	echo json_encode( [ "data" => $sizeResultSet === 0 ? [] : $rs, ] );
}
