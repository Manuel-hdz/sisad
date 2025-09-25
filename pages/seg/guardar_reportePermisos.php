<?php 
	/**
	  * Nombre del Módulo:Seguridad Industrial                                             
	  * Nombre Programador: Nadia Madahí López Hernández
	  * Fecha: 08/Febrero/2012                                      			
	  * Descripción: Este archivo contiene funciones para almacenar la información en una hoja de calculo de excel de las consultas realizadas y reportes generados.
	  **/
	 /**
      * Listado del contenido del programa                                            
      *   Includes: 
	        1. Modulo de conexion con la base de datos
			2. Modulo de operaciones con la BD*/
			include("../../includes/conexion.inc");
			include("../../includes/op_operacionesBD.php");
			include("../../includes/func_fechas.php");
			
	/**   Código en: pages\rec\guardar_reporte.php                                   
      **/
	  
	  			
	if(isset($_POST['hdn_consulta'])){
	
		//Ubicacion de las imagenes que estan contenidas en los encabezados
		define("HOST", $_SERVER['HTTP_HOST']);
		//Obtener el nombre del Nombre de la Carpeta Raíz donde se encontrará almacenado el SISAD
		$raiz = explode("/",$_SERVER['PHP_SELF']);
		define("SISAD",$raiz[1]);		
		
		switch($hdn_origen){
			case "reportePermisoPeligroso":
				guardarRepPermisoPel($hdn_consulta, $hdn_consulta2, $hdn_nomReporte);							
			break;	
			case "reportePermisoAlturas":
				guardarRepPermisoAlt($hdn_consulta, $hdn_consulta2, $hdn_nomReporte);							
			break;		
			case "TRABAJOS ALTURAS":
				consultarRepPermisoAlturas($hdn_consulta, $hdn_nomReporte, $hdn_msg);							
			break;						
			case "TRABAJOS FLAMA ABIERTA":
				consultarRepPermisoFlama($hdn_consulta, $hdn_nomReporte, $hdn_msg);							
			break;	
			case "TRABAJOS PELIGROSOS":
				consultarRepPermisoPeligroso($hdn_consulta, $hdn_nomReporte, $hdn_msg);						
			break;	
			case "reportePlanContingencia":
				consultarRepPlanContingencia($hdn_consulta, $hdn_nomReporte, $hdn_msg);						
			break;
			case "reportePlanContingenciaEjecutados":
				consultarRepPlanContingenciaEjecutados($hdn_consulta, $hdn_nomReporte, $hdn_msg);						
			break;
				
		}	
	}
	
	if(isset ($_GET['clavePermisoSeg'])){
		//Ubicacion de las imagenes que estan contenidas en los encabezados
		define("HOST", $_SERVER['HTTP_HOST']);
		//Obtener el nombre del Nombre de la Carpeta Raíz donde se encontrará almacenado el SISAD
		$raiz = explode("/",$_SERVER['PHP_SELF']);
		define("SISAD",$raiz[1]);	
		
		obtenerClavePermisoSeg($_GET['clavePermisoSeg']);
	}
	else if(isset ($_GET['clavePermisoSegAlturas'])){
		//Ubicacion de las imagenes que estan contenidas en los encabezados
		define("HOST", $_SERVER['HTTP_HOST']);
		//Obtener el nombre del Nombre de la Carpeta Raíz donde se encontrará almacenado el SISAD
		$raiz = explode("/",$_SERVER['PHP_SELF']);
		define("SISAD",$raiz[1]);	
		obtenerClavePermisoSegAlturas($_GET['clavePermisoSegAlturas']);
	}
	
	//Esta funcion exporta el REPORTE de Plan de Contingencia que ya se encuentran Realizados ó Ejecutados
	function consultarRepPlanContingenciaEjecutados($hdn_consulta, $hdn_nomReporte, $hdn_msg){
		//Esribir datos de la pagina en excel
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=$hdn_nomReporte.xls");
		
		include_once("verFoto.php");
		//Realizar la conexion a la BD 
		$conn = conecta("bd_seguridad");
		//Ejecutar la sentencia y almacena los datos de la consulta en la variable $rs (result set)
		$rs_PCE= mysql_query($hdn_consulta);
		//Obtenemos el numero de Imagens
		$numImagenes = contarImagenes($hdn_consulta);
				
		//Verificamos que la consulta haya generado datos	
		if($datos_PCE=mysql_fetch_array($rs_PCE)){
			//Definir el estilo de la tabla y el encabezado que aparecera sobre la tabla?>
			<head>
				<style>					
					<!--
					body { font-family:Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; }
					/*En este formato  se establece el tamaño,tipo de letra,color de fondo que ocupa la columna principal de una tabla*/
					.nombres_columnas { font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #FFFFFF; background-color: #9BBA59; font-weight: bold; 
					border-top-width: medium; border-right-width: thin;
					border-bottom-width: medium; border-left-width: thin; border-top-style: solid; border-right-style: none; border-bottom-style: 
					solid; border-left-style: none; 
					border-top-color: #000000; border-bottom-color: #000000; }
					/*En este formato  se establece el tamaño,tipo de letra,color de fondo que se ocupa en la fila principal para resaltar datos  de una tabla*/
					.nombres_filas { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #9BBB59; }
					/*En este formato  se establece el tamaño,tipo de letra,color de fondo que contendra datos  de una tabla*/
					.renglon_gris { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #E7E7E7; vertical-align:middle;}
					/*En este formato  se establece el tamaño,tipo de letra,color de fondo que contendra datos  de una tabla*/
					.renglon_blanco { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #FFFFFF; vertical-align:middle;} 
					#tabla { position:absolute; left:0px; top:0px; width:1111px; height:175px; z-index:5; }
					.texto_encabezado {font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #0000CC; }
					.sub_encabezado {font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #0000CC; }
					.titulo_tabla {font-family: Arial, Helvetica, sans-serif; font-weight: bold; font-size: 14px; }
					.borde_linea { border-top:3px; border-top-color:#4E6128; border-top-style:solid;}
					.Estilo1 {color: #FFFFFF;	font-weight: bold;}
					-->
				</style>
				</head>											
				<body>
				<div id="tabla">				
				<table width="1100">					
					<tr>
						<td align="left" valign="baseline" colspan="<?php echo 2+$numImagenes; ?>">
							<img src="http://<?php echo HOST; ?>/<?php echo SISAD; ?>/images/logo.png" width="118" height="58" align="absbottom" />
						</td>
						<td colspan="5">&nbsp;</td>
						<td valign="baseline" colspan="5">
							<div align="right"><span class="texto_encabezado">
								<span class="Estilo1">
									MANUAL  DE PROCEDIMIENTOS DE LA CALIDAD</span><br><em>CONCRETO  LANZADO DE FRESNILLO, S.A DE C.V.</em>
								</span>
							</div>
						</td>
					</tr>											
					<tr>
						<td colspan="<?php echo 12+$numImagenes; ?>" align="center" class="borde_linea">
							<span class="sub_encabezado">
								CONFIDENCIAL, PROPIEDAD DE &ldquo;CONCRETO LANZADO DE FRESNILLO, S.A DE C.V.&rdquo; PROHIBIDA SU REPRODUCCI&Oacute;N TOTAL O PARCIAL
							</span>
						</td>
					</tr>					
					<tr><td colspan="12">&nbsp;</td></tr>
					<tr><td colspan="<?php echo 12+$numImagenes;?>" align="center" class="titulo_tabla"><?php echo $hdn_msg; ?></td></tr>
					<tr><td colspan="12">&nbsp;</td></tr>			
					<tr>
						<td align='center' class='nombres_columnas'>CLAVE PLAN</td>
						<td align='center' class='nombres_columnas'>RESPONSABLE PLAN</td>							
						<td align='center' class='nombres_columnas'>&Aacute;REA</td>
						<td align='center' class='nombres_columnas'>LUGAR</td>	
						<td align='center' class='nombres_columnas'>NOMBRE SIMULACRO</td>
						<td align='center' class='nombres_columnas'>TIPO SIMULACRO</td>
						<td align='center' class='nombres_columnas'>TIEMPO TOTAL</td>
						<td align='center' class='nombres_columnas'>FECHA REGISTRO</td>	
						<td align='center' class='nombres_columnas'>FECHA PROGRAMADA</td>																																		
						<td align='center' class='nombres_columnas'>FECHA REALIZADO</td>																																		
						<td align='center' class='nombres_columnas'>COMENTARIOS</td>																																		
						<td align='center' class='nombres_columnas'>OBSERVACIONES</td>
						<td colspan="<?php echo $numImagenes;?>" align='center' class='nombres_columnas'>EVIDENCIAS DEL SIMULACRO</td>																																								
					</tr><?php
					$nom_clase = "renglon_gris";
					$cont = 1;
					$idPlan ="";
					do{	?>
					<tr>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos_PCE['id_plan']; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos_PCE['responsable']; ?></td>					
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos_PCE['area']; ?></td>					
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos_PCE['lugar']; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos_PCE['nom_simulacro']; ?></td>					
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos_PCE['tipo_simulacro']; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos_PCE['tiempo_total']; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo modFecha($datos_PCE['fecha_reg'],1); ?></td>				
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo modFecha($datos_PCE['fecha_programada'],1); ?></td>	
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo modFecha($datos_PCE['fecha_realizado'],1); ?></td>			
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos_PCE['comentarios']; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos_PCE['observaciones']; ?></td><?php 
						$idPlan = $datos_PCE['id_plan'];
						$imagen = mostrarImagen($idPlan, 1);
						if($imagen!=""){?>
							<td colspan="3" class="<?php echo $nom_clase; ?>" width="200" height="180" align="center" valign="bottom">			
								<br>
								<img src="http://<?php echo HOST; ?>/<?php echo SISAD."/pages/seg/".$imagen;?>" width="200" height="150" />
								<br>
								<span class="texto_negro">EVIDENCIA 1</span>	
							</td><?php 
						} 
					$imagen = mostrarImagen($idPlan, 2);
					if($imagen!=""){?>
						<td colspan="3" class="<?php echo $nom_clase; ?>" width="200" height="180" align="center" valign="bottom">
							<br>
							<img src="http://<?php echo HOST; ?>/<?php echo SISAD."/pages/seg/".$imagen;?>"width="200" height="150" />
							<br>
							<span class="texto_negro">EVIDENCIA 2</span>
						</td><?php 
					} 
					$imagen = mostrarImagen($idPlan, 3);
					if($imagen!=""){?>
						<td colspan="3" class="<?php echo $nom_clase; ?>" width="200" height="180" align="center" valign="bottom">
							<br>
							<img src="http://<?php echo HOST; ?>/<?php echo SISAD."/pages/seg/".$imagen;?>" width="200" height="150" />
							<br>
							<span class="texto_negro">EVIDENCIA 3</span>
						</td><?php 
					} 
					$imagen = mostrarImagen($idPlan, 4);
					if($imagen!=""){?>
						<td colspan="3" class="<?php echo $nom_clase; ?>" width="200" height="180" align="center" valign="bottom">
							<br>
							<img src="http://<?php echo HOST; ?>/<?php echo SISAD."/pages/seg/".$imagen;?>" width="200" height="150" />
							<br>
							<span class="texto_negro">EVIDENCIA 4</span>
						</td><?php 
					} 
					$imagen = mostrarImagen($idPlan, 5);
					if($imagen!=""){?>
						<td colspan="3" class="<?php echo $nom_clase; ?>" width="200" height="180" align="center" valign="bottom">
							<br>
							<img src="http://<?php echo HOST; ?>/<?php echo SISAD."/pages/seg/".$imagen;?>" width="200" height="150" />
							<br>
							<span class="texto_negro">EVIDENCIA 5</span>
						</td><?php 
					}?>
				</tr><?php				
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";	
			}while($datos_PCE=mysql_fetch_array($rs_PCE));?>					
			</table>
			</div>
			</body><?php 
		}
		//Cerrar la conexion con la BD		
		mysql_close($conn);		
	}//Fin de la Funcion consultarRepPermisoFlama

	
	//Esta funcion exporta el REPORTE PERMISO PELIGROSO  de los empleados a un archivo de excel
	function guardarRepPermisoPel($hdn_consulta, $hdn_consulta2, $hdn_nomReporte){
		//Esribir datos de la pagina en excel
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=$hdn_nomReporte.xls");
			
		//Realizar la conexion a la BD 
		$conn = conecta("bd_seguridad");
		
		//Ejecutar la sentencia y almacena los datos de la consulta en la variable $rs (result set)
		$rs_datos = mysql_query($hdn_consulta);
		//Ejecuta la sentencia donde almacena el tipo de permiso que se esta generadondo
		$rs_datos2 = mysql_query($hdn_consulta2);
		
		//Verificamos que la consulta haya generado datos	
		if($datos=mysql_fetch_array($rs_datos)){
			if($datos2=mysql_fetch_array($rs_datos2)){

			//Definir el estilo de la tabla y el encabezado que aparecera sobre la tabla?>
			<head>
				<style>					
					<!--
					body { font-family:Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; }
					/*En este formato  se establece el tamaño,tipo de letra,color de fondo que ocupa la columna principal de una tabla*/
					.nombres_columnas { font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #FFFFFF; background-color: #9BBA59; font-weight: bold; 
					border-top-width: medium; border-right-width: thin;
					border-bottom-width: medium; border-left-width: thin; border-top-style: solid; border-right-style: none; border-bottom-style: 
					solid; border-left-style: none; 
					border-top-color: #000000; border-bottom-color: #000000; }
					/*En este formato  se establece el tamaño,tipo de letra,color de fondo que se ocupa en la fila principal para resaltar datos  de una tabla*/
					.nombres_filas { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #9BBB59; }
					/*En este formato  se establece el tamaño,tipo de letra,color de fondo que contendra datos  de una tabla*/
					.renglon_gris { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #E7E7E7; }
					/*En este formato  se establece el tamaño,tipo de letra,color de fondo que contendra datos  de una tabla*/
					.renglon_blanco { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #FFFFFF; } 
					#tabla { position:absolute; left:0px; top:0px; width:1111px; height:175px; z-index:5; }
					.texto_encabezado {font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #0000CC; }
					.sub_encabezado {font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #0000CC; }
					.titulo_tabla {font-family: Arial, Helvetica, sans-serif; font-weight: bold; font-size: 14px; }
					.borde_linea { border-top:3px; border-top-color:#4E6128; border-top-style:solid;}
					.Estilo1 {color: #FFFFFF;	font-weight: bold;}
					-->
				</style>
			</head>											
			<body>
			<div id="tabla">				
				<table width="1100">					
					<tr>
						<td align="left" valign="baseline"><img src="http://<?php echo HOST; ?>/<?php echo SISAD; ?>/images/logo.png" width="118" height="58" 
                        align="absbottom" /></td>
						<td valign="baseline">
							<div align="right"><span class="texto_encabezado">
								<span class="Estilo1">
									MANUAL  DE PROCEDIMIENTOS DE LA CALIDAD</span><br><em>CONCRETO  LANZADO DE FRESNILLO, S.A DE C.V.</em>
								</span>
							</span></div>
						</td>
					</tr>											
					<tr>
						<td colspan="2" align="center" class="borde_linea">
							<span class="sub_encabezado">CONFIDENCIAL, PROPIEDAD DE &ldquo;CONCRETO LANZADO DE FRESNILLO, S.A DE C.V.&rdquo; PROHIBIDA SU REPRODUCCI
                            &Oacute;N TOTAL O PARCIAL</span>
						</td>
					</tr>					
					<tr>
						<td colspan="2">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="2">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="2" align="center" class="titulo_tabla">PERMISOS GENERALES</td>
					</tr>					
					<tr>
						<td colspan="2">&nbsp;</td>
					</tr>			
					<?php
						$nom_clase = "renglon_gris";
						$cont = 1;
						$cont2 =1;
					do{	?>	
						<tr>
							<td align='center' class='nombres_filas'>CLAVE DEL PERMISO </td>
							<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['id_permiso_trab']; ?></td>
						</tr>
						<tr>
							<td align='center' class='nombres_filas'>NOMBRE DEL SOLICITANTE(RESPONSABLE POR LA OBRA POR PARTE DE CONCRETO LANZADO DE FRESNILLO S.A DE C.V.)</td>
							<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['nom_solicitante']; ?></td>
						</tr>				
						<tr>
							<td align='center' class='nombres_filas'>NOMBRE DEL SUPERVISOR A CARGO DE LA OBRA</td>
							<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['nom_supervisor']; ?></td>
						</tr>				
						<tr>
							<td align='center' class='nombres_filas'>NOMBRE DEL RESPONSABLE DE LA OBRA POR PARTE DE LA CIA. CONTRATISTA</td>
							<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['nom_responsable']; ?></td>					
						</tr>					
						<tr>	
							<td align='center' class='nombres_filas'>NOMBRE DE LA CIA. CONTRATISTA</td>
							<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['nom_contratista']; ?></td>
						</tr>				
						<tr>						
							<td align='center' class='nombres_filas'>DESCRIPCION DEL TRABAJO</td>
							<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['descripcion_trabajo']; ?></td>
						</tr>												
						<tr>
							<td align='center' class='nombres_filas'>INDIQUE EL TRABAJO PELIGROSO QUE SE REALIZARA</td>
							<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['trabajo_realizar']; ?></td>					
						</tr>				
						<tr>
							<td align='center' class='nombres_filas'>PERIODO DE INICIO DE LA OBRA</td>	
							<td align="center" class="<?php echo $nom_clase; ?>"><?php echo modFecha($datos['fecha_ini'],1); ?></td>																				
						</tr>
						<tr>
							<td align='center' class='nombres_filas'>PERIODO FIN DE LA OBRA</td>	
							<td align="center" class="<?php echo $nom_clase; ?>"><?php echo modFecha($datos['fecha_fin'],1); ?></td>															
						</tr>
						<tr>
							<td align='center' class='nombres_filas'>HORARIO DE INICIO DE LA OBRA</td>	
							<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['horario_ini'];?>&nbsp;<?php echo $datos['meridiano_ini']; ?></td>
						</tr>
						<tr>
							<td align='center' class='nombres_filas'>HORARIO FIN DE LA OBRA</td>	
							<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['horario_fin'];?>&nbsp;<?php echo $datos['meridiano_fin']; ?></td>					
						</tr>				
						<tr>
							<td align='center' class='nombres_filas'>TIPO DE TRABAJO QUE SE REALIZARA</td>	
							<td align='center' class='nombres_filas'>INDIQUE EL TRABAJO ESPECIFICO QUE SE AUTORIZA</td>													
						</tr>
						<tr>
							<td align="center" class="<?php echo $nom_clase; ?>"><strong><?php echo $datos['nom_permiso']; ?></strong></td>	
							<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['trabajo_especifico']; ?></td>	
						</tr>
						<tr>
							<td colspan="2" align="center" class="titulo_tabla">REGLAMENTO DE SEGURIDAD QUE DEBE DE CUMPLIR EL CONTRATISTA AL REALIZAR EL TRABAJO</td>
						</tr>
						<tr>
							<td colspan="2" align="center" class="titulo_tabla"><?php echo $datos['nom_permiso']; ?></td>	
						</tr><?php 
							do{
							//Se realiza un ciclo para que imprima tantos renglones como sea posible dentro de la tabla?>											
								<tr>
									<td colspan="2" align="left" class="<?php echo $nom_clase; ?>"><?php echo $cont; ?>
									<?php echo" .- ".  $datos2['actividad']; ?></td>	
								</tr><?php 
								$cont++;
							}while($datos2=mysql_fetch_array($rs_datos2));
						
							//Crear la sentencia que traera cono resultado las medidas de CARACTER GENERAL que se van a ostrar cualquiera que este sea el reporte
							$sql_stm_mcg = "SELECT nom_permiso, actividad FROM pasos_permiso JOIN permisos_secundarios
								 ON id_permiso_secundario = permisos_secundarios_id_permiso_secundario WHERE nom_permiso = 'MEDIDAS CARACTER GENERAL'";
											
							//Ejecutar la sentencia previamente creada
							$rs_mcg = mysql_query($sql_stm_mcg);	
																														
								//Confirmar que la consulta de datos fue realizada con exito.
								if($datos_mcg=mysql_fetch_array($rs_mcg)){?>
									<tr>
										<td colspan="2" align="center" class="titulo_tabla"><?php echo $datos_mcg['nom_permiso']; ?></td>	
									</tr>
									<?php do{?>
											<tr>
												<td colspan="2" align="left" class="<?php echo $nom_clase; ?>"><?php echo $cont2;?>
												<?php echo" .- ".$datos_mcg['actividad'];?></td>	
											</tr><?php
											$cont2++;
										}while($datos_mcg=mysql_fetch_array($rs_mcg));	
								}?>
					<tr>
						<td align='center' class='nombres_filas'>NOMBRE DEL RESPONSABLE </td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['firma_responsable']; ?></td>					
					</tr>	
					<tr>
						<td align='center' class='nombres_filas'><strong>FIRMA </strong></td>
						<td align="center" class="<?php echo $nom_clase; ?>">____________________________</td>					
					</tr>						
					<tr>
						<td align='center' class='nombres_filas'>FUNCIONARIO RESPONSABLE</td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['funcionario_res']; ?></td>					
					</tr>	
					<tr>
						<td align='center' class='nombres_filas'>NOMBRE SUPERVISOR</td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['supervisor']; ?></td>					
					</tr>	
					<tr>
						<td align='center' class='nombres_filas'>NOMBRE OPERADOR</td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['operador']; ?></td>					
					</tr>										
					<tr>
						<td align='center' class='nombres_filas'>SUPERVISOR OBRA CONTRATISTA</td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['supervisor_obra']; ?></td>					
					</tr>
					<tr>
						<td align='center' class='nombres_filas'><strong>ACEPTACI&Oacute;N</strong></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['aceptacion']; ?></td>					
					</tr>	
					<tr>
						<td align='center' class='nombres_filas'><strong>ACEPTO CUMPLIR TOTALMENTE ESTAS MEDIDAS DE SEGURIDAD</strong></td>
						<td align="center" class="<?php echo $nom_clase; ?>">____________________________</td>					
					</tr><?php 																		
					//Determinar el color del siguiente renglon a dibujar
					$cont++;
					if($cont%2==0)
						$nom_clase = "renglon_blanco";
					else
						$nom_clase = "renglon_gris";
						
				}while($datos=mysql_fetch_array($rs_datos)); 
			?>
			</table>
			</div>
			</body>
			<?php }
		}
		//Cerrar la conexion con la BD		
		mysql_close($conn);		
	}//Fin de la Funcion 
	
	
	
	
	
	//Esta funcion exporta el REPORTE HDE PERMISOS DE ALTURAS a un archivo de excel
	function guardarRepPermisoAlt($hdn_consulta, $hdn_consulta2, $hdn_nomReporte){
		//Esribir datos de la pagina en excel
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=$hdn_nomReporte.xls");
			
		//Realizar la conexion a la BD 
		$conn = conecta("bd_seguridad");
		
		//Ejecutar la sentencia y almacena los datos de la consulta en la variable $rs (result set)
		$rs_PA = mysql_query($hdn_consulta);

		$rs_PA2 = mysql_query($hdn_consulta2);

		//Verificamos que la consulta haya generado datos	
		if($datosPA=mysql_fetch_array($rs_PA)){
			
			if($datosPA2=mysql_fetch_array($rs_PA2)){

			//Definir el estilo de la tabla y el encabezado que aparecera sobre la tabla?>
			<head>
				<style>					
					<!--
					body { font-family:Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; }
					/*En este formato  se establece el tamaño,tipo de letra,color de fondo que ocupa la columna principal de una tabla*/
					.nombres_columnas { font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #FFFFFF; background-color: #9BBA59; font-weight: bold; 
					border-top-width: medium; border-right-width: thin;
					border-bottom-width: medium; border-left-width: thin; border-top-style: solid; border-right-style: none; border-bottom-style: 
					solid; border-left-style: none; 
					border-top-color: #000000; border-bottom-color: #000000; }
					/*En este formato  se establece el tamaño,tipo de letra,color de fondo que se ocupa en la fila principal para resaltar datos  de una tabla*/
					.nombres_filas { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #9BBB59; }
					/*En este formato  se establece el tamaño,tipo de letra,color de fondo que contendra datos  de una tabla*/
					.renglon_gris { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #E7E7E7; }
					/*En este formato  se establece el tamaño,tipo de letra,color de fondo que contendra datos  de una tabla*/
					.renglon_blanco { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #FFFFFF; } 
					#tabla { position:absolute; left:0px; top:0px; width:1111px; height:175px; z-index:5; }
					.texto_encabezado {font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #0000CC; }
					.sub_encabezado {font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #0000CC; }
					.titulo_tabla {font-family: Arial, Helvetica, sans-serif; font-weight: bold; font-size: 14px; }
					.borde_linea { border-top:3px; border-top-color:#4E6128; border-top-style:solid;}
					.Estilo1 {color: #FFFFFF;	font-weight: bold;}
					-->
				</style>
			</head>											
			<body>
			<div id="tabla">				
				<table width="1100">					
					<tr>
						<td align="left" valign="baseline"><img src="http://<?php echo HOST; ?>/<?php echo SISAD; ?>/images/logo.png" width="118" height="58" 
                        align="absbottom" /></td>
						<td valign="baseline">
							<div align="right"><span class="texto_encabezado">
								<span class="Estilo1">
									MANUAL  DE PROCEDIMIENTOS DE LA CALIDAD</span><br><em>CONCRETO  LANZADO DE FRESNILLO, S.A DE C.V.</em>
								</span>
							</span></div>
						</td>
					</tr>											
					<tr>
						<td colspan="2" align="center" class="borde_linea">
							<span class="sub_encabezado">EL PRESENTE PERMISO PARA TRABAJOS EN ALTURAS, ES DE APLICACI&Oacute;N OBLIGATORIA, PARA TODO EL PERSONAL QUE LABORA DENTRO
							DE LAS INSTALACIONES DE CONCRETO LANZADO SA. DE C.V. INCLUYENDO EL PERSONAL CONTRATISTA, PARA TRABAJOS DE 1.8 METROS DE ALTURA O M&Aacute;S
							SOBRE LA SUPERFICIE DE TRABAJO, DONDE SE TENGA QUE UTILIZAR CUALQUIER TIPO DE ESCALERA, ANDAMIOS O PLATAFORMAS ELEVADAS, INCLUYENDO TRABAJOS
							EN LOS TIROS, CASTILLOS, TOLVAS Y CONTRAPOZOS</span>
						</td>
					</tr>					
					<tr>
						<td colspan="2">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="2">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="2" align="center" class="titulo_tabla">CUALQUIER TRABAJO EN ALTURAS DEBE SER NOTIFICADO A SEGURIDAD</td>
					</tr>
					<tr>
						<td colspan="2" align="center" class="titulo_tabla">AUTORIZACION PARA TRABAJOS QUE SE REALIZAN EN ALTURAS</td>
					</tr>					
					<tr>
						<td colspan="2">&nbsp;</td>
					</tr>			
					<?php
						$nom_clase = "renglon_gris";
						$cont = 1;
					do{	?>	
						<tr>
							<td align='center' class='nombres_filas'>CLAVE DEL PERMISO ALTURAS</td>
							<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datosPA['id_permiso_trab']; ?></td>
						</tr>
						<tr>
							<td align='center' class='nombres_filas'>FECHA GENERACIÓN DEL PERMISO DE ALTURAS</td>
							<td align="center" class="<?php echo $nom_clase; ?>"><?php echo modFecha($datosPA['fecha_ini'],1); ?></td>
						</tr> 
						<tr>
							<td align='center' class='nombres_filas'>NOMBRE Y FIRMA DE LA PERSONA QUE REALIZA EL TRABAJO</td>
							<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datosPA['nom_solicitante']; ?></td>
						</tr>				
						<tr>
							<td align='center' class='nombres_filas'>NOMBRE Y FIRMA DE LA PERSONA QUE AUTORIZA EL TRABAJO</td>
							<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datosPA['nom_responsable']; ?></td>
						</tr>				
						<tr>
							<td align='center' class='nombres_filas'>NOMBRE Y FIRMA DEL LÍDER DEL ÁREA OPERATIVO</td>
							<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datosPA['nom_supervisor']; ?></td>					
						</tr>					
						<tr>	
							<td align='center' class='nombres_filas'>TRABAJO A REALIZAR</td>
							<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datosPA['trabajo_realizar']; ?></td>
						</tr>				
						<tr>						
							<td align='center' class='nombres_filas'>LUGAR</td>
							<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datosPA['lugar_trabajo']; ?></td>
						</tr>												
						<tr>
							<td align='center' class='nombres_filas'>DESCRIPCIÓN DEL TRABAJO</td>
							<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datosPA['descripcion_trabajo']; ?></td>					
						</tr>				
						<tr>
							<td colspan="2" align='center' class='nombres_filas'>¿CUÁLES SON LOS RIESGOS QUE EL COLABORADOR VA ENCONTRAR EN EL DESARROLLO DE SU TRABAJO Y COMO EVITARLOS?</td>	
						</tr>
						<tr>
							<td colspan="2" align="center" class="<?php echo $nom_clase; ?>"><?php echo $datosPA['riesgos_trabajo']; ?></td>																				
						</tr>
						<tr>
							<td colspan="2" align="center" class="titulo_tabla"><?php echo $datosPA2['nom_permiso']; ?></td>
						</tr>
						<tr>
							<td align="center" class="<?php echo $nom_clase; ?>"> 
								<strong> REVISAR:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; NOTA: LA PERSONA DEBE PESAR UN MÍNIMO DE 55 KG. Y MÁXIMO 140 KG. </strong></td>
							<td align="center" class="<?php echo $nom_clase; ?>"> <strong>¿SE CUMPLEN LAS CONDICIONES? </strong></td>
						</tr><?php 
							do{//Se realiza un ciclo para que imprima tantos renglones como sea posible dentro de la tabla?>											
								<tr>
									<td align="left" class="<?php echo $nom_clase; ?>"><?php echo $cont; ?>
									<?php echo" .- ".  $datosPA2['actividad']; ?></td>	
									<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datosPA2['respuesta']; ?></td>	
								</tr> 
						<?php
								$cont++;
							}while($datosPA2=mysql_fetch_array($rs_PA2));?>
						<tr>
							<td colspan="2" align="center" class="<?php echo $nom_clase; ?>"> 
								<strong> EL TRABAJO SE PODRA REALIZAR SOLO SI SE CUMPLEN CON TODOS LOS PUNTOS ANTERIORES</strong></td>
						</tr>
						<tr>		
							<td colspan="2" align="center" class="<?php echo $nom_clase; ?>"> 
								<strong>UNA PERSONA NO DEBE ESTAR SUSPENDIDA POR MÁS DE 15 MIN. DESPUES DE UNA CAÍDA </strong></td>
						</tr><?php																
					//Determinar el color del siguiente renglon a dibujar
					$cont++;
					if($cont%2==0)
						$nom_clase = "renglon_blanco";
					else
						$nom_clase = "renglon_gris";
						
				}while($datosPA=mysql_fetch_array($rs_PA)); 
			?>
			</table>
			</div>
			</body>
			<?php }
		}
		//Cerrar la conexion con la BD		
		mysql_close($conn);		
	}//Fin de la Funcion 
	

	//Esta funcion exporta el REPORTE De permiso de Alturas generados dentro del departamento
	function consultarRepPermisoAlturas($hdn_consulta, $hdn_nomReporte, $hdn_msg){
		//Esribir datos de la pagina en excel
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=$hdn_nomReporte.xls");
			
		//Realizar la conexion a la BD 
		$conn = conecta("bd_seguridad");
		//Ejecutar la sentencia y almacena los datos de la consulta en la variable $rs (result set)
		$rs_TPA= mysql_query($hdn_consulta);
		
		//Verificamos que la consulta haya generado datos	
		if($datos_TPA=mysql_fetch_array($rs_TPA)){
			//Definir el estilo de la tabla y el encabezado que aparecera sobre la tabla?>
			<head>
				<style>					
					<!--
					body { font-family:Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; }
					/*En este formato  se establece el tamaño,tipo de letra,color de fondo que ocupa la columna principal de una tabla*/
					.nombres_columnas { font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #FFFFFF; background-color: #9BBA59; font-weight: bold; 
					border-top-width: medium; border-right-width: thin;
					border-bottom-width: medium; border-left-width: thin; border-top-style: solid; border-right-style: none; border-bottom-style: 
					solid; border-left-style: none; 
					border-top-color: #000000; border-bottom-color: #000000; }
					/*En este formato  se establece el tamaño,tipo de letra,color de fondo que se ocupa en la fila principal para resaltar datos  de una tabla*/
					.nombres_filas { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #9BBB59; }
					/*En este formato  se establece el tamaño,tipo de letra,color de fondo que contendra datos  de una tabla*/
					.renglon_gris { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #E7E7E7; }
					/*En este formato  se establece el tamaño,tipo de letra,color de fondo que contendra datos  de una tabla*/
					.renglon_blanco { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #FFFFFF; } 
					#tabla { position:absolute; left:0px; top:0px; width:1111px; height:175px; z-index:5; }
					.texto_encabezado {font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #0000CC; }
					.sub_encabezado {font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #0000CC; }
					.titulo_tabla {font-family: Arial, Helvetica, sans-serif; font-weight: bold; font-size: 14px; }
					.borde_linea { border-top:3px; border-top-color:#4E6128; border-top-style:solid;}
					.Estilo1 {color: #FFFFFF;	font-weight: bold;}
					-->
				</style>
			</head>											
			<body>
			<div id="tabla">				
				<table width="1100">					
					<tr>
						<td align="left" valign="baseline" colspan="2"><img src="http://<?php echo HOST; ?>/<?php echo SISAD; ?>/images/logo.png" width="118" height="58" 
                        align="absbottom" /></td>
						<td colspan="2">&nbsp;</td>
						<td valign="baseline" colspan="4">
							<div align="right"><span class="texto_encabezado">
								<span class="Estilo1">
									MANUAL  DE PROCEDIMIENTOS DE LA CALIDAD</span><br><em>CONCRETO  LANZADO DE FRESNILLO, S.A DE C.V.</em>
								</span>
							</span></div>
						</td>
					</tr>											
					<tr>
						<td colspan="8" align="center" class="borde_linea">
							<span class="sub_encabezado">CONFIDENCIAL, PROPIEDAD DE &ldquo;CONCRETO LANZADO DE FRESNILLO, S.A DE C.V.&rdquo; PROHIBIDA SU REPRODUCCI
                            &Oacute;N TOTAL O PARCIAL</span>
						</td>
					</tr>					
					<tr>
						<td colspan="8">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="8" align="center" class="titulo_tabla"><?php echo $hdn_msg;?></td>
					</tr>
					<tr>
						<td colspan="8">&nbsp;</td>
					</tr>			
					<tr>
						<td align='center' class='nombres_columnas'>CLAVE PERMISO</td>
						<td align='center' class='nombres_columnas'>NOMBRE DEL TRABAJADOR</td>							
						<td align='center' class='nombres_columnas'>NOMBRE AUTORIZA EL TRABAJO</td>
						<td align='center' class='nombres_columnas'>LÍDER ÁREA OPERATIVA</td>	
						<td align='center' class='nombres_columnas'>TRABAJO A REALIZAR</td>
						<td align='center' class='nombres_columnas'>DESCRIPCION DEL TRABAJO</td>
						<td align='center' class='nombres_columnas'>RIESGOS DEL TRABAJO</td>	
						<td align='center' class='nombres_columnas'>FECHA REGISTRO</td>											
      				</tr>	
			<?php
			$nom_clase = "renglon_gris";
			$cont = 1;
			do{	
				?>
				<tr>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos_TPA['id_permiso_trab']; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos_TPA['nom_solicitante']; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos_TPA['nom_responsable']; ?></td>					
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos_TPA['nom_supervisor']; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos_TPA['trabajo_realizar']; ?></td>					
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos_TPA['descripcion_trabajo']; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos_TPA['riesgos_trabajo']; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo modFecha($datos_TPA['fecha_ini'],1); ?></td>				
				</tr>
				<?php
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
					
			}while($datos_TPA=mysql_fetch_array($rs_TPA)); ?>
			</table>
			</div>
			</body>
			<?php	}
		
		//Cerrar la conexion con la BD		
		mysql_close($conn);		
	}//Fin de la Funcion consultarRepPermisoAlturas($hdn_consulta, $hdn_nomReporte){
	
	
	
	//Esta funcion exporta el REPORTE De permiso de Flama generados dentro del departamento
	function consultarRepPermisoFlama($hdn_consulta, $hdn_nomReporte, $hdn_msg){
		//Esribir datos de la pagina en excel
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=$hdn_nomReporte.xls");
			
		//Realizar la conexion a la BD 
		$conn = conecta("bd_seguridad");
		//Ejecutar la sentencia y almacena los datos de la consulta en la variable $rs (result set)
		$rs_TPF= mysql_query($hdn_consulta);
		
		//Verificamos que la consulta haya generado datos	
		if($datos_TPF=mysql_fetch_array($rs_TPF)){
			//Definir el estilo de la tabla y el encabezado que aparecera sobre la tabla?>
			<head>
				<style>					
					<!--
					body { font-family:Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; }
					/*En este formato  se establece el tamaño,tipo de letra,color de fondo que ocupa la columna principal de una tabla*/
					.nombres_columnas { font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #FFFFFF; background-color: #9BBA59; font-weight: bold; 
					border-top-width: medium; border-right-width: thin;
					border-bottom-width: medium; border-left-width: thin; border-top-style: solid; border-right-style: none; border-bottom-style: 
					solid; border-left-style: none; 
					border-top-color: #000000; border-bottom-color: #000000; }
					/*En este formato  se establece el tamaño,tipo de letra,color de fondo que se ocupa en la fila principal para resaltar datos  de una tabla*/
					.nombres_filas { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #9BBB59; }
					/*En este formato  se establece el tamaño,tipo de letra,color de fondo que contendra datos  de una tabla*/
					.renglon_gris { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #E7E7E7; }
					/*En este formato  se establece el tamaño,tipo de letra,color de fondo que contendra datos  de una tabla*/
					.renglon_blanco { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #FFFFFF; } 
					#tabla { position:absolute; left:0px; top:0px; width:1111px; height:175px; z-index:5; }
					.texto_encabezado {font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #0000CC; }
					.sub_encabezado {font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #0000CC; }
					.titulo_tabla {font-family: Arial, Helvetica, sans-serif; font-weight: bold; font-size: 14px; }
					.borde_linea { border-top:3px; border-top-color:#4E6128; border-top-style:solid;}
					.Estilo1 {color: #FFFFFF;	font-weight: bold;}
					-->
				</style>
			</head>											
			<body>
			<div id="tabla">				
				<table width="1100">					
					<tr>
						<td align="left" valign="baseline" colspan="2"><img src="http://<?php echo HOST; ?>/<?php echo SISAD; ?>/images/logo.png" width="118" height="58" 
                        align="absbottom" /></td>
						<td colspan="3">&nbsp;</td>
						<td valign="baseline" colspan="5">
							<div align="right"><span class="texto_encabezado">
								<<span class="Estilo1">
									MANUAL  DE PROCEDIMIENTOS DE LA CALIDAD</span><br><em>CONCRETO  LANZADO DE FRESNILLO, S.A DE C.V.</em>
								</span>
							</span></div>
						</td>
					</tr>											
					<tr>
						<td colspan="10" align="center" class="borde_linea">
							<span class="sub_encabezado">CONFIDENCIAL, PROPIEDAD DE &ldquo;CONCRETO LANZADO DE FRESNILLO, S.A DE C.V.&rdquo; PROHIBIDA SU REPRODUCCI
                            &Oacute;N TOTAL O PARCIAL</span>
						</td>
					</tr>					
					<tr>
						<td colspan="10">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="10" align="center" class="titulo_tabla"><?php echo $hdn_msg;?></td>
					</tr>
					<tr>
						<td colspan="10">&nbsp;</td>
					</tr>			
					<tr>
						<td align='center' class='nombres_columnas'>CLAVE PERMISO</td>
						<td align='center' class='nombres_columnas'>FOLIO PERMISO</td>							
						<td align='center' class='nombres_columnas'>LUGAR DE TRABAJO</td>
						<td align='center' class='nombres_columnas'>NOMBRE CONTRATISTA</td>	
						<td align='center' class='nombres_columnas'>FECHA REGISTRO</td>
						<td align='center' class='nombres_columnas'>TRABAJO ESPECIFICO</td>
						<td align='center' class='nombres_columnas'>ENCARGADO DEL TRABAJO</td>	
						<td align='center' class='nombres_columnas'>FUNCIONARIO RESPONSABLE</td>	
						<td align='center' class='nombres_columnas'>SUPERVISOR DE LA OBRA</td>											
						<td align='center' class='nombres_columnas'>FECHA EXPIRACIÓN</td>																	
      				</tr>	
			<?php
			$nom_clase = "renglon_gris";
			$cont = 1;
			do{	
				?>
				<tr>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos_TPF['id_permiso_trab']; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos_TPF['folio_permiso']; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos_TPF['lugar_trabajo']; ?></td>					
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos_TPF['nom_contratista']; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo modFecha($datos_TPF['fecha_ini'],1); ?></td>				
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos_TPF['trabajo_especifico']; ?></td>					
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos_TPF['firma_responsable']; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos_TPF['funcionario_res']; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos_TPF['supervisor_obra']; ?></td>			
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo modFecha($datos_TPF['fecha_expiracion'],1); ?><?php echo $datos_TPF['hora_expiracion']; ?></td>				
				</tr>
				<?php
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
					
			}while($datos_TPF=mysql_fetch_array($rs_TPF)); ?>
			</table>
			</div>
			</body>
			<?php	}
		
		//Cerrar la conexion con la BD		
		mysql_close($conn);		
	}//Fin de la Funcion consultarRepPermisoFlama
	

	//Esta funcion exporta el REPORTE De permiso de Flama generados dentro del departamento
	function consultarRepPermisoPeligroso($hdn_consulta, $hdn_nomReporte, $hdn_msg){
		//Esribir datos de la pagina en excel
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=$hdn_nomReporte.xls");
			
		//Realizar la conexion a la BD 
		$conn = conecta("bd_seguridad");
		//Ejecutar la sentencia y almacena los datos de la consulta en la variable $rs (result set)
		$rs_PTP= mysql_query($hdn_consulta);

		//Verificamos que la consulta haya generado datos	
		if($datos_PTP=mysql_fetch_array($rs_PTP)){
			//Definir el estilo de la tabla y el encabezado que aparecera sobre la tabla?>
			<head>
				<style>					
					<!--
					body { font-family:Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; }
					/*En este formato  se establece el tamaño,tipo de letra,color de fondo que ocupa la columna principal de una tabla*/
					.nombres_columnas { font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #FFFFFF; background-color: #9BBA59; font-weight: bold; 
					border-top-width: medium; border-right-width: thin;
					border-bottom-width: medium; border-left-width: thin; border-top-style: solid; border-right-style: none; border-bottom-style: 
					solid; border-left-style: none; 
					border-top-color: #000000; border-bottom-color: #000000; }
					/*En este formato  se establece el tamaño,tipo de letra,color de fondo que se ocupa en la fila principal para resaltar datos  de una tabla*/
					.nombres_filas { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #9BBB59; }
					/*En este formato  se establece el tamaño,tipo de letra,color de fondo que contendra datos  de una tabla*/
					.renglon_gris { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #E7E7E7; }
					/*En este formato  se establece el tamaño,tipo de letra,color de fondo que contendra datos  de una tabla*/
					.renglon_blanco { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #FFFFFF; } 
					#tabla { position:absolute; left:0px; top:0px; width:1111px; height:175px; z-index:5; }
					.texto_encabezado {font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #0000CC; }
					.sub_encabezado {font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #0000CC; }
					.titulo_tabla {font-family: Arial, Helvetica, sans-serif; font-weight: bold; font-size: 14px; }
					.borde_linea { border-top:3px; border-top-color:#4E6128; border-top-style:solid;}
					.Estilo1 {color: #FFFFFF;	font-weight: bold;}
					-->
				</style>
			</head>											
			<body>
			<div id="tabla">				
				<table width="1100">					
					<tr>
						<td align="left" valign="baseline" colspan="2"><img src="http://<?php echo HOST; ?>/<?php echo SISAD; ?>/images/logo.png" width="118" height="58" 
                        align="absbottom" /></td>
						<td colspan="7">&nbsp;</td>
						<td valign="baseline" colspan="10">
							<div align="right"><span class="texto_encabezado">
								<span class="Estilo1">
									MANUAL  DE PROCEDIMIENTOS DE LA CALIDAD</span><br><em>CONCRETO  LANZADO DE FRESNILLO, S.A DE C.V.</em>
								</span>
							</span></div>
						</td>
					</tr>											
					<tr>
						<td colspan="19" align="center" class="borde_linea">
							<span class="sub_encabezado">CONFIDENCIAL, PROPIEDAD DE &ldquo;CONCRETO LANZADO DE FRESNILLO, S.A DE C.V.&rdquo; PROHIBIDA SU REPRODUCCI
                            &Oacute;N TOTAL O PARCIAL</span>
						</td>
					</tr>					
					<tr>
						<td colspan="19">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="19" align="center" class="titulo_tabla"><?php echo $hdn_msg;?></td>
					</tr>
					<tr>
						<td colspan="19">&nbsp;</td>
					</tr>			
					<tr>
						<td align='center' class='nombres_columnas'>CLAVE PERMISO</td>
						<td align='center' class='nombres_columnas'>TIPO PERMISO</td>							
						<td align='center' class='nombres_columnas'>NOMBRE SOLICITANTE</td>
						<td align='center' class='nombres_columnas'>NOMBRE SUPERVISOR</td>	
						<td align='center' class='nombres_columnas'>NOMBRE RESPONSABLE</td>
						<td align='center' class='nombres_columnas'>NOMBRE CONTRATISTA</td>
						<td align='center' class='nombres_columnas'>DESCRIPCIÓN DEL TRABAJO</td>	
						<td align='center' class='nombres_columnas'>TRABAJO A REALIZAR</td>	
						<td align='center' class='nombres_columnas'>PERIODO DE INICIO</td>											
						<td align='center' class='nombres_columnas'>PERIODO DE FIN</td>																	
						<td align='center' class='nombres_columnas'>HORARIO DE INICIO</td>																	
						<td align='center' class='nombres_columnas'>HORARIO DE FIN</td>																	
						<td align='center' class='nombres_columnas'>TRABAJO ESPECIFICO</td>																	
						<td align='center' class='nombres_columnas'>FIRMA RESPONSABLE</td>																	
						<td align='center' class='nombres_columnas'>FUNCIONARIO RESPONSABLE</td>																	
						<td align='center' class='nombres_columnas'>SUPERVISOR</td>																																									
						<td align='center' class='nombres_columnas'>SUPERVISOR DE LA OBRA</td>																							
						<td align='center' class='nombres_columnas'>OPERADOR</td>																							
						<td align='center' class='nombres_columnas'>FIRMA ACEPTACION</td>																							
      				</tr>				
			<?php
			$nom_clase = "renglon_gris";
			$cont = 1;
			do{	
				?>
				<tr>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos_PTP['id_permiso_trab']; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos_PTP['nom_permiso']; ?></td>					
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos_PTP['nom_solicitante']; ?></td>					
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos_PTP['nom_supervisor']; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos_PTP['nom_responsable']; ?></td>					
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos_PTP['nom_contratista']; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos_PTP['descripcion_trabajo']; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos_PTP['trabajo_realizar']; ?></td>	
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo modFecha($datos_PTP['fecha_ini'],1); ?></td>				
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo modFecha($datos_PTP['fecha_fin'],1); ?></td>				
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos_PTP['horario_ini']; ?><?php echo $datos_PTP['meridiano_ini']; ?></td>	
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos_PTP['horario_fin']; ?><?php echo $datos_PTP['meridiano_fin']; ?></td>	
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos_PTP['trabajo_especifico']; ?></td>	
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos_PTP['firma_responsable']; ?></td>	
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos_PTP['funcionario_res']; ?></td>	
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos_PTP['supervisor']; ?></td>	
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos_PTP['supervisor_obra']; ?></td>	
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos_PTP['operador']; ?></td>	
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos_PTP['aceptacion']; ?></td>									
				</tr>
				<?php
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
					
			}while($datos_PTP=mysql_fetch_array($rs_PTP)); ?>
			</table>
			</div>
			</body>
			<?php	}
		
		//Cerrar la conexion con la BD		
		mysql_close($conn);		
	}//Fin de la Funcion consultarRepPermisoFlama

	//Esta funcion exporta el REPORTE de Plan de Contingencia que solo se encuentran planeados y aun no se ejecutan
	function consultarRepPlanContingencia($hdn_consulta, $hdn_nomReporte, $hdn_msg){
		//Esribir datos de la pagina en excel
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=$hdn_nomReporte.xls");
			
		//Realizar la conexion a la BD 
		$conn = conecta("bd_seguridad");
		//Ejecutar la sentencia y almacena los datos de la consulta en la variable $rs (result set)
		$rs_PC= mysql_query($hdn_consulta);
		
		//Verificamos que la consulta haya generado datos	
		if($datos_PC=mysql_fetch_array($rs_PC)){
			//Definir el estilo de la tabla y el encabezado que aparecera sobre la tabla?>
			<head>
				<style>					
					<!--
					body { font-family:Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; }
					/*En este formato  se establece el tamaño,tipo de letra,color de fondo que ocupa la columna principal de una tabla*/
					.nombres_columnas { font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #FFFFFF; background-color: #9BBA59; font-weight: bold; 
					border-top-width: medium; border-right-width: thin;
					border-bottom-width: medium; border-left-width: thin; border-top-style: solid; border-right-style: none; border-bottom-style: 
					solid; border-left-style: none; 
					border-top-color: #000000; border-bottom-color: #000000; }
					/*En este formato  se establece el tamaño,tipo de letra,color de fondo que se ocupa en la fila principal para resaltar datos  de una tabla*/
					.nombres_filas { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #9BBB59; }
					/*En este formato  se establece el tamaño,tipo de letra,color de fondo que contendra datos  de una tabla*/
					.renglon_gris { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #E7E7E7; }
					/*En este formato  se establece el tamaño,tipo de letra,color de fondo que contendra datos  de una tabla*/
					.renglon_blanco { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #FFFFFF; } 
					#tabla { position:absolute; left:0px; top:0px; width:1111px; height:175px; z-index:5; }
					.texto_encabezado {font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #0000CC; }
					.sub_encabezado {font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #0000CC; }
					.titulo_tabla {font-family: Arial, Helvetica, sans-serif; font-weight: bold; font-size: 14px; }
					.borde_linea { border-top:3px; border-top-color:#4E6128; border-top-style:solid;}
					.Estilo1 {color: #FFFFFF;	font-weight: bold;}
					-->
				</style>
			</head>											
			<body>
			<div id="tabla">				
				<table width="1100">					
					<tr>
						<td align="left" valign="baseline" colspan="2"><img src="http://<?php echo HOST; ?>/<?php echo SISAD; ?>/images/logo.png" width="118" height="58" 
                        align="absbottom" /></td>
						<td colspan="3">&nbsp;</td>
						<td valign="baseline" colspan="3">
							<div align="right"><span class="texto_encabezado">
								<span class="Estilo1">
									MANUAL  DE PROCEDIMIENTOS DE LA CALIDAD</span><br><em>CONCRETO  LANZADO DE FRESNILLO, S.A DE C.V.</em>
								</span>
							</span></div>
						</td>
					</tr>											
					<tr>
						<td colspan="8" align="center" class="borde_linea">
							<span class="sub_encabezado">CONFIDENCIAL, PROPIEDAD DE &ldquo;CONCRETO LANZADO DE FRESNILLO, S.A DE C.V.&rdquo; PROHIBIDA SU REPRODUCCI
                            &Oacute;N TOTAL O PARCIAL</span>
						</td>
					</tr>					
					<tr>
						<td colspan="8">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="8" align="center" class="titulo_tabla"><?php echo $hdn_msg; ?></td>
					</tr>
					<tr>
						<td colspan="8">&nbsp;</td>
					</tr>			
					<tr>
						<td align='center' class='nombres_columnas'>CLAVE PLAN</td>
						<td align='center' class='nombres_columnas'>RESPONSABLE PLAN</td>							
						<td align='center' class='nombres_columnas'>&Aacute;REA</td>
						<td align='center' class='nombres_columnas'>LUGAR</td>	
						<td align='center' class='nombres_columnas'>NOMBRE SIMULACRO</td>
						<td align='center' class='nombres_columnas'>TIPO SIMULACRO</td>
						<td align='center' class='nombres_columnas'>FECHA REGISTRO</td>	
						<td align='center' class='nombres_columnas'>FECHA PROGRAMADA A REALIZAR</td>																																		
      				</tr>				
			<?php
			$nom_clase = "renglon_gris";
			$cont = 1;
			do{
				?>
				<tr>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos_PC['id_plan']; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos_PC['responsable']; ?></td>					
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos_PC['area']; ?></td>					
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos_PC['lugar']; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos_PC['nom_simulacro']; ?></td>					
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos_PC['tipo_simulacro']; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo modFecha($datos_PC['fecha_reg'],1); ?></td>				
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo modFecha($datos_PC['fecha_programada'],1); ?></td>				
				</tr>
				<?php
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
					
			}while($datos_PC=mysql_fetch_array($rs_PC)); ?>
			</table>
			</div>
			</body>
			<?php	}
		
		//Cerrar la conexion con la BD		
		mysql_close($conn);		
	}//Fin de la Funcion consultarRepPlanContingencia


	//Funcion que nos permite conocer el numero de fotografias almacenadas
	function contarImagenes($consulta){
		//Realizar la conexion a la BD 
		$conn = conecta("bd_seguridad");
		//Ejecutar consulta
		$rs=mysql_query($consulta);
		//Contador que nos permite almacenar temporalmente el valor del contador
		$contAux=0;
		//Comprobamos la existencia de los datos en la cosnulta principal
		if($datos=mysql_fetch_array($rs)){
			//Ciclo que nos permite verificar los datos que vienen en cada plan de contingencia
			do{
				//Crear consulta para conocer el nuemro de fotos que se agregaron al plan de contingencia
				$sql_stm_foto = "SELECT mime1, mime2, mime3, mime4, mime5 FROM tiempos_simulacro WHERE planes_contingencia_id_plan = '$datos[id_plan]'";
				//Ejecutar la sentencia y almacena los datos de la consulta en la variable $rs (result set)
				$rs_foto= mysql_query($sql_stm_foto);
				//Variable que nos permitira almacenar el numero de imagenes
				$cont_interno = 0;
				//Verificamos que la consulta haya generado datos
				if($datos_foto=mysql_fetch_array($rs_foto)){
					//Ciclo que nos permitira recorrer todos los campos mime para verificar que tengan cargada una imagen (Registro Actual)
					do{
						//Ciclo que nos permite recorrer cada uno; ya que se encuentran en un solo registro
						for($i=1; $i<=5; $i++){
							//Comprobamos que el contenido del mime sea diferente a vacio
							if($datos_foto["mime".$i]!=""){
								//Si es asi incrementamos el contador para saber el numero de columnas
								$cont_interno++;
							}
							//VErificamos que el contenido del aux sea diferente; el contador auxiliar nos permitira almacenar el ultimo valor guardado
							//y el contador interno tiene el valor actual
							if($cont_interno>=$contAux){
								//Guaramos el valor del contador interno en el contador auxiliar
								$contAux=$cont_interno;
							}						
						}
					}while($datos_foto=mysql_fetch_array($rs_foto));
				}
			}while($datos=mysql_fetch_array($rs));
			
		}
		//REtornamos el numero de fotos y lo multiplicamos por 3;ya que cada imagen en el Excel necesita de 3 columnas
		return $contAux*3;
	}
	
	
	/*SECCIÓN QUE PERMITE GENERAR EL REPORTE DE EXCEL AL MOMENTO DE CONSULTAR LA INFORMACION EN GENERAL*/
	
	//Funcion que nos permite obntener la clave del permiso
	function obtenerClavePermisoSeg($clavePermisoSeg){
	//Importamos archivo para realizar la conexion con la BD
		include_once("../../includes/conexion.inc");
		
		//Esribir datos de la pagina en excel
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=$clavePermisoSeg.xls");
	
		//Realizar la conexion a la BD de Seguridad
		$conn = conecta("bd_seguridad");
	
			
				//Crear la sentencia para obtener la Clave del permiso de acuerdo al registro seleccionado
				$stm_sql = "SELECT * FROM permisos_trabajos JOIN permisos_secundarios ON id_permiso_secundario = permisos_secundarios_id_permiso_secundario 
				WHERE id_permiso_trab = '$clavePermisoSeg'";
				
				$stm_sql2 = "SELECT  pasos_permiso.permisos_secundarios_id_permiso_secundario, actividad
								FROM permisos_trabajos JOIN pasos_permiso 
								ON pasos_permiso.permisos_secundarios_id_permiso_secundario = permisos_trabajos.permisos_secundarios_id_permiso_secundario
								WHERE id_permiso_trab = '$clavePermisoSeg'";	
				
				/*Ejecutar las sentencias creadas anteriormente, la primera me trae la clave del permiso seleccionado y la 
					segunda trae los pasos que corresponden a dicho permiso*/
				$rs_datos = mysql_query($stm_sql);
				$rs_datos2 = mysql_query($stm_sql2);
				
				if($datos=mysql_fetch_array($rs_datos)){
					if($datos2=mysql_fetch_array($rs_datos2)){
					
					//Definir el estilo de la tabla y el encabezado que aparecera sobre la tabla?>
					<head>
						<style>					
							<!--
							body { font-family:Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; }
							/*En este formato  se establece el tamaño,tipo de letra,color de fondo que ocupa la columna principal de una tabla*/
							.nombres_columnas { font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #FFFFFF; background-color: #9BBA59; font-weight: bold; 
							border-top-width: medium; border-right-width: thin;
							border-bottom-width: medium; border-left-width: thin; border-top-style: solid; border-right-style: none; border-bottom-style: 
							solid; border-left-style: none; 
							border-top-color: #000000; border-bottom-color: #000000; }
							/*En este formato  se establece el tamaño,tipo de letra,color de fondo que se ocupa en la fila principal para resaltar datos  de una tabla*/
							.nombres_filas { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #9BBB59; }
							/*En este formato  se establece el tamaño,tipo de letra,color de fondo que contendra datos  de una tabla*/
							.renglon_gris { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #E7E7E7; }
							/*En este formato  se establece el tamaño,tipo de letra,color de fondo que contendra datos  de una tabla*/
							.renglon_blanco { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #FFFFFF; } 
							#tabla { position:absolute; left:0px; top:0px; width:1111px; height:175px; z-index:5; }
							.texto_encabezado {font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #0000CC; }
							.sub_encabezado {font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #0000CC; }
							.titulo_tabla {font-family: Arial, Helvetica, sans-serif; font-weight: bold; font-size: 14px; }
							.borde_linea { border-top:3px; border-top-color:#4E6128; border-top-style:solid;}
							.Estilo1 {color: #FFFFFF;	font-weight: bold;}
							-->
						</style>
					</head>											
					<body>
					<div id="tabla">				
						<table width="1100">					
							<tr>
								<td align="left" valign="baseline"><img src="http://<?php echo HOST; ?>/<?php echo SISAD; ?>/images/logo.png" width="118" height="58" 
								align="absbottom" /></td>
								<td valign="baseline">
									<div align="right"><span class="texto_encabezado">
										<span class="Estilo1">
											MANUAL  DE PROCEDIMIENTOS DE LA CALIDAD</span><br><em>CONCRETO  LANZADO DE FRESNILLO, S.A DE C.V.</em>
										</span>
									</span></div>
								</td>
							</tr>											
							<tr>
								<td colspan="2" align="center" class="borde_linea">
									<span class="sub_encabezado">CONFIDENCIAL, PROPIEDAD DE &ldquo;CONCRETO LANZADO DE FRESNILLO, S.A DE C.V.&rdquo; PROHIBIDA SU REPRODUCCI
									&Oacute;N TOTAL O PARCIAL</span>
								</td>
							</tr>					
							<tr>
								<td colspan="2">&nbsp;</td>
							</tr>
							<tr>
								<td colspan="2">&nbsp;</td>
							</tr>
							<tr>
								<td colspan="2" align="center" class="titulo_tabla">PERMISOS GENERALES</td>
							</tr>					
							<tr>
								<td colspan="2">&nbsp;</td>
							</tr>			
							<?php
								$nom_clase = "renglon_gris";
								$cont = 1;
								$cont2 =1;
							do{	?>	
								<tr>
									<td align='center' class='nombres_filas'>CLAVE DEL PERMISO </td>
									<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['id_permiso_trab']; ?></td>
								</tr>
								<tr>
									<td align='center'
									 class='nombres_filas'>NOMBRE DEL SOLICITANTE(RESPONSABLE POR LA OBRA POR PARTE DE CONCRETO LANZADO DE FRESNILLO S.A DE C.V.)</td>
									<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['nom_solicitante']; ?></td>
								</tr>				
								<tr>
									<td align='center' class='nombres_filas'>NOMBRE DEL SUPERVISOR A CARGO DE LA OBRA</td>
									<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['nom_supervisor']; ?></td>
								</tr>				
								<tr>
									<td align='center' class='nombres_filas'>NOMBRE DEL RESPONSABLE DE LA OBRA POR PARTE DE LA CIA. CONTRATISTA</td>
									<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['nom_responsable']; ?></td>					
								</tr>					
								<tr>	
									<td align='center' class='nombres_filas'>NOMBRE DE LA CIA. CONTRATISTA</td>
									<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['nom_contratista']; ?></td>
								</tr>				
								<tr>						
									<td align='center' class='nombres_filas'>DESCRIPCION DEL TRABAJO</td>
									<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['descripcion_trabajo']; ?></td>
								</tr>												
								<tr>
									<td align='center' class='nombres_filas'>INDIQUE EL TRABAJO PELIGROSO QUE SE REALIZARA</td>
									<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['trabajo_realizar']; ?></td>					
								</tr>				
								<tr>
									<td align='center' class='nombres_filas'>PERIODO DE INICIO DE LA OBRA</td>	
									<td align="center" class="<?php echo $nom_clase; ?>"><?php echo modFecha($datos['fecha_ini'],1); ?></td>																				
								</tr>
								<tr>
									<td align='center' class='nombres_filas'>PERIODO FIN DE LA OBRA</td>	
									<td align="center" class="<?php echo $nom_clase; ?>"><?php echo modFecha($datos['fecha_fin'],1); ?></td>															
								</tr>
								<tr>
									<td align='center' class='nombres_filas'>HORARIO DE INICIO DE LA OBRA</td>	
									<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['horario_ini'];?>&nbsp;<?php echo $datos['meridiano_ini']; ?></td>
								</tr>
								<tr>
									<td align='center' class='nombres_filas'>HORARIO FIN DE LA OBRA</td>	
									<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['horario_fin'];?>&nbsp;<?php echo $datos['meridiano_fin']; ?></td>					
								</tr>				
								<tr>
									<td align='center' class='nombres_filas'>TIPO DE TRABAJO QUE SE REALIZARA</td>	
									<td align='center' class='nombres_filas'>INDIQUE EL TRABAJO ESPECIFICO QUE SE AUTORIZA</td>													
								</tr>
								<tr>
									<td align="center" class="<?php echo $nom_clase; ?>"><strong><?php echo $datos['nom_permiso']; ?></strong></td>	
									<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['trabajo_especifico']; ?></td>	
								</tr>
								<tr>
									<td colspan="2" align="center" class="titulo_tabla">REGLAMENTO DE SEGURIDAD QUE DEBE DE CUMPLIR EL CONTRATISTA AL REALIZAR EL TRABAJO</td>
								</tr>
								<tr>
									<td colspan="2" align="center" class="titulo_tabla"><?php echo $datos['nom_permiso']; ?></td>	
								</tr><?php 
									do{
									//Se realiza un ciclo para que imprima tantos renglones como sea posible dentro de la tabla?>											
										<tr>
											<td colspan="2" align="left" class="<?php echo $nom_clase; ?>"><?php echo $cont; ?>
											<?php echo" .- ".  $datos2['actividad']; ?></td>	
										</tr><?php 
										$cont++;
									}while($datos2=mysql_fetch_array($rs_datos2));
								
									//Crear la sentencia que traera cono resultado las medidas de CARACTER GENERAL que se van a ostrar cualquiera que este sea el reporte
									$sql_stm_mcg = "SELECT nom_permiso, actividad FROM pasos_permiso JOIN permisos_secundarios
										 ON id_permiso_secundario = permisos_secundarios_id_permiso_secundario WHERE nom_permiso = 'MEDIDAS CARACTER GENERAL'";
													
									//Ejecutar la sentencia previamente creada
									$rs_mcg = mysql_query($sql_stm_mcg);	
																																
										//Confirmar que la consulta de datos fue realizada con exito.
										if($datos_mcg=mysql_fetch_array($rs_mcg)){?>
											<tr>
												<td colspan="2" align="center" class="titulo_tabla"><?php echo $datos_mcg['nom_permiso']; ?></td>	
											</tr>
											<?php do{?>
													<tr>
														<td colspan="2" align="left" class="<?php echo $nom_clase; ?>"><?php echo $cont2;?>
														<?php echo" .- ".$datos_mcg['actividad'];?></td>	
													</tr><?php
													$cont2++;
												}while($datos_mcg=mysql_fetch_array($rs_mcg));	
										 }?>
							<tr>
								<td align='center' class='nombres_filas'>NOMBRE DEL RESPONSABLE </td>
								<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['firma_responsable']; ?></td>					
							</tr>	
							<tr>
								<td align='center' class='nombres_filas'><strong>FIRMA </strong></td>
								<td align="center" class="<?php echo $nom_clase; ?>">____________________________</td>					
							</tr>						
							<tr>
								<td align='center' class='nombres_filas'>FUNCIONARIO RESPONSABLE</td>
								<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['funcionario_res']; ?></td>					
							</tr>	
							<tr>
								<td align='center' class='nombres_filas'>NOMBRE SUPERVISOR</td>
								<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['supervisor']; ?></td>					
							</tr>	
							<tr>
								<td align='center' class='nombres_filas'>NOMBRE OPERADOR</td>
								<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['operador']; ?></td>					
							</tr>										
							<tr>
								<td align='center' class='nombres_filas'>SUPERVISOR OBRA CONTRATISTA</td>
								<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['supervisor_obra']; ?></td>					
							</tr>
							<tr>
								<td align='center' class='nombres_filas'><strong>ACEPTACI&Oacute;N</strong></td>
								<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['aceptacion']; ?></td>					
							</tr>	
							<tr>
								<td align='center' class='nombres_filas'><strong>ACEPTO CUMPLIR TOTALMENTE ESTAS MEDIDAS DE SEGURIDAD</strong></td>
								<td align="center" class="<?php echo $nom_clase; ?>">____________________________</td>					
							</tr><?php 																		
							//Determinar el color del siguiente renglon a dibujar
							$cont++;
							if($cont%2==0)
								$nom_clase = "renglon_blanco";
							else
								$nom_clase = "renglon_gris";
								
						}while($datos=mysql_fetch_array($rs_datos)); 
					?>
					</table>
					</div>
					</body>
					<?php }
				}
		//Cerrar la conexion con la BD		
			mysql_close($conn);
				
		}//Fin de la Funcion obtenerId()





//Funcion que nos permite obntener la clave del permiso
	function obtenerClavePermisoSegAlturas($clavePermisoSegAlturas){
	//Importamos archivo para realizar la conexion con la BD
		include_once("../../includes/conexion.inc");
		
		//Esribir datos de la pagina en excel
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=$clavePermisoSegAlturas.xls");
	
		//Realizar la conexion a la BD 
		$conn = conecta("bd_seguridad");
		
		$stm_sql = "SELECT id_permiso_trab, tipo_permiso, lugar_trabajo, riesgos_trabajo, nom_solicitante, nom_supervisor, 
			nom_responsable, descripcion_trabajo, trabajo_realizar, fecha_ini FROM permisos_trabajos WHERE id_permiso_trab = '$clavePermisoSegAlturas'";
			
		$stm_sqlCS = "SELECT num_actividad, respuesta, actividad, nom_permiso FROM revision_cs  JOIN permisos_secundarios 
			ON permisos_secundarios_id_permiso_secundario = id_permiso_secundario WHERE permisos_trabajos_id_permiso_trab = '$clavePermisoSegAlturas'";
		
		//Ejecutar la sentencia y almacena los datos de la consulta en la variable $rs (result set)
		$rs_PA = mysql_query($stm_sql);

		$rs_PA2 = mysql_query($stm_sqlCS);

		//Verificamos que la consulta haya generado datos	
		if($datosPA=mysql_fetch_array($rs_PA)){
			
			if($datosPA2=mysql_fetch_array($rs_PA2)){

			//Definir el estilo de la tabla y el encabezado que aparecera sobre la tabla?>
			<head>
				<style>					
					<!--
					body { font-family:Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; }
					/*En este formato  se establece el tamaño,tipo de letra,color de fondo que ocupa la columna principal de una tabla*/
					.nombres_columnas { font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #FFFFFF; background-color: #9BBA59; font-weight: bold; 
					border-top-width: medium; border-right-width: thin;
					border-bottom-width: medium; border-left-width: thin; border-top-style: solid; border-right-style: none; border-bottom-style: 
					solid; border-left-style: none; 
					border-top-color: #000000; border-bottom-color: #000000; }
					/*En este formato  se establece el tamaño,tipo de letra,color de fondo que se ocupa en la fila principal para resaltar datos  de una tabla*/
					.nombres_filas { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #9BBB59; }
					/*En este formato  se establece el tamaño,tipo de letra,color de fondo que contendra datos  de una tabla*/
					.renglon_gris { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #E7E7E7; }
					/*En este formato  se establece el tamaño,tipo de letra,color de fondo que contendra datos  de una tabla*/
					.renglon_blanco { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #FFFFFF; } 
					#tabla { position:absolute; left:0px; top:0px; width:1111px; height:175px; z-index:5; }
					.texto_encabezado {font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #0000CC; }
					.sub_encabezado {font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #0000CC; }
					.titulo_tabla {font-family: Arial, Helvetica, sans-serif; font-weight: bold; font-size: 14px; }
					.borde_linea { border-top:3px; border-top-color:#4E6128; border-top-style:solid;}
					.Estilo1 {color: #FFFFFF;	font-weight: bold;}
					-->
				</style>
			</head>											
			<body>
			<div id="tabla">				
				<table width="1100">					
					<tr>
						<td align="left" valign="baseline"><img src="http://<?php echo HOST; ?>/<?php echo SISAD; ?>/images/logo.png" width="118" height="58" 
                        align="absbottom" /></td>				

						<td valign="baseline">
							<div align="right"><span class="texto_encabezado">
								<span class="Estilo1">
									MANUAL  DE PROCEDIMIENTOS DE LA CALIDAD</span><br><em>CONCRETO  LANZADO DE FRESNILLO, S.A DE C.V.</em>
								</span>
							</span></div>
						</td>
					</tr>											
					<tr>
						<td colspan="2" align="center" class="borde_linea">
							<span class="sub_encabezado">EL PRESENTE PERMISO PARA TRABAJOS EN ALTURAS, ES DE APLICACI&Oacute;N OBLIGATORIA, PARA TODO EL PERSONAL QUE LABORA DENTRO
							DE LAS INSTALACIONES DE CONCRETO LANZADO SA. DE C.V. INCLUYENDO EL PERSONAL CONTRATISTA, PARA TRABAJOS DE 1.8 METROS DE ALTURA O M&Aacute;S
							SOBRE LA SUPERFICIE DE TRABAJO, DONDE SE TENGA QUE UTILIZAR CUALQUIER TIPO DE ESCALERA, ANDAMIOS O PLATAFORMAS ELEVADAS, INCLUYENDO TRABAJOS
							EN LOS TIROS, CASTILLOS, TOLVAS Y CONTRAPOZOS</span>
						</td>
					</tr>					
					<tr>
						<td colspan="2">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="2">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="2" align="center" class="titulo_tabla">CUALQUIER TRABAJO EN ALTURAS DEBE SER NOTIFICADO A SEGURIDAD</td>
					</tr>
					<tr>
						<td colspan="2" align="center" class="titulo_tabla">AUTORIZACION PARA TRABAJOS QUE SE REALIZAN EN ALTURAS</td>
					</tr>					
					<tr>
						<td colspan="2">&nbsp;</td>
					</tr>			
					<?php
						$nom_clase = "renglon_gris";
						$cont = 1;
					do{	?>	
						<tr>
							<td align='center' class='nombres_filas'>CLAVE DEL PERMISO ALTURAS</td>
							<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datosPA['id_permiso_trab']; ?></td>
						</tr>
						<tr>
							<td align='center' class='nombres_filas'>FECHA GENERACIÓN DEL PERMISO DE ALTURAS</td>
							<td align="center" class="<?php echo $nom_clase; ?>"><?php echo modFecha($datosPA['fecha_ini'],1); ?></td>
						</tr>
						<tr>
							<td align='center' class='nombres_filas'>NOMBRE Y FIRMA DE LA PERSONA QUE REALIZA EL TRABAJO</td>
							<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datosPA['nom_solicitante']; ?></td>
						</tr>				
						<tr>
							<td align='center' class='nombres_filas'>NOMBRE Y FIRMA DE LA PERSONA QUE AUTORIZA EL TRABAJO</td>
							<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datosPA['nom_responsable']; ?></td>
						</tr>				
						<tr>
							<td align='center' class='nombres_filas'>NOMBRE Y FIRMA DEL LÍDER DEL ÁREA OPERATIVO</td>
							<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datosPA['nom_supervisor']; ?></td>					
						</tr>					
						<tr>	
							<td align='center' class='nombres_filas'>TRABAJO A REALIZAR</td>
							<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datosPA['trabajo_realizar']; ?></td>
						</tr>				
						<tr>						
							<td align='center' class='nombres_filas'>LUGAR</td>
							<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datosPA['lugar_trabajo']; ?></td>
						</tr>												
						<tr>
							<td align='center' class='nombres_filas'>DESCRIPCIÓN DEL TRABAJO</td>
							<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datosPA['descripcion_trabajo']; ?></td>					
						</tr>				
						<tr>
							<td colspan="2" align='center' class='nombres_filas'>¿CUÁLES SON LOS RIESGOS QUE EL COLABORADOR VA ENCONTRAR EN EL DESARROLLO DE SU TRABAJO Y COMO EVITARLOS?</td>	
						</tr>
						<tr>
							<td colspan="2" align="center" class="<?php echo $nom_clase; ?>"><?php echo $datosPA['riesgos_trabajo']; ?></td>																				
						</tr>
						<tr>
							<td colspan="2" align="center" class="titulo_tabla"><?php echo $datosPA2['nom_permiso']; ?></td>
						</tr>
						<tr>
							<td align="center" class="<?php echo $nom_clase; ?>"> 
								<strong> REVISAR:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; NOTA: LA PERSONA DEBE PESAR UN MÍNIMO DE 55 KG. Y MÁXIMO 140 KG. </strong></td>
							<td align="center" class="<?php echo $nom_clase; ?>"> <strong>¿SE CUMPLEN LAS CONDICIONES? </strong></td>
						</tr><?php 
							do{//Se realiza un ciclo para que imprima tantos renglones como sea posible dentro de la tabla?>											
								<tr>
									<td align="left" class="<?php echo $nom_clase; ?>"><?php echo $cont; ?>
									<?php echo" .- ".  $datosPA2['actividad']; ?></td>	
									<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datosPA2['respuesta']; ?></td>	
								</tr> 
						<?php
								$cont++;
							}while($datosPA2=mysql_fetch_array($rs_PA2));?>
						<tr>
							<td colspan="2" align="center" class="<?php echo $nom_clase; ?>"> 
								<strong> EL TRABAJO SE PODRA REALIZAR SOLO SI SE CUMPLEN CON TODOS LOS PUNTOS ANTERIORES</strong></td>
						</tr>
						<tr>		
							<td colspan="2" align="center" class="<?php echo $nom_clase; ?>"> 
								<strong>UNA PERSONA NO DEBE ESTAR SUSPENDIDA POR MÁS DE 15 MIN. DESPUES DE UNA CAÍDA </strong></td>
						</tr><?php																
					//Determinar el color del siguiente renglon a dibujar
					$cont++;
					if($cont%2==0)
						$nom_clase = "renglon_blanco";
					else
						$nom_clase = "renglon_gris";
						
				}while($datosPA=mysql_fetch_array($rs_PA)); 
			?>
			</table>
			</div>
			</body>
			<?php }
		}
		//Cerrar la conexion con la BD		
		mysql_close($conn);		
	}//Fin de la Funcion 
	
?>