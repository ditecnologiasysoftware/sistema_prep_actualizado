<?php 
	@session_start();
	$id_estado = $_SESSION[id_estado];
    $id_municipio = $_SESSION[id_municipio];
	error_reporting(E_ALL ^ E_NOTICE);
	ini_set('display_errors', '0');

	date_default_timezone_set('Mexico/General');

	require ("clase_variables.php");
    require ("clase_mysql.php"); 
	require ("clase_funciones.php");
	
	$conexion = new DB_mysql(1);
	$funciones = new Funciones(1);
	
	require_once 'PHPExcel_1.8.0/Classes/PHPExcel.php';
	$objPHPExcel = new PHPExcel();

	//CONSULTAS
	$sentencia = "";
	$inner = "";

	if($id_municipio != 0){
		$sentencia .= " AND r.id_municipio = '".$id_municipio."'";
	}else if($id_estado != 0){
		$sentencia .= " AND m.id_estado = '".$id_estado."'";
	}

	if(isset($_GET['municipio_busqueda']) && $_GET['municipio_busqueda'] != 0){
	    $sentencia .= " AND r.id_municipio = '".$funciones->limpia($_GET['municipio_busqueda'])."'";
	    $peticion_enlace .= "&municipio_busqueda=".$_GET['municipio_busqueda'];
	}

	if($_GET['casilla_busqueda'] != 0){
	    $sentencia .= " AND r.id_casilla = '".$funciones->limpia($_GET['casilla_busqueda'])."'";
	}

	if($_GET['hora_busqueda'] != "" && $_GET['hora2_busqueda'] != ""){
	    $sentencia .= " AND date_format(r.fecha_registro, '%H:%i') >= '".$_GET['hora_busqueda']."'";
	    $sentencia .= " AND date_format(r.fecha_registro, '%H:%i') <= '".$_GET['hora2_busqueda']."'";
	}

	if(isset($_GET['folio_busqueda']) && $_GET['folio_busqueda'] != ""){
	    $sentencia .= " AND r.folio LIKE '%".$funciones->limpia($_GET['folio_busqueda'])."%'";
	}

	if(isset($_GET['servicio_busqueda']) && $_GET['servicio_busqueda'] != 0){
	    $sentencia .= " AND r.tipo_reporte = '".$funciones->limpia($_GET['servicio_busqueda'])."'";
	}

	if(isset($_GET['tipo_busqueda']) && $_GET['tipo_busqueda'] != 0){
	    $sentencia .= " AND r.tipo_registro = '".$funciones->limpia($_GET['tipo_busqueda'])."'";
	}

	if(isset($_GET['etiqueta']) && $_GET['etiqueta'] != 0){
	    $sentencia .= " AND re.id_etiqueta = '".$funciones->limpia($_GET['etiqueta'])."'";
	    $inner = " INNER JOIN tbl_reporte_etiqueta AS re ON r.id_reporte = re.id_reporte";
	}

	$cadena = "SELECT r.*, m.nombre as municipio, e.nombre as estado, date_format(r.fecha_registro, '%H:%i') as hora, date_format(r.fecha_registro, '%Y-%m-%d') as fecha2 
	FROM tbl_reporte AS r 
	INNER JOIN tblc_municipio AS m ON r.id_municipio = m.id_municipio 
	INNER JOIN tblc_estado AS e ON m.id_estado = e.id_estado 
	".$inner."
	WHERE r.id_reporte != 0".$sentencia." ORDER BY r.fecha_registro DESC";

	$datos = $conexion->obtenerlista($cadena);
	$total = $conexion->numregistros();

	//Informacion del excel
   	$objPHPExcel->getProperties()
        ->setCreator("Reportes")
        ->setLastModifiedBy("Reportes")
        ->setTitle("Reportes")
        ->setSubject("")
        ->setDescription("Documento generado con PHPExcel")
        ->setCategory("Reportes");
				
	$hoy = date("m/d/Y");		
	$letra = "A";
	$fila = 1;
		$objPHPExcel->getActiveSheet()->getColumnDimension("A")->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension("B")->setAutoSize(true);
		$objPHPExcel->getSheet(0)->setTitle("reportes");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue($letra.$fila,"Reportes");
		//$objPHPExcel->getActiveSheet()->getStyle("A1")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle("A1")->getFont()->setBold(true)->setName('Verdana')->setSize(20)->getColor()->setRGB('7c3794');
		$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.$fila.':I'.$fila);
											
		$fila++;
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("A".$fila,"Total de registros encontrados: ".$total);
		$objPHPExcel->getActiveSheet()->getStyle("A".$fila)->getFont()->setBold(true)->setName('Verdana')->setSize(13)->getColor()->setRGB('eb212a');
		$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.$fila.':I'.$fila);
		
		$fila++;
		$fila++;
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("A".$fila,"Folio");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("B".$fila,"Tipo");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("C".$fila,"Municipio");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("D".$fila,"Casilla");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("E".$fila,"Descripción");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("F".$fila,"Registro");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("G".$fila,"Fecha");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("H".$fila,"Nombre");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("I".$fila,"Incidencias");

		$objPHPExcel->getActiveSheet()->getStyle("A".$fila.":I".$fila)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle("A".$fila.":I".$fila)->getFill()->getStartColor()->setARGB('c75e76');
		$objPHPExcel->getActiveSheet()->getStyle("A".$fila.":I".$fila)->getFont()->setBold(true)->getColor()->setRGB('FFFFFF');

		$fila++;
		foreach($datos as $dato)
		{
			$etiquetas = "";
			$retiquetas = $conexion->obtenerlista("SELECT e.etiqueta FROM tbl_reporte_etiqueta as re 
				INNER JOIN tblc_etiqueta as e ON e.id_etiqueta = re.id_etiqueta 
				WHERE re.id_reporte = ".$dato->id_reporte);

			foreach ($retiquetas as $retiqueta) {
				$etiquetas .= $retiqueta->etiqueta.", ";
			}

			$etiquetas = trim($etiquetas,", ");

			$objPHPExcel->setActiveSheetIndex(0)->setCellValue("A".$fila,$dato->folio);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue("B".$fila,$funciones->tipo_reporte($dato->tipo_reporte));
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue("C".$fila,$dato->municipio.', '.$dato->estado);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue("D".$fila,$funciones->llenarCasillatbl2($dato->id_casilla));
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue("E".$fila,$dato->descripcion);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue("F".$fila,$funciones->tipo_registro($dato->tipo_registro));
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue("G".$fila,$funciones->fecha2($dato->fecha2).' - '.$dato->hora.' hrs.');
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue("H".$fila,$dato->nombre);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue("I".$fila,$etiquetas);
			$fila++;			
			
		}

    foreach(range('A','I') as $columnID){
		    $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
		}

		
	// Se modifican los encabezados del HTTP para indicar que se envia un archivo de Excel.
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="reportes.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
?>