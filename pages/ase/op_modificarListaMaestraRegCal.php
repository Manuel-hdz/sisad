<?php
	/**
	  * Nombre del Módulo: Aseguramiento Calidad                                               
	  * Nombre Programador: Daisy Adriana Martínez Fernández                           
	  * Fecha: 09/Noviembre/2011                                      			
	  * Descripción: Este archivo permite mostrar y modificar los registros en la Base de datos
	  **/
	 	
	
	//Función que permite mostrar los documentos Registrados en las fechas especificadas
	function mostrarRegistrosLista(){
		//Importamos archivo para realizar la conexion con la BD
		include_once("../../includes/conexion.inc");
		
		//Incluimos archivo para modificar fechas
		include_once("../../includes/func_fechas.php");
		
		//Incluimos archivos para realizar las operaciones con la Base de datos (funcion ObtenerDato)
		include_once("../../includes/op_operacionesBD.php");
		
		//Realizar la conexion a la BD de Aseguramiento
		$conn = conecta("bd_aseguramiento");
		
		//Creamos la sentencia SQL
		$stm_sql ="SELECT *	FROM lista_maestra_registros_calidad ORDER BY codigo_forma";
					
		//Ejecutamos la sentencia SQL
		$rs = mysql_query($stm_sql);
		
		//Si la consulta trajo datos creamos la tabla para mostrarlos
		if($datos = mysql_fetch_array($rs)){		
			echo "				
				<table cellpadding='5' width='100%' id='tabla-resultados'> 
				<thead>
				";
			echo "	<tr>
						<th class='nombres_columnas' align='center'>SELECCIONAR</th>
						<th class='nombres_columnas' align='center'>C&Oacute;DIGO FORMATO </th>
						<th class='nombres_columnas' align='center'>DEPARTAMENTO EMISOR</th>
						<th class='nombres_columnas' align='center'>NO. REVISI&Oacute;N</th>
						<th class='nombres_columnas' align='center'>FECHA DE REVISI&Oacute;N</th>
						<th class='nombres_columnas' align='center'>TITILO</th>
						<th class='nombres_columnas' align='center'>ACCESIBLE</th>
						<th class='nombres_columnas' align='center'>M&Eacute;TODO DE COLECCI&Oacute;N</th>
						<td class='nombres_columnas' align='center'>INDEXACI&Oacute;N</td>
						<td class='nombres_columnas' align='center'>UBICACI&Oacute;N</td>
						<td class='nombres_columnas' align='center'>PERIODO MTTO</td>
						<td class='nombres_columnas' align='center'>DISPOSICI&Oacute;N FINAL</td>
						<td class='nombres_columnas' align='center'>DOC. ASOCIADOS</td>	
					</tr>
					</thead>";
			$nom_clase = "renglon_gris";
			$cont = 1;	
			echo "<tbody>";
			do{		
				//Obtenemos para quien tiene acceso la lista maestra de registros de calidad
				$accesible = obtenerDato("bd_aseguramiento", "catalogo_acceso", "acceso", "id_acceso", $datos['catalogo_acceso_id_acceso']);		
				echo "	<tr>
							<td class='$nom_clase' align='center'>
								<input type='radio' id='rdb_id' name='rdb_id' value='$datos[id_registro]'/>
							</td>				
							<td class='$nom_clase' align='center'>$datos[codigo_forma]</td>
							<td class='$nom_clase' align='center'>$datos[dpto_emisor]</td>						
							<td class='$nom_clase' align='left'>$datos[no_rev]</td>
							<td class='$nom_clase' align='center'>".modFecha($datos['fecha_revision'],1)."</td>
							<td class='$nom_clase' align='center'>$datos[titulo]</td>
							<td class='$nom_clase' align='center'>$accesible</td>
							<td class='$nom_clase' align='center'>$datos[metodo_coleccion]</td>
							<td class='$nom_clase' align='left'>$datos[indexacion]</td>
							<td class='$nom_clase' align='left'>$datos[ubicacion]</td>
							<td class='$nom_clase' align='left'>$datos[periodo_mantenimiento]</td>
							<td class='$nom_clase' align='left'>$datos[disposicion_final]</td>
							<td class='$nom_clase' align='left'>$datos[doc_aso]</td>
						</tr>";			
				//Determinar el color del siguiente renglon a dibujar
				$cont++;	
			}while($datos=mysql_fetch_array($rs)); 	
			echo "</tbody>";
			echo "</table>";?>	
			<input name="hdn_consulta" type="hidden" value="<?php echo $stm_sql; ?>"/>
			<input name="hdn_nomReporte" type="hidden" value="Reporte Lista MaestraRegCal_<?php echo date("d_m_Y");?>"/>
			<input name="hdn_origen" type="hidden" value="reporteListaMaestra"/><?php 
			return 1;
		}
		else{
			//Si no se encuentra ningun resultado desplegar un mensaje					
			echo "<label class='msje_correcto'> No Existen Registro en Lista Maestra de Registros de Calidad</label>";
			return 0;
		}?>							
		<?php
		//Cerrar la conexion con la BD
		mysql_close($conn);
	}//Cierre de la funcion
	
	//Funcion que se encarga de modifica  el Registro seleccionado
	function modificarRegistroSeleccionado(){
		//Incluimos archivos para realizar las operaciones con la Base de datos (funcion ObtenerDato)
		include_once("../../includes/op_operacionesBD.php");
		
		//Conectar a la BD de Aseguramiento
		$conn = conecta("bd_aseguramiento");

		//Crear sentencia SQL
		$sql_stm ="SELECT * FROM lista_maestra_registros_calidad WHERE id_registro = '$_POST[rdb_id]'";

		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($sql_stm);

		//Confirmar que la consulta de datos fue realizada con exito.
		$datos=mysql_fetch_array($rs);?>
		<fieldset class="borde_seccion" id="tabla-modificarRegistro" name="tabla-modificarRegistro">
		<legend class="titulo_etiqueta">Ingrese la Informaci&oacute;n del Registro </legend>	
		<br>
	
		<form onsubmit="return valFormLista(this);"name="frm_agregarRegistro" id="frm_agregarRegistro" method="post" action="op_modificarListaMaestraRegCal.php">
	  	<table width="764" height="358"  cellpadding="5" cellspacing="5" class="tabla_frm">
        	<tr>
          		<td width="131" height="31"><div align="right">*Departamento Emisor </div></td>
         		<td width="212">
		  		<?php  
					$cmb_depto=$datos['dpto_emisor'];
					$conn = conecta("bd_usuarios");
					$result=mysql_query("SELECT DISTINCT UPPER (depto) as depto FROM usuarios WHERE depto != 'PANEL' ORDER BY depto");
					if($depto=mysql_fetch_array($result)){?>
					  <select name="cmb_depto" id="cmb_depto" size="1" class="combo_box">
						<option value="">Departamento</option>
						<?php 
						  do{
								if ($depto['depto'] == $cmb_depto){
									echo "<option value='$depto[depto]' selected='selected'>$depto[depto]</option>";
								}
								else{
									echo "<option value='$depto[depto]'>$depto[depto]</option>";
								}
							}while($depto=mysql_fetch_array($result)); 
						//Cerrar la conexion con la BD		
						mysql_close($conn);?>
					  </select>
					  <?php }
						else{
							echo "<label class='msje_correcto'> No hay Departamentos Registrados</label>
							<input type='hidden' name='cmb_depto' id='cmb_depto'/>";?>
					  <?php }?>          
	  	 		</td>
          		<td><div align="right">*Indexaci&oacute;n </div></td>
         		<td width="197"><input name="txt_indexacion" id="txt_indexacion" type="text" class="caja_de_texto" size="30" onkeypress="return permite(event,'num_car', 1);" value="<?php echo $datos['indexacion'];?>"/></td>
        	</tr>
        	<tr>
          		<td><div align="right">*C&oacute;digo Formato </div></td>
          		<td><input name="txt_noFormato" id="txt_noFormato" type="text" class="caja_de_texto" size="15" onkeypress="return permite(event,'num_car', 1);" value="<?php echo $datos['codigo_forma'];?>"/></td>
          		<td width="157"><div align="right">*Periodo Mantenimiento</div></td>
          		<td width="197"><input name="txt_perMtto" id="txt_perMtto" type="text" class="caja_de_texto" size="30" onkeypress="return permite(event,'num_car', 1);" value="<?php echo $datos['periodo_mantenimiento'];?>"/></td>
        	</tr>
        	<tr>
          		<td><div align="right">*No. de Revisi&oacute;n </div></td>
          		<td><input name="txt_noRevision" id="txt_noRevision" type="text" class="caja_de_texto" size="10" onkeypress="return permite(event,'num', 2);" value="<?php echo $datos['no_rev'];?>"/></td>
         		<td><div align="right">*Disposici&oacute;n Final </div></td>
          		<td><input name="txt_dispFinal" id="txt_dispFinal" type="text" class="caja_de_texto" size="20" onkeypress="return permite(event,'num_car', 1);" value="<?php echo $datos['disposicion_final'];?>"/></td>
        	</tr>
        	<tr>
          		<td><div align="right">*Fecha</div></td>
          		<td><input name="txt_fecha" type="text" id="txt_fecha" size="10" maxlength="15"  readonly="readonly" value="<?php echo modFecha($datos['fecha_revision'],1);?>"/></td>
          		<td><div align="right">*Documentos Asociados </div></td>
          		<td><input name="txt_docAso" id="txt_docAso" type="text" class="caja_de_texto" size="30" onkeypress="return permite(event,'num_car', 1);" value="<?php echo $datos['doc_aso'];?>"/></td>
        	</tr>
			<tr>
		  		<td height="43"><div align="right">*T&iacute;tulo</div></td>
		  		<td><textarea name="txa_titulo" id="txa_titulo" maxlength="120" onkeyup="return ismaxlength(this)" class="caja_de_texto" rows="2" cols="30" onkeypress="return permite(event,'num_car', 0);"><?php echo $datos['titulo'];?></textarea>  </td>
				<td><div align="right"><div align="right">*M&eacute;todo de Colecci&oacute;n </div></div></td>
          		<td>
					<textarea name="txa_metColeccion" id="txa_metColeccion" maxlength="120" onkeyup="return ismaxlength(this)" class="caja_de_texto" rows="2" cols="30"
					onkeypress="return permite(event,'num_car', 0);"><?php echo $datos['metodo_coleccion'];?></textarea>
				</td>
       		</tr>
			<tr>
				<td><div align="right">*Accesible a </div></td>
          		<td>
					<?php  
					$accesoOri=obtenerDato("bd_aseguramiento", "catalogo_acceso", "acceso", "id_acceso", $datos['catalogo_acceso_id_acceso']);
					$cmb_acceso=$accesoOri;
					$conn = conecta("bd_aseguramiento");
					$result=mysql_query("SELECT DISTINCT acceso FROM catalogo_acceso ORDER BY acceso");
					if($acceso=mysql_fetch_array($result)){?>
					  <select name="cmb_acceso" id="cmb_acceso" size="1" class="combo_box">
						<option value="">Acceso</option>
						<?php 
						  do{
								if ($acceso['acceso'] == $cmb_acceso){
									echo "<option value='$acceso[acceso]' selected='selected'>$acceso[acceso]</option>";
								}
								else{
									echo "<option value='$acceso[acceso]'>$acceso[acceso]</option>";
								}
							}while($acceso=mysql_fetch_array($result)); 
						//Cerrar la conexion con la BD		
						mysql_close($conn);?>
					  </select>
					  <?php }
						else{
							echo "<label class='msje_correcto'> No hay Accesos Registrados</label>
							<input type='hidden' name='cmb_acceso' id='cmb_acceso'/>";?>
					  <?php }?>          
				</td>
			  	<td><div align="right">
					<input type="checkbox" name="ckb_acceso" id="ckb_acceso" onclick="agregarNuevoAcceso(this, 'txt_acceso', 'cmb_acceso'); " title="Seleccione para Escribir el Nombre de una Ubicaci&oacute;n que no Exista" />
	           		Agregar Acceso </div>
				</td>
				<td><input name="txt_acceso" id="txt_acceso" type="text" class="caja_de_texto" size="20" readonly="readonly"/></td>
			</tr>
			<tr>
				<td><div align="right">Ubicaci&oacute;n</div></td>
          		<td>
					<input name="txt_ubicacion" id="txt_ubicacion" type="text" class="caja_de_texto" size="40" readonly="readonly" 
					onclick="window.open('verDepartamentos.php','_blank','top=50, left=50, width=380, height=500, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');" value="<?php echo $datos['ubicacion'];?>" title="De Click Sobre La Caja De Texto Para Agregar Departamentos"/>
				</td>
			</tr>
        	<tr>
          		<td colspan="3"><strong>* Los campos marcados con asterisco son <u>obligatorios</u></strong></td>
        	</tr>
        	<tr>
          		<td colspan="4"><div align="center">
              		<input name="sbt_guardarMod" type="submit" class="botones" id= "sbt_guardarMod" value="Modificar" title="Guardar Registro De Lista Maestra" 	onmouseover="window.status='';return true"/>
		            &nbsp;&nbsp;&nbsp;
        		    <input name="rst_limpiar" type="reset" class="botones"  value="Restablecer" title="Restablecer Formulario" onmouseover="window.status='';return true"/>
            		&nbsp;&nbsp;&nbsp;
            		<input name="btn_cancelar" type="button" class="botones" value="Cancelar" title="Regresar al Men&uacute; Repositorio" onmouseover="window.status='';return true"  onclick="confirmarSalida('frm_modificarListaMaestraRegCal.php')" />
					<input type="hidden" name="hdn_claveValida" id="hdn_claveValida" value="si" />
					<input type="hidden" name="rdb_id" id="rdb_id" value="<?php echo $_POST['rdb_id'];?>" />
          		</div></td>
        	</tr>
      </table>
		</form>
		</fieldset>
		<div id="calendario">
			<input name="calendario" type="image" id="calendario2" onclick="displayCalendar (document.frm_agregarRegistro.txt_fecha,'dd/mm/yyyy',this)" 
			onmouseover="window.status='';return true" src="../../images/calendar.png"  title="Seleccione una fecha" align="absbottom" width="25" height="25" border="0" />
		</div>
		<?php
	}						
	
		
	//Verificamos que este definido el botón de guardar en el post
	 if (isset($_POST["sbt_guardarMod"])){
		guardarModificacionRegistro();
	 }
	 
	 
	//Esta funcion permite registrar los Archivos en la BD
	function guardarModificacionRegistro(){
		//Iniciamos la sesion; ya que el archivo FRM es el que contiene la sesion iniciada y para esta funcion no se depende de el
		session_start();
		//Importamos archivo para realizar la conexion con la BD
		include_once("../../includes/conexion.inc");
		//Incluimos archivo para modificar fechas segun sea requerido	
		include_once("../../includes/func_fechas.php");
		//Incluimos archivos para realizar las operaciones con la Base de datos (funcion ObtenerDato)
		include_once("../../includes/op_operacionesBD.php");
		
		//Realizar la conexion a la BD de Aseguramiento Calidad
		$conn = conecta("bd_aseguramiento");
		
		//Declaramos $band para verificar si hubo errores	
		$band=0;
		//Declaramos $band para verificar si hubo errores	
		$band=0;
		//Declaramos variables para insercion de valores en la BD 
		$id_acceso="";
		
		//Verificamos que el combo acceso este definido y que no venga vacio. Si es asi recuperar el id correspondiente para almacenarlo en la BD
		if(isset($_POST["cmb_acceso"])&&$_POST["cmb_acceso"]!=""){
			$acceso = $_POST["cmb_acceso"];
			//Obtenemos el id de la norma que se encuentra almacenado en la BD para realizar la inserción
			$id_acceso=obtenerDato("bd_aseguramiento", "catalogo_acceso", "id_acceso", "acceso", $_POST["cmb_acceso"]);
		}
		
		//Verificamos que se ecuentre definida la caja de texto txt_acceso
		if(isset($_POST["txt_acceso"])&&$_POST["txt_acceso"]!=""){
		$id_acceso=obtenerDato("bd_aseguramiento", "catalogo_acceso", "id_acceso", "acceso", $_POST["txt_acceso"]);
			if($id_acceso==""){
				//Obtenemos el id del acceso para realizar la insercion en la BD
				$id_acceso = obtenerIdAcceso();
				$stm_sql2 = "INSERT INTO catalogo_acceso(id_acceso,acceso) VALUES('$id_acceso','$_POST[txt_acceso]')";
				//Ejecutar la sentencia previamente creada
				$rs2 = mysql_query($stm_sql2);
				$acceso=$_POST['txt_acceso'];
			}
		}
		
		//Creamos las variables para realizar los cambios en las mismas y permitir el correcto almacenamiento en la BD
		$fecha=modFecha($_POST["txt_fecha"],3);
		$depto=$_POST["cmb_depto"];
		$indexacion=strtoupper($_POST["txt_indexacion"]);
		$no_formato=strtoupper($_POST["txt_noFormato"]);
		$perMtto=strtoupper($_POST["txt_perMtto"]);
		$noRevision=$_POST["txt_noRevision"];
		$dispFinal=strtoupper($_POST["txt_dispFinal"]);
		$docAso=strtoupper($_POST["txt_docAso"]);
		$titulo=strtoupper($_POST["txa_titulo"]);
		$metColeccion=strtoupper($_POST["txa_metColeccion"]);
		$ubicacion=strtoupper($_POST["txt_ubicacion"]);
		$cveRegistro=$_POST['rdb_id'];
		
			//Crear la sentencia para realizar el registro de los datos
		 	$stm_sql = "UPDATE lista_maestra_registros_calidad SET catalogo_acceso_id_acceso='$id_acceso',  dpto_emisor='$depto', codigo_forma='$no_formato',
						no_rev='$noRevision', fecha_revision='$fecha', titulo='$titulo', ubicacion='$ubicacion', metodo_coleccion='$metColeccion'
						,indexacion='$indexacion', periodo_mantenimiento='$perMtto', disposicion_final='$dispFinal', doc_aso='$docAso' WHERE
						id_registro='$cveRegistro'";
						
			//Ejecutar la sentencia previamente creada 
			$rs = mysql_query($stm_sql);
			if(!$rs)
				$band = 1;						
		if ($band==1){
			$error = mysql_error();
			echo "<meta http-equiv='refresh' content='5;url=error.php?err=$error'>";;
		}
		else{
			//Registrar la Operacion en la Bitácora de Movimientos
			registrarOperacion("bd_aseguramiento",$cveRegistro,"ModListMaeRegCal",$_SESSION['usr_reg']);
			//Redireccionar a la pantalla de Exito
			echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
		}
		//Cerrar la conexion con la BD		
		//mysql_close($conn);
	}	
	
	//Funcion que permite obtener el id de Acceso
	function obtenerIdAcceso(){
		//Realizar la conexion a la BD 
		$conn = conecta("bd_aseguramiento");
		
		$id_cadena="";
		//Crear la sentencia para obtener la Clave reciente acorde al ultimo registro
		$stm_sql = "SELECT MAX(id_acceso)+1 AS cant FROM catalogo_acceso";
		$rs = mysql_query($stm_sql);
		if($datos=mysql_fetch_array($rs)){
			$cant=$datos["cant"];
			if($cant==NULL)
				$id_cadena=1;
			else
				$id_cadena = $datos[0];
		}
		
		//Cerrar la conexion con la BD		
		//mysql_close($conn);
		
		return $id_cadena;
	}//Fin de la Funcion obtenerId()
?>