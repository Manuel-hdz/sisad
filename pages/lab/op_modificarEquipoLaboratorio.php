<?php
	/**
	  * Nombre del Módulo: Laboratorio                                               
	  * Nombre Programador: Daisy Adriana Martínez Fernández                           
	  * Fecha: 18/Junio/2011                                      			
	  * Descripción: Este archivo permite consultar los Equipos de la Base de datos así como modificarlos si fuere necesario
	  **/
	 	
	//Función que permite mostrar los Equipos 
	function mostrarEquipos(){
		//Importamos archivo para realizar la conexion con la BD
		include_once("../../includes/conexion.inc");
		
		//Realizar la conexion a la BD de Laboratorio
		$conn = conecta("bd_Laboratorio");
	
		if(isset($_POST["cmb_marca"])){
			//Creamos la sentencia SQL
			$stm_sql ="SELECT * FROM equipo_lab WHERE marca='$_POST[cmb_marca]' AND estado=1 ORDER BY no_interno " ;
			
			//Variable que guarda el titulo de la tabla
			$titulo="Equipos Registrados con la Marca <u><em>".$_POST["cmb_marca"] ."</u></em>";
			
			//Variable que almacena el msj de error
			$error="No existen Equipos Registrados con la Marca <u><em>". $_POST["cmb_marca"]."</u></em>";
		}
		else{
			//Creamos la sentencia SQL
			$stm_sql ="SELECT * FROM equipo_lab WHERE no_interno='$_POST[txt_noInterno]' AND estado=1 ORDER BY no_interno ";
			
			//Variable que guarda el titulo de la tabla
			$titulo="Equipos Registrados con el N&uacute;mero Interno <u><em>".$_POST["txt_noInterno"]."</u></em>";
			
			//Variable que almacena el msj de error
			$error="No existen Equipos Registrados el N&uacute;mero Interno <u><em>". $_POST["txt_noInterno"]."</u></em>";
		}
			
					
		//Ejecutamos la sentencia SQL
		$rs = mysql_query($stm_sql);
		
		//Si la consulta trajo datos creamos la tabla para mostrarlos
		if($datos=mysql_fetch_array($rs)){						
			echo "								
			<table cellpadding='5' width='100%'>
				<caption class='titulo_etiqueta'>$titulo</caption>					
				<tr>
					<td  class='nombres_columnas'>SELECCIONAR</td>
					<td class='nombres_columnas'>NO INTERNO</td>
					<td class='nombres_columnas'>NOMBRE</td>
					<td class='nombres_columnas'>MARCA</td>
					<td class='nombres_columnas'>NO SERIE</td>
					<td class='nombres_columnas'>RESOLUCI&Oacute;N</td>
					<td class='nombres_columnas'>ESCALA</td>
					<td class='nombres_columnas'>EXACTITUD</td>
					<td class='nombres_columnas'>ENCARGADO</td>
					<td class='nombres_columnas'>APLICACI&Oacute;N</td>
					<td class='nombres_columnas'>CALIBRABLE</td>
			</tr>";		
			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{
				echo "<tr>
						<td class='nombres_filas' align='center'>
							<input type='radio' id='rdb_equipo' name='rdb_equipo' value='$datos[no_interno]'/>
						</td>		
						<td align='center' class='$nom_clase'>$datos[no_interno]</td>
						<td align='center' class='$nom_clase'>$datos[nombre]</td>
						<td align='center' class='$nom_clase'>$datos[marca]</td>
						<td align='center' class='$nom_clase'>$datos[no_serie]</td>
						<td align='center' class='$nom_clase'>$datos[resolucion]</td>					
						<td align='center' class='$nom_clase'>$datos[escala]</td>
						<td align='center' class='$nom_clase'>$datos[exactitud]</td>
						<td align='center' class='$nom_clase'>$datos[encargado]</td>
						<td align='center' class='$nom_clase'>$datos[aplicacion]</td>";
						if($datos['calibrable']== '1'){
							echo "<td align='center' class='$nom_clase'>SI</td>";
						}
						else{
							echo "<td align='center' class='$nom_clase'>NO</td>";
						}
				echo "</tr>";																		
					
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
	

	//Funcion que se encarga de modifica  el Equipo seleccionado
	function modificarEquipoSeleccionado(){
		//Conectar a la BD de Topografía
		$conn = conecta("bd_laboratorio");

		//Crear sentencia SQL
		$sql_stm ="SELECT * FROM equipo_lab  WHERE no_interno = '$_POST[rdb_equipo]'";

		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($sql_stm);

		//Confirmar que la consulta de datos fue realizada con exito.
		$datos=mysql_fetch_array($rs);?>
		<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
		<div class="titulo_barra" id="titulo-registrar">Modificar Equipo </div>
		<fieldset class="borde_seccion" id="tabla-agregarEquipo" name="tabla-agregarEquipo">
		<legend class="titulo_etiqueta">Ingrese la Informaci&oacute;n del Equipo </legend>	
		<br>
		<form  name="frm_modificarEquipo" method="post" action="frm_modificarEquipoLaboratorio.php" onsubmit="return valFormRegMod(this);">
		  <table  cellpadding="5" cellspacing="5" class="tabla_frm">
			<tr>
				<td width="120"><div align="right">*No Interno </div></td>
				<td>
					<input name="txt_noInterno" id="txt_noInterno" type="text" class="caja_de_texto" size="3" maxlength="3" 
					onkeypress="return permite(event,'num', 3);" value="<?php echo $datos['no_interno'];?>"/>
					<input type="hidden" name="hdn_idOriginal" id="hdn_idOriginal" value="<?php echo $datos['no_interno'];?>"/>
				</td>
				<td width="190"><div align="right">No Serie </div></td>
				<td>
					<input name="txt_noSerie" type="text" id="txt_noSerie" size="10" maxlength="15" onkeypress="return permite(event,'num_car', 1);"
					value="<?php echo $datos['no_serie'];?>"/>
				</td>
			</tr>
			<tr>
				<td><div align="right">Marca </div></td>
				<td>
					<input name="txt_marca" id="txt_marca" type="text" class="caja_de_texto" size="20" maxlength="30" 
					onkeypress="return permite(event,'num_car', 1);" value="<?php echo $datos['marca'];?>"/>
				</td>
				<td><div align="right">Resoluci&oacute;n </div></td>
				<td>
					<input name="txt_resolucion" id="txt_resolucion" type="text" class="caja_de_texto" size="20" maxlength="30" 
					onkeypress="return permite(event,'num_car', 1);" value="<?php echo $datos['resolucion'];?>"/>
				</td>
			</tr>
			<tr>
				<td><div align="right">*Instrumento</div></td>
				<td>
					<input name="txt_nomEquipo" id="txt_nomEquipo" type="text" class="caja_de_texto" size="40" maxlength="50"
					onkeypress="return permite(event,'num_car', 1);" value="<?php echo $datos['nombre'];?>"/>
				</td>
				<td><div align="right">Escala</div></td>
				<td>
					<input name="txt_escala" id="txt_escala" type="text" class="caja_de_texto" size="10" maxlength="15" 
					onkeypress="return permite(event,'num_car', 0);" value="<?php echo $datos['escala'];?>"/>
				</td>
			</tr>
			<tr>
				<td><div align="right">Exactitud</div></td>
				<td>
					<input name="txt_exactitud" id="txt_exactitud" type="text" class="caja_de_texto" size="10" maxlength="15" 
					onkeypress="return permite(event,'num_car', 0);" value="<?php echo $datos['exactitud'];?>"/>
				</td>
				<td><div align="right">*Asignado a </div></td>
				<td>
					<input name="txt_responsable" id="txt_responsable" type="text" class="caja_de_texto" size="30" maxlength="35" 
					onkeypress="return permite(event,'num_car', 2);" value="<?php echo $datos['encargado'];?>"/>
				</td>
			</tr>
			<tr>
				<td><div align="right">*Aplicaci&oacute;n</div></td>
				<td>
					<textarea name="txa_aplicacion" id="txa_aplicacion" maxlength="120" onkeyup="return ismaxlength(this)" class="caja_de_texto" rows="2" cols="30"
					onkeypress="return permite(event,'num_car', 0);" ><?php echo $datos['aplicacion'];?></textarea>         
				</td>
				<td><div align="right">*Calibrable</div></td>
				<td>
					<select name="cmb_calibrable" id="cmb_calibrable" size="1" class="combo_box">
						<option value="">Calibrable</option>
						<option <?php if($datos["calibrable"]==1) echo "selected='selected'";?> value="1">SI</option>
						<option <?php if($datos["calibrable"]==0) echo "selected='selected'";?>value="0">NO</option>
					</select>
				</td>
			</tr>
			<tr>
				<td colspan="3"><strong>* Los campos marcados con asterisco son <u>obligatorios</u></strong></td>
			</tr>
			<tr>
				<td colspan="4">
					<div align="center"> 
						<input name="sbt_guardar" type="submit" class="botones" id= "sbt_guardar" value="Guardar" title="Guardar Equipo"
						onMouseOver="window.status='';return true"/>
						<input type="hidden" name="hdn_noInterno" id="hdn_noInterno" value="<?php echo $datos['no_interno']?>"/>
						&nbsp;&nbsp;&nbsp;
						<input name="rst_limpiar" type="reset" class="botones"  value="Restablecer" title="Restablecer Formulario" onMouseOver="window.status='';return true"/> 
						&nbsp;&nbsp;&nbsp;
						<input name="btn_cancelar" type="button" class="botones" value="Cancelar" title="Regresar al Men&uacute; Equipo" 
						onmouseover="window.status='';return true" onclick="confirmarSalida('frm_modificarEquipoLaboratorio.php')" />
					</div>			
				</td>
			</tr>
		  </table>
		</form>
	</fieldset>	<?php
	}						
	
		
	//Verificamos que este definido el botón de guardar en el post
	 if (isset($_POST["sbt_guardar"])){
	 	guardarModificacionEquipo();
	 }
	 
	function guardarModificacionEquipo(){
		//Realizar la conexion a la BD de Laboratorio
		$conn = conecta("bd_laboratorio");	 			
		
		//Guardamos los datos que vienen del post para darles el tratamiento segun la BD
		$noInterno=strtoupper($_POST["txt_noInterno"]);
		$nomEquipo=strtoupper($_POST["txt_nomEquipo"]);
		$responsable=strtoupper($_POST["txt_responsable"]);
		$aplicacion=strtoupper($_POST["txa_aplicacion"]);
		//Poner tres guiones medios en caso de que no vengan definidos los datos en el POST
		if($_POST["txt_marca"]!="")
			$marca=strtoupper($_POST["txt_marca"]);
		else
			$marca="---";
		if($_POST["txt_noSerie"]!="")
			$noSerie=strtoupper($_POST["txt_noSerie"]);
		else
			$noSerie="---";
		if($_POST["txt_resolucion"]!="")
			$resolucion=strtoupper($_POST["txt_resolucion"]);
		else
			$resolucion="---";
		if($_POST["txt_escala"]!="")
			$escala=strtoupper($_POST["txt_escala"]);
		else
			$escala="---";
		if($_POST["txt_exactitud"]!="")
			$exactitud=strtoupper($_POST["txt_exactitud"]);
		else
			$exactitud="---";
			
		//ID original
		$id= $_POST['hdn_idOriginal'];	
						
		//Crear la sentencia para realizar el registro del nuevo Equipo en la BD de Laboratorio
		$stm_sql = "UPDATE equipo_lab SET no_interno='$noInterno', nombre='$nomEquipo', marca='$marca', no_serie='$noSerie', resolucion='$resolucion', 
						escala='$escala', exactitud='$exactitud',encargado='$responsable', aplicacion='$aplicacion', calibrable='$_POST[cmb_calibrable]' 
						WHERE no_interno='$id'";					
		
		//Ejecutar la sentencia previamente creada		
		$rs = mysql_query($stm_sql);									
									
		//Confirmar que la insercion de datos fue realizada con exito.
		if($rs){
			verificarDatos($id, $noInterno);
			registrarOperacion("bd_Laboratorio",$noInterno,"ModificarEquipo",$_SESSION['usr_reg']);
			echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
		}
		else{
			$error = mysql_error();
			echo "<meta http-equiv='refresh' content='7;url=error.php?err=$error'>";

		}	
		//Cerrar la conexion con la BD		
		//mysql_close($conn);
		//La Conexion a la BD se cierra en la funcion registrarOperacion();
	}	
	
	//Funcion que verifica  si exiten id iguales para tambien modificarlos
	function verificarDatos($idOriginal, $idNuevo){
		$conn= conecta('bd_laboratorio');
		$stm_sql="";
		
		//Comprobar tabla cronograma_actividades
		if(obtenerDato("bd_laboratorio","cronograma_servicios","equipo_lab_no_interno","equipo_lab_no_interno", $idOriginal)!=''){
			$stm_sql="UPDATE cronograma_servicios SET equipo_lab_no_interno='$idNuevo' WHERE equipo_lab_no_interno='$idOriginal'";
			$rs=(mysql_query($stm_sql));
		}
				
		mysql_close($conn);
	}//	FIN function verificarDatos($idOriginal, $idNuevo)
?>