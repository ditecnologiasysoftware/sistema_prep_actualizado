# Sistema PREP Actualizado

Modernización del sistema de resultados electorales preliminares. Conserva los módulos operativos de captura de actas, resultados, candidatos, partidos, procesos electorales, geografía electoral, representantes, reportes, mapas y gráficas.

## Cambios principales

- Configuración sensible mediante `.env` y plantilla `.env.example`.
- Acceso a datos centralizado en `php/Entity.php` con consultas preparadas.
- Vistas de `pg/` migradas de la instancia heredada `DB_mysql` a `Entity`.
- Nueva identidad visual adaptable para acceso, encabezado, navegación, formularios, paneles y tablas.
- Recursos públicos consolidados en `assets/css`, `assets/js`, `assets/images` y `assets/fonts`.
- Llaves de Google Maps y reCAPTCHA retiradas del código.
- Documentación del esquema y de instalación en `docs/`.

## Puesta en marcha

Consulte [docs/INSTALACION.md](docs/INSTALACION.md) y [docs/BASE_DATOS.md](docs/BASE_DATOS.md).
