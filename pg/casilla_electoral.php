<script>
    window.onload = function() {
        casilla_electoral_lista();
        casilla_electoral_registro();
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
                <li>Casilla Electoral</li>
            </ul>
            <h4>Casilla Electoral</h4>
        </div>
    </div>
</div>
<div class="panel-group" id="accordion2">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapseOne2">
                    <div style="color:#FFFFFF">Buscar</div>
                </a>
            </h4>
        </div>
        <div id="collapseOne2" class="panel-collapse collapse <?php if (isset($_POST['bus'])) echo "in"; ?>">
            <div class="panel-body">
                <table width="100%" border="0">
                    <tr>
                        <td width="100%">
                            <div class="form-group">
                                <form id="form_busqueda">
                                    <div class="col-sm-6">
                                        <label class="col-sm-12">Seccion :</label>
                                        <input type="number" name="n" id="n" class="form-control" value="<?php if (isset($_POST['n'])) echo $_POST['n']; ?>" />
                                    </div>

                                    <div class="col-sm-6">
                                        <label class="col-sm-12">Tipo de casilla :</label>
                                        <select name="tipo" class="form-control">
                                            <option value="0"> Todos los tipos </option>
                                            <?php
                                            echo $funciones->getcomboTipoEleccion($row['tipo']);
                                            ?>
                                        </select>
                                    </div>

                                    <?php
                                        if ($id_estado == 0) {
                                        ?>
                                            <div class="form-group col-sm-4">
                                                <div style="margin: 2px 5px;">
                                                    <label>Estado</label>
                                                    <div>
                                                        <select class="form-control" name="estado_busqueda" id="estado_busqueda" onchange="combodependiente('estado_busqueda', 'municipio_busqueda', 'combo_dependiente/municipios2.php')" required style="width: 98%">
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
                                                        <select class="form-control"name="municipio_busqueda" id="municipio_busqueda" required style="width: 98%" onchange="combodependiente('municipio_busqueda', 'casilla_busqueda', 'combo_dependiente/casillas.php')">
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
                                    <br>
                                    <input type="button" class="btn btn-primary mr5" value="Buscar" onclick="casilla_electoral_lista()" />
                                    <input type="button" class="btn btn-secundary mr5" onclick="window.location.href='casilla_electoral'" value="Cancelar">

                                </form>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div><!-- panel -->
</div><!-- panel-group -->
<div class="contentpanel">
    <div class="row">
        <div id="formulario_registro" class="col-md-4"></div>
        <div id="contenido" class="col-md-8"></div>
    </div>
</div>