<?php 
	/**
	  * Nombre del Módulo: Laboratorio                                               
	  * Nombre Programador: Daisy Adriana Martínez Fernández
	  * Fecha: 25/Junio/2011                                      			
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
	
	  			
	
	
	//Ubicacion de las imagenes que estan contenidas en los encabezados
	define("HOST", $_SERVER['HTTP_HOST']);
	//Obtener el nombre del Nombre de la Carpeta Raíz donde se encontrará almacenado el SISAD
	$raiz = explode("/",$_SERVER['PHP_SELF']);
	define("SISAD",$raiz[1]);	
	
	
	if(isset($_POST['rdb_id'])){
		guardarRepPA($hdn_depto);				
	}
	if(!isset($_POST['rdb_id'])){
		switch($hdn_origen){
				case "ordenTrabajo":
					guardarRepOrdenTrabajo($hdn_consulta,$hdn_nomReporte,$hdn_msg);
				break;
				case "ReporteFechas":
					exportarReporteFechas();
				break;
				case "ReportePeriodo":
					exportarReportePeriodo();
				break;
				case "reporteComparativoMensual":
					guardarReporteComparativoMensual($hdn_nomReporte, $hdn_consulta, $hdn_msg, $hdn_fechaIniBD, $hdn_fechaFinBD,$hdn_combo, $hdn_consultaMesAnt, $hdn_consultaMes1, $hdn_consultaMes2, $hdn_empleados);
				break;
				case "reporteAusentismo":
					guardarRepAusentismo($hdn_consulta,$hdn_nomReporte,$hdn_msg,$hdn_fecha, $hdn_fechaIni, $hdn_fechaFin);
				break;
				case "reporteCapacitaciones":
					guardarRepCapacitaciones($hdn_consulta,$hdn_nomReporte,$hdn_msg);								
				break;
				case "mttoPreventivo":
					guardarRepPreventivo($hdn_consulta,$hdn_nomReporte,$hdn_msg);
				break;	
				case "mttoCorrectivo":
					guardarRepCorrectivo($hdn_consulta,$hdn_nomReporte,$hdn_msg);
				break;
				case "reporteListaMaestra":
					guardarRepListaMaestra($hdn_consulta,$hdn_nomReporte);
				break;
				case "consultarOrdenTrabajo":
					guardarConsultaOrdenTrabajo($hdn_consulta,$hdn_nomConsulta,$hdn_msg);
				break;	
				case "bitacoraPrev" || "bitacoraCorr":
					guardarConsultaBitacora($hdn_origen,$hdn_consulta,$hdn_consultaMat,$hdn_consultaAct,$hdn_consultaMec,$hdn_consultaGam,$hdn_nomReporte,$hdn_msg);
				break;
		}
	}
	
	if(isset($_POST['sbt_excel'])){
		if(isset($_POST['hdn_consulta'])){
		
			//Ubicacion de las imagenes que estan contenidas en los encabezados
			define("HOST", $_SERVER['HTTP_HOST']);
			//Obtener el nombre del Nombre de la Carpeta Raíz donde se encontrará almacenado el SISAD
			$raiz = explode("/",$_SERVER['PHP_SELF']);
			define("SISAD",$raiz[1]);
		
		
			switch($hdn_tipoReporte){
				case "reporte_requisiciones":
					guardarRepRequisiciones($hdn_consulta,$hdn_nomReporte,$hdn_msg);
				break;
				case "reporte_detallerequisiciones":
					guardarRepDetalleReq($hdn_consulta,$hdn_nomReporte,$hdn_msg);
				break;
			}
		}
	}
	

	//Esta funcion exporte el REPORTE CORRECTIVO a un archivo de excel
	function guardarRepListaMaestra($hdn_consulta,$hdn_nomReporte){
		//Esribir datos de la pagina en excel
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=$hdn_nomReporte.xls");
				
		//Realizar la conexion a la BD de Aseguramiento
		$conn = conecta("bd_aseguramiento");
		
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
					.nombres_filas { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #9BBB59; 
					vertical-align:middle; text-align:center;}
					/*En este formato  se establece el tamaño,tipo de letra,color de fondo que contendra datos  de una tabla*/
					.renglon_gris { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #E7E7E7;
					vertical-align:middle; text-align:center;}
					/*En este formato  se establece el tamaño,tipo de letra,color de fondo que contendra datos  de una tabla*/
					.renglon_blanco { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #FFFFFF;
					vertical-align:middle; text-align:center; } 
					#tabla { position:absolute; left:0px; top:0px; width:1111px; height:175px; z-index:5; }
					.texto_encabezado {font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #0000CC; }
					.sub_encabezado {font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #0000CC; }
					.titulo_tabla {font-family: Arial, Helvetica, sans-serif; font-weight: bold; font-size: 14px; }
					.borde_linea { border-top:3px; border-top-color:#4E6128; border-top-style:solid;}
					.borde_firma { border-top:3px; border-top-color:#000000; border-top-style:solid;}
					.nombres_tablas {font-family: Arial, Helvetica, sans-serif;	font-size: 12px;	font-weight: normal;	border-top-width: medium;	
					border-right-width:  thin;	border-bottom-width: medium;	border-left-width: thin;	border-top-style: solid;	border-right-style: solid;
					border-bottom-style: solid;	border-left-style: solid;	border-top-color: #000000;	border-bottom-color: #000000;	border-left-color: #000000;
					border-right-color: #000000;}
					.Estilo6 {font-size: 14;color:#0000CC;font-weight: bold;}
					.Estilo4 {font-size: 14px; color:#0000CC; font-weight: bold;}
					.Estilo5 {font-size: 14px; color:#000000; font-weight: bold;}
					.caracter{color:#FFFFFF;}
					.Estilo13 {font-size: 14px; color:#000000; font-weight: bold;}
					.Estilo13 {font-size: 12px}
.Estilo14 {font-size: 9px}
					-->
				</style>
			</head>											
			<body>
			<div id="tabla">				
				<table width="100%">					
					<tr>
						<td align="left" valign="baseline" colspan="2"><img src="http://<?php echo HOST; ?>/<?php echo SISAD; ?>/images/logo.png" width="118" height="58" 
                        align="absbottom" /></td>
						<td colspan="7">&nbsp;</td>
						<td valign="baseline" colspan="4">
							<div align="right"><span class="texto_encabezado">
								<strong>MANUAL  DE PROCEDIMIENTOS DE LA CALIDAD</strong><br><em>CONCRETO  LANZADO DE FRESNILLO, S.A DE C.V.</em>
							</span></div>						</td>
					</tr>											
					<tr>
						<td colspan="13" align="center" class="borde_linea">
							<span class="sub_encabezado">CONFIDENCIAL, PROPIEDAD DE &ldquo;CONCRETO LANZADO DE FRESNILLO, S.A DE C.V.&rdquo; PROHIBIDA SU REPRODUCCI
                            &Oacute;N TOTAL O PARCIAL</span>						</td>
					</tr>					
					<tr>
						<td colspan="13">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="13">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="13" align="center" class="Estilo6">F. 4.2.4 -01 LISTA MAESTRA DE REGISTROS DE CALIDAD</td>
					</tr>
					<tr>
						<td colspan="13">&nbsp;</td>
					</tr>			
					<tr>
						<td width="28" align='center' class='nombres_columnas'>NO.</td>
						<td width="139" align='center' class='nombres_columnas'>C&Oacute;DIGO DE FORMA</td>
						<td width="189" align='center' class='nombres_columnas'>DEPTO EMISOR</td>
						<td width="79" align='center' class='nombres_columnas'>NO. REV.</td>
						<td width="119" align='center' class='nombres_columnas'>FECHA DE REV.</td>
						<td width="161" align='center' class='nombres_columnas'>TITILO</td>
						<td width="67" align='center' class='nombres_columnas'>UBICACI&Oacute;N</td>
						<td width="109" align='center' class='nombres_columnas'>ACCESIBLE A</td>
						<td width="88" align='center' class='nombres_columnas'>METODO DE COLECCI&Oacute;N</td>
						<td width="91" align='center' class='nombres_columnas'>INDEXACI&Oacute;N</td>
						<td width="90" align='center' class='nombres_columnas'>PERIODO MTTO</td>						
						<td width="112" align='center' class='nombres_columnas'>DISPOSICI&Oacute;N FINAL</td>												
						<td width="79" align='center' class='nombres_columnas'>DOC. ASOCIADOS</td>												
      				</tr>
			<?php
			$nom_clase = "renglon_gris";
			$cont = 1;
			$cant_total = 0;
			do{
				//Obtenemos para quien tiene acceso la lista maestra de registros de calidad
				$accesible = obtenerDato("bd_aseguramiento", "catalogo_acceso", "acceso", "id_acceso", $datos['catalogo_acceso_id_acceso']);
			?>			
					<tr>
						<td class="<?php echo $nom_clase; ?>" align='center'><?php echo $cont;?></td>
						<td class="<?php echo $nom_clase; ?>" align='center'><?php echo $datos['dpto_emisor'];?></td>
						<td class="<?php echo $nom_clase; ?>" align='center'><?php echo $datos['codigo_forma'];?></td>
						<td class="<?php echo $nom_clase; ?>" align='center'><?php echo $datos['no_rev'];?></td>
						<td class="<?php echo $nom_clase; ?>" align='center'><?php echo modFecha($datos['fecha_revision'],1);?></td>
						<td class="<?php echo $nom_clase; ?>" align='center'><?php echo $datos['titulo'];?></td>
						<td class="<?php echo $nom_clase; ?>" align='center'><?php echo $datos['ubicacion'];?></td>
						<td class="<?php echo $nom_clase; ?>" align='center'><?php echo $accesible;?></td>
						<td class="<?php echo $nom_clase; ?>" align='center'><?php echo $datos['metodo_coleccion'];?></td>
						<td class="<?php echo $nom_clase; ?>" align='center'><?php echo $datos['indexacion'];?></td>
						<td class="<?php echo $nom_clase; ?>" align='center'><?php echo $datos['periodo_mantenimiento'];?></td>
						<td class="<?php echo $nom_clase; ?>" align='center'><?php echo $datos['disposicion_final'];?></td>
						<td class="<?php echo $nom_clase; ?>" align='center'><?php echo $datos['doc_aso'];?></td>																		
					</tr>
				<?php
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
			}while($datos=mysql_fetch_array($rs_datos)); ?>
				<tr><td>&nbsp;</td></tr><?php 
				if($cont<40){
					for($i=1; $i<40;$i++){?>
						<tr><td>&nbsp;</td></tr><?php
					}
				}?>
				<tr>
					<th colspan="13" valign="bottom" bordercolor="#0000FF" class="Estilo6">___________________________________________________________________________________________________________________________________________________________			        </th>
				</tr>
				<tr>
					<td colspan="2" align="center" class="Estilo4"><span class="Estilo14">Fecha Emisi&oacute;n</span> </td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td class="Estilo4" align="center"><span class="Estilo14">No. Revisi&oacute;n</span></td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td colspan="2" class="Estilo4" align="center"><span class="Estilo14">Fecha Revisi&oacute;n</span></td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td colspan="2" class="Estilo4" align="center"><span class="Estilo14">Página 1 de 1</span></td>
				</tr>
				<tr>
					<td colspan="2" align="center" class="Estilo4"><span class="Estilo14">Feb - 10 </span> </td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td class="Estilo4" align="center"><span class="Estilo14"> Rev. 01 </span></td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td colspan="2" class="Estilo4" align="center"><span class="Estilo14">Feb. 10</span></td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td  colspan="2" class="Estilo4" align="center"><span class="Estilo14">F. 4.2.1-01, Rev. 01</span></td>
				</tr>
			</table>
			</body><?php
		}
		
	//Cerrar la conexion con la BD		
		mysql_close($conn);		
	}//Fin de la Funcion guardarRepPreventivos($hdn_consulta,$hdn_nomReporte




//Esta funcion exporta a Excel el resultado de las CONSULTAS REALIZADAS al Modulo de Almacen		
	function guardarReporteComparativoMensual($hdn_nomReporte, $hdn_consulta,$hdn_msg, $hdn_fechaIniBD, $hdn_fechaFinBD, $hdn_combo, $hdn_consultaMesAnt, $hdn_consultaMes1, $hdn_consultaMes2, $hdn_empleados){
		//Esribir datos de la pagina en excel
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=$hdn_nomReporte.xls");
				
		//Realizar la conexion a la BD de Almacen
		$conn = conecta("bd_gerencia");
		
		//Seccionamos la fecha de inicio
		$seccFechaIniBD=split("-",$hdn_fechaIniBD);
		$mesIni=$seccFechaIniBD[1];
		$anioIni=$seccFechaIniBD[0];
		$diasMesIni=diasMes($mesIni,$anioIni);
	
		//Seccionaos la fecha de Fin para obtener los conceptos de manera separada y asi pdoer procesarlos
		$seccFechaFinBD=split("-",$hdn_fechaFinBD);
		$mesFin=$seccFechaFinBD[1];
		$anioFin=$seccFechaFinBD[0];
		$diaFin=$seccFechaFinBD[2];
		$diasMesFin=diasMes($mesIni,$anioIni);
	
		//Seccionamos el combo que contiene el periodo	
		$secCombo=split("-",$hdn_combo);
		$mes1=obtenerNombreCompletoMes($secCombo[1]);
		$mes2=obtenerNombreCompletoMes($secCombo[2]);
		$mes3=obtenerMesAnterior($mes1);
		$anio1=$secCombo[0];
		$anio2=$secCombo[0];
		$anio3=$secCombo[0];
		
		//Comprobamos que meses estan seleccionados para poner el valor
		if($mes2=="ENERO" && $mes1=="DICIEMBRE" && $mes3=="NOVIEMBRE"){		
		 	$anio2=$anio1-1;
			$anio3=$anio1;
			$anio1=$anio1-1;
		}
		if($mes3=="DICIEMBRE" && $mes2=="FEBRERO" && $mes1=="ENERO"){		
		 	$anio2=$anio1-1;
			$anio3=$anio1;
			$anio1=$anio1;
		}
		
		//Obtenemos el numero de mes del mes anterior para asi poder obtener el numero de dias y complementar la consulta
		$obtenerNumMesAnterior=obtenerNumMes($mes3);
		$diasMesAnterior=diasMes($obtenerNumMesAnterior,$anio2);
		
	
		//Ejecutar la sentencia y almacena los datos de la consulta en la variable $rs (result set)
		$rs_datos = mysql_query($hdn_consulta);
			
		if($datos=mysql_fetch_array($rs_datos)){
			//Definir el estilo de la tabla y el encabezado que aparecera sobre la tabla?>
			<head>
				<style>					
					<!--
					body { font-family:Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; }
					/*En este formato  se establece el tamaño,tipo de letra,color de fondo que ocupa la columna princiaplal de una tebla*/
					.nombres_columnas { font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #FFFFFF; background-color: #9BBA59; font-weight: bold; border-top-width: medium; border-right-width: thin;
										border-bottom-width: medium; border-left-width: thin; border-top-style: solid; border-right-style: none; border-bottom-style: solid; border-left-style: none; 
										border-top-color: #000000; border-bottom-color: #000000; }
					/*En este formato  se establece el tamaño,tipo de letra,color de fondo que se ocupa en la fila principal para resaltar datos  de una tebla*/
					.nombres_filas { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #9BBB59; }
					/*En este formato  se establece el tamaño,tipo de letra,color de fondo que contendra datos  de una tebla*/
					.renglon_gris { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #E7E7E7; }
					/*En este formato  se establece el tamaño,tipo de letra,color de fondo que contendra datos  de una tebla*/
					.renglon_blanco { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #FFFFFF; } 
					#tabla { position:absolute; left:0px; top:0px; width:1111px; height:175px; z-index:5; }
					.texto_encabezado {font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #0000CC; }
					.sub_encabezado {font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #0000CC; }
					.titulo_tabla {font-family: Arial, Helvetica, sans-serif; font-weight: bold; font-size: 14px; }
					.borde_linea { border-top:3px; border-top-color:#4E6128; border-top-style:solid;}
					.caracter{color:#9BBA59;}
					-->
				</style>
			</head>											
			<body>
			<div id="tabla">				
				<table width="1265">					
					<tr>
						<td height="71" colspan="2" align="left" valign="baseline"><img src="http://<?php echo HOST; ?>/<?php echo SISAD; ?>/images/logo.png" width="118" 
                        height="58" align="absbottom" /></td>
						<td colspan="5">&nbsp;</td>
					</tr>											
					<tr>
						<td colspan="4" align="center" class="borde_linea">
							<span class="sub_encabezado">CONFIDENCIAL, PROPIEDAD DE &ldquo;CONCRETO LANZADO DE FRESNILLO, S.A DE C.V.&rdquo; PROHIBIDA SU REPRODUCCI&Oacute;N TOTAL O PARCIAL</span>
						</td>
					</tr>					
					<tr>
						<td colspan="4">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="4">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="4" align="center" class="titulo_tabla"><?php echo $hdn_msg;?></td>
					</tr>
					<tr>
						<td colspan="4">&nbsp;</td>
					</tr>			
				<tr>
					<td width="345" rowspan='2' align='center' class='nombres_columnas'>CONCEPTO</td>
					<td colspan='3' class='nombres_columnas' align='center'>MES</td>
				</tr>
				<tr>
					<td width="300" align='center' class='nombres_columnas'><span class="caracter">'</span><?php echo $mes3." ".$anio2;?></td>
					<td width="300" align='center' class='nombres_columnas'><span class="caracter">'</span><?php echo $mes1." ".$anio1;?></td>
					<td width="300" align='center' class='nombres_columnas'><span class="caracter">'</span><?php echo $mes2." ".$anio3;?></td>
				</tr>
			<?php
			$nom_clase = "renglon_gris";
			$cont = 1;
			//Variables que nos permite acyumular el total del mes Anterior	EJ... Diciembre, enero, febrero... Diciembre seria el mes anterior
			$totalMesAnt="";
			//Variable que almacena el primer mes; considerando el ejemplo anterior el primer mes seria enero
			$totalMes1="";
			//Almacena el segundo mes tomando de referencia el ejemplo antes mencionado
			$totalMes2="";
			//Permite acumular el total de Dias en el mes anterior
			$totalDiasGralAnt="";
			//Permite guardar el total de dias del mes 1
			$totalDiasGralMes1="";
			//Permite almacenar el totl de dias del mes2
			$totalDiasGralMes2="";
			do{			
				//Ejecutar la sentencia y almacena los datos de la consulta en la variable $rs (result set)
				$rsMesMesAnt = mysql_query($hdn_consultaMesAnt);
				$cantMesAnt=mysql_fetch_array($rsMesMesAnt);
				//Si la cantidad es igual a null la igualamos a cero para que no afecte en las operaciones y permita realizar las mismas
				if($cantMesAnt['cantidad']==NULL){
					$cantMesAnt['cantidad']=0;
				}
				//Variables para acumular elt otal del mes
				$totalMesAnt=$totalMesAnt+$cantMesAnt['cantidad'];
				$totalDiasGralAnt=restarFechas($anio2."-".$obtenerNumMesAnterior."-". 01,$anio2."-".$obtenerNumMesAnterior."-".$diasMesAnterior);
				
				//Ejecutamos la consulta
				$rsMesMes1 = mysql_query($hdn_consultaMes1);
				$cantMes1=mysql_fetch_array($rsMesMes1);
				//Si la cantidad es igual a null la igualamos a cero para que no afecte en las operaciones y permita realizar las mismas
				if($cantMes1['cantidad']==NULL){
					$cantMes1['cantidad']=0;
				}
				//Variables para acumular el total del mes
				$totalMes1=$totalMes1+$cantMes1['cantidad'];
				$totalDiasGralMes1=restarFechas($hdn_fechaIniBD,$anio1."-".$mesIni."-".$diasMesIni)+1;
				
				//Ejecutamos la sentencia que permnite el proceso del siguiente mes
				$rsMesMes2 = mysql_query($hdn_consultaMes2);
				$cantMes2=mysql_fetch_array($rsMesMes2);
				//Si la cantidad es igual a null la igualamos a cero para que no afecte en las operaciones y permita realizar las mismas
				if($cantMes2['cantidad']==NULL){
					$cantMes2['cantidad']=0;
				}
				//Variables para acumular los resultados del mes 2
				$totalMes2=$totalMes2+$cantMes2['cantidad'];
				$totalDiasGralMes2=restarFechas($anio3."-".$mesFin."-". 01,$anio3."-".$mesFin."-".$diaFin)+1;?>
					<tr>
						<td class='nombres_columnas' align='center'><?php echo $datos['concepto'];?></td>
						<td class='<?php echo $nom_clase; ?>' align='center'><?php echo number_format($cantMesAnt['cantidad'],2,".",",");?></td>
						<td class='<?php echo $nom_clase; ?>' align='center'><?php echo number_format($cantMes1['cantidad'],2,".",",");?></td>
						<td class='<?php echo $nom_clase; ?>' align='center'><?php echo number_format($cantMes2['cantidad'],2,".",",");?></td>
					</tr>
				<?php
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
					
			}while($datos=mysql_fetch_array($rs_datos)); ?>
				<tr>
					<td colspan='1' class='nombres_columnas' align='center'>TOTAL MES</td>
					<td class='<?php echo $nom_clase?>' align='center'><?php echo number_format($totalMesAnt,2,".",",");?></td>
					<td class='<?php echo $nom_clase?>' align='center'><?php echo number_format($totalMes1,2,".",",");?></td>
					<td class='<?php echo $nom_clase?>' align='center'><?php echo number_format($totalMes2,2,".",",");?></td>
				</tr><?php 
			//Comprobamos que los valores necesarios para realizar la operacion no sean iguales a cero y asi evitar errores al mostrar los resultados
			if($totalMesAnt==0||$_POST["hdn_empleados"]==0||$totalDiasGralAnt==0)
				$productividadMesAnt=0;
			else
				$productividadMesAnt=$totalMesAnt/($_POST["hdn_empleados"]/$totalDiasGralAnt);
			//Comprobamos que los valores necesarios para realizar la operacion no sean iguales a cero y asi evitar errores al mostrar los resultados
			if($totalMes1==0||$_POST["hdn_empleados"]==0||$totalDiasGralMes1==0)
				$productividadMes1=0;
			else
				$productividadMes1=$totalMes1/($_POST["hdn_empleados"]/$totalDiasGralMes1);
			//Comprobamos que los valores necesarios para realizar la operacion no sean iguales a cero y asi evitar errores al mostrar los resultados
			if($totalMes2==0||$_POST["hdn_empleados"]==0||$totalDiasGralMes2==0)
				$productividadMes2=0;
			else
				$productividadMes2=$totalMes2/($_POST["hdn_empleados"]/$totalDiasGralMes2);?>
				<tr>
					<td colspan='1' class='nombres_columnas' align='center'>PRODUCTIVIDAD ((m&sup3;/m&sup2;)/PERSONA/D&Iacute;A)</td>
					<td class='nombres_columnas' align='center'><?php echo number_format($productividadMesAnt,2,".",",");?></td>
					<td class='nombres_columnas' align='center'><?php echo number_format($productividadMes1,2,".",",");?></td>
					<td class='nombres_columnas' align='center'><?php echo number_format($productividadMes2,2,".",",");?></td>
				</tr>
			</table>
			</div>
			</body><?php
	}
		
		//Cerrar la conexion con la BD		
		mysql_close($conn);		
	}//Fin de la Funcion guardarRepConsulta($hdn_consulta,$hdn_nomReporte,$hdn_mensaje)	



	//Esta funcion exporte el REPORTE  a un archivo de excel
	function guardarRepPA($hdn_depto){
		//Esribir datos de la pagina en excel
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=ReportePlanAcciones_".$hdn_depto."_$_POST[rdb_id]_".date("d-m-Y").".xls"); 

		//Realizar la conexion a la BD 
		$conn = conecta("bd_aseguramiento");
		//Variables que seran utiliazadas en el proceso de la generacion del archivo
		$idPlan=$_POST['rdb_id'];
		$copias="";
		$participantes="";
		
		/******************************************************REFERENCIAS*************************************************/
		//Sentencia que nos permite mostrar las referencias registradas para el plan de acciones seleccionado
		$stm_sqlRef = "SELECT * FROM (referencias JOIN detalle_referencias ON id_referencia=referencias_id_referencia) 
					   WHERE detalle_referencias.plan_acciones_id_plan_acciones='$idPlan'";
		//Ejecutar la sentencia
		$rs_datosRef = mysql_query($stm_sqlRef);
		//Almacenar los resultados en un arreglo para mostrarlos posteriormente
		$datosRed=mysql_fetch_array($rs_datosRef);
		
		/******************************************************PLAN ACCIONES***********************************************/
		//Creamos la consulta que nos permitira obtener los conceptos para poder generar dicho reporte
		$sql_datosPrinPA="SELECT * FROM plan_acciones WHERE id_plan_acciones='$idPlan'";
		//Ejecutar la sentencia y almacena los 	datos de la consulta en la variable $rs (result set)
		$rs_datosPrinPA = mysql_query($sql_datosPrinPA);
		//Guardar los datos en variables para mostrarlos despues en el reporte
		if($datopPrinPA=mysql_fetch_array($rs_datosPrinPA)){
			//Almacenamos los datos en variables para manejarlos dentro del reporte
			$area_auditada=$datopPrinPA['area_auditada'];
			$creador=$datopPrinPA['creador'];
			$aprobador=$datopPrinPA['aprobador'];
			$verificador=$datopPrinPA['verificador'];
			$fecha=modFecha($datopPrinPA['fecha'],6);
			$revision=$datopPrinPA['revision'];
			$no_documento=$datopPrinPA['no_documento'];
			$referencia = $datopPrinPA['referencia'];
		}
		
		/******************************************************COPIAS ENTREGADAS*********************************************/
		//Creamos la consulta que nos permitira obtener los conceptos para poder generar dicho reporte
		$sql_copiasEntregadas = "SELECT * FROM copias_entregadas WHERE plan_acciones_id_plan_acciones='$idPlan'";
		//Ejecutar la sentencia y almacena los 	datos de la consulta en la variable $rs (result set)
		$rs_CE = mysql_query($sql_copiasEntregadas);
		//Variable que nos permite concatenar un espacio en blanco solo despues del primer registro
		$cont=1;
		//Guardar los datos en variables para mostrarlos despues en el reporte
		if($datoCE=mysql_fetch_array($rs_CE)){
			do{
				$nomDepto = obtenerDato("bd_usuarios", "usuarios", "depto", "no_depto", $datoCE['catalogo_departamentos_id_departamento']);
				$nomDepto = strtoupper($nomDepto);
				if($cont==1){				
					$copias = $nomDepto;
				}
				else{
					$copias .= ", ".$nomDepto;
				}
				$cont++;
			}while($datoCE=mysql_fetch_array($rs_CE));
		}
		
		//Realizar la conexion a la BD 
		$conn = conecta("bd_aseguramiento");
		/******************************************************PARTICIPANTES*************************************************/
		//Creamos la consulta que nos permitira obtener los conceptos para poder generar dicho reporte
		$sql_participantes = "SELECT nombre FROM catalogo_participantes_auditoria WHERE plan_acciones_id_plan_acciones='$idPlan'";
		//Ejecutar la sentencia y almacena los 	datos de la consulta en la variable $rs (result set)
		$rs_part = mysql_query($sql_participantes);
		//Variable que nos permite concatenar un espacio en blanco solo despues del primer registro
		$conta=1;
		//Guardar los datos en variables para mostrarlos despues en el reporte
		if($datosPart=mysql_fetch_array($rs_part)){
			do{
				if($conta==1){				
					$participantes = $datosPart['nombre'];
				}
				else{
					$participantes .= ", ".$datosPart['nombre'];
				}
				$conta++;
			}while($datosPart=mysql_fetch_array($rs_part));
		}
	
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
				.texto_encabezado {font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #0000CC; }
				.sub_encabezado {font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #0000CC; }
				.titulo_tabla {font-family: Arial, Helvetica, sans-serif; font-weight: bold; font-size: 14px; }
				.borde_linea { border-top:3px; border-top-color:#4E6128; border-top-style:solid;}
				/*En este formato  se establece el tamaño,tipo de letra,color de fondo que ocupa la columna princiaplal de una tebla*/
				.nombres_tablas {font-family: Arial, Helvetica, sans-serif;	font-size: 12px;	font-weight: normal;	border-top-width: medium;	
				border-right-width:  thin;	border-bottom-width: medium;	border-left-width: thin;	border-top-style: solid;	border-right-style: solid;
				border-bottom-style: solid;	border-left-style: solid;	border-top-color: #000000;	border-bottom-color: #000000;	border-left-color: #000000;
				border-right-color: #000000;}
				.Estilo6 {font-size: 14;color:#0000CC;font-weight: bold;}
				.Estilo4 {font-size: 14px; color:#0000CC; font-weight: bold;}
				.Estilo5 {font-size: 14px; color:#000000; font-weight: bold;}
				.Estilo7 {font-size: 14px; color:#0000CC;  font-weight:lighter;}
				.caracter{color:#FFFFFF;}
				.Estilo12 {font-size: 14px; color:#000000; font-weight: bold;}
				.Estilo12 {font-size: 12px}
				.Estilo13 {font-size: 14px; color:#000000; font-weight: bold;}
				.Estilo13 {font-size: 12px}
			-->
			</style>
		</head>													
		<body>
		<table width="100%" border="0" >
         <tr>
						<td align="left" valign="baseline" colspan="3"><img src="http://<?php echo HOST; ?>/<?php echo SISAD; ?>/images/logo.png" width="118" 
                        height="58" align="absbottom" /></td>
						<td colspan="3">&nbsp;</td>
						<td valign="baseline" colspan="5">
							<div align="right"><span class="texto_encabezado">
								<strong>MANUAL  DE PROCEDIMIENTOS DE LA CALIDAD</strong><br><em>CONCRETO  LANZADO DE FRESNILLO, S.A DE C.V.</em>
							</span></div>
						</td>
					</tr>											
					<tr>
						<td colspan="11" align="center" class="borde_linea">
							<span class="sub_encabezado">CONFIDENCIAL, PROPIEDAD DE &ldquo;CONCRETO LANZADO DE FRESNILLO, S.A DE C.V.&rdquo; PROHIBIDA SU REPRODUCCI&Oacute;N TOTAL O PARCIAL</span>
						</td>
					</tr>					
          <tr>
            <td colspan="11">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="11">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="11">&nbsp;</td>
          </tr>
          <tr>
            <td width="221">&nbsp;</td>
            <td colspan="8" align="center" class="nombres_tablas"><span class="Estilo4">F 8.2.2 - 06 PLAN DE ACCIONES CORRECTIVAS DE AUDITOR&Iacute;A INTERNA PARA <em><?php echo $hdn_depto;?></em></span> </td>
            <td width="55">&nbsp;</td>
			<td>&nbsp;</td>
          </tr>
          <tr>
            <td colspan="11">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="11">&nbsp;</td>
          </tr>
          <tr>
            <td class="nombres_columnas" align="center">NO. REF </td>
            <td colspan="2" align="center" class="nombres_columnas">DESVIACI&Oacute;N/OBSERVACI&Oacute;N/EXPLICACI&Oacute;N</td>
            <td colspan="2" align="center" class="nombres_columnas">JUSTIFICACI&Oacute;N DE LA DESVIACI&Oacute;N </td>
            <td width="220" align="center" class="nombres_columnas">ACCI&Oacute;N PLANEADA </td>
            <td width="145" align="center" class="nombres_columnas">FECHA PLANEADA </td>
            <td width="145" align="center" class="nombres_columnas">FECHA REAL TERMINACI&Oacute;N </td>
            <td colspan="3" align="center" class="nombres_columnas">VALIDACI&Oacute;N ASE </td>
		  </tr>
          <?php 
						do{
							$nom_clase = "renglon_gris";
							$cont = 1;?>
          <tr>
            <td class='<?php echo $nom_clase;?>' align='center'><?php echo $datosRed['no_referencia'];?></td>
            <td colspan="2" align='lelf' class='<?php echo $nom_clase;?>'><?php echo $datosRed['desv_obs_exp'];?></td>
            <td colspan="2" align='lelf' class='<?php echo $nom_clase;?>'><?php echo $datosRed['justificacion'];?></td>
            <td class='<?php echo $nom_clase;?>' align='lelf'><?php echo $datosRed['accion_planeada'];?></td>
            <td class='<?php echo $nom_clase;?>' align='center' ><span class="caracter">'</span><?php echo modFecha($datosRed['fecha_planeada'],6);?></td>
            <td class='<?php echo $nom_clase;?>' align='center'><span class="caracter">'</span><?php echo modFecha($datosRed['fecha_real_terminacion'],6);?></td>
            <td colspan="3" align='center' class='<?php echo $nom_clase;?>'><?php echo $datosRed['validacion_ase'];?></td>
		  </tr>
		  <?php 
								//Determinar el color del siguiente renglon a dibujar
								$cont++;
								if($cont%2==0)
									$nom_clase = "renglon_blanco";
								else
									$nom_clase = "renglon_gris";
						}while($datosRed=mysql_fetch_array($rs_datosRef));?>
          <tr>
            <td colspan="11">&nbsp;</td>
          </tr>
          <tr>
            <td class="nombres_columnas" align="center">&Aacute;REA AUDITADA </td>
            <td colspan="2" align="center" class="nombres_columnas">PARTICIPANTES EN LA AUDITOR&Iacute;A </td>
            <td colspan="3" align="center" class="nombres_columnas">COPIA A </td>
            <td colspan="2" align="center" class="nombres_columnas"><p>PERTENECE/REFERENCIA</p></td>
            <td colspan="3" align="center" class="nombres_columnas">NO. DOC. </td>
		  </tr>
          <tr>
            <?php $nom_clase = "renglon_gris";?>
            <td align='center' class='<?php echo $nom_clase;?>'><?php echo $area_auditada;?></td>
            <td colspan="2" align='center' class='<?php echo $nom_clase;?>'><?php echo $participantes;?></td>
            <td colspan="3" align='center' class='<?php echo $nom_clase;?>'><?php echo $copias;?></td>
            <td colspan="2" align='center' class='<?php echo $nom_clase;?>'><?php echo $referencia;?></td>
            <td colspan="3" align='center' class='<?php echo $nom_clase;?>'><?php echo $no_documento;?></td>
		  </tr>
          <tr>
            <td colspan="11">&nbsp;</td>
          </tr>
          <tr>
            <td class="nombres_columnas" align="center">ELABORADO POR </td>
            <td colspan="2" align="center" class="nombres_columnas">APROBADO</td>
            <td colspan="3" align="center" class="nombres_columnas">VERIFICADO POR </td>
            <td colspan="2" align="center" class="nombres_columnas"><p>FECHA EMISI&Oacute;N</p></td>
            <td colspan="3" align="center" class="nombres_columnas">REVISI&Oacute;N</td>
		  </tr>
          <tr>
            <?php  $nom_clase = "renglon_gris";?>
            <td class='<?php echo $nom_clase;?>' align='center'><?php echo $creador;?></td>
            <td colspan="2" align='center' class='<?php echo $nom_clase;?>'><?php echo $aprobador;?></td>
            <td colspan="3" align='center' class='<?php echo $nom_clase;?>'><?php echo $verificador;?></td>
            <td colspan="2" align='center' class='<?php echo $nom_clase;?>'><span class="caracter">'</span><?php echo $fecha;;?></td>
            <td colspan="3" align='center' class='<?php echo $nom_clase;?>'><?php echo $revision;?></td>
		  </tr>
          <tr>
            <td>&nbsp;</td>
            <td colspan="4" class="Estilo13">&nbsp;</td>
            <td colspan="2" class="Estilo13">&nbsp;</td>
            <td colspan="3" class="Estilo13">&nbsp;</td>
            <td width="40">&nbsp;</td>
          </tr>
          <tr>
            <td height="20">&nbsp;</td>
            <td colspan="4" class="Estilo13">&nbsp;</td>
            <td colspan="2" class="Estilo13">&nbsp;</td>
            <td colspan="3" class="Estilo13">&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
        </table>
		</body><?php					
	}//Fin de la Funcion 
	
	

	//Esta funcion exporta los datos del Reporte de Produccion por Fecha
	function exportarReporteFechas(){
		//Esribir datos de la pagina en excel
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=ReporteFechas.xls");				
				
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
				.renglon_volumen { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; } 
				#tabla { position:absolute; left:0px; top:0px; width:1111px; height:175px; z-index:5; }
				.texto_encabezado {font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #0000CC; }
				.sub_encabezado {font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #0000CC; }
				.titulo_tabla {font-family: Arial, Helvetica, sans-serif; font-weight: bold; font-size: 14px; }
				.borde_linea { border-top:3px; border-top-color:#4E6128; border-top-style:solid;}
				.borde_firma { border-top:3px; border-top-color:#000000; border-top-style:solid;}
				-->
			</style>
		</head>											
		<body><?php
		
		//Declarar la variables que seran utilizadas en la exportacion
		$msgPro = "";
		$presupuestoDiario = 0;
		$volProDiario = 0;
		$presAcumulado = 0;
		$volumenRealTotal = 0;
		$difVolumen = 0;
		$difVolAcumulado = 0;
		$observaciones = "";
		
		$msgEq = "";
		$consultaEq = "";
		
		$msgSeg="";
		$consultaSeg="";
		$numAcc="";
		$numRegistros="";
		
		$msgCol="";
		$consultaCol="";
		
		//Recuperar la Fecha
		$fecha = $_POST['hdn_fecha'];
		
		
		//Recuperar toda la información que viene en el POST
		if(isset($_POST['hdn_msgPro'])){
			$msgPro = strtoupper($_POST['hdn_msgPro']);
			$presupuestoDiario = $_POST['hdn_presupuestoDiario'];
			$volProDiario = $_POST['hdn_volProDiario'];
			$presAcumulado = $_POST['hdn_presAcumulado'];
			$volumenRealTotal = $_POST['hdn_volumenRealTotal'];						
			$difVolumen = $volProDiario - $presupuestoDiario;	
			$difVolAcumulado = $volumenRealTotal - $presAcumulado;		
			$observaciones = $_POST['hdn_observaciones'];						
		}
		
		if(isset($_POST['hdn_msgEq'])){
			$msgEq = strtoupper($_POST['hdn_msgEq']);
		}	
		
		if(isset($_POST['hdn_msgSeg'])){
			$msgSeg = strtoupper($_POST['hdn_msgSeg']);
			$consultaSeg = $_POST['hdn_consultaSeg'];
			$numAcc = $_POST['hdn_numAcc'];
			$numRegistros = $_POST['hdn_numRegistros'];						
		}
		
		if(isset($_POST['hdn_msgCol'])){
			$msgCol = strtoupper($_POST['hdn_msgCol']);
			$consultaCol = $_POST['hdn_consultaCol'];
		}	
		
		//Realizar la conexion a la BD de Producción
		$conn = conecta("bd_produccion");?>
		
        <div id="tabla">				
        <table width="100%">					
            <tr>
                <td align="left" valign="baseline" colspan="4">
                    <img src="http://<?php echo HOST; ?>/<?php echo SISAD; ?>/images/logo.png" width="118" height="58" align="absbottom" />
                </td>
                <td align="right" colspan="4">
                    <strong><br><em>CONCRETO  LANZADO DE FRESNILLO, S.A DE C.V.</em></strong>
                </td>				
            </tr>	
			<tr>
				<td class="borde_linea" colspan="8">&nbsp;</td>
			</tr>										
        </table>
        </div><?php				
        
		/*****************************************************************/
		/**********************  DATOS PRODUCCIÓN  ***********************/
		/*****************************************************************/
		
  		//Verificar que la consulta tenga datos
		if($difVolumen!=0 && $difVolAcumulado!=0){?>			

            <div id="tabla">				
                <table width="100%">	
                    <tr>
                        <td colspan="8">&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="8">&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="8" align="center" class="titulo_tabla"><?php echo $msgPro; ?></td>
                    </tr>
                    <tr>
                        <td class='nombres_columnas' align='center'>FECHA</td>
                        <td class='nombres_columnas' align='center'>VOL. PRESUPUESTADO</td>
                        <td class='nombres_columnas' align='center'>VOL. PRODUCIDO</td>
                        <td class='nombres_columnas' align='center'>DIFERENCIA</td>
                        <td class='nombres_columnas' align='center'>ACUMULADO PRESUPUESTADO</td>
                        <td class='nombres_columnas' align='center'>ACUMULADO REAL</td>
                        <td class='nombres_columnas' align='center'>DIFERENCIA</td>
                        <td class='nombres_columnas' align='center'>OBSERVACIONES</td>                        
                    </tr><?php
                $nom_clase = "renglon_gris";
                $cont = 1;
								
				//Revisar si la DIFERENCIA DEL DIA es positiva o negativa y determinar el color de los numeros a mostrar
				$estilo = "style='color:#0000CC; font-weight:bold;'";
				if($difVolumen<0)
					$estilo = "style='color:#FF0000; font-weight:bold;'";												
				//Revisar si la DIFERENCIA DEL PERIODO es positiva o negativa y determinar el color de los numeros a mostrar
				$estilo2 = "style='color:#0000CC; font-weight:bold;'";
				if($difVolAcumulado<0)
					$estilo2 = "style='color:#FF0000; font-weight:bold;'";
                
				//Mostrar todos los registros que han sido completados?>                                
					<tr>
						<td class='<?php echo $nom_clase?>' align='center'><?php echo $fecha; ?></td>
						<td class='<?php echo $nom_clase?>' align='center'><?php echo number_format($presupuestoDiario,2,".",",");?></td>
						<td class='<?php echo $nom_clase?>' align='center'><?php echo number_format($volProDiario,2,".",",");?></td>
						<td class='<?php echo $nom_clase?>' align='center' <?php echo $estilo; ?>><?php echo number_format($difVolumen,2,".",",");?></td>
						<td class='<?php echo $nom_clase?>' align='center'><?php echo number_format($presAcumulado,2,".",",");?></td>
						<td class='<?php echo $nom_clase?>' align='center'><?php echo number_format($volumenRealTotal,2,".",",");?></td>
						<td class='<?php echo $nom_clase?>' align='center' <?php echo $estilo2; ?>><?php echo number_format($difVolAcumulado,2,".",",");?></td>
						<td class='<?php echo $nom_clase?>' align='center'><?php echo $observaciones;?></td>
					</tr>
                
                </table>
				<br><br><?php
		}//fin if($difVolumen!=0 && $difVolAcumulado!=0)
		
				
		/*****************************************************************/
		/************************  EQUIPOS   *****************************/
		/*****************************************************************/				
		//Colocar el Titulo de la Tabla
		if(isset($_POST['hdn_msgEq'])){?>			
            <table cellpadding="5" width="100%">
            <tr>
                <td colspan="8" align="center" class="titulo_tabla"><?php echo $msgEq; ?></td>
            </tr><?php
			
			//Ejecutar la sentencia y almacena los datos de la consulta en la variable $rs (result set)
			$rs_equipos = mysql_query("SELECT DISTINCT nom_equipo FROM equipos WHERE bitacora_produccion_fecha='$fecha'");
				
			if($datos=mysql_fetch_array($rs_equipos)){
							
				//Colocar los Encabezados para las columnas?>
				<tr>
					<td class='nombres_columnas' align='center' width='20%' colspan="2">EQUIPO</td>
					<td class='nombres_columnas' align='center' width='10%' colspan="2">M&sup3;</td>
					<td class='nombres_columnas' align='center' colspan="4">OBSERVACIONES</td>
				</tr><?php

				do{	
					//Obtener los datos acumulados por cada Equipo, sumar el volumen y concatenar las observaciones
					$volAcumulado = 0;
					$observaciones = "";
					$rs_equipo = mysql_query("SELECT vol_producido,observaciones FROM equipos WHERE bitacora_produccion_fecha='$fecha' AND nom_equipo = '$datos[nom_equipo]'");
					if($datos_equipo=mysql_fetch_array($rs_equipo)){
						do{
							$volAcumulado += $datos_equipo['vol_producido'];
							$observaciones .= $datos_equipo['observaciones'].", ";	
						}while($datos_equipo=mysql_fetch_array($rs_equipo));
					}//Cierre if($datos_equipo=mysql_fetch_array($rs_equipo))
					
					//Retirar la ultima como y espacio en blanco de la cadena
					$observaciones = substr($observaciones,0,(strlen($observaciones)-2));?>			
					<tr>                        
						<td class='<?php echo $nom_clase?>' align="center" colspan="2"><?php echo $datos['nom_equipo']; ?></td>
						<td class='<?php echo $nom_clase?>' align="center" colspan="2"><?php echo number_format($volAcumulado,2,".",",");?></td>
						<td class='<?php echo $nom_clase?>' align="left" colspan="4"><?php echo $observaciones;?></td>
					</tr><?php
					
					//Determinar el color del siguiente renglon a dibujar
					$cont++;
					if($cont%2==0)
						$nom_clase = "renglon_blanco";
					else
						$nom_clase = "renglon_gris";
					
				}while($datos=mysql_fetch_array($rs_equipos));
			}//FIN if($datos=mysql_fetch_array($rs_equipos)){?>
			</table>
			<br><br><?php			
		}//FIN if(isset($_POST['hdn_msgEq'])) (EQUIPOS)        
        
		
		/*****************************************************************/
		/************************  COLADOS   *****************************/
		/*****************************************************************/				
		//Colocar el Titulo de la Tabla
		if(isset($_POST['hdn_msgCol'])){?>           				
            <table cellpadding="5" width="100%">				
            <tr>
                <td colspan="8" align="center" class="titulo_tabla"><?php echo $msgCol; ?></td>
            </tr><?php
			
			//Ejecutar la sentencia y almacena los datos de la consulta en la variable $rs (result set)
			$rs_colados = mysql_query($consultaCol);
				
			if($datos=mysql_fetch_array($rs_colados)){
							
				//Colocar los Encabezados para las columnas?>
				<tr>
					<td class='nombres_columnas' align='center' width='30%' colspan="2">CLIENTE</td>
					<td class='nombres_columnas' align='center' width='10%'>M&sup3;</td>
					<td class='nombres_columnas' align='center' width='20%'>COLADO</td>
					<td class='nombres_columnas' align='center' colspan="4">OBSERVACIONES</td>
				</tr><?php

				do{	?>			
					<tr>                        
						<td class='<?php echo $nom_clase?>' align="center" colspan="2"><?php echo $datos['cliente']; ?></td>
						<td class='<?php echo $nom_clase?>' align='center'><?php echo number_format($datos['volumen'],2,".",",");?></td>
						<td class='<?php echo $nom_clase?>' align="center"><?php echo $datos['colado']; ?></td>
						<td class='<?php echo $nom_clase?>' align="center" colspan="4"><?php echo $datos['observaciones']; ?></td>
					</tr><?php
					
					//Determinar el color del siguiente renglon a dibujar
					$cont++;
					if($cont%2==0)
						$nom_clase = "renglon_blanco";
					else
						$nom_clase = "renglon_gris";
					
				}while($datos=mysql_fetch_array($rs_colados));
			}//FIN if($datos=mysql_fetch_array($rs_colados)){?>
			</table>
			<br><br><?php
		}//FIN if(isset($_POST['hdn_msgCol'])) (COLADOS)
        

		/*****************************************************************/
		/************************  SEGURIDAD   ***************************/
		/*****************************************************************/				
		//Colocar el Titulo de la Tabla
		if(isset($_POST['hdn_msgSeg'])){?>           				
            <table cellpadding="5" width="100%">				
            <tr>
                <td colspan="8" align="center" class="titulo_tabla"><?php echo $msgSeg; ?></td>
            </tr><?php
			
			$numAcc;
			$numRegistros;
			$nunInc=($numRegistros-$numAcc);
			
			$rs_seguridad = mysql_query($consultaSeg);
				
			if($datos=mysql_fetch_array($rs_seguridad)){
							
				//Colocar los Encabezados para las columnas?>
				<tr>
					<td class='nombres_columnas' align='center' width='1%' colspan="2">INCIDENTES</td>
					<td class='nombres_columnas' align='center' width='1%' colspan="3">ACCIDENTES</td>
					<td class='nombres_columnas' align='center' width='1%' colspan="3">TOTAL MES</td>
                    <td width="70%"></td>
				</tr>
                <tr>
					<td class='<?php echo $nom_clase?>' align='center' colspan="2"><?php echo $nunInc;?></td>
					<td class='<?php echo $nom_clase?>' align='center' colspan="3"><?php echo $numAcc;?></td>
					<td class='<?php echo $nom_clase?>' align='center' colspan="3"><?php echo $numRegistros;?></td>
				</tr>
           	</table>
           	<table cellpadding="5" width="100%">   
				<tr>
					<td class='nombres_columnas' align='center' width='20%' colspan="2">TIPO</td>
					<td class='nombres_columnas' align='center' colspan="6">OBSERVACIONES</td>
				</tr><?php

				do{	?>			
					<tr>                        
						<td class='<?php echo $nom_clase?>' align="center" colspan="2"><?php echo $datos['tipo']; ?></td>
						<td class='<?php echo $nom_clase?>' align="center" colspan="6"><?php echo $datos['observaciones']; ?></td>
					</tr><?php
					
					//Determinar el color del siguiente renglon a dibujar
					$cont++;
					if($cont%2==0)
						$nom_clase = "renglon_blanco";
					else
						$nom_clase = "renglon_gris";
					
				}while($datos=mysql_fetch_array($rs_seguridad));
			}//FIN if($datos=mysql_fetch_array($rs_colados)){?>
			</table><?php
		}//FIN if(isset($_POST['hdn_msgCol'])) (COLADOS)?>        		
		  
		</body><?php 		
		//Cerrar la conexion con la BD		
		mysql_close($conn);	
	}//Fin de la Funcion consultaReporteFechas()
	
	
	
	/*Esta funcion se encarga de exportar los datos del Reporte por Periodo a una Hoja de Calculo */
	function exportarReportePeriodo(){			
		//Esribir datos de la pagina en excel
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=ReporteFechas.xls");				
				
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
				.renglon_volumen { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; } 				
				.texto_encabezado {font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #0000CC; }
				.sub_encabezado {font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #0000CC; }
				.titulo_tabla {font-family: Arial, Helvetica, sans-serif; font-weight: bold; font-size: 14px; }
				.borde_linea { border-top:3px; border-top-color:#4E6128; border-top-style:solid;}
				.borde_firma { border-top:3px; border-top-color:#000000; border-top-style:solid;}
				-->
			</style>
		</head>											
		<body><?php
		
		
			//Conectarse a la Base de Datos de Produccion
			$conn = conecta("bd_produccion");
			
			//Recuperar datos del POST
			$periodo = $_POST['hdn_periodo'];		
			
			//Verificar si viene el numero de empleados en el post para agregar la Seccion de Productividad al reporte que será exportado
			$noEmpleados = "";
			if(isset($_POST['hdn_numEmpleados']))
				$noEmpleados = $_POST['hdn_numEmpleados'];
			
			//Crear y ejecutar la Sentencia SQL para obtener las fechas del Periodo Seleccionado
			$fechas = mysql_fetch_array(mysql_query("SELECT fecha_inicio, fecha_fin FROM presupuesto WHERE periodo = '$periodo'"));
					
			//Obtener el año de inicio y el año de fin de las fechas que componen el periodo
			$anioInicio = substr($fechas['fecha_inicio'],0,4);
			$anioFin = substr($fechas['fecha_fin'],0,4);
			
			//Seperar el valor del Periodo para obtener los meses, aqui se considera que los periodos son siempre de dos meses consecutivos
			$nomMesInicio = obtenerNombreCompletoMes(substr($periodo,5,3));
			$nomMesFin = obtenerNombreCompletoMes(substr($periodo,9,3));
			
			//Obtener los dias del mes de Inicio del periodo
			$diasMesInicio = diasMes(obtenerNumMes($nomMesInicio), $anioInicio);
							
			
			//Obtener el ancho en dias de los meses que componen el periodo
			$anchoDiasInicio = $diasMesInicio - intval(substr($fechas['fecha_inicio'],-2)) + 1;
			$anchoDiasFin = intval(substr($fechas['fecha_fin'],-2));
			$totalDias = $anchoDiasInicio + $anchoDiasFin;
			
			//Arreglos para almacenar los totales
			$sumPorDia = array();//Arreglo que contendra la suma de todas las ubicaciones por día
			$prodRealPorDia = array();//Arreglo que contendra la produccion real por dia acumulada
			$prodPresPorDia = array();//Arreglo que contendra la produccion presupuestada por dia acumulada
																			
			//Crear la Sentencia para obtener las ubicaciones que tienen registros en el periodo seleccionado
			$sql_stm_ubicaciones = "SELECT DISTINCT id_destino, destino FROM catalogo_destino JOIN datos_bitacora ON id_destino=catalogo_destino_id_destino 
									WHERE bitacora_produccion_fecha>='$fechas[fecha_inicio]' && bitacora_produccion_fecha<='$fechas[fecha_fin]'";
			//Ejecutar la Senetencia para obtener las ubicaciones
			$rs_ubicaciones = mysql_query($sql_stm_ubicaciones);
			
			//Sibujar los registros de cada una de las ubicaciones encontradas, de a renglon por ubicación		
			if($ubicaciones=mysql_fetch_array($rs_ubicaciones)){			
								
				
				/***********************DIBUJAR EL ENCABEZADO DE LA TABLA**********************/?>
				<table border="0" cellpadding="5">					
					<tr>
						<td align="left" valign="baseline" colspan="3">
							<img src="http://<?php echo HOST; ?>/<?php echo SISAD; ?>/images/logo.png" width="118" height="58" align="absbottom" />
						</td>
						<td align="right" colspan="<?php echo ($totalDias - 2) + 2;?>">
							<strong><br><em>CONCRETO  LANZADO DE FRESNILLO, S.A DE C.V.</em></strong>
						</td>				
					</tr>	
					<tr>
						<td class="borde_linea" colspan="<?php echo $totalDias + 3;?>">&nbsp;</td>
					</tr>										
				</table>
				<br><br><br>
				<table border="0" cellpadding="5">
				<caption class="titulo_tabla"><strong>Reporte de Producci&oacute;n en el Periodo <em><u><?php echo $periodo;?></u></em></strong></caption>
				<tr>
					<td rowspan="2" class="nombres_columnas">CONCEPTO</td>
					<td colspan="<?php echo $anchoDiasInicio; ?>" class="nombres_columnas" align="center"><?php echo $nomMesInicio." ".$anioInicio; ?></td>	
					<td colspan="<?php echo $anchoDiasFin; ?>" class="nombres_columnas" align="center"><?php echo $nomMesFin." ".$anioFin; ?></td>	
					<td rowspan="2" class="nombres_columnas" align="center">TOTAL MES</td>
					<td rowspan="2" class="nombres_columnas" align="center">PROMEDIO</td>
				</tr>
				<tr><?php
				//Ciclo para Colocar los dias en el encabezado
				$diaActual = substr($fechas['fecha_inicio'],-2);
				for($i=0;$i<$totalDias;$i++){
					//Si el dia es menor a 10 colocar un cero a la izquierda
					if($diaActual<10){?>
						<td class="nombres_columnas" align="center">0<?php echo $diaActual; ?></td><?php
					}else{?>			
						<td class="nombres_columnas" align="center"><?php echo $diaActual; ?></td><?php
					}
					
					//Inicializar cada posición del arreglo que contandrá la suma por día de todas las ubicaciones
					$sumPorDia[$diaActual] = 0; 
						
					if($diaActual==$diasMesInicio)
						$diaActual = 0;
					
					//Incrementar el dia
					$diaActual++;
				}//Cierre for($i=0;$i<$totalDias;$i++)?>
				</tr><?php
			
			
			
				/***************COLOCAR POR RENGLON EL DETALLE DE CADA UBICACIÓN*****************/
				//Manipular el color de los renglones de cada ubicación
				$nom_clase = "renglon_gris";
				$cont = 1;
				do{?>
					<tr>
						<td class="nombres_filas"><strong><?php echo $ubicaciones['destino'];?></strong></td><?php
						//Obtener el dia, mes y año de inicio como actuales
						$diaActual = substr($fechas['fecha_inicio'],-2);
						$mesActual = substr($fechas['fecha_inicio'],5,2);
						$anioActual = $anioInicio;
						
						//Variables para calcular el total y el promedio de cada Concepto
						$sumTotal = 0;
						$sumPromedio = 0;
						$contRegs = 0;
						
						//Ciclo para colocar el valor de cada dia, en el caso de que no haya registro en el dia, se dejara vacio
						for($i=0;$i<$totalDias;$i++){
							//Armar la Fecha del Dia Actual en formato aaaa-mm-dd para hacer la consulta en la BD
							$fechaActual = $anioActual;
							if($mesActual<10) $fechaActual .= "-0".$mesActual; else $fechaActual .= "-".$mesActual;
							if($diaActual<10) $fechaActual .= "-0".$diaActual; else $fechaActual .= "-".$diaActual;
							
							//Comprobar si la Fecha Actual es Domingo
							$colorFondo = "";
							if(obtenerNombreDia($fechaActual)=="Domingo")
								$colorFondo = "#FFFF00";
													
							//Ejecutar Sentencia SQL para obtener el Volumen del Dia y la Ubicacion Actuales
							$rs_volumen = mysql_query("SELECT SUM(vol_producido) AS vol_producido FROM datos_bitacora 
														WHERE bitacora_produccion_fecha = '$fechaActual' AND catalogo_destino_id_destino = $ubicaciones[id_destino]");
							$volumen = mysql_fetch_array($rs_volumen);
							//Si existe Volumen, imprimirlo
							if($volumen['vol_producido']!=""){?>
								<td align="center" <?php 
									if($colorFondo==""){//Si el dia no es Domingo colocar la clase del Renglon Blanco o Gris segun aplique?> 
										class="<?php echo $nom_clase; ?>"<?php 								
										$sumPromedio += $volumen['vol_producido'];//Obtener la suma total para obtener el Promedio descartando los Domingos
										$contRegs++;//Saber cuantos registros son sin contar los domingos para obtener el promedio
									} 
									else {//Colocar fondo amarillo cuando sea domingo?> 
										bgcolor="#FFFF00" style="font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000;"<?php 
									}?>
								><?php 
									//Imprimir el volumen de la fecha actual								
									echo $volumen['vol_producido']; ?>
								</td><?php
								
								//Obtener la suma total de la ubicacion que esta siendo impresa
								$sumTotal += $volumen['vol_producido'];
								
								//Sumar los volumenes encontrados por dia
								$sumPorDia[$diaActual] += $volumen['vol_producido']; 
								
							}
							else{//Si no existe volumen colocar la celda vacia?>
								<td align="center" <?php if($colorFondo==""){?> class="<?php echo $nom_clase; ?>"<?php } else {?> bgcolor="#FFFF00" <?php }?>>&nbsp;</td><?php
							}
							
							//Cuando se llegue al dia final del primer mes, resetear el contador de Dias y cambiar de Mes NOTA: Falta comprobar cuando se cambia de año
							if($diaActual==$diasMesInicio){
								$diaActual = 0;
								$mesActual++;
								
								//Verificar el cambio de año
								if($mesActual==13){
									$mesActual = 1;
									$anioActual++;
								}
							}
							
							//Incrementar el dia
							$diaActual++;
						}//Cierre for($i=0;$i<$totalDias;$i++)?>
						<td align="center" class="<?php echo $nom_clase; ?>"><strong><?php echo number_format($sumTotal,2,".",","); ?></strong></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><strong><?php echo number_format(floatval($sumPromedio/$contRegs),2,".",","); ?></strong></td>
					</tr><?php
					
					//Determinar el color del siguiente renglon a dibujar
					$cont++;
					if($cont%2==0)
						$nom_clase = "renglon_blanco";
					else
						$nom_clase = "renglon_gris";
				}while($ubicaciones=mysql_fetch_array($rs_ubicaciones));
				
				
				
				
				/*****************COLOCAR EL TOTAL DE CADA DIA DEL PERIODO*******************/?>
				<tr>
					<td class="nombres_filas"><strong>TOTAL D&Iacute;A</strong></td><?php
					//Obtener el dia, mes y año de inicio como actuales
					$diaActual = substr($fechas['fecha_inicio'],-2);
					$mesActual = substr($fechas['fecha_inicio'],5,2);
					$anioActual = $anioInicio;
					
					//Variables para calcular el total y el promedio de cada Concepto
					$sumTotal = 0;
					$sumPromedio = 0;
					$contRegs = 0;
					for($i=0;$i<$totalDias;$i++){
						//Armar la Fecha del Dia Actual en formato aaaa-mm-dd para saber si es domingo o no
						$fechaActual = $anioActual;
						if($mesActual<10) $fechaActual .= "-0".$mesActual; else $fechaActual .= "-".$mesActual;
						if($diaActual<10) $fechaActual .= "-0".$diaActual; else $fechaActual .= "-".$diaActual;
						
						//Comprobar si la Fecha Actual es Domingo
						$colorFondo = "";
						if(obtenerNombreDia($fechaActual)=="Domingo")
							$colorFondo = "#FFFF00";
							
						//Colocar la suma del dia Actual en el caso que exista
						if($sumPorDia[$diaActual]!=0){?>
							<td align="center" style="font-weight:bold; color:#FF0000;" <?php 
								if($colorFondo==""){?> 
									class="<?php echo $nom_clase; ?>"<?php 
									$sumPromedio += $sumPorDia[$diaActual];//Obtener la suma total para obtener el Promedio descartando los Domingos
									$contRegs++;//Saber cuantos registros son sin contar los domingos para obtener el promedio
								} 
								else {?> 
									bgcolor="#FFFF00" style="font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000;" <?php 
								}?>
							><?php 
								echo round($sumPorDia[$diaActual],1); ?>
							</td><?php
							
							//Obtener la suma total de la ubicacion que esta siendo impresa
							$sumTotal += $sumPorDia[$diaActual];
						}
						else{//Si no existe suma del dia colocar un espacio vacio?>
							<td align="center" <?php if($colorFondo==""){?> class="<?php echo $nom_clase; ?>"<?php } else {?> bgcolor="#FFFF00" <?php }?>>&nbsp;</td><?php					
						}
																
						//Cuando se llegue al dia final del primer mes, resetear el contador de Dias y cambiar de Mes NOTA: Falta comprobar cuando se cambia de año
						if($diaActual==$diasMesInicio){
							$diaActual = 0;				
							$mesActual++;
							
							//Verificar el cambio de año
							if($mesActual==13){
								$mesActual = 1;
								$anioActual++;
							}
						}	
						
						//Incrementar el dia
						$diaActual++;
					}//Cierre for($i=0;$i<$totalDias;$i++)?>
					<td align="center" class="<?php echo $nom_clase; ?>"><strong><?php echo number_format($sumTotal,2,".",","); ?></strong></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><strong><?php echo number_format(floatval($sumPromedio/$contRegs),2,".",","); ?></strong></td>
				</tr><?php	
				
				
				
				/****************COLOCAR LA PRODUCCION REAL DEL PERIODO POR DIA*******************/
				//Hacer cambio del color de renglon
				if($nom_clase=="renglon_blanco") $nom_clase="renglon_gris"; else if($nom_clase=="renglon_gris") $nom_clase="renglon_blanco";?>
				<tr>
					<td class="nombres_filas"><strong>REAL</strong></td><?php
					//Obtener el dia y mes de inicio como actuales
					$diaActual = substr($fechas['fecha_inicio'],-2);
					$valDiaAnterior = 0;
					for($i=0;$i<$totalDias;$i++){
						if($i==0){?>
							<td align="center" class="<?php echo $nom_clase; ?>"><strong><?php echo round($sumPorDia[$diaActual],1); ?></strong></td><?php
							$valDiaAnterior = $sumPorDia[$diaActual];
							//Almacenar la Produccion Real Diaria Acumulada
							$prodRealPorDia[$diaActual] = $sumPorDia[$diaActual];
						}
						else{?>
							<td align="center" class="<?php echo $nom_clase; ?>"><strong><?php echo round($sumPorDia[$diaActual]+$valDiaAnterior,1); ?></strong></td><?php
							//Almacenar la Produccion Real Diaria Acumulada
							$prodRealPorDia[$diaActual] = $sumPorDia[$diaActual] + $valDiaAnterior;
							
							//La suma del dia actual se convierte en el valor del dia anterior
							$valDiaAnterior = $sumPorDia[$diaActual] + $valDiaAnterior;						
						}
									
						//Cuando se llegue al dia final del primer mes, resetear el contador de Dias y cambiar de Mes NOTA: Falta comprobar cuando se cambia de año
						if($diaActual==$diasMesInicio)
							$diaActual = 0;															
						
						//Incrementar el dia
						$diaActual++;
					}//Cierre for($i=0;$i<$totalDias;$i++)?>				
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr><?php
				
				
				
				/****************COLOCAR LA PRODUCCION PRESUPUESTADA DEL PERIODO*******************/
				//Hacer cambio del color de renglon			
				if($nom_clase=="renglon_blanco") $nom_clase="renglon_gris"; else if($nom_clase=="renglon_gris") $nom_clase="renglon_blanco";
				//Obtener el Presupusto diario del periodo
				$presupuesto = obtenerDato("bd_produccion", "presupuesto", "vol_ppto_dia", "periodo", $periodo);?>			
				<tr>
					<td class="nombres_filas"><strong>PRESUPUESTO</strong></td><?php
					//Obtener el dia, mes y año de inicio como actuales
					$diaActual = substr($fechas['fecha_inicio'],-2);
					$mesActual = substr($fechas['fecha_inicio'],5,2);
					$anioActual = $anioInicio;
					
					//Variable para acumular el presupuesto dia a dia
					$presAnterior = 0;
					for($i=0;$i<$totalDias;$i++){
						//Armar la Fecha del Dia Actual en formato aaaa-mm-dd para saber si es domingo o no
						$fechaActual = $anioActual;
						if($mesActual<10) $fechaActual .= "-0".$mesActual; else $fechaActual .= "-".$mesActual;
						if($diaActual<10) $fechaActual .= "-0".$diaActual; else $fechaActual .= "-".$diaActual;
						
						//Comprobar si la Fecha Actual es Domingo
						$diaDomingo = false;
						if(obtenerNombreDia($fechaActual)=="Domingo")
							$diaDomingo = true;
					
						//Colocar el presupuesto en el dia inicial
						if($i==0){?>
							<td align="center" class="<?php echo $nom_clase; ?>"><strong><?php echo round($presupuesto,1); ?></strong></td><?php
							$presAnterior = $presupuesto;
							//Almacenar la produccion presupuestada por dia acumulada
							$prodPresPorDia[$diaActual] = $presupuesto;
						}
						else{
							//Verificar si la fecha actual es domingo y colocar directamente el presupuesto anterior
							if($diaDomingo){?>
								<td align="center" class="<?php echo $nom_clase; ?>"><strong><?php echo round($presAnterior,1); ?></strong></td><?php
								//Almacenar la produccion presupuestada por dia acumulada
								$prodPresPorDia[$diaActual] = $presAnterior;
							}
							else{?>						
								<td align="center" class="<?php echo $nom_clase; ?>"><strong><?php echo round($presupuesto+$presAnterior,1); ?></strong></td><?php
								//Almacenar la produccion presupuestada por dia acumulada
								$prodPresPorDia[$diaActual] = $presupuesto + $presAnterior;
								
								//La suma del dia actual se convierte en el valor del dia anterior
								$presAnterior = $presupuesto + $presAnterior;
								
							}
						}
									
						//Cuando se llegue al dia final del primer mes, resetear el contador de Dias y cambiar de Mes NOTA: Falta comprobar cuando se cambia de año
						if($diaActual==$diasMesInicio){
							$diaActual = 0;				
							$mesActual++;
							
							//Verificar el cambio de año
							if($mesActual==13){
								$mesActual = 1;
								$anioActual++;
							}
						}
						
						//Incrementar el dia
						$diaActual++;
					}//Cierre for($i=0;$i<$totalDias;$i++)?>				
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr><?php
				
				
				
				/****************COLOCAR LA DIFERENCIA ENTRE LA PRODUCCION REAL Y LA PRESUPUESTADA*******************/
				//Hacer cambio del color de renglon			
				if($nom_clase=="renglon_blanco") $nom_clase="renglon_gris"; else if($nom_clase=="renglon_gris") $nom_clase="renglon_blanco";?>			
				<tr>
					<td class="nombres_filas"><strong>DIFERENCIA</strong></td><?php
					//Obtener el dia y mes de inicio como actuales
					$diaActual = substr($fechas['fecha_inicio'],-2);
					for($i=0;$i<$totalDias;$i++){
						//Hacer la resta de la Produccion Real menos la Presupuestada, cuando se llega aqui existe un registro de producción real por cada registro de Produccion presupuestada?>
						<td align="center" class="<?php echo $nom_clase; ?>"><strong><?php echo round($prodRealPorDia[$diaActual]-$prodPresPorDia[$diaActual],1);?></strong></td><?php					
						
						//Cuando se llegue al dia final del primer mes, resetear el contador de Dias y cambiar de Mes NOTA: Falta comprobar cuando se cambia de año
						if($diaActual==$diasMesInicio)
							$diaActual = 0;
						
						//Incrementar el dia
						$diaActual++;
					}//Cierre for($i=0;$i<$totalDias;$i++)?>
					<td>&nbsp;</td>
					<td>&nbsp;</td>				
				</tr><?php
				
				
				/****************COLOCAR LA PRODUCTIVIDAD DE CADA DIA*******************/			
				if($noEmpleados!=""){?>
					<tr>
						<td colspan="31" align="right">&nbsp;</td>
						<td colspan="2" align="center" class="nombres_columnas">&nbsp;PRODUCTIVIDAD PROMEDIO</td>
					</tr>
					<tr>
						<td class="nombres_filas"><strong>PRODUCTIVIDAD</strong></td><?php
						//Obtener la Suma Total de la Productividad para sacar el Promedio de la misma
						$totalProductividad = 0;
						$contDias = 0;
						foreach($sumPorDia as $ind => $totalDia){
							//Colocar la suma del dia Actual en el caso que exista
							if($totalDia!=0){
								$productividad = $totalDia/$noEmpleados;?>						
								<td align="center" class="<?php echo $nom_clase; ?>"><?php echo number_format($productividad,2,".","."); ?></td><?php						
								//Obtener la suma total de la ubicacion que esta siendo impresa
								$totalProductividad += $productividad;
								$contDias++;
							}
							else{//Si no existe suma del dia colocar un espacio vacio?>
								<td class="<?php echo $nom_clase; ?>">&nbsp;</td><?php					
							}																				
						}//Cierre foreach($sumPorDia as $ind => $totalDia)?>
						<td colspan="2" class="<?php echo $nom_clase; ?>" align="center"><strong><?php echo number_format($totalProductividad/$contDias,2,".",","); ?></strong></td>
					</tr><?php
				}//Cierre if($noEmpleados!="") ?>												
				
				</table><?php								
				
			}//Cierre if($ubicaciones=mysql_fetch_array($rs_ubicaciones))?>
			
		</body><?php		
	
	}//Cierre de la funcion exportarReportePeriodo()
	
//Esta funcion exporta a Excel REPORTE DE ORDEN DE COMPRA
	function guardarRepVentas($hdn_consulta,$hdn_nomReporte,$hdn_msg){
		//Esribir datos de la pagina en excel
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=$hdn_nomReporte.xls");
				
		//Realizar la conexion a la BD de Almacen
		$conn = conecta("bd_compras");
		//Ejecutar la sentencia y almacena los datos de la consulta en la variable $rs (result set)
		$rs_datos = mysql_query($hdn_consulta);
			
		if($datos=mysql_fetch_array($rs_datos)){
			//Definir el estilo de la tabla y el encabezado que aparecera sobre la tabla?>
			<head>
				<style>					
					<!--
					body { font-family:Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; }
					/*En este formato  se establece el tamaño,tipo de letra,color de fondo que ocupa la columna princiaplal de una tebla*/
					.nombres_columnas { font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #FFFFFF; background-color: #9BBA59; font-weight: bold; 
					border-top-width: medium; border-right-width: thin;
					border-bottom-width: medium; border-left-width: thin; border-top-style: solid; border-right-style: none; border-bottom-style: solid; 
					border-left-style: none; 
					border-top-color: #000000; border-bottom-color: #000000; }
					/*En este formato  se establece el tamaño,tipo de letra,color de fondo que se ocupa en la fila principal para resaltar datos  de una tebla*/
					.nombres_filas { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #9BBB59; }
					/*En este formato  se establece el tamaño,tipo de letra,color de fondo que contendra datos  de una tebla*/
					.renglon_gris { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #E7E7E7; }
					/*En este formato  se establece el tamaño,tipo de letra,color de fondo que contendra datos  de una tebla*/
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
						<td class="nombres_columnas" align="center">NO.</td>
						<td class="nombres_columnas" align="center">FECHA</td>
						<td class="nombres_columnas" align="center">CLIENTE</td>
                        <td class="nombres_columnas" align="center">VENDI&Oacute;</td>
						<td class="nombres_columnas" align="center">AUTORIZACI&Oacute;N</td>
						<td class="nombres_columnas" align="center">SUBTOTAL</td>
						<td class="nombres_columnas" align="center">IVA</td>
						<td class="nombres_columnas" align="center">TOTAL</td>
      				</tr>
			<?php
			$nom_clase = "renglon_gris";
			$cont = 1;
			$cant_total = 0;
			do{	?>			
					<tr>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['id_venta']; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['fecha']; ?></td>
						<td align="left" class="<?php echo $nom_clase; ?>"><?php echo $datos['razon_social']; ?></td>
                        <td align="left" class="<?php echo $nom_clase; ?>"><?php echo $datos['vendio']; ?></td>
                        <td align="left" class="<?php echo $nom_clase; ?>"><?php echo $datos['autorizador']; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>">$ <?php echo number_format($datos['subtotal'],2,".",","); ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>">$ <?php echo number_format($datos['iva'],2,".",","); ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>">$ <?php echo number_format($datos['total'],2,".",","); ?></td>																		
					</tr>
				<?php
				$cant_total += $datos['total'];
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
					
			}while($datos=mysql_fetch_array($rs_datos)); ?>
				<tr><td colspan="7">&nbsp;</td><td class="nombres_columnas" align="center">$ <?php echo number_format($cant_total,2,".",","); ?></td></tr>
			</table>
			</div>
			</body>
<?php	}
		
		//Cerrar la conexion con la BD		
		mysql_close($conn);		
	}//Fin de la Funcion guardarRepOrdenCompra($hdn_consulta,$hdn_nomReporte,$hdn_mensaje)	
	

//Esta funcion exporte el REPORTE AUSENTISMO a un archivo de excel
	function guardarRepAusentismo($hdn_consulta,$hdn_nomReporte,$hdn_msg, $hdn_fecha, $hdn_fechaIni, $hdn_fechaFin){
		//Esribir datos de la pagina en excel
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=$hdn_nomReporte.xls");
			
		//Realizar la conexion a la BD 
		$conn = conecta("bd_recursos");
		
		//Ejecutar la sentencia y almacena los datos de la consulta en la variable $rs (result set)
		$rs_datos = mysql_query($hdn_consulta);
		
		//Verificamos que la consulta genere resultados	
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
						<td align='center' class='nombres_columnas'>NO.</td>
						<td align='center' class='nombres_columnas'>RFC</td>
						<td align='center' class='nombres_columnas'>NOMBRE</td>
						<td align='center' class='nombres_columnas'>AUSENCIAS</td>
						<td align='center' class='nombres_columnas'>ASISTENCIAS A CUMPLIR</td>
						<td align='center' class='nombres_columnas'>&Aacute;rea</td>
						<td align='center' class='nombres_columnas'>PUESTO</td>																																								
      				</tr>
			<?php
			$nom_clase = "renglon_gris";
			$cont = 1;
			do{	
			//Generamos la consulta que cuenta el total de asistencias del empleado segun rfc, se ejecuta esta consulta nuevamente para obtener el estado
			$stm_sql2 = "SELECT COUNT(estado) AS faltas FROM kardex WHERE empleados_rfc_empleado = '$datos[empleados_rfc_empleado]' 
							AND estado='F' AND fecha_entrada>='$hdn_fechaIni' AND fecha_entrada<='$hdn_fechaFin'";
			//Ejecutamos la sentencia
			$rs_datos2 = mysql_query($stm_sql2);
			//Guardamos los resultados de la consulta en el arreglo
			$arrConsulta2 = mysql_fetch_array($rs_datos2);
				?>
				<tr>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $cont; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['empleados_rfc_empleado']; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['nombre']; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $arrConsulta2['faltas']; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $hdn_fecha; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['area']; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['puesto']; ?></td>
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
	}//Fin de la Funcion
	
//Esta funcion exporte el REPORTE CAPACITACIÖN a un archivo de excel
	function guardarRepCapacitaciones($hdn_consulta,$hdn_nomReporte,$hdn_msg){
		//Esribir datos de la pagina en excel
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=$hdn_nomReporte.xls");
			
		//Realizar la conexion a la BD 
		$conn = conecta("bd_recursos");
		
		//Ejecutar la sentencia y almacena los datos de la consulta en la variable $rs (result set)
		$rs_datos = mysql_query($hdn_consulta);
		
		//Verificamos que la consulta haya generado datos	
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
						<td valign="baseline" colspan="4">
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
						<td align='center' class='nombres_columnas'>NO.</td>
						<td align='center' class='nombres_columnas'>RFC</td>
						<td align='center' class='nombres_columnas'>NOMBRE</td>
						<td align='center' class='nombres_columnas'>NOMBRE CAPACITACI&Oacute;N</td>
						<td align='center' class='nombres_columnas'>HORAS CAPACITACI&Oacute;N</td>
						<td align='center' class='nombres_columnas'>DESCRIPCI&Oacute;N</td>
						<td align='center' class='nombres_columnas'>FECHA INICIO</td>
						<td align='center' class='nombres_columnas'>FECHA FIN</td>
						<td align='center' class='nombres_columnas'>INSTRUCTOR</td>																																						
      				</tr>
			<?php
			$nom_clase = "renglon_gris";
			$cont = 1;
			do{	
				?>
				<tr>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $cont; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['empleados_rfc_empleado']; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['nombre']; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['nom_capacitacion']; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['hrs_capacitacion']; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['descripcion']; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo modFecha($datos['fecha_inicio'],1); ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo modFecha($datos['fecha_fin'],1); ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['instructor']; ?></td>
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
	}//Fin de la Funcion 

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
						<td colspan="5">&nbsp;</td>
						<td valign="baseline" colspan="2">
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
						<td class="nombres_columnas" align="center">CLAVE BITACORA</td>
                        <td class="nombres_columnas" align="center">&Aacute;REA</td>
						<td class="nombres_columnas" align="center">CLAVE EQUIPO</td>
                        <td class="nombres_columnas" align="center">TIPO MTTO</td>
                        <td class="nombres_columnas" align="center">FECHA MTTO</td>
                        <td class="nombres_columnas" align="center">TURNO</td>						
                        <td class="nombres_columnas" align="center">COMENTARIOS</td>				
						<td class="nombres_columnas" align="center">TIEMPO MTTO</td>						
						<td class="nombres_columnas" align="center">COSTO MTTO</td>														
      				</tr>
			<?php
			$nom_clase = "renglon_gris";
			$cont = 1;
			$cant_total = 0;
			do{	?>			
					<tr>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['id_bitacora']; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['area']; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['id_equipo']; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['tipo_mtto']; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['fecha_mtto']; ?></td>						
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['turno']; ?></td>												
						<td align="left" class="<?php echo $nom_clase; ?>"><?php echo $datos['comentarios']; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['tiempo_total']; ?> Hrs.</td>						
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
				<tr><td colspan="8">&nbsp;</td><td class="nombres_columnas" align="center">$ <?php echo number_format($cant_total,2,".",","); ?></td></tr>
			</table>
			</div>
			</body>			
	<?php	}
		
	//Cerrar la conexion con la BD		
		mysql_close($conn);		
	}//Fin de la Funcion guardarRepPreventivos($hdn_consulta,$hdn_nomReporte)		
		
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
					#tabla { position:absolute; left:11px; top:-1px; width:1111px; height:175px; z-index:5; }
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
