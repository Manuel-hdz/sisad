<?php
	function mostrarPlatillos(){
		$conn=conecta("bd_comaro");
		$stm_sql="SELECT * FROM menu";
		$rs=mysql_query($stm_sql);
		if ($datos=mysql_fetch_array($rs)){
			echo "<table class='tabla_frm' cellpadding='5' width='100%'>";
			echo "<caption class='titulo_etiqueta'>Men&uacute; Comaro</caption>";
			echo "	<tr>
						<td class='nombres_columnas_comaro' align='center'>SELECCIONAR</td>
						<td class='nombres_columnas_comaro' align='center'>CLAVE</td>
						<td class='nombres_columnas_comaro' align='center'>DESCRIPCION</td>
						<td class='nombres_columnas_comaro' align='center'>COSTO UNITARIO</td>
					</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{	
				echo "<tr>
						<td class='$nom_clase' align='center'>
							<input type='radio' name='rdb_idPlatillo' id='rdb_idPlatillo$cont' value='$datos[id_menu]'/>
						</td>
						<td class='$nom_clase' align='center'>$datos[id_menu]</td>
						<td class='$nom_clase' align='center'>$datos[descripcion]</td>
						<td class='$nom_clase' align='center'>$ ".number_format($datos['costo_unit'],2,".",",")."</td>
					  </tr>";			
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
			}while($datos=mysql_fetch_array($rs)); 	
			echo "</table>";
		} else {
			echo "<br><br><br><br><br><br><br><br><p align='center' class='msje_correcto'>No hay Platillos Registrados en el Men&uacute;</p>";
		}
		mysql_close($conn);
	}
	
	function consultarPlatillo($id_platillo){
		$conn = conecta("bd_comaro");
		$stm_sql = "SELECT * FROM menu WHERE id_menu = '$id_platillo'";
		$rs=mysql_query($stm_sql);
		if ($datos=mysql_fetch_array($rs)){
			?>
			<table width="100%" border="0" align="left" cellpadding="5" cellspacing="5" class="tabla_frm">		
			<tr>
				<td><div align="right">*Descripcion</div></td>
				<td><textarea name="txa_descripcion" maxlength="60" cols="30" rows="3" class="caja_de_texto" id="txa_descripcion" style="resize:none;"><?php echo $datos["descripcion"]; ?></textarea></td>
				<td><div align="right">*Costo Venta</div></td>
				<td>
					$ <input type='text' name='txt_costo' id='txt_costo' class='caja_de_num' size='10' maxlength="10" value="<?php echo number_format($datos['costo_unit'],2,".",","); ?>"
					onkeypress="return permite(event, 'num', 2);" onBlur="formatCurrency(value.replace(/,/g,''),'txt_costo');"/>
				</td>
				<input type="hidden" name="id_plat" id="id_plat" value="<?php echo $id_platillo; ?>"/>
			</tr>
			<tr>
				<td colspan="6"><strong>* Los campos marcados con asterisco son <u>obligatorios</u>.</strong></td>
			</tr>
			<tr>
				<td colspan="6" align="center">
					<input type="submit" class="botones" name="sbt_guardar" id="sbt_guardar" value="Modificar" title="Registrar el Platllo en el Men&uacute;" onmouseover="window.status='';return true;"/>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="reset" class="botones" name="btn_limpiar" id="btn_limpiar" value="Limpiar" title="Limpiar el Formulario" onclick="txa_descripcion.focus();"/>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="button" class="botones" name="btn_regresar" id="btn_regresar" value="Regresar" title="Volver al Men&uacute; de Platillos" onclick="location.href='frm_modificarPlatillos.php'"/>
				</td>
			</tr>
			</table>
			<?php
		}
	}
	
	function modificarPlatillo(){
		$conn = conecta("bd_comaro");
		$descripcion = strtoupper($_POST["txa_descripcion"]);
		$descripcion = trim($descripcion);
		$costo = str_replace(",","",$_POST["txt_costo"]);
		$id_menu = $_POST["id_plat"];
		$stm_sql = "UPDATE menu SET descripcion = '$descripcion', costo_unit = $costo WHERE id_menu = '$id_menu'";
		$rs = mysql_query($stm_sql);
		if($rs){
			//Cerramos la conexion con la Base de Datos
			mysql_close($conn);
			//Registrar el movimiento en la bitácora de Movimientos
			registrarOperacion("bd_comaro","$id_menu","ModificarPlatilloMenu",$_SESSION['usr_reg']);
			echo "<meta http-equiv='refresh' content='4;url=exito.php'>";
		}
		else{
			$error = mysql_error();			
			echo "<meta http-equiv='refresh' content='7;url=error.php?err=$error'>";
		}
	}
?>