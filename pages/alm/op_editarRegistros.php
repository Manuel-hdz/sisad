<?php
	/**
	  * Nombre del Módulo: Almacén                                               
	  * Nombre Programador: Miguel Angel Garay Castro                            
	  * Fecha: 27/Enero/2011                                      			
	  * Descripción: Este archivo contiene funciones para editar los registros del Detalle de los materiales en las diferentes paginas del Modulo de Almacén
	  **/
	 
	
	/*Esta funcion muestra el formulario para editar los datos del registro seleccionado*/  	  	  	 
	function editarRegistroEntrada($pos){
		?>
		<form onsubmit="return valFormEditarRegistroEntrada(this);" name="frm_editarRegistroEntrada" action="frm_editarRegistros.php" method="post">
		<table width="100%" class="tabla_frm" cellpadding="5" cellspacing="5">
			<tr>
				<td width="20%" align="right">Clave</td>
				<td>
					<input name="txt_clave" type="text" class="caja_de_num" size="10" maxlength="10" disabled="disabled"
					value="<?php echo $_SESSION['datosEntrada'][$pos]["clave"]; ?>" />
				</td>
			</tr>
			<tr>
				<td align="right">Material</td>
				<td>
					<input name="txt_nomMaterial" type="text" class="caja_de_texto" size="40" maxlength="60" disabled="disabled"
					value="<?php echo $_SESSION['datosEntrada'][$pos]["nombre"]; ?>" />
				</td>
			</tr>
			<tr>
				<td align="right">Existencia</td>
				<td>
					<input name="txt_existencia" type="text" class="caja_de_num" size="10" maxlength="20" disabled="disabled"
					value="<?php echo $_SESSION['datosEntrada'][$pos]["existencia"]; ?>" />
				</td>
			</tr>
			<tr>
				<td align="right">Unidad de Medida</td>
				<td>
					<input name="txt_unidadMedida" type="text" class="caja_de_num" size="10" maxlength="20" disabled="disabled"
					value="<?php echo $_SESSION['datosEntrada'][$pos]["unidad"]; ?>" />
				</td>
			</tr>
			<tr>
				<td align="right">Cantidad</td>
				<td>
					<input name="txt_cantEntrada" type="text" class="caja_de_num" onkeypress="return permite(event,'num');" size="15" maxlength="20" 
					value="<?php echo $_SESSION['datosEntrada'][$pos]["cantEntrada"]; ?>" />
				</td>
			</tr>
			<tr>
				<td align="right">Costo</td>
				<td>
					$<input name="txt_costoUnidad" type="text" class="caja_de_num" id="txt_costoUnidad" onchange="formatCurrency(value,'txt_costoUnidad');" 
					onkeypress="return permite(event,'num');" size="15" maxlength="20" value="<?php echo $_SESSION['datosEntrada'][$pos]["costoUnidad"]; ?>" />
				</td>
			</tr>
			<tr>
				<td align="right">Moneda</td>
				<td>
					<select name="cmb_tipoMoneda" id="cmb_tipoMoneda" size="1" class="combo_box">
						<option value="">Moneda</option>
						<option <?php if($_SESSION['datosEntrada'][$pos]["tipoMoneda"] == "PESOS") echo "selected=selected"; ?> value="PESOS">PESOS</option>
						<option <?php if($_SESSION['datosEntrada'][$pos]["tipoMoneda"] == "DOLARES") echo "selected=selected"; ?>value="DOLARES">DOLARES</option>
						<option <?php if($_SESSION['datosEntrada'][$pos]["tipoMoneda"] == "EUROS") echo "selected=selected"; ?>value="EUROS">EUROS</option>
					</select>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<input type="hidden" name="hdn_origen" value="entrada" />
					<input type="hidden" name="hdn_posicion" value="<?php echo $pos;?>" />
					<input type="submit" name="sbt_modificar" value="Modificar" class="botones" title="Modificar Datos del Registro de Entrada" onmouseover="window.status='';return true;" />
					&nbsp;&nbsp;&nbsp;
					<input type="reset" name="rst_limpiar" value="Limpiar" class="botones" title="Restablecer Datos"  />
					&nbsp;&nbsp;&nbsp;
					<input type="submit" name="sbt_borrar" value="Borrar Registro" class="botones" title="Borrar el Registro del Detalle de Entradas" onmouseover="window.status='';return true;" />
					&nbsp;&nbsp;&nbsp;
					<input type="button" name="btn_regresar" value="Regresar" class="botones" title="Regresar al Registro de Entrada de Material" onclick="location.href='frm_entradaMaterial2A.php'" />
				</td>
			</tr>
		</table>				
		</form>
		<?php
	}//Cierre de la funcion editarRegistroEntrada($pos)
	
	
	/*Esta funcion guarda los cambios realizados en el registro seleccionado*/
	function guardarRegistroEntrada(){
		if(isset($_POST['sbt_modificar'])){
			//Quitar las comas en caso de que existan en as cantidades numericas
			$_POST['txt_costoUnidad'] = str_replace(",","",$_POST['txt_costoUnidad']);
			
			$_SESSION['datosEntrada'][$_POST['hdn_posicion']]['cantEntrada'] = $_POST['txt_cantEntrada'];
			$_SESSION['datosEntrada'][$_POST['hdn_posicion']]['costoUnidad'] = $_POST['txt_costoUnidad'];
			$_SESSION['datosEntrada'][$_POST['hdn_posicion']]['costoTotal'] = (intval($_POST['txt_costoUnidad']) * intval($_POST['txt_cantEntrada']));
			$_SESSION['datosEntrada'][$_POST['hdn_posicion']]['tipoMoneda'] = $_POST['cmb_tipoMoneda'];
			
			echo "<label class='msje_correcto'>Registro Modificado con &Eacute;xito</label>";
			echo "<meta http-equiv='refresh' content='2;url=frm_entradaMaterial2A.php'>";			
		}
		if(isset($_POST['sbt_borrar'])){
			unset($_SESSION['datosEntrada'][$_POST['hdn_posicion']]);//Vaciar la posicion
			$_SESSION['datosEntrada'] = array_values($_SESSION['datosEntrada']);//Rectificar los indices
			//Si el arreglo de SESSIO se queda Vacio, quitarlo de la SESSION
			if(count($_SESSION['datosEntrada'])==0) 
				unset($_SESSION['datosEntrada']);
			
			echo "<label class='msje_correcto'>Registro Eliminado con &Eacute;xito</label>";
			echo "<meta http-equiv='refresh' content='2;url=frm_entradaMaterial2A.php'>";
		}
	}//Cierre de la funcion guardarRegistroEntrada()
	
	
	/*Esta funcion muestra el formulario para editar los datos del registro seleccionado*/  	  	  	 
	function editarRegistroSalida($pos){
		?>
		<form onsubmit="return valFormEditarRegistroSalida(this);" name="frm_editarRegistroSalida" id="frm_editarRegistroSalida" action="frm_editarRegistros.php" method="post">
		<table width="100%" class="tabla_frm" cellpadding="5" cellspacing="5">
			<tr>
				<td width="20%" align="right">Clave</td>
				<td>
					<input name="txt_clave" type="text" class="caja_de_num" size="10" maxlength="10" disabled="disabled"
					value="<?php echo $_SESSION['datosSalida'][$pos]["clave"]; ?>" />
				</td>
			</tr>
			<tr>
				<td align="right">Material</td>
				<td>
					<input name="txt_nomMaterial" type="text" class="caja_de_texto" size="40" maxlength="60" disabled="disabled"
					value="<?php echo $_SESSION['datosSalida'][$pos]["nombre"]; ?>" />
				</td>
			</tr>
			<tr>
				<td align="right">Existencia</td>
				<td>
					<input name="txt_existencia" type="text" class="caja_de_num" size="10" maxlength="20" disabled="disabled"
					value="<?php echo $_SESSION['datosSalida'][$pos]["cantRestante"]; ?>" />
				</td>
			</tr>			
			<tr>
				<td align="right">Cantidad</td>
				<td>
					<input name="txt_cantSalida" type="text" class="caja_de_num" onkeypress="return permite(event,'num');" size="15" maxlength="20" 
					value="<?php echo $_SESSION['datosSalida'][$pos]["cantSalida"]; ?>" />
				</td>
			</tr>
			<tr>
				<td align="right">Costo</td>
				<td>
					$<input name="txt_costoUnidad" type="text" class="caja_de_num" size="15" maxlength="20" disabled="disabled"
					value="<?php echo $_SESSION['datosSalida'][$pos]["costoUnidad"]; ?>" />
				</td>
			</tr>
			<tr>
				<td align="right">Id. Equipo</td>
				<td>
					<?php $conn_mtto = conecta("bd_mantenimiento");//Conectarse a la BD de Mantenimiento
					$idEquipo = $_SESSION['datosSalida'][$pos]["idEquipo"];?>
					<select name="cmb_idEquipo" id="cmb_idEquipo" size="1" class="combo_box" title="Seleccionar Id del Equipo al que va Destinado el Material">
						<option value="" title="Seleccionar Id del Equipo">Id Equipo</option>
						<?php $result_mtto = mysql_query("SELECT id_equipo, nom_equipo FROM equipos WHERE  `estado` =  'ACTIVO' ORDER BY id_equipo");		
							$band = 0;
							if($idEquipo=="N/A")
								echo "<option value='N/A' title='Material que no Aplica para un Equipo' selected='selected'>NO APLICA</option>";
							else
								echo "<option value='N/A' title='Material que no Aplica para un Equipo'>NO APLICA</option>";
							while ($datos_equipos=mysql_fetch_array($result_mtto)){							
								if($idEquipo==$datos_equipos['id_equipo'])
									echo "<option value='$datos_equipos[id_equipo]' title='$datos_equipos[nom_equipo]' selected='selected'>$datos_equipos[id_equipo]</option>";
								else
									echo "<option value='$datos_equipos[id_equipo]' title='$datos_equipos[nom_equipo]'>$datos_equipos[id_equipo]</option>";							
							}
						?>
					</select><?php		
					//Cerrar la conexion con la BD		
					mysql_close($conn_mtto); ?>
				</td>
			</tr>
			<tr><td colspan="2">&nbsp;</td></tr>
			<?php if(isset($_GET["id_empl"])){ ?>
					<input type="hidden" id="id_empl" name="id_empl" value="<?php echo $_GET['id_empl']; ?>" />
					<input type="hidden" id="id_kiosco" name="id_kiosco" value="<?php echo $_GET['id_kiosco']; ?>" />
			<?php } ?>
			<tr>
				<td colspan="2">
					<input type="hidden" name="hdn_origen" value="salida" />
					<input type="hidden" name="hdn_posicion" value="<?php echo $pos;?>" />
					<input type="submit" name="sbt_modificar" value="Modificar" class="botones" title="Modificar Datos del Registro de Salida" onmouseover="window.status='';return true;" />
					&nbsp;&nbsp;&nbsp;
					<input type="reset" name="rst_limpiar" value="Limpiar" class="botones" title="Restablecer Datos" />
					&nbsp;&nbsp;&nbsp;
					<input type="submit" name="sbt_borrar" value="Borrar Registro" class="botones" title="Borrar el Registro del Detalle de Salida" onmouseover="window.status='';return true;" />
					&nbsp;&nbsp;&nbsp;
					<?php 
					//Si no esta definido en el Get el parametro "cb", quiere decir que se llego aqui desde frm_salidaMaterial.php
					if(!isset($_GET["cb"])){?>
						<input type="button" name="btn_regresar" value="Regresar" class="botones" title="Regresar al Registro de Salida de Material" 
						onclick="document.getElementById('frm_editarRegistroSalida').action='frm_salidaMaterial.php'; submit();" />
					<?php }
					//En caso de estar definido en el Get el parametro "cb", se llego aqui desde frm_salidaMaterialBC.php
					else{?>
						<input type="hidden" name="hdn_salidaBC"/>
						<input type="button" name="btn_regresar" value="Regresar" class="botones" title="Regresar al Registro de Salida de Material" onclick="location.href='frm_salidaMaterialBC.php'" />
					<?php }?>
				</td>
			</tr>
		</table>				
		</form>
		<?php
	}//Cierre de la funcion editarRegistroSalida($pos)
	
	
	/*Esta funcion guarda los cambios realizados en el registro seleccionado*/
	function guardarRegistroSalida(){
		if(isset($_POST['sbt_modificar'])){			
			//Obtener la cantidad de salida del material que se esta editando
			$totalSalida = obtenerExistenciaMaterial($_SESSION['datosSalida'], "clave", $_SESSION['datosSalida'][$_POST['hdn_posicion']]["clave"]);
			//Descontar al total de salida, la cantidad del registro que se esta editando
			$totalSalida -= $_SESSION['datosSalida'][$_POST['hdn_posicion']]['cantSalida'];
			//Sumar la cantidad actual que se quiere registrar en la salida
			$totalSalida += $_POST['txt_cantSalida'];
			
			if($totalSalida <= $_SESSION['datosSalida'][$_POST['hdn_posicion']]["existencia"]){
				$cost_unit = str_replace(",","",$_SESSION['datosSalida'][$_POST['hdn_posicion']]['costoUnidad']);
				$_SESSION['datosSalida'][$_POST['hdn_posicion']]['cantSalida'] = $_POST['txt_cantSalida'];
				$_SESSION['datosSalida'][$_POST['hdn_posicion']]['costoTotal'] = number_format(($_POST['txt_cantSalida'] * $cost_unit),2,".",",");	
				$_SESSION['datosSalida'][$_POST['hdn_posicion']]['idEquipo'] = $_POST['cmb_idEquipo'];		
				echo "<label class='msje_correcto'>Registro Modificado con &Eacute;xito</label>";
				//Si no esta definido en el POST la Variable "hdn_salidaBC", quiere decir que se llego aqui desde frm_salidaMaterial.php
				if(!isset($_POST["hdn_salidaBC"])){
					if(isset($_POST["id_empl"])){
						?>
						<form id="frm_temp" name="frm_temp" action="frm_salidaMaterial.php" method="post">
							<input type="hidden" name="id_empl" id="id_empl" value="<?php echo $_POST["id_empl"]; ?>"/>
							<input type="hidden" name="id_kiosco" id="id_kiosco" value="<?php echo $_POST["id_kiosco"]; ?>"/>
						</form>
						<script>
							setTimeout("document.getElementById('frm_temp').submit();",0.2*1000);
						</script>
						<?php
					} else {
						echo "<meta http-equiv='refresh' content='2;url=frm_salidaMaterial.php'>";
					}
				}
				//Si no esta definido en el POST la Variable "hdn_salidaBC", quiere decir que se llego aqui desde frm_salidaMaterialBC.php
				else
					echo "<meta http-equiv='refresh' content='2;url=frm_salidaMaterialBC.php'>";
			}
			else{
				echo "<label class='msje_correcto'>El material ".$_SESSION['datosSalida'][$_POST['hdn_posicion']]["nombre"]." no alcanza a cubrir la cantidad de salida solicitada</label>";
				echo "<meta http-equiv='refresh' content='2;url=frm_editarRegistros.php?origen=salida&pos=$_POST[hdn_posicion]'>";
			}
		}
		if(isset($_POST['sbt_borrar'])){							
			unset($_SESSION['datosSalida'][$_POST['hdn_posicion']]);//Vaciar la posicion
			$_SESSION['datosSalida'] = array_values($_SESSION['datosSalida']);//Rectificar los indices
			//Si el arreglo de SESSIO se queda Vacio, quitarlo de la SESSION
			if(count($_SESSION['datosSalida'])==0) 
				unset($_SESSION['datosSalida']);
			
			echo "<label class='msje_correcto'>Registro Eliminado con &Eacute;xito</label>";
			//Si no esta definido en el POST la Variable "hdn_salidaBC", quiere decir que se llego aqui desde frm_salidaMaterial.php
			if(!isset($_POST["hdn_salidaBC"])){
				if(isset($_POST["id_empl"])){
					?>
					<form id="frm_temp" name="frm_temp" action="frm_salidaMaterial.php" method="post">
						<input type="hidden" name="id_empl" id="id_empl" value="<?php echo $_POST["id_empl"]; ?>"/>
						<input type="hidden" name="id_kiosco" id="id_kiosco" value="<?php echo $_POST["id_kiosco"]; ?>"/>
					</form>
					<script>
						setTimeout("document.getElementById('frm_temp').submit();",2*1000);
					</script>
					<?php
				} else {
					echo "<meta http-equiv='refresh' content='2;url=frm_salidaMaterial.php'>";
				}
			}
			//Si no esta definido en el POST la Variable "hdn_salidaBC", quiere decir que se llego aqui desde frm_salidaMaterialBC.php
			else
				echo "<meta http-equiv='refresh' content='2;url=frm_salidaMaterialBC.php'>";
		}
	}//Cierre de la funcion guardarRegistroSalida()
	
	
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
			<!--
			<tr>
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
			</tr>
			-->
			<tr>
				<td colspan="2">&nbsp;</td>
			</tr>
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
	
	
	/*Esta funcion muestra el formulario para editar los datos del registro seleccionado*/  	  	  	 
	function editarRegistroOrdenCompra($pos){
		?>
		<form onsubmit="return valFormEditarRegistroOC(this);" name="frm_editarRegistroOC" action="frm_editarRegistros.php" method="post">
		<table width="100%" class="tabla_frm" cellpadding="5" cellspacing="5">
			<tr>
				<td width="20%" align="right">Clave</td>
				<td>
					<input name="txt_clave" type="text" class="caja_de_num" size="10" maxlength="10" disabled="disabled"
					value="<?php echo $_SESSION['datosOC'][$pos]["clave"]; ?>" />
				</td>
			</tr>
			<tr>
				<td align="right">Descripcion</td>
				<td>
					<input name="txt_nomMaterial" type="text" class="caja_de_texto" size="40" maxlength="60" disabled="disabled"
					value="<?php echo $_SESSION['datosOC'][$pos]["descripcion"]; ?>" />
				</td>
			</tr>			
			<tr>
				<td align="right">Cantidad</td>
				<td>
					<input name="txt_cantOC" type="text" class="caja_de_num" onkeypress="return permite(event,'num');" size="10" maxlength="20" 
					value="<?php echo $_SESSION['datosOC'][$pos]["cantidad"]; ?>" />
				</td>
			</tr>	
			<tr><td colspan="2">&nbsp;</td></tr>
			<tr>
				<td colspan="2">
					<input type="hidden" name="hdn_origen" value="orden compra" />
					<input type="hidden" name="hdn_posicion" value="<?php echo $pos;?>" />
					<input type="submit" name="sbt_modificar" value="Modificar" class="botones" title="Modificar Datos del Registro de la Orden de Compra" onmouseover="window.status='';return true;" />
					&nbsp;&nbsp;&nbsp;
					<input type="reset" name="rst_limpiar" value="Limpiar" class="botones" title="Restablecer Datos" />
					&nbsp;&nbsp;&nbsp;
					<input type="submit" name="sbt_borrar" value="Borrar Registro" class="botones" title="Borrar el Registro del Detalle de la Orden de Compra" onmouseover="window.status='';return true;" />
					&nbsp;&nbsp;&nbsp;
					<input type="button" name="btn_regresar" value="Regresar" class="botones" title="Regresar al Registro de la Orden de Compra" onclick="location.href='frm_generarOC.php'" />
				</td>
			</tr>
		</table>				
		</form>
		<?php
	}//Cierre de la funcion editarRegistroSalida($pos)
	
	
	/*Esta funcion guarda los cambios realizados en el registro seleccionado*/
	function guardarRegistroOrdenCompra(){
		if(isset($_POST['sbt_modificar'])){							
			$_SESSION['datosOC'][$_POST['hdn_posicion']]['cantidad'] = $_POST['txt_cantOC'];

			echo "<label class='msje_correcto'>Registro Modificado con &Eacute;xito</label>";
			echo "<meta http-equiv='refresh' content='2;url=frm_generarOC.php'>";
		}
		
		if(isset($_POST['sbt_borrar'])){							
			unset($_SESSION['datosOC'][$_POST['hdn_posicion']]);//Vaciar la posicion
			$_SESSION['datosOC'] = array_values($_SESSION['datosOC']);//Rectificar los indices
			//Si el arreglo de SESSIO se queda Vacio, quitarlo de la SESSION
			if(count($_SESSION['datosOC'])==0) 
				unset($_SESSION['datosOC']);
								
			echo "<label class='msje_correcto'>Registro Eliminado con &Eacute;xito</label>";
			echo "<meta http-equiv='refresh' content='2;url=frm_generarOC.php'>";
		}
	}//Cierre de la funcion guardarRegistroSalida()	
	
	
?>