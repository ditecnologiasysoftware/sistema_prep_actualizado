<?php 
require "../php/inicializandoDatosExterno.php"; 
$id = (int) ($_POST['id'] ?? 0);
$es_edicion = $id > 0;
if($es_edicion){
    $row = $entity->row($entity->statement('estados_registro.6.1').$id." ");
}
?>
    <form class="form-horizontal" id="enviar_formulario" method="post" enctype="multipart/form-data" action="php/subir.php">
        <div class="panel panel-default">
            <div class="panel-heading">

                <h4 class="panel-title">Formulario de registro</h4>
                <p></p>
            </div>
            <div class="panel-body">

                <div class="form-group">
                    <label class="col-sm-3">Clave :</label>
                    <div class="col-sm-8">
                        <input type="text" name="clave" id="clave" class="form-control" maxlength="5" required value="<?php if (!empty($_POST['id'])) echo htmlspecialchars($row['clave'], ENT_QUOTES, 'UTF-8'); ?>" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3">Nombre :</label>
                    <div class="col-sm-8">
                        <input type="text" name="nombre" id="nombre" class="form-control" value="<?php if (!empty($_POST['id'])) echo $row['nombre']; ?>" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3">Latitud :</label>
                    <div class="col-sm-8">
                        <input type="text" name="latitud" id="latitud" class="form-control" value="<?php if (!empty($_POST['id'])) echo $row['latitud']; ?>" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3">Longitud :</label>
                    <div class="col-sm-8">
                        <input type="text" name="longitud" id="longitud" class="form-control" value="<?php if (!empty($_POST['id'])) echo $row['longitud']; ?>" />
                    </div>
                </div>
                <!-- form-group -->

            </div><!-- panel-body -->
            <div class="panel-footer">
                <input type="submit" class="btn btn-primary mr5" id="btn_guardar" value="Guardar" />
                <button class="btn btn-danger mr5" onclick="estados_registro()">Cancelar</button>
            </div><!-- panel-footer -->
        </div><!-- panel-default -->
        <input type="hidden" name="opcion" id="opcion" value="<?php echo $es_edicion ? '111' : '110'; ?>" />
        <input type="hidden" name="id" id="id" value="<?php if ($es_edicion) echo $id; ?>" />

    </form>
    <div id="cargando"></div>
