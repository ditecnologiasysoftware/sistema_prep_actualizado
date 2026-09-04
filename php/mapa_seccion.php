<?php
@session_start();
$id_estado = $_SESSION['id_estado'];
$id_municipio = $_SESSION['id_municipio'];
//**************************************************************************
	$datos = array();
//**************************************************************************
	require 'clase_variables.php';
	require 'clase_mysql.php';
	require 'clase_funciones.php';
//**************************************************************************
	$funciones = new Funciones(1);
	$conexion  = new DB_mysql(1);

	$tipo_mapa = 1;
	$eleccion = '';
    $query = "";
    $cerca = '';
    $centro = '';

    if ($id_estado == 0 && $id_municipio == 0) { 
        $cerca = '4';
        $centro = '21.8852562, -102.2915677';
    }else if ($id_estado != 0 && $id_municipio == 0) {
          $query .= " and estado_c = ".$id_estado."";
          $coordena = $conexion->consultadato("SELECT CONCAT(latitud,',',longitud) as coordenada FROM tblc_estado WHERE id_estado =".$id_estado);
        $cerca = '8';
        $centro = $coordena;
    }else if ($id_estado != 0 && $id_municipio != 0) {
        $query .= " and municipio_c = ".$id_municipio." and estado_c = ".$id_estado."";
        $cerca = '12';
         $coordena = $conexion->consultadato("SELECT CONCAT(latitud,',',longitud) as coordenada FROM tblc_municipio WHERE id_municipio =".$id_municipio);
        $centro = $coordena;
    }
    if(isset($_GET['e'])){ 
      if ($_GET['e'] != 0) {
        $query .= " and estado_c = ".$_GET['e']."";
         $coordena = $conexion->consultadato("SELECT CONCAT(latitud,',',longitud) as coordenada FROM tblc_estado WHERE id_estado =".$_GET['e']);
        $cerca = '8';
        $centro = $coordena;
      }
    }
    if(isset($_GET['m'])){
      if($_GET['m'] != 0){
        $query .= " and municipio_c = ".$_GET['m']."";
         $cerca = '12';
         $coordena = $conexion->consultadato("SELECT CONCAT(latitud,',',longitud) as coordenada FROM tblc_municipio WHERE id_municipio =".$_GET['m']);
        $centro = $coordena;
      }
    }

     if($_GET['c'] != 0){
     	 $idpelectoral = $funciones->limpia($_GET['c']);
         if ($_GET['t'] != '0') {
          $idteleccion = $funciones->limpia($_GET['t']);
          $eleccion .= " and idt_eleccion_c = ".$idteleccion."";
        }              
      }else{
      $idpelectoral = $conexion->consultadato("SELECT idp_electoral_c FROM vw_resultado_elecciones WHERE idp_electoral_c = (SELECT MAX(idp_electoral_c) FROM vw_resultado_elecciones) LIMIT 1");
      }            
      $cadena = "SELECT * FROM vw_resultado_elecciones WHERE idp_electoral_c = ".$idpelectoral.$eleccion.$query." GROUP BY seccion";
      $cadenaResultado = $conexion->obtenerlista($cadena);
      $total = $conexion->numregistros();

    $geojson = array();
	$feature = array();
	$geojson['type'] = 'FeatureCollection';

	$buscar=array(chr(13).chr(10), "\r\n", "\n", "\r", "\x0B", "\t", "\0");
  	$reemplazar=array("", "", "", "");

	if($total>0){
		$i=0;		
		foreach ($cadenaResultado as $value){		
			$InfoPopUp ='';
			    switch ($tipo_mapa) {
			     	case 1: // PUNTO						 	
		    		$cadena = "";
		            $img = "";
		            $tipo="";
		                    $idCasilla =    $value->id_casilla;
		                    $numCasilla = $value->seccion;
		                    $municipio  = $conexion->consultadato("SELECT nombre FROM tblc_municipio WHERE id_municipio =".$value->id_municipio);
		                    $latitud = $value->latitud;
		                    $longitud   = $value->longitud;
		                    $distrito  = $conexion->consultadato("SELECT d.nombre FROM tblc_distrito as d JOIN tblc_seccion as s ON(d.id_distrito = s.id_distrito) WHERE s.nombre =".$value->seccion);
		                    $seccion  = $value->seccion;
		                     if(isset($_GET['t'])){
		                        if ($_GET['t'] != '0') {
		                          $tipo_eleccion = $value->idt_eleccion_c;
		                        }else{  $tipo_eleccion = '0'; }  
		                      }else{ $tipo_eleccion = '0'; }

		                    switch ($value->tipo) {
		                        case 1:
		                            $tipo="Basica";
		                        break;
		                        case 2:
		                            $tipo="Contigua";
		                        break;
		                        case 3:
		                            $tipo="Extraordinaria";
		                        break;               
		                    }                  
		                    // $ganador = $conexion->fetch_array("SELECT vw_resultado_elecciones.*, SUM(resultado) as sumaganador FROM vw_resultado_elecciones WHERE seccion = ".$value->seccion.$eleccion." ORDER BY sumaganador DESC LIMIT 1");
		                     $ganador = $conexion->fetch_array("SELECT vw_resultado_elecciones.*, SUM(resultado) AS resultado_total FROM vw_resultado_elecciones WHERE seccion = ".$value->seccion.$eleccion." GROUP BY idcandidato_c ORDER BY resultado_total DESC LIMIT 1");


		                    $votos_ganador = $ganador['resultado_total'];
		                     if($_GET['c'] != 0){ if ($_GET['t'] != '0') { if ($votos_ganador != 0) { $img = $ganador['icono_pa']; }else{ $img = 'marker.png'; } }else{ $img = 'marker.png'; } }else{ $img = 'marker.png'; }
		                    $icono = "archivos/partido_politico/".$img."";
		                    $nombre_ganador = $ganador['nombre_c'];
		                    $color_ganador = $ganador['color_pa'];
		                     switch ($ganador['tipo']) {
		                        case 1:
		                            $tipo_ganador="Basica";
		                        break;
		                        case 2:
		                            $tipo_ganador="Contigua";
		                        break;
		                        case 3:
		                            $tipo_ganador="Extraordinaria";
		                        break;               
		                    }       
			 				if($tipo_eleccion != 0){   
			 					$contenidoDetalle ='<h3 style="color:'.$color_ganador.'"><img src="archivos/partido_politico/'.$img.'">'.$nombre_ganador.'</h3>';
		                    	$contenidoDetalle .='<b><font style="color:#262626" size="4">TOTAL DE VOTOS: </font><font style="color:#4272A2" size="4">'.$votos_ganador.'</font></b>&nbsp;';                                    
						    }else{

						           $contenidoDetalle ='';
						   	 }
		 						$InfoPopUp .="<div id='content'>";
		                        $InfoPopUp .=      "<div id='siteNotice'>";
		                        $InfoPopUp .=     "</div>".$contenidoDetalle;                                    
		                        $InfoPopUp .=        "<b><font style='color:#262626' size='4'>SECCIÓN: </font><font style='color:#4272A2' size='4'>".$numCasilla."</font></b>&nbsp;";
		                        $InfoPopUp .=                 "<b> - <font style='color:#4272A2' size='4'>".$distrito."</font></b><br>";
		                      	 $InfoPopUp .=  "<div id='tbl_resultado".$seccion."'>";                                    
		                        $InfoPopUp .= "</div>";
		                        $InfoPopUp .="</div>";

                    		//SCRIPT PARA EL PUNTO CAPA
							$feature [] = array(
								      'type'  => 'Feature',
								'properties'  =>  array(
									 'title'  => $nombre_ganador,
									  'icon'  => $icono,
									  'info'  => $InfoPopUp
								),
								   'geometry' => array(
									   'type' => 'Point',
									   'icon' => $icono,
							    'coordinates' => (array) json_decode('['.$latitud.','.$longitud.']')
								)
							);
							//TERMINA SCRIPT PARA EL PUNTO CAPA
			     	break;
			     	case 2: // LINEA
			     			$feature []= array(
								      'type' => 'Feature',
								'properties' =>  array(
									'title'  =>  'EACH nombre',
								   'stroke'  => 'EACH color',
							  'stroke-width' => 3,
							'stroke-opacity' => 1,
							   'Description' => 'EACH descripcion',
							     'clickable' => true,
									  'info' => $InfoPopUp
								),
								  'geometry' => array(
									  'type' => 'MultiLineString',
							   'coordinates' => create_polyline('EACH coordenadas')
								)
							);
			     	break;
			     	case 3: // POLIGONO
			     		$feature [] = array(
								      'type' => 'Feature',
								  'geometry' => array(
									  'type' => 'Polygon',
								     'title' => 'EACH nombre',
							   'coordinates' => create_polygon('EACH coordenadas')
								),
								'properties' =>  array(
									'stroke' => 'EACH color',
							  'stroke-width' => 2,
									  'fill' => 'EACH color',
							  'fill-opacity' => 0.4,
							     'clickable' => true,
									  'info' => $InfoPopUp
								)
							);
							
			     	break;
			    }
			$geojson['features'] = $feature;
			$i++;
			
		}

}

function create_polygon($data_polygon){
			$PoligonoArray= explode(' ',$data_polygon);
			if(count($PoligonoArray)>0){
				$Coordenadas = array();
				foreach($PoligonoArray as $value){
					$Coordenadas[]= (array) json_decode('['.$value.']');
					
				}
				
			}
			return array($Coordenadas);
}

function create_polyline($data_polyline){
			$PolilineaArray= explode(' ',$data_polyline);
			if(count($PolilineaArray)>0){
				$Coordenadas = array();
				foreach($PolilineaArray as $value){
					$Coordenadas[]= (array) json_decode('['.$value.']');
					
				}
			}
			return array($Coordenadas);
}	
	// convertimos el array de datos a formato json	
	$output = json_encode($geojson, JSON_NUMERIC_CHECK);
	header('Content-type: application/json');
	echo $output;	
	exit();