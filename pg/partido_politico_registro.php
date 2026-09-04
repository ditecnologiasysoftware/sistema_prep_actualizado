<?php
require "../php/inicializandoDatosExterno.php";
if ($_POST['id'] > 0) {
    $id = $funciones->limpia($_POST['id']);
    $row = $entity->row("SELECT * FROM tblc_partido_politico WHERE id_partido_politico = " . $id . " ");
}
?>
    <form id="enviar_formulario" class="form-horizontal" method="post" enctype="multipart/form-data" action="php/subir.php">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">Formulario de Registro</h4>
                <p></p>
            </div>
            <div class="panel-body">

                <div class="form-group">
                    <label class="col-sm-3">Nombre :</label>
                    <div class="col-sm-9">
                        <input type="text" name="nombre" id="nombre" class="form-control" value="<?php if (!empty($_POST['id'])) echo $row['nombre']; ?>" />
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3">Color :</label>
                    <div class="col-sm-9">
                        <input style="height: 5em;" type="color" name="color" id="color" class="form-control" value="<?php if (!empty($_POST['id'])) echo $row['colo']; ?>" />
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3">Icono :</label>
                    <div class="col-sm-9">
                        <input type="file" id="icono" name="icono">
                        <p class="help-block">Subir un Icono del Producto.</p>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3">Ordenamiento :</label>
                    <div class="col-sm-9">
                        <input style="height: 5em;" type="number" name="ordenamiento" id="ordenamiento" class="form-control" value="<?php if (!empty($_POST['id'])) echo $row['ordenamiento']; ?>" />
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
                <button class="btn btn-danger mr5" onclick="partido_politico_registro()">Cancelar</button>
            </div><!-- panel-footer -->
        </div><!-- panel-default -->
        <input type="hidden" name="opcion" id="opcion" value="<?php if (!isset($_POST['id'])) echo "116";
                                                                else echo "117"; ?>" />
        <input type="hidden" name="id" id="id" value="<?php if (isset($_POST['id'])) echo $id; ?>" />

    </form>
    <div id="cargando"></div>
