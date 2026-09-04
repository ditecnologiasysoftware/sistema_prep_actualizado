<?php 
require "../php/inicializandoDatosExterno.php";
$pagina = isset($_POST['pag']) ? $funciones->limpia($_POST['pag']) : 1;
$limite = 10;
$cantenlaces = 7;
$inicio = ($pagina - 1) * $limite;

$query_c1 = "";
$query_c2 = "";

if ($id_municipio != 0) {
    $query_pe .= " and id_municipio = " . $id_municipio . "";
    $query_c1 .= " and c.id_municipio = " . $id_municipio . "";
    $query_c2 .= " and municipio_c = " . $id_municipio . "";
} elseif ($id_estado != 0) {
    $query_pe .= " and id_estado = " . $id_estado . "";
    $query_c1 .= " and m.id_estado = " . $id_estado . "";
    $query_c2 .= " and estado_c = " . $id_estado . "";
}

$peticion_enlace = "";
$sentencia = "";

if (!empty($_POST['q'])) {
    $sentencia .= " AND c.id_proceso_electoral = '" . $_POST['q'] . "'";
    $peticion_enlace .= "&q=" . $_POST['q'];
}

$query = "";

if ($id_municipio != 0) {
    $query .= " and pe.id_municipio = " . $id_municipio . "";
} elseif ($id_estado != 0) {
    $query .= " and pe.id_estado = " . $id_estado . "";
}

if (!empty($_POST['estado_busqueda'])) {
    if ($_POST['estado_busqueda'] != 0) {
        $query .= " and pe.id_estado = " . $_POST['estado_busqueda'] . "";
    }
    $peticion_enlace .= "&estado_busqueda=" . $_POST['estado_busqueda'];
}

if (!empty($_POST['municipio_busqueda'])) {
    if ($_POST['municipio_busqueda'] != 0) {
        $query .= " and pe.id_municipio = " . $_POST['municipio_busqueda'] . "";
    }
    $peticion_enlace .= "&municipio_busqueda=" . $_POST['municipio_busqueda'];
}
$cadena = "SELECT c.*, pe.id_tipo_eleccion, pe.id_municipio, pe.id_estado, pe.fecha as f_proceso, pe.descripcion as desc_proceso, t.nombre as nom_tipoeleccion, t.tipo as tipo_eleccion 
FROM tblc_candidato AS c 
INNER JOIN tblc_proceso_electoral AS pe ON c.id_proceso_electoral = pe.id_proceso_electoral 
INNER JOIN tblc_tipo_eleccion as t ON pe.id_tipo_eleccion = t.id_tipo_eleccion 
WHERE c.principal = 1" . $query . $sentencia . " LIMIT " . $inicio . "," . $limite;

$cadena2 = "SELECT COUNT(c.id_candidato) FROM tblc_candidato AS c 
INNER JOIN tblc_proceso_electoral AS pe ON c.id_proceso_electoral = pe.id_proceso_electoral 
WHERE c.principal = 1" . $query . $sentencia;

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
                            $estadolist = $entity->row("SELECT CONCAT(e.nombre,', ',m.nombre) AS nom, m.latitud as lat, m.longitud as lon FROM tblc_estado as e JOIN tblc_municipio as m ON(e.id_estado = m.id_estado) WHERE m.id_municipio = " . $resultado_fila->id_municipio);
                            $mapatipo = '2';
                            $latLong = $estadolist['lat'] . ',' . $estadolist['lon'];
                        } elseif ($resultado_fila->id_estado != 0 || $resultado_fila->id_municipio == 0) {
                            $estadolist = $entity->row("SELECT e.nombre as nom, e.latitud as lat, e.longitud as lon FROM tblc_estado as e WHERE e.id_estado = " . $resultado_fila->id_estado);
                            $mapatipo = '1';
                            $latLong = $estadolist['lat'] . ',' . $estadolist['lon'];
                        }

                        $entity->scalar("SELECT c.id_casilla FROM tblc_casilla AS c 
                                                        INNER JOIN tblc_municipio as m ON c.id_municipio = c.id_municipio 
                                                        WHERE c.id_casilla != 0" . $query_c1 . " GROUP BY c.id_casilla");
                        $totalCasillas = $entity->numregistros();

                        $entity->objects("SELECT * FROM vw_resultado_elecciones WHERE idp_electoral_c = " . $resultado_fila->id_proceso_electoral . $query_c2 . " GROUP BY id_casilla");
                        $totalCasillasRegistradas = $entity->numregistros();

                        $tipoPartidos = "";
                        $partidos = $entity->objects("SELECT p.nombre FROM tblc_candidato_partido AS cp JOIN tblc_partido_politico AS p ON cp.id_partido_politico = p.id_partido_politico WHERE cp.id_candidato =" . $resultado_fila->id_candidato);
                        foreach ($partidos as $value) {
                            $tipoPartidos .= $value->nombre . ', ';
                        }
                        $tipoPartidos = trim($tipoPartidos, ', ');

                        $votos = $entity->scalar("SELECT SUM(resultado) as sumaresultado FROM tbl_resultado WHERE id_candidato = " . $resultado_fila->id_candidato);

                        $votos_total = $entity->scalar("SELECT SUM(resultado) as sumaresultado FROM vw_resultado_elecciones WHERE idp_electoral_c = " . $resultado_fila->id_proceso_electoral);

                        $votos_nulos = $entity->scalar("SELECT SUM(votos_nulos) as sumaresultado FROM tbl_acta WHERE id_proceso_electoral = " . $resultado_fila->id_proceso_electoral);
                        $no_registrados = $entity->scalar("SELECT SUM(no_registrados) as sumaresultado FROM tbl_acta WHERE id_proceso_electoral = " . $resultado_fila->id_proceso_electoral);

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