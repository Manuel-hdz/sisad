<?php 
	include("../../includes/conexion.inc");
	include("../../includes/op_operacionesBD.php");
	
	if(isset($_POST['hdn_consulta'])){
		
		//Ubicacion de las imagenes que estan contenidas en los encabezados
		define("HOST", $_SERVER['HTTP_HOST']);
		//Obtener el nombre del Nombre de la Carpeta Ra�z donde se encontrar� almacenado el SISAD
		$raiz = explode("/",$_SERVER['PHP_SELF']);
		define("SISAD",$raiz[1]);
		
		switch($hdn_origen){
			case "compras":
				guardarRepCompras($hdn_consulta,$hdn_nomReporte,$hdn_msg);
			break;
			case "ventas":
				guardarRepVentas($hdn_consulta,$hdn_nomReporte,$hdn_msg);
			break;	
			case "detalle_compras":
				guardarRepComprasDetalle($hdn_consulta,$hdn_nomReporte,$hdn_msg);
			break;
			case "reporte_pedidos":
				guardarRepPedidos($hdn_consulta,$hdn_nomReporte,$hdn_msg);
			break;
			case "reporte_requisiciones":
				guardarRepRequisiciones($hdn_consulta,$hdn_nomReporte,$hdn_msg);
			break;
			case "reporte_detallerequisiciones":
				guardarRepDetalleReq($hdn_consulta,$hdn_nomReporte,$hdn_msg);
			break;
			case "reporte_detallepedidos":
				guardarRepDetallePedidos($hdn_consulta,$hdn_nomReporte,$hdn_msg);
			break;
			case "OTSE":
				guardarRepOTSE($hdn_consulta,$hdn_nomReporte,$hdn_msg);
			break;
			case "reporte_OTSE":
				guardarRepOrdenesTrabajoSE($hdn_consulta,$hdn_nomReporte,$hdn_msg);
			break;
			case "reporte_PedOTSE":
				guardarRepPedidosOTSE($hdn_nomReporte);
			break;
			case "ReporteGastos":
				guardarRepGastos($hdn_consulta,$hdn_msg);
			break;
			case 'juntaActividades':
			guardarRepActJunta($hdn_consulta, $hdn_nomReporte, $hdn_msg);
			break;
		}										
	}
	
	
	if(isset($_POST["hdn_divExpRepPagos"])){
		//Ubicacion de las imagenes que estan contenidas en los encabezados
		//define("HOST", $_SERVER['HTTP_HOST']);
		//Obtener el nombre del Nombre de la Carpeta Ra�z donde se encontrar� almacenado el SISAD
		$raiz = explode("/",$_SERVER['PHP_SELF']);
		//define("SISAD",$raiz[1]);
		guardarRepPagos();
	}
	
	//Funcion que genera el reporte de activiades de la junta
	function guardarRepActJunta($hdn_consulta, $hdn_nomReporte, $hdn_msg) {
		header("Content-type: application/vnd.ms-excel; name='excel'");
		header("Content-Disposition: attachment; filename=$hdn_nomReporte.xls");
		header("Content-Disposition: filename=$hdn_nomReporte.xls");
		header("Pragma: no-cache");
		header("Expires: 0");

		$conn = conecta("bd_gerencia");
		$rs = mysql_query($hdn_consulta);
		?>
		<head>
			<style>
				body {
					font-family: Arial, Helvetica, sans-serif;
					font-size: 12px;
					color: #000000;
				}

				/*En este formato  se establece el tama�o,tipo de letra,color de fondo que ocupa la columna princiaplal de una tebla*/
				.nombres_columnas {
					font-family: Arial, Helvetica, sans-serif;
					font-size: 13px;
					color: #FFFFFF;
					background-color: #9BBA59;
					font-weight: bold;
					border-top-width: medium;
					border-right-width: thin;
					border-bottom-width: medium;
					border-left-width: thin;
					border-top-style: solid;
					border-right-style: none;
					border-bottom-style:
						solid;
					border-left-style: none;
					border-top-color: #000000;
					border-bottom-color: #000000;
				}

				/*En este formato  se establece el tama�o,tipo de letra,color de fondo que se ocupa en la fila principal para resaltar datos  de una tebla*/
				.nombres_filas {
					font-family: Arial, Helvetica, sans-serif;
					font-size: 9px;
					color: #000000;
					background-color: #9BBB59;
				}

				/*En este formato  se establece el tama�o,tipo de letra,color de fondo que contendra datos  de una tebla*/
				.renglon_gris {
					font-family: Arial, Helvetica, sans-serif;
					font-size: 10px;
					color: #000000;
					background-color: #E7E7E7;
				}

				/*En este formato  se establece el tama�o,tipo de letra,color de fondo que contendra datos  de una tebla*/
				.renglon_blanco {
					font-family: Arial, Helvetica, sans-serif;
					font-size: 10px;
					color: #000000;
					background-color: #FFFFFF;
				}

				#tabla {
					position: absolute;
					left: 0px;
					top: 0px;
					width: 1111px;
					height: 175px;
					z-index: 5;
				}

				.texto_encabezado {
					font-family: Arial, Helvetica, sans-serif;
					font-size: 10px;
					color: #0000CC;
				}

				.sub_encabezado {
					font-family: Arial, Helvetica, sans-serif;
					font-size: 9px;
					color: #0000CC;
					text-align: center !important;
				}

				.titulo_tabla {
					font-family: Arial, Helvetica, sans-serif;
					font-weight: bold;
					font-size: 14px;
					text-align: center;
				}

				.borde_linea {
					border-top: 3px;
					border-top-color: #4E6128;
					border-top-style: solid;
				}

				/* Definir estado por medio de iconos */
				.face-terminado {
					font-family: Wingdings;
					font-size: medium;
					text-align: center;
					background: rgb(0, 255, 0);
				}

				.face-pendiente {
					font-family: Wingdings;
					font-size: medium;
					text-align: center;
					background: rgb(255, 204, 0);
				}

				.face-alerta {
					font-family: Wingdings;
					font-size: medium;
					text-align: center;
					background: rgb(255, 0, 0);
				}
			</style>
		</head>

		<body>
			<table>
				<tr>
					<td align="left" valign="baseline" colspan="2"><img
							src="http://<?php echo HOST; ?>/<?php echo SISAD; ?>/images/logo.png" width="118" height="58"
							align="absbottom" /></td>
					<td colspan="3">&nbsp;</td>
					<td valign="baseline" colspan="2">
						<div align="right"><span class="texto_encabezado">
								<strong>REPORTE JUNTA ADMINISTRATIVA</strong><br><em>CONCRETO LANZADO DE FRESNILLO, S.A DE C.V.</em>
							</span></div>
					</td>
				</tr>
				<tr>
					<td colspan="7" align="center" class="borde_linea">
						<span class="sub_encabezado">CONFIDENCIAL, PROPIEDAD DE &ldquo;CONCRETO LANZADO DE FRESNILLO, S.A DE
							C.V.&rdquo; PROHIBIDA SU REPRODUCCI
							&Oacute;N TOTAL O PARCIAL</span>
					</td>
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
				<thead>
					<th class="nombres_columnas">ACTIVIDAD</th>
					<th class="nombres_columnas">AREA</th>
					<th class="nombres_columnas">FECHA INICIAL</th>
					<th class="nombres_columnas">FECHA FIN</th>
					<th class="nombres_columnas">ESTADO</th>
					<th class="nombres_columnas">OBSERVACIONES</th>
					<th class="nombres_columnas">PROGRESO</th>
				</thead>
				<tbody>
					<?php
						include_once("../../includes/func_fechas.php");
						include_once("op_registrarJunta.php");

						$cont = 0;
						$clase_renglon = "";
		
						$estado = "";
						$clase = "";
						while ($datos = mysql_fetch_array($rs)) {
							if ($datos['estado'] == 'TERMINADA') {
								$estado = "J";
								$clase = "face-terminado";
							}
							if ($datos['estado'] == 'PENDIENTE') {
								$estado = "K";
								$clase = "face-pendiente";
							}
							if ($datos['estado'] == 'ALERTA') {
								$estado = "L";
								$clase = "face-alerta";
							}
		
							if ($cont%2 == 0) {
								$clase_renglon = "renglon_blanco";
							} else {
								$clase_renglon = "renglon_gris";
							}
							?>
					<tr style="text-align: center;">
						<td class="<?php echo $clase_renglon; ?>"><?php echo $datos['actividad']; ?></td>
						<td class="<?php echo $clase_renglon; ?>">
							<?php echo consultarResponsablesAct($datos['id_actividad'], 2); ?></td>
						<td class="<?php echo $clase_renglon; ?>"><?php echo modFecha($datos['fecha_ini'],7); ?></td>
						<td class="<?php echo $clase_renglon; ?>"><?php echo modFecha($datos['fecha_fin'],7); ?></td>
						<td class="<?php echo $clase; ?>"" ><?php echo $estado; ?></td>
						<td class="<?php echo $clase_renglon; ?>"><?php echo $datos['observaciones']; ?></td>
						<td class="<?php echo $clase_renglon; ?>"><?php echo $datos['porcentaje']; ?>%</td>
					</tr>
					<?php
						$cont++; 
						}
						?>
				</tbody>
			</table>
		</body>
		<?php
		mysql_close($conn);
	}


	//Funcion que guarda directamente los reportes de pagos
	function guardarRepPagos(){
		header("Content-type: application/vnd.ms-excel; name='excel'");
		header("Content-Disposition: attachment; filename=ReportePagos.xls");
		header("Content-Disposition: filename=ReportePagos.xls");
		header("Pragma: no-cache");
		header("Expires: 0");
		?>
		<head>
			<style>					
				
				body { font-family:Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; }
				/*En este formato  se establece el tama�o,tipo de letra,color de fondo que ocupa la columna principal de una tabla*/
				.nombres_columnas { font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #FFFFFF; background-color: #9BBA59; font-weight: bold; 
				border-top-width: medium; border-right-width: thin;
				border-bottom-width: medium; border-left-width: thin; border-top-style: solid; border-right-style: none; border-bottom-style: 
				solid; border-left-style: none; 
				border-top-color: #000000; border-bottom-color: #000000; }
				/*En este formato  se establece el tama�o,tipo de letra,color de fondo que se ocupa en la fila principal para resaltar datos  de una tabla*/
				.nombres_filas { font-family: Arial, Helvetica, sans-serif; font-size: 11px; color: #000000; background-color: #9BBB59; font-weight:bold; }
				/*En este formato  se establece el tama�o,tipo de letra,color de fondo que contendra datos  de una tabla*/
				.renglon_gris { font-family: Arial, Helvetica, sans-serif; font-size: 11px; color: #000000; background-color: #E7E7E7; }
				/*En este formato  se establece el tama�o,tipo de letra,color de fondo que contendra datos  de una tabla*/
				.renglon_blanco { font-family: Arial, Helvetica, sans-serif; font-size: 11px; color: #000000; background-color: #FFFFFF; } 
				#tabla { position:absolute; left:0px; top:0px; width:1111px; height:175px; z-index:5; }
				.texto_encabezado {font-family: Arial, Helvetica, sans-serif; font-size: 11px; color: #0000CC; }
				.sub_encabezado {font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #0000CC; }
				.titulo_etiqueta {font-family: Arial, Helvetica, sans-serif; font-weight: bold; font-size: 14px; }
				.borde_linea { border-top:3px; border-top-color:#4E6128; border-top-style:solid;}
				
			</style>
		</head>	
		<table>
			<tr>
				<td align="left" valign="baseline" colspan="4"><img src="http://<?php echo 'HOST'; ?>/<?php echo 'SISAD'; ?>/images/logo.png" width="150" height="70" 
				align="absbottom" /></td>
				<td colspan="4">&nbsp;</td>
				<td valign="baseline" colspan="4">
					<div align="right"><span class="texto_encabezado">
						<strong></strong><br><em></em>
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
		echo $_POST['hdn_divExpRepPagos'];
	}
	
	
	//Esta funcion exporte el REPORTE REA a un archivo de excel
	function guardarRepCompras($hdn_consulta,$hdn_nomReporte,$hdn_msg){
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
					/*En este formato  se establece el tama�o,tipo de letra,color de fondo que ocupa la columna princiaplal de una tebla*/
					.nombres_columnas { font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #FFFFFF; background-color: #9BBA59; font-weight: bold; 
					border-top-width: medium; border-right-width: thin;
					border-bottom-width: medium; border-left-width: thin; border-top-style: solid; border-right-style: none; border-bottom-style: 
					solid; border-left-style: none; 
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
						<td align="left" valign="baseline" colspan="2"><img src="http://<?php echo HOST; ?>/<?php echo SISAD; ?>/images/logo.png" width="118" height="58" 
                        align="absbottom" /></td>
						<td colspan="4">&nbsp;</td>
						<td valign="baseline" colspan="2">
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
						<td class="nombres_columnas" align="center">No.</td>
						<td class="nombres_columnas" align="center">FECHA CREACION</td>
						<td class="nombres_columnas" align="center">FECHA PAGO</td>
						<td class="nombres_columnas" align="center">FECHA ENTRADA</td>
						<td class="nombres_columnas" align="center">ESTADO PAGO</td>
						<td class="nombres_columnas" align="center">PROVEEDOR</td>
						<td class="nombres_columnas" align="center">TIPO MONEDA</td>
						<td class="nombres_columnas" align="center">TOTAL</td>																																											
      				</tr>
			<?php
			$nom_clase = "renglon_gris";
			$cont = 1;
			//Cantidad Total Pesos
			$cant_totalP = 0;
			//Cantidad Total Dolares
			$cant_totalD = 0;
			do{
				$fecha_pago='NO PAGADO';
				$fecha_entrega='NO ENTREGADO';
				if($datos['fecha_pago']!="")
					$fecha_pago=$datos['fecha_pago'];									
				if($datos['fecha_entrega']!="")
					$fecha_entrega=$datos['fecha_entrega'];
			
				?>			
				<tr>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['id_pedido']; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['fecha']; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $fecha_pago; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $fecha_entrega; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['estado']; ?></td>
					<td align="left" class="<?php echo $nom_clase; ?>"><?php echo $datos['razon_social']; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['tipo_moneda']; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>">$<?php echo number_format($datos['total'],2,".",","); ?></td>
				</tr><?php
				if($datos["tipo_moneda"]=="PESOS")
					$cant_totalP += $datos['total'];
				else
					$cant_totalD += $datos['total'];	
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
					
			}while($datos=mysql_fetch_array($rs_datos)); ?>
				<tr><td colspan="6">&nbsp;</td><td class='nombres_columnas' align="center">TOTAL M.N.</td><td class="nombres_columnas" align="center">$<?php echo number_format($cant_totalP,2,".",",");?></td></tr>
				<tr><td colspan="6">&nbsp;</td><td class='nombres_columnas' align="center">TOTAL USD</td><td class="nombres_columnas" align="center">$<?php echo number_format($cant_totalD,2,".",",");?></td></tr>
			</table>
			</div>
			</body><?php	
		}
		
		//Cerrar la conexion con la BD		
		mysql_close($conn);		
	}//Fin de la Funcion guardarRepREA($hdn_consulta,$hdn_nomReporte)
	
	//Esta funcion exporta el REPORTE OTSE a un archivo de excel
	function guardarRepOTSE($hdn_consulta,$hdn_nomReporte,$hdn_msg){
		//Esribir datos de la pagina en excel
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=$hdn_nomReporte.xls");
				
		//Conectar a la BD de Mantenimiento
		$conn = conecta("bd_mantenimiento");
		//Ejecutar la sentencia y almacena los datos de la consulta en la variable $rs (result set)
		$rs_datos = mysql_query($hdn_consulta);
			
		if($datos_OTSE=mysql_fetch_array($rs_datos)){
			//Definir el estilo de la tabla y el encabezado que aparecera sobre la tabla?>
			<head>
				<style>					
					<!--
					body { font-family:Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; }
					/*En este formato  se establece el tama�o,tipo de letra,color de fondo que ocupa la columna princiaplal de una tebla*/
					.nombres_columnas { font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #FFFFFF; background-color: #9BBA59; font-weight: bold; 
					border-top-width: medium; border-right-width: thin;
					border-bottom-width: medium; border-left-width: thin; border-top-style: solid; border-right-style: none; border-bottom-style: 
					solid; border-left-style: none; 
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
						<td align="left" valign="baseline" colspan="2"><img src="http://<?php echo HOST; ?>/<?php echo SISAD; ?>/images/logo.png" width="118" height="58" 
                        align="absbottom" /></td>
						<td colspan="4">&nbsp;</td>
						<td valign="baseline" colspan="2">
							<div align="right"><span class="texto_encabezado">
								<strong>REPORTE OTSE</strong><br><em>CONCRETO  LANZADO DE FRESNILLO, S.A DE C.V.</em>
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
						<td class='nombres_columnas' align="center">ID ORDEN</td>
						<td class='nombres_columnas' align="center">FECHA REGISTRO</td>
						<td class='nombres_columnas' align="center">PROVEEDOR</td>
						<td class='nombres_columnas' align="center">DIRECCI&Oacute;N</td>
						<td class='nombres_columnas' align="center">FECHA ENTREGA</td>
						<td class='nombres_columnas' align="center">FECHA RECEPCI&Oacute;N</td>
						<td class='nombres_columnas' align="center">REPRESENTANTE PROVEEDOR</td>
						<td class='nombres_columnas' align="center">ENCARGADO COMPRASAS</td>
						<td class='nombres_columnas' align="center">SOLICIT&Oacute;</td>
						<td class='nombres_columnas' align="center">AUTORIZ&Oacute;</td>
						<td class='nombres_columnas' align="center">IVA</td>
						<td class='nombres_columnas' align="center">COSTO TOTAL</td>
						<td class='nombres_columnas' align="center">MONEDA</td>
						<td class='nombres_columnas' align="center">FACTURA</td>
      				</tr>
			<?php
			$nom_clase = "renglon_gris";
			$cont = 1;
			//Cantidad Total Pesos
			$cant_totalP = 0;
			//Cantidad Total Dolares
			$cant_totalD = 0;
			do{
				//Verificar si esta incluido el Costo, en el caso de que no lo tenga, colocamos la Leyenda N/R
				$costo = "N/R";
				$iva = "N/R";
				if($datos_OTSE['costo_total']!=0){
					$costo = "$".number_format($datos_OTSE['costo_total'],2,".",",");
					$iva = $datos_OTSE['costo_total'] - ($datos_OTSE['costo_total'] / (1 + (16/100) ) ) ;
					$iva = "$".number_format($iva,2,".",",");
				}
				
				//Verificar si esta registrada el No. de Factura
				$factura = "N/R";
				if($datos_OTSE['factura']!="")
					$factura = $datos_OTSE['factura'];	
				
				include_once("../../includes/func_fechas.php");
				
				echo "
					</td>
					<td class='$nom_clase' align='center'>$datos_OTSE[id_orden]</td>
					<td class='$nom_clase' align='center'>".modFecha($datos_OTSE['fecha_creacion'],1)."</td>
					<td class='$nom_clase' align='left'>$datos_OTSE[nom_proveedor]</td>
					<td class='$nom_clase' align='left'>$datos_OTSE[direccion]</td>
					<td class='$nom_clase' align='center'>".modFecha($datos_OTSE['fecha_entrega'],1)."</td>
					<td class='$nom_clase' align='center'>".modFecha($datos_OTSE['fecha_recepcion'],1)."</td>
					<td class='$nom_clase' align='center'>$datos_OTSE[rep_proveedor]</td>
					<td class='$nom_clase' align='center'>$datos_OTSE[encargado_compras]</td>
					<td class='$nom_clase' align='center'>$datos_OTSE[solicito]</td>
					<td class='$nom_clase' align='center'>$datos_OTSE[autorizo]</td>
					<td class='$nom_clase' align='center'>$iva</td>
					<td class='$nom_clase' align='center'>$costo</td>
					<td class='$nom_clase' align='center'>$datos_OTSE[moneda]</td>
					<td class='$nom_clase' align='center'>$factura</td>
				</tr>";
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
					
			}while($datos_OTSE=mysql_fetch_array($rs_datos)); ?>
			
			</table>
			</div>
			</body><?php	
		}
		
		//Cerrar la conexion con la BD		
		mysql_close($conn);		
	}//Fin de la Funcion guardarRepREA($hdn_consulta,$hdn_nomReporte)
	
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
					/*En este formato  se establece el tama�o,tipo de letra,color de fondo que ocupa la columna princiaplal de una tebla*/
					.nombres_columnas { font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #FFFFFF; background-color: #9BBA59; font-weight: bold; 
					border-top-width: medium; border-right-width: thin;
					border-bottom-width: medium; border-left-width: thin; border-top-style: solid; border-right-style: none; border-bottom-style: solid; 
					border-left-style: none; 
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
	
	
	
		//Esta funcion exporta a Excel REPORTE DE ORDEN DE COMPRA
	function guardarRepComprasDetalle($hdn_consulta,$hdn_nomReporte,$hdn_msg){
		//Esribir datos de la pagina en excel
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=$hdn_nomReporte.xls");
				
		//Realizar la conexion a la BD de Almacen
		$conn = conecta("bd_compras");
		//Ejecutar la sentencia y almacena los datos de la consulta en la variable $rs (result set)
		$rs_datos = mysql_query($hdn_consulta);
			
		if($datos=mysql_fetch_array($rs_datos)){
			//Manejo de fechas
			include_once("../../includes/func_fechas.php");
			//Definir el estilo de la tabla y el encabezado que aparecera sobre la tabla?>
			<head>
				<style>					
					
					body { font-family:Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; }
					/*En este formato  se establece el tama�o,tipo de letra,color de fondo que ocupa la columna princiaplal de una tebla*/
					.nombres_columnas { font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #FFFFFF; background-color: #9BBA59; font-weight: bold; 
					border-top-width: medium; border-right-width: thin;
					border-bottom-width: medium; border-left-width: thin; border-top-style: solid; border-right-style: none; border-bottom-style: solid; 
					border-left-style: none; 
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
					
					/*Formato para los Renglones con PESOS*/
					.renglon_peso { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #9C6500; background-color: #FFEB9C; }
					/*Formato para los Renglones con DOLARES*/
					.renglon_dolar { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #006100; background-color: #C6EFCE; } 
					
				</style>
			</head>			
			<body>
			<div id="tabla">				
				<table width="1100">					
					<tr>
						<td align="left" valign="baseline" colspan="3"><img src="http://<?php echo HOST; ?>/<?php echo SISAD; ?>/images/logo.png" width="118" height="58" 
                        align="absbottom" /></td>
						<td colspan="8">&nbsp;</td>
						<td valign="baseline" colspan="3">
							<div align="right"><span class="texto_encabezado">
								<strong>MANUAL DE PROCEDIMIENTOS DE LA CALIDAD</strong><br><em>CONCRETO LANZADO DE FRESNILLO, S.A DE C.V.</em>
							</span></div>
						</td>
					</tr>											
					<tr>
						<td colspan="14" align="center" class="borde_linea">
							<span class="sub_encabezado">CONFIDENCIAL, PROPIEDAD DE &ldquo;CONCRETO LANZADO DE FRESNILLO, S.A DE C.V.&rdquo; PROHIBIDA SU REPRODUCCI
                            &Oacute;N TOTAL O PARCIAL</span>
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
						<td class="nombres_columnas" align="center">ID PEDIDO</td>
						<td class="nombres_columnas" align="center">PROVEEDOR</td>
						<td class="nombres_columnas" align="center">REQUISICI&Oacute;N</td>
                        <td class="nombres_columnas" align="center">FECHA</td>
						<td class="nombres_columnas" align="center">SOLICITANTE</td>
						<td class="nombres_columnas" align="center">UNIDAD</td>
						<td class="nombres_columnas" align="center">CANTIDAD</td>
						<td class='nombres_columnas' align="center" width="10%">PRECIO UNITARIO</td>
						<td class='nombres_columnas' align="center" width="10%">IMPORTE</td>
						<td class="nombres_columnas" align="center">MONEDA</td>
						<td class="nombres_columnas" align="center">DESCRIPCI&Oacute;N</td>
						<td class="nombres_columnas" align="center">EQUIPO</td>
						<td class="nombres_columnas" align="center">COMENTARIOS</td>
						<td class="nombres_columnas" align="center">FORMA PAGO</td>
      				</tr>
			<?php
			$nom_clase = "renglon_gris";
			$cont = 1;
			$cant_total = 0;
			do{	
				$equipo="N/D";
					if ($datos["equipo"]!="")
						$equipo=$datos["equipo"];
						
				//Identificar el Tipo de Moneda para resaltarlo segun corresponda
				//Ademas de asignar el prefijo de la moneda empleada
				if ($datos["tipo_moneda"]=="PESOS"){
					$tipoMoneda="M.N.";
					$claseMoneda="renglon_peso";
				}
				else{
					$tipoMoneda="USD";
					$claseMoneda="renglon_dolar";
				}
			?>			
					<tr>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['id_pedido']; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['razon_social']; ?></td>
						<td align="left" class="<?php echo $nom_clase; ?>"><?php echo $datos['requisiciones_id_requisicion']; ?></td>
                        <td align="left" class="<?php echo $nom_clase; ?>"><?php echo modFecha($datos["fecha"],1); ?></td>
                        <td align="left" class="<?php echo $nom_clase; ?>"><?php echo $datos['solicitor']; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos["unidad"]; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos["cantidad_real"]; ?></td>
						
						<td align="center" class="<?php echo $claseMoneda; ?>"><strong><?php echo "$".number_format($datos["precio_unitario"],2,".",",")?></strong></td>
						<td align="center" class="<?php echo $claseMoneda; ?>"><strong><?php echo "$".number_format($datos["importe"],2,".",",")?></strong></td>
						<td align="center" class="<?php echo $claseMoneda; ?>"><strong><?php echo $tipoMoneda?></strong></td>
						
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos["descripcion"]; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $equipo;?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['comentarios'];?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['cond_pago'];?></td>
					</tr>
				<?php
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
					
			}while($datos=mysql_fetch_array($rs_datos));?>
			</table>
			</div>
			</body>
<?php	}
		
		//Cerrar la conexion con la BD		
		mysql_close($conn);		
	}//Fin de la Funcion guardarRepOrdenCompra($hdn_consulta,$hdn_nomReporte,$hdn_mensaje)
	
	//Esta funcion exporte el REPORTE CORRECTIVO a un archivo de excel
	function guardarRepPedidos($hdn_consulta,$hdn_nomReporte,$hdn_msg){
		//Esribir datos de la pagina en excel
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=$hdn_nomReporte.xls");
				
		//Realizar la conexion a la BD de Mantenimiento
		$conn = conecta("bd_compras");
		//Ejecutar la sentencia y almacena los datos de la consulta en la variable $rs (result set)
		$rs_datos = mysql_query($hdn_consulta);
			
		if($datos=mysql_fetch_array($rs_datos)){
			//Definir el estilo de la tabla y el encabezado que aparecera sobre la tabla?>
			<head>
				<style>					
					<!--
					body { font-family:Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; }
					/*En este formato  se establece el tama�o,tipo de letra,color de fondo que ocupa la columna principal de una tabla*/
					.nombres_columnas { font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #FFFFFF; background-color: #9BBA59; font-weight: bold; 
					border-top-width: medium; border-right-width: thin;
					border-bottom-width: medium; border-left-width: thin; border-top-style: solid; border-right-style: none; border-bottom-style: solid; 
					border-left-style: none; 
					border-top-color: #000000; border-bottom-color: #000000; }
					/*En este formato  se establece el tama�o,tipo de letra,color de fondo que se ocupa en la fila principal para resaltar datos  de una tabla*/
					.nombres_filas { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #9BBB59; }
					/*En este formato  se establece el tama�o,tipo de letra,color de fondo que contendra datos  de una tabla*/
					.renglon_gris { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #E7E7E7; }
					/*En este formato  se establece el tama�o,tipo de letra,color de fondo que contendra datos  de una tabla*/
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
						<td class="nombres_columnas" align="center">PEDIDO</td>
						<td class="nombres_columnas" align="center">PROVEEDOR</td>
						<td class="nombres_columnas" align="center">FECHA</td>
						<td class="nombres_columnas" align="center">SOLICIT&Oacute;</td>
                        <td class="nombres_columnas" align="center">REALIZ&Oacute;</td>
						<td class="nombres_columnas" align="center">DEPARTAMENTO</td>
						<td class="nombres_columnas" align="center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;TOTAL&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
						<td class="nombres_columnas" align="center">MONEDA</td>
      				</tr>
			<?php
			$nom_clase = "renglon_gris";
			$cont = 1;
			$total_consumo = 0;
			do{
			?>			
				<tr>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['id_pedido']; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['razon_social']; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['fecha']; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['solicitor']; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['revisor']; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['depto_solicitor']; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>">$ <?php echo number_format($datos["total"],2,".",","); ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['tipo_moneda']; ?></td>
				</tr>
				<?php
				$total_consumo += $datos["total"];
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
					
			}while($datos=mysql_fetch_array($rs_datos)); ?>
			<tr>
				<td colspan='5'></td>
				<td class='nombres_columnas' align="center">TOTAL:</td>
				<td class='nombres_columnas' align="center">$ <?php echo number_format($total_consumo,2,".",","); ?></td>
			</tr>
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
		
		//Realizar la conexion a la BD de Mantenimiento
		if($bd != "TODOS"){
			$conn=conecta("$bd");
		
			$stm_sql = "SELECT * 
						FROM  `requisiciones` 
						WHERE  `fecha_req`
						BETWEEN  '$fecha_i'
						AND  '$fecha_f'
						ORDER BY `area_solicitante` ASC";
			
			//Ejecutar la consulta
			$rs = mysql_query($stm_sql);
			//Mostrar los resultados obtenidos
			if($datos = mysql_fetch_array($rs)){?>										
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
							<td class='nombres_columnas' align="center">ID REQUISICI&Oacute;N</td>
							<td class='nombres_columnas' align="center">DEPARTAMENTO</td>
							<td class='nombres_columnas' align="center">FECHA</td>
							<td class='nombres_columnas' align="center">SOLICIT&Oacute;</td>
							<td class='nombres_columnas' align="center">REALIZ&Oacute;</td>
							<td class='nombres_columnas' align="center">ESTADO</td>
							<td class='nombres_columnas' align="center">PRIORIDAD</td>
						</tr>
						<?php
						$nom_clase = "renglon_gris";
						$cont = 1;
						do{
						?>			
							<tr>
								<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['id_requisicion']; ?></td>
								<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['area_solicitante']; ?></td>
								<td align="center" class="<?php echo $nom_clase; ?>"><?php echo modFecha($datos['fecha_req'],1); ?></td>
								<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['solicitante_req']; ?></td>
								<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['elaborador_req']; ?></td>
								<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['estado']; ?></td>
								<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['prioridad']; ?></td>
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
		}
		
		else{
			$conn=conecta("$bd");
		
			$rs_db = mysql_query('SHOW DATABASES;');
			$ciclos = 1;
			$aux = 0;
			$num_bd = mysql_num_rows($rs_db);
			while($db = mysql_fetch_row($rs_db)){
				if(substr( $db[0], 0, 3 ) == "bd_"){
					mysql_select_db("$db[0]");
					$stm_sql = "SELECT * 
								FROM  `requisiciones` 
								WHERE  `fecha_req`
								BETWEEN  '$fecha_i'
								AND  '$fecha_f'
								ORDER BY `area_solicitante` ASC";
				
					//Ejecutar la consulta
					$rs = mysql_query($stm_sql);
					if($rs){
						//Mostrar los resultados obtenidos
						if($datos = mysql_fetch_array($rs)){
							if($aux == 0){?>									
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
											<td class='nombres_columnas' align="center">ID REQUISICI&Oacute;N</td>
											<td class='nombres_columnas' align="center">DEPARTAMENTO</td>
											<td class='nombres_columnas' align="center">FECHA</td>
											<td class='nombres_columnas' align="center">SOLICIT&Oacute;</td>
											<td class='nombres_columnas' align="center">REALIZ&Oacute;</td>
											<td class='nombres_columnas' align="center">ESTADO</td>
											<td class='nombres_columnas' align="center">PRIORIDAD</td>
										</tr>
										<?php
										$nom_clase = "renglon_gris";
										$cont = 1;
							}
							do{
							?>			
								<tr>
									<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['id_requisicion']; ?></td>
									<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['area_solicitante']; ?></td>
									<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['fecha_req']; ?></td>
									<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['solicitante_req']; ?></td>
									<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['elaborador_req']; ?></td>
									<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['estado']; ?></td>
									<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['prioridad']; ?></td>
								</tr>
								<?php
								//Determinar el color del siguiente renglon a dibujar
								$cont++;
								if($cont%2==0)
									$nom_clase = "renglon_blanco";
								else
									$nom_clase = "renglon_gris";
								
							}while($datos=mysql_fetch_array($rs));
							$aux++;
						}
					}
				}
				if($ciclos == $num_bd && $aux>0){
					?>
					</table>
					</div>
					</body>
					<?php
				}
				$ciclos++;
			}
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
		
		//Realizar la conexion a la BD de Mantenimiento
		if($bd != "TODOS"){
			$conn=conecta("$bd");
			if ($clave!="todos"){
				$stm_sql = "SELECT * 
							FROM  `detalle_requisicion` 
							WHERE  `requisiciones_id_requisicion` LIKE  '$clave'";
				
				//Ejecutar la consulta
				$rs = mysql_query($stm_sql);
				//Mostrar los resultados obtenidos
				if($datos = mysql_fetch_array($rs)){?>							
					<body>
					<div id="tabla">				
						<table width="1100">					
							<tr>
								<td align="left" valign="baseline" colspan="3"><img src="http://<?php echo HOST; ?>/<?php echo SISAD; ?>/images/logo.png" width="150" height="65" 
								align="absbottom" /></td>
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
								<td class='nombres_columnas' align='center'>ID REQUISICI&Oacute;N</td>
								<td class='nombres_columnas' align='center'>CANTIDAD</td>
								<td class='nombres_columnas' align='center'>UNIDAD DE MEDIDA</td>
								<td class='nombres_columnas' align='center'>DESCRIPCI&Oacute;N</td>
								<td class='nombres_columnas' align='center'>APLICACI&Oacute;N</td>
								<td class='nombres_columnas' align='center'>ESTADO</td>
							</tr>
							<?php
							$nom_clase = "renglon_gris";
							$cont = 1;
							do{
							?>			
								<tr>
									<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['requisiciones_id_requisicion']; ?></td>
									<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['cant_req']; ?></td>
									<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['unidad_medida']; ?></td>
									<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['descripcion']; ?></td>
									<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['aplicacion']; ?></td>
									<?php
									if($datos['estado'] == 1)
										echo "<td class='$nom_clase'>ENVIADA</td>";
									else if($datos['estado'] == 2)
										echo "<td class='$nom_clase'>PEDIDO</td>";
									else if($datos['estado'] == 3)
										echo "<td class='$nom_clase'>CANCELADA</td>";
									else if($datos['estado'] == 4)
										echo "<td class='$nom_clase'>COTIZANDO</td>";
									else if($datos['estado'] == 5)
										echo "<td class='$nom_clase'>EN PROCESO</td>";
									else if($datos['estado'] == 6)
										echo "<td class='$nom_clase'>EN TRANSITO</td>";
									else if($datos['estado'] == 7)
										echo "<td class='$nom_clase'>ENTREGADA</td>";
									else if($datos['estado'] == 8)
										echo "<td class='$nom_clase'>AUTORIZADA</td>";
									else if($datos['estado'] == 9)
										echo "<td class='$nom_clase'>NO AUTORIZADA</td>";
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
			} else {
				$stm_sql = "SELECT  `detalle_requisicion` . * 
							FROM  `detalle_requisicion` 
							JOIN  `requisiciones` ON  `requisiciones_id_requisicion` =  `id_requisicion` 
							WHERE `fecha_req` 
							BETWEEN  '$fecha_i'
							AND  '$fecha_f'
							ORDER BY  `area_solicitante`,`fecha_req`,`requisiciones_id_requisicion` ASC";
				
				//Ejecutar la consulta
				$rs = mysql_query($stm_sql);
				//Mostrar los resultados obtenidos
				if($datos = mysql_fetch_array($rs)){?>									
					<body>
					<div id="tabla">				
						<table width="1100">					
							<tr>
								<td align="left" valign="baseline" colspan="3"><img src="http://<?php echo HOST; ?>/<?php echo SISAD; ?>/images/logo.png" width="150" height="65" 
								align="absbottom" /></td>
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
								<td class='nombres_columnas' align='center'>ID REQUISICI&Oacute;N</td>
								<td class='nombres_columnas' align='center'>CANTIDAD</td>
								<td class='nombres_columnas' align='center'>UNIDAD DE MEDIDA</td>
								<td class='nombres_columnas' align='center'>DESCRIPCI&Oacute;N</td>
								<td class='nombres_columnas' align='center'>APLICACI&Oacute;N</td>
								<td class='nombres_columnas' align='center'>ESTADO</td>
							</tr>
							<?php
							$nom_clase = "renglon_gris";
							$cont = 1;
							do{
							?>			
								<tr>
									<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['requisiciones_id_requisicion']; ?></td>
									<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['cant_req']; ?></td>
									<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['unidad_medida']; ?></td>
									<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['descripcion']; ?></td>
									<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['aplicacion']; ?></td>
									<?php
									if($datos['estado'] == 1)
										echo "<td class='$nom_clase'>ENVIADA</td>";
									else if($datos['estado'] == 2)
										echo "<td class='$nom_clase'>PEDIDO</td>";
									else if($datos['estado'] == 3)
										echo "<td class='$nom_clase'>CANCELADA</td>";
									else if($datos['estado'] == 4)
										echo "<td class='$nom_clase'>COTIZANDO</td>";
									else if($datos['estado'] == 5)
										echo "<td class='$nom_clase'>EN PROCESO</td>";
									else if($datos['estado'] == 6)
										echo "<td class='$nom_clase'>EN TRANSITO</td>";
									else if($datos['estado'] == 7)
										echo "<td class='$nom_clase'>ENTREGADA</td>";
									else if($datos['estado'] == 8)
										echo "<td class='$nom_clase'>AUTORIZADA</td>";
									else if($datos['estado'] == 9)
										echo "<td class='$nom_clase'>NO AUTORIZADA</td>";
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
			}
		}
		else{
			$conn=conecta("$bd");
			if ($clave!="todos"){
				$rs_db = mysql_query('SHOW DATABASES;');
				while($db = mysql_fetch_row($rs_db)){
					if(substr( $db[0], 0, 3 ) == "bd_"){
						$departamento = strtoupper(substr($db[0], 3));
						mysql_select_db("$db[0]");
						$stm_sql = "SELECT * 
									FROM  `detalle_requisicion` 
									WHERE  `requisiciones_id_requisicion` LIKE  '$clave'";
						
						//Ejecutar la consulta
						$rs = mysql_query($stm_sql);
						//Mostrar los resultados obtenidos
						if($datos = mysql_fetch_array($rs)){?>									
							<body>
							<div id="tabla">				
								<table width="1100">					
									<tr>
										<td align="left" valign="baseline" colspan="3"><img src="http://<?php echo HOST; ?>/<?php echo SISAD; ?>/images/logo.png" width="150" height="65" 
										align="absbottom" /></td>
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
										<td class='nombres_columnas' align='center'>ID REQUISICI&Oacute;N</td>
										<td class='nombres_columnas' align='center'>CANTIDAD</td>
										<td class='nombres_columnas' align='center'>UNIDAD DE MEDIDA</td>
										<td class='nombres_columnas' align='center'>DESCRIPCI&Oacute;N</td>
										<td class='nombres_columnas' align='center'>APLICACI&Oacute;N</td>
										<td class='nombres_columnas' align='center'>ESTADO</td>
									</tr>
									<?php
									$nom_clase = "renglon_gris";
									$cont = 1;
									do{
									?>			
										<tr>
											<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['requisiciones_id_requisicion']; ?></td>
											<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['cant_req']; ?></td>
											<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['unidad_medida']; ?></td>
											<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['descripcion']; ?></td>
											<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['aplicacion']; ?></td>
											<?php
											if($datos['estado'] == 1)
												echo "<td class='$nom_clase'>ENVIADA</td>";
											else if($datos['estado'] == 2)
												echo "<td class='$nom_clase'>PEDIDO</td>";
											else if($datos['estado'] == 3)
												echo "<td class='$nom_clase'>CANCELADA</td>";
											else if($datos['estado'] == 4)
												echo "<td class='$nom_clase'>COTIZANDO</td>";
											else if($datos['estado'] == 5)
												echo "<td class='$nom_clase'>EN PROCESO</td>";
											else if($datos['estado'] == 6)
												echo "<td class='$nom_clase'>EN TRANSITO</td>";
											else if($datos['estado'] == 7)
												echo "<td class='$nom_clase'>ENTREGADA</td>";
											else if($datos['estado'] == 8)
												echo "<td class='$nom_clase'>AUTORIZADA</td>";
											else if($datos['estado'] == 9)
												echo "<td class='$nom_clase'>NO AUTORIZADA</td>";
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
					}
				}
			} else {
				$rs_db = mysql_query('SHOW DATABASES;');
				$ciclos = 1;
				$aux = 0;
				$num_bd = mysql_num_rows($rs_db);
				while($db = mysql_fetch_row($rs_db)){
					if(substr( $db[0], 0, 3 ) == "bd_"){
						mysql_select_db("$db[0]");
						$stm_sql = "SELECT  `detalle_requisicion` . * 
									FROM  `detalle_requisicion` 
									JOIN  `requisiciones` ON  `requisiciones_id_requisicion` =  `id_requisicion` 
									WHERE `fecha_req` 
									BETWEEN  '$fecha_i'
									AND  '$fecha_f'
									ORDER BY  `area_solicitante`,`fecha_req`,`requisiciones_id_requisicion` ASC";
						
						//Ejecutar la consulta
						$rs = mysql_query($stm_sql);
						//Mostrar los resultados obtenidos
						if($rs){
							if($datos = mysql_fetch_array($rs)){
								if($aux == 0){
									//Definir el estilo de la tabla y el encabezado que aparecera sobre la tabla?>							
									<body>
									<div id="tabla">				
										<table width="1100">					
											<tr>
												<td align="left" valign="baseline" colspan="3"><img src="http://<?php echo HOST; ?>/<?php echo SISAD; ?>/images/logo.png" width="150" height="65" 
												align="absbottom" /></td>
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
												<td class='nombres_columnas' align='center'>ID REQUISICI&Oacute;N</td>
												<td class='nombres_columnas' align='center'>CANTIDAD</td>
												<td class='nombres_columnas' align='center'>UNIDAD DE MEDIDA</td>
												<td class='nombres_columnas' align='center'>DESCRIPCI&Oacute;N</td>
												<td class='nombres_columnas' align='center'>APLICACI&Oacute;N</td>
												<td class='nombres_columnas' align='center'>ESTADO</td>
											</tr>
											<?php
											$nom_clase = "renglon_gris";
											$cont = 1;
								}
								do{
								?>			
									<tr>
										<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['requisiciones_id_requisicion']; ?></td>
										<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['cant_req']; ?></td>
										<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['unidad_medida']; ?></td>
										<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['descripcion']; ?></td>
										<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['aplicacion']; ?></td>
										<?php
										if($datos['estado'] == 1)
											echo "<td class='$nom_clase'>ENVIADA</td>";
										else if($datos['estado'] == 2)
											echo "<td class='$nom_clase'>PEDIDO</td>";
										else if($datos['estado'] == 3)
											echo "<td class='$nom_clase'>CANCELADA</td>";
										else if($datos['estado'] == 4)
											echo "<td class='$nom_clase'>COTIZANDO</td>";
										else if($datos['estado'] == 5)
											echo "<td class='$nom_clase'>EN PROCESO</td>";
										else if($datos['estado'] == 6)
											echo "<td class='$nom_clase'>EN TRANSITO</td>";
										else if($datos['estado'] == 7)
											echo "<td class='$nom_clase'>ENTREGADA</td>";
										else if($datos['estado'] == 8)
											echo "<td class='$nom_clase'>AUTORIZADA</td>";
										else if($datos['estado'] == 9)
											echo "<td class='$nom_clase'>NO AUTORIZADA</td>";
										?>
									</tr>
									<?php
									//Determinar el color del siguiente renglon a dibujar
									$cont++;
									if($cont%2==0)
										$nom_clase = "renglon_blanco";
									else
										$nom_clase = "renglon_gris";
								}while($datos=mysql_fetch_array($rs));
								$aux++;
							}
						}
					}
					if($ciclos == $num_bd && $aux>0){
						?>
						</table>
						</div>
						</body>
						<?php
					}
					$ciclos++;
				}
			}
		}
		//Cerrar la conexion con la BD		
		mysql_close($conn);
	}
	
	function guardarRepDetallePedidos($hdn_consulta,$hdn_nomReporte,$hdn_msg){
		//Esribir datos de la pagina en excel
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=$hdn_nomReporte.xls");
				
		//Realizar la conexion a la BD de Mantenimiento
		$conn = conecta("bd_compras");
		//Ejecutar la sentencia y almacena los datos de la consulta en la variable $rs (result set)
		$rs_datos = mysql_query($hdn_consulta);
			
		if($datos=mysql_fetch_array($rs_datos)){
			//Definir el estilo de la tabla y el encabezado que aparecera sobre la tabla?>
			<head>
				<style>					
					<!--
					body { font-family:Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; }
					/*En este formato  se establece el tama�o,tipo de letra,color de fondo que ocupa la columna principal de una tabla*/
					.nombres_columnas { font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #FFFFFF; background-color: #9BBA59; font-weight: bold; 
					border-top-width: medium; border-right-width: thin;
					border-bottom-width: medium; border-left-width: thin; border-top-style: solid; border-right-style: none; border-bottom-style: solid; 
					border-left-style: none; 
					border-top-color: #000000; border-bottom-color: #000000; }
					/*En este formato  se establece el tama�o,tipo de letra,color de fondo que se ocupa en la fila principal para resaltar datos  de una tabla*/
					.nombres_filas { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #9BBB59; }
					/*En este formato  se establece el tama�o,tipo de letra,color de fondo que contendra datos  de una tabla*/
					.renglon_gris { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #E7E7E7; }
					/*En este formato  se establece el tama�o,tipo de letra,color de fondo que contendra datos  de una tabla*/
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
						<td class="nombres_columnas" align="center">PEDIDO</td>
						<td class="nombres_columnas" align="center">FECHA</td>
						<td class="nombres_columnas" align="center">EQUIPO</td>
						<td class="nombres_columnas" align="center">MATERIAL</td>
						<td class="nombres_columnas" align="center">CANTIDAD</td>
                        <td class="nombres_columnas" align="center">COSTO UNITARIO</td>
						<td class="nombres_columnas" align="center">IMPORTE</td>
						<td class="nombres_columnas" align="center">CENTRO DE COSTOS</td>
						<td class="nombres_columnas" align="center">CUENTA</td>
						<td class="nombres_columnas" align="center">SUBCUENTA</td>																
      				</tr>
			<?php
			$nom_clase = "renglon_gris";
			$cont = 1;
			$cant_total = 0;
			do{
			?>			
				<tr>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['pedido_id_pedido']; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['fecha']; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['equipo']; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['material']; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['cantidad_real']; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>">$ <?php echo number_format($datos['precio_unitario'],2,".",","); ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>">$ <?php echo number_format($datos['importe'],2,".",","); ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['cc']; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['cuenta']; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['subcuenta']; ?></td>
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
	
	function guardarRepOrdenesTrabajoSE($hdn_consulta,$hdn_nomReporte,$hdn_msg){
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=$hdn_nomReporte.xls");
		
		$conn = conecta("bd_mantenimiento");
		$rs_datos = mysql_query($hdn_consulta);
			
		if($datos=mysql_fetch_array($rs_datos)){
			?>
			<head>
				<style>					
					<!--
					body { font-family:Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; }
					/*En este formato  se establece el tama�o,tipo de letra,color de fondo que ocupa la columna princiaplal de una tebla*/
					.nombres_columnas { font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #FFFFFF; background-color: #9BBA59; font-weight: bold; 
					border-top-width: medium; border-right-width: thin;
					border-bottom-width: medium; border-left-width: thin; border-top-style: solid; border-right-style: none; border-bottom-style: 
					solid; border-left-style: none; 
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
							<td align="left" valign="baseline" colspan="2">
								<img src="http://<?php echo HOST; ?>/<?php echo SISAD; ?>/images/logo.png" width="118" height="58" align="absbottom" />
							</td>
							<td colspan="10">&nbsp;</td>
							<td valign="baseline" colspan="2">
								<div align="right">
									<span class="texto_encabezado">
										<strong>REPORTE OTSE</strong><br><em>CONCRETO  LANZADO DE FRESNILLO, S.A DE C.V.</em>
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
								<?php echo $hdn_msg; ?>
							</td>
						</tr>
						<tr>
							<td colspan="14">&nbsp;</td>
						</tr>
						<tr>
							<td class="nombres_columnas" align="center">ID ORDEN</td>
							<td class="nombres_columnas" align="center">FECHA</td>
							<td class="nombres_columnas" align="center">CLASIFICACION</td>
							<td class="nombres_columnas" align="center">PROVEEDOR</td>
							<td class="nombres_columnas" align="center">REALIZO</td>
							<td class="nombres_columnas" align="center">SOLICITO</td>
							<td class="nombres_columnas" align="center">AUTORIZO</td>
							<td class="nombres_columnas" align="center">DEPARTAMENTO</td>
							<td class="nombres_columnas" align="center">COMPLEMENTADA</td>
							<td class="nombres_columnas" align="center">SISTEMA</td>
							<td class="nombres_columnas" align="center">APLICACION</td>
							<td class="nombres_columnas" align="center">DESCRIPCION</td>
							<td class="nombres_columnas" align="center">EQUIPO</td>
							<td class="nombres_columnas" align="center">COSTO</td>
						</tr>
						<?php
						$nom_clase = "renglon_gris";
						$cont = 1;
						$crear = true;
						$avance = 0;
						do{
							include_once("../../includes/func_fechas.php");
							$rows = obtenerNumRowsOTSE($datos["orden_servicios_externos_id_orden"]);
							?>
							<tr>
								<?php
								if($crear){
								?>
									<td class="<?php echo $nom_clase; ?>" align="center" style="vertical-align:middle;" rowspan="<?php echo $rows; ?>">
										<?php echo $datos["orden_servicios_externos_id_orden"]; ?>
									</td>
									<td class="<?php echo $nom_clase; ?>" align="center" style="vertical-align:middle;" rowspan="<?php echo $rows; ?>">
										<?php echo modFecha($datos["fecha_entrega"],1); ?>
									</td>
									<td class="<?php echo $nom_clase; ?>" align="center" style="vertical-align:middle;" rowspan="<?php echo $rows; ?>">
										<?php echo $datos["clasificacion"]; ?>
									</td>
									<td class="<?php echo $nom_clase; ?>" align="center" style="vertical-align:middle;" rowspan="<?php echo $rows; ?>">
										<?php echo $datos["nom_proveedor"]; ?>
									</td>
									<td class="<?php echo $nom_clase; ?>" align="center" style="vertical-align:middle;" rowspan="<?php echo $rows; ?>">
										<?php echo $datos["encargado_compras"]; ?>
									</td>
									<td class="<?php echo $nom_clase; ?>" align="center" style="vertical-align:middle;" rowspan="<?php echo $rows; ?>">
										<?php echo $datos["solicito"]; ?>
									</td>
									<td class="<?php echo $nom_clase; ?>" align="center" style="vertical-align:middle;" rowspan="<?php echo $rows; ?>">
										<?php echo $datos["autorizo"]; ?>
									</td>
									<td class="<?php echo $nom_clase; ?>" align="center" style="vertical-align:middle;" rowspan="<?php echo $rows; ?>">
										<?php echo $datos["depto"]; ?>
									</td>
									<td class="<?php echo $nom_clase; ?>" align="center" style="vertical-align:middle;" rowspan="<?php echo $rows; ?>">
										<?php echo $datos["complementada"]; ?>
									</td>
								<?php
								}
								?>
								<td class="<?php echo $nom_clase; ?>" align="center" style="vertical-align:middle;">
									<?php echo $datos["sistema"]; ?>
								</td>
								<td class="<?php echo $nom_clase; ?>" align="center" style="vertical-align:middle;">
									<?php echo $datos["aplicacion"]; ?>
								</td>
								<td class="<?php echo $nom_clase; ?>" align="center" style="vertical-align:middle;">
									<?php echo $datos["descripcion"]; ?>
								</td>
								<td class="<?php echo $nom_clase; ?>" align="center" style="vertical-align:middle;">
									<?php echo $datos["equipo"]; ?>
								</td>
								<td class="<?php echo $nom_clase; ?>" align="center" style="vertical-align:middle;">
									<?php echo "$".number_format($datos["costo_actividad"],2,".",","); ?>
								</td>
							</tr>
							<?php
							$avance++;
							if($rows - $avance > 0){
								$crear = false;
							} else {
								$avance = 0;
								$crear = true;
							}
							$cont++;
							if($cont%2==0)
								$nom_clase = "renglon_blanco";
							else
								$nom_clase = "renglon_gris";
						}while($datos = mysql_fetch_array($rs_datos));
						?>
					</table>
				</div>
			</body>
			<?php
		}
	}
	
	function guardarRepPedidosOTSE($hdn_nomReporte){
		include_once("../../includes/func_fechas.php");
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=$hdn_nomReporte.xls");
		
		$conn=conecta("bd_compras");
		if(isset($_POST["txt_pedido"])){
			$tipo = 1;
			$pedido = strtoupper($_POST["txt_pedido"]);
			if(substr($pedido,0,3) == "PED"){
				$stm_sql = "SELECT T1.id_pedido, T1.proveedores_rfc, T2.razon_social, T3.no_factura, T3.id_entrada, T1.cond_pago, T1.fecha, T1.subtotal, T1.iva, T1.total, T1.tipo_moneda, T1.revisor, T1.estado, T4.fecha_pago, T3.fecha_entrada, T4.factura_recibida
							FROM  `pedido` AS T1
							JOIN proveedores AS T2 ON T1.proveedores_rfc = T2.rfc
							JOIN bd_almacen.entradas AS T3 ON T3.requisiciones_id_requisicion = T1.id_pedido
							LEFT JOIN pedidos_recibidos AS T4 ON T4.id_pedido = T1.id_pedido
							AND T4.factura = T3.no_factura
							WHERE T1.id_pedido =  '$pedido'";
			} else {
				$stm_sql = "SELECT T1.id_orden AS id_pedido, T2.rfc AS proveedores_rfc, T3.proveedor AS razon_social, T3.no_factura, T3.id_entrada, T1.clasificacion AS cond_pago, T1.fecha_entrega AS fecha, ROUND( (
							T1.costo_total / 1.16
							), 2 ) AS subtotal, ROUND( T1.costo_total - ( T1.costo_total / 1.16 ) , 2 ) AS iva, T1.costo_total AS total, T1.moneda AS tipo_moneda, T1.encargado_compras AS revisor,  'NO PAGADO' AS estado, T4.fecha_pago, T3.fecha_entrada, T4.factura_recibida
							FROM bd_mantenimiento.orden_servicios_externos AS T1
							JOIN bd_almacen.entradas AS T3 ON T3.comp_directa = T1.id_orden
							LEFT JOIN bd_compras.proveedores AS T2 ON T2.razon_social = T3.proveedor
							LEFT JOIN bd_compras.pedidos_recibidos AS T4 ON T4.id_pedido = T1.id_orden
							AND T4.factura = T3.no_factura
							WHERE id_orden = '$pedido' 
							AND complementada =  'SI'";
			}
		} else if(isset($_POST["txt_fechaIni"])){
			$tipo = 2;
			$fini = modFecha($_POST["txt_fechaIni"],3);
			$ffin = modFecha($_POST["txt_fechaFin"],3);
			$stm_sql = "SELECT T1.id_pedido, T1.proveedores_rfc, T2.razon_social, T3.no_factura, T3.id_entrada, T1.cond_pago, T1.fecha, T1.subtotal, T1.iva, T1.total, T1.tipo_moneda, T1.revisor, T1.estado, T4.fecha_pago, T3.fecha_entrada, T4.factura_recibida
						FROM  `pedido` AS T1
						JOIN proveedores AS T2 ON T1.proveedores_rfc = T2.rfc
						JOIN bd_almacen.entradas AS T3 ON T3.requisiciones_id_requisicion = T1.id_pedido
						LEFT JOIN pedidos_recibidos AS T4 ON T4.id_pedido = T1.id_pedido
						AND T4.factura = T3.no_factura
						WHERE T1.fecha
						BETWEEN  '$fini'
						AND  '$ffin'";
			
			$stm_sql2 = "SELECT T1.id_orden AS id_pedido, T2.rfc AS proveedores_rfc, T3.proveedor AS razon_social, T3.no_factura, T3.id_entrada, T1.clasificacion AS cond_pago, T1.fecha_entrega AS fecha, ROUND( (
						T1.costo_total / 1.16
						), 2 ) AS subtotal, ROUND( T1.costo_total - ( T1.costo_total / 1.16 ) , 2 ) AS iva, T1.costo_total AS total, T1.moneda AS tipo_moneda, T1.encargado_compras AS revisor,  'NO PAGADO' AS estado, T4.fecha_pago, T3.fecha_entrada, T4.factura_recibida
						FROM bd_mantenimiento.orden_servicios_externos AS T1
						JOIN bd_almacen.entradas AS T3 ON T3.comp_directa = T1.id_orden
						LEFT JOIN bd_compras.proveedores AS T2 ON T2.razon_social = T3.proveedor
						LEFT JOIN bd_compras.pedidos_recibidos AS T4 ON T4.id_pedido = T1.id_orden
						AND T4.factura = T3.no_factura
						WHERE fecha_entrega
						BETWEEN  '$fini'
						AND  '$ffin'
						AND complementada =  'SI'";
		}
		$cont = 1;
		?>
		<head>
			<style>					
				<!--
				body { font-family:Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; }
				/*En este formato  se establece el tama�o,tipo de letra,color de fondo que ocupa la columna princiaplal de una tebla*/
				.nombres_columnas { font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #FFFFFF; background-color: #9BBA59; font-weight: bold; 
				border-top-width: medium; border-right-width: thin;
				border-bottom-width: medium; border-left-width: thin; border-top-style: solid; border-right-style: none; border-bottom-style: 
				solid; border-left-style: none; 
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
				<?php
				for($i=0; $i<$tipo; $i++){
					if($i==0){
						$rs = mysql_query($stm_sql);
					} else {
						$rs = mysql_query($stm_sql2);
					}
					if($datos = mysql_fetch_array($rs)){
						echo 
						"<table cellpadding='5' width='100%' align='center' id='tabla-resultadosPedidos' style='table-layout:fixed;'>";
						?>
							<tr>
								<td align="left" valign="baseline" colspan="2">
									<img src="http://<?php echo HOST; ?>/<?php echo SISAD; ?>/images/logo.png" width="118" height="58" align="absbottom" />
								</td>
								<td colspan="11">&nbsp;</td>
								<td valign="baseline" colspan="2">
									<div align="right">
										<span class="texto_encabezado">
											<strong>REPORTE PEDIDOS Y ORDENES DE TRABAJO</strong><br><em>CONCRETO  LANZADO DE FRESNILLO, S.A DE C.V.</em>
										</span>
									</div>
								</td>
							</tr>
							<tr>
								<td colspan="15" align="center" class="borde_linea">
									<span class="sub_encabezado">
										CONFIDENCIAL, PROPIEDAD DE &ldquo;CONCRETO LANZADO DE FRESNILLO, S.A DE C.V.&rdquo; PROHIBIDA SU REPRODUCCI&Oacute;N TOTAL O PARCIAL
									</span>
								</td>
							</tr>
							<tr>
								<td colspan="15">&nbsp;</td>
							</tr>
							<tr>
								<td colspan="15">&nbsp;</td>
							</tr>
							<tr>
								<td colspan="15" align="center" class="titulo_tabla">
									<?php 
									if(isset($_POST["txt_pedido"])){
										if(substr($_POST["txt_pedido"],0,3) == "PED"){
											echo "CONSULTA DE PEDIDOS Y ORDENES DE TRABAJO - PEDIDO ".$_POST['txt_pedido'];
										} else {
											echo "CONSULTA DE PEDIDOS Y ORDENES DE TRABAJO - OTSE ".$_POST['txt_pedido'];
										}
									} else {
										echo "CONSULTA DE PEDIDOS Y ORDENES DE TRABAJO DEL ".$_POST["txt_fechaIni"]." AL ".$_POST["txt_fechaFin"];
									}
									?>
								</td>
							</tr>
							<tr>
								<td colspan="15">&nbsp;</td>
							</tr>
						<?php
						echo "
							<thead>
								<tr>
									<th class='nombres_columnas' align='center' width=90px>N� PEDIDO</th>
									<th class='nombres_columnas' align='center' width=120px>RFC PROVEEDOR</th>
									<th class='nombres_columnas' align='center' width=300px>PROVEEDOR</th>
									<th class='nombres_columnas' align='center' width=120px>FACTURA/REMISION ALMAC&Eacute;N</th>
									<th class='nombres_columnas' align='center' width=100px>FACTURA RECIBIDA</th>
									<th class='nombres_columnas' align='center' width=100px>CONDICIONES PAGO</th>
									<th class='nombres_columnas' align='center' width=100px>SUBTOTAL</th>
									<th class='nombres_columnas' align='center' width=100px>IVA</th>
									<th class='nombres_columnas' align='center' width=100px>TOTAL</th>
									<th class='nombres_columnas' align='center' width=70px>MONEDA</th>
									<th class='nombres_columnas' align='center' width=210px>REVIS&Oacute;</th>
									<th class='nombres_columnas' align='center' width=80px>ESTADO</th>
									<th class='nombres_columnas' align='center' width=160px>FECHA PEDIDO</th>
									<th class='nombres_columnas' align='center' width=160px>FECHA ENTREGA</th>
									<th class='nombres_columnas' align='center' width=160px>FECHA RECIBIDO</th>
								</tr>
							</thead>";
						$nom_clase = "renglon_gris";
						echo 
							"<tbody>";	
						do{
							$estado = estadoRecibidos($datos["id_pedido"],$datos["no_factura"]);
							if($datos["estado"] == "CANCELADO"){
								$color = "#FF0000";
								$estado = "CANCELADO";
							}
							else if($estado == "RECIBIDO"){
								$color = "#00FF88";
							}
							else
								$color = "";
							echo "	
							<tr>
								<td class='$nom_clase' align='center' style='background-color:$color;'>$datos[id_pedido]</td>					
								<td class='$nom_clase' align='center' style='background-color:$color;'>$datos[proveedores_rfc]</td>					
								<td class='$nom_clase' align='center' style='background-color:$color;'>$datos[razon_social]</td>
								<td class='$nom_clase' align='center' style='background-color:$color;'>$datos[no_factura]</td>
								<td class='$nom_clase' align='center' style='background-color:$color;'>$datos[factura_recibida]</td>
								<td class='$nom_clase' align='center' style='background-color:$color;'>$datos[cond_pago]</td>
								<td class='$nom_clase' align='center' style='background-color:$color;'>$".number_format($datos["subtotal"],2,".",",")."</td>
								<td class='$nom_clase' align='center' style='background-color:$color;'>$".number_format($datos["iva"],2,".",",")."</td>
								<td class='$nom_clase' align='center' style='background-color:$color;'>$".number_format($datos["total"],2,".",",")."</td>
								<td class='$nom_clase' align='center' style='background-color:$color;'>$datos[tipo_moneda]</td>
								<td class='$nom_clase' align='center' style='background-color:$color;'>$datos[revisor]</td>
								<td class='$nom_clase' align='center' style='background-color:$color;'>$estado</td>
								<td class='$nom_clase' align='center' style='background-color:$color;'>".modFecha($datos["fecha"],2)."</td>";
								if ($datos["fecha_entrada"]!="")
									echo"<td class='$nom_clase' align='center' style='background-color:$color;'>".modFecha($datos["fecha_entrada"],2)."</td>";
								else
									echo"<td class='$nom_clase' align='center' style='background-color:$color;'>".$datos["fecha_entrada"]."</td>";
								
								if ($datos["fecha_pago"]!="")
									echo"<td class='$nom_clase' align='center' style='background-color:$color;'>".modFecha($datos["fecha_pago"],2)."</td>";
								else
									echo"<td class='$nom_clase' align='center' style='background-color:$color;'>".$datos["fecha_pago"]."</td>";
								echo"<input type='hidden' id='txt_factura' name='txt_factura' value='$datos[no_factura]' />";
								echo"<input type='hidden' id='txt_idEntrada' name='txt_idEntrada' value='$datos[id_entrada]' />";
										
							echo 
							"</tr>";
							
							//Determinar el color del siguiente renglon a dibujar
							$cont++;
							if($cont%2==0)
								$nom_clase = "renglon_blanco";
							else
								$nom_clase = "renglon_gris";
								
						}while($datos=mysql_fetch_array($rs));
						echo 
							"</tbody>
						</table>";
					}
				}
			?>
			</div>
		</body>
		<?php
	}
	
	function guardarRepGastos($hdn_consulta,$hdn_msg){
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=ReporteGastos.xls");
		
		$conn = conecta("bd_compras");
		$rs_datos = mysql_query($hdn_consulta);
			
		if($datos=mysql_fetch_array($rs_datos)){
			?>
			<head>
				<style>					
					<!--
					body { font-family:Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; }
					/*En este formato  se establece el tama�o,tipo de letra,color de fondo que ocupa la columna princiaplal de una tebla*/
					.nombres_columnas { font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #FFFFFF; background-color: #9BBA59; font-weight: bold; 
					border-top-width: medium; border-right-width: thin;
					border-bottom-width: medium; border-left-width: thin; border-top-style: solid; border-right-style: none; border-bottom-style: 
					solid; border-left-style: none; 
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
					<table width="100%">
						<tr>
							<td align="left" valign="baseline" colspan="2">
								<img src="http://<?php echo HOST; ?>/<?php echo SISAD; ?>/images/logo.png" width="118" height="58" align="absbottom" />
							</td>
							<td valign="baseline" colspan="2">
								<div align="right">
									<span class="texto_encabezado">
										<strong>REPORTE PAGOS</strong><br><em>CONCRETO  LANZADO DE FRESNILLO, S.A DE C.V.</em>
									</span>
								</div>
							</td>
						</tr>
						<tr>
							<td colspan="4" align="center" class="borde_linea">
								<span class="sub_encabezado">
									CONFIDENCIAL, PROPIEDAD DE &ldquo;CONCRETO LANZADO DE FRESNILLO, S.A DE C.V.&rdquo; PROHIBIDA SU REPRODUCCI&Oacute;N TOTAL O PARCIAL
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
							<td colspan="4" align="center" class="titulo_tabla">
								<?php echo $hdn_msg; ?>
							</td>
						</tr>
						<tr>
							<td colspan="4">&nbsp;</td>
						</tr>
						<tr>
							<td class='nombres_columnas' align="center">FECHA</td>
							<td class='nombres_columnas' align="center">FACTURA</td>
							<td class='nombres_columnas' align="center">DESCRIPCION</td>
							<td class='nombres_columnas' align="center">IMPORTE</td>
						</tr>
						<?php
						include_once("../../includes/func_fechas.php");
						$nom_clase = "renglon_gris";
						$cont = 1;
						do{
							?>
							<tr>
								<td class='<?php echo $nom_clase; ?>' align="center"><?php echo modFecha($datos['fecha_gasto'],2); ?></td>
								<td class='<?php echo $nom_clase; ?>' align="center"><?php echo $datos['factura']; ?></td>
								<td class='<?php echo $nom_clase; ?>' align="center"><?php echo $datos['descripcion']; ?></td>
								<td class='<?php echo $nom_clase; ?>' align="center"><?php echo "$".number_format($datos['importe'],2,".",","); ?></td>
							</tr>
							<?php
							$cont++;
							if($cont%2==0)
								$nom_clase = "renglon_blanco";
							else
								$nom_clase = "renglon_gris";
						}while($datos=mysql_fetch_array($rs_datos));
						?>
					</table>
				</div>
			<?php
		}
	}
	
	function obtenerNumRowsOTSE($id_otse){
		$stm_sql_nr = "SELECT * 
					FROM  `actividades_realizadas` 
					WHERE  `orden_servicios_externos_id_orden` LIKE  '$id_otse'";
		$rs_nr = mysql_query($stm_sql_nr);
		$renglones = mysql_num_rows($rs_nr);
		
		return $renglones;
	}
	
	function estadoRecibidos($pedido, $entrada){
		$estado = "NO RECIBIDO";
		$conn = conecta("bd_compras");
		$stm_sql = "SELECT * FROM pedidos_recibidos WHERE id_pedido='$pedido' AND factura='$entrada'";
		$rs = mysql_query($stm_sql);
		if($rs){
			if(mysql_fetch_array($rs))
				$estado = "RECIBIDO";
		}
		return $estado;
	}
?>