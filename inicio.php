<?php
require "php/inicializandoDatos.php";
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>Sistema PREP | Panel electoral</title>

        <link href="assets/css/style.default.css" rel="stylesheet">
        <link href="assets/css/select2.css" rel="stylesheet" />
        <link href="assets/css/c3.css" rel="stylesheet" />
        <link href="assets/css/sweetalert2.min.css" rel="stylesheet" />
        <!-- Tema actual: debe cargarse al final para sustituir la apariencia heredada. -->
        <link href="assets/css/prep-modern.css?v=20260905-1" rel="stylesheet" />

        <?php $mapsKey = $_ENV['GOOGLE_MAPS_API_KEY'] ?? ''; ?>
        <?php if ($mapsKey !== ''): ?>
            <script src="https://maps.googleapis.com/maps/api/js?key=<?php echo rawurlencode($mapsKey); ?>" defer></script>
        <?php endif; ?>

        <script src="assets/js/jquery-1.11.1.min.js"></script>
        <script src="assets/js/jquery-migrate-1.2.1.min.js"></script>
        <script src="assets/js/bootstrap.min.js"></script>
        <script src="assets/js/modernizr.min.js"></script>
        <script src="assets/js/pace.min.js"></script>
        <script src="assets/js/jquery.cookies.js"></script>
        <script src="assets/js/jquery-ui-1.10.3.min.js"></script>
        <script src="assets/js/sweetalert2.all.min.js"></script>
        <script src="assets/js/prep-alerts.js"></script>

        <script src="assets/js/select2.min.js"></script>
        <script src="assets/js/funciones.js?v=20260905-2"></script>
        <script src="assets/js/ajax_funciones.js?v=20260905-2"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" crossorigin="anonymous" />
        <link href="https://fonts.googleapis.com/css2?family=Material+Icons" rel="stylesheet">
        <script src="assets/js/c3.js"></script>
        <script src="https://d3js.org/d3.v3.min.js" charset="utf-8"></script>
        <script src="assets/js/custom.js"></script>    
        <script src="assets/js/jquery.maskedinput.min.js"></script>
        <script type="text/javascript" src="https://www.google.com/jsapi"></script>

    </head>

    <body class="prep-admin">

        <?php include("pg/modal.php"); ?>
        
        <header>
            <div class="headerwrapper">
                <div class="header-left">
				
					<a href="inicio" class="prep-brand" aria-label="Ir al inicio">
                        <span class="prep-brand-mark">P</span>
                        <span><strong>PREP</strong><small>Panel electoral</small></span>
                    </a>
                </div><!-- header-left -->
                
                <div class="header-right">
                    <button type="button" class="prep-menu-toggle menu-collapse" aria-label="Ocultar menú de navegación" aria-controls="prep-navigation" aria-expanded="true" title="Ocultar o mostrar menú">
                        <span class="sr-only">Ocultar o mostrar menú</span>
                        <i class="fa fa-bars" aria-hidden="true"></i>
                    </button>
                    
                    <div class="pull-right">

                        <div class="btn-group btn-group-list btn-group-notification" id="notificaciones">
                            
                        </div><!-- btn-group -->
                        <div class="btn-group btn-group-option">
                            <button type="button" class="btn btn-default dropdown-toggle" >
                              <i class="fa fa-user-circle"></i> <?php echo htmlspecialchars($nombre, ENT_QUOTES, 'UTF-8'); ?>
                            </button>
                        </div><!-- btn-group -->

                        <div class="btn-group btn-group-option">
                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                              <i class="fa fa-caret-down"></i>
                            </button>

                            <ul class="dropdown-menu pull-right" role="menu">
                              <li class="divider"></li>
                              <li><a title="" href="php/cerrarses.php"><i class="glyphicon glyphicon-log-out"></i>Salir</a></li>
                            </ul>
                        </div><!-- btn-group -->
                        
                    </div><!-- pull-right -->
                    
                </div><!-- header-right -->
                
            </div><!-- headerwrapper -->
        </header>
        
        <section>
		
            <div class="mainwrapper">
                <div class="leftpanel" id="prep-navigation">
                    
				<?php include("menu.php"); ?>
                </div><!-- leftpanel -->
                <button type="button" class="prep-menu-overlay" aria-label="Cerrar menú"></button>
                
                <div class="mainpanel">
                    
					<?php
						if($consulta_privilegios != 0 || $modulo == "privilegios" || $modulo == "bitacora")
							include_once('pg/'.$modulo.'.php');
						else{
							echo '
	                    <div class="contentpanel" >
	                    	<center><h2>Acceso denegado</h2></center>
	                    </div>
							';
						}
					?>
                    
                </div><!-- mainpanel -->
            </div><!-- mainwrapper -->
		<script>
          
			jQuery("select, #select-multi").select2();
			
			jQuery("#select-basic, #select-multi").select2();
            jQuery('#select-search-hide').select2({
                minimumResultsForSearch: -1
            });
            
            function format(item) {
                return '<i class="fa ' + ((item.element[0].getAttribute('rel') === undefined)?"":item.element[0].getAttribute('rel') ) + ' mr10"></i>' + item.text;
            }
            
            // This will empty first option in select to enable placeholder
            //jQuery('select option:first-child').text('');
            
            jQuery("#icono").select2({
                formatResult: format,
                formatSelection: format,
                escapeMarkup: function(m) { return m; }
            });
			
			jQuery("#menu2").select2({
                formatResult: format,
                formatSelection: format,
                escapeMarkup: function(m) { return m; }
            });
			
			$.mask.definitions['H']='[01]';
			$.mask.definitions['h']='[0123456789]';
		    $.mask.definitions['N']='[012345]';
		    $.mask.definitions['n']='[0123456789]';
		    $("#hora_inicio").mask("Hh:Nn");
			$("#hora_final").mask("Hh:Nn");
            $('#fecha').datepicker({dateFormat: 'dd-m-yy', changeMonth: true, changeYear: true, yearRange: '-100:+0'});
            $('#fecha2').datepicker({dateFormat: 'dd-m-yy', changeMonth: true, changeYear: true, yearRange: '-100:+0'});
			
            
      
		</script>
        
    </body>
</html>
