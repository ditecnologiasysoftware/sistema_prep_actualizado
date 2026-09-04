<script>
    window.onload = function () {
        permisos_registro();
        permisos_lista();
    }
</script>

<div class="pageheader">
    <div class="media">
        <div class="pageicon pull-left">
            <i class="fa fa-home"></i>
        </div>
        <div class="media-body">
            <ul class="breadcrumb">
                <li><a href=""><i class="glyphicon glyphicon-home"></i></a></li>
                <li>Permisos</li>
            </ul>
            <h4>Permisos</h4>
        </div>
    </div>
</div>

<div class="contentpanel">
    <div class="row">
        <form id="form_busqueda"> <input type="hidden" name="pag" id="pag"></form>
        <div id="formulario_registro"></div>
        <div id="contenido"></div>
    </div>
</div>