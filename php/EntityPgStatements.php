<?php

final class EntityPgStatements
{
    private const STATEMENTS = [
        'casilla_faltante.municipal_count_suffix' => 'SELECT COUNT(c.id_casilla) FROM tblc_casilla as c WHERE c.id_municipio = ',
        'casilla_faltante.municipal_list_suffix' => 'SELECT c.* FROM tblc_casilla as c WHERE c.id_municipio = ',
        'casilla_faltante.municipal_missing_suffix' => ' AND (SELECT COUNT(DISTINCT r.id_casilla) FROM tbl_resultado as r INNER JOIN tblc_candidato AS can ON r.id_candidato = can.id_candidato WHERE r.id_casilla = c.id_casilla AND can.id_proceso_electoral = ',
        'bitacora.10.1' => "SELECT nombre FROM tblc_usuario WHERE id_usuario = ",
        'bitacora.12.2' => "SELECT date_format(fecha_acceso, '%H:%i') as hora, date_format(fecha_acceso, '%d-%m-%Y') as fecha FROM tbl_sesion WHERE id_usuario = ",
        'bitacora.141.4' => "SELECT ses.so, ses.navegador, ses.ip, log.descripcion, log.fecha, date_format(log.fecha, '%H:%i') as hora, date_format(log.fecha, '%Y-%m-%d') as fecha2 FROM tbl_log AS log INNER JOIN tbl_sesion AS ses ON log.id_sesion = ses.id_sesion WHERE ses.id_usuario = ",
        'bitacora.142.5' => "SELECT COUNT(log.id_log) FROM tbl_log AS log INNER JOIN tbl_sesion AS ses ON log.id_sesion = ses.id_sesion WHERE ses.id_usuario = ",
        'bitacora.75.3' => "SELECT DISTINCT YEAR(fecha_acceso) as id, YEAR(fecha_acceso) as valor FROM tbl_sesion WHERE id_usuario=",
        'bitacora_representante.41.1' => "SELECT tbl_representante_movil.estatus as estatus_disp, tbl_representante_movil.*, tblc_representante.* FROM tblc_representante INNER JOIN tbl_representante_movil WHERE tbl_representante_movil.id_representante = tblc_representante.id_representante  and tbl_representante_movil.id_representante =",
        'bitacora_representante.43.2' => "SELECT COUNT(tblc_representante.id_representante) FROM tblc_representante INNER JOIN tbl_representante_movil WHERE tbl_representante_movil.id_representante = tblc_representante.id_representante and tbl_representante_movil.id_representante =",
        'bitacora_representante.47.3' => "SELECT nombre FROM tblc_representante WHERE id_representante =",
        'candidato.51.1' => "SELECT id_proceso_electoral as id, CONCAT('Fecha: ', fecha) as valor FROM tblc_proceso_electoral WHERE estatus = 1 ORDER BY fecha DESC",
        'candidato_lista.34.1' => "SELECT c.* FROM tblc_candidato AS c 
INNER JOIN tblc_proceso_electoral AS pe ON c.id_proceso_electoral = pe.id_proceso_electoral 
WHERE c.fecha_eliminado IS NULL",
        'candidato_lista.38.2' => "SELECT COUNT(c.id_candidato) FROM tblc_candidato AS c 
INNER JOIN tblc_proceso_electoral AS pe ON c.id_proceso_electoral = pe.id_proceso_electoral 
WHERE c.fecha_eliminado IS NULL",
        'candidato_lista.69.3' => "SELECT t.nombre FROM tblc_proceso_electoral AS p INNER JOIN tblc_tipo_eleccion AS t ON p.id_tipo_eleccion = t.id_tipo_eleccion WHERE id_proceso_electoral =",
        'candidato_lista.71.4' => "SELECT p.nombre FROM tblc_candidato_partido AS cp JOIN tblc_partido_politico AS p ON cp.id_partido_politico = p.id_partido_politico WHERE cp.id_candidato =",
        'candidato_registro.39.1' => "SELECT id_partido_politico FROM tblc_candidato_partido WHERE id_candidato = ",
        'candidato_resultados.15.2' => "SELECT nombre FROM tblc_candidato WHERE id_candidato =",
        'candidato_resultados.54.3' => "SELECT p.nombre FROM tblc_candidato_partido AS cp JOIN tblc_partido_politico AS p ON cp.id_partido_politico = p.id_partido_politico WHERE cp.id_candidato =",
        'candidato_resultados.7.1' => "SELECT c.nombre, r.id_candidato, c.id_proceso_electoral, pe.descripcion, pe.fecha, SUM(r.resultado) as suma 
    FROM tbl_resultado AS r 
    INNER JOIN tblc_candidato AS c ON r.id_candidato = c.id_candidato 
    INNER JOIN tblc_proceso_electoral AS pe ON pe.id_proceso_electoral = c.id_proceso_electoral 
    WHERE c.id_proceso_electoral = ",
        'captura_masiva_registro.26.2' => "SELECT id_proceso_electoral as id, CONCAT(descripcion,' - ', fecha) as valor FROM tblc_proceso_electoral WHERE estatus = 1",
        'captura_masiva_registro.27.3' => "SELECT id_proceso_electoral as id, CONCAT(descripcion,' - ', fecha) as valor FROM tblc_proceso_electoral WHERE estatus = 1",
        'captura_masiva_registro.41.4' => "SELECT id_estado as id, nombre as valor FROM tblc_estado ORDER BY nombre",
        'captura_masiva_registro.42.5' => "SELECT id_estado as id, nombre as valor FROM tblc_estado ORDER BY nombre",
        'captura_masiva_registro.52.6' => "SELECT id_municipio as id, nombre as valor FROM tblc_municipio WHERE id_estado = ",
        'captura_masiva_registro.6.1' => "SELECT pe.id_estado, pe.id_municipio, ln.* FROM tbl_lista_nominal as ln 
    JOIN tblc_proceso_electoral as pe ON(ln.id_proceso_electoral = pe.id_proceso_electoral)
    WHERE ln.id_lista_nominal = ",
        'captura_masiva_registro.63.7' => "SELECT id_municipio as id, nombre as valor FROM tblc_municipio WHERE id_estado = ",
        'captura_masiva_registro.64.8' => "SELECT id_municipio as id, nombre as valor FROM tblc_municipio WHERE id_estado = ",
        'captura_masiva_registro.99.9' => "SELECT c.id_casilla as id, c.numero as numero_casilla, c.num_contigua as contigua_num_casilla, c.tipo as tipo_casilla, c.seccion as seccion_casilla FROM tblc_casilla as c JOIN tblc_municipio as m ON(c.id_municipio = m.id_municipio) WHERE c.id_casilla != 0",
        'casilla_electoral_listado.37.1' => "SELECT c.* FROM tblc_casilla as c JOIN tblc_municipio as m ON(c.id_municipio = m.id_municipio) WHERE c.fecha_eliminado IS NULL",
        'casilla_electoral_listado.39.2' => "SELECT COUNT(c.id_casilla) FROM tblc_casilla as c JOIN tblc_municipio as m ON(c.id_municipio = m.id_municipio) WHERE c.fecha_eliminado IS NULL",
        'casilla_electoral_listado.78.3' => "SELECT nombre FROM tblc_municipio WHERE id_municipio =",
        'casilla_electoral_registro.15.2' => "SELECT id_seccion FROM tblc_seccion WHERE fecha_eliminado IS NULL AND id_municipio = ? AND nombre = ? LIMIT 1",
        'casilla_electoral_registro.7.1' => "SELECT (SELECT id_estado FROM tblc_municipio WHERE id_municipio = dis.id_municipio) as id_estado, dis.* FROM tblc_casilla as dis WHERE dis.id_casilla = ?",
        'casilla_faltante.40.1' => "SELECT * FROM tblc_proceso_electoral WHERE id_proceso_electoral = ",
        'casilla_faltante.41.2' => "SELECT * FROM tblc_tipo_eleccion WHERE id_tipo_eleccion = ",
        'casilla_faltante.45.3' => "SELECT COUNT(id_casilla) FROM tblc_casilla",
        'casilla_faltante.49.4' => "SELECT COUNT(c.id_casilla) FROM tblc_casilla AS c 
                  INNER JOIN tblc_municipio AS m On c.id_municipio = m.id_municipio WHERE m.id_estado = ",
        'casilla_faltante.54.5' => "SELECT COUNT(c.id_casilla) FROM tblc_casilla as c
                  WHERE c.id_municipio = ",
        'casilla_faltante.57.6' => "SELECT c.* FROM tblc_casilla as c
                  WHERE c.id_municipio = ",
        'casilla_resultados.7.1' => "SELECT nombre FROM tblc_candidato WHERE id_candidato =",
        'casilla_resultados_lista.19.1' => "SELECT *, sum(resultado) as suma FROM vw_resultado_elecciones WHERE idp_electoral_c = ",
        'casilla_resultados_lista.20.2' => "SELECT id_casilla FROM vw_resultado_elecciones WHERE idp_electoral_c = ",
        'casilla_resultados_lista.41.3' => "SELECT SUM(resultado) as sumaresultado FROM tbl_resultado WHERE id_candidato = ",
        'casilla_resultados_lista.42.4' => "SELECT SUM(votos_nulos) as sumaresultado FROM tbl_acta WHERE id_proceso_electoral = ",
        'casilla_resultados_lista.43.5' => "SELECT SUM(no_registrados) as sumaresultado FROM tbl_acta WHERE id_proceso_electoral = ",
        'casilla_resultados_lista.44.6' => "SELECT SUM(total_votos) as total_votos FROM tbl_acta WHERE id_proceso_electoral = ",
        'casilla_resultados_lista.70.7' => "SELECT archivo FROM tbl_acta WHERE id_casilla =",
        'categorias_listado.16.1' => "SELECT * FROM tblc_categoria",
        'categorias_listado.17.2' => "SELECT COUNT(id_categoria) FROM tblc_categoria",
        'categorias_registro.5.1' => "SELECT * FROM tblc_categoria WHERE id_categoria = ",
        'configurar_registro.8.1' => "SELECT * FROM tbl_configuracion WHERE id_configuracion = 1",
        'distrito_lista.22.1' => "SELECT d.*, e.nombre as estado FROM tblc_distrito as d 
JOIN tblc_estado as e ON(d.id_estado = e.id_estado) WHERE d.fecha_eliminado IS NULL",
        'distrito_lista.25.2' => "SELECT COUNT(d.id_distrito) FROM tblc_distrito as d WHERE d.fecha_eliminado IS NULL",
        'distrito_lista.57.3' => "SELECT COUNT(id_seccion) FROM tblc_seccion WHERE id_distrito = ",
        'editar_resultado.110.3' => "SELECT * FROM tbl_resultado WHERE id_resultado = ",
        'editar_resultado.115.4' => "SELECT nombre FROM tblc_representante WHERE id_representante =",
        'editar_resultado.80.1' => "SELECT * FROM tbl_resultado WHERE id_resultado = ",
        'editar_resultado.81.2' => "SELECT * FROM vw_resultado_elecciones WHERE idrepresentante_r =",
        'estados_listado.16.1' => "SELECT * FROM tblc_estado WHERE fecha_eliminado IS NULL",
        'estados_listado.17.2' => "SELECT COUNT(id_estado) FROM tblc_estado WHERE fecha_eliminado IS NULL",
        'estados_registro.6.1' => "SELECT * FROM tblc_estado WHERE id_estado = ",
        'estatus_casilla_lista.22.1' => "SELECT ec.*, c.nombre as casilla, c.seccion, m.nombre as muni  FROM tbl_estatus_casilla as ec 
JOIN tblc_casilla as c ON(c.id_casilla = ec.id_casilla) 
JOIN tblc_municipio as m ON(c.id_municipio = m.id_municipio) 
JOIN tblc_estado as e ON(e.id_estado = m.id_estado) 
WHERE ec.id_estatus_casilla != 0",
        'estatus_casilla_lista.28.2' => "SELECT COUNT(ec.id_estatus_casilla) FROM tbl_estatus_casilla as ec 
JOIN tblc_casilla as c ON(c.id_casilla = ec.id_casilla) 
JOIN tblc_municipio as m ON(c.id_municipio = m.id_municipio) 
JOIN tblc_estado as e ON(e.id_estado = m.id_estado) 
WHERE ec.id_estatus_casilla != 0",
        'estatus_casilla_lista.68.3' => "SELECT c.* FROM tblc_casilla as c JOIN tblc_municipio as m ON(c.id_municipio = m.id_municipio) WHERE c.id_casilla != 0",
        'estatus_casilla_registro.24.2' => "SELECT id_proceso_electoral as id, CONCAT(descripcion,' - ', fecha) as valor FROM tblc_proceso_electoral WHERE estatus = 1",
        'estatus_casilla_registro.26.3' => "SELECT id_proceso_electoral as id, CONCAT(descripcion,' - ', fecha) as valor FROM tblc_proceso_electoral WHERE estatus = 1",
        'estatus_casilla_registro.53.4' => "SELECT c.* FROM tblc_casilla as c JOIN tblc_municipio as m ON(c.id_municipio = m.id_municipio) WHERE c.id_casilla != 0",
        'estatus_casilla_registro.55.5' => "SELECT c.* FROM tblc_casilla as c JOIN tblc_municipio as m ON(c.id_municipio = m.id_municipio) WHERE c.id_casilla != 0",
        'estatus_casilla_registro.6.1' => "SELECT  * FROM tbl_estatus_casilla WHERE id_estatus_casilla = ",
        'etiquetas_listado.17.1' => "SELECT e.*, c.nombre as categoria FROM tblc_etiqueta AS e 
                                                            INNER JOIN tblc_categoria AS c ON e.id_categoria = c.id_categoria",
        'etiquetas_listado.19.2' => "SELECT COUNT(id_etiqueta) FROM tblc_etiqueta AS e",
        'etiquetas_registro.25.2' => "SELECT id_categoria as id, nombre as valor from tblc_categoria",
        'etiquetas_registro.26.3' => "SELECT id_categoria as id, nombre as valor from tblc_categoria",
        'etiquetas_registro.6.1' => "SELECT * FROM tblc_etiqueta WHERE id_etiqueta = ",
        'grafica_eleccion.107.5' => "SELECT id_municipio as id, nombre as valor FROM tblc_municipio WHERE id_estado = ",
        'grafica_eleccion.164.6' => "SELECT * FROM tblc_proceso_electoral",
        'grafica_eleccion.165.7' => "SELECT COUNT(id_proceso_electoral) FROM tblc_proceso_electoral",
        'grafica_eleccion.172.8' => "SELECT * FROM tblc_tipo_eleccion WHERE id_tipo_eleccion = ",
        'grafica_eleccion.173.9' => "SELECT c.id_casilla FROM tblc_casilla AS c 
        INNER JOIN tblc_municipio as m ON c.id_municipio = c.id_municipio 
        WHERE c.id_casilla != 0",
        'grafica_eleccion.178.10' => "SELECT * FROM vw_resultado_elecciones WHERE idp_electoral_c = ",
        'grafica_eleccion.246.11' => "SELECT SUM(votos_nulos) as nulos, SUM(no_registrados) as nr FROM tbl_acta WHERE id_proceso_electoral = ",
        'grafica_eleccion.247.12' => "SELECT nombre_c, SUM(resultado) as votos FROM vw_resultado_elecciones WHERE idp_electoral_c = ",
        'grafica_eleccion.276.13' => "SELECT SUM(votos_nulos) as nulos, SUM(no_registrados) as nr FROM tbl_acta WHERE id_proceso_electoral = ",
        'grafica_eleccion.277.14' => "SELECT nombre_pa, color_pa, SUM(resultado) as votos FROM vw_resultado_elecciones WHERE idp_electoral_c = ",
        'grafica_eleccion.74.1' => "SELECT id_proceso_electoral as id, CONCAT(descripcion,' - ', fecha) as valor FROM tblc_proceso_electoral WHERE estatus = 1",
        'grafica_eleccion.86.2' => "SELECT id_estado as id, nombre as valor FROM tblc_estado ORDER BY nombre",
        'grafica_eleccion.87.3' => "SELECT id_estado as id, nombre as valor FROM tblc_estado ORDER BY nombre",
        'grafica_eleccion.96.4' => "SELECT id_municipio as id, nombre as valor FROM tblc_municipio WHERE id_estado = ",
        'graficas.124.1' => "SELECT (SELECT COUNT(re.id_reporte) FROM tbl_reporte_etiqueta AS re INNER JOIN tbl_reporte AS r ON re.id_reporte = r.id_reporte WHERE re.id_etiqueta = e.id_etiqueta",
        'graficas.125.2' => "SELECT e.etiqueta, e.id_etiqueta, (SELECT COUNT(re.id_reporte) FROM tbl_reporte_etiqueta AS re INNER JOIN tbl_reporte AS r ON re.id_reporte = r.id_reporte WHERE e.id_etiqueta = re.id_etiqueta",
        'graficas.183.3' => "SELECT COUNT(id_reporte) FROM tbl_reporte WHERE tipo_registro = 1",
        'graficas.184.4' => "SELECT COUNT(id_reporte) FROM tbl_reporte WHERE tipo_registro = 2",
        'graficas.185.5' => "SELECT COUNT(id_reporte) FROM tbl_reporte WHERE tipo_registro = 3",
        'graficas.217.6' => "SELECT c.nombre, (SELECT COUNT(re.id_reporte) FROM tbl_reporte_etiqueta AS re INNER JOIN tbl_reporte AS r ON re.id_reporte = r.id_reporte INNER JOIN tblc_etiqueta AS e ON e.id_etiqueta = re.id_etiqueta WHERE e.id_categoria = c.id_categoria",
        'graficas_resultado_modal.161.6' => "SELECT SUM(votos_nulos) as nulos, SUM(no_registrados) as nr FROM tbl_acta WHERE id_proceso_electoral = ",
        'graficas_resultado_modal.162.7' => "SELECT nombre_c, SUM(resultado) as votos FROM vw_resultado_elecciones WHERE idp_electoral_c = ",
        'graficas_resultado_modal.193.8' => "SELECT SUM(votos_nulos) as nulos, SUM(no_registrados) as nr FROM tbl_acta WHERE id_proceso_electoral = ",
        'graficas_resultado_modal.194.9' => "SELECT nombre_pa, color_pa, SUM(resultado) as votos FROM vw_resultado_elecciones WHERE idp_electoral_c = ",
        'graficas_resultado_modal.79.1' => "SELECT * FROM tblc_proceso_electoral",
        'graficas_resultado_modal.80.2' => "SELECT COUNT(id_proceso_electoral) FROM tblc_proceso_electoral",
        'graficas_resultado_modal.87.3' => "SELECT * FROM tblc_tipo_eleccion WHERE id_tipo_eleccion = ",
        'graficas_resultado_modal.88.4' => "SELECT c.id_casilla FROM tblc_casilla AS c 
                                INNER JOIN tblc_municipio as m ON c.id_municipio = c.id_municipio 
                                WHERE c.id_casilla != 0",
        'graficas_resultado_modal.93.5' => "SELECT * FROM vw_resultado_elecciones WHERE idp_electoral_c = ",
        'importar.40.1' => "SELECT COUNT(r.id_resultado) FROM tbl_resultado as r JOIN tblc_candidato as c ON(r.id_candidato = c.id_candidato) WHERE c.id_proceso_electoral =",
        'importar.43.2' => "SELECT id_candidato FROM tblc_candidato WHERE principal = 1 and id_candidato =",
        'importar.45.3' => "INSERT INTO tbl_resultado(id_candidato, id_casilla, resultado, id_representante, fecha_registro, id_usuario) VALUES(",
        'importar.52.4' => 'INSERT INTO tbl_log(id_sesion,fecha,descripcion,script) VALUES("',
        'incidencias_registro.114.7' => "SELECT id_etiqueta as id, etiqueta as valor FROM tblc_etiqueta ORDER BY etiqueta",
        'incidencias_registro.117.8' => "SELECT id_etiqueta FROM tbl_reporte_etiqueta WHERE id_reporte = ",
        'incidencias_registro.30.1' => "SELECT r.*, m.id_estado FROM tbl_reporte as r INNER JOIN tblc_municipio as m ON r.id_municipio = m.id_municipio WHERE r.id_reporte = ",
        'incidencias_registro.48.2' => "SELECT id_estado as id, nombre as valor FROM tblc_estado ORDER BY nombre",
        'incidencias_registro.49.3' => "SELECT id_estado as id, nombre as valor FROM tblc_estado ORDER BY nombre",
        'incidencias_registro.68.4' => "SELECT id_municipio as id, nombre as valor FROM tblc_municipio WHERE id_estado = ",
        'incidencias_registro.85.5' => "SELECT c.* FROM tblc_casilla as c JOIN tblc_municipio as m ON(c.id_municipio = m.id_municipio) WHERE c.id_casilla != 0",
        'incidencias_registro.87.6' => "SELECT c.* FROM tblc_casilla as c JOIN tblc_municipio as m ON(c.id_municipio = m.id_municipio) WHERE c.id_casilla != 0",
        'inicio.44.1' => 'SELECT COUNT(r.id_reporte) FROM tbl_reporte AS r INNER JOIN tblc_municipio AS m ON r.id_municipio = m.id_municipio INNER JOIN tblc_estado AS e ON m.id_estado = e.id_estado WHERE r.tipo_reporte = 1',
        'inicio.66.2' => 'SELECT COUNT(r.id_reporte) FROM tbl_reporte AS r INNER JOIN tblc_municipio AS m ON r.id_municipio = m.id_municipio INNER JOIN tblc_estado AS e ON m.id_estado = e.id_estado WHERE r.tipo_reporte = 2',
        'lista_bingo.19.1' => "SELECT * FROM tbl_acta WHERE id_proceso_electoral = ",
        'lista_bingo.22.2' => "SELECT * FROM tbl_bingo ORDER BY numero ASC",
        'lista_candidatos.30.3' => "SELECT resultado FROM tbl_resultado WHERE id_casilla = ",
        'lista_candidatos.7.1' => "SELECT * FROM tbl_acta WHERE id_proceso_electoral = ",
        'lista_candidatos.9.2' => "SELECT c.*, p.id_partido_politico, p.nombre as partido, p.icono, p.colo 
                               FROM tblc_candidato_partido AS cp 
                               INNER JOIN tblc_candidato AS c ON c.id_candidato = cp.id_candidato 
                               INNER JOIN tblc_partido_politico AS p ON p.id_partido_politico = cp.id_partido_politico 
                               WHERE c.id_proceso_electoral = ",
        'lista_candidatos2.25.1' => "SELECT * FROM tbl_acta WHERE id_proceso_electoral = ",
        'lista_candidatos2.27.2' => "SELECT c.*, p.id_partido_politico, p.nombre as partido, p.icono, p.colo 
                    FROM tblc_candidato_partido AS cp 
                    INNER JOIN tblc_candidato AS c ON c.id_candidato = cp.id_candidato 
                    INNER JOIN tblc_partido_politico AS p ON p.id_partido_politico = cp.id_partido_politico 
                    WHERE c.id_proceso_electoral = ",
        'lista_candidatos2.38.3' => "SELECT resultado FROM tbl_resultado WHERE id_casilla = ",
        'mapa.12.1' => "SELECT latitud, longitud FROM tbl_reporte WHERE id_reporte = ",
        'mapa_eleccion.106.11' => "SELECT nombre FROM tblc_municipio WHERE id_municipio =",
        'mapa_eleccion.128.12' => "SELECT vw_resultado_elecciones.* FROM vw_resultado_elecciones WHERE id_casilla = ",
        'mapa_eleccion.18.1' => "SELECT CONCAT(latitud,',',longitud) as coordenada FROM tblc_estado WHERE id_estado =",
        'mapa_eleccion.198.13' => "SELECT id_proceso_electoral as id, CONCAT(descripcion,' - ', fecha) as valor FROM tblc_proceso_electoral WHERE estatus = 1 ORDER BY fecha DESC",
        'mapa_eleccion.206.14' => "SELECT id_tipo_eleccion as id, nombre as valor FROM tblc_tipo_eleccion ORDER BY nombre DESC",
        'mapa_eleccion.218.15' => "SELECT id_estado as id, nombre as valor FROM tblc_estado ORDER BY nombre",
        'mapa_eleccion.219.16' => "SELECT id_estado as id, nombre as valor FROM tblc_estado ORDER BY nombre",
        'mapa_eleccion.228.17' => "SELECT id_municipio as id, nombre as valor FROM tblc_municipio WHERE id_estado = ",
        'mapa_eleccion.239.18' => "SELECT id_municipio as id, nombre as valor FROM tblc_municipio WHERE id_estado = ",
        'mapa_eleccion.26.2' => "SELECT CONCAT(latitud,',',longitud) as coordenada FROM tblc_municipio WHERE id_municipio =",
        'mapa_eleccion.261.19' => "SELECT nombre FROM tblc_municipio WHERE id_municipio=",
        'mapa_eleccion.33.3' => "SELECT CONCAT(latitud,',',longitud) as coordenada FROM tblc_estado WHERE id_estado =",
        'mapa_eleccion.42.4' => "SELECT CONCAT(latitud,',',longitud) as coordenada FROM tblc_municipio WHERE id_municipio =",
        'mapa_eleccion.54.5' => "SELECT MAX(id_proceso_electoral) FROM tblc_proceso_electoral WHERE estatus = 1 LIMIT 1",
        'mapa_eleccion.62.6' => "SELECT * FROM vw_resultado_elecciones WHERE idp_electoral_c = ",
        'mapa_eleccion.67.7' => "SELECT * FROM tblc_estado WHERE id_estado =",
        'mapa_eleccion.81.8' => "SELECT * FROM vw_resultado_elecciones WHERE idp_electoral_c = ",
        'mapa_eleccion.86.9' => "SELECT * FROM tblc_municipio WHERE id_municipio =",
        'mapa_eleccion.99.10' => "SELECT * FROM vw_resultado_elecciones WHERE idp_electoral_c = ",
        'mapa_resultados.105.3' => "SELECT vw_resultado_elecciones.* FROM vw_resultado_elecciones WHERE id_casilla = ",
        'mapa_resultados.73.1' => "SELECT * FROM vw_resultado_elecciones WHERE idp_electoral_c = ",
        'mapa_resultados.87.2' => "SELECT nombre FROM tblc_municipio WHERE id_municipio =",
        'mapa_seccion.104.9' => "SELECT * FROM tblc_municipio WHERE id_municipio =",
        'mapa_seccion.117.10' => "SELECT * FROM vw_resultado_elecciones WHERE idp_electoral_c = ",
        'mapa_seccion.124.11' => "SELECT nombre FROM tblc_municipio WHERE id_municipio =",
        'mapa_seccion.127.12' => "SELECT d.nombre FROM tblc_distrito as d JOIN tblc_seccion as s ON(d.id_distrito = s.id_distrito) WHERE s.nombre =",
        'mapa_seccion.150.13' => "SELECT vw_resultado_elecciones.*, SUM(resultado) AS resultado_total FROM vw_resultado_elecciones WHERE seccion = ",
        'mapa_seccion.230.14' => "SELECT id_proceso_electoral as id, CONCAT(descripcion,' - ', fecha) as valor FROM tblc_proceso_electoral WHERE estatus = 1 ORDER BY fecha DESC",
        'mapa_seccion.238.15' => "SELECT id_tipo_eleccion as id, nombre as valor FROM tblc_tipo_eleccion ORDER BY nombre DESC",
        'mapa_seccion.250.16' => "SELECT id_estado as id, nombre as valor FROM tblc_estado ORDER BY nombre",
        'mapa_seccion.251.17' => "SELECT id_estado as id, nombre as valor FROM tblc_estado ORDER BY nombre",
        'mapa_seccion.260.18' => "SELECT id_municipio as id, nombre as valor FROM tblc_municipio WHERE id_estado = ",
        'mapa_seccion.27.1' => "SELECT CONCAT(latitud,',',longitud) as coordenada FROM tblc_estado WHERE id_estado =",
        'mapa_seccion.270.19' => "SELECT id_municipio as id, nombre as valor FROM tblc_municipio WHERE id_estado = ",
        'mapa_seccion.295.20' => "SELECT nombre FROM tblc_municipio WHERE id_municipio=",
        'mapa_seccion.42.2' => "SELECT CONCAT(latitud,',',longitud) as coordenada FROM tblc_municipio WHERE id_municipio =",
        'mapa_seccion.51.3' => "SELECT CONCAT(latitud,',',longitud) as coordenada FROM tblc_estado WHERE id_estado =",
        'mapa_seccion.60.4' => "SELECT CONCAT(latitud,',',longitud) as coordenada FROM tblc_municipio WHERE id_municipio =",
        'mapa_seccion.72.5' => "SELECT MAX(id_proceso_electoral) FROM tblc_proceso_electoral WHERE estatus = 1 LIMIT 1",
        'mapa_seccion.80.6' => "SELECT * FROM vw_resultado_elecciones WHERE idp_electoral_c = ",
        'mapa_seccion.85.7' => "SELECT * FROM tblc_estado WHERE id_estado =",
        'mapa_seccion.99.8' => "SELECT * FROM vw_resultado_elecciones WHERE idp_electoral_c = ",
        'mapa_seccion_2.105.7' => "SELECT id_estado as id, nombre as valor FROM tblc_estado ORDER BY nombre",
        'mapa_seccion_2.106.8' => "SELECT id_estado as id, nombre as valor FROM tblc_estado ORDER BY nombre",
        'mapa_seccion_2.115.9' => "SELECT id_municipio as id, nombre as valor FROM tblc_municipio WHERE id_estado = ",
        'mapa_seccion_2.126.10' => "SELECT id_municipio as id, nombre as valor FROM tblc_municipio WHERE id_estado = ",
        'mapa_seccion_2.15.1' => "SELECT CONCAT(latitud,',',longitud) as coordenada FROM tblc_estado WHERE id_estado =",
        'mapa_seccion_2.20.2' => "SELECT CONCAT(latitud,',',longitud) as coordenada FROM tblc_municipio WHERE id_municipio =",
        'mapa_seccion_2.25.3' => "SELECT CONCAT(latitud,',',longitud) as coordenada FROM tblc_estado WHERE id_estado =",
        'mapa_seccion_2.33.4' => "SELECT CONCAT(latitud,',',longitud) as coordenada FROM tblc_municipio WHERE id_municipio =",
        'mapa_seccion_2.85.5' => "SELECT id_proceso_electoral as id, CONCAT('Fecha del Proceso Electoral: ', fecha) as valor FROM tblc_proceso_electoral WHERE estatus = 1 ORDER BY fecha DESC",
        'mapa_seccion_2.93.6' => "SELECT id_tipo_eleccion as id, nombre as valor FROM tblc_tipo_eleccion ORDER BY nombre DESC",
        'municipios_listado.16.1' => "SELECT * FROM tblc_municipio WHERE fecha_eliminado IS NULL",
        'municipios_listado.17.2' => "SELECT COUNT(id_municipio) FROM tblc_municipio WHERE fecha_eliminado IS NULL",
        'municipios_listado.54.3' => "select nombre from tblc_estado where id_estado=",
        'municipios_registro.23.2' => "select id_estado as id, nombre as valor from tblc_estado",
        'municipios_registro.24.3' => "select id_estado as id, nombre as valor from tblc_estado",
        'municipios_registro.6.1' => "SELECT * FROM tblc_municipio WHERE id_municipio = ",
        'nominal_lista.16.1' => "SELECT * FROM tbl_lista_nominal  WHERE fecha_eliminado IS NULL",
        'nominal_lista.18.2' => "SELECT COUNT(id_lista_nominal) FROM tbl_lista_nominal WHERE fecha_eliminado IS NULL",
        'nominal_lista.89.3' => "SELECT * FROM tblc_casilla as c WHERE c.id_casilla =",
        'nominal_registro.25.2' => "SELECT id_proceso_electoral as id, CONCAT(descripcion,' - ', fecha) as valor FROM tblc_proceso_electoral WHERE estatus = 1",
        'nominal_registro.26.3' => "SELECT id_proceso_electoral as id, CONCAT(descripcion,' - ', fecha) as valor FROM tblc_proceso_electoral WHERE estatus = 1",
        'nominal_registro.38.4' => "SELECT id_estado as id, nombre as valor FROM tblc_estado ORDER BY nombre",
        'nominal_registro.39.5' => "SELECT id_estado as id, nombre as valor FROM tblc_estado ORDER BY nombre",
        'nominal_registro.49.6' => "SELECT id_municipio as id, nombre as valor FROM tblc_municipio WHERE id_estado = ",
        'nominal_registro.5.1' => "SELECT pe.id_estado, pe.id_municipio, ln.* FROM tbl_lista_nominal as ln 
    JOIN tblc_proceso_electoral as pe ON(ln.id_proceso_electoral = pe.id_proceso_electoral)
    WHERE ln.id_lista_nominal = ",
        'nominal_registro.60.7' => "SELECT id_municipio as id, nombre as valor FROM tblc_municipio WHERE id_estado = ",
        'nominal_registro.61.8' => "SELECT id_municipio as id, nombre as valor FROM tblc_municipio WHERE id_estado = ",
        'nominal_registro.96.9' => "SELECT c.id_casilla as id, c.numero as numero_casilla, c.num_contigua as contigua_num_casilla, c.tipo as tipo_casilla, c.seccion as seccion_casilla FROM tblc_casilla as c JOIN tblc_municipio as m ON(c.id_municipio = m.id_municipio) WHERE c.id_casilla != 0",
        'ordenar_acta_lista.9.1' => "SELECT c.*, cp.ordenamiento, p.id_partido_politico, p.nombre as partido, p.icono, p.colo 
            FROM tblc_candidato_partido AS cp 
            INNER JOIN tblc_candidato AS c ON c.id_candidato = cp.id_candidato 
            INNER JOIN tblc_partido_politico AS p ON p.id_partido_politico = cp.id_partido_politico 
            WHERE c.id_proceso_electoral = ",
        'partido_politico_lista.3.1' => "SELECT * FROM tblc_partido_politico WHERE fecha_eliminado IS NULL ORDER BY nombre ASC",
        'partido_politico_registro.5.1' => "SELECT * FROM tblc_partido_politico WHERE id_partido_politico = ",
        'partido_resultados.7.1' => "SELECT c.nombre, r.id_candidato, r.id_partido_politico, c.id_proceso_electoral, pe.descripcion, pe.fecha, p.nombre as partido, p.icono, p.colo, SUM(r.resultado) as suma 
    FROM tbl_resultado AS r 
    INNER JOIN tblc_candidato AS c ON r.id_candidato = c.id_candidato 
    INNER JOIN tblc_partido_politico AS p ON p.id_partido_politico = r.id_partido_politico 
    INNER JOIN tblc_proceso_electoral AS pe ON pe.id_proceso_electoral = c.id_proceso_electoral 
    WHERE c.id_proceso_electoral = ",
        'permisos_lista.45.3' => "SELECT * FROM tblc_permiso WHERE id_padre = ",
        'permisos_lista.8.1' => "SELECT * FROM tblc_permiso WHERE id_padre = 0 AND fecha_eliminado IS NULL ORDER BY ordenamiento ASC LIMIT ",
        'permisos_lista.9.2' => "SELECT COUNT(id_permiso) FROM tblc_permiso WHERE id_padre = 0 AND fecha_eliminado IS NULL",
        'permisos_registro.25.2' => 'SELECT id_permiso AS id, nombre AS valor, icono AS nombre_icono FROM tblc_permiso WHERE id_padre = 0 AND fecha_eliminado IS NULL ORDER BY nombre ASC',
        'permisos_registro.42.3' => 'SELECT id_icono AS id, nombre AS valor FROM tblc_iconos',
        'permisos_registro.5.1' => "SELECT * FROM tblc_permiso WHERE id_permiso = ",
        'privilegios_modulo.24.3' => 'SELECT * FROM tblc_permiso WHERE id_padre = ',
        'privilegios_modulo.7.1' => "SELECT * FROM tbl_usuario_permiso WHERE id_usuario = ",
        'privilegios_modulo.8.2' => "SELECT * FROM tblc_permiso WHERE id_padre = 0 AND fecha_eliminado IS NULL ORDER BY ordenamiento ASC",
        'proceso_electoral_lista.14.1' => "SELECT * FROM tblc_proceso_electoral WHERE fecha_eliminado IS NULL",
        'proceso_electoral_lista.16.2' => "SELECT COUNT(id_proceso_electoral) FROM tblc_proceso_electoral WHERE fecha_eliminado IS NULL",
        'registro_bingo.42.1' => "SELECT id_proceso_electoral as id, CONCAT(descripcion,' - ', fecha) as valor FROM tblc_proceso_electoral WHERE estatus = 1",
        'registro_bingo.51.2' => "SELECT id_estado as id, nombre as valor FROM tblc_estado ORDER BY nombre",
        'registro_bingo.52.3' => "SELECT id_estado as id, nombre as valor FROM tblc_estado ORDER BY nombre",
        'registro_bingo.59.4' => "SELECT id_municipio as id, nombre as valor FROM tblc_municipio WHERE id_estado = ",
        'registro_bingo.68.5' => "SELECT id_municipio as id, nombre as valor FROM tblc_municipio WHERE id_estado = ",
        'registro_bingo.69.6' => "SELECT id_municipio as id, nombre as valor FROM tblc_municipio WHERE id_estado = ",
        'registro_bingo.78.7' => "SELECT c.* FROM tblc_casilla as c JOIN tblc_municipio as m ON(c.id_municipio = m.id_municipio) WHERE c.id_casilla != 0",
        'registro_resultados_completo.133.7' => "SELECT c.*, p.id_partido_politico, p.nombre as partido, p.icono, p.colo 
                                              FROM tblc_candidato_partido AS cp 
                                              INNER JOIN tblc_candidato AS c ON c.id_candidato = cp.id_candidato 
                                              INNER JOIN tblc_partido_politico AS p ON p.id_partido_politico = cp.id_partido_politico 
                                              WHERE c.id_proceso_electoral = ",
        'registro_resultados_completo.51.1' => "SELECT id_proceso_electoral as id, CONCAT(descripcion,' - ', fecha) as valor FROM tblc_proceso_electoral WHERE estatus = 1",
        'registro_resultados_completo.65.2' => "SELECT id_estado as id, nombre as valor FROM tblc_estado ORDER BY nombre",
        'registro_resultados_completo.66.3' => "SELECT id_estado as id, nombre as valor FROM tblc_estado ORDER BY nombre",
        'registro_resultados_completo.75.4' => "SELECT id_municipio as id, nombre as valor FROM tblc_municipio WHERE id_estado = ",
        'registro_resultados_completo.84.5' => "SELECT id_municipio as id, nombre as valor FROM tblc_municipio WHERE id_estado = ",
        'registro_resultados_completo.85.6' => "SELECT id_municipio as id, nombre as valor FROM tblc_municipio WHERE id_estado = ",
        'registro_resultados_completo_lista.21.1' => "SELECT c.*, p.id_partido_politico, p.nombre as partido, p.icono, p.colo 
                                              FROM tblc_candidato_partido AS cp 
                                              INNER JOIN tblc_candidato AS c ON c.id_candidato = cp.id_candidato 
                                              INNER JOIN tblc_partido_politico AS p ON p.id_partido_politico = cp.id_partido_politico 
                                              WHERE c.id_proceso_electoral = ",
        'reporte_apoyos.114.2' => "select id_usuario as id, nombre as valor from tblc_usuario",
        'reporte_apoyos.124.3' => "select id_usuario as id, nombre as valor from tblc_usuario WHERE autoriza_apoyo = 1",
        'reporte_apoyos.95.1' => "select id_estado as id, nombre as valor from tblc_estado",
        'reporte_cheques.104.2' => "select id_usuario as id, nombre as valor from tblc_usuario",
        'reporte_cheques.58.1' => "select id_banco as id, nombre as valor from tblc_banco ORDER BY nombre",
        'reporte_pagos.107.4' => "select id_forma_pago as id, nombre as valor from tblc_forma_pago ORDER BY nombre",
        'reporte_pagos.125.5' => "select id_usuario as id, nombre as valor from tblc_usuario",
        'reporte_pagos.135.6' => "select id_usuario as id, nombre as valor from tblc_usuario WHERE autoriza_apoyo = 1",
        'reporte_pagos.70.1' => "select id_proveedor as id, nombre as valor from tblc_proveedor ORDER BY nombre",
        'reporte_pagos.83.2' => "select id_partida as id, nombre as valor from tblc_partida ORDER BY nombre",
        'reporte_pagos.95.3' => "select id_concepto_pago as id, nombre as valor from tblc_concepto_pago ORDER BY nombre",
        'reportes.128.4' => "SELECT id_etiqueta as id, etiqueta as valor FROM tblc_etiqueta ORDER BY etiqueta",
        'reportes.129.5' => "SELECT id_etiqueta as id, etiqueta as valor FROM tblc_etiqueta ORDER BY etiqueta",
        'reportes.148.6' => "SELECT c.* FROM tblc_casilla as c JOIN tblc_municipio as m ON(c.id_municipio = m.id_municipio) WHERE c.id_casilla != 0 AND c.id_municipio = ",
        'reportes.53.1' => "SELECT id_estado as id, nombre as valor FROM tblc_estado ORDER BY nombre",
        'reportes.54.2' => "SELECT id_estado as id, nombre as valor FROM tblc_estado ORDER BY nombre",
        'reportes.73.3' => "SELECT id_municipio as id, nombre as valor FROM tblc_municipio WHERE id_estado = ",
        'reportes_listado.125.3' => "SELECT e.etiqueta FROM tbl_reporte_etiqueta AS er INNER JOIN tblc_etiqueta AS e ON er.id_etiqueta = e.id_etiqueta WHERE er.id_reporte = ",
        'reportes_listado.63.1' => "SELECT r.*, m.nombre as municipio, e.nombre as estado, date_format(r.fecha_registro, '%H:%i') as hora, date_format(r.fecha_registro, '%Y-%m-%d') as fecha2 
FROM tbl_reporte AS r 
INNER JOIN tblc_municipio AS m ON r.id_municipio = m.id_municipio 
INNER JOIN tblc_estado AS e ON m.id_estado = e.id_estado 
",
        'reportes_listado.70.2' => "SELECT COUNT(r.id_reporte) FROM tbl_reporte AS r 
INNER JOIN tblc_municipio AS m ON r.id_municipio = m.id_municipio 
INNER JOIN tblc_estado AS e ON m.id_estado = e.id_estado 
",
        'reportes_registro.109.2' => "SELECT id_etiqueta as id, etiqueta as valor FROM tblc_etiqueta ORDER BY etiqueta",
        'reportes_registro.112.3' => "SELECT id_etiqueta FROM tbl_reporte_etiqueta WHERE id_reporte = ",
        'reportes_registro.5.1' => "SELECT r.*, m.id_estado FROM tbl_reporte as r INNER JOIN tblc_municipio as m ON r.id_municipio = m.id_municipio WHERE r.id_reporte = ",
        'representante_lista.31.1' => "SELECT r.*, m.nombre as municipio, m.id_estado FROM tblc_representante as r
INNER JOIN tblc_municipio as m ON r.id_municipio = m.id_municipio
WHERE r.fecha_eliminado IS NULL",
        'representante_lista.35.2' => "SELECT COUNT(r.id_representante) FROM tblc_representante as r
INNER JOIN tblc_municipio as m ON r.id_municipio = m.id_municipio
WHERE r.fecha_eliminado IS NULL",
        'representante_lista.70.3' => "SELECT CONCAT(descripcion,' - ', fecha) FROM tblc_proceso_electoral WHERE id_proceso_electoral = ",
        'resultado_eleccion.46.1' => "SELECT id_proceso_electoral as id, CONCAT(descripcion,' - ', fecha) as valor FROM tblc_proceso_electoral WHERE estatus = 1",
        'resultado_eleccion.57.2' => "SELECT id_estado as id, nombre as valor FROM tblc_estado ORDER BY nombre",
        'resultado_eleccion.58.3' => "SELECT id_estado as id, nombre as valor FROM tblc_estado ORDER BY nombre",
        'resultado_eleccion.66.4' => "SELECT id_municipio as id, nombre as valor FROM tblc_municipio WHERE id_estado = ",
        'resultado_eleccion.76.5' => "SELECT id_municipio as id, nombre as valor FROM tblc_municipio WHERE id_estado = ",
        'resultado_eleccion_lista.100.4' => "SELECT e.nombre as nom, e.latitud as lat, e.longitud as lon FROM tblc_estado as e WHERE e.id_estado = ",
        'resultado_eleccion_lista.105.5' => "SELECT c.id_casilla FROM tblc_casilla AS c 
                                                        INNER JOIN tblc_municipio as m ON c.id_municipio = c.id_municipio 
                                                        WHERE c.id_casilla != 0",
        'resultado_eleccion_lista.110.6' => "SELECT * FROM vw_resultado_elecciones WHERE idp_electoral_c = ",
        'resultado_eleccion_lista.114.7' => "SELECT p.nombre FROM tblc_candidato_partido AS cp JOIN tblc_partido_politico AS p ON cp.id_partido_politico = p.id_partido_politico WHERE cp.id_candidato =",
        'resultado_eleccion_lista.120.8' => "SELECT SUM(resultado) as sumaresultado FROM tbl_resultado WHERE id_candidato = ",
        'resultado_eleccion_lista.122.9' => "SELECT SUM(resultado) as sumaresultado FROM vw_resultado_elecciones WHERE idp_electoral_c = ",
        'resultado_eleccion_lista.124.10' => "SELECT SUM(votos_nulos) as sumaresultado FROM tbl_acta WHERE id_proceso_electoral = ",
        'resultado_eleccion_lista.125.11' => "SELECT SUM(no_registrados) as sumaresultado FROM tbl_acta WHERE id_proceso_electoral = ",
        'resultado_eleccion_lista.50.1' => "SELECT c.*, pe.id_tipo_eleccion, pe.id_municipio, pe.id_estado, pe.fecha as f_proceso, pe.descripcion as desc_proceso, t.nombre as nom_tipoeleccion, t.tipo as tipo_eleccion 
FROM tblc_candidato AS c 
INNER JOIN tblc_proceso_electoral AS pe ON c.id_proceso_electoral = pe.id_proceso_electoral 
INNER JOIN tblc_tipo_eleccion as t ON pe.id_tipo_eleccion = t.id_tipo_eleccion 
WHERE c.principal = 1",
        'resultado_eleccion_lista.56.2' => "SELECT COUNT(c.id_candidato) FROM tblc_candidato AS c 
INNER JOIN tblc_proceso_electoral AS pe ON c.id_proceso_electoral = pe.id_proceso_electoral 
WHERE c.principal = 1",
        'resultado_eleccion_lista.96.3' => "SELECT CONCAT(e.nombre,', ',m.nombre) AS nom, m.latitud as lat, m.longitud as lon FROM tblc_estado as e JOIN tblc_municipio as m ON(e.id_estado = m.id_estado) WHERE m.id_municipio = ",
        'seccion_listado.21.1' => "SELECT s.*, e.nombre as estado, m.nombre as muni FROM tblc_seccion as s 
JOIN tblc_municipio as m ON(s.id_municipio = m.id_municipio) 
JOIN tblc_estado as e ON(e.id_estado = m.id_estado) 
WHERE s.fecha_eliminado IS NULL",
        'seccion_listado.26.2' => "SELECT COUNT(s.id_seccion) FROM tblc_seccion as s JOIN tblc_distrito as d ON(s.id_distrito = d.id_distrito) JOIN tblc_municipio as m ON(d.id_municipio = m.id_municipio) WHERE s.fecha_eliminado IS NULL",
        'tbl_resultados.34.1' => "SELECT * FROM vw_resultado_elecciones WHERE id_casilla = ",
        'tbl_resultados_eleccion.38.1' => "SELECT * FROM vw_resultado_elecciones WHERE id_casilla = ",
        'tbl_resultados_eleccion_seccion.38.1' => "SELECT vw_resultado_elecciones.*, SUM(resultado) AS resultado_total FROM vw_resultado_elecciones WHERE seccion = ",
        'tipo_eleccion_lista.3.1' => "SELECT * FROM tblc_tipo_eleccion WHERE fecha_eliminado IS NULL ORDER BY nombre ASC",
        'tipo_eleccion_registro.5.1' => "SELECT * FROM tblc_tipo_eleccion WHERE id_tipo_eleccion = ",
        'usuarios_lista.17.1' => "SELECT * FROM tblc_usuario WHERE id_usuario != 0",
        'usuarios_lista.18.2' => "SELECT COUNT(id_usuario) FROM tblc_usuario WHERE id_usuario != 0",
        'usuarios_registro.101.4' => "SELECT id_municipio as id, nombre as valor FROM tblc_municipio WHERE id_estado = ",
        'usuarios_registro.6.1' => "SELECT * FROM tblc_usuario WHERE id_usuario = ",
        'usuarios_registro.81.2' => "SELECT id_estado as id, nombre as valor FROM tblc_estado ORDER BY nombre",
        'usuarios_registro.83.3' => "SELECT id_estado as id, nombre as valor FROM tblc_estado ORDER BY nombre",
    ];

    public static function get(string $key): string
    {
        if (!array_key_exists($key, self::STATEMENTS)) {
            throw new InvalidArgumentException("No existe la consulta registrada '" . $key . "'.");
        }

        return self::STATEMENTS[$key];
    }
}
