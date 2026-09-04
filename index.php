<?php
require_once __DIR__ . '/vendor/autoload.php';
Dotenv\Dotenv::createImmutable(__DIR__)->safeLoad();
$siteKey = $_ENV['RECAPTCHA_SITE_KEY'] ?? '';
$lang = "es";
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>Inicio de Sesión</title>

        <link href="assets/css/style.default.css" rel="stylesheet">
        <link href="assets/css/prep-modern.css" rel="stylesheet">

        <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
        <script src="assets/js/html5shiv.js"></script>
        <script src="assets/js/respond.min.js"></script>
        <![endif]-->
    </head>

    <body class="signin prep-signin">
        
        
        <section>
            
            <div class="panel panel-signin">
                <div class="panel-body">
                    <div class="logo text-center">
                        <img src="assets/images/logo.png" width="50%">
                    </div>
                    <br />
                    <p class="prep-eyebrow text-center">Resultados electorales preliminares</p>
                    <h3 class="text-center mb5">SISTEMA PREP</h3>
                    <!--<p class="text-center">Inicio de Sesión</p>-->
                    <div class="mb30"></div>
                    <form class="form-horizontal" method="post" enctype="multipart/form-data" action="php/inicio_sesion.php" target="mandar_formulario">
                        <div class="input-group mb15">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                            <input type="text" class="form-control" placeholder="Usuario"  name="usuario" id="usuario">
                        </div><!-- input-group -->
                        <div class="input-group mb15">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                            <input type="password" class="form-control" placeholder="Contraseña" name="pass" id="pass">
                        </div><!-- input-group -->

                        <!-- <div class="input-group mb15">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                            <input type="text" class="form-control" placeholder="Código de Verificación" name="captcha" id="captcha">
                        </div> -->
                        <!-- <img src="captcha/securimage_show.php" class="img_captcha" /> -->
                        <?php if ($siteKey !== ''): ?>
                            <div class="g-recaptcha" data-sitekey="<?php echo htmlspecialchars($siteKey, ENT_QUOTES, 'UTF-8'); ?>"></div>
                        <?php endif; ?>

                        <div class="clearfix">
                            <div class="pull-left">
                                <div class="ckbox ckbox-primary mt10">
                                    <!--<input type="checkbox" id="rememberMe" value="1">
                                    <label for="rememberMe">Remember Me</label>-->
                                </div>
                            </div>
                            <div class="pull-right">
                                <button type="submit" class="btn btn-success">Entrar<i class="fa fa-angle-right ml5"></i></button>
                            </div>
                        </div>                      
                    </form>
					<iframe style="display: none;" name="mandar_formulario" id="mandar_formulario" width="100%" height="40px" frameborder="0" ></iframe>
                    
                </div><!-- panel-body -->
            </div><!-- panel -->
            
        </section>

        <script src="assets/js/jquery-1.11.1.min.js"></script>
        <script src="assets/js/jquery-migrate-1.2.1.min.js"></script>
        <script src="assets/js/bootstrap.min.js"></script>
        <script src="assets/js/modernizr.min.js"></script>
        <script src="assets/js/pace.min.js"></script>
        <script src="assets/js/retina.min.js"></script>
        <script src="assets/js/jquery.cookies.js"></script>

        <script src="assets/js/custom.js"></script>
		<script src="assets/js/funciones.js"></script>
        <?php if ($siteKey !== ''): ?>
            <script src="https://www.google.com/recaptcha/api.js?hl=<?php echo $lang; ?>" async defer></script>
        <?php endif; ?>

    </body>
</html>
