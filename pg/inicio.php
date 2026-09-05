                    <?php
                    $sentencia = "";
                    if($id_municipio != 0){
                        $sentencia .= $entity->statement('fragment.inicio.4.1').$id_municipio."'";
                    }else if($id_estado != 0){
                        $sentencia .= $entity->statement('fragment.inicio.6.2').$id_estado."'";
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
                                <h4><strong style="color:#036">Sistema electoral</strong></h4>
                            </div>
                        </div><!-- media -->
                    </div><!-- pageheader -->
                    
                    <!--FIN ARRIBA---------------------------------------------------------------------------------->
                    <div class="contentpanel">
                     
                     
                      <div class="contentpanel">
                        
                        <div class="row row-stat">


                            <!--<div class="col-md-4">
                                <div class="panel panel-success-alt noborder">
                                    <div class="panel-heading noborder">
                                        <div class="panel-btns">
                                           
                                        </div>
                                        <div class="panel-icon"><i class="fa fa-check-square-o"></i></div>
                                        <div class="media-body">
                                            <h5 class="md-title nomargin">Denuncias realizadas</h5>
                                            <h1 class="mt5"><a href="reportes&servicio_busqueda=1"  style="color:#FFF">
                                            <?php 
                                                echo $entity->scalar($entity->statement('inicio.44.1').$sentencia);

											?>
                                            </a></h1>
                                        </div>
                                        <hr>
                                        
                                    </div>
                                </div>
                            </div><!-- col-md-4 -->

                            <!--<div class="col-md-4">
                                <div class="panel panel-success-alt noborder">
                                    <div class="panel-heading noborder">
                                        <div class="panel-btns">
                                           
                                        </div>
                                        <div class="panel-icon"><i class="fa fa-check-square-o"></i></div>
                                        <div class="media-body">
                                            <h5 class="md-title nomargin">Observaciones realizadas</h5>
                                            <h1 class="mt5"><a href="reportes&servicio_busqueda=2"  style="color:#FFF">
                                            <?php 
                                                echo $entity->scalar($entity->statement('inicio.66.2').$sentencia);

                                            ?>
                                            </a></h1>
                                        </div>
                                        <hr>
                                        
                                    </div>
                                </div>

                            </div><!-- col-md-4 -->
                            <center><h1>Bienvenido usuario:<br><br><?php echo $nombre ?></h1></center>

                        </div><!-- row -->
                        
                        <br/>
                                
                    </div>