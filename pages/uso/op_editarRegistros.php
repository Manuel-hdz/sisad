<?php
	/**
	  * Nombre del Módulo: Desarrollo                                               
	  * Nombre Programador: Daisy Adriana Martínez Fernández                            
	  * Fecha: 18/Octubre/2011                                      			
	  * Descripción: Este archivo contiene funciones para editar los registros del Detalle de los materiales  del Modulo de Desarrollo
	  **/
	 	
	
	/*Esta funcion muestra el formulario para editar los datos del registro seleccionado*/  	  	  	 
	function editarRegistroRequisicion($pos){
		?>
		<form name="frm_editarRegistroRequisicion" action="frm_editarRegistros.php" method="post">
		<table width="100%" class="tabla_frm" cellpadding="5" cellspacing="5">
			<tr>
				<td width="20%" align="right">Clave</td>
				<td>
					<input name="txt_clave" type="text" class="caja_de_num" size="10" maxlength="10" disabled="disabled"
					value="<?php echo $_SESSION['datosRequisicion'][$pos]["clave"]; ?>" />
				</td>
			</tr>
			<tr>
				<td align="right">Material</td>
				<td>
					<input name="txt_nomMaterial" type="text" class="caja_de_texto" size="40" maxlength="60" disabled="disabled"
					value="<?php echo $_SESSION['datosRequisicion'][$pos]["material"]; ?>" />
				</td>
			</tr>
			<tr>
				<td align="right">Unidad de Medida</td>
				<td>
					<input name="txt_unidad" type="text" class="caja_de_num" size="10" maxlength="20" disabled="disabled"
					value="<?php echo $_SESSION['datosRequisicion'][$pos]["unidad"]; ?>" />
				</td>
			</tr>			
			<tr>
				<td align="right">Cantidad</td>
				<td>
					<input name="txt_cantReq" type="text" class="caja_de_num" onkeypress="return permite(event,'num');" size="10" maxlength="20" 
					value="<?php echo $_SESSION['datosRequisicion'][$pos]["cantReq"]; ?>" required="required" autocomplete="off"/>
				</td>
			</tr>
			<tr>
				<td><div align="right">Equipo</div></td>
				<td>
					<?php
					$conn1 = conecta("bd_mantenimiento");//Conectarse con la BD de Mantenimiento
					$rs_equipos = mysql_query("SELECT DISTINCT id_equipo FROM equipos WHERE estado='ACTIVO' ORDER BY id_equipo");
					if($equipos=mysql_fetch_array($rs_equipos)){?>
						<select name="txt_aplicacion" id="txt_aplicacion" class="combo_box" required="required"
						onchange="cargarCuentas_Equipo(this.value,'cmb_con_cos','cmb_cuenta','cmb_subcuenta');">
							<option value="">Equipos</option>
							<option value="N/A">N/A</option><?php
							do{
								if($_SESSION['datosRequisicion'][$pos]["aplicacionReq"] == $equipos["id_equipo"])
									echo "<option value='$equipos[id_equipo]' selected='selected'>$equipos[id_equipo]</option>";
								else
									echo "<option value='$equipos[id_equipo]'>$equipos[id_equipo]</option>";
							}while($equipos=mysql_fetch_array($rs_equipos));?>
						</select><?php
					}
					else
						echo "<label class='msje_correcto'><u><strong> NO</u></strong> Hay Equipos Registrados</label>";
					mysql_close($conn1);
					?>
				</td>
			</tr>
			<tr>
				<td><div align="right">Centro de Costos</div></td>
				<td>
					<?php 
					$conn = conecta("bd_recursos");		
					$stm_sql = "SELECT * FROM control_costos ORDER BY descripcion";
					$rs = mysql_query($stm_sql);
					
					if($datos = mysql_fetch_array($rs)){?>
						<select name="cmb_con_cos" id="cmb_con_cos" class="combo_box" onchange="cargarCuentas(this.value,'cmb_cuenta')" required="required">
							<?php
							echo "<option value=''>Centro de Costos</option>";
							do{
								if($_SESSION['datosRequisicion'][$pos]["cc"] == $datos["id_control_costos"])
									echo "<option value='$datos[id_control_costos]' selected=selected>$datos[descripcion]</option>";
								else
									echo "<option value='$datos[id_control_costos]'>$datos[descripcion]</option>";
							}while($datos = mysql_fetch_array($rs));?>
						</select>
					<?php
					}
					else{
						echo "<label class='msje_correcto'> No actualmente centro de costos</label>
							<input type='hidden' name='cmb_area' id='cmb_area'/>";
					}
					mysql_close($conn);
					?>
				</td>
				<script>
					document.getElementById("cmb_con_cos").onchange();
				</script>
			</tr>
			<tr>
				<td><div align="right">Cuenta</div></td>
				<td>
					<span id="datosCuenta">
						<!--
						<select name="cmb_cuenta" id="cmb_cuenta" class="combo_box" onchange="cargarSubCuentas(cmb_con_cos.value,cmb_cuenta.value,cmb_subcuenta.name); setTimeout('cargarCategroias(cmb_con_cos.value,cmb_cuenta.value,cmb_cat.name)',200)" required="required">
						-->
						<select name="cmb_cuenta" id="cmb_cuenta" class="combo_box" onchange="cargarSubCuentas(cmb_con_cos.value,cmb_cuenta.value,cmb_subcuenta.name);" required="required">
							<option value="">Cuentas</option>
						</select>
					</span>
				</td>
				<script>
					setTimeout("document.getElementById('cmb_cuenta').value='<?php echo $_SESSION['datosRequisicion'][$pos]['cuenta']; ?>'",200);
					setTimeout("document.getElementById('cmb_cuenta').onchange()",300);
				</script>
			</tr>
			<tr>
				<td><div align="right">Subcuenta</div></td>
				<td>
					<span id="datosSubCuenta">
						<select name="cmb_subcuenta" id="cmb_subcuenta" class="combo_box" onchange="hdn_subcuenta.value = this.value" required="required">
							<option value="">SubCuentas</option>
						</select>
					</span>
				</td>
				<script>
					setTimeout("document.getElementById('cmb_subcuenta').value='<?php echo $_SESSION['datosRequisicion'][$pos]['subcuenta']; ?>'",500);
				</script>
			</tr>
			<!-- <tr>
				<input type="hidden" name="txt_cat" id="txt_cat" size="5" maxlength="10" value="<?php echo $_SESSION['datosRequisicion'][$pos]['aplicacionReq']; ?>"/>
				<td>
					<div align="right">Categoria</div>
				</td>
				<td colspan="2">
					<span id="datosCategoria">
						<select name="cmb_cat" id="cmb_cat" class="combo_box" required="required">
							<option value="">Categorias</option>
						</select>
					</span>
				</td>
				<script>
					setTimeout("document.getElementById('cmb_cat').value='<?php echo $_SESSION['datosRequisicion'][$pos]['aplicacionReq']; ?>'",600);
				</script>
			</tr> -->
			<tr><td colspan="2">&nbsp;</td></tr>
			<tr>
				<td colspan="2">
					<input type="hidden" name="hdn_origen" value="requisicion" />
					<input type="hidden" name="hdn_posicion" value="<?php echo $pos;?>" />
					<input type="submit" name="sbt_modificar" value="Modificar" class="botones" title="Modificar Datos del Registro de Requisici&oacute;n" onmouseover="window.status='';return true;" />
					&nbsp;&nbsp;&nbsp;
					<input type="reset" name="rst_limpiar" value="Limpiar" class="botones" title="Restablecer Datos" />
					&nbsp;&nbsp;&nbsp;
					<input type="submit" name="sbt_borrar" value="Borrar Registro" class="botones" title="Borrar el Registro del Detalle de Requisici&oacute;n" onmouseover="window.status='';return true;" />
					&nbsp;&nbsp;&nbsp;
					<input type="button" name="btn_regresar" value="Regresar" class="botones" title="Regresar al Registro de la Requisici&oacute;n" onclick="location.href='frm_generarRequisicion.php'" />
				</td>
			</tr>
		</table>				
		</form>
		<?php
	}//Cierre de la funcion editarRegistroSalida($pos)
	
	
	/*Esta funcion guarda los cambios realizados en el registro seleccionado*/
	function guardarRegistroRequisicion(){
		if(isset($_POST['sbt_modificar'])){							
			$_SESSION['datosRequisicion'][$_POST['hdn_posicion']]['cantReq'] = $_POST['txt_cantReq'];
			$_SESSION['datosRequisicion'][$_POST['hdn_posicion']]['aplicacionReq'] = strtoupper($_POST['txt_aplicacion']);
			$_SESSION['datosRequisicion'][$_POST['hdn_posicion']]['cc'] = strtoupper($_POST['cmb_con_cos']);
			$_SESSION['datosRequisicion'][$_POST['hdn_posicion']]['cuenta'] = strtoupper($_POST['cmb_cuenta']);
			$_SESSION['datosRequisicion'][$_POST['hdn_posicion']]['subcuenta'] = strtoupper($_POST['cmb_subcuenta']);
			//$_SESSION['datosRequisicion'][$_POST['hdn_posicion']]['equipo'] = strtoupper($_POST['txt_aplicacion']);
			echo "<label class='msje_correcto'>Registro Modificado con &Eacute;xito</label>";
			echo "<meta http-equiv='refresh' content='2;url=frm_generarRequisicion.php'>";
		}
		
		if(isset($_POST['sbt_borrar'])){							
			unset($_SESSION['datosRequisicion'][$_POST['hdn_posicion']]);//Vaciar la posicion
			$_SESSION['datosRequisicion'] = array_values($_SESSION['datosRequisicion']);//Rectificar los indices
			//Si el arreglo de SESSIO se queda Vacio, quitarlo de la SESSION
			if(count($_SESSION['datosRequisicion'])==0) 
				unset($_SESSION['datosRequisicion']);
								
			echo "<label class='msje_correcto'>Registro Eliminado con &Eacute;xito</label>";
			echo "<meta http-equiv='refresh' content='2;url=frm_generarRequisicion.php'>";
		}
	}//Cierre de la funcion guardarRegistroSalida()
		
?>