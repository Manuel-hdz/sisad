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
	
	if(isset($_GET["tipoRep"])){
		//Ubicacion de las imagenes que estan contenidas en los encabezados
		define("HOST", $_SERVER['HTTP_HOST']);
		//Obtener el nombre del Nombre de la Carpeta Raíz donde se encontrará almacenado el SISAD
		$raiz = explode("/",$_SERVER['PHP_SELF']);
		define("SISAD",$raiz[1]);
		
		switch($_GET["tipoRep"]){
			case "RepTiempos":
				guardarReporteTiempos($_GET["fechaI"],$_GET["fechaF"],$_GET["turno"],$_GET["equipo"]);
			break;
			case "RepEstatus":
				guardarReporteStatus($_GET["fechaI"],$_GET["fechaF"],$_GET["turno"],$_GET["equipo"]);
			break;
			case "RepEquipos":
				guardarReporteEquipos($_GET["patron"],$_GET["valPatron"]);
			break;
		}
	}
	
	if(isset($_POST["hdn_divRepProgMtto"])){
		//Ubicacion de las imagenes que estan contenidas en los encabezados
		define("HOST", $_SERVER['HTTP_HOST']);
		//Obtener el nombre del Nombre de la Carpeta Raíz donde se encontrará almacenado el SISAD
		$raiz = explode("/",$_SERVER['PHP_SELF']);
		define("SISAD",$raiz[1]);
		guardarRepMttoProg();
	}
	
	function guardarRepMttoProg(){
		header("Content-type: application/vnd.ms-excel; name='excel'");
		//header("Content-Disposition: attachment; filename=ReporteEstadistico.xls");
		header("Content-Disposition: filename=ReporteMttoProg.xls");
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
				border-top-color: #000000; border-bottom-color: #000000; vertical-align:middle;}
				/*En este formato  se establece el tamaño,tipo de letra,color de fondo que se ocupa en la fila principal para resaltar datos  de una tabla*/
				.nombres_filas { font-family: Arial, Helvetica, sans-serif; font-size: 11px; color: #000000; background-color: #9BBB59; font-weight:bold; vertical-align:middle;}
				/*En este formato  se establece el tamaño,tipo de letra,color de fondo que contendra datos  de una tabla*/
				.renglon_gris { font-family: Arial, Helvetica, sans-serif; font-size: 11px; color: #000000; background-color: #E7E7E7; vertical-align:middle;border-width:thin;border-style:solid;}
				/*En este formato  se establece el tamaño,tipo de letra,color de fondo que contendra datos  de una tabla*/
				.renglon_blanco { font-family: Arial, Helvetica, sans-serif; font-size: 11px; color: #000000; background-color: #FFFFFF; vertical-align:middle;border-width:thin;border-style:solid;}
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
			</tr>											
			<tr>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
			</tr>
		</table>
		<?php
		echo $_POST['hdn_divRepProgMtto'];
		?>
		<table>
			<tr>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td colspan="28">
					<span class="sub_encabezado">NOTA: ESTE PUEDE VARIAR EL DIA DE SU PROGRAMACI&Oacute;N POR LAS SIGUIENTES CAUSAS: FALTA DE OPERADOR, REPARACI&Oacute;N O FALTA DE LUGAR DE TRABAJO</span>
				</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td>&nbsp;</td><td>&nbsp;</td>
				<td colspan="8" align="center"><u>JUAN RUBEN PEREZ</u></td>
				<td>&nbsp;</td><td>&nbsp;</td>
				<td colspan="8" align="center"><u>LEONARDO TORRES JIMENO</u></td>
				<td>&nbsp;</td><td>&nbsp;</td>
				<td colspan="8" align="center"><u>RENE REYES MUJICA</u></td>
			</tr>
			<tr>
				<td>&nbsp;</td><td>&nbsp;</td>
				<td colspan="8" align="center">ELABOR&Oacute;</td>
				<td>&nbsp;</td><td>&nbsp;</td>
				<td colspan="8" align="center">REVIS&Oacute;</td>
				<td>&nbsp;</td><td>&nbsp;</td>
				<td colspan="8" align="center">AUTORIZ&Oacute;</td>
			</tr>
		</table>
		<?php
	}

	
	function guardarReporteEquipos($patron,$valorPatron){
		//Esribir datos de la pagina en excel
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=ListaEquipos.xls");
		//Definir el estilo de la tabla y el encabezado que aparecera sobre la tabla?>
			<head>
				<style>					
					<!--
					body { font-family:Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; }
					/*En este formato  se establece el tamaño,tipo de letra,color de fondo que ocupa la columna principal de una tabla*/
					.nombres_columnas { font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #FFFFFF; background-color: #9BBA59; font-weight: bold; 
					border-top-width: medium; border-right-width: thin;
					border-bottom-width: medium; border-left-width: thin; border-top-style: solid; border-right-style: solid; border-bottom-style: 
					solid; border-left-style: solid; 
					border-top-color: #000000; border-bottom-color: #000000; vertical-align:middle;}
					/*En este formato  se establece el tamaño,tipo de letra,color de fondo que se ocupa en la fila principal para resaltar datos  de una tabla*/
					.nombres_filas { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #9BBB59; font-weight:bold; vertical-align:middle;}
					/*En este formato  se establece el tamaño,tipo de letra,color de fondo que contendra datos  de una tabla*/
					.renglon_gris { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #E7E7E7; vertical-align:middle;}
					/*En este formato  se establece el tamaño,tipo de letra,color de fondo que contendra datos  de una tabla*/
					.renglon_blanco { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #FFFFFF; vertical-align:middle;}
					#tabla { position:absolute; left:0px; top:0px; width:1111px; height:175px; z-index:5; }
					.texto_encabezado {font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #0000CC; }
					.sub_encabezado {font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #0000CC; }
					.titulo_tabla {font-family: Arial, Helvetica, sans-serif; font-weight: bold; font-size: 14px; }
					.borde_linea { border-top:3px; border-top-color:#4E6128; border-top-style:solid;}
					.msje_correcto{font-family: Arial, Helvetica, sans-serif;font-size: 9px;color: #0000CC;font-weight:bold;}
					.msje_incorrecto{font-family: Arial, Helvetica, sans-serif;font-size: 9px;color: #FF0000;font-weight:bold;}
					-->
				</style>
			</head>											
			<body>
		<?php
		//Verificamos bajo que patron se esta pidiendo hacer la consults
		if ($patron==1){
			$patron=$valorPatron;
			//Creamos la sentencia SQL para mostrar los datos de los Equipos de AREA = PATRON
			$stm_sql="SELECT * FROM equipos WHERE area='$patron' AND estado='ACTIVO'";
			//Creamos el titulo de la tabla
			$titulo="Datos de los Equipos del &Aacute;rea <em>".$patron."</em>";
		}
		if ($patron==2){
			$patron=$valorPatron;
			//Creamos la sentencia SQL para mostrar los datos del Equipo de CLAVE = PATRON
			$stm_sql="SELECT * FROM equipos WHERE id_equipo='$patron' AND estado='ACTIVO'";
			//Verificar que el area este definida, de no ser asi, el usuario es AuxMtto
			if (isset($_POST["hdn_area"])){
				//De estar definida el area, concatenamos a la sentencia SQL la condicion que restringe el area del vehiculo
				$stm_sql.=" AND area='$_POST[hdn_area]'";
			}
			//Creamos el titulo de la tabla
			$titulo="Datos del Equipo con Clave <em><u>".strtoupper($patron)."</u></em>";
		}
		if ($patron==3){
			$patron=$valorPatron;
			//Creamos la sentencia SQL para mostrar los datos de los Equipos de FAMILIA = PATRON
			$stm_sql="SELECT * FROM equipos WHERE familia = '$patron' AND estado = 'ACTIVO'";
			//Verificar que el area este definida, de no ser asi, el usuario es AuxMtto
			if (isset($_POST["hdn_area"])){
				//De estar definida el area, concatenamos a la sentencia SQL la condicion que restringe el area del vehiculo
				$stm_sql.=" AND area = '$_POST[hdn_area]'";
			}
			//Creamos el titulo de la tabla			
			$titulo="Datos de los Equipos de la Familia <em><u>".$patron."</u></em>";
		}
		//Conectar a la BD de Mtto
		$conn=conecta("bd_mantenimiento");
		//Ejecutar la sentencia
		$rs=mysql_query($stm_sql);
		//Extraer los datos de la consulta
		if($datos=mysql_fetch_array($rs)){
			?>
			<div id="tabla">				
				<table width="1100">					
					<tr>
						<td align="left" valign="baseline" colspan="2"><img src="http://<?php echo HOST; ?>/<?php echo SISAD; ?>/images/logo.png" width="118" height="58" 
						align="absbottom" /></td>
						<td colspan="14">&nbsp;</td>
						<td valign="baseline" colspan="3">
							<div align="right"><span class="texto_encabezado">
								<strong>MANUAL  DE PROCEDIMIENTOS DE LA CALIDAD</strong><br><em>CONCRETO LANZADO DE FRESNILLO, S.A DE C.V.</em>
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
						<td colspan="19">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="19" align="center" class="titulo_tabla">LISTA DE EQUIPOS</td>
					</tr>
					<tr>
						<td colspan="19">&nbsp;</td>
					</tr>			
					<tr>
						<td class="nombres_columnas" align="center">CLAVE</td>
						<td class='nombres_columnas' align="center">EQUIPO</td>
						<td class="nombres_columnas" align="center">FECHA DE ALTA</td>
						<td class="nombres_columnas" align="center">MARCA/MODELO</td>
						<td class='nombres_columnas' align="center">MODELO</td>
						<td class='nombres_columnas' align="center">P&Oacute;LIZA</td>
						<td class='nombres_columnas' align="center">N&Uacute;MERO DE SERIE</td>
						<td class='nombres_columnas' align="center">N&Uacute;MERO DE SERIE EQUIPO ADICIONAL</td>
						<td class='nombres_columnas' align="center">PLACAS</td>
						<td class='nombres_columnas' align="center">TENENCIA</td>
						<td class='nombres_columnas' align="center">TARJETA DE CIRCULACI&Oacute;N</td>
						<td class='nombres_columnas' align="center">TIPO DE MOTOR</td>
						<td class='nombres_columnas' align="center">&Aacute;REA</td>
						<td class='nombres_columnas' align="center">FAMILIA</td>
						<td class='nombres_columnas' align="center">FECHA DE F&Aacute;BRICA</td>
						<td class='nombres_columnas' align="center">ASIGNADO A</td>
						<td class='nombres_columnas' align="center">PROVEEDOR</td>
						<td class='nombres_columnas' align="center">DESCRIPCI&Oacute;N</td>
						<td class='nombres_columnas' align="center">DISPONIBILIDAD</td>
					</tr>
			<?php
			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{	?>
					<tr>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['id_equipo']; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['nom_equipo']; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo modFecha($datos["fecha_alta"],1) ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['marca_modelo']; ?></td>						
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['modelo']; ?></td>						
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['poliza']; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['num_serie']; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['num_serie_olla']; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['placas']; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['tenencia']; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['tar_circulacion']; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['tipo_motor']; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['area']; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['familia']; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo modFecha($datos["fecha_fabrica"],1) ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['asignado']; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['proveedor']; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['descripcion']; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['disponibilidad']; ?></td>
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
	
	function guardarReporteTiempos($txt_fechaIni,$txt_fechaFin,$cmb_turno,$cmb_equipo){
		//Esribir datos de la pagina en excel
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=ReporteEquipos.xls");
		//Definir el estilo de la tabla y el encabezado que aparecera sobre la tabla?>
			<head>
				<style>					
					<!--
					body { font-family:Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; }
					/*En este formato  se establece el tamaño,tipo de letra,color de fondo que ocupa la columna principal de una tabla*/
					.nombres_columnas { font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #FFFFFF; background-color: #9BBA59; font-weight: bold; 
					border-top-width: medium; border-right-width: thin;
					border-bottom-width: medium; border-left-width: thin; border-top-style: solid; border-right-style: solid; border-bottom-style: 
					solid; border-left-style: solid; 
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
					.msje_correcto{font-family: Arial, Helvetica, sans-serif;font-size: 9px;color: #0000CC;font-weight:bold;}
					.msje_incorrecto{font-family: Arial, Helvetica, sans-serif;font-size: 9px;color: #FF0000;font-weight:bold;}
					-->
				</style>
			</head>											
			<body>
		<?php
		$area="MINA";
		//Obtener las Fechas
		$fechaI=modFecha($txt_fechaIni,3);
		$fechaF=modFecha($txt_fechaFin,3);
		//Extraer y verificar el Equipo, si tiene valor diferente de vacio, verificar la Familia
		$equipo=$cmb_equipo;
		//Obtener el Turno
		$turno=$cmb_turno;
		//Variable que segun el dato del Turno, indicara si es jornada de 8 o de 24 Hrs
		$hrsProgramadas=24;
		//Preparacion de la sentencia SQL
		$sql_stm="SELECT equipos_id_equipo,tipo_mtto,fecha_mtto,turno,tiempo_total FROM bitacora_mtto WHERE fecha_mtto BETWEEN '$fechaI' AND '$fechaF'";
		if($turno!=""){
			$sql_stm.=" AND turno='$turno'";
			$hrsProgramadas=8;
		}
		if($equipo!="")
			$sql_stm.=" AND equipos_id_equipo='$equipo'";
		$sql_stm.="ORDER BY equipos_id_equipo,tipo_mtto DESC,fecha_mtto,turno";
		//Conectar a la BD de Mtto
		$conn=conecta("bd_mantenimiento");
		//Ejecutar la sentencia
		$rs=mysql_query($sql_stm);
		//Extraer los datos de la consulta
		if($datos=mysql_fetch_array($rs)){
			//Arreglo que almacenara los Equipos
			$equipos=array();
			//Arreglo que almacenara el numero de Horas de Servicio en caso que se encuentren resultados
			$hrsMtto=array();
			//Asignar a la variable Equipo, el primero encontrado
			$equipo=$datos["equipos_id_equipo"];
			//Obtener el Tiempo Total en cantidad
			$tiempoMttoPrev=0;
			$tiempoMttoCorr=0;
			//Asignar a la variable Fecha la Fecha actual
			$fecha=$datos["fecha_mtto"];
			//Asignar a la variable Turno el Turno actual}
			$turno=$datos["turno"];
			do{
				//Verificar que sea el mismo equipo
				if($equipo==$datos["equipos_id_equipo"]){
					//Verificar que sea el mismo tipo de Mtto
					if($datos["tipo_mtto"]=="PREVENTIVO"){
						//Verificar que sea la misma Fecha de Mtto
						if($fecha==$datos["fecha_mtto"]){
							//Verificar que sea el mismo Turno
							if($turno==$datos["turno"]){
								//Dividir el tiempo de Mtto por el simbolo de DOS PUNTOS ":"
								$hora=split(":",$datos["tiempo_total"]);
								//Incrementar las horas segun la primer posicion, del arreglo que quedo como resultado
								$hrs=intval($hora[0]);
								//Incrementar las horas segun la segunda posicion, del arreglo que quedo como resultado
								$min=intval($hora[1]);
								//Obtener el Tiempo Total en cantidad
								$tiempoMttoPrev+=round(($hrs+($min/60)),2);
								$equipos[$equipo][$fecha][$turno]["P"]=$tiempoMttoPrev;
							}
							//Si no es el mismo Turno, cambiar el Puntero y restablecer el acumulador de Horas Preventivas
							else{
								$turno=$datos["turno"];
								$tiempoMttoPrev=0;
								//Dividir el tiempo de Mtto por el simbolo de DOS PUNTOS ":"
								$hora=split(":",$datos["tiempo_total"]);
								//Incrementar las horas segun la primer posicion, del arreglo que quedo como resultado
								$hrs=intval($hora[0]);
								//Incrementar las horas segun la segunda posicion, del arreglo que quedo como resultado
								$min=intval($hora[1]);
								//Obtener el Tiempo Total en cantidad
								$tiempoMttoPrev+=round(($hrs+($min/60)),2);
								$equipos[$equipo][$fecha][$turno]["P"]=$tiempoMttoPrev;
							}
						}
						//Si no es la misma Fecha, cambiar el puntero y restablecer el acumulador de Horas Preventivas
						else{
							$fecha=$datos["fecha_mtto"];
							$tiempoMttoPrev=0;
							//Dividir el tiempo de Mtto por el simbolo de DOS PUNTOS ":"
							$hora=split(":",$datos["tiempo_total"]);
							//Incrementar las horas segun la primer posicion, del arreglo que quedo como resultado
							$hrs=intval($hora[0]);
							//Incrementar las horas segun la segunda posicion, del arreglo que quedo como resultado
							$min=intval($hora[1]);
							//Obtener el Tiempo Total en cantidad
							$tiempoMttoPrev+=round(($hrs+($min/60)),2);
							$equipos[$equipo][$fecha][$turno]["P"]=$tiempoMttoPrev;
						}
					}
					//Si el Mtto no es Preventivo, entonces es correctivo
					else{
						//Verificar que sea la misma Fecha de Mtto
						if($fecha==$datos["fecha_mtto"]){
							//Verificar que sea el mismo Turno
							if($turno==$datos["turno"]){
								//Dividir el tiempo de Mtto por el simbolo de DOS PUNTOS ":"
								$hora=split(":",$datos["tiempo_total"]);
								//Incrementar las horas segun la primer posicion, del arreglo que quedo como resultado
								$hrs=intval($hora[0]);
								//Incrementar las horas segun la segunda posicion, del arreglo que quedo como resultado
								$min=intval($hora[1]);
								//Obtener el Tiempo Total en cantidad
								$tiempoMttoCorr+=round(($hrs+($min/60)),2);
								$equipos[$equipo][$fecha][$turno]["C"]=$tiempoMttoCorr;
							}
							//Si no es el mismo Turno, cambiar el Puntero y restablecer el acumulador de Horas Preventivas
							else{
								$turno=$datos["turno"];
								$tiempoMttoCorr=0;
								//Dividir el tiempo de Mtto por el simbolo de DOS PUNTOS ":"
								$hora=split(":",$datos["tiempo_total"]);
								//Incrementar las horas segun la primer posicion, del arreglo que quedo como resultado
								$hrs=intval($hora[0]);
								//Incrementar las horas segun la segunda posicion, del arreglo que quedo como resultado
								$min=intval($hora[1]);
								//Obtener el Tiempo Total en cantidad
								$tiempoMttoCorr+=round(($hrs+($min/60)),2);
								$equipos[$equipo][$fecha][$turno]["C"]=$tiempoMttoCorr;
							}
						}
						//Si no es la misma Fecha, cambiar el puntero y restablecer el acumulador de Horas Correctivas
						else{
							$fecha=$datos["fecha_mtto"];
							$tiempoMttoCorr=0;
							//Dividir el tiempo de Mtto por el simbolo de DOS PUNTOS ":"
							$hora=split(":",$datos["tiempo_total"]);
							//Incrementar las horas segun la primer posicion, del arreglo que quedo como resultado
							$hrs=intval($hora[0]);
							//Incrementar las horas segun la segunda posicion, del arreglo que quedo como resultado
							$min=intval($hora[1]);
							//Obtener el Tiempo Total en cantidad
							$tiempoMttoCorr+=round(($hrs+($min/60)),2);
							$equipos[$equipo][$fecha][$turno]["C"]=$tiempoMttoCorr;
						}
					}
				}
				//Aqui el Equipo ya es otro
				else{
					$equipo=$datos["equipos_id_equipo"];
					$tiempoMttoPrev=0;
					$tiempoMttoCorr=0;
					//Verificar que sea el mismo tipo de Mtto
					if($datos["tipo_mtto"]=="PREVENTIVO"){
						//Verificar que sea la misma Fecha de Mtto
						if($fecha==$datos["fecha_mtto"]){
							//Verificar que sea el mismo Turno
							if($turno==$datos["turno"]){
								//Dividir el tiempo de Mtto por el simbolo de DOS PUNTOS ":"
								$hora=split(":",$datos["tiempo_total"]);
								//Incrementar las horas segun la primer posicion, del arreglo que quedo como resultado
								$hrs=intval($hora[0]);
								//Incrementar las horas segun la segunda posicion, del arreglo que quedo como resultado
								$min=intval($hora[1]);
								//Obtener el Tiempo Total en cantidad
								$tiempoMttoPrev+=round(($hrs+($min/60)),2);
								$equipos[$equipo][$fecha][$turno]["P"]=$tiempoMttoPrev;
							}
							//Si no es el mismo Turno, cambiar el Puntero y restablecer el acumulador de Horas Preventivas
							else{
								$turno=$datos["turno"];
								$tiempoMttoPrev=0;
								//Dividir el tiempo de Mtto por el simbolo de DOS PUNTOS ":"
								$hora=split(":",$datos["tiempo_total"]);
								//Incrementar las horas segun la primer posicion, del arreglo que quedo como resultado
								$hrs=intval($hora[0]);
								//Incrementar las horas segun la segunda posicion, del arreglo que quedo como resultado
								$min=intval($hora[1]);
								//Obtener el Tiempo Total en cantidad
								$tiempoMttoPrev+=round(($hrs+($min/60)),2);
								$equipos[$equipo][$fecha][$turno]["P"]=$tiempoMttoPrev;
							}
						}
						//Si no es la misma Fecha, cambiar el puntero y restablecer el acumulador de Horas Preventivas
						else{
							$fecha=$datos["fecha_mtto"];
							$tiempoMttoPrev=0;
							//Dividir el tiempo de Mtto por el simbolo de DOS PUNTOS ":"
							$hora=split(":",$datos["tiempo_total"]);
							//Incrementar las horas segun la primer posicion, del arreglo que quedo como resultado
							$hrs=intval($hora[0]);
							//Incrementar las horas segun la segunda posicion, del arreglo que quedo como resultado
							$min=intval($hora[1]);
							//Obtener el Tiempo Total en cantidad
							$tiempoMttoPrev+=round(($hrs+($min/60)),2);
							$equipos[$equipo][$fecha][$turno]["P"]=$tiempoMttoPrev;
						}
					}
					//Mantenimiento Correctivo
					else{
						//Verificar que sea la misma Fecha de Mtto
						if($fecha==$datos["fecha_mtto"]){
							//Verificar que sea el mismo Turno
							if($turno==$datos["turno"]){
								//Dividir el tiempo de Mtto por el simbolo de DOS PUNTOS ":"
								$hora=split(":",$datos["tiempo_total"]);
								//Incrementar las horas segun la primer posicion, del arreglo que quedo como resultado
								$hrs=intval($hora[0]);
								//Incrementar las horas segun la segunda posicion, del arreglo que quedo como resultado
								$min=intval($hora[1]);
								//Obtener el Tiempo Total en cantidad
								$tiempoMttoCorr+=round(($hrs+($min/60)),2);
								$equipos[$equipo][$fecha][$turno]["C"]=$tiempoMttoCorr;
							}
							//Si no es el mismo Turno, cambiar el Puntero y restablecer el acumulador de Horas Preventivas
							else{
								$turno=$datos["turno"];
								$tiempoMttoCorr=0;
								//Dividir el tiempo de Mtto por el simbolo de DOS PUNTOS ":"
								$hora=split(":",$datos["tiempo_total"]);
								//Incrementar las horas segun la primer posicion, del arreglo que quedo como resultado
								$hrs=intval($hora[0]);
								//Incrementar las horas segun la segunda posicion, del arreglo que quedo como resultado
								$min=intval($hora[1]);
								//Obtener el Tiempo Total en cantidad
								$tiempoMttoCorr+=round(($hrs+($min/60)),2);
								$equipos[$equipo][$fecha][$turno]["C"]=$tiempoMttoCorr;
							}
						}
						//Si no es la misma Fecha, cambiar el puntero y restablecer el acumulador de Horas Correctivas
						else{
							$fecha=$datos["fecha_mtto"];
							$tiempoMttoCorr=0;
							//Dividir el tiempo de Mtto por el simbolo de DOS PUNTOS ":"
							$hora=split(":",$datos["tiempo_total"]);
							//Incrementar las horas segun la primer posicion, del arreglo que quedo como resultado
							$hrs=intval($hora[0]);
							//Incrementar las horas segun la segunda posicion, del arreglo que quedo como resultado
							$min=intval($hora[1]);
							//Obtener el Tiempo Total en cantidad
							$tiempoMttoCorr+=round(($hrs+($min/60)),2);
							$equipos[$equipo][$fecha][$turno]["C"]=$tiempoMttoCorr;
						}
					}
				}
			}while($datos=mysql_fetch_array($rs));
		}//La sentencia no requiere un ELSE
		/*INICIO DE LA DECLARACION DEL ENCABEZADO*/
		//Obtener la cantidad de Dias entre las 2 Fechas
		$dias=restarFechas($fechaI,$fechaF)+1;
		//Partir la Fecha de Inicio en secciones de dia, mes y año
		$diaI=substr($fechaI,0,2);
		$mesI=substr($fechaI,3,2);
		$anioI=substr($fechaI,-4);
		//Obtener la cantidad de Dias del primer Mes
		$cantDiasMesCurso=diasMes($mesI,$anioI);
		//Convertir en numero los dias,mes y año de la Fecha de Inicio
		$diasInicio=0+$diaI;
		$mesInicio=0+$mesI;
		$anioInicio=0+$anioI;
		//Partir la Fecha de Fin en secciones de dia, mes y año
		$diaF=substr($fechaF,0,2);
		$mesF=substr($fechaF,3,2);
		$anioF=substr($fechaF,-4);
		//Convertir en numero los dias,mes y año de la Fecha de Inicio
		$diasTope=0+$diaF;
		$mesTope=0+$mesF;
		$anioTope=0+$anioF;
		//Cuerpo del Encabezado
		echo "<table class='tabla_frm' cellpadding='5'>";
		?>
		<tr>
			<td align="left" valign="baseline" colspan="3"><img src="http://<?php echo HOST;?>/<?php echo SISAD;?>/images/logo.png" width="118" height="58" 
			align="absbottom" /></td>
			<td colspan="<?php echo($dias*3)-4?>">&nbsp;</td>
			<td valign="baseline" colspan="3">
				<div align="right"><span class="texto_encabezado">
					<strong>MANUAL  DE PROCEDIMIENTOS DE LA CALIDAD</strong><br><em>CONCRETO  LANZADO DE FRESNILLO, S.A DE C.V.</em>
				</span></div>
			</td>
		</tr>											
		<tr>
			<td colspan="<?php echo($dias*3)+2?>" align="center" class="borde_linea">
				<span class="sub_encabezado">CONFIDENCIAL, PROPIEDAD DE &ldquo;CONCRETO LANZADO DE FRESNILLO, S.A DE C.V.&rdquo; PROHIBIDA SU REPRODUCCI
				&Oacute;N TOTAL O PARCIAL</span>
			</td>
		</tr>					
		<tr>
			<td colspan="<?php echo($dias*3)+2?>">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="<?php echo($dias*3)+2?>" align="center">Reporte de Equipos del <?php echo $txt_fechaIni;?> al <?php echo $txt_fechaFin;?></td>
		</tr>
		<tr>
			<td colspan="<?php echo($dias*3)+2?>">&nbsp;</td>
		</tr>
		<?php
		echo "	<tr>
					<td class='nombres_columnas' align='center' rowspan='3'>EQUIPO</td>
					<td class='nombres_columnas' align='center' rowspan='3'>TURNO</td>
					<td class='nombres_columnas' align='center' colspan='".($dias*3)."'>FECHA</td>
				</tr>
		";
		//Declaracion del renglon para las Fechas
		echo "<tr>";
		//Asignar la fecha de inicio a una variable
		$fechaMostrar=$fechaI;
		do{
			//Dibujar la Fecha de Inicio
			echo "<td class='nombres_columnas' colspan='3' align='center' width='300px'>".modFecha($fechaMostrar,1)."</td>";
			$fechaMostrar=sumarDiasFecha($fechaMostrar,1);
		}while($fechaMostrar!=sumarDiasFecha($fechaF,1));
		echo "</tr>";
		//Declaracion de los Renglones de los conceptos
		echo "<tr>";
		$cont=0;
		do{
			echo "<td class='nombres_columnas' align='center' width='100px'>D.M.</td>";
			$cont++;
			echo "<td class='nombres_columnas' align='center' width='100px'>D.F.</td>";
			$cont++;
			echo "<td class='nombres_columnas' align='center' width='100px'>U.E.</td>";
			$cont++;
		}while($cont<($dias*3));
		echo "</tr>";
		/*FIN DE LA DECLARACION DEL ENCABEZADO*/
		/*INICIO DEL LLENADO DE DATOS*/
		//Extraer todos los Equipos de MttoMina que se encuentren Activos
		$sql_stm="SELECT id_equipo FROM equipos WHERE area='MINA' AND estado='ACTIVO'";
		//Si el Equipo esta definido desde el POST, usarlo como filtro, (Esto es redundante pero se puede aplicar la misma estructura)
		if($cmb_equipo!="")
			$sql_stm.=" AND id_equipo='$equipo'";
		$sql_stm.=" ORDER BY familia,id_equipo";
		$rs=mysql_query($sql_stm);
		//Contador para el manejo de Turnos
		$contador=1;
		//Clase inicial para el renglon
		$nom_clase = "renglon_gris";
		if($datosEquipos=mysql_fetch_array($rs)){
			//Recorrer los Equipos
			do{
				//Asignar el idEquipo a una variable para su mejor manejo
				$idEquipo=$datosEquipos["id_equipo"];
				//Verificar si el Equipo se encuentra en el arreglo de Equipos
				if(isset($equipos[$idEquipo])){
					//Cuando no hay Turno definido
					if($cmb_turno==""){
						//Obtener el Turno
						$numTurno=1;
						do{
							//Obtener el Turno en modo Texto
							if($numTurno==1)
								$turnoActual="TURNO DE PRIMERA";
							if($numTurno==2)
								$turnoActual="TURNO DE SEGUNDA";
							if($numTurno==3)
								$turnoActual="TURNO DE TERCERA";
							//Obtener la Fecha Actual
							$fechaActual=$fechaI;
							echo "<tr>";
							echo "<td align='center' class='$nom_clase'>$idEquipo</td>";
							echo "<td align='center' class='$nom_clase'>$turnoActual</td>";
							do{
								//Obtener las horas en Turno
								$hrsTurno=obtenerHorasXFechaXTurno($idEquipo,$fechaActual,$turnoActual);
								//Variable para acumular horas en Mtto Preventivo y Correctivo
								$hrsMtto=0;
								//Sumar a la variable de las Horas de Mantenimiento las Horas encontradas por equipo,fecha,turno y que son registradas directamente
								//sin necesidad de haberse generado una órden de Trabajo y que por lo tanto, no se guardan en la bitácora de Mantenimiento
								$hrsMtto+=obtenerMttoDiarioTurno($idEquipo,$fechaActual,$turnoActual);
								//Si esta definida la siguiente posicion, tiene servicios preventivos
								if(isset($equipos[$idEquipo][$fechaActual][$turnoActual]["P"]))
									//Acumular a las horas de Mtto, las horas de Mtto Preventivo
									$hrsMtto+=$equipos[$idEquipo][$fechaActual][$turnoActual]["P"];
								//Si esta definida la siguiente posicion, tiene servicios correctivos
								if(isset($equipos[$idEquipo][$fechaActual][$turnoActual]["C"]))
									//Acumular a las horas de Mtto, las horas de Mtto Correctivo
									$hrsMtto+=$equipos[$idEquipo][$fechaActual][$turnoActual]["C"];
								//Calcular las Horas en Stand By, se pasa el 8 directo, ya que los calculos son por Turno

								$hrsSB=8-($hrsTurno+$hrsMtto);
								//Calcular la Disponibilidad Mecanica
								if($hrsTurno!=0 && $hrsMtto!=0)
									$dispMec=($hrsTurno/($hrsTurno+$hrsMtto))*100;
								elseif($hrsTurno!=0 || $hrsMtto!=0)
									$dispMec=($hrsTurno/($hrsTurno+$hrsMtto))*100;
								else
									$dispMec=0;
								//Calcular la Disponibilidad Física en base a 8 horas por Turno
								$dispFis=(($hrsTurno+$hrsSB)/8)*100;
								//Calcular la Utilizacion Efectiva en base a 8 horas por Turno
								$utilEfec=($hrsTurno/8)*100;
								//Convertir los datos encontrados a numeros mas entendibles
								$dispMec=number_format($dispMec,2,".",",");
								$dispFis=number_format($dispFis,2,".",",");
								$utilEfec=number_format($utilEfec,2,".",",");
								//Disponibilidad Mecanica
								if($dispMec=="0.00")
									echo "<td align='center' class='$nom_clase' title='Disponibilidad Mec&aacute;nica del $idEquipo en $turnoActual del ".modFecha($fechaActual,1)."'>
											<span class='msje_incorrecto'>$dispMec%<span></td>";
								elseif(floatval($dispMec)>=85)
									echo "<td align='center' class='$nom_clase' title='Disponibilidad Mec&aacute;nica del $idEquipo en $turnoActual del ".modFecha($fechaActual,1)."'>
											<span class='msje_correcto'>$dispMec%<span></td>";
								else
									echo "<td align='center' class='$nom_clase' title='Disponibilidad Mec&aacute;nica del $idEquipo en $turnoActual del ".modFecha($fechaActual,1)."'>
											<span>$dispMec%<span></td>";
								//Disponibilidad Fisica
								if($dispFis=="0.00")
									echo "<td align='center' class='$nom_clase' title='Disponibilidad F&iacute;sica del $idEquipo en $turnoActual del ".modFecha($fechaActual,1)."'>
											<span class='msje_incorrecto'>$dispFis%</span></td>";
								elseif(floatval($dispFis)>=85)
									echo "<td align='center' class='$nom_clase' title='Disponibilidad F&iacute;sica del $idEquipo en $turnoActual del ".modFecha($fechaActual,1)."'>
											<span class='msje_correcto'>$dispFis%</span></td>";
								else
									echo "<td align='center' class='$nom_clase' title='Disponibilidad F&iacute;sica del $idEquipo en $turnoActual del ".modFecha($fechaActual,1)."'>
											<span>$dispFis%</span></td>";
								//Utilizacion Efectivas
								if($utilEfec=="0.00")
									echo "<td align='center' class='$nom_clase' title='Utilizaci&oacute;n Efectiva del $idEquipo en $turnoActual del ".modFecha($fechaActual,1)."'>
											<span class='msje_incorrecto'>$utilEfec%</span></td>";
								elseif(floatval($utilEfec)>=85)
									echo "<td align='center' class='$nom_clase' title='Utilizaci&oacute;n Efectiva del $idEquipo en $turnoActual del ".modFecha($fechaActual,1)."'>
											<span class='msje_correcto'>$utilEfec%</span></td>";
								else
									echo "<td align='center' class='$nom_clase' title='Utilizaci&oacute;n Efectiva del $idEquipo en $turnoActual del ".modFecha($fechaActual,1)."'>
											<span>$utilEfec%</span></td>";
								//Incrementar la Fecha Actual en 1 Dia
								$fechaActual=sumarDiasFecha($fechaActual,1);
							}while($fechaActual!=sumarDiasFecha($fechaF,1));
							$numTurno++;
							echo "</tr>";
						}while($numTurno!=4);
					}
					//Cuando SI hay Turno definido
					else{
						$turnoActual=$cmb_turno;
						//Obtener la Fecha Actual
						$fechaActual=$fechaI;
						echo "<tr>";
						echo "<td align='center' class='$nom_clase'>$idEquipo</td>";
						echo "<td align='center' class='$nom_clase'>$turnoActual</td>";
						do{
							//Obtener las horas en Turno
							$hrsTurno=obtenerHorasXFechaXTurno($idEquipo,$fechaActual,$turnoActual);
							//Variable para acumular horas en Mtto Preventivo y Correctivo
							$hrsMtto=0;
							//Sumar a la variable de las Horas de Mantenimiento las Horas encontradas por equipo,fecha,turno y que son registradas directamente
							//sin necesidad de haberse generado una órden de Trabajo y que por lo tanto, no se guardan en la bitácora de Mantenimiento
							$hrsMtto+=obtenerMttoDiarioTurno($idEquipo,$fechaActual,$turnoActual);
							//Si esta definida la siguiente posicion, tiene servicios preventivos
							if(isset($equipos[$idEquipo][$fechaActual][$turnoActual]["P"]))
								//Acumular a las horas de Mtto, las horas de Mtto Preventivo
								$hrsMtto+=$equipos[$idEquipo][$fechaActual][$turnoActual]["P"];
							//Si esta definida la siguiente posicion, tiene servicios correctivos
							if(isset($equipos[$idEquipo][$fechaActual][$turnoActual]["C"]))
								//Acumular a las horas de Mtto, las horas de Mtto Correctivo
								$hrsMtto+=$equipos[$idEquipo][$fechaActual][$turnoActual]["C"];
							//Calcular las Horas en Stand By, se pasa el 8 directo, ya que los calculos son por Turno
							$hrsSB=8-($hrsTurno+$hrsMtto);
							//Calcular la Disponibilidad Mecanica
							if($hrsTurno!=0 && $hrsMtto!=0)
								$dispMec=($hrsTurno/($hrsTurno+$hrsMtto))*100;
							elseif($hrsTurno!=0 || $hrsMtto!=0)
								$dispMec=($hrsTurno/($hrsTurno+$hrsMtto))*100;
							else
								$dispMec=0;
							//Calcular la Disponibilidad Física en base a 8 horas por Turno
							$dispFis=(($hrsTurno+$hrsSB)/8)*100;
							//Calcular la Utilizacion Efectiva en base a 8 horas por Turno
							$utilEfec=($hrsTurno/8)*100;
							//Convertir los datos encontrados a numeros mas entendibles
							$dispMec=number_format($dispMec,2,".",",");
							$dispFis=number_format($dispFis,2,".",",");
							$utilEfec=number_format($utilEfec,2,".",",");
							//Disponibilidad Mecanica
							if($dispMec=="0.00")
								echo "<td align='center' class='$nom_clase' title='Disponibilidad Mec&aacute;nica del $idEquipo en $turnoActual del ".modFecha($fechaActual,1)."'>
										<span class='msje_incorrecto'>$dispMec%<span></td>";
							elseif(floatval($dispMec)>=85)
								echo "<td align='center' class='$nom_clase' title='Disponibilidad Mec&aacute;nica del $idEquipo en $turnoActual del ".modFecha($fechaActual,1)."'>
										<span class='msje_correcto'>$dispMec%<span></td>";
							else
								echo "<td align='center' class='$nom_clase' title='Disponibilidad Mec&aacute;nica del $idEquipo en $turnoActual del ".modFecha($fechaActual,1)."'>
										<span>$dispMec%<span></td>";
							//Disponibilidad Fisica
							if($dispFis=="0.00")
								echo "<td align='center' class='$nom_clase' title='Disponibilidad F&iacute;sica del $idEquipo en $turnoActual del ".modFecha($fechaActual,1)."'>
										<span class='msje_incorrecto'>$dispFis%</span></td>";
							elseif(floatval($dispFis)>=85)
								echo "<td align='center' class='$nom_clase' title='Disponibilidad F&iacute;sica del $idEquipo en $turnoActual del ".modFecha($fechaActual,1)."'>
										<span class='msje_correcto'>$dispFis%</span></td>";
							else
								echo "<td align='center' class='$nom_clase' title='Disponibilidad F&iacute;sica del $idEquipo en $turnoActual del ".modFecha($fechaActual,1)."'>
										<span>$dispFis%</span></td>";
							//Utilizacion Efectivas
							if($utilEfec=="0.00")
								echo "<td align='center' class='$nom_clase' title='Utilizaci&oacute;n Efectiva del $idEquipo en $turnoActual del ".modFecha($fechaActual,1)."'>
										<span class='msje_incorrecto'>$utilEfec%</span></td>";
							elseif(floatval($utilEfec)>=85)
								echo "<td align='center' class='$nom_clase' title='Utilizaci&oacute;n Efectiva del $idEquipo en $turnoActual del ".modFecha($fechaActual,1)."'>
										<span class='msje_correcto'>$utilEfec%</span></td>";
							else
								echo "<td align='center' class='$nom_clase' title='Utilizaci&oacute;n Efectiva del $idEquipo en $turnoActual del ".modFecha($fechaActual,1)."'>
										<span>$utilEfec%</span></td>";
							//Incrementar la Fecha Actual en 1 Dia
							$fechaActual=sumarDiasFecha($fechaActual,1);
						}while($fechaActual!=sumarDiasFecha($fechaF,1));
						echo "</tr>";
					}
				}
				else{
					/********************/
					//EQUIPOS SIN MTTO
					//Cuando no hay Turno definido
					if($cmb_turno==""){
						//Obtener el Turno
						$numTurno=1;
						do{
							//Obtener el Turno en modo Texto
							if($numTurno==1)
								$turnoActual="TURNO DE PRIMERA";
							if($numTurno==2)
								$turnoActual="TURNO DE SEGUNDA";
							if($numTurno==3)
								$turnoActual="TURNO DE TERCERA";
							//Obtener la Fecha Actual
							$fechaActual=$fechaI;
							echo "<tr>";
							echo "<td align='center' class='$nom_clase'>$idEquipo</td>";
							echo "<td align='center' class='$nom_clase'>$turnoActual</td>";
							do{
								//Obtener las horas en Turno
								$hrsTurno=obtenerHorasXFechaXTurno($idEquipo,$fechaActual,$turnoActual);
								//Variable para acumular horas en Mtto Preventivo y Correctivo
								$hrsMtto=0;
								//Sumar a la variable de las Horas de Mantenimiento las Horas encontradas por equipo,fecha,turno y que son registradas directamente
								//sin necesidad de haberse generado una órden de Trabajo y que por lo tanto, no se guardan en la bitácora de Mantenimiento
								$hrsMtto+=obtenerMttoDiarioTurno($idEquipo,$fechaActual,$turnoActual);
								//Si esta definida la siguiente posicion, tiene servicios preventivos
								if(isset($equipos[$idEquipo][$fechaActual][$turnoActual]["P"]))
									//Acumular a las horas de Mtto, las horas de Mtto Preventivo
									$hrsMtto+=$equipos[$idEquipo][$fechaActual][$turnoActual]["P"];
								//Si esta definida la siguiente posicion, tiene servicios correctivos
								if(isset($equipos[$idEquipo][$fechaActual][$turnoActual]["C"]))
									//Acumular a las horas de Mtto, las horas de Mtto Correctivo
									$hrsMtto+=$equipos[$idEquipo][$fechaActual][$turnoActual]["C"];
								//Calcular las Horas en Stand By, se pasa el 8 directo, ya que los calculos son por Turno
								$hrsSB=8-($hrsTurno+$hrsMtto);
								//Calcular la Disponibilidad Mecanica
								if($hrsTurno!=0 && $hrsMtto!=0)
									$dispMec=($hrsTurno/($hrsTurno+$hrsMtto))*100;
								elseif($hrsTurno!=0 || $hrsMtto!=0)
									$dispMec=($hrsTurno/($hrsTurno+$hrsMtto))*100;
								else
									$dispMec=0;
								//Calcular la Disponibilidad Física en base a 8 horas por Turno
								$dispFis=(($hrsTurno+$hrsSB)/8)*100;
								//Calcular la Utilizacion Efectiva en base a 8 horas por Turno
								$utilEfec=($hrsTurno/8)*100;
								//Convertir los datos encontrados a numeros mas entendibles
								$dispMec=number_format($dispMec,2,".",",");
								$dispFis=number_format($dispFis,2,".",",");
								$utilEfec=number_format($utilEfec,2,".",",");
								//Disponibilidad Mecanica
								if($dispMec=="0.00")
									echo "<td align='center' class='$nom_clase' title='Disponibilidad Mec&aacute;nica del $idEquipo en $turnoActual del ".modFecha($fechaActual,1)."'>
											<span class='msje_incorrecto'>$dispMec%<span></td>";
								elseif(floatval($dispMec)>=85)
									echo "<td align='center' class='$nom_clase' title='Disponibilidad Mec&aacute;nica del $idEquipo en $turnoActual del ".modFecha($fechaActual,1)."'>
											<span class='msje_correcto'>$dispMec%<span></td>";
								else
									echo "<td align='center' class='$nom_clase' title='Disponibilidad Mec&aacute;nica del $idEquipo en $turnoActual del ".modFecha($fechaActual,1)."'>
											<span>$dispMec%<span></td>";
								//Disponibilidad Fisica
								if($dispFis=="0.00")
									echo "<td align='center' class='$nom_clase' title='Disponibilidad F&iacute;sica del $idEquipo en $turnoActual del ".modFecha($fechaActual,1)."'>
											<span class='msje_incorrecto'>$dispFis%</span></td>";
								elseif(floatval($dispFis)>=85)
									echo "<td align='center' class='$nom_clase' title='Disponibilidad F&iacute;sica del $idEquipo en $turnoActual del ".modFecha($fechaActual,1)."'>
											<span class='msje_correcto'>$dispFis%</span></td>";
								else
									echo "<td align='center' class='$nom_clase' title='Disponibilidad F&iacute;sica del $idEquipo en $turnoActual del ".modFecha($fechaActual,1)."'>
											<span>$dispFis%</span></td>";
								//Utilizacion Efectivas
								if($utilEfec=="0.00")
									echo "<td align='center' class='$nom_clase' title='Utilizaci&oacute;n Efectiva del $idEquipo en $turnoActual del ".modFecha($fechaActual,1)."'>
											<span class='msje_incorrecto'>$utilEfec%</span></td>";
								elseif(floatval($utilEfec)>=85)
									echo "<td align='center' class='$nom_clase' title='Utilizaci&oacute;n Efectiva del $idEquipo en $turnoActual del ".modFecha($fechaActual,1)."'>
											<span class='msje_correcto'>$utilEfec%</span></td>";
								else
									echo "<td align='center' class='$nom_clase' title='Utilizaci&oacute;n Efectiva del $idEquipo en $turnoActual del ".modFecha($fechaActual,1)."'>
											<span>$utilEfec%</span></td>";
								//Incrementar la Fecha Actual en 1 Dia
								$fechaActual=sumarDiasFecha($fechaActual,1);
							}while($fechaActual!=sumarDiasFecha($fechaF,1));
							$numTurno++;
							echo "</tr>";
						}while($numTurno!=4);
					}
					//Con turno Definido
					else{
						$turnoActual=$cmb_turno;
						//Obtener la Fecha Actual
						$fechaActual=$fechaI;
						echo "<tr>";
						echo "<td align='center' class='$nom_clase'>$idEquipo</td>";
						echo "<td align='center' class='$nom_clase'>$turnoActual</td>";
						do{
							//Obtener las horas en Turno
							$hrsTurno=obtenerHorasXFechaXTurno($idEquipo,$fechaActual,$turnoActual);
							//Variable para acumular horas en Mtto Preventivo y Correctivo
							$hrsMtto=0;
							//Sumar a la variable de las Horas de Mantenimiento las Horas encontradas por equipo,fecha,turno y que son registradas directamente
							//sin necesidad de haberse generado una órden de Trabajo y que por lo tanto, no se guardan en la bitácora de Mantenimiento
							$hrsMtto+=obtenerMttoDiarioTurno($idEquipo,$fechaActual,$turnoActual);
							//Si esta definida la siguiente posicion, tiene servicios preventivos
							if(isset($equipos[$idEquipo][$fechaActual][$turnoActual]["P"]))
								//Acumular a las horas de Mtto, las horas de Mtto Preventivo
								$hrsMtto+=$equipos[$idEquipo][$fechaActual][$turnoActual]["P"];
							//Si esta definida la siguiente posicion, tiene servicios correctivos
							if(isset($equipos[$idEquipo][$fechaActual][$turnoActual]["C"]))
								//Acumular a las horas de Mtto, las horas de Mtto Correctivo
								$hrsMtto+=$equipos[$idEquipo][$fechaActual][$turnoActual]["C"];
							//Calcular las Horas en Stand By, se pasa el 8 directo, ya que los calculos son por Turno
							$hrsSB=8-($hrsTurno+$hrsMtto);
							//Calcular la Disponibilidad Mecanica
							if($hrsTurno!=0 && $hrsMtto!=0)
								$dispMec=($hrsTurno/($hrsTurno+$hrsMtto))*100;
							elseif($hrsTurno!=0 || $hrsMtto!=0)
								$dispMec=($hrsTurno/($hrsTurno+$hrsMtto))*100;
							else
								$dispMec=0;
							//Calcular la Disponibilidad Física en base a 8 horas por Turno
							$dispFis=(($hrsTurno+$hrsSB)/8)*100;
							//Calcular la Utilizacion Efectiva en base a 8 horas por Turno
							$utilEfec=($hrsTurno/8)*100;
							//Convertir los datos encontrados a numeros mas entendibles
							$dispMec=number_format($dispMec,2,".",",");
							$dispFis=number_format($dispFis,2,".",",");
							$utilEfec=number_format($utilEfec,2,".",",");
							//Disponibilidad Mecanica
							if($dispMec=="0.00")
								echo "<td align='center' class='$nom_clase' title='Disponibilidad Mec&aacute;nica del $idEquipo en $turnoActual del ".modFecha($fechaActual,1)."'>
										<span class='msje_incorrecto'>$dispMec%<span></td>";
							elseif(floatval($dispMec)>=85)
								echo "<td align='center' class='$nom_clase' title='Disponibilidad Mec&aacute;nica del $idEquipo en $turnoActual del ".modFecha($fechaActual,1)."'>
										<span class='msje_correcto'>$dispMec%<span></td>";
							else
								echo "<td align='center' class='$nom_clase' title='Disponibilidad Mec&aacute;nica del $idEquipo en $turnoActual del ".modFecha($fechaActual,1)."'>
										<span>$dispMec%<span></td>";
							//Disponibilidad Fisica
							if($dispFis=="0.00")
								echo "<td align='center' class='$nom_clase' title='Disponibilidad F&iacute;sica del $idEquipo en $turnoActual del ".modFecha($fechaActual,1)."'>
										<span class='msje_incorrecto'>$dispFis%</span></td>";
							elseif(floatval($dispFis)>=85)
								echo "<td align='center' class='$nom_clase' title='Disponibilidad F&iacute;sica del $idEquipo en $turnoActual del ".modFecha($fechaActual,1)."'>
										<span class='msje_correcto'>$dispFis%</span></td>";
							else
								echo "<td align='center' class='$nom_clase' title='Disponibilidad F&iacute;sica del $idEquipo en $turnoActual del ".modFecha($fechaActual,1)."'>
										<span>$dispFis%</span></td>";
							//Utilizacion Efectivas
							if($utilEfec=="0.00")
								echo "<td align='center' class='$nom_clase' title='Utilizaci&oacute;n Efectiva del $idEquipo en $turnoActual del ".modFecha($fechaActual,1)."'>
										<span class='msje_incorrecto'>$utilEfec%</span></td>";
							elseif(floatval($utilEfec)>=85)
								echo "<td align='center' class='$nom_clase' title='Utilizaci&oacute;n Efectiva del $idEquipo en $turnoActual del ".modFecha($fechaActual,1)."'>
										<span class='msje_correcto'>$utilEfec%</span></td>";
							else
								echo "<td align='center' class='$nom_clase' title='Utilizaci&oacute;n Efectiva del $idEquipo en $turnoActual del ".modFecha($fechaActual,1)."'>
										<span>$utilEfec%</span></td>";
							//Incrementar la Fecha Actual en 1 Dia
							$fechaActual=sumarDiasFecha($fechaActual,1);
						}while($fechaActual!=sumarDiasFecha($fechaF,1));
						echo "</tr>";
					}
					//
					/********************/
				}
				//Incrementar el contador de Turnos
				$contador++;
				//Verificar que clase le toca al Renglon
				if($contador%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
			}while($datosEquipos=mysql_fetch_array($rs));
		}
		/*FIN DEL LLENADO DE DATOS*/
		echo "</table>";
		/*
		//Comenzar a dibujar la Tabla
		echo "<table class='tabla_frm' cellpadding='5'>";
		echo "<caption class='titulo_etiqueta'>F&oacute;rmulas Empleadas</caption>";
		echo "	<tr>
					<td class='nombres_columnas' align='center'>DISPONIBILIDAD MEC&Aacute;NICA</td>
					<td class='renglon_gris' align='center'>(HorasTrabajadas/(HorasTrabajadas+HorasMantenimiento))*100</td>
				</tr>	
				<tr>
					<td class='nombres_columnas' align='center'>DISPONIBILIDAD F&Iacute;SICA</td>
					<td class='renglon_blanco' align='center'>((HorasTrabajadas+HorasStandBy)/HorasPorTurno)*100</td>
				</tr>	
				<tr>
					<td class='nombres_columnas' align='center'>UTILIZACI&Oacute;N EFECTIVA</td>
					<td class='renglon_gris' align='center'>(HorasTrabajadas/HorasPorTurno)*100</td>
				</tr>";
		echo "</table>";
		*/
		//Cerrar la BD
		mysql_close($conn);
		echo "</body>";
	}
	
	//Funcion que extrae las horas efectivas de un Equipo en una fecha y tuno determinados
	function obtenerHorasXFechaXTurno($equipo,$fecha,$turno){
		//Sentencia SQL para extraer el total de horas efectivas
		$sql="SELECT SUM(hrs_efectivas) AS total FROM horometro_odometro WHERE equipos_id_equipo='$equipo' AND fecha='$fecha' AND turno='$turno'";
		//Ejecutar la sentencia SQL
		$rs=mysql_query($sql);
		//Verificar resultados
		if($datos=mysql_fetch_array($rs)){
			//Si regresa NULL, regresar 0, de lo contrario, asignar el Valor
			if($datos["total"]!=NULL)
				$horas=$datos["total"];
			else
				$horas=0;
		}
		else
			$horas=-1;
		//Retornar el valor de las Horas
		return $horas;
	}
	
	//Funcion que extrae las horas efectivas de un Equipo en una fecha y tuno determinados
	function obtenerMttoDiarioTurno($equipo,$fecha,$turno){
		//Variable para acumular las horas de Mtto Preventivo
		$horas=0;
		//Sentencia SQL para extraer el total de horas efectivas
		$sql="SELECT SUM(mtto_prev) AS total FROM horometro_odometro WHERE equipos_id_equipo='$equipo' AND fecha='$fecha' AND turno='$turno'";
		//Ejecutar la sentencia SQL
		$rs=mysql_query($sql);
		//Verificar resultados
		if($datos=mysql_fetch_array($rs)){
			//Si regresa NULL, regresar 0, de lo contrario, asignar el Valor
			if($datos["total"]!=NULL)
				$horas=$datos["total"];
		}
		//Retornar el valor de las Horas
		return $horas;
	}//Fin de obtenerHorasXFechaXTurno($equipo,$fecha,$turno)
	
	function guardarReporteStatus($txt_fechaIni,$txt_fechaFin,$cmb_turno,$cmb_equipo){
		//Esribir datos de la pagina en excel
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=ReporteStatus.xls");
		//Definir el estilo de la tabla y el encabezado que aparecera sobre la tabla?>
			<head>
				<style>					
					<!--
					body { font-family:Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; }
					/*En este formato  se establece el tamaño,tipo de letra,color de fondo que ocupa la columna principal de una tabla*/
					.nombres_columnas { font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #FFFFFF; background-color: #9BBA59; font-weight: bold; 
					border-top-width: medium; border-right-width: thin;
					border-bottom-width: medium; border-left-width: thin; border-top-style: solid; border-right-style: solid; border-bottom-style: 
					solid; border-left-style: solid; 
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
					.msje_correcto{font-family: Arial, Helvetica, sans-serif;font-size: 9px;color: #0000CC;font-weight:bold;}
					.msje_incorrecto{font-family: Arial, Helvetica, sans-serif;font-size: 9px;color: #FF0000;font-weight:bold;}
					-->
				</style>
			</head>											
			<body>
		<?php
		//Area de donde se esta obteniendo el reporte
		$area="MINA";
		//Obtener las Fechas
		$fechaI=modFecha($txt_fechaIni,3);
		$fechaF=modFecha($txt_fechaFin,3);
		//Extraer y verificar el Equipo, si tiene valor diferente de vacio, verificar la Familia
		$equipo=$cmb_equipo;
		//Obtener el Turno
		$turno=$cmb_turno;
		//Preparacion de la sentencia SQL
		$sql_stm="SELECT equipos_id_equipo,fecha,turno,disponibilidad,observaciones FROM estatus WHERE fecha BETWEEN '$fechaI' AND '$fechaF'";
		if($turno!=""){
			$sql_stm.=" AND turno='$turno'";
			$hrsProgramadas=8;
		}
		if($equipo!="")
			$sql_stm.=" AND equipos_id_equipo='$equipo'";
		else
			$sql_stm.=" AND equipos_id_equipo=ANY(SELECT id_equipo FROM equipos WHERE area='$area')";
		//Concatenar la parte final de la sentencia
		$sql_stm.=" ORDER BY fecha,equipos_id_equipo,turno";
		//Conectar a la BD de Mtto
		$conn=conecta("bd_mantenimiento");
		//Ejecutar la sentencia
		$rs=mysql_query($sql_stm);
		//Extraer los datos de la consulta
		if($datos=mysql_fetch_array($rs)){
			?>
			<div id="tabla">				
				<table width="1100">					
					<tr>
						<td align="left" valign="baseline" colspan="2"><img src="http://<?php echo HOST; ?>/<?php echo SISAD; ?>/images/logo.png" width="118" height="58" 
						align="absbottom" /></td>
						<td colspan="2">&nbsp;</td>
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
						<td colspan="7" align="center" class="titulo_tabla">REPORTE DE ESTATUS DE EQUIPOS</td>
					</tr>
					<tr>
						<td colspan="7">&nbsp;</td>
					</tr>			
					<tr>
						<td>&nbsp;</td>
						<td class="nombres_columnas" align="center">FECHA</td>
						<td class='nombres_columnas' align="center">EQUIPO</td>
						<td class="nombres_columnas" align="center">TURNO</td>
						<td class='nombres_columnas' align="center">STATUS</td>						
						<td class='nombres_columnas' align="center">OBSERVACIONES</td>
						<td>&nbsp;</td>
					</tr>
			<?php
			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{	?>
					<tr>
						<td>&nbsp;</td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo modFecha($datos["fecha"],1) ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['equipos_id_equipo']; ?></td>						
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['turno']; ?></td>						
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['disponibilidad']; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['observaciones']; ?></td>
						<td>&nbsp;</td>
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
