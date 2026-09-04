<?php
require "../php/inicializandoDatosExterno.php";
if ($_POST['id'] > 0) {
    $id = $funciones->limpia($_POST['id']);
    $row = $entity->row($querys->getprocesoelectoral($id));
}
?>
    <form id="enviar_formulario" class="form-horizontal" method="post" enctype="multipart/form-data" action="php/subir.php" target="mandar_formulario">
        <div class="panel panel-default">
            <div class="panel-heading">

                <h4 class="panel-title">Formulario de Registro</h4>
                <p></p>
            </div>
            <div class="panel-body">

                <div class="form-group">
                    <label class="col-sm-3">Fecha :</label>
                    <div class="col-sm-9">
                        <input type="text" name="fecha" id="fecha" class="form-control" required value="<?php if (!empty($_POST['id'])) echo date("d-m-Y", strtotime($row['fecha'])) ?>" />
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3">Nombre :</label>
                    <div class="col-sm-9">
                        <input type="text" name="descripcion" id="descripcion" class="form-control" required value="<?php if (!empty($_POST['id'])) echo $row['descripcion']; ?>" />
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3">Tipo de Elección :</label>
                    <div class="col-sm-9">
                        <select name="eleccion" id="eleccion" class="form-control" required>
                            <?php
                            if (!empty($_POST['id'])) echo $funciones->llenarcombomodifica($querys->combotipoeleccion(), $row['id_tipo_eleccion']);
                            else echo $funciones->llenarcombo($querys->combotipoeleccion());
                            ?>
                        </select>
                    </div>
                </div>

                <?php if ($id_estado == 0) { ?>
                    <div class="form-group">
                        <label class="col-sm-3">Estado :</label>
                        <div class="col-sm-9">
                            <select name="id_estado" id="id_estado" onchange="combodependiente('id_estado', 'id_municipio', 'combo_dependiente/municipios2.php')" class="form-control" required>
                                <option value="0"> Seleccionar Estado </option>
                                <?php
                                if (!empty($_POST['id'])) echo $funciones->llenarcombomodifica($querys->comboestados(), $row['id_estado']);
                                else echo $funciones->llenarcombo($querys->comboestados());
                                ?>
                            </select>
                        </div>
                    </div>
                <?php } else {
                    echo '<input type="hidden" name="id_estado" id="id_estado" value="'.$id_estado.'" />';
                }

                if ($id_municipio == 0) { ?>
                    <div class="form-group">
                        <label class="col-sm-3">Municipio :</label>
                        <div class="col-sm-9">
                            <select name="id_municipio" id="id_municipio" class="form-control" required>
                                <option value="0"> Seleccionar Municipio </option>
                                <?php
                                if (!empty($_POST['id'])) echo $funciones->llenarcombomodifica($querys->combomunicipios($row['id_estado']), $row['id_municipio']);
                                else if ($id_municipio == 0) echo $funciones->llenarcombo($querys->combomunicipios($id_estado));
                                ?>
                            </select>
                        </div>
                    </div>
                <?php } else { ?>
                    <input type="hidden" name="id_municipio" id="id_municipio" value="<?= $id_municipio; ?>" />
                <?php
                } ?>

                <div class="form-group">
                    <label class="col-sm-3">Estatus :</label>
                    <div class="col-sm-9">
                        <select name="estatus" id="estatus" class="form-control" required>
                            <?php
                                if (!empty($_POST['id'])) echo $funciones->getcombotipoactivo($row['estatus']);
                                else echo $funciones->getcombotipoactivo(1);
                            ?>
                        </select>
                    </div>
                </div>

            </div><!-- panel-body -->
            <div class="panel-footer">
                <input type="submit" class="btn btn-primary mr5" id="btn_guardar" value="Guardar">
                <button class="btn btn-danger mr5" onclick="proceso_electoral_registro()">Cancelar</button>
            </div><!-- panel-footer -->
        </div><!-- panel-default -->
        <input type="hidden" name="opcion" id="opcion" value="<?php if (!isset($_POST['id'])) echo "114";
                                                                else echo "115"; ?>" />
        <input type="hidden" name="id" id="id" value="<?php if (isset($_POST['id'])) echo $id; ?>" />

    </form>
    <div id="cargando"></div>
