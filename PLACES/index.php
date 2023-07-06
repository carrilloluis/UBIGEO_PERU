<?php
/**
  * @author 	Luis Carrillo Gutiérrez, Ing.
  * @date 		04.JULIO.2023
  * @version 	0.0.1.0
  * @about		Módulo de CÓDIGOS de UBIGEO [RENIEC] 
  */

if (apache_getenv('ENVIRONMENT') !== strtolower('PROD')) {
	define('SERVER_DB', '127.0.0.1'); /* ENTORNO de DESARROLLO [dev] */
	define('PORT_DB', 3306);
	define('DATABASE_NAME', '+++');
	define('USER_DB', 'root');
	define('PASSWORD_DB', '+++');
} else {
	define('SERVER_DB', '127.0.0.1'); /* ENTORNO de PRODUCCIÓN [prod] */
	define('PORT_DB', 3306);
	define('DATABASE_NAME', '+++');
	define('USER_DB', 'root');
	define('PASSWORD_DB', '+++');
}
define('DSN_DB', 'mysql:host=' . SERVER_DB . ':' . @strval(PORT_DB) . ';dbname=' . DATABASE_NAME . ';charset=utf8');
  
require_once('./SimpleRoute.php');
# require_once('./JSONWebToken.php');

	# Listados de DEPARTAMENTOS / REGIONES
	Route::add('/PLACES/v1/departments/', function()
	{
		require_once('./LIST-DEPARTMENTS.php');
		if ( function_exists('LIST_DEPARTMENTS') ) { 
			LIST_DEPARTMENTS();
		} else {
			http_response_code(405);
			die(json_encode( ['msg' => 'El Método/ENDPOINT/API REST... solicitado NO EXISTE' ] ));
			# return ;
		}
	}, 'GET');

	
	# Listados de PROVINCIAS
	Route::add('/PLACES/v1/provinces/([0-9]{2})/', function( $idDepartment )
	{
		require_once('./LIST-PROVINCES-BY-DEPARTMENT.php');
		if ( function_exists('LIST_PROVINCES_BY_DEPARTMENT') ) { 
			LIST_PROVINCES_BY_DEPARTMENT ( $idDepartment );
		} else {
			http_response_code(405);
			die(json_encode( ['msg' => 'El Método/ENDPOINT/API REST... solicitado NO EXISTE' ] ));
			# return ;
		}
	}, 'GET');

	# Listados de DISTRITOS
	Route::add('/PLACES/v1/districts/([0-9]{2})/([0-9]{2})/', function( $idDepartment, $idProvince )
	{
		# Extraer datos del JWT [Id] [Rol] 
		require_once('./LIST-DISTRICTS-BY-PROVINCE+DEPARTMENT.php');
		if ( function_exists('LIST_DISTRICTS_BY_DEPARTMENT_AND_PROVINCE') ) { 
			LIST_DISTRICTS_BY_DEPARTMENT_AND_PROVINCE ( $idDepartment, $idProvince );
		} else {
			http_response_code(405);
			die(json_encode( ['msg' => 'El Método/ENDPOINT/API REST... solicitado NO EXISTE' ] ));
			# return ;
		}
	}, 'GET');

 	Route::add('/PLACES/v1/__version__/', function()
	{
		$months_ = array('Enero', 'Febrero', 'Marzo' , 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
		echo '&mu;Servicio de Gestión de CÓDIGOS de UBIGEO (RENIEC)<br />UNA / Puno <br />', @strval($months_[3]), '.2023 &mdash; ', $months_[@intval(date("m") - 1)], '.', date("Y"),'<br /> OTI @ UNA Puno &mdash; versi&oacute;n ', apache_getenv('VERSION');
	}, 'GET');

	Route::pathNotFound(function($path) 
	{
		echo '<h1>Error 404 - ["'.$path.'"]<br /> URL/Página no encontrada</h1>';
	});

	Route::run('/', true, true);
?>