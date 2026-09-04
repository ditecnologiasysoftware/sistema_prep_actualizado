      <!--  ARRIBA----------------------------------------------------------------------------------- -->

       <div class="pageheader">
            <div class="media">
                <div class="pageicon pull-left">
                    <i class="fa fa-database"></i>
                </div>
                <div class="media-body">
                    <ul class="breadcrumb">
                        <li><a href=""><i class="glyphicon glyphicon-home"></i></a></li>
                        <li>Ordenamiento de partidos en el Acta</li>
                    </ul>
                    <h4>Ordenamiento de partidos en el Acta</h4>
                </div>
            </div><!-- media -->
        </div><!-- pageheader -->
        
        <!--FIN ARRIBA-------------------------------------------------------------------------------- -->
        <div class="contentpanel">
          <!-- CONTENIDO ----------------------------------------------------------------------- -->
		   <div class="row">
                <div class="col-md-12">

                    <div class="panel panel-default">

                        <div class="panel-heading">

                            <div class="row">
                                <div class="form-group col-sm-8">
                                    <select name="idprocesoElect" id="idprocesoElect" style="width:95%;" required onchange="ordenar_acta_lista(this)">
                                        <option value=""> - Seleccionar Proceso Electoral - </option>
                                        <?php
                                        echo $funciones->llenarcombo($querys->comboprocesoelectoral($id_proceso_electoral));
                                        ?>
                                    </select>
                                </div> 

                                <div class="form-group col-sm-4"><h4 class="panel-title">Seleccionar el proceso electoral y organiza los Partidos registrados en el acta arrastransolos al orden correspondiente</h4></div>

                            </div>

                        </div>
                        <div class="panel-body">

                              <table id="tabla" class="table table-striped table-bordered responsive">
                                <thead>
                                  <tr>
                                    <th>ID</th>
                                    <th>Partido</th>
                                    <th>Candidato</th>
                                  </tr>
                                </thead>
                                <tbody id="listapartidos">
                                </tbody>
                              </table>

                        </div><!-- panel-body -->

                        <div class="panel-footer">
                            <button onclick="guardarOrden()" class="btn btn-primary mr5">Guardar Orden</button>
                        </div><!-- panel-footer -->

                        </form>
                    </div><!-- panel-default -->                                    

                      <script>
                        const tabla = document.getElementById("tabla").querySelector("tbody");
                        let draggingEl = null;

                        tabla.addEventListener("dragstart", e => {
                          if (e.target.tagName === "TR") {
                            draggingEl = e.target;
                            e.target.classList.add("dragging");
                          }
                        });

                        tabla.addEventListener("dragend", e => {
                          if (e.target.tagName === "TR") {
                            e.target.classList.remove("dragging");
                            draggingEl = null;
                          }
                        });

                        tabla.addEventListener("dragover", e => {
                          e.preventDefault();
                          const afterEl = getDragAfterElement(tabla, e.clientY);
                          if (afterEl) {
                            tabla.insertBefore(draggingEl, afterEl);
                          } else {
                            tabla.appendChild(draggingEl);
                          }
                        });

                        function getDragAfterElement(container, y) {
                          const draggableElements = [...container.querySelectorAll("tr:not(.dragging)")];

                          let closest = null;
                          let closestOffset = Number.NEGATIVE_INFINITY;

                          draggableElements.forEach(row => {
                            const box = row.getBoundingClientRect();
                            const offset = y - box.top - box.height / 2;

                            if (offset < 0 && offset > closestOffset) {
                              closestOffset = offset;
                              closest = row;
                            }
                          });

                          return closest; // puede ser null
                        }

                        function guardarOrden() {
                          const orden = [...tabla.querySelectorAll("tr")].map(el => el.dataset.id);

                          fetch("php/subir_orden.php", {
                            method: "POST",
                            headers: { "Content-Type": "application/json" },
                            body: JSON.stringify({ orden: orden })
                          })
                          .then(res => res.text())
                          .then(data => {
                            alert("Orden guardado: " + data);
                          });
                        }
                      </script>

                </div>
              
            </div><!-- row -->  
                    
            <!--FIN DE CONTENIDO-------------------------------------------------------->
            
        </div><!-- contentpanel -->