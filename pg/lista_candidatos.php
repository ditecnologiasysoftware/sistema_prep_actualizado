<?php
require "../php/inicializandoDatosExterno.php";

 $idproceso = $entity->scopedProcessId($funciones->limpia($_POST['id_proceso_electoral']));
 $casilla = $funciones->limpia($_POST['casilla']);

 $acta = $entity->row($entity->statement('lista_candidatos.7.1') . $idproceso . $entity->statement('fragment.lista_candidatos.7.1') . $casilla);

 $consulta =  $entity->statement('lista_candidatos.9.2') . $idproceso . $entity->statement('fragment.lista_candidatos.9.2');
 $resul_lista = $entity->objects($consulta);

?>

    <form id="enviar_formulario" method="post" action="php/subir.php">
        <?php if ($entity->numregistros() != 0) { ?>
        <table id="basicTable" class="table table-striped table-bordered responsive">
            <thead class="">
                <tr>
                    <th style="width:75%">Partido</th>
                    <th style="width:10%">Total Votos</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    foreach ($resul_lista as $resultado_fila) {
                        $resultados = $entity->scalar($entity->statement('lista_candidatos.30.3') . $casilla . $entity->statement('fragment.lista_candidatos.26.3') . $resultado_fila->id_partido_politico);
                ?>
                        <tr>
                            <td>
                                <img src="archivos/partido_politico/<?php echo $resultado_fila->icono ?>" alt="" style="width: 50px; height: 50px; object-fit: contain; vertical-align: middle;" />&nbsp;&nbsp;&nbsp;<font color="<?= $resultado_fila->colo ?>"><b><?php echo $resultado_fila->partido ?></b></font>
                                <br><?php echo $resultado_fila->nombre ?>
                            </td>
                            <!--<td>
                                                              <?php echo $resultado_fila->nombre ?>
                                                          </td>-->
                            <td style="text-align: right;">
                                <input type="hidden" name="partido[]" id="partido" value="<?php echo $resultado_fila->id_partido_politico; ?>">
                                <input type="hidden" name="candidato[]" id="candidato" value="<?php echo $resultado_fila->id_candidato; ?>">
                                <input style="width: 120px;" type="number" name="votos[]" id="votos" class="form-control" value="<?php echo $resultados ?>" placeholder="Votos">
                            </td>
                        </tr>
                    <?php
                    }
                    ?>
                    <tr>
                        <td>
                            <font color="#2B4E7D"><b>NO REGISTRADOS : </b></font>
                        </td>
                        <td style="text-align: right;"><input style="width: 120px;" type="number" name="no_registrados" id="no_registrados" class="form-control" value="<?php echo $acta['no_registrados'] ?>" placeholder="No registrados"></td>
                    </tr>
                    <tr>
                        <td>
                            <font color="#2B4E7D"><b>VOTOS NULOS : </b></font>
                        </td>
                        <td style="text-align: right;"><input style="width: 120px;" type="number" name="votos_null" id="votos_null" class="form-control" value="<?php echo $acta['votos_nulos'] ?>" placeholder="Votos nulos"></td>
                    </tr>
                    <tr >
                      <td><font color="#2B4E7D"><b>TOTAL DE VOTOS : </b></font></td>
                      <td style="text-align: right;"><input style="width: 120px;" type="number" name="total_votos" id="total_votos" class="form-control" value="<?php echo $acta['total_votos'] ?>" placeholder="total de votos"></td>
                  </tr>
                    <tr>
                        <td>
                            <font color="#2B4E7D"><b>ACTA : </b></font>
                        </td>
                        <td style="text-align: right;">
                            <input type="file" name="acta_file" id="acta_file">
                            <?php
                            if ($acta['votos_nulos'] != "")
                                echo '<a href="archivos/actas_eleccion/' . $acta['archivo'] . '" target="_blank">Ver archivo</a>';
                            ?>
                        </td>
                    </tr>

                    <tr>
                        <td colspan="2">
                            <div class="form-group text-right">
                                <input type="submit" class="btn btn-primary mr5" value="Guardar Resultados">
                                <button class="btn btn-danger mr5" onclick="window.location.href='resultados'">Cancelar</button>
                            </div>
                        </td>
                    </tr>
            </tbody>
        </table>

        <?php
                } else {
                    echo '<center><h1>No se encontraron resultados</h1></center>';
                }
        ?>

        <input type="hidden" name="opcion" id="opcion" value="131" />
        <input type="hidden" name="idprocesoElect" id="idprocesoElect" value="<?php echo $idproceso; ?>" />
        <input type="hidden" name="idcasillaElect" id="idcasillaElect" value="<?php echo $casilla; ?>" />

    </form>
