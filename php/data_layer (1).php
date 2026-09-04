<?php
//**************************************************************************
	$datos = array();
	session_start();
//**************************************************************************
	require 'clase_variables.php';
	require 'clase_mysql.php';
	require 'clase_funciones.php';
//**************************************************************************
	$funciones = new Funciones(1);
	$conexion  = new DB_mysql(1);
//**************************************************************************
	//exit($querys->getElementsCat($idsCategoria, $edo, $mun));

	$pe = $funciones->limpia($_GET['pe']);
	$query = base64_decode($_GET['query']);

	$cadena = "SELECT * FROM vw_resultado_elecciones WHERE idp_electoral_c = ".$idpelectoral.$eleccion.$query." GROUP BY id_casilla";
    $cadenaResultado = $conexion->obtenerlista($cadena);
	$tipo = 1;
	$color = "#2bbbea";

	function random_color_part() {
	    return str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT);
	}

	function random_color() {
	    return random_color_part() . random_color_part() . random_color_part();
	}


	$geojson = array();
	$feature = array();
	$geojson['type'] = 'FeatureCollection';
	
	if($totalRows>0){
		$i=0;
		
		foreach ($rows as $row){
			//$color = "#".random_color();
			$distrito = $conexion->consultadato("SELECT nombre FROM tblc_distrito_electoral WHERE id_distrito_electoral = ".$row->id_distrito);
			$total = $conexion->consultadato("SELECT COUNT(id_registrado) FROM tbl_registrado WHERE tipo != 1 AND seccion_elector = '".$row->nombre."'");

			$InfoPopUp ='<h2>Sección '.$row->nombre.'</h2>';
			$InfoPopUp.='<hr style="margin-top:0; margin-bottom:0.4em;">';
			$InfoPopUp.='<div>Distrito: <strong>'.$distrito.'</strong></div>';
			$InfoPopUp.='<div># de registros: <strong>'.$total.'</strong></div>';

			if($tipo == 3){
								
				$feature [] = array(
					      'type' => 'Feature',
					  'geometry' => array(
						  'type' => 'Polygon',
					     'title' => $row->nombre,
				   'coordinates' => create_polygon($row->coordenadas)
					),
					'properties' =>  array(
						'stroke' => $color,
				  'stroke-width' => 1,
						  'fill' => $color,
				  'fill-opacity' => 0.6,
				     'clickable' => true,
						  'info' => $InfoPopUp
					)
				);
				
				
			} else if($tipo == 1){

				$aCoordsPoint = explode(',', $row->coordenadas);
				$icono = 'archivos/partido_politico/';
				$feature [] = array(
					      'type'  => 'Feature',
					'properties'  =>  array(
						 'title'  => $row->nombre,
						  'icon'  => $icono,
						  'info'  => $InfoPopUp
					),
					   'geometry' => array(
						   'type' => 'Point',
						   'icon' => $icono,
				    'coordinates' => (array) json_decode('['.$aCoordsPoint[0].','.$aCoordsPoint[1].']')
					)
				);
			} else if($tipo == 2){
				$feature []= array(
					      'type' => 'Feature',
					'properties' =>  array(
						'title'  =>  $color,
					   'stroke'  => $color,
				  'stroke-width' => 5,
				'stroke-opacity' => 1,
				   'Description' => $row->descripcion,
				     'clickable' => true,
						  'info' => $InfoPopUp
					),
					  'geometry' => array(
						  'type' => 'MultiLineString',
				   'coordinates' => create_polyline($row->coordenadas)
					)
				);
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
	$conexion->cerrarconexion();
	// convertimos el array de datos a formato json	
	$output = json_encode($geojson, JSON_NUMERIC_CHECK);
	header('Content-type: application/json');
	echo $output;	

	exit();