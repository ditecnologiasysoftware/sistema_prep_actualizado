<?php 
require "../php/inicializandoDatosExterno.php";

$pagina = !empty($_POST['pag']) ? $funciones->limpia($_POST['pag']) : 1;
$limite = 7;
$cantenlaces = 7;
$inicio = ($pagina - 1) * $limite;

$peticion_enlace = "";
$sentencia = "";
$query = "";
$query .= $entity->electoralScope('c.id_proceso_electoral');
if ($id_municipio != 0) {
    $query_pe .= $entity->statement('fragment.candidato_lista.13.1') . $id_municipio . "";
} elseif ($id_estado != 0) {
    $query_pe .= $entity->statement('fragment.candidato_lista.15.2') . $id_estado . "";
}

if ($id_estado != 0) {
    $query .= $entity->statement('fragment.candidato_lista.19.3') . $id_estado . "";
}
if ($id_municipio != 0) {
    $query .= $entity->statement('fragment.candidato_lista.22.4') . $id_municipio . "";
}
if (!empty($_POST['n'])) {
    $sentencia .= $entity->statement('fragment.candidato_lista.25.5') . $_POST['n'] . "%'";
    $peticion_enlace .= "&n=" . $_POST['n'];
}

if (!empty($_POST['pe'])) {
	$sentencia .= $entity->statement('fragment.candidato_lista.30.6') . $entity->scopedProcessId($_POST['pe']) . "'";
    $peticion_enlace .= "&pe=" . $_POST['pe'];
}

$cadena = $entity->statement('candidato_lista.34.1') . $sentencia . $query . $entity->statement('fragment.candidato_lista.34.7') . $inicio . "," . $limite . "";

$cadena2 = $entity->statement('candidato_lista.38.2') . $sentencia . $query;

$totalRegistros = $entity->scalar($cadena2);
$resul_lista = $entity->objects($cadena);

?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">Listado Candidato Electoral</h4>
            <p></p>
        </div>
        <div class="panel-body">
            
            <table id="basicTable" class="table table-striped table-bordered responsive">
                <thead class="">
                    <tr>
                        <th>Nombre</th>
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

                        $tipoEleccion = $entity->scalar($entity->statement('candidato_lista.69.3') . $resultado_fila->id_proceso_electoral);
                        $tipoPartidos = "";
                        $partidos = $entity->objects($entity->statement('candidato_lista.71.4') . $resultado_fila->id_candidato);
                        foreach ($partidos as $value) {
                            $tipoPartidos .= $value->nombre . ', ';
                        }
                        $tipoPartidos = trim($tipoPartidos, ', ');
                    ?>
                        <tr>
                            <td><?php
                                if ($resultado_fila->principal == 1) {
                                    $princial = '<font color="#078514">Candidato Princial</font>';
                                } else {
                                    $princial = '';
                                }
                                echo '<strong>' . $resultado_fila->nombre . ' - <font color="#314F8B">' . $tipoEleccion . '</font></strong><br>
                                                            <font color="#23558C">' . $tipoPartidos . '</font><br>' . $princial; ?></td>
                            <?php if ($editar == 1) { ?>
                                <td align="center"><a class="btn btn-success" onclick="candidato_registro(<?= $resultado_fila->id_candidato ?>)"><span class="fa fa-pen"></span></a></td>
                            <?php } ?>
                            <?php if ($eliminar == 1) { ?>
                                <td align="center"><a class="btn btn-danger" href="javascript:eliminar(this,<?= $resultado_fila->id_candidato ?>,41)"><span class="fa fa-trash"></span></a></td>
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
                                    ?><li><a href="javascript:candidato_lista(<?php echo $enlace['numero'] ?>)"><?php echo $enlace['vista']; ?></a></li><?php
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
