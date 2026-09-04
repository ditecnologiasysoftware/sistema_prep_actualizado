<?php 
    @session_start();
  $id_usuario = $_SESSION['id_usuario'];
  $autentificado = $_SESSION['autentificado'];
  $nombre = $_SESSION['nombre'];
  $id_sesion_sistema = $_SESSION['id_sesion_sistema'];
  
  require ("../php/clase_variables.php");
  require ("../php/clase_mysql.php");
  require ("../php/clase_funciones.php");
  
  $funciones = new Funciones();
  //LLAMAMOS A LA CLASE CONEXION
  $entity = Entity::createInstance();

  $idproceso = $funciones->limpia($_POST['p']); 
  $casilla = $funciones->limpia($_POST['c']);

  $acta = $entity->row("SELECT * FROM tbl_acta WHERE id_proceso_electoral = ".$idproceso." AND id_casilla = ".$casilla);


  $consulta =  "SELECT * FROM tbl_bingo ORDER BY numero ASC";
    $resultados = $entity->objects($consulta);
 ?>
   <style>
       .div1-css{
        background-color: black;
        color: #fff;
        border-radius: 50%;       
        width: 67%;
        height: 62px;
        cursor: pointer;
       }
       .div2-css{
        background-color: red;
        color: #fff;
        border-radius: 50%;       
        width: 67%;
        height: 62px;
        cursor: pointer;

       }
       .p-div{
            font-size: 20px;
            padding: 15px;
       }

      .tooltiptext {
          visibility: hidden;
          width: 135px;
          background-color: #5b5b5b;
          color: #fff;
          text-align: center;
          border-radius: 6px;
          padding: 6px 0;
          position: absolute;
          z-index: 1;
          margin-left: -60px;

      }

      .div1-css:hover .tooltiptext {
        visibility: visible;
      }

      .div2-css:hover .tooltiptext {
        visibility: visible;
      }

      .padding-md{
            padding: 9px;
      }
    </style>
 <div class="control-group">
  <?php foreach ($resultados as $bing) { ?>
 
    <div class="col-md-2 padding-md" >
    
        <div class="div1-css " id="<?= $bing->id_bingo ?>" onclick="cambiarValorbingo(<?= $bing->id_bingo ?>,<?= $bing->numero ?>)">
            <p class="p-div"><?= $bing->numero ?></p>
              <span class="tooltiptext"><?= $bing->nombre_completo ?></span>

        </div>      
    </div>
   <?php } ?>
     <!-- <div class="col-md-2 padding-md">
    
        <div class="div1-css " id="2" onclick="cambiarValorbingo('2','2')">
            <p class="p-div">2</p>
              <span class="tooltiptext">Luis Perz</span>

        </div>
      
    </div>
      <div class="col-md-2 padding-md" >
    
        <div class="div1-css " id="3" onclick="cambiarValorbingo('3','3')">
            <p class="p-div">3</p>
              <span class="tooltiptext">Luis Perz</span>

        </div>
      
    </div>
     
     <div class="col-md-2 padding-md" >
    
        <div class="div1-css " id="4" onclick="cambiarValorbingo('4','4')">
            <p class="p-div">4</p>
              <span class="tooltiptext">Luis Perz juan lopez xim</span>

        </div>
      
    </div>
     <div class="col-md-2 padding-md" >
    
        <div class="div2-css " id="5" onclick="cambiarValorbingo('5','5')">
            <p class="p-div">5</p>
              <span class="tooltiptext">Luis Perz</span>

        </div>
      
    </div>
    <div class="col-md-2 padding-md" >
    
        <div class="div1-css " id="6" onclick="cambiarValorbingo('6','6')">
            <p class="p-div">6</p>
              <span class="tooltiptext">Luis Perz</span>

        </div>
      
    </div>
    <div class="col-md-2 padding-md" >
    
        <div class="div1-css " id="7" onclick="cambiarValorbingo('7','7')">
            <p class="p-div">7</p>
              <span class="tooltiptext">Luis Perz</span>

        </div>
      
    </div>
    <div class="col-md-2 padding-md" >
    
        <div class="div2-css " id="8" onclick="cambiarValorbingo('8','8')">
            <p class="p-div">8</p>
              <span class="tooltiptext">Luis Perz</span>

        </div>
      
    </div>
    <div class="col-md-2 padding-md" >
    
        <div class="div1-css " id="9" onclick="cambiarValorbingo('9','9')">
            <p class="p-div">9</p>
              <span class="tooltiptext">Luis Perz</span>

        </div>
      
    </div>
     <div class="col-md-2 padding-md" >
    
         <div class="div2-css " id="10" onclick="cambiarValorbingo('10','10')" >
            <p class="p-div">981</p>
              <span class="tooltiptext">Luis Perz</span>
        </div>
      
    </div>-->
 </div>


 <script type="text/javascript">
   function cambiarValorbingo(id, numero){
    let clase = $('#'+id).attr('class');
      if(clase == 'div2-css'){
         $('#'+id).removeClass("div2-css").addClass("div1-css");
      }else{
         $('#'+id).removeClass("div1-css").addClass("div2-css");
      }

   }
 </script>