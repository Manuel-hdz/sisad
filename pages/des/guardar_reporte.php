<?php 
	/**
	  * Nombre del Módulo: Desarrollo                                               
	  * Nombre Programador: Antonio de Jesús Jiménez Cuevas                            
	  * Fecha: 14/Noviembre/2011
	  * Descripción: Este archivo contiene funciones para almacenar la información en una hoja de calculo de excel de las consultas realizadas y reportes generados como lo son:
	  *	1. Reporte Nomina
	  *	2. Reporte Rezagado
	  *	3. Reporte Equipo Utilitario
	  *	4. Reporte Barrenacion con Jumbo
	  *	5. Reporte Barrenacion con Maquina de Pierna
	  *	6. Reporte Voladuras
	  *	7. Reporte Avance
	  *	8. Reporte Servicios con Minera Fresnillo
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
	
	if(isset($_POST['sbt_excel'])){
		if(isset($_POST['hdn_consulta'])){
		
			//Ubicacion de las imagenes que estan contenidas en los encabezados
			define("HOST", $_SERVER['HTTP_HOST']);
			//Obtener el nombre del Nombre de la Carpeta Raíz donde se encontrará almacenado el SISAD
			$raiz = explode("/",$_SERVER['PHP_SELF']);
			define("SISAD",$raiz[1]);
		
		
			switch($hdn_tipoReporte){
				case "exportarNomina":
					guardarRepNomina($hdn_consulta,$hdn_msje,$fecha_ini, $fecha_fin);
				break;
				case "reporteRezagado":
					guardarRepRezagadoExcel($hdn_consulta2,$hdn_msje,$fecha_ini,$fecha_fin);
				break;
				case "reporteUtilitario":
					guardarRepUtilitario($hdn_consulta,$hdn_msje);
				break;
				case "reporteBarrenacionJum":
					guardarRepBarrJumboExcel($hdn_consulta2,$hdn_msje,$fecha_ini, $fecha_fin);
				break;
				case "reporteBarrenacionMP":
					guardarRepBarrMP($hdn_consulta,$hdn_msje);
				break;
				case "reporteVoladuras":
					guardarRepVoladurasExcel($hdn_consulta2,$hdn_msje,$fecha_ini, $fecha_fin);
				break;
				case "reporteAvance";
					guardarRepAvance($hdn_consulta,$hdn_consultaMP,$hdn_msje);
				break;
				case "reporteServicios";
					guardarRepServicios($hdn_consulta,$hdn_msje);
				break;
				case "reporteBarrenacionAnc":
					guardarRepBarrAnc($hdn_consulta,$hdn_msje);
				break;
				case "reporteAyudante":
					guardarRepAyudanteExcel($hdn_consulta2,$hdn_msje,$fecha_ini, $fecha_fin);
				break;
				case "salidasDetalle":
					guardarRepSalidaDetalle($hdn_fechaI,$hdn_fechaF,$hdn_orden);
				break;
				case "reporte_requisiciones":
					guardarRepRequisiciones($hdn_consulta,$hdn_nomReporte,$hdn_msg);
				break;
				case "reporte_detallerequisiciones":
					guardarRepDetalleReq($hdn_consulta,$hdn_nomReporte,$hdn_msg);
				break;
			}				
		}
	}
	
	if(isset($_POST['sbt_tabla'])){
		if(isset($_POST['hdn_consulta'])){
		
			//Ubicacion de las imagenes que estan contenidas en los encabezados
			define("HOST", $_SERVER['HTTP_HOST']);
			//Obtener el nombre del Nombre de la Carpeta Raíz donde se encontrará almacenado el SISAD
			$raiz = explode("/",$_SERVER['PHP_SELF']);
			define("SISAD",$raiz[1]);
		
		
			switch($hdn_tipoReporte){
				case "reporteBarrenacionJum":
					guardarRepBarrJumbo($hdn_consulta,$hdn_msje);
				break;
				case "reporteRezagado":
					guardarRepRezagado($hdn_consulta,$hdn_msje);
				break;
				case "reporteVoladuras":
					guardarRepVoladuras($hdn_consulta,$hdn_msje);
				break;
				case "reporteAyudante":
					guardarRepAyudante($hdn_consulta,$hdn_consulta3,$hdn_msje);
				break;
			}				
		}
	}
	
	function guardarRepSalidaDetalle($fechaI,$fechaC,$orden){
		//Esribir datos de la pagina en excel
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=ReporteDetalleSalidas.xls");
		
		$cadena =  obtenerCentroCostos('DESARROLLO');
		
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
	
	//Esta funcion exporta la Nómina a un archivo de excel
	function guardarRepNomina($hdn_consulta,$hdn_msje,$fecha_ini,$fecha_fin){
		//Manejo de fechas
		include_once("../../includes/func_fechas.php");
		//Esribir datos de la pagina en excel
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=Nomina.xls");		
		//Realizar la conexion a la BD de Desarrollo
		$conn = conecta("bd_desarrollo");
		//Ejecutar la sentencia y almacena los datos de la consulta en la variable $rs (result set)
		$rs_datos = mysql_query($hdn_consulta);
			
		if($datos=mysql_fetch_array($rs_datos)){
			//Definir el estilo de la tabla y el encabezado que aparecera sobre la tabla?>
			<head>
				<style>					
					<!--
					body { font-family:Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; }
					/*En este formato  se establece el tamaño,tipo de letra,color de fondo que ocupa la columna princiaplal de una tebla*/
					.nombres_columnas { font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #FFFFFF; background-color: #9BBA59; font-weight: bold; border-top-width: medium; border-right-width: medium;
										border-bottom-width: medium; border-left-width: medium; border-top-style: solid; border-right-style: solid; border-bottom-style: solid; border-left-style: solid; 
										border-top-color: #000000; border-bottom-color: #000000;}
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
					/*En este formato  se establece el tamaño,tipo de letra,color de fondo que contendra datos  de una tabla*/
					.texto_totales {
						font-family: Calibri; font-size: 19px; color: #000000; background-color: #FFFFFF; font-weight: bold;
						text-decoration: underline; text-align: center; vertical-align: middle;
					}
					.cantidad_topes_total {
						font-family: Calibri; font-size: 16px; color: #000000; background-color: #E7E7E7; font-weight: bold;
						text-align: center; vertical-align: middle; border-style: solid; border-width: 1px;
					}
					.cantidad_total {
						font-family: Calibri; font-size: 16px; color: #000000; font-weight: normal;
						text-align: center; vertical-align: middle; border-style: solid; border-width: 1px;
					}
					-->
				</style>
			</head>
			<body>
			<div id="tabla">				
				<table width="1100">
					<?php //do{
						//if($cadena != $datos["nombre"]){?>
							<tr></tr>
							<tr>
								<td></td><td></td>
								<img src="http://<?php echo HOST; ?>/<?php echo SISAD; ?>/images/logo.png" width="250" height="100"/>
							</tr>
							<tr>
								<td></td><td></td><td></td>
								<td colspan="13" rowspan="2" class="cantidad_total" style="font-size: 25px; border-style: none; font-weight: bold;">
									NOMINA DE DESARROLLO UNIDAD FRESNILLO
								</td>
								<td></td>
								<td class="cantidad_total" style="border-style: none; font-weight: bold;" colspan="2">Semana:</td>
								<td style="border-bottom-style: solid; border-bottom-width: 1px;" colspan="2"><?php echo $fecha_ini." al ".$fecha_fin ?></td>
							</tr>
							<tr></tr>
							<tr>
								<td width="10"></td><td width="60"></td>
								<td width="150"></td><td width="100"></td><td width="50"></td>
								<td width="30"></td><td width="30"></td><td width="30"></td><td width="30"></td>
								<td width="30"></td><td width="30"></td><td width="30"></td><td width="30"></td>
								<td width="120"></td><td width="150"></td><td width="120"></td><td width="150"></td>
								<td width="50"></td><td width="50"></td><td width="50"></td><td width="200"></td>
							</tr>
							<tr></tr>
							<tr>
								<td></td>
								<td class="cantidad_topes_total">N°</td>
								<td colspan=3 class="cantidad_topes_total" style="font-size: 19px;">NOMBRE DEL COLABORADOR</td>
								<td class="cantidad_topes_total">J</td>
								<td class="cantidad_topes_total">V</td>
								<td class="cantidad_topes_total">S</td>
								<td class="cantidad_topes_total">D</td>
								<td class="cantidad_topes_total">L</td>
								<td class="cantidad_topes_total">M</td>
								<td class="cantidad_topes_total">M</td>
								<td class="cantidad_topes_total">E</td>
								<td class="cantidad_topes_total">SUELDO B.</td>
								<td class="cantidad_topes_total">SUELDO DIARIO</td>
								<td class="cantidad_topes_total">DESTAJO</td>
								<td class="cantidad_topes_total">TOTAL</td>
								<td class="cantidad_topes_total">HRS. EXTRA</td>
								<td class="cantidad_topes_total">G. 8HRS</td>
								<td class="cantidad_topes_total">G. 12HRS</td>
								<td class="cantidad_topes_total">COMENTARIOS</td>
							</tr>
					<?php //} 
						$totalsb = 0; 
						$totald = 0; 
						$totalt = 0;
						do{
							if($datos["horas_extra"] > 0) $e = "X"; else $e = "";
							$destajo = $datos["destajo"] + ($datos["horas_extra"] * ($datos["sueldo_diario"] / 8) * 2);
							if($datos["guarda_12hrs"] == 1) {
								$g12 = "X";
								$destajo += 500;
							}	else $g12 = "";
							if($datos["guarda_8hrs"] == 1) {
								$g8 = "X";
								$destajo += 350;
							}	else $g8 = "";
							$totalsb += $datos["sueldo_base"]; 
							$totald += $destajo;
							$totalt += $datos["total_pagado"]; ?>
							<tr>
								<td></td>
								<td class="cantidad_total" style="font-weight: bold;"><u><?php echo $datos["id_empleados_empresa"]; ?></u></td>
								<td colspan=3 class="cantidad_total" style="font-weight: bold; background-color:yellow;"><u><?php echo $datos["nombre_emp"]; ?></u></td>
								<td class="cantidad_total"><?php if($datos["jueves"]=="B") echo "<font style='color:darkred;'>AL</font>"; else echo $datos["jueves"]; ?></td>
								<td class="cantidad_total"><?php if($datos["viernes"]=="B") echo "<font style='color:darkred;'>AL</font>"; else echo $datos["viernes"]; ?></td>
								<td class="cantidad_total"><?php if($datos["sabado"]=="B") echo "<font style='color:darkred;'>AL</font>"; else echo $datos["sabado"]; ?></td>
								<td class="cantidad_total"><?php if($datos["domingo"]=="B") echo "<font style='color:darkred;'>AL</font>"; else echo $datos["domingo"]; ?></td>
								<td class="cantidad_total"><?php if($datos["lunes"]=="B") echo "<font style='color:darkred;'>AL</font>"; else echo $datos["lunes"]; ?></td>
								<td class="cantidad_total"><?php if($datos["martes"]=="B") echo "<font style='color:darkred;'>AL</font>"; else echo $datos["martes"]; ?></td>
								<td class="cantidad_total"><?php if($datos["miercoles"]=="B") echo "<font style='color:darkred;'>AL</font>"; else echo $datos["miercoles"]; ?></td>
								<td class="cantidad_total"><?php echo $e; ?></td>
								<td class="cantidad_total" style="background-color:yellow;"><?php echo $datos["sueldo_base"]; ?></td>
								<td class="cantidad_total" style="font-weight: bold;"><u><?php echo $datos["sueldo_diario"]; ?></u></td>
								<td class="cantidad_total" style="mso-number-format:'Currency';"><?php echo $destajo; ?></td>
								<td class="cantidad_total" style="mso-number-format:'Currency'; background-color:yellow;"><?php echo $datos["total_pagado"]; ?></td>
								<td class="cantidad_total"><?php echo $datos["horas_extra"]; ?></td>
								<td class="cantidad_total"><?php echo $g8; ?></td>
								<td class="cantidad_total"><?php echo $g12; ?></td>
								<td class="cantidad_total"><?php echo $datos["comentarios"]; ?></td>
							</tr>
					<?php } while($datos=mysql_fetch_array($rs_datos));?>
							<tr>
								<td></td>
								<td class="cantidad_total"></td>
								<td colspan=3 class="cantidad_total"></td>
								<td class="cantidad_total"></td>
								<td class="cantidad_total"></td>
								<td class="cantidad_total"></td>
								<td class="cantidad_total"></td>
								<td class="cantidad_total"></td>
								<td class="cantidad_total"></td>
								<td class="cantidad_total"></td>
								<td class="cantidad_total"></td>
								<td class="cantidad_total"></td>
								<td class="cantidad_total"></td>
								<td class="cantidad_total"></td>
								<td class="cantidad_total"></td>
								<td class="cantidad_total"></td>
							</tr>
					<?php 
						//if($datos["nombre"] != $datos2["nombre"]){?>
							<tr>
								<td></td><td></td>
								<td colspan=10 class="cantidad_total" style="font-weight: bold;"><u>TOTAL</u></td>
								<td></td>
								<td class="cantidad_total" style="font-weight: bold;"><u><?php echo $totalsb; ?></u></td>
								<td></td>
								<td class="cantidad_total" style="font-weight: bold; mso-number-format:'Currency';"><u><?php echo $totald; ?></u></td>
								<td class="cantidad_total" style="background-color:#33CC66; font-weight: bold; mso-number-format:'Currency';"><u><?php echo $totalt; ?></u></td>
							</tr>
					<?php //}
					//$cadena = $datos["nombre"];
					//$datos2=mysql_fetch_array($rs_datos2);
					//}while($datos=mysql_fetch_array($rs_datos)); ?>
			</table>
			</div>
			</body>
<?php	}
		//Cerrar la conexion con la BD		
		mysql_close($conn);		
	}//Fin de la Funcion guardarRepNomina($hdn_consulta,$hdn_nomReporte)

	//Esta funcion exporta el Reporte de Rezagado a un archivo de excel
	function guardarRepRezagadoExcel($hdn_consulta,$hdn_msje,$fecha_ini,$fecha_fin){
		//Manejo de fechas
		include_once("../../includes/func_fechas.php");
		//Esribir datos de la pagina en excel
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=Tarjetas_Rezagado.xls");		
		//Realizar la conexion a la BD de Desarrollo
		$conn = conecta("bd_desarrollo");
		//Ejecutar la sentencia y almacena los datos de la consulta en la variable $rs (result set)
		$rs_datos = mysql_query($hdn_consulta);
		$rs_datos2 = mysql_query($hdn_consulta);
		$datos2=mysql_fetch_array($rs_datos2);
		$cadena = "";	
		if($datos=mysql_fetch_array($rs_datos)){
			$datos2=mysql_fetch_array($rs_datos2);
			//Definir el estilo de la tabla y el encabezado que aparecera sobre la tabla?>
			<head>
				<style>					
					<!--
					body { font-family:Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; }
					/*En este formato  se establece el tamaño,tipo de letra,color de fondo que ocupa la columna princiaplal de una tebla*/
					.nombres_columnas { font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #FFFFFF; background-color: #9BBA59; font-weight: bold; border-top-width: medium; border-right-width: medium;
										border-bottom-width: medium; border-left-width: medium; border-top-style: solid; border-right-style: solid; border-bottom-style: solid; border-left-style: solid; 
										border-top-color: #000000; border-bottom-color: #000000;}
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
					/*En este formato  se establece el tamaño,tipo de letra,color de fondo que contendra datos  de una tabla*/
					.texto_totales {
						font-family: Calibri; font-size: 19px; color: #000000; background-color: #FFFFFF; font-weight: bold;
						text-decoration: underline; text-align: center; vertical-align: middle;
					}
					.cantidad_topes_total {
						font-family: Calibri; font-size: 19px; color: #000000; background-color: #E7E7E7; font-weight: bold;
						text-decoration: underline; text-align: center; vertical-align: middle; border-style: solid; border-width: 1px;
					}
					.cantidad_total {
						font-family: Calibri; font-size: 16px; color: #000000; font-weight: bold;
						text-align: center; vertical-align: middle; border-style: solid; border-width: 1px;
					}
					-->
				</style>
			</head>
			<body>
			<div id="tabla">				
				<table width="1100" style="border-style: solid; border-width: 20px;">
					<?php 
						$cuch = 0; $topes = 0; $tras_cuch= 0;
						$totalc = 0; $total_topes = 0; $total_cucharones = 0;
						$obsv = "";
						do{
						if($cadena != $datos["nombre_emp"]){
							//$nombre_empl = obtenerDatoRecHum("CONCAT(nombre,' ',ape_pat,' ',ape_mat) AS nombre", "id_empleados_empresa", $datos["nombre"]);?>
							<tr></tr>
							<tr>
								<td></td><td></td>
								<img src="http://<?php echo HOST; ?>/<?php echo SISAD; ?>/images/logo.png" width="250" height="100"/>
							</tr>
							<tr>
								<td></td><td></td><td></td><td></td><td></td><td></td>
								<td colspan="6" rowspan="3" class="cantidad_total" style="font-size: 25px;"><u>TARJETA DE OP. SCOOP TRAM</u></td>
							</tr>
							<tr></tr><tr></tr><tr></tr>
							<tr height="6"></tr>
							<tr>
								<td></td><td></td>
								<td valign="middle" align="center"><font face="Calibri">Nº DE EMPLEADO:</font></td>
								<td></td>
								<td colspan="4" class="texto_totales" style="background-color:#99CC33;"><?php echo $datos["nombre_emp"];?></td>
							</tr>
							<tr height="6"></tr>
							<tr>
								<td width="10"></td><td width="10"></td><td width="140"></td><td width="20"></td>
						
								<td width="140" align="center"><font face="Calibri"><strong>LUGAR DE TRABAJO</strong></font></td>
								<td width="100" align="center"><font face="Calibri"><strong>TOTAL DE CUCHARONES</strong></font></td>
								<td width="100" align="center"><font face="Calibri"><strong>TOPES REZAGADOS</strong></font></td>
								<td width="100" align="center"><font face="Calibri"><strong>CHUCHARON TRASPALEO</strong></font></td>
								<td colspan="4" align="center"><font face="Calibri"><strong>OBSERVACIONES</strong></font></td>
								<td width="10"></td><td width="10"></td>
							</tr>
					<?php } ?>
					<?php if($datos["nombre_emp"] == $datos2["nombre_emp"] && $datos["fecha"] == $datos2["fecha"]) {
								$cuch += $datos["cuch"]; 
								if($datos["tope_limpio"] == 1){
									$topes++;
								}
								if($datos["traspaleo"] == 1){
									$tras_cuch += $datos["cuch"]; 
								}
								if($datos["observaciones"] != ""){
									$obsv .= "*".$datos["observaciones"];
								}
						} else { 
								$cuch += $datos["cuch"]; 
								if($datos["tope_limpio"] == 1){
									$topes++;
								}
								if($datos["traspaleo"] == 1){
									$tras_cuch += $datos["cuch"]; 
								}
								if($datos["observaciones"] != ""){
									$obsv .= $datos["observaciones"];
								} ?>
							<tr height='6'></tr>
							<tr>
								<td></td><td></td>
								<td class="cantidad_total"><?php echo modFecha($datos["fecha"],8);?></td>
								<td></td>
								<td class="cantidad_total" style='font-weight: normal;'><?php echo $datos["obra"];?></td>
								<td class="cantidad_total" style='font-weight: normal;'><?php echo $cuch;?></td>
								<td class="cantidad_total" style='font-weight: normal;'><?php echo $topes;?></td>
								<td class="cantidad_total" style='font-weight: normal;'><?php echo $tras_cuch;?></td>
								<td class="cantidad_total" style='font-weight: normal;' colspan="4"><?php echo $obsv;?></td>
							</tr>
					<?php
						$totalc += $cuch;
						$total_topes += $topes;
						$total_cucharones += $tras_cuch;
						$cuch = 0; $topes = 0; $tras_cuch= 0; $obsv = "";
					} ?>
					<?php
						if($datos["nombre_emp"] != $datos2["nombre_emp"]){ ?>
							<tr height="6"></tr>
							<tr>
								<td width="10"></td><td width="10"></td>
								<td colspan="3" class="texto_totales">TOTAL DE TOPES</td>
								<td class="cantidad_topes_total"><?php echo $totalc;?></td>
								<td class="cantidad_topes_total"><?php echo $total_topes;?></td>
								<td class="cantidad_topes_total"><?php echo $total_cucharones;?></td>
								<td width="10"></td>
								<td align="center" width="80"><font face="Calibri"><strong><u>EXTRAS</u></strong></font></td><td width="10"></td><td width="150"></td>
							</tr>
							<tr height="6"></tr>
							<tr>
								<td></td><td></td><td></td><td></td>
								<td class="texto_totales">SUELDO BASE</td>
								<td></td>
								<?php $sb = obtenerExtras_SD($fecha_ini, $fecha_fin, $datos["nombre"],"sueldo_base"); ?>
								<td colspan="2" class="cantidad_total" style="mso-number-format:'Currency';"><?php echo $sb;?></td>
								<td></td>
								<?php $extra = obtenerExtras_SD($fecha_ini, $fecha_fin, $datos["nombre"],"extra"); ?>
								<td class="cantidad_total" style="font-weight: normal; mso-number-format:'Currency'">0</td>
								<td></td>
								<td class="cantidad_total" style='font-weight: normal;'></td>
							</tr>
							<tr height="6"></tr>
							<tr>
								<td></td><td></td><td></td><td></td>
								<td class="texto_totales">DESTAJO</td>
								<td></td>
								<?php 
									$tope = obtenerDatoBonificacion($datos["puesto"], "SCOOP", "TOPE REZAGADO");
									$cucharon = obtenerDatoBonificacion($datos["puesto"], "SCOOP", "CUCHARON TRASPALEO");
									$destajo = ($tope * $total_topes) + ($cucharon * $total_cucharones);
								?>
								<td colspan="2" style="background-color:yellow; mso-number-format:'Currency';" class="cantidad_total"><?php echo $destajo;?></td>
							</tr>
							<tr height="6"></tr>
							<tr>
								<td></td><td></td><td></td><td></td>
								<td class="texto_totales">GRAN TOTAL</td>
								<td></td>
								<?php
									$total = $sb + $destajo;
								?>
								<td colspan="2" style="background-color:#33CC66; font-size: 19px; mso-number-format:'Currency';" class="cantidad_total"><?php echo $total;?></td>
							</tr>
							<tr height="6"></tr>
							<tr height="6">
								<td style="border-style: solid; border-width: 20px; background-color:#000000;"></td>
								<td style="border-style: solid; border-width: 20px; background-color:#000000;"></td>
								<td style="border-style: solid; border-width: 20px; background-color:#000000;"></td>
								<td style="border-style: solid; border-width: 20px; background-color:#000000;"></td>
								<td style="border-style: solid; border-width: 20px; background-color:#000000;"></td>
								<td style="border-style: solid; border-width: 20px; background-color:#000000;"></td>
								<td style="border-style: solid; border-width: 20px; background-color:#000000;"></td>
								<td style="border-style: solid; border-width: 20px; background-color:#000000;"></td>
								<td style="border-style: solid; border-width: 20px; background-color:#000000;"></td>
								<td style="border-style: solid; border-width: 20px; background-color:#000000;"></td>
								<td style="border-style: solid; border-width: 20px; background-color:#000000;"></td>
								<td style="border-style: solid; border-width: 20px; background-color:#000000;"></td>
								<td style="border-style: solid; border-width: 20px; background-color:#000000;"></td>
								<td style="border-style: solid; border-width: 20px; background-color:#000000;"></td>
							</tr>
					<?php 
							$totalc = 0;
							$total_topes = 0;
							$total_cucharones = 0;
						}
					$cadena = $datos["nombre_emp"];
					$datos2=mysql_fetch_array($rs_datos2);
					}while($datos=mysql_fetch_array($rs_datos)); ?>
			</table>
			</div>
			</body>
<?php	}
}//Fin de la Funcion guardarRepRezagadoExcel($hdn_consulta,$hdn_nomReporte)

	//Esta funcion exporta el Reporte de Rezagado a un archivo de excel
	function guardarRepRezagado($hdn_consulta,$hdn_msje){
		//Manejo de fechas
		include_once("../../includes/func_fechas.php");
		//Esribir datos de la pagina en excel
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=Reporte_Rezagado.xls");		
		//Realizar la conexion a la BD de Desarrollo
		$conn = conecta("bd_desarrollo");
		//Ejecutar la sentencia y almacena los datos de la consulta en la variable $rs (result set)
		$rs_datos = mysql_query($hdn_consulta);
			
		if($datos=mysql_fetch_array($rs_datos)){
			//Definir el estilo de la tabla y el encabezado que aparecera sobre la tabla?>
			<head>
				<style>					
					<!--
					body { font-family:Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; }
					/*En este formato  se establece el tamaño,tipo de letra,color de fondo que ocupa la columna princiaplal de una tebla*/
					.nombres_columnas { font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #FFFFFF; background-color: #9BBA59; font-weight: bold; border-top-width: medium; border-right-width: medium;
										border-bottom-width: medium; border-left-width: medium; border-top-style: solid; border-right-style: solid; border-bottom-style: solid; border-left-style: solid; 
										border-top-color: #000000; border-bottom-color: #000000;}
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
						<td align="left" valign="baseline" colspan="3"><img src="http://<?php echo HOST; ?>/<?php echo SISAD; ?>/images/logo.png" width="118" height="58" align="absbottom" /></td>
						<td colspan="14">&nbsp;</td>
						<td valign="baseline" colspan="3">
							<div align="right"><span class="texto_encabezado">
								<strong>MANUAL DE PROCEDIMIENTOS DE LA CALIDAD</strong><br><em>CONCRETO LANZADO DE FRESNILLO, S.A DE C.V.</em>
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
						<td colspan="20" align="center" class="titulo_tabla"><?php echo $hdn_msje;?></td>
					</tr>
					<tr>
						<td colspan="20">&nbsp;</td>
					</tr>			
					<tr>						
						<td align="center" class="nombres_columnas" rowspan="2">FECHA</td>						
        				<td align="center" class="nombres_columnas" rowspan="2">TURNO</td>
        				<td align="center" class="nombres_columnas" rowspan="2">PUESTO</td>
						<td align="center" class="nombres_columnas" rowspan="2">NOMBRE</td>
						<td align="center" class="nombres_columnas" rowspan="2">EQUIPO</td>
						<td align="center" class="nombres_columnas" colspan="3">HOR&Oacute;METRO</td>
						<td align="center" class="nombres_columnas" colspan="4">AVANCE</td>
						<th class='nombres_columnas' align='center' rowspan='2'>ORIGEN</th>
						<th class='nombres_columnas' align='center' rowspan='2'>DESTINO</th>
						<th class='nombres_columnas' align='center' rowspan='2'>NO.<br>CUCHARONES</th>
						<th class='nombres_columnas' align='center' rowspan='2'>TRASPALEO</th>
						<th class='nombres_columnas' align='center' rowspan='2'>TOPE LIMPIO</th>
						<td align="center" class="nombres_columnas" colspan="2">OBSERVACIONES</td>
      				</tr>
					<tr>						
						<td align="center" class="nombres_columnas">INICIAL</td>						
        				<td align="center" class="nombres_columnas">FINAL</td>
        				<td align="center" class="nombres_columnas">HORAS<br>TOTALES</td>
						
						<th class='nombres_columnas' align='center'>OBRA</th>
						<th class='nombres_columnas' align='center'>MACHOTE</th>
						<th class='nombres_columnas' align='center'>MEDIDA</th>
						<th class='nombres_columnas' align='center'>AVANCE</th>
						
						<td align="center" class="nombres_columnas">REZAGADO</td>
						<td align="center" class="nombres_columnas">AVANCE</td>
      				</tr>
			<?php
			$nom_clase = "renglon_gris";
			$cont = 1;
			$cant_total = 0;
						
			do{?>
					<tr>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo modFecha($datos["fecha"],1)?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos["turno"]; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos["puesto"]; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos["nombre_emp"]; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos["id_equipo"]; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos["horo_ini"];?></td>												
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos["horo_fin"]; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos["horas_totales"]; ?></td>
						
						<td class='<?php echo $nom_clase?>' align='center'><?php echo $datos["obra"]?></td>
						<td class='<?php echo $nom_clase?>' align='center'><?php echo $datos["machote"]?></td>
						<td class='<?php echo $nom_clase?>' align='center'><?php echo $datos["medida"]?></td>
						<td class='<?php echo $nom_clase?>' align='center'><?php echo $datos["avance"]?></td>
				
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos["origen"]; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos["destino"]; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos["cuch"]; ?></td>
						
				<?php if($datos["traspaleo"] == 0){ ?>
							<td class="<?php echo $nom_clase; ?>" align='center'></td>
				<?php } else { ?>
							<td class="<?php echo $nom_clase; ?>" align='center'>X</td>
				<?php }
					  if($datos["tope_limpio"] == 0){ ?>
							<td class="<?php echo $nom_clase; ?>" align='center'></td>
				<?php } else { ?>
							<td class="<?php echo $nom_clase; ?>" align='center'>X</td>
				<?php } ?>
						
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos["obsRez"]; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos["obsAva"]; ?></td>
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
	}//Fin de la Funcion guardarRepRezagado($hdn_consulta,$hdn_nomReporte)
	
	//Esta funcion exporta el Reporte de Barrenacion Anclas a un archivo de excel
	function guardarRepBarrAnc($hdn_consulta,$hdn_msje){
		//Manejo de fechas
		include_once("../../includes/func_fechas.php");
		//Esribir datos de la pagina en excel
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=Reporte_BarrenacionAnclas.xls");		
		//Realizar la conexion a la BD de Desarrollo
		$conn = conecta("bd_desarrollo");
		//Ejecutar la sentencia y almacena los datos de la consulta en la variable $rs (result set)
		$rs_datos = mysql_query($hdn_consulta);
		$rs_datos2 = mysql_query($hdn_consulta);
		$datos2=mysql_fetch_array($rs_datos2);
		$cadena = "";	
		if($datos=mysql_fetch_array($rs_datos)){
			$datos2=mysql_fetch_array($rs_datos2);
			//Definir el estilo de la tabla y el encabezado que aparecera sobre la tabla?>
			<head>
				<style>					
					<!--
					body { font-family:Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; }
					/*En este formato  se establece el tamaño,tipo de letra,color de fondo que ocupa la columna princiaplal de una tebla*/
					.nombres_columnas { font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #FFFFFF; background-color: #9BBA59; font-weight: bold; border-top-width: medium; border-right-width: medium;
										border-bottom-width: medium; border-left-width: medium; border-top-style: solid; border-right-style: solid; border-bottom-style: solid; border-left-style: solid; 
										border-top-color: #000000; border-bottom-color: #000000;}
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
					/*En este formato  se establece el tamaño,tipo de letra,color de fondo que contendra datos  de una tabla*/
					.texto_totales {
						font-family: Calibri; font-size: 19px; color: #000000; background-color: #FFFFFF; font-weight: bold;
						text-decoration: underline; text-align: center; vertical-align: middle;
					}
					.cantidad_topes_total {
						font-family: Calibri; font-size: 19px; color: #000000; background-color: #E7E7E7; font-weight: bold;
						text-decoration: underline; text-align: center; vertical-align: middle; border-style: solid; border-width: 1px;
					}
					.cantidad_total {
						font-family: Calibri; font-size: 16px; color: #000000; font-weight: bold;
						text-align: center; vertical-align: middle; border-style: solid; border-width: 1px;
					}
					-->
				</style>
			</head>
			<body>
			<div id="tabla">				
				<table width="1100" style="border-style: solid; border-width: 20px;">
					<?php //do{
						//if($cadena != $datos["nombre"]){?>
							<tr></tr>
							<tr>
								<td></td><td></td>
								<img src="http://<?php echo HOST; ?>/<?php echo SISAD; ?>/images/logo.png" width="250" height="100"/>
							</tr>
							<tr>
								<td></td><td></td><td></td><td></td><td></td><td></td>
								<td colspan="3" rowspan="3" class="cantidad_total" style="font-size: 25px;"><u>TARJETA DE ANCLADOR</u></td>
							</tr>
							<tr></tr><tr></tr><tr></tr>
							<tr height="6"></tr>
							<tr>
								<td></td><td></td>
								<td valign="middle" align="center"><font face="Calibri">Nº DE EMPLEADO:</font></td>
								<td></td>
								<td colspan="4" class="texto_totales" style="background-color:#99CC33;">NOMBRE EMPLEADO</td>
							</tr>
							<tr height="6"></tr>
							<tr>
								<td width="10"></td><td width="10"></td><td width="140"></td><td width="20"></td>
						
								<td width="140" align="center"><font face="Calibri"><strong>LUGAR DE TRABAJO</strong></font></td>
								<td width="100" align="center"><font face="Calibri"><strong>ANCLAS COLOCADAS</strong></font></td>
								<td width="100" align="center"><font face="Calibri"><strong>EXTRA</strong></font></td>
								<td width="220" align="center"><font face="Calibri"><strong>OBSERVACIONES</strong></font></td>
								<td width="10"></td><td width="10"></td>
							</tr>
					<?php //} ?>
							<tr height='6'></tr>
							<tr>
								<td></td><td></td>
								<td class="cantidad_total">FECHA</td>
								<td></td>
								<td class="cantidad_total" style='font-weight: normal;'>dato BD</td>
								<td class="cantidad_total" style='font-weight: normal;'>dato BD</td>
								<td class="cantidad_total" style='font-weight: normal;'>dato BD</td>
								<td class="cantidad_total" style='font-weight: normal;'>dato BD</td>
							</tr>
					<?php
						//if($datos["nombre"] != $datos2["nombre"]){?>
							<tr height="6"></tr>
							<tr>
								<td width="10"></td><td width="10"></td>
								<td colspan="3" class="texto_totales">TOTAL DE ANCLAS</td>
								<td class="cantidad_topes_total" colspan="2">dato BD</td>
							</tr>
							<tr height="6"></tr>
							<tr>
								<td></td><td></td><td></td><td></td>
								<td class="texto_totales">SUELDO BASE</td>
								<td colspan="2" class="cantidad_total" style="mso-number-format:'Currency';">SUELDO BASE</td>
							</tr>
							<tr height="6"></tr>
							<tr>
								<td></td><td></td><td></td><td></td>
								<td class="texto_totales">DESTAJO</td>
								<td colspan="2" style="background-color:yellow" class="cantidad_total">cantidad DESTAJO</td>
							</tr>
							<tr height="6"></tr>
							<tr>
								<td></td><td></td><td></td><td></td>
								<td class="texto_totales">TOTAL</td>
								<td colspan="2" style="background-color:#33CC66; font-size: 19px;" class="cantidad_total">cantidad DESTAJO</td>
							</tr>
							<tr height="6"></tr>
							<tr height="6">
								<td style="border-style: solid; border-width: 20px; background-color:#000000;"></td>
								<td style="border-style: solid; border-width: 20px; background-color:#000000;"></td>
								<td style="border-style: solid; border-width: 20px; background-color:#000000;"></td>
								<td style="border-style: solid; border-width: 20px; background-color:#000000;"></td>
								<td style="border-style: solid; border-width: 20px; background-color:#000000;"></td>
								<td style="border-style: solid; border-width: 20px; background-color:#000000;"></td>
								<td style="border-style: solid; border-width: 20px; background-color:#000000;"></td>
								<td style="border-style: solid; border-width: 20px; background-color:#000000;"></td>
								<td style="border-style: solid; border-width: 20px; background-color:#000000;"></td>
								<td style="border-style: solid; border-width: 20px; background-color:#000000;"></td>
							</tr>
					<?php //}
					//$cadena = $datos["nombre"];
					//$datos2=mysql_fetch_array($rs_datos2);
					//}while($datos=mysql_fetch_array($rs_datos)); ?>
			</table>
			</div>
			</body>
<?php	}
}//Fin de la Funcion guardarRepBarrAnc($hdn_consulta,$hdn_nomReporte)
	
	//Esta funcion exporta el Reporte de Ayudante General a un archivo de excel
	function guardarRepAyudanteExcel($hdn_consulta,$hdn_msje,$fecha_ini,$fecha_fin){
		//Manejo de fechas
		include_once("../../includes/func_fechas.php");
		//Esribir datos de la pagina en excel
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=Tarjetas_AyudanteGral.xls");		
		//Realizar la conexion a la BD de Desarrollo
		$conn = conecta("bd_desarrollo");
		//Ejecutar la sentencia y almacena los datos de la consulta en la variable $rs (result set)
		$rs_datos = mysql_query($hdn_consulta);
		$rs_datos2 = mysql_query($hdn_consulta);
		$datos2=mysql_fetch_array($rs_datos2);
		$cadena = "";	
		if($datos=mysql_fetch_array($rs_datos)){
			$datos2=mysql_fetch_array($rs_datos2);
			//Definir el estilo de la tabla y el encabezado que aparecera sobre la tabla?>
			<head>
				<style>					
					<!--
					body { font-family:Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; }
					/*En este formato  se establece el tamaño,tipo de letra,color de fondo que ocupa la columna princiaplal de una tebla*/
					.nombres_columnas { font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #FFFFFF; background-color: #9BBA59; font-weight: bold; border-top-width: medium; border-right-width: medium;
										border-bottom-width: medium; border-left-width: medium; border-top-style: solid; border-right-style: solid; border-bottom-style: solid; border-left-style: solid; 
										border-top-color: #000000; border-bottom-color: #000000;}
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
					/*En este formato  se establece el tamaño,tipo de letra,color de fondo que contendra datos  de una tabla*/
					.texto_totales {
						font-family: Calibri; font-size: 19px; color: #000000; background-color: #FFFFFF; font-weight: bold;
						text-decoration: underline; text-align: center; vertical-align: middle;
					}
					.cantidad_topes_total {
						font-family: Calibri; font-size: 19px; color: #000000; background-color: #E7E7E7; font-weight: bold;
						text-decoration: underline; text-align: center; vertical-align: middle; border-style: solid; border-width: 1px;
					}
					.cantidad_total {
						font-family: Calibri; font-size: 16px; color: #000000; font-weight: bold;
						text-align: center; vertical-align: middle; border-style: solid; border-width: 1px;
					}
					-->
				</style>
			</head>
			<body>
			<div id="tabla">				
				<table width="1100" style="border-style: solid; border-width: 20px;">
					<?php 
					$total_tb = 0; $total_tc = 0; $total_anc = 0;
					$total_avance1 = 0; $total_avance2 = 0;
					$topes_barrenados = 0; $topes_cargados = 0; $anclas = 0;
					$avance1 = 0; $avance2 = 0;
					do{
						if($cadena != $datos["nombre_emp"]){?>
							<tr></tr>
							<tr>
								<td></td><td></td>
								<img src="http://<?php echo HOST; ?>/<?php echo SISAD; ?>/images/logo.png" width="250" height="100"/>
							</tr>
							<tr>
								<td></td><td></td><td></td><td></td><td></td><td></td>
								<td colspan="8" rowspan="3" class="cantidad_total" style="font-size: 25px;"><u>TARJETA DE AYUDANTE GENERAL</u></td>
							</tr>
							<tr></tr><tr></tr><tr></tr>
							<tr height="6"></tr>
							<tr>
								<td></td><td></td>
								<td valign="middle" align="center"><font face="Calibri">Nº DE EMPLEADO:</font></td>
								<td></td>
								<td colspan="5" class="texto_totales" style="background-color:#99CC33;"><?php echo $datos["nombre_emp"]; ?></td>
							</tr>
							<tr height="6"></tr>
							<tr>
								<td width="10"></td><td width="10"></td><td width="140"></td><td width="20"></td>
						
								<td width="140" align="center"><font face="Calibri"><strong>LUGAR DE TRABAJO</strong></font></td>
								<td width="110" align="center"><font face="Calibri"><strong>TOPES BARRENADOS</strong></font></td>
								<td width="90" align="center"><font face="Calibri"><strong>AVANCE</strong></font></td><td width="10"></td>
								<td width="100" align="center"><font face="Calibri"><strong>TOPES DISPARADOS</strong></font></td>
								<td width="90" align="center"><font face="Calibri"><strong>AVANCE</strong></font></td><td width="10"></td>
								<td width="100" align="center"><font face="Calibri"><strong>ANCLAS INSTALADAS</strong></font></td>
								<td align="center" colspan="4"><font face="Calibri"><strong>OBSERVACIONES</strong></font></td>
								<td width="10"></td><td width="10"></td>
							</tr>
					<?php } if($datos["nombre_emp"] != $datos2["nombre_emp"] || $datos["fecha_registro"] != $datos2["fecha_registro"]) { 
								$topes_barrenados += obtenerDatoSumaBD("topes_barrenados","barrenacion_jumbo","bitacora_avance_id_bitacora",$datos["id_bitacora"],$datos["fecha_registro"],$datos["nombre"],"JUMBO");
								$topes_cargados += obtenerDatoSumaBD("topes_cargados","voladuras","bitacora_avance_id_bitacora",$datos["id_bitacora"],$datos["fecha_registro"],$datos["nombre"],"VOLADURAS");
								$anclas += obtenerDatoSumaBD("anclas","barrenacion_jumbo","bitacora_avance_id_bitacora",$datos["id_bitacora"],$datos["fecha_registro"],$datos["nombre"],"JUMBO");
								$avance1 += obtenerDatoSumaBD("avance","barrenacion_jumbo","bitacora_avance_id_bitacora",$datos["id_bitacora"],$datos["fecha_registro"],$datos["nombre"],"JUMBO");
								$avance2 += obtenerDatoSumaBD("avance","voladuras","bitacora_avance_id_bitacora",$datos["id_bitacora"],$datos["fecha_registro"],$datos["nombre"],"VOLADURAS");
					?>
							<tr height='6'></tr>
							<tr>
								<td></td><td></td>
								<td class="cantidad_total"><?php echo modFecha($datos["fecha_registro"],8);?></td>
								<td></td>
								<td class="cantidad_total" style='font-weight: normal;'><?php echo $datos["obra"]; ?></td>
								<td class="cantidad_total" style='font-weight: normal;'><?php if($topes_barrenados > 0) echo  $topes_barrenados; ?></td>
								<td class="cantidad_total" style='font-weight: normal;'><?php if($topes_barrenados > 0) echo $avance1; ?></td><td></td>
								<td class="cantidad_total" style='font-weight: normal;'><?php if($topes_cargados > 0) echo  $topes_cargados; ?></td>
								<td class="cantidad_total" style='font-weight: normal;'><?php if($topes_cargados > 0) echo $avance2; ?></td><td></td>
								<td class="cantidad_total" style='font-weight: normal;'><?php echo  $anclas; ?></td>
								<td class="cantidad_total" style='font-weight: normal;' colspan="4"><?php echo $datos["observaciones"]; ?></td>
							</tr>
					<?php
							$total_tb += $topes_barrenados; $total_tc += $topes_cargados; $total_anc += $anclas;
							if($topes_barrenados > 0)
								$total_avance1 += $avance1;
							if($topes_cargados > 0)
								$total_avance2 += $avance2;
								
							$topes_barrenados = 0; $topes_cargados = 0; $anclas = 0;
							$avance1 = 0; $avance2 = 0;
						} else if($datos["id_bitacora"] != $datos2["id_bitacora"]){
								$topes_barrenados += obtenerDatoSumaBD("topes_barrenados","barrenacion_jumbo","bitacora_avance_id_bitacora",$datos["id_bitacora"],$datos["fecha_registro"],$datos["nombre"],"JUMBO");
								$topes_cargados += obtenerDatoSumaBD("topes_cargados","voladuras","bitacora_avance_id_bitacora",$datos["id_bitacora"],$datos["fecha_registro"],$datos["nombre"],"VOLADURAS");
								$anclas += obtenerDatoSumaBD("anclas","barrenacion_jumbo","bitacora_avance_id_bitacora",$datos["id_bitacora"],$datos["fecha_registro"],$datos["nombre"],"JUMBO");
								$avance1 += obtenerDatoSumaBD("avance","barrenacion_jumbo","bitacora_avance_id_bitacora",$datos["id_bitacora"],$datos["fecha_registro"],$datos["nombre"],"JUMBO");
								$avance2 += obtenerDatoSumaBD("avance","voladuras","bitacora_avance_id_bitacora",$datos["id_bitacora"],$datos["fecha_registro"],$datos["nombre"],"VOLADURAS");
						}
						if($datos["nombre_emp"] != $datos2["nombre_emp"]){?>
							<tr height="6"></tr>
							<tr>
								<td width="10"></td><td width="10"></td>
								<td colspan="3" class="texto_totales">TOTAL DE METROS</td>
								<td class="cantidad_topes_total"><?php if($total_tb > 0) echo  $total_tb; else echo 0; ?></td>
								<td class="cantidad_topes_total"><?php if($total_avance1 > 0) echo  $total_avance1; else echo 0; ?></td><td></td>
								<td class="cantidad_topes_total"><?php if($total_tc > 0) echo  $total_tc; else echo 0; ?></td>
								<td class="cantidad_topes_total"><?php if($total_avance2 > 0) echo  $total_avance2; else echo 0; ?></td><td></td>
								<td class="cantidad_topes_total"><?php if($total_anc > 0) echo  $total_anc; else echo 0; ?></td>
							</tr>
							<tr height="6"></tr>
							<?php 
								$destajo1 = obtenerDatoBonificacion($datos["puesto"], "JUMBO", "AVANCE") * $total_avance1;
								$destajo2 = obtenerDatoBonificacion($datos["puesto"], "VOLADURAS", "AVANCE") * $total_avance2;
								$destajo3 = obtenerDatoBonificacion($datos["puesto"], "JUMBO", "ANCLAS") * $total_anc;
							?>
							<tr>
								<td></td><td></td><td></td><td></td>
								<td class="texto_totales">DESTAJO</td>
								<td colspan="2" class="cantidad_total" style="mso-number-format:'Currency'; background-color:yellow;"><?php echo  $destajo1; ?></td><td></td>
								<td colspan="2" class="cantidad_total" style="mso-number-format:'Currency'; background-color:yellow;"><?php echo  $destajo2; ?></td><td></td>
								<td class="cantidad_total" style="mso-number-format:'Currency'; background-color:yellow;"><?php echo  $destajo3; ?></td>
								<td width="10"></td>
								<td align="center" width="80"><font face="Calibri"><strong><u>EXTRAS</u></strong></font></td><td width="10"></td><td width="150"></td>
							</tr>
							<tr height="6"></tr>
							<tr>
								<td></td><td></td><td></td><td></td>
								<td class="texto_totales">SUELDO BASE</td>
								<?php $sb = obtenerExtras_SD($fecha_ini, $fecha_fin, $datos["nombre"],"sueldo_base"); ?>
								<td colspan="7" class="cantidad_total" style="mso-number-format:'Currency';"><?php echo  $sb; ?></td>
								<td></td>
								<?php $extra = obtenerExtras_SD($fecha_ini, $fecha_fin, $datos["nombre"],"extra"); ?>
								<td class="cantidad_total" style="font-weight: normal; mso-number-format:'Currency'">0</td>
								<td></td>
								<td class="cantidad_total" style='font-weight: normal;'></td>
							</tr>
							<tr height="6"></tr>
							<?php 
								$total = $destajo1 + $destajo2 + $destajo3 + $sb;
							?>
							<tr>
								<td></td><td></td><td></td><td></td>
								<td class="texto_totales">TOTAL</td>
								<td colspan="7" style="background-color:#33CC66; font-size: 19px; mso-number-format:'Currency';" class="cantidad_total"><?php echo  $total; ?></td>
							</tr>
							<tr height="6"></tr>
							<tr height="6">
								<td style="border-style: solid; border-width: 20px; background-color:#000000;"></td>
								<td style="border-style: solid; border-width: 20px; background-color:#000000;"></td>
								<td style="border-style: solid; border-width: 20px; background-color:#000000;"></td>
								<td style="border-style: solid; border-width: 20px; background-color:#000000;"></td>
								<td style="border-style: solid; border-width: 20px; background-color:#000000;"></td>
								<td style="border-style: solid; border-width: 20px; background-color:#000000;"></td>
								<td style="border-style: solid; border-width: 20px; background-color:#000000;"></td>
								<td style="border-style: solid; border-width: 20px; background-color:#000000;"></td>
								<td style="border-style: solid; border-width: 20px; background-color:#000000;"></td>
								<td style="border-style: solid; border-width: 20px; background-color:#000000;"></td>
								<td style="border-style: solid; border-width: 20px; background-color:#000000;"></td>
								<td style="border-style: solid; border-width: 20px; background-color:#000000;"></td>
								<td style="border-style: solid; border-width: 20px; background-color:#000000;"></td>
								<td style="border-style: solid; border-width: 20px; background-color:#000000;"></td>
								<td style="border-style: solid; border-width: 20px; background-color:#000000;"></td>
								<td style="border-style: solid; border-width: 20px; background-color:#000000;"></td>
								<td style="border-style: solid; border-width: 20px; background-color:#000000;"></td>
								<td style="border-style: solid; border-width: 20px; background-color:#000000;"></td>
							</tr>
					<?php 
							$total_tb = 0; $total_tc = 0; $total_anc = 0;
							$total_avance1 = 0; $total_avance2 = 0;
							$topes_barrenados = 0; $topes_cargados = 0; $anclas = 0;
							$avance1 = 0; $avance2 = 0;
						}
					$cadena = $datos["nombre_emp"];
					$datos2=mysql_fetch_array($rs_datos2);
					}while($datos=mysql_fetch_array($rs_datos)); ?>
			</table>
			</div>
			</body>
<?php	}
}//Fin de la Funcion guardarRepAyudanteExcel($hdn_consulta,$hdn_nomReporte)
	
	//Esta funcion exporta el Reporte de Rezagado a un archivo de excel
	function guardarRepAyudante($hdn_consulta,$hdn_consulta3,$hdn_msje){ 
		$total_tb = 0; $total_bd=0; $total_tc=0;?>
		<head>
			<style>					
				<!--
				body { font-family:Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; }
				/*En este formato  se establece el tamaño,tipo de letra,color de fondo que ocupa la columna princiaplal de una tebla*/
				.nombres_columnas { font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #FFFFFF; background-color: #9BBA59; font-weight: bold; border-top-width: medium; border-right-width: medium;
									border-bottom-width: medium; border-left-width: medium; border-top-style: solid; border-right-style: solid; border-bottom-style: solid; border-left-style: solid; 
									border-top-color: #000000; border-bottom-color: #000000;}
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
		</head> <?php		
		//Manejo de fechas
		include_once("../../includes/func_fechas.php");
		//Esribir datos de la pagina en excel
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=Reporte_AyudanteGral.xls");		
		//Definir el estilo de la tabla y el encabezado que aparecera sobre la tabla?>
												
			<body>
			<div id="tabla">				
				<table width="1100">					
					<tr>
						<td align="left" valign="baseline" colspan="3"><img src="http://<?php echo HOST; ?>/<?php echo SISAD; ?>/images/logo.png" width="118" height="58" align="absbottom" /></td>
						<td colspan="11">&nbsp;</td>
						<td valign="baseline" colspan="3">
							<div align="right"><span class="texto_encabezado">
								<strong>MANUAL DE PROCEDIMIENTOS DE LA CALIDAD</strong><br><em>CONCRETO LANZADO DE FRESNILLO, S.A DE C.V.</em>
							</span></div>
						</td>
					</tr>											
					<tr>
						<td colspan="17" align="center" class="borde_linea">
							<span class="sub_encabezado">CONFIDENCIAL, PROPIEDAD DE &ldquo;CONCRETO LANZADO DE FRESNILLO, S.A DE C.V.&rdquo; PROHIBIDA SU REPRODUCCI&Oacute;N TOTAL O PARCIAL</span>
						</td>
					</tr>					
					<tr>
						<td colspan="17">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="17">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="17" align="center" class="titulo_tabla"><?php echo $hdn_msje;?></td>
					</tr>
					<tr>
						<td colspan="17">&nbsp;</td>
					</tr>			
					<tr>
						<th class='nombres_columnas' align='center' rowspan='2'>FECHA</th>
        				<th class='nombres_columnas' align='center' rowspan='2'>TURNO</th>
				        <th class='nombres_columnas' align='center' rowspan='2'>PUESTO</th>
        				<th class='nombres_columnas' align='center' rowspan='2'>NOMBRE</th>
						<th class='nombres_columnas' align='center' colspan='4'>AVANCE</th>
						<th class='nombres_columnas' align='center' rowspan='2'>EQUIPO</th>
						<th class='nombres_columnas' align='center' colspan='3'>HOROMETRO</th>
						<th class='nombres_columnas' align='center' rowspan='2'>BARRENOS DISPARADOS</th>
						<th class='nombres_columnas' align='center' rowspan='2'>TOPES BARRENADOS</th>
						<th class='nombres_columnas' align='center' rowspan='2'>TOPES CARGADOS</th>
						<th class='nombres_columnas' align='center' colspan='2'>OBSERVACIONES</th>
      				</tr>
					<tr>
						<th class='nombres_columnas' align='center'>OBRA</th>
						<th class='nombres_columnas' align='center'>MACHOTE</th>
						<th class='nombres_columnas' align='center'>MEDIDA</th>
						<th class='nombres_columnas' align='center'>AVANCE</th>
						<th class='nombres_columnas' align='center'>INICIAL</th>
        				<th class='nombres_columnas' align='center'>FINAL</th>
						<th class='nombres_columnas' align='center'>HORAS<br>TOTALES</th>
						<th class='nombres_columnas' align='center'>GENERAL</th>
						<th class='nombres_columnas' align='center'>AVANCE</th>
					</tr>
		<?php
		//Realizar la conexion a la BD de Desarrollo
		$conn = conecta("bd_desarrollo");
		//Ejecutar la Sentencia 
		$rs = mysql_query($hdn_consulta);
		$rs2 = mysql_query($hdn_consulta);
		$datos2=mysql_fetch_array($rs2);
		//Confirmar que la consulta de datos fue realizada con exito.
		if($datos=mysql_fetch_array($rs)){
			$datos2=mysql_fetch_array($rs2);
			
			
			$nom_clase = "renglon_gris";
			$cont = 1;
			$cant_total = 0;
						
			do{
				$total_tb += $datos["topes_barrenados"]; $total_bd += $datos["barrenos_disp"]; $total_tc += 0;
				if($datos["nombre_emp"] != $datos2["nombre_emp"] || $datos["fecha"] != $datos2["fecha"]) { ?>
					<tr>
						<td class='nombres_filas' align='center'><?php echo modFecha($datos["fecha"],1); ?></td>
						<td class='<?php echo $nom_clase; ?>' align='left'><?php echo $datos["turno"]; ?></td>
						<td class='<?php echo $nom_clase; ?>' align='center'><?php echo $datos["puesto"]; ?></td>
						<td class='<?php echo $nom_clase; ?>' align='center'><?php echo $datos["nombre_emp"]; ?></td>
						
						<td class='<?php echo $nom_clase; ?>' align='center'><?php echo $datos["obra"]; ?></td>
						<td class='<?php echo $nom_clase; ?>' align='center'><?php echo $datos["machote"]; ?></td>
						<td class='<?php echo $nom_clase; ?>' align='center'><?php echo $datos["medida"]; ?></td>
						<td class='<?php echo $nom_clase; ?>' align='center'><?php echo $datos["avance"]; ?></td>
						
						<td class='<?php echo $nom_clase; ?>' align='center'><?php echo $datos["id_equipo"]; ?></td>
						<td class='<?php echo $nom_clase; ?>' align='center'><?php echo $datos["horo_ini"]; ?></td>
						<td class='<?php echo $nom_clase; ?>' align='center'><?php echo $datos["horo_fin"]; ?></td>
						<td class='<?php echo $nom_clase; ?>' align='center'><?php echo $datos["horas_totales"]; ?></td>
						
						<td class='<?php echo $nom_clase; ?>' align='center'><?php echo $total_bd; ?></td>
						<td class='<?php echo $nom_clase; ?>' align='center'><?php echo $total_tb; ?></td>
						<td class='<?php echo $nom_clase; ?>' align='center'>0</td>
						<td class='<?php echo $nom_clase; ?>' align='center'><?php echo $datos["obsJu"]; ?></td>
						<td class='<?php echo $nom_clase; ?>' align='center'><?php echo $datos["obsAva"]; ?></td>
					</tr>
				<?php
					$total_tb = 0; $total_bd=0; $total_tc=0;
					//Determinar el color del siguiente renglon a dibujar
					$cont++;
					if($cont%2==0)
						$nom_clase = "renglon_blanco";
					else
						$nom_clase = "renglon_gris";	
				}
				$datos2=mysql_fetch_array($rs2);
			}while($datos=mysql_fetch_array($rs)); 
		}
		//Ejecutar la Sentencia 
		$rs_vol = mysql_query($hdn_consulta3);
		$rs_vol2 = mysql_query($hdn_consulta3);
		$datos_vol2=mysql_fetch_array($rs_vol2);
		//Confirmar que la consulta de datos fue realizada con exito.
		if($datos_vol=mysql_fetch_array($rs_vol)){
			$datos_vol2=mysql_fetch_array($rs_vol2);
			
			do{
				$total_tb += 0; $total_bd += 0; $total_tc += $datos_vol["topes_cargados"];
				 if($datos_vol["nombre_emp"] != $datos_vol2["nombre_emp"] || $datos_vol["fecha"] != $datos_vol2["fecha"]) { ?>
					<tr>
						<td class='nombres_filas' align='center'><?php echo modFecha($datos_vol["fecha"],1); ?></td>
						<td class='<?php echo $nom_clase; ?>' align='left'><?php echo $datos_vol["turno"]; ?></td>
						<td class='<?php echo $nom_clase; ?>' align='center'><?php echo $datos_vol["puesto"]; ?></td>
						<td class='<?php echo $nom_clase; ?>' align='center'><?php echo $datos_vol["nombre_emp"]; ?></td>
						
						<td class='<?php echo $nom_clase; ?>' align='center'><?php echo $datos_vol["obra"]; ?></td>
						<td class='<?php echo $nom_clase; ?>' align='center'><?php echo $datos_vol["machote"]; ?></td>
						<td class='<?php echo $nom_clase; ?>' align='center'><?php echo $datos_vol["medida"]; ?></td>
						<td class='<?php echo $nom_clase; ?>' align='center'><?php echo $datos_vol["avance"]; ?></td>
						
						<td class='<?php echo $nom_clase; ?>' align='center'><?php echo $datos_vol["id_equipo"]; ?></td>
						<td class='<?php echo $nom_clase; ?>' align='center'><?php echo $datos_vol["horo_ini"]; ?></td>
						<td class='<?php echo $nom_clase; ?>' align='center'><?php echo $datos_vol["horo_fin"]; ?></td>
						<td class='<?php echo $nom_clase; ?>' align='center'><?php echo $datos_vol["horas_totales"]; ?></td>
						
						<td class='<?php echo $nom_clase; ?>' align='center'>0</td>
						<td class='<?php echo $nom_clase; ?>' align='center'>0</td>
						<td class='<?php echo $nom_clase; ?>' align='center'><?php echo $total_tc; ?></td>
						<td class='<?php echo $nom_clase; ?>' align='center'><?php echo $datos_vol["obsJu"]; ?></td>
						<td class='<?php echo $nom_clase; ?>' align='center'><?php echo $datos_vol["obsAva"]; ?></td>
					</tr>
				<?php
					$total_tb = 0; $total_bd=0; $total_tc=0;
					//Determinar el color del siguiente renglon a dibujar
					$cont++;
					if($cont%2==0)
						$nom_clase = "renglon_blanco";
					else
						$nom_clase = "renglon_gris";
				}
				
				$datos_vol2=mysql_fetch_array($rs_vol2);
			}while($datos_vol=mysql_fetch_array($rs_vol)); ?>
<?php	} ?>
		</table>
		</div>
		</body> <?php
		//Cerrar la conexion con la BD		
		mysql_close($conn);		
	}//Fin de la Funcion guardarRepRezagado($hdn_consulta,$hdn_nomReporte)
	
	//Esta funcion exporta el Reporte de Equipo Utilitario a un archivo de excel
	function guardarRepUtilitario($hdn_consulta,$hdn_msje){
		//Manejo de fechas
		include_once("../../includes/func_fechas.php");
		//Esribir datos de la pagina en excel
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=Reporte_Utilitario.xls");		
		//Realizar la conexion a la BD de Desarrollo
		$conn = conecta("bd_desarrollo");
		//Ejecutar la sentencia y almacena los datos de la consulta en la variable $rs (result set)
		$rs_datos = mysql_query($hdn_consulta);
			
		if($datos=mysql_fetch_array($rs_datos)){
			//Definir el estilo de la tabla y el encabezado que aparecera sobre la tabla?>
			<head>
				<style>					
					<!--
					body { font-family:Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; }
					/*En este formato  se establece el tamaño,tipo de letra,color de fondo que ocupa la columna princiaplal de una tebla*/
					.nombres_columnas { font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #FFFFFF; background-color: #9BBA59; font-weight: bold; border-top-width: medium; border-right-width: medium;
										border-bottom-width: medium; border-left-width: medium; border-top-style: solid; border-right-style: solid; border-bottom-style: solid; border-left-style: solid; 
										border-top-color: #000000; border-bottom-color: #000000;}
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
						<td align="left" valign="baseline" colspan="3"><img src="http://<?php echo HOST; ?>/<?php echo SISAD; ?>/images/logo.png" width="118" height="58" align="absbottom" /></td>
						<td colspan="6">&nbsp;</td>
						<td valign="baseline" colspan="3">
							<div align="right"><span class="texto_encabezado">
								<strong>MANUAL DE PROCEDIMIENTOS DE LA CALIDAD</strong><br><em>CONCRETO LANZADO DE FRESNILLO, S.A DE C.V.</em>
							</span></div>
						</td>
					</tr>											
					<tr>
						<td colspan="12" align="center" class="borde_linea">
							<span class="sub_encabezado">CONFIDENCIAL, PROPIEDAD DE &ldquo;CONCRETO LANZADO DE FRESNILLO, S.A DE C.V.&rdquo; PROHIBIDA SU REPRODUCCI&Oacute;N TOTAL O PARCIAL</span>
						</td>
					</tr>					
					<tr>
						<td colspan="12">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="12">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="12" align="center" class="titulo_tabla"><?php echo $hdn_msje;?></td>
					</tr>
					<tr>
						<td colspan="12">&nbsp;</td>
					</tr>			
					<tr>						
						<td align="center" class="nombres_columnas" rowspan="2">FECHA</td>						
        				<td align="left" class="nombres_columnas" rowspan="2">TURNO</td>
        				<td align="center" class="nombres_columnas" rowspan="2">PUESTO</td>
						<td align="center" class="nombres_columnas" rowspan="2">NOMBRE</td>
						<td align="center" class="nombres_columnas" rowspan="2">EQUIPO</td>
						<td align="center" class="nombres_columnas" colspan="3">HOR&Oacute;METRO</td>
						<td align="center" class="nombres_columnas" colspan="3">TEPETATE</td>
						<td align="center" class="nombres_columnas" rowspan="2">OBSERVACIONES</td>
      				</tr>
					<tr>						
						<td align="center" class="nombres_columnas">INICIAL</td>						
        				<td align="left" class="nombres_columnas">FINAL</td>
        				<td align="center" class="nombres_columnas">HORAS<br>TOTALES</td>
						<td align="center" class="nombres_columnas">LUGAR AMACIZADO</td>
						<td align="center" class="nombres_columnas">LUGAR BALASTREADO</td>
						<td align="center" class="nombres_columnas">LIMPIA ACEQUIA</td>
      				</tr>
			<?php
			$nom_clase = "renglon_gris";
			$cont = 1;
			$cant_total = 0;
						
			do{?>
					<tr>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo modFecha($datos["fecha"],1)?></td>
						<td align="left" class="<?php echo $nom_clase; ?>"><?php echo $datos["turno"]; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos["puesto"]; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos["nombre"]; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos["id_equipo"]; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos["horo_ini"];?></td>												
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos["horo_fin"]; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos["horas_totales"]; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos["lugar_amacizado"]; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos["lugar_balastreado"]; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos["limpia_acequia"]; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos["observaciones"]; ?></td>
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
	}//Fin de la Funcion guardarRepRezagado($hdn_consulta,$hdn_nomReporte)
	
	//Esta funcion exporta el Reporte de Barrenacion con Jumbo a un archivo de excel
	function guardarRepBarrJumboExcel($hdn_consulta,$hdn_msje,$fecha_ini,$fecha_fin){
		//Manejo de fechas
		include_once("../../includes/func_fechas.php");
		//Esribir datos de la pagina en excel
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=Tarjetas_Jumbo.xls");		
		//Realizar la conexion a la BD de Desarrollo
		$conn = conecta("bd_desarrollo");
		//Ejecutar la sentencia y almacena los datos de la consulta en la variable $rs (result set)
		$rs_datos = mysql_query($hdn_consulta);
		$rs_datos2 = mysql_query($hdn_consulta);
		$datos2=mysql_fetch_array($rs_datos2);
		$cadena = "";	
		if($datos=mysql_fetch_array($rs_datos)){
			$datos2=mysql_fetch_array($rs_datos2);
			//Definir el estilo de la tabla y el encabezado que aparecera sobre la tabla?>
			<head>
				<style>					
					<!--
					body { font-family:Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; }
					/*En este formato  se establece el tamaño,tipo de letra,color de fondo que ocupa la columna princiaplal de una tebla*/
					.nombres_columnas { font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #FFFFFF; background-color: #9BBA59; font-weight: bold; border-top-width: medium; border-right-width: medium;
										border-bottom-width: medium; border-left-width: medium; border-top-style: solid; border-right-style: solid; border-bottom-style: solid; border-left-style: solid; 
										border-top-color: #000000; border-bottom-color: #000000;}
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
					/*En este formato  se establece el tamaño,tipo de letra,color de fondo que contendra datos  de una tabla*/
					.texto_totales {
						font-family: Calibri; font-size: 19px; color: #000000; background-color: #FFFFFF; font-weight: bold;
						text-decoration: underline; text-align: center; vertical-align: middle;
					}
					.cantidad_topes_total {
						font-family: Calibri; font-size: 16px; color: #000000; background-color: #E7E7E7; font-weight: bold;
						text-decoration: underline; text-align: center; vertical-align: middle; border-style: solid; border-width: 1px;
					}
					.cantidad_total {
						font-family: Calibri; font-size: 16px; color: #000000; font-weight: bold;
						text-align: center; vertical-align: middle; border-style: solid; border-width: 1px;
					}
					-->
				</style>
			</head>											
			<body>
			<div id="tabla">				
				<table width="1100" style="border-style: solid; border-width: 20px;">					
					<?php 
						$total_anclas = 0; $total_barrenos = 0;
						$total_barr_ser = 0; $total_escareado = 0;
						$total_topes = 0; $total_avance = 0;
						do{
						if($cadena != $datos["nombre_emp"]){?>
							<tr></tr>
							<tr>
								<td></td><td></td>
								<img src="http://<?php echo HOST; ?>/<?php echo SISAD; ?>/images/logo.png" width="250" height="100"/>
							</tr>
							<tr>
								<td></td><td></td><td></td><td></td><td></td><td></td>
								<td colspan="9" rowspan="3" class="cantidad_total" style="font-size: 30px;"><u><i>TARJETA DE JUMBERO</i></u></td>
							</tr>
							<tr></tr><tr></tr><tr></tr>
							<tr height="6"></tr>
							<tr>
								<td></td><td></td>
								<td valign="middle" align="center" colspan="2"><font face="Calibri">NOMBRE DE EMPLEADO:</font></td>
								<td></td>
								<td colspan="5" class="texto_totales" style="background-color:#99CC33;"><?php echo $datos["nombre_emp"];?></td>
								<td></td>
								<td valign="middle" align="center" colspan="2"><font face="Calibri">NUM. DE FICHA</font></td>
							</tr>
							<tr height="6"></tr>
							<tr>
								<td width="10"></td><td width="10"></td><td width="100"></td><td width="95"></td><td width="10"></td>
						
								<td width="200" align="center"><font face="Calibri"><strong><u>LUGAR DE TRABAJO</u></strong></font></td><td width="10"></td>
								<td width="70" align="center"><font face="Calibri"><strong><u>ANCLAS</u></strong></font></td><td width="10"></td>
								<td width="90" align="center"><font face="Calibri"><strong><u>BARRENOS</u></strong></font></td><td width="10"></td>
								<td width="95" align="center"><font face="Calibri"><strong><u>ESCAREADO</u></strong></font></td><td width="10"></td>
								<td width="95" align="center"><font face="Calibri"><strong><u>TOPES BARRENADOS</u></strong></font></td><td width="10"></td>
								<td width="70" align="center"><font face="Calibri"><strong><u>AVANCE</u></strong></font></td><td width="10"></td>
								<td align="center" colspan="3"><font face="Calibri"><strong><u>OBSERVACIONES</u></strong></font></td><td width="10"></td><td width="10"></td>
							</tr>
					<?php } ?>
							<tr height='6'></tr>
							<tr>
								<td></td><td></td>
								<td class="cantidad_total" colspan="2"><?php echo modFecha($datos["fecha"],9);?></td>
								<td></td>
								<td class="cantidad_total" style='font-weight: normal;'><?php echo $datos["obra"];?></td><td></td>
								<td class="cantidad_total" style='font-weight: normal;'><?php echo $datos["anclas"];?></td><td></td>
								<td class="cantidad_total" style='font-weight: normal;'><?php echo $datos["barrenos_dados"];?></td><td></td>
								<td class="cantidad_total" style='font-weight: normal;'><?php echo $datos["escareado"];?></td><td></td>
								<td class="cantidad_total" style='font-weight: normal;'><?php echo $datos["topes_barrenados"];?></td><td></td>
								<td class="cantidad_total" style='font-weight: normal;'><?php echo $datos["avance"];?></td><td></td>
								<td class="cantidad_total" colspan="3" style='font-weight: normal;'><?php echo $datos["observaciones"];?></td><td></td>
							</tr>
							<tr height='6'></tr>
							<tr>
								<td></td><td></td>
								<td colspan="2"></td>
								<td></td>
								<?php if($datos["nombre_emp"] == $datos2["nombre_emp"] && $datos["fecha"] == $datos2["fecha"]) { ?>
								<td class="cantidad_total" style='font-weight: normal; background-color:#F5F5F5;'><?php echo $datos2["obra"];?></td><td></td>
								<td class="cantidad_total" style='font-weight: normal; background-color:#F5F5F5;'><?php echo $datos2["anclas"];?></td><td></td>
								<td class="cantidad_total" style='font-weight: normal; background-color:#F5F5F5;'><?php echo $datos2["barrenos_dados"];?></td><td></td>
								<td class="cantidad_total" style='font-weight: normal; background-color:#F5F5F5;'><?php echo $datos2["escareado"];?></td><td></td>
								<td class="cantidad_total" style='font-weight: normal; background-color:#F5F5F5;'><?php echo $datos2["topes_barrenados"];?></td><td></td>
								<td class="cantidad_total" style='font-weight: normal; background-color:#F5F5F5;'><?php echo $datos2["avance"];?></td><td></td>
								<td class="cantidad_total" colspan="3" style='font-weight: normal; background-color:#F5F5F5;'><?php echo $datos2["observaciones"];?></td><td></td>
								<?php } else { ?>
								<td class="cantidad_total" style='font-weight: normal; background-color:#F5F5F5;'></td><td></td>
								<td class="cantidad_total" style='font-weight: normal; background-color:#F5F5F5;'></td><td></td>
								<td class="cantidad_total" style='font-weight: normal; background-color:#F5F5F5;'></td><td></td>
								<td class="cantidad_total" style='font-weight: normal; background-color:#F5F5F5;'></td><td></td>
								<td class="cantidad_total" style='font-weight: normal; background-color:#F5F5F5;'></td><td></td>
								<td class="cantidad_total" style='font-weight: normal; background-color:#F5F5F5;'></td><td></td>
								<td class="cantidad_total" colspan="3" style='font-weight: normal; background-color:#F5F5F5;'></td><td></td>
								<?php } ?>
							</tr>
					<?php
						$total_anclas += $datos["anclas"]; $total_barrenos += $datos["barrenos_dados"];
						$total_barr_ser += 0; $total_escareado += $datos["escareado"];
						$total_topes += $datos["topes_barrenados"]; $total_avance += $datos["avance"];
						if($datos["nombre_emp"] == $datos2["nombre_emp"] && $datos["fecha"] == $datos2["fecha"]) {
							$total_anclas += $datos2["anclas"]; $total_barrenos += $datos2["barrenos_dados"];
							$total_barr_ser += 0; $total_escareado += $datos2["escareado"];
							$total_topes += $datos2["topes_barrenados"]; $total_avance += $datos2["avance"];
							$datos=mysql_fetch_array($rs_datos);
							$datos2=mysql_fetch_array($rs_datos2);
						}
						if($datos["nombre_emp"] != $datos2["nombre_emp"]){?>
							<tr height="6"></tr>
							<tr height="25">
								<td width="15"></td><td width="10"></td>
								<td colspan="2" class="texto_totales" style="font-size: 16px; border-style: solid; border-width: 1px;">TRABAJO REALIZADO</td>
								<td></td><td></td><td></td>
								<td class="cantidad_topes_total"><?php echo $total_anclas; ?></td><td></td>
								<td class="cantidad_topes_total"><?php echo $total_barrenos; ?></td><td></td>
								<td class="cantidad_topes_total"><?php echo $total_escareado; ?></td><td></td>
								<td class="cantidad_topes_total"><?php echo $total_topes; ?></td><td></td>
								<td class="cantidad_topes_total"><?php echo $total_avance; ?></td><td></td>
							</tr>
							<tr height="6"></tr>
							<tr>
								<td></td><td></td><td></td><td></td><td></td>
								<td class="texto_totales">DESTAJO</td>
								<td></td>
								<td colspan="5"></td><td></td>
								<?php 
									$avance = obtenerDatoBonificacion($datos["puesto"], "JUMBO", "AVANCE");
									$destajo = ($total_avance * $avance);
								?>
								<td colspan="3" class="cantidad_total" style="mso-number-format:'Currency'; background-color:yellow;"><?php echo $destajo; ?></td>
								<td></td>
								<td align="center" width="80"><font face="Calibri"><strong><u>EXTRAS</u></strong></font></td><td width="10"></td><td width="220"></td>
							</tr>
							<tr height="6"></tr>
							<tr>
								<td></td><td></td><td></td><td></td><td></td>
								<td class="texto_totales">SUELDO BASE</td>
								<td></td>
								<?php $sb = obtenerExtras_SD($fecha_ini, $fecha_fin, $datos["nombre"],"sueldo_base"); ?>
								<td colspan="9" style="mso-number-format:'Currency';" class="cantidad_total"><?php echo $sb;?></td>
								<td></td>
								<?php $extra = obtenerExtras_SD($fecha_ini, $fecha_fin, $datos["nombre"],"extra"); ?>
								<td class="cantidad_total" style="font-weight: normal; mso-number-format:'Currency'">0</td>
								<td></td>
								<td class="cantidad_total" style='font-weight: normal;'></td>
							</tr>
							<tr height="6"></tr>
							<tr>
								<td></td><td></td><td></td><td></td><td></td>
								<td class="texto_totales">TOTAL</td>
								<td></td>
								<?php
									$total = ($destajo + $sb);
								?>
								<td colspan="9" style="background-color:#33CC66; font-size: 19px; mso-number-format:'Currency';" class="cantidad_total"><?php echo $total;?></td>
							</tr>
							<tr height="6"></tr>
							<tr height="6">
								<td style="border-style: solid; border-width: 20px; background-color:#000000;"></td>
								<td style="border-style: solid; border-width: 20px; background-color:#000000;"></td>
								<td style="border-style: solid; border-width: 20px; background-color:#000000;"></td>
								<td style="border-style: solid; border-width: 20px; background-color:#000000;"></td>
								<td style="border-style: solid; border-width: 20px; background-color:#000000;"></td>
								<td style="border-style: solid; border-width: 20px; background-color:#000000;"></td>
								<td style="border-style: solid; border-width: 20px; background-color:#000000;"></td>
								<td style="border-style: solid; border-width: 20px; background-color:#000000;"></td>
								<td style="border-style: solid; border-width: 20px; background-color:#000000;"></td>
								<td style="border-style: solid; border-width: 20px; background-color:#000000;"></td>
								<td style="border-style: solid; border-width: 20px; background-color:#000000;"></td>
								<td style="border-style: solid; border-width: 20px; background-color:#000000;"></td>
								<td style="border-style: solid; border-width: 20px; background-color:#000000;"></td>
								<td style="border-style: solid; border-width: 20px; background-color:#000000;"></td>
								<td style="border-style: solid; border-width: 20px; background-color:#000000;"></td>
								<td style="border-style: solid; border-width: 20px; background-color:#000000;"></td>
								<td style="border-style: solid; border-width: 20px; background-color:#000000;"></td>
								<td style="border-style: solid; border-width: 20px; background-color:#000000;"></td>
								<td style="border-style: solid; border-width: 20px; background-color:#000000;"></td>
								<td style="border-style: solid; border-width: 20px; background-color:#000000;"></td>
								<td style="border-style: solid; border-width: 20px; background-color:#000000;"></td>
							</tr>
					<?php 
							$total_anclas = 0; $total_barrenos = 0;
							$total_barr_ser = 0; $total_escareado = 0;
							$total_topes = 0; $total_avance = 0;
						}
					$cadena = $datos["nombre_emp"];
					$datos2=mysql_fetch_array($rs_datos2);
					}while($datos=mysql_fetch_array($rs_datos)); ?>
			</table>
			</div>
			</body>
<?php	}
		//Cerrar la conexion con la BD		
		mysql_close($conn);		
	}//Fin de la Funcion guardarRepBarrJumboExcel($hdn_consulta,$hdn_nomReporte)
	
	//Esta funcion exporta el Reporte de Barrenacion con Jumbo a un archivo de excel
	function guardarRepBarrJumbo($hdn_consulta,$hdn_msje){
		//Manejo de fechas
		include_once("../../includes/func_fechas.php");
		//Esribir datos de la pagina en excel
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=Reporte_BarrenacionJumbo.xls");		
		//Realizar la conexion a la BD de Desarrollo
		$conn = conecta("bd_desarrollo");
		//Ejecutar la sentencia y almacena los datos de la consulta en la variable $rs (result set)
		$rs_datos = mysql_query($hdn_consulta);
			
		if($datos=mysql_fetch_array($rs_datos)){
			//Definir el estilo de la tabla y el encabezado que aparecera sobre la tabla?>
			<head>
				<style>					
					<!--
					body { font-family:Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; }
					/*En este formato  se establece el tamaño,tipo de letra,color de fondo que ocupa la columna princiaplal de una tebla*/
					.nombres_columnas { font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #FFFFFF; background-color: #9BBA59; font-weight: bold; border-top-width: medium; border-right-width: medium;
										border-bottom-width: medium; border-left-width: medium; border-top-style: solid; border-right-style: solid; border-bottom-style: solid; border-left-style: solid; 
										border-top-color: #000000; border-bottom-color: #000000;}
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
						<td align="left" valign="baseline" colspan="3"><img src="http://<?php echo HOST; ?>/<?php echo SISAD; ?>/images/logo.png" width="118" height="58" align="absbottom" /></td>
						<td colspan="26">&nbsp;</td>
						<td valign="baseline" colspan="3">
							<div align="right"><span class="texto_encabezado">
								<strong>MANUAL DE PROCEDIMIENTOS DE LA CALIDAD</strong><br><em>CONCRETO LANZADO DE FRESNILLO, S.A DE C.V.</em>
							</span></div>
						</td>
					</tr>											
					<tr>
						<td colspan="32" align="center" class="borde_linea">
							<span class="sub_encabezado">CONFIDENCIAL, PROPIEDAD DE &ldquo;CONCRETO LANZADO DE FRESNILLO, S.A DE C.V.&rdquo; PROHIBIDA SU REPRODUCCI&Oacute;N TOTAL O PARCIAL</span>
						</td>
					</tr>					
					<tr>
						<td colspan="32">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="32">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="32" align="center" class="titulo_tabla"><?php echo $hdn_msje;?></td>
					</tr>
					<tr>
						<td colspan="32">&nbsp;</td>
					</tr>			
					<tr>						
						<td align="center" class="nombres_columnas" rowspan="2">FECHA</td>						
        				<td align="center" class="nombres_columnas" rowspan="2">TURNO</td>
        				<td align="center" class="nombres_columnas" rowspan="2">PUESTO</td>
						<td align="center" class="nombres_columnas" rowspan="2">NOMBRE</td>
						<td align="center" class="nombres_columnas" colspan="4">AVANCE</td>
						<td align="center" class="nombres_columnas" rowspan="2">EQUIPO</td>
						<td align="center" class="nombres_columnas" colspan="3">HOR&Oacute;METRO</td>
						<td align="center" class="nombres_columnas" colspan="3">BRAZO 1</td>
						<td align="center" class="nombres_columnas" colspan="3">BRAZO 2</td>
						<th class='nombres_columnas' align='center' colspan='7'>BARRENOS</th>
						<th class='nombres_columnas' align='center' rowspan='2'>TOPES BARRENADOS</th>
						<td align="center" class="nombres_columnas" rowspan="2">REANCLAJE</td>
						<td align="center" class="nombres_columnas" rowspan="2">COPLES</td>
						<td align="center" class="nombres_columnas" rowspan="2">ZANCOS</td>
						<td align="center" class="nombres_columnas" rowspan="2">ANCLAS</td>
						<td align="center" class="nombres_columnas" colspan="2">OBSERVACIONES</td>
      				</tr>
					<tr>
						<th class='nombres_columnas' align='center'>OBRA</th>
						<th class='nombres_columnas' align='center'>MACHOTE</th>
						<th class='nombres_columnas' align='center'>MEDIDA</th>
						<th class='nombres_columnas' align='center'>AVANCE</th>
					
						<td align="center" class="nombres_columnas">INICIAL</td>						
        				<td align="center" class="nombres_columnas">FINAL</td>
        				<td align="center" class="nombres_columnas">HORAS<br>TOTALES</td>
						
						<td align="center" class="nombres_columnas">INICIAL</td>						
        				<td align="center" class="nombres_columnas">FINAL</td>
        				<td align="center" class="nombres_columnas">HORAS<br>TOTALES</td>
						
						<td align="center" class="nombres_columnas">INICIAL</td>						
        				<td align="center" class="nombres_columnas">FINAL</td>
        				<td align="center" class="nombres_columnas">HORAS<br>TOTALES</td>
						
						<td align="center" class="nombres_columnas">DADOS</td>
						<td align="center" class="nombres_columnas">DISPARADOS</td>
						<td align="center" class="nombres_columnas">LONGITUD</td>
						<td align="center" class="nombres_columnas">DESBORDE</td>
						<td align="center" class="nombres_columnas">ENCAPILLE</td>
						<td align="center" class="nombres_columnas">DESPATE</td>
						
						<th class='nombres_columnas' align='center'>ESCAREADO</th>
						
						<td align="center" class="nombres_columnas">BARRENACI&Oacute;N</td>
						<td align="center" class="nombres_columnas">AVANCE</td>
      				</tr>
			<?php
			$nom_clase = "renglon_gris";
			$cont = 1;
			$cant_total = 0;
						
			do{
				$brazo1HI=obtenerDatoBicondicional("bd_desarrollo","registro_brazos","horo_ini","bitacora_avance_id_bitacora",$datos["bitacora_avance_id_bitacora"],"num_brazo",1);
				if($brazo1HI==""){
					$brazo1HI=0;
					$brazo1HF=0;
					$brazo1HT=0;
				}
				else{
					$brazo1HF=obtenerDatoBicondicional("bd_desarrollo","registro_brazos","horo_fin","bitacora_avance_id_bitacora",$datos["bitacora_avance_id_bitacora"],"num_brazo",1);
					$brazo1HT=obtenerDatoBicondicional("bd_desarrollo","registro_brazos","horas_totales","bitacora_avance_id_bitacora",$datos["bitacora_avance_id_bitacora"],"num_brazo",1);
				}
				
				$brazo2HI=obtenerDatoBicondicional("bd_desarrollo","registro_brazos","horo_ini","bitacora_avance_id_bitacora",$datos["bitacora_avance_id_bitacora"],"num_brazo",2);
				if($brazo2HI==""){
					$brazo2HI=0;
					$brazo2HF=0;
					$brazo2HT=0;
				}
				else{
					$brazo2HF=obtenerDatoBicondicional("bd_desarrollo","registro_brazos","horo_fin","bitacora_avance_id_bitacora",$datos["bitacora_avance_id_bitacora"],"num_brazo",2);
					$brazo2HT=obtenerDatoBicondicional("bd_desarrollo","registro_brazos","horas_totales","bitacora_avance_id_bitacora",$datos["bitacora_avance_id_bitacora"],"num_brazo",2);
				}
				
				if($cont%2!=0)
					$row = 2;
				else
					$row = 1;
				
				if ($row==2){
			?>
					<tr>
						<td align="center" class="<?php echo $nom_clase; ?>" rowspan='<?php echo $row?>'><?php echo modFecha($datos["fecha"],1)?></td>
						<td align="center" class="<?php echo $nom_clase; ?>" rowspan='<?php echo $row?>'><?php echo $datos["turno"]; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos["puesto"]; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos["nombre_emp"]; ?></td>
						
						<td class='<?php echo $nom_clase?>' align='center' rowspan='<?php echo $row?>'><?php echo $datos["obra"]?></td>
						<td class='<?php echo $nom_clase?>' align='center' rowspan='<?php echo $row?>'><?php echo $datos["machote"]?></td>
						<td class='<?php echo $nom_clase?>' align='center' rowspan='<?php echo $row?>'><?php echo $datos["medida"]?></td>
						<td class='<?php echo $nom_clase?>' align='center' rowspan='<?php echo $row?>'><?php echo $datos["avance"]?></td>
						
						<td align="center" class="<?php echo $nom_clase; ?>" rowspan='<?php echo $row?>'><?php echo $datos["id_equipo"]; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>" rowspan='<?php echo $row?>'><?php echo $datos["horo_ini"];?></td>												
						<td align="center" class="<?php echo $nom_clase; ?>" rowspan='<?php echo $row?>'><?php echo $datos["horo_fin"]; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>" rowspan='<?php echo $row?>'><?php echo $datos["horas_totales"]; ?></td>
						
						<td align="center" class="<?php echo $nom_clase; ?>" rowspan='<?php echo $row?>'><?php echo $brazo1HI; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>" rowspan='<?php echo $row?>'><?php echo $brazo1HF; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>" rowspan='<?php echo $row?>'><?php echo $brazo1HT; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>" rowspan='<?php echo $row?>'><?php echo $brazo2HI; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>" rowspan='<?php echo $row?>'><?php echo $brazo2HF; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>" rowspan='<?php echo $row?>'><?php echo $brazo2HT; ?></td>
						
						<td align="center" class="<?php echo $nom_clase; ?>" rowspan='<?php echo $row?>'><?php echo $datos["barrenos_dados"]; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>" rowspan='<?php echo $row?>'><?php echo $datos["barrenos_disp"]; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>" rowspan='<?php echo $row?>'><?php echo $datos["barrenos_long"]; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>" rowspan='<?php echo $row?>'><?php echo $datos["desborde"]; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>" rowspan='<?php echo $row?>'><?php echo $datos["encapille"]; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>" rowspan='<?php echo $row?>'><?php echo $datos["despate"]; ?></td>
						
						<td align="center" class="<?php echo $nom_clase; ?>" rowspan='<?php echo $row?>'><?php echo $datos["escareado"]; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>" rowspan='<?php echo $row?>'><?php echo $datos["topes_barrenados"]; ?></td>
						
						<td class='<?php echo $nom_clase?>' align='center' rowspan='<?php echo $row?>'><?php echo $datos["reanclaje"]?></td>
						<td class='<?php echo $nom_clase?>' align='center' rowspan='<?php echo $row?>'><?php echo $datos["coples"]?></td>
						<td class='<?php echo $nom_clase?>' align='center' rowspan='<?php echo $row?>'><?php echo $datos["zancos"]?></td>
						<td class='<?php echo $nom_clase?>' align='center' rowspan='<?php echo $row?>'><?php echo $datos["anclas"]?></td>
						<td align="center" class="<?php echo $nom_clase; ?>" rowspan='<?php echo $row?>'><?php echo $datos["obsJu"]; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>" rowspan='<?php echo $row?>'><?php echo $datos["obsAva"]; ?></td>
					</tr>
				<?php
				}
				else{
				?>
					<tr>
						<td class='<?php echo $nom_clase?>' align='center'><?php echo $datos["puesto"]?></td>
						<td class='<?php echo $nom_clase?>' align='center'><?php echo $datos["nombre_emp"]?></td>
					<tr>
				<?php }
				//Determinar el color del siguiente renglon a dibujar
				$cont++;	
			}while($datos=mysql_fetch_array($rs_datos)); ?>
			</table>
			</div>
			</body>
<?php	}
		//Cerrar la conexion con la BD		
		mysql_close($conn);		
	}//Fin de la Funcion guardarRepBarrJumbo($hdn_consulta,$hdn_nomReporte)
	
	//Esta funcion exporta el Reporte de Barrenacion con Maquina de Pierna a un archivo de excel
	function guardarRepBarrMP($hdn_consulta,$hdn_msje){
		//Manejo de fechas
		include_once("../../includes/func_fechas.php");
		//Esribir datos de la pagina en excel
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=Reporte_BarrenacionMP.xls");		
		//Realizar la conexion a la BD de Desarrollo
		$conn = conecta("bd_desarrollo");
		//Ejecutar la sentencia y almacena los datos de la consulta en la variable $rs (result set)
		$rs_datos = mysql_query($hdn_consulta);
			
		if($datos=mysql_fetch_array($rs_datos)){
			//Definir el estilo de la tabla y el encabezado que aparecera sobre la tabla?>
			<head>
				<style>					
					<!--
					body { font-family:Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; }
					/*En este formato  se establece el tamaño,tipo de letra,color de fondo que ocupa la columna princiaplal de una tebla*/
					.nombres_columnas { font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #FFFFFF; background-color: #9BBA59; font-weight: bold; border-top-width: medium; border-right-width: medium;
										border-bottom-width: medium; border-left-width: medium; border-top-style: solid; border-right-style: solid; border-bottom-style: solid; border-left-style: solid; 
										border-top-color: #000000; border-bottom-color: #000000;}
					/*En este formato  se establece el tamaño,tipo de letra,color de fondo que se ocupa en la fila principal para resaltar datos  de una tabla*/
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
						<td align="left" valign="baseline" colspan="3"><img src="http://<?php echo HOST; ?>/<?php echo SISAD; ?>/images/logo.png" width="118" height="58" align="absbottom" /></td>
						<td colspan="16">&nbsp;</td>
						<td valign="baseline" colspan="3">
							<div align="right"><span class="texto_encabezado">
								<strong>MANUAL DE PROCEDIMIENTOS DE LA CALIDAD</strong><br><em>CONCRETO LANZADO DE FRESNILLO, S.A DE C.V.</em>
							</span></div>
						</td>
					</tr>											
					<tr>
						<td colspan="22" align="center" class="borde_linea">
							<span class="sub_encabezado">CONFIDENCIAL, PROPIEDAD DE &ldquo;CONCRETO LANZADO DE FRESNILLO, S.A DE C.V.&rdquo; PROHIBIDA SU REPRODUCCI&Oacute;N TOTAL O PARCIAL</span>
						</td>
					</tr>					
					<tr>
						<td colspan="22">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="22">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="22" align="center" class="titulo_tabla"><?php echo $hdn_msje;?></td>
					</tr>
					<tr>
						<td colspan="22">&nbsp;</td>
					</tr>			
					<tr>						
						<td align="center" class="nombres_columnas" rowspan="2">FECHA</td>						
        				<td align="center" class="nombres_columnas" rowspan="2">TURNO</td>
        				<td align="center" class="nombres_columnas" rowspan="2">PUESTO</td>
						<td align="center" class="nombres_columnas" rowspan="2">NOMBRE</td>
						<td align="center" class="nombres_columnas" colspan="4">AVANCE</td>
						<td align="center" class="nombres_columnas" rowspan="2">EQUIPO</td>
						<td align="center" class="nombres_columnas" colspan="3">HOR&Oacute;METRO</td>
						<td align="center" class="nombres_columnas" colspan="3">BARRENOS</td>
						<td align="center" class="nombres_columnas" colspan="2">BROCAS</td>
						<td align="center" class="nombres_columnas" colspan="2">BARRAS</td>
						<td align="center" class="nombres_columnas" rowspan="2">ANCLAS</td>
						<td align="center" class="nombres_columnas" colspan="2">OBSERVACIONES</td>
      				</tr>
					<tr>
						<th class='nombres_columnas' align='center'>OBRA</th>
						<th class='nombres_columnas' align='center'>MACHOTE</th>
						<th class='nombres_columnas' align='center'>MEDIDA</th>
						<th class='nombres_columnas' align='center'>AVANCE</th>
					
						<td align="center" class="nombres_columnas">INICIAL</td>						
        				<td align="center" class="nombres_columnas">FINAL</td>
        				<td align="center" class="nombres_columnas">HORAS<br>TOTALES</td>
						
						<td align="center" class="nombres_columnas">DADOS</td>
						<td align="center" class="nombres_columnas">DISPARADOS</td>
						<td align="center" class="nombres_columnas">LONGITUD</td>
											
						<td align="center" class="nombres_columnas">NUEVAS</td>
						<td align="center" class="nombres_columnas">AFILADAS</td>
						
						<td align="center" class="nombres_columnas">6</td>
						<td align="center" class="nombres_columnas">8</td>
						
						<td align="center" class="nombres_columnas">BARRENACI&Oacute;N</td>
						<td align="center" class="nombres_columnas">AVANCE</td>
      				</tr>
			<?php
			$nom_clase = "renglon_gris";
			$cont = 1;
			$cant_total = 0;
						
			do{
				if($cont%2!=0)
					$row = 2;
				else
					$row = 1;
				
				if ($row==2){
			?>
					<tr>
						<td align="center" class="nombres_filas" rowspan='<?php echo $row?>'><?php echo modFecha($datos["fecha"],1)?></td>
						<td align="center" class="<?php echo $nom_clase; ?>" rowspan='<?php echo $row?>'><?php echo $datos["turno"]; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos["puesto"]; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos["nombre"]; ?></td>
						
						<td class='<?php echo $nom_clase?>' align='center' rowspan='<?php echo $row?>'><?php echo $datos["obra"]?></td>
						<td class='<?php echo $nom_clase?>' align='center' rowspan='<?php echo $row?>'><?php echo $datos["machote"]?></td>
						<td class='<?php echo $nom_clase?>' align='center' rowspan='<?php echo $row?>'><?php echo $datos["medida"]?></td>
						<td class='<?php echo $nom_clase?>' align='center' rowspan='<?php echo $row?>'><?php echo $datos["avance"]?></td>
						
						<td align="center" class="<?php echo $nom_clase; ?>" rowspan='<?php echo $row?>'><?php echo $datos["id_equipo"]; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>" rowspan='<?php echo $row?>'><?php echo $datos["hie"];?></td>												
						<td align="center" class="<?php echo $nom_clase; ?>" rowspan='<?php echo $row?>'><?php echo $datos["hfe"]; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>" rowspan='<?php echo $row?>'><?php echo $datos["hte"]; ?></td>
						
						<td align="center" class="<?php echo $nom_clase; ?>" rowspan='<?php echo $row?>'><?php echo $datos["barrenos_dados"]; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>" rowspan='<?php echo $row?>'><?php echo $datos["barrenos_disparos"]; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>" rowspan='<?php echo $row?>'><?php echo $datos["barrenos_longitud"]; ?></td>
						
						<td align="center" class="<?php echo $nom_clase; ?>" rowspan='<?php echo $row?>'><?php echo $datos["broca_nva"]; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>" rowspan='<?php echo $row?>'><?php echo $datos["broca_afil"]; ?></td>
						
						<td class='<?php echo $nom_clase?>' align='center' rowspan='<?php echo $row?>'><?php echo $datos["barra_6"]?></td>
						<td class='<?php echo $nom_clase?>' align='center' rowspan='<?php echo $row?>'><?php echo $datos["barra_8"]?></td>
						
						<td class='<?php echo $nom_clase?>' align='center' rowspan='<?php echo $row?>'><?php echo $datos["anclas"]?></td>
						<td align="center" class="<?php echo $nom_clase; ?>" rowspan='<?php echo $row?>'><?php echo $datos["obsMP"]; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>" rowspan='<?php echo $row?>'><?php echo $datos["obsAva"]; ?></td>
					</tr>
				<?php
				}
				else{
				?>
					<tr>
						<td class='<?php echo $nom_clase?>' align='center'><?php echo $datos["puesto"]?></td>
						<td class='<?php echo $nom_clase?>' align='center'><?php echo $datos["nombre"]?></td>
					<tr>
				<?php }
				//Determinar el color del siguiente renglon a dibujar
				$cont++;	
			}while($datos=mysql_fetch_array($rs_datos)); ?>
			</table>
			</div>
			</body>
<?php	}
		//Cerrar la conexion con la BD		
		mysql_close($conn);		
	}//Fin de la Funcion guardarRepBarrMP($hdn_consulta,$hdn_nomReporte)
	
	//Esta funcion exporta el Reporte de Voladuras a un archivo de excel
	function guardarRepVoladurasExcel($hdn_consulta,$hdn_msje,$fecha_ini,$fecha_fin){
		//Manejo de fechas
		include_once("../../includes/func_fechas.php");
		//Esribir datos de la pagina en excel
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=Tarjetas_Voladura.xls");		
		//Realizar la conexion a la BD de Desarrollo
		$conn = conecta("bd_desarrollo");
		//Ejecutar la sentencia y almacena los datos de la consulta en la variable $rs (result set)
		$rs_datos = mysql_query($hdn_consulta);
		$rs_datos2 = mysql_query($hdn_consulta);
		$datos2=mysql_fetch_array($rs_datos2);
		$cadena = "";	
		if($datos=mysql_fetch_array($rs_datos)){
			$datos2=mysql_fetch_array($rs_datos2);
			//Definir el estilo de la tabla y el encabezado que aparecera sobre la tabla?>
			<head>
				<style>					
					<!--
					body { font-family:Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; }
					/*En este formato  se establece el tamaño,tipo de letra,color de fondo que ocupa la columna princiaplal de una tebla*/
					.nombres_columnas { font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #FFFFFF; background-color: #9BBA59; font-weight: bold; border-top-width: medium; border-right-width: medium;
										border-bottom-width: medium; border-left-width: medium; border-top-style: solid; border-right-style: solid; border-bottom-style: solid; border-left-style: solid; 
										border-top-color: #000000; border-bottom-color: #000000;}
					/*En este formato  se establece el tamaño,tipo de letra,color de fondo que se ocupa en la fila principal para resaltar datos  de una tabla*/
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
					/*En este formato  se establece el tamaño,tipo de letra,color de fondo que contendra datos  de una tabla*/
					.texto_totales {
						font-family: Calibri; font-size: 19px; color: #000000; background-color: #FFFFFF; font-weight: bold;
						text-decoration: underline; text-align: center; vertical-align: middle;
					}
					.cantidad_topes_total {
						font-family: Calibri; font-size: 16px; color: #000000; background-color: #E7E7E7; font-weight: bold;
						text-decoration: underline; text-align: center; vertical-align: middle; border-style: solid; border-width: 1px;
					}
					.cantidad_total {
						font-family: Calibri; font-size: 16px; color: #000000; font-weight: bold;
						text-align: center; vertical-align: middle; border-style: solid; border-width: 1px;
					}
					-->
				</style>
			</head>											
			<body>
			<div id="tabla">				
				<table width="1100" style="border-style: solid; border-width: 20px;">
					<?php 
						$total_topes1 = 0; $total_avance1 = 0;
						$total_topes2 = 0; $total_avance2 = 0;
						do{
						if($cadena != $datos["nombre_emp"]){ ?>
							<tr></tr>
							<tr>
								<td></td><td></td>
								<img src="http://<?php echo HOST; ?>/<?php echo SISAD; ?>/images/logo.png" width="250" height="100"/>
							</tr>
							<tr>
								<td></td><td></td><td></td><td></td><td></td><td></td>
								<td colspan="9" rowspan="3" class="cantidad_total" style="font-size: 30px;"><u><i>TARJETA DE CARGADOR</i></u></td>
							</tr>
							<tr></tr><tr></tr><tr></tr>
							<tr height="6"></tr>
							<tr>
								<td></td><td></td>
								<td valign="middle" align="center" colspan="2"><font face="Calibri">NOMBRE DE EMPLEADO:</font></td>
								<td></td>
								<td colspan="5" class="texto_totales" style="background-color:#99CC33;"><?php echo $datos["nombre_emp"]; ?></td>
								<td></td>
								<td valign="middle" align="center" colspan="2"><font face="Calibri">NUM. DE FICHA</font></td>
							</tr>
							<tr height="6"></tr>
							<tr>
								<td width="10"></td><td width="10"></td><td width="110"></td><td width="110"></td><td width="10"></td>
						
								<td width="220" align="center"><font face="Calibri"><strong><u>LUGAR DE TRABAJO</u></strong></font></td><td width="10"></td>
								<td width="100" align="center"><font face="Calibri"><strong><u>TOPES CARGADOS</u></strong></font></td><td width="10"></td>
								<td width="70" align="center"><font face="Calibri"><strong><u>AVANCE</u></strong></font></td><td width="10"></td>
								<td width="100" align="center"><font face="Calibri"><strong><u>TOPES CARGADOS</u></strong></font></td><td width="10"></td>
								<td width="70" align="center"><font face="Calibri"><strong><u>AVANCE</u></strong></font></td><td width="10"></td>
								<td align="center" colspan="3"><font face="Calibri"><strong><u>OBSERVACIONES</u></strong></font></td><td width="10"></td><td width="10"></td>
							</tr>
					<?php } ?>
							<tr height='6'></tr>
							<tr>
								<td></td><td></td>
								<td class="cantidad_total" colspan="2"><?php echo modFecha($datos["fecha"],9);?></td>
								<td></td>
								<td class="cantidad_total" style='font-weight: normal;'><?php echo $datos["obra"]; ?></td><td></td>
								<td class="cantidad_total" style='font-weight: normal;'><?php echo $datos["topes_cargados"]; ?></td><td></td>
								<td class="cantidad_total" style='font-weight: normal;'><?php echo $datos["avance"]; ?></td><td></td>
								<?php if($datos["nombre_emp"] == $datos2["nombre_emp"] && $datos["fecha"] == $datos2["fecha"]) { ?>
								<td class="cantidad_total" style='font-weight: normal;'><?php echo $datos2["topes_cargados"]; ?></td><td></td>
								<td class="cantidad_total" style='font-weight: normal;'><?php echo $datos2["avance"]; ?></td><td></td>
								<?php } else { ?>
								<td class="cantidad_total" style='font-weight: normal;'></td><td></td>
								<td class="cantidad_total" style='font-weight: normal;'></td><td></td>
								<?php } ?>
								<td class="cantidad_total" colspan="3" style='font-weight: normal;'><?php echo $datos["observaciones"]; ?></td><td></td>
							</tr>
					<?php
						if($datos["nombre_emp"] == $datos2["nombre_emp"] && $datos["fecha"] == $datos2["fecha"]) {
							$total_topes1 += $datos["topes_cargados"]; $total_avance1 += $datos["avance"];
							$total_topes2 += $datos2["topes_cargados"]; $total_avance2 += $datos2["avance"];
							$datos=mysql_fetch_array($rs_datos);
							$datos2=mysql_fetch_array($rs_datos2);
						} else{
							$total_topes1 += $datos["topes_cargados"]; $total_avance1 += $datos["avance"];
						}
						if($datos["nombre_emp"] != $datos2["nombre_emp"]){?>
							<tr height="6"></tr>
							<tr height="25">
								<td width="15"></td><td width="10"></td>
								<td colspan="2" class="texto_totales" style="font-size: 16px; border-style: solid; border-width: 1px;">TRABAJO REALIZADO</td>
								<td></td><td></td><td></td>
								<td class="cantidad_topes_total"><?php echo $total_topes1; ?></td><td></td>
								<td class="cantidad_topes_total"><?php echo $total_avance1; ?></td><td></td>
								<td class="cantidad_topes_total"><?php echo $total_topes2; ?></td><td></td>
								<td class="cantidad_topes_total"><?php echo $total_avance2; ?></td><td></td>
							</tr>
							<tr height="6"></tr>
							<tr>
								<td></td><td></td><td></td><td></td><td></td>
								<td class="texto_totales">DESTAJO</td>
								<td></td>
								<?php 
									$avance = obtenerDatoBonificacion($datos["puesto"], "VOLADURAS", "AVANCE");
									$destajo1 = ($total_avance1 * $avance);
									$destajo2 = ($total_avance2 * $avance);
								?>
								<td colspan="3" class="cantidad_total" style="mso-number-format:'Currency'; background-color:yellow;"><?php echo $destajo1; ?></td>
								<td></td>
								<td colspan="3" class="cantidad_total" style="mso-number-format:'Currency'; background-color:yellow;"><?php echo $destajo2; ?></td>
								<td></td>
								<td align="center" width="80"><font face="Calibri"><strong><u>EXTRAS</u></strong></font></td><td width="10"></td><td width="220"></td>
							</tr>
							<tr height="6"></tr>
							<tr>
								<td></td><td></td><td></td><td></td><td></td>
								<td class="texto_totales">SUELDO BASE</td>
								<td></td>
								<?php $sb = obtenerExtras_SD($fecha_ini, $fecha_fin, $datos["nombre"],"sueldo_base"); ?>
								<td colspan="7" style="mso-number-format:'Currency';" class="cantidad_total"><?php echo $sb; ?></td>
								<td></td>
								<?php $extra = obtenerExtras_SD($fecha_ini, $fecha_fin, $datos["nombre"],"extra"); ?>
								<td class="cantidad_total" style="font-weight: normal; mso-number-format:'Currency'">0</td>
								<td></td>
								<td class="cantidad_total" style='font-weight: normal;'></td>
							</tr>
							<tr height="6"></tr>
							<tr>
								<td></td><td></td><td></td><td></td><td></td>
								<td class="texto_totales">TOTAL</td>
								<td></td>
								<?php 
									$total = ($destajo2 + $destajo1 + $sb);
								?>
								<td colspan="7" style="background-color:#33CC66; font-size: 19px; mso-number-format:'Currency';" class="cantidad_total"><?php echo $total; ?></td>
							</tr>
							<tr height="6"></tr>
							<tr height="6">
								<td style="border-style: solid; border-width: 20px; background-color:#000000;"></td>
								<td style="border-style: solid; border-width: 20px; background-color:#000000;"></td>
								<td style="border-style: solid; border-width: 20px; background-color:#000000;"></td>
								<td style="border-style: solid; border-width: 20px; background-color:#000000;"></td>
								<td style="border-style: solid; border-width: 20px; background-color:#000000;"></td>
								<td style="border-style: solid; border-width: 20px; background-color:#000000;"></td>
								<td style="border-style: solid; border-width: 20px; background-color:#000000;"></td>
								<td style="border-style: solid; border-width: 20px; background-color:#000000;"></td>
								<td style="border-style: solid; border-width: 20px; background-color:#000000;"></td>
								<td style="border-style: solid; border-width: 20px; background-color:#000000;"></td>
								<td style="border-style: solid; border-width: 20px; background-color:#000000;"></td>
								<td style="border-style: solid; border-width: 20px; background-color:#000000;"></td>
								<td style="border-style: solid; border-width: 20px; background-color:#000000;"></td>
								<td style="border-style: solid; border-width: 20px; background-color:#000000;"></td>
								<td style="border-style: solid; border-width: 20px; background-color:#000000;"></td>
								<td style="border-style: solid; border-width: 20px; background-color:#000000;"></td>
								<td style="border-style: solid; border-width: 20px; background-color:#000000;"></td>
								<td style="border-style: solid; border-width: 20px; background-color:#000000;"></td>
								<td style="border-style: solid; border-width: 20px; background-color:#000000;"></td>
								<td style="border-style: solid; border-width: 20px; background-color:#000000;"></td>
							</tr>
					<?php 
							$total_topes1 = 0; $total_avance1 = 0;
							$total_topes2 = 0; $total_avance2 = 0;
						}
					$cadena = $datos["nombre_emp"];
					$datos2=mysql_fetch_array($rs_datos2);
					}while($datos=mysql_fetch_array($rs_datos)); ?>
			</table>
			</div>
			</body>
<?php	}
		//Cerrar la conexion con la BD		
		mysql_close($conn);		
	}//Fin de la Funcion guardarRepVoladurasExcel($hdn_consulta,$hdn_nomReporte)
	
	function guardarRepVoladuras($hdn_consulta,$hdn_msje){
		//Manejo de fechas
		include_once("../../includes/func_fechas.php");
		//Esribir datos de la pagina en excel
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=Reporte_Voladuras.xls");		
		//Realizar la conexion a la BD de Desarrollo
		$conn = conecta("bd_desarrollo");
		//Ejecutar la sentencia y almacena los datos de la consulta en la variable $rs (result set)
		$rs_datos = mysql_query($hdn_consulta);
			
		if($datos=mysql_fetch_array($rs_datos)){
			//Definir el estilo de la tabla y el encabezado que aparecera sobre la tabla?>
			<head>
				<style>					
					<!--
					body { font-family:Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; }
					/*En este formato  se establece el tamaño,tipo de letra,color de fondo que ocupa la columna princiaplal de una tebla*/
					.nombres_columnas { font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #FFFFFF; background-color: #9BBA59; font-weight: bold; border-top-width: medium; border-right-width: medium;
										border-bottom-width: medium; border-left-width: medium; border-top-style: solid; border-right-style: solid; border-bottom-style: solid; border-left-style: solid; 
										border-top-color: #000000; border-bottom-color: #000000;}
					/*En este formato  se establece el tamaño,tipo de letra,color de fondo que se ocupa en la fila principal para resaltar datos  de una tabla*/
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
						<td align="left" valign="baseline" colspan="3"><img src="http://<?php echo HOST; ?>/<?php echo SISAD; ?>/images/logo.png" width="118" height="58" align="absbottom" /></td>
						<td colspan="10">&nbsp;</td>
						<td valign="baseline" colspan="3">
							<div align="right"><span class="texto_encabezado">
								<strong>MANUAL DE PROCEDIMIENTOS DE LA CALIDAD</strong><br><em>CONCRETO LANZADO DE FRESNILLO, S.A DE C.V.</em>
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
						<td colspan="16" align="center" class="titulo_tabla"><?php echo $hdn_msje;?></td>
					</tr>
					<tr>
						<td colspan="16">&nbsp;</td>
					</tr>			
					<tr>						
						<td align="center" class="nombres_columnas" rowspan="2">FECHA</td>						
        				<td align="center" class="nombres_columnas" rowspan="2">TURNO</td>
        				<td align="center" class="nombres_columnas" rowspan="2">PUESTO</td>
						<td align="center" class="nombres_columnas" rowspan="2">NOMBRE</td>
						<td align="center" class="nombres_columnas" rowspan="2">EQUIPO</td>
						<td align="center" class="nombres_columnas" colspan="3">HOR&Oacute;METRO</td>
						<td align="center" class="nombres_columnas" colspan="4">AVANCE</td>
						<td align="center" class="nombres_columnas" rowspan="2">LONGITUD<br>BARRENO<br>CARGA</td>
						<td align="center" class="nombres_columnas" rowspan="2">FACTOR CARGA</td>
						<td align="center" class="nombres_columnas" rowspan="2">TOPE CARGADO</td>
						<td align="center" class="nombres_columnas" colspan="2">OBSERVACIONES</td>
      				</tr>
					<tr>
						<td align="center" class="nombres_columnas">INICIAL</td>						
        				<td align="center" class="nombres_columnas">FINAL</td>
        				<td align="center" class="nombres_columnas">HORAS<br>TOTALES</td>
						
						<th class='nombres_columnas' align='center'>OBRA</th>
						<th class='nombres_columnas' align='center'>MACHOTE</th>
						<th class='nombres_columnas' align='center'>MEDIDA</th>
						<th class='nombres_columnas' align='center'>AVANCE</th>
						
						<td align="center" class="nombres_columnas">VOLADURA</td>
						<td align="center" class="nombres_columnas">AVANCE</td>
      				</tr>
			<?php
			$nom_clase = "renglon_gris";
			$cont = 1;
			$cant_total = 0;
						
			do{
				if($cont%2!=0)
					$row = 2;
				else
					$row = 1;
				
				if ($row==2){
			?>
					<tr>
						<td align="center" class="nombres_filas" rowspan='<?php echo $row?>'><?php echo modFecha($datos["fecha"],1)?></td>
						<td align="center" class="<?php echo $nom_clase; ?>" rowspan='<?php echo $row?>'><?php echo $datos["turno"]; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos["puesto"]; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos["nombre_emp"]; ?></td>
						
						<td align="center" class="<?php echo $nom_clase; ?>" rowspan='<?php echo $row?>'><?php echo $datos["id_equipo"]; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>" rowspan='<?php echo $row?>'><?php echo $datos["horo_ini"];?></td>												
						<td align="center" class="<?php echo $nom_clase; ?>" rowspan='<?php echo $row?>'><?php echo $datos["horo_fin"]; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>" rowspan='<?php echo $row?>'><?php echo $datos["horas_totales"]; ?></td>
						
						<td class='<?php echo $nom_clase?>' align='center' rowspan='<?php echo $row?>'><?php echo $datos["obra"]?></td>
						<td class='<?php echo $nom_clase?>' align='center' rowspan='<?php echo $row?>'><?php echo $datos["machote"]?></td>
						<td class='<?php echo $nom_clase?>' align='center' rowspan='<?php echo $row?>'><?php echo $datos["medida"]?></td>
						<td class='<?php echo $nom_clase?>' align='center' rowspan='<?php echo $row?>'><?php echo $datos["avance"]?></td>
									
						<td align="center" class="<?php echo $nom_clase; ?>" rowspan='<?php echo $row?>'><?php echo $datos["long_barreno_carg"]; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>" rowspan='<?php echo $row?>'><?php echo $datos["factor_carga"]; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>" rowspan='<?php echo $row?>'><?php echo $datos["topes_cargados"]; ?></td>

						<td align="center" class="<?php echo $nom_clase; ?>" rowspan='<?php echo $row?>'><?php echo $datos["obsVol"]; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>" rowspan='<?php echo $row?>'><?php echo $datos["obsAva"]; ?></td>
					</tr>
				<?php
				}
				else{
				?>
					<tr>
						<td class='<?php echo $nom_clase?>' align='center'><?php echo $datos["puesto"]?></td>
						<td class='<?php echo $nom_clase?>' align='center'><?php echo $datos["nombre_emp"]?></td>
					<tr>
				<?php }
				//Determinar el color del siguiente renglon a dibujar
				$cont++;	
			}while($datos=mysql_fetch_array($rs_datos)); ?>
			</table>
			</div>
			</body>
<?php	}
		//Cerrar la conexion con la BD		
		mysql_close($conn);		
	}//Fin de la Funcion guardarRepVoladuras($hdn_consulta,$hdn_nomReporte)
	
	function guardarRepAvance($hdn_consulta,$hdn_consultaMP,$hdn_msje){
		//Manejo de fechas
		include_once("../../includes/func_fechas.php");
		//Esribir datos de la pagina en excel
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=Reporte_Avance.xls");		
		//Realizar la conexion a la BD de Desarrollo
		$conn = conecta("bd_desarrollo");
		//Definir el estilo de la tabla y el encabezado que aparecera sobre la tabla?>
		<head>
			<style>					
				<!--
				body { font-family:Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; }
				/*En este formato  se establece el tamaño,tipo de letra,color de fondo que ocupa la columna princiaplal de una tebla*/
				.nombres_columnas { font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #FFFFFF; background-color: #9BBA59; font-weight: bold; border-top-width: medium; border-right-width: medium;
									border-bottom-width: medium; border-left-width: medium; border-top-style: solid; border-right-style: solid; border-bottom-style: solid; border-left-style: solid; 
									border-top-color: #000000; border-bottom-color: #000000;}
				/*En este formato  se establece el tamaño,tipo de letra,color de fondo que se ocupa en la fila principal para resaltar datos  de una tabla*/
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
					<td align="left" valign="baseline"><img src="http://<?php echo HOST; ?>/<?php echo SISAD; ?>/images/logo.png" width="118" height="58" align="absbottom" /></td>
					<td>&nbsp;</td>
					<td valign="baseline" colspan="3">
						<div align="right"><span class="texto_encabezado">
							<strong>MANUAL DE PROCEDIMIENTOS DE LA CALIDAD</strong><br><em>CONCRETO LANZADO DE FRESNILLO, S.A DE C.V.</em>
						</span></div>
					</td>
				</tr>											
				<tr>
					<td colspan="5" align="center" class="borde_linea">
						<span class="sub_encabezado">CONFIDENCIAL, PROPIEDAD DE &ldquo;CONCRETO LANZADO DE FRESNILLO, S.A DE C.V.&rdquo; PROHIBIDA SU REPRODUCCI&Oacute;N TOTAL O PARCIAL</span>
					</td>
				</tr>					
				<tr>
					<td colspan="5">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="5">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="5" align="center" class="titulo_tabla"><?php echo $hdn_msje;?></td>
				</tr>
				<tr>
					<td colspan="5">&nbsp;</td>
				</tr>			
				<tr>						
					<td align="center" class="nombres_columnas">OBRA</td>						
					<td align="center" class="nombres_columnas">DISPAROS TOTALES</td>
					<td align="center" class="nombres_columnas">MACHOTE</td>
					<td align="center" class="nombres_columnas">MEDIDA</td>
					<td align="center" class="nombres_columnas">AVANCE</td>
				</tr>
		<?php
		//Ejecutar la Sentencia de Avance con Jumbo
		$rsJu = mysql_query($hdn_consulta);
		//Ejecutar la Sentencia de Avance con Maquina de Pierna
		$rsMP = mysql_query($hdn_consultaMP);
		//Obtener el numero de resultados para Jumbo
		$tamJu=mysql_num_rows($rsJu);
		//Obtener el numero de resultados para Maquina de Pierna
		$tamMP=mysql_num_rows($rsMP);

		if ($tamJu>=$tamMP){
			//Confirmar que la consulta de datos de Jumbo fue realizada con exito.
			if($datosJu=mysql_fetch_array($rsJu)){
				do{
					$avance[]=array("id_ubicacion"=>$datosJu["id_ubicacion"],"obra"=>$datosJu["obra"],"disp"=>$datosJu["disp"],"machote"=>$datosJu["machote"],"medida"=>$datosJu["medida"],"avance"=>$datosJu["avance"]);
				}while($datosJu=mysql_fetch_array($rsJu));
			}
	
			//Confirmar que la consulta de datos de Maquina de Pierna fue realizada con exito.
			if($datosMP=mysql_fetch_array($rsMP)){
				do{
					//Variable que indica si se agrego o no un Registro
					$band=0;
					$cont=0;
					do{
						if($avance[$cont]["id_ubicacion"]==$datosMP["id_ubicacion"]){
							$avance[$cont]["disp"]+=$datosMP["disp"];
							$band=1;
						}
						$cont++;
					}while($cont<$tamJu);
					if ($band==0)
						$avance[]=array("id_ubicacion"=>$datosMP["id_ubicacion"],"obra"=>$datosMP["obra"],"disp"=>$datosMP["disp"],"machote"=>$datosMP["machote"],"medida"=>$datosMP["medida"],"avance"=>$datosMP["avance"]);
				}while($datosMP=mysql_fetch_array($rsMP));
			}
		}
		
		else{
			//Confirmar que la consulta de datos de Jumbo fue realizada con exito.
			if($datosMP=mysql_fetch_array($rsMP)){
				do{
					$avance[]=array("id_ubicacion"=>$datosMP["id_ubicacion"],"obra"=>$datosMP["obra"],"disp"=>$datosMP["disp"],"machote"=>$datosMP["machote"],"medida"=>$datosMP["medida"],"avance"=>$datosMP["avance"]);
				}while($datosMP=mysql_fetch_array($rsMP));
			}
	
			//Confirmar que la consulta de datos de Maquina de Pierna fue realizada con exito.
			if($datosJu=mysql_fetch_array($datosJu)){
				do{
					//Variable que indica si se agrego o no un Registro
					$band=0;
					$cont=0;
					do{
						if($avance[$cont]["id_ubicacion"]==$datosJu["id_ubicacion"]){
							$avance[$cont]["disp"]+=$datosJu["disp"];
							$band=1;
						}
						$cont++;
					}while($cont<$tamMP);
					if ($band==0)
						$avance[]=array("id_ubicacion"=>$datosJu["id_ubicacion"],"obra"=>$datosJu["obra"],"disp"=>$datosJu["disp"],"machote"=>$datosJu["machote"],"medida"=>$datosJu["medida"],"avance"=>$datosJu["avance"]);
				}while($datosJu=mysql_fetch_array($rsJu));
			}
		}
		$nom_clase = "renglon_gris";
		$cont = 1;
		$cont=0;
		$tam=count($avance);
			do{	
				?>
				<tr>
					<td align="center" class="nombres_filas"><?php echo $avance[$cont]["obra"];?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $avance[$cont]["disp"];?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $avance[$cont]["machote"]; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $avance[$cont]["medida"]; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $avance[$cont]["avance"]; ?></td>	
				</tr>
				<?php
				//Determinar el color del siguiente renglon a dibujar
				$cont++;	
			}while($cont<$tam); ?>
			</table>
			</div>
			</body>
<?php
		//Cerrar la conexion con la BD		
		mysql_close($conn);
	}//Fin de la funcion guardarRepAvance($hdn_consulta,$hdn_consultaMP,$hdn_msje)
	
	//Esta funcion exporta el Reporte de Servicios a un archivo de excel
	function guardarRepServicios($hdn_consulta,$hdn_msje){
		//Manejo de fechas
		include_once("../../includes/func_fechas.php");
		//Esribir datos de la pagina en excel
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=Reporte_Servicios.xls");		
		//Realizar la conexion a la BD de Desarrollo
		$conn = conecta("bd_desarrollo");
		//Ejecutar la sentencia y almacena los datos de la consulta en la variable $rs (result set)
		$rs_datos = mysql_query($hdn_consulta);
			
		if($datos=mysql_fetch_array($rs_datos)){
			//Definir el estilo de la tabla y el encabezado que aparecera sobre la tabla?>
			<head>
				<style>					
					<!--
					body { font-family:Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; }
					/*En este formato  se establece el tamaño,tipo de letra,color de fondo que ocupa la columna princiaplal de una tebla*/
					.nombres_columnas { font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #FFFFFF; background-color: #9BBA59; font-weight: bold; border-top-width: medium; border-right-width: medium;
										border-bottom-width: medium; border-left-width: medium; border-top-style: solid; border-right-style: solid; border-bottom-style: solid; border-left-style: solid; 
										border-top-color: #000000; border-bottom-color: #000000;}
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
					.texto_slogan {font-family: "Script MT Bold"; font-size: 30px; color: #000;}
					.texto_datos {font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #000;}
					-->
				</style>
			</head>											
			<body>
			<div id="tabla">				
				<table width="1100">					
					<!--
					Reporte con el Encabezado de Manual de Procedimientos de la Calidad
					<tr>
						<td align="left" valign="baseline"><img src="http://<?php echo HOST; ?>/<?php echo SISAD; ?>/images/logo.png" width="118" height="58" align="absbottom" /></td>
						<td>&nbsp;</td>
						<td valign="baseline" colspan="3">
							<div align="right"><span class="texto_encabezado">
								<strong>MANUAL DE PROCEDIMIENTOS DE LA CALIDAD</strong><br><em>CONCRETO LANZADO DE FRESNILLO, S.A DE C.V.</em>
							</span></div>
						</td>
					</tr>											
					<tr>
						<td colspan="5" align="center" class="borde_linea">
							<span class="sub_encabezado">CONFIDENCIAL, PROPIEDAD DE &ldquo;CONCRETO LANZADO DE FRESNILLO, S.A DE C.V.&rdquo; PROHIBIDA SU REPRODUCCI&Oacute;N TOTAL O PARCIAL</span>
						</td>
					</tr>
					-->
					<!-- Reporte con el encabezado usado segun el documento de Reporte de Turnos Administrativos de Desarrollo-->
					<tr>
						<td colspan="2">&nbsp;</td>
						<td align="center"><p align="center">&nbsp;&nbsp;&nbsp;<img src="http://<?php echo HOST; ?>/<?php echo SISAD; ?>/images/logo.png" width="354" height="174" align="absbottom" /></p>
						</td>
						<td colspan="2">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="5">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="5">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="5" align="center" class="texto_datos"><em>CALLE TIRO SAN LUIS #2, COL. BELE&Ntilde;A. C.P. 99000 TEL Y FAX 01(493) 932-37-52 FRESNILLO, ZAC.</em></td>
					</tr>
					<tr>
						<td colspan="5" align="center" class="texto_datos"><a href="mailto:guillermo@concretolanzadodefresnillo.com">guillermo@concretolanzadodefresnillo.com</a></td>
					</tr>
					<!--Fin Reporte con el encabezado usado segun el documento de Reporte de Turnos Administrativos de Desarrollo-->
					<tr>
						<td colspan="5">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="5" align="right" class="texto_datos"><em>Fresnillo Zacatecas a <?php echo modFecha(date("Y-m-d"),2); ?><em></td>
					</tr>
					<tr>
						<td colspan="5">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="5" align="left" class="texto_datos"><em><?php echo $_POST["txt_dirigido"]; ?><em></td>
					</tr>
					<tr>
						<td colspan="5" align="left" class="texto_datos"><em><?php echo $_POST["txt_puesto"]; ?><em></td>
					</tr>
					<tr>
						<td colspan="5" align="left" class="texto_datos"><em><?php echo $_POST["txt_empresa"]; ?><em></td>
					</tr>
					<tr>
						<td colspan="5">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="5" align="center" class="titulo_tabla"><?php echo $hdn_msje;?></td>
					</tr>
					<tr>
						<td colspan="5">&nbsp;</td>
					</tr>			
					<tr>						
						<td align="center" class="nombres_columnas">FECHA</td>						
        				<td align="center" class="nombres_columnas">CATEGOR&Iacute;A</td>
        				<td align="center" class="nombres_columnas">ACTIVIDAD</td>
						<td align="center" class="nombres_columnas">TURNOS OFICIAL</td>
						<td align="center" class="nombres_columnas">TURNOS AYUDANTE</td>
      				</tr>
			<?php
			$nom_clase = "renglon_gris";
			$cont = 1;
			$cant_oficial = 0;
			$cant_ayudante = 0;
			do{
				$cant_oficial+=$datos["turnoOf"];
				$cant_ayudante+=$datos["turnoAy"];
			?>
					<tr>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo modFecha($datos["fecha"],1)?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos["categoria"]; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos["actividad"]; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos["turnoOf"]; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos["turnoAy"]; ?></td>
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
				<td>&nbsp;</td>
				<td class="titulo_tabla"><em>Total Ayudante</em></td>
				<td class="titulo_tabla" colspan="2" align="center"><?php echo $cant_oficial;?></td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td class="titulo_tabla"><em>Total Oficial</em></td>
				<td class="titulo_tabla" colspan="2" align="center"><?php echo $cant_ayudante;?></td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td class="titulo_tabla"><em>Total</em></td>
				<td class="titulo_tabla" colspan="2" align="center"><?php echo $cant_ayudante+$cant_oficial;?></td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td colspan="2" align="left" class="texto_datos"><em>Contratista</em></td>
				<td>&nbsp;</td>
				<td colspan="2" align="right" class="texto_datos"><em>Superintendente de Mina</em></td>
			</tr>
			<tr>
				<td colspan="2" align="left" class="texto_datos"><em><?php echo $_POST["txt_contratista"];?></em></td>
				<td>&nbsp;</td>
				<td colspan="2" align="right" class="texto_datos"><em><?php echo $_POST["txt_smina"];?></em></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td colspan="5" align="center" class="texto_datos"><em>Jefe de Secci&oacute;n Mina</em></td>
			</tr>
			<tr>
				<td colspan="5" align="center" class="texto_datos"><em><?php echo $_POST["txt_jmina"];?></em></td>
			</tr>
			<tr>
				<td colspan="5">&nbsp;</td>
			</tr>
			<tr>
				<td colspan="5">&nbsp;</td>
			</tr>
			<tr>
				<td colspan="5">&nbsp;</td>
			</tr>
			<tr>
				<td colspan="5" align="center" class="texto_slogan">Lo mejor en estabilizar taludes y obras mineras</td>
			</tr>
			</table>
			</div>
			</body>
<?php	}
		//Cerrar la conexion con la BD		
		mysql_close($conn);		
	}//Fin de la Funcion guardarRepServicios($hdn_consulta,$hdn_nomReporte)
	
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
	
	function obtenerDatoBonificacion($puesto, $area, $concpeto){
		//Conectarse con la BD de Desarrollo
		$conn = conecta("bd_desarrollo");
		
		$stm_sql = "SELECT bonificacion
					FROM catalogo_bonificacion
					WHERE puesto =  '$puesto'
					AND area =  '$area'
					AND concepto =  '$concpeto'";
		$rs = mysql_query($stm_sql);
		$datos = mysql_fetch_array($rs);
		
		return $datos[0];
		
		//Cerrar la conexion con la BD		
		mysql_close($conn);
	}//Fin de la funcion obtenerDatoBonificacion($puesto, $area, $concpeto)
	
	function obtenerDatoSumaBD($campo, $tabla, $union, $bit, $fecha, $nombre, $area){
		//Conectarse con la BD de Desarrollo
		$conn = conecta("bd_desarrollo");
		
		$stm_sql = "SELECT SUM(T1.$campo)
					FROM $tabla AS T1
					JOIN personal AS T2 ON T1.bitacora_avance_id_bitacora =T2.bitacora_avance_id_bitacora
					JOIN bitacora_avance AS T3 ON T1.bitacora_avance_id_bitacora =T3.id_bitacora
					WHERE T1.bitacora_avance_id_bitacora =  '$bit'
					AND T1.fecha =  '$fecha'
					AND T2.nombre = '$nombre'
					AND T2.area = '$area'";
		if($campo == "avance"){
		$stm_sql = "SELECT SUM(T3.$campo)
					FROM $tabla AS T1
					JOIN personal AS T2 ON T1.bitacora_avance_id_bitacora =T2.bitacora_avance_id_bitacora
					JOIN bitacora_avance AS T3 ON T1.bitacora_avance_id_bitacora =T3.id_bitacora
					WHERE T1.bitacora_avance_id_bitacora =  '$bit'
					AND T1.fecha =  '$fecha'
					AND T2.nombre = '$nombre'
					AND T2.area = '$area'";
		}
		$rs = mysql_query($stm_sql);
		$datos = mysql_fetch_array($rs);
		
		return $datos[0];
		
		//Cerrar la conexion con la BD		
		mysql_close($conn);
	}//Fin de la funcion obtenerDatoSumaBD($campo, $tabla, $busq1, $busq2)
	
	function obtenerExtras_SD($fechaI, $fechaf, $id_emp, $concepto){
		//Conectarse con la BD de Desarrollo
		$conn = conecta("bd_desarrollo");
		
		$stm_sql = "SELECT T1.id_nomina, T2.sueldo_diario, T2.sueldo_base,T2.horas_extra, T2.guarda_8hrs, T2.guarda_12hrs
					FROM nominas AS T1
					JOIN detalle_nominas AS T2
					USING ( id_nomina ) 
					WHERE T1.fecha_inicio =  '$fechaI'
					AND T1.fecha_fin =  '$fechaf'
					AND T2.id_empleados_empresa =  '$id_emp'";
		$rs = mysql_query($stm_sql);
		$datos = mysql_fetch_array($rs);
		$extra = $datos["horas_extra"] * ($datos["sueldo_diario"] / 8);
		if($datos["guarda_8hrs"] == 1){
			$extra += 350;
		} else if($datos["guarda_12hrs"] == 1){
			$extra += 500;
		}
		$sb = $datos["sueldo_base"];
		
		if($concepto == "extra")
			return $extra;
		else
			return $sb;
		
		//Cerrar la conexion con la BD		
		mysql_close($conn);
	}//Fin de la funcion obtenerExtras_SD($fechaI, $fechaf, $id_emp, $concepto)
	
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