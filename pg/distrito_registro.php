<?php
require "../php/inicializandoDatosExterno.php";

    if ($_POST['id'] > 0) {
        $id = $funciones->limpia($_POST['id']);
        $row = $entity->row($querys->getdistrito($id));
    }
?>
    <form class="form-horizontal" id="enviar_formulario" method="post" enctype="multipart/form-data" action="php/subir.php">
        <div class="panel panel-default">
            <div class="panel-heading">

                <h4 class="panel-title">Formulario de Registro</h4>
                <p></p>
            </div>
            <div class="panel-body">

                <?php if ($id_estado == 0) { ?>
                    <div class="form-group">
                        <label class="col-sm-3">Estado :</label>
                        <div class="col-sm-9">
                            <select name="id_estado" id="id_estado" class="form-control" required>
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
                ?>

                <div class="form-group">
                    <label class="col-sm-3">Nombre :</label>
                    <div class="col-sm-9">
                        <input type="text" name="nombre" id="nombre" class="form-control" value="<?php if (!empty($_POST['id'])) echo $row['nombre']; ?>" />
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3">Coordenadas :</label>
                    <div class="col-sm-9">
                        <textarea name="coordenadas" rows="4" class="form-control"><?php if (!empty($_POST['id'])) echo $row['coordenadas']; ?></textarea>
                    </div>
                </div>

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
                <button class="btn btn-danger mr5" onclick="distrito_registro()">Cancelar</button>
            </div><!-- panel-footer -->
        </div><!-- panel-default -->
        <input type="hidden" name="opcion" id="opcion" value="<?php if (!isset($_POST['id'])) echo "120";
                                                                else echo "121"; ?>" />
        <input type="hidden" name="id" id="id" value="<?php if (isset($_POST['id'])) echo $id; ?>" />

    </form>
    <div id="cargando"></div>
