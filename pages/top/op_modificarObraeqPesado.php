<?php
	/**
	  * Nombre del Módulo: Topografía                                               
	  * Nombre Programador: Antonio de Jesús Jiménez Cuevas                           
	  * Fecha: 13/Agosto/2011
	  * Descripción: Este archivo contiene funciones para almacenar la información relacionada con el formulario de Generar Obras 
	  **/

	//Cuando el usuario de modificar, los datos seran tomados del $_POST para almacenarlos en la BD
	if(isset($_POST['sbt_modificarObraEq'])){
		guardarCambiosObraEquipo();			
	}
		
	/*Esta funcion consulta los datos de la Obra en la Base de Datos y los carga al formulario*/	
	function mostrarObraEqPesado(){
		$tipoObra=$_POST['cmb_tipoObraEqP'];
		$nomObra=$_POST['cmb_nomObraEq'];
		//Realizar la conexion a la BD de Topografia
		$conn = conecta("bd_topografia");
		//Crear sentencia SQL
		$stm_sql = "SELECT * FROM equipo_pesado WHERE fam_equipo = '$tipoObra' AND concepto = '$nomObra'";
		//Ejecutar la sentencia creada
		$rs = mysql_query($stm_sql);
		//Si se obtiene un resultado de la busqueda, cargar los datos a las variables para ser mostradas en el formulario de Modificar Obras
		if($datosObra=mysql_fetch_array($rs)){
			$txt_idObra = $datosObra['id_registro'];
			$cmb_familia = $datosObra['fam_equipo'];
			$concepto = $datosObra['concepto'];
			$txt_unidad = $datosObra['unidad'];			
			$txt_fechaRegistro = modFecha($datosObra['fecha_registro'],1);
			$txt_precioEstimacionMN = number_format($datosObra['pumn_estimacion'],2,".",",");
			$txt_precioEstimacionUSD = number_format($datosObra['puusd_estimacion'],2,".",",");
			?>
			<fieldset class="borde_seccion" id="tabla-registrarObra" name="tabla-registrarObra">
			<legend class="titulo_etiqueta">Ingrese Informaci&oacute;n del Registro </legend>	
			<br>
			<form onSubmit="return valFormRegObraEP(this);" name="frm_modificarObraEP" id="frm_modificarObraEP" method="post" action="op_modificarObraeqPesado.php">
				<table width="731" cellpadding="5" cellspacing="5" class="tabla_frm">
					<tr>
						<td width="123"><div align="right">Id Registro</div></td>
						<td width="207"><input name="txt_idObra" id="txt_idObra" type="text" class="caja_de_texto" size="10" maxlength="10" value="<?php echo $txt_idObra;?>"  readonly="readonly"/></td>
						<td width="154"><div align="right">Fecha Registro</div></td>
						<td width="180">
							<input name="txt_fechaRegistro" id="txt_fechaRegistro" type="text" class="caja_de_texto" size="10" maxlength="10" readonly="readonly"
							value="<?php echo $txt_fechaRegistro; ?>" /></td>
					</tr>
					<tr>
					  <td><div align="right">*Tipo Equipo</div></td>
					  <td>
						<?php $result = cargarComboEspecifico("cmb_familia","familia","equipos","bd_mantenimiento","MINA","area","Equipo",$cmb_familia);
						if($result==0){ ?>
						<select name="cmb_familia" id="cmb_familia" class="combo_box">
								<option value="">Equipo</option>
						</select>
						<?php }?>
					  </td>
					  <td><div align="right">*Unidad</div></td>
						<td><input name="txt_unidad" id="txt_unidad" type="text" class="caja_de_texto" value="<?php echo $txt_unidad?>" onkeypress="return permite(event,'num_car',2);" tabindex="2" /></td>
					</tr>
					<tr>
					  <td><div align="right">*Precio Unitario M.N. Estimaci&oacute;n</div></td>
					  <td>$
						<input name="txt_precioEstimacionMN" id="txt_precioEstimacionMN" type="text" class="caja_de_texto" onkeypress="return permite(event,'num',2);" 
						value="<?php echo $txt_precioEstimacionMN?>" onchange="formatCurrency(value,'txt_precioEstimacionMN')" tabindex="4" />
						</td>
						<td><div align="right">*Precio Unitario USD Estimaci&oacute;n</div></td>
						<td>$
						<input name="txt_precioEstimacionUSD" id="txt_precioEstimacionUSD" type="text" class="caja_de_texto" 
						onkeypress="return permite(event,'num',2);" value="<?php echo $txt_precioEstimacionUSD?>" onchange="formatCurrency(value,'txt_precioEstimacionUSD')" tabindex="6" />                </td>
					</tr>
					<tr>
						<td><div align="right">*Nombre de la Obra de Equipo Pesado </div></td>
						<td colspan="3">
							<input name="txt_nombreObraEqP" type="text" class="caja_de_texto" id="txt_nombreObraEqP" onkeypress="return permite(event,'num_car',0);" 
					  		value="<?php echo $concepto?>" size="80" maxlength="80" tabindex="7" 
							onblur="validarCambioClave('bd_topografia','equipo_pesado','concepto',this,'<?php echo $concepto;?>','sbt_modificarObraEq','frm_modificarObraEP')"/>
					  </td>
					</tr>
					<tr>
						<td colspan="4"><div align="left"><strong>* Los campos marcados con asterisco son <u>obligatorios</u></strong></div></td>
					</tr>
					<tr>
						<td colspan="4" align="center">
							<input type="hidden" name="hdn_claveValida" id="hdn_claveValida" value="si"/>
							<input name="sbt_modificarObraEq" type="submit" class="botones" id="sbt_modificarObraEq"  value="Modificar" title="Modificar Obra de Equipo Pesado" 
							onmouseover="window.status='';return true" tabindex="9" />
							&nbsp;&nbsp;&nbsp;
							<input name="rst_limpiar" type="reset" class="botones" id="rst_limpiar"  value="Restablecer" title="Restablecer Formulario" 
							onmouseover="window.status='';return true" tabindex="10" />
							&nbsp;&nbsp;&nbsp;
							<input name="btn_cancelar" type="button" class="botones" id="btn_cancelar" value="Cancelar" title="Cancelar y Regresar a Elegir otra Obra" 
							onmouseover="window.status='';return true" onclick="confirmarSalida('frm_modificarEqPesado.php');" tabindex="11"/>
						</td>
					</tr>
				</table>
			</form>
			</fieldset>
			<?php
		}
		else{
			return "Error";
		}	
		//Cerrar la Conexion con la Base de Datos
		mysql_close($conn);
	}
	
	function guardarCambiosObraEquipo(){
		include_once("../../includes/conexion.inc");
		include_once("../../includes/op_operacionesBD.php");
		include_once("../../includes/func_fechas.php");
		//Recoger los datos
		$id_obraEq = $_POST['txt_idObra'];
		$familia = $_POST['cmb_familia'];		
		$concepto = strtoupper($_POST['txt_nombreObraEqP']);
		$unidad = strtoupper($_POST['txt_unidad']);
		$precioEstimacionMN = str_replace(",","",$_POST['txt_precioEstimacionMN']);
		$precioEstimacionUSD = str_replace(",","",$_POST['txt_precioEstimacionUSD']);
		$fechaRegistro = modfecha($_POST['txt_fechaRegistro'],3);
		//Realizar la conexion a la BD de Topografía
		$conn = conecta("bd_topografia");
		//Crear la Sentencias SQL para Almacenar los datos de la Obra
		$stm_sql= "UPDATE equipo_pesado SET fam_equipo='$familia', concepto='$concepto', unidad='$unidad',
					pumn_estimacion='$precioEstimacionMN', puusd_estimacion='$precioEstimacionUSD', fecha_registro='$fechaRegistro' WHERE id_registro='$id_obraEq'";
		//Ejecutar la Sentencia
		$rs=mysql_query($stm_sql);
		//Verificar Resultado
		if ($rs){
			session_start();
			//Guardar la operacion realizada
			registrarOperacion("bd_topografia",$id_obraEq,"ModificarObraEqPesado",$_SESSION['usr_reg']);															
			echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
		}
		else{
			$error = mysql_error();
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
		}
	}
?>