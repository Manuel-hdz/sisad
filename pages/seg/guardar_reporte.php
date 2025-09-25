<?php 
	/**
	  * Nombre del Módulo: Seguridad Industrial	                                              
	  * Nombre Programador: Daisy Adriana Martínez Fernández	                         
	  * Fecha: 17/07/2011                                      			
	  * Descripción: Este archivo contiene funciones para almacenar la información en una hoja de calculo de excel de las consultas realizadas 
	  **/
	 /**
      * Listado del contenido del programa                                            
      *   Includes: 
	        1. Modulo de conexion con la base de datos
			2. Modulo de operaciones con la BD*/
			include("../../includes/conexion.inc");
			include("../../includes/op_operacionesBD.php");
			include("../../includes/func_fechas.php");
	/**   Código en: pages\alm\guardar_reporte.php                                   
      **/
	
	if(isset($_POST['hdn_consulta'])){
		//Ubicacion de las imagenes que estan contenidas en los encabezados
		define("HOST", $_SERVER['HTTP_HOST']);
		//Obtener el nombre del Nombre de la Carpeta Raíz donde se encontrará almacenado el SISAD
		$raiz = explode("/",$_SERVER['PHP_SELF']);
		define("SISAD",$raiz[1]);	
		
		switch($hdn_tipoReporte){
			case "reporteBitacoraResiduos":
				guardarRepBitResiduos($hdn_consulta, $hdn_nomReporte, $hdn_msg, $hdn_tipoResiduo, $hdn_nombre);
			break;
			case "reporteIncidentesAccidentes":
				guardarRepAccInc($hdn_consulta, $hdn_msg, $hdn_tipoReporte, $hdn_nomReporte);
			break;
			case "reporteRecorridosSeguridad":
				guardaRepRecSeg($hdn_consulta, $hdn_msg, $hdn_tipoReporte, $hdn_nomReporte);
			break;
			case "reporte_requisiciones":
				guardarRepRequisiciones($hdn_consulta,$hdn_nomReporte,$hdn_msg);
			break;
			case "reporte_detallerequisiciones":
				guardarRepDetalleReq($hdn_consulta,$hdn_nomReporte,$hdn_msg);
			break;
		}										
	}
	
	if(isset($_POST["hdn_patron"])){
		//Ubicacion de las imagenes que estan contenidas en los encabezados
		define("HOST", $_SERVER['HTTP_HOST']);
		//Obtener el nombre del Nombre de la Carpeta Raíz donde se encontrará almacenado el SISAD
		$raiz = explode("/",$_SERVER['PHP_SELF']);
		define("SISAD",$raiz[1]);
		exportarEmpleados($hdn_patron);
	}

	//Esta funcion exporte el REPORTE ASISTENCIA a un archivo de excel
	function guardarRepBitResiduos($hdn_consulta, $hdn_nomReporte, $hdn_msg, $hdn_tipoResiduo, $hdn_nombre){
		//Esribir datos de la pagina en excel
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=$hdn_nomReporte.xls");

		//Realizar la conexion a la BD 
		$conn = conecta("bd_seguridad");
		
		//Verificamos el residuo para ver la unidad
		if($hdn_tipoResiduo=="ACEITE"){
			$unidad = "Lts";
		}
		else{
			$unidad = "Kgs";
		}
		//Variable que nos permitira conocer la cantidad total del residuo
		$cantTotal =0;
		//Ejecutar la sentencia y almacena los datos de la consulta en la variable $rs (result set)
		$rs_datos = mysql_query($hdn_consulta);
		
		//Verificar que la consulta tenga datos
		if($datos=mysql_fetch_array($rs_datos)){
			//Definir el estilo de la tabla y el encabezado que aparecera sobre la tabla?>
			<head>
				<style>					
					<!--
					body { font-family:Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; }
					/*En este formato  se establece el tamaño,tipo de letra,color de fondo que ocupa la columna principal de una tabla*/
					.nombres_columnas { font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #FFFFFF; background-color: #9BBA59; font-weight: bold; 
					border-top-width: medium; border-right-width: medium;
					border-bottom-width: medium; border-left-width: medium; border-left-style:solid; border-left-color:#000000; border-right-color:#000000;
					border-right-style:solid; border-top-style: solid; 	border-right-style: solid; border-bottom-style: 	solid; border-left-style: solid; 
					border-top-color: #000000; border-bottom-color: #000000; vertical-align:middle; }
					/*En este formato  se establece el tamaño,tipo de letra,color de fondo que se ocupa en la fila principal para resaltar datos  de una tabla*/
					.nombres_filas { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #9BBB59; vertical-align:middle;}
					/*En este formato  se establece el tamaño,tipo de letra,color de fondo que contendra datos  de una tabla*/
					.renglon_gris { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #E7E7E7; vertical-align:middle;}
					/*En este formato  se establece el tamaño,tipo de letra,color de fondo que contendra datos  de una tabla*/
					.renglon_blanco { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #FFFFFF; } 
					#tabla { position:absolute; left:0px; top:0px; width:1111px; height:175px; z-index:5; }
					.texto_encabezado {font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #0000CC; vertical-align:middle;}
					.sub_encabezado {font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #0000CC; vertical-align:middle;}
					.titulo_tabla {font-family: Arial, Helvetica, sans-serif; font-weight: bold; font-size: 14px; vertical-align:middle;}
					.borde_linea { border-top:3px; border-top-color:#4E6128; border-top-style:solid;}
					.msje_incorrecto { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #FF0000; font-weight: bold;}
					.msje_correcto { font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #009900; font-weight: bold;}
					.nombres_filas2 { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; }
					.Estilo1 {font-size: 10px;font-weight: bold;}
					-->
				</style>
			</head>											
			<body>
			<div id="tabla">				
				<table width="100%">					
					<tr>
						<td height="69" colspan="2" align="left" valign="baseline"><img src="http://<?php echo HOST; ?>/<?php echo SISAD; ?>/images/logo.png" width="118" 
                        height="58" 
                        align="absbottom" /></td>
						<td colspan="14">&nbsp;</td>
					  <td colspan="3">
							<div align="right"></div></td>
					</tr>											
					<tr>
						<td colspan="19" align="center" class="borde_linea">
							<span class="sub_encabezado">CONFIDENCIAL, PROPIEDAD DE &ldquo;CONCRETO LANZADO DE FRESNILLO, S.A DE C.V.&rdquo; PROHIBIDA SU REPRODUCCI
                            &Oacute;N TOTAL O PARCIAL</span>						</td>
					</tr>					
					<tr>
						<td colspan="19">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="19">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="19" align="center" class="titulo_tabla"><?php echo $hdn_msg; ?></td>
					</tr>
					<tr>
						<td colspan="19">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="19" align='center' class='nombres_columnas'>ALMACENAMIENTO TEMPORAL DE RESIDUOS PELIGROSOS </td>
					</tr>
					<tr>
						<td colspan="5" align='center' class='nombres_columnas'>GENERACI&Oacute;N</td>
						<td colspan="4" align='center' class='nombres_columnas'>ALMAC&Eacute;N TEMPORAL</td>
						<td colspan="4" align='center' class='nombres_columnas'>
							<p>FASE DE MANEJO SIGUIENTE A LA SALIDA DEL ALMAC&Eacute;N TEMPORAL</p>
						    <p>PRESTADOR DE SERVICIOS </p>						</td>
						<td colspan="5" align='center' class='nombres_columnas'>CARACTERISTICAS DE PELIGROSIDAD DEL RESIDUO-CODIGO DE PELIGROSIDAD (CPR) ART. 71 FRACCION INCISO (b)</td>
						<td  align='center' class='nombres_columnas'>FASE DE MANEJO SOGUIENTE A LA SALIDA DEL &Aacute;REA DE TRANSFERENCIA O RESGUARDO ART.71 FRACCION I INCIDO&euro;</td>
					</tr>
								
					<tr>
						<td align='center' class='nombres_columnas'>NO.</td>
						<td align='center' class='nombres_columnas'>&Aacute;REA</td>
						<td align='center' class='nombres_columnas'>NOMBRE RESIDUO</td>
						<td align='center' class='nombres_columnas'>CANTIDAD GENERADA</td>
						<td align='center' class='nombres_columnas'>UNIDAD(<?php echo $unidad;?>)</td>
						<td align='center' class='nombres_columnas'>NOMBRE Y FIRMA ENTREGA</td>
						<td align='center' class='nombres_columnas'>NOMBRE Y FIRMA RECIBE</td>
						<td align='center' class='nombres_columnas'>FECHA INGRESO</td>
						<td align='center' class='nombres_columnas'>FECHA SALIDA</td>
						<td align='center' class='nombres_columnas'>RAZ&Oacute;N SOCIAL</td>
						<td align='center' class='nombres_columnas'>NO. MANIFIESTO</td>
						<td align='center' class='nombres_columnas'>NO. AUTORIZACI&Oacute;N</td>
						<td align='center' class='nombres_columnas'>NOMBRE TRANSPORTISTA</td>
						<td align='center' class='nombres_columnas'>C</td>
						<td align='center' class='nombres_columnas'>R</td>
						<td align='center' class='nombres_columnas'>E</td>
						<td align='center' class='nombres_columnas'>T</td>
						<td align='center' class='nombres_columnas'>I</td>
						<td align='center' class='nombres_columnas'>MANEJO</td>
					</tr><?php
				$nom_clase = "renglon_gris";
				$cont = 1;
				do{	?>
				<tr>
					<td class='<?php echo $nom_clase;?>' align='center'><?php echo $cont;?></td>
					<td class='<?php echo $nom_clase;?>' align='center'><?php echo $datos['area'];?></td>
					<td class='<?php echo $nom_clase;?>' align='center'><?php echo $hdn_tipoResiduo;?></td>
					<td class='<?php echo $nom_clase;?>' align='center'><?php echo $datos['cantidad'];?></td>
					<td class='<?php echo $nom_clase;?>' align='center'><?php echo $datos['tipo_unidad'];?></td>
					<td class='<?php echo $nom_clase;?>' align='center'><?php echo $datos['nom_firm_entrega'];?></td>
					<td class='<?php echo $nom_clase;?>' align='center'><?php echo $datos['nom_firm_recibe'];?></td>
					<td class='<?php echo $nom_clase;?>' align='center'><?php echo modFecha($datos['fecha_ingreso'],1);?></td>
					<td class='<?php echo $nom_clase;?>' align='center'><?php echo modFecha($datos['fecha_salida'],1);?></td>
					<td class='<?php echo $nom_clase;?>' align='center'><?php echo $datos['razon_social'];?></td>
					<td class='<?php echo $nom_clase;?>' align='center'><?php echo $datos['num_manifiesto'];?></td>
					<td class='<?php echo $nom_clase;?>' align='center'><?php echo $datos['num_autorizacion'];?></td>
					<td class='<?php echo $nom_clase;?>' align='center'><?php echo $datos['nom_transportista']?></td><?php 
					if($datos['pel_corrosivo']=='1'){?>
						<td class='<?php echo $nom_clase;?>' align='center'><span class='msje_correcto'><strong>&radic;</strong></span></td><?php 
					}
					else{?>
						<td class='<?php echo $nom_clase;?>' align='center'> <span  class='msje_incorrecto'><strong>X</strong></span></td><?php 
					}
					if($datos['pel_reactivo']=='1'){?>
						<td class='<?php echo $nom_clase;?>' align='center'> <span  class='msje_correcto'><strong>&radic;</strong></span></td><?php
					}
					else{?>
						<td class='<?php echo $nom_clase;?>' align='center'> <span  class='msje_incorrecto'><strong>X</strong></span></td><?php 
					}
					if($datos['pel_explosivo']=='1'){?>
						<td class='<?php echo $nom_clase;?>' align='center'> <span  class='msje_correcto'><strong>&radic;</strong></span></td><?php 
					}
					else{?>
						<td class='<?php echo $nom_clase;?>' align='center'> <span  class='msje_incorrecto'><strong>X</strong></span></td><?php 
					}
					if($datos['pel_toxico']=='1'){?>
						<td class='<?php echo $nom_clase;?>' align='center'> <span  class='msje_correcto'><strong>&radic;</strong></span></td><?php 
					}
					else{?>
						<td class='<?php echo $nom_clase;?>' align='center'> <span  class='msje_incorrecto'><strong>X</strong></span></td><?php 
					}
					if($datos['pel_inflamable']=='1'){?>
						<td class='<?php echo $nom_clase;?>' align='center'> <span  class='msje_correcto'><strong>&radic;</strong></span></td><?php 
					}
					else{?>
						<td class='<?php echo $nom_clase;?>' align='center'> <span  class='msje_incorrecto'><strong>X</strong></span></td><?php 
					}?>
					<td class='<?php echo $nom_clase;?>' align='center'><?php echo $datos['fase_salida'];?></td>					
				</tr>
				<?php
				$cantTotal = $cantTotal+$datos['tipo_unidad'];
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
					
			}while($datos=mysql_fetch_array($rs_datos)); ?>
				<tr>
					<td colspan="3"></td>
					<td align='center' class='nombres_columnas'>TOTAL DEL RESIDUO</td>
					<td class='<?php echo $nom_clase;?>' align='center'><?php echo $cantTotal;?></td>
				</tr>
				<tr><td colspan="18">&nbsp;</td></tr>
				<tr><td colspan="18">&nbsp;</td></tr>
				<tr><td colspan="18">&nbsp;</td></tr>
				<tr><td colspan="18">&nbsp;</td></tr>
				<tr><td colspan="18">&nbsp;</td></tr>
				<tr><td colspan="18">&nbsp;</td></tr>
				<tr><td colspan="18">&nbsp;</td></tr>
				<tr>
					<td colspan="9">&nbsp;</td>
					<td colspan="4" align='center' class='nombres_columnas'>NOMBRE Y FIRMA RESPONSABLE BIT&Aacute;CORA</td>
					<td colspan="6">&nbsp;</td>
				</tr>
				<tr><td colspan="18">&nbsp;</td></tr>
				<tr><td colspan="18">&nbsp;</td></tr>
				<tr>
					<td colspan="9">&nbsp;</td>
					<td  colspan="4"><div align="center"><strong>_____________________________________________</strong></div></td>
					<td colspan="6">&nbsp;</td>				</tr>
				<tr>
					<td colspan="8">&nbsp;</td>
					<td  colspan="4" class='Estilo1'><div align="center"><?php echo $hdn_nombre;?></div></td>
					<td colspan="6">&nbsp;</td>
				</tr>
				</table>
			</div>
			</body><?php
		}
		
		//Cerrar la conexion con la BD		
		mysql_close($conn);		
	}//Fin de function 
	
	
		
	//Esta funcion exporte el REPORTE ASISTENCIA a un archivo de excel
	function guardaRepRecSeg($hdn_consulta, $hdn_msg, $hdn_tipoReporte, $hdn_nomReporte){
		//Esribir datos de la pagina en excel
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=$hdn_nomReporte.xls");
			
		//Realizar la conexion a la BD 
		$conn = conecta("bd_seguridad");
				
		//Ejecutar la sentencia y almacena los datos de la consulta en la variable $rs (result set)
		$rs_datos = mysql_query($hdn_consulta);
		
		//Verificar que la consulta tenga datos
		if($datos=mysql_fetch_array($rs_datos)){
			//Definir el estilo de la tabla y el encabezado que aparecera sobre la tabla?>
			<head>
				<style>					
					<!--
					body { font-family:Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; }
					/*En este formato  se establece el tamaño,tipo de letra,color de fondo que ocupa la columna principal de una tabla*/
					.nombres_columnas { font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #FFFFFF; background-color: #9BBA59; font-weight: bold; 
					border-top-width: medium; border-right-width: medium;
					border-bottom-width: medium; border-left-width: medium; border-left-style:solid; border-left-color:#000000; border-right-color:#000000;
					border-right-style:solid; border-top-style: solid; 	border-right-style: solid; border-bottom-style: 	solid; border-left-style: solid; 
					border-top-color: #000000; border-bottom-color: #000000; }
					/*En este formato  se establece el tamaño,tipo de letra,color de fondo que se ocupa en la fila principal para resaltar datos  de una tabla*/
					.nombres_filas { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #9BBB59; vertical-align:middle;}
					/*En este formato  se establece el tamaño,tipo de letra,color de fondo que contendra datos  de una tabla*/
					.renglon_gris { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #E7E7E7; vertical-align:middle;}
					/*En este formato  se establece el tamaño,tipo de letra,color de fondo que contendra datos  de una tabla*/
					.renglon_blanco { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #FFFFFF; vertical-align:middle;} 
					#tabla { position:absolute; left:0px; top:0px; width:1111px; height:175px; z-index:5; }
					.texto_encabezado {font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #0000CC; vertical-align:middle}
					.sub_encabezado {font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #0000CC; vertical-align:middle;}
					.titulo_tabla {font-family: Arial, Helvetica, sans-serif; font-weight: bold; font-size: 14px; vertical-align:middle;}
					.borde_linea { border-top:3px; border-top-color:#4E6128; border-top-style:solid;}
					.msje_incorrecto { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #FF0000; font-weight: bold;}
					.msje_correcto { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #009900; font-weight: bold;}
					.nombres_filas2 { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; }
					.Estilo1 {font-size: 10px;font-weight: bold;}
					-->
				</style>
			</head>											
			<body>
			<div id="tabla">				
				<table width="100%">					
					<tr>
						<td height="65" colspan="3" align="left" valign="baseline"><img src="http://<?php echo HOST; ?>/<?php echo SISAD; ?>/images/logo.png" width="118" 
                        height="58" 
                        align="absbottom" /></td>
						<td colspan="4">&nbsp;</td>
						<td colspan="3">
							<div align="right"></div></td>
					</tr>											
					<tr>
						<td colspan="9" align="center" class="borde_linea">
							<span class="sub_encabezado">CONFIDENCIAL, PROPIEDAD DE &ldquo;CONCRETO LANZADO DE FRESNILLO, S.A DE C.V.&rdquo; PROHIBIDA SU REPRODUCCI
                            &Oacute;N TOTAL O PARCIAL</span>						</td>
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
					<?php
				$nom_clase = "renglon_gris";
				$cont = 1;
				$clavePlan = "";
				do{	?>
					<tr><?php 	$nom_clase = "renglon_gris";//Ponemos el color del renglon en gris ya que este solo sera un registro y no cambiara de color?>
						<td colspan="9" class="nombres_columnas" align="center">RECORRIDO DE SEGURIDAD CON CLAVE <?php echo $datos['id_recorrido'];?></td>
					</tr>							
					<tr>
						<td class='nombres_columnas' colspan="2" align='center'>NO.</td>
						<td class='nombres_columnas' colspan="2" align='center'>FECHA</td>
						<td class='nombres_columnas' colspan="2" align='center'>RESPONSABLE</td>
						<td class='nombres_columnas' colspan="3" align='center'>OBSERVACIONES</td>
					</tr>
					<tr>
						<td class='<?php echo $nom_clase;?>' colspan="2" align='center'><?php echo $cont;?></td>
						<td class='<?php echo $nom_clase;?>' colspan="2" align='center'><?php echo modFecha($datos['fecha'],1);?></td>
						<td class='<?php echo $nom_clase;?>' colspan="2" align='center'><?php echo $datos['responsable'];?></td>	
						<td class='<?php echo $nom_clase;?>' colspan="3" align='center'><?php echo $datos['observaciones'];?></td>
					</tr>
					<tr><td colspan="9">&nbsp;</td></tr>
					<tr>
						<td>&nbsp;</td>
						<td colspan="6" class="nombres_columnas" align="center">REGISTRO DE ANOMALIAS PARA EL RECORRIDO NO. <?php echo $datos['id_recorrido'];?></td>
					</tr>	
					<tr>
						<td>&nbsp;</td>
						<td class='nombres_columnas' align='center'>NO.</td>
						<td class='nombres_columnas' align='center'>&Aacute;REA</td>
						<td class='nombres_columnas' align='center'>LUGAR</td>
						<td class='nombres_columnas' align='center'>ANOMAL&Iacute;A</td>
						<td class='nombres_columnas' align='center'>CORRECCI&Oacute;N ANOMAL&Iacute;A</td>
						<td class='nombres_columnas' align='center'>FECHA</td>
					</tr><?php 
						//Declaramos el color de los renglones; empieza en gris
						$nom_claseInt = "renglon_gris";
						//Declaramos contador interno
						$contInterno = 1;
						//Guardamos la clave del plan para hacer la comparación posteriormente
						$clavePlan = $datos['id_recorrido'];
						//Consulta que permite verificar las anomalias registradas 
						$stm_sqlAn = "SELECT * FROM detalle_recorridos_seguridad WHERE recorridos_seguridad_id_recorrido='$clavePlan' ORDER BY anomalia";
						//Ejecutamos la sentencia Previamente creada
						$rs2=mysql_query($stm_sqlAn);
						//Comprobamos si exisitieron resultados
						if($arrAn=mysql_fetch_array($rs2)){
							do{?>
								<tr>
									<td>&nbsp;</td>
									<td class='<?php echo $nom_claseInt;?>' align='center'><?php echo $contInterno;?></td>
									<td class='<?php echo $nom_claseInt;?>' align='center'><?php echo $arrAn['area'];?></td>
									<td class='<?php echo $nom_claseInt;?>' align='center'><?php echo $arrAn['lugar'];?></td>
									<td class='<?php echo $nom_claseInt;?>' align='center'><?php echo $arrAn['anomalia'];?></td>
									<td class='<?php echo $nom_claseInt;?>' align='center'><?php echo $arrAn['correccion_anomalia'];?></td>
									<td class='<?php echo $nom_claseInt;?>' align='center'><?php echo modFecha($arrAn['fecha'],1);?></td>
								</tr><?php 
								//Incrementamos el contador interno
								$contInterno++;
								//Verificamos que color corresponde al Renglon
								if($contInterno%2==0)
									$nom_claseInt = "renglon_blanco";
								else
									$nom_claseInt = "renglon_gris";
							}while($arrAn=mysql_fetch_array($rs2));
						}
					?>
				<tr>
					<td colspan="9">&nbsp;</td>
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
	}//Fin de function guardarRepAsistencia($hdn_consulta,$hdn_nomReporte,$hdn_msg ,$hdn_fechaIni, $hdn_fechaFin, $hdn_diferencia)
	
	//Esta funcion exporte el REPORTE ASISTENCIA a un archivo de excel
	function guardarRepAccInc($hdn_consulta, $hdn_msg, $hdn_tipoReporte, $hdn_nomReporte){
		//Esribir datos de la pagina en excel
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=$hdn_nomReporte.xls");
			
		//Realizar la conexion a la BD 
		$conn = conecta("bd_seguridad");
				
		//Ejecutar la sentencia y almacena los datos de la consulta en la variable $rs (result set)
		$rs_datos = mysql_query($hdn_consulta);
		
		//Verificar que la consulta tenga datos
		if($datos=mysql_fetch_array($rs_datos)){
			//Definir el estilo de la tabla y el encabezado que aparecera sobre la tabla?>
			<head>
				<style>					
					<!--
					body { font-family:Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; }
					/*En este formato  se establece el tamaño,tipo de letra,color de fondo que ocupa la columna principal de una tabla*/
					.nombres_columnas { font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #FFFFFF; background-color: #9BBA59; font-weight: bold; 
					border-top-width: medium; border-right-width: medium;
					border-bottom-width: medium; border-left-width: medium; border-left-style:solid; border-left-color:#000000; border-right-color:#000000;
					border-right-style:solid; border-top-style: solid; 	border-right-style: solid; border-bottom-style: 	solid; border-left-style: solid; 
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
					.msje_incorrecto { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #FF0000; font-weight: bold;}
					.msje_correcto { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #009900; font-weight: bold;}
					.nombres_filas2 { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; }
					.Estilo1 {font-size: 10px;font-weight: bold;}
					-->
				</style>
			</head>											
			<body>
			<div id="tabla">				
				<table width="100%">					
					<tr>
						<td height="65" colspan="3" align="left" valign="baseline"><img src="http://<?php echo HOST; ?>/<?php echo SISAD; ?>/images/logo.png" width="118" 
                        height="58" 
                        align="absbottom" /></td>
						<td colspan="4">&nbsp;</td>
						<td colspan="3">
							<div align="right"></div></td>
					</tr>											
					<tr>
						<td colspan="9" align="center" class="borde_linea">
							<span class="sub_encabezado">CONFIDENCIAL, PROPIEDAD DE &ldquo;CONCRETO LANZADO DE FRESNILLO, S.A DE C.V.&rdquo; PROHIBIDA SU REPRODUCCI
                            &Oacute;N TOTAL O PARCIAL</span>						</td>
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
						<td class='nombres_columnas' align='center'>NO. ACCIDENTE</td>
						<td class='nombres_columnas' align='center'>CLAVE INFORME</td>
						<td class='nombres_columnas' align='center'>EMPLEADO</td>
						<td class='nombres_columnas' align='center'>&Aacute;REA DE TRABAJO</td>
						<td class='nombres_columnas' align='center'>PUESTO</td>
						<td class='nombres_columnas' align='center'>TURNO</td>
						<td class='nombres_columnas' align='center'>TIPO DE INFORME</td>
						<td class='nombres_columnas' align='center'>LUGAR ACCIDENTE</td>
						<td class='nombres_columnas' align='center'>FECHA ACCIDENTE</td>
					</tr><?php
				$nom_clase = "renglon_gris";
				$cont = 1;
				do{	
					//Obtenemos el nombre del Empleado
					$nombreEmpleado = obtenerNombreEmpleado($datos['empleados_rfc_empleado']);?>
				<tr>
					<td class='<?php echo $nom_clase;?>' align='center'><?php echo $cont;?></td>
					<td class='<?php echo $nom_clase;?>' align='center'><?php echo $datos['id_informe'];?></td>
					<td class='<?php echo $nom_clase;?>' align='center'><?php echo $nombreEmpleado;?></td>
					<td class='<?php echo $nom_clase;?>' align='center'><?php echo $datos['area'];?></td>
					<td class='<?php echo $nom_clase;?>' align='center'><?php echo $datos['puesto'];?></td>
					<td class='<?php echo $nom_clase;?>' align='center'><?php echo $datos['turno'];?></td>
					<td class='<?php echo $nom_clase;?>' align='center'><?php echo $datos['tipo_informe'];?></td>
					<td class='<?php echo $nom_clase;?>' align='center'><?php echo $datos['area_acci'];?></td>
					<td class='<?php echo $nom_clase;?>' align='center'><?php echo modFecha($datos['fecha_accidente'],1);?></td>
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
	}//Fin de function guardarRepAsistencia($hdn_consulta,$hdn_nomReporte,$hdn_msg ,$hdn_fechaIni, $hdn_fechaFin, $hdn_diferencia)
	
	//Esta funcion exporta el catalogo de Empleados segun los datos seleccionados por el usuario
	function exportarEmpleados($patron){
		$sql="SELECT *";
		//array con los nombres de las columnas
		$nomCols=array();
		//array con los nombres de los campos segun la BD
		$nomCampos=array();
		foreach($_POST as $ind => $value){
			if(substr($ind,0,7)=="ckb_col"){
				switch($value){
					case "nombreCompleto":
						//Agregar los titulos de las columnas
						$nomCols[]="NOMBRE";
						$nomCols[]="APELLIDO PATERNO";
						$nomCols[]="APELLIDO MATERNO";
						//Obtener el nombre de los campos
						$nomCampos[]="nombre";
						$nomCampos[]="ape_pat";
						$nomCampos[]="ape_mat";
					break;
					case "antiguedad":
						//Agregar los titulos de las columnas
						$nomCols[]="ANTIG&Uuml;EDAD";
						$nomCampos[]="antiguedad";
					break;
					case "fechaNacimiento":
						//Agregar los titulos de las columnas
						$nomCols[]="FECHA DE NACIMIENTO";
						$nomCampos[]="fechaNacimiento";
					break;
					default:
						//Obtener el nombre de los campos
						$nomCampos[]="$value";
						//Ciclo para obtener los titulos de las Columnas
						switch($value){
							case "rfc_empleado":
								$nomCols[]="RFC";
							break;
							case "curp":
								$nomCols[]="CURP";
							break;
							case "id_empleados_empresa":
								$nomCols[]="ID EMPRESA";
							break;
							case "no_ss":
								$nomCols[]="NO. SEGURO SOCIAL";
							break;
							case "puesto":
								$nomCols[]="PUESTO";
							break;
							case "area":
								$nomCols[]="&Aacute;REA";
							break;
						}
					break;
				}
			}
		}
		//Concatenarle la tabla de busqueda
		$sql.=" FROM empleados";
		//Verificamos bajo que patron se esta pidiendo hacer la consulta
		if ($patron==1){
			//Creamos la sentencia SQL para mostrar los datos del empleado con el nombre que llega en el txt_nombre via POST
			$sql.=" WHERE CONCAT(nombre,' ',ape_pat,' ',ape_mat)='$_POST[hdn_nombre]' AND estado_actual = 'ALTA'";
			//Creamos el titulo de la tabla
			$titulo="Datos del Empleado <em>".$_POST["hdn_nombre"]."</em>";
		}
		if ($patron==2){
			$sql.=" WHERE estado_actual = 'ALTA'";
			//Creamos el titulo de la tabla
			$titulo="Datos de Todos los Empleados";
		}
		if ($patron==3){
			//Creamos la sentencia SQL para mostrar los datos de los empleados que estan en el área que llega via POST
			$sql.=" WHERE area='$_POST[hdn_area]' AND estado_actual = 'ALTA'";
			//Creamos el titulo de la tabla
			$titulo="Datos de los Empleados del &Aacute;rea <em><u>".$_POST["hdn_area"]."</u></em>";
		}
		if ($patron==4){
			//Creamos la sentencia SQL para mostrar los datos del empleado con el nombre que llega en el txt_nombre via POST
			$sql.=" WHERE CONCAT(nombre,' ',ape_pat,' ',ape_mat)='$_POST[hdn_nombre]' AND estado_actual = 'BAJA'";
			//Creamos el titulo de la tabla
			$titulo="Datos del Empleado Baja <em>".$_POST["hdn_nombre"]."</em>";
		}
		if ($patron==5){
			$sql.=" WHERE estado_actual = 'BAJA'";
			//Creamos el titulo de la tabla
			$titulo="Datos de Todos los Empleados Baja";
		}
		$cantCols=count($nomCols);
		//Esribir datos de la pagina en excel
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=listaTrabajadores.xls");	
		//Realizar la conexion a la BD 
		$conn = conecta("bd_recursos");
		//Ejecutar la sentencia y almacena los datos de la consulta en la variable $rs (result set)
		$rs_datos = mysql_query($sql);
		echo $sql;
		//Verificar que la consulta tenga datos
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
					border-top-color: #000000; border-bottom-color: #000000; vertical-align:middle;}
					/*En este formato  se establece el tamaño,tipo de letra,color de fondo que se ocupa en la fila principal para resaltar datos  de una tabla*/
					.nombres_filas { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #9BBB59; vertical-align:middle;}
					/*En este formato  se establece el tamaño,tipo de letra,color de fondo que contendra datos  de una tabla*/
					.renglon_gris { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #E7E7E7; vertical-align:middle;}
					/*En este formato  se establece el tamaño,tipo de letra,color de fondo que contendra datos  de una tabla*/
					.renglon_blanco { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #FFFFFF; vertical-align:middle;} 
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
						<td colspan="<?php echo $cantCols-5;?>">&nbsp;</td>
						<td valign="baseline" colspan="3">
							<div align="right"><span class="texto_encabezado">
								<strong>MANUAL  DE PROCEDIMIENTOS DE LA CALIDAD</strong><br><em>CONCRETO  LANZADO DE FRESNILLO, S.A DE C.V.</em>
							</span></div>
						</td>
					</tr>											
					<tr>
						<td colspan="<?php echo $cantCols?>" align="center" class="borde_linea">
							<span class="sub_encabezado">CONFIDENCIAL, PROPIEDAD DE &ldquo;CONCRETO LANZADO DE FRESNILLO, S.A DE C.V.&rdquo; PROHIBIDA SU REPRODUCCI
                            &Oacute;N TOTAL O PARCIAL</span>
						</td>
					</tr>					
					<tr>
						<td colspan="<?php echo $cantCols?>">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="<?php echo $cantCols?>">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="<?php echo $cantCols?>" align="center" class="titulo_tabla"><?php echo $titulo; ?></td>
					</tr>
					<tr>
						<td colspan="<?php echo $cantCols?>">&nbsp;</td>
					</tr>			
					<tr>
						<?php
							//Dibujar las columnas con sus respectivos nombres
							foreach($nomCols as $ind => $value){
								echo "<td align='center' class='nombres_columnas'>$value</td>";
							}
						?>
      				</tr>
			<?php
				$nom_clase = "renglon_gris";
				$cont = 1;
				do{
					echo "<tr>";
					foreach($nomCampos as $ind => $value){
						//Antigüedad
						if($value=="antiguedad")
							echo "<td class='$nom_clase' align='center'>".round((restarFechas($datos["fecha_ingreso"],date("Y-m-d"))/365),2)." a&ntilde;os</td>";
						//Fecha de Nacimiento
						elseif($value=="fechaNacimiento")
							echo "<td class='$nom_clase' align='center'>".modFecha(calcularFecha(substr($datos["rfc_empleado"],4,6)),2)."</td>";
						else{
							echo "<td align='center' class='$nom_clase'>$datos[$value]</td>";
						}
					}
					echo "</tr>";
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
			<?php	
			}
		//Cerrar la conexion con la BD		
		mysql_close($conn);		
	}
	
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