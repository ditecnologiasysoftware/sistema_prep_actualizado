    <?php
    require "../php/inicializandoDatosExterno.php";

    $anio = date('Y');
    //$meses =array('01'=>"Enero", '02'=>"Febrero", '03'=>"Marzo", '04'=>"Abril", '05'=>"Mayo", '06'=>"Junio", '07'=>"Julio", '08'=>"Agosto", '09'=>"Septiembre", '10'=>"Octubre", '11'=>"Noviembre", '12'=>"Diciembre");
    $meses =array('04'=>"Abril", '05'=>"Mayo", '06'=>"Junio", '07'=>"Julio");
    $query_pe = "";
    $query = "";
    $query2 = "";

    $query_c1 = "";
    $query_c2 = "";

    if ($id_municipio != 0) {
       $query_pe .= $entity->statement('fragment.graficas_resultado_modal.15.1').$id_municipio."";
       $query_c1 .= $entity->statement('fragment.graficas_resultado_modal.16.2').$id_municipio."";
        $query_c2 .= $entity->statement('fragment.graficas_resultado_modal.17.3').$id_municipio."";
    }
    elseif ($id_estado != 0) {
       $query_pe .= $entity->statement('fragment.graficas_resultado_modal.20.4').$id_estado."";
        $query_c1 .= $entity->statement('fragment.graficas_resultado_modal.21.5').$id_estado."";
        $query_c2 .= $entity->statement('fragment.graficas_resultado_modal.22.6').$id_estado."";
    }


    $idcandidatoP = $funciones->limpia($_POST['id']);

    ?>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h4 id="titulo" class="modal-title">Grafia de resultados</h4>
</div>

<div class="modal-body">
    <div class="row">
        <div class="col-sm-12 col-md-12 col-xs-12">
            <div class="white-box">
                <div class="row">

                    <div class="col-md-12 form-group">
                        
                        <!------------------------------>

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

                             
                              <div class="contentpanel">
                              <?php 
                              if(isset($_GET['estado_busqueda']) ){ 
                                if($_GET['estado_busqueda'] != 0){

                                     //condiciona un estado y municipio
                                } 
                              }

                            $query = "";
                            $query .= $entity->statement('fragment.graficas_resultado_modal.67.7').$idcandidatoP;                                    


                             if ($id_municipio != 0) {
                                $query .= $entity->statement('fragment.graficas_resultado_modal.71.8').$id_municipio."";
                                $query2 .= $entity->statement('fragment.graficas_resultado_modal.72.9').$id_municipio."";
                             }
                             elseif ($id_estado != 0) {
                                $query .= $entity->statement('fragment.graficas_resultado_modal.75.10').$id_estado."";
                                $query2 .= $entity->statement('fragment.graficas_resultado_modal.76.11').$id_estado."";
                             }
                             
                             $cadena = $entity->statement('graficas_resultado_modal.79.1').$query.$entity->statement('fragment.graficas_resultado_modal.79.12');
                             $cadena2 = $entity->statement('graficas_resultado_modal.80.2').$query;                                                              
                              
                              $totalRegistros = $entity->scalar($cadena2);
                              $resul_lista = $entity->objects($cadena);
                            
                            foreach($resul_lista as $resultado_fila){

                                $tipo_eleccion = $entity->row($entity->statement('graficas_resultado_modal.87.3').$resultado_fila->id_tipo_eleccion);
                              $entity->scalar($entity->statement('graficas_resultado_modal.88.4').$query_c1.$entity->statement('fragment.graficas_resultado_modal.88.13'));
                                $totalCasillas = $entity->numregistros();
                                  
                                $entity->objects($entity->statement('graficas_resultado_modal.93.5').$resultado_fila->id_proceso_electoral.$query2.$entity->statement('fragment.graficas_resultado_modal.91.14')); 
                                $totalCasillasRegistradas = $entity->numregistros();
                                $casillas_faltantes = $totalCasillas - $totalCasillasRegistradas;

                              ?>
                              <font size="5" color="#2D55AE"><?php echo strtoupper($resultado_fila->descripcion) ?></font>
                              <hr>

                              <div class="row"><!-- row grafias 1-->

                                  <div class="col-md-6">
                                      <div class="panel panel-default">
                                          <div class="panel-heading">
                                              <h4 class="panel-title">Resultados por Casillas</h4>
                                              <p></p>
                                          </div>
                                          <div class="panel-body">
                                          <center><font id="datosCasilla<?= $resultado_fila->id_proceso_electoral ?>" size="3" style="color: #335C93; font-weight: bold;"></font></center>
                                          <div id="grafica-casilla<?= $resultado_fila->id_proceso_electoral ?>" class="graficas" style="margin-top: -2em; margin-left: 3em;"></div><br>
                                          <div style="text-align: right;">
                                            <h4><b><a class="fancybox" data-fancybox-type='iframe' href='pg/casilla_faltante.php?c=<?php echo base64_encode($resultado_fila->id_proceso_electoral);?>' title="Casillas Faltantes">Casillas Faltantes: <?php echo $casillas_faltantes ?></a></b></h4>
                                          </div>
                                        </div><!-- panel-body -->
                                      </div><!-- panel-default -->
                                  </div>

                                  <div class="col-md-6">
                                      <div class="panel panel-default">
                                          <div class="panel-heading">                                        
                                              <h4 class="panel-title">Gráfica por Candidatos</h4>
                                              <p></p>
                                          </div>
                                          <div class="panel-body">                                        
                                               <div id="grafica<?= $resultado_fila->id_proceso_electoral ?>" class="graficas text-center"></div>                                
                                          </div><!-- panel-body -->                                    
                                      </div><!-- panel-default -->
                                  </div>

                                  <div class="col-md-12">
                                      <div class="panel panel-default">
                                          <div class="panel-heading">                                        
                                              <h4 class="panel-title">Gráfica por Partidos políticos</h4>
                                              <p></p>
                                          </div>
                                          <div class="panel-body">                                        
                                               <div id="grafica_candidato<?= $resultado_fila->id_proceso_electoral ?>" class="graficas text-center" style="width: 100%; height: 600px;"></div>                                
                                          </div><!-- panel-body -->                                    
                                      </div><!-- panel-default -->
                                  </div>

                              </div><!-- row grafias 1-->

                              <script type="text/javascript">
                                    // Load the Visualization API and the piechart package.
                                google.load('visualization', '1.0', {'packages':['corechart']});
                                // Set a callback to run when the Google Visualization API is loaded.
                                google.setOnLoadCallback(drawChart<?= $resultado_fila->id_proceso_electoral ?>);
                                // Callback that creates and populates a data table,
                                // instantiates the pie chart, passes in the data and
                                // draws it.
                              function drawChart<?= $resultado_fila->id_proceso_electoral ?>() {
                                   
                                  //GRAFICA TOTALES POR PARTIDO POLITICO    
                                  var data = new google.visualization.DataTable();
                                      data.addColumn('string', 'Partidos');
                                      data.addColumn('number', 'Votos');
                                      data.addRows([
                                  <?php
                                      $acta = $entity->row($entity->statement('graficas_resultado_modal.161.6').$resultado_fila->id_proceso_electoral.$entity->statement('fragment.graficas_resultado_modal.159.15'));
                                      $votopartidos = $entity->objects($entity->statement('graficas_resultado_modal.162.7').$resultado_fila->id_proceso_electoral.$query2.$entity->statement('fragment.graficas_resultado_modal.160.16'));
                                      $cadena = "";
                                      foreach($votopartidos as $partido){
                                          $cadena .= ",
                                          ['".$partido->nombre_c." (".$partido->votos.")', ".$partido->votos."]";
                                      }
                                      $cadena .= ",
                                          ['NO REGISTRADOS (".$acta['nr'].")', ".$acta['nr']."]";
                                      $cadena .= ",
                                          ['VOTOS NULOS (".$acta['nulos'].")', ".$acta['nulos']."]";
                                      echo trim($cadena, ',');    
                                  ?>
                                      ]);
                                    var options = {
                                      //legend: 'none',
                                      //pieSliceText: 'label',
                                      //title: 'Swiss Language Use (100 degree rotation)',
                                      //pieStartAngle: 100,
                                      width: 520,
                                      height: 430,
                                      chartArea:{width:'95%',height:'95%'},
                                      //is3D: true,
                                    };
                                    var chart2 = new google.visualization.PieChart(document.getElementById('grafica<?= $resultado_fila->id_proceso_electoral ?>'));
                                    chart2.draw(data, options);
                                    //FIN GRAFICA TOTALES POR PARTIDO POLITICO

                                    //GRAFICA TOTALES POR CANDIDATO    

                                    var data = google.visualization.arrayToDataTable([
                                       <?php
                                      $acta = $entity->row($entity->statement('graficas_resultado_modal.193.8').$resultado_fila->id_proceso_electoral.$entity->statement('fragment.graficas_resultado_modal.191.17'));
                                          $candidatos = $entity->objects($entity->statement('graficas_resultado_modal.194.9').$resultado_fila->id_proceso_electoral.$query2.$entity->statement('fragment.graficas_resultado_modal.192.18'));
                                          $cadena = "['Candidato', 'Votos', { role: 'style' }]";
                                          foreach($candidatos as $partido){
                                              $cadena .= ",
                                              ['".$partido->nombre_pa." (".$partido->votos.")', ".$partido->votos.", '".$partido->color_pa."']";
                                          }
                                          $cadena .= ",
                                          ['NO REGISTRADOS (".$acta['nr'].")', ".$acta['nr'].", '#000000']";
                                          $cadena .= ",
                                          ['VOTOS NULOS (".$acta['nulos'].")', ".$acta['nulos'].", '#000000']";
                                          echo $cadena;
                                      ?>]);

                                    var options = {
                                      //title: 'Motivation Level Throughout the Day',
                                      width: 1100,
                                      height: 550,
                                      bar: { groupWidth: '100%' },
                                      legend: { position: "none" },
                                      axes: {
                                            x: {
                                              0: { side: 'top', label: 'White to move'} // Top x-axis.
                                            }
                                          },
                                      hAxis: {
                                        title: 'Partidos'
                                      },
                                      vAxis: {
                                        title: 'Votos'
                                      }
                                    };

                                    var chart3 = new google.visualization.ColumnChart(document.getElementById('grafica_candidato<?= $resultado_fila->id_proceso_electoral ?>'));
                                    chart3.draw(data, options);

                                      //FIN GRAFICA TOTALES POR CANDIDATO   
                                        
                                      // GRAFICA TOTALES POR CASILLA
                                   var chart<?= $resultado_fila->id_proceso_electoral ?> = c3.generate({
                                            bindto: '#grafica-casilla<?= $resultado_fila->id_proceso_electoral ?>',
                                  
                                            data: {
                                                columns: [
                                                    ['Casillas', <?= $totalCasillasRegistradas ?>]
                                                ],
                                                type: 'gauge',
                                            },
                                             size: {
                                                  height: 300,
                                                  width: 390
                                              },
                                            gauge: {
                                                min: 0,
                                                max: <?= $totalCasillas ?>
                                            }
                                        });
                                      document.getElementById("datosCasilla<?= $resultado_fila->id_proceso_electoral ?>").innerHTML = "<?= $totalCasillasRegistradas ?> DE <?= $totalCasillas ?> CASILLAS REGISTRADAS"
                                     //FIN GRAFICA TOTALES POR PARTIDO POLITICO    

                                    }
                                </script>

                                    <?php } 
                                 ?>


                        <!------------------------------>


                    </div>

                    <div style="clear: both;"></div>

                </div>
            </div>
        </div>
    </div>

</div>

                 