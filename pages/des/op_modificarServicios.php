<?php
	/**
	  * Nombre del Módulo: Desarrollo                                               
	  * Nombre Programador: Daisy Adriana Martínez Fernández                           
	  * Fecha: 07/Noviembre/2011                                      			
	  * Descripción: Este archivo permite consultar los registros de Servicios realizados a Minera Fresnillo
	  **/
	 	
	//Función que permite mostrar los Servicios Registrados 
	function mostrarRegistros(){
		//Realizar la conexion a la BD de Desarrollo
		$conn = conecta("bd_desarrollo");
		
		//Modificamos las fechas
		$fechaIni=modFecha($_POST["txt_fechaIni"],3);
		$fechaFin=modFecha($_POST["txt_fechaFin"],3);
		
			//Creamos la sentencia SQL
			$stm_sql = "SELECT * FROM detalle_servicios WHERE fecha>='$fechaIni' AND fecha<='$fechaFin' 
					   ORDER BY id_servicio";
			
			//Variable que guarda el titulo de la tabla
			$titulo="Registros de Servicios de <u><em>".$_POST["txt_fechaIni"]."</u></em> a <u><em>".$_POST["txt_fechaFin"]."</u></em>";
			
			//Variable que almacena el msj de error
			$error="No existen Registros del <u><em>".$_POST["txt_fechaIni"]."</u></em> al <u><em>".$_POST["txt_fechaFin"]."</u></em>";
							
		//Ejecutamos la sentencia SQL
		$rs = mysql_query($stm_sql);
		
		//Si la consulta trajo datos creamos la tabla para mostrarlos
		if($datos=mysql_fetch_array($rs)){						
			echo "								
			<table cellpadding='5' width='100%'>
				<caption class='titulo_etiqueta'>$titulo</caption>					
				<tr>
					<td  class='nombres_columnas'>SELECCIONAR</td>
					<td class='nombres_columnas'>FECHA</td>
					<td class='nombres_columnas'>CATEGOR&Iacute;A</td>
					<td class='nombres_columnas'>ACTIVIDAD</td>
					<td class='nombres_columnas'>TURNOS OFICIAL</td>
					<td class='nombres_columnas'>TURNOS AYUDANTE</td>
			</tr>";		
			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{
				$cat=$datos["categoria"];
				if ($cat=="AMBOS")
					$cat="OFICIAL Y<br>AYUDANTE GENERAL";
				echo "<tr>
						<td class='nombres_filas' align='center'>
							<input type='radio' id='rdb_equipo' name='rdb_id' value='$datos[id_servicio]'/>
						</td>		
						<td align='center' class='$nom_clase'>".modFecha($datos['fecha'],1)."</td>
						<td align='center' class='$nom_clase'>$cat</td>
						<td align='center' class='$nom_clase'>$datos[actividad]</td>
						<td align='center' class='$nom_clase'>$datos[turnoOf]</td>
						<td align='center' class='$nom_clase'>$datos[turnoAy]</td>
					</tr>";																		
					
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
			}while($datos=mysql_fetch_array($rs));

			echo "</table>";
			return 1;
		}
		else{
			//Si no se encuentra ningun resultado desplegar un mensaje					
			echo "<label class='msje_correcto'>$error</label>";
			return 0;
		}?>							
		<?php
		//Cerrar la conexion con la BD
		mysql_close($conn);
	}//Cierre de la funcion
	

	//Funcion que se encarga de modifica  el Registro seleccionado
	function modificarRegistroSeleccionado(){
		//Conectar a la BD de Desarrollo
		$conn = conecta("bd_desarrollo");

		//Crear sentencia SQL
		$sql_stm ="SELECT * FROM detalle_servicios WHERE id_servicio = '$_POST[rdb_id]'";

		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($sql_stm);

		//Confirmar que la consulta de datos fue realizada con exito.
		$datos=mysql_fetch_array($rs);?>
		<script language="javascript" type="text/javascript">
			setTimeout("activarTurnosAdmon('<?php echo $datos["categoria"];?>')",500);
		</script>
		<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
    	<div class="titulo_barra" id="titulo-modificar">Modificar Servicios con Minera Fresnillo </div>
	
		<fieldset class="borde_seccion" id="tabla-modificarRegistro" name="tabla-modificarRegistro">
		<legend class="titulo_etiqueta">Modificar Datos del Servicio</legend>	
		<br>
		<form onsubmit="return valFormRegServicios(this);" name="frm_modificarServicio" method="post" action="frm_modificarServicios.php">
		<table width="684" height="216" cellpadding="5" cellspacing="5" class="tabla_frm">
		<tr>
  		  <td width="83"><div align="right">Fecha</div></td>
			<td width="172">
				<input type="text" name="txt_fecha" id="txt_fecha" size="10" maxlength="10" class="caja_de_texto" readonly="readonly" 
				value="<?php echo modFecha($datos["fecha"],1) ?>"/>
			</td>
			<td width="98"><div align="right">*Categor&iacute;a</div></td>
			<td width="264">
				<select name="cmb_categoria" id="cmb_categoria" onchange="activarTurnosAdmon(this.value);">
					<option value="">Categor&iacute;a</option>
					<option <?php if($datos["categoria"]=="OFICIAL") echo "selected='selected'";?> value="OFICIAL">OFICIAL</option>
					<option <?php if($datos["categoria"]=="AYUDANTE GENERAL") echo "selected='selected'";?> value="AYUDANTE GENERAL">AYUDANTE GENERAL</option>
					<option <?php if($datos["categoria"]=="AMBOS") echo "selected='selected'";?> value="AMBOS">AMBOS</option>
				</select>
				<?php
				$oficial="readonly='readonly'";
				$aygral="readonly='readonly'";
				if ($datos["categoria"]=="OFICIAL" || $datos["categoria"]=="AMBOS")
					$oficial="";
				if ($datos["categoria"]=="AYUDANTE GENERAL" || $datos["categoria"]=="AMBOS")
					$aygral="";					
				?>
	  		</td>
		</tr>
		<tr>
	  		<td rowspan="2"><div align="right">*Actividad</div></td>
			<td rowspan="2">
				<textarea name="txa_actividad" id="txa_actividad" maxlength="160" onkeyup="return ismaxlength(this)" class="caja_de_texto" rows="3" cols="30"
           	 	onkeypress="return permite(event,'num_car', 0);" ><?php echo $datos["actividad"];?></textarea>
			</td>
			<td><div align="right">**Turnos Oficial</div></td>
	  		<td>
			<input name="txt_turnosOf" id="txt_turnosOf" type="text" class="caja_de_texto" size="5" maxlength="5" value="<?php echo $datos["turnoOf"];?>" onkeypress="return permite(event,'num', 2);" <?php echo $oficial;?>/>
			<input type="hidden" name="hdn_revisarOf" id="hdn_revisarOf" value="no"/>
			</td>
		</tr>
		<tr>
			<td><div align="right">**Turnos Ayudante</div></td>
	  		<td>
			<input name="txt_turnosAy" id="txt_turnosAy" type="text" class="caja_de_texto" size="5" maxlength="5" value="<?php echo $datos["turnoAy"];?>" onkeypress="return permite(event,'num', 2);" <?php echo $aygral;?>/>
			<input type="hidden" name="hdn_revisarAy" id="hdn_revisarAy" value="no"/>
			</td>
		</tr>
		<tr>
		<td colspan="4"><strong>
		*Los campos marcados con asterisco (*) son <u>obligatorios.</u><br>
		**Los campos marcados con doble asterisco (**) son <u>obligatorios.</u> Dependiendo lo seleccionado.
		</strong></td>
		</tr>
		<tr>
				<td colspan="4">
					<div align="center"> 
						<input type="hidden" name="hdn_validar" id="hdn_validar" value="si"/>
						<input type="hidden" name="hdn_botonSeleccionado" id="hdn_botonSeleccionado" value="" />
						<input name="hdn_radio" id="hdn_radio"  type="hidden" value="<?php echo $_POST["rdb_id"];?>"/>      	    	
						<input name="sbt_guardar"  id="sbt_guardar" type="submit" class="botones"  value="Guardar" title="Guardar Cambios en el Registro" 
						onMouseOver="window.status='';return true" onclick="hdn_botonSeleccionado.value='sbt_guardar'" />
						&nbsp;&nbsp;&nbsp;
						<input name="rst_limpiar" type="reset" class="botones"  value="Restablecer" title="Restablecer Formulario" 
						onMouseOver="window.status='';return true" /> 
						&nbsp;&nbsp;&nbsp;
						<input name="sbt_cancelar" type="submit" class="botones" value="Cancelar" title="Regresar a la P&aacute;gina Anterior" 
						onMouseOver="window.status='';return true" 
						onclick="location.href='menu_servicios.php';document.frm_modificarServicio.action='frm_modificarServicios.php';hdn_botonSeleccionado.value='sbt_cancelar'"/>
						<input type="hidden" name="txt_fechaIni" value="<?php echo $_POST["txt_fechaIni"];?>"/>
						<input type="hidden" name="txt_fechaFin" value="<?php echo $_POST["txt_fechaFin"];?>"/>
					</div>		
				</td>
			</tr>
		</table>
		</form>
		</fieldset>

		<div id="calendario">
			<input type="image" name="fecha" id="fecha_registro" src="../../images/calendar.png"
			onclick="displayCalendar(document.frm_modificarServicio.txt_fecha,'dd/mm/yyyy',this)" 
			onmouseover="window.status='';return true"width="25" height="25" border="0" align="absbottom" title="Seleccionar Fecha de Realizaci&Oacute;del Servicio"/> 
		</div>
		<?php
	}						
	 
	function guardarModificacionRegistro(){
		//Realizar la conexion a la BD de Desarrollo
		$conn = conecta("bd_desarrollo");	 			

		//Ponemos en mayusculas los campos necesarios
		$actividades=strtoupper($_POST["txa_actividad"]);		
		$fecha=modFecha($_POST["txt_fecha"],3);
		$categoria=$_POST["cmb_categoria"];
		$turnOf=$_POST["txt_turnosOf"];
		$turnAy=$_POST["txt_turnosAy"];
		
		//Crear la sentencia para realizar el registro del nuevo Equipo en la BD de desarrollo
		$stm_sql = "UPDATE detalle_servicios SET fecha='$fecha', categoria='$categoria', actividad='$actividades', turnoOf='$turnOf', turnoAy='$turnAy' WHERE id_servicio='$_POST[hdn_radio]'";					

		//Ejecutar la sentencia previamente creada		
		$rs = mysql_query($stm_sql);									
									
		//Confirmar que la insercion de datos fue realizada con exito.
		if($rs){
			//Cerrar la conexion con la BD		
			mysql_close($conn);
			//Registrar la Operacion en la tabla de movimientos
			registrarOperacion("bd_desarrollo",$_POST["hdn_radio"],"modificarRegistro",$_SESSION['usr_reg']);
			echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
		}
		else{
			$error = mysql_error();
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
			//Cerrar la conexion con la BD		
			mysql_close($conn);
		}
	}	
?>