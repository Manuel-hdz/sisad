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
		<form onsubmit="return valFormEditarRegistroRequisicion(this);" name="frm_editarRegistroRequisicion" action="frm_editarRegistros.php" method="post">
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
					value="<?php echo $_SESSION['datosRequisicion'][$pos]["cantReq"]; ?>" />
				</td>
			</tr>
			<tr>
				<td align="right">Aplicacion</td>
				<td>
					<input name="txt_aplicacion" type="text" class="caja_de_texto" onkeypress="return permite(event,'num_car');"
					size="30" maxlength="60" value="<?php echo $_SESSION['datosRequisicion'][$pos]["aplicacionReq"]; ?>" />
				</td>
			</tr>
			<tr><td colspan="2">&nbsp;</td></tr>
			<tr>
				<td colspan="2">
					<input type="hidden" name="hdn_origen" value="requisicion" />
					<input type="hidden" name="hdn_posicion" value="<?php echo $pos;?>" />
					<input type="submit" name="sbt_modificar" value="Modificar" class="botones" title="Modificar Datos del Registro de Requisici&oacute;n" 
                    onmouseover="window.status='';return true;" />
					&nbsp;&nbsp;&nbsp;
					<input type="reset" name="rst_limpiar" value="Limpiar" class="botones" title="Restablecer Datos" />
					&nbsp;&nbsp;&nbsp;
					<input type="submit" name="sbt_borrar" value="Borrar Registro" class="botones" title="Borrar el Registro del Detalle de Requisici&oacute;n"
                    onmouseover="window.status='';return true;" />
					&nbsp;&nbsp;&nbsp;
					<input type="button" name="btn_regresar" value="Regresar" class="botones" title="Regresar al Registro de la Requisici&oacute;n"
                    onclick="location.href='frm_generarRequisicion.php'" />
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