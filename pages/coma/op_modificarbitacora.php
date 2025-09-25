<?php
	function mostrarBitacoras(){
		$fecha_ini = modFecha($_POST['txt_fechaIni'],3);
		$fecha_fin = modFecha($_POST['txt_fechaFin'],3);
		$estado = $_POST['cmb_estado'];
		$pagado = $_POST['cmb_pag'];
		$turno = $_POST['cmb_turno'];
		$conn=conecta("bd_comaro");
		
		$titulo_tabla = "Bitacoras del $_POST[txt_fechaIni] al $_POST[txt_fechaFin]";
		$stm_sql = "SELECT T1.id_bitacora , T1.id_empleados_empresa , CONCAT( T2.nombre,  ' ', T2.ape_pat,  ' ', T2.ape_mat ) AS nombre_empl, 
					T1.fecha_registo , T1.turno , T3.descripcion, T1.estado , T1.pagado, T3.costo_unit, T1.descuento
					FROM  bitacora_comensal AS T1
					JOIN bd_recursos.empleados AS T2
					USING (  id_empleados_empresa ) 
					JOIN menu AS T3
					USING (  id_menu ) 
					WHERE  fecha_registo 
					BETWEEN  '$fecha_ini'
					AND  '$fecha_fin'";
		if($estado != ""){
			$stm_sql .= " AND T1.estado = '$estado'";
			if($estado == "A")
				$titulo_tabla .= ", ESTADO: APARTADO";
			if($estado == "E")
				$titulo_tabla .= ", ESTADO: ENTREGADO";
		}
		if($pagado != ""){
			$stm_sql .= " AND T1.pagado = '$pagado'";
			$titulo_tabla .= ", PAGADO: $pagado";
		}
		if($turno != ""){
			$stm_sql .= " AND T1.turno = '$turno'";
			$titulo_tabla .= ", TURNO: $turno";
		}
		
		$rs=mysql_query($stm_sql);
		if ($datos=mysql_fetch_array($rs)){
			echo "<table class='tabla_frm' cellpadding='5' width='100%'>";
			echo "<caption class='titulo_etiqueta'>$titulo_tabla</caption>";
			echo "	<tr>
						<td class='nombres_columnas_comaro' align='center'>SELECCIONAR</td>
						<td class='nombres_columnas_comaro' align='center'>ID EMPLEADO</td>
						<td class='nombres_columnas_comaro' align='center'>NOMBRE EMPLEADO</td>
						<td class='nombres_columnas_comaro' align='center'>FECHA</td>
						<td class='nombres_columnas_comaro' align='center'>TURNO</td>
						<td class='nombres_columnas_comaro' align='center'>PLATILLO</td>
						<td class='nombres_columnas_comaro' align='center'>ESTADO</td>
						<td class='nombres_columnas_comaro' align='center'>PAGADO</td>
						<td class='nombres_columnas_comaro' align='center'>COSTO</td>
					</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{	
				$costo = $datos['costo_unit'] * ($datos['descuento'] / 100);
				$costo = $datos['costo_unit'] - $costo;
				echo "<tr>
						<td class='$nom_clase' align='center'>
							<input type='radio' name='rdb_idBitacora' id='rdb_idBitacora$cont' value='$datos[id_bitacora]'/>
						</td>
						<td class='$nom_clase' align='center'>$datos[id_empleados_empresa]</td>
						<td class='$nom_clase' align='center'>$datos[nombre_empl]</td>
						<td class='$nom_clase' align='center'>".modFecha($datos['fecha_registo'],1)."</td>
						<td class='$nom_clase' align='center'>$datos[turno]</td>
						<td class='$nom_clase' align='center'>$datos[descripcion]</td>";
				if($datos['estado'] == 'A')
					echo "<td class='$nom_clase' align='center'>APARTADO</td>";
				if($datos['estado'] == 'E')
					echo "<td class='$nom_clase' align='center'>ENTREGADO</td>";
					echo "<td class='$nom_clase' align='center'>$datos[pagado]</td>
						  <td class='$nom_clase' align='center'>$ ".number_format($costo,2,".",",")."</td>
					  </tr>";			
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
			}while($datos=mysql_fetch_array($rs)); 	
			echo "</table>";
		} else {
			echo "<br><br><br><br><br><br><br><br><p align='center' class='msje_correcto'>No hay Bitacoras Registradas con los Parametros Seleccionados</p>";
		}
		mysql_close($conn);
	}
	
	function consultarBitacora($id_bitacora){
		$conn = conecta("bd_comaro");
		$stm_sql = "SELECT T1.*, T2.descripcion, CONCAT(T3.nombre,' ',T3.ape_pat,' ',T3.ape_mat) AS nombre_empl
					FROM bitacora_comensal AS T1 
					JOIN menu AS T2 
					USING(id_menu)
					JOIN bd_recursos.empleados AS T3 
					USING(id_empleados_empresa)
					WHERE id_bitacora = '$id_bitacora'";
		$rs=mysql_query($stm_sql);
		if ($datos=mysql_fetch_array($rs)){
			?>
			<table width="100%" border="0" align="left" cellpadding="5" cellspacing="5" class="tabla_frm">		
			
			<tr>
				<td><div align="right">*C&oacute;digo Empleado</div></td>
				<td>
					<input type="text" name="txt_codBarTrabajador" id="txt_codBarTrabajador" class="caja_de_texto" size="10" 
					maxlength="20" readonly="readonly" onchange="extraerInfoEmpCB(this);" value="<?php echo $datos['id_empleados_empresa']; ?>"/>
				</td>
				<td><div align="right">*Empleado</div></td>
				<td>
					<input type="text" name="txt_empleado" id="txt_empleado" class="caja_de_texto" size="40" readonly="readonly" value="<?php echo $datos['nombre_empl']; ?>"/>
				</td>
			</tr>
			<tr>
				<td><div align="right">*Turno</div></td>
				<td>
					<input type="text" name="txt_turno" id="txt_turno" class="caja_de_texto" size="10" 
					maxlength="20" readonly="readonly" value="<?php echo $datos['turno']; ?>"/>
				</td>
				<td><div align="right">*Platillo</div></td>
				<td>
					<input type="text" name="txt_plat" id="txt_plat" class="caja_de_texto" size="40" readonly="readonly" value="<?php echo $datos['descripcion']; ?>"/>
				</td>
			</tr>
			<tr>
				<td><div align="right">*Estado</div></td>
				<td>
					<select name="cmb_estado" id="cmb_estado" class="combo_box">
						<option value="">Seleccionar Estado</option>
						<option value="A"<?php if($datos['estado'] == 'A') echo " selected='selected'";?>>Apartado</option>
						<option value="E"<?php if($datos['estado'] == 'E') echo " selected='selected'";?>>Entregado</option>
					</select>			
				</td>
				<td><div align="right">*Pagado</div></td>
				<td>
					<select name="cmb_pag" id="cmb_pag" class="combo_box">
						<option value="">Seleccionar</option>
						<option value="NO"<?php if($datos['pagado'] == 'NO') echo " selected='selected'";?>>NO</option>
						<option value="SI"<?php if($datos['pagado'] == 'SI') echo " selected='selected'";?>>SI</option>
					</select>			
				</td>
			</tr>
			<tr>
				<td colspan="6"><strong>* Los campos marcados con asterisco son <u>obligatorios</u>.</strong></td>
			</tr>
			<tr>
				<td colspan="6" align="center">
					<input type="submit" class="botones" name="sbt_guardar" id="sbt_guardar" value="Guardar" title="Modificar Bitacora" onmouseover="window.status='';return true;"/>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="reset" class="botones" name="btn_limpiar" id="btn_limpiar" value="Limpiar" title="Limpiar el Formulario"/>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="button" class="botones" name="btn_regresar" id="btn_regresar" value="Regresar" title="Volver a seleccionar Parametros de busqueda" onclick="location.href='frm_modificarBitacora.php'"/>
				</td>
			</tr>
			<input type="hidden" id="id_bit" name="id_bit" value="<?php echo $id_bitacora?>"/>
			</table>
			<?php
		}
	}
	
	function modificarBitacora(){
		$conn = conecta("bd_comaro");
		$id_bit = $_POST["id_bit"];
		$estado = $_POST["cmb_estado"];
		$pagado = $_POST["cmb_pag"];
		$stm_sql = "UPDATE bitacora_comensal SET estado = '$estado', pagado = '$pagado' WHERE id_bitacora = '$id_bit'";
		$rs = mysql_query($stm_sql);
		if($rs){
			//Cerramos la conexion con la Base de Datos
			mysql_close($conn);
			//Registrar el movimiento en la bitácora de Movimientos
			registrarOperacion("bd_comaro","$id_bit","ModificarBitacora",$_SESSION['usr_reg']);
			echo "<meta http-equiv='refresh' content='1;url=exito.php'>";
		}
		else{
			$error = mysql_error();
			echo "<meta http-equiv='refresh' content='5;url=error.php?err=$error'>";
		}
	}
?>