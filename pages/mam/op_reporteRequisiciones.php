<?php
	/**
	  * Nombre del Módulo: Mantenimiento                                              
	  * Nombre Programador: Armando Ayala Alvarado
	  * Fecha: 12/Junio/2012
	  * Descripción: Este archivo contiene funciones para generar el Reporte de Consumo de Aceites 
	  **/

	function reporteRequisiciones(){
		?><div id="reporte" class="borde_seccion2" align="center"><?php
		$msg_grafica = "";
		$fecha_i = modFecha($_POST["txt_fecha_ini"],3);
		$fecha_f = modFecha($_POST["txt_fecha_fin"],3);
		if(substr( $_POST["cmb_departamento"], 0, 3) == "bd_")
			$departamento = strtoupper(substr( $_POST["cmb_departamento"], 3));
		else
			$departamento = strtoupper($_POST["cmb_departamento"]);
		$bd = $_POST["cmb_departamento"];
		$msg_titulo = "Reporte de Requisiciones del $_POST[txt_fecha_ini] al $_POST[txt_fecha_fin] Departamento: $departamento";
		if($departamento == "TODOS"){
			$flag = mostrarRequisiciones($bd,$fecha_i,$fecha_f,$msg_titulo);
		} else{
			$flag = mostrarRequisicionesArea($bd,$fecha_i,$fecha_f,$msg_titulo);
		}
		?></div>
		<div id="btns-regpdf">
		<table width="100%">
			<tr>
				<?php if($flag[0]==1) { ?>			
				<td align="center">
					<form action="guardar_reporte.php" method="post">
						<input name="hdn_consulta" type="hidden" value="<?php echo $flag[1]; ?>" />
						<input name="hdn_nomReporte" type="hidden" value="Reporte de Requisiciones" />
						<input name="hdn_tipoReporte" type="hidden" value="reporte_requisiciones" />		
						<input name="hdn_msg" type="hidden" value="<?php echo $msg_titulo; ?>" />
						<input name="hdn_fecha_ini" type="hidden" value="<?php echo modFecha($_POST["txt_fecha_ini"],3); ?>" />
						<input name="hdn_fecha_fin" type="hidden" value="<?php echo modFecha($_POST["txt_fecha_fin"],3); ?>" />
						<input name="hdn_bd" type="hidden" value="<?php echo $bd; ?>" />
						<input name="sbt_excel" type="submit" class="botones" value="Exportar a Excel" title="Exportar a Excel los Datos de la Consulta Realizada" 
                        onMouseOver="window.estatus='';return true"  />
					</form>
				</td>
				<?php } ?>
				<td align="center">
					<input type="button" name="btn_regresar" value="Regresar" class="botones" title="Regresar a la P&aacute;gina de Reportes de Requisiciones" onclick=
                    "location.href='frm_reporteRequisiciones.php'" />
				</td>	
			</tr>
		</table>			
		</div><?php
										
		//Cerrar la conexion con la BD
//		mysql_close($conn);
	}
	
	//Esta función se encarga de mostrar el detalle del Pedido seleccionado
	function mostrarDetalleReqArea($clave,$bd,$fecha_i,$fecha_f){
		$msg_titulo = "";
		$departamento = strtoupper(substr( $bd, 3));
		?><div id="reporte" class="borde_seccion2" align="center"><?php
		//Realizar la conexion a la BD de Compras
		$conn = conecta("$bd");
		
		if ($clave!="todos"){
			$stm_sql = "SELECT * 
						FROM  `detalle_requisicion` 
						WHERE  `requisiciones_id_requisicion` LIKE  '$clave'";
						
			$msg_titulo = "DETALLE DE LA REQUISICI&Oacute;N <em><u>$clave</u></em> DEL DEPARTAMENTO: <em><u>$departamento</u></em>";
		}
		if ($clave=="todos"){
			$stm_sql = "SELECT  `detalle_requisicion` . * 
						FROM  `detalle_requisicion` 
						JOIN  `requisiciones` ON  `requisiciones_id_requisicion` =  `id_requisicion`
						JOIN bitacora_movimientos ON id_operacion = requisiciones_id_requisicion
						WHERE `fecha_req` 
						BETWEEN  '$fecha_i'
						AND  '$fecha_f'
						AND  `requisiciones_id_requisicion` LIKE  '%MAM%'
						ORDER BY  `area_solicitante`,`fecha_req`,`requisiciones_id_requisicion` ASC";
			
			$msg_titulo = "DETALLE DE LA REQUISICIONES DEL DEPARTAMENTO: <em><u>$departamento</u></em> DEL <em><u>".modFecha($fecha_i,1)."</u></em> AL <em><u>".modFecha($fecha_f,1)."</u></em>";
		}
		
		$rs = mysql_query($stm_sql);
		
		if($datos=mysql_fetch_array($rs)){
			echo "								
			<table cellpadding='5'>
				<caption class='titulo_etiqueta'>$msg_titulo</caption>			
				<tr>
					<td class='nombres_columnas' align='center'>ID REQUISICI&Oacute;N</td>
					<td class='nombres_columnas' align='center'>CANTIDAD</td>
					<td class='nombres_columnas' align='center'>UNIDAD DE MEDIDA</td>
					<td class='nombres_columnas' align='center'>DESCRIPCI&Oacute;N</td>
					<td class='nombres_columnas' align='center'>APLICACI&Oacute;N</td>
					<td class='nombres_columnas' align='center'>ESTADO</td>
					<td class='nombres_columnas' align='center'>TIEMPO DE ENTREGA</td>
				</tr>
				";
			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{
				$dias_ent = calcularDiasEntregaDetalleReq($datos["requisiciones_id_requisicion"],$bd,$datos["partida"]);
				if($datos['aplicacion'] != "")
					$aplicacion = $datos['aplicacion'];
				else
					$aplicacion = obtenerCentroCosto('control_costos','id_control_costos',$datos['id_control_costos']);
				echo "<tr>		
						<td class='$nom_clase'>$datos[requisiciones_id_requisicion]</td>
						<td class='$nom_clase'>$datos[cant_req]</td>
						<td class='$nom_clase'>$datos[unidad_medida]</td>
						<td class='$nom_clase'>$datos[descripcion]</td>
						<td class='$nom_clase'>$aplicacion</td>";
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
				echo "	<td class='$nom_clase'>$dias_ent</td>
					</tr>";															
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
			}while($datos=mysql_fetch_array($rs));
			echo "</table>";
		}
		?>
		</div><!--Cierre del Layer "reporte"-->
		<div id="btns-regpdf" align="center">
		<table width="100%">
			<tr>	
				<td align="center">
					<form action="frm_reporteRequisiciones.php" method="post" name="frm_reporteRequisiciones">
						<input name="hdn_consulta" type="hidden" value="<?php echo $stm_sql; ?>" />
						<input name="hdn_nomReporte" type="hidden" value="Reporte Detalle de Requisiciones" />
						<input name="hdn_tipoReporte" type="hidden" value="reporte_detallerequisiciones" />		
						<input name="hdn_msg" type="hidden" value="<?php echo $msg_titulo; ?>" />
						<input type="hidden" name="cmb_departamento" id="cmb_departamento" value="<?php echo $bd; ?>" />
						<input type="hidden" name="hdn_clave" id="hdn_clave" value="<?php echo $clave; ?>" />
						<input type="hidden" name="txt_fecha_ini" id="txt_fecha_ini" value="<?php echo modFecha($fecha_i,1); ?>" />
						<input type="hidden" name="txt_fecha_fin" id="txt_fecha_fin" value="<?php echo modFecha($fecha_f,1); ?>" />
						<input name="sbt_excel" type="submit" class="botones" value="Exportar a Excel" title="Exportar el Reporte a Excel" 
						onMouseOver="window.estatus='';return true" onclick="document.frm_reporteRequisiciones.action='guardar_reporte.php';document.frm_reporteRequisiciones.submit();"/>
						&nbsp;&nbsp;&nbsp;&nbsp;
						<input name="sbt_consultarConsumo" type="submit" class="botones" value="Regresar" title="Regresar a la Pantalla del Reporte de Requisiciones" 
						onMouseOver="window.estatus='';return true" onclick="document.frm_reporteRequisiciones.action='frm_reporteRequisiciones.php';"/>
					</form>
				</td>								
			</tr>
		</table>			
		</div>
		<?php
		//Cerrar la conexion con la BD
		mysql_close($conn);
	}
	
	function mostrarRequisicionesArea($bd,$fecha_i,$fecha_f,$msg_titulo){
		$flag = 0;
		$conn=conecta("$bd");
		
		$stm_sql = "SELECT * 
					FROM requisiciones
					JOIN bitacora_movimientos ON id_operacion = id_requisicion
					WHERE fecha_req
					BETWEEN  '$fecha_i'
					AND  '$fecha_f'
					AND  `id_requisicion` LIKE  '%MAM%'
					ORDER BY estado, fecha_req ASC ";
		
		//Ejecutar la consulta
		$rs = mysql_query($stm_sql);
		//Mostrar los resultados obtenidos
		if($datos = mysql_fetch_array($rs)){
			$flag = 1;
			echo "								
				<table cellpadding='5'>
					<caption class='titulo_etiqueta'>$msg_titulo</caption>
					<tr>
						<td class='nombres_columnas'>VER DETALLE</td>
						<td class='nombres_columnas'>ID REQUISICI&Oacute;N</td>
						<td class='nombres_columnas'>DEPARTAMENTO</td>
						<td class='nombres_columnas'>FECHA</td>
						<td class='nombres_columnas'>SOLICIT&Oacute;</td>
						<td class='nombres_columnas'>REALIZ&Oacute;</td>
						<td class='nombres_columnas'>ESTADO</td>
						<td class='nombres_columnas'>PRIORIDAD</td>
						<td class='nombres_columnas'>TIEMPO DE ENTREGA</td>
					</tr>";
					echo "<form name='frm_mostrarDetalleRE' method='post' action='frm_reporteRequisiciones.php'>
					<tr>
						<td class='nombres_filas'><input type='checkbox' name='RE' value='todos' 	
						onClick='javascript:document.frm_mostrarDetalleRE.submit();' title='Ver El Detalle de Todas las Requisiciones'/></td>
						<td class='nombres_filas' colspan='8' align='left'><img src='../../images/arrow.png' height='20' width='30'> Ver Detalle de Todo</td>	
						<input type='hidden' name='verDetalle' value='si' />
						<input name='fecha_inicial' id='fecha_inicial' type='hidden' value='".modFecha($_POST["txt_fecha_ini"],3)."'/>
						<input name='fecha_final' id='fecha_final' type='hidden' value='".modFecha($_POST["txt_fecha_fin"],3)."'/>
						<input name='select_bd' id='select_bd' type='hidden' value='$bd'/>
					</tr>";
			
			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{
				$dias_ent = calcularDiasEntregaReq($datos["id_requisicion"],$bd);
				echo "	
					<tr>	
						<td class='nombres_filas'><input type='checkbox' name='RE' value='$datos[id_requisicion]'
						onClick='javascript:document.frm_mostrarDetalleRE.submit();'/></td>
						<td class='$nom_clase'>$datos[id_requisicion]</td>
						<td class='$nom_clase'>$datos[area_solicitante]</td>
						<td class='$nom_clase'>".modFecha($datos['fecha_req'],1)."</td>
						<td class='$nom_clase'>$datos[solicitante_req]</td>
						<td class='$nom_clase'>$datos[elaborador_req]</td>
						<td class='$nom_clase'>$datos[estado]</td>
						<td class='$nom_clase'>$datos[prioridad]</td>
						<td class='$nom_clase'>$dias_ent</td>
					</tr>";
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
			}while($datos=mysql_fetch_array($rs));
			echo "</form></table>";
		}
		else{
			echo "<br><br><br><br><br><br><br><br><p align='center' class='msje_correcto'>No hay Requisiciones con esos parametros de busqueda</p>";
		}
		return array($flag,$stm_sql);
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