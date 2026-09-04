<?php
require "../php/inicializandoDatosExterno.php"; 
 $id = $funciones->limpia($_POST['id']);
 $datosproceso = $entity->row($querys->getprocesoelectoral($id));

        switch($datosproceso['tipo']){

            case 1: //Federal

            if ($id_estado == 0) { ?>
                <div class="form-group">
                    <label class="col-sm-12">Estado :</label>
                    <div class="col-sm-12">
                        <select name="id_estado" id="id_estado" onchange="combodependiente('id_estado', 'id_municipio', 'combo_dependiente/municipios2.php')" class="form-control" required>
                            <option value="0"> Seleccionar Estado </option>
                            <?php
                            echo $funciones->llenarcombo($querys->comboestados());
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
                    <label class="col-sm-12">Municipio :</label>
                    <div class="col-sm-12">
                        <select name="id_municipio" id="id_municipio" class="form-control" onchange="combodependiente('id_municipio', 'casilla', 'combo_dependiente/casillas.php')" required>
                            <option value="0"> Seleccionar Municipio </option>
                            <?php
                            if ($id_estado != 0) echo $funciones->llenarcombo($querys->combomunicipios($id_estado));
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
                <label class="col-sm-12"><b>Seleccionar Casilla Electoral :</b></label>
                <div class="col-sm-12">
                    <select name="casilla" id="casilla" class="form-control" required onchange="resultados_lista()">
                        <option value="">-- Seleccionar Casilla --</option>
                        <?php
                        if($id_municipio != 0) echo $funciones->llenarcombo($querys->combocasillas(0,$id_municipio));
                        ?>
                    </select>
                </div>
            </div>

            <?php
            break;

            case 2: //Estatal
            ?>
            <div class="form-group">
                <label class="col-sm-12"><b>Estado :</b></label>
                <div class="col-sm-12">
                    <?php $datcampo = $entity->row($querys->getestado($datosproceso['id_estado'])); ?>
                    <input type="text" disabled class="form-control" value="<?= $datcampo['nombre'] ?>" />
                    <input type="hidden" name="id_estado" id="id_estado" class="form-control" value="<?= $datosproceso['id_estado'] ?>" />
                </div>
            </div>

            <?php if ($id_municipio == 0) { ?>
                <div class="form-group">
                    <label class="col-sm-12">Municipio :</label>
                    <div class="col-sm-12">
                        <select name="id_municipio" id="id_municipio" class="form-control" onchange="combodependiente('id_municipio', 'casilla', 'combo_dependiente/casillas.php')" required>
                            <option value="0"> Seleccionar Municipio </option>
                            <?php
                            echo $funciones->llenarcombo($querys->combomunicipios($datosproceso['id_estado']));
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
                <label class="col-sm-12"><b>Seleccionar Casilla Electoral :</b></label>
                <div class="col-sm-12">
                    <select name="casilla" id="casilla" class="form-control" required onchange="resultados_lista()">
                        <option value="">-- Seleccionar Casilla --</option>
                        <?php
                        if($id_municipio != 0) echo $funciones->llenarcombo($querys->combocasillas(0,$id_municipio));
                        ?>
                    </select>
                </div>
            </div>

            <?php
            break;

            case 3: // Municipal

            ?>

            <div class="form-group">
                <label class="col-sm-12"><b>Estado :</b></label>
                <div class="col-sm-12">
                    <?php $datcampo = $entity->row($querys->getestado($datosproceso['id_estado'])); ?>
                    <input type="text" disabled class="form-control" value="<?= $datcampo['nombre'] ?>" />
                    <input type="hidden" name="id_estado" id="id_estado" class="form-control" value="<?= $datosproceso['id_estado'] ?>" />
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-12"><b>Municipio :</b></label>
                <div class="col-sm-12">
                    <?php $datcampo = $entity->row($querys->getmunicipio($datosproceso['id_municipio'])); ?>
                    <input type="text" disabled class="form-control" value="<?= $datcampo['nombre'] ?>" />
                    <input type="hidden" name="id_municipio" id="id_municipio" class="form-control" value="<?= $datosproceso['id_municipio'] ?>" />
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-12"><b>Seleccionar Casilla Electoral :</b></label>
                <div class="col-sm-12">
                    <select name="casilla" id="casilla" class="form-control" required onchange="resultados_lista()">
                        <option value="">-- Seleccionar Casilla --</option>
                        <?php
                        echo $funciones->llenarcombo($querys->combocasillas($datosproceso['id_estado'],$datosproceso['id_municipio']));
                        ?>
                    </select>
                </div>
            </div>
            <?php

            break;

        }

        ?>
