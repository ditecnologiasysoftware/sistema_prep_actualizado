                    <?php
                    $sentencia = "";
                    if($id_municipio != 0){
                        $sentencia .= " AND r.id_municipio = '".$id_municipio."'";
                    }else if($id_estado != 0){
                        $sentencia .= " AND m.id_estado = '".$id_estado."'";
                    }
                    ?>
                    <div class="pageheader">
                        <div class="media">
                            <div class="pageicon pull-left">
                                <i class="fa fa-home"></i>
                            </div>
                            <div class="media-body">
                                <ul class="breadcrumb">
                                    <li><a href=""><i class="glyphicon glyphicon-home"></i></a></li>
                                    <li>Inicio</li>
                                </ul>
                                <h4><strong style="color:#036">Bienvenido al Sistema administrador de denuncias</strong></h4>
                            </div>
                        </div><!-- media -->
                    </div><!-- pageheader -->
                    
                    <!--FIN ARRIBA---------------------------------------------------------------------------------->
                    <div class="contentpanel">
                     
                     
                      <div class="contentpanel">
                        
                        <div class="row row-stat">


                            <div class="col-md-4">
                                <div class="panel panel-success-alt noborder">
                                    <div class="panel-heading noborder">
                                        <div class="panel-btns">
                                           
                                        </div><!-- panel-btns -->
                                        <div class="panel-icon"><i class="fa fa-check-square-o"></i></div>
                                        <div class="media-body">
                                            <h5 class="md-title nomargin">Denuncias realizadas</h5>
                                            <h1 class="mt5"><a href="reportes&servicio_busqueda=1"  style="color:#FFF">
                                            <?php 
                                                echo $entity->scalar('SELECT COUNT(r.id_reporte) FROM tbl_reporte AS r INNER JOIN tblc_municipio AS m ON r.id_municipio = m.id_municipio INNER JOIN tblc_estado AS e ON m.id_estado = e.id_estado WHERE r.tipo_reporte = 1'.$sentencia);

											?>
                                            </a></h1>
                                        </div><!-- media-body -->
                                        <hr>
                                        
                                    </div><!-- panel-body -->
                                </div><!-- panel -->
                            </div><!-- col-md-4 -->

                            <div class="col-md-4">
                                <div class="panel panel-success-alt noborder">
                                    <div class="panel-heading noborder">
                                        <div class="panel-btns">
                                           
                                        </div><!-- panel-btns -->
                                        <div class="panel-icon"><i class="fa fa-check-square-o"></i></div>
                                        <div class="media-body">
                                            <h5 class="md-title nomargin">Observaciones realizadas</h5>
                                            <h1 class="mt5"><a href="reportes&servicio_busqueda=2"  style="color:#FFF">
                                            <?php 
                                                echo $entity->scalar('SELECT COUNT(r.id_reporte) FROM tbl_reporte AS r INNER JOIN tblc_municipio AS m ON r.id_municipio = m.id_municipio INNER JOIN tblc_estado AS e ON m.id_estado = e.id_estado WHERE r.tipo_reporte = 2'.$sentencia);

                                            ?>
                                            </a></h1>
                                        </div><!-- media-body -->
                                        <hr>
                                        
                                    </div><!-- panel-body -->
                                </div><!-- panel -->
                            </div><!-- col-md-4 -->
                            
                        </div><!-- row -->
                        
                        <br/>
                                
                    </div>