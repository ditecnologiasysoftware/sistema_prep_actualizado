<?php
require "../php/inicializandoDatosExterno.php";

if (!empty($_POST['id'])) {
    $id = $funciones->limpia($_POST['id']);
    $row = $entity->row($entity->statement('estatus_casilla_registro.6.1') . $id . " ");
}
?>
    <form class="form-horizontal" id="enviar_formulario" method="post" enctype="multipart/form-data"
        action="php/subir.php">
        <div class="panel panel-default">
            <div class="panel-heading">

                <h4 class="panel-title">Formulario de Registro</h4>
                <p></p>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <label class="col-sm-3">Proceso Electoral :</label>
                    <div class="col-sm-9">
                        <select name="id_proceso_electoral" id="id_proceso_electoral" class="form-control">
                            <?php
                            if (!empty($_POST['id']))
                                echo $funciones->llenarcombomodifica($entity->statement('estatus_casilla_registro.24.2') . $query_pe . $entity->statement('fragment.estatus_casilla_registro.24.1'), $row['id_proceso_electoral']);
                            else
                                echo $funciones->llenarcombomodifica($entity->statement('estatus_casilla_registro.26.3') . $query_pe . $entity->statement('fragment.estatus_casilla_registro.26.2'), $row['id_proceso_electoral']);
                            ?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3">Acción :</label>
                    <div class="col-sm-9">
                        <select name="tipo" id="tipo" class="form-control">
                            <?php
                            if (!empty($_POST['id']))
                                echo $funciones->getcomboestatuscasilla($row['tipo']);
                            else
                                echo $funciones->getcomboestatuscasilla(1);
                            ?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3">Casilla :</label>
                    <div class="col-sm-9">
                        <select name="id_casilla" id="id_casilla" class="form-control">
                            <option value=""> - Seleccionar Casilla -</option>
                            <?php
                            if (!empty($_POST['id']))
                                echo $funciones->llenarcombomodificaCasilla($entity->statement('estatus_casilla_registro.53.4') . $query . $entity->statement('fragment.estatus_casilla_registro.53.3'), $row['id_casilla']);
                            else
                                echo $funciones->llenarcombomodificaCasilla($entity->statement('estatus_casilla_registro.55.5') . $query . $entity->statement('fragment.estatus_casilla_registro.55.4'), 0);
                            ?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3">Hora :</label>
                    <div class="col-sm-9">
                        <input type="text" name="hora" id="hora_inicio" class="form-control" value="<?php if (!empty($_POST['id']))
                            echo substr($row['fecha_hora'], 11, 5); ?>" />
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3">Observaciones :</label>
                    <div class="col-sm-9">
                        <textarea name="coordenadas" rows="4" class="form-control"><?php if (!empty($_POST['id']))
                            echo $row['observaciones']; ?></textarea>
                    </div>
                </div>
            </div><!-- panel-body -->
            <div class="panel-footer">
                <button class="btn btn-primary mr5"><?php if (!empty($_POST['id']))
                    echo "Editar";
                else
                    echo "Guardar"; ?></button>
                <?php
                $redi = "location='estatus_casilla'";

                if (!empty($_POST['id']))
                    echo '<button class="btn btn-danger mr5" onclick="' . $redi . '">Cancelar</button>';

                ?>
            </div><!-- panel-footer -->
        </div><!-- panel-default -->
        <input type="hidden" name="opcion" id="opcion" value="<?php if (!isset($_POST['id']))
            echo "134";
        else
            echo "135"; ?>" />
        <input type="hidden" name="id" id="id" value="<?php if (isset($_POST['id']))
            echo $id; ?>" />


        <div id="cargando"></div>
    </form>
