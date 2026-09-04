# Modelo de datos de Sistema PREP

El esquema `admin_prep` contiene 25 tablas y la vista `vw_resultado_elecciones`.

## Núcleo electoral

- `tblc_proceso_electoral`: elección configurada por fecha, tipo y territorio.
- `tblc_tipo_eleccion`: cargo o clase de elección (federal, estatal o municipal).
- `tblc_candidato`: candidaturas del proceso y su orden en el acta.
- `tblc_partido_politico`: catálogo de partidos, color, icono y orden.
- `tblc_candidato_partido`: relación muchos-a-muchos y orden de partidos por candidatura.
- `tbl_acta`: evidencia del escrutinio por casilla, votos nulos, no registrados y total.
- `tbl_resultado`: votos por candidato, partido y casilla.
- `vw_resultado_elecciones`: lectura consolidada usada por gráficas, mapas y resultados.

## Geografía electoral

`tblc_estado` → `tblc_municipio` → `tblc_distrito`/`tblc_seccion` → `tblc_casilla`.

## Operación y seguridad

- `tblc_usuario`, `tblc_permiso`, `tbl_usuario_permiso`, `tbl_sesion` y `tbl_log`.
- `tblc_representante` y `tbl_representante_movil` para captura desde casilla.
- `tbl_estatus_casilla` para incidencias y apertura/cierre.
- `tbl_reporte`, `tblc_categoria`, `tblc_etiqueta` y `tbl_reporte_etiqueta`.

Las bajas lógicas disponibles se identifican mediante `fecha_eliminado IS NULL`.
