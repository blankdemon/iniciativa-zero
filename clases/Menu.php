<?php
	class Menu {
		var $enlaces = array();
		var $estilosPagina = array();
		var $javascriptFiles = array();
		var $javascriptOnloadFunctions = array();
		
		function Menu() {
			$q = "SELECT * FROM menu WHERE ";
			if($_SESSION[id_cuenta]>0 && $_SESSION[nivelAcceso]==2) {
				$q .= "(nivel_acceso <= " . (int) $_SESSION[nivelAcceso] . " OR id_menu IN (1,3)) AND id_menu NOT IN(2,5) ";
			} else {
				if($_SESSION[id_cuenta]>0) {
					$q .= "(nivel_acceso <= 1) AND id_menu NOT IN(2,5) ";
				} else {
					$q .= "nivel_acceso=0";
				}
			}
			$q .= " ORDER BY orden";
			
			$s = mysql_query($q);
			if(@mysql_num_rows($s)) {
				while($r = mysql_fetch_array($s)) {
					// incializo las variables en vacio todo el rato...
					$javascript_files_actual = '';
					$javascript_onload_functions_actual = '';

					// creo los enlaces de los menus y el contenido de los menus
					array_push($this->enlaces, array($r[id_menu], $r[nombre], $r[descripcion], $r[pagina], $r[en_menu], $r[nivel_acceso], $r[javascript_files], $r[javascript_onload_functions]));
					
					// se obtienen y procesan los javascript que seran cargados en una pagina en especial
					if($_REQUEST[id_pagina] > 0) {
						if($_REQUEST[id_pagina]==$r[id_menu]) {
							$javascript_files_actual = $r[javascript_files];
							$javascript_onload_functions_actual = $r[javascript_onload_functions];
						}
					} else {
						$javascript_files_actual = $this->enlaces[0][6];
						$javascript_onload_functions_actual = $this->enlaces[0][7];
					}
					
					// despliega las funciones y los ficheros javascript que deben ser cargados en las diversas paginas (estos puede variar)
					$jsf = explode(",", $javascript_files_actual);
					if(sizeof($jsf)) {
						for($i=0;$i<sizeof($jsf);$i++) {
							if(!empty($jsf[$i])) 
								array_push($this->javascriptFiles, trim($jsf[$i]));
						}				
					}

					$jsof = explode(",", $javascript_onload_functions_actual);
					if(sizeof($jsof)) {
						for($i=0;$i<sizeof($jsof);$i++) {
							if(!empty($jsof[$i])) 
								array_push($this->javascriptOnloadFunctions, trim($jsof[$i]));
						}				
					}
				}
			}
			
			/*
				echo '<pre>';
				echo '<b>$javascriptFiles:</b><br>';
				print_r($this->javascriptFiles);
				echo '<b>$javascriptOnloadFunctions:</b><br>';
				print_r($this->javascriptOnloadFunctions);
				echo '</pre>';
			*/
		}
		
		function crearEnlaces() {
			if(sizeof($this->enlaces)) {
				$enlace = '<div id="dropList"><ul id="menu">';
				for($i=0;$i<sizeof($this->enlaces);$i++) {
					if($this->enlaces[$i][4]) {
						$qq = "SELECT id_menu, nombre, descripcion FROM menu WHERE id_menu_pertenece = " . $this->enlaces[$i][0];
						$ss = mysql_query($qq);
						
						$enlace .= 	'<li class="level1-li sub"><a class="level1-a" href="';
						if(!mysql_num_rows($ss)) {
							$enlace .= '?id_pagina=' . (int) $this->enlaces[$i][0] . '"';
							if($_REQUEST[id_pagina]== $this->enlaces[$i][0]) $enlace .= ' id="seleccionado"';
							if(!empty($this->enlaces[$i][2])) $enlace .= ' title="' . $this->enlaces[$i][2] . '"';
						} else {
							$enlace .= 'javascript:;"';
						}		
						$enlace .= '>' . $this->enlaces[$i][1] . '<!--[if gte IE 7]><!--></a><!--<![endif]-->';
						
						if(mysql_num_rows($ss)) {						
							$enlace .= '<!--[if lte IE 6]><table><tr><td><![endif]-->
							<div class="listHolder col1">
								<div class="listCol">
									<ul>';
									while($r = mysql_fetch_array($ss)) {
										$enlace .= '<li><a href="?id_pagina=' . $r[id_menu] . '" title="' . $r[descripcion] . '">' . $r[nombre] . '</a></li>';
									}
							$enlace .= '</ul>
									</div>
								</div>
							<!--[if lte IE 6]></td></tr></table></a><![endif]-->';
						}
						$enlace .= '</li>
						<!-- div class="separador_nav"></div -->';
					}
				}
				$enlace .= '</ul></div>';
				echo $enlace;
			} else {
				echo 'no hay enlaces disponibles';
			}
		}
		
		function paginaRequerida() {
			// si ha iniciado session se requiere la pagina de paneles
			//if(isset($_SESSION["usuario"]) || $interfaz_login == 1) return 'paneles';
			
			if($_REQUEST[id_pagina] > 0) {
				for($i=0;$i<sizeof($this->enlaces);$i++) {
					if($_REQUEST[id_pagina]==$this->enlaces[$i][0]) {
						$pagina_existe = true;
						return $this->enlaces[$i][3];												
					}
				}
				
				if(!$pagina_existe) {
					return 'inicio';
				}
			} else {
				return 'inicio';
			}
		}
	};
?>
