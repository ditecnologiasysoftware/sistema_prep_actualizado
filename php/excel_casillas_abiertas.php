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

	if ($id_estado != 0) {
       $sentencia .= " and m.id_estado = ".$id_estado."";
    }
    if ($id_municipio != 0) {
       $sentencia .= " and c.id_municipio = ".$id_municipio."";
    }
    if(isset($_GET['c'])){
        $sentencia .= " AND ec.id_casilla = '".$_GET['c']."'";
        $peticion_enlace .= "&c=".$_GET['c'];
    }

	$cadena = "SELECT ec.*, c.nombre as casilla, c.seccion, c.seccion, m.nombre as muni  FROM tbl_estatus_casilla as ec 
            JOIN tblc_casilla as c ON(c.id_casilla = ec.id_casilla) 
            JOIN tblc_municipio as m ON(c.id_municipio = m.id_municipio) 
            JOIN tblc_estado as e ON(e.id_estado = m.id_estado) 
            WHERE ec.tipo = 1".$sentencia." ORDER BY ec.fecha_hora ASC";

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
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue($letra.$fila,"Casillas aperturadas");
		//$objPHPExcel->getActiveSheet()->getStyle("A1")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle("A1")->getFont()->setBold(true)->setName('Verdana')->setSize(20)->getColor()->setRGB('7c3794');
		$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.$fila.':I'.$fila);
											
		$fila++;
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("A".$fila,"Total de registros encontrados: ".$total);
		$objPHPExcel->getActiveSheet()->getStyle("A".$fila)->getFont()->setBold(true)->setName('Verdana')->setSize(13)->getColor()->setRGB('eb212a');
		$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.$fila.':I'.$fila);
		
		$fila++;
		$fila++;
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("A".$fila,"Sección");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("B".$fila,"Casilla");
		//$objPHPExcel->setActiveSheetIndex(0)->setCellValue("C".$fila,"Hora");

		$objPHPExcel->getActiveSheet()->getStyle("A".$fila.":C".$fila)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle("A".$fila.":C".$fila)->getFill()->getStartColor()->setARGB('c75e76');
		//$objPHPExcel->getActiveSheet()->getStyle("A".$fila.":C".$fila)->getFont()->setBold(true)->getColor()->setRGB('FFFFFF');

		$fila++;
		foreach($datos as $dato)
		{
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue("A".$fila,$dato->seccion);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue("B".$fila,$dato->casilla);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue("C".$fila,$funciones->ordenaFechaHora($dato->fecha_hora));
			
			$fila++;			
		}

    foreach(range('A','C') as $columnID){
		    $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
		}

		
	// Se modifican los encabezados del HTTP para indicar que se envia un archivo de Excel.
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="casillas.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
?>