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
date_default_timezone_set('America/Mexico_City');
//*****************************************************************************************************************
  require_once("../../php/clase_variables.php");
  require_once("../../php/clase_mysql.php");
  require_once("../../php/clase_funciones.php");

    $conexion = new DB_mysql();
    $funciones = new Funciones();


   $lat = $_POST['lat'];
   $lon = $_POST['lon'];
	 $comb_proceso_elect = $_POST['comb_proceso_elect'];
     
    if ($_POST['idcasilla'] == 0) {
       $idcasilla = $_POST['comb_casilla'];
      }else{
        $idcasilla = $_POST['idcasilla'];
    }    
   $idrepresenta = $_POST['idrepresenta'];
   // $registroExiste = $conexion->consultadato("SELECT COUNT(r.id_resultado) FROM tbl_resultado as r JOIN tblc_candidato as c ON(r.id_candidato = c.id_candidato) WHERE c.id_proceso_electoral =".$comb_proceso_elect." and id_casilla = ".$idcasilla." and id_representante =".$idrepresenta);
   
    foreach($_POST['candidatos'] as $indice => $idcandidato){  
      $registroExiste = $conexion->consultadato("SELECT COUNT(r.id_resultado) FROM tbl_resultado as r JOIN tblc_candidato as c ON(r.id_candidato = c.id_candidato) WHERE c.id_proceso_electoral =".$comb_proceso_elect." and r.id_candidato = ".$idcandidato." and r.id_casilla = ".$idcasilla." and r.id_representante =".$idrepresenta);

      if ($registroExiste == 0) {
        $total_votos = $_POST['total_votos'.$idcandidato];
        $consulta = "INSERT INTO tbl_resultado(id_candidato, id_casilla, resultado, id_representante, fecha_registro, latitud, longitud) VALUES(".$idcandidato.", ".$idcasilla.", '".$total_votos."' , '".$idrepresenta."', NOW(), '".$lat."', '".$lon."')";

        if($conexion->consulta($consulta) == 0){ echo "0"; exit(0);   }
       
       }else{
        echo "3";  exit(0);
       }
    }
    echo "¡Los Resultados se guardardaron satisfactoriamente!";

	
?>