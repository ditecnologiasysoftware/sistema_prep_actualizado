                    <?php
                    $anio = date('Y');
                    //$meses =array('01'=>"Enero", '02'=>"Febrero", '03'=>"Marzo", '04'=>"Abril", '05'=>"Mayo", '06'=>"Junio", '07'=>"Julio", '08'=>"Agosto", '09'=>"Septiembre", '10'=>"Octubre", '11'=>"Noviembre", '12'=>"Diciembre");
                    $meses = array('04' => "Abril", '05' => "Mayo", '06' => "Junio", '07' => "Julio");
                    $sentencias = "";
                    $sentencias2 = "";
                    if ($id_municipio != 0) {
                        $sentencias = $entity->statement('fragment.graficas.8.1') . $id_municipio;
                        $sentencias2 = $entity->statement('fragment.graficas.9.2') . $id_municipio;
                    }
                    ?>
                    <style type="text/css">
                        .graficas {
                            width: 100%;
                            height: 360px;
                        }

                        /* Etiquetas en nube centrada multicolor */
                        .cloud-label-widget-content {
                            text-align: center;
                        }

                        .label-size-1 {
                            color: #9e9e9e;
                            font-size: 12px;
                            margin: 3px;
                        }

                        .label-size-2 {
                            color: #996666;
                            font-size: 25px;
                            margin: 3px;
                        }

                        .label-size-3 {
                            color: #333;
                            font-size: 30px;
                            margin: 3px;
                        }

                        .label-size-4 {
                            color: #0fecec;
                            font-size: 44px;
                            margin: 3px;
                        }

                        .label-size-5 {
                            color: #990000;
                            font-size: 60px;
                            margin: 3px;
                        }
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
                                                $total = $entity->scalar($entity->statement('graficas.124.1') . $sentencias2 . $entity->statement('graficas.etiquetas_total_suffix'));
                                                $etiquetas = $entity->objects($entity->statement('graficas.125.2') . $sentencias2 . $entity->statement('graficas.etiquetas_list_suffix'));
                                                foreach ($etiquetas as $value) {
                                                    // Verificar si $total no es cero para evitar la división por cero
                                                    $porcentaje = 0;
                                                    if ($total != 0) {
                                                        $porcentaje = $funciones->getporcentaje($value->conteo, $total);
                                                    }

                                                    // Determinar la clase según el porcentaje
                                                    if ($porcentaje <= 20) {
                                                        $clase = "label-size-1";
                                                    } else if ($porcentaje > 20 && $porcentaje <= 40) {
                                                        $clase = "label-size-2";
                                                    } else if ($porcentaje > 40 && $porcentaje <= 60) {
                                                        $clase = "label-size-3";
                                                    } else if ($porcentaje > 60 && $porcentaje <= 80) {
                                                        $clase = "label-size-4";
                                                    } else if ($porcentaje > 80) {
                                                        $clase = "label-size-5";
                                                    }

                                                    // Imprimir etiqueta con su clase y conteo
                                                    echo '<a href="reportes&etiqueta=' . $value->id_etiqueta . '" class="' . $clase . '">' . $value->etiqueta . '(' . $value->conteo . ') </a>';
                                                }
                                                ?>

                                            </div>

                                        </div><!-- panel-body -->

                                    </div><!-- panel-default -->

                                </div>

                            </div><!-- row -->

                            <br />

                        </div>

                        <script type="text/javascript">
                            // Load the Visualization API and the piechart package.
                            google.load('visualization', '1.0', {
                                'packages': ['corechart']
                            });
                            // Set a callback to run when the Google Visualization API is loaded.
                            google.setOnLoadCallback(drawChart);
                            // Callback that creates and populates a data table,
                            // instantiates the pie chart, passes in the data and
                            // draws it.
                            function drawChart() {
                                //GRAFICA TOTALES POR tipo de registros

                                var data = new google.visualization.DataTable();
                                data.addColumn('string', 'Tipo de registro');
                                data.addColumn('number', 'Reportes');
                                data.addRows([
                                    <?php
                                    $telefono = $entity->scalar($entity->statement('graficas.183.3') . $sentencias);
                                    $whatsapp = $entity->scalar($entity->statement('graficas.184.4') . $sentencias);
                                    $personal = $entity->scalar($entity->statement('graficas.185.5') . $sentencias);

                                    echo "['Teléfono (" . $telefono . ")', " . $telefono . "],
                ";
                                    echo "['Whatsapp (" . $whatsapp . ")', " . $whatsapp . "],
                ";
                                    echo "['Personal (" . $personal . ")', " . $personal . "]";
                                    ?>
                                ]);

                                var options = {
                                    //legend: 'none',
                                    //pieSliceText: 'label',
                                    //title: 'Swiss Language Use (100 degree rotation)',
                                    //pieStartAngle: 100,
                                    chartArea: {
                                        width: '95%',
                                        height: '95%'
                                    },
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
                                    $categorias = $entity->objects($entity->statement('graficas.217.6') . $sentencias2 . $entity->statement('graficas.categorias_suffix'));
                                    $cadena = "";
                                    foreach ($categorias as $categoria) {
                                        $cadena .= ",
        ['" . $categoria->nombre . " (" . $categoria->conteo . ")', " . $categoria->conteo . "]";
                                    }
                                    echo trim($cadena, ',');
                                    ?>
                                ]);

                                var options = {
                                    //legend: 'none',
                                    //pieSliceText: 'label',
                                    //title: 'Swiss Language Use (100 degree rotation)',
                                    //pieStartAngle: 100,
                                    chartArea: {
                                        width: '95%',
                                        height: '95%'
                                    },
                                    is3D: true,
                                };

                                var chart4 = new google.visualization.PieChart(document.getElementById('grafica4'));
                                chart4.draw(data, options);

                            }
                        </script>
