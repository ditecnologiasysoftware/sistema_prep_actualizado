
<script>
	window.onload = function(){
		resultado_eleccion_lista();
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
                <li>Resultado Electorales</li>
            </ul>
            <h4>Resultado Electorales</h4>
        </div>
    </div><!-- media -->
</div><!-- pageheader -->
<div class="contentpanel">
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <div class="panel-group" id="accordion2">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" data-parent="#accordion2" href="#collapseOne2">
                                    <div style="color:#FFFFFF">Seleccione el proceso electoral</div>
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
                                                    <div class="form-group">
                                                        <div class="col-sm-4">
                                                            <select name="q" id="q" class="form-control">
                                                                <?php if ((int) $id_proceso_electoral === 0) { ?><option value="0"> - Todos los procesos electorales - </option><?php } ?>
                                                                <?php
                                                                echo $funciones->llenarcombomodifica($querys->comboprocesoelectoral(), $_POST['q'] ?? $id_proceso_electoral);
                                                                ?>
                                                            </select>
                                                        </div>
                                                        <?php
                                                        if ($id_estado == 0 && $id_municipio == 0) {
                                                        ?>
                                                            <div class="col-sm-4">
                                                                <select name="estado_busqueda" id="estado_busqueda" onchange="combodependiente('estado_busqueda', 'municipio_busqueda', 'combo_dependiente/municipios.php')" class="form-control" required>
                                                                    <option value="0">Todos los Estados</option>
                                                                    <?php
                                                                    if (!empty($_POST['estado_busqueda'])) echo $funciones->llenarcombomodifica($entity->statement('resultado_eleccion.57.2'), $_POST['estado_busqueda']);
                                                                    else echo $funciones->llenarcombo($entity->statement('resultado_eleccion.58.3'));
                                                                    ?>
                                                                </select>
                                                            </div>
                                                            <div class="col-sm-4">
                                                                <select name="municipio_busqueda" id="municipio_busqueda" class="form-control">
                                                                    <option value="0">Todos los Municipios</option>
                                                                    <?php
                                                                    if (!empty($_POST['municipio_busqueda']) && $_POST['estado_busqueda'] != 0) echo $funciones->llenarcombomodifica($entity->statement('resultado_eleccion.66.4') . $_POST['estado_busqueda'] . $entity->statement('fragment.resultado_eleccion.66.2'), $_POST['municipio_busqueda']);
                                                                    ?>
                                                                </select>
                                                            </div>
                                                        <?php
                                                        } elseif ($id_estado != 0 && $id_municipio == 0) {
                                                        ?>
                                                            <div class="col-sm-4">
                                                                <select name="municipio_busqueda" id="municipio_busqueda" class="form-control">
                                                                    <?php
                                                                    if (!empty($_POST['municipio_busqueda'])) echo $funciones->llenarcombomodifica($entity->statement('resultado_eleccion.76.5') . $id_estado . $entity->statement('fragment.resultado_eleccion.76.3'), $_POST['municipio_busqueda']);
                                                                    ?>
                                                                </select>
                                                            </div>
                                                        <?php
                                                        }
                                                        ?>
                                                    </div>
                                                </form>
                                            </div>
                                            <div class="col-sm-4">
                                                <input type="button" class="btn btn-primary mr5" value="Buscar" onclick="resultado_eleccion_lista()" />
                                                <input type="button" class="btn btn-secundary mr5" onclick="window.location.href='resultado_eleccion'" value="Cancelar">
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- panel -->
        </div><!-- panel-group -->
    </div>

    <div id="listado">
    </div>
</div>
