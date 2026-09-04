 
                    <h5 class="leftpanel-title">Menu</h5>
                    <ul class="nav nav-pills nav-stacked">
                        <?php 
						$obtener_menu_padre = $entity->objects("SELECT DISTINCT up.id_permiso AS id, p.* FROM tblc_permiso AS p INNER JOIN tbl_usuario_permiso AS up ON p.id_permiso = up.id_permiso WHERE up.id_usuario = ? AND p.id_padre = 0 AND p.fecha_eliminado IS NULL ORDER BY ordenamiento", [(int) $id_usuario]);
						foreach($obtener_menu_padre as $menu_padre){
							
							if($menu_padre->archivo == NULL || $menu_padre->archivo == "#" || $menu_padre->archivo == "")
								$menu_padre->archivo = '#';
							
							$obtener_submenu = $entity->objects("SELECT DISTINCT up.id_permiso AS id, p.* FROM tblc_permiso AS p INNER JOIN tbl_usuario_permiso AS up ON p.id_permiso = up.id_permiso WHERE up.id_usuario = ? AND p.fecha_eliminado IS NULL AND p.id_padre = ? ORDER BY ordenamiento", [(int) $id_usuario, (int) $menu_padre->id_permiso]);
							$num_arreglo = count($obtener_submenu);
							
							if($num_arreglo != 0)
								$despliega = 'parent';
							else
								$despliega = '';
							
							echo '<li class="'.$despliega.'"><a href="'.$menu_padre->archivo.'"><i class="fa '.$menu_padre->icono.'"></i> <span>'.$menu_padre->nombre.'</span></a>';
							
							if($num_arreglo != 0){
								echo '<ul class="children'.$activo.'">';
									foreach($obtener_submenu as $menu_hijo){
										
										if($menu_hijo->archivo === $modulo){
						                    $activo = ' class="nav-hover"';
						                	}
						                else {
						                    $activo = '';
						                	}

										echo '<li'.$activo.'><a href="'.$menu_hijo->archivo.'">'.$menu_hijo->nombre.'</a></li>';
												
									}
								echo ' </ul>';
							}
							echo '</li>';
						}				
						?>
                        
                    </ul>
                    
                
