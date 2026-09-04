<?php
  //  error_reporting(E_ALL ^ E_NOTICE);
    ini_set('display_errors', '0');
    ini_set('memory_limit', '1024M');
    ini_set('max_execution_time', 0);
    set_time_limit(0);

header("Content-Type: text/html;charset=utf-8");
require ("clase_funciones.php");
require ("clase_variables.php");
require ("clase_mysql.php");
require_once ("Entity.php");

$entity = Entity::createInstance();
$funciones = new Funciones();
	//LLAMAMOS A LA CLASE CONEXION
// some address values

    $casillas = $entity->objects("SELECT * FROM tblc_casilla WHERE id_municipio = ?", [1]);

    foreach ($casillas as $value) {
    	// building the JSON URL string for Google API call 
	    $direccion = str_replace(' ', '+', trim($value->direccion)).",";

		$url = "https://maps.googleapis.com/maps/api/geocode/json?address=" . rawurlencode($direccion) . "&key=" . rawurlencode($_ENV['GOOGLE_MAPS_API_KEY'] ?? '');

		// Parsing the JSON response from the Google Geocode API to get exact map coordinates:
		// latitude , longitude (see the Google doc. for the complete data return here:
		// https://developers.google.com/maps/documentation/geocoding/.)

		$jsonData   = file_get_contents($url);

		$data = json_decode($jsonData);

		$xlat = $data->{'results'}[0]->{'geometry'}->{'location'}->{'lat'};
		$xlong = $data->{'results'}[0]->{'geometry'}->{'location'}->{'lng'};

		//echo $xlat.",".$xlong;

		$consulta = "UPDATE tblc_casilla SET latitud = '".$xlat."', longitud = '".$xlong."' WHERE id_casilla = '".$value->id_casilla."'";
		
		if(!$entity->execute("UPDATE tblc_casilla SET latitud = ?, longitud = ? WHERE id_casilla = ?", [$xlat, $xlong, $value->id_casilla])){
			echo '<span style="color:red">CASILLA '.$value->seccion.' - '.$value->nombre.' no registrada</span><br><br>';
		}
		echo '<span style="color:green">CASILLA '.$value->seccion.' - '.$value->nombre.' no registrada</span> <br>'.$consulta."<br><br>";
    }

	echo '<script>alert("Actualizacion realizada satisfactoriamente")</script>';
	exit(0);	
?>
