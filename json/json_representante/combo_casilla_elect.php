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

	require_once("../../php/clase_variables.php");
	require_once("../../php/clase_mysql.php");
	require_once("../../php/clase_funciones.php");

	$conexion = new DB_mysql();
	$funciones = new Funciones();

	$consulta = "SELECT id_casilla as id, CONCAT('Casilla Número: ', numero) as valor FROM tblc_casilla ORDER BY numero ASC";
?>
 <option value="0">-- Seleccionar Casilla --</option>
<?php
	$funciones->llenarcombo($consulta);
?> 