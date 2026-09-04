<?php
$query = "";
$query_pe = "";

if ($id_municipio != 0) {
    $query .= " and c.id_municipio = " . $id_municipio . "";
    $query_pe .= " and id_municipio = " . $id_municipio . "";
} elseif ($id_estado != 0) {
    $query .= " and m.id_estado = " . $id_estado . "";
    $query_pe .= " and id_estado = " . $id_estado . "";
}
?>
<script>
    window.onload = function() {
        //lista_candidatos();
        resultados_registro();
    }
</script>
<div class="pageheader">
    <div class="media">
        <div class="pageicon pull-left">
            <i class="fa fa-database"></i>
        </div>
        <div class="media-body">
            <ul class="breadcrumb">
                <li><a href=""><i class="glyphicon glyphicon-home"></i></a></li>
                <li>Registro de Resultados</li>
            </ul>
            <h4>Registro de Resultados</h4>
        </div>
    </div><!-- media -->
</div><!-- pageheader -->

<div class="content-panel">
    <div class="row">
        <div id="contenido"></div>
        <div id="listado">Seleccionar Casilla</div>
    </div>
</div>