<?php
	/**
	  * Nombre del M�dulo: Recursos Humanos
	  * Nombre Programador: Antonio de Jes�s Jim�nez Cuevas
	  * Fecha: 07/Abril/2011
	  * Descripci�n: Este archivo contiene funciones para Consultar un Empleado de la BD de Recursos y poder Modificarlo
	**/

	//Funcion que muestra al empleado buscado segun frm_modificarEmpleado.php
	function mostrarEmpleados1($patron){
		//Verificar si esta definido el arreglo datosPersonalesMod en la SESSION, de ser as�
		//Asignarles los datos de las variables de Session a las variables anteriormente
		//declaradas
		if (isset($_SESSION["datosPersonalesMod"])){
			$rfc=$_SESSION["datosPersonalesMod"]["rfc"];
			$nombre=$_SESSION["datosPersonalesMod"]["nombre"];
			$apPat=$_SESSION["datosPersonalesMod"]["apPat"];
			$apMat=$_SESSION["datosPersonalesMod"]["apMat"];
			$sangre=$_SESSION["datosPersonalesMod"]["sangre"];
			$curp=$_SESSION["datosPersonalesMod"]["curp"];
			$calle=$_SESSION["datosPersonalesMod"]["calle"];
			$num_ext=$_SESSION["datosPersonalesMod"]["num_ext"];
			$num_int=$_SESSION["datosPersonalesMod"]["num_int"];
			$codigoPost=$_SESSION["datosPersonalesMod"]["cp"];
			$fecha=$_SESSION["datosPersonalesMod"]["fecha"];
			$col=$_SESSION["datosPersonalesMod"]["col"];
			$estado=$_SESSION["datosPersonalesMod"]["estado"];
			$pais=$_SESSION["datosPersonalesMod"]["pais"];
			$nac=$_SESSION["datosPersonalesMod"]["nac"];
			$nss=$_SESSION["datosPersonalesMod"]["nss"];
			$edoCivil=$_SESSION["datosPersonalesMod"]["edoCivil"];
			$obs=$_SESSION["datosPersonalesMod"]["obs"];
			$contactoAcc=$_SESSION["datosPersonalesMod"]["contactoAcc"];
			$telCasa=$_SESSION["datosPersonalesMod"]["telCasa"];
			$celular=$_SESSION["datosPersonalesMod"]["celular"];
			$mun_loc=strtoupper($_SESSION["datosPersonalesMod"]["mun_loc"]);
			$telTrabajador=$_SESSION["datosPersonalesMod"]["telTrabajador"];
			$lugarNac=$_SESSION["datosPersonales"]["lugarNac"];
			//Datos Formato DC-4
			$discapacidad=$_SESSION["datosPersonalesMod"]["discapacidad"];
			$hijosDepEco=$_SESSION["datosPersonalesMod"]["hijosDepEco"];
			//Datos Academicos
			$nivEstudios=$_SESSION["datosPersonalesMod"]["nivEstudios"];
			$titulo=$_SESSION["datosPersonalesMod"]["titulo"];
			$carrera=$_SESSION["datosPersonalesMod"]["carrera"];
			$tipoEscuela=$_SESSION["datosPersonalesMod"]["tipoEscuela"];
			//Control de costos
			$cmb_con_cos=$_SESSION["datosPersonales"]["control_cos"];
			$cmb_cuenta=$_SESSION["datosPersonales"]["cuentas"];
			//Datos de alimentos
			$derechoAlimento=$_SESSION["datosPersonales"]["alimento"];
		}
		else{
			if (isset($_POST["txt_nombreBuscar"]) || isset($_POST["txt_nombreBuscar_baja"]))
				//Creamos la sentencia SQL para mostrar los datos del empleado con el nombre que llega en el txt_nombre via POST
				$stm_sql= "SELECT rfc_empleado,nombre,ape_pat,ape_mat,tipo_sangre,curp,calle,num_ext,num_int,fecha_ingreso,
						colonia,estado,pais,nacionalidad,no_ss,edo_civil, observaciones, localidad,nom_accidente,tel_accidente,cel_accidente,telefono,
						discapacidad,hijos_dep_eco,nivel_estudio,titulo,carrera,tipo_escuela,lugar_nacimiento,id_control_costos,id_cuentas,cp,derecho_alimentos 
						FROM empleados WHERE CONCAT(nombre,' ',ape_pat,' ',ape_mat)=";
			if (isset($_GET["rfc"]))
				//Creamos la sentencia SQL para mostrar los datos del empleado con el nombre que llega en el rfc via GET
				$stm_sql= "SELECT rfc_empleado,nombre,ape_pat,ape_mat,tipo_sangre,curp,calle,num_ext,num_int,fecha_ingreso,
						colonia,estado,pais,nacionalidad,no_ss, edo_civil, observaciones, localidad,nom_accidente,tel_accidente,cel_accidente,telefono,
						discapacidad,hijos_dep_eco,nivel_estudio,titulo,carrera,tipo_escuela,lugar_nacimiento,id_control_costos,id_cuentas,cp,derecho_alimentos 
						FROM empleados WHERE rfc_empleado='$_GET[rfc]'";
			
			if ($patron == 1){
				if (isset($_POST["txt_nombreBuscar"])){
					$stm_sql .= "'$_POST[txt_nombreBuscar]' AND estado_actual = 'ALTA'";
				}
				if (isset($_GET["rfc"])){
					$stm_sql .= " AND estado_actual = 'ALTA'";
				}
			}
			if ($patron == 2){
				if (isset($_POST["txt_nombreBuscar_baja"])){
					$stm_sql .= "'$_POST[txt_nombreBuscar_baja]' AND estado_actual = 'BAJA'";
				}
				if (isset($_GET["rfc"])){
					$stm_sql .= " AND estado_actual = 'BAJA'";
				}
			}
			
			//Abrimos la conexion con la Base de datos
			$conn=conecta("bd_recursos");
			//Ejecutamos la sentencia SQL
			$rs=mysql_query($stm_sql);
			if ($datos=mysql_fetch_array($rs)){
				$rfc=$datos["rfc_empleado"];
				$nombre=$datos["nombre"];
				$apPat=$datos["ape_pat"];
				$apMat=$datos["ape_mat"];
				$sangre=$datos["tipo_sangre"];
				$curp=$datos["curp"];
				$calle=$datos["calle"];
				$num_ext=$datos["num_ext"];
				$num_int=$datos["num_int"];
				$codigoPost=$datos["cp"];
				$fecha=modFecha($datos["fecha_ingreso"],1);
				$col=$datos["colonia"];
				$estado=$datos["estado"];
				$pais=$datos["pais"];
				$nac=$datos["nacionalidad"];
				$nss=$datos["no_ss"];
				$edoCivil=$datos["edo_civil"];
				$obs=$datos["observaciones"];
				$contactoAcc=$datos["nom_accidente"];
				$telCasa=$datos["tel_accidente"];
				$celular=$datos["cel_accidente"];
				$mun_loc=$datos["localidad"];
				$telTrabajador=$datos["telefono"];
				$lugarNac=$datos["lugar_nacimiento"];
				//Datos Formato DC-4
				$discapacidad=$datos["discapacidad"];
				$hijosDepEco=$datos["hijos_dep_eco"];
				//Datos Academicos
				$nivEstudios=$datos["nivel_estudio"];
				$titulo=$datos["titulo"];
				$carrera=$datos["carrera"];
				$tipoEscuela=$datos["tipo_escuela"];
				//Control de costos
				$cmb_con_cos=$datos["id_control_costos"];
				$cmb_cuenta=$datos["id_cuentas"];
				//Datos de alimentos
				$derechoAlimento=$datos["derecho_alimentos"];
			}
			else{
				echo "<br><br><br><br><br>
					  <br><br><br><br><br>
					  <br><br><br><br><br>
					  <br><br><br><br><br>";
					 
				if ($patron == 1){
					 echo "<p align='center' class='msje_correcto'>No hay Trabajadores Registrados con el Nombre <em><u>$_POST[txt_nombreBuscar]</u></em></p>";
				}
				if ($patron == 2){
					 echo "<p align='center' class='msje_correcto'>No hay Trabajadores Registrados con el Nombre <em><u>$_POST[txt_nombreBuscar_baja]</u></em></p>";
				}
					  ?>
					  <p align="center">
					  <input name="btn_cancelar" type="button" class="botones" value="Regresar" title="Regresar a Buscar Otro Empleado para Modificar" 
					  onMouseOver="window.status='';return true" onclick="location.href='frm_modificarEmpleado.php?cancelar'" />
					  </p>
					  <?php
				return 0;
			}
		}
		?>
		<fieldset class="borde_seccion" id="tabla-modificarEmpleado">
		<legend class="titulo_etiqueta">Modificar Empleado - Datos Personales (1/2) </legend>
		<form onSubmit="return valFormModificarEmpleado(this);" name="frm_modificarEmpleado1" method="post" action="frm_modificarEmpleado.php">
		  <table width="100%" height="336" cellpadding="5" cellspacing="5" class="tabla_frm">
            <tr>
              <td width="119"><div align="right">*RFC</div></td>
              <td width="161"><input name="txt_rfc" id="txt_rfc" type="text" class="caja_de_texto" size="10" maxlength="13" 
            	onkeypress="return permite(event,'num_car', 3);" autocomplete="off" required="required"
            	onblur="return validarCambioClave('bd_recursos','empleados','rfc_empleado',this,'<?php echo $rfc?>','sbt_continuar','frm_modificarEmpleado1')" value="<?php echo $rfc;?>" />
			  </td>
              <td width="143"><div align="right">*CURP</div></td>
              <td width="151"><input name="txt_curp" id="txt_curp" type="text" class="caja_de_texto" size="20" maxlength="18" onkeypress="return permite(event,'num_car',3);" 
            	value="<?php echo $curp;?>" autocomplete="off" required="required"/></td>
              <td width="95"><div align="right">*NSS </div></td>
              <td width="167"><input name="txt_nss" id="txt_nss" type="text" class="caja_de_texto" size="11" maxlength="11" onkeypress="return permite(event,'num',3);" 
            	value="<?php echo $nss;?>"/></td>
            </tr>
            <tr>
              <td><div align="right">*Nombre</div></td>
              <td><input name="txt_nombre" id="txt_nombre" type="text" class="caja_de_texto" size="25" maxlength="25" onkeypress="return permite(event,'car',0);" 
            	value="<?php echo $nombre;?>" autocomplete="off" required="required"/>
              </td>
              <td><div align="right">*Apellido Paterno </div></td>
              <td><input name="txt_apePat" id="txt_apePat" type="text" class="caja_de_texto" size="25" maxlength="25" onkeypress="return permite(event,'car',0);" 
            	value="<?php echo $apPat;?>" autocomplete="off" required="required"/></td>
              <td width="92"><div align="right">*Apellido Materno </div></td>
              <td width="143"><input name="txt_apeMat" id="txt_apeMat" type="text" class="caja_de_texto" size="25" maxlength="25" onkeypress="return permite(event,'car',0);" 
                value="<?php echo $apMat;?>" autocomplete="off" required="required"/></td>
            </tr>
            <tr>
              <td><div align="right">*Calle</div></td>
              <td><input name="txt_calle" id="txt_calle" type="text" class="caja_de_texto" size="20" maxlength="40" onkeypress="return permite(event,'num_car',3);" 
                value="<?php echo $calle;?>" autocomplete="off" required="required"/></td>
              <td><div align="right">*N&uacute;m Ext.</div></td>
              <td><input name="txt_numExt" id="txt_numExt" type="text" class="caja_de_texto" size="3" maxlength="5" onkeypress="return permite(event,'num',0);" 
            	value="<?php echo $num_ext;?>" onchange="if (this.value&lt;=0){ alert ('N&uacute;mero no V&aacute;lido');this.value='';}" autocomplete="off" required="required"/>
                N&uacute;m Int.
                <input name="txt_numInt" id="txt_numInt" type="text" class="caja_de_texto" size="3" maxlength="5" onkeypress="return permite(event,'num_car',3);" 
                value="<?php echo $num_int;?>" onchange="if (this.value&lt;=0){ alert ('N&uacute;mero no V&aacute;lido');this.value='';}"/></td>
              <td width="92"><div align="right">*Colonia</div></td>
              <td width="143"><input type="text" name="txt_colonia" id="txt_colonia" size="20" maxlength="20" onkeypress="return permite(event,'num_car',1);" 
            	class="caja_de_texto" value="<?php echo $col;?>" /></td>
            </tr>
            <tr>
        		<td>&nbsp;</td>
        		<td>&nbsp;</td>
        		<td>
        			<div align="right">*C.P.</div>
        		</td>
       		 	<td>
      	 	 		<input name="txt_cp" id="txt_cp" type="text" class="caja_de_num" size="5" maxlength="5" onkeypress="return permite(event,'num',3)" value="<?php echo $codigoPost; ?>" autocomplete="off" required="required">
      		  	</td>
      		</tr>
            <tr>
              <td><div align="right">*Municipio/Localidad</div></td>
              <td><input name="txt_munLoc" id="txt_munLoc" type="text" class="caja_de_texto" size="20" maxlength="40" onkeypress="return permite(event,'num_car',3);" 
                value="<?php echo $mun_loc;?>" autocomplete="off" required="required"/></td>
              <td><div align="right">*Estado</div></td>
              <td><input name="txt_estado" id="txt_estado" type="text" class="caja_de_texto" size="20" maxlength="20" onkeypress="return permite(event,'car',0);" 
            	value="<?php echo $estado;?>" autocomplete="off" required="required"/></td>
              <td><div align="right">*Pa&iacute;s</div></td>
              <td><input name="txt_pais" id="txt_pais" type="text" class="caja_de_texto" size="20" maxlength="20" onkeypress="return permite(event,'car',0);" 
                value="<?php echo $pais;?>" autocomplete="off" required="required"/></td>
            </tr>
            <tr>
              <td><div align="right">*Nacionalidad</div></td>
              <td><input name="txt_nacionalidad" id="txt_nacionalidad" type="text" class="caja_de_texto" size="20" maxlength="20" 
            	onkeypress="return permite(event,'car',0);" value="<?php echo $nac;?>" autocomplete="off" required="required"/></td>
              <td><div align="right">Tel&eacute;fono</div></td>
          <td><input name="txt_telTrabajador" id="txt_telTrabajador" type="text" class="caja_de_texto" size="16" maxlength="15"  onblur="validarTelefono(this);"
            	onkeypress="return permite(event,'num',3);" value="<?php echo $telTrabajador;?>"/></td>
            <td><div align="right">*Estado Civil</div></td>
          <td><select name="cmb_estado" id="cmb_estado" size="1" class="combo_box" required="required">
              <option <?php if($edoCivil=="") echo "selected='selected'"?> value="">Estado Civil</option>
              <option <?php if($edoCivil=="SOLTERO") echo "selected='selected'"?> value="SOLTERO">SOLTERO</option>
              <option <?php if($edoCivil=="UNI�N LIBRE") echo "selected='selected'"?> value="UNI&Oacute;N LIBRE">UNI&Oacute;N LIBRE</option>
              <option <?php if($edoCivil=="CASADO") echo "selected='selected'"?> value="CASADO">CASADO</option>
              <option <?php if($edoCivil=="DIVORCIADO") echo "selected='selected'"?> value="DIVORCIADO">DIVORCIADO</option>
              <option <?php if($edoCivil=="VIUDO") echo "selected='selected'"?> value="VIUDO">VIUDO</option>
            </select></td>
        </tr>       	
        <tr>
            <td><div align="right">*Tipo Sangre</div></td>
          <td><input name="txt_sangre" id="txt_sangre" type="text" class="caja_de_texto" size="5" maxlength="5" 
            	onkeypress="return permite(event,'car',0);" value="<?php echo $sangre;?>" autocomplete="off" required="required"/></td>
            <td><div align="right">*Fecha de Ingreso</div>		  
            <td><input type="text" name="txt_fechaIngreso" id="txt_fechaIngreso" size="10" maxlength="10" class="caja_de_texto" readonly="readonly" 
            	value="<?php echo $fecha; ?>"/></td>
			<td><div align="right">Lugar Nacimiento </div></td>
            <td><input name="txt_lugarNac" id="txt_lugarNac" type="text" class="caja_de_texto" size="20" maxlength="40" onkeypress="return permite(event,'num_car',3);" 
                value="<?php echo $lugarNac;?>" autocomplete="off" required="required"/></td>
        </tr>
		<tr>
			<td><div align="right">*Discapacidad</div></td>
			<td>
				<select name="cmb_tipoDisc" id="cmb_tipoDisc" class="combo_box" required="required">
					<option <?php if($discapacidad=="") echo "selected='selected' ";?>value="">Discapacidad</option>
					<option <?php if($discapacidad=="N/A") echo "selected='selected' ";?>value="N/A">NINGUNA</option>
					<option <?php if($discapacidad=="MOTRIZ") echo "selected='selected' ";?>value="MOTRIZ">MOTRIZ</option>
					<option <?php if($discapacidad=="VISUAL") echo "selected='selected' ";?>value="VISUAL">VISUAL</option>
					<option <?php if($discapacidad=="MENTAL") echo "selected='selected' ";?>value="MENTAL">MENTAL</option>
					<option <?php if($discapacidad=="AUDITIVA") echo "selected='selected' ";?>value="AUDITIVA">AUDITIVA</option>
					<option <?php if($discapacidad=="DE LENGUAJE") echo "selected='selected' ";?>value="DE LENGUAJE">DE LENGUAJE</option>
				</select>
			</td>
			<td><div align="right">Dep. Econ&oacute;micos </div></td>
			<td>
				<input type="text" class="caja_de_num" name="txt_depEco" id="txt_depEco" size="2" maxlength="2" onkeypress="return permite(event,'num',3);" value="<?php echo $hijosDepEco;?>" autocomplete="off" required="required"/> Hijos
			</td>
			<td><div align="right">Observaciones</div></td>
			<td><textarea name="txa_observaciones" id="txa_observaciones" maxlength="120" onkeyup="return ismaxlength(this)" class="caja_de_texto" 
            	rows="2" cols="30" onkeypress="return permite(event,'num_car', 0);"><?php echo $obs;?></textarea></td>
		</tr>
		<tr>
			<td><div align="right">*Nivel Estudios</div></td>
			<td>
				<select name="cmb_nivEstudios" id="cmb_nivEstudios" class="combo_box" title="Nivel M&aacute;ximo de Estudios Terminados" required="required">
					<option <?php if($nivEstudios=="") echo "selected='selected' ";?>value="">Escolaridad</option>
					<option <?php if($nivEstudios=="1") echo "selected='selected' ";?>value="1">PRIMARIA</option>
					<option <?php if($nivEstudios=="2") echo "selected='selected' ";?>value="2">SECUNDARIA</option>
					<option <?php if($nivEstudios=="3") echo "selected='selected' ";?>value="3">BACHILLERATO</option>
					<option <?php if($nivEstudios=="4") echo "selected='selected' ";?>value="4">CARRERA T&Eacute;CNICA</option>
					<option <?php if($nivEstudios=="5") echo "selected='selected' ";?>value="5">LICENCIATURA</option>
					<option <?php if($nivEstudios=="6") echo "selected='selected' ";?>value="6">ESPECIALIDAD</option>
					<option <?php if($nivEstudios=="7") echo "selected='selected' ";?>value="7">MAESTR&Iacute;A</option>
					<option <?php if($nivEstudios=="8") echo "selected='selected' ";?>value="8">DOCTORADO</option>
				</select>
			</td>
			<td><div align="right">*Doc. Obtenido</div></td>
			<td>
				<select name="cmb_docObtenido" id="cmb_docObtenido" class="combo_box" title="Documento Probatorio Obtenido" required="required">
					<option <?php if($titulo=="") echo "selected='selected' ";?>value="">Documento</option>
					<option <?php if($titulo=="1") echo "selected='selected' ";?>value="1">T&Iacute;TULO</option>
					<option <?php if($titulo=="2") echo "selected='selected' ";?>value="2">CERTIFICADO</option>
					<option <?php if($titulo=="3") echo "selected='selected' ";?>value="3">DIPLOMA</option>
					<option <?php if($titulo=="4") echo "selected='selected' ";?>value="4">OTRO</option>
				</select>
			</td>
			<td><div align="right">*Estudio/Carrera</div></td>
			<td>
				<input type="text" name="txt_carrera" id="txt_carrera" class="caja_de_texto" maxlength="40" size="30" value="<?php echo $carrera;?>" autocomplete="off" required="required"/>
			</td>
		</tr>
		<tr>
			<td><div align="right">*Instituci&oacute;n</div></td>
			<td>
				<select name="cmb_institucion" id="cmb_institucion" class="combo_box" title="Tipo de Instituci&oacute;n Educativa" required="required">
					<option <?php if($tipoEscuela=="") echo "selected='selected' ";?>value="">Tipo</option>
					<option <?php if($tipoEscuela=="1") echo "selected='selected' ";?>value="1">P&Uacute;BLICA</option>
					<option <?php if($tipoEscuela=="2") echo "selected='selected' ";?>value="2">PRIVADA</option>
				</select>
			</td>
			<td><div align="right">*Control de Costos</div></td>
			<td>
				<?php 
					$conn = conecta("bd_recursos");		
					$stm_sql = "SELECT * FROM control_costos WHERE habilitado = 'SI' ORDER BY descripcion";
					$rs = mysql_query($stm_sql);
					//Confirmar que la consulta fue exitosa y guardar los datos en el ComboBox		
					if($datos = mysql_fetch_array($rs)){?>
						<select name="cmb_con_cos" id="cmb_con_cos" class="combo_box" onchange="cargarCuentas(this.value,'cmb_cuenta')" required="required">
							<?php //Colocar el mensaje en definido en el parametro $msj para ser desplegado en el ComboBox
							echo "<option value=''>Control de Costos</option>";
							do{
								if ($datos['id_control_costos'] == $cmb_con_cos){
									echo "<option value='$datos[id_control_costos]' selected='selected'>$datos[descripcion]</option>";
								}else{
									echo "<option value='$datos[id_control_costos]'>$datos[descripcion]</option>";
								}
							}while($datos = mysql_fetch_array($rs));
							echo "<script type='text/javascript'>
									cargarCuentas(cmb_con_cos.value,'cmb_cuenta');
								  </script>";
							?>
							<script type="text/javascript">
								setTimeout("document.getElementById('cmb_cuenta').value='<?php echo $cmb_cuenta ?>'",500);
							</script>
						</select>
					<?php
					}
					else{
						echo "<label class='msje_correcto'> No actualmente control de costos</label>
							<input type='hidden' name='cmb_area' id='cmb_area'/>";
					}
					//Cerrar la conexion con la BD		
					mysql_close($conn);
				?>
			</td>
			<td width="15%"><div align="right">*Cuenta</div></td>
    		    <td width="40%">
					<span id="datosCuenta">
						<select name="cmb_cuenta" id="cmb_cuenta" class="combo_box" required="required">
							<option value="">Cuentas</option>
						</select>
					</span>
			  </td>
		</tr>
		<tr><td colspan="4"><strong>Derecho de alimentos</strong></td></tr>
        <tr>
            <td><div align="right">Derecho </div></td>
			<td>
				<select name="cmb_alimento" id="cmb_alimento" class="combo_box" title="Nivel de Beneficio de alimentos" required="required">
					<option <?php if($derechoAlimento=="") echo "selected='selected' ";?>value="">% de Beneficio</option>
					<option <?php if($derechoAlimento=="1") echo "selected='selected' ";?>value="1">3 Comidas al 100%</option>
					<option <?php if($derechoAlimento=="2") echo "selected='selected' ";?>value="2">2 Comidas al 100%</option>
					<option <?php if($derechoAlimento=="3") echo "selected='selected' ";?>value="3">1 Comida al 100%</option>
					<option <?php if($derechoAlimento=="4") echo "selected='selected' ";?>value="4">3 Comidas al 50%</option>
					<option <?php if($derechoAlimento=="5") echo "selected='selected' ";?>value="5">0% Sin Descuento</option>
				</select>
			</td>
        </tr>
		<tr><td colspan="4"><strong>Datos de Contacto en caso de Accidente</strong></td></tr>
        <tr>
            <td><div align="right">Nombre </div></td>
       	  <td><input name="txt_contactoAcc" id="txt_contactoAcc" type="text" class="caja_de_texto" size="28" maxlength="60" 
            	onkeypress="return permite(event,'num_car',0);" value="<?php echo $contactoAcc;?>"/></td>
            <td><div align="right">Tel Casa </div></td>
            <td><input name="txt_telCasa" id="txt_telCasa" type="text" class="caja_de_texto" size="16" maxlength="15" onblur="validarTelefono(this);" 
            	onkeypress="return permite(event,'num',3);" value="<?php echo $telCasa;?>"/></td>
            <td><div align="right">Celular</div></td>
       	  <td><input name="txt_celular" id="txt_celular" type="text" class="caja_de_texto" size="16" maxlength="15"  onblur="validarTelefono(this);"
            	onkeypress="return permite(event,'num',3);" value="<?php echo $celular;?>"/></td>
        </tr>
            <tr>
              <td colspan="4"><strong>* Los campos marcados con asterisco son <u>obligatorios</u></strong></td>
            </tr>
            <tr>
              <td colspan="6"><?php
					if (isset($_GET["rfc"]))
						$rfcOriginal=$_GET["rfc"];
					else
						$rfcOriginal=$rfc;
				?>
                  <div align="center">
                    <input type="hidden" name="hdn_rfc" id="hdn_rfc" value="<?php echo $rfcOriginal; ?>" />
                    <input name="sbt_continuar" id="sbt_continuar" type="submit" class="botones"  value="Continuar" title="Agregar los Datos Personales del Empleado" 
				onmouseover="window.status='';return true" />
                    &nbsp;&nbsp;&nbsp;
                    <input type="button" name="btn_modBeneficiarios" onmouseover="window.status='';return true;" title="Modificar los Beneficiarios del Empleado"
				class="botones" value="Beneficiarios" onclick="location.href='frm_modificarBeneficiarios.php?mod=ben&rfc=<?php echo $rfcOriginal;?>'"/>
                    &nbsp;&nbsp;&nbsp;
                    <input type="button" name="btn_modBecarios" onmouseover="window.status='';return true;" title="Modificar los Becarios del Empleado"
				class="botones" value="Becarios" onclick="location.href='frm_modificarBeneficiarios.php?mod=bec&rfc=<?php echo $rfcOriginal;?>'"/>
                    &nbsp;&nbsp;&nbsp;
                    <?php if (!isset($_SESSION["datosPersonales"])){?>
                    <input name="rst_limpiar2" type="reset" class="botones"  value="Limpiar" title="Limpiar Formulario" onmouseover="window.status='';return true"/>
                    <?php }
					else {
				?>
                    <input name="rst_limpiar2" type="reset" class="botones"  value="Reestablecer" title="Reestablecer Formulario" onmouseover="window.status='';return true"/>
                    <?php }	?>
                    &nbsp;&nbsp;&nbsp;
                    <input name="btn_cancelar2" type="button" class="botones" value="Cancelar" title="Regresar a Buscar Otro Empleado para Modificar" 
				onmouseover="window.status='';return true" onclick="confirmarSalida('frm_modificarEmpleado.php?cancelar');"/>
                </div></td>
            </tr>
          </table>
		</form>
		</fieldset>
		<div id="calendario">
			<input type="image" name="fechaIngreso" id="fechaIngreso" src="../../images/calendar.png"
			onclick="displayCalendar(document.frm_modificarEmpleado1.txt_fechaIngreso,'dd/mm/yyyy',this)" 
			onmouseover="window.status='';return true"width="25" height="25" border="0" align="absbottom" title="Seleccionar Fecha de Ingreso de Empleado"/> 
		</div>
		<?php
		//Cerramos la conexion con la Base de Datos
		//mysql_close($conn);
	}//Fin de la funcion de mostrarEmpleados
	
	function mostrarEmpleados2(){
		//Creamos la sentencia SQL para mostrar los datos del empleado con el nombre que llega en el txt_nombre via POST
		$stm_sql= "SELECT id_empleados_empresa,id_empleados_area,jornada,no_cta,mime,sueldo_diario,area,puesto,oc_esp FROM empleados 
					WHERE rfc_empleado='$_POST[hdn_rfc]'";
						
		//Abrimos la conexion con la Base de datos
		$conn=conecta("bd_recursos");
		//Ejecutamos la sentencia SQL
		$rs=mysql_query($stm_sql);
		if ($datos=mysql_fetch_array($rs)){
			$cve_emp=$datos["id_empleados_empresa"];
			$cve_area=$datos["id_empleados_area"];
			$jor=$datos["jornada"];
			$no_cta=$datos["no_cta"];
			$foto=$datos["mime"];
			$sueldo=number_format($datos["sueldo_diario"],2,".",",");
			$cmb_area=$datos["area"];
			$puesto=$datos["puesto"];
			$ocEsp=$datos["oc_esp"];
		}
		else{
			echo "<br><br><br><br><br><br><br><br><p align='center' class='msje_correcto'>No hay Trabajadores Registrados con el Nombre <em><u>$_POST[txt_nombre]</u></em></p>";
			return 0;
		}
		?>
		
		<script language="javascript" type="text/javascript">
			//cargarCombo('<?php echo $cmb_area; ?>','bd_recursos','empleados','puesto','area','cmb_puesto','Puesto','<?php echo $puesto;?>');
		</script>
		
		<fieldset class="borde_seccion" id="tabla-modificarEmpleado2">
		<legend class="titulo_etiqueta">Modificar Empleado - Datos Laborales (2/2) </legend>	
		<br>
		<form onSubmit="return valFormModificarEmpleado2(this);" name="frm_modificarEmpleado2" method="post" action="frm_modificarEmpleado.php" enctype="multipart/form-data">
		<table width="923" height="336" cellpadding="5" cellspacing="5" class="tabla_frm">
		<tr>
			<td width="137"><div align="right">*Clave Empresarial</div></td>
			<td width="298">
				<input name="txt_cveEmp" id="txt_cveEmp" type="text" class="caja_de_texto" size="15" maxlength="13" onkeypress="return permite(event,'num_car', 3);" value="<?php echo $cve_emp;?>" onblur="verificarCveEmp(this.value,hdn_cveOriginal.value,'sbt_modificarEmpleado');"/>
				<span id="error" class="msj_error">Clave Empresarial Duplicada</span>
				<input type="hidden" id="hdn_cveOriginal" name="hdn_cveOriginal" value="<?php echo $cve_emp;?>"/>
			</td>
			<?php 
			
			$cc = obtenerNombreCentroCostos($_SESSION["datosPersonales"]["control_cos"]);
			$clave_area = obtenerIdArea($cc,1);
			if($clave_area==null){
				$clave_area = obtenerIdArea($cc,2);
			}
			$id_depto=obtenerIdDepto($cc,1);
			if ($id_depto==null){
				$id_depto=obtenerIdDepto($cc,2);
			}
			if(isset($_SESSION["datosPersonales"]["cuentas"])){
				$id_depto=obtenerIdDepto2($_SESSION["datosPersonales"]["cuentas"],$clave_area);
			}
			?>
			<td width="168"><div align="right">*Clave &Aacute;rea </div></td>
			<td width="253"><input name="txt_cveArea" id="txt_cveArea" type="text" class="caja_de_texto" size="25" 
			maxlength="25" onkeypress="return permite(event,'num',0);" readonly="readonly" value="<?php echo $clave_area;?>" autocomplete="off" required="required"/></td>
		</tr>
		<tr>
			<td><div align="right">Jornada</div></td>
		  <td>
		  	<input name="txt_jornada" id="txt_jornada" type="text" class="caja_de_num" size="5" maxlength="2" 
			onkeypress="return permite(event,'num',3);" value="<?php echo $jor;?>" />&nbsp;Hrs.
		</td>
		  <td><div align="right">*N&uacute;mero de Cuenta</div></td>
			<td><input name="txt_numCta" id="txt_numCta" type="text" class="caja_de_texto" size="20" maxlength="20" onkeypress="return permite(event,'num_car',3);" value="<?php echo $no_cta;?>" autocomplete="off" required="required"/></td>
		</tr>
		<!--<tr>
			<td><div align="right">*&Aacute;rea </div></td>
			<td>
				<?php 
					//$result=mysql_query("SELECT DISTINCT area,id_depto FROM empleados WHERE id_depto>0 ORDER BY area");
					$result=mysql_query("SELECT DISTINCT area,id_depto FROM empleados ORDER BY area");?>
					<select name="cmb_area" id="cmb_area" size="1" class="combo_box" onchange="ordDato(this.value,'hdn_cveArea','hdn_claveDepto');">
						<option value="">&Aacute;rea</option>
							<?php while ($row=mysql_fetch_array($result)){
								if ($row['area'] == $cmb_area){
									echo "<option value='$row[id_depto];$row[area]' selected='selected'>$row[area]</option>";
								}
								else{
									echo "<option value='$row[id_depto];$row[area]'>$row[area]</option>";
								}
							}
				?>
					</select>
			</td>
			<td><div align="right">
			  <input type="checkbox" name="ckb_nuevaArea" id="ckb_nuevaArea" onclick="agregarNuevaArea(this, 'ckb_nuevoPuesto', 'txt_nuevaArea', 'txt_nuevoPuesto', 'cmb_area', 'cmb_puesto');" 
			  title="Seleccione para escribir el nombre de un &Aacute;rea que no exista"/>
			  Agregar Nueva &Aacute;rea </div></td>
			<td>
			<input name="txt_nuevaArea" id="txt_nuevaArea" type="text" class="caja_de_texto" readonly="readonly" size="30" maxlength="30"/>
			<input name="hdn_cveArea" id="hdn_cveArea" type="hidden"/>
			</td>
		</tr>-->
		<tr>
			<td><div align="right">*Puesto</div></td>
			<td>
			<?php 
			$conn = conecta("bd_recursos");
			$result=mysql_query("SELECT DISTINCT puesto FROM empleados WHERE id_depto = '$id_depto' AND id_empleados_area = '$clave_area' ORDER BY puesto ");?>
			<select name="cmb_puesto" id="cmb_puesto" required="required" autocomplete="off" required="required">
				<option value="">Puesto</option>
				<?php while ($row=mysql_fetch_array($result)){
							if ($row['puesto'] == $puesto){
								echo "<option value='$row[puesto]' selected='selected'>$row[puesto]</option>";
							}
							else{
								echo "<option value='$row[puesto]'>$row[puesto]</option>";
							}
						} 
				//Cerrar la conexion con la BD		
				mysql_close($conn);?>
			</select>
			</td>
			<td><div align="right">
			  <input type="checkbox" name="ckb_nuevoPuesto" id="ckb_nuevoPuesto" onclick="agregarNuevoPuesto(this, 'ckb_nuevaArea', 'txt_nuevaArea', 'txt_nuevoPuesto', 'cmb_area', 'cmb_puesto');" 
			  title="Seleccione para escribir el nombre de un Puesto que no exista"/>
			  Agregar Nuevo Puesto </div></td>
		  <td><input name="txt_nuevoPuesto" id="txt_nuevoPuesto" type="text" class="caja_de_texto" readonly="readonly" size="30" maxlength="30"/></td>
		</tr>
		<tr>
			<td><div align="right">Fotograf&iacute;a</div></td>
		  <td><input type="file" id="foto" name="foto" class="caja_de_texto" size="20" title="Buscar Imagen" value=""
				onclick="alert('La Imagen no Debe Pesar mas de 10 Mb, de los Contrario no se Almacenar&aacute; en el Cat&aacute;logo de Empleados');" onchange="return validarImagen(this,'hdn_foto');"/><br/>
				<?php
				if ($foto!="")
					echo "<label class='msje_correcto'>Foto Cargada</label>";
				else
					echo "<label class='msje_correcto'>Sin Foto Cargada</label>";
				?>
		  <input type="hidden" id="hdn_foto" name="hdn_foto" value=""/></td>
			<td><div align="right">Sueldo Diario </div></td>
			<td>$
			  <input type="text" name="txt_sueldo" id="txt_sueldo" class="caja_de_num" size="10" maxlength="10" onchange="formatCurrency(value,'txt_sueldo');" onkeypress="return permite(event,'num',0);" value="<?php echo $sueldo;?>"/></td>
		</tr>
		<tr>
			<td><div align="right">Ocupaci&oacute;n Espec&iacute;fica</div></td>
			<td>
			  <input type="text" name="txt_ocEsp" id="txt_ocEsp" class="caja_de_texto" size="20" maxlength="20" onkeypress="return permite(event,'num_car',2);" 
			  title="Ocupaci&oacute;n Espec&iacute;fica, usada para el Formato de Capacitaciones DC-4" value="<?php echo $ocEsp;?>"/>
			</td>
		</tr>
		<tr>
		   <td colspan="4"><strong>* Los campos marcados con asterisco son <u>obligatorios</u></strong></td>
		</tr>
		<tr>
			<td colspan="4">
				<div align="center"> 
				<input type="hidden" name="hdn_claveDepto" id="hdn_claveDepto" value=""/>    
				<input name="sbt_modificarEmpleado" id="sbt_modificarEmpleado" type="submit" class="botones"  value="Modificar" title="Registrar los cambios hechos al Empleado" 
				onMouseOver="window.status='';return true" />
				&nbsp;&nbsp;&nbsp;
				<input name="rst_limpiar" type="reset" class="botones"  value="Limpiar" title="Limpiar Formulario" onMouseOver="window.status='';return true" onclick="cmb_area.disabled=false;cmb_puesto.disabled=false;sbt_modificarEmpleado.disabled=false;error.style.visibility='hidden';sbt_modificarEmpleado.title='Registrar los cambios hechos al Empleado';cargarCombo('<?php echo $cmb_area; ?>','bd_recursos','empleados','puesto','area','cmb_puesto','Puesto','<?php echo $puesto;?>');"/> 
				&nbsp;&nbsp;&nbsp;
				<input name="btn_cancelar" type="button" class="botones" value="Regresar" title="Regresar a la Pantalla de Datos Personales" 
				onMouseOver="window.status='';return true" onclick="location.href='frm_modificarEmpleado.php?rfc=<?php echo $_POST["hdn_rfc"]; ?>'"/>
				&nbsp;&nbsp;&nbsp;
				<input name="btn_regresar" type="button" class="botones" value="Cancelar" title="Cancelar y Regresar a Buscar Otro Empleado para Modificar" 
				onMouseOver="window.status='';return true" onclick="confirmarSalida('frm_modificarEmpleado.php?cancelar')"/>
				</div>		</td>
		</tr>
		</table>
		</form>
		</fieldset>
		<?php
		//Cerramos la conexion con la Base de Datos
		mysql_close($conn);
	}
	
	//Funcion que permite modificar los datos de los Trabajadores, estos datos son leidos de los Vectores POST y SESSION
	function modificarEmpleado(){		
		//Recoger los datos Personales de la SESSION
		$rfc=strtoupper($_SESSION["datosPersonalesMod"]["rfc"]);
		$nombre=strtoupper($_SESSION["datosPersonalesMod"]["nombre"]);
		$apPat=strtoupper($_SESSION["datosPersonalesMod"]["apPat"]);
		$apMat=strtoupper($_SESSION["datosPersonalesMod"]["apMat"]);
		$sangre=strtoupper($_SESSION["datosPersonalesMod"]["sangre"]);
		$curp=strtoupper($_SESSION["datosPersonalesMod"]["curp"]);
		$calle=strtoupper($_SESSION["datosPersonalesMod"]["calle"]);
		$num_ext=strtoupper($_SESSION["datosPersonalesMod"]["num_ext"]);
		$num_int=strtoupper($_SESSION["datosPersonalesMod"]["num_int"]);
		$codigoPost=strtoupper($_SESSION["datosPersonalesMod"]["cp"]);
		$fecha=modFecha($_SESSION["datosPersonalesMod"]["fecha"],3);
		$col=strtoupper($_SESSION["datosPersonalesMod"]["col"]);
		$estado=strtoupper($_SESSION["datosPersonalesMod"]["estado"]);
		$pais=strtoupper($_SESSION["datosPersonalesMod"]["pais"]);
		$nac=strtoupper($_SESSION["datosPersonalesMod"]["nac"]);
		$nss=strtoupper($_SESSION["datosPersonalesMod"]["nss"]);
		$edoCivil=strtoupper($_SESSION["datosPersonalesMod"]["edoCivil"]);
		$obs=strtoupper($_SESSION["datosPersonalesMod"]["obs"]);
		$contactoAcc=strtoupper($_SESSION["datosPersonalesMod"]["contactoAcc"]);
		$telCasa=$_SESSION["datosPersonalesMod"]["telCasa"];
		$celular=$_SESSION["datosPersonalesMod"]["celular"];
		$mun_loc=strtoupper($_SESSION["datosPersonalesMod"]["mun_loc"]);
		$telTrabajador=$_SESSION["datosPersonalesMod"]["telTrabajador"];
		$lugarNac=strtoupper($_SESSION["datosPersonales"]["lugarNac"]);
		//Datos Formato DC-4
		$discapacidad=$_SESSION["datosPersonalesMod"]["discapacidad"];
		$hijosDepEco=$_SESSION["datosPersonalesMod"]["hijosDepEco"];
		//Datos Academicos
		$nivEstudios=$_SESSION["datosPersonalesMod"]["nivEstudios"];
		$titulo=$_SESSION["datosPersonalesMod"]["titulo"];
		$carrera=strtoupper($_SESSION["datosPersonalesMod"]["carrera"]);
		$tipoEscuela=$_SESSION["datosPersonalesMod"]["tipoEscuela"];
		//Control de costos
		$cmb_con_cos=$_SESSION["datosPersonales"]["control_cos"];
		$cmb_cuenta=$_SESSION["datosPersonales"]["cuentas"];
		//Datos de alimentos
		$derechoAlimento=$_SESSION["datosPersonales"]["alimento"];
		//Recoger el RFC original para poder realizar la modificacion
		$rfcOriginal=$_SESSION["datosPersonalesMod"]["rfcOriginal"];
		
		//Recoger los datos laborales del POST
		$cve_empresa=strtoupper($_POST["txt_cveEmp"]);
		$cve_empresaOriginal=$_POST["hdn_cveOriginal"];
		$cve_area=strtoupper($_POST["txt_cveArea"]);
		$jornada=strtoupper($_POST["txt_jornada"]);
		$num_cta=strtoupper($_POST["txt_numCta"]);
		$ocEsp=strtoupper($_POST["txt_ocEsp"]);
		
		$sueldo=$_POST["txt_sueldo"];
		if (strlen($sueldo)>6)
			$sueldo=str_replace(",","",$sueldo);

		//Verificamos si viene el combo Activo de AREA para preparar la Sentencia SQL
		/*if (isset($_POST["cmb_area"])){
			$depto=split(";",$_POST["cmb_area"]);
			$area=$depto[1];
			$id_depto=$depto[0];
			$nuevo="no";
		}
		else{
			$area=strtoupper($_POST["txt_nuevaArea"]);
			$id_depto=obtenerIdDepto();
			$nuevo="si";
		}*/
		$area = obtenerNombreCentroCostos($cmb_con_cos);
		$id_depto=obtenerIdDepto($area,1);
		$nuevo="no";
		if ($id_depto==null){
			$id_depto=obtenerIdDepto($area,2);
			$nuevo="si";
		}
		if(isset($cmb_cuenta)){
			$clave_area = obtenerIdArea($area,1);
			if($clave_area==null){
				$clave_area = obtenerIdArea($area,2);
			}
			$id_depto=obtenerIdDepto2($cmb_cuenta,$clave_area);
		}
		//Verificamos si viene el combo Activo de PUESTO para preparar la Sentencia SQL
		if (isset($_POST["cmb_puesto"]))
			$puesto=$_POST["cmb_puesto"];
		else
			$puesto=strtoupper($_POST["txt_nuevoPuesto"]);
		
		//Abrimos la conexion con la Base de datos
		$conn=conecta("bd_recursos");
		
		//Crear primera parte de la sentencia SQL
		$stm_sql = "UPDATE empleados SET rfc_empleado='$rfc',curp='$curp',id_empleados_area='$cve_area',id_empleados_empresa='$cve_empresa',
				nombre='$nombre',ape_pat='$apPat', ape_mat='$apMat',sueldo_diario='$sueldo',tipo_sangre='$sangre',no_ss='$nss',
				fecha_ingreso='$fecha',puesto='$puesto',no_cta='$num_cta',area='$area',jornada='$jornada',calle='$calle',num_ext='$num_ext',num_int='$num_int',
				colonia='$col',cp=$codigoPost,estado='$estado',pais='$pais',nacionalidad='$nac',edo_civil='$edoCivil', observaciones='$obs', id_depto='$id_depto',
				telefono='$telTrabajador', nom_accidente='$contactoAcc',tel_accidente='$telCasa',cel_accidente='$celular',localidad='$mun_loc',
				discapacidad='$discapacidad',hijos_dep_eco='$hijosDepEco',nivel_estudio='$nivEstudios',titulo='$titulo',carrera='$carrera', 
				tipo_escuela='$tipoEscuela',oc_esp='$ocEsp',lugar_nacimiento='$lugarNac',id_control_costos='$cmb_con_cos',id_cuentas='$cmb_cuenta',derecho_alimentos='$derechoAlimento',estado_actual='ALTA'";
		
		//Cargar la imagen en el caso de que se haya cargado una y el tipo para ser almacenados en la BD
		if($_FILES["foto"]["error"]!=4){
			$foto_info = cargarImagen("foto"); 
			$stm_sql.=",fotografia='$foto_info[foto]',mime='$foto_info[type]'";
		}
		else 
			$foto_info = array("foto"=>"", "type"=>"");
		
		//Complementar la sentencia SQL de Actualizacion
		$stm_sql .= " WHERE rfc_empleado='$rfcOriginal'";

		/********************VERIFICAR SI EL PUESTO Y AREA DEL EMPLEADO FUERON MODIFICADOS ANTES DE ACTUALIZAR EL REGISTRO*************************/
		$guardarRegistro = "no";
		$rs_puesto = mysql_query("SELECT area, puesto FROM empleados WHERE rfc_empleado = '$rfc'");		
		if($datos_puesto=mysql_fetch_array($rs_puesto)){
			if($datos_puesto['area']!=$area)
				$guardarRegistro = "si";
			if($datos_puesto['puesto']!=$puesto)
				$guardarRegistro = "si";
		}				
		/********************VERIFICAR SI EL PUESTO Y AREA DEL EMPLEADO FUERON MODIFICADOS ANTES DE ACTUALIZAR EL REGISTRO*************************/

		//Ejecutar la Sentecia para Actualizar los Datos del Empleado
		$rs=mysql_query($stm_sql);
		
		//Verificar si la actualizacion se llevo a cabo con exito
		if ($rs){		
			
			/**********************************************GUARDAR REGISTRO DE LA MODIFICACION DEL PUESTO Y AREA EN LA TABLA DE 'baja_modificaciones'**********************************************/
			if($guardarRegistro=="si"){
				//Obtener la fecha Actual
				$fecha_modificacion = date("Y-m-d");
				//Cuando el registro haya sido modificado con exito se guardara diho registro en la tabla bajas_modificaciones en la BD de RH
				mysql_query("INSERT INTO bajas_modificaciones (empleados_rfc_empleado, nombre, ape_pat, ape_mat, fecha_ingreso, fecha_baja, area, puesto, observaciones, fecha_mod_puesto) 
				VALUES ('$rfc', '$nombre', '$apPat', '$apMat', '$fecha', '', '$datos_puesto[area]', '$datos_puesto[puesto]', '$obs', '$fecha_modificacion')");
			}
			/**********************************************GUARDAR REGISTRO DE LA MODIFICACION DEL PUESTO Y AREA EN LA TABLA DE 'baja_modificaciones'**********************************************/
			//Borrar de la sesion datosPersonalesMod
			unset($_SESSION["datosPersonalesMod"]);
			if($foto_info["foto"]!=""){ 
					//Extrae el contenido de la foto original
					$fp = opendir("documentos/");//Abrir el archivo temporal el modo lectura'r' binaria'b'
						
					rmdir("documentos/temp");
					closedir($fp);//Cerrar el puntero al archivo abierto	
				}
			
			//Cerrar la conexion con la BD
			mysql_close($conn);
			
			//Ingresar al Trabajador en el Ibix para el HandPunch
			actualizarHandPunch($nuevo,$area,$id_depto,$cve_empresa,$cve_empresaOriginal,$nombre,$apPat,$apMat,$fecha,$puesto,$curp,$rfc,$nss,$sangre);
			
			//Si el rfc cambio, se debe actualizar en las tablas de Beneficiarios y de Becas
			if ($rfc!=$rfcOriginal){
				//Verificar si el trabajador ha modificarse tiene Beneficiarios
				if (obtenerDato("bd_recursos", "beneficiarios", "empleados_rfc_empleado", "empleados_rfc_empleado", $rfc)!="")
					//Modificar la relacion de beneficiarios del Empleado
					modificarBeneficiarios($rfc,$rfcOriginal);
				//Verificar si el trabajador ha modificarse tiene Beneficiarios
				if (obtenerDato("bd_recursos", "becas", "empleados_rfc_empleado", "empleados_rfc_empleado", $rfc)!="")
					//Modificar la relacion de becarios del Empleado
					modificarBecarios($rfc,$rfcOriginal);
			}
			//Registrar la Operacion en la Bit�cora de Movimientos
			registrarOperacion("bd_recursos","$rfcOriginal","ModificarInformacionEmpleado",$_SESSION['usr_reg']);
			cambiarAreaChecador($cve_empresa,$cmb_con_cos,$cmb_cuenta);
			//Redireccionar a la pantalla de Exito
			echo "<meta http-equiv='refresh' content='0;url=exito.php'>";															
		}
		else{
			$error = mysql_error();
			//Cerrar la conexion con la BD
			mysql_close($conn);
			//Redireccionar a una pagina de error
			echo "<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>$error - $clave_area";
			break;
			echo "<meta http-equiv='refresh' content='7;url=error.php?err=$error'>";
		}
	}
	
	function cambiarAreaChecador($cve_empresa,$cmb_con_cos,$cmb_cuenta){
		$deptoChecador = 0;
		$cc = obtenerDato("bd_recursos", "control_costos", "descripcion", "id_control_costos", $cmb_con_cos);
		$cuen = obtenerDato("bd_recursos", "cuentas", "descripcion", "id_cuentas", $cmb_cuenta);
		
		switch($cc){
			case "INDIRECTOS":
				$deptoChecador = 7;
			break;
			case "ADMINISTRACI�N":
				$deptoChecador = 8;
			break;
			case "ZARPEO MADERO":
				if($cuen == "MANTENIMIENTO")
					$deptoChecador = 18;
				else
					$deptoChecador = 9;
			break;
			case "GOMAR":
				$deptoChecador = 11;
			break;
			case "SAN ALBERTO":
				if($cuen == "MANTENIMIENTO")
					$deptoChecador = 14;
				else
					$deptoChecador = 13;
			break;
			case "DESARROLLO SABINAS":
				$deptoChecador = 15;
			break;
			case "ZARPEO FRESNILLO":
				if($cuen == "MANTENIMIENTO")
					$deptoChecador = 17;
				else
					$deptoChecador = 16;
			break;
			case "ALCANTARILLADO SABINAS":
				$deptoChecador = 19;
			break;
			case "ZARPEO SAUCITO":
				if($cuen == "MANTENIMIENTO")
					$deptoChecador = 21;
				else
					$deptoChecador = 20;
			break;
			case "DESARROLLO 695":
				$deptoChecador = 22;
			break;
			case "SAN RICARDO":
				$deptoChecador = 23;
			break;
			case "OBRA CIVIL":
				$deptoChecador = 24;
			break;
		}
		
		if($deptoChecador != 0){
			$conn_access = odbc_connect("EasyClocking","","");
			$sql = "UPDATE Userinfo SET DeptID = '$deptoChecador' WHERE Userid = '$cve_empresa'";
			$rs_access = odbc_exec ($conn_access,$sql);
		}
	}
	
	//Esta funcion se encarga de convertir la imagen en un flujo binario para ser almacenado en la tabla de Empleados en el campo fotografia que es de tipo longblob
	function cargarImagen($nomInputFile){
		// Mime types permitidos: todos los navegadores==>'image/gif', IE==>'image/x-png' y 'image/pjpeg', Mozilla Firefox==>'image/png' y 'image/jpeg'
		$mimetypes = array("image/gif", "image/x-png", "image/png", "image/pjpeg", "image/jpeg");
		//El nombre debe ser el mismo que el declarado en el formulario en el atributo 'name' de la etiqueta 'input' y el 'type' debe ser 'file' 
		//Ej. <input name="foto" type="file" class="caja_de_texto" id="foto" size="20" />
		
		$name = $_FILES[$nomInputFile]["name"];
		$type = $_FILES[$nomInputFile]["type"];
		$tmp_name = $_FILES[$nomInputFile]["tmp_name"];
		$size = $_FILES[$nomInputFile]["size"];			
		
		//Verificamos si el archivo es una imagen v�lida y que el tama�o de la misma no exceda los 10,000 Kb 10,240,000 Bytes
		if(in_array($type, $mimetypes) && $size<10240000){
			//Cargar y Redimensionar la Imagen en el Directorio "../rec/documentos/temp"
			$archivoRedimensionado = cargarFotosEmp($nomInputFile);									
			//Extrae el contenido de la foto original
			$fp = fopen("documentos/temp"."/".$name, "rb");//Abrir el archivo temporal el modo lectura'r' binaria'b'
			$tfoto = fread($fp, filesize("documentos/temp"."/".$name));//Leer el archivo completo limitando la lectura al tama�o del archivo
			$tfoto = addslashes($tfoto);//Anteponer la \ a las comillas que puediera contener el archivo para evitar que sea interpretado como final de cadena
			fclose($fp);//Cerrar el puntero al archivo abierto				
		
			// Borra archivos temporales si es que existen
			@unlink("documentos/temp"."/".$name);			
			//Regresar la foto convertida para ser almacena en la BD
			return $foto_info = array("foto"=>$tfoto,"type"=>$type);
		}
		else{
			//Regresar la Info vacia de la foto en el caso de que no sea una imagen valida o exceda 10Mb 
			return $foto_info = array("foto"=>"","type"=>"");
		}
	}
	
	
	
	//funcion que carga una imagen a un Directorio Temporal en la carpeta de "rec/documentos/temp"
	function cargarFotosEmp($nomInputFile){		
		//Esta variable Indica si la Imagen fue guardada en el directorio indicado
		$estado = 0;
		//Crear la variabe que sera la ruta de almacenamiento
		$Ruta="";
		//Variable que Alamcenara la Carpeta donde sera guardada la imagen redimensionada
		$carpeta="";
		
		//Abrir un Gestor de Directorios
		$dir = opendir($Ruta); 
		//verificar si el archivo ha sido almacenado en la carpeta temporal
		if(is_uploaded_file($_FILES[$nomInputFile]['tmp_name'])) { 
			//crear el nombre de la carpeta contenedora de la fotografia cargada
			$carpeta="documentos/temp";
			
			//veririfcar si el nombre de la carpeta exite de lo contrario crearla
			if (!file_exists($carpeta."/"))
				echo mkdir($carpeta."/", 0777);
						
			//Mover la fotografia de la carpteta temporal a la que le hemos indicado					
			move_uploaded_file($_FILES[$nomInputFile]['tmp_name'], $carpeta."/".$_FILES[$nomInputFile]['name']);
			//llamar la funcion que se encarga de reducir el peso de la fotografia 
			redimensionarFoto($carpeta."/".$_FILES[$nomInputFile]['name'],$_FILES[$nomInputFile]['name'],$carpeta."/",100,100);
		}
				
		return $carpeta;

	}//FIN 	function cargarImagen()
	
	//Funcion que elimina a los Beneficiarios del Trabajador seleccionado
	function eliminarBeneficiarios($rfc){
		$nombre=$_POST["rdb_rfc"];
		$conn2=conecta("bd_recursos");
		$stm_sql="DELETE FROM beneficiarios WHERE nombre='$nombre' AND empleados_rfc_empleado='$rfc'";
		$rs=mysql_query($stm_sql);
		if (!$rs){
			//Redireccionar a una pagina de error
			$error=mysql_error();
			//Cerrar la conexion con la BD
			mysql_close($conn2);
			echo "<meta http-equiv='refresh' content='7;url=error.php?err=$error'>";
			return 0;
		}else{
			//Cerrar la conexion con la BD
			mysql_close($conn2);
			//Registrar la Operacion en la Bit�cora de Movimientos
			registrarOperacion("bd_recursos","$rfc","EliminarBeneficiarioEmpleado",$_SESSION['usr_reg']);
			return 1;
		}
	}
	
	//Funcion que elimina a los Becarios del Trabajador seleccionado
	function eliminarBecarios($rfc){
		$nombre=$_POST["rdb_rfc"];
		$conn2=conecta("bd_recursos");
		$stm_sql="DELETE FROM becas WHERE nom_becario='$nombre' AND empleados_rfc_empleado='$rfc'";
		$rs=mysql_query($stm_sql);
		if (!$rs){
			//Redireccionar a una pagina de error
			$error=mysql_error();
			//Cerrar la conexion con la BD
			mysql_close($conn2);
			echo "<meta http-equiv='refresh' content='7;url=error.php?err=$error'>";
			return 0;
		}else{
			//Cerrar la conexion con la BD
			mysql_close($conn2);
			//Registrar la Operacion en la Bit�cora de Movimientos
			registrarOperacion("bd_recursos","$rfc","EliminarBecarioEmpleado",$_SESSION['usr_reg']);
			return 1;
		}
	}
	
	//Funcion que modifica el RFC en la tabla de Beneficiarios, esto es por si el RFC cambio
	function modificarBeneficiarios($rfc,$rfcOriginal){
		//Conectar a la BD
		$conn=conecta("bd_recursos");
		$stm_sql="UPDATE beneficiarios SET empleados_rfc_empleado='$rfc' WHERE empleados_rfc_empleado='$rfcOriginal'";
		$rs=mysql_query($stm_sql);
		if (!$rs){
			//Redireccionar a una pagina de error
			$error=mysql_error();
			//Cerrar la conexion con la BD
			mysql_close($conn);
			echo "<meta http-equiv='refresh' content='7;url=error.php?err=$error'>";
		}
		else{
			//Cerrar la conexion con la BD
			mysql_close($conn);
			//Registrar la Operacion en la Bit�cora de Movimientos
			registrarOperacion("bd_recursos","$rfc","ActualizarBeneficiarioEmpleado",$_SESSION['usr_reg']);
		}
	}
	
	//Funcion que modifica el RFC en la tabla de Becarios, esto es por si el RFC cambio
	function modificarBecarios($rfc,$rfcOriginal){
		//Conectar a la BD
		$conn=conecta("bd_recursos");
		$stm_sql="UPDATE becas SET empleados_rfc_empleado='$rfc' WHERE empleados_rfc_empleado='$rfcOriginal'";
		$rs=mysql_query($stm_sql);
		if (!$rs){
			//Redireccionar a una pagina de error
			$error=mysql_error();
			//Cerrar la conexion con la BD
			mysql_close($conn);
			echo "<meta http-equiv='refresh' content='7;url=error.php?err=$error'>";
		}
		else{
			//Cerrar la conexion con la BD
			mysql_close($conn);
			//Registrar la Operacion en la Bit�cora de Movimientos
			registrarOperacion("bd_recursos","$rfc","ActualizarBecarioEmpleado",$_SESSION['usr_reg']);
		}
	}
	
	//Funcion que obtiene un Id para los deptos
	function obtenerIdDepto($id_cc,$op){
		//Conectarse a la BD de Recursos
		$conn = conecta("bd_recursos");
		if($op == 1)
			$stm_sql = "SELECT id_depto FROM empleados WHERE area = '$id_cc'";
		if($op == 2)
			$stm_sql = "SELECT MAX(id_depto)+1 AS claveDepto FROM empleados";
		$rs = mysql_query($stm_sql);
		mysql_close($conn);
		if($datos = mysql_fetch_array($rs))		
			return $datos[0];
		else
			return "";
	}//fin de obtenerIdDepto()
	
	//Funcion que obtiene un Id para los deptos
	function obtenerIdDepto2($id_c,$id_ar){
		//Conectarse a la BD de Recursos
		$conn = conecta("bd_recursos");
		
		$stm_sql = "SELECT id_depto FROM empleados WHERE id_empleados_area = '$id_ar' AND id_cuentas = '$id_c'";
		
		$rs = mysql_query($stm_sql);
		if($datos = mysql_fetch_array($rs)){
			mysql_close($conn);
			return $datos[0];
		}
		else{
			$stm_sql = "SELECT COALESCE( MAX( id_depto ) , 0 ) +1 AS claveDepto FROM empleados";
			$rs = mysql_query($stm_sql);
			if($datos = mysql_fetch_array($rs)){
				mysql_close($conn);
				return $datos[0];
			}
		}
	}//fin de obtenerIdDepto2()
	
	//Funcion que obtiene un ID para las areas
	function obtenerIdArea($id_cc,$op){
		//Conectarse a la BD de Recursos
		$conn = conecta("bd_recursos");
		if($op == 1)
			$stm_sql = "SELECT id_empleados_area FROM empleados WHERE area = '$id_cc'";
		if($op == 2)
			$stm_sql = "SELECT MAX(id_empleados_area)+1 AS num_areas FROM empleados";
		$rs = mysql_query($stm_sql);
		mysql_close($conn);
		if($datos = mysql_fetch_array($rs))		
			return $datos[0];
		else
			return null;
	}//fin de obtenerIdArea($id_cc,$op)
	
	//Funcion que obtiene el nombre de un centro de costos
	function obtenerNombreCentroCostos($id_cc){
		//Conectarse a la BD de Recursos
		$conn = conecta("bd_recursos");
		$stm_sql = "SELECT descripcion
					FROM control_costos
					WHERE id_control_costos =  '$id_cc'";
		$rs = mysql_query($stm_sql);
		mysql_close($conn);
		if($datos = mysql_fetch_array($rs))		
			return $datos[0];
		else
			return "";
	}//fin de obtenerNombreCentroCostos($id_cc)
	
	function actualizarHandPunch($nuevo,$area,$id_depto,$cve_empresa,$cve_empresaOriginal,$nombre,$apPat,$apMat,$fecha,$puesto,$curp,$rfc,$nss,$sangre){
		//Usuario
		$user="Administrador";
		//Password
		$password="libre";
		//Ubicacion de la Base de Datos
		$mdbFilename=realpath("handpunch\IBIXConnect.mdb");
		//Conexion a la Base de Datos
		$db_connstr="Driver={Microsoft Access Driver (*.mdb)}; Dbq=$mdbFilename"; 
		$conn=odbc_connect($db_connstr, $user, $password);
		//Capturar la clave de empleado para preparar el NIP del trabajador para el HandPunch
		$nip=$cve_empresa;
		$longNip=strlen($nip);
		if ($longNip==1)
			$nip="000".$nip;
		if ($longNip==2)
			$nip="00".$nip;
		if ($longNip==3)
			$nip="0".$nip;
		//Concatenar el nip con la Clave del Trabajador
		$idTrabajador="0100".$nip;
		if ($nuevo=="si"){
			//Preparar la sentencia Access para Ingresar al Departamento
			$stm_acc="INSERT INTO tblDepto (Emp,Depto,Nombre) VALUES ('1','$id_depto','$area')";
			//Ejecutar la sentencia
			$rs=odbc_exec($conn,$stm_acc);
		}
		//Preparar la sentencia Access para Ingresar al Trabajador
		$stm_acc="UPDATE tblTrabajador SET Trabajador='$cve_empresa',NIP='$nip',Clave='$idTrabajador',Nombre='$nombre $apPat $apMat',Depto='$id_depto',FechaAlta='$fecha',Puesto='$puesto',Curp='$curp',Rfc='$rfc',Imss='$nss',TipoSangre='$sangre',NumRegistro='$cve_empresa' WHERE Trabajador=$cve_empresaOriginal;";
		//Ejecutar la sentencia
		$rs=odbc_exec($conn,$stm_acc);
		//Cerrar la conexion con la BD
		odbc_close($conn);
	}
?>