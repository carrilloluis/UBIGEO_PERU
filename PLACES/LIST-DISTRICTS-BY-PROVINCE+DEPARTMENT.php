<?php
/**
  * @author 	Luis Carrillo Gutiérrez, Ing.
  * @date 		04.JULIO.2023
  * @version 	0.0.1.0
  * @about		Módulo de CÓDIGOS de UBIGEO [RENIEC] (servicio para FRONTEND) / Listado de DISTRITOS
  */

function LIST_DISTRICTS_BY_DEPARTMENT_AND_PROVINCE (
	$pIdDepartment_, /* Codigo de 2 CARACTERES para DEPARTAMENTO */
	$pIdProvince_ /* Codigo de 2 CARACTERES para PROVINCIA */
)
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
	$strQuery = "SELECT DISTINCT SUBSTR(`Id`, 5, 2) AS code, `District` AS title FROM `Survey_Places` WHERE LENGTH(`Id`) = 6 AND SUBSTR(id, 1, 2) = ? AND SUBSTR(id, 3, 2) = ? ORDER BY `Id`"; # GROUP BY `Id`

	/**
	 * Realiza la __Solicitud de datos__ (SQL QUERY)
	 */
	try {
		$stmt = $conn->prepare($strQuery);
		$stmt->bindParam(1, $pIdDepartment_, PDO::PARAM_STR, 2);
		$stmt->bindParam(2, $pIdProvince_, PDO::PARAM_STR, 2);
		$stmt->execute();
	} catch(Exception $error) {
		http_response_code(500);
		die(json_encode( [ 'msg' => $error->getMessage() ] ));
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
	echo json_encode( [ 'data' => $sizeResultSet === 0 ? [] : $rs, ] );
}
