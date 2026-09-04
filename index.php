<?php
require_once __DIR__ . '/vendor/autoload.php';
Dotenv\Dotenv::createImmutable(__DIR__)->safeLoad();
$siteKey = $_ENV['RECAPTCHA_SITE_KEY'] ?? '';
$lang = 'es';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Acceso al Sistema de Resultados Electorales Preliminares">
    <title>Inicio de Sesión | Sistema PREP</title>
    <link href="assets/css/style.default.css" rel="stylesheet">
    <link href="assets/css/prep-modern.css?v=20260904-login" rel="stylesheet">
</head>
<body class="signin prep-signin">
    <main class="prep-login-shell">
        <section class="prep-login-card" aria-labelledby="login-title">
            <div class="prep-login-brand">
                <div class="prep-login-brand-content">
                    <span class="prep-login-badge"><i class="fa fa-check-circle"></i> Plataforma electoral</span>
                    <h1>Resultados claros.<br>Decisiones informadas.</h1>
                    <p>Consulta y administra el avance de los resultados electorales preliminares desde una plataforma segura y confiable.</p>
                    <div class="prep-login-features" aria-label="Características del sistema">
                        <span><i class="fa fa-shield"></i> Acceso seguro</span>
                        <span><i class="fa fa-bar-chart-o"></i> Seguimiento en tiempo real</span>
                    </div>
                </div>
                <small class="prep-login-brand-footer">Sistema de Resultados Electorales Preliminares</small>
            </div>

            <div class="prep-login-form-panel">
                <div class="prep-login-heading">
                    <div class="logo">
                        <img src="assets/images/logo.png" alt="Logotipo del Sistema PREP">
                    </div>
                    <p class="prep-eyebrow">Resultados electorales preliminares</p>
                    <h2 id="login-title">Bienvenido</h2>
                    <p class="prep-login-subtitle">Ingresa tus credenciales para continuar.</p>
                </div>

                <form class="prep-login-form" method="post" enctype="multipart/form-data" action="php/inicio_sesion.php" target="mandar_formulario">
                    <div class="prep-field">
                        <label for="usuario">Usuario</label>
                        <div class="prep-input-wrap">
                            <i class="glyphicon glyphicon-user" aria-hidden="true"></i>
                            <input type="text" class="form-control" placeholder="Escribe tu usuario" name="usuario" id="usuario" autocomplete="username" required autofocus>
                        </div>
                    </div>
                    <div class="prep-field">
                        <label for="pass">Contraseña</label>
                        <div class="prep-input-wrap">
                            <i class="glyphicon glyphicon-lock" aria-hidden="true"></i>
                            <input type="password" class="form-control" placeholder="Escribe tu contraseña" name="pass" id="pass" autocomplete="current-password" required>
                            <button class="prep-password-toggle" type="button" aria-label="Mostrar contraseña" aria-controls="pass">
                                <i class="fa fa-eye" aria-hidden="true"></i>
                            </button>
                        </div>
                    </div>

                    <?php if ($siteKey !== ''): ?>
                        <div class="prep-recaptcha">
                            <div class="g-recaptcha" data-sitekey="<?php echo htmlspecialchars($siteKey, ENT_QUOTES, 'UTF-8'); ?>"></div>
                        </div>
                    <?php endif; ?>

                    <button type="submit" class="btn prep-login-submit">
                        <span>Iniciar sesión</span><i class="fa fa-arrow-right" aria-hidden="true"></i>
                    </button>
                </form>
                <p class="prep-login-help"><i class="fa fa-lock"></i> Tus credenciales se transmiten de forma segura.</p>
                <iframe class="prep-login-response" name="mandar_formulario" id="mandar_formulario" title="Respuesta de inicio de sesión"></iframe>
            </div>
        </section>
    </main>

    <script src="assets/js/jquery-1.11.1.min.js"></script>
    <script src="assets/js/jquery-migrate-1.2.1.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/modernizr.min.js"></script>
    <script src="assets/js/pace.min.js"></script>
    <script src="assets/js/jquery.cookies.js"></script>
    <script src="assets/js/custom.js"></script>
    <script src="assets/js/funciones.js"></script>
    <script>
        (function () {
            var toggle = document.querySelector('.prep-password-toggle');
            var password = document.getElementById('pass');
            if (!toggle || !password) return;
            toggle.addEventListener('click', function () {
                var show = password.type === 'password';
                password.type = show ? 'text' : 'password';
                toggle.setAttribute('aria-label', show ? 'Ocultar contraseña' : 'Mostrar contraseña');
                toggle.querySelector('i').className = show ? 'fa fa-eye-slash' : 'fa fa-eye';
                password.focus();
            });
        }());
    </script>
    <?php if ($siteKey !== ''): ?>
        <script src="https://www.google.com/recaptcha/api.js?hl=<?php echo $lang; ?>" async defer></script>
    <?php endif; ?>
</body>
</html>
