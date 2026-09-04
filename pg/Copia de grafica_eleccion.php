    <?php
    $anio = date('Y');
    //$meses =array('01'=>"Enero", '02'=>"Febrero", '03'=>"Marzo", '04'=>"Abril", '05'=>"Mayo", '06'=>"Junio", '07'=>"Julio", '08'=>"Agosto", '09'=>"Septiembre", '10'=>"Octubre", '11'=>"Noviembre", '12'=>"Diciembre");
    $meses =array('04'=>"Abril", '05'=>"Mayo", '06'=>"Junio", '07'=>"Julio");
    $query_pe = "";
    $query = "";
    $query2 = "";

    if ($id_municipio != 0) {
       $query_pe .= " and id_municipio = ".$id_municipio."";
    }
    elseif ($id_estado != 0) {
       $query_pe .= " and id_estado = ".$id_estado."";
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
                    <li>Gráficas Elecciones</li>
                </ul>
                <h4>Gráficas Elecciones</h4>
            </div>
        </div><!-- media -->
    </div><!-- pageheader -->
    
    <!--FIN ARRIBA---------------------------------------------------------------------------------->
    <div class="contentpanel">
       <div class="form-group">
          <div class="panel-group" id="accordion2">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapseOne2">
                           <div style="color:#FFFFFF" >Seleccione el proceso electoral</div>
                        </a>
                    </h4>
                </div>
                <div id="collapseOne2" class="panel-collapse collapse <?php if(isset($_POST['bus'])) echo "in"; ?>">
                    <div class="panel-body">
                    <table width="100%" border="0">
                            <tr>
                                <td width="100%">
                                     <div class="form-group">
                                            <form id="form_busqueda">
                                            <div class="form-group">
                                                    <div class="col-sm-4">

                                                    <select name="q" id="q" class="form-control">
                                                        <?php 
                                                            echo $funciones->llenarcombomodifica("SELECT id_proceso_electoral as id, CONCAT(descripcion,' - ', fecha) as valor FROM tblc_proceso_electoral WHERE estatus = 1".$query_pe." ORDER BY fecha DESC", $_GET['q'] );
                                                        ?>
                                                    </select>

                                                    </div>
                                                  <?php
                                                if($id_estado == 0 && $id_municipio == 0){
                                                ?>
                                                    <div class="col-sm-4">
                                                      <select name="estado_busqueda" id="estado_busqueda" onchange="combodependiente('estado_busqueda', 'municipio_busqueda', 'combo_dependiente/municipios.php')" class="form-control" required>
                                                        <option value="0">Todos los Estados</option>
                                                        <?php 
                                                        if(isset($_GET['estado_busqueda'])) echo $funciones->llenarcombomodifica("SELECT id_estado as id, nombre as valor FROM tblc_estado ORDER BY nombre", $_GET['estado_busqueda']);
                                                        else echo $funciones->llenarcombo("SELECT id_estado as id, nombre as valor FROM tblc_estado ORDER BY nombre");
                                                        ?>
                                                    </select>
                                                    </div>

                                                    <div class="col-sm-4">
                                                      <select name="municipio_busqueda" id="municipio_busqueda" class="form-control" >
                                                         <option value="0">Todos los Municipios</option>
                                                            <?php 
                                                            if(isset($_GET['municipio_busqueda']) && $_GET['estado_busqueda'] != 0) echo $funciones->llenarcombomodifica("SELECT id_municipio as id, nombre as valor FROM tblc_municipio WHERE id_estado = ".$_GET['estado_busqueda']." ORDER BY nombre", $_GET['municipio_busqueda']);
                                                            ?>
                                                        </select>
                                                    </div>
                                                <?php
                                                }
                                                else if($id_estado != 0 && $id_municipio == 0){
                                                  ?>
                                                    <div class="col-sm-4">
                                                      <select name="municipio_busqueda" id="municipio_busqueda" class="form-control" >
                                                      <?php 
                                                       if(isset($_GET['municipio_busqueda'])) echo $funciones->llenarcombomodifica("SELECT id_municipio as id, nombre as valor FROM tblc_municipio WHERE id_estado = ".$id_estado." ORDER BY nombre", $_GET['municipio_busqueda']);
                                                                                ?>
                                                   </select>
                                                    </div>
                                                  <?php
                                                }
                                                ?>
                                                </div>
                                              <div class="col-sm-4">
                                                <input type="submit" class="btn btn-primary mr5" value="Buscar" />
                                                <input type="button" class="btn btn-secundary mr5" onclick="window.location.href='grafica_eleccion'" value="Cancelar">
                                                </div>
                                            </form>
                                      </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div><!-- panel -->
        </div><!-- panel-group -->
  </div>
     
      <div class="contentpanel">
      <?php 
      if(isset($_GET['estado_busqueda']) ){ 
        if($_GET['estado_busqueda'] != 0){

             //condiciona un estado y municipio
        } 
      }

    $query = "";
    if(isset($_GET['q'])){
         $query .= " WHERE id_proceso_electoral = ".$funciones->limpia($_GET['q']);                                    
      }else{
        //$idpelectoral = $entity->scalar("SELECT MAX(id_proceso_electoral) FROM tblc_proceso_electoral WHERE estatus = 1 LIMIT 1");
        $query .= " WHERE estatus = 1";
      }

     if ($id_municipio != 0) {
        $query .= " and id_municipio = ".$id_municipio."";
        $query2 .= " and id_municipio = ".$id_municipio."";
     }
     elseif ($id_estado != 0) {
        $query .= " and id_estado = ".$id_estado."";
        $query2 .= " and estado_c = ".$id_estado."";
     }
     elseif(isset($_GET['municipio_busqueda']) && $_GET['municipio_busqueda'] != 0){
        $query .= " and id_municipio = ".$_GET['municipio_busqueda']."";
        $query2 .= " and id_municipio = ".$id_municipio."";
    }
     elseif(isset($_GET['estado_busqueda']) && $_GET['estado_busqueda'] != 0){ 
        $query .= " and id_estado = ".$_GET['estado_busqueda']."";
        $query2 .= " and estado_c = ".$_GET['estado_busqueda']."";
    }
     
     $cadena = "SELECT * FROM tblc_proceso_electoral".$query." ORDER BY fecha ASC ";
     $cadena2 = "SELECT COUNT(id_proceso_electoral) FROM tblc_proceso_electoral".$query;                                                              
      
      $totalRegistros = $entity->scalar($cadena2);
      $resul_lista = $entity->objects($cadena);

      foreach($resul_lista as $resultado_fila){

        $tipo_eleccion = $entity->row("SELECT * FROM tblc_tipo_eleccion WHERE id_tipo_eleccion = ".$resultado_fila->id_tipo_eleccion);
        switch ($tipo_eleccion['tipo']) {
          case '1': //FEDERAL
            $totalCasillas = $entity->scalar("SELECT COUNT(id_casilla) FROM tblc_casilla");
            
            /*$casillasabiertas = $entity->scalar("SELECT COUNT(id_estatus_casilla) FROM tbl_estatus_casilla WHERE tipo = 1 AND id_proceso_electoral = ".$resultado_fila->id_proceso_electoral);
            $casilllascerradas = $entity->scalar("SELECT COUNT(id_estatus_casilla) FROM tbl_estatus_casilla WHERE tipo = 2 AND id_proceso_electoral = ".$resultado_fila->id_proceso_electoral);
            $casillassincaptura = $entity->scalar("SELECT COUNT(c.id_casilla) FROM tblc_casilla AS c WHERE c.id_casilla NOT IN (SELECT ec.id_casilla FROM tbl_estatus_casilla AS ec WHERE ec.tipo IN(1,2,3) AND ec.id_casilla = c.id_casilla AND ec.id_proceso_electoral = ".$resultado_fila->id_proceso_electoral.")");
            */
            break;

          case '2'://ESTATAL
            $totalCasillas = $entity->scalar("SELECT COUNT(c.id_casilla) FROM tblc_casilla AS c 
              INNER JOIN tblc_municipio AS m On c.id_municipio = m.id_municipio 
              WHERE m.id_estado = ".$resultado_fila->id_estado);
            
            /*$casillasabiertas = $entity->scalar("SELECT COUNT(ec.id_estatus_casilla) FROM tbl_estatus_casilla as ec 
              INNER JOIN tblc_casilla AS c ON ec.id_casilla = c.id_casilla 
              INNER JOIN tblc_municipio AS m On c.id_municipio = m.id_municipio 
              WHERE ec.tipo = 1 AND ec.id_proceso_electoral = ".$resultado_fila->id_proceso_electoral." AND m.id_estado = ".$resultado_fila->id_estado);

            $casilllascerradas = $entity->scalar("SELECT COUNT(ec.id_estatus_casilla) FROM tbl_estatus_casilla as ec 
              INNER JOIN tblc_casilla AS c ON ec.id_casilla = c.id_casilla 
              INNER JOIN tblc_municipio AS m On c.id_municipio = m.id_municipio 
              WHERE ec.tipo = 2 AND ec.id_proceso_electoral = ".$resultado_fila->id_proceso_electoral." AND m.id_estado = ".$resultado_fila->id_estado);

            $casilllasnoaparturada = $entity->scalar("SELECT COUNT(ec.id_estatus_casilla) FROM tbl_estatus_casilla as ec 
              INNER JOIN tblc_casilla AS c ON ec.id_casilla = c.id_casilla 
              INNER JOIN tblc_municipio AS m On c.id_municipio = m.id_municipio 
              WHERE ec.tipo = 3 AND ec.id_proceso_electoral = ".$resultado_fila->id_proceso_electoral." AND m.id_estado = ".$resultado_fila->id_estado);

            $casillassincaptura = $entity->scalar("SELECT COUNT(c.id_casilla) FROM tblc_casilla AS c 
              INNER JOIN tblc_municipio AS m On c.id_municipio = m.id_municipio 
              WHERE m.id_estado = ".$resultado_fila->id_estado." AND c.id_casilla NOT IN (SELECT ec.id_casilla FROM tbl_estatus_casilla AS ec WHERE ec.tipo IN(1,2,3) AND ec.id_casilla = c.id_casilla AND ec.id_proceso_electoral = ".$resultado_fila->id_proceso_electoral.")");
            */
            break;

          case '3'://MUNICIPAL
            $totalCasillas = $entity->scalar("SELECT COUNT(id_casilla) FROM tblc_casilla 
              WHERE id_municipio = ".$resultado_fila->id_municipio);

            /*$casillasabiertas = $entity->scalar("SELECT COUNT(ec.id_estatus_casilla) FROM tbl_estatus_casilla as ec 
              INNER JOIN tblc_casilla AS c ON ec.id_casilla = c.id_casilla 
              WHERE ec.tipo = 1 AND ec.id_proceso_electoral = ".$resultado_fila->id_proceso_electoral." AND c.id_municipio = ".$resultado_fila->id_municipio);

            $casilllascerradas = $entity->scalar("SELECT COUNT(ec.id_estatus_casilla) FROM tbl_estatus_casilla as ec 
              INNER JOIN tblc_casilla AS c ON ec.id_casilla = c.id_casilla 
              WHERE ec.tipo = 2 AND ec.id_proceso_electoral = ".$resultado_fila->id_proceso_electoral." AND c.id_municipio = ".$resultado_fila->id_municipio);
            
            $casilllasnoaparturada = $entity->scalar("SELECT COUNT(ec.id_estatus_casilla) FROM tbl_estatus_casilla as ec 
              INNER JOIN tblc_casilla AS c ON ec.id_casilla = c.id_casilla 
              WHERE ec.tipo = 3 AND ec.id_proceso_electoral = ".$resultado_fila->id_proceso_electoral." AND c.id_municipio = ".$resultado_fila->id_municipio);

            $casillassincaptura = $entity->scalar("SELECT COUNT(c.id_casilla) FROM tblc_casilla AS c 
              WHERE c.id_municipio = ".$resultado_fila->id_municipio." AND c.id_casilla NOT IN (SELECT ec.id_casilla FROM tbl_estatus_casilla AS ec WHERE ec.tipo IN(1,2,3) AND ec.id_casilla = c.id_casilla AND ec.id_proceso_electoral = ".$resultado_fila->id_proceso_electoral.")");
            */
            break;
        }
          
        $entity->objects("SELECT * FROM vw_resultado_elecciones WHERE idp_electoral_c = ".$resultado_fila->id_proceso_electoral.$query2." GROUP BY id_casilla"); 
        $totalCasillasRegistradas = $entity->numregistros();
        $casillas_faltantes = $totalCasillas - $totalCasillasRegistradas;

      ?>
      <font size="5" color="#2D55AE"><?php echo strtoupper($resultado_fila->descripcion) ?></font>
      <hr>

      <div class="row"><!-- row grafias 1-->

          <div class="col-md-12">
              <div class="panel panel-default">
                  <div class="panel-heading">
                      <h4 class="panel-title">Apertura y cierre de casilla</h4>
                      <p></p>
                  </div>
                  <div class="panel-body">
                  <div id="grafica-casillasac<?= $resultado_fila->id_proceso_electoral ?>" class="graficas" style="margin-top: -2em; margin-left: 3em;"></div><br>
                  <div style="text-align: right;">
                  </div>
                </div><!-- panel-body -->
              </div><!-- panel-default -->
          </div>

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
                       <div id="grafica_candidato<?= $resultado_fila->id_proceso_electoral ?>" class="graficas text-center"></div>                                
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
       ///APERTURA Y CIERRE DE CASILLA
        var data1 = google.visualization.arrayToDataTable([
           <?php
          $cadena1 = "
              ['Estatus de casilla', 'Casillas', { role: 'style' }],
              ['Aperturadas (".$casillasabiertas.")', ".$casillasabiertas.", '#68a953'],
              ['Cerradas (".$casilllascerradas.")', ".$casilllascerradas.", '#c94d4d'],
              ['Sin registro (".$casillassincaptura.")', ".$casillassincaptura.", '#5394a9']";

          echo trim($cadena1, ',');    
          ?>]);

        var options1 = {
          //title: 'Motivation Level Throughout the Day',
          width: '100%',
          height: 400,
          bar: { groupWidth: '90%' },
          legend: { position: "none" },
          hAxis: {
            title: 'Estatus de casilla'
          },
          vAxis: {
            title: 'Casillas'
          }
        };

        var chart1 = new google.visualization.ColumnChart(document.getElementById('grafica-casillasac<?= $resultado_fila->id_proceso_electoral ?>'));
        chart1.draw(data1, options1);
        //FIN GRAFICA ESTATUS DE CASILLAS
      //GRAFICA TOTALES POR PARTIDO POLITICO    
      var data = new google.visualization.DataTable();
          data.addColumn('string', 'Partidos');
          data.addColumn('number', 'Votos');
          data.addRows([
      <?php
          $acta = $entity->row("SELECT SUM(votos_nulos) as nulos, SUM(no_registrados) as nr FROM tbl_acta WHERE id_proceso_electoral = ".$resultado_fila->id_proceso_electoral." GROUP BY id_proceso_electoral");
          $votopartidos = $entity->objects("SELECT nombre_c, SUM(resultado) as votos FROM vw_resultado_elecciones WHERE idp_electoral_c = ".$resultado_fila->id_proceso_electoral.$query2." GROUP BY idcandidato_c");
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
          chartArea:{width:'95%',height:'95%'},
          //is3D: true,
        };
        var chart2 = new google.visualization.PieChart(document.getElementById('grafica<?= $resultado_fila->id_proceso_electoral ?>'));
        chart2.draw(data, options);
        //FIN GRAFICA TOTALES POR PARTIDO POLITICO

        //GRAFICA TOTALES POR CANDIDATO    

        var data = google.visualization.arrayToDataTable([
           <?php
          $acta = $entity->row("SELECT SUM(votos_nulos) as nulos, SUM(no_registrados) as nr FROM tbl_acta WHERE id_proceso_electoral = ".$resultado_fila->id_proceso_electoral." GROUP BY id_proceso_electoral");
              $candidatos = $entity->objects("SELECT nombre_pa, color_pa, SUM(resultado) as votos FROM vw_resultado_elecciones WHERE idp_electoral_c = ".$resultado_fila->id_proceso_electoral.$query2." GROUP BY idp_politico_c");
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
          width: '100%',
          height: 500,
          bar: { groupWidth: '90%' },
          legend: { position: "none" },
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
        
        <br/>
                
    </div>


                 