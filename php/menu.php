                    <?php 
							$url = $_SERVER['REQUEST_URI'];
							$partir_url = explode("clinica/",$url);
							
							$quitar_var = explode("&",$partir_url[1]);
							
							if(isset($quitar_var[1]))
								$partir_url[1] = $quitar_var[0];
							else
								$partir_url[1] = $partir_url[1];
								
							
							//echo $partir_url[1];
					?>
                                
                                
                    <h5 class="leftpanel-title">&nbsp;</h5>
                    <ul class="nav nav-pills nav-stacked">
                        <li><a href="inicio"><i class="fa fa-home"></i> <span>Inicio</span></a></li>

                        <?php 
						
						if(base64_decode($_SESSION['tipo_usuario']) == 1){
						

								$obtener_menu_padre = $conexion->obtenerlista("SELECT * FROM tbl_permiso WHERE id_padre = 0 ORDER BY ordenamiento ASC");
																	
								 foreach($obtener_menu_padre as $menu_padre){
										
										if($menu_padre->archivo == NULL)
											$menu_padre->archivo = '#';
										
										
										$obtener_submenu = $conexion->obtenerlista('SELECT * FROM tbl_permiso WHERE id_padre = '.$menu_padre->id_permiso.' ORDER BY ordenamiento ASC');
										
										$num_arreglo = count($obtener_submenu);
										
										if($num_arreglo != 0)
											$despliega = 'class="parent"';
										else
											$despliega = '';
										
										echo '<li '.$despliega.'><a href="'.$menu_padre->archivo.'"><i class="fa fa-suitcase"></i> <span>'.$menu_padre->nombre.'</span></a>';
										
										
										
										if($num_arreglo != 0){
												echo '<ul class="children">';
													foreach($obtener_submenu as $menu_hijo){
															
															echo '<li><a href="'.$menu_hijo->archivo.'">'.$menu_hijo->nombre.'</a></li>';
																
													}
												echo ' </ul>';
										}
										echo '</li>';
								 }
						}
						else{ // si no eres super usuario
							echo $a = "SELECT * FROM tbl_usuario_permiso WHERE id_usuario = ".base64_decode($_SESSION['idusuario'])."";
							$obtener_permisos = $conexion->obtenerlista($a);
							
								$agrupar = array_count_values($obtener_permisos);
								
								foreach($agrupar as $key => $valor){
									echo $valor;
								}
							
							}
						
						?>
                        
                           
                        <!--
                        <li><a href="messages.html"><span class="pull-right badge">5</span><i class="fa fa-envelope-o"></i> <span>Messages</span></a></li>
                        <li class="parent"><a href=""><i class="fa fa-edit"></i> <span>Forms</span></a>
                            <ul class="children">
                                <li><a href="code-editor.html">Code Editor</a></li>
                                <li><a href="general-forms.html">General Forms</a></li>
                                <li><a href="form-layouts.html">Layouts</a></li>
                                <li><a href="wysiwyg.html">Text Editor</a></li>
                                <li><a href="form-validation.html">Validation</a></li>
                                <li><a href="form-wizards.html">Wizards</a></li>
                            </ul>
                        </li>
                        <li class="parent"><a href=""><i class="fa fa-bars"></i> <span>Tables</span></a>
                            <ul class="children">
                                <li><a href="basic-tables.html">Basic Tables</a></li>
                                <li><a href="data-tables.html">Data Tables</a></li>
                            </ul>
                        </li>
                        <li><a href="maps.html"><i class="fa fa-map-marker"></i> <span>Maps</span></a></li>
                        <li class="parent"><a href=""><i class="fa fa-file-text"></i> <span>Pages</span></a>
                            <ul class="children">
                                <li><a href="notfound.html">404 Page</a></li>
                                <li><a href="blank.html">Blank Page</a></li>
                                <li><a href="calendar.html">Calendar</a></li>
                                <li><a href="invoice.html">Invoice</a></li>
                                <li><a href="locked.html">Locked Screen</a></li>
                                <li><a href="media-manager.html">Media Manager</a></li>
                                <li><a href="people-directory.html">People Directory</a></li>
                                <li><a href="profile.html">Profile</a></li>                                
                                <li><a href="search-results.html">Search Results</a></li>
                                <li><a href="signin.html">Sign In</a></li>
                                <li><a href="signup.html">Sign Up</a></li>
                            </ul>
                        </li>
                        -->
                    </ul>
                    
                