<?php 
	/**
	  * Nombre del Módulo: Gerencia Técnica                                               
	  * Nombre Programador: Miguel Angel Garay Castro                          
	  * Fecha: 17/07/2011                                      			
	  * Descripción: Este archivo contiene funciones para almacenar la información en una hoja de calculo de excel de las consultas realizadas y reportes generados como lo son:
	  * 			 1. Consultas realizadas al Catalogo de Almacen
	  *				 5. Consultas del Material que ha salido del Almacen
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
	
	if(isset($_POST['hdn_consulta']) || isset($_POST['hdn_msg'])){
				
		//Ubicacion de las imagenes que estan contenidas en los encabezados
		define("HOST", $_SERVER['HTTP_HOST']);
		//Obtener el nombre del Nombre de la Carpeta Raíz donde se encontrará almacenado el SISAD
		$raiz = explode("/",$_SERVER['PHP_SELF']);
		define("SISAD",$raiz[1]);

		
		switch($hdn_tipoReporte){
			case "reporteCuadrillas":
				guardarRepCuadrilla($hdn_periodo,$hdn_nomReporte);
			break;
			case "consulta":
				guardarRepConsulta($hdn_consulta,$hdn_nomReporte,$hdn_msg);
			break;	
			case "reporteAsistencia":
				guardarRepAsistencia($hdn_consulta,$hdn_nomReporte,$hdn_msg,$hdn_fechaIni, $hdn_fechaFin, $hdn_diferencia);
			break;	
			case "reporteComparativoMina":
				guardarRepComparativoMina($hdn_destino,$hdn_anio,$hdn_msg,$hdn_nomGrafica);
			break;	
			case "reporteAnual":
				guardarRepAnual($hdn_msg,$hdn_anio,$hdn_nomGrafica);
			break;	
			case "reporteRendimiento":
				guardarRepRendimiento($hdn_nomReporte, $hdn_consulta, $hdn_idRegRendimiento);
			break;
			case "reporteAgregado":
				guardarRepAgregados($hdn_tituloTabla, $hdn_nomReporte, $hdn_PBM);				
			break;
			case "salidasDetalle":
				guardarRepSalidaDetalle($hdn_fechaI,$hdn_fechaF,$hdn_orden);
			break;
			case "reporteKardexAsistencia":
				exportarKardexAsistencia($hdn_fechaI,$hdn_fechaF,$hdn_dias,$hdn_consulta);
			break;
			case "reporte_requisiciones":
				guardarRepRequisiciones($hdn_consulta,$hdn_nomReporte,$hdn_msg);
			break;
			case "reporte_detallerequisiciones":
				guardarRepDetalleReq($hdn_consulta,$hdn_nomReporte,$hdn_msg);
			break;
		}										
	}
	
	if(isset($_GET['tipoRep'])){
		//Ubicacion de las imagenes que estan contenidas en los encabezados
		define("HOST", $_SERVER['HTTP_HOST']);
		//Obtener el nombre del Nombre de la Carpeta Raíz donde se encontrará almacenado el SISAD
		$raiz = explode("/",$_SERVER['PHP_SELF']);
		define("SISAD",$raiz[1]);
		switch($_GET["tipoRep"]){
			case "RepCompMes":
				guardarReporteComparativoMensual();
			break;
		}
	}
	
	function guardarRepSalidaDetalle($fechaI,$fechaC,$orden){
		//Esribir datos de la pagina en excel
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=ReporteDetalleSalidas.xls");
		
		$cadena =  obtenerCentroCostos('ZARPEO');
		
		//Realizar la conexion a la BD de Almacen
		$conn = conecta("bd_almacen");
		
		//Convertir Formato Fecha Sistema a formato Fecha Base de Datos para consultas
		$f1 = modFecha($fechaI,3);
		$f2 = modFecha($fechaC,3);
		
		$filtro_centro_costo = $_POST["hdn_cc"];
		$filtro_cuentas = $_POST["hdn_cuenta"];
		$filtro_subcuentas = $_POST["hdn_subcuenta"];
		
		//Crear la sentencia para mostrar el Reporte de Orden de Compra dadas las fechas escritas
		$stm_sql = "SELECT id_salida,fecha_salida,solicitante,destino,depto_solicitante,turno,no_vale,cuentas,subcuentas,moneda FROM salidas WHERE fecha_salida BETWEEN '$f1' AND '$f2'";
		
		if($filtro_centro_costo!="")
			$stm_sql.= " AND destino = '$filtro_centro_costo'";
		else
			$stm_sql.= $cadena;
		
		if($filtro_cuentas!="")
			$stm_sql.= " AND cuentas = '$filtro_cuentas'";
		
		if($filtro_subcuentas!="")
			$stm_sql.= " AND subcuentas = '$filtro_subcuentas'";
				
		//Si se selecciono un criterio de ordenación, adjuntarlo a la sentencia SQL
		if($orden!="")
			$stm_sql.= " ORDER BY $orden,fecha_salida";
		else
			$stm_sql.= " ORDER BY fecha_salida";
		
		//Ejecutar la sentencia y almacena los datos de la consulta en la variable $rs (result set)
		$rs = mysql_query($stm_sql);
			
		if($row=mysql_fetch_array($rs)){
			//Definir el estilo de la tabla y el encabezado que aparecera sobre la tabla?>
			<head>
				<style>					
					<!--
					body { font-family:Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; }
					/*En este formato  se establece el tamaño,tipo de letra,color de fondo que ocupa la columna princiaplal de una tebla*/
					.nombres_columnas { font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #FFFFFF; background-color: #9BBA59; font-weight: bold; border-top-width: medium; border-right-width: thin;
										border-bottom-width: medium; border-left-width: thin; border-top-style: solid; border-right-style: none; border-bottom-style: solid; border-left-style: none; 
										border-top-color: #000000; border-bottom-color: #000000;vertical-align:middle;text-align:center;}
					/*En este formato  se establece el tamaño,tipo de letra,color de fondo que se ocupa en la fila principal para resaltar datos  de una tebla*/
					.nombres_filas { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #9BBB59;vertical-align:middle;text-align:center;}
					/*En este formato  se establece el tamaño,tipo de letra,color de fondo que contendra datos  de una tebla*/
					.renglon_gris { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #E7E7E7;vertical-align:middle;text-align:center;}
					/*En este formato  se establece el tamaño,tipo de letra,color de fondo que contendra datos  de una tebla*/
					.renglon_blanco { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #FFFFFF;vertical-align:middle;text-align:center;}
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
						<td align="left" valign="baseline" colspan="2"><img src="http://<?php echo HOST; ?>/<?php echo SISAD; ?>/images/logo.png" width="118" height="58" align="absbottom" /></td>
						<td colspan="16">&nbsp;</td>
						<td valign="baseline" colspan="2">
							<div align="right"><span class="texto_encabezado">
								<strong>MANUAL  DE PROCEDIMIENTOS DE LA CALIDAD</strong><br><em>CONCRETO  LANZADO DE FRESNILLO, S.A DE C.V.</em>
							</span></div>
						</td>
					</tr>											
					<tr>
						<td colspan="20" align="center" class="borde_linea">
							<span class="sub_encabezado">CONFIDENCIAL, PROPIEDAD DE &ldquo;CONCRETO LANZADO DE FRESNILLO, S.A DE C.V.&rdquo; PROHIBIDA SU REPRODUCCI&Oacute;N TOTAL O PARCIAL</span>
						</td>
					</tr>					
					<tr>
						<td colspan="20">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="20">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="20" align="center" class="titulo_tabla"><?php echo "Reporte Detallado de Salidas generadas del <strong><u>$fechaI</u></strong> al <strong><u>$fechaC</u></strong>" ?></td>
					</tr>
					<tr>
						<td colspan="20">&nbsp;</td>
					</tr>			
					<tr>						
						<td class='nombres_columnas'>PEDIDO</td>
						<td class='nombres_columnas'>FECHA PEDIDO</td>		
						<td class='nombres_columnas'>DEPARTAMENTO</td>
						<td class='nombres_columnas'>CENTRO COSTOS</td>
						<td class='nombres_columnas'>CUENTA</td>
						<td class='nombres_columnas'>SUBCUENTA</td>
						<td class='nombres_columnas'>CLAVE MATERIAL</td>
						<td class='nombres_columnas'>NOMBRE MATERIAL</td>
						<td class='nombres_columnas'>UNIDAD MEDIDA</td>
						<td class='nombres_columnas'>EXISTENCIA EN STOCK</td>
						<td class='nombres_columnas'>CLAVE ENTRADA</td>
						<td class='nombres_columnas'>FECHA ENTRADA</td>
						<td class='nombres_columnas'>CANTIDAD ENTRADA</td>
						<th class='nombres_columnas'>CLAVE SALIDA</th>
						<th class='nombres_columnas'>FECHA SALIDA</th>
						<th class='nombres_columnas'>DEPARTAMENTO SOLICITANTE</th>
						<th class='nombres_columnas'>SOLICITANTE</th>
						<th class='nombres_columnas'>DESTINO</th>
						<th class='nombres_columnas'>TURNO</th>
						<td class='nombres_columnas'>NO. VALE</td>
						<td class='nombres_columnas'>EQUIPO DESTINO</td>
						<td class='nombres_columnas'>CANTIDAD SALIDA</td>
						<td class='nombres_columnas'>COSTO UNITARIO</td>
						<td class='nombres_columnas'>COSTO TOTAL</td>
						<td class='nombres_columnas'>TIPO MONEDA</td>
      				</tr>
			<?php
			//Mostramos los registros
			$nom_clase = "renglon_gris";
			$cont = 1;
			
			/*Variable para realizar la operacion y obtener el total de costo de los materiales a los cuales se le registro una salida*/
			$cant_total_pesos = 0;
			$cant_total_dolares = 0;
			$cant_total_euros = 0;
			$cant_total = 0;
			do{
				$id_salida=$row["id_salida"];
				mysql_close($conn);
				
				$centro_costos = obtenerDatosCentroCostos($row["destino"],"control_costos","id_control_costos");
				$cuenta = obtenerDatosCentroCostos($row["cuentas"],"cuentas","id_cuentas");
				$subcuenta = obtenerDatosCentroCostos($row["subcuentas"],"subcuentas","id_subcuentas");
				
				$conn = conecta('bd_almacen');
				$sql_detalle="SELECT materiales_id_material,nom_material,unidad_material,cant_salida,costo_unidad,costo_total,id_equipo_destino,moneda FROM detalle_salidas WHERE salidas_id_salida='$id_salida'";
				/*$sql_detalle = "SELECT DISTINCT T1.materiales_id_material, T1.nom_material, T1.unidad_material, T1.cant_salida, T1.costo_unidad, T1.costo_total, T1.id_equipo_destino, T2.destino
								FROM detalle_salidas AS T1
								JOIN detalle_es AS T2 ON T1.`salidas_id_salida` = T2.`no_vale` 
								WHERE salidas_id_salida =  '$id_salida'";*/
				$rs_detalle=mysql_query($sql_detalle);
				$reg=mysql_num_rows($rs_detalle);
				if($detalle=mysql_fetch_array($rs_detalle)){
					$ctrl=0;
					do{
						$idEntrada=obtenerDatosEntrada($detalle["materiales_id_material"]);
						$fechaEntrada="<label title='No Aplica al No Haber una Entrada Relacionada'>NA</label>";
						$cantidadEntrada="<label title='No Aplica al No Haber una Entrada Relacionada'>NA</label>";
						$existencia=obtenerDato("bd_almacen", "materiales", "existencia","id_material",$detalle["materiales_id_material"]);
						$req="ND";
						$fechaPedido="ND";
						
						if($idEntrada!="ND"){
							$fechaEntrada=obtenerDato("bd_almacen", "entradas", "fecha_entrada","id_entrada",$idEntrada);
							$fechaEntrada=modFecha($fechaEntrada,1);
							$cantidadEntrada=obtenerDatoBicondicional("bd_almacen","detalle_entradas","cant_entrada","entradas_id_entrada",$idEntrada,"materiales_id_material",$detalle["materiales_id_material"]);
							//Obtener el ID de la Requisicion o del Pedido
							$req=obtenerDato("bd_almacen", "entradas", "requisiciones_id_requisicion","id_entrada",$idEntrada);
							if ($req!=""){
								if (substr($req,0,3)=="PED")
									$fechaPedido=obtenerDato("bd_compras", "pedido", "fecha","id_pedido",$req);
								else{
									$fechaPedido=obtenerDato("bd_compras", "pedido", "fecha","requisiciones_id_requisicion",$req);
									if ($fechaPedido!="")
										$fechaPedido=modFecha($fechaPedido,1);
									$req=obtenerDato("bd_compras", "pedido", "id_pedido","requisiciones_id_requisicion",$req);
								}
								if ($req=="")
									$req="ND";
								if ($fechaPedido=="")
									$fechaPedido="ND";
								//Reabrir la conexion a la BD de almacen
								$conn=conecta("bd_almacen");
							}
							else
								$req="ND";
						}
						echo "
							<tr>
							<td class='$nom_clase' title='Clave del Pedido'>$req</td>
							<td class='$nom_clase' title='Fecha del Pedido'>$fechaPedido</td>
							
							<td class='$nom_clase' title='Departamento'>$row[depto_solicitante]</td>
							<td class='$nom_clase' title='Centro de Costos'>$centro_costos</td>
							<td class='$nom_clase' title='Cuenta'>$cuenta</td>
							<td class='$nom_clase' title='SubCuenta'>$subcuenta</td>";
							
						if($detalle["materiales_id_material"]=="¬NOVALE")
							echo "<td class='$nom_clase'>NO APLICA</td>";
						else
							echo "<td class='$nom_clase'>$detalle[materiales_id_material]</td>";
							
						echo "
							<td class='$nom_clase' title='Nombre del Material'>$detalle[nom_material]</td>
							<td class='$nom_clase' title='Unidad de Medida del Material'>$detalle[unidad_material]</td>
							<td class='$nom_clase' title='Existencia en Stock del Material'>$existencia</td>
							<td class='$nom_clase' title='Clave de la &Uacute;ltima Entrada de $detalle[nom_material]'>$idEntrada</td>
							<td class='$nom_clase' title='Fecha de la &Uacute;ltima Entrada de $detalle[nom_material]'>$fechaEntrada</td>
							<td class='$nom_clase' title='Cantidad Entrada de $detalle[nom_material]'>$cantidadEntrada</td>";
							
						echo "<td class='$nom_clase' title='N&uacute;mero de Salida'>$row[id_salida]</td>
								<td class='$nom_clase' title='Fecha de Salida'>".modFecha($row['fecha_salida'],1)."</td>
								<td class='$nom_clase' title='Departamento Solicitor'>$row[depto_solicitante]</td>
								<td class='$nom_clase' reg' title='Solicitante'>$row[solicitante]</td>";
								if($centro_costos != "N/A"){
									echo "<td class='$nom_clase' title='Destino del Material'>$centro_costos</td>";
								} else {
									echo "<td class='$nom_clase' title='Destino del Material'>$row[destino]</td>";
								}
						echo "<td class='$nom_clase' title='Turno en el que el Material Sali&oacute;'>$row[turno]</td>
								<td class='$nom_clase' title='N&uacute;mero de Vale'>$row[no_vale]</td>";
						
						echo "	
							<td class='$nom_clase' title='Equipo al Que Va Destinado el Material'>$detalle[id_equipo_destino]</td>
							<td class='$nom_clase' title='Cantidad de Salida del Material'>$detalle[cant_salida]</td>
							<td class='$nom_clase' title='Costo Unitario del Material'>$".number_format($detalle["costo_unidad"],2,".",",")."</td>
							<td class='$nom_clase' title='Costo Total del Material'>$".number_format($detalle["costo_total"],2,".",",")."</td>
							<td class='$nom_clase' title='Tipo Moneda'>$detalle[moneda]</td>
							</tr>
						";
						$ctrl++;
						//Operación que mostrara el total del costo de los materiales
						if($detalle["moneda"] == "PESOS")
							$cant_total_pesos += $detalle['costo_total'];
						else if($detalle["moneda"] == "DOLARES")
							$cant_total_dolares += $detalle['costo_total'];
						else if($detalle["moneda"] == "EUROS")
							$cant_total_euros += $detalle['costo_total'];
						$cant_total += $detalle['costo_total'];
						
						$cont++;
						if($cont%2==0)
							$nom_clase = "renglon_blanco";
						else
							$nom_clase = "renglon_gris";				
					}while($detalle=mysql_fetch_array($rs_detalle));
				}	
									
				//Determinar el color del siguiente renglon a dibujar
				/*$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";		*/				
			}while($row = mysql_fetch_array($rs));

			//Colocar el Check para cuando se selecciona VER TODO
			echo "
				<tr>
					<td colspan='22'>&nbsp;</td>
					<td class='nombres_columnas' rowspan='3'>TOTAL</td>
					<td class='nombres_columnas'>$".number_format($cant_total_pesos,2,".",",")."</td>
					<td class='nombres_columnas'>PESOS</td>
				</tr>
				<tr>
					<td colspan='22'>&nbsp;</td>
					<td class='nombres_columnas'>$".number_format($cant_total_dolares,2,".",",")."</td>
					<td class='nombres_columnas'>DOLARES</td>
				</tr>
				<tr>
					<td colspan='22'>&nbsp;</td>
					<td class='nombres_columnas'>$".number_format($cant_total_euros,2,".",",")."</td>
					<td class='nombres_columnas'>EUROS</td>
				</tr>
				<tr>
					<td colspan='23'>&nbsp;</td>
					<td class='nombres_columnas'>$".number_format($cant_total,2,".",",")."</td>
				</tr>";
			?>
			</table>
			</div>
			</body>
<?php	}
		
		//Cerrar la conexion con la BD		
		mysql_close($conn);		
	}
		
	/*Esta funcion exportara los datos del Reporte de Cuadrillas*/
	function guardarRepCuadrilla($hdn_periodo,$hdn_nomReporte){
		//Esribir datos de la pagina en excel
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=$hdn_nomReporte.xls");
		
		//Definir el estilo de la tabla y el encabezado que aparecera sobre la tabla?>
		<head>
			<style>					
				<!--
				body { font-family:Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; }
				/*En este formato  se establece el tamaño,tipo de letra,color de fondo que ocupa la columna princiaplal de una tebla*/
				.nombres_columnas { font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #FFFFFF; background-color: #9BBA59; font-weight: bold; border-top-width: medium; border-right-width: thin;
									border-bottom-width: medium; border-left-width: thin; border-top-style: solid; border-right-style: none; border-bottom-style: solid; border-left-style: none; 
									border-top-color: #000000; border-bottom-color: #000000; }
				/*En este formato  se establece el tamaño,tipo de letra,color de fondo que se ocupa en la fila principal para resaltar datos  de una tebla*/
				.nombres_filas { font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; background-color: #9BBB59; }
				/*En este formato  se establece el tamaño,tipo de letra,color de fondo que contendra datos  de una tebla*/
				.renglon_gris { font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; background-color: #E7E7E7; }
				/*En este formato  se establece el tamaño,tipo de letra,color de fondo que contendra datos  de una tebla*/
				.renglon_blanco { font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; background-color: #FFFFFF; } 
				#tabla { position:absolute; left:0px; top:0px; width:1111px; height:175px; z-index:5; }
				.texto_encabezado {font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #0000CC; }
				.sub_encabezado {font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #0000CC; }
				.borde_linea { border-top:3px; border-top-color:#4E6128; border-top-style:solid;}
				.caracter{color:#9BBA59;}
				.msje_correcto{ font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #0066FF; font-weight:bold; }
				#ver-reporteMensual {position:absolute; left:30px; top:190px; width:940px; height:450px; z-index:15; }
				-->
			</style>
		</head>											
		<body>
			<div id="ver-reporteMensual" class="borde_seccion2" align="center"><?php
			
				include_once("op_reporteCuadrillas.php");?>
				
				<table width="250%">					
					<tr>
						<td align="left" valign="baseline" colspan="3"><img src="http://<?php echo HOST; ?>/<?php echo SISAD; ?>/images/logo.png" width="118" 
                        height="58" align="absbottom" /></td>
						<td colspan="31">&nbsp;</td>
						<td valign="baseline" colspan="6">
							<div align="right"><span class="texto_encabezado">
								<strong>MANUAL  DE PROCEDIMIENTOS DE LA CALIDAD</strong><br><em>CONCRETO  LANZADO DE FRESNILLO, S.A DE C.V.</em>
							</span></div>
						</td>
					</tr>											
					<tr>
						<td colspan="40" align="center" class="borde_linea">
							<span class="sub_encabezado">CONFIDENCIAL, PROPIEDAD DE &ldquo;CONCRETO LANZADO DE FRESNILLO, S.A DE C.V.&rdquo; PROHIBIDA SU REPRODUCCI&Oacute;N TOTAL O PARCIAL</span>
						</td>
					</tr>					
					<tr>
						<td colspan="39">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="39">&nbsp;</td>
					</tr>
				</table><?php
				
				//Dibujar la Tabla con el Detalle del Zarpeo por Cuadrilla								
				$sumaPorDiaZarpeo = verReporteMensual($hdn_periodo);				
												
				//Dibujar la grafica del Reporte Mensual de Produccion con la información proporcionada de cada Ubicacion
				foreach($_SESSION['ubicacionesGrafica'] as $ubicacion => $datosUbicacion){
					//Recuperar los datos necesarios para generar la Grafica
					$msgGrafica = $datosUbicacion['msgGraficaRptCuadrillas'];
					$presupuesto = $datosUbicacion['presupuesto'];
					$avance = $datosUbicacion['avanceReal'];
			
					$grafica = dibujarGrafica($msgGrafica,$presupuesto,$avance); ?>								
					<table width="250%">						
						<tr>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>
								<div align="center">
									<img src="http://<?php echo HOST; ?>/<?php echo SISAD; ?>/pages/ger/<?php echo $grafica;?>" width="940" height="450" />
								</div>							
							</td>
						</tr>
					</table>
					<br><br><br><br><br><br><br><br><br><?php
				}?>																					
			</div>
		</body><?php
	}
	
	//Esta funcion exporta a Excel el resultado de las CONSULTAS REALIZADAS al Modulo de Almacen		
	function guardarRepConsulta($hdn_consulta,$hdn_nomReporte,$hdn_msg){
		//Esribir datos de la pagina en excel
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=$hdn_nomReporte.xls");
				
		//Realizar la conexion a la BD de Almacen
		$conn = conecta("bd_almacen");
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
				<table width="1650">					
					<tr>
						<td align="left" valign="baseline" colspan="2"><img src="http://<?php echo HOST; ?>/<?php echo SISAD; ?>/images/logo.png" width="118" 
                        height="58" align="absbottom" /></td>
						<td colspan="11">&nbsp;</td>
						<td valign="baseline" colspan="3">
							<div align="right"><span class="texto_encabezado">
								<strong>MANUAL  DE PROCEDIMIENTOS DE LA CALIDAD</strong><br><em>CONCRETO  LANZADO DE FRESNILLO, S.A DE C.V.</em>
							</span></div>
						</td>
					</tr>											
					<tr>
						<td colspan="16" align="center" class="borde_linea">
							<span class="sub_encabezado">CONFIDENCIAL, PROPIEDAD DE &ldquo;CONCRETO LANZADO DE FRESNILLO, S.A DE C.V.&rdquo; PROHIBIDA SU REPRODUCCI&Oacute;N TOTAL O PARCIAL</span>
						</td>
					</tr>					
					<tr>
						<td colspan="16">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="16">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="16" align="center" class="titulo_tabla"><?php echo $hdn_msg; ?></td>
					</tr>
					<tr>
						<td colspan="16">&nbsp;</td>
					</tr>			
					<tr>
						<td align="center" class="nombres_columnas">CLAVE</td>
        				<td align="center" class="nombres_columnas">NOMBRE (DESCRIPCION)</td>
				        <td align="center" class="nombres_columnas">UNIDAD DE MEDIDA</td>
        				<td align="center" class="nombres_columnas">LINEA DEL ARTICULO (CATEGORIA)</td>
						<th align="center" class="nombres_columnas">GRUPO</th>
						<td align="center" class="nombres_columnas">COSTO UNITARIO</td>
        				<td align="center" class="nombres_columnas">EXISTENCIA</td>
        				<td align="center" class="nombres_columnas">NIVEL MINIMO </td>
        				<td align="center" class="nombres_columnas">NIVEL M&Aacute;XIMO </td>
        				<td align="center" class="nombres_columnas">PUNTO DE REORDEN </td>											
        				<td align="center" class="nombres_columnas">PROVEEDOR</td>
        				<td align="center" class="nombres_columnas">UBICACI&Oacute;N</td>
        				<td align="center" class="nombres_columnas">FECHA DE ALTA </td>	
        				<td align="center" class="nombres_columnas">FACTOR DE CONVERSI&Oacute;N </td>
        				<td align="center" class="nombres_columnas">UNIDAD DE DESPACHO </td>																	
        				<td align="center" class="nombres_columnas">COMENTARIOS</td>
      				</tr>
			<?php
			$nom_clase = "renglon_gris";
			$cont = 1;
			do{	?>			
					<tr>
						<td align="center" class="nombres_filas"><?php echo $datos['id_material']; ?></td>
						<td align="left" class="<?php echo $nom_clase; ?>"><?php echo $datos['nom_material']; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['unidad_medida']; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['linea_articulo']; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['grupo']; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo "$ ".number_format($datos['costo_unidad'],2,".",","); ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['existencia']; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['nivel_minimo']; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['nivel_maximo']; ?></td>	
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['re_orden']; ?></td>											
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['proveedor']; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['ubicacion']; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['fecha_alta']; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['factor_conv']; ?></td>	
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['unidad_despacho']; ?></td>																	
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['comentarios']; ?></td>
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
	}//Fin de la Funcion guardarRepConsulta($hdn_consulta,$hdn_nomReporte,$hdn_mensaje)
		
		
	//Esta funcion exporte el REPORTE ASISTENCIA a un archivo de excel
	function guardarRepAsistencia($hdn_consulta,$hdn_nomReporte,$hdn_msg ,$hdn_fechaIni, $hdn_fechaFin, $hdn_diferencia){
		//Esribir datos de la pagina en excel
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=$hdn_nomReporte.xls");
			
		//Realizar la conexion a la BD 
		$conn = conecta("bd_recursos");
		
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
						<td align="left" valign="baseline" colspan="2"><img src="http://<?php echo HOST; ?>/<?php echo SISAD; ?>/images/logo.png" width="118" 
                        height="58" 
                        align="absbottom" /></td>
						<td colspan="3">&nbsp;</td>
						<td valign="baseline" colspan="1">
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
						<td colspan="7">&nbsp;</td>
					</tr>			
					<tr>
						<td align='center' class='nombres_columnas'>NO.</td>
						<td align='center' class='nombres_columnas'>RFC</td>
						<td align='center' class='nombres_columnas'>NOMBRE</td>
						<td align='center' class='nombres_columnas'>TOTAL ASISTENCIAS</td>
						<td align='center' class='nombres_columnas'>ASISTENCIAS A CUMPLIR</td>
						<td align='center' class='nombres_columnas'>PUESTO</td>																																											
      				</tr><?php
				$nom_clase = "renglon_gris";
				$cont = 1;
				do{	
					//Generamos la consulta que cuenta el total de asistencias del empleado segun rfc
					$stm_sql2 = "SELECT COUNT(estado) AS total_asistencias FROM kardex WHERE empleados_rfc_empleado = '$datos[empleados_rfc_empleado]' 
									AND estado='A' AND fecha_entrada>='$hdn_fechaIni' AND fecha_entrada<='$hdn_fechaFin' ";
			
					//Ejecutamos la sentencia
					$rs_datos2 = mysql_query($stm_sql2);
					//Guardamos los resultados de la sentencia en el arreglo
					$arrConsulta2 = mysql_fetch_array($rs_datos2);?>
				<tr>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $cont; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['empleados_rfc_empleado']; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['nombre']; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $arrConsulta2['total_asistencias']; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $hdn_diferencia; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['puesto']; ?></td>
				</tr><?php
				
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
	
	
	
	//Esta funcion exporta a Excel el resultado de las CONSULTAS REALIZADAS al Modulo de Almacen		
	function guardarReporteComparativoMensual(){
		//Esribir datos de la pagina en excel
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=ReporteComparativoMensual.xls");
				
		//Realizar la conexion a la BD de Almacen
		$conn = conecta("bd_gerencia");

		//Recuperar los datos del GET
		$periodo=$_GET['periodo'];
		$ubicacion=$_GET['ubicacion'];
		$numEmp=$_GET["numEmp"];
		
		//Definir el estilo de la tabla y el encabezado que aparecera sobre la tabla?>
		<head>
			<style>					
				<!--
				body { font-family:Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; }
				/*En este formato  se establece el tamaño,tipo de letra,color de fondo que ocupa la columna princiaplal de una tabla*/
				.nombres_columnas { font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #FFFFFF; background-color: #9BBA59; font-weight: bold; border-top-width: medium; border-right-width: thin;
									border-bottom-width: medium; border-left-width: thin; border-top-style: solid; border-right-style: none; border-bottom-style: solid; border-left-style: none; 
									border-top-color: #000000; border-bottom-color: #000000; vertical-align:middle;}
				/*En este formato  se establece el tamaño,tipo de letra,color de fondo que se ocupa en la fila principal para resaltar datos  de una tabla*/
				.nombres_filas { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #9BBB59;  vertical-align:middle;}
				/*En este formato  se establece el tamaño,tipo de letra,color de fondo que contendra datos  de una tabla*/
				.renglon_gris { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #E7E7E7;  vertical-align:middle;}
				/*En este formato  se establece el tamaño,tipo de letra,color de fondo que contendra datos  de una tabla*/
				.renglon_blanco { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #FFFFFF;  vertical-align:middle;} 
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
			<table>					
				<tr>
					<td align="left" valign="baseline" colspan="2"><img src="http://<?php echo HOST; ?>/<?php echo SISAD; ?>/images/logo.png" width="118" 
					height="58" align="absbottom" /></td>
<!--					<td colspan="4">&nbsp;</td>		-->
					<td>&nbsp;</td>
					<td valign="baseline" colspan="3">
						<div align="right"><span class="texto_encabezado">
							<strong>MANUAL  DE PROCEDIMIENTOS DE LA CALIDAD</strong><br><em>CONCRETO  LANZADO DE FRESNILLO, S.A DE C.V.</em>
						</span></div>
					</td>
					
				</tr>											
				<tr>
					<td colspan="6" align="center" class="borde_linea">
						<span class="sub_encabezado">CONFIDENCIAL, PROPIEDAD DE &ldquo;CONCRETO LANZADO DE FRESNILLO, S.A DE C.V.&rdquo; PROHIBIDA SU REPRODUCCI&Oacute;N TOTAL O PARCIAL</span>
					</td>
				</tr>					
				<tr>
					<td colspan="6">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="6">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="6">&nbsp;</td>
				</tr>			
		<?php
		$anio=substr($periodo,0,4);
		$mesIniPer=substr($periodo,5,3);
		$mesFinPer=substr($periodo,-3);
		if($mesFinPer=="ENE")
			$anio-=1;
		//Obtenemos la fecha inicial y final de la BD para tomarla como parametro al realizar las operaciones para realizar los calculos
		$fechaIniMesActual=obtenerDatoBicondicional("bd_gerencia", "presupuesto", "fecha_inicio", "periodo", $periodo, "catalogo_ubicaciones_id_ubicacion", $ubicacion);
		$fechaFinMesActual=obtenerDatoBicondicional("bd_gerencia", "presupuesto", "fecha_fin", "periodo", $periodo, "catalogo_ubicaciones_id_ubicacion", $ubicacion);
		//Obtener el nombre de la ubicacion para colocarlo en el titulo de la tabla
		$nomUbicacion = obtenerDato("bd_gerencia","catalogo_ubicaciones","ubicacion","id_ubicacion",$ubicacion);
		//Conectar a la BD de gerencia
		$conn = conecta("bd_gerencia");
		//Ejecutar la sentencia que extrae las fechas de inicio y fin del periodo anterior al actual
		$rsPeriodo=mysql_query("SELECT SUBSTRING(periodo,6,3) AS mes,fecha_inicio,fecha_fin,periodo FROM presupuesto WHERE catalogo_ubicaciones_id_ubicacion='$ubicacion' 
							AND periodo LIKE '$anio%$mesIniPer' ORDER BY fecha_inicio");
		//Extraer los Datos del periodo anterior cuando este existe
		if($datosPeriodo=mysql_fetch_array($rsPeriodo)){
			$fechaIniMesAnterior=$datosPeriodo["fecha_inicio"];
			$fechaFinMesAnterior=$datosPeriodo["fecha_fin"];
			$mesAnterior=$datosPeriodo["mes"];
			$periodo2=$datosPeriodo["periodo"];
		}
		//Si el periodo no existe, obtener el mes que le corresponde y las Fechas quedan como cadenas vacias
		else{
			$fechaIniMesAnterior="";
			$fechaFinMesAnterior="";
			$secCombo=split("-",$periodo);
			$mesAnterior=obtenerNombreCompletoMes($secCombo[1]);
			$mesAnterior=substr(obtenerMesAnterior($mesAnterior),0,3);
			$periodo2=$anio."-".$mesAnterior."-".$mesIniPer;
		}
		//Crear el periodo 3 y obtener las fechas de la BD, si no existe el periodo, no se toman los registros asociados a el
		//Ejecutar la sentencia que extrae las fechas de inicio y fin del periodo anterior de inicio
		$rsPeriodo2=mysql_query("SELECT SUBSTRING(periodo,6,3) AS mes,fecha_inicio,fecha_fin,periodo FROM presupuesto WHERE catalogo_ubicaciones_id_ubicacion='$ubicacion' 
							AND periodo LIKE '$anio%$mesAnterior' ORDER BY fecha_inicio");
		//Extraer los Datos del periodo anterior cuando este existe
		if($datosPeriodo2=mysql_fetch_array($rsPeriodo2)){
			$fechaIniMesAnterior3=$datosPeriodo2["fecha_inicio"];
			$fechaFinMesAnterior3=$datosPeriodo2["fecha_fin"];
			$mesAnterior3=$datosPeriodo2["mes"];
			$periodo3=$datosPeriodo2["periodo"];
		}
		//Si el periodo no existe, obtener el mes que le corresponde y las Fechas quedan como cadenas vacias
		else{
			$fechaIniMesAnterior3="";
			$fechaFinMesAnterior3="";
			//Obtener el nombre completo del Mes
			$mesAnterior3=obtenerNombreCompletoMes($mesAnterior);
			//Obtener el Mes Anterior
			$mesAnterior3=substr(obtenerMesAnterior($mesAnterior3),0,3);
			//Obtener el Periodo
			$periodo3=$anio."-".$mesAnterior3."-".$mesAnterior;
		}
		//Crear el mensaje que se mostrara en el titulo de la tabla
		$msg_titulo= "Comparativo Mensual <em><u>$nomUbicacion</em></u> en el Periodo <em><u>$periodo</u></em>";
		//Crear el Mensaje en caso de que la consulta no arroje ningún resultado
		$msg_error = "<label class='msje_correcto' align='center'>No se Encontr&oacute; Ning&uacute;n Presupuesto Registrado del Periodo<u><em> $periodo</u></em></label>";		
		//Desplegar los encabezados de la Tabla
		echo "
			<tr>
				<td colspan='6' align='center' class='titulo_etiqueta'>$msg_titulo</td>
			</tr>
			<tr>
				<td rowspan='2' class='nombres_columnas' align='center'>CONCEPTO</td>
				<td rowspan='2' class='nombres_columnas' align='center'>UNIDAD</td>
				<td colspan='3' class='nombres_columnas' align='center'>MES</td>
				<td rowspan='2' class='nombres_columnas' align='center'>PROMEDIO</td>
			</tr>
			<tr>
				<td class='nombres_columnas' align='center'>$mesAnterior</td>
				<td class='nombres_columnas' align='center'>$mesIniPer</td>
				<td class='nombres_columnas' align='center'>$mesFinPer</td>
			</tr>";
		//Extraer los conceptos de la bitacora que se tengan registrados para cualquiera de los 3 periodos a buscar
		$rsConceptos=mysql_query("SELECT DISTINCT aplicacion FROM bitacora_zarpeo WHERE destino='$nomUbicacion' AND ((fecha BETWEEN '$fechaIniMesAnterior3' AND '$fechaFinMesAnterior3') OR (fecha BETWEEN '$fechaIniMesAnterior' AND '$fechaFinMesAnterior') OR (fecha BETWEEN '$fechaIniMesActual' AND '$fechaFinMesActual')) ORDER BY fecha");		
		//Verificar la consulta y extraer los datos
		if($conceptos=mysql_fetch_array($rsConceptos)){
			//Variables que acumulan la Produccion por Mes NO APLICA PARA INSTALACION DE MALLAS
			$totalMes3=0;//Mes Actual
			$totalMes2=0;//Mes Anterior
			$totalMes1=0;//Mes Primero(2 Meses Anterior al Actual)
			$totalPromedio=0;
			//Nombre de la Clase
			$nom_clase = "renglon_gris";
			//contador
			$cont = 1;
			do{
				//Produccion Mes Actual
				$prodMes3=0;
				//Produccion Mes Anterior
				$prodMes2=0;
				//Produccion del Primer Mes (calculo hecho en base al mes actual y los 2 anteriores)
				$prodMes1=0;
				//Extraer la cantidad de produccion realizada en el concepto indicado en el Primer Mes
				$produccion=mysql_fetch_array(mysql_query("SELECT SUM(cantidad) AS total FROM bitacora_zarpeo WHERE destino='$nomUbicacion' AND fecha BETWEEN '$fechaIniMesAnterior3' AND '$fechaFinMesAnterior3' AND aplicacion='$conceptos[aplicacion]'"));
				if($produccion["total"]!=NULL)
					$prodMes1=$produccion["total"];
				//Extraer la cantidad de produccion realizada en el concepto indicado en el Segundo Mes
				$produccion=mysql_fetch_array(mysql_query("SELECT SUM(cantidad) AS total FROM bitacora_zarpeo WHERE destino='$nomUbicacion' AND fecha BETWEEN '$fechaIniMesAnterior' AND '$fechaFinMesAnterior' AND aplicacion='$conceptos[aplicacion]'"));
				if($produccion["total"]!=NULL)
					$prodMes2=$produccion["total"];
				//Extraer la cantidad de produccion realizada en el concepto indicado en el Tercer Mes
				$produccion=mysql_fetch_array(mysql_query("SELECT SUM(cantidad) AS total FROM bitacora_zarpeo WHERE destino='$nomUbicacion' AND fecha BETWEEN '$fechaIniMesActual' AND '$fechaFinMesActual' AND aplicacion='$conceptos[aplicacion]'"));
				if($produccion["total"]!=NULL)
					$prodMes3=$produccion["total"];
				
				if($conceptos["aplicacion"]=="INSTALACION MALLA")
					$unidadMedida="M&sup2;";
				else{
					$unidadMedida="M&sup3;";
					$totalMes3+=$prodMes3;
					$totalMes2+=$prodMes2;
					$totalMes1+=$prodMes1;
					$totalPromedio+=(($prodMes1+$prodMes2+$prodMes3)/3);
				}
				echo "<tr>";
					echo "<td class='nombres_columnas' align='center'>$conceptos[aplicacion]</td>";
					echo "<td class='$nom_clase' align='center'>$unidadMedida</td>";
					echo "<td class='$nom_clase' align='center'>".number_format($prodMes1,2,".",",")."</td>";
					echo "<td class='$nom_clase' align='center'>".number_format($prodMes2,2,".",",")."</td>";
					echo "<td class='$nom_clase' align='center'>".number_format($prodMes3,2,".",",")."</td>";
					echo "<td class='$nom_clase' align='center'>".number_format((($prodMes1+$prodMes2+$prodMes3)/3),2,".",",")."</td>";
				echo "</tr>";
				
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";	
			}while($conceptos=mysql_fetch_array($rsConceptos));
			echo "<tr>";
				echo "<td class='nombres_columnas' align='center'>Total Mes</td>";
				echo "<td class='nombres_filas' align='center'>M&sup3;</td>";
				echo "<td class='nombres_filas' align='center'>".number_format($totalMes1,2,".",",")."</td>";
				echo "<td class='nombres_filas' align='center'>".number_format($totalMes2,2,".",",")."</td>";
				echo "<td class='nombres_filas' align='center'>".number_format($totalMes3,2,".",",")."</td>";
				echo "<td class='nombres_filas' align='center'>".number_format($totalPromedio,2,".",",")."</td>";				
			echo "</tr>";
			//Calcular y Mostrar la Productividad
			$prod1=$totalMes1/$numEmp/26;
			$prod2=$totalMes2/$numEmp/26;
			$prod3=$totalMes3/$numEmp/26;
			$prodPromedio=$totalPromedio/$numEmp/26;
			echo"
				<tr><td colspan='6'>&nbsp;</td></tr>
				<tr>
					<td class='nombres_columnas' align='center'>PRODUCTIVIDAD</td>
					<td class='nombres_columnas' align='center'>M&sup3;/PERSONA/D&Iacute;A</td>
					<td class='nombres_columnas' align='center'>".number_format($prod1,2,".",",")."</td>
					<td class='nombres_columnas' align='center'>".number_format($prod2,2,".",",")."</td>
					<td class='nombres_columnas' align='center'>".number_format($prod3,2,".",",")."</td>
					<td class='nombres_columnas' align='center'>".number_format($prodPromedio,2,".",",")."</td>
				</tr>
				<tr><td colspan='6'>&nbsp;</td></tr>
				<tr><td colspan='6' class='$nom_clase' align='center'>C&aacute;lculo con $numEmp Trabajadores</td></tr>
				";
		}
		echo "</table>";
		//Cerrar la conexion con la BD		
		mysql_close($conn);		
		?>
		</div>
		</body>
		<?php
	}//Fin de la Funcion guardarRepConsulta($hdn_consulta,$hdn_nomReporte,$hdn_mensaje)	
	
	
	//Esta funcion exporta la infromacion generada en el reporte comparativo de minas a un archivo de excel
	function guardarRepComparativoMina($hdn_destino,$hdn_anio,$hdn_msg,$hdn_nomGrafica){
		//Esribir datos de la pagina en excel
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=ReporteComparativo$hdn_destino.xls");
	
		//Conectar a la BD de gerencia
		$conn = conecta("bd_gerencia");

		//Definir el estilo de la tabla y el encabezado que aparecera sobre la tabla?>
        <head>
            <style>					
                <!--
                body { font-family:Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; }
                /*En este formato  se establece el tamaño,tipo de letra,color de fondo que ocupa la columna princiaplal de una tabla*/
                .nombres_columnas { font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #FFFFFF; background-color: #9BBA59; font-weight: bold; 
				border-top-width: medium; border-right-width: thin; border-bottom-width: medium; border-left-width: thin; border-top-style: solid;
				border-right-style: none; border-bottom-style: solid; border-left-style: none; border-top-color: #000000; border-bottom-color: #000000; }
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
                #grafica { position:absolute; left:0px; top:630px; width:959px; height:375px; z-index:5; }
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
            <table width="100%">					
            	<tr>
                	<td colspan="3" valign="baseline" align="left">
						<img src="http://<?php echo HOST; ?>/<?php echo SISAD; ?>/images/logo.png" width="118" height="58" 
                        align="absbottom" />					
					</td>
                	
                	<td >
						<div align="right" class="sub_encabezado"> 
						<em>CONCRETO  LANZADO DE FRESNILLO, S.A DE C.V</em>
						</div>
					</td>
           	  	</tr>
				<tr>
              		<td colspan="4" class="borde_linea" align="center">
						<span class="sub_encabezado">
							CONFIDENCIAL, PROPIEDAD DE &ldquo;CONCRETO LANZADO DE FRESNILLO, S.A DE C.V.&rdquo; PROHIBIDA SU REPRODUCCI
                 			&Oacute;N TOTAL O PARCIAL
						</span>					
					</td>
              	</tr>
                <tr>
                    <td colspan="4">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="4">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="4" align="center" class="titulo_tabla"><?php echo $hdn_msg; ?></td>
                </tr>
                <tr>
                    <td colspan="5">&nbsp;</td>
                </tr>
			</table><?php			

		$destino = $hdn_destino;
		$anio = $hdn_anio;
		
		//Obtener el ID de la ubicacion
		$idUbicacion=obtenerDato("bd_gerencia","catalogo_ubicaciones","id_ubicacion","ubicacion",$destino);
		//Conectar a la BD de gerencia
		$conn = conecta("bd_gerencia");
		//contador que nos permite controlar el ciclo de los meses
		$cont = 0;
		//arreglos en el cual se almacenaran los tatales de zarpeos como los de pisos
		
		$resZarpeo = array(0=>0,1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0,11=>0);
		$resPisos = array(0=>0,1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0,11=>0);
		$meses = array(0=>"ENERO",1=>"FEBRERO",2=>"MARZO",3=>"ABRIL",4=>"MAYO",5=>"JUNIO",6=>"JULIO",7=>"AGOSTO",8=>"SEPTIEMBRE",9=>"OCTUBRE",10=>"NOVIEMBRE",11=>"DICIEMBRE");
		
		$rsMeses=mysql_query("SELECT SUBSTRING(periodo,-3) AS mes,fecha_inicio,fecha_fin FROM presupuesto WHERE catalogo_ubicaciones_id_ubicacion='$idUbicacion' AND SUBSTRING(periodo,1,4)='$anio' ORDER BY fecha_inicio");
		if($mes=mysql_fetch_array($rsMeses)){
			do{
				//Obtener las fechas de Inicio y de Fin
				$fechaIni=$mes["fecha_inicio"];
				$fechaFin=$mes["fecha_fin"];
				//Obtener el nombre del MES a buscar en la bitacora
				$mesBitacora=$mes["mes"];
				//Obtener la posicion del arreglo de meses que corresponde al Mes encontrado en la consulta
				foreach($meses as $ind =>$value){
					if(substr($value,0,3)==$mesBitacora){
						//Crear la sentencia SQL para obtener el registro del mes correspondiente de zarpeos
						$sql_stmZarp = "SELECT sum(cantidad) AS zarpeoTotal FROM bitacora_zarpeo WHERE fecha BETWEEN '$fechaIni' AND '$fechaFin' AND destino='$destino' AND aplicacion='ZARPEO VIA HUMEDA'";
						//Crear y ejecutar la sentencia SQL para obtener el registro del mes correspondiente de pisos
						$sql_stmPisos = "SELECT sum(cantidad) AS volTotal FROM bitacora_transporte WHERE fecha BETWEEN '$fechaIni' AND '$fechaFin' AND destino = '$destino'";
						//Ejecutar las sentencia de zarpeos
						$rsZarpeo = mysql_query($sql_stmZarp);
						$rsPisos = mysql_query($sql_stmPisos);
						
						//Comprobar si existen datos de Zarpeo
						if($datosZarp=mysql_fetch_array($rsZarpeo)){										
							//verificar si $datos['aplicacion'] esta vacia asignale valor 0
							if ($datosZarp['zarpeoTotal']!=0)
								$resZarpeo[$ind] = $datosZarp['zarpeoTotal'];										
						}//FIN if($datos=mysql_fetch_array($rsZarp))
						
						//comprobar si existen datos de Pisos
						if($datosPisos = mysql_fetch_array($rsPisos)){											
							//verificar si $datos['aplicacion'] esta vacia asignale valor 0
							if ($datosPisos['volTotal']>$resZarpeo[$ind])
								$resPisos[$ind] = $datosPisos['volTotal'] - $resZarpeo[$ind];
						}//FIN if($cantTrasporte = mysql_fetch_array($rs_transporte))
					}
				}
			}while($mes=mysql_fetch_array($rsMeses));
		}
		//Desplegar los resultados de la consulta en una tabla
		echo "				
		<table cellpadding='5' width='100%'>				
			<tr>
				<td class='nombres_columnas' align='center'>MES</td>
				<td class='nombres_columnas' align='center'>ZARPEO</td>
				<td class='nombres_columnas' align='center'>PISOS</td>
				<td class='nombres_columnas' align='center'>TOTAL</td>
			</tr>";

		$nom_clase = "renglon_gris";
		$cont = 1;
		//contador que nos permite controlar el ciclo de los meses
		$contMes = 0;
		//Variables que permitiran sumar el total de cada arreglo para poder obtener su promedio
		$sumaZarpeo=0;
		$sumaPisos=0;
		$totalMes=0; 
		do{	
			//Realizar la suma de zarpeo y de pisos en un mes
			$totalMes=($resZarpeo[$contMes]+$resPisos[$contMes]);
			//Mostrar todos los registros que han sido completados
			echo "
				<tr>
					<td class='nombres_filas' width='15%'>$meses[$contMes]</td>
					<td class='$nom_clase' width='15%' align='right'>".number_format($resZarpeo[$contMes],2,".",",")."</td>
					<td class='$nom_clase' width='15%' align='right'>".number_format($resPisos[$contMes],2,".",",")."</td>
					<td class='$nom_clase' width='15%' align='right'>".number_format($totalMes,2,".",",")."</td>
				</tr>";
			//Realizar las sumas de los valores que contiene cada arreglo 
			$sumaZarpeo= ($sumaZarpeo+$resZarpeo[$contMes]);
			$sumaPisos= ($sumaPisos+$resPisos[$contMes]);
				
			//Determinar el color del siguiente renglon a dibujar
			$cont++;
			if($cont%2==0)
				$nom_clase = "renglon_blanco";
			else
				$nom_clase = "renglon_gris";
				$contMes++;
		}while($contMes<12);
		
		//declarar en 0 el promedio de ambas sumas
		$promSumas=0;
		//Realizar el promedio de cada obra y el total
		$sumaZarpeo= $sumaZarpeo/12;
		$sumaPisos= $sumaPisos/12;
		$promSumas= ($sumaZarpeo+$sumaPisos);
		
		echo  " 
			<tr>
				<td class='$nom_clase' width='15%' align='right'>PROMEDIO</td>
				<td class='$nom_clase' width='15%' align='right'>".number_format(($sumaZarpeo),2,".",",")."</td>
				<td class='$nom_clase' width='15%' align='right'>".number_format(($sumaPisos),2,".",",")."</td>
				<td class='$nom_clase' width='15%' align='right'>".number_format(($promSumas),2,".",",")."</td>
			</tr>
		</table>";
		?>
      </div> 
      <div id="grafica">   
	      <img src="http://<?php echo HOST; ?>/<?php echo SISAD; ?>/pages/ger/<?php echo $hdn_nomGrafica; ?>" width="100%" height="100%" border="0"/>
      </div><?php      
		
	}//FIN guardarRepComparativoMina($hdn_destino,$hdn_anio,$hdn_msg,$hdn_nomGrafica);	
	
	
	//Esta funcion exporta la infromacion generada en el reporte anual a un archivo de excel
	function guardarRepAnual($hdn_msg,$hdn_anio,$hdn_nomGrafica){
		//Esribir datos de la pagina en excel
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=ReporteAnual$hdn_anio.xls");
	

		//Definir el estilo de la tabla y el encabezado que aparecera sobre la tabla?>
        <head>
            <style>					
                <!--
                body { font-family:Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; }
                /*En este formato  se establece el tamaño,tipo de letra,color de fondo que ocupa la columna princiaplal de una tabla*/
                .nombres_columnas { font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #FFFFFF; background-color: #9BBA59; font-weight: bold; 
				border-top-width: medium; border-right-width: thin; border-bottom-width: medium; border-left-width: thin; border-top-style: solid;
				border-right-style: none; border-bottom-style: solid; border-left-style: none; border-top-color: #000000; border-bottom-color: #000000; }
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
                #grafica { position:absolute; left:0px; top:630px; width:959px; height:375px; z-index:5; }
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
            <table width="100%">					
            	<tr>
                	<td colspan="3" valign="baseline" align="left">
						<img src="http://<?php echo HOST; ?>/<?php echo SISAD; ?>/images/logo.png" width="118" height="58" 
                        align="absbottom" />					
					</td>
                	<td colspan="2"></td>
                	<td colspan="3">
						<div align="right" class="sub_encabezado"> 
						
				  		<em>CONCRETO  LANZADO DE FRESNILLO, S.A DE C.V</div>					
					</td>
           	  	</tr>
				<tr>
              		<td colspan="9" class="borde_linea" align="center">
						<span class="sub_encabezado">
							CONFIDENCIAL, PROPIEDAD DE &ldquo;CONCRETO LANZADO DE FRESNILLO, S.A DE C.V.&rdquo; PROHIBIDA SU REPRODUCCI
                 			&Oacute;N TOTAL O PARCIAL
						</span>					
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
			</table><?php			


		//Obtener los datos
		$anio = $hdn_anio;
		
				//Conectar a la BD de gerencia
		$conn = conecta("bd_gerencia");		

		//contador que nos permite controlar el ciclo de los meses
		$cont = 0;
		
		//Variables que nos permiten sumar el total mensual y el total de los totales
		$totalMensual=0;
		$totalTotales=0;
		
		//arreglo que contiene el nombre de los meses del año
		$meses = array(0=>"ENERO",1=>"FEBRERO",2=>"MARZO",3=>"ABRIL",4=>"MAYO",5=>"JUNIO",6=>"JULIO",7=>"AGOSTO",8=>"SEPTIEMBRE",9=>"OCTUBRE",10=>"NOVIEMBRE",
		11=>"DICIEMBRE");
		
		//ayudara a obtener el total por concepto y posteriromente el promendio
		$sumaConceptos = array();
							
		//Este arreglo contendra el valor de cada concepto en cada mes del año seleccionado
		$conceptos = array(); 
		
		//Declarar el arreglo para almacenar los destinos
		$destinos = array();
		//Ejecutar la sentencia que permite obtener los destinos registrados en la Bitacora de Zarpeo
		$rsDestino = mysql_query("SELECT DISTINCT destino FROM bitacora_zarpeo JOIN catalogo_ubicaciones ON ubicacion=destino");
		
		//Guardar las UBicaciones encontradas en el arreglo de destinos
		while($datos=mysql_fetch_array($rsDestino))
			$destinos[] = $datos['destino'];
		
		//Crear el mensaje que se mostrara en el titulo de la tabla
		$msg = "Comparativo Anual de Producci&oacute;n en el A&ntilde;o <em><u> $anio</em></u>";			
		
		//Desplegar los resultados de la consulta en una tabla
		echo "				
		<table cellpadding='5' width='130%'>				
			<tr>
				<td colspan='10' align='center' class='titulo_etiqueta'>$msg</td>
			</tr>
			<tr>
				<td rowspan='2' class='nombres_columnas' align='center'>MES</td>";
		foreach($destinos as $ind => $destino){
			$sumaConceptos[$destino] = array("ZARPEO"=>0,"PISOS"=>0);
			//Inicializar cada concepto como un arreglo para almacenar los valores de cada uno de los meses
			$conceptos[$destino." ZARPEO"] = array();
			$conceptos[$destino." PISOS"] = array();			
			echo "<td colspan='2' class='nombres_columnas' align='center'>$destino</td>";
		}

		//Agregar un indice para acumular los Colados y otro para el Zarpeo Via Seca
		$sumaConceptos['COLADOS'] = array("BOMBEO"=>0,"TD"=>0);
		$sumaConceptos['VIASECA'] = 0;
		
		//Inicializar cada concepto como un arreglo para almacenar los valores de cada uno de los meses
		$conceptos["COLADOS BOMBEO"] = array();
		$conceptos["COLADOS TD"] = array();
		$conceptos["VIASECA"] = array();
			
		echo "	<td colspan='2' class='nombres_columnas' align='center'>COLADOS</td>
				<td rowspan='2' class='nombres_columnas' align='center'>VIA SECA</td>
				<td rowspan='2' class='nombres_columnas' align='center'>TOTAL</td>
			</tr>";
			
		foreach($destinos as $ind => $destino){
			echo"<td class='nombres_columnas' align='center'>ZARPEO</td>
				<td class='nombres_columnas' align='center'>PISOS</td>";
		}
		
		echo "	<td class='nombres_columnas' align='center'>BOMBEO</td>
				<td class='nombres_columnas' align='center'>TIRO DIRECTO</td>
			</tr>";
		
		//Este ciclo ayudara a obtener los datos por cada mes
		$nom_clase = "renglon_gris";
		$contRenglon= 1;
		
		$contMes = 0;
		do{	
			//Variable para acumular el total de los conceptos encotrados por cada mes
			$totalMes = 0;	
			//Conectar a la BD de gerencia
			$conn = conecta("bd_gerencia");

			echo "
			<tr>
				<td class='nombres_filas' width='15%'>$meses[$contMes]</td>";
			
			//Este ciclo nos ayuda a obtener los conceptos de cada ubicacion encontrada
			foreach($destinos as $ind => $destino){
				//Obtener el numero del mes en dos digitos
				$mes = obtenerMes($contMes+1);
				//Variables de Zarpeo, Transporte y Pisos
				$cantZarpeoTotal = 0;
				$cantTrasporte = 0;
				$pisos = 0;
				//Extraer el Id del Destino
				$idUbicacion=mysql_fetch_array(mysql_query("SELECT id_ubicacion FROM catalogo_ubicaciones WHERE ubicacion='$destino'"));
				//Extraer los meses dados de alta en los periodos
				$mesActual=substr($meses[$contMes],0,3);
				$rsMeses=mysql_query("SELECT fecha_inicio,fecha_fin FROM presupuesto WHERE catalogo_ubicaciones_id_ubicacion='$idUbicacion[0]' AND SUBSTRING(periodo,-3)='$mesActual' AND SUBSTRING(periodo,1,4)='$anio'");
				if($datosMeses=mysql_fetch_array($rsMeses)){
					$fechaIni=$datosMeses["fecha_inicio"];
					$fechaFin=$datosMeses["fecha_fin"];
					//Crear la sentencia SQL para obtener el registro del mes correspondiente de zarpeos
					$datosZarpeoTotal = mysql_fetch_array(mysql_query("SELECT sum(cantidad) AS volTotal FROM bitacora_zarpeo WHERE fecha BETWEEN '$fechaIni' AND '$fechaFin' 
					AND destino='$destino'"));
					if($datosZarpeoTotal['volTotal']!="")
						$cantZarpeoTotal = $datosZarpeoTotal['volTotal'];
					//Crear la sentencia SQL para obtener el registro del mes correspondiente de pisos
					$datosTrasporte = mysql_fetch_array(mysql_query("SELECT sum(cantidad) AS volTotal FROM bitacora_transporte WHERE fecha BETWEEN '$fechaIni' AND '$fechaFin' 
					AND destino = '$destino'"));
					if($datosTrasporte['volTotal']!="")
						$cantTrasporte = $datosTrasporte['volTotal'];	
					//Obtener la Diferencia entre el volumen de Zarpeo y el volumen transportado, la diferencia se cosidera como pisos
					//La cantidad de zarpeo no puede ser negativa; la siguiente comparación verificara resultado positivo para obtener los pisos
					if($cantTrasporte>$cantZarpeoTotal)
						$pisos = $cantTrasporte - $cantZarpeoTotal;				
				}
				else{
					$fechaIni="";
					$fechaFin="";
				}
				echo "					
					<td class='$nom_clase' width='15%' align='right'>".number_format($cantZarpeoTotal,2,".",",")."</td>
					<td class='$nom_clase' width='15%' align='right'>".number_format($pisos,2,".",",")."</td>";	
				//Acumular el Zarpeo Via Humeda y los Pisos por cada Ubicación en cada Mes registrado para sacar el Promedio
				$sumaConceptos[$destino]['ZARPEO'] += $cantZarpeoTotal;
				$sumaConceptos[$destino]['PISOS'] += $pisos;
				//Acumular el total de cada concepto por ubicacion para obtener el total del MES
				$totalMes += ($cantZarpeoTotal + $pisos);
				$totalTotales	+= ($cantZarpeoTotal + $pisos);
				//Guardar los datos necesario para la Grafica por cada Ubicacion encontrada por Mes
				$conceptos[$destino." ZARPEO"][] = $cantZarpeoTotal;
				$conceptos[$destino." PISOS"][] = $pisos;												
			}//Fin de foreach($destinos as $ind => $destino)
			
			//************************
			//****** Para obtener los colados (estos provienen de la base de datos de producción en la tabla de detalle_colados)
			//************************
			
			//Cerrar el ultimo enlace de Conexion a la BD
			mysql_close($conn);
			
			//Reconectar a la BD de Produccion
			$conn = conecta("bd_produccion");
			//Obtener el numero del mes en dos digitos
			$mes = obtenerMes($contMes+1);
			//Crear la sentencia SQL para obtener el registro del mes correspondiente de bombeo
			$cantBombeo = 0;
			//Obtener las fechas de los presupuestos de Produccion
			$rsMesesProd=mysql_query("SELECT fecha_inicio,fecha_fin FROM presupuesto WHERE SUBSTRING(periodo,-3)='$mesActual' AND SUBSTRING(periodo,1,4)='$anio'");
			if($datosProd=mysql_fetch_array($rsMesesProd)){
				$fechaIniProd=$datosProd["fecha_inicio"];
				$fechaFinProd=$datosProd["fecha_fin"];
			}
			else{
				$fechaIniProd="";
				$fechaFinProd="";
			}
			if($fechaIniProd!="" && $fechaFinProd!=""){
				//Crear la sentencia SQL para obtener el registro del mes correspondiente de BOMBEO
				$datosBombeo = mysql_fetch_array(mysql_query("SELECT sum(volumen) AS volTotal FROM detalle_colados WHERE bitacora_produccion_fecha BETWEEN '$fechaIniProd' AND '$fechaFinProd' 
				AND tipo_colado='BOMBEO'"));
				if($datosBombeo['volTotal']!="")
					$cantBombeo = $datosBombeo['volTotal'];
									
				//Crear la sentencia SQL para obtener el registro del mes correspondiente de TIRO DIRECTO
				$cantTiroD = 0;
				$datosTiroD = mysql_fetch_array(mysql_query("SELECT sum(volumen) AS volTotal FROM detalle_colados WHERE bitacora_produccion_fecha BETWEEN '$fechaIniProd' AND '$fechaFinProd' 
				AND tipo_colado='TIRO DIRECTO'"));
				if($datosTiroD['volTotal']!="")
					$cantTiroD = $datosTiroD['volTotal'];
			}
			else{
				$cantBombeo = 0;
				$cantTiroD = 0;
			}			
			echo "	
				<td class='$nom_clase' align='right'>".number_format($cantBombeo,2,".",",")."</td>
				<td class='$nom_clase' align='right'>".number_format($cantTiroD,2,".",",")."</td>";
			
			//Acumular los Colados de cada mes para obtener el promedio
			$sumaConceptos['COLADOS']['BOMBEO'] += $cantBombeo;
			$sumaConceptos['COLADOS']['TD'] += $cantTiroD;
			//Acumular el total de los colados para obtener el total del mes
			 $totalMes += ($cantBombeo + $cantTiroD);
			//Total de todo el año
			$totalTotales	+= ($cantBombeo + $cantTiroD);
			//Guardar los datos necesario para la Grafica con los datos de Colados
			$conceptos["COLADOS BOMBEO"][] = $cantBombeo;
			$conceptos["COLADOS TD"][] = $cantTiroD;
			
			
			//************************
			//****** Para obtener la via seca (estos provienen de la base de datos de gerencia en la tabla de bitacora_zarpeo)
			//************************
			
			//Cerrar el ultimo enlace de Conexion a la BD
			mysql_close($conn);

			//Reconectar a la BD de Gerencia
			$conn = conecta("bd_gerencia");
			//Obtener el numero del mes en dos digitos
			$mes = obtenerMes($contMes+1);
			
			$cantViaSeca = 0;
			$datosViaSeca=mysql_fetch_array(mysql_query("SELECT sum(cantidad) AS volTotal FROM bitacora_zarpeo WHERE fecha BETWEEN '$fechaIni' AND '$fechaFin' 
			AND aplicacion='ZARPEO VIA SECA'"));
			if($datosViaSeca['volTotal']!="")
				$cantViaSeca = $datosViaSeca['volTotal'];
			
			echo "
					<td class='$nom_clase' align='right'>".number_format($cantViaSeca,2,".",",")."</td>";
			
			//Realizar la suma de cada mes para obtener el total del Zarpeo Via Seca
			$cantViaSeca= $sumaConceptos['VIASECA'] + $cantViaSeca;
			//Acumular el total del Zarpeo de la Via Seca para obtener el total del mes
			$totalMes += $cantViaSeca;	
			$totalTotales	+=  $cantViaSeca;
			//Guardar los datos necesario para la Grafica con los datos de los Volumenes de Via Seca
			$conceptos["VIASECA"][] = $cantViaSeca;
			
			echo "
					<td class='$nom_clase' align='right'>".number_format($totalMes,2,".",",")."</td>
				</tr>";
			
			//Determinar el color del siguiente renglon a dibujar
			$contMes++;
			$contRenglon++;
			if($contRenglon%2==0)
				$nom_clase = "renglon_blanco";
			else
				$nom_clase = "renglon_gris";			
			
			//Cerrar el ultimo enlace de Conexion a la BD
			mysql_close($conn);
			
		}while($contMes<12);					
		
		//Colocar el Promedio por mes de cada concepto
		echo "<tr>
				<td class='nombres_filas' width='15%' align='right'>PROMEDIO</td>";
		//Colocar el promedio por cada Concepto de Cada Ubicacion Encontrada
		foreach($destinos as $ind => $destino){
			//Obtener promedio de Zarpeo y de Pisos
			$promZarpeo = $sumaConceptos[$destino]['ZARPEO']/12;
			$promPisos = $sumaConceptos[$destino]['PISOS']/12;
			
			echo " 
				<td class='$nom_clase' width='15%' align='right'>".number_format($promZarpeo,2,".",",")."</td>
				<td class='$nom_clase' width='15%' align='right'>".number_format($promPisos,2,".",",")."</td>";
		}
		
		//Colocar el Promedio de los Colados
		$promBombeo = $sumaConceptos["COLADOS"]['BOMBEO']/12;
		$promTD = $sumaConceptos["COLADOS"]['TD']/12;
		echo " 
				<td class='$nom_clase' width='15%' align='right'>".number_format($promBombeo,2,".",",")."</td>
				<td class='$nom_clase' width='15%' align='right'>".number_format($promTD,2,".",",")."</td>";
				
		//Colocar el promedio del Zarpeo de Via Seca
		$promViaSeca = $sumaConceptos["VIASECA"]/12;
		echo " 
				<td class='$nom_clase' width='15%' align='right'>".number_format($promViaSeca,2,".",",")."</td>
				<td class='$nom_clase' width='15%' align='right'>".number_format($totalTotales,2,".",",")."</td>
			</tr>
		</table>";
		?>
      
      <div id="grafica">   
	      <img src="http://<?php echo HOST; ?>/<?php echo SISAD; ?>/pages/ger/<?php echo $hdn_nomGrafica; ?>" width="100%" height="100%" border="0"/>
      </div><?php    	
					
	}//FIN guardarRepAnual($hdn_msg,$hdn_anio){
	
	
	

	//Esta funcion exporte el REPORTE  a un archivo de excel
	function guardarRepRendimiento($hdn_nomReporte, $hdn_consulta, $hdn_idRegRendimiento){
		//Esribir datos de la pagina en excel
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=$hdn_nomReporte.xls");
				
		
		//Separar el Nombre del Reporte (ReporteRendimiento_1/300-20-NT) para obtener el ID del Registro de Rendimiento y el ID de la Mezcla				
		$seccNomReporte = split("/", $hdn_nomReporte);
		$idMezcla = $seccNomReporte[1];
		//Obtener el Nombre de la Mezcla
		$nomMezcla = obtenerDato("bd_laboratorio", "mezclas", "nombre", "id_mezcla", $idMezcla);
		
		//Realizar la conexion a la BD 
		$conn = conecta("bd_laboratorio");
			
																						
		/********************************************OBTENER LOS DATOS DEL DISEÑO DE LA MEZCLA********************************************/
		//Verificar si el diseño original fue modificado, buscar en la tabla de Cambios Diseño Mezcla primero
		$sql_stm_mat1 = "SELECT * FROM cambios_disenio_mezcla WHERE rendimiento_id_registro_rendimiento = $hdn_idRegRendimiento AND mezclas_id_mezcla = '$idMezcla'";
		$sql_stm_mat2 = "SELECT * FROM materiales_de_mezclas WHERE mezclas_id_mezcla='$idMezcla'";
		
		$sql_stm_materiales = "";
		
		//Verificar si la primera consulta regresa datos, para tomar el diseño de la mezcla de ahi
		if($datos=mysql_fetch_array(mysql_query($sql_stm_mat1)))
			$sql_stm_materiales = $sql_stm_mat1;		
		else//Si el diseño no fue modificado, tomar los datos de la segunda consulta
			$sql_stm_materiales = $sql_stm_mat2;		
																
		//Ejecutar la Sentencia SQL para obtener los datos del Diseño de la Mezcla seleccionada
		$rs_materiales = mysql_query($sql_stm_materiales);			
		//Cerrar la Conexion con la BD de Laboratorio
		mysql_close($conn);
		
		
		//Arreglo que almacena los nombres de los materiales
		$nombresMat = array();
		//Arreglo para Almacenar los volumenes de los Materiales
		$cantidadesMat = array();
		//Arreglo que permite guardar las unidades
		$unidadesMat = array();		
		
		$cont=1;	
		//Verificar que la consulta tenga datos
		if($datosMat=mysql_fetch_array($rs_materiales)){
			do{						
				//Recuperar datos adicionales del los materiales de la mezcla seleccionada
				$nomMaterial = obtenerDato('bd_almacen', 'materiales', 'nom_material', 'id_material', $datosMat['catalogo_materiales_id_material']);
											
				//Guardamos los nombres de los materiales en el arreglo; se obtiene en obtener dato $nomMaterial
				$nombresMat[] = $nomMaterial;
				//Almacenamos los volumenes
				$cantidadesMat[] = $datosMat['cantidad'];
				//Almacenamos las unidades
				$unidadesMat[] = $datosMat['unidad_medida'];
				//incrementamos el contador
				$cont++;
			}while($datosMat=mysql_fetch_array($rs_materiales));
		}//Cierre if($datosMat=mysql_fetch_array($rs_materiales))
				
		
		/********************************************OBTENER LOS DATOS DEL RENDIMIENTO Y LA MEZCLA********************************************/
		//Realizar la conexion a la BD 
		$conn = conecta("bd_laboratorio");
				
		$rs_rendimiento = mysql_query("SELECT * FROM rendimiento JOIN mezclas ON mezclas_id_mezcla=id_mezcla 
									WHERE id_registro_rendimiento = $hdn_idRegRendimiento AND id_mezcla = '$idMezcla'");		
		//Guardamos los datos del Detalle del Rendimiento en las variables que serán mostradas
		if($datos_rend=mysql_fetch_array($rs_rendimiento)){					
			//Recuperar los datos de la Mezcla						
			$expediente = $datos_rend['expediente'];
			$equipo_mezclado = $datos_rend['equipo_mezclado'];
			//Recuperar los datos generales del Rendimiento
			$num_muestra = $datos_rend['num_muestra'];
			$localizacion = $datos_rend['localizacion'];
			$revenimiento = $datos_rend['revenimiento'];		
			$temperatura = $datos_rend['temperatura'];
			$hora = $datos_rend['hora'];
			$fechaRegistro = $datos_rend['fecha_registro'];
			$observaciones = $datos_rend['observaciones'];
			$notas = $datos_rend['comentarios'];
		}										
		
		/********************************************OBTENER LOS DATOS DEL DETALLE DEL RENDIMIENTO********************************************/
		//Ejecutamos la consulta para obtener el Detalle del Rendimiento de la Mezcla Seleccionada que viene en el POST
		$rs_detalleRend = mysql_query($hdn_consulta);		
		//Guardamos los datos del Detalle del Rendimiento en las variables que serán mostradas
		if($datos_detalleRend=mysql_fetch_array($rs_detalleRend)){			
			$pvol_bruto = round($datos_detalleRend['pvol_bruto'],5);
			$pvol_molde = round($datos_detalleRend['pvol_molde'],5);
			$pvol_unit = round($datos_detalleRend['pvol_unit'],5);
			$factor_recipiente = round($datos_detalleRend['factor_recipiente'],5);
			$pvol_teorico_rend = round($datos_detalleRend['pvol_teorico_rend'],5);
			$pvol_rend = round($datos_detalleRend['pvol_rend'],5);
			$pvol_teorico_caire = round($datos_detalleRend['pvol_teorico_caire'],5);
			$pvol_caire = round($datos_detalleRend['pvol_caire'],5);
			$cb = round($datos_detalleRend['cb'],5);
			$r = round($datos_detalleRend['r'],5);				
			$caireReal = round($datos_detalleRend['caire_real'],5);
		}
				
		/********************************************OBTENER LAS PRUEBAS REALIZADAS********************************************/		
		$normas = array();
		$rs_pruebasEjec = mysql_query("SELECT catalogo_pruebas_id_prueba, norma, nombre 
										FROM pruebas_realizadas JOIN catalogo_pruebas ON catalogo_pruebas_id_prueba=id_prueba										
										WHERE rendimiento_id_registro_rendimiento = $hdn_idRegRendimiento");
		//Guardamos los datos del Detalle del Rendimiento en las variables que serán mostradas
		if($datos_pruebasEjec=mysql_fetch_array($rs_pruebasEjec)){
			do{
				$normas[] = $datos_pruebasEjec['norma'].", ".$datos_pruebasEjec['nombre'];
			}while($datos_pruebasEjec=mysql_fetch_array($rs_pruebasEjec));
		}
				
				
		//Definir el estilo de la tabla y el encabezado que aparecera sobre la tabla?>
		<head>
			<style>					
				<!--
				body { font-family:Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; }				
				.Estilo9 {font-size: 14px; color:#0000CC; font-weight: bold;}
				.Estilo10 {font-size: 14px; color:#000000; font-weight: bold;}
				.borde_linea {border-top:3px; border-top-color:#4E6128; border-top-style:solid;}
				.borde_celda {
					border-top-width: medium; border-top-style: solid; border-top-color: #000000;	
					border-right-width: thin; border-right-style: solid; border-right-color: #000000;
					border-left-width: thin; border-left-style: solid; border-left-color: #000000;
					border-bottom-width: medium; border-bottom-style: solid; border-bottom-color: #000000;						
				}								
				.sub_encabezado {font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #0000CC; }				
				.texto_encabezado {font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #0000CC; }
				.titulo_tabla {font-family: Arial, Helvetica, sans-serif; font-size: 12px; font-weight:bold; }
			-->
			</style>
		</head>	
		<body>		
		<table width="1020">
        	<tr>
            	<td colspan="5">
					<img src="http://<?php echo HOST; ?>/<?php echo SISAD; ?>/images/logo.png" width="118" height="58" align="absbottom" />				
				</td>
            	<td colspan="6">
					<div align="right"> 
						<span class="texto_encabezado">
							<strong>LABORATORIO DE CONTROL DE CALIDAD</strong><br>
							<em>CONCRETO  LANZADO DE FRESNILLO, S.A DE C.V</em>
					  </span>						
				  </div>
				</td>
          	</tr>
          	<tr>
            	<td colspan="11" align="center" class="borde_linea">
					<span class="sub_encabezado"> 
						CONFIDENCIAL, PROPIEDAD DE &ldquo;CONCRETO LANZADO DE FRESNILLO, S.A DE C.V.&rdquo; PROHIBIDA SU REPRODUCCI&Oacute;N TOTAL O PARCIAL
					</span>				
				</td>
          	</tr>
          	<tr>
		  		<?php //Aqui se especifica el ancho que tendra cada columna de acuerdo al diseño?>
				<td width="80">&nbsp;</td>
            	<td width="150">&nbsp;</td>
            	<td width="80">&nbsp;</td>
            	<td width="80">&nbsp;</td>
            	<td width="80">&nbsp;</td>
				<td width="80">&nbsp;</td>
				<td width="80">&nbsp;</td>
				<td width="150">&nbsp;</td>
				<td width="80">&nbsp;</td>
				<td width="80">&nbsp;</td>
				<td width="80">&nbsp;</td>
          	</tr>
          	<tr><td colspan="11">&nbsp;</td></tr>
			<tr><td colspan="11">&nbsp;</td></tr>
			<tr><td colspan="11">&nbsp;</td></tr>
			<tr><td colspan="11" class="borde_celda"><div align="center"  class="Estilo10">REPORTE DE RENDIMIENTO EN OBRA PARA OBRA EN INTERIOR MINA</div></td></tr>
			<tr><td colspan="11">&nbsp;</td></tr>
			<tr><td colspan="11">&nbsp;</td></tr>
			<tr><td colspan="11">&nbsp;</td></tr>
          	<tr>
            	<td>&nbsp;</td>
            	<td colspan="5" class="titulo_tabla"><?php echo strtoupper($_GET['nombre']); ?></td>
				<td class="titulo_tabla" align="right">Fecha:</td>
				<td colspan="2" class="titulo_tabla"><?php echo verFecha(1);?></td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
         	</tr>
          	<tr>
            	<td>&nbsp;</td>
           		<td colspan="10" class="titulo_tabla"><?php echo strtoupper($_GET['puesto']); ?></td>
          	</tr>
          	<tr>
          		<td>&nbsp;</td>
            	<td colspan="10" class="titulo_tabla"><?php echo strtoupper($_GET['empresa']); ?></td>
          	</tr>
          	<tr><td colspan="11">&nbsp;</td></tr>
          	<tr><td colspan="11">&nbsp;</td></tr>
          	<tr><td colspan="11">&nbsp;</td></tr>
          	<tr>
           	  	<td>&nbsp;</td>
           	  	<td class="borde_celda"><div align="center" class="Estilo9">EXPEDIENTE:</div></td>
            	<td colspan="3"><div align="center" class="titulo_tabla"><?php echo $expediente;?></div></td>
				<td colspan="2">&nbsp;</td>
           	  	<td class="borde_celda"><div align="center" class="Estilo9">N. MUESTRA:</div></td>
            	<td colspan="2"><div align="center" class="titulo_tabla"><?php echo $num_muestra;?></div></td>
				<td>&nbsp;</td>
          	</tr>
          	<tr><td colspan="11">&nbsp;</td></tr>
          	<tr>
			  	<td><div align="center"></div></td>
			  	<td class="borde_celda"><div align="center" class="Estilo9">LOCALIZACI&Oacute;N:</div></td>
				<td colspan="3"><div align="center" class="titulo_tabla"><?php echo $localizacion;?></div></td>
				<td colspan="2">&nbsp;</td>
				<td class="borde_celda"><div align="center" class="Estilo9">REVENIMIENTO:</div></td>
				<td colspan="2"><div align="center" class="titulo_tabla"><?php echo $revenimiento;?> CM.</div></td>
				<td>&nbsp;</td>
          	</tr>
          	<tr><td colspan="11">&nbsp;</td></tr>
          	<tr>
			  	<td><div align="center"></div></td>
			  	<td class="borde_celda"><div align="center" class="Estilo9">EQUIPO DE MEZCLADO: </div></td>
				<td colspan="3"><div align="center" class="titulo_tabla"><?php echo $equipo_mezclado;?></div></td>
				<td colspan="2">&nbsp;</td>
				<td class="borde_celda"><div align="center" class="Estilo9">HORA:</div></td>
				<td colspan="2"><div align="center" class="titulo_tabla"><?php echo substr($hora,0,5);?> HRS.</div></td>
				<td>&nbsp;</td>
          	</tr>
          	<tr><td colspan="11">&nbsp;</td></tr>
          	<tr>
			  	<td><div align="center"></div></td>
			  	<td class="borde_celda"><div align="center" class="Estilo9">TEMPERATURA:</div></td>				
				<td colspan="3"><div align="center" class="titulo_tabla"><?php echo $temperatura;?>&deg;C</div></td>
				<td colspan="2">&nbsp;</td>
				<td class="borde_celda"><div align="center" class="Estilo9">TIPO DE MEZCLA: </div></td>
				<td colspan="2"><div align="center" class="titulo_tabla"><?php echo $nomMezcla;?></div></td>
				<td>&nbsp;</td>
          	</tr>
          	<tr><td colspan="11">&nbsp;</td></tr>
          	<tr>
				<td colspan="2">&nbsp;</td>
				<td colspan="5" class="Estilo10"><div align="center">DOSIFICACI&Oacute;N</div></td>
				<td colspan="4">&nbsp;</td>
			</tr>
          	<tr>
				<td>&nbsp;</td>
				<td colspan="7" class="titulo_tabla" align="center">El análisis del rendimiento presentado, es en base al diseño que se muestra a continuación:</td>
				<td colspan="3">&nbsp;</td>
			</tr>
		   	<tr><td colspan="11">&nbsp;</td></tr>
		   	<tr>
				<td colspan="2">&nbsp;</td>
				<td colspan="2" class="borde_celda"><div align="center" class="Estilo9">MATERIALES</div></td>
				<td colspan="2"class="borde_celda"><div align="center" class="Estilo9">1 m&sup3;</div></td>
				<td class="borde_celda"><div align="center" class="Estilo9">UNIDAD</div></td>
				<td colspan="4">&nbsp;</td>
          	</tr><?php		
				//Este ciclo nos permite recorrer el arreglo de cantidades y el de los nombres de los materiales; para dibujar la tabla de manera dinamica				
				$totales = 0;
				foreach($cantidadesMat as $ind => $cantidad){
					//Formatear la cantidad del material que va a ser desplegado, obteniendo la cantidad de decimales y despues aplicar la funcion number_format
					$decs = contarDecimales($cantidadesMat[$ind]);
					$cantFormat = number_format($cantidadesMat[$ind],$decs,".",",");?>
					<tr>
						<td colspan="2">&nbsp;</td>
						<td colspan="2" class="borde_celda"><div align="center" class="Estilo9"><?php echo $nombresMat[$ind];?></div></td>
						<td colspan="2" class="borde_celda"><div align="center" class="Estilo9"><?php echo $cantFormat;?></div></td>
						<td class="borde_celda"><div align="center" class="Estilo9"><?php echo $unidadesMat[$ind];?></div></td>
						<td colspan="4">&nbsp;</td><?php 
						//Obtener el total de las cantidades de los materiales listados
						$totales = $totales+str_replace(",","",$cantidadesMat[$ind]);?>
					</tr><?php					 
				}//Cierre foreach($cantidadesMat as $ind => $cantidad)?>
			<tr>
				<td colspan="2">&nbsp;</td>
				<td colspan="2" class="borde_celda"><div align="center" class="Estilo9">TOTALES</div></td><?php
					//Formatear el Total de la Suma de los pesos de los materiales de la mezcla, obteniendo la cantidad de decimales y despues aplicar la funcion number_format
					$decs = contarDecimales(round($totales,5));
					$totalFormat = number_format($totales,$decs,".",",");?>
				<td colspan="2" class="borde_celda"><div align="center" class="Estilo9"><?php echo $totalFormat;?></div></td>
				<td class="borde_celda">&nbsp;</td>
				<td colspan="4">&nbsp;</td>
			</tr>
          	<tr><td colspan="11">&nbsp;</td></tr>
          	<tr>
				<td>&nbsp;</td>
				<td class="borde_celda"><div align="center" class="Estilo9">P. VOL. (KG/M&sup3;) </div></td><?php 
					$pVol = ($pvol_bruto-$pvol_molde)*$factor_recipiente; 
					//Formatear el numero indicado, obteniendo la cantidad de decimales y despues aplicar la funcion number_format
					$decs = contarDecimales(round($pVol,5));
					$pVolFormat = number_format($pVol,$decs,".",",");?>
			  	<td colspan="2"><div align="center" class="Estilo10"><?php echo $pVolFormat; ?> KG/M&sup3;</div></td>
				<td colspan="3">&nbsp;</td>
				<td colspan="2" class="borde_celda"><div align="center" class="Estilo9">RENDIMIENTO (M&sup3;) </div></td><?php 
					$rendimiento = $pvol_teorico_rend/$pvol_rend; 
					//Formatear el numero indicado, obteniendo la cantidad de decimales y despues aplicar la funcion number_format
					$decs = contarDecimales(round($rendimiento,5));
					$rendFormat = number_format($rendimiento,$decs,".",",");?>
			  	<td colspan="2"><div align="center" class="Estilo10"><?php echo $rendFormat; ?> M&sup3;</div></td>
         	</tr>
         	<tr>
				<td>&nbsp;</td>
				<td><div align="left" class="titulo_tabla">PESO BRUTO  </div></td>
			  	<td><div align="right" class="titulo_tabla"><?php
					//Formatear el numero indicado, obteniendo la cantidad de decimales y despues aplicar la funcion number_format
					$decs = contarDecimales(round($pvol_bruto,5));
					$pvolBrutoFormat = number_format($pvol_bruto,$decs,".",",");
					echo $pvolBrutoFormat;?></div>
				</td>
				<td>&nbsp;</td>
				<td colspan="3">&nbsp;</td>
				<td><div align="left" class="titulo_tabla">PESO VOL. TE&Oacute;RICO </div></td>
			 	<td><div align="right" class="titulo_tabla"><?php 
					//Formatear el numero indicado, obteniendo la cantidad de decimales y despues aplicar la funcion number_format
					$decs = contarDecimales(round($pvol_teorico_rend,5));
					$pvolTeoricoFormat = number_format($pvol_teorico_rend,$decs,".",",");
					echo $pvolTeoricoFormat;?></div>
				</td>
				<td colspan="2">&nbsp;</td>
          	</tr>
          	<tr>
				<td>&nbsp;</td>
				<td><div align="left" class="titulo_tabla">PESO MOLDE</div></td>
			  	<td><div align="right" class="titulo_tabla"><?php 
					//Formatear el numero indicado, obteniendo la cantidad de decimales y despues aplicar la funcion number_format
					$decs = contarDecimales(round($pvol_molde,5));
					$pvolMoldeFormat = number_format($pvol_molde,$decs,".",",");
					echo $pvolMoldeFormat;?></div></td>
				<td>&nbsp;</td>
				<td colspan="3">&nbsp;</td>
				<td><div align="left" class="titulo_tabla">PESO VOL. </div></td>
			  	<td><div align="right" class="titulo_tabla"><?php 
					//Formatear el numero indicado, obteniendo la cantidad de decimales y despues aplicar la funcion number_format
					$decs = contarDecimales(round($pvol_rend,5));
					$pvolRendFormat = number_format($pvol_rend,$decs,".",",");
					echo $pvolRendFormat;?></div>
				</td>
				<td colspan="2">&nbsp;</td>
          	</tr>
          	<tr>
				<td>&nbsp;</td>
				<td><div align="left" class="titulo_tabla">PESO UNITARIO </div></td>
			  	<td><div align="right" class="titulo_tabla"><?php 
					//Formatear el numero indicado, obteniendo la cantidad de decimales y despues aplicar la funcion number_format
					$decs = contarDecimales(round($pvol_unit,5));
					$pvolUnitFormat = number_format($pvol_unit,$decs,".",",");
					echo $pvolUnitFormat;?></div>
				</td>
				<td>&nbsp;</td>
				<td colspan="7">&nbsp;</td>
          	</tr>
          	<tr>
				<td>&nbsp;</td>
				<td><div align="left" class="titulo_tabla">FACTOR RECIPIENTE</div></td>
			 	<td><div align="right" class="titulo_tabla"><?php 
					//Formatear el numero indicado, obteniendo la cantidad de decimales y despues aplicar la funcion number_format
					$decs = contarDecimales(round($factor_recipiente,5));
					$factorFormat = number_format($factor_recipiente,$decs,".",",");
					echo $factorFormat;?></div>
				</td>
				<td colspan="4">&nbsp;</td>          					
				<td colspan="2" class="borde_celda"><div align="center" class="Estilo9">CONTENIDO REAL DE CEMENTO (KG)</div></td>
			  	<td colspan="2"><div align="center" class="Estilo10"><?php 
					$contRealCemento = $cb/$r;
					//Formatear el numero indicado, obteniendo la cantidad de decimales y despues aplicar la funcion number_format					
					$decs = contarDecimales(round($contRealCemento,5));
					$contRealFormat = number_format($contRealCemento,$decs,".",",");
					echo $contRealFormat; ?> KG</div></td>				
			</tr>	
			<tr>
				<td colspan="7">&nbsp;</td>
				<td><div align="left" class="titulo_tabla">Cb</div></td>
			  	<td><div align="right" class="titulo_tabla"><?php echo $cb;?></div></td>
				<td colspan="2">&nbsp;</td>
			</tr>
			<tr>
				<td colspan="7">&nbsp;</td>
				<td><div align="left" class="titulo_tabla">R </div></td>
			  	<td><div align="right" class="titulo_tabla"><?php echo $r;?></div></td>	
				<td colspan="2">&nbsp;</td>
			</tr>	
			<tr>
				<td>&nbsp;</td>
				<td colspan="2" class="borde_celda"><div align="center" class="Estilo9">CONTENIDO DE AIRE (%)</div></td><?php 				
					$contAire = (($pvol_rend-$pvol_teorico_rend)/$pvol_rend)*100; 
					//Formatear el numero indicado, obteniendo la cantidad de decimales y despues aplicar la funcion number_format					
					$decs = contarDecimales(round($contAire,5));
					$contAireFormat = number_format($contAire,$decs,".",",");?>					
			  	<td><div align="center" class="Estilo10"><?php echo $contAireFormat; ?> %</div></td>								
				<td colspan="7" width="40">&nbsp;</td>
          	</tr>
          	<tr>
				<td>&nbsp;</td>				
				<td><div align="left" class="titulo_tabla">PESO VOLUMETRICO</div></td>
			  	<td><div align="right" class="titulo_tabla"><?php 
					//Formatear el numero indicado, obteniendo la cantidad de decimales y despues aplicar la funcion number_format					
					$decs = contarDecimales(round($pvol_rend,5));
					$pvolRendFormat = number_format($pvol_rend,$decs,".",",");
					echo $pvolRendFormat;?></div></td>
				<td colspan="4">&nbsp;</td>
				<td colspan="2" class="borde_celda"><div align="center" class="Estilo9">CONTENIDO REAL DE AIRE (%)</div></td>
			  	<td><div align="center" class="Estilo10"><?php 
					//Formatear el numero indicado, obteniendo la cantidad de decimales y despues aplicar la funcion number_format					
					$decs = contarDecimales(round($caireReal,5));
					$cAireFormat = number_format($caireReal,$decs,".",",");
					echo $cAireFormat; ?> %</div></td>
				<td>&nbsp;</td>
          	</tr>
          	<tr>
				<td>&nbsp;</td>								
				<td><div align="left" class="titulo_tabla">PESO MEZCLA </div></td>
			  	<td><div align="right" class="titulo_tabla"><?php 
					//Formatear el numero indicado, obteniendo la cantidad de decimales y despues aplicar la funcion number_format					
					$decs = contarDecimales(round($pvol_teorico_rend,5));
					$pVolTeoFormat = number_format($pvol_teorico_rend,$decs,".",",");
					echo $pVolTeoFormat;?></div></td>										
				<td colspan="8">&nbsp;</td>
          	</tr>
          	<tr><td colspan="11">&nbsp;</td></tr>
          	<tr><td colspan="11">&nbsp;</td></tr>          	
          	<tr>
				<td>&nbsp;</td>
            	<td colspan="9" rowspan="5" class="borde_celda" valign="top">
					<span class="Estilo9">OBSERVACIONES:</span>
					<br>
              		<span class="titulo_tabla"><?php echo $observaciones;?></span>				
				</td>
				<td>&nbsp;</td>
            </tr>
          	<tr><td colspan="11">&nbsp;</td></tr>
          	<tr><td colspan="11">&nbsp;</td></tr>
          	<tr><td colspan="11">&nbsp;</td></tr>
			<tr><td colspan="11">&nbsp;</td></tr>
			<tr><td colspan="11">&nbsp;</td></tr>
          	<tr>
		  		<td>&nbsp;</td>
            	<td colspan="9" rowspan="2" class="titulo_tabla">					
					NOTA: EL CÁLCULO DE RENDIMIENTO SE HACE PARA 1m³, UTILIZANDO TODOS LOS PESOS DE DOSIFICACIÓN QUE SE REQUIEREN PARA LA MEZCLA.
					<br>
					CON LA SIGUIENTE FORMULA:
				</td>
				<td>&nbsp;</td>
         	</tr>
          	<tr><td colspan="11">&nbsp;</td></tr>
			<tr>
				<td>&nbsp;</td>
				<td colspan="10"><img src="http://<?php echo HOST; ?>/<?php echo SISAD; ?>/pages/lab/images/rpt-rendimiento-formula.png" ></td>
			</tr>
			<tr><td colspan="11">&nbsp;</td></tr>
			<tr><td colspan="11">&nbsp;</td></tr><?php
			//Colocar cada norma en un renglon
			foreach($normas as $ind => $norma){?>
				<tr>
					<td>&nbsp;</td>
					<td colspan="9" class="titulo_tabla">
						<?php echo $norma; ?>
					</td>
					<td>&nbsp;</td>
				</tr><?php
			}?>								          	
          	<tr><td colspan="11">&nbsp;</td></tr>
		  	<tr><td colspan="11">&nbsp;</td></tr>
		  	<tr><td colspan="11">&nbsp;</td></tr>
          	<tr><td colspan="11">&nbsp;</td></tr>
          	<tr>
				<td>&nbsp;</td>
				<td colspan="4">__________________________________________</td>
				<td colspan="6">&nbsp;</td>
			</tr>
          	<tr>
				<td>&nbsp;</td>
				<td align="center"colspan="4"><div class="titulo_tabla" align="center">JEFE DE LABORATORIO</div></td>
				<td colspan="6">&nbsp;</td>
			</tr>
          	<tr>
				<td>&nbsp;</td>
				<td align="center"colspan="4"><div class="titulo_tabla" align="center">ING. EDGAR ALAN GARCIA CRUZ</div></td>
				<td colspan="6">&nbsp;</td>
			</tr>
		  	<tr><td colspan="11">&nbsp;</td></tr>
		  	<tr><td colspan="11">&nbsp;</td></tr>
          	<tr>
				<td>&nbsp;</td>
				<td colspan="5">
					<div class="Estilo9" align="left">C.C.P. ING. JAVIER AGUAYO SANCHEZ. GERENTE T&Eacute;CNICO.</div>
				</td>
				<td colspan="5">&nbsp;</td>
			</tr>
          	<tr>
				<td>&nbsp;</td>
				<td colspan="3"><div class="Estilo9" align="left">C.C.P. ARCHIVO.</div></td>
				<td colspan="7">&nbsp;</td>
			</tr>
        </table>
		</body><?php					
	}//Fin de la Funcion guardarRepRendimiento($hdn_nomReporte, $hdn_consulta, $hdn_idRegRendimiento)
	

	
	//Esta funcion exporte el REPORTE  a un archivo de excel
	function guardarRepAgregados($hdn_tituloTabla, $hdn_nomReporte, $hdn_PBM ){
		//Esribir datos de la pagina en excel
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=$hdn_nomReporte.xls"); 

		//Realizar la conexion a la BD 
		$conn = conecta("bd_laboratorio");
		
		//Creamos la consulta que nos permitira guardar los conceptos necesarios para la correctar elaboración del reporte
		$sql_base="SELECT pvss_wm, pvss_vm, pvsc_wm ,pvsc_vm, densidad_msss, densidad_va, absorcion_msss, absorcion_ws, granulometria, origen_material, 
		                modulo_finura, nom_material, pl_wsc, pl_ws, fecha FROM (pruebas_agregados JOIN bd_almacen.materiales ON id_material=catalogo_materiales_id_material)
						WHERE id_pruebas_agregados='$hdn_PBM'";
		//Ejecutar la sentencia y almacena los 	datos de la consulta en la variable $rs (result set)
		$rs_datos = mysql_query($sql_base);

		//Verificar que la consulta tenga datos
		if($datos=mysql_fetch_array($rs_datos)){
			//Almacenamos los datos en variables para manejarlos dentro del reporte
			$pvss_wm=$datos['pvss_wm'];
			$pvss_vm=$datos['pvss_vm'];
			$pvsc_wm=$datos['pvsc_wm'];
			$pvsc_vm=$datos['pvsc_vm'];
			$densidad_msss=$datos['densidad_msss'];
			$densidad_va=$datos['densidad_va'];
			$absorcion_msss=$datos['absorcion_msss'];
			$absorcion_ws=$datos['absorcion_ws'];
			$granulometria=$datos['granulometria'];
			$origen_material=$datos['origen_material'];
			$modulo_finura=$datos['modulo_finura'];
			$nom_material=$datos['nom_material'];
			$fecha=$datos['fecha'];
			$pl_wsc=$datos['pl_wsc'];
			$pl_ws=$datos['pl_ws'];
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
			<table width="949" border="0" >
            	<tr>
                	<td align="left" valign="baseline" colspan="2">
						<img src="http://<?php echo HOST; ?>/<?php echo SISAD; ?>/images/logo.png" width="118" height="58" align="absbottom" /></td>
                	<td width="82"></td>
                	<td width="75"></td>
                	<td width="80"></td>
                	<td width="75"></td>
                	<td width="84"></td>
					<td colspan="5">
						<div align="right" class="sub_encabezado"> 
						<span class="texto_encabezado"><strong>LABORATORIO DE CONTROL DE CALIDAD</strong><br>
						<em>CONCRETO  LANZADO DE FRESNILLO, S.A DE C.V</em></span><span class="texto_encabezado1 Estilo2">
						<span class="Estilo3"><em>.</em></span> </span></div>					
					</td>
			  	</tr>
              	<tr>
              		<td colspan="12" align="center" class="borde_linea">
						<span class="sub_encabezado">CONFIDENCIAL, PROPIEDAD DE &ldquo;CONCRETO LANZADO DE FRESNILLO, S.A DE C.V.&rdquo; PROHIBIDA SU REPRODUCCI
                 		&Oacute;N TOTAL O PARCIAL</span>					
					</td>
              	</tr>
              	<tr><td colspan="12">&nbsp;</td></tr>
              	<tr><td colspan="12">&nbsp;</td></tr>
			  	<tr><td colspan="12">&nbsp;</td></tr>
              	<tr>
			  		<td>&nbsp;</td>
              		<td colspan="10" align="center" class="nombres_tablas">
			  			<span class="Estilo4">F 4.6.0 - 03 REPORTE DE ESTUDIO DE AGREGADOS PARA CONCRETO - <?php echo  $hdn_tituloTabla." ".$origen_material; ?></span>
					</td>
					<td>&nbsp;</td>
              	</tr>
				<tr><td colspan="12">&nbsp;</td></tr>
				<tr><td colspan="12">&nbsp;</td></tr>
				<tr><td colspan="12">&nbsp;</td></tr>
				<tr>
					<td width="72">&nbsp;</td>
					<td class="Estilo5">Dirigido a:</td>
					<td class="Estilo5">&nbsp;</td>
					<td class="Estilo5">&nbsp;</td>
					<td class="Estilo5">&nbsp;</td>
					<td class="Estilo5">&nbsp;</td>
					<td colspan="2" class="Estilo5"><div align="right">Fecha Muestreo: &nbsp;</div></td>
					<td colspan="4" class="Estilo5"><div align="left"><span class="Estilo13"><?php echo modFecha($fecha,2); ?></span></div></td>
	 	  	    </tr>
			 	<tr>
					<td>&nbsp;</td>
					<td width="82" class="Estilo5">&nbsp;</td>
					<td colspan="3" class="Estilo5"><em>Ing. Guillermo Mart&iacute;nez</em></td>
				    <td class="Estilo5">&nbsp;</td>
				    <td colspan="2" class="Estilo5"><div align="right">Fecha Reporte:&nbsp; </div></td>
				    <td colspan="3" class="Estilo5"><span class="Estilo12"><span class="Estilo11">Fllo, Zacatecas <?php echo modFecha(date("Y-m-d"),2);?></span></span></td>
				    <td class="Estilo5">&nbsp;</td>
				    <td class="Estilo5">&nbsp;</td>
			 	</tr>
				<tr>
					<td colspan="2">&nbsp;</td>
					<td colspan="10" class="Estilo5"><em>Gerente General </em></td>
				</tr>
				<tr>
					<td colspan="2">&nbsp;</td>
					<td colspan="10" class="Estilo5"><em>Concreto Lanzado de Fresnillo  S.A. de C.V. </em></td>
				</tr>
				<tr><td colspan="12">&nbsp;</td></tr>
				<tr>
					<td>&nbsp;</td>
					<td colspan="3" class="Estilo5"><div align="center"><strong class="Estilo5"><em>PVSS (Kg/m&sup3;)  :</em></strong></div></td>
					<td class="Estilo5"><div align="center"><strong class="Estilo5"><?php echo round(($pvss_wm/$pvss_vm)*1000,2);?></strong></div></td>
					<td colspan="2">&nbsp;</td>
					<td colspan="3" class="Estilo5"><div align="center"><em>PVSC (Kg/m&sup3;)  :</em></div></td>
					<td width="83" class="Estilo5"><div align="center"><?php echo round(($pvsc_wm/$pvsc_vm)*1000,2);?></div></td>
					<td width="19">&nbsp;</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td><div align="center" class="Estilo5">Wm:</div></td>
					<td><div align="right"><span class="Estilo5"><?php echo $pvss_wm;?></span></div></td>
					<td class="Estilo5">Kg</td>
					<td><div align="center"></div></td>
					<td colspan="2">&nbsp;</td>
					<td width="81" class="Estilo5"><div align="center" class="Estilo5">Wm:</div></td>
					<td width="83" class="Estilo5"><div align="right"><?php echo $pvsc_wm;?></div></td>
					<td width="83" class="Estilo5">Kg</td>
					<td width="83" class="Estilo4"><div align="center"></div></td>
					<td width="19">&nbsp;</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td><div align="center" class="Estilo5">Vm:</div></td>
					<td><div align="right"><span class="Estilo5"><?php echo $pvss_vm; ?></span></div></td>
					<td class="Estilo5">Lts</td>
					<td><div align="center"></div></td>
					<td colspan="2">&nbsp;</td>
					<td class="Estilo5"><div align="center" class="Estilo5">Vm:</div></td>
					<td width="83" class="Estilo5"><div align="right"><?php echo $pvsc_vm;?></div></td>
					<td width="83" class="Estilo5">Lts</td>
					<td width="83" class="Estilo4"><div align="center"></div></td>
					<td width="19">&nbsp;</td>
				</tr>
				<tr><td>&nbsp;</td>
					<td>&nbsp;</td>
					<td class="Estilo5">&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td colspan="2">&nbsp;</td>
					<td class="Estilo5">&nbsp;</td>
					<td class="Estilo5">&nbsp;</td>
					<td class="Estilo5">&nbsp;</td>
					<td width="83" class="Estilo5">&nbsp;</td>
					<td width="19">&nbsp;</td>
				</tr>
				<tr><td colspan="12">&nbsp;</td></tr>
				<tr><td colspan="12">&nbsp;</td></tr>
				<tr>
					<td>&nbsp;</td>
					<td colspan="3" class="Estilo5"><div align="center"><strong class="Estilo5"><em>DENSIDAD (gr/cm&sup3;)  :</em></strong></div></td>
					<td class="Estilo5"><div align="center"><strong class="Estilo5"><?php echo round(($densidad_msss/$densidad_va),2);?></strong></div></td>
					<td colspan="2">&nbsp;</td>
					<td colspan="3" class="Estilo5"><div align="center"><em>ABSORCI&Oacute;N (%)  :</em></div></td>
					<td width="83" class="Estilo5"><div align="center"><?php echo round((($absorcion_msss-$absorcion_ws)/$absorcion_ws)*100,2);?></div></td>
					<td width="19">&nbsp;</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td><div align="center" class="Estilo5">Msss:</div></td>
					<td><div align="right"><span class="Estilo5"><?php echo $densidad_msss;?></span></div></td>
					<td class="Estilo5">gr</td>
					<td><div align="center"></div></td>
					<td colspan="2">&nbsp;</td>
					<td width="81" class="Estilo5"><div align="center" class="Estilo5">Msss:</div></td>
					<td width="83" class="Estilo5"><div align="right"><?php echo $absorcion_msss;?></div></td>
					<td width="83" class="Estilo5">gr</td>
					<td width="83" class="Estilo4"><div align="center"></div></td>
					<td width="19">&nbsp;</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td><div align="center" class="Estilo5">Va:</div></td>
					<td><div align="right"><span class="Estilo5"><?php echo $densidad_va; ?></span></div></td>
					<td class="Estilo5">cm&sup3;</td>
					<td><div align="center"></div></td>
					<td colspan="2">&nbsp;</td>
					<td class="Estilo5"><div align="center" class="Estilo5">Ws:</div></td>
					<td width="83" class="Estilo5"><div align="right"><?php echo $absorcion_ws;?></div></td>
					<td width="83" class="Estilo5">gr</td>
					<td width="83" class="Estilo4"><div align="center"></div></td>
					<td width="19">&nbsp;</td>
				</tr>
				<tr><td colspan="12">&nbsp;</td></tr>
				<tr><td colspan="12">&nbsp;</td></tr><?php 
				//Creamos la variable cadena para almacenar el nombre del material
				$cadena=$nom_material;
				//Creamos la varialble la cual contendra el concepto a buscar
				$cadenaBusq="ARENA";
				//Comparamos si viene ARENA en $cadena entonces dibujamos el renglon
				if(stristr($cadena, $cadenaBusq)==true){?>
					<tr>
					  <td colspan="12" align="right" class="Estilo5"><em>M&Oacute;DULO FINURA :</em> <?php echo $modulo_finura;?></td>
					</tr>
					<tr>
					  <td colspan="12" align="right" class="Estilo5">
					  	<em>P&Eacute;RDIDA POR LAVADO(%):</em> <?php echo round(((bcsub($pl_wsc,$pl_ws))/$pl_ws)*100,2);?>
					  </td>
					</tr><?php 
				}?>
				<tr><td colspan="12">&nbsp;</td></tr>
				<tr>
					<td>&nbsp;</td>
					<td colspan="2" class="Estilo5">GRANULOMETR&Iacute;A:</td>
					<td colspan="9">&nbsp;</td>
				</tr>
				<tr><td colspan="12">&nbsp;</td></tr>
				<tr><td colspan="12">&nbsp;</td></tr>
				<tr>
					<td colspan="3">&nbsp;</td>
					<td colspan="2" class="Estilo5"><?php echo $granulometria;?> </td>

					<td colspan="7">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="2">&nbsp;</td>
					<td colspan="3" class="nombres_tablas"><div align="center" class="Estilo5">MALLAS</div></td>
				
					<td colspan="3" class="nombres_tablas"><div align="center" class="Estilo5">% QUE PASA </div></td>
					<td colspan="3" class="nombres_tablas"><div align="center" class="Estilo5">% RETENIDO ACUMULADO </div></td>
					<td>&nbsp;</td>
				</tr><?php 
				
				//Consulta para obtener conceptos, retenido, limite inferior asu como limite superior para realizar las operacionesq ue permiten los calculos en el reporte
				$sql_detalle="SELECT  concepto, retenido,limite_inferior, limite_superior FROM detalle_prueba_agregados 
							  WHERE pruebas_agregados_id_pruebas_agregados='$hdn_PBM' ORDER BY numero DESC";
							  
				//Ejecutar la sentencia y almacena los 	datos de la consulta 
				$rs_detalle = mysql_query($sql_detalle);
		
				//Variable para guardar el total retenido
				$totalRetenido=0;
				//Arreglo para guardar la consulta; y asi permitir mostrar todos los registros al mismo tiempo
				$consultaConceptos=array();
				//Arreglo para Almacenar el limite inferior
				$limiteInferior=array();
				//Arreglo para Almacenar el limite superior
				$limiteSuperior=array();
				//Verificar que la consulta tenga datos
				if($datos=mysql_fetch_array($rs_detalle)){					
					do{
						//Acumulamos el total retenido
						$totalRetenido+=$datos['retenido'];
						//Almacenamos los conceptos
						$consultaConceptos[]=$datos['concepto'];
						//Almacenamos los limites_inferiores
						$limiteInferior[]=$datos['limite_inferior'];
						//Almacenamos los limites Superiores
						$limiteSuperior[]=$datos['limite_superior'];	
					}while($datos=mysql_fetch_array($rs_detalle));
				}
				//Consulta que permite obtener el numero y el retenido de cada agregado
				$sql_detalleASC="SELECT numero, retenido FROM detalle_prueba_agregados WHERE pruebas_agregados_id_pruebas_agregados='$hdn_PBM' ORDER BY numero";

				//Ejecutar la sentencia y almacena los 	datos de la consulta 
				$rs_detalleASC = mysql_query($sql_detalleASC);
				//Comprobamos que la consulta tiene datos
				if($datos=mysql_fetch_array($rs_detalleASC)){
					//Creamos el arreglo para guardar el porcentaje retenido
					$porcentajeRetenido=array();
					//Igualamos el total retenido 
					$totalRetenido=$totalRetenido;
					do{	
						//Almacenamos la operación necesaria para obtener el porcentaje Retenido	
						$porcentajeRetenido[]=(($datos['retenido']/$totalRetenido)*100);
					}while($datos=mysql_fetch_array($rs_detalleASC));
				}
				//Variable para controlar la cantidad de datos
				$tam=count($porcentajeRetenido);
				//Arrreglo para obtener el porcentaje retenido acumulado
				$porcentajeRetenidoAcumulado=array();
				//Guardamos el porcentaje retenido en su ultima posición como la primera posición del porcentaje retenido acumulado
				$porcentajeRetenidoAcumulado[]=$porcentajeRetenido[$tam-1];
				//Variable para controlar internamente el ciclo
				$band=0;
				//Variable para controlar la posicion inicial del arreglo (segun formula)
				$ctrl=$tam-2;
				do{
					//Almacenamos en el porcentaje retenido Acumulado la suma del porcentaje retenido mas el pocentaje retenido acumulado, bcadd tiene como 
					//objetivo obtener el resultado con un punto de presicion
					$porcentajeRetenidoAcumulado[]=bcadd($porcentajeRetenidoAcumulado[$band],$porcentajeRetenido[$ctrl],2);					
					//Disminuimos ctrl 
					$ctrl--;
					$band++;
				}while($ctrl>=0);
				//Arreglo que almacena el porcentaje retenido Acumulado de manera invertida
				$pRAInvertido=array();
				//Arreglo que almacena el porcentaje retenido acumuñlado sin invertir
				$porcentajeRetenidoSIN=array();					
				foreach($porcentajeRetenidoAcumulado as $ind =>$porcentaje){
					$pRAInvertido[]=round($porcentaje);
					$porcentajeRetenidoSIN[]=round($porcentaje);
				}
				//Arreglo que guarda el portentaje Retenido Acumulado pero de manera invertida
				$pRAInvertido=array_reverse($pRAInvertido);
				//Arreglo para Almacenar el porcentaje que pasa
				$porcentajePasa=array();
				//Realizamos la operación indicada por el cliente 100- el porcentajeRetenido en la ultima posicion
				$porcentajePasa[]=100-$porcentajeRetenido[$tam-1];
				$band=0;
				$ctrl=$tam-2;
				do{
					$porcentajePasa[]=bcsub($porcentajePasa[$band],$porcentajeRetenido[$ctrl],2);
					$band++;
					$ctrl--;
				}while($ctrl>=0);
				//Arrelgo para almacenar el porcentaje que pasa
				$pPasa=array();
				//Recorrremos para almacenar el pocentaje que pasa y a su vez redondearlo
				foreach($porcentajePasa as $ind =>$porcentajeP){
						$pPasa[]=abs(round($porcentajeP));	
				}?>
				<tr>
					<td colspan="2">&nbsp;</td><?php 
					$band=0;
					do{
						if($band!=0){?>
							<td colspan="2">&nbsp;</td>
						<?php } ?>
							<td colspan="3"class="nombres_tablas"><div align="center" class="Estilo5"><span class="caracter">'</span><?php echo $consultaConceptos[$band];?></div></td>
							<td colspan="3"class="nombres_tablas"><div align="center" class="Estilo5"><?php echo $pPasa[$band];?></div></td>
							<td colspan="3"class="nombres_tablas"><div align="center" class="Estilo5"><?php echo $porcentajeRetenidoSIN[$band];?></div></td>
			  </tr>
						<?php
						$band++;
					}while($band<$tam);?>					
				</tr>												
				<tr><td colspan="12">&nbsp;</td></tr>
				<tr><td colspan="12">&nbsp;</td></tr>
				<tr><td colspan="3">&nbsp;</td>
					<td colspan="6" rowspan="2" class="nombres_tablas">
						<div align="center" class="Estilo5">GR&Aacute;FICA DE COMPOSICI&Oacute;N GRANULOM&Eacute;TRICA</div>					</td>
					<td colspan="3">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="3">&nbsp;</td>
					<td colspan="3">&nbsp;</td>
				</tr>
				<tr><td colspan="12">&nbsp;</td></tr>
				<tr>
					<td colspan="2">&nbsp;</td>
					<td colspan="12"><?php
						//Dibujar la grafica con la información proporcionada
						$nombre=dibujarGraficaAgregados($consultaConceptos,$pPasa,$pRAInvertido, $limiteInferior, $limiteSuperior);?>
						<div align="center"><img src="http://<?php echo HOST; ?>/<?php echo SISAD; ?>/pages/ger/<?php echo $nombre;?>" width="700" height="400" 
							align="absbottom" />						</div>			  		</td>
				</tr>
				<tr><td colspan="12">&nbsp;</td></tr>
				<tr><td colspan="12">&nbsp;</td></tr>
				<tr><td colspan="12">&nbsp;</td></tr>
				<tr><td colspan="12">&nbsp;</td></tr>
				<tr><td colspan="12">&nbsp;</td></tr>
				<tr><td colspan="12">&nbsp;</td></tr>
				<tr><td colspan="12">&nbsp;</td></tr>
				<tr><td colspan="12">&nbsp;</td></tr>
				<tr><td colspan="12">&nbsp;</td></tr>
				<tr>
					<td>&nbsp;</td>
					<td colspan="10" rowspan="7" class="nombres_tablas" valign="top"><p class="Estilo5">OBSERVACIONES:</p><?php
						//Consulta que permite extraer la norma asi como la descripcion de la misma
						$stm_observaciones="SELECT observaciones FROM (detalle_prueba_agregados JOIN pruebas_agregados ON 
											id_pruebas_agregados=pruebas_agregados_id_pruebas_agregados) WHERE id_pruebas_agregados='$hdn_PBM'";
						$rs_observaciones = mysql_query($stm_observaciones);
						$cont=1;
						if($datos=mysql_fetch_array($rs_observaciones)){
							do{
								if($datos['observaciones']!=""){
									echo "<p>".$cont.".-".$datos['observaciones']."</p>"; 
									$cont++;
								}
							}while($datos=mysql_fetch_array($rs_observaciones));
						}?>					</td>
					<td>&nbsp;</td>
				</tr>
			  	<tr>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
			  	</tr>
			  	<tr>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
			  	</tr>
			  	<tr>
			  		<td>&nbsp;</td>
			    	<td>&nbsp;</td>
			  	</tr>
			  	<tr>
			  		<td>&nbsp;</td>
			    	<td>&nbsp;</td>
			  	</tr>
			  	<tr>
			  		<td>&nbsp;</td>
			    	<td>&nbsp;</td>
			  	</tr>
			  	<tr>
			  		<td>&nbsp;</td>
			    	<td>&nbsp;</td>
			  	</tr>
			  	<tr><td colspan="12">&nbsp;</td></tr>
			  	<tr><td colspan="12">&nbsp;</td></tr>
			  	<tr><td>&nbsp;</td>
			    	<td colspan="10" class="Estilo5"><?php
					//Consulta que permite extraer la norma asi como la descripcion de la misma
					$stm_catalogoMat="SELECT norma, nombre FROM ((catalogo_pruebas JOIN pruebas_realizadas ON catalogo_pruebas_id_prueba=id_prueba)
									  JOIN pruebas_agregados ON id_pruebas_agregados=pruebas_agregados_id_pruebas_agregados)
									  WHERE id_pruebas_agregados='$hdn_PBM'";
					$rs_catalogoMat = mysql_query($stm_catalogoMat);
					if($datos=mysql_fetch_array($rs_catalogoMat)){
						echo $datos['norma']." ".$datos['nombre']; 
					}?>					</td>
			    	<td>&nbsp;</td>
			  	</tr>
			  	<tr><td colspan="12">&nbsp;</td></tr>
				<tr><td colspan="12">&nbsp;</td></tr>
				<tr><td colspan="12">&nbsp;</td></tr>
				<tr><td colspan="12">&nbsp;</td></tr>
				<tr>
					<td>&nbsp;</td>
			    	<td colspan="4" style="border-bottom:solid; border-bottom-style:solid; border-bottom-color:#000000; border-bottom-width:thin;">&nbsp;</td>
			    	<td colspan="2">&nbsp;</td>
			    	<td colspan="4" style="border-bottom:solid; border-bottom-style:solid; border-bottom-color:#000000; border-bottom-width:thin;">&nbsp;</td>
			   		<td>&nbsp;</td>
				</tr>
				<tr>
			  		<td>&nbsp;</td>
			    	<td colspan="4" class="Estilo5"><div align="center" class="Estilo5">JEFE DE LABORATORIO</div></td>
			    	<td colspan="2" class="Estilo5">&nbsp;</td>
			    	<td colspan="4" class="Estilo5"><div align="center" class="Estilo5">GERENTE T&Eacute;CNICO </div></td>
			   		<td>&nbsp;</td>
			  	</tr>
			  	<tr>
			  		<td>&nbsp;</td>
					<td colspan="4" class="Estilo5"><div align="center" class="Estilo5">Ing Edgar Alan Garc&iacute;a Cruz </div></td>
					<td colspan="2" class="Estilo5">&nbsp;</td>
					<td colspan="4" class="Estilo5"><div align="center" class="Estilo5">Ing. Javier Aguayo Sanchez </div></td>
					<td>&nbsp;</td>
			  	</tr>
            </table>
		</body><?php					
	}//Fin de la Funcion guardarRepAgregados($hdn_tituloTabla, $hdn_nomReporte, $hdn_PBM )
	
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
	
	function exportarKardexAsistencia($hdn_fechaI,$hdn_fechaF,$hdn_dias,$hdn_consulta){
		//Esribir datos de la pagina en excel
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=EmpleadosAsistencias.xls");
		
		//Titulo
		$msg="REPORTE DE ASISTENCIAS DEL $hdn_fechaI AL $hdn_fechaF";
		//Recuperar las variables que vienen como parametros en el GET
		$fechaI=modFecha($hdn_fechaI,3);
		$fechaF=modFecha($hdn_fechaF,3);
		
		//Obtener a los trabajadores del área seleccionada
		$conn=conecta("bd_recursos");
		
		$res=mysql_query($hdn_consulta);
		?>				
		<head>
			<style>					
				<!--
				body { font-family:Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; }
				/*En este formato  se establece el tamaño,tipo de letra,color de fondo que ocupa la columna principal de una tabla*/
				.nombres_columnas { font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #FFFFFF; background-color: #9BBA59; font-weight: bold; 
				border-top-width: medium; border-right-width: medium; border-bottom-width: medium; border-left-width: medium; border-top-style: solid; border-right-style: solid;
				border-bottom-style: solid; border-left-style: solid; border-top-color: #000000; border-bottom-color: #000000; vertical-align:middle; }
				/*En este formato  se establece el tamaño,tipo de letra,color de fondo que se ocupa en la fila principal para resaltar datos  de una tabla*/
				.nombres_filas { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #9BBB59;}
				/*En este formato  se establece el tamaño,tipo de letra,color de fondo que contendra datos  de una tabla*/
				.renglon_gris { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #E7E7E7; vertical-align:middle;border-style:solid;border-color:#000000;border-width:thin;}
				/*En este formato  se establece el tamaño,tipo de letra,color de fondo que contendra datos  de una tabla*/
				.renglon_blanco { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #FFFFFF; vertical-align:middle;border-style:solid;border-color:#000000;border-width:thin;}
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
					<td align="left" valign="baseline" colspan="2">
						<img src="http://<?php echo HOST; ?>/<?php echo SISAD; ?>/images/logo.png" width="118" height="58" align="absbottom" />
					</td>
					<td colspan="<?php echo $hdn_dias; ?>">&nbsp;</td>
					<td valign="baseline" colspan="4">
						<div align="right"><span class="texto_encabezado">
							<strong>MANUAL DE PROCEDIMIENTOS DE LA CALIDAD</strong><br><em>CONCRETO LANZADO DE FRESNILLO, S.A DE C.V.</em>
						</span></div>
					</td>
				</tr>											
				<tr>
					<td colspan="<?php echo $hdn_dias + 6; ?>" align="center" class="borde_linea">
						<span class="sub_encabezado">
							CONFIDENCIAL, PROPIEDAD DE &ldquo;CONCRETO LANZADO DE FRESNILLO, S.A DE C.V.&rdquo; PROHIBIDA 
							SU REPRODUCCI&Oacute;N TOTAL O PARCIAL
						</span>
					</td>
				</tr>
				<tr>
					<td colspan="<?php echo $hdn_dias + 6; ?>">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="<?php echo $hdn_dias + 6; ?>">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="<?php echo $hdn_dias + 6; ?>" align="center" class="titulo_tabla"><?php echo $msg;?></td>
				</tr>
				<?php
				if($datos=mysql_fetch_array($res)){
					//Recuperar las Fechas de Inicio y de Fin
					$fechaI=$hdn_fechaI;
					$fechaF=$hdn_fechaF;
					//Convertir las Fechas a formato legible por MySQL
					$fechaIMod=modFecha($fechaI,3);
					$fechaFMod=modFecha($fechaF,3);
					//Obtener la cantidad de Dias entre las 2 Fechas
					$dias=restarFechas($fechaIMod,$fechaFMod)+1;
					$verificaMes=0;
					//Partir la Fecha de Inicio en secciones de dia, mes y año
					$diaI=substr($fechaI,0,2);
					$mesI=substr($fechaI,3,2);
					$anioI=substr($fechaI,-4);
					//Obtener la cantidad de Dias del primer Mes
					$cantDiasMesCurso=diasMes($mesI,$anioI);
					//Convertir en numero los dias,mes y año de la Fecha de Inicio
					$diasActual=0+$diaI;
					$mesActual=0+$mesI;
					$anioActual=0+$anioI;
					//Partir la Fecha de Fin en secciones de dia, mes y año
					$diaF=substr($fechaF,0,2);
					$mesF=substr($fechaF,3,2);
					$anioF=substr($fechaF,-4);
					//Convertir en numero los dias,mes y año de la Fecha de Inicio
					$diasTope=0+$diaF;
					$mesTope=0+$mesF;
					$anioTope=0+$anioF;
					
					//Comenzar a dibujar la Tabla
					echo "<table class='tabla_frm' cellpadding='5' id='tabla-resultadosKardex'>";
					echo "
						<thead>
							<tr>
								<th class='nombres_columnas' align='center' rowspan='2'>ID</th>
								<th class='nombres_columnas' align='center' rowspan='2'>NOMBRE</th>
						";
					
					//Obtener en el contador como primer valor
					$cont=$diasActual;
					//Arreglo con la cantidad de Dias por Mes
					$cantDias=array();
					//Arreglo con las Fechas
					$fechas=array();
					//Proceso cuando el año de tope e inicial son iguales
					if ($anioTope==$anioActual){
						//Proceso cuando el mes de Tope es mayor al Actual
						if ($mesTope>$mesActual){
							/***********************************/
							$mes=obtenerNombreMes($mesActual);
							$cols=($cantDiasMesCurso-$diasActual)+1;
							$cantDias[]=$cols;
							$ctrlFechas=$diasActual;
							do{
								$fechas[]=$anioActual."-".$mesActual."-".$cont;
								$cont++;
							}while($cont<=$cantDiasMesCurso);
							//Dibujar la columna del primer Mes
							echo "<td class='nombres_columnas' align='center' colspan='$cols'>$mes</td>";
							/***********************************/
							if(($mesActual+1)<$mesTope){
								//Siguientes Meses hasta antes del Tope
								do{
									$mesActual=$mesActual+1;
									$cantDiasMesCurso=diasMes($mesActual,$anioActual);
									$cont=1;
									do{
										$fechas[]=$anioActual."-".$mesActual."-".$cont;
										$cont++;
									}while($cont<=$cantDiasMesCurso);
									/***********************************/
									$mes=obtenerNombreMes($mesActual);
									$cols=$cantDiasMesCurso;
									$cantDias[]=$cols;
									//Dibujar la columna del primer Mes
									echo "<td class='nombres_columnas' align='center' colspan='$cols'>$mes</td>";
									/***********************************/
								}while(($mesActual+1)<$mesTope);
							}
							//Mes Tope
							$mesActual=$mesTope;
							$cont=1;
							do{
								$fechas[]=$anioActual."-".$mesActual."-".$cont;
								$cont++;
							}while($cont<=$diasTope);
							/***********************************/
							$mes=obtenerNombreMes($mesActual);
							$cols=$diasTope;
							$cantDias[]=$cols;
							//Dibujar la columna del primer Mes
							echo "<td class='nombres_columnas' align='center' colspan='$cols'>$mes</td>";
							/***********************************/
						}
						//Procesos cuando el mes de Tope y de inicio son iguales
						else{
							if($mesTope==$mesActual){
								do{
									$fechas[]=$anioActual."-".$mesActual."-".$cont;
									$cont++;
								}while($cont<=$diaF);
							}
							/***********************************/
							$mes=obtenerNombreMes($mesActual);
							$cols=($diaF-$diaI)+1;
							$cantDias[]=$cols;
							//Dibujar la columna del primer Mes
							echo "<td class='nombres_columnas' align='center' colspan='$cols'>$mes</td>";
							/***********************************/
						}
					}
					//Proceso cuando los años son diferentes
					else{
						
						//if($mesActual<=$mesTope){
							$ctrl=1;
							//Primer Mes
							do{
								$fechas[]=$anioActual."-".$mesActual."-".$cont;
								$cont++;
							}while($cont<=$cantDiasMesCurso);
							/***********************************/
							$mes=obtenerNombreMes($mesActual);
							$cols=($cantDiasMesCurso-$diasActual)+1;
							$cantDias[]=$cols;
							//Dibujar la columna del primer Mes
							echo "<td class='nombres_columnas' align='center' colspan='$cols'>$mes</td>";
							/***********************************/
							$estado=0;
							//Meses Siguientes
							do{
								$mesActual++;
								if($mesActual>12){
									$mesActual=$mesActual-12;
									$anioActual++;
								}
								$cantDiasMesCurso=diasMes($mesActual,$anioActual);
								/***********************************/
								$mes=obtenerNombreMes($mesActual);
								$cols=$cantDiasMesCurso;
								$cantDias[]=$cols;
								//Dibujar la columna del primer Mes
								echo "<td class='nombres_columnas' align='center' colspan='$cols'>$mes</td>";
								/***********************************/
								$cont=1;
								do{
									$fechas[]=$anioActual."-".$mesActual."-".$cont;
									$cont++;
								}while($cont<=$cantDiasMesCurso);
								if ($anioActual==$anioTope && $mesActual==($mesTope-1))
									$estado=1;
							}while($estado!=1);
							//Ultimo Mes
							$cont=1;
							do{
								$fechas[]=$anioActual."-".$mesActual."-".$cont;
								$cont++;
							}while($cont<=$diasTope);
							/***********************************/
							$mes=obtenerNombreMes($mesTope);
							$cols=$diasTope;
							$cantDias[]=$cols;
							//Dibujar la columna del primer Mes
							echo "<td class='nombres_columnas' align='center' colspan='$cols'>$mes</td>";
							/***********************************/
						//}
					}
					echo "<td class='nombres_columnas' align='center' colspan='4'>TOTAL POR EMPLEADO</td>";
					echo "</tr>";
					//Obtener la cantidad de Dias entre las 2 Fechas
					$diasTotales=restarFechas($fechaIMod,$fechaFMod)+1;
					//Contador para recorrer el arreglo de los Dias de cada Mes
					$cont=0;
					//Cantidad de Registros para mostrar el numero de dias
					$tamDias=count($cantDias);
					echo "<tr>";
					do{
						//Registro Primer Mes
						if ($cont==0){
							if($tamDias==1)
								$cantDiasMesCurso=$diasTope;
							else
								$cantDiasMesCurso=diasMes($mesI,$anioI);
							$ctrl=$diaI;
							do{
								if (strlen($ctrl)!=2)
									echo "<td class='nombres_columnas' align='center'>0$ctrl</td>";
								else
									echo "<td class='nombres_columnas' align='center'>$ctrl</td>";
								$ctrl++;
							}while($ctrl<=$cantDiasMesCurso);
						}
						//Registro Siguientes Meses
						if ($cont>0){
							//Variable para mostrar los numeros de la fecha en la columna
							$ctrl=1;
							do{
								if (strlen($ctrl)!=2)
									echo "<td class='nombres_columnas' align='center'>0$ctrl</td>";
								else
									echo "<td class='nombres_columnas' align='center'>$ctrl</td>";
								$ctrl++;
							}while($ctrl<=$cantDias[$cont]);
						}
						$cont++;
					}while($cont<$tamDias);
					echo "	<td class='nombres_columnas' align='center'>A</td>
							<td class='nombres_columnas' align='center'>F</td>
							<td class='nombres_columnas' align='center'>I</td>
							<td class='nombres_columnas' align='center'>AL</td>
						</tr>
						</thead>";
				
					$nom_clase = "renglon_gris";
					$cont = 1;
					echo "<tbody>";
					//Llenado de Datos de la Tabla
					do{
						$ta_emp = 0; $tf_emp = 0; $ti_emp = 0; $tal_emp = 0;
						echo "<tr>";
						echo "<td class='nombres_filas' align='center'>$datos[id_empleados_empresa]</td>";
						echo "<td class='$nom_clase' align='left'>$datos[nombre_emp]</td>";
						$ctrl=0;
						do{
							//Funcion que obtiene la checada en caso de Existir
							$checada=obtenerChecada($fechas[$ctrl],$datos["id_empleados_empresa"],$ta_emp,$tf_emp,$ti_emp,$tal_emp);
							echo "<td class='$nom_clase' align='center' style='color:#FFFFFF;$checada[5]'>$checada[0]</td>";
							$ctrl++;
							$ta_emp = $checada[1]; $tf_emp = $checada[2]; $ti_emp = $checada[3]; $tal_emp = $checada[4];
						}while($ctrl<(count($fechas)));
						echo "<td class='$nom_clase' align='center'>$ta_emp</td>";
						echo "<td class='$nom_clase' align='center'>$tf_emp</td>";
						echo "<td class='$nom_clase' align='center'>$ti_emp</td>";
						echo "<td class='$nom_clase' align='center'>$tal_emp</td>";
						echo "</tr>";
						
						//Determinar el color del siguiente renglon a dibujar
						$cont++;
						if($cont%2==0)
							$nom_clase = "renglon_blanco";
						else
							$nom_clase = "renglon_gris";
					}while($datos=mysql_fetch_array($res));
					echo "</tbody>";
					echo "</table>";
				}////Fin del IF que verifica que existan resultados en la consulta
				
				mysql_close($conn);
				?>
		</div>
		</body>
		<?php
	}//Cierre de la funcion exportarKardexAsistencia($hdn_fechaI,$hdn_fechaF,$hdn_dias,$hdn_consulta)
	
	//Grafica que es incluida en el reporte de Agregados
	function dibujarGraficaAgregados($consultaConceptos,$pPasa,$pRAInvertido, $limiteInferior, $limiteSuperior){	
		require_once ('../../includes/graficas/jpgraph/jpgraph.php');
		require_once ('../../includes/graficas/jpgraph/jpgraph_line.php');
				
		// So
		$wdata = array_reverse($pPasa);
		$ydata = array_reverse($limiteInferior);
		$zdata = array_reverse($limiteSuperior);
			
		// Create the graph. These two calls are always required
		$graph = new Graph(700,450);
		$graph->SetScale('textlin');
		$graph->yaxis->title->Set('%PASA');
		$graph->SetMargin(40,180,20,40);
		//Cambiar color del margen
		$graph->SetMarginColor("silver@0.5");
		//Establecer el margen separación entre etiquetas
		//$graph->xaxis->SetTextLabelInterval(2);
			
		// Crear las caracteristicas para cada una de las lineas
		$lineplot=new LinePlot($wdata);
		$lineplot->SetColor('blue');
		$lineplot->SetLegend('% Pasa');	
		//$lineplot->value->Show();
		
		$lineplot3=new LinePlot($ydata);
		$lineplot3->SetColor('red');
		$lineplot3->SetLegend('Límite Inferior');	
		//Muestra los valores de los datos en las lineas
		//$lineplot3->value->Show();
		
		$lineplot4=new LinePlot($zdata);
		$lineplot4->SetColor('green');
		$lineplot4->SetLegend('Límite Superior');	
		//$lineplot4->value->Show();
		
		//Agregar Nombres de los rotulos
		$graph->xaxis->SetTickLabels(array_reverse($consultaConceptos));
		
		//Agregar las lineas de datos a la grafica
		$graph->Add($lineplot);
		$graph->Add($lineplot3);
		$graph->Add($lineplot4);
		
		//Alinear los rotulos de la leyenda
		$graph->legend->SetPos(0.05,0.5,'right', 'center');
		
		$rnd=rand(0,1000);
		
		$grafica= 'tmp/grafica'.$rnd.'.png';
		//Dibujar la grafica y guardarla en un archivo temporal	
		$graph->Stroke($grafica);
		
		return $grafica;
		
			
	}//Cierre de la funcion dibujarGrafica($consultaConceptos,$pPasa,$pRAInvertido, $limiteInferior, $limiteSuperior)

	
	//Esta funcion es necesaria para el funcionamiento de guardarRepComparativoMina($hdn_destino,$hdn_anio,$hdn_msg,$hdn_nomGrafica)
	//Esta funcion es necesaria para el funcionamiento de guardarRepAnual($hdn_msg,$hdn_anio){
	function obtenerMes($cont){
		if($cont<=9)
			return '0'.$cont;
		if($cont>=10)
			return $cont;
	} //FIN function obtenerMes($cont)
	
	function obtenerCentroCostos($valor){
		$conn_rec=conecta("bd_recursos");
		$dato=" AND (";
		$sql_stm_rec="SELECT * FROM control_costos WHERE descripcion LIKE '%$valor%'";
		$rs_rec=mysql_query($sql_stm_rec);
		$num_reg = mysql_num_rows($rs_rec);
		
		$datos_rec=mysql_fetch_array($rs_rec);
		if ($datos_rec){
			$aux = 1;
			do{
				$dato .= "destino = '$datos_rec[id_control_costos]'";
				
				if($aux != $num_reg){
					$dato .= " OR ";
				}
				$aux++;
			}while($datos_rec=mysql_fetch_array($rs_rec));
			$dato .= ")";
		}
		mysql_close($conn_rec);
		return $dato;
	}
	
	function obtenerDatosCentroCostos($valor,$tabla,$busq){
		$conn_rec=conecta("bd_recursos");
		$dato="N/A";
		$sql_stm_rec="SELECT descripcion FROM $tabla WHERE $busq='$valor'";
		$rs_rec=mysql_query($sql_stm_rec);
		$datos_rec=mysql_fetch_array($rs_rec);
		if ($datos_rec[0]!="")
			$dato=$datos_rec[0];
		return $dato;
		mysql_close($conn_rec);
	}
	
	function obtenerDatosEntrada($valor){
		$conn=conecta("bd_almacen");
		$dato="ND";
		$sql_stm="SELECT MAX(entradas_id_entrada) AS id_entrada FROM detalle_entradas WHERE materiales_id_material='$valor'";
		$rs=mysql_query($sql_stm);
		$datos=mysql_fetch_array($rs);
		if ($datos[0]!="")
			$dato=$datos[0];
		return $dato;
	}
	
	function obtenerNombreMes($mes){
		//Comparar el valor de Mes para obtener su nombre de Mes correspondiente
		switch($mes){
			case 1:
				$mes="ENERO";
				break;
			case 2:
				$mes="FEBRERO";
				break;
			case 3:
				$mes="MARZO";
				break;
			case 4:
				$mes="ABRIL";
				break;
			case 5:
				$mes="MAYO";
				break;
			case 6:
				$mes="JUNIO";
				break;
			case 7:
				$mes="JULIO";
				break;
			case 8:
				$mes="AGOSTO";
				break;
			case 9:
				$mes="SEPTIEMBRE";
				break;
			case 10:
				$mes="OCTUBRE";
				break;
			case 11:
				$mes="NOVIEMBRE";
				break;
			case 12:
				$mes="DICIEMBRE";
				break;
		}
		return $mes;
	}
	
	function obtenerChecada($fecha,$id_empleado,$a,$f,$i,$al){
		$conexion = conecta("bd_gerencia");
		//Hacer un split a la Fecha por los guiones
		$fechaArray=split("-",$fecha);
		//Si el mes en la fecha es de un digito, colocar un 0, a la izquiera
		if(strlen($fechaArray[1])<2)
			$fechaArray[1]="0".$fechaArray[1];
		//Si el dia en la fecha es de un digito, colocar un 0, a la izquiera
		if(strlen($fechaArray[2])<2)
			$fechaArray[2]="0".$fechaArray[2];
		//Reensamblar la Fecha con los guiones dejandola con el formato aaaa-mm-dd
		$fecha=$fechaArray[0]."-".$fechaArray[1]."-".$fechaArray[2];
		$dia = strtolower(obtenerNombreDia2($fecha));
		//Sentencia SQL para extraer la checada de la tabla correspondiente
		$stm_sql = "SELECT T2 . $dia
					FROM  `nominas` AS T1
					JOIN  `detalle_nominas` AS T2
					USING (  `id_nomina` ) 
					WHERE  `fecha_inicio` <=  '$fecha'
					AND  `fecha_fin` >=  '$fecha'
					AND  `id_empleados_empresa` =  '$id_empleado'";
		//Ejecutar la sentencia SQL
		$rs=mysql_query($stm_sql);
		$estado="";
		$checada="";
		//$nombre=obtenerNombreEmpleado($rfc);
		//$fechaMostrar=modFecha($fecha,2);
		//Variables para controlar el color de Fondo y Letra en caso de haber o no, datos
		$color="";
		//Si la consulta regresa resultados, verificarlos
		if ($datos=mysql_fetch_array($rs)){
			$estado=$datos["".$dia];
			if ($estado=="A"){
				$a += 1;
				$color="background-color:#006600";
				//$checada="<input type='text' class='caja_de_num' size='1' readonly='readonly' value='$estado' style='font-size:19px;$color;border-width:0;'/>";
				$checada="$estado";
			}
			if ($estado=="F"){
				$f += 1;
				$color="background-color:#990000";
				$checada="$estado";
			}
			if ($estado=="I"){
				$i += 1;
				$color="background-color:#000099";
				$checada="$estado";
			}
			if ($estado=="B"){
				$al += 1;
				$color="background-color:#666666";
				$estado="AL";
				$checada="$estado";
			}
			//if ($estado=="A")
				//$color.=";color:#669900";
		}
		$fecha=str_replace("-","°",$fecha);
		
		//$checada="<input type='text' name='ckb_$fecha $ctrl' id='ckb_$fecha $ctrl' class='caja_de_num' size='1' readonly='readonly' onclick='asignarEstadoKardexArea(this);' value='$dia' style='font-size:19px;cursor:pointer;$color' title='Asignar la Incidencia del $fechaMostrar para $nombre'/>";
		return array($checada,$a,$f,$i,$al,$color);
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