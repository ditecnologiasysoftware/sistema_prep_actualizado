<?php 
require "../php/inicializandoDatosExterno.php";
$pagina = isset($_POST['pag']) ? $funciones->limpia($_POST['pag']) : 1;
$limite = 10;
$cantenlaces = 7;
$inicio = ($pagina - 1) * $limite;

$query_c1 = "";
$query_c2 = "";

if ($id_municipio != 0) {
    $query_pe .= $entity->statement('fragment.resultado_eleccion_lista.12.1') . $id_municipio . "";
    $query_c1 .= $entity->statement('fragment.resultado_eleccion_lista.13.2') . $id_municipio . "";
    $query_c2 .= $entity->statement('fragment.resultado_eleccion_lista.14.3') . $id_municipio . "";
} elseif ($id_estado != 0) {
    $query_pe .= $entity->statement('fragment.resultado_eleccion_lista.16.4') . $id_estado . "";
    $query_c1 .= $entity->statement('fragment.resultado_eleccion_lista.17.5') . $id_estado . "";
    $query_c2 .= $entity->statement('fragment.resultado_eleccion_lista.18.6') . $id_estado . "";
}

$peticion_enlace = "";
$sentencia = "";

if (!empty($_POST['q'])) {
    $sentencia .= $entity->statement('fragment.resultado_eleccion_lista.25.7') . $_POST['q'] . "'";
    $peticion_enlace .= "&q=" . $_POST['q'];
}

$query = "";
$query .= $entity->electoralScope('c.id_proceso_electoral');

if ($id_municipio != 0) {
    $query .= $entity->statement('fragment.resultado_eleccion_lista.32.8') . $id_municipio . "";
} elseif ($id_estado != 0) {
    $query .= $entity->statement('fragment.resultado_eleccion_lista.34.9') . $id_estado . "";
}

if (!empty($_POST['estado_busqueda'])) {
    if ($_POST['estado_busqueda'] != 0) {
        $query .= $entity->statement('fragment.resultado_eleccion_lista.39.10') . $_POST['estado_busqueda'] . "";
    }
    $peticion_enlace .= "&estado_busqueda=" . $_POST['estado_busqueda'];
}

if (!empty($_POST['municipio_busqueda'])) {
    if ($_POST['municipio_busqueda'] != 0) {
        $query .= $entity->statement('fragment.resultado_eleccion_lista.46.11') . $_POST['municipio_busqueda'] . "";
    }
    $peticion_enlace .= "&municipio_busqueda=" . $_POST['municipio_busqueda'];
}
$cadena = $entity->statement('resultado_eleccion_lista.50.1') . $query . $sentencia . $entity->statement('fragment.resultado_eleccion_lista.50.12') . $inicio . "," . $limite;

$cadena2 = $entity->statement('resultado_eleccion_lista.56.2') . $query . $sentencia;

$entity->objects($cadena2);
$totalRegistros = $entity->numregistros();

$resul_lista = $entity->objects($cadena);
?>
                   

    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">Listado Resultado Electorales</h4>
            <p></p>
        </div>
        <div class="panel-body">
            <table id="basicTable" class="table table-striped table-bordered responsive">
                <thead class="">
                    <tr>
                        <th>Elección</th>
                        <th>Candidato</th>
                        <th>Tipo</th>
                        <th>Partidos</th>
                        <th>Casillas</th>
                        <th>Votos</th>
                        <th>Total</th>
                        <th>Porcentaje</th>
                        <th>Casillas</th>
                        <th>Partidos</th>
                        <th>Candidatos</th>
                        <th>Graficas</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                     foreach ($resul_lista as $resultado_fila) {
                        $estadolist = '';

                        if ($resultado_fila->id_estado != 0 && $resultado_fila->id_municipio != 0) {
                            $estadolist = $entity->row($entity->statement('resultado_eleccion_lista.96.3') . $resultado_fila->id_municipio);
                            $mapatipo = '2';
                            $latLong = $estadolist['lat'] . ',' . $estadolist['lon'];
                        } elseif ($resultado_fila->id_estado != 0 || $resultado_fila->id_municipio == 0) {
                            $estadolist = $entity->row($entity->statement('resultado_eleccion_lista.100.4') . $resultado_fila->id_estado);
                            $mapatipo = '1';
                            $latLong = $estadolist['lat'] . ',' . $estadolist['lon'];
                        }

                        $entity->scalar($entity->statement('resultado_eleccion_lista.105.5') . $query_c1 . $entity->statement('fragment.resultado_eleccion_lista.99.13'));
                        $totalCasillas = $entity->numregistros();

                        $entity->objects($entity->statement('resultado_eleccion_lista.110.6') . $resultado_fila->id_proceso_electoral . $query_c2 . $entity->statement('fragment.resultado_eleccion_lista.102.14'));
                        $totalCasillasRegistradas = $entity->numregistros();

                        $tipoPartidos = "";
                        $partidos = $entity->objects($entity->statement('resultado_eleccion_lista.114.7') . $resultado_fila->id_candidato);
                        foreach ($partidos as $value) {
                            $tipoPartidos .= $value->nombre . ', ';
                        }
                        $tipoPartidos = trim($tipoPartidos, ', ');

                        $votos = $entity->scalar($entity->statement('resultado_eleccion_lista.120.8') . $resultado_fila->id_candidato);

                        $votos_total = $entity->scalar($entity->statement('resultado_eleccion_lista.122.9') . $resultado_fila->id_proceso_electoral);

                        $votos_nulos = $entity->scalar($entity->statement('resultado_eleccion_lista.124.10') . $resultado_fila->id_proceso_electoral);
                        $no_registrados = $entity->scalar($entity->statement('resultado_eleccion_lista.125.11') . $resultado_fila->id_proceso_electoral);

                        $votos_total = $votos_total + $votos_nulos + $no_registrados;
                    ?>
                        <tr>
                            <td>
                                <font><?php echo $funciones->fecha4($resultado_fila->f_proceso) ?></font>
                            </td>
                            <td>
                                <font><?php echo $resultado_fila->nombre ?></font>
                            </td>
                            <td><strong><?php echo $resultado_fila->nom_tipoeleccion . '<br> <font color="#316EA6">' . $estadolist['nom'] . '</font>'; ?></strong></td>
                            <td>
                                <font><b><?php echo $tipoPartidos ?></b></font>
                            </td>
                            <td>
                                <font><b><?php echo $totalCasillasRegistradas ?> de <?php echo $totalCasillas ?></b></font>
                            </td>
                            <td align="center">
                                <strong>
                                    <?php
                                    echo number_format($votos, 0, '.', ',')
                                    ?>
                                </strong>
                            </td>
                            <td align="center">
                                <strong>
                                    <?php
                                    echo number_format($votos_total, 0, '.', ',')
                                    ?>
                                </strong>
                            </td>
                            <td align="center">
                                <strong>
                                    <?php
                                    $porcentaje = $votos * 100 / $votos_total;
                                    if ($porcentaje > 50)
                                        echo '<span style = "color:green;">' . number_format($porcentaje, 1, '.', ',') . '</span>';
                                    else
                                        echo '<span style = "color:red;">' . number_format($porcentaje, 1, '.', ',') . '</span>';
                                    ?> %
                                </strong>
                            </td>
                            <td align="center">
                                <a class="btn btn-success" href='javascript:casilla_resultados(<?php echo $resultado_fila->id_proceso_electoral; ?>,<?php echo $resultado_fila->id_candidato; ?>)' title="Resultados por casillas"><span class="glyphicon glyphicon-list"></span></a>
                            </td>
                            <td align="center">
                                <a class="btn btn-success" href='javascript:partido_resultados(<?php echo $resultado_fila->id_proceso_electoral; ?>,<?php echo $resultado_fila->id_candidato; ?>)' title="Resultados por partidos"><span class="glyphicon glyphicon-list"></span></a>
                            </td>
                            <td align="center">
                                <a class="btn btn-success" href='javascript:candidato_resultados(<?php echo $resultado_fila->id_proceso_electoral; ?>,<?php echo $resultado_fila->id_candidato; ?>)' title="Resultados por candidatos"><span class="glyphicon glyphicon-list"></span></a>
                            </td>
                            <td align="center">
                                <a class="btn btn-success" href='javascript:graficas_resultado_modal(<?php echo $resultado_fila->id_proceso_electoral; ?>,<?php echo $resultado_fila->id_candidato; ?>)' title="Graficas de resultados"><span class="glyphicon glyphicon-ok-circle"></span></a>
                            </td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="10">
                            <div>
                                <ul class="pagination pagination-sm">
                                    <?php
                                    $pag = new Paginador();
                                    $pag->setCantidadRegistros($limite);
                                    $pag->setCantidadEnlaces($cantenlaces);
                                    $datos = $pag->paginar($pagina, $totalRegistros);

                                    if ($datos) {
                                        echo 'Pagina: ' . $pagina . ' de ' . $pag->getCantidadPaginas() . '<br />';
                                        foreach ($datos as $enlace) {
                                            if ($enlace['active'] == false) {
                                    ?><li><a href="javascript:resultado_eleccion_lista(<?php echo $enlace['numero']?>)"><?php echo $enlace['vista']; ?></a></li><?php
                                    } else {
                                        ?><li class="active"><span><?php echo $enlace['vista']; ?></span></li><?php
                                            }
                                        }
                                    } ?>
                                </ul>
                            </div>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div><!-- body -->
    </div><!-- panel default -->
