<script>
    window.onload = function() {
        representante_lista();
        representante_registro();
    }
</script>
<div class="pageheader">
    <div class="media">
        <div class="pageicon pull-left">
            <i class="fa fa-home"></i>
        </div>
        <div class="media-body">
            <ul class="breadcrumb">
                <li><a href=""><i class="glyphicon glyphicon-home"></i></a></li>
                <li>Representante de Casilla</li>
            </ul>
            <h4>Representante de Casilla</h4>
        </div>
    </div><!-- media -->
</div><!-- pageheader -->

<div class="content-panel">
    <div class="row">
        <div class="col-md-12">
            
            <div class="panel-group" id="accordion2">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion2" href="#collapseOne2">
                                <div style="color:#FFFFFF">Buscar</div>
                            </a>
                        </h4>
                    </div>
                    <div id="collapseOne2" class="panel-collapse collapse <?php if (!empty($_POST['bus'])) echo "in"; ?>">
                        <div class="panel-body">
                            <table width="100%" border="0">
                                <tr>
                                    <td width="100%">
                                        <div class="form-group">

                                            <form id="form_busqueda">
                                                <?php
                                                if ($id_estado == 0) {
                                                ?>
                                                    <div class="form-group col-sm-4">
                                                        <div style="margin: 2px 5px;">
                                                            <label>Estado</label>
                                                            <div>
                                                                <select class="select2-container" name="estado_busqueda" id="estado_busqueda" onchange="combodependiente('estado_busqueda', 'municipio_busqueda', 'combo_dependiente/municipios2.php')" required style="width: 98%">
                                                                    <option value="0">Todos los Estados</option>
                                                                    <?php
                                                                    echo $funciones->llenarcombo($querys->comboestados());
                                                                    ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php
                                                } else{
                                                    echo '<input type="hidden" name="estado_busqueda" id="estado_busqueda" value="'.$id_estado.'" />';
                                                }

                                                if ($id_municipio == 0) {
                                                ?>
                                                    <div class="form-group col-sm-4">
                                                        <div style="margin: 2px 5px;"><label>Municipio:</label>
                                                            <div>
                                                                <select class="select2-container"name="municipio_busqueda" id="municipio_busqueda" required style="width: 98%" onchange="combodependiente('municipio_busqueda', 'casilla_busqueda', 'combo_dependiente/casillas.php')">
                                                                    <option value="0">Todos los Municipios</option>
                                                                    <?php
                                                                    if ($id_estado != 0) echo $funciones->llenarcombo($querys->combomunicipios($id_estado));
                                                                    ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php
                                                } else{
                                                    echo '<input type="hidden" name="municipio_busqueda" id="municipio_busqueda" value="'.$id_municipio.'" />';
                                                }
                                                ?>

                                                <div class="form-group col-sm-4">
                                                    <div style="margin: 2px 5px;"><label>Casilla:</label>
                                                        <div>
                                                            <select class="select2-container" name="casilla_busqueda" id="casilla_busqueda" required style="width: 98%">
                                                                <option value="0">Todas Casilla</option>
                                                                <?php
                                                                if ($id_municipio != 0) echo $funciones->llenarcombomodificaCasilla($querys->combocasillas(0,$id_municipio),0);
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-sm-4">
                                                    <label class="col-sm-12">Nombre Representante:</label>
                                                    <input type="text" name="n" id="n" class="form-control" value="" />

                                                </div><br>

                                                <input type="hidden" name="pagina" id="pagina" class="form-control" value="1" />
                                                <input type="button" class="btn btn-primary mr5" value="Buscar" onclick="representante_lista()" />
                                                <input type="button" class="btn btn-secundary mr5" onclick="window.location.href='representante'" value="Cancelar">

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

        <div id="contenido" class="col-md-4"></div>
        <div id="listado" class="col-md-8"></div>
    </div><!-- row -->
</div><!-- contentpanel -->