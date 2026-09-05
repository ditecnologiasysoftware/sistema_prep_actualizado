<?php
    class Querys {


        ///COMBOS

        function combocasillas($estado,$municipio=0){
            $sentencia = ($estado != '0') ? " AND m.id_estado = '".$estado."'" : '';
            $sentencia .= ($municipio != '0') ? " AND c.id_municipio = '".$municipio."'" : '';

            $strQuery = 'SELECT c.id_casilla as id, CONCAT("Sección ", c.seccion, " - ", c.nombre) as valor 
                FROM tblc_casilla as c 
                JOIN tblc_municipio as m ON(c.id_municipio = m.id_municipio) 
                WHERE c.fecha_eliminado IS NULL' . $sentencia . '  ORDER BY c.id_casilla ASC';

            return $strQuery;
        }

        function combosecciones($municipio=0){
            $sentencia = ($municipio != '0') ? " AND id_municipio = '".$municipio."'" : '';

            $strQuery = 'SELECT id_seccion as id, nombre as valor FROM tblc_seccion WHERE fecha_eliminado IS NULL AND estatus = 1 '.$sentencia.' ORDER BY nombre';

            return $strQuery;
        }

        function combodistritos($estado=0){
            $sentencia = ($municipio != '0') ? " AND id_estado = '".$estado."'" : '';

            $strQuery = 'SELECT id_distrito as id, nombre as valor FROM tblc_distrito WHERE fecha_eliminado IS NULL '.$sentencia.' ORDER BY nombre';

            return $strQuery;
        }

        function combomunicipios($estado=0){
            $sentencia = ($estado != '0') ? " AND id_estado = '".$estado."'" : '';

            $strQuery = 'SELECT id_municipio as id, nombre as valor FROM tblc_municipio WHERE fecha_eliminado IS NULL '.$sentencia.' ORDER BY nombre';

            return $strQuery;
        }

        function comboestados(){
            $strQuery = 'SELECT id_estado as id, nombre as valor FROM tblc_estado WHERE fecha_eliminado IS NULL ORDER BY nombre';

            return $strQuery;
        }

        function combotipoeleccion(){
            $strQuery = 'SELECT id_tipo_eleccion as id, nombre as valor FROM tblc_tipo_eleccion WHERE fecha_eliminado IS NULL AND estatus = 1 ORDER BY nombre';

            return $strQuery;
        }

        function combopartidopolitico(){
            $strQuery = 'SELECT id_partido_politico as id, nombre as valor FROM tblc_partido_politico WHERE fecha_eliminado IS NULL AND estatus = 1 ORDER BY nombre';

            return $strQuery;
        }

        function comboprocesoelectoral($id=0){
            $idSesion = (int) ($_SESSION['id_proceso_electoral'] ?? 0);
            $estadoSesion = (int) ($_SESSION['id_estado'] ?? 0);
            $municipioSesion = (int) ($_SESSION['id_municipio'] ?? 0);
            $idPermitido = $idSesion > 0 ? $idSesion : (int) $id;

            $sentencia = ($idPermitido > 0) ? " AND id_proceso_electoral = '".$idPermitido."'" : '';
            if ($idPermitido === 0 && $municipioSesion > 0) {
                $sentencia .= " AND id_municipio = '".$municipioSesion."'";
            } elseif ($idPermitido === 0 && $estadoSesion > 0) {
                $sentencia .= " AND id_estado = '".$estadoSesion."'";
            }

            $strQuery = 'SELECT id_proceso_electoral as id, CONCAT(descripcion," - ", fecha) as valor FROM tblc_proceso_electoral WHERE fecha_eliminado IS NULL AND estatus = 1'.$sentencia.' ORDER BY fecha DESC';

            return $strQuery;
        }

        //GETREGISTRO

        //REPRESENTANTE
        function getrepresentante($id){
            $strQuery = 'SELECT r.*, m.id_estado FROM tblc_representante as r 
            INNER JOIN tblc_municipio as m ON r.id_municipio = m.id_municipio 
            WHERE r.id_representante = '.$id;

            return $strQuery;
        }

        //SECCIÓN
        function getseccion($id){
            $strQuery = 'SELECT s.*, m.id_estado FROM tblc_seccion as s 
            INNER JOIN tblc_municipio as m ON s.id_municipio = m.id_municipio
            WHERE id_seccion = '.$id;

            return $strQuery;
        }

        //DISTRITO
        function getdistrito($id){
            $strQuery = 'SELECT * FROM tblc_distrito WHERE id_distrito = '.$id;

            return $strQuery;
        }

        //PROCESO ELECTORAL
        function getprocesoelectoral($id){
            $strQuery = 'SELECT pe.*, te.nombre as tipoeleccion, te.tipo FROM tblc_proceso_electoral AS pe 
            INNER JOIN tblc_tipo_eleccion AS te ON pe.id_tipo_eleccion = te.id_tipo_eleccion
            WHERE pe.id_proceso_electoral = '.$id;

            return $strQuery;
        }

        //CANDIDATO
        function getcandidato($id){
            $strQuery = 'SELECT * FROM tblc_candidato WHERE id_candidato = '.$id;

            return $strQuery;
        }

        //ESTADO
        function getestado($id){
            $strQuery = 'SELECT * FROM tblc_estado WHERE id_estado = '.$id;

            return $strQuery;
        }

        function getmunicipio($id){
            $strQuery = 'SELECT * FROM tblc_municipio WHERE id_municipio = '.$id;

            return $strQuery;
        }




    } //fin de la Clase querys
?>
