<?php
require "../php/inicializandoDatosExterno.php";
$pagina = isset($_POST['pag']) ? $funciones->limpia($_POST['pag']) : 1;
$limite = 10;
$cantenlaces = 7;
$inicio = ($pagina - 1) * $limite;

$peticion_enlace = "";
$sentencia = "";
$query = "";

if (!empty($_POST['n'])) {
    $sentencia .= $entity->statement('fragment.nominal_lista.13.1') . $_POST['n'] . "%'";
    $peticion_enlace .= "&n=" . $_POST['n'];
}
$cadena = $entity->statement('nominal_lista.16.1') . $sentencia . $entity->statement('fragment.nominal_lista.16.2') . $inicio . "," . $limite . "";

$cadena2 = $entity->statement('nominal_lista.18.2') . $sentencia;

$totalRegistros = $entity->scalar($cadena2);

$resul_lista = $entity->objects($cadena);
 
?>

<div class="col-md-8">
    <div class="panel panel-default">
        <div class="panel-heading">

            <h4 class="panel-title">Listado Lista Nominal</h4>
            <p></p>
        </div>
        <div class="panel-body">
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
                                                    <label class="col-sm-12">Nombre :</label>
                                                    <input type="text" name="n" id="n" class="form-control" value="<?php if (!empty($_POST['n'])) echo $_POST['n']; ?>" />

                                                </div><br>
                                                <input type="submit" class="btn btn-primary mr5" value="Buscar" />
                                                <input type="button" class="btn btn-secundary mr5" onclick="window.location.href='lista_nominal'" value="Cancelar">

                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div><!-- panel -->
            </div><!-- panel-group -->

            <table id="basicTable" class="table table-striped table-bordered responsive">
                <thead class="">
                    <tr>
                        <th>Consecutivo</th>
                        <th>Nombre</th>
                        <th>Cve. Elector</th>
                        <th>Casilla</th>
                        <th>¿Voto?</th>
                        <?php if ($editar == 1) { ?>
                            <th>Editar</th>
                        <?php } ?>
                        <?php if ($eliminar == 1) { ?>
                            <th>Eliminar</th>
                        <?php } ?>
                    </tr>
                </thead>

                <tbody>
                    <?php
                    foreach ($resul_lista as $resultado_fila) {
                        $casilla = $entity->row($entity->statement('nominal_lista.89.3') . $resultado_fila->id_casilla);
                    ?>
                        <tr>
                            <td><?php echo $resultado_fila->folio; ?></td>
                            <td><?php echo '<strong>' . $resultado_fila->nombre . '</strong>'; ?></td>
                            <td><?php echo $resultado_fila->clave_elector; ?></td>
                            <td> Sección: <?= $casilla['seccion'] . ' ' . $casilla['nombre'] ?>
                            </td>
                            <td><?php echo ($resultado_fila->estatus_voto == 1) ? 'SI' : 'NO'; ?></td>

                            <?php if ($editar == 1) { ?>
                                <td align="center"><a class="btn btn-success" onclick="nominal_registro(<?= $resultado_fila->id_lista_nominal ?>)"><span class="fa fa-pen"></span></a></td>
                            <?php } ?>
                            <?php if ($eliminar == 1) { ?>
                                <td align="center"><a class="btn btn-danger" href="javascript:eliminar(this,<?= $resultado_fila->id_lista_nominal ?>,44)"><span class="fa fa-trash"></span></a></td>
                            <?php } ?>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="7">
                            <div>
                                <ul class="pagination pagination-sm">
                                    <?php
                                    $pag = new Paginador();
                                    // Configuramos la cantidad de registros por pagina, por defecto son 10.
                                    // Debe de estar coordinado con la cantidad de registros traídos con la consulta MySQL.
                                    $pag->setCantidadRegistros($limite);
                                    // Configurar la cantidad de enlaces en la barra de navegación (por defecto son 10).
                                    $pag->setCantidadEnlaces($cantenlaces);
                                    //$pag->setMarcador('', '');
                                    // Y mandamos a paginar desde la pagina actual y le pasamos tambien el total
                                    // de registros de la consulta mysql.
                                    $datos = $pag->paginar($pagina, $totalRegistros);

                                    if ($datos) {

                                        echo 'Pagina: ' . $pagina . ' de ' . $pag->getCantidadPaginas() . '<br />';
                                        foreach ($datos as $enlace) {
                                            if ($enlace['active'] == false) {
                                    ?><li><a href="?pag=<?php echo $enlace['numero'] . $peticion_enlace ?>"><?php echo $enlace['vista']; ?></a></li><?php
                                            } else {
                                                ?><li class="active"><span><?php echo $enlace['vista']; ?></span></li><?php
                                            }
                                        }
                                    }

                                    ?>
                                </ul>
                            </div>
                        </td>
                    </tr>
                </tfoot>
            </table>

        </div><!-- body -->

    </div><!-- panel default -->

</div><!-- col 8 -->

</div><!-- row -->