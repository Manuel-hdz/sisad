<?php
	/**
	  * Nombre del Módulo: Mantenimiento                                              
	  * Nombre Programador: Antonio de Jesús Jiménez Cuevas
	  * Fecha: 12/Junio/2012
	  * Descripción: Este archivo contiene funciones para generar el Reporte de Consumo de Aceites 
	  **/

	/*Funcion que recopila los datos para dibujar la grafica*/
	function reporteAceites(){
		//Crear las Fechas
		$fechaI=$_POST["cmb_anios"]."-".$_POST["cmb_meses"]."-01";
		//Obtener el ultimo dia del mes Seleccionado
		$diaFinal=diasMes($_POST["cmb_meses"],$_POST["cmb_anios"]);
		$fechaF=$_POST["cmb_anios"]."-".$_POST["cmb_meses"]."-".$diaFinal;
		//Obtener el nombre del Mes
		$mes=nombreMes($_POST["cmb_meses"]);
		if($_POST["cmb_tipo"]=="I" || $_POST["cmb_tipo"]=="E"){
			if($_POST["cmb_tipo"]=="E")
				//Ensamblar el titulo de la grafica
				$titulo="Reporte de Incrementos de Aceite de $mes $_POST[cmb_anios] \nÁrea: 'CONCRETO'";
			if($_POST["cmb_tipo"]=="I")
				//Ensamblar el titulo de la grafica
				$titulo="Reporte de Aceites Nuevos de $mes $_POST[cmb_anios] \nÁrea: 'CONCRETO'";
			reporteEntInc($fechaI,$fechaF,$_POST["cmb_tipo"],$titulo);
		}
		else{
			//Extraer y verificar el Equipo, si tiene valor diferente de vacio, mostrar todo el Reporte
			$equipo=$_POST["cmb_equipo"];
			//Conectarse a la BD
			$conn=conecta("bd_mantenimiento");
			//Variable que contedra la grafica
			$grafica="";
			//Verificar el departamento donde el usuario esta logueado
			if($_SESSION["depto"]=="MttoConcreto")
				$area="CONCRETO";
			else
				$area="MINA";
			if($equipo==""){
				//Ensamblar el titulo de la grafica
				$titulo="Reporte de Consumo de Aceite de $mes $_POST[cmb_anios] \nÁrea: '$area'";
				//Sentencia SQL
				$sql_stm="SELECT catalogo_aceites_id_aceite,nom_aceite,SUM(bitacora_aceite.cantidad) AS aceiteUsado,COUNT(catalogo_aceites_id_aceite) AS cantRegistros 
						FROM bitacora_aceite JOIN catalogo_aceites ON id_aceite=catalogo_aceites_id_aceite WHERE fecha BETWEEN '$fechaI' AND '$fechaF' AND tipo_mov='S' 
						GROUP BY catalogo_aceites_id_aceite ORDER BY aceiteUsado DESC,nom_aceite";
			}
			else{
				//Ensamblar el titulo de la grafica
				$titulo="Reporte de Consumo de Aceite del Equipo: $equipo en $mes $_POST[cmb_anios] \nÁrea: '$area'";
				//Sentencia SQL
				$sql_stm="SELECT catalogo_aceites_id_aceite,nom_aceite,SUM(bitacora_aceite.cantidad) AS aceiteUsado,COUNT(catalogo_aceites_id_aceite) AS cantRegistros 
						FROM bitacora_aceite JOIN catalogo_aceites ON id_aceite=catalogo_aceites_id_aceite WHERE fecha BETWEEN '$fechaI' AND '$fechaF' AND tipo_mov='S'
						AND equipos_id_equipo='$equipo' GROUP BY catalogo_aceites_id_aceite ORDER BY aceiteUsado DESC,nom_aceite";
			}
			//Ejecutar la sentencia
			$rs=mysql_query($sql_stm);
			if($datos=mysql_fetch_array($rs)){
				$cantRegistros=array();
				$aceites=array();
				$totalRegistros=0;
				$totalAceiteGastado=0;
				do{
					//Acumular los servicios para obtener la cantidad de ellos
					$totalRegistros+=$datos["cantRegistros"];
					//Recuperar los servicios para calcular el porcentaje posteriormente
					$cantRegistros[]=$datos["aceiteUsado"];
					//Recuperar las etiquetas
					$aceites[]=$datos["nom_aceite"]."\n".$datos["aceiteUsado"]." LTS";
					//Acumular el Total de Aceites Gastados
					$totalAceiteGastado+=$datos["aceiteUsado"];
				}while($datos=mysql_fetch_array($rs));
				//Recorrer el arreglo de servicios para calcular su valor porcentual
				$cont=0;
				//Obtener el total de Aceites utilizados
				$aceiteMax=array_sum($cantRegistros);
				do{
					//Reasignar a la posicion actual el valor porcentual calculado
					$cantRegistros[$cont]=($cantRegistros[$cont]*100)/$aceiteMax;//$totalRegistros;
					$cont++;
				}while($cont<(count($cantRegistros)));
				//Dibujar la grafica
				$grafica=graficaAceites($cantRegistros,$aceites,$titulo,$totalAceiteGastado);
				mysql_close($conn);
			}
			else{
				mysql_close($conn);
				?>
				<script type="text/javascript" language="javascript">
					location.href='frm_reporteAceites.php?noResults';
				</script>
				<?php
			}
			//Retornar la Grafica
			return $grafica;
		}
	}//Fin function reporteAceites()
	
	function reporteConsumoAceite(){
		?><div id="reporte" class="borde_seccion2" align="center"><?php
		$flag = 0;
		$msg_grafica = "";
		$msg_titulo = "Reporte de Consumo de Aceite del $_POST[txt_fecha_ini] al $_POST[txt_fecha_fin]";
		$conn=conecta("bd_mantenimiento");
		
		$stm_sql = "SELECT  `id_equipo` ,  `nom_equipo` ,  `marca_modelo` ,  `modelo` ,  `poliza` ,  `num_serie` ,  `num_serie_olla` ,  `placas` ,  `asignado` 
					FROM  `equipos` WHERE ";
		
		if($_POST["cmb_area"] == "TODO"){
			$stm_sql .= "area != ''";
		}
		else{
			$stm_sql .= "area = '$_POST[cmb_area]'";
			$msg_titulo .= " Área $_POST[cmb_area]";
		}
		if($_POST["cmb_familia"] == ""){
			$stm_sql .= " AND familia != ''";
		}
		else{
			$stm_sql .= " AND familia = '$_POST[cmb_familia]'";
			$msg_titulo .= " Familia $_POST[cmb_familia]";
		}
		if($_POST["cmb_equipo"] == ""){
			$stm_sql .= " AND id_equipo != ''";
		}
		else{
			$stm_sql .= " AND id_equipo = '$_POST[cmb_equipo]'";
			$msg_titulo .= " Equipo $_POST[cmb_equipo]";
		}
		$stm_sql .= " AND estado = 'ACTIVO' ORDER BY id_equipo";
		
		//Ejecutar la consulta
		$rs = mysql_query($stm_sql);
		//Mostrar los resultados obtenidos
		if($datos = mysql_fetch_array($rs)){
			$flag = 1;
			$total_consumo = 0;
			echo "								
				<table cellpadding='5'>
					<caption class='titulo_etiqueta'>$msg_titulo</caption>
					<tr>
						<td class='nombres_columnas'>VER DETALLE</td>
						<td class='nombres_columnas'>ID EQUIPO</td>
						<td class='nombres_columnas'>NOMBRE</td>
						<td class='nombres_columnas'>MARCA</td>
						<td class='nombres_columnas'>MODELO</td>
						<td class='nombres_columnas'>PLACAS</td>
						<td class='nombres_columnas'>ASIGNADO</td>
						<td class='nombres_columnas'>TIPO</td>
						<td class='nombres_columnas'>LTS. CONSUMIDOS</td>
						<td class='nombres_columnas'>RENDIMIENTO (KM/L)</td>
					</tr>";
					echo "<form name='frm_mostrarDetalleRE' method='post' action='frm_reporteConsumoAceite.php'>
					<tr>
						<td class='nombres_filas'><input type='checkbox' name='RE' value='todos' 	
						onClick='javascript:document.frm_mostrarDetalleRE.submit();' title='Ver El Detalle de Todos los Equipos'/></td>
						<td class='nombres_filas' colspan='9' align='left'><img src='../../images/arrow.png' height='20' width='30'> Ver Detalle de Todo</td>	
						<input type='hidden' name='verDetalle' value='si' />
						<input name='fecha_inicial' id='fecha_inicial' type='hidden' value='".modFecha($_POST["txt_fecha_ini"],3)."'/>
						<input name='fecha_final' id='fecha_final' type='hidden' value='".modFecha($_POST["txt_fecha_fin"],3)."'/>
						<input name='select_area' id='select_area' type='hidden' value='$_POST[cmb_area]'/>
						<input name='select_familia' id='select_familia' type='hidden' value='$_POST[cmb_familia]'/>
						<input name='select_equipo' id='select_equipo' type='hidden' value='$_POST[cmb_equipo]'/>
					</tr>";
			
			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{
				$consumo_aceite = consumoAceite($datos["id_equipo"],modFecha($_POST["txt_fecha_ini"],3),modFecha($_POST["txt_fecha_fin"],3),"3");
				$rendimiento = rendimientoAceite($datos["id_equipo"],modFecha($_POST["txt_fecha_ini"],3),modFecha($_POST["txt_fecha_fin"],3),"3");
				echo "	
					<tr>	
						<td class='nombres_filas'><input type='checkbox' name='RE' value='$datos[id_equipo]'
						onClick='javascript:document.frm_mostrarDetalleRE.submit();'/></td>
						<td class='$nom_clase'>$datos[id_equipo]</td>
						<td class='$nom_clase'>$datos[nom_equipo]</td>
						<td class='$nom_clase'>$datos[marca_modelo]</td>
						<td class='$nom_clase'>$datos[modelo]</td>
						<td class='$nom_clase'>$datos[placas]</td>
						<td class='$nom_clase'>$datos[asignado]</td>
						<td class='$nom_clase'>DIESEL</td>
						<td class='$nom_clase'>".number_format($consumo_aceite,2,".",",")."</td>
						<td class='$nom_clase'>".number_format($rendimiento,2,".",",")."</td>
					</tr>";
					$total_consumo += $consumo_aceite;
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
			}while($datos=mysql_fetch_array($rs));
			echo 	"<tr>
						<td colspan='7'></td>
						<td class='nombres_columnas'>TOTAL:</td>
						<td class='nombres_columnas'>$total_consumo</td>
					</tr>";
			echo "</form></table>";
		}
		?></div>
		<div id="btns-regpdf">
		<table width="100%">
			<tr>
				<td align="center">
					<input type="button" name="btn_regresar" value="Regresar" class="botones" title="Regresar a la P&aacute;gina de Reportes de Consumo de Aceite" onclick=
                    "location.href='frm_reporteConsumoAceite.php'" />
				</td>				
				<?php if($flag==1) { ?>			
				<td align="center">
					<form action="guardar_reporte.php" method="post">
						<input name="hdn_consulta" type="hidden" value="<?php echo $stm_sql; ?>" />
						<input name="hdn_nomReporte" type="hidden" value="Reporte de Consumo de Aceite" />
						<input name="hdn_origen" type="hidden" value="mantenimiento_aceite" />		
						<input name="hdn_msg" type="hidden" value="<?php echo $msg_titulo; ?>" />
						<input name="hdn_fecha_ini" type="hidden" value="<?php echo modFecha($_POST["txt_fecha_ini"],3); ?>" />
						<input name="hdn_fecha_fin" type="hidden" value="<?php echo modFecha($_POST["txt_fecha_fin"],3); ?>" />
						<input name="sbt_exportar" type="submit" class="botones" value="Exportar a Excel" title="Exportar a Excel los Datos de la Consulta Realizada" 
                        onMouseOver="window.estatus='';return true"  />
					</form>
				</td>
				<?php }
				/*if($flag==1){ ?>
				<td align="center">
					<?php 
						$datosGrapCompras = array("hdn_consulta"=>$stm_sql, "hdn_msg"=>$msg_grafica);
						$_SESSION['datosGrapCompras'] = $datosGrapCompras;
					?>						
					<input type="button" name="btn_verGrafica" class="botones" value="Ver Gr&aacute;fica" title="Ver Gr&aacute;fica de Compras" 
					onClick="javascript:window.open('verGraficas.php?graph=Compra',
					'_blank','top=0, left=0, width=1035, height=723, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');" />	
				</td>
				<?php }*/ ?>
			</tr>
		</table>			
		</div><?php
										
		//Cerrar la conexion con la BD
		mysql_close($conn);
	}
	
	//Esta función se encarga de mostrar el detalle del Pedido seleccionado
	function mostrarDetalleRE($clave){
		$msg_titulo = "";
		?><div id="reporte" class="borde_seccion2" align="center"><?php
		//Realizar la conexion a la BD de Compras
		$conn = conecta("bd_mantenimiento");
		if ($clave!="todos"){
			$stm_sql = "SELECT * FROM bitacora_aceite WHERE equipos_id_equipo = '$clave' AND tipo_mov = 'S' AND fecha BETWEEN '$_POST[fecha_inicial]' AND '$_POST[fecha_final]' AND  `catalogo_aceites_id_aceite` =  '3' ORDER BY equipos_id_equipo, fecha";
			$rs = mysql_query($stm_sql);
			if($datos=mysql_fetch_array($rs)){
				$msg_titulo = "DETALLE DEL CONSUMO DE ACEITE DEL EQUIPO <em><u>$clave</u></em> DEL <em><u>".modFecha($_POST['fecha_inicial'],1)."</u></em> AL <em><u>".modFecha($_POST['fecha_final'],1)."</u></em>";
				echo "								
				<table cellpadding='5'>
					<caption class='titulo_etiqueta'>DETALLE DEL CONSUMO DE ACEITE DEL EQUIPO <em><u>$clave</u></em> 
					DEL <em><u>".modFecha($_POST['fecha_inicial'],1)."</u></em> AL <em><u>".modFecha($_POST['fecha_final'],1)."</u></em></caption>			
					<tr>
						<td class='nombres_columnas' align='center'>NO.</td>
						<td class='nombres_columnas' align='center'>FECHA</td>
						<td class='nombres_columnas' align='center'>TURNO</td>
						<td class='nombres_columnas' align='center'>RESPONSABLE</td>
						<td class='nombres_columnas' align='center'>CANTIDAD</td>
						<td class='nombres_columnas' width='10' align='center'>OD&Oacute;METRO HOR&Oacute;METRO</td>
					</tr>
					";
				$nom_clase = "renglon_gris";
				$cont = 1;	
				do{
					echo "<tr>		
							<td class='nombres_filas'>$cont</td>
							<td class='$nom_clase'>$datos[fecha]</td>
							<td class='$nom_clase'>$datos[turno]</td>
							<td class='$nom_clase' align='left'>$datos[supervisor_mtto]</td>
							<td class='$nom_clase'>".number_format($datos['cantidad'],2,".",",")." LTS.</td>
							<td class='$nom_clase'>".number_format($datos['odometro_horometro'],2,".",",")."</td>
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
						<form action="frm_reporteConsumoAceite.php" method="post" name="frm_reporteConsumoAceite">
							<input name="hdn_consulta" type="hidden" value="<?php echo $stm_sql; ?>" />
							<input name="hdn_nomReporte" type="hidden" value="Reporte Detalle de Consumo de Aceite" />
							<input name="hdn_origen" type="hidden" value="mantenimiento_detalle_aceite" />		
							<input name="hdn_msg" type="hidden" value="<?php echo $msg_titulo; ?>" />
							<input type="hidden" name="txt_fecha_ini" id="txt_fecha_ini" value="<?php echo modFecha($_POST["fecha_inicial"],1); ?>" /> 
							<input type="hidden" name="txt_fecha_fin" id="txt_fecha_fin" value="<?php echo modFecha($_POST["fecha_final"],1); ?>" />
							<input type="hidden" name="cmb_area" id="cmb_area" value="<?php echo $_POST["select_area"]; ?>" />
							<input type="hidden" name="cmb_familia" id="cmb_familia" value="<?php echo $_POST["select_familia"]; ?>" />
							<input type="hidden" name="cmb_equipo" id="cmb_equipo" value="<?php echo $_POST["select_equipo"]; ?>" />
							<input name="btn_exportar" type="button" class="botones" value="Exportar a Excel" title="Exportar el Reporte a Excel" 
							onMouseOver="window.estatus='';return true" onclick="document.frm_reporteConsumoAceite.action='guardar_reporte.php';document.frm_reporteConsumoAceite.submit();"/>
							&nbsp;&nbsp;&nbsp;&nbsp;
							<input name="sbt_consultarConsumo" type="submit" class="botones" value="Regresar" title="Regresar a la Pantalla del Reporte de Compras" 
							onMouseOver="window.estatus='';return true" onclick="document.frm_reporteConsumoAceite.action='frm_reporteConsumoAceite.php';"/>
						</form>
					</td>								
				</tr>
			</table>			
			</div>
			<?php
		}
		if ($clave=="todos"){
			$fecha_ini=$_POST["fecha_inicial"];
			$fecha_fin=$_POST["fecha_final"];
			$stm_sql = "SELECT  `bitacora_aceite`. * 
						FROM  `bitacora_aceite` 
						JOIN  `equipos` ON  `equipos`.`id_equipo` =  `bitacora_aceite`.`equipos_id_equipo`
						WHERE  `catalogo_aceites_id_aceite` =3
						AND  `fecha` 
						BETWEEN  '$fecha_ini'
						AND  '$fecha_fin'
						AND `tipo_mov` = 'S'";
			if($_POST["select_area"] != "TODO"){
				$stm_sql .= " AND area='$_POST[select_area]'";
			}
			if($_POST["select_familia"] != ""){
				$stm_sql .= " AND familia='$_POST[select_familia]'";
			}
			if($_POST["select_equipo"] != ""){
				$stm_sql .= " AND id_equipo='$_POST[select_equipo]'";
			}
			$stm_sql .= " ORDER BY equipos_id_equipo, fecha";
			$msg_titulo="DETALLE DEL CONSUMO DE ACEITE DEL <em><u>".modFecha($_POST['fecha_inicial'],1)."</u></em> AL <em><u>".modFecha($_POST['fecha_final'],1)."</u></em>";
			$rs = mysql_query($stm_sql);
			if($datos=mysql_fetch_array($rs)){
				echo "								
				<table cellpadding='5'>
					<caption class='titulo_etiqueta'>$msg_titulo</caption>
					<thead>						
					<tr>
						<th class='nombres_columnas' align='center'>NO.</th>
						<th class='nombres_columnas' align='center'>EQUIPO</th>
						<td class='nombres_columnas' align='center'>FECHA</td>
						<td class='nombres_columnas' align='center'>TURNO</td>
						<td class='nombres_columnas' align='center'>RESPONSABLE</td>
						<td class='nombres_columnas' align='center'>CANTIDAD</td>
						<td class='nombres_columnas' width='10' align='center'>OD&Oacute;METRO HOR&Oacute;METRO</td>
					</tr>
					</thead>
				";
				$nom_clase = "renglon_gris";
				$cont = 1;
				echo "<tbody>";	
				do{
					
					echo "<tr>		
							<td class='nombres_filas'>$cont</td>
							<td class='$nom_clase'>$datos[equipos_id_equipo]</td>
							<td class='$nom_clase'>$datos[fecha]</td>
							<td class='$nom_clase'>$datos[turno]</td>
							<td class='$nom_clase' align='left'>$datos[supervisor_mtto]</td>
							<td class='$nom_clase'>".number_format($datos['cantidad'],2,".",",")." LTS.</td>
							<td class='$nom_clase'>".number_format($datos['odometro_horometro'],2,".",",")."</td>
						</tr>";									
						
					//Determinar el color del siguiente renglon a dibujar
					$cont++;
					if($cont%2==0)
						$nom_clase = "renglon_blanco";
					else
						$nom_clase = "renglon_gris";
				}while($datos=mysql_fetch_array($rs));
				echo "</tbody>";
				echo "</table>";
			}
			?>
			</div><!--Cierre del Layer "reporte"-->
			<div id="btns-regpdf" align="center">
			<table width="100%">
				<tr>	
					<td align="center">
						<form action="frm_reporteConsumoAceite.php" method="post" name="frm_reporteConsumoAceite">
							<input name="hdn_consulta" type="hidden" value="<?php echo $stm_sql; ?>" />
							<input name="hdn_nomReporte" type="hidden" value="Reporte Detalle de Consumo de Aceite" />
							<input name="hdn_origen" type="hidden" value="mantenimiento_detalle_aceite" />		
							<input name="hdn_msg" type="hidden" value="<?php echo $msg_titulo; ?>" />
							<input type="hidden" name="txt_fecha_ini" id="txt_fecha_ini" value="<?php echo modFecha($_POST["fecha_inicial"],1); ?>" /> 
							<input type="hidden" name="txt_fecha_fin" id="txt_fecha_fin" value="<?php echo modFecha($_POST["fecha_final"],1); ?>" />
							<input type="hidden" name="cmb_area" id="cmb_area" value="<?php echo $_POST["select_area"]; ?>" />
							<input type="hidden" name="cmb_familia" id="cmb_familia" value="<?php echo $_POST["select_familia"]; ?>" />
							<input type="hidden" name="cmb_equipo" id="cmb_equipo" value="<?php echo $_POST["select_equipo"]; ?>" />
							<input name="btn_exportar" type="button" class="botones" value="Exportar a Excel" title="Exportar el Reporte a Excel" 
							onMouseOver="window.estatus='';return true" onclick="document.frm_reporteConsumoAceite.action='guardar_reporte.php';document.frm_reporteConsumoAceite.submit();"/>
							&nbsp;&nbsp;&nbsp;&nbsp;
							<input name="sbt_consultarConsumo" type="submit" class="botones" value="Regresar" title="Regresar a la Pantalla del Reporte de Compras" 
							onMouseOver="window.estatus='';return true" onclick="document.frm_reporteConsumoAceite.action='frm_reporteConsumoAceite.php';"/>
						</form>
					</td>								
				</tr>
			</table>			
			</div>
			<?php
		}
		//Cerrar la conexion con la BD
		mysql_close($conn);
	}//Cierre de la funcion	mostrarDetalleRE($clave)
	
	function reporteConsumoGasolina(){
		?><div id="reporte" class="borde_seccion2" align="center"><?php
		$flag = 0;
		$msg_grafica = "";
		$msg_titulo = "Reporte de Consumo de Gasolina del $_POST[txt_fecha_ini] al $_POST[txt_fecha_fin]";
		$conn=conecta("bd_mantenimiento");
		
		$stm_sql = "SELECT  `id_equipo` ,  `nom_equipo` ,  `marca_modelo` ,  `modelo` ,  `poliza` ,  `num_serie` ,  `num_serie_olla` ,  `placas` ,  `asignado` 
					FROM  `equipos` WHERE ";
		
		if($_POST["cmb_area"] == "TODO"){
			$stm_sql .= "area != ''";
		}
		else{
			$stm_sql .= "area = '$_POST[cmb_area]'";
			$msg_titulo .= " Área $_POST[cmb_area]";
		}
		if($_POST["cmb_familia"] == ""){
			$stm_sql .= " AND familia != ''";
		}
		else{
			$stm_sql .= " AND familia = '$_POST[cmb_familia]'";
			$msg_titulo .= " Familia $_POST[cmb_familia]";
		}
		if($_POST["cmb_equipo"] == ""){
			$stm_sql .= " AND id_equipo != ''";
		}
		else{
			$stm_sql .= " AND id_equipo = '$_POST[cmb_equipo]'";
			$msg_titulo .= " Equipo $_POST[cmb_equipo]";
		}
		$stm_sql .= " AND estado = 'ACTIVO' ORDER BY id_equipo";
		
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
						<td class='nombres_columnas'>ID EQUIPO</td>
						<td class='nombres_columnas'>NOMBRE</td>
						<td class='nombres_columnas'>MARCA</td>
						<td class='nombres_columnas'>MODELO</td>
						<td class='nombres_columnas'>PLACAS</td>
						<td class='nombres_columnas'>ASIGNADO</td>
						<td class='nombres_columnas'>TIPO</td>
						<td class='nombres_columnas'>LTS. CONSUMIDOS</td>
						<td class='nombres_columnas'>COSTO</td>
						<td class='nombres_columnas'>RENDIMIENTO (KM/L)</td>
					</tr>";
					echo "<form name='frm_mostrarDetalleRE' method='post' action='frm_reporteConsumoGasolina.php'>
					<tr>
						<td class='nombres_filas'><input type='checkbox' name='RE' value='todos' 	
						onClick='javascript:document.frm_mostrarDetalleRE.submit();' title='Ver El Detalle de Todos los Equipos'/></td>
						<td class='nombres_filas' colspan='10' align='left'><img src='../../images/arrow.png' height='20' width='30'> Ver Detalle de Todo</td>	
						<input type='hidden' name='verDetalle' value='si' />
						<input name='fecha_inicial' id='fecha_inicial' type='hidden' value='".modFecha($_POST["txt_fecha_ini"],3)."'/>
						<input name='fecha_final' id='fecha_final' type='hidden' value='".modFecha($_POST["txt_fecha_fin"],3)."'/>
						<input name='select_area' id='select_area' type='hidden' value='$_POST[cmb_area]'/>
						<input name='select_familia' id='select_familia' type='hidden' value='$_POST[cmb_familia]'/>
						<input name='select_equipo' id='select_equipo' type='hidden' value='$_POST[cmb_equipo]'/>
					</tr>";
			
			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{
				$consumo_gasolina = consumoGasolina($datos["id_equipo"],modFecha($_POST["txt_fecha_ini"],3),modFecha($_POST["txt_fecha_fin"],3));
				$rendimiento = rendimientoGasolina($datos["id_equipo"],modFecha($_POST["txt_fecha_ini"],3),modFecha($_POST["txt_fecha_fin"],3));
				$costo = costoGasolina($datos["id_equipo"],modFecha($_POST["txt_fecha_ini"],3),modFecha($_POST["txt_fecha_fin"],3));
				echo "	
					<tr>	
						<td class='nombres_filas'><input type='checkbox' name='RE' value='$datos[id_equipo]'
						onClick='javascript:document.frm_mostrarDetalleRE.submit();'/></td>
						<td class='$nom_clase'>$datos[id_equipo]</td>
						<td class='$nom_clase'>$datos[nom_equipo]</td>
						<td class='$nom_clase'>$datos[marca_modelo]</td>
						<td class='$nom_clase'>$datos[modelo]</td>
						<td class='$nom_clase'>$datos[placas]</td>
						<td class='$nom_clase'>$datos[asignado]</td>
						<td class='$nom_clase'>GASOLINA</td>
						<td class='$nom_clase'>".number_format($consumo_gasolina,2,".",",")."</td>
						<td class='$nom_clase'>$ ".number_format($costo,2,".",",")."</td>
						<td class='$nom_clase'>".number_format($rendimiento,2,".",",")."</td>
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
		?></div>
		<div id="btns-regpdf">
		<table width="100%">
			<tr>
				<?php if($flag==1) { ?>			
				<td align="center">
					<form action="guardar_reporte.php" method="post">
						<input name="hdn_consulta" type="hidden" value="<?php echo $stm_sql; ?>" />
						<input name="hdn_nomReporte" type="hidden" value="Reporte de Consumo de Gasolina" />
						<input name="hdn_origen" type="hidden" value="mantenimiento_gasolina" />		
						<input name="hdn_msg" type="hidden" value="<?php echo $msg_titulo; ?>" />
						<input name="hdn_fecha_ini" type="hidden" value="<?php echo modFecha($_POST["txt_fecha_ini"],3); ?>" />
						<input name="hdn_fecha_fin" type="hidden" value="<?php echo modFecha($_POST["txt_fecha_fin"],3); ?>" />
						<input name="sbt_exportar" type="submit" class="botones" value="Exportar a Excel" title="Exportar a Excel los Datos de la Consulta Realizada" 
                        onMouseOver="window.estatus='';return true"  />
					</form>
				</td>
				<?php }
				/*if($flag==1){ ?>
				<td align="center">
					<?php 
						$datosGrapCompras = array("hdn_consulta"=>$stm_sql, "hdn_msg"=>$msg_grafica);
						$_SESSION['datosGrapCompras'] = $datosGrapCompras;
					?>						
					<input type="button" name="btn_verGrafica" class="botones" value="Ver Gr&aacute;fica" title="Ver Gr&aacute;fica de Compras" 
					onClick="javascript:window.open('verGraficas.php?graph=Compra',
					'_blank','top=0, left=0, width=1035, height=723, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');" />	
				</td>
				<?php }*/ ?>
				<td align="center">
					<input type="button" name="btn_regresar" value="Regresar" class="botones" title="Regresar a la P&aacute;gina de Reportes de Consumo de Aceite" onclick=
                    "location.href='frm_reporteConsumoGasolina.php'" />
				</td>			
			</tr>
		</table>			
		</div><?php
										
		//Cerrar la conexion con la BD
		mysql_close($conn);
	}
	
	//Esta función se encarga de mostrar el detalle del Pedido seleccionado
	function mostrarDetalleGasolinaRE($clave){
		$msg_titulo = "";
		?><div id="reporte" class="borde_seccion2" align="center"><?php
		//Realizar la conexion a la BD de Compras
		$conn = conecta("bd_mantenimiento");
		if ($clave!="todos"){
			$stm_sql = "SELECT * FROM bitacora_gasolina WHERE equipos_id_equipo = '$clave' AND fecha BETWEEN '$_POST[fecha_inicial]' AND '$_POST[fecha_final]'";
			$rs = mysql_query($stm_sql);
			if($datos=mysql_fetch_array($rs)){
				$msg_titulo = "DETALLE DEL CONSUMO DE GASOLINA DEL EQUIPO <em><u>$clave</u></em> DEL <em><u>".modFecha($_POST['fecha_inicial'],1)."</u></em> AL <em><u>".modFecha($_POST['fecha_final'],1)."</u></em>";
				echo "								
				<table cellpadding='5'>
					<caption class='titulo_etiqueta'>DETALLE DEL CONSUMO DE GASOLINA DEL EQUIPO <em><u>$clave</u></em> 
					DEL <em><u>".modFecha($_POST['fecha_inicial'],1)."</u></em> AL <em><u>".modFecha($_POST['fecha_final'],1)."</u></em></caption>			
					<tr>
						<td class='nombres_columnas' align='center'>NO.</td>
						<td class='nombres_columnas' align='center'>EQUIPO</td>
						<td class='nombres_columnas' align='center'>FECHA</td>
						<td class='nombres_columnas' align='center'>RESPONSABLE</td>
						<td class='nombres_columnas' align='center'>CANTIDAD</td>
						<td class='nombres_columnas' width='10' align='center'>OD&Oacute;METRO HOR&Oacute;METRO</td>
					</tr>
					";
				$nom_clase = "renglon_gris";
				$cont = 1;
				do{
					$responsable = obtenerNombreEmpleado($datos["responsable"]);
					echo "<tr>		
							<td class='nombres_filas'>$cont</td>
							<td class='$nom_clase'>$datos[equipos_id_equipo]</td>
							<td class='$nom_clase'>$datos[fecha]</td>
							<td class='$nom_clase' align='left'>$responsable</td>
							<td class='$nom_clase'>".number_format($datos['cantidad'],2,".",",")." LTS.</td>
							<td class='$nom_clase'>".number_format($datos['odometro'],2,".",",")."</td>
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
						<form action="frm_reporteConsumoGasolina.php" method="post" name="frm_reporteConsumoGasolina">
							<input name="hdn_consulta" type="hidden" value="<?php echo $stm_sql; ?>" />
							<input name="hdn_nomReporte" type="hidden" value="Reporte Detalle de Consumo de Gasolina" />
							<input name="hdn_origen" type="hidden" value="mantenimiento_detalle_gasolina" />		
							<input name="hdn_msg" type="hidden" value="<?php echo $msg_titulo; ?>" />
							<input type="hidden" name="txt_fecha_ini" id="txt_fecha_ini" value="<?php echo modFecha($_POST["fecha_inicial"],1); ?>" /> 
							<input type="hidden" name="txt_fecha_fin" id="txt_fecha_fin" value="<?php echo modFecha($_POST["fecha_final"],1); ?>" />
							<input type="hidden" name="cmb_area" id="cmb_area" value="<?php echo $_POST["select_area"]; ?>" />
							<input type="hidden" name="cmb_familia" id="cmb_familia" value="<?php echo $_POST["select_familia"]; ?>" />
							<input type="hidden" name="cmb_equipo" id="cmb_equipo" value="<?php echo $_POST["select_equipo"]; ?>" />
							<input name="btn_exportar" type="button" class="botones" value="Exportar a Excel" title="Exportar el Reporte a Excel" 
							onMouseOver="window.estatus='';return true" onclick="document.frm_reporteConsumoGasolina.action='guardar_reporte.php';document.frm_reporteConsumoGasolina.submit();"/>
							&nbsp;&nbsp;&nbsp;&nbsp;
							<input name="sbt_consultarConsumo" type="submit" class="botones" value="Regresar" title="Regresar a la Pantalla del Reporte de Compras" 
							onMouseOver="window.estatus='';return true" onclick="document.frm_reporteConsumoGasolina.action='frm_reporteConsumoGasolina.php';"/>
						</form>
					</td>								
				</tr>
			</table>			
			</div>
			<?php
		}
		if ($clave=="todos"){
			$fecha_ini=$_POST["fecha_inicial"];
			$fecha_fin=$_POST["fecha_final"];
			$stm_sql = "SELECT  `bitacora_gasolina`. * 
						FROM  `bitacora_gasolina` 
						JOIN  `equipos` ON  `equipos`.`id_equipo` =  `bitacora_gasolina`.`equipos_id_equipo`
						AND  `fecha` 
						BETWEEN  '$fecha_ini'
						AND  '$fecha_fin'";
			if($_POST["select_area"] != "TODO"){
				$stm_sql .= " AND area='$_POST[select_area]'";
			}
			if($_POST["select_familia"] != ""){
				$stm_sql .= " AND familia='$_POST[select_familia]'";
			}
			if($_POST["select_equipo"] != ""){
				$stm_sql .= " AND id_equipo='$_POST[select_equipo]'";
			}
			$stm_sql .= " ORDER BY equipos_id_equipo, fecha";
			$msg_titulo="DETALLE DEL CONSUMO DE GASOLINA DEL <em><u>".modFecha($_POST['fecha_inicial'],1)."</u></em> AL <em><u>".modFecha($_POST['fecha_final'],1)."</u></em>";
			$rs = mysql_query($stm_sql);
			if($datos=mysql_fetch_array($rs)){
				echo "								
				<table cellpadding='5'>
					<caption class='titulo_etiqueta'>$msg_titulo</caption>
					<thead>						
					<tr>
						<th class='nombres_columnas' align='center'>NO.</th>
						<th class='nombres_columnas' align='center'>EQUIPO</th>
						<td class='nombres_columnas' align='center'>FECHA</td>
						<td class='nombres_columnas' align='center'>RESPONSABLE</td>
						<td class='nombres_columnas' align='center'>CANTIDAD</td>
						<td class='nombres_columnas' width='10' align='center'>OD&Oacute;METRO HOR&Oacute;METRO</td>
					</tr>
					</thead>
				";
				$nom_clase = "renglon_gris";
				$cont = 1;
				echo "<tbody>";	
				do{
					$responsable = obtenerNombreEmpleado($datos["responsable"]);
					echo "<tr>		
							<td class='nombres_filas'>$cont</td>
							<td class='$nom_clase'>$datos[equipos_id_equipo]</td>
							<td class='$nom_clase'>$datos[fecha]</td>
							<td class='$nom_clase' align='left'>$responsable</td>
							<td class='$nom_clase'>".number_format($datos['cantidad'],2,".",",")." LTS.</td>
							<td class='$nom_clase'>".number_format($datos['odometro'],2,".",",")."</td>
						</tr>";									
						
					//Determinar el color del siguiente renglon a dibujar
					$cont++;
					if($cont%2==0)
						$nom_clase = "renglon_blanco";
					else
						$nom_clase = "renglon_gris";
				}while($datos=mysql_fetch_array($rs));
				echo "</tbody>";
				echo "</table>";
			}
			?>
			</div><!--Cierre del Layer "reporte"-->
			<div id="btns-regpdf" align="center">
			<table width="100%">
				<tr>	
					<td align="center">
						<form action="frm_reporteConsumoGasolina.php" method="post" name="frm_reporteConsumoGasolina">
							<input name="hdn_consulta" type="hidden" value="<?php echo $stm_sql; ?>" />
							<input name="hdn_nomReporte" type="hidden" value="Reporte Detalle de Consumo de Gasolina" />
							<input name="hdn_origen" type="hidden" value="mantenimiento_detalle_gasolina" />		
							<input name="hdn_msg" type="hidden" value="<?php echo $msg_titulo; ?>" />
							<input type="hidden" name="txt_fecha_ini" id="txt_fecha_ini" value="<?php echo modFecha($_POST["fecha_inicial"],1); ?>" /> 
							<input type="hidden" name="txt_fecha_fin" id="txt_fecha_fin" value="<?php echo modFecha($_POST["fecha_final"],1); ?>" />
							<input type="hidden" name="cmb_area" id="cmb_area" value="<?php echo $_POST["select_area"]; ?>" />
							<input type="hidden" name="cmb_familia" id="cmb_familia" value="<?php echo $_POST["select_familia"]; ?>" />
							<input type="hidden" name="cmb_equipo" id="cmb_equipo" value="<?php echo $_POST["select_equipo"]; ?>" />
							<input name="btn_exportar" type="button" class="botones" value="Exportar a Excel" title="Exportar el Reporte a Excel" 
							onMouseOver="window.estatus='';return true" onclick="document.frm_reporteConsumoGasolina.action='guardar_reporte.php';document.frm_reporteConsumoGasolina.submit();"/>
							&nbsp;&nbsp;&nbsp;&nbsp;
							<input name="sbt_consultarConsumo" type="submit" class="botones" value="Regresar" title="Regresar a la Pantalla del Reporte de Compras" 
							onMouseOver="window.estatus='';return true" onclick="document.frm_reporteConsumoGasolina.action='frm_reporteConsumoGasolina.php';"/>
						</form>
					</td>								
				</tr>
			</table>			
			</div>
			<?php
		}
		//Cerrar la conexion con la BD
		mysql_close($conn);
	}//Cierre de la funcion	mostrarDetalleRE($clave)
	
	//Funcion que muestra el Grafico de consumos de aceite
	function graficaAceites($cantServicios,$servicios,$titulo,$totalAceite){
		require_once ('../../includes/graficas/jpgraph/jpgraph.php');
		require_once ('../../includes/graficas/jpgraph/jpgraph_bar.php');	
		//Obtener la cantidad de Registros
		$cantRes=count($cantServicios);
		//Registros por Grafica
		$cantDatos=10;
		//Obtener la cantidad de graficas
		$ciclos=$cantRes/$cantDatos;
		//Redondear el valor de los ciclos
		$ciclos=intval($ciclos);
		//Obtener el residuo para saber si incrementar en 1 la cantidad de ciclos
		$residuo=$cantRes%$cantDatos;
		//Si residuo es mayor a 0, incrementar en uno los ciclos
		if($residuo>0)
			$ciclos+=1;
		//Inicializar variable de control para la cantidad de ciclos
		$cont=0;
		//Contador por cada grafica a dibujar
		$contPorGrafica=0;
		do{
			//Declarar el arreglo de costos Entrada por cada grafica
			$serviciosPorGrafica=array();
			//Declarar el arreglo de leyendas por cada grafica
			$leyendaPorGrafica=array();
			//Obtener los datos a graficar
			do{
				//Asignar a la posicion actual el valor de costos de Entrada
				$serviciosPorGrafica[]=$cantServicios[$contPorGrafica];
				//Asignar a la posicion actual la leyenda en la posicion que corresponde
				$leyendaPorGrafica[]=$servicios[$contPorGrafica];
				//Incrementar la variable de control por cada grafica
				$contPorGrafica++;
			}while(count($leyendaPorGrafica)<$cantDatos && $contPorGrafica<$cantRes);
			/**********************/
			$datay = $serviciosPorGrafica;
			// Create the graph and setup the basic parameters
			$graph = new Graph(945,430,'auto');
			$graph->img->SetMargin(40,30,60,125);
			$graph->SetScale('textint');
			$graph->SetFrame(false);
			$graph->yaxis->SetLabelFormat('%.d%%');
			// Setup X-axis labels
			$graph->xaxis->SetTickLabels($leyendaPorGrafica);
			$graph->xaxis->SetLabelAngle(45);
			$graph->xaxis->SetFont(FF_VERDANA,FS_NORMAL,8);
			// Setup graph title ands fonts
			$graph->title->Set($titulo);
			// Crear y agregar un Texto
			$txt=new Text("TOTAL ACEITE GASTADO\n      $totalAceite LTS");
			$txt->SetPos(620,30);
			$txt->SetColor('black');
			$txt->SetFont(FF_FONT2,FS_BOLD);
			$txt->SetBox('lightsteelblue','navy','gray@0.5');
			$graph->AddText($txt);
			//Pie de Tabla
			$graph->footer->center->Set('Aceite');
			$graph->footer->center->SetFont(FF_ARIAL,FS_BOLD,12);
			$graph->footer->center->SetColor('darkred');
			// Create a bar pot
			$bplot = new BarPlot($datay);
			$bplot->SetFillGradient("lightsteelblue","darkgreen",GRAD_VER);
			$bplot->SetWidth(0.5);
			//Obtener el Valor Minimo a Graficar
			$valorMinimo=min($serviciosPorGrafica);
			//Si el valor minimo es Mayor a 10, realizar la rutina que obtiene el porcentaje a ampliar la grafica
			if ($valorMinimo>10){
				//Obtener el Valor Maximo a graficar
				$valorMaximo=max($serviciosPorGrafica);
				//Restar a 100 el valor Máximo
				$valorGrace=100-$valorMaximo;
				//Obtener el valor de "Gracia" para ajustarlo ->"Gracia" se refiere al porcentaje dejado entre el valor maximo a graficar y el Alto de la Grafica
				$valorGrace=($valorGrace*100)/$valorMaximo;
				//Asignar el porcentaje de Gracia en valor Entero
				$graph->yaxis->scale->SetGrace(intval($valorGrace));
			}
			else{
				//Esta propiedad, da como maximo 100 en el Eje Y, se usa para datos menores a 10 solamente, ya que de lo contrario no genera el punto de partida como 0
				$bplot->SetYBase(100);
			}
			//Setup the values that are displayed on top of each bar
			$bplot->value->Show();
			$bplot->SetValuePos('center');
			$bplot->value->SetFormat('%.2f%%');
			// Must use TTF fonts if we want text at an arbitrary angle
			$bplot->value->SetFont(FF_ARIAL,FS_BOLD,12);
			$bplot->value->SetAngle(45);
			//$bplot->value->SetFormatCallback('separator1000');
			// Black color for positive values and darkred for negative values
			$bplot->value->SetColor('black','darkred');
			$graph->Add($bplot);
			//crear el nombre aleatorio de la grafica, generar el valor automaticamente en un rango de 0 a 1000
			$rnd=rand(0,1000);
			$grafica= "tmp/grafica".$rnd.".png";
			//Dibujar la grafica y guardarla en un archivo temporal	
			$graph->Stroke($grafica);
			/**********************/
			$cont++;
			//Agregar la primer grafica al DIV principal
			if($cont==1){
				?>
				<div align='center' id='tabla-graficas' class='borde_seccion2' width='100%' >
				<a href='<?php echo $grafica;?>' rel='lightbox[repMensual]' title='Gr&aacute;fica del Reporte de Consumo de Aceites'>
					<img width="100%" height="100%" border="0" src="<?php echo $grafica;?>" title="Gr&aacute;fica del Reporte de Consumo de Aceites"/>
				</a>
				</div>
				<?php
			}
			//Agregar las siguientes graficas al DIV secundario
			else{
				?>	
					<div id="imagenes" style="visibility:hidden;width:1px;height:1px;overflow:hidden">
					<a href='<?php echo $grafica;?>' rel='lightbox[repMensual]' title='Gr&aacute;fica del Reporte de Consumo de Aceites'>
						<img width="2%" height="2%" border="0" src="<?php echo $grafica;?>" title="Gr&aacute;fica del Reporte de Consumo de Aceites"/>
					</a>
					</div>
				<?php
			}
		}while($cont<$ciclos);
	}//Cierre graficaAceites($cantServicios,$servicios,$titulo,$totalAceite)
	
	function nombreMes($mes){
		switch($mes){
			case "01":
				$mes="ENERO";
			break;
			case "02":
				$mes="FEBRERO";
			break;
			case "03":
				$mes="MARZO";
			break;
			case "04":
				$mes="ABRIL";
			break;
			case "05":
				$mes="MAYO";
			break;
			case "06":
				$mes="JUNIO";
			break;
			case "07":
				$mes="JULIO";
			break;
			case "08":
				$mes="AGOSTO";
			break;
			case "09":
				$mes="SEPTIEMBRE";
			break;
			case "10":
				$mes="OCTUBRE";
			break;
			case "11":
				$mes="NOVIEMBRE";
			break;
			case "12":
				$mes="DICIEMBRE";
			break;
		}
		return $mes;
	}
	
	function reporteEntInc($fechaI,$fechaF,$tipo,$titulo){
		//Conectarse a la BD
		$conn=conecta("bd_mantenimiento");
		$sql_stm="SELECT catalogo_aceites_id_aceite,nom_aceite,fecha,bitacora_aceite.cantidad,tipo_mov FROM bitacora_aceite JOIN catalogo_aceites 
				ON id_aceite=catalogo_aceites_id_aceite WHERE fecha BETWEEN '$fechaI' AND '$fechaF' AND tipo_mov='$tipo' ORDER BY fecha,nom_aceite,tipo_mov DESC;";
		$rs=mysql_query($sql_stm);
		if($datos=mysql_fetch_array($rs)){
		?>
			<div align='center' id='tabla-graficas' class='borde_seccion2' width='100%' >
			<?php
				echo "<table class='tabla_frm' cellpadding='5'><br>";
				echo "<caption class='titulo_etiqueta'>$titulo</caption>";
				echo "	<tr>
							<td class='nombres_columnas' align='center' width='150'>ACEITE</td>
							<td class='nombres_columnas' align='center' width='150'>FECHA</td>
							<td class='nombres_columnas' align='center' width='150'>CANTIDAD</td>
						</tr>";
				$nom_clase = "renglon_gris";
				$cont = 1;	
				$estado="";
				do{	
					echo "
							<tr>					
							<td class='nombres_filas' align='center'>$datos[nom_aceite]</td>					
							<td class='$nom_clase' align='center'>".modFecha($datos["fecha"],1)."</td>
							<td class='$nom_clase' align='center'>".number_format($datos["cantidad"],2,".",",")." LTS</td>
							</tr>
						";		
					//Determinar el color del siguiente renglon a dibujar
					$cont++;
					if($cont%2==0)
						$nom_clase = "renglon_blanco";
					else
						$nom_clase = "renglon_gris";
				}while($datos=mysql_fetch_array($rs)); 
				echo "</table>";
			?>
			</div>
		<?php
		}else{
			?>
			<script type="text/javascript" language="javascript">
				location.href='frm_reporteAceites.php?noResults';
			</script>
			<?php
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
?>