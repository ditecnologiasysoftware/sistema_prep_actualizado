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

	$entity = Entity::createInstance();
	$funciones = new Funciones(1);
	$query = "";
	$idcasilla = $funciones->limpia($_POST['idcasilla']);
	$idtie = $funciones->limpia($_POST['idtie']);
	if ($idtie != '0') {
		$query .= $entity->statement('fragment.tbl_resultados_eleccion.27.1').$idtie."";
	}

	$result='<center><br><table style="width: 100%;">
		        <thead>
		          <tr>
		            <th align="center" style="background-color: #4272A2; color:#FFFFFF;"><b>Candidatos</b></th>
		            <th align="center" style="background-color: #4272A2; color:#FFFFFF;"><b>Total de Votos</b></th>
		          </tr>
		        </thead>
		        <tbody>';
		                $resultado = $entity->statement('tbl_resultados_eleccion.38.1').$idcasilla.$query.$entity->statement('fragment.tbl_resultados_eleccion.38.2') ;
		                $resultadoss = $entity->objects($resultado);
		                foreach($resultadoss as $resultadoo){                  
		                    $result.='<tr>
		                                <td><img style="width: 25px; height: 25px;" src="archivos/partido_politico/'.$resultadoo->icono_pa.'"><font color="'.$resultadoo->color_p.'"> '.$resultadoo->nombre_c.' - <b>'.$resultadoo->nombre_te.'</b></font></td>
		                                <td align="center"><b>'.$resultadoo->resultado.'</b></td>
		                              </tr>';  
		                  } 
	$result.='</tbody>
		      </table>';
       
        $result.='</center>';
                       
         echo $result;
    $entity->cerrarconexion();
?>		
