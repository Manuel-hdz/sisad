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
	
	function guardarRepSalidaDetalle($fechaI,$fechaC,$orden){
		//Esribir datos de la pagina en excel
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=ReporteDetalleSalidas.xls");
		
		$cadena =  obtenerCentroCostos('GOMAR');
		
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
					.nombres_columnas_gomar { font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #FFFFFF; background-color: #EDBE02; font-weight: bold; border-top-width: medium; border-right-width: thin;
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
						<td class='nombres_columnas_gomar'>PEDIDO</td>
						<td class='nombres_columnas_gomar'>FECHA PEDIDO</td>		
						<td class='nombres_columnas_gomar'>DEPARTAMENTO</td>
						<td class='nombres_columnas_gomar'>CENTRO COSTOS</td>
						<td class='nombres_columnas_gomar'>CUENTA</td>
						<td class='nombres_columnas_gomar'>SUBCUENTA</td>
						<td class='nombres_columnas_gomar'>CLAVE MATERIAL</td>
						<td class='nombres_columnas_gomar'>NOMBRE MATERIAL</td>
						<td class='nombres_columnas_gomar'>UNIDAD MEDIDA</td>
						<td class='nombres_columnas_gomar'>EXISTENCIA EN STOCK</td>
						<td class='nombres_columnas_gomar'>CLAVE ENTRADA</td>
						<td class='nombres_columnas_gomar'>FECHA ENTRADA</td>
						<td class='nombres_columnas_gomar'>CANTIDAD ENTRADA</td>
						<th class='nombres_columnas_gomar'>CLAVE SALIDA</th>
						<th class='nombres_columnas_gomar'>FECHA SALIDA</th>
						<th class='nombres_columnas_gomar'>DEPARTAMENTO SOLICITANTE</th>
						<th class='nombres_columnas_gomar'>SOLICITANTE</th>
						<th class='nombres_columnas_gomar'>DESTINO</th>
						<th class='nombres_columnas_gomar'>TURNO</th>
						<td class='nombres_columnas_gomar'>NO. VALE</td>
						<td class='nombres_columnas_gomar'>EQUIPO DESTINO</td>
						<td class='nombres_columnas_gomar'>CANTIDAD SALIDA</td>
						<td class='nombres_columnas_gomar'>COSTO UNITARIO</td>
						<td class='nombres_columnas_gomar'>COSTO TOTAL</td>
						<td class='nombres_columnas_gomar'>TIPO MONEDA</td>
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
					<td class='nombres_columnas_gomar' rowspan='3'>TOTAL</td>
					<td class='nombres_columnas_gomar'>$".number_format($cant_total_pesos,2,".",",")."</td>
					<td class='nombres_columnas_gomar'>PESOS</td>
				</tr>
				<tr>
					<td colspan='22'>&nbsp;</td>
					<td class='nombres_columnas_gomar'>$".number_format($cant_total_dolares,2,".",",")."</td>
					<td class='nombres_columnas_gomar'>DOLARES</td>
				</tr>
				<tr>
					<td colspan='22'>&nbsp;</td>
					<td class='nombres_columnas_gomar'>$".number_format($cant_total_euros,2,".",",")."</td>
					<td class='nombres_columnas_gomar'>EUROS</td>
				</tr>
				<tr>
					<td colspan='23'>&nbsp;</td>
					<td class='nombres_columnas_gomar'>$".number_format($cant_total,2,".",",")."</td>
				</tr>";
			?>
			</table>
			</div>
			</body>
<?php	}
		
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
				.nombres_columnas_gomar { font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #FFFFFF; background-color: #EDBE02; font-weight: bold; border-top-width: medium; border-right-width: thin;
											border-bottom-width: medium; border-left-width: thin; border-top-style: solid; border-right-style: none; border-bottom-style: solid; border-left-style: none; 
											border-top-color: #000000; border-bottom-color: #000000;vertical-align:middle;text-align:center;}
				/*En este formato  se establece el tamaño,tipo de letra,color de fondo que se ocupa en la fila principal para resaltar datos  de una tebla*/
				.nombres_filas { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #9BBB59;vertical-align:middle;text-align:center;}
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
						<td class='nombres_columnas_gomar' align="center">ID REQUISICI&Oacute;N</td>
						<td class='nombres_columnas_gomar' align="center">DEPARTAMENTO</td>
						<td class='nombres_columnas_gomar' align="center">FECHA</td>
						<td class='nombres_columnas_gomar' align="center">SOLICIT&Oacute;</td>
						<td class='nombres_columnas_gomar' align="center">REALIZ&Oacute;</td>
						<td class='nombres_columnas_gomar' align="center">ESTADO</td>
						<td class='nombres_columnas_gomar' align="center">PRIORIDAD</td>
						<td class='nombres_columnas_gomar' align="center">TIEMPO DE ENTREGA</td>
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
				.nombres_columnas_gomar { font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #FFFFFF; background-color: #EDBE02; font-weight: bold; border-top-width: medium; border-right-width: thin;
											border-bottom-width: medium; border-left-width: thin; border-top-style: solid; border-right-style: none; border-bottom-style: solid; border-left-style: none; 
											border-top-color: #000000; border-bottom-color: #000000;vertical-align:middle;text-align:center;}
				/*En este formato  se establece el tamaño,tipo de letra,color de fondo que se ocupa en la fila principal para resaltar datos  de una tebla*/
				.nombres_filas { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #9BBB59;vertical-align:middle;text-align:center;}
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
						<td class='nombres_columnas_gomar' align='center'>ID REQUISICI&Oacute;N</td>
						<td class='nombres_columnas_gomar' align='center'>CANTIDAD</td>
						<td class='nombres_columnas_gomar' align='center'>UNIDAD DE MEDIDA</td>
						<td class='nombres_columnas_gomar' align='center'>DESCRIPCI&Oacute;N</td>
						<td class='nombres_columnas_gomar' align='center'>APLICACI&Oacute;N</td>
						<td class='nombres_columnas_gomar' align='center'>ESTADO</td>
						<td class='nombres_columnas_gomar' align='center'>TIEMPO DE ENTREGA</td>
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