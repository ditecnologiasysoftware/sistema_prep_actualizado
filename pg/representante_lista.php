<?php
require "../php/inicializandoDatosExterno.php";
$pagina = !empty($_POST['pagina']) ? $funciones->limpia($_POST['pagina']) : 1;
$limite = 10;
$cantenlaces = 7;
$inicio = ($pagina - 1) * $limite;

$peticion_enlace = "";
$sentencia = "";
$sentencia .= $entity->electoralScope('r.id_proceso_electoral');
$query = "";
if ($_POST['id_estado'] != 0) {
    $id_estado = $funciones->limpia($_POST['id_estado']);
    $sentencia .= $entity->statement('fragment.representante_lista.13.1') . $id_estado . "";
}

if ($_POST['id_municipio'] != 0) {
    $id_municipio = $funciones->limpia($_POST['id_municipio']);
    $sentencia .= $entity->statement('fragment.representante_lista.18.2') . $id_municipio . "";
}

if ($_POST['id_municipio'] != 0) {
    $id_municipio = $funciones->limpia($_POST['id_municipio']);
    $sentencia .= $entity->statement('fragment.representante_lista.23.3') . $id_municipio . "";
}

if (isset($_POST['n'])) {
$sentencia .= $entity->statement('fragment.representante_lista.27.4') . $_POST['n'] . "%'";
$peticion_enlace .= "&n=" . $_POST['n'];
}

$cadena = $entity->statement('representante_lista.31.1') . $sentencia . $entity->statement('fragment.representante_lista.31.5') . $inicio . "," . $limite . "";

$cadena2 = $entity->statement('representante_lista.35.2') . $sentencia;

$totalRegistros = $entity->scalar($cadena2);

$resul_lista = $entity->objects($cadena);
?>
    <div class="panel panel-default">
        <div class="panel-heading">

            <h4 class="panel-title">Listado Representante de Casilla</h4>
            <p></p>
        </div>
        <div class="panel-body">

            <table id="basicTable" class="table table-striped table-bordered responsive">
                <thead class="">
                    <tr>
                        <th>Nombre</th>
                        <th>Municipio</th>
                        <th>Casilla</th>
                        <th>Proceso Elect.</th>
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
                        $procesoelQuery = $entity->statement('representante_lista.70.3').$resultado_fila->id_proceso_electoral;
                        $precesoElectoral = $entity->scalar($procesoelQuery);
                    ?>
                        <tr>
                            <td><?php echo '<strong>' . $resultado_fila->nombre . '</strong><br>Tel. ' . $resultado_fila->telefono; ?></td>
                            <td><?php echo $resultado_fila->municipio; ?></td>
                            <td><?php if ($resultado_fila->id_casilla != 0) echo $funciones->llenarCasillatbl($resultado_fila->id_casilla);
                                else echo "<b>No tiene casilla asignada</b>";  ?></td>
                            <td><?php echo $precesoElectoral; ?></td>
                            <?php if ($editar == 1) { ?>
                                <td align="center"><a class="btn btn-success" onclick="representante_registro(<?= $resultado_fila->id_representante ?>)"><span class="fa fa-pen"></span></a></td>
                            <?php } ?>
                            <?php if ($eliminar == 1) { ?>
                                <td align="center"><a class="btn btn-danger" href="javascript:eliminar(this,<?= $resultado_fila->id_representante ?>,40)"><span class="fa fa-trash"></span></a></td>
                            <?php } ?>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="6">
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
                                    ?><li><a href="javascript:representante_lista(<?php echo $enlace['numero']?>)"><?php echo $enlace['vista']; ?></a></li><?php
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
