                    <?php
                    $anio = date('Y');
                    //$meses =array('01'=>"Enero", '02'=>"Febrero", '03'=>"Marzo", '04'=>"Abril", '05'=>"Mayo", '06'=>"Junio", '07'=>"Julio", '08'=>"Agosto", '09'=>"Septiembre", '10'=>"Octubre", '11'=>"Noviembre", '12'=>"Diciembre");
                    $meses =array('04'=>"Abril", '05'=>"Mayo", '06'=>"Junio", '07'=>"Julio");
                    $sentencias = "";
                    $sentencias2 = "";
                    if($id_municipio != 0){
                        $sentencias = " AND id_municipio = ".$id_municipio;
                        $sentencias2 = " AND r.id_municipio = ".$id_municipio;
                    }
                    ?>
                    <style type="text/css">
                    .graficas{ width: 100%; height: 360px;}
                    /* Etiquetas en nube centrada multicolor */
                    .cloud-label-widget-content {text-align: center;}
                    .label-size-1 {color: #9e9e9e; font-size: 12px; margin: 3px;}
                    .label-size-2 {color: #996666; font-size: 25px; margin: 3px;}
                    .label-size-3 {color: #333; font-size: 30px; margin: 3px;}
                    .label-size-4 {color: #0fecec; font-size: 44px; margin: 3px;}
                    .label-size-5 {color: #990000; font-size: 60px; margin: 3px;}
                    </style>
                    <div class="pageheader">
                        <div class="media">
                            <div class="pageicon pull-left">
                                <i class="fa fa-home"></i>
                            </div>
                            <div class="media-body">
                                <ul class="breadcrumb">
                                    <li><a href=""><i class="glyphicon glyphicon-home"></i></a></li>
                                    <li>Gráficas</li>
                                </ul>
                                <h4>Gráficas</h4>
                            </div>
                        </div><!-- media -->
                    </div><!-- pageheader -->
                    
                    <!--FIN ARRIBA---------------------------------------------------------------------------------->
                    <div class="contentpanel">
                     
                     
                      <div class="contentpanel">
                        
                        <div class="row">

                            <div class="col-md-6">

                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        
                                        <h4 class="panel-title">Gráfica por tipo de reporte</h4>
                                        <p></p>
                                    </div>
                                    <div class="panel-body">
                                        
                                         <div id="grafica1" class="graficas"></div>                                         
                                    
                                    </div><!-- panel-body -->

                                </div><!-- panel-default -->

                            </div>

                            <div class="col-md-6">

                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        
                                        <h4 class="panel-title">Gráfica por municipios</h4>
                                        <p></p>
                                    </div>
                                    <div class="panel-body">
                                        
                                         <div id="grafica2" class="graficas"></div>                                         
                                    
                                    </div><!-- panel-body -->
                                    
                                </div><!-- panel-default -->

                            </div>

                            <div class="col-md-6">

                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        
                                        <h4 class="panel-title">Gráfica por tipo de registro</h4>
                                        <p></p>
                                    </div>
                                    <div class="panel-body">
                                        
                                         <div id="grafica3" class="graficas"></div>                                         
                                    
                                    </div><!-- panel-body -->

                                </div><!-- panel-default -->

                            </div>

                            <div class="col-md-6">

                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        
                                        <h4 class="panel-title">Gráfica por Categorías</h4>
                                        <p></p>
                                    </div>
                                    <div class="panel-body">
                                        
                                         <div id="grafica4" class="graficas"></div>                                         
                                    
                                    </div><!-- panel-body -->
                                    
                                </div><!-- panel-default -->

                            </div>

                            <div class="col-md-12">

                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        
                                        <h4 class="panel-title">Nube de Etiquetas</h4>
                                        <p></p>
                                    </div>
                                    <div class="panel-body">
                                        
                                        <div id="etiquetas" class="cloud-label-widget-content">
                                             <?php
                                             $total = $entity->scalar("SELECT (SELECT COUNT(re.id_reporte) FROM tbl_reporte_etiqueta AS re INNER JOIN tbl_reporte AS r ON re.id_reporte = r.id_reporte WHERE re.id_etiqueta = e.id_etiqueta".$sentencias2.") AS conteo FROM tblc_etiqueta AS e ORDER BY conteo DESC LIMIT 1 ");
                                             $etiquetas = $entity->objects("SELECT e.etiqueta, e.id_etiqueta, (SELECT COUNT(re.id_reporte) FROM tbl_reporte_etiqueta AS re INNER JOIN tbl_reporte AS r ON re.id_reporte = r.id_reporte WHERE e.id_etiqueta = re.id_etiqueta".$sentencias2.") AS conteo FROM tblc_etiqueta AS e ORDER BY conteo");
                                             foreach ($etiquetas as $value) {
                                                $porcentaje = $funciones->getporcentaje($value->conteo,$total);
                                                
                                                if($porcentaje <= 20){
                                                    $clase = "label-size-1";
                                                } else if($porcentaje > 20 && $porcentaje <= 40){
                                                    $clase = "label-size-2";
                                                } else if($porcentaje > 40 && $porcentaje <= 60){
                                                    $clase = "label-size-3";
                                                } else if($porcentaje > 60 && $porcentaje <= 80){
                                                    $clase = "label-size-4";
                                                } else if($porcentaje > 80){
                                                    $clase = "label-size-5";
                                                }

                                                 echo ' <a href="reportes&etiqueta='.$value->id_etiqueta.'" class="'.$clase.'">'.$value->etiqueta.'('.$value->conteo.') </a>  ';
                                                
                                             }
                                             ?>
                                        </div>                                         
                                    
                                    </div><!-- panel-body -->
                                    
                                </div><!-- panel-default -->

                            </div>

                            <div class="col-md-12">

                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        
                                        <h4 class="panel-title">Gráfica de registros por meses</h4>
                                        <p></p>
                                    </div>
                                    <div class="panel-body">
                                        
                                         <div id="grafica5" class="graficas"></div>                                         
                                    
                                    </div><!-- panel-body -->
                                    
                                </div><!-- panel-default -->

                            </div>
                            
                        </div><!-- row -->
                        
                        <br/>
                                
                    </div>

                    <script type="text/javascript">
    // Load the Visualization API and the piechart package.
      google.load('visualization', '1.0', {'packages':['corechart']});
      // Set a callback to run when the Google Visualization API is loaded.
      google.setOnLoadCallback(drawChart);
      // Callback that creates and populates a data table,
      // instantiates the pie chart, passes in the data and
      // draws it.
      function drawChart() {
        // Create the data table.
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Tipo de Reporte');
        data.addColumn('number', 'Reportes');
        data.addRows([
            <?php
            $denuncia = $entity->scalar("SELECT COUNT(id_reporte) FROM tbl_reporte WHERE tipo_reporte = 1".$sentencias);
            $observaciones = $entity->scalar("SELECT COUNT(id_reporte) FROM tbl_reporte WHERE tipo_reporte = 2".$sentencias);
            
            echo "['Denuncias (".$denuncia.")', ".$denuncia."],
                ";
            echo "['Observaciones (".$observaciones.")', ".$observaciones."]";
            ?>
        ]);
        // Set chart options
        var options = {
          //legend: 'none',
        pieSliceText: 'label',
        //title: 'Swiss Language Use (100 degree rotation)',
        pieStartAngle: 100,
        chartArea:{width:'90%',height:'90%'},
        };

        // Instantiate and draw our chart, passing in some options.
        var chart = new google.visualization.PieChart(document.getElementById('grafica1'));
        chart.draw(data, options);

        //GRAFICA TOTALES POR Municipios
    
    var data = new google.visualization.DataTable();
        data.addColumn('string', 'Municipios');
        data.addColumn('number', 'Reportes');
        data.addRows([
    <?php
    $municipios = $entity->objects("SELECT m.nombre, (SELECT COUNT(r.id_reporte) FROM tbl_reporte AS r WHERE r.id_municipio = m.id_municipio) AS conteo FROM tblc_municipio AS m WHERE (SELECT COUNT(r.id_reporte) FROM tbl_reporte AS r WHERE r.id_municipio = m.id_municipio) != 0 ORDER BY conteo DESC");
    $cadena = "";
    foreach($municipios as $municipio){
        $cadena .= ",
        ['".$municipio->nombre." (".$municipio->conteo.")', ".$municipio->conteo."]";
    }
    echo trim($cadena, ',');
    
    ?>
        ]);

      var options = {
        //legend: 'none',
        //pieSliceText: 'label',
        //title: 'Swiss Language Use (100 degree rotation)',
        //pieStartAngle: 100,
        chartArea:{width:'95%',height:'95%'},
        //is3D: true,
      };

        var chart2 = new google.visualization.PieChart(document.getElementById('grafica2'));
        chart2.draw(data, options);

        //GRAFICA TOTALES POR tipo de registros
    
    var data = new google.visualization.DataTable();
        data.addColumn('string', 'Tipo de registro');
        data.addColumn('number', 'Reportes');
        data.addRows([
    <?php
            $real = $entity->scalar("SELECT COUNT(id_reporte) FROM tbl_reporte WHERE tipo_registro = 1".$sentencias);
            $fuera = $entity->scalar("SELECT COUNT(id_reporte) FROM tbl_reporte WHERE tipo_registro = 2".$sentencias);
            
            echo "['En tiempo real (".$real.")', ".$real."],
                ";
            echo "['Fuera de tiempo (".$fuera.")', ".$fuera."]";
            ?>
        ]);

      var options = {
        //legend: 'none',
        //pieSliceText: 'label',
        //title: 'Swiss Language Use (100 degree rotation)',
        //pieStartAngle: 100,
        chartArea:{width:'95%',height:'95%'},
        is3D: true,
      };

        var chart3 = new google.visualization.PieChart(document.getElementById('grafica3'));
        chart3.draw(data, options);

        //GRAFICA TOTALES POR Categorias de Etiquetas
    
    var data = new google.visualization.DataTable();
        data.addColumn('string', 'Categoría');
        data.addColumn('number', 'Reportes');
        data.addRows([
    <?php
    $categorias = $entity->objects("SELECT c.nombre, (SELECT COUNT(re.id_reporte) FROM tbl_reporte_etiqueta AS re INNER JOIN tbl_reporte AS r ON re.id_reporte = r.id_reporte INNER JOIN tblc_etiqueta AS e ON e.id_etiqueta = re.id_etiqueta WHERE e.id_categoria = c.id_categoria".$sentencias2.") AS conteo FROM tblc_categoria AS c ORDER BY conteo DESC");
    $cadena = "";
    foreach($categorias as $categoria){
        $cadena .= ",
        ['".$categoria->nombre." (".$categoria->conteo.")', ".$categoria->conteo."]";
    }
    echo trim($cadena, ',');
    ?>
        ]);

      var options = {
        //legend: 'none',
        //pieSliceText: 'label',
        //title: 'Swiss Language Use (100 degree rotation)',
        //pieStartAngle: 100,
        chartArea:{width:'95%',height:'95%'},
        is3D: true,
      };

        var chart4 = new google.visualization.PieChart(document.getElementById('grafica4'));
        chart4.draw(data, options);
        
        //GRAFICA DE REGISTROS MENSUALES
        var data = google.visualization.arrayToDataTable([
          ['Meses', 'Denuncias', 'Observaciones'],
          <?php
          foreach ($meses as $clave => $valor) {

            $denuncias2 = $entity->scalar("SELECT COUNT(id_reporte) FROM tbl_reporte WHERE tipo_reporte = 1 AND MONTH(fecha_registro) = '".$clave."' AND YEAR(fecha_registro) = '".$anio."'".$sentencias); 
            $observaciones2 = $entity->scalar("SELECT COUNT(id_reporte) FROM tbl_reporte WHERE tipo_reporte = 2 AND MONTH(fecha_registro) = '".$clave."' AND YEAR(fecha_registro) = '".$anio."'".$sentencias); 
            if($clave == '07'){
                            
                echo "['".$valor."', ".$denuncias2.", ".$observaciones2."]
                ";
                }
            else{
                echo "['".$valor."', ".$denuncias2.", ".$observaciones2."],
                ";
                }
            }
          ?>
        ]);

        var options = {
        //title: 'Swiss Language Use (100 degree rotation)',
        //chartArea:{width:'80%',height:'85%'},
        };
        var chart5 = new google.visualization.LineChart(document.getElementById('grafica5'));
        chart5.draw(data, options);

      }

    </script>