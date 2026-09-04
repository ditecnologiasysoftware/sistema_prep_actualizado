<?php
if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400');
}

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
}
	require_once("../php/clase_variables.php");
	require_once("../php/clase_mysql.php");
	require_once("../php/clase_funciones.php");

	$conexion = new DB_mysql(1);
	$funciones = new Funciones(1);
		
	$categorias = $conexion->obtenerlista("SELECT * FROM tblc_categoria ORDER BY nombre");
	

	foreach($categorias as $categoria){
		echo '
    <optgroup label="'.$categoria->nombre.'">';

   		$etiquetas = $conexion->obtenerlista("SELECT * FROM tblc_etiqueta WHERE id_categoria = ".$categoria->id_categoria." ORDER BY etiqueta");
        foreach($etiquetas as $etiqueta){
        	echo '
        <option value="'.$etiqueta->id_etiqueta.'">'.$etiqueta->etiqueta.'</option>	
        	';
        }
    	echo '
    </optgroup>
	';
		
		}
?>