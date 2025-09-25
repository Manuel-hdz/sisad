<?php
	/**
	  * Nombre del M�dulo: Panel de Control
	  * Nombre Programador: Antonio de Jes�s Jim�nez Cuevas
	  * Fecha: 13/Agosto/2011
	  * Descripci�n: Este archivo contiene funciones para almacenar la informaci�n relacionada con el formulario de BorrarUsuarios del Sistema
	**/

	//Funcion que muestra los Usuarios Registrados
	function mostrarUsuarios(){
		include_once("../../includes/conexion.inc");
		//Realizar la conexion a la BD de Usuarios
		$conn = conecta("bd_usuarios");
		
		//Crear la sentencia para mostrar el catalogo de Materiales
		//$stm_sql = "SELECT AES_DECRYPT(clave,128) AS clave,usuario,tipo_usuario,depto FROM usuarios";
		$stm_sql = "SELECT depto,tipo_usuario,usuario,nombre FROM usuarios JOIN credenciales ON usuarios_usuario=usuario WHERE usuario!='CPanel' ORDER BY depto,tipo_usuario,nombre";

		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($stm_sql);	
										
		//Confirmar que la consulta de datos fue realizada con exito.
		if($datos=mysql_fetch_array($rs)){
			//Desplegar los resultados de la consulta en una tabla
			echo "				
				<table cellpadding='5' width='100%'>      			
				<tr>
				    <td colspan='18' align='center' class='titulo_etiqueta'>Seleccione un Usuario para Modificar</td>
  				</tr>
					<tr>
						<td class='nombres_columnas' align='center' colspan='2'>SELECCIONAR</td>
						<td class='nombres_columnas' align='center'>DEPARTAMENTO</td>
						<td class='nombres_columnas' align='center'>TIPO DE USUARIO</td>
						<td class='nombres_columnas' align='center'>USUARIO</td>
						<td class='nombres_columnas' align='center'>ASIGNADO A</td>			
      				</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;
			do{	
				echo "	<tr>
						<td class='nombres_filas' align='center'>$cont.-</td>
						<td class='nombres_filas' align='center'>";?><input type="radio" name="rdb_usuario" id="rdb_usuario" value="<?php echo $datos["usuario"];?>" title="Modificar al Usuario Seleccionado"/><?php echo "</td>
						<td class='nombres_filas' align='center'>$datos[depto]</td>
						<td class='$nom_clase' align='center'>$datos[tipo_usuario]</td>
						<td class='$nom_clase' align='center'>$datos[usuario]</td>
						<td class='$nom_clase' align='center'>$datos[nombre]</td>";
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
					
			}while($datos=mysql_fetch_array($rs));
			echo "</table>";
		}
		//Cerrar la conexion con la BD		
		mysql_close($conn);
	}
	
	function mostrarUsuario($usuario){
		//Incluir el archivo de Conexion
		include_once("../../includes/conexion.inc");
		//Realizar la conexion a la BD de Usuarios
		$conn = conecta("bd_usuarios");
		
		$stm_sql = "SELECT usuario,tipo_usuario,depto,nombre FROM usuarios JOIN credenciales ON usuarios_usuario=usuario WHERE usuario='$usuario'";

		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($stm_sql);
		
		//Confirmar que la consulta de datos fue realizada con exito.
		if($datos=mysql_fetch_array($rs)){
			?>
			<fieldset class="borde_seccion" id="tabla-datos" name="tabla-datos">
			<legend class="titulo_etiqueta">Modificar Informaci&oacute;n del Usuario</legend>
			<form name="frm_modificarUsuario" method="post" action="frm_modificarUsuario.php" onsubmit="return valFormUsuarios(this);">
			<table width="741" cellpadding="5" cellspacing="5" class="tabla_frm">
				<tr>
					<td><div align="right">*Trabajador</div></td>
					<td colspan="3">
						<input type="text" name="txt_nombre" id="txt_nombre" onkeyup="lookup(this,'empleados','1');" 
						value="<?php echo $datos["nombre"];?>" size="60" maxlength="80" onkeypress="return permite(event,'car',0);" tabindex="1"/>
						<div id="res-spider">
							<div align="left" class="suggestionsBox" id="suggestions1" style="display: none;">
								<img src="../../images/upArrow.png" style="position: relative; top: -12px; left: 10px;" alt="upArrow" />
								<div class="suggestionList" id="autoSuggestionsList1">&nbsp;</div>
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<td width="128"><div align="right">**Nombre de Usuario</div></td>
					<td>
						<input name="txt_usuario" id="txt_usuario" type="text" class="caja_de_texto" size="15" value="<?php echo $datos["usuario"];?>" disabled="disabled"/>
						<input name="hdn_usuario" id="hdn_usuario" type="hidden" value="<?php echo $datos["usuario"];?>">
					</td>
				  <td width="146"><div align="right">**Contrase&ntilde;a</div></td>
					<td><input type="password" class="caja_de_texto" name="txt_pass" id="txt_pass" size="15" value="" 
						onchange="validarFortaleza(this);txt_passConfirm.value='';"/><label id="fortaleza"></label></td>
				</tr>     
				<tr>
					<td width="128"><div align="right">*Tipo de Usuario</div></td>
					<td width="237">
						<select name="cmb_tipo" id="cmb_tipo" size="1" class="combo_box" disabled="disabled">
							<option <?php if($datos["tipo_usuario"]=="") echo "selected='selected'"; ?> value="">Tipo</option>
							<option <?php if($datos["tipo_usuario"]=="administrador") echo "selected='selected'"; ?> value="administrador">ADMINISTRADOR</option>
							<option <?php if($datos["tipo_usuario"]=="auxiliar") echo "selected='selected'"; ?> value="auxiliar">AUXILIAR</option>
							<option <?php if($datos["tipo_usuario"]=="externo") echo "selected='selected'"; ?> value="externo">EXTERNO</option>
						</select>
				  </td>
				  <td width="146"><div align="right">**Confirmar Contrase&ntilde;a</div></td>
					<td width="163" colspan="2">
					<input type="password" class="caja_de_texto" name="txt_passConfirm" id="txt_passConfirm" size="15" value="" onchange="validarPass(txt_pass,txt_passConfirm);"/>
				  </td>
				</tr> 
				<tr>
					<td width="128"><div align="right">*Departamento</div></td>
					<td colspan="3">
						<select name="cmb_depto" id="cmb_depto" size="1" class="combo_box" disabled="disabled">
							<option <?php if($datos["depto"]=="") echo "selected='selected'"; ?> value="">Departamento</option>
							<option <?php if($datos["depto"]=="Almacen") echo "selected='selected'"; ?> value="Almacen">ALMACEN</option>
							<option <?php if($datos["depto"]=="Compras") echo "selected='selected'"; ?> value="Compras">COMPRAS</option>
							<option <?php if($datos["depto"]=="MttoConcreto") echo "selected='selected'"; ?> value="MttoConcreto">MANTENIMIENTO CONCRETO</option>
							<option <?php if($datos["depto"]=="MttoMina") echo "selected='selected'"; ?> value="MttoMina">MANTENIMIENTO MINA</option>
							<option <?php if($datos["depto"]=="MttoElectrico") echo "selected='selected'"; ?> value="MttoElectrico">MANTENIMIENTO EL&Eacute;CTRICO</option>
							<option <?php if($datos["depto"]=="RecursosHumanos") echo "selected='selected'"; ?> value="RecursosHumanos">RECURSOS HUMANOS</option>
							<option <?php if($datos["depto"]=="Topografia") echo "selected='selected'"; ?> value="Topografia">TOPOGRAFIA</option>
							<option <?php if($datos["depto"]=="Laboratorio") echo "selected='selected'"; ?> value="Laboratorio">LABORATORIO</option>
							<option <?php if($datos["depto"]=="Lampisteria") echo "selected='selected'"; ?> value="Lampisteria">LAMPISTERIA</option>
							<option <?php if($datos["depto"]=="Produccion") echo "selected='selected'"; ?> value="Produccion">PRODUCCION</option>
							<option <?php if($datos["depto"]=="GerenciaTecnica") echo "selected='selected'"; ?> value="GerenciaTecnica">GERENCIA TECNICA</option>
							<option <?php if($datos["depto"]=="Desarrollo") echo "selected='selected'"; ?> value="Desarrollo">DESARROLLO</option>
							<option <?php if($datos["depto"]=="AseguramientoCalidad") echo "selected='selected'"; ?> value="AseguramientoCalidad">ASEGURAMIENTO CALIDAD</option>
							<option <?php if($datos["depto"]=="SeguridadIndustrial") echo "selected='selected'"; ?> value="SeguridadIndustrial">SEGURIDAD INDUSTRIAL</option>
							<option <?php if($datos["depto"]=="SeguridadAmbiental") echo "selected='selected'"; ?> value="SeguridadAmbiental">SEGURIDAD AMBIENTAL</option>
							<option <?php if($datos["depto"]=="Clinica") echo "selected='selected'"; ?> value="Clinica">UNIDAD SALUD OCUPACIONAL</option>
							<option <?php if($datos["depto"]=="Comaro") echo "selected='selected'"; ?> value="Comaro">COMARO</option>
							<option <?php if($datos["depto"]=="Sistemas") echo "selected='selected'"; ?> value="Sistemas">SISTEMAS</option>
							<option <?php if($datos["depto"]=="SupervisionDes") echo "selected='selected'"; ?> value="SupervisionDes">SUPERVSION DESARROLLO</option>
						</select>
					</td>
				</tr>
				<tr>
					<td colspan="5">
						<strong>
						* Datos marcados con asterisco son <u>obligatorios</u><br>
						** Datos <u>Obligatorios</u> y Sensibles a <u>May&uacute;sculas</u> y <u>Min&uacute;sculas</u>
						</strong>
					</td>
				</tr>
				<tr>
					<td colspan="6">
						<div align="center">
							<input type="hidden" name="hdn_fortaleza" id="hdn_fortaleza" value="" />
							<input name="sbt_modificar" type="submit" class="botones" value="Modificar" title="Modificar los datos Usuario" 
							onmouseover="window.status='';return true"/>
							&nbsp;&nbsp;&nbsp;
							<input type="reset" name="btn_borrar" class="botones" value="Limpiar" title="Restablecer el Formulario" onclick="fortaleza.innerHTML =''"/>
							&nbsp;&nbsp;&nbsp;
							<input name="btn_regresar" type="button" class="botones" value="Regresar" title="Regresar a Elegir Otro Usuario" 
							onMouseOver="window.status='';return true" onclick="location.href='frm_modificarUsuario.php';" />
						</div>
					</td>
				</tr>
			</table>
			</form>
			</fieldset>
		<?php
		}
		//Cerrar la conexion con la BD		
		mysql_close($conn);
	}
	
	function modificarUsuario($user,$pass){
		//Incluir el archivo de conexion
		include_once("../../includes/conexion.inc");
		//Realizar la conexion a la BD de Almacen
		$conn = conecta("bd_usuarios");
		//Crear la sentencia para mostrar el catalogo de Materiales
		$stm_sql = "UPDATE usuarios SET clave=AES_ENCRYPT('$pass',128) WHERE usuario='$user'";
		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($stm_sql);
		//Cerrar la conexion con la BD		
		mysql_close($conn);
	}
	
	function modificarCredencial($user,$trabajador){
		$trabajador=strtoupper($trabajador);
		//Incluir el archivo de conexion
		include_once("../../includes/conexion.inc");
		//Realizar la conexion a la BD de Almacen
		$conn = conecta("bd_usuarios");
		//Crear la sentencia para mostrar el catalogo de Materiales
		$stm_sql = "UPDATE credenciales SET nombre='$trabajador' WHERE usuarios_usuario='$user'";
		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($stm_sql);
		//Cerrar la conexion con la BD		
		mysql_close($conn);
	}

?>