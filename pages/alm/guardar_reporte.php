<?php 
	/**
	  * Nombre del M�dulo: Almac�n                                               
	  * Nombre Programador: Miguel Angel Garay Castro                            
	  * Fecha: 25/Octubre/2010                                      			
	  * Descripci�n: Este archivo contiene funciones para almacenar la informaci�n en una hoja de calculo de excel de las consultas realizadas y reportes generados como lo son:
	  *				 1. Reporte REA
	  *				 2. Reporte de Inventario
	  * 			 3. Reporte de Orden de Compra
	  * 			 4. Consultas realizadas al Catalogo de Almacen
	  *				 5. Consultas del Material que ha salido del Almacen
	  *				 6. Consultas del Material que ha salido del Almacen con detalle de la ultima Entrada y el Pedido asignado
	  **/
	 
	 
	 /**
      * Listado del contenido del programa                                            
      *   Includes: 
	        1. Modulo de conexion con la base de datos
			2. Modulo de operaciones con la BD
			3. Modulo de formato de Fechas*/
			include("../../includes/conexion.inc");
			include("../../includes/op_operacionesBD.php");
			include("../../includes/func_fechas.php");
			
	/**   C�digo en: pages\alm\guardar_reporte.php                                   
      **/
	  		
	
	if(isset($_POST['hdn_consulta'])){
		//Ubicacion de las imagenes que estan contenidas en los encabezados
		define("HOST", $_SERVER['HTTP_HOST']);
		//Obtener el nombre del Nombre de la Carpeta Ra�z donde se encontrar� almacenado el SISAD
		$raiz = explode("/",$_SERVER['PHP_SELF']);
		define("SISAD",$raiz[1]);	
		
		switch($hdn_tipoReporte){
			case "consulta":
				guardarRepConsulta($hdn_consulta,$hdn_nomReporte,$hdn_msg);
			break;
			case "inventario":
				guardarRepInventario($hdn_consulta,$hdn_nomReporte,$hdn_msg);
			break;
			case "orden_compra":
				guardarRepOrdenCompra($hdn_consulta,$hdn_nomReporte,$hdn_msg);
			break;
			case "rea":
				guardarRepREA($hdn_consulta,$hdn_nomReporte,$hdn_msg);
			break;
			case "salidas":
				guardarRepSalida($hdn_consulta,$hdn_nomReporte,$hdn_msg);
			break;
			case "salidasDetalle":
				guardarRepSalidaDetalle($hdn_fechaI,$hdn_fechaF,$hdn_orden);
			break;
			case "salidasAuditoria":
				guardarRepSalidaAuditoria($hdn_consulta,$hdn_consulta2,$hdn_consulta3,$hdn_msg);
			break;
			case "generarReporteSalidas":
				generarRepSalidaDetalle($hdn_fechaI,$hdn_fechaF,$hdn_orden);
			break;
			case "reporte_requisiciones":
				guardarRepRequisiciones($hdn_consulta,$hdn_nomReporte,$hdn_msg);
			break;
			case "reporte_detallerequisiciones":
				guardarRepDetalleReq($hdn_consulta,$hdn_nomReporte,$hdn_msg);
			break;
			case "salidasDetalleCat":
				guardarRepSalidaDetalleCat($hdn_fechaI,$hdn_fechaF);
			break;
			case "ReporteSalidasCat":
				guardarRepSalidaCat($hdn_fechaI,$hdn_fechaF);
			break;
			case "reporteEntradas":
				guardarRepEntradas($hdn_consulta,$hdn_nomReporte,$hdn_msg);
			break;
		}
	}
	
	if(isset($_POST["sbt_exportarCSV"])){
		exportarCSVMateriales();
	}
	
	function exportarCSVMateriales(){
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=materiales.csv");
		$conn=conecta("bd_almacen");
		$linea=$_POST["cmb_lineaArticulo"];
		$rs=mysql_query("SELECT id_material,existencia,ubicacion FROM materiales WHERE linea_articulo='$linea'");
		if($datos=mysql_fetch_array($rs)){
			echo "ID MATERIAL,EXISTENCIA SISTEMA,UBICACION,EXISTENCIA REAL
";
			do{
				echo "$datos[id_material],$datos[existencia],$datos[ubicacion]
";
			}while($datos=mysql_fetch_array($rs));
		}
		mysql_close($conn);
	}
	
	function guardarRepEntradas($hdn_consulta, $hdn_nomReporte, $hdn_msg){
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=$hdn_nomReporte.xls");

		$conn = conecta("bd_almacen");

		$rs_datos = mysql_query($hdn_consulta);
		if ($datos = mysql_fetch_array($rs_datos)) {
			?>
			<head>
				<style>
					<!--
					body { font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; }
					.nombres_columnas { font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #FFFFFF; background-color: #9BBA59; font-weight: bold; border-top-width: medium; border-right-width: thin;
										border-bottom-width: medium; border-left-width: thin; border-top-style: solid; border-right-style: none; border-bottom-style: solid; border-left-style: none; 
										border-top-color: #000000; border-bottom-color: #000000; }
					.nombres_filas { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #9BBB59; }
					.renglon_gris { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #E7E7E7; }
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
							<td align="left" valign="baseline" colspan="3">
								<img src="http://<?php echo HOST; ?>/<?php echo SISAD; ?>/images/logo.png" width="118" height="58" align="absbottom" />
							</td>
							<td colspan="4">&nbsp;</td>
							<td valign="baseline" colspan="3">
								<div align="right">
									<span class="texto_encabezado">
										<strong>MANUAL  DE PROCEDIMIENTOS DE LA CALIDAD</strong><br><em>CONCRETO  LANZADO DE FRESNILLO, S.A DE C.V.</em>
									</span>
								</div>
							</td>
						</tr>
						<tr>
							<td colspan="10" align="center" class="borde_linea">
								<span class="sub_encabezado">
									CONFIDENCIAL, PROPIEDAD DE &ldquo;CONCRETO LANZADO DE FRESNILLO, S.A DE C.V.&rdquo; PROHIBIDA SU REPRODUCCI&Oacute;N TOTAL O PARCIAL
								</span>
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
							<td class='nombres_columnas'>Clave</td>
							<td class='nombres_columnas'>Origen</td>
							<td class='nombres_columnas'>No. Origen</td>
							<td class='nombres_columnas'>Proveedor</td>
							<td class='nombres_columnas'>No. Factura</td>
							<td class='nombres_columnas'>Costo Entrada</td>
							<td class='nombres_columnas'>Fecha</td>
							<td class='nombres_columnas'>Hora</td>
							<td class='nombres_columnas'>Aceptado</td>
							<td class='nombres_columnas'>Comentarios</td>
						</tr>
						<?php
						$nom_clase = "renglon_gris";
						$cont = 1;
						do {
							if($datos['requisiciones_id_requisicion']!=""){
								$noOrigen = $datos['requisiciones_id_requisicion'];
								if(substr($noOrigen,0,3)=="PED"){
									$origen="Pedido";
								} else {
									$origen = "Requisicion";
								}
							}
							if($datos['orden_compra_id_orden_compra']!=""){
								$origen = "Orden de Compra";
								$noOrigen = $datos['orden_compra_id_orden_compra'];
							}
							if($datos['comp_directa']!=""){
								$origen = "Compra Directa";
								$noOrigen = $datos['comp_directa'];
							}
							?>
							<tr>
								<td class='<?php echo $nom_clase; ?>'><?php echo $datos['id_entrada']; ?></td>
								<td class='<?php echo $nom_clase; ?>'><?php echo $origen; ?></td>
								<td class='<?php echo $nom_clase; ?>'><?php echo $noOrigen; ?></td>
								<td class='<?php echo $nom_clase; ?>'><?php echo $datos['proveedor']; ?></td>
								<td class='<?php echo $nom_clase; ?>'><?php echo $datos['no_factura']; ?></td>
								<td class='<?php echo $nom_clase; ?>'>$<?php echo number_format($datos['costo_total'],2,".",","); ?></td>
								<td class='<?php echo $nom_clase; ?>'><?php echo modFecha($datos['fecha_entrada'],1); ?></td>
								<td class='<?php echo $nom_clase; ?>'><?php echo $datos['hora_entrada']; ?></td>
								<td class='<?php echo $nom_clase; ?>'><?php echo $datos['aceptado']; ?></td>
								<td class='<?php echo $nom_clase; ?>'><?php echo $datos['comentarios']; ?></td>
							</tr>
							<?php
							$cont++;
							if($cont%2==0)
								$nom_clase = "renglon_blanco";
							else
								$nom_clase = "renglon_gris";
						} while ($datos = mysql_fetch_array($rs_datos));
						?>
					</table>
				</div>
			</body>
			<?php
		}
	}

	//Esta funcion exporte el REPORTE REA a un archivo de excel
	function guardarRepREA($hdn_consulta,$hdn_nomReporte,$hdn_msg){
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
					
					body { font-family:Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; }
					/*En este formato  se establece el tama�o,tipo de letra,color de fondo que ocupa la columna princiaplal de una tebla*/
					.nombres_columnas { font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #FFFFFF; background-color: #9BBA59; font-weight: bold; border-top-width: medium; border-right-width: thin;
										border-bottom-width: medium; border-left-width: thin; border-top-style: solid; border-right-style: none; border-bottom-style: solid; border-left-style: none; 
										border-top-color: #000000; border-bottom-color: #000000; }
					/*En este formato  se establece el tama�o,tipo de letra,color de fondo que se ocupa en la fila principal para resaltar datos  de una tebla*/
					.nombres_filas { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #9BBB59; }
					/*En este formato  se establece el tama�o,tipo de letra,color de fondo que contendra datos  de una tebla*/
					.renglon_gris { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #E7E7E7; }
					/*En este formato  se establece el tama�o,tipo de letra,color de fondo que contendra datos  de una tebla*/
					.renglon_blanco { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #FFFFFF; } 
					#tabla { position:absolute; left:0px; top:0px; width:1111px; height:175px; z-index:5; }
					.texto_encabezado {font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #0000CC; }
					.sub_encabezado {font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #0000CC; }
					.titulo_tabla {font-family: Arial, Helvetica, sans-serif; font-weight: bold; font-size: 14px; }
					.borde_linea { border-top:3px; border-top-color:#4E6128; border-top-style:solid;}
					
				</style>
			</head>											
			<body>
			<div id="tabla">				
				<table width="1100">					
					<tr>
						<td align="left" valign="baseline" colspan="3"><img src="http://<?php echo HOST; ?>/<?php echo SISAD; ?>/images/logo.png" width="118" height="58" align="absbottom" /></td>
						<td colspan="9">&nbsp;</td>
						<td valign="baseline" colspan="3">
							<div align="right"><span class="texto_encabezado">
								<strong>MANUAL  DE PROCEDIMIENTOS DE LA CALIDAD</strong><br><em>CONCRETO  LANZADO DE FRESNILLO, S.A DE C.V.</em>
							</span></div>
						</td>
					</tr>											
					<tr>
						<td colspan="15" align="center" class="borde_linea">
							<span class="sub_encabezado">CONFIDENCIAL, PROPIEDAD DE &ldquo;CONCRETO LANZADO DE FRESNILLO, S.A DE C.V.&rdquo; PROHIBIDA SU REPRODUCCI&Oacute;N TOTAL O PARCIAL</span>
						</td>
					</tr>					
					<tr>
						<td colspan="15">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="15">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="15" align="center" class="titulo_tabla"><?php echo $hdn_msg; ?></td>
					</tr>
					<tr>
						<td colspan="15">&nbsp;</td>
					</tr>			
					<tr>						
						<td align="center" class="nombres_columnas">CLAVE</td>						
        				<td align="left" class="nombres_columnas">NOMBRE (DESCRIPCION)</td>
        				<td align="center" class="nombres_columnas">UNIDAD DE MEDIDA</td>
						<td align="center" class="nombres_columnas">LINEA DEL ARTICULO (CATEGORIA)</td>
						<td align="center" class="nombres_columnas">CANTIDAD ENTRADA</td>
						<td align="center" class="nombres_columnas">COSTO UNIDAD</td>
						<td align="center" class="nombres_columnas">SUBTOTAL</td>
						<td align="center" class="nombres_columnas">MONEDA</td>
						<td align="center" class="nombres_columnas">PROVEEDOR</td>
						<td align="center" class="nombres_columnas">NO. FACTURA</td>
						<td align="center" class="nombres_columnas">OR&Iacute;GEN</td>
						<td align="center" class="nombres_columnas">NO. OR&Iacute;GEN</td>
						<td align="center" class="nombres_columnas">ACEPTADO</td>
						<td align="center" class="nombres_columnas">COMENTARIOS</td>																							
						<td align="center" class="nombres_columnas">CENTRO DE COSTOS</td>																							
      				</tr>
			<?php
			$nom_clase = "renglon_gris";
			$cont = 1;
			$cont_req=0;
			$cont_cd=0;
			$cont_oc=0;	
			$cant_total = 0;
			$pesosTotal=0; $dolaresTotal=0; $eurosTotal=0; $naTotal=0;
			do{	
				if($datos['requisiciones_id_requisicion']!=""){
					$origen="REQUISICI&Oacute;N";
					$no_origen=$datos["requisiciones_id_requisicion"];
					$cont_req++;
				}
				if($datos['orden_compra_id_orden_compra']!=""){
					$origen="ORDEN DE COMPRA";
					$no_origen=$datos["orden_compra_id_orden_compra"];
					$cont_oc++;
				}
				if($datos['comp_directa']!=""){
					$origen="COMPRA DIRECTA";
					$no_origen=$datos["comp_directa"];
					$cont_cd++;
				}
				$area = obtenerCentroCostos($no_origen,$datos['partida_pedido']);
				?>			
					<tr>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['materiales_id_material']; ?></td>
						<td align="left" class="<?php echo $nom_clase; ?>"><?php echo $datos['nom_material']; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['unidad_material']; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['linea_material']; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['cant_entrada']; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>">$<?php echo $datos['costo_unidad']; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>">$ <?php echo number_format($datos['costo_total'],2,".",",");?></td>												
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['tipo_moneda']; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['proveedor']; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['no_factura']; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $origen; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $no_origen; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['aceptado']; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['comentarios']; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $area; ?></td>
					</tr>
				<?php
				$cant_total += $datos['costo_total'];
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
				
				if($datos["tipo_moneda"] == "PESOS")
					$pesosTotal += $datos['costo_total'];
				
				else if($datos["tipo_moneda"] == "DOLARES")
					$dolaresTotal += $datos['costo_total'];
				
				else if($datos["tipo_moneda"] == "EUROS")
					$eurosTotal += $datos['costo_total'];
				
				else
					$naTotal += $datos['costo_total'];
				
			}while($datos=mysql_fetch_array($rs_datos));
			if($pesosTotal > 0){ ?>
			<tr>
				<td colspan="6">&nbsp;</td>
				<td class="nombres_columnas" align="right">$<?php echo number_format($pesosTotal,2,".",",")?></td>
				<td class="nombres_columnas">PESOS</td>
			</tr>
			<?php }
			if($dolaresTotal > 0){ ?>
			<tr>
				<td colspan="6">&nbsp;</td>
				<td class="nombres_columnas" align="right">$<?php echo number_format($dolaresTotal,2,".",",")?></td>
				<td class="nombres_columnas">DOLARES</td>
			</tr>
			<?php }
			if($eurosTotal > 0){ ?>
			<tr>
				<td colspan="6">&nbsp;</td>
				<td class="nombres_columnas" align="right">&euro;<?php echo number_format($eurosTotal,2,".",",")?></td>
				<td class="nombres_columnas">EUROS</td>
			</tr>
			<?php }
			if($naTotal > 0){ ?>
			<tr>
				<td colspan="6">&nbsp;</td>
				<td class="nombres_columnas" align="right">$<?php echo number_format($naTotal,2,".",",")?></td>
				<td class="nombres_columnas">N/A</td>
			</tr>
			<?php } ?>
			<tr>
				<td colspan="6">&nbsp;</td>
				<td class="nombres_columnas" align="right">$<?php echo number_format($cant_total,2,".",",")?></td>
				<td class="nombres_columnas">TOTAL</td>
			</tr>
			</table>
			</div>
			</body>
<?php	}
		//Cerrar la conexion con la BD		
		mysql_close($conn);		
	}//Fin de la Funcion guardarRepREA($hdn_consulta,$hdn_nomReporte)
	
	
	//Esta funcion exporta a Excel REPORTE DE ORDEN DE COMPRA
	function guardarRepOrdenCompra($hdn_consulta,$hdn_nomReporte,$hdn_msg){
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
					/*En este formato  se establece el tama�o,tipo de letra,color de fondo que ocupa la columna princiaplal de una tebla*/
					.nombres_columnas { font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #FFFFFF; background-color: #9BBA59; font-weight: bold; border-top-width: medium; border-right-width: thin;
										border-bottom-width: medium; border-left-width: thin; border-top-style: solid; border-right-style: none; border-bottom-style: solid; border-left-style: none; 
										border-top-color: #000000; border-bottom-color: #000000; }
					/*En este formato  se establece el tama�o,tipo de letra,color de fondo que se ocupa en la fila principal para resaltar datos  de una tebla*/
					.nombres_filas { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #9BBB59; }
					/*En este formato  se establece el tama�o,tipo de letra,color de fondo que contendra datos  de una tebla*/
					.renglon_gris { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #E7E7E7; }
					/*En este formato  se establece el tama�o,tipo de letra,color de fondo que contendra datos  de una tebla*/
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
						<td colspan="3">&nbsp;</td>
						<td valign="baseline" colspan="3">
							<div align="right"><span class="texto_encabezado">
								<strong>MANUAL  DE PROCEDIMIENTOS DE LA CALIDAD</strong><br><em>CONCRETO  LANZADO DE FRESNILLO, S.A DE C.V.</em>
							</span></div>
						</td>
					</tr>											
					<tr>
						<td colspan="9" align="center" class="borde_linea">
							<span class="sub_encabezado">CONFIDENCIAL, PROPIEDAD DE &ldquo;CONCRETO LANZADO DE FRESNILLO, S.A DE C.V.&rdquo; PROHIBIDA SU REPRODUCCI&Oacute;N TOTAL O PARCIAL</span>
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
						<td>&nbsp;</td>
						<td class="nombres_columnas" align="center">CLAVE</td>
        				<td class="nombres_columnas" align="center">NOMBRE (DESCRIPCION)</td>
				        <td class="nombres_columnas" align="center">CANTIDAD</td>
						<td class="nombres_columnas" align="center">ID ORDEN COMPRA</td>
						<td class="nombres_columnas" align="center">FECHA</td>
						<td class="nombres_columnas" align="center">AREA SOLICITANTE</td>
						<td class="nombres_columnas" align="center">SOLICITO</td>															
						<td>&nbsp;</td>
      				</tr>
			<?php
			$nom_clase = "renglon_gris";
			$cont = 1;
			do{	?>			
					<tr>
						<td>&nbsp;</td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['catalogo_mf_codigo_mf']; ?></td>
						<td align="left" class="<?php echo $nom_clase; ?>"><?php echo $datos['descripcion']; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['cant_oc']; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['id_orden_compra']; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['fecha_oc']; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['a_solicitante_oc']; ?></td>
						<td align="left" class="<?php echo $nom_clase; ?>"><?php echo $datos['solicitante_oc']; ?></td>												
						<td>&nbsp;</td>
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
	}//Fin de la Funcion guardarRepOrdenCompra($hdn_consulta,$hdn_nomReporte,$hdn_mensaje)
	

	//Esta funcion exporta el REPORTE DE INVENTARIO a un archivo de excel
	function guardarRepInventario($hdn_consulta,$hdn_nomReporte,$hdn_msg){
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
					/*En este formato  se establece el tama�o,tipo de letra,color de fondo que ocupa la columna princiaplal de una tebla*/
					.nombres_columnas { font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #FFFFFF; background-color: #9BBA59; font-weight: bold; border-top-width: medium; border-right-width: thin;
										border-bottom-width: medium; border-left-width: thin; border-top-style: solid; border-right-style: none; border-bottom-style: solid; border-left-style: none; 
										border-top-color: #000000; border-bottom-color: #000000; }
					/*En este formato  se establece el tama�o,tipo de letra,color de fondo que se ocupa en la fila principal para resaltar datos  de una tebla*/
					.nombres_filas { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #9BBB59; }
					/*En este formato  se establece el tama�o,tipo de letra,color de fondo que contendra datos  de una tebla*/
					.renglon_gris { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #E7E7E7; }
					/*En este formato  se establece el tama�o,tipo de letra,color de fondo que contendra datos  de una tebla*/
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
				<table width="1300">					
					<tr>
						<td align="left" valign="baseline" colspan="2"><img src="http://<?php echo HOST; ?>/<?php echo SISAD; ?>/images/logo.png" width="118" height="58" align="absbottom" /></td>
						<td colspan="9">&nbsp;</td>
						<td valign="baseline" colspan="3">
							<div align="right"><span class="texto_encabezado">
								<strong>MANUAL  DE PROCEDIMIENTOS DE LA CALIDAD</strong><br><em>CONCRETO  LANZADO DE FRESNILLO, S.A DE C.V.</em>
							</span></div>
						</td>
					</tr>											
					<tr>
						<td colspan="14" align="center" class="borde_linea">
							<span class="sub_encabezado">CONFIDENCIAL, PROPIEDAD DE &ldquo;CONCRETO LANZADO DE FRESNILLO, S.A DE C.V.&rdquo; PROHIBIDA SU REPRODUCCI&Oacute;N TOTAL O PARCIAL</span>
						</td>
					</tr>					
					<tr>
						<td colspan="14">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="14">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="14" align="center" class="titulo_tabla"><?php echo $hdn_msg; ?></td>
					</tr>
					<tr>
						<td colspan="14">&nbsp;</td>
					</tr>			
					<tr>
						<td align="center" class="nombres_columnas">CLAVE</td>
        				<td align="center" class="nombres_columnas">NOMBRE (DESCRIPCION)</td>
				        <td align="center" class="nombres_columnas">UNIDAD DE MEDIDA</td>
        				<td align="center" class="nombres_columnas">LINEA DEL ARTICULO (CATEGORIA)</td>
						<th align="center" class="nombres_columnas">GRUPO</th>
        				<td align="center" class="nombres_columnas">EXISTENCIA</td>
        				<td align="center" class="nombres_columnas">NIVEL MINIMO </td>	
						<td align="center" class="nombres_columnas">NIVEL MAXIMO </td>	
						<td align="center" class="nombres_columnas">COSTO UNITARIO</td>	
						<td align="center" class="nombres_columnas">COSTO TOTAL</td>
						<td align="center" class="nombres_columnas">MONEDA</td>
        				<td align="center" class="nombres_columnas">PROVEEDOR</td>
        				<td align="center" class="nombres_columnas">UBICACI&Oacute;N</td>																	
        				<td align="center" class="nombres_columnas">COMENTARIOS</td>
      				</tr>
			<?php
			$nom_clase = "renglon_gris";
			$cont = 1;
			$cantTotal=0;
			$pesosTotal=0; $dolaresTotal=0; $eurosTotal=0; $naTotal=0;
			do{	
				$unidad_medida=obtenerDato("bd_almacen","unidad_medida", "unidad_medida", "materiales_id_material", $datos['id_material']);
				$costoPiezas=$datos["existencia"]*$datos["costo_unidad"];
				$cantTotal+=$costoPiezas;
				?>			
					<tr>
						<td align="center" class="nombres_filas" style="mso-number-format:'\@';"><?php echo $datos['id_material']; ?></td>
						<td align="left" class="<?php echo $nom_clase; ?>"><?php echo $datos['nom_material']; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $unidad_medida; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['linea_articulo']; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['grupo']; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['existencia']; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['nivel_minimo']; ?></td>	
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['nivel_maximo']; ?></td>	
						<td align="center" class="<?php echo $nom_clase; ?>">$<?php echo number_format($datos['costo_unidad'],2,".",","); ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>">$<?php echo number_format($costoPiezas,2,".",","); ?></td>	
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['moneda']; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['proveedor']; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['ubicacion']; ?></td>																
						<td align="left" class="<?php echo $nom_clase; ?>"><?php echo $datos['comentarios']; ?></td>
					</tr>
				<?php
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
				
				if($datos["moneda"] == "PESOS")
					$pesosTotal += $costoPiezas;
				
				else if($datos["moneda"] == "DOLARES")
					$dolaresTotal += $costoPiezas;
				
				else if($datos["moneda"] == "EUROS")
					$eurosTotal += $costoPiezas;
				
				else
					$naTotal += $costoPiezas;
				
			}while($datos=mysql_fetch_array($rs_datos));
			if($pesosTotal > 0){ ?>
			<tr>
				<td colspan="9">&nbsp;</td>
				<td class="nombres_columnas" align="right">$<?php echo number_format($pesosTotal,2,".",",");?></td>
				<td class="nombres_columnas">PESOS</td>
			</tr>
			<?php }
			if($dolaresTotal > 0){ ?>
			<tr>
				<td colspan="9">&nbsp;</td>
				<td class="nombres_columnas" align="right">$<?php echo number_format($dolaresTotal,2,".",",");?></td>
				<td class="nombres_columnas">DOLARES</td>
			</tr>
			<?php }
			if($eurosTotal > 0){ ?>
			<tr>
				<td colspan="9">&nbsp;</td>
				<td class="nombres_columnas" align="right">&euro;<?php echo number_format($eurosTotal,2,".",",");?></td>
				<td class="nombres_columnas">EUROS</td>
			</tr>
			<?php }
			if($naTotal > 0){ ?>
			<tr>
				<td colspan="9">&nbsp;</td>
				<td class="nombres_columnas" align="right">$<?php echo number_format($naTotal,2,".",",");?></td>
				<td class="nombres_columnas">N/A</td>
			</tr>
			<?php } ?>
			<tr>
				<td colspan="9">&nbsp;</td>
				<td class="nombres_columnas" align="right">$<?php echo number_format($cantTotal,2,".",",");?></td>
				<td class="nombres_columnas">TOTAL</td>
			</tr>
			</table>
			</div>
			</body>
<?php	}
		
		//Cerrar la conexion con la BD		
		mysql_close($conn);		
	}//Fin de la Funcion guardarRepInventario($hdn_consulta,$hdn_nomReporte,$hdn_mensaje)

	
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
					/*En este formato  se establece el tama�o,tipo de letra,color de fondo que ocupa la columna princiaplal de una tebla*/
					.nombres_columnas { font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #FFFFFF; background-color: #9BBA59; font-weight: bold; border-top-width: medium; border-right-width: thin;
										border-bottom-width: medium; border-left-width: thin; border-top-style: solid; border-right-style: none; border-bottom-style: solid; border-left-style: none; 
										border-top-color: #000000; border-bottom-color: #000000; }
					/*En este formato  se establece el tama�o,tipo de letra,color de fondo que se ocupa en la fila principal para resaltar datos  de una tebla*/
					.nombres_filas { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #9BBB59; }
					/*En este formato  se establece el tama�o,tipo de letra,color de fondo que contendra datos  de una tebla*/
					.renglon_gris { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #E7E7E7; }
					/*En este formato  se establece el tama�o,tipo de letra,color de fondo que contendra datos  de una tebla*/
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
				<table width="1650">					
					<tr>
						<td align="left" valign="baseline" colspan="2"><img src="http://<?php echo HOST; ?>/<?php echo SISAD; ?>/images/logo.png" width="118" height="58" align="absbottom" /></td>
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
        				<td align="center" class="nombres_columnas">COSTO TOTAL</td>
        				<td align="center" class="nombres_columnas">NIVEL MINIMO </td>
        				<td align="center" class="nombres_columnas">NIVEL M&Aacute;XIMO </td>
        				<td align="center" class="nombres_columnas">PUNTO DE REORDEN </td>											
        				<td align="center" class="nombres_columnas">PROVEEDOR</td>
        				<td align="center" class="nombres_columnas">UBICACI&Oacute;N</td>
        				<td align="center" class="nombres_columnas">FECHA DE ALTA </td>	
        				<td align="center" class="nombres_columnas">FACTOR DE CONVERSI&Oacute;N </td>
        				<td align="center" class="nombres_columnas">UNIDAD DE DESPACHO</td>
        				<td align="center" class="nombres_columnas">APLICACION</td>
        				<td align="center" class="nombres_columnas">CLAVE MATERIAL EQUIVALENTE</td>
						<td align="center" class="nombres_columnas">MATERIAL EQUIVALENTE</td>
						<td align="center" class="nombres_columnas">PROVEEDOR DEL MATERIAL EQUIVALENTE</td>																
        				<td align="center" class="nombres_columnas">COMENTARIOS</td>
      				</tr>
			<?php
			$nom_clase = "renglon_gris";
			$cont = 1;
			do{	?>			
					<tr>
						<td align="center" class="nombres_filas" style="mso-number-format:'\@';"><?php echo $datos['id_material']; ?></td>
						<td align="left" class="<?php echo $nom_clase; ?>"><?php echo $datos['nom_material']; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['unidad_medida']; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['linea_articulo']; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['grupo']; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo "$ ".number_format($datos['costo_unidad'],2,".",","); ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['existencia']; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo "$ ".number_format($datos['costo_unidad'] * $datos['existencia'],2,".",","); ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['nivel_minimo']; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['nivel_maximo']; ?></td>	
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['re_orden']; ?></td>											
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['proveedor']; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['ubicacion']; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['fecha_alta']; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['factor_conv']; ?></td>	
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['unidad_despacho']; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['aplicacion']; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['clave_equivalente']; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['nombre']; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['equi_prov']; ?></td>
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
			</body>
<?php	}
		
		//Cerrar la conexion con la BD		
		mysql_close($conn);		
	}//Fin de la Funcion guardarRepConsulta($hdn_consulta,$hdn_nomReporte,$hdn_mensaje)
	
	//Esta funcion exporte el REPORTE REA a un archivo de excel
	function guardarRepSalida($hdn_consulta,$hdn_nomReporte,$hdn_msg){
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
					/*En este formato  se establece el tama�o,tipo de letra,color de fondo que ocupa la columna princiaplal de una tebla*/
					.nombres_columnas { font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #FFFFFF; background-color: #9BBA59; font-weight: bold; border-top-width: medium; border-right-width: thin;
										border-bottom-width: medium; border-left-width: thin; border-top-style: solid; border-right-style: none; border-bottom-style: solid; border-left-style: none; 
										border-top-color: #000000; border-bottom-color: #000000; }
					/*En este formato  se establece el tama�o,tipo de letra,color de fondo que se ocupa en la fila principal para resaltar datos  de una tebla*/
					.nombres_filas { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #9BBB59; }
					/*En este formato  se establece el tama�o,tipo de letra,color de fondo que contendra datos  de una tebla*/
					.renglon_gris { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #E7E7E7; }
					/*En este formato  se establece el tama�o,tipo de letra,color de fondo que contendra datos  de una tebla*/
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
						<td align="left" valign="baseline" colspan="2"><img src="http://<?php echo HOST; ?>/<?php echo SISAD; ?>/images/logo.png" width="118" height="58" align="absbottom" /></td>
						<td colspan="7">&nbsp;</td>
						<td valign="baseline" colspan="2">
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
						<td colspan="11" align="center" class="titulo_tabla"><?php echo $hdn_msg; ?></td>
					</tr>
					<tr>
						<td colspan="11">&nbsp;</td>
					</tr>			
					<tr>						
						<td colspan="3">&nbsp;</td>
						<td align="center" class="nombres_columnas">CLAVE</td>						
        				<td align="left" class="nombres_columnas">MATERIAL</td>
        				<td align="center" class="nombres_columnas">UNIDAD DE MEDIDA</td>
						<td align="center" class="nombres_columnas">CANTIDAD SALIDA</td>
						<td align="center" class="nombres_columnas">COSTO UNITARIO</td>
						<td align="center" class="nombres_columnas">COSTO TOTAL</td>
						<td align="center" class="nombres_columnas">ID EQUIPO</td>
						<td colspan="3">&nbsp;</td>
      				</tr>
			<?php
			$nom_clase = "renglon_gris";
			$cont = 1;	
			$cant_total = 0;				
			do{	
				$unidad_medida=obtenerDato("bd_almacen","unidad_medida", "unidad_medida", "materiales_id_material", $datos['materiales_id_material']);
				$idEquipo = "N/A";
				if($datos['id_equipo_destino']!="")
					$idEquipo = $datos['id_equipo_destino'];?>			
				
					<tr>
						<td colspan="3">&nbsp;</td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['materiales_id_material']; ?></td>
						<td align="left" class="<?php echo $nom_clase; ?>"><?php echo $datos['nom_material']; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $unidad_medida; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['cant_salida']; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>">$ <?php echo number_format($datos['costo_unidad'],2,".",",");?></td>
						<td align="center" class="<?php echo $nom_clase; ?>">$ <?php echo number_format($datos['costo_total'],2,".",",");?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $idEquipo; ?></td>
						<td colspan="3">&nbsp;</td>
					</tr>
				<?php
				
				$cant_total += $datos['costo_total'];
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
					
			}while($datos=mysql_fetch_array($rs_datos)); ?>
				<td colspan='7'>&nbsp;</td><td colspan="1" align="center" class="nombres_columnas">TOTAL</td>
				<td align="center" class="nombres_columnas">$ <?php echo number_format($cant_total,2,".",","); ?></td>
			</table>
			</div>
			</body>
<?php	}
		
		//Cerrar la conexion con la BD		
		mysql_close($conn);		
	}//Fin de la Funcion guardarRepREA($hdn_consulta,$hdn_nomReporte)
	
	function guardarRepSalidaDetalle($fechaI,$fechaC,$orden){
		//Esribir datos de la pagina en excel
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=ReporteDetalleSalidas.xls");
				
		//Realizar la conexion a la BD de Almacen
		$conn = conecta('bd_almacen');

		//Convertir Formato Fecha Sistema a formato Fecha Base de Datos para consultas
		$f1 = modFecha($fechaI,3);
		$f2 = modFecha($fechaC,3);
		
		$filtro_centro_costo = $_POST["hdn_cc"];
		$filtro_cuentas = $_POST["hdn_cuenta"];
		
		$msg = "Reporte de Salidas generadas del <strong><u>$fechaI</u></strong> al <strong><u>$fechaC</u></strong>";
		
		$vuelta = 1;
		$reporte = false;
		
		$nom_clase = "renglon_gris";
		$cont = 1;
		
		/*Variable para realizar la operacion y obtener el total de costo de los materiales a los cuales se le registro una salida*/
		$cant_total_pesos = 0;
		$cant_total_dolares = 0;
		$cant_total_euros = 0;
		$cant_total = 0;
		
		?>
		<head>
			<style>					
				<!--
				body { font-family:Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; }
				/*En este formato  se establece el tama�o,tipo de letra,color de fondo que ocupa la columna princiaplal de una tebla*/
				.nombres_columnas { font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #FFFFFF; background-color: #9BBA59; font-weight: bold; border-top-width: medium; border-right-width: thin;
									border-bottom-width: medium; border-left-width: thin; border-top-style: solid; border-right-style: none; border-bottom-style: solid; border-left-style: none; 
									border-top-color: #000000; border-bottom-color: #000000;vertical-align:middle;text-align:center;}
				/*En este formato  se establece el tama�o,tipo de letra,color de fondo que se ocupa en la fila principal para resaltar datos  de una tebla*/
				.nombres_filas { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #9BBB59;vertical-align:middle;text-align:center;}
				/*En este formato  se establece el tama�o,tipo de letra,color de fondo que contendra datos  de una tebla*/
				.renglon_gris { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #E7E7E7;vertical-align:middle;text-align:center;}
				/*En este formato  se establece el tama�o,tipo de letra,color de fondo que contendra datos  de una tebla*/
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
			<?php
			do{
				if($vuelta == 1){
					?>
					<div id="tabla">				
						<table width="1100">					
							<tr>
								<td align="left" valign="baseline" colspan="2"><img src="http://<?php echo HOST; ?>/<?php echo SISAD; ?>/images/logo.png" width="118" height="58" align="absbottom" /></td>
								<td colspan="8">&nbsp;</td>
								<td valign="baseline" colspan="2">
									<div align="right">
										<span class="texto_encabezado">
											<strong>MANUAL  DE PROCEDIMIENTOS DE LA CALIDAD</strong><br><em>CONCRETO  LANZADO DE FRESNILLO, S.A DE C.V.</em>
										</span>
									</div>
								</td>
							</tr>
							<tr>
								<td colspan="12" align="center" class="borde_linea">
									<span class="sub_encabezado">
										CONFIDENCIAL, PROPIEDAD DE &ldquo;CONCRETO LANZADO DE FRESNILLO, S.A DE C.V.&rdquo; PROHIBIDA SU REPRODUCCI&Oacute;N TOTAL O PARCIAL
									</span>
								</td>
							</tr>
							<tr>
								<td colspan="12">&nbsp;</td>
							</tr>
							<tr>
								<td colspan="12">&nbsp;</td>
							</tr>
							<tr>
								<td colspan="12" align="center" class="titulo_tabla">
									<?php echo "$msg"; ?>
								</td>
							</tr>
							<tr>
								<td colspan="12">&nbsp;</td>
							</tr>
							<tr>
								<th class='nombres_columnas'>SOLICITANTE</th>
								<th class='nombres_columnas'>EQUIPO</th>
								<th class='nombres_columnas'>CENTRO DE COSTOS</th>
								<th class='nombres_columnas'>CUENTA</th>
								<th class='nombres_columnas'>FECHA SALIDA</th>
								<th class='nombres_columnas'>TURNO</th>
								
								<th class='nombres_columnas'>MATERIAL</th>
								<th class='nombres_columnas'>CANTIDAD</th>
								<th class='nombres_columnas'>UNIDAD MEDIDA</th>
								
								<th class='nombres_columnas'>COSTO UNITARIO</th>
								<th class='nombres_columnas'>COSTO TOTAL</th>
								<th class='nombres_columnas'>MONEDA</th>
							</tr>
							<?php
					$stm_sql = "SELECT DISTINCT T1.id_salida, T1.fecha_salida, T1.solicitante, T1.destino, T1.turno, T1.cuentas, 
												T2.materiales_id_material, T2.nom_material, T2.unidad_material, T2.cant_salida, T2.costo_unidad, T2.costo_total, T2.id_equipo_destino, T2.moneda AS moneda_detalle, 
												T3.id_control_costos, T3.id_cuentas
												FROM salidas AS T1
												JOIN detalle_salidas AS T2 ON T1.id_salida = T2.salidas_id_salida
												LEFT JOIN bd_mantenimiento.equipos AS T3 ON T2.id_equipo_destino = T3.id_equipo
												WHERE T1.fecha_salida
												BETWEEN  '$f1'
												AND  '$f2'
												AND T3.id_control_costos = '$filtro_centro_costo'";
												
					/*							
					$stm_sql = "SELECT DISTINCT T1.id_salida, T1.fecha_salida, T1.solicitante, T1.destino, T1.turno, T1.cuentas, 
												T2.materiales_id_material, T2.nom_material, T2.unidad_material, T2.cant_salida, T2.costo_unidad, T2.costo_total, T2.id_equipo_destino, T2.moneda AS moneda_detalle, T1.tipo
												FROM salidas AS T1
												JOIN detalle_salidas AS T2 ON T1.id_salida = T2.salidas_id_salida
												WHERE T1.fecha_salida
												BETWEEN  '$f1'
												AND  '$f2'
												AND T1.destino =  '$filtro_centro_costo'
												ORDER BY  `T2`.`id_equipo_destino` ASC ";
					*/
				}
				else if($vuelta == 2){
					$stm_sql = "SELECT DISTINCT T1.id_salida, T1.fecha_salida, T1.solicitante, T1.destino, T1.turno, T1.cuentas, 
												T2.materiales_id_material, T2.nom_material, T2.unidad_material, T2.cant_salida, T2.costo_unidad, T2.costo_total, T2.id_equipo_destino, T2.moneda AS moneda_detalle 
												FROM salidas AS T1
												JOIN detalle_salidas AS T2 ON T1.id_salida = T2.salidas_id_salida
												WHERE T1.fecha_salida
												BETWEEN  '$f1'
												AND  '$f2'
												AND T1.destino = '$filtro_centro_costo'
												AND T2.id_equipo_destino = 'N/A'";
				}
				else if($vuelta == 3){
					$stm_sql = "SELECT DISTINCT T1.id_salida, T1.fecha_salida, T1.solicitante, T1.destino, T1.turno, T1.cuentas, 
												T2.materiales_id_material, T2.nom_material, T2.unidad_material, T2.cant_salida, T2.costo_unidad, T2.costo_total, T2.id_equipo_destino, T2.moneda AS moneda_detalle, T1.tipo,
												T4.id_control_costos, T4.id_cuentas 
												FROM salidas AS T1
												JOIN detalle_salidas AS T2 ON T1.id_salida = T2.salidas_id_salida
												JOIN detalle_es AS T3 ON T2.salidas_id_salida = T3.no_vale
												JOIN bd_recursos.empleados AS T4 ON T3.empleados_rfc_empleado = T4.rfc_empleado
												WHERE T1.fecha_salida
												BETWEEN  '$f1'
												AND  '$f2'
												AND T1.destino = 'EPP'
												AND T4.id_control_costos =  '$filtro_centro_costo'";
				}
				
				$rs = mysql_query($stm_sql);
				if($row = mysql_fetch_array($rs)){
					$reporte = true;
					//Mostramos los registros
					
					do{
						$id_salida=$row["id_salida"];
						mysql_close($conn);
						
						if($vuelta == 1 || $vuelta == 3){
							$centro_costos = obtenerDatosCentroCostos($row["id_control_costos"],"control_costos","id_control_costos");
							$cuenta = obtenerDatosCentroCostos($row["id_cuentas"],"cuentas","id_cuentas");
						}
						else if($vuelta == 2){
							$centro_costos = obtenerDatosCentroCostos($row["destino"],"control_costos","id_control_costos");
							$cuenta = obtenerDatosCentroCostos($row["cuentas"],"cuentas","id_cuentas");
						}
						
						$moneda = $row["moneda_detalle"];
						if($moneda == "")
							$moneda = obtenerDatoMaterial($row["materiales_id_material"],"materiales","moneda","id_material");
						
						$group_material = obtenerDatoMaterial($row["materiales_id_material"],"materiales","grupo","id_material");
						if($group_material == "")
							$group_material = "Sin Grupo";
						
						$conn = conecta('bd_almacen');
						
						echo "
							<tr>
								<td class='$nom_clase' title='Empleado al que se entrego el material'>$row[solicitante]</td>
								<td class='$nom_clase' title='Equipo al Que Va Destinado el Material'>$row[id_equipo_destino]</td>
								<td class='$nom_clase' title='Equipo al Que Va Destinado el Material'>$centro_costos</td>
								<td class='$nom_clase' title='Equipo al Que Va Destinado el Material'>$cuenta</td>
								<td class='$nom_clase' title='Fecha de Salida'>".modFecha($row['fecha_salida'],1)."</td>
								<td class='$nom_clase' title='Turno en el que el Material Sali&oacute;'>$row[turno]</td>
								<td class='$nom_clase' title='Material'>$row[nom_material]</td>
								<td class='$nom_clase' title='Cantidad'>$row[cant_salida]</td>
								<td class='$nom_clase' title='Unidad de Medida'>$row[unidad_material]</td>
								<td class='$nom_clase' title='Costo Unitario'>$".number_format($row["costo_unidad"],2,".",",")."</td>
								<td class='$nom_clase' title='Costo Total'>$".number_format($row["costo_total"],2,".",",")."</td>
								<td class='$nom_clase' title='Moneda'>$moneda</td>
							</tr>
							";
						//Operaci�n que mostrara el total del costo de los materiales
						if($moneda == "PESOS")
							$cant_total_pesos += $row['costo_total'];
						else if($moneda == "DOLARES")
							$cant_total_dolares += $row['costo_total'];
						else if($moneda == "EUROS")
							$cant_total_euros += $row['costo_total'];
						
						$cant_total += $row['costo_total'];
						
						//Determinar el color del siguiente renglon a dibujar
						$cont++;
						if($cont%2==0)
							$nom_clase = "renglon_blanco";
						else
							$nom_clase = "renglon_gris";						
					}while($row = mysql_fetch_array($rs));
				}
				$vuelta++;
			}while($vuelta <= 3);
			
			if($reporte){
				//Colocar el Check para cuando se selecciona VER TODO
				echo "
					<tr>
						<td colspan='9'>&nbsp;</td>
						<td class='nombres_columnas' rowspan='3'>TOTAL</td>
						<td class='nombres_columnas'>$".number_format($cant_total_pesos,2,".",",")."</td>
						<td class='nombres_columnas'>PESOS</td>
					</tr>
					<tr>
						<td colspan='9'>&nbsp;</td>
						<td class='nombres_columnas'>$".number_format($cant_total_dolares,2,".",",")."</td>
						<td class='nombres_columnas'>DOLARES</td>
					</tr>
					<tr>
						<td colspan='9'>&nbsp;</td>
						<td class='nombres_columnas'>$".number_format($cant_total_euros,2,".",",")."</td>
						<td class='nombres_columnas'>EUROS</td>
					</tr>";
				$band=1;
			}
			?>
						</table>
					</div>
		</body>
		
		<?php
		//Cerrar la conexion con la BD		
		mysql_close($conn);		
	}
	
	function guardarRepSalidaAuditoria($hdn_consulta,$hdn_consulta2,$hdn_consulta3,$hdn_msg){
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=ReporteSalidasAuditoria.xls");
		
		?>
		<head>
			<style>					
				<!--
				body { font-family:Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; }
				/*En este formato  se establece el tama�o,tipo de letra,color de fondo que ocupa la columna princiaplal de una tebla*/
				.nombres_columnas { font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #FFFFFF; background-color: #9BBA59; font-weight: bold; border-top-width: medium; border-right-width: thin;
									border-bottom-width: medium; border-left-width: thin; border-top-style: solid; border-right-style: none; border-bottom-style: solid; border-left-style: none; 
									border-top-color: #000000; border-bottom-color: #000000;vertical-align:middle;text-align:center;}
				.totales_columnas { font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #000000; background-color: #FFFF00; font-weight: bold; border-top-width: medium; border-right-width: thin;
									border-bottom-width: medium; border-left-width: thin; border-top-style: solid; border-right-style: none; border-bottom-style: solid; border-left-style: none; 
									border-top-color: #000000; border-bottom-color: #000000;vertical-align:middle;text-align:center;}
				/*En este formato  se establece el tama�o,tipo de letra,color de fondo que se ocupa en la fila principal para resaltar datos  de una tebla*/
				.nombres_filas { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #9BBB59;vertical-align:middle;text-align:center;}
				/*En este formato  se establece el tama�o,tipo de letra,color de fondo que contendra datos  de una tebla*/
				.renglon_gris { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #E7E7E7;vertical-align:middle;text-align:center;}
				/*En este formato  se establece el tama�o,tipo de letra,color de fondo que contendra datos  de una tebla*/
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
			<?php
			$cont = 1;
			$vuelta = 1;
			$bandera = 0;
			$nom_clase = "renglon_gris";
			?>
			<table class='tabla_frm' cellpadding='5' width='100%'>
				<tr>
					<td align="left" valign="baseline" colspan="2"><img src="http://<?php echo HOST; ?>/<?php echo SISAD; ?>/images/logo.png" width="118" height="58" align="absbottom" /></td>
					<td colspan="10">&nbsp;</td>
					<td valign="baseline" colspan="2">
						<div align="right">
							<span class="texto_encabezado">
								<strong>MANUAL  DE PROCEDIMIENTOS DE LA CALIDAD</strong><br><em>CONCRETO  LANZADO DE FRESNILLO, S.A DE C.V.</em>
							</span>
						</div>
					</td>
				</tr>
				<tr>
					<td colspan="14" align="center" class="borde_linea">
						<span class="sub_encabezado">
							CONFIDENCIAL, PROPIEDAD DE &ldquo;CONCRETO LANZADO DE FRESNILLO, S.A DE C.V.&rdquo; PROHIBIDA SU REPRODUCCI&Oacute;N TOTAL O PARCIAL
						</span>
					</td>
				</tr>
				<tr>
					<td colspan="14">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="14">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="14" align="center" class="titulo_tabla">
						<?php echo "$hdn_msg"; ?>
					</td>
				</tr>
				<tr>
					<td colspan="14">&nbsp;</td>
				</tr>
				<tr>
					<th class='nombres_columnas'>SOLICITANTE</th>
					<th class='nombres_columnas'>EQUIPO</th>
					<th class='nombres_columnas'>CENTRO DE COSTOS</th>
					<th class='nombres_columnas'>CUENTA</th>
					
					<th class='nombres_columnas'>MATERIAL</th>
					<th class='nombres_columnas'>CANTIDAD</th>
					<th class='nombres_columnas'>UNIDAD MEDIDA</th>
					
					<th class='nombres_columnas'>COSTO UNITARIO</th>
					<th class='nombres_columnas'>COSTO TOTAL</th>
					<th class='nombres_columnas'>MONEDA</th>
					<th class='nombres_columnas'>ESTATUS</th>
					
					<th class='nombres_columnas'>ENTRADAS</th>
					<th class='nombres_columnas'>SALIDA</th>
					<th class='nombres_columnas'>FECHA SALIDA</th>
					<th class='nombres_columnas'>TURNO</th>
				</tr>
				<?php 
				do{
					$conn = conecta('bd_almacen');
					if($vuelta == 1){
						$stm_sql = $hdn_consulta;
					} else if($vuelta == 2){
						$stm_sql = $hdn_consulta2;
					} else if($vuelta == 3){
						$stm_sql = $hdn_consulta3;
					}
					$rs = mysql_query($stm_sql);
					if($rs){
						while($row = mysql_fetch_array($rs)){
							
							$ent_ped = obtenerPedEntradas($row["entradas"]);
							$desc_cc = obtenerDatosCentroCostos($row['destino'],"control_costos","id_control_costos");
							$desc_cuenta = obtenerDatosCentroCostos($row['cuentas'],"cuentas","id_cuentas");
							
							if($vuelta == 1 || $vuelta == 3){
								$desc_cc = obtenerDatosCentroCostos($row["id_control_costos"],"control_costos","id_control_costos");
								$desc_cuenta = obtenerDatosCentroCostos($row["id_cuentas"],"cuentas","id_cuentas");
							}
							
							$total_mat = $row["cant_salida"] * $row["costo_unidad"];
							$moneda = $row["moneda"];
							$estatus = obtenerDatoMaterial($row["materiales_id_material"],"materiales","estatus","id_material");
							if($moneda == "")
								$moneda = obtenerDatoMaterial($row["materiales_id_material"],"materiales","moneda","id_material");
							?>
							<tr>
								<td class='<?php echo $nom_clase; ?>'><?php echo $row['solicitante']; ?></td>
								<td class='<?php echo $nom_clase; ?>'><?php echo $row['id_equipo_destino']; ?></td>
								<td class='<?php echo $nom_clase; ?>'><?php echo $desc_cc; ?></td>
								<td class='<?php echo $nom_clase; ?>'><?php echo $desc_cuenta; ?></td>
								<td class='<?php echo $nom_clase; ?>'><?php echo $row['nom_material']; ?></td>
								<td class='<?php echo $nom_clase; ?>'><?php echo $row['cant_salida']; ?></td>
								<td class='<?php echo $nom_clase; ?>'><?php echo $row['unidad_material']; ?></td>
								<td class='<?php echo $nom_clase; ?>'><?php echo $row['costo_unidad']; ?></td>
								<td class='<?php echo $nom_clase; ?>'><?php echo $total_mat; ?></td>
								<td class='<?php echo $nom_clase; ?>'><?php echo $moneda; ?></td>
								<td class='<?php echo $nom_clase; ?>'><?php echo $estatus; ?></td>
								<td class='<?php echo $nom_clase; ?>' style="white-space: nowrap;"><?php echo $ent_ped; ?></td>
								<td class='<?php echo $nom_clase; ?>'><?php echo $row['id_salida']; ?></td>
								<td class='<?php echo $nom_clase; ?>'><?php echo modFecha($row['fecha_salida'],1); ?></td>
								<td class='<?php echo $nom_clase; ?>'><?php echo $row['turno']; ?></td>
							</tr>
							<?php
							$cont++;
							
							if($cont%2==0)
								$nom_clase = "renglon_blanco";
							else
								$nom_clase = "renglon_gris";
						}
						$bandera = 1;
					} else {
						$bandera = 0;
						echo mysql_error()."<br>";
					}
					$vuelta++;
					mysql_close($conn);
				}while($vuelta <= 3);
				?>
			</table>
		</body>
			<?php
	}
	
	function generarRepSalidaDetalle($fechaI,$fechaC,$orden){
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=ReporteSalidasCentroCostos.xls");
		$dep = obtenerDatosCentroCostos($_POST["hdn_cc"],"control_costos","id_control_costos");
		$msg = "Reporte de Salidas generadas del <strong><u>$fechaI</u></strong> al <strong><u>$fechaC</u></strong> de <strong><u>$dep</u></strong>";
		
		?>
		<head>
			<style>					
				<!--
				body { font-family:Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; }
				/*En este formato  se establece el tama�o,tipo de letra,color de fondo que ocupa la columna princiaplal de una tebla*/
				.nombres_columnas { font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #FFFFFF; background-color: #9BBA59; font-weight: bold; border-top-width: medium; border-right-width: thin;
									border-bottom-width: medium; border-left-width: thin; border-top-style: solid; border-right-style: none; border-bottom-style: solid; border-left-style: none; 
									border-top-color: #000000; border-bottom-color: #000000;vertical-align:middle;text-align:center;}
				.totales_columnas { font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #000000; background-color: #FFFF00; font-weight: bold; border-top-width: medium; border-right-width: thin;
									border-bottom-width: medium; border-left-width: thin; border-top-style: solid; border-right-style: none; border-bottom-style: solid; border-left-style: none; 
									border-top-color: #000000; border-bottom-color: #000000;vertical-align:middle;text-align:center;}
				/*En este formato  se establece el tama�o,tipo de letra,color de fondo que se ocupa en la fila principal para resaltar datos  de una tebla*/
				.nombres_filas { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #9BBB59;vertical-align:middle;text-align:center;}
				/*En este formato  se establece el tama�o,tipo de letra,color de fondo que contendra datos  de una tebla*/
				.renglon_gris { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #E7E7E7;vertical-align:middle;text-align:center;}
				/*En este formato  se establece el tama�o,tipo de letra,color de fondo que contendra datos  de una tebla*/
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
					<?php
					$conn = conecta('bd_almacen');
					
					$f1 = modFecha($fechaI,3);
					$f2 = modFecha($fechaC,3);
					
					$filtro_centro_costo = $_POST["hdn_cc"];
					$filtro_cuentas = $_POST["hdn_cuenta"];
					
					$vuelta = 1;
					
					do{
						if($vuelta == 1){
							$stm_sql = "SELECT DISTINCT T1.id_salida, T1.fecha_salida, T1.solicitante, T1.destino, T1.turno, T1.cuentas, 
														T2.materiales_id_material, T2.nom_material, T2.unidad_material, T2.cant_salida, T2.costo_unidad, T2.costo_total, T2.id_equipo_destino, T2.moneda AS moneda_detalle, 
														T3.id_control_costos, T3.id_cuentas
														FROM salidas AS T1
														JOIN detalle_salidas AS T2 ON T1.id_salida = T2.salidas_id_salida
														LEFT JOIN bd_mantenimiento.equipos AS T3 ON T2.id_equipo_destino = T3.id_equipo
														WHERE T1.fecha_salida
														BETWEEN  '$f1'
														AND  '$f2'
														AND T3.id_control_costos = '$filtro_centro_costo'";
														
							/*							
							$stm_sql = "SELECT DISTINCT T1.id_salida, T1.fecha_salida, T1.solicitante, T1.destino, T1.turno, T1.cuentas, 
														T2.materiales_id_material, T2.nom_material, T2.unidad_material, T2.cant_salida, T2.costo_unidad, T2.costo_total, T2.id_equipo_destino, T2.moneda AS moneda_detalle, T1.tipo
														FROM salidas AS T1
														JOIN detalle_salidas AS T2 ON T1.id_salida = T2.salidas_id_salida
														WHERE T1.fecha_salida
														BETWEEN  '$f1'
														AND  '$f2'
														AND T1.destino =  '$filtro_centro_costo'
														ORDER BY  `T2`.`id_equipo_destino` ASC ";
							*/
						}
						else if($vuelta == 2){
							$stm_sql = "SELECT DISTINCT T1.id_salida, T1.fecha_salida, T1.solicitante, T1.destino, T1.turno, T1.cuentas, 
														T2.materiales_id_material, T2.nom_material, T2.unidad_material, T2.cant_salida, T2.costo_unidad, T2.costo_total, T2.id_equipo_destino, T2.moneda AS moneda_detalle 
														FROM salidas AS T1
														JOIN detalle_salidas AS T2 ON T1.id_salida = T2.salidas_id_salida
														WHERE T1.fecha_salida
														BETWEEN  '$f1'
														AND  '$f2'
														AND T1.destino = '$filtro_centro_costo'
														AND T2.id_equipo_destino = 'N/A'";
						}
						else if($vuelta == 3){
							$stm_sql = "SELECT DISTINCT T1.id_salida, T1.fecha_salida, T1.solicitante, T1.destino, T1.turno, T1.cuentas, 
														T2.materiales_id_material, T2.nom_material, T2.unidad_material, T2.cant_salida, T2.costo_unidad, T2.costo_total, T2.id_equipo_destino, T2.moneda AS moneda_detalle, T1.tipo,
														T4.id_control_costos, T4.id_cuentas 
														FROM salidas AS T1
														JOIN detalle_salidas AS T2 ON T1.id_salida = T2.salidas_id_salida
														JOIN detalle_es AS T3 ON T2.salidas_id_salida = T3.no_vale
														JOIN bd_recursos.empleados AS T4 ON T3.empleados_rfc_empleado = T4.rfc_empleado
														WHERE T1.fecha_salida
														BETWEEN  '$f1'
														AND  '$f2'
														AND T1.destino = 'EPP'
														AND T4.id_control_costos =  '$filtro_centro_costo'";
						}
						$rs = mysql_query($stm_sql);
						if($row = mysql_fetch_array($rs)){
							do{
								$id_salida=$row["id_salida"];
								mysql_close($conn);
								
								if($vuelta == 1 || $vuelta == 3){
									$centro_costos = obtenerDatosCentroCostos($row["id_control_costos"],"control_costos","id_control_costos");
									$cuenta = obtenerDatosCentroCostos($row["id_cuentas"],"cuentas","id_cuentas");
								}
								else if($vuelta == 2){
									$centro_costos = obtenerDatosCentroCostos($row["destino"],"control_costos","id_control_costos");
									$cuenta = obtenerDatosCentroCostos($row["cuentas"],"cuentas","id_cuentas");
								}
								$moneda = $row["moneda_detalle"];
								if($moneda == "")
									$moneda = obtenerDatoMaterial($row["materiales_id_material"],"materiales","moneda","id_material");
								
								$group_material = obtenerDatoMaterial($row["materiales_id_material"],"materiales","grupo","id_material");
								if($group_material == "")
									$group_material = "Sin Grupo";
								
								$conn = conecta('bd_almacen');
								
								if(!isset($grupos)){
									$grupos = 	array(
													$group_material => array(array(
																			'solicito' => $row['solicitante'],
																			'equipo' => $row['id_equipo_destino'],
																			'cc' => $centro_costos,
																			'cuen' => $cuenta,
																			'fechaSal' => modFecha($row['fecha_salida'],1),
																			'turno' => $row['turno'],
																			'nom_mat' => $row['nom_material'],
																			'cant_mat' => $row['cant_salida'],
																			'unidad' => $row['unidad_material'],
																			'cost_mat' => $row['costo_unidad'],
																			'total_mat' => $row['costo_total'],
																			'moneda_mat' => $moneda,
																			'grupo_mat' => $group_material
																		 ))
												);
								} else {
									if(array_key_exists($group_material,$grupos)){
										$grupos[$group_material][] = array(
																			'solicito' => $row['solicitante'],
																			'equipo' => $row['id_equipo_destino'],
																			'cc' => $centro_costos,
																			'cuen' => $cuenta,
																			'fechaSal' => modFecha($row['fecha_salida'],1),
																			'turno' => $row['turno'],
																			'nom_mat' => $row['nom_material'],
																			'cant_mat' => $row['cant_salida'],
																			'unidad' => $row['unidad_material'],
																			'cost_mat' => $row['costo_unidad'],
																			'total_mat' => $row['costo_total'],
																			'moneda_mat' => $moneda,
																			'grupo_mat' => $group_material
																	 );
									} else {
										$grupos[$group_material] =  array(array(
																			'solicito' => $row['solicitante'],
																			'equipo' => $row['id_equipo_destino'],
																			'cc' => $centro_costos,
																			'cuen' => $cuenta,
																			'fechaSal' => modFecha($row['fecha_salida'],1),
																			'turno' => $row['turno'],
																			'nom_mat' => $row['nom_material'],
																			'cant_mat' => $row['cant_salida'],
																			'unidad' => $row['unidad_material'],
																			'cost_mat' => $row['costo_unidad'],
																			'total_mat' => $row['costo_total'],
																			'moneda_mat' => $moneda,
																			'grupo_mat' => $group_material
																	));
									}
								}
							}while($row = mysql_fetch_array($rs));
						}
						$vuelta++;
					}while($vuelta <= 3);
					?>
					<tr>
						<td align="left" valign="baseline" colspan="2"><img src="http://<?php echo HOST; ?>/<?php echo SISAD; ?>/images/logo.png" width="118" height="58" align="absbottom" /></td>
						<td colspan="8">&nbsp;</td>
						<td valign="baseline" colspan="2">
							<div align="right">
								<span class="texto_encabezado">
									<strong>MANUAL  DE PROCEDIMIENTOS DE LA CALIDAD</strong><br><em>CONCRETO  LANZADO DE FRESNILLO, S.A DE C.V.</em>
								</span>
							</div>
						</td>
					</tr>
					<tr>
						<td colspan="12" align="center" class="borde_linea">
							<span class="sub_encabezado">
								CONFIDENCIAL, PROPIEDAD DE &ldquo;CONCRETO LANZADO DE FRESNILLO, S.A DE C.V.&rdquo; PROHIBIDA SU REPRODUCCI&Oacute;N TOTAL O PARCIAL
							</span>
						</td>
					</tr>
					<tr>
						<td colspan="12">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="12">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="12" align="center" class="titulo_tabla">
							<?php echo "$msg"; ?>
						</td>
					</tr>
					<tr>
						<td colspan="12">&nbsp;</td>
					</tr>
					<?php
					$nom_clase = "renglon_gris";
					$cont = 1;
					foreach($grupos as $group => $key){
						$array_pesos= "";
						$array_dolares = "";
						$array_euros = "";
						
						foreach($key as $k => $reg){
							if($reg["moneda_mat"] == "PESOS")
								$array_pesos[] = $reg; 
							else if($reg["moneda_mat"] == "DOLARES")
								$array_dolares[] = $reg; 
							else if($reg["moneda_mat"] == "EUROS")
								$array_euros[] = $reg; 
						}
						if(is_array($array_pesos)){
							$total = 0;
							imprimirEncabezados();
							foreach($array_pesos as $kp => $reg){
								imprimirDatosReporte($reg,$cont);
								$total += $reg["total_mat"];
								$grupo_cat = $reg["grupo_mat"];
								$moneda_cat = $reg["moneda_mat"];
								$cont++;
							}
							unset($array_pesos);
							
							if($total > 0){
								$aux = obtenerDatoTabla("grupos_mat","id_grupo",$grupo_cat,"bd_almacen","grupo");
								if ($aux != "N/A") {
									$grupo_cat = $aux;
								}
								echo "
								<tr>
									<td colspan='9'>&nbsp;</td>
									<td class='totales_columnas'>TOTAL</td>
									<td class='totales_columnas'>$".number_format($total,2,".",",")."</td>
									<td class='totales_columnas'>$moneda_cat</td>
									<td class='totales_columnas'>$grupo_cat</td>
								</tr>
								<tr></tr>";
							}
						}
						
						if(is_array($array_dolares)){
							$total = 0;
							imprimirEncabezados();
							foreach($array_dolares as $kp => $reg){
								imprimirDatosReporte($reg,$cont);
								$total += $reg["total_mat"];
								$grupo_cat = $reg["grupo_mat"];
								$moneda_cat = $reg["moneda_mat"];
								$cont++;
							}
							unset($array_dolares);
							
							if($total > 0){
								$aux = obtenerDatoTabla("grupos_mat","id_grupo",$grupo_cat,"bd_almacen","grupo");
								if ($aux != "N/A") {
									$grupo_cat = $aux;
								}
								echo "
								<tr>
									<td colspan='9'>&nbsp;</td>
									<td class='totales_columnas'>TOTAL</td>
									<td class='totales_columnas'>$".number_format($total,2,".",",")."</td>
									<td class='totales_columnas'>$moneda_cat</td>
									<td class='totales_columnas'>$grupo_cat</td>
								</tr>
								<tr></tr>";
							}
						}
						
						if(is_array($array_euros)){
							$total = 0;
							imprimirEncabezados();
							foreach($array_euros as $kp => $reg){
								imprimirDatosReporte($reg,$cont);
								$total += $reg["total_mat"];
								$grupo_cat = $reg["grupo_mat"];
								$moneda_cat = $reg["moneda_mat"];
								$cont++;
							}
							unset($array_euros);
							
							if($total > 0){
								$aux = obtenerDatoTabla("grupos_mat","id_grupo",$grupo_cat,"bd_almacen","grupo");
								if ($aux != "N/A") {
									$grupo_cat = $aux;
								}
								echo "
								<tr>
									<td colspan='9'>&nbsp;</td>
									<td class='totales_columnas'>TOTAL</td>
									<td class='totales_columnas'>$".number_format($total,2,".",",")."</td>
									<td class='totales_columnas'>$moneda_cat</td>
									<td class='totales_columnas'>$grupo_cat</td>
								</tr>
								<tr></tr>";
							}
						}
					}
					?>
				</table>
			</div>
		</body>
		<?php
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
				/*En este formato  se establece el tama�o,tipo de letra,color de fondo que ocupa la columna principal de una tabla*/
				.nombres_columnas { font-family: Arial, Helvetica, sans-serif; font-size: 13px; color: #FFFFFF; background-color: #9BBA59; font-weight: bold; 
				border-top-width: medium; border-right-width: thin;
				border-bottom-width: medium; border-left-width: thin; border-top-style: solid; border-right-style: none; border-bottom-style: solid; 
				border-left-style: none; 
				border-top-color: #000000; border-bottom-color: #000000; }
				/*En este formato  se establece el tama�o,tipo de letra,color de fondo que se ocupa en la fila principal para resaltar datos  de una tabla*/
				.nombres_filas { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #9BBB59; }
				/*En este formato  se establece el tama�o,tipo de letra,color de fondo que contendra datos  de una tabla*/
				.renglon_gris { font-family: Arial, Helvetica, sans-serif; font-size: 11px; color: #000000; background-color: #E7E7E7; }
				/*En este formato  se establece el tama�o,tipo de letra,color de fondo que contendra datos  de una tabla*/
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
				/*En este formato  se establece el tama�o,tipo de letra,color de fondo que ocupa la columna principal de una tabla*/
				.nombres_columnas { font-family: Arial, Helvetica, sans-serif; font-size: 13px; color: #FFFFFF; background-color: #9BBA59; font-weight: bold; 
				border-top-width: medium; border-right-width: thin;
				border-bottom-width: medium; border-left-width: thin; border-top-style: solid; border-right-style: none; border-bottom-style: solid; 
				border-left-style: none; 
				border-top-color: #000000; border-bottom-color: #000000; }
				/*En este formato  se establece el tama�o,tipo de letra,color de fondo que se ocupa en la fila principal para resaltar datos  de una tabla*/
				.nombres_filas { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #9BBB59; }
				/*En este formato  se establece el tama�o,tipo de letra,color de fondo que contendra datos  de una tabla*/
				.renglon_gris { font-family: Arial, Helvetica, sans-serif; font-size: 11px; color: #000000; background-color: #E7E7E7; }
				/*En este formato  se establece el tama�o,tipo de letra,color de fondo que contendra datos  de una tabla*/
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
	
	function obtenerRFCEmpleadoSalida($id_salida){
		$conn_rec=conecta("bd_almacen");
		$dato="N/A";
		$sql_stm_rfc = "SELECT empleados_rfc_empleado 
						FROM  `detalle_es` 
						WHERE  `no_vale` LIKE  '$id_salida'";
		$rs_rfc=mysql_query($sql_stm_rfc);
		$datos_rfc=mysql_fetch_array($rs_rfc);
		if ($datos_rfc[0]!="")
			$dato=$datos_rfc[0];
		return $dato;
		mysql_close($conn_rec);
	}
	
	function obtenerDatosEmpleado($campo,$busq,$comp){
		$conn_rec=conecta("bd_recursos");
		$dato="N/A";
		$sql_stm_rfc = "SELECT $campo 
						FROM  `empleados` 
						WHERE  `$comp` = '$busq'";
		$rs_rfc=mysql_query($sql_stm_rfc);
		$datos_rfc=mysql_fetch_array($rs_rfc);
		if ($datos_rfc[0]!="")
			$dato=$datos_rfc[0];
		return $dato;
		mysql_close($conn_rec);
	}
	
	function obtenerNumeroRenglones($id,$cc,$cuenta){
		$conn_mat=conecta("bd_almacen");
		$dato=0;
		$sql_stm_num = "SELECT COUNT( * ) 
						FROM  `detalle_salidas` AS T1
						LEFT JOIN bd_mantenimiento.equipos AS T2
						ON T2.id_equipo = T1.id_equipo_destino
						WHERE T1.salidas_id_salida =  '$id'";
		
		if($cc!="" && $cc!="N/A" && $cc!="EPP")
			$sql_stm_num.= " AND T2.id_control_costos = '$cc'";
		
		if($cuenta!="" && $cuenta!="N/A")
			$sql_stm_num.= " AND T2.id_cuentas = '$cuenta'";
		
		$rs_num=mysql_query($sql_stm_num);
		$datos_num=mysql_fetch_array($rs_num);
		if ($datos_num[0]!="")
			$dato=$datos_num[0];
		return $dato;
		mysql_close($conn_mat);
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
	
	function obtenerDatoMaterial($clave,$tabla,$valor,$busq){
		$conn_mat=conecta("bd_almacen");
		$dato="";
		
		$sql_stm_num = "SELECT  $valor
						FROM  `$tabla` 
						WHERE  $busq =  '$clave'";
		
		$rs_num=mysql_query($sql_stm_num);
		if($datos_num=mysql_fetch_array($rs_num)){
			$dato=$datos_num[0];
		}
		
		return $dato;
		mysql_close($conn_mat);
	}
	
	function imprimirDatosReporte($reg,$num){
		if($num%2==0)
			$nom_clase = "renglon_blanco";
		else
			$nom_clase = "renglon_gris";
		
		echo "
		<tr>
			<td class='$nom_clase' title='Empleado al que se entrego el material'>$reg[solicito]</td>
			<td class='$nom_clase' title='Equipo al Que Va Destinado el Material'>$reg[equipo]</td>
			<td class='$nom_clase' title='Centro de Costos'>$reg[cc]</td>
			<td class='$nom_clase' title='Cuenta'>$reg[cuen]</td>
			<td class='$nom_clase' title='Fecha de Salida'>$reg[fechaSal]</td>
			<td class='$nom_clase' title='Turno en el que el Material Sali&oacute;'>$reg[turno]</td>
			<td class='$nom_clase' title='Material'>$reg[nom_mat]</td>
			<td class='$nom_clase' title='Cantidad'>$reg[cant_mat]</td>
			<td class='$nom_clase' title='Unidad de Medida'>$reg[unidad]</td>
			<td class='$nom_clase' title='Costo Unitario'>$".number_format($reg["cost_mat"],2,".",",")."</td>
			<td class='$nom_clase' title='Costo Total'>$".number_format($reg["total_mat"],2,".",",")."</td>
			<td class='$nom_clase' title='Moneda'>$reg[moneda_mat]</td>
		</tr>
		";
	}
	
	function imprimirEncabezados(){
		echo "
		<tr>
			<th class='nombres_columnas'>SOLICITANTE</th>
			<th class='nombres_columnas'>EQUIPO</th>
			<th class='nombres_columnas'>CENTRO DE COSTOS</th>
			<th class='nombres_columnas'>CUENTA</th>
			<th class='nombres_columnas'>FECHA SALIDA</th>
			<th class='nombres_columnas'>TURNO</th>
			
			<th class='nombres_columnas'>MATERIAL</th>
			<th class='nombres_columnas'>CANTIDAD</th>
			<th class='nombres_columnas'>UNIDAD MEDIDA</th>
			
			<th class='nombres_columnas'>COSTO UNITARIO</th>
			<th class='nombres_columnas'>COSTO TOTAL</th>
			<th class='nombres_columnas'>MONEDA</th>
		</tr>";
	}
	
	function obtenerRenglones($id){
		$conn_mat=conecta("bd_almacen");
		$dato=0;
		$sql_stm_num = "SELECT COUNT( * ) 
						FROM  `detalle_salidas` 
						WHERE salidas_id_salida =  '$id'";
		
		$rs_num=mysql_query($sql_stm_num);
		
		$datos_num=mysql_fetch_array($rs_num);
		if ($datos_num[0]!="")
			$dato=$datos_num[0];
		
		return $dato;
		mysql_close($conn_mat);
	}
	
	function obtenerPedEntradas($entradas){
		$dato = "";
		$arreglo_ent = explode(",",$entradas);
		
		for($i=0; $i < count($arreglo_ent); $i++){
			$con_entradas = conecta("bd_almacen");
			$stm_sql_entradas = "SELECT  `requisiciones_id_requisicion` ,  `comp_directa` 
								 FROM  `entradas`
								 WHERE  `id_entrada` LIKE  '$arreglo_ent[$i]'";
			$rs_entradas = mysql_query($stm_sql_entradas);
			if(!$rs_entradas){
				echo mysql_error();
			}
			$dato_entradas = mysql_fetch_array($rs_entradas);
			if($dato_entradas["comp_directa"] == "N/A"){
				$pedido = "COMPRA DIRECTA";
				$dato .= $pedido."->".$arreglo_ent[$i].", ";
			} else {
				$pedido = $dato_entradas["requisiciones_id_requisicion"];
				$requi = obtenerReqPedido($pedido);
				$dato .= $requi."->".$pedido."->".$arreglo_ent[$i].", ";
			}
		}
		$dato = substr($dato,0,strlen($dato)-2);
		return $dato;
	}
	
	function obtenerReqPedido($pedido){
		$dato = "N/A";
		
		$con_pedido = conecta("bd_compras");
		
		$stm_sql_pedidos = "SELECT  `requisiciones_id_requisicion` 
							FROM  `pedido` 
							WHERE  `id_pedido` LIKE  '$pedido'";
		$rs_pedidos = mysql_query($stm_sql_pedidos);
		if(!$rs_pedidos){
			echo mysql_error();
		}
		$dato_pedidos = mysql_fetch_array($rs_pedidos);
		
		$dato = $dato_pedidos[0];
		
		return $dato;
	}
	
	function obtenerDatoTabla($tabla,$busq,$valor,$bd,$retorno){
		$dat = "N/A"; 
		$con = conecta("$bd");
		$stm_sql = "SELECT $retorno
					FROM  `$tabla` 
					WHERE  `$busq` LIKE  '$valor'";
		$rs = mysql_query($stm_sql);
		if($rs){
			if($datos = mysql_fetch_array($rs)){
				$dat = $datos[0];
			}
		}
		return $dat;
	}
	
	function verDetalleSalidasCat($f1,$f2,$id_cc,$id_cuenta,$id_cat,$id_equipo,$dolar,$euro,$band){
		$stm_sql_salidas = "SELECT DISTINCT T2.salidas_id_salida, T1.fecha_salida, T1.solicitante, IF( T1.destino =  'EPP', T4.id_control_costos, T1.destino ) AS centro_costo, IF( T1.destino =  'EPP', T4.id_cuentas, T1.cuentas ) AS cuenta, T1.subcuentas, T1.turno, T2.materiales_id_material, T2.nom_material, T2.unidad_material, T2.cant_salida, T2.costo_unidad, IF( T2.moneda =  '', T5.moneda, T2.moneda ) AS moneda, T2.id_equipo_destino, T5.categoria
							FROM salidas AS T1
							JOIN detalle_salidas AS T2 ON T1.id_salida = T2.salidas_id_salida
							LEFT JOIN detalle_es AS T3 ON T1.no_vale = T3.no_vale
							AND T2.materiales_id_material = T3.materiales_id_material
							LEFT JOIN bd_recursos.empleados AS T4 ON T3.empleados_rfc_empleado = T4.rfc_empleado
							JOIN materiales AS T5 ON T5.id_material = T2.materiales_id_material
							WHERE fecha_salida
							BETWEEN  '$f1'
							AND  '$f2'";
		
		if($id_cc != "TODOS"){
			$stm_sql_salidas .= " AND (
									T1.destino =  '$id_cc'
									OR (
										T1.destino =  'EPP'
										AND T4.id_control_costos =  '$id_cc'
									)
								)";
		}
		if($id_cuenta != "TODAS"){
			$stm_sql_salidas .= " AND (
									T1.cuentas =  '$id_cuenta'
									OR (
										T1.destino =  'EPP'
										AND T4.id_cuentas =  '$id_cuenta'
									)
								)";
		}
		if($id_equipo != "TODOS"){
			$stm_sql_salidas .= " AND T2.id_equipo_destino =  '$id_equipo'";
		}
		if($id_cat != "TODAS"){
			$stm_sql_salidas .= " AND T5.categoria = '$id_cat'";
		}
		$stm_sql_salidas .= " ORDER BY cuenta, categoria, centro_costo";
		$rs_salidas = mysql_query($stm_sql_salidas);
		if($rs_salidas){
			$cont = 0;
			$nom_clase = "renglon_blanco";
			if($dato_salidas = mysql_fetch_array($rs_salidas)){
				do{
					$categoria = strtoupper(obtenerDatoTabla('categorias_mat','id_categoria',$dato_salidas['categoria'],"bd_almacen","descripcion"));
					$conrol_costos = strtoupper(obtenerDatoTabla('control_costos','id_control_costos',$dato_salidas['centro_costo'],"bd_recursos","descripcion"));
					$cuenta = strtoupper(obtenerDatoTabla('cuentas','id_cuentas',$dato_salidas['cuenta'],"bd_recursos","descripcion"));
					$subcuenta = strtoupper(obtenerDatoTabla('subcuentas','id_subcuentas',$dato_salidas['subcuentas'],"bd_recursos","descripcion"));
					$costo_unit = $dato_salidas["costo_unidad"];
					if($dato_salidas["moneda"] == "DOLARES"){
						$costo_unit = $costo_unit * $dolar;
					}
					else if($dato_salidas["moneda"] == "EUROS"){
						$costo_unit = $costo_unit * $euro;
					}
					$costo_total = $dato_salidas['cant_salida'] * $costo_unit;
					?>
					<tr>
						<td class="<?php echo $nom_clase; ?>"><?php echo $dato_salidas['salidas_id_salida']; ?></td>
						<td class="<?php echo $nom_clase; ?>"><?php echo modFecha($dato_salidas['fecha_salida'],1); ?></td>
						<td class="<?php echo $nom_clase; ?>"><?php echo $dato_salidas['solicitante']; ?></td>
						<td class="<?php echo $nom_clase; ?>"><?php echo $conrol_costos; ?></td>
						<td class="<?php echo $nom_clase; ?>"><?php echo $cuenta; ?></td>
						<td class="<?php echo $nom_clase; ?>"><?php echo $subcuenta; ?></td>
						<td class="<?php echo $nom_clase; ?>"><?php echo $dato_salidas['turno']; ?></td>
						<td class="<?php echo $nom_clase; ?>"><?php echo $dato_salidas['materiales_id_material']; ?></td>
						<td class="<?php echo $nom_clase; ?>"><?php echo $dato_salidas['nom_material']; ?></td>
						<td class="<?php echo $nom_clase; ?>"><?php echo $dato_salidas['unidad_material']; ?></td>
						<td class="<?php echo $nom_clase; ?>"><?php echo $dato_salidas['cant_salida']; ?></td>
						<td class="<?php echo $nom_clase; ?>">$<?php echo number_format($costo_unit,2,".",","); ?></td>
						<td class="<?php echo $nom_clase; ?>">$<?php echo number_format($costo_total,2,".",","); ?></td>
						<td class="<?php echo $nom_clase; ?>">PESOS</td>
						<td class="<?php echo $nom_clase; ?>"><?php echo $dato_salidas['id_equipo_destino']; ?></td>
						<td class="<?php echo $nom_clase; ?>"><?php echo $categoria; ?></td>
					</tr>
					<?php
					$cont++;
					if($cont%2==0)
						$nom_clase = "renglon_blanco";
					else
						$nom_clase = "renglon_gris";
				}while($dato_salidas = mysql_fetch_array($rs_salidas));
				$band = 1;
			}
		}
		return $band;
	}
	
	function guardarRepSalidaDetalleCat($fechaI,$fechaC){
		//Esribir datos de la pagina en excel
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=Salidas_detalle_categoria.xls");
				
		$band = 0;
		$f1 = modFecha($fechaI,3);
		$f2 = modFecha($fechaC,3);
		
		$filtro_cc = $_POST["hdn_cc"];
		$filtro_cuenta = $_POST["hdn_cuenta"];
		$filtro_cat = $_POST["hdn_cat"];
		$filtro_equipo = $_POST["hdn_equipo"];
		$dolar = $_POST["hdn_dolar"];
		$euro = $_POST["hdn_euro"];
		?>
		<head>
			<style>					
				<!--
				body { font-family:Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; }
				/*En este formato  se establece el tama�o,tipo de letra,color de fondo que ocupa la columna princiaplal de una tebla*/
				.nombres_columnas { font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #FFFFFF; background-color: #9BBA59; font-weight: bold; border-top-width: medium; border-right-width: thin;
									border-bottom-width: medium; border-left-width: thin; border-top-style: solid; border-right-style: none; border-bottom-style: solid; border-left-style: none; 
									border-top-color: #000000; border-bottom-color: #000000;vertical-align:middle;text-align:center;}
				/*En este formato  se establece el tama�o,tipo de letra,color de fondo que se ocupa en la fila principal para resaltar datos  de una tebla*/
				.nombres_filas { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #9BBB59;vertical-align:middle;text-align:center;}
				/*En este formato  se establece el tama�o,tipo de letra,color de fondo que contendra datos  de una tebla*/
				.renglon_gris { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #E7E7E7;vertical-align:middle;text-align:center;}
				/*En este formato  se establece el tama�o,tipo de letra,color de fondo que contendra datos  de una tebla*/
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
				<table cellpadding='5' width='100%'>
					<tr>
						<td align="left" valign="baseline" colspan="2">
							<img src="http://<?php echo HOST; ?>/<?php echo SISAD; ?>/images/logo.png" width="118" height="58" align="absbottom" />
						</td>
						<td colspan="12">&nbsp;</td>
						<td valign="baseline" colspan="2">
							<div align="right">
								<span class="texto_encabezado">
									<strong>MANUAL  DE PROCEDIMIENTOS DE LA CALIDAD</strong><br><em>CONCRETO  LANZADO DE FRESNILLO, S.A DE C.V.</em>
								</span>
							</div>
						</td>
					</tr>
					<tr>
						<td colspan="16" align="center" class="borde_linea">
							<span class="sub_encabezado">
								CONFIDENCIAL, PROPIEDAD DE &ldquo;CONCRETO LANZADO DE FRESNILLO, S.A DE C.V.&rdquo; PROHIBIDA SU REPRODUCCI&Oacute;N TOTAL O PARCIAL
							</span>
						</td>
					</tr>
					<tr>
						<td colspan="16">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="16">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="16" align="center" class="titulo_tabla">
							REPORTE DE SALIDAS DEL <?php echo $fechaI; ?> AL <?php echo $fechaC; ?> <br> DOLAR: <?php echo $dolar; ?>&nbsp;&nbsp;&nbsp;&nbsp;EURO: <?php echo $euro; ?>
						</td>
					</tr>
					<tr>
						<td colspan="16">&nbsp;</td>
					</tr>     			
					<tr>					
						<td class='nombres_columnas'>ID&nbsp;SALIDA</td>
						<td class='nombres_columnas'>FECHA</td>
						<td class='nombres_columnas'>SOLICITANTE</td>
						<td class='nombres_columnas'>CENTRO&nbsp;DE&nbsp;COSTO</td>						
						<td class='nombres_columnas'>CUENTA</td>
						<th class='nombres_columnas'>SUBCUENTA</th>
						<th class='nombres_columnas'>TURNO</th>
						<th class='nombres_columnas'>CLAVE&nbsp;MATERIAL</th>
						<th class='nombres_columnas'>MATERIAL</th>
						<th class='nombres_columnas'>UNIDAD&nbsp;MEDIDA</th>
						<th class='nombres_columnas'>CANTIDAD&nbsp;SALIDA</th>
						<th class='nombres_columnas'>COSTO&nbsp;UNITARIO</th>
						<th class='nombres_columnas'>COSTO&nbsp;TOTAL</th>
						<th class='nombres_columnas'>MONEDA</th>
						<th class='nombres_columnas'>EQUIPO</th>
						<th class='nombres_columnas'>CATEGORIA</th>
					</tr>
					<?php
					if($filtro_cc == "DESARROLLO"){
						$arr_cc = array(0=>"CONT002",1=>"CONT010",2=>"CONT015");
					}
					else if($filtro_cc == "ZARPEO"){
						$arr_cc = array(0=>"CONT016",1=>"CONT003",2=>"CONT004",3=>"CONT005",4=>"CONT014");
					}
					else if($filtro_cc == "PLANTAS"){
						$arr_cc = array(0=>"CONT012",1=>"CONT020",2=>"CONT021");
					} else {
						$arr_cc = array(0=>$filtro_cc);
					}
					for($i=0; $i<count($arr_cc); $i++){
						$conn = conecta('bd_almacen');
						$band = verDetalleSalidasCat($f1,$f2,$arr_cc[$i],$filtro_cuenta,$filtro_cat,$filtro_equipo,$dolar,$euro,$band);
						mysql_close($conn);
					}
				?>
				</table>
			</div>
		</body>
		<?php
	}
	
	function guardarRepSalidaCat($fechaI,$fechaC){
		//Esribir datos de la pagina en excel
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=Salidas_categoria.xls");
				
		$f1 = modFecha($fechaI,3);
		$f2 = modFecha($fechaC,3);
		
		$filtro_cc = $_POST["hdn_cc"];
		$filtro_cuenta = $_POST["hdn_cuenta"];
		$filtro_cat = $_POST["hdn_cat"];
		$filtro_equipo = $_POST["hdn_equipo"];
		$dolar = $_POST["hdn_dolar"];
		$euro = $_POST["hdn_euro"];
		
		$year_ini = explode("/",$fechaI);
		$year_ini = $year_ini[2];
		$year_fin = explode("/",$fechaC);
		$year_fin = $year_fin[2];
		$num_col = ($year_fin - $year_ini) + 1;
		$meses = 12;
		?>
		<head>
			<style>					
				<!--
				body { font-family:Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; }
				/*En este formato  se establece el tama�o,tipo de letra,color de fondo que ocupa la columna princiaplal de una tebla*/
				.nombres_columnas { font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #FFFFFF; background-color: #9BBA59; font-weight: bold; border-top-width: medium; border-right-width: thin;
									border-bottom-width: medium; border-left-width: thin; border-top-style: solid; border-right-style: none; border-bottom-style: solid; border-left-style: none; 
									border-top-color: #000000; border-bottom-color: #000000;vertical-align:middle;text-align:center;}
				.titulos_cuentas { font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #000000; background-color: #FFFF00; font-weight: bold; border-top-width: medium; border-right-width: thin;
									border-bottom-width: medium; border-left-width: thin; border-top-style: solid; border-right-style: none; border-bottom-style: solid; border-left-style: none; 
									border-top-color: #000000; border-bottom-color: #000000;vertical-align:middle;text-align:center;}
				/*En este formato  se establece el tama�o,tipo de letra,color de fondo que se ocupa en la fila principal para resaltar datos  de una tebla*/
				.nombres_filas { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #9BBB59;vertical-align:middle;text-align:center; border-style:solid;}
				/*En este formato  se establece el tama�o,tipo de letra,color de fondo que contendra datos  de una tebla*/
				.renglon_gris { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #E7E7E7;vertical-align:middle;text-align:center; border-style:solid;}
				/*En este formato  se establece el tama�o,tipo de letra,color de fondo que contendra datos  de una tebla*/
				.renglon_blanco { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #FFFFFF;vertical-align:middle;text-align:center; border-style:solid;}
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
				<table cellpadding='5' width='100%'>
					<tr>
						<td align="left" valign="baseline" colspan="2">
							<img src="http://<?php echo HOST; ?>/<?php echo SISAD; ?>/images/logo.png" width="118" height="58" align="absbottom" />
						</td>
						<td colspan="<?php echo $meses*$num_col-3; ?>">&nbsp;</td>
						<td valign="baseline" colspan="2">
							<div align="right">
								<span class="texto_encabezado">
									<strong>MANUAL  DE PROCEDIMIENTOS DE LA CALIDAD</strong><br><em>CONCRETO  LANZADO DE FRESNILLO, S.A DE C.V.</em>
								</span>
							</div>
						</td>
					</tr>
					<tr>
						<td colspan="<?php echo $meses*$num_col+1; ?>" align="center" class="borde_linea">
							<span class="sub_encabezado">
								CONFIDENCIAL, PROPIEDAD DE &ldquo;CONCRETO LANZADO DE FRESNILLO, S.A DE C.V.&rdquo; PROHIBIDA SU REPRODUCCI&Oacute;N TOTAL O PARCIAL
							</span>
						</td>
					</tr>
					<tr>
						<td colspan="<?php echo $meses*$num_col+1; ?>">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="<?php echo $meses*$num_col+1; ?>">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="<?php echo $meses*$num_col+1; ?>" align="center" class="titulo_tabla">
							REPORTE DE SALIDAS DEL <?php echo $fechaI; ?> AL <?php echo $fechaC; ?> <br> DOLAR: <?php echo $dolar; ?>&nbsp;&nbsp;&nbsp;&nbsp;EURO: <?php echo $euro; ?>
						</td>
					</tr>
					<tr>
						<td colspan="<?php echo $meses*$num_col+1; ?>">&nbsp;</td>
					</tr>     			
					<tr>					
						<td class='nombres_columnas' rowspan="2">CATEGORIA</td>
						<?php
						for($i=0; $i<$num_col; $i++){
						?>
							<td class='nombres_columnas' colspan="<?php echo $meses; ?>" style="border-width:1px; border-left: solid; border-right: solid;"><?php echo $year_ini+$i; ?></td>
						<?php
						}
						?>
					</tr>
					<tr>
						<?php
						for($i=0; $i<$num_col; $i++){
						?>
							<td class='nombres_columnas' style="border-width:1px; border-left: solid;">ENERO</td>
							<td class='nombres_columnas'>FEBRERO</td>
							<td class='nombres_columnas'>MARZO</td>
							<td class='nombres_columnas'>ABRIL</td>
							<td class='nombres_columnas'>MAYO</td>
							<td class='nombres_columnas'>JUNIO</td>
							<td class='nombres_columnas'>JULIO</td>
							<td class='nombres_columnas'>AGOSTO</td>
							<td class='nombres_columnas'>SEPTIEMBRE</td>
							<td class='nombres_columnas'>OCTUBRE</td>
							<td class='nombres_columnas'>NOVIEMBRE</td>
							<td class='nombres_columnas' style="border-width:1px; border-right: solid;">DICIEMBRE</td>
						<?php
						}
						?>
					</tr>
					<?php
					$arreglo = crearArregloCat($year_ini,$num_col);
					if($filtro_cc == "DESARROLLO"){
						$arr_cc = array(0=>"CONT002",1=>"CONT010",2=>"CONT015");
					}
					else if($filtro_cc == "ZARPEO"){
						$arr_cc = array(0=>"CONT016",1=>"CONT003",2=>"CONT004",3=>"CONT005",4=>"CONT014");
					}
					else if($filtro_cc == "PLANTAS"){
						$arr_cc = array(0=>"CONT012",1=>"CONT020",2=>"CONT021");
					} else {
						$arr_cc = array(0=>$filtro_cc);
					}
					for($i=0; $i<count($arr_cc); $i++){
						$conn = conecta('bd_almacen');
						$arreglo = agregarTotallesArregloCat($f1,$f2,$arr_cc[$i],$filtro_cuenta,$filtro_cat,$filtro_equipo,$dolar,$euro,$arreglo);
						mysql_close($conn);
					}
					mostrarDatosArregloCat($arreglo,$meses,$num_col);
					//echo "<pre>";
					//print_r($arreglo);
					//echo "</pre>";
					?>
				</table>
			</div>
		</body>
		<?php
	}
	
	function crearArregloCat($a_ini,$years){
		$limite = $a_ini + $years;
		
		$conn = conecta("bd_recursos");
		$stm_sql = "SELECT id_cuentas, descripcion
					FROM  `cuentas` 
					WHERE  `habilitado` LIKE  'SI'
					ORDER BY  `descripcion` ASC ";
		$rs = mysql_query($stm_sql);
		if($cuenta = mysql_fetch_array($rs)){
			do{
				if(!isset($dato)){
					$dato = array(
								$cuenta["id_cuentas"] => array("nombre_cuenta"=>$cuenta["descripcion"])
							);
				} else {
					$dato[$cuenta["id_cuentas"]] = array("nombre_cuenta"=>$cuenta["descripcion"]);
				}
				$conn_cat = conecta("bd_almacen");
				$stm_sql_cat = "SELECT DISTINCT T1.id_categoria, UPPER( T1.descripcion ) AS categoria
								FROM categorias_mat AS T1
								JOIN rel_cat_costos AS T2
								USING ( id_categoria ) 
								WHERE T2.id_cuentas LIKE  '$cuenta[id_cuentas]'
								ORDER BY categoria";
				$rs_cat = mysql_query($stm_sql_cat);
				if($categoria = mysql_fetch_array($rs_cat)){
					do{
						$dato[$cuenta["id_cuentas"]][$categoria["id_categoria"]] = array("nombre" => $categoria["categoria"]);
						for($i=$a_ini; $i<$limite; $i++){
							$dato[$cuenta["id_cuentas"]][$categoria["id_categoria"]][$i] = array();
							for($j=1; $j<=12; $j++){
								$mes = obtenerNombreMeses($j);
								$dato[$cuenta["id_cuentas"]][$categoria["id_categoria"]][$i][$mes] = array("total" => 0);
							}
						}
					}while($categoria = mysql_fetch_array($rs_cat));
				}
			}while($cuenta = mysql_fetch_array($rs));
			$dato["OTRAS"] = array("nombre_cuenta"=>"OTRAS CUENTAS");
			$dato["OTRAS"]["EXT"] = array("nombre" => "EXTRAS");
			for($i=$a_ini; $i<$limite; $i++){
				$dato["OTRAS"]["EXT"][$i] = array();
				for($j=1; $j<=12; $j++){
					$mes = obtenerNombreMeses($j);
					$dato["OTRAS"]["EXT"][$i][$mes] = array("total" => 0);
				}
			}
		}
		mysql_close($conn);
		return $dato;
	}
	
	function agregarTotallesArregloCat($f1,$f2,$id_cc,$id_cuenta,$id_cat,$id_equipo,$dolar,$euro,$arreglo){
		$stm_sql_salidas = "SELECT DISTINCT T2.salidas_id_salida, T1.fecha_salida, T1.solicitante, IF( T1.destino =  'EPP', T4.id_control_costos, T1.destino ) AS centro_costo, IF( T1.destino =  'EPP', T4.id_cuentas, T1.cuentas ) AS cuenta, T1.subcuentas, T1.turno, T2.materiales_id_material, T2.nom_material, T2.unidad_material, T2.cant_salida, T2.costo_unidad, IF( T2.moneda =  '', T5.moneda, T2.moneda ) AS moneda, T2.id_equipo_destino, T5.categoria
							FROM salidas AS T1
							JOIN detalle_salidas AS T2 ON T1.id_salida = T2.salidas_id_salida
							LEFT JOIN detalle_es AS T3 ON T1.no_vale = T3.no_vale
							AND T2.materiales_id_material = T3.materiales_id_material
							LEFT JOIN bd_recursos.empleados AS T4 ON T3.empleados_rfc_empleado = T4.rfc_empleado
							JOIN materiales AS T5 ON T5.id_material = T2.materiales_id_material
							WHERE fecha_salida
							BETWEEN  '$f1'
							AND  '$f2'";
		
		if($id_cc != "TODOS"){
			$stm_sql_salidas .= " AND (
									T1.destino =  '$id_cc'
									OR (
										T1.destino =  'EPP'
										AND T4.id_control_costos =  '$id_cc'
									)
								)";
		}
		if($id_cuenta != "TODAS"){
			$stm_sql_salidas .= " AND (
									T1.cuentas =  '$id_cuenta'
									OR (
										T1.destino =  'EPP'
										AND T4.id_cuentas =  '$id_cuenta'
									)
								)";
		}
		if($id_equipo != "TODOS"){
			$stm_sql_salidas .= " AND T2.id_equipo_destino =  '$id_equipo'";
		}
		if($id_cat != "TODAS"){
			$stm_sql_salidas .= " AND T5.categoria = '$id_cat'";
		}
		$stm_sql_salidas .= " ORDER BY fecha_salida, cuenta, categoria";
		$rs_salidas = mysql_query($stm_sql_salidas);
		if($rs_salidas){
			if($dato_salidas = mysql_fetch_array($rs_salidas)){
				do{
					$fecha = explode("-",$dato_salidas["fecha_salida"]);
					$year = $fecha[0];
					$mes = obtenerNombreMeses($fecha[1]);
					$cc = $dato_salidas["centro_costo"];
					$cuenta = $dato_salidas["cuenta"];
					$categoria = $dato_salidas["categoria"];
					$costo_unit = $dato_salidas["costo_unidad"];
					if($dato_salidas["moneda"] == "DOLARES"){
						$costo_unit = $costo_unit * $dolar;
					}
					else if($dato_salidas["moneda"] == "EUROS"){
						$costo_unit = $costo_unit * $euro;
					}
					$total = $dato_salidas['cant_salida'] * $costo_unit;
					$arreglo = modificarArregloCat($year,$mes,$cuenta,$categoria,$total,$arreglo,$cc);
				}while($dato_salidas = mysql_fetch_array($rs_salidas));
			}
		}
		return $arreglo;
	}
	
	function modificarArregloCat($year,$mes,$cuenta,$categoria,$total,$arreglo,$cc){
		
		if(isset($arreglo[$cuenta][$categoria][$year][$mes]) && comprobarRelacioCat($cc,$cuenta,$categoria)){
			$subt = $arreglo[$cuenta][$categoria][$year][$mes]["total"];
			$arreglo[$cuenta][$categoria][$year][$mes]["total"] = $subt + $total;
		} else {
			$subt = $arreglo["OTRAS"]["EXT"][$year][$mes]["total"];
			$arreglo["OTRAS"]["EXT"][$year][$mes]["total"] = $subt + $total;
		}
		return $arreglo;
	}
	
	function mostrarDatosArregloCat($arreglo,$num_meses,$years){
		foreach($arreglo as $cuenta=>$datos_cuenta){
			$cont = 0;
			$nom_clase = "renglon_blanco";
			foreach($datos_cuenta as $categoria=>$datos_categoria){
				?>
				<tr>
				<?php
				if($categoria == "nombre_cuenta"){
					?>
					<td class='titulos_cuentas' colspan="<?php echo ($num_meses*$years)+1; ?>"><?php echo $datos_categoria; ?></td>
					<?php
				} else {
					foreach($datos_categoria as $anio=>$dato_anios){
						if($anio == "nombre"){
							?>
							<td class='nombres_filas'><?php echo $dato_anios; ?></td>
							<?php
						} else {
							foreach($dato_anios as $meses=>$dato_meses){
								foreach($dato_meses as $total){
									if($total == 0){
										?>
										<td class="<?php echo $nom_clase; ?>">$<?php echo number_format($total,2,".",","); ?></td>
										<?php
									} else {
										?>
										<td class="<?php echo $nom_clase; ?>"><strong>$<?php echo number_format($total,2,".",","); ?></strong></td>
										<?php
									}
								}
							}
						}
					}
				}
				?>
				</tr>
				<?php
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
			}
		}
	}
	
	function comprobarRelacioCat($id_cc,$id_cuenta,$id_cat){
		$existe = false;
		$stm_sql_rel = "SELECT * 
						FROM  `rel_cat_costos` 
						WHERE  `id_categoria` LIKE  '$id_cat'
						AND  `id_control_costos` LIKE  '$id_cc'
						AND  `id_cuentas` LIKE  '$id_cuenta'";
		$rs_rel = mysql_query($stm_sql_rel);
		if($rs_rel){
			if(mysql_fetch_array($rs_rel)){
				$existe = true;
			}
		}
		return $existe;
	}

	function obtenerCentroCostos($valor,$partida){
		$busq = substr($valor,0,3);
		$base = '';

		if( $busq == "PED" ){
			$stm_sql = "SELECT T2.descripcion
						FROM `detalles_pedido` AS T1
						JOIN bd_recursos.control_costos AS T2 ON T1.id_control_costos = T2.id_control_costos
						WHERE `pedido_id_pedido` LIKE '$valor'
						AND `partida` =$partida";
			$base = "bd_compras";
		} else if( $busq == "SEM" || $busq == "SEC" ){
			$stm_sql = "SELECT T2.descripcion
						FROM `orden_servicios_externos` AS T1
						JOIN bd_recursos.control_costos AS T2 ON T1.id_control_costos = T2.id_control_costos
						WHERE `id_orden` LIKE '$valor'";
			$base = "bd_mantenimiento";
		} else {
			return "";
		}

		$conn_cc = conecta("$base");

		$rs_cc = mysql_query($stm_sql);

		if( $rs_cc ){
			$datos_cc = mysql_fetch_array( $rs_cc );
			return $datos_cc['descripcion'];
		} else {
			return "";
		}
	}
?>