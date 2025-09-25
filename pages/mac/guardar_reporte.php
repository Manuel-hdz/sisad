<?php 
	/**
	  * Nombre del Módulo: Mantenimiento                                               
	  * Nombre Programador: Nadia Madahí López Hernández
	  * Fecha: 24/Febrero/2011                                      			
	  * Descripción: Este archivo contiene funciones para almacenar la información en una hoja de calculo de excel de las consultas realizadas y reportes generados como lo son:
	  *			 1. Reporte de Mantenimientos Preventivos
	  *			 2. Reporte de Mantenimientos Correctivos
	  *			 3. Reporte de Orden de Trabajo
	  **/
	 
	 
	 /**
      * Listado del contenido del programa                                            
      *   Includes: 
	        1. Modulo de conexion con la base de datos
			2. Modulo de operaciones con la BD*/
			include("../../includes/conexion.inc");
			include("../../includes/op_operacionesBD.php");
			include("../../includes/func_fechas.php");
			
	/**   Código en: pages\man\guardar_reporte.php                                   
      **/
	
	  			
	if(isset($_POST['hdn_consulta'])){
		
		//Ubicacion de las imagenes que estan contenidas en los encabezados
		define("HOST", $_SERVER['HTTP_HOST']);
		//Obtener el nombre del Nombre de la Carpeta Raíz donde se encontrará almacenado el SISAD
		$raiz = explode("/",$_SERVER['PHP_SELF']);
		define("SISAD",$raiz[1]);
		
			
		switch($hdn_origen){
			case "mttoPreventivo":
				guardarRepPreventivo($hdn_consulta,$hdn_nomReporte,$hdn_msg);
			break;	
			case "mttoCorrectivo":
				guardarRepCorrectivo($hdn_consulta,$hdn_nomReporte,$hdn_msg);
			break;	
			case "ordenTrabajo":
				guardarRepOrdenTrabajo($hdn_consulta,$hdn_nomReporte,$hdn_msg);
			break;
			case "consultarOrdenTrabajo":
				guardarConsultaOrdenTrabajo($hdn_consulta,$hdn_nomConsulta,$hdn_msg);
			break;
			case "mttoCostos":
				guardarRptCostos($hdn_consulta,$hdn_nomReporte,$hdn_msg, $hdn_consultaCM, $hdn_consultaCMat, $hdn_consultaCOT, $hdn_consultaImpC);
			break;
			case "mantenimiento_aceite":
				guardarRepConsumoAceite($hdn_consulta,$hdn_nomReporte,$hdn_msg);
			break;
			case "mantenimiento_detalle_aceite":
				guardarRepDetalleConsumoAceite($hdn_consulta,$hdn_nomReporte,$hdn_msg);
			break;
			case "mantenimiento_gasolina":
				guardarRepConsumoGasolina($hdn_consulta,$hdn_nomReporte,$hdn_msg);
			break;
			case "mantenimiento_detalle_gasolina":
				guardarRepDetalleConsumoGasolina($hdn_consulta,$hdn_nomReporte,$hdn_msg);
			break;
			case "bitacoraPrev" || "bitacoraCorr":
				guardarConsultaBitacora($hdn_origen,$hdn_consulta,$hdn_consultaMat,$hdn_consultaAct,$hdn_consultaMec,$hdn_consultaGam,$hdn_nomReporte,$hdn_msg);
			break;
		}
		
		switch($hdn_tipoReporte){
			case "reporte_requisiciones":
				guardarRepRequisiciones($hdn_consulta,$hdn_nomReporte,$hdn_msg);
			break;
			case "reporte_detallerequisiciones":
				guardarRepDetalleReq($hdn_consulta,$hdn_nomReporte,$hdn_msg);
			break;
		}
	}
	
	if(isset($_POST["hdn_divExpRepEstadistico"])){
		//Ubicacion de las imagenes que estan contenidas en los encabezados
		define("HOST", $_SERVER['HTTP_HOST']);
		//Obtener el nombre del Nombre de la Carpeta Raíz donde se encontrará almacenado el SISAD
		$raiz = explode("/",$_SERVER['PHP_SELF']);
		define("SISAD",$raiz[1]);
		guardarRepEstadistico();
	}
	
	//Esta funcion exporte el REPORTE PREVENTIVO a un archivo de excel
	function guardarRptCostos($hdn_consulta,$hdn_nomReporte,$hdn_msg, $hdn_consultaCM, $hdn_consultaCMat, $hdn_consultaCOT, $hdn_consultaImpC){
		//Esribir datos de la pagina en excel
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=$hdn_nomReporte.xls");
				
		//Realizar la conexion a la BD de Mantenimiento
		$conn = conecta("bd_mantenimiento");
		//Ejecutar la sentencia y almacena los datos de la consulta en la variable $rs (result set)
		$rs_datos = mysql_query($hdn_consulta);
			
		if($datos=mysql_fetch_array($rs_datos)){
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
						<td valign="baseline" colspan="2">
							<div align="right"><span class="texto_encabezado">
								<strong>MANUAL  DE PROCEDIMIENTOS DE LA CALIDAD</strong><br><em>CONCRETO  LANZADO DE FRESNILLO, S.A DE C.V.</em>
							</span></div>
						</td>
					</tr>											
					<tr>
						<td colspan="7" align="center" class="borde_linea">
							<span class="sub_encabezado">CONFIDENCIAL, PROPIEDAD DE &ldquo;CONCRETO LANZADO DE FRESNILLO, S.A DE C.V.&rdquo; PROHIBIDA SU REPRODUCCI
                            &Oacute;N TOTAL O PARCIAL</span>
						</td>
					</tr>					
					<tr>
						<td colspan="7">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="7">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="7" align="center" class="titulo_tabla"><?php echo $hdn_msg; ?></td>
					</tr>
					<tr>
						<td colspan="7">&nbsp;</td>
					</tr>			
					<tr>
						<td  align="center" class='nombres_columnas'>NO.</td>
						<td  align="center" class='nombres_columnas'>CLAVE EQUIPO</td>
						<td class='nombres_columnas'>NOMBRE EQUIPO</td>
						<td class='nombres_columnas'>COSTO MATERIALES PEDIDOS</td>
						<td class='nombres_columnas'>COSTO MANO DE OBRA</td>
						<td class='nombres_columnas'>COSTO DE ORDEN DE TRABAJO EXTERNA</td>
						<td class='nombres_columnas'>COSTO TOTAL EQUIPO</td>																																									
      				</tr>
			<?php
			$nom_clase = "renglon_gris";
			$cont = 1;
			$cant_total = 0;
			do{
				$conn=conecta('bd_mantenimiento');	
				//Ejecutamos sentencia para el costo del mantenimiento
				$datosCM=mysql_fetch_array(mysql_query($hdn_consultaCM));
				//Ejecutamos la sentencia para el costo de los materiales
				$datosCMat=mysql_fetch_array(mysql_query($hdn_consultaCMat));
				//Ejecutamos la sentencia para el costo de la orden de trabajo externa
				$datosCOT=mysql_fetch_array(mysql_query($hdn_consultaCOT));
				//Conectamos a la BAse de datos de compra para obtener el costo de los materiales
				$conn2=conecta('bd_compras');
				//Ejecutamos la sentencia para el importe sobre los materiales pedidos en compras
				$datosImpC=mysql_fetch_array(mysql_query($hdn_consultaImpC));
				//Verificamos si el valor del costo del vale es  vacio si es asi el costo de la mano de obra es el costo del vale
				if($datosCMat['costo_vale']!=""){
					$costoManoObra = $datosCMat['costo_mtto'];
				}
				else{
					//De lo contrario el costo de la Mano de Obra sera igual al costo de los materiales del mantenimiento menos el costo del vale
					$costoManoObra = $datosCM['costo_mtto'] -$datosCMat['costo_vale'];
				}
				//Obtenemos el costo total del mantenimiento
				$costoMtto =$costoManoObra+$datosImpC['importe']+$datosCOT['costo_actividad'];
				?>
					
				<tr>
					<td align="center" class='nombres_filas'><?php echo $cont;?></td>							
					<td align="center" class='nombres_filas'><?php echo $datos['id_equipo'];?></td>
					<td class='<?php echo $nom_clase;?>'><?php echo $datos['nom_equipo'];?></td>
					<td class='<?php echo $nom_clase;?>'>$<?php echo number_format($datosImpC['importe'],2,".",",");?></td>
					<td class='<?php echo $nom_clase;?>'>$<?php echo number_format($costoManoObra,2,".",",");?></td>
					<td class='<?php echo $nom_clase;?>'>$<?php echo number_format($datosCOT['costo_actividad'],2,".",",");?></td>
					<td class='<?php echo $nom_clase;?>'>$<?php echo number_format($costoMtto,2,".",",");?></td>
				</tr>
				<?php
				$cant_total += $costoMtto;	
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
					
			}while($datos=mysql_fetch_array($rs_datos)); ?>
				<tr>
					<td colspan='5'>&nbsp;</td>
					<td align='right' class="renglon_blanco"><strong>COSTO TOTAL</strong></td>
					<td class='nombres_columnas'>$<?php echo number_format($cant_total,2,".",",");?></td>
				</tr>
			</table>
			</div>
			</body>
			<?php	}
		
		//Cerrar la conexion con la BD		
		mysql_close($conn);		
	}//Fin de la Funcion guardarRepPreventivos($hdn_consulta,$hdn_nomReporte)
	
	  			
	//Esta funcion exporte el REPORTE PREVENTIVO a un archivo de excel
	function guardarRepPreventivo($hdn_consulta,$hdn_nomReporte,$hdn_msg){
		//Esribir datos de la pagina en excel
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=$hdn_nomReporte.xls");
				
		//Realizar la conexion a la BD de Mantenimiento
		$conn = conecta("bd_mantenimiento");
		//Ejecutar la sentencia y almacena los datos de la consulta en la variable $rs (result set)
		$rs_datos = mysql_query($hdn_consulta);
			
		if($datos=mysql_fetch_array($rs_datos)){
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
						<td valign="baseline" colspan="2">
							<div align="right"><span class="texto_encabezado">
								<strong>MANUAL  DE PROCEDIMIENTOS DE LA CALIDAD</strong><br><em>CONCRETO  LANZADO DE FRESNILLO, S.A DE C.V.</em>
							</span></div>
						</td>
					</tr>											
					<tr>
						<td colspan="7" align="center" class="borde_linea">
							<span class="sub_encabezado">CONFIDENCIAL, PROPIEDAD DE &ldquo;CONCRETO LANZADO DE FRESNILLO, S.A DE C.V.&rdquo; PROHIBIDA SU REPRODUCCI
                            &Oacute;N TOTAL O PARCIAL</span>
						</td>
					</tr>					
					<tr>
						<td colspan="7">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="7">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="7" align="center" class="titulo_tabla"><?php echo $hdn_msg; ?></td>
					</tr>
					<tr>
						<td colspan="7">&nbsp;</td>
					</tr>			
					<tr>
						<td class="nombres_columnas" align="center">&Aacute;REA</td>
						<td class="nombres_columnas" align="center">FAMILIA</td>
						<td class="nombres_columnas" align="center">CLAVE EQUIPO</td>
						<td class="nombres_columnas" align="center">FECHA MTTO</td>
						<td class="nombres_columnas" align="center">TIPO MTTO</td>
						<td class="nombres_columnas" align="center">HOROMETRO/ODOMETRO</td>
						<td class="nombres_columnas" align="center">COSTO MTTO</td>																																											
      				</tr>
			<?php
			$nom_clase = "renglon_gris";
			$cont = 1;
			$cant_total = 0;
			do{	
				
				//Determinar el Valor de la Metrica
				$metrica = 0;
				if($datos['horometro']!=0)
					$metrica = $datos['horometro']." Hrs.";
				else if($datos['odometro']!=0)
					$metrica = $datos['odometro']." Kms.";
				?>
					
				<tr>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['area']; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['familia']; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['id_equipo']; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['fecha_mtto']; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['tipo_mtto']; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $metrica; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>">$ <?php echo number_format($datos['costo_mtto'],2,".",","); ?></td>
				</tr>
				<?php
				$cant_total += $datos['costo_mtto'];	
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
					
			}while($datos=mysql_fetch_array($rs_datos)); ?>
				<tr><td colspan="6">&nbsp;</td><td class="nombres_columnas" align="center">$ <?php echo number_format($cant_total,2,".",",");?></td></tr>
			</table>
			</div>
			</body>
			<?php	}
		
		//Cerrar la conexion con la BD		
		mysql_close($conn);		
	}//Fin de la Funcion guardarRepPreventivos($hdn_consulta,$hdn_nomReporte)
	
	
	
	//Esta funcion exporte el REPORTE CORRECTIVO a un archivo de excel
	function guardarRepCorrectivo($hdn_consulta,$hdn_nomReporte,$hdn_msg){
		//Esribir datos de la pagina en excel
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=$hdn_nomReporte.xls");
				
		//Realizar la conexion a la BD de Mantenimiento
		$conn = conecta("bd_mantenimiento");
		//Ejecutar la sentencia y almacena los datos de la consulta en la variable $rs (result set)
		$rs_datos = mysql_query($hdn_consulta);
			
		if($datos=mysql_fetch_array($rs_datos)){
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
						<td valign="baseline" colspan="2">
							<div align="right"><span class="texto_encabezado">
								<strong>MANUAL  DE PROCEDIMIENTOS DE LA CALIDAD</strong><br><em>CONCRETO  LANZADO DE FRESNILLO, S.A DE C.V.</em>
							</span></div>
						</td>
					</tr>											
					<tr>
						<td colspan="7" align="center" class="borde_linea">
							<span class="sub_encabezado">CONFIDENCIAL, PROPIEDAD DE &ldquo;CONCRETO LANZADO DE FRESNILLO, S.A DE C.V.&rdquo; PROHIBIDA SU REPRODUCCI
                            &Oacute;N TOTAL O PARCIAL</span>
						</td>
					</tr>					
					<tr>
						<td colspan="7">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="7">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="7" align="center" class="titulo_tabla"><?php echo $hdn_msg; ?></td>
					</tr>
					<tr>
						<td colspan="7">&nbsp;</td>
					</tr>			
					<tr>
						<td class="nombres_columnas" align="center">&Aacute;REA</td>
						<td class="nombres_columnas" align="center">FAMILIA</td>
						<td class="nombres_columnas" align="center">CLAVE EQUIPO</td>
						<td class="nombres_columnas" align="center">FECHA MTTO</td>
						<td class="nombres_columnas" align="center">TIPO MTTO</td>
						<td class="nombres_columnas" align="center">HOROMETRO/ODOMETRO</td>
						<td class="nombres_columnas" align="center">COSTO MTTO</td>																																											
      				</tr>
			<?php
			$nom_clase = "renglon_gris";
			$cont = 1;
			$cant_total = 0;
			do{	
				
				//Determinar el Valor de la Metrica
				$metrica = 0;
				if($datos['horometro']!=0)
					$metrica = $datos['horometro']." Hrs.";
				else if($datos['odometro']!=0)
					$metrica = $datos['odometro']." Kms.";
				?>
					
				<tr>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['area']; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['familia']; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['id_equipo']; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['fecha_mtto']; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['tipo_mtto']; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $metrica; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>">$ <?php echo number_format($datos['costo_mtto'],2,".",","); ?></td>
				</tr>
				<?php
				$cant_total += $datos['costo_mtto'];	
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
					
			}while($datos=mysql_fetch_array($rs_datos)); ?>
				<tr><td colspan="6">&nbsp;</td><td class="nombres_columnas" align="center">$ <?php echo number_format($cant_total,2,".",",");?></td></tr>
			</table>
			</div>
			</body>
			<?php	}
		
		//Cerrar la conexion con la BD		
		mysql_close($conn);		
	}//Fin de la Funcion guardarRepPreventivos($hdn_consulta,$hdn_nomReporte)		
		
		
		
		
	//Esta funcion exporte el REPORTE CORRECTIVO a un archivo de excel
	function guardarRepOrdenTrabajo($hdn_consulta,$hdn_nomReporte,$hdn_msg){
		//Esribir datos de la pagina en excel
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=$hdn_nomReporte.xls");
				
		//Realizar la conexion a la BD de Mantenimiento
		$conn = conecta("bd_mantenimiento");
		//Ejecutar la sentencia y almacena los datos de la consulta en la variable $rs (result set)
		$rs_datos = mysql_query($hdn_consulta);
			
		if($datos=mysql_fetch_array($rs_datos)){
			//Definir el estilo de la tabla y el encabezado que aparecera sobre la tabla?>
			<head>
				<style>					
					<!--
					body { font-family:Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; }
					/*En este formato  se establece el tamaño,tipo de letra,color de fondo que ocupa la columna principal de una tabla*/
					.nombres_columnas { font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #FFFFFF; background-color: #9BBA59; font-weight: bold; 
					border-top-width: medium; border-right-width: thin;
					border-bottom-width: medium; border-left-width: thin; border-top-style: solid; border-right-style: none; border-bottom-style: solid; 
					border-left-style: none; 
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
					-->
				</style>
			</head>											
			<body>
			<div id="tabla">				
				<table width="1100">					
					<tr>
						<td align="left" valign="baseline" colspan="3"><img src="http://<?php echo HOST; ?>/<?php echo SISAD; ?>/images/logo.png" width="118" height="58" 
                        align="absbottom" /></td>
						<td colspan="7">&nbsp;</td>
						<td valign="baseline" colspan="3">
							<div align="right"><span class="texto_encabezado">
								<strong>MANUAL  DE PROCEDIMIENTOS DE LA CALIDAD</strong><br><em>CONCRETO  LANZADO DE FRESNILLO, S.A DE C.V.</em>
							</span></div>
						</td>
					</tr>											
					<tr>
						<td colspan="13" align="center" class="borde_linea">
							<span class="sub_encabezado">CONFIDENCIAL, PROPIEDAD DE &ldquo;CONCRETO LANZADO DE FRESNILLO, S.A DE C.V.&rdquo; PROHIBIDA SU REPRODUCCI
                            &Oacute;N TOTAL O PARCIAL</span>
						</td>
					</tr>					
					<tr>
						<td colspan="13">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="13">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="13" align="center" class="titulo_tabla"><?php echo $hdn_msg; ?></td>
					</tr>
					<tr>
						<td colspan="13">&nbsp;</td>
					</tr>			
					<tr>										
						<td class="nombres_columnas" align="center">CLAVE ORDEN TRABAJO</td>
						<td class="nombres_columnas" align="center">CLAVE EQUIPO</td>
						<td class="nombres_columnas" align="center">ESTADO</td>
						<td class="nombres_columnas" align="center">SERVICIO</td>
                        <td class="nombres_columnas" align="center">FECHA CREACION</td>
						<td class="nombres_columnas" align="center">FECHA PROG.</td>
						<td class="nombres_columnas" align="center">TURNO</td>
						<td class="nombres_columnas" align="center">HOROMETRO/ODOMETRO</td>
						<td class="nombres_columnas" align="left">OPERADOR</td>
						<td class="nombres_columnas" align="left">COMENTARIOS</td>						
						<td class="nombres_columnas" align="center">COSTO TOTAL</td>	
						<td class="nombres_columnas" align="center">ESTADO</td>	
						<td class="nombres_columnas" align="left">AUTORIZACION</td>																		
      				</tr>
			<?php
			$nom_clase = "renglon_gris";
			$cont = 1;
			$cant_total = 0;
			do{	
				//Obtener el Valor de la Metrica
				$metrica = 0;
				if($datos['horometro']!=0)
					$metrica = $datos['horometro']." Hrs.";
				else if($datos['odometro']!=0)
					$metrica = $datos['odometro']." Kms.";
					
				//Determinar el Estado de la Requisicion
				$estado = "";
				if($datos['estado']==0)
					$estado = "PROGRAMADA";
				else
					$estado = "EJECUTADA";?>			
					
					
					<tr>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['id_orden_trabajo']; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['equipos_id_equipo']; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['edo']; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['servicio']; ?></td>						
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo modFecha($datos['fecha_creacion'],1); ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo modFecha($datos['fecha_prog'],1); ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['turno']; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $metrica; ?></td>																		
						<td align="left" class="<?php echo $nom_clase; ?>"><?php echo $datos['operador_equipo']; ?></td>						
						<td align="left" class="<?php echo $nom_clase; ?>"><?php echo $datos['comentarios']; ?></td>						
						<td align="center" class="<?php echo $nom_clase; ?>">$ <?php echo number_format($datos['costo_mtto'],2,".",","); ?></td>																		
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $estado; ?></td>
						<td align="left" class="<?php echo $nom_clase; ?>"><?php echo $datos['autorizo_ot']; ?></td>
					</tr>
				<?php
				$cant_total += $datos['costo_mtto'];
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
					
			}while($datos=mysql_fetch_array($rs_datos)); ?>
				<tr><td colspan="10">&nbsp;</td><td class="nombres_columnas" align="center">$ <?php echo number_format($cant_total,2,".",","); ?></td></tr>
			</table>
			</div>
			</body>	
		

<?php	}
		
		//Cerrar la conexion con la BD		
		mysql_close($conn);		
	}//Fin de la Funcion guardarRepPreventivos($hdn_consulta,$hdn_nomReporte)

	//Esta funcion exporte el REPORTE CORRECTIVO a un archivo de excel
	function guardarConsultaOrdenTrabajo($hdn_consulta,$hdn_nomReporte,$hdn_msg){
		//Esribir datos de la pagina en excel
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=$hdn_nomReporte.xls");
				
		//Realizar la conexion a la BD de Mantenimiento
		$conn = conecta("bd_mantenimiento");
		//Ejecutar la sentencia y almacena los datos de la consulta en la variable $rs (result set)
		$rs_datos = mysql_query($hdn_consulta);
			
		if($datos=mysql_fetch_array($rs_datos)){
			//Definir el estilo de la tabla y el encabezado que aparecera sobre la tabla?>
			<head>
				<style>					
					<!--
					body { font-family:Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; }
					/*En este formato  se establece el tamaño,tipo de letra,color de fondo que ocupa la columna principal de una tabla*/
					.nombres_columnas { font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #FFFFFF; background-color: #9BBA59; font-weight: bold; 
					border-top-width: medium; border-right-width: thin;
					border-bottom-width: medium; border-left-width: thin; border-top-style: solid; border-right-style: none; border-bottom-style: solid; 
					border-left-style: none; 
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
						<td valign="baseline" colspan="3">
							<div align="right"><span class="texto_encabezado">
								<strong>MANUAL  DE PROCEDIMIENTOS DE LA CALIDAD</strong><br><em>CONCRETO  LANZADO DE FRESNILLO, S.A DE C.V.</em>
							</span></div>
						</td>
					</tr>											
					<tr>
						<td colspan="12" align="center" class="borde_linea">
							<span class="sub_encabezado">CONFIDENCIAL, PROPIEDAD DE &ldquo;CONCRETO LANZADO DE FRESNILLO, S.A DE C.V.&rdquo; PROHIBIDA SU REPRODUCCI
                            &Oacute;N TOTAL O PARCIAL</span>
						</td>
					</tr>					
					<tr>
						<td colspan="11">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="11">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="11" align="center" class="titulo_tabla"><?php echo $hdn_msg; ?></td>
					</tr>
					<tr>
						<td colspan="11">&nbsp;</td>
					</tr>			
					<tr>
						<td class="nombres_columnas" align="center">CLAVE ORDEN TRABAJO</td>
                        <td class='nombres_columnas' align="center">CLAVE EQUIPO</td>
						<td class='nombres_columnas' width='70' align="center">ESTADO</td>
						<td class="nombres_columnas" align="center">SERVICIO</td>
						<td class='nombres_columnas' align="center">FECHA CREACION</td>						
						<td class='nombres_columnas' align="center">FECHA PROG.</td>						
						<td class='nombres_columnas' width='150' align="center">TURNO</td>						
						<td class='nombres_columnas' align="center">HOROMETRO/ODOMETRO</td>
						<td class='nombres_columnas' align="center" width='90'>OPERADOR</td>						
						<td class='nombres_columnas' align="center">COMENTARIOS</td>	
						<td class='nombres_columnas' align="center" width='90'>ESTADO</td>						
						<td class='nombres_columnas' align="center">AUTORIZACION</td                        
      				></tr>
			<?php
			$nom_clase = "renglon_gris";
			$cont = 1;

			
			do{	
				//Obtener el Valor de la Metrica
				$metrica = 0;
				if($datos['horometro']!=0)
					$metrica = number_format($datos['horometro'],2,".",",")." Hrs.";
				else if($datos['odometro']!=0)
					$metrica = number_format($datos['odometro'],2,".",",")." Kms.";

				//Determinar el Estado
				$estado = "";
				if($datos['estado']==0)
					$estado = "PROGRAMADA";
				else
					$estado = "EJECUTADA";?>			

					<tr>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['id_orden_trabajo']; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['equipos_id_equipo']; ?></td>						
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['edo']; ?></td>						
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['servicio']; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo modFecha($datos['fecha_creacion'],1); ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo modFecha($datos['fecha_prog'],1); ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['turno']; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $metrica; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['operador_equipo']; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['comentarios']; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $estado; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['autorizo_ot']; ?></td>
					</tr>
				<?php
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
					
			}while($datos=mysql_fetch_array($rs_datos)); ?>
			</table>
			</div>
			</body><?php	
		}		
		//Cerrar la conexion con la BD		
		mysql_close($conn);		
	}//Fin de la Funcion guardarConsultaOrdenTrabajo($hdn_consulta,$hdn_nomReporte,$hdn_msg){
		

	//Esta funcion exporta la informacion de la BITACORA a un archivo de excel
	function guardarConsultaBitacora($origen,$hdn_consulta,$hdn_consultaMat,$hdn_consultaAct,$hdn_consultaMec,$hdn_consultaGam,$hdn_nomReporte,$hdn_msg){
		//Esribir datos de la pagina en excel
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=$hdn_nomReporte.xls");
				
				
		//Realizar la conexion a la BD de Mantenimiento
		$conn = conecta("bd_mantenimiento");
		//Ejecutar la sentencia y almacena los datos de la consulta en la variable $rs (result set)
		$rs_datos = mysql_query($hdn_consulta);
			
		if($datos=mysql_fetch_array($rs_datos)){
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
					.nombres_filas { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #9BBB59; font-weight:bold; }
					/*En este formato  se establece el tamaño,tipo de letra,color de fondo que contendra datos  de una tabla*/
					.renglon_gris { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #E7E7E7; }
					/*En este formato  se establece el tamaño,tipo de letra,color de fondo que contendra datos  de una tabla*/
					.renglon_blanco { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #FFFFFF; } 
					#tabla { position:absolute; left:0px; top:0px; width:1111px; height:175px; z-index:5; }
					.texto_encabezado {font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #0000CC; }
					.sub_encabezado {font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #0000CC; }
					.titulo_tabla {font-family: Arial, Helvetica, sans-serif; font-weight: bold; font-size: 14px; }
					.borde_linea { border-top:3px; border-top-color:#4E6128; border-top-style:solid;}
					-->
				</style>
			</head>											
			<body>
			<div id="tabla" align="center"><?php 
				/****************************************************************************************/
				/**********DESPLEGAR LOS DATOS GENERALES DE LA(S) BITACORA(S) SELECCIONADA(S)************/
				/****************************************************************************************/?>
				<table width="1100">					
					<tr>
						<td align="left" valign="baseline" colspan="3"><img src="http://<?php echo HOST; ?>/<?php echo SISAD; ?>/images/logo.png" width="118" height="58" 
                        align="absbottom" /></td>
						<td colspan="4">&nbsp;</td>
						<td valign="baseline" colspan="3">
							<div align="right"><span class="texto_encabezado">
								<strong>MANUAL  DE PROCEDIMIENTOS DE LA CALIDAD</strong><br><em>CONCRETO  LANZADO DE FRESNILLO, S.A DE C.V.</em>
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
						<td colspan="10">&nbsp;</td>
					</tr>
					<tr>
						<?php //Manipular la Aparicion del Asterisco que asocia el Mensaje de Gamas no registradas en un Mtto. Correctivo
							$asterisco = "";
							if($origen=="bitacoraCorr")
								$asterisco = "*";?>
						<td colspan="10" align="center" class="titulo_tabla"><?php echo $asterisco.$hdn_msg; ?></td>
					</tr>
					<tr>
						<td colspan="10">&nbsp;</td>
					</tr>					
					<tr>
						<td colspan="10" class="nombres_columnas" align="center">DATOS DE LA BIT&Aacute;CORA</td>
					</tr>
						<?php if($origen=="bitacoraPrev"){//Modificar los Nombres de las Columnas Dependiendo del Tipo de Mtto que se esta consultando ?>
							<td class="nombres_filas" align="center">CLAVE EQUIPO</td>
						<?php } else if($origen=="bitacoraCorr"){ ?>
							<td class="nombres_filas" align="center">ID BIT&Aacute;CORA</td>
							<td class="nombres_filas" align="center">CLAVE EQUIPO</td>
						<?php }?>						
						<td class="nombres_filas" align="center">TIPO MANTENIMIENTO</td>
						<td class="nombres_filas" align="center">FECHA REALIZACION</td>
						<td class="nombres_filas" align="center">TURNO</td>
						<td class="nombres_filas" align="center">HOROMETR/ODOMETRO</td>
						<td class="nombres_filas" align="center">TIEMPO MANTENIMIENTO</td>
						<td class="nombres_filas" align="center">COSTO MANTENIMIENTO</td>
						<td class="nombres_filas" align="center">FACTURA</td>
						<td class="nombres_filas" align="center">COMENTARIOS</td>
						<?php if($origen=="bitacoraPrev"){ ?>
							<td class="nombres_filas" align="center">PROX. MANTENIMIENTO</td>
						<?php } ?>
      				</tr><?php
				$nom_clase = "renglon_gris";
				$cont = 1;
				$cant_total = 0;
				do{//Mostrar los datos Generales de la Bitacora o Bitacoras selecciondas
				
					//Determinar la cantidad de la Metrica cuando la OT de la Bitacora consultada haya sido realizada
					$metrica = "No Disponible";
					if($datos['horometro']!="" && $datos['horometro']>0)
						$metrica = number_format($datos['horometro'],2,".",",")." Hrs.";
					else if($datos['odometro']!="" && $datos['odometro']>0)
						$metrica = number_format($datos['odometro'],2,".",",")." Kms.";
																
					//Determinar si la OT de la Bitacora que se estan consultadoya fue realizada
					$fecha = "No Realizada";
					if($datos['fecha_mtto']!="" && $datos['fecha_mtto']!="0000-00-00")
						$fecha = modFecha($datos['fecha_mtto'],1);
									
				
					//Imprimir los Datos de acuerdo al tipo de Mtto. de la Bitacora que se esta consultando
					?><tr><?php
					if($origen=="bitacoraPrev"){ ?>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['equipos_id_equipo']; ?></td>
					<?php } else if($origen=="bitacoraCorr"){ ?>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['id_bitacora']; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['equipos_id_equipo']; ?></td>
					<?php }?>				
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['tipo_mtto']; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $fecha; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['turno']; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $metrica; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['tiempo_total']; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>">$ <?php echo number_format($datos['costo_mtto'],2,".",","); ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['num_factura']; ?></td>
						<td align="left" class="<?php echo $nom_clase; ?>"><?php echo $datos['comentarios']; ?></td>
					<?php if($origen=="bitacoraPrev"){ 
						//Determinar si la OT de la Bitacora que se estan consultadoya fue realizada
						$prox_mtto = "No Disponible";
						if($datos['prox_mtto']!="" && $datos['prox_mtto']!="0000-00-00")
							$prox_mtto = modFecha($datos['prox_mtto'],1);?>
							
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $prox_mtto; ?></td>
					<?php } ?>
					</tr><?php
				
					$cant_total += $datos['costo_mtto'];	
					//Determinar el color del siguiente renglon a dibujar
					$cont++;
					if($cont%2==0)
						$nom_clase = "renglon_blanco";
					else
						$nom_clase = "renglon_gris";
						
				}while($datos=mysql_fetch_array($rs_datos));
				
				//Determinar la Ubicacion del Total del Costo de Materiales
				if($origen=="bitacoraPrev")	$numCols = 6;
				else if($origen=="bitacoraCorr") $numCols = 7;?>
				<tr><td colspan="<?php echo $numCols; ?>">&nbsp;</td><td class="nombres_filas" align="center">$ <?php echo number_format($cant_total,2,".",",");?></td></tr>
			</table><br><br><?php 
			
			
			/********************************************************************************************************/
			/***********************DESPLEGAR LOS MATERIALES UTILIZADOS EN EL MANTENIMIENTO**************************/
			/********************************************************************************************************/
			//Conectarse a la Base de Datos de Almacen
			$conn_almacen = conecta("bd_almacen");	
			//Ejecutar la Consulta
			$rs_datos = mysql_query($hdn_consultaMat,$conn_almacen);
			if($datos=mysql_fetch_array($rs_datos)){?>
				<table width="800">
					<tr>
						<td colspan="8" class="nombres_columnas" align="center">MATERIALES UTILIZADOS EN EL MANTENIMIENTO</td>
					</tr>
					<tr>						
						<td class="nombres_filas" align="center">ID BIT&Aacute;CORA</td>
						<td class="nombres_filas" align="center">ID VALE</td>
						<td class="nombres_filas" align="center">CLAVE MATERIAL</td>
						<td class="nombres_filas" align="center">NOMBRE MATERIAL</td>
						<td class="nombres_filas" align="center">CANTIDAD</td>
						<td class="nombres_filas" align="center">PRECIO UNITARIO</td>
						<td class="nombres_filas" align="center">UNIDAD MEDIDA</td>
						<td class="nombres_filas" align="center">IMPORTE</td>
					</tr><?php
				$nom_clase = "renglon_gris";
				$cont = 1;
				$cant_total = 0;
				do{	
					//Obtener el Nombre del Material y la Unidad de Media
					$nomMaterial = obtenerDato("bd_almacen", "materiales", "nom_material", "id_material", $datos["materiales_id_material"]);				
					$unidadMedida = obtenerDato("bd_almacen", "unidad_medida", "unidad_medida", "materiales_id_material", $datos["materiales_id_material"]);	
					if($origen=="bitacoraPrev"){
						$id_bitacora=substr($hdn_msg,16,10);
						$noVale = obtenerDato("bd_mantenimiento", "materiales_mtto", "id_vale", "bitacora_mtto_id_bitacora", $id_bitacora);
					}
					else{
						$id_bitacora=$datos['id_bitacora'];
						$noVale= $datos['id_vale']; 
							
					}
						?>
					<tr>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $id_bitacora; ?></td>	
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $noVale; ?></td>					
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['materiales_id_material']; ?></td>
						<td align="left"  class="<?php echo $nom_clase; ?>"><?php echo $nomMaterial; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['cant_salida']; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>">$ <?php echo number_format($datos['costo_unidad'],2,".",",");?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $unidadMedida; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>">$ <?php echo number_format($datos['costo_total'],2,".",","); ?></td>
					</tr><?php 
					
					//Sumar el costo de los Materiales
					$cant_total += $datos['costo_total'];
					//Determinar el color del siguiente renglon a dibujar
					$cont++;
					if($cont%2==0)
						$nom_clase = "renglon_blanco";
					else
						$nom_clase = "renglon_gris";
						
				}while($datos=mysql_fetch_array($rs_datos));?>
					<tr><td colspan="7">&nbsp;</td><td class="nombres_filas" align="center">$<?php echo number_format($cant_total,2,".",",")?></td>
				</table><br><br><?php
			}//Cierre if($datos=mysql_fetch_array($rs_datos))
			
			//Cerrar la Conexion con la Base de Datos de Almacen
			mysql_close($conn_almacen);
			
			
			/********************************************************************************************************/
			/***********************DESPLEGAR LAS ACTIVIDADES REALIZADAS EN EL MANTENIMIENTO*************************/
			/********************************************************************************************************/
			//Conectarse a la Base de Datos de Mantenimiento
			$conn = conecta("bd_mantenimiento");
			//Ejecutar la sentencia y almacena los datos de la consulta en la variable $rs (result set)
			$rs_datos = mysql_query($hdn_consultaAct);
			if($datos=mysql_fetch_array($rs_datos)){?>				
				<table width="600">
					<tr>
						<td colspan="4" class="nombres_columnas" align="center">ACTIVIDADES CORRECTIVAS REGISTRADAS EN LA BITACORA</td>
					</tr>
					<tr>
						<td class='nombres_filas' align="center">ID BIT&Aacute;CORA</td>						
						<td class='nombres_filas' align="center">SISTEMA</td>
						<td class='nombres_filas' align="center">APLICACI&Oacute;N</td>
						<td class='nombres_filas' align="center">DESCRIPCI&Oacute;N</td>
					</tr><?php
				$nom_clase = "renglon_gris";
				$cont = 1;				
				do{?>	
					<tr>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['bitacora_mtto_id_bitacora']; ?></td>						
						<td align="left" class="<?php echo $nom_clase; ?>"><?php echo $datos['sistema']; ?></td>
						<td align="left" class="<?php echo $nom_clase; ?>"><?php echo $datos['aplicacion']; ?></td>
						<td align="left" class="<?php echo $nom_clase; ?>"><?php echo $datos['descripcion']; ?></td>
					</tr><?php 
					
					//Determinar el color del siguiente renglon a dibujar
					$cont++;
					if($cont%2==0)
						$nom_clase = "renglon_blanco";
					else
						$nom_clase = "renglon_gris";						
				}while($datos=mysql_fetch_array($rs_datos));?>
				</table><br><br><?php
			}//Cierre if($datos=mysql_fetch_array($rs_datos))															
			
			
			/********************************************************************************************************/
			/***********************DESPLEGAR LOS MECANICOS QUE REALIZARON EL MANTENIMIENTO**************************/
			/********************************************************************************************************/
			//Ejecutar la sentencia y almacena los datos de la consulta en la variable $rs (result set)
			$rs_datos = mysql_query($hdn_consultaMec);
			if($datos=mysql_fetch_array($rs_datos)){?>
				<table width="400">
					<tr>
						<td colspan="2" class="nombres_columnas" align="center">MEC&Aacute;NICOS QUE REALIZARON EL MANTENIMIENTO</td>
					</tr>
					<tr>
						<td class='nombres_filas' align="center">CLAVE BIT&Aacute;CORA</td>
						<td class='nombres_filas' align="center">MEC&Aacute;NICO</td>
					</tr><?php
				$nom_clase = "renglon_gris";
				$cont = 1;
				do{	?>	
					<tr>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['bitacora_mtto_id_bitacora']; ?></td>
						<td align="left" class="<?php echo $nom_clase; ?>"><?php echo $datos['nom_mecanico']; ?></td>
					</tr><?php 
					//Determinar el color del siguiente renglon a dibujar
					$cont++;
					if($cont%2==0)
						$nom_clase = "renglon_blanco";
					else
						$nom_clase = "renglon_gris";
						
				}while($datos=mysql_fetch_array($rs_datos));?>
				</table><br><br><?php
			}//Cierre if($datos=mysql_fetch_array($rs_datos)) 
			
			
			/********************************************************************************************************/
			/********************DESPLEGAR EL DETALLE DE LAS GAMAS ASOCIADAS AL MANTENIMIENTO************************/
			/********************************************************************************************************/
			//Ejecutar la sentencia y almacena los datos de la consulta en la variable $rs (result set)
			if($hdn_consultaGam!="EN LOS MANTENIMIENTOS CORRECTIVOS LAS GAMAS NO APLICAN"){
				$rs_datos = mysql_query($hdn_consultaGam);
				if($datos=mysql_fetch_array($rs_datos)){?>
					<table width="800">				
						<tr>
							<td colspan="5" class="nombres_columnas" align="center">GAMAS APLICADAS AL EQUIPO</td>
						</tr>
						<tr>
							<td class="nombres_filas" align="center">CLAVE BIT&Aacute;CORA</td>
							<td class='nombres_filas' align="center">ORDEN TRABAJO</td>
							<td class='nombres_filas' align="center">CLAVE GAMA</td>
							<td class='nombres_filas' align="center">NOMBRE GAMA</td>
							<td class='nombres_filas' align="center">CICLO SERVICIO</td>
						</tr>
					<?php
					$nom_clase = "renglon_gris";
					$cont = 1;
					do{	?>	
						<tr><?php //Muestra Detalle Consulta gamas?>
							<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['id_bitacora']; ?></td>
							<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['orden_trabajo_id_orden_trabajo']; ?></td>
							<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['gama_id_gama']; ?></td>
							<td align="left" class="<?php echo $nom_clase; ?>"><?php echo $datos['nom_gama']; ?></td>
							<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['ciclo_servicio']; ?></td>
						</tr><?php 
						//Determinar el color del siguiente renglon a dibujar
						$cont++;
						if($cont%2==0)
							$nom_clase = "renglon_blanco";
						else
							$nom_clase = "renglon_gris";
							
					}while($datos=mysql_fetch_array($rs_datos));?>
					</table><br><br><?php
				}//Cierre if($datos=mysql_fetch_array($rs_datos))
			}
			else{?>
				<label class="texto_encabezado"><strong>*<?php echo $hdn_consultaGam;?></strong></label>
			<?php }?>
			
			</div>
			</body><?php				
		}//Cierre if($datos=mysql_fetch_array($rs_datos))
		
		//Cerrar la conexion con la BD		
		mysql_close($conn);		
	}//Fin de la Funcion 

	function guardarRepEstadistico(){
		header("Content-type: application/vnd.ms-excel; name='excel'");
		header("Content-Disposition: attachment; filename=ReporteEstadistico.xls");
		//header("Content-Disposition: filename=ReporteEstadistico.ls");
		header("Pragma: no-cache");
		header("Expires: 0");
		?>
		<head>
			<style>					
				<!--
				body { font-family:Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; }
				/*En este formato  se establece el tamaño,tipo de letra,color de fondo que ocupa la columna principal de una tabla*/
				.nombres_columnas { font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #FFFFFF; background-color: #9BBA59; font-weight: bold; 
				border-top-width: medium; border-right-width: thin;
				border-bottom-width: medium; border-left-width: thin; border-top-style: solid; border-right-style: none; border-bottom-style: 
				solid; border-left-style: none; 
				border-top-color: #000000; border-bottom-color: #000000; }
				/*En este formato  se establece el tamaño,tipo de letra,color de fondo que se ocupa en la fila principal para resaltar datos  de una tabla*/
				.nombres_filas { font-family: Arial, Helvetica, sans-serif; font-size: 11px; color: #000000; background-color: #9BBB59; font-weight:bold; }
				/*En este formato  se establece el tamaño,tipo de letra,color de fondo que contendra datos  de una tabla*/
				.renglon_gris { font-family: Arial, Helvetica, sans-serif; font-size: 11px; color: #000000; background-color: #E7E7E7; }
				/*En este formato  se establece el tamaño,tipo de letra,color de fondo que contendra datos  de una tabla*/
				.renglon_blanco { font-family: Arial, Helvetica, sans-serif; font-size: 11px; color: #000000; background-color: #FFFFFF; } 
				#tabla { position:absolute; left:0px; top:0px; width:1111px; height:175px; z-index:5; }
				.texto_encabezado {font-family: Arial, Helvetica, sans-serif; font-size: 11px; color: #0000CC; }
				.sub_encabezado {font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #0000CC; }
				.titulo_etiqueta {font-family: Arial, Helvetica, sans-serif; font-weight: bold; font-size: 14px; }
				.borde_linea { border-top:3px; border-top-color:#4E6128; border-top-style:solid;}
				-->
			</style>
		</head>	
		<table>
			<tr>
				<td align="left" valign="baseline" colspan="3"><img src="http://<?php echo HOST; ?>/<?php echo SISAD; ?>/images/logo.png" width="150" height="70" 
				align="absbottom" /></td>
				<td colspan="7">&nbsp;</td>
				<td valign="baseline" colspan="2">
					<div align="right"><span class="texto_encabezado">
						<strong>MANUAL DE PROCEDIMIENTOS DE LA CALIDAD</strong><br><em>CONCRETO LANZADO DE FRESNILLO, S.A DE C.V.</em>
					</span></div>
				</td>
			</tr>											
			<tr>
				<td colspan="12" align="center" class="borde_linea" width="100%">
					<span class="sub_encabezado">CONFIDENCIAL, PROPIEDAD DE &ldquo;CONCRETO LANZADO DE FRESNILLO, S.A DE C.V.&rdquo; PROHIBIDA SU REPRODUCCI&Oacute;N TOTAL O PARCIAL</span>
				</td>
			</tr>					
			<tr>
				<td colspan="12">&nbsp;</td>
			</tr>
			<tr>
				<td colspan="12">&nbsp;</td>
			</tr>
		</table>
		<?php
		echo $_POST['hdn_divExpRepEstadistico'];
	}
	
	//Esta funcion exporte el REPORTE CORRECTIVO a un archivo de excel
	function guardarRepConsumoAceite($hdn_consulta,$hdn_nomReporte,$hdn_msg){
		//Esribir datos de la pagina en excel
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=$hdn_nomReporte.xls");
				
		//Realizar la conexion a la BD de Mantenimiento
		$conn = conecta("bd_mantenimiento");
		//Ejecutar la sentencia y almacena los datos de la consulta en la variable $rs (result set)
		$rs_datos = mysql_query($hdn_consulta);
			
		if($datos=mysql_fetch_array($rs_datos)){
			//Definir el estilo de la tabla y el encabezado que aparecera sobre la tabla?>
			<head>
				<style>					
					<!--
					body { font-family:Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; }
					/*En este formato  se establece el tamaño,tipo de letra,color de fondo que ocupa la columna principal de una tabla*/
					.nombres_columnas { font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #FFFFFF; background-color: #9BBA59; font-weight: bold; 
					border-top-width: medium; border-right-width: thin;
					border-bottom-width: medium; border-left-width: thin; border-top-style: solid; border-right-style: none; border-bottom-style: solid; 
					border-left-style: none; 
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
					-->
				</style>
			</head>											
			<body>
			<div id="tabla">				
				<table width="1100">					
					<tr>
						<td align="left" valign="baseline" colspan="3"><img src="http://<?php echo HOST; ?>/<?php echo SISAD; ?>/images/logo.png" width="150" height="65" 
                        align="absbottom" /></td>
						<td colspan="3">&nbsp;</td>
						<td valign="baseline" colspan="3">
							<div align="right"><span class="texto_encabezado">
								<strong>MANUAL  DE PROCEDIMIENTOS DE LA CALIDAD</strong><br><em>CONCRETO  LANZADO DE FRESNILLO, S.A DE C.V.</em>
							</span></div>
						</td>
					</tr>											
					<tr>
						<td colspan="9" align="center" class="borde_linea">
							<span class="sub_encabezado">CONFIDENCIAL, PROPIEDAD DE &ldquo;CONCRETO LANZADO DE FRESNILLO, S.A DE C.V.&rdquo; PROHIBIDA SU REPRODUCCI
                            &Oacute;N TOTAL O PARCIAL</span>
						</td>
					</tr>					
					<tr>
						<td colspan="9">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="9">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="9" align="center" class="titulo_tabla"><?php echo $hdn_msg; ?></td>
					</tr>
					<tr>
						<td colspan="9">&nbsp;</td>
					</tr>			
					<tr>										
						<td class="nombres_columnas" align="center">ID EQUIPO</td>
						<td class="nombres_columnas" align="center">NOMBRE</td>
						<td class="nombres_columnas" align="center">MARCA</td>
						<td class="nombres_columnas" align="center">MODELO</td>
                        <td class="nombres_columnas" align="center">PLACAS</td>
						<td class="nombres_columnas" align="center">ASIGNADO</td>
						<td class="nombres_columnas" align="center">TIPO</td>
						<td class="nombres_columnas" align="center">LTS. CONSUMIDOS</td>
						<td class="nombres_columnas" align="center">RENDIMIENTO</td>
      				</tr>
			<?php
			$nom_clase = "renglon_gris";
			$cont = 1;
			$total_consumo = 0;
			do{
				$consumo_aceite = consumoAceite($datos["id_equipo"],$_POST["hdn_fecha_ini"],$_POST["hdn_fecha_fin"],"3");
				$rendimiento = rendimientoAceite($datos["id_equipo"],$_POST["hdn_fecha_ini"],$_POST["hdn_fecha_fin"],"3");
			?>			
				<tr>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['id_equipo']; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['nom_equipo']; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['marca_modelo']; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['modelo']; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['placas']; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['asignado']; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>">DIESEL</td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo number_format($consumo_aceite,2,".",","); ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo number_format($rendimiento,2,".",","); ?></td>
				</tr>
				<?php
				$total_consumo += $consumo_aceite;
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
					
			}while($datos=mysql_fetch_array($rs_datos)); ?>
			<tr>
				<td colspan='6'></td>
				<td class='nombres_columnas' align="center">TOTAL:</td>
				<td class='nombres_columnas' align="center">$ <?php echo number_format($total_consumo,2,".",","); ?></td>
			</tr>
			</table>
			</div>
			</body>	
		

<?php	}
		
		//Cerrar la conexion con la BD		
		mysql_close($conn);		
	}//Fin de la Funcion guardarRepConsumoAceite($hdn_consulta,$hdn_nomReporte)
	
	function guardarRepDetalleConsumoAceite($hdn_consulta,$hdn_nomReporte,$hdn_msg){
		//Esribir datos de la pagina en excel
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=$hdn_nomReporte.xls");
				
		//Realizar la conexion a la BD de Mantenimiento
		$conn = conecta("bd_mantenimiento");
		//Ejecutar la sentencia y almacena los datos de la consulta en la variable $rs (result set)
		$rs_datos = mysql_query($hdn_consulta);
			
		if($datos=mysql_fetch_array($rs_datos)){
			//Definir el estilo de la tabla y el encabezado que aparecera sobre la tabla?>
			<head>
				<style>					
					<!--
					body { font-family:Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; }
					/*En este formato  se establece el tamaño,tipo de letra,color de fondo que ocupa la columna principal de una tabla*/
					.nombres_columnas { font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #FFFFFF; background-color: #9BBA59; font-weight: bold; 
					border-top-width: medium; border-right-width: thin;
					border-bottom-width: medium; border-left-width: thin; border-top-style: solid; border-right-style: none; border-bottom-style: solid; 
					border-left-style: none; 
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
					-->
				</style>
			</head>											
			<body>
			<div id="tabla">				
				<table width="1100">					
					<tr>
						<td align="left" valign="baseline" colspan="3"><img src="http://<?php echo HOST; ?>/<?php echo SISAD; ?>/images/logo.png" width="150" height="65" 
                        align="absbottom" /></td>
						<td colspan="1">&nbsp;</td>
						<td valign="baseline" colspan="3">
							<div align="right"><span class="texto_encabezado">
								<strong>MANUAL  DE PROCEDIMIENTOS DE LA CALIDAD</strong><br><em>CONCRETO  LANZADO DE FRESNILLO, S.A DE C.V.</em>
							</span></div>
						</td>
					</tr>											
					<tr>
						<td colspan="7" align="center" class="borde_linea">
							<span class="sub_encabezado">CONFIDENCIAL, PROPIEDAD DE &ldquo;CONCRETO LANZADO DE FRESNILLO, S.A DE C.V.&rdquo; PROHIBIDA SU REPRODUCCI
                            &Oacute;N TOTAL O PARCIAL</span>
						</td>
					</tr>					
					<tr>
						<td colspan="7">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="7">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="7" align="center" class="titulo_tabla"><?php echo $hdn_msg; ?></td>
					</tr>
					<tr>
						<td colspan="7">&nbsp;</td>
					</tr>			
					<tr>										
						<td class="nombres_columnas" align="center">NO.</td>
						<td class="nombres_columnas" align="center">EQUIPO</td>
						<td class="nombres_columnas" align="center">FECHA</td>
						<td class="nombres_columnas" align="center">TURNO</td>
                        <td class="nombres_columnas" align="center">RESPONSABLE</td>
						<td class="nombres_columnas" align="center">CANTIDAD</td>
						<td class="nombres_columnas" align="center">OD&Oacute;METRO / HOR&Oacute;METRO</td>																		
      				</tr>
			<?php
			$nom_clase = "renglon_gris";
			$cont = 1;
			$cant_total = 0;
			do{
			?>			
				<tr>
					<td align="center" class="nombres_filas"><?php echo $cont; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['equipos_id_equipo']; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['fecha']; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['turno']; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['supervisor_mtto']; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo number_format($datos['cantidad'],2,".",","); ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo number_format($datos['odometro_horometro'],2,".",","); ?></td>
				</tr>
				<?php
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
					
			}while($datos=mysql_fetch_array($rs_datos)); ?>
			</table>
			</div>
			</body>	
		

<?php	}
		
		//Cerrar la conexion con la BD		
		mysql_close($conn);		
	}//Fin de la Funcion guardarRepDetalleConsumoAceite($hdn_consulta,$hdn_nomReporte)
	
	//Esta funcion exporte el REPORTE CORRECTIVO a un archivo de excel
	function guardarRepConsumoGasolina($hdn_consulta,$hdn_nomReporte,$hdn_msg){
		//Esribir datos de la pagina en excel
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=$hdn_nomReporte.xls");
				
		//Realizar la conexion a la BD de Mantenimiento
		$conn = conecta("bd_mantenimiento");
		//Ejecutar la sentencia y almacena los datos de la consulta en la variable $rs (result set)
		$rs_datos = mysql_query($hdn_consulta);
			
		if($datos=mysql_fetch_array($rs_datos)){
			//Definir el estilo de la tabla y el encabezado que aparecera sobre la tabla?>
			<head>
				<style>					
					<!--
					body { font-family:Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; }
					/*En este formato  se establece el tamaño,tipo de letra,color de fondo que ocupa la columna principal de una tabla*/
					.nombres_columnas { font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #FFFFFF; background-color: #9BBA59; font-weight: bold; 
					border-top-width: medium; border-right-width: thin;
					border-bottom-width: medium; border-left-width: thin; border-top-style: solid; border-right-style: none; border-bottom-style: solid; 
					border-left-style: none; 
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
					-->
				</style>
			</head>											
			<body>
			<div id="tabla">				
				<table width="1100">					
					<tr>
						<td align="left" valign="baseline" colspan="3"><img src="http://<?php echo HOST; ?>/<?php echo SISAD; ?>/images/logo.png" width="150" height="65" 
                        align="absbottom" /></td>
						<td colspan="4">&nbsp;</td>
						<td valign="baseline" colspan="3">
							<div align="right"><span class="texto_encabezado">
								<strong>MANUAL  DE PROCEDIMIENTOS DE LA CALIDAD</strong><br><em>CONCRETO  LANZADO DE FRESNILLO, S.A DE C.V.</em>
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
						<td colspan="10">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="10" align="center" class="titulo_tabla"><?php echo $hdn_msg; ?></td>
					</tr>
					<tr>
						<td colspan="10">&nbsp;</td>
					</tr>			
					<tr>										
						<td class="nombres_columnas" align="center">ID EQUIPO</td>
						<td class="nombres_columnas" align="center">NOMBRE</td>
						<td class="nombres_columnas" align="center">MARCA</td>
						<td class="nombres_columnas" align="center">MODELO</td>
                        <td class="nombres_columnas" align="center">PLACAS</td>
						<td class="nombres_columnas" align="center">ASIGNADO</td>
						<td class="nombres_columnas" align="center">TIPO</td>
						<td class="nombres_columnas" align="center">LTS. CONSUMIDOS</td>
						<td class="nombres_columnas" align="center">COSTO</td>
						<td class="nombres_columnas" align="center">RENDIMIENTO</td>
      				</tr>
			<?php
			$nom_clase = "renglon_gris";
			$cont = 1;
			$total_consumo = 0;
			do{
				$consumo_gasolina = consumoGasolina($datos["id_equipo"],$_POST["hdn_fecha_ini"],$_POST["hdn_fecha_fin"]);
				$rendimiento = rendimientoGasolina($datos["id_equipo"],$_POST["hdn_fecha_ini"],$_POST["hdn_fecha_fin"]);
				$costo = costoGasolina($datos["id_equipo"],$_POST["hdn_fecha_ini"],$_POST["hdn_fecha_fin"]);
			?>			
				<tr>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['id_equipo']; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['nom_equipo']; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['marca_modelo']; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['modelo']; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['placas']; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['asignado']; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>">GASOLINA</td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo number_format($consumo_gasolina,2,".",","); ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>">$ <?php echo number_format($costo,2,".",","); ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo number_format($rendimiento,2,".",","); ?></td>
				</tr>
				<?php
				$total_consumo += $consumo_gasolina;
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
					
			}while($datos=mysql_fetch_array($rs_datos)); ?>
			<tr>
				<td colspan='6'></td>
				<td class='nombres_columnas' align="center">TOTAL:</td>
				<td class='nombres_columnas' align="center"><?php echo number_format($total_consumo,2,".",","); ?></td>
			</tr>
			</table>
			</div>
			</body>	
		

<?php	}
		
		//Cerrar la conexion con la BD		
		mysql_close($conn);		
	}//Fin de la Funcion guardarRepConsumoAceite($hdn_consulta,$hdn_nomReporte)
	
	function guardarRepDetalleConsumoGasolina($hdn_consulta,$hdn_nomReporte,$hdn_msg){
		//Esribir datos de la pagina en excel
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=$hdn_nomReporte.xls");
				
		//Realizar la conexion a la BD de Mantenimiento
		$conn = conecta("bd_mantenimiento");
		//Ejecutar la sentencia y almacena los datos de la consulta en la variable $rs (result set)
		$rs_datos = mysql_query($hdn_consulta);
			
		if($datos=mysql_fetch_array($rs_datos)){
			//Definir el estilo de la tabla y el encabezado que aparecera sobre la tabla?>
			<head>
				<style>					
					<!--
					body { font-family:Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; }
					/*En este formato  se establece el tamaño,tipo de letra,color de fondo que ocupa la columna principal de una tabla*/
					.nombres_columnas { font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #FFFFFF; background-color: #9BBA59; font-weight: bold; 
					border-top-width: medium; border-right-width: thin;
					border-bottom-width: medium; border-left-width: thin; border-top-style: solid; border-right-style: none; border-bottom-style: solid; 
					border-left-style: none; 
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
					-->
				</style>
			</head>											
			<body>
			<div id="tabla">				
				<table width="1100">					
					<tr>
						<td align="left" valign="baseline" colspan="3"><img src="http://<?php echo HOST; ?>/<?php echo SISAD; ?>/images/logo.png" width="150" height="65" 
                        align="absbottom" /></td>
						<!--<td colspan="1">&nbsp;</td>-->
						<td valign="baseline" colspan="3">
							<div align="right"><span class="texto_encabezado">
								<strong>MANUAL  DE PROCEDIMIENTOS DE LA CALIDAD</strong><br><em>CONCRETO  LANZADO DE FRESNILLO, S.A DE C.V.</em>
							</span></div>
						</td>
					</tr>											
					<tr>
						<td colspan="6" align="center" class="borde_linea">
							<span class="sub_encabezado">CONFIDENCIAL, PROPIEDAD DE &ldquo;CONCRETO LANZADO DE FRESNILLO, S.A DE C.V.&rdquo; PROHIBIDA SU REPRODUCCI
                            &Oacute;N TOTAL O PARCIAL</span>
						</td>
					</tr>					
					<tr>
						<td colspan="6">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="6">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="6" align="center" class="titulo_tabla"><?php echo $hdn_msg; ?></td>
					</tr>
					<tr>
						<td colspan="6">&nbsp;</td>
					</tr>			
					<tr>										
						<td class="nombres_columnas" align="center">NO.</td>
						<td class="nombres_columnas" align="center">EQUIPO</td>
						<td class="nombres_columnas" align="center">FECHA</td>
                        <td class="nombres_columnas" align="center">RESPONSABLE</td>
						<td class="nombres_columnas" align="center">CANTIDAD</td>
						<td class="nombres_columnas" align="center">OD&Oacute;METRO / HOR&Oacute;METRO</td>																		
      				</tr>
			<?php
			$nom_clase = "renglon_gris";
			$cont = 1;
			$cant_total = 0;
			do{
				$responsable = obtenerNombreEmpleado($datos["responsable"]);
			?>			
				<tr>
					<td align="center" class="nombres_filas"><?php echo $cont; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['equipos_id_equipo']; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['fecha']; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $responsable; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo number_format($datos['cantidad'],2,".",","); ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo number_format($datos['odometro'],2,".",","); ?></td>
				</tr>
				<?php
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
					
			}while($datos=mysql_fetch_array($rs_datos)); ?>
			</table>
			</div>
			</body>	
		

<?php	}
		
		//Cerrar la conexion con la BD		
		mysql_close($conn);		
	}//Fin de la Funcion guardarRepDetalleConsumoAceite($hdn_consulta,$hdn_nomReporte)
	
	function guardarRepRequisiciones($hdn_consulta,$hdn_nomReporte,$hdn_msg){
		include_once("../../includes/func_fechas.php");
		//Esribir datos de la pagina en excel
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=$hdn_nomReporte.xls");
		?>
		<head>
			<style>					
				<!--
				body { font-family:Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; }
				/*En este formato  se establece el tamaño,tipo de letra,color de fondo que ocupa la columna principal de una tabla*/
				.nombres_columnas { font-family: Arial, Helvetica, sans-serif; font-size: 13px; color: #FFFFFF; background-color: #9BBA59; font-weight: bold; 
				border-top-width: medium; border-right-width: thin;
				border-bottom-width: medium; border-left-width: thin; border-top-style: solid; border-right-style: none; border-bottom-style: solid; 
				border-left-style: none; 
				border-top-color: #000000; border-bottom-color: #000000; }
				/*En este formato  se establece el tamaño,tipo de letra,color de fondo que se ocupa en la fila principal para resaltar datos  de una tabla*/
				.nombres_filas { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #9BBB59; }
				/*En este formato  se establece el tamaño,tipo de letra,color de fondo que contendra datos  de una tabla*/
				.renglon_gris { font-family: Arial, Helvetica, sans-serif; font-size: 11px; color: #000000; background-color: #E7E7E7; }
				/*En este formato  se establece el tamaño,tipo de letra,color de fondo que contendra datos  de una tabla*/
				.renglon_blanco { font-family: Arial, Helvetica, sans-serif; font-size: 11px; color: #000000; background-color: #FFFFFF; } 
				#tabla { position:absolute; left:0px; top:0px; width:1111px; height:175px; z-index:5; }
				.texto_encabezado {font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #0000CC; }
				.sub_encabezado {font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #0000CC; }
				.titulo_tabla {font-family: Arial, Helvetica, sans-serif; font-weight: bold; font-size: 14px; }
				.borde_linea { border-top:3px; border-top-color:#4E6128; border-top-style:solid;}
				-->
			</style>
		</head>	
		<?php
		$fecha_i = $_POST["hdn_fecha_ini"];
		$fecha_f = $_POST["hdn_fecha_fin"];
		$bd = $_POST["hdn_bd"];
		
		$conn=conecta("$bd");
		$rs = mysql_query($hdn_consulta);
		if($datos = mysql_fetch_array($rs)){?>										
			<body>
			<div id="tabla">				
				<table width="1100">					
					<tr>
						<td align="left" valign="baseline" colspan="3"><img src="http://<?php echo HOST; ?>/<?php echo SISAD; ?>/images/logo.png" width="150" height="65" 
						align="absbottom" /></td>
						<td colspan="2">&nbsp;</td>
						<td valign="baseline" colspan="3">
							<div align="right"><span class="texto_encabezado">
								<strong>MANUAL  DE PROCEDIMIENTOS DE LA CALIDAD</strong><br><em>CONCRETO  LANZADO DE FRESNILLO, S.A DE C.V.</em>
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
						<td colspan="8">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="8" align="center" class="titulo_tabla"><?php echo $hdn_msg; ?></td>
					</tr>
					<tr>
						<td colspan="8">&nbsp;</td>
					</tr>			
					<tr>
						<td class='nombres_columnas' align="center">ID REQUISICI&Oacute;N</td>
						<td class='nombres_columnas' align="center">DEPARTAMENTO</td>
						<td class='nombres_columnas' align="center">FECHA</td>
						<td class='nombres_columnas' align="center">SOLICIT&Oacute;</td>
						<td class='nombres_columnas' align="center">REALIZ&Oacute;</td>
						<td class='nombres_columnas' align="center">ESTADO</td>
						<td class='nombres_columnas' align="center">PRIORIDAD</td>
						<td class='nombres_columnas' align="center">TIEMPO DE ENTREGA</td>
					</tr>
					<?php
					$nom_clase = "renglon_gris";
					$cont = 1;
					do{
						$dias_ent = calcularDiasEntregaReq($datos["id_requisicion"],$bd);
					?>			
						<tr>
							<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['id_requisicion']; ?></td>
							<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['area_solicitante']; ?></td>
							<td align="center" class="<?php echo $nom_clase; ?>"><?php echo modFecha($datos['fecha_req'],1); ?></td>
							<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['solicitante_req']; ?></td>
							<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['elaborador_req']; ?></td>
							<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['estado']; ?></td>
							<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['prioridad']; ?></td>
							<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $dias_ent; ?></td>
						</tr>
						<?php
						//Determinar el color del siguiente renglon a dibujar
						$cont++;
						if($cont%2==0)
							$nom_clase = "renglon_blanco";
						else
							$nom_clase = "renglon_gris";
						
					}while($datos=mysql_fetch_array($rs)); ?>
				</table>
			</div>
			</body><?php
		}
		//Cerrar la conexion con la BD		
		mysql_close($conn);
	}
	
	function guardarRepDetalleReq($hdn_consulta,$hdn_nomReporte,$hdn_msg){
		include_once("../../includes/func_fechas.php");
		//Esribir datos de la pagina en excel
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=$hdn_nomReporte.xls");
		?>
		<head>
			<style>					
				<!--
				body { font-family:Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; }
				/*En este formato  se establece el tamaño,tipo de letra,color de fondo que ocupa la columna principal de una tabla*/
				.nombres_columnas { font-family: Arial, Helvetica, sans-serif; font-size: 13px; color: #FFFFFF; background-color: #9BBA59; font-weight: bold; 
				border-top-width: medium; border-right-width: thin;
				border-bottom-width: medium; border-left-width: thin; border-top-style: solid; border-right-style: none; border-bottom-style: solid; 
				border-left-style: none; 
				border-top-color: #000000; border-bottom-color: #000000; }
				/*En este formato  se establece el tamaño,tipo de letra,color de fondo que se ocupa en la fila principal para resaltar datos  de una tabla*/
				.nombres_filas { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #9BBB59; }
				/*En este formato  se establece el tamaño,tipo de letra,color de fondo que contendra datos  de una tabla*/
				.renglon_gris { font-family: Arial, Helvetica, sans-serif; font-size: 11px; color: #000000; background-color: #E7E7E7; }
				/*En este formato  se establece el tamaño,tipo de letra,color de fondo que contendra datos  de una tabla*/
				.renglon_blanco { font-family: Arial, Helvetica, sans-serif; font-size: 11px; color: #000000; background-color: #FFFFFF; } 
				#tabla { position:absolute; left:0px; top:0px; width:1111px; height:175px; z-index:5; }
				.texto_encabezado {font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #0000CC; }
				.sub_encabezado {font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #0000CC; }
				.titulo_tabla {font-family: Arial, Helvetica, sans-serif; font-weight: bold; font-size: 14px; }
				.borde_linea { border-top:3px; border-top-color:#4E6128; border-top-style:solid;}
				-->
			</style>
		</head>
		<?php
		$fecha_i = modFecha($_POST["txt_fecha_ini"],3);
		$fecha_f = modFecha($_POST["txt_fecha_fin"],3);
		$bd = $_POST["cmb_departamento"];
		$clave = $_POST["hdn_clave"];
		
		$conn=conecta("$bd");
		//Ejecutar la consulta
		$rs = mysql_query($hdn_consulta);
		//Mostrar los resultados obtenidos
		if($datos = mysql_fetch_array($rs)){?>							
			<body>
			<div id="tabla">				
				<table width="1100">					
					<tr>
						<td align="left" valign="baseline" colspan="3"><img src="http://<?php echo HOST; ?>/<?php echo SISAD; ?>/images/logo.png" width="150" height="65" 
						align="absbottom" /></td>
						<td valign="baseline" colspan="4">
							<div align="right"><span class="texto_encabezado">
								<strong>MANUAL  DE PROCEDIMIENTOS DE LA CALIDAD</strong><br><em>CONCRETO  LANZADO DE FRESNILLO, S.A DE C.V.</em>
							</span></div>
						</td>
					</tr>											
					<tr>
						<td colspan="7" align="center" class="borde_linea">
							<span class="sub_encabezado">CONFIDENCIAL, PROPIEDAD DE &ldquo;CONCRETO LANZADO DE FRESNILLO, S.A DE C.V.&rdquo; PROHIBIDA SU REPRODUCCI
							&Oacute;N TOTAL O PARCIAL</span>
						</td>
					</tr>					
					<tr>
						<td colspan="7">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="7">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="7" align="center" class="titulo_tabla"><?php echo $hdn_msg; ?></td>
					</tr>
					<tr>
						<td colspan="7">&nbsp;</td>
					</tr>			
					<tr>
						<td class='nombres_columnas' align='center'>ID REQUISICI&Oacute;N</td>
						<td class='nombres_columnas' align='center'>CANTIDAD</td>
						<td class='nombres_columnas' align='center'>UNIDAD DE MEDIDA</td>
						<td class='nombres_columnas' align='center'>DESCRIPCI&Oacute;N</td>
						<td class='nombres_columnas' align='center'>APLICACI&Oacute;N</td>
						<td class='nombres_columnas' align='center'>ESTADO</td>
						<td class='nombres_columnas' align='center'>TIEMPO DE ENTREGA</td>
					</tr>
					<?php
					$nom_clase = "renglon_gris";
					$cont = 1;
					do{
						$dias_ent = calcularDiasEntregaDetalleReq($datos["requisiciones_id_requisicion"],$bd,$datos["partida"]);
							if($datos['aplicacion'] != "")
								$aplicacion = $datos['aplicacion'];
							else
								$aplicacion = obtenerCentroCosto('control_costos','id_control_costos',$datos['id_control_costos']);
					?>
						<tr>
							<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['requisiciones_id_requisicion']; ?></td>
							<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['cant_req']; ?></td>
							<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['unidad_medida']; ?></td>
							<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['descripcion']; ?></td>
							<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $aplicacion; ?></td>
							<?php
							if($datos['estado'] == 1)
								echo "<td align='center' class='$nom_clase'>ENVIADA</td>";
							else if($datos['estado'] == 2)
								echo "<td align='center' class='$nom_clase'>PEDIDO</td>";
							else if($datos['estado'] == 3)
								echo "<td align='center' class='$nom_clase'>CANCELADA</td>";
							else if($datos['estado'] == 4)
								echo "<td align='center' class='$nom_clase'>COTIZANDO</td>";
							else if($datos['estado'] == 5)
								echo "<td align='center' class='$nom_clase'>EN PROCESO</td>";
							else if($datos['estado'] == 6)
								echo "<td align='center' class='$nom_clase'>EN TRANSITO</td>";
							else if($datos['estado'] == 7)
								echo "<td align='center' class='$nom_clase'>ENTREGADA</td>";
							else if($datos['estado'] == 8)
								echo "<td align='center' class='$nom_clase'>AUTORIZADA</td>";
							else if($datos['estado'] == 9)
								echo "<td align='center' class='$nom_clase'>NO AUTORIZADA</td>";
							echo "	<td align='center' class='$nom_clase'>$dias_ent</td>";
							?>
						</tr>
						<?php
						//Determinar el color del siguiente renglon a dibujar
						$cont++;
						if($cont%2==0)
							$nom_clase = "renglon_blanco";
						else
							$nom_clase = "renglon_gris";
						
					}while($datos=mysql_fetch_array($rs)); ?>
				</table>
			</div>
			</body><?php
		}
		//Cerrar la conexion con la BD		
		mysql_close($conn);
	}
	
	function consumoAceite($equipo,$fecha_ini,$fecha_fin,$aceite){
		$consumo_aceite = 0;
		$con_consumo = ("bd_mantenimiento");
		
		$stm_sql_consumo = "SELECT SUM(  `cantidad` ) AS cantidad_total
							FROM  `bitacora_aceite` 
							WHERE  `equipos_id_equipo` LIKE  '$equipo'
							AND  `fecha` 
							BETWEEN  '$fecha_ini'
							AND  '$fecha_fin'
							AND  `tipo_mov` =  'S'
							AND  `catalogo_aceites_id_aceite` =  '$aceite'";
		
		$rs_consumo = mysql_query($stm_sql_consumo);
		
		if($rs_consumo){
			$datos_consumo = mysql_fetch_array($rs_consumo);
			$consumo_aceite = $datos_consumo["cantidad_total"];
		}
		
		return $consumo_aceite;
	}
	
	function rendimientoAceite($equipo,$fecha_ini,$fecha_fin,$aceite){
		$rendimiento = 0; $cantidad = 0;
		$inicial = 0; $final = 0;
		$con_consumo = ("bd_mantenimiento");
		
		$stm_sql_consumo = "SELECT cantidad, odometro_horometro, fecha
							FROM  `bitacora_aceite` 
							WHERE  `equipos_id_equipo` LIKE  '$equipo'
							AND  `fecha` 
							BETWEEN  '$fecha_ini'
							AND  '$fecha_fin'
							AND  `tipo_mov` =  'S'
							AND  `catalogo_aceites_id_aceite` =  '$aceite'
							ORDER BY fecha";
		
		$rs_consumo = mysql_query($stm_sql_consumo);
		
		if($rs_consumo){
			$i = 1;
			while($datos_consumo = mysql_fetch_array($rs_consumo)){
				if($i == 1)
					$inicial = $datos_consumo["odometro_horometro"];
				
				$cantidad += $datos_consumo["cantidad"];
				$final = $datos_consumo["odometro_horometro"];
				$i++;
			}
			if($cantidad != 0)
				$rendimiento = ($final - $inicial) / $cantidad;
		}
		
		return $rendimiento;
	}
	
	function consumoGasolina($equipo,$fecha_ini,$fecha_fin){
		$consumo_gasolina = 0;
		$con_consumo = ("bd_mantenimiento");
		
		$stm_sql_consumo = "SELECT SUM(  `cantidad` ) AS cantidad_total
							FROM  `bitacora_gasolina` 
							WHERE  `equipos_id_equipo` LIKE  '$equipo'
							AND  `fecha` 
							BETWEEN  '$fecha_ini'
							AND  '$fecha_fin'";
		
		$rs_consumo = mysql_query($stm_sql_consumo);
		
		if($rs_consumo){
			$datos_consumo = mysql_fetch_array($rs_consumo);
			$consumo_gasolina = $datos_consumo["cantidad_total"];
		}
		
		return $consumo_gasolina;
	}
	function rendimientoGasolina($equipo,$fecha_ini,$fecha_fin){
		$rendimiento = 0; $cantidad = 0;
		$inicial = 0; $final = 0;
		$con_consumo = ("bd_mantenimiento");
		
		$stm_sql_consumo = "SELECT cantidad, odometro, fecha
							FROM  `bitacora_gasolina` 
							WHERE  `equipos_id_equipo` LIKE  '$equipo'
							AND  `fecha` 
							BETWEEN  '$fecha_ini'
							AND  '$fecha_fin'
							ORDER BY fecha";
		
		$rs_consumo = mysql_query($stm_sql_consumo);
		
		if($rs_consumo){
			$i = 1;
			while($datos_consumo = mysql_fetch_array($rs_consumo)){
				if($i == 1)
					$inicial = $datos_consumo["odometro"];
				
				$cantidad += $datos_consumo["cantidad"];
				$final = $datos_consumo["odometro"];
				$i++;
			}
			if($cantidad != 0)
				$rendimiento = ($final - $inicial) / $cantidad;
		}
		
		return $rendimiento;
	}
	function costoGasolina($equipo,$fecha_ini,$fecha_fin){
		$costo_gasolina = 0;
		$con_costo = ("bd_mantenimiento");
		
		$stm_sql_costo = "SELECT SUM(  `cantidad` ) AS cantidad_total, costo_litro
							FROM  `bitacora_gasolina` 
							WHERE  `equipos_id_equipo` LIKE  '$equipo'
							AND  `fecha` 
							BETWEEN  '$fecha_ini'
							AND  '$fecha_fin'
							GROUP BY costo_litro";
		
		$rs_costo = mysql_query($stm_sql_costo);
		
		if($rs_costo){
			while($datos_costo = mysql_fetch_array($rs_costo)){
				$costo_gasolina = $datos_costo["cantidad_total"] * $datos_costo["costo_litro"];
			}
		}
		
		return $costo_gasolina;
	}
	
	function calcularDiasEntregaReq($id_requisicion,$bd){
		$dias = "NO APLICA";
		$conec = conecta("$bd");
		$stm_sql = "SELECT *, DATEDIFF( CURDATE( ) , fecha ) AS dias_dif 
					FROM requisiciones
					JOIN bd_compras.bitacora_movimientos ON id_operacion = id_requisicion
					WHERE id_requisicion LIKE  '$id_requisicion'
					AND tipo_operacion LIKE  '%CambiaEstado%'
					AND estado = 'EN TRANSITO'
					ORDER BY fecha DESC 
					LIMIT 1";
		$rs = mysql_query($stm_sql);
		if($rs){
			if($datos = mysql_fetch_array($rs)){
				$dias = $datos["cant_entrega"];
				if($datos["tipo_entrega"] == "SEMANAS")
					$dias = $dias * 7;
				else if($datos["tipo_entrega"] == "MESES")
					$dias = $dias * 30;
				$dias = $dias - $datos["dias_dif"];
				if($dias == 0)
					$dias = "HOY";
				else if($dias < 0)
					$dias = "EXPIRADO HACE ".abs($dias)." DIAS";
				else
					$dias = $dias." DIAS";
			}
		}
		return $dias;
	}
	
	function calcularDiasEntregaDetalleReq($id_requisicion,$bd,$partida){
		$dias = "NO APLICA";
		$conec = conecta("$bd");
		$stm_sql = "SELECT * , DATEDIFF( CURDATE( ) , fecha_estado ) AS dias_dif
					FROM detalle_requisicion
					WHERE requisiciones_id_requisicion LIKE  '$id_requisicion'
					AND estado =  '6'
					AND partida =  '$partida'
					ORDER BY fecha_estado DESC ";
		$rs = mysql_query($stm_sql);
		if($rs){
			if($datos = mysql_fetch_array($rs)){
				$dias = $datos["cant_entrega"];
				if($datos["tipo_entrega"] == "SEMANAS")
					$dias = $dias * 7;
				else if($datos["tipo_entrega"] == "MESES")
					$dias = $dias * 30;
				$dias = $dias - $datos["dias_dif"];
				if($dias == 0)
					$dias = "HOY";
				else if($dias < 0)
					$dias = "EXPIRADO HACE ".abs($dias)." DIAS";
				else
					$dias = $dias." DIAS";
			}
		}
		return $dias;
	}
	
	function obtenerCentroCosto($tabla,$busq,$valor){
		$dat = $valor; 
		$con = conecta("bd_recursos");
		$stm_sql = "SELECT descripcion
					FROM  `$tabla` 
					WHERE  `$busq` LIKE  '$valor'";
		$rs = mysql_query($stm_sql);
		if($rs){
			if($datos = mysql_fetch_array($rs)){
				$dat = $datos[0];
			}
		}
		mysql_close($con);
		return $dat;
	}
?>
