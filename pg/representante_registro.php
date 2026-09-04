<?php
require "../php/inicializandoDatosExterno.php";
if (!empty($_POST['id'])) {
    $id = $funciones->limpia($_POST['id']);
    $row = $entity->row($querys->getrepresentante($id));
}
?>
    <form id="enviar_formulario" class="form-horizontal" method="post" enctype="multipart/form-data" action="php/subir.php">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">Formulario de Registro</h4>
                <p></p>
            </div>
            <div class="panel-body">

                <?php if ($id_proceso_electoral == 0) { ?>
                <div class="form-group">
                    <label class="col-sm-3">Proceso Electoral :</label>
                    <div class="col-sm-9">
                        <select name="id_proceso_electoral" id="id_proceso_electoralb" class="form-control" required>
                            <option value="0"> Seleccionar Proceso Electoral </option>
                            <?php
                            if (!empty($_POST['id'])) echo $funciones->llenarcombomodifica($querys->comboprocesoelectoral(), $row['id_proceso_electoral']);
                            else echo $funciones->llenarcombo($querys->comboprocesoelectoral());
                            ?>
                        </select>
                    </div>
                </div><?php 
                }
                else {
                    echo '<input type="hidden" name="id_proceso_electoral" id="id_proceso_electoral" value="'.$id_proceso_electoral.'" />';
                }
                
                if ($id_estado == 0) { ?>
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
                <?php }
                else {
                    echo '<input type="hidden" name="id_estado" id="id_estado" value="'.$id_estado.'" />';
                }

                if ($id_municipio == 0) { ?>
                    <div class="form-group">
                        <label class="col-sm-3">Municipio :</label>
                        <div class="col-sm-9">
                            <select name="id_municipio" id="id_municipio" class="form-control" onchange="combodependiente('id_municipio', 'casilla', 'combo_dependiente/casillas.php')" required>
                                <option value="0"> Seleccionar Municipio </option>
                                <?php
                                if (!empty($_POST['id'])) echo $funciones->llenarcombomodifica($querys->combomunicipios($row['id_estado']), $row['id_municipio']);
                                else if ($id_estado != 0) echo $funciones->llenarcombo($querys->combomunicipios($id_estado));
                                ?>
                            </select>
                        </div>
                    </div>

                <?php 
                    }
                    else {
                ?>
                    <input type="hidden" name="id_municipio" id="id_municipio" value="<?= $id_municipio; ?>" />
                <?php 
                    }
                ?>

                <div class="form-group">
                    <label class="col-sm-3">Casilla :</label>
                    <div class="col-sm-9">
                        <select name="casilla" id="casilla" class="form-control" required>
                            <option value="0">-- Ninguna Casilla --</option>
                            <?php
                            if (!empty($_POST['id'])) {
                                echo $funciones->llenarcombomodifica($querys->combocasillas(0,$row['id_municipio']), $row['id_casilla']);
                            }
                            else{

                                echo $funciones->llenarcombo($querys->combocasillas($id_estado,$id_municipio));

                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3">Nombre Completo:</label>
                    <div class="col-sm-9">
                        <input type="text" name="nombre" id="nombre" class="form-control" value="<?php if (!empty($_POST['id'])) echo $row['nombre']; ?>" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3">Teléfono :</label>
                    <div class="col-sm-9">
                        <input type="text" name="telefono" id="telefono" class="form-control" value="<?php if (!empty($_POST['id'])) echo $row['telefono']; ?>" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3">Correo :</label>
                    <div class="col-sm-9">
                        <input type="text" name="correo" id="correo" class="form-control" value="<?php if (!empty($_POST['id'])) echo $row['correo']; ?>" />
                    </div>
                </div>
                <div class="form-group"> <br>
                    <center>
                        <font color="#2155A9" size="4">DATOS DE ACCESO</font>
                    </center>
                    <hr>
                    <label class="col-sm-3">Usuario :</label>
                    <div class="col-sm-9">
                        <input type="text" name="usuario" id="usuario" class="form-control" value="<?php if (!empty($_POST['id'])) echo $row['usuario']; ?>" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3">Contraseña :</label>
                    <div class="col-sm-9">
                        <input type="password" name="pass" id="pass" class="form-control" value="" />
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
                <button class="btn btn-danger mr5" onclick="representante_registro()">Cancelar</button>
            </div><!-- panel-footer -->
        </div><!-- panel-default -->
        <input type="hidden" name="opcion" id="opcion" value="<?php if (!isset($_POST['id'])) echo "126";
                                                                else echo "127"; ?>" />
        <input type="hidden" name="id" id="id" value="<?php if (isset($_POST['id'])) echo $id; ?>" />

    </form>
    <div id="cargando"></div>
