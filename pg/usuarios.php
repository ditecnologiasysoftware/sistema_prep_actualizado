<script>
    window.onload = function () {
        usuarios_registro();
    }
</script>
<ul class="nav nav-tabs">
	<li <?php if (!isset($_GET['act']))
		echo 'class="active"'; ?>><a onclick="usuarios_registro()" data-toggle="tab"><strong>Formulario
				de
				Registro</strong></a></li>
	<li <?php if (isset($_GET['list']) or isset($_GET['act']))
		echo 'class="active"'; ?>><a onclick="usuarios_lista()"
			data-toggle="tab"><strong>Listado de Usuarios</strong></a></li>
</ul>
<div class="pageheader">
	<div class="media">
		<div class="pageicon pull-left">
			<i class="fa fa-database"></i>
		</div>
		<div class="media-body">
			<ul class="breadcrumb">
				<li><a href=""><i class="glyphicon glyphicon-home"></i></a></li>
				<li>Usuarios</li>
			</ul>
			<h4>Usuarios</h4>
		</div>
	</div><!-- media -->
</div><!-- pageheader -->
<div class="tab-content">
	<div id="contenido">
	</div>
</div>