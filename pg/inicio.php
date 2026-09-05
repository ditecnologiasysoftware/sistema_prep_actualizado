<?php
$dashboard = $entity->electoralDashboard(
    (int) $id_proceso_electoral,
    (int) $id_estado,
    (int) $id_municipio
);
$puedeVerResultados = $entity->userCanAccessAny((int) $id_usuario, ['resultado_eleccion', 'resultados', 'registro_resultados_completo']);
$puedeVerCasillas = $entity->userCanAccessAny((int) $id_usuario, ['casilla_electoral', 'estatus_casilla']);
$puedeVerCandidatos = $entity->userCanAccessAny((int) $id_usuario, ['candidato']);
?>
<style>
    .prep-dashboard { padding: 24px; }
    .prep-dashboard .scope-banner { background: linear-gradient(135deg, #123b67, #2374a7); color: #fff; border-radius: 12px; padding: 22px; margin-bottom: 22px; box-shadow: 0 8px 24px rgba(18,59,103,.18); }
    .prep-dashboard .scope-banner h2 { margin: 0 0 6px; font-weight: 700; }
    .prep-dashboard .scope-banner p { margin: 0; opacity: .9; }
    .prep-dashboard .metric { background: #fff; border: 1px solid #e5eaf0; border-radius: 10px; padding: 18px; min-height: 128px; margin-bottom: 20px; box-shadow: 0 3px 12px rgba(25,45,70,.07); }
    .prep-dashboard .metric i { color: #2374a7; font-size: 28px; float: right; }
    .prep-dashboard .metric .value { font-size: 30px; font-weight: 700; color: #16324f; line-height: 1.2; }
    .prep-dashboard .metric .label { color: #66788a; font-size: 13px; padding: 0; }
    .prep-dashboard .progress { height: 12px; margin: 12px 0 4px; background: #e8eef4; }
    .prep-dashboard .progress-bar { background: #26a269; }
    .prep-dashboard .panel { border-radius: 10px; overflow: hidden; border-color: #e5eaf0; }
    .prep-dashboard .panel-heading { background: #fff; border-bottom: 1px solid #e5eaf0; padding: 16px 20px; }
    .prep-dashboard .leader-votes { font-weight: 700; color: #123b67; text-align: right; }
</style>

<div class="pageheader">
    <div class="media">
        <div class="pageicon pull-left"><i class="fa fa-dashboard"></i></div>
        <div class="media-body">
            <ul class="breadcrumb"><li><i class="glyphicon glyphicon-home"></i></li><li>Inicio</li></ul>
            <h4>Panel de control electoral</h4>
        </div>
    </div>
</div>

<div class="contentpanel prep-dashboard">
    <div class="scope-banner">
        <h2>Bienvenido, <?= htmlspecialchars($nombre, ENT_QUOTES, 'UTF-8') ?></h2>
        <p><strong>Alcance visible:</strong> <?= htmlspecialchars($dashboard['proceso'], ENT_QUOTES, 'UTF-8') ?></p>
    </div>

    <div class="row">
        <?php if ($puedeVerCasillas || $puedeVerResultados) { ?>
        <div class="col-sm-6 col-lg-3"><div class="metric"><i class="fa fa-map-marker"></i><div class="value"><?= number_format($dashboard['casillas']) ?></div><div class="label">Casillas en el alcance</div></div></div>
        <div class="col-sm-6 col-lg-3"><div class="metric"><i class="fa fa-file-text"></i><div class="value"><?= number_format($dashboard['actas']) ?></div><div class="label">Actas capturadas</div></div></div>
        <div class="col-sm-6 col-lg-3"><div class="metric"><i class="fa fa-clock-o"></i><div class="value"><?= number_format($dashboard['pendientes']) ?></div><div class="label">Casillas pendientes</div></div></div>
        <?php } ?>
        <?php if ($puedeVerResultados) { ?>
        <div class="col-sm-6 col-lg-3"><div class="metric"><i class="fa fa-check-square-o"></i><div class="value"><?= number_format($dashboard['votos']) ?></div><div class="label">Votos contabilizados</div></div></div>
        <?php } elseif ($puedeVerCandidatos) { ?>
        <div class="col-sm-6 col-lg-3"><div class="metric"><i class="fa fa-users"></i><div class="value"><?= number_format($dashboard['candidatos']) ?></div><div class="label">Candidatos registrados</div></div></div>
        <?php } ?>
    </div>

    <?php if ($puedeVerCasillas || $puedeVerResultados) { ?>
    <div class="panel panel-default">
        <div class="panel-heading"><strong>Avance de captura de actas</strong><span class="pull-right"><?= $dashboard['avance'] ?>%</span></div>
        <div class="panel-body">
            <div class="progress"><div class="progress-bar" role="progressbar" style="width: <?= min(100, $dashboard['avance']) ?>%"></div></div>
            <small><?= number_format($dashboard['actas']) ?> de <?= number_format($dashboard['casillas']) ?> casillas cuentan con acta.</small>
        </div>
    </div>
    <?php } ?>

    <?php if ($puedeVerResultados && $dashboard['lideres'] !== []) { ?>
    <div class="panel panel-default">
        <div class="panel-heading"><strong>Resultados preliminares: primeros lugares</strong></div>
        <div class="table-responsive">
            <table class="table table-striped" style="margin-bottom:0">
                <thead><tr><th>Candidato</th><th class="text-right">Votos</th></tr></thead>
                <tbody>
                <?php foreach ($dashboard['lideres'] as $lider) { ?>
                    <tr><td><?= htmlspecialchars($lider->nombre, ENT_QUOTES, 'UTF-8') ?></td><td class="leader-votes"><?= number_format((int) $lider->votos) ?></td></tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php } ?>
</div>
