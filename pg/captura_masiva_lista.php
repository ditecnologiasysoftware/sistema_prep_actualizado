<div class="col-md-8">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">Capturar Lista Nominal</h4>
            <p></p>
        </div>
        <div class="panel-body">
            <div id="contFormulario"></div>
        </div><!-- body -->
    </div><!-- panel default -->
    <div class="panel-footer" id="mostrarSave" style="display:none;">
        <button class="btn btn-primary mr5"><?php if (!empty($_POST['id'])) echo "Editar";
                                            else echo "Guardar"; ?></button>
        <?php
        $redi = "location='captura_masiva'";
        if (!empty($_POST['id'])) echo '<button class="btn btn-danger mr5" onclick="' . $redi . '">Cancelar</button>';
        ?>
    </div><!-- panel-footer -->
</div><!-- col 8 -->

