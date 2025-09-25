<?php
	/**
	  * Nombre del Módulo: Laboratorio                                               
	  * Nombre Programador: Daisy Adriana Martínez Fernández
	  * Fecha: 23/Agosto/2011
	  * Descripción: Este archivo contiene funciones para almacenar la información relacionada con el formulario de Catalogo de Normas en la BD
	  **/
	 /**********************************************************************************************************************************************/
	 /********************************************************REGISTRAR CATALOGO DE NORMAS**********************************************************/
	 /**********************************************************************************************************************************************/
	 
	 //Verificar que el boton de Finalizar venga en el POST, de esta manera podemos mandar llamar a la funcion que 
	 //Realiza el registro de la norma
	 if (isset($_POST["sbt_finalizar"])){
	 	registrarNorma();
	 }
	//Verificamos que este definido en el post el boton de eliminar para llamar a la funcion que elimina el Registro
	if(isset($_POST["sbt_eliminar"])){
		eliminarRegistroSeguridad();
	}
	//Verificar que este definido en el post el boton de modificar para llamar a la funcion que modifica el registro
	if(isset($_POST["sbt_modificar"])){
		modificarNorma();
	}
	//Verificar que este definido en el post el boton de guardar para llamar a la funcion que modifica los registros
	if(isset($_POST["sbt_guardarModificacion"])){
		guardarModificarNorma();
	}
	
	
	//Funcion que permite registrar la norma asi como su detalle	
	function registrarNorma(){
		//Declaramos la variable bandera para control de la consulta
		$band=0;
		//Obtenemos el id y la fecha para la realizacion del registro
	 	$idCatalogo=obtenerIdNorma();
		$fecha=date("Y-m-d");
		$hora=date("H:i");
		//Obtenemos el Id del material para almacenarlo en la BD	
		$idMaterial=obtenerDato('bd_almacen', 'materiales', 'id_material','nom_material', $_POST["txt_agregado"]);
		//Conectamos con la BD
		$conn = conecta("bd_laboratorio");
		//Creamos la sentencia SQL para insertar los datos en la tabla Mecanico
		$stm_sql="INSERT INTO catalogo_normas (id_norma,catalogo_materiales_id_material, norma, fecha,hora)
		VALUES('$idCatalogo','$idMaterial','$_POST[txt_norma]', '$fecha', '$hora')";
					
		//Ejecutar la sentencia previamente creadas
		$rs = mysql_query($stm_sql);
		//Recorremos el arreglo delas normas para insertar en la BD los datos guardados en el mismo
		foreach($_SESSION['catNormas'] as $ind => $norma){
			$stm_sql_detalle="INSERT INTO detalle_catalogo_normas (catalogo_normas_id_norma,concepto, lim_inferior, lim_superior)
			VALUES('$idCatalogo','$norma[concepto]','$norma[limiteInf]', '$norma[limiteSup]')";
			//Ejecutar la sentencia previamente creadas
			$rs2 = mysql_query($stm_sql_detalle);
			if(!$rs2){
				$band=1;
			}						
			//Romper el proceso de registro del detalle de la entrada en el caso de que existan errores	
			if($band==1)
				break;	
		}
		//Si band existe en uno; hay un error por lo cual tenemos que enviar a la pantalla de error
		if ($band==1){
			$error = mysql_error();
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
		}
		else{
			//Registramos la operacion en la BD
			registrarOperacion("bd_laboratorio",$idCatalogo,"RegistroNormas",$_SESSION['usr_reg']);								
			echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
		}
		//Cerrar la conexion con la BD		
		//mysql_close($conn);
	}	//Fin de function registrarNorma()	
	
	
	//Esta funcion genera la Clave del de acuerdo a los registros en la BD
	function obtenerIdNorma(){
		//Realizar la conexion a la BD 
		$conn = conecta("bd_laboratorio");
		
		$id_cadena="";
		//Crear la sentencia para obtener la Clave reciente acorde al ultimo registro
		$stm_sql = "SELECT MAX(id_norma)+1 AS cant FROM catalogo_normas";
		$rs = mysql_query($stm_sql);
		if($datos=mysql_fetch_array($rs)){
			$cant=$datos["cant"];
			//Obtener las ultimas 3 cifras de la Bitacora Registrado en la BD y sumarle 1
			if($cant=="")
				$id_cadena=1;
			if($cant>0 && $cant<10)
				$id_cadena .= "00".$cant;
			if($cant>9 && $cant<100)
				$id_cadena .= "0".$cant;
			if($cant>=100)
				$id_cadena .= $cant;
		}
		
		//Cerrar la conexion con la BD		
		//mysql_close($conn);
		
		return $id_cadena;
	}//Fin de la Funcion obtenerId()
	
	
	//Desplegar los registros en la pantallad de registro de los conceptos
	function mostrarMateriales(){
		echo "<table cellpadding='5' width='100%'>";
		echo "<caption><p class='titulo_tabla'><strong>Registros Agregados </strong></p></caption>";
		echo "      			
			<tr>
				<td class='nombres_columnas' align='center'>NO.</td>
				<td class='nombres_columnas' align='center'>CONCEPTO</td>
				<td class='nombres_columnas' align='center'>NORMA</td>
				<td class='nombres_columnas' align='center'>AGREGADO</td>				
				<td class='nombres_columnas' align='center'>L&Iacute;MITE INFERIOR</td>
				<td class='nombres_columnas' align='center'>L&Iacute;MITE SUPERIOR</td>
				<td class='nombres_columnas' align='center'>BORRAR</td>
        	</tr>";
		$nom_clase = "renglon_gris";
		$cont = 1;
		$valor=0;
		$aux="";
		foreach ($_SESSION['catNormas'] as $ind => $norma) {
			echo "<tr>";
			foreach ($norma as $key => $value) {
				switch($key){
					case "concepto":
						echo "<td align='center'  class='nombres_filas'>$cont</td>";
						echo "<td align='center'  class='$nom_clase'>$value</td>";
					break;
					case "norma":
						echo "<td align='center'  class='$nom_clase'>$value</td>";
					break;
					case "agregado":
						echo "<td align='center'  class='$nom_clase'>$value</td>";
					break;
					
					case "limiteInf":
						echo "<td align='center' class='$nom_clase'>$value</td>";
					break;
					case "limiteSup":
						echo "<td align='center'  class='$nom_clase'>$value</td>";
						$count=count($_SESSION['catNormas']);
						if($cont==$count){?>
							<td class="<?php echo $nom_clase;?>" align="center"	><input type="image" src="../../images/borrar.png" width="30" height="25"
							border="0" title="Borrar Registro" 
							onclick="location.href='frm_registrarCatalogoNormas.php?noRegistro=<?php echo $valor;?>'"/>
							</td><?php 
							$valor=$aux;
						}
						else{?>
							<td class="<?php echo $nom_clase;?>" align="center"	><?php echo "N/A"; ?></td><?php 
						}
						$valor++;
					break;
					
				}				
			}
			//Determinar el color del siguiente renglon a dibujar
			$cont++;
			if($cont%2==0)
				$nom_clase = "renglon_blanco";
			else
				$nom_clase = "renglon_gris";
			echo "</tr>";			
		}
		echo "</table>";
	}//Fin de la funcion mostrarMateriales()	
	
	
	/**********************************************************************************************************************************************/
	 /********************************************************OPCIONES DEL CATALOGO DE NORMAS******************************************************/
	 /*********************************************************************************************************************************************/
	 
	 //Funcion que se encarga de desplegar las normas Registradas
	function mostrarNormas(){

		//Conectar a la BD de laboratorio
		$conn = conecta("bd_laboratorio");
		
		//Crear sentencia SQL
		$sql_stm ="SELECT id_norma,norma,concepto, lim_inferior, lim_superior, fecha, catalogo_materiales_id_material, hora  
		FROM ((bd_laboratorio.detalle_catalogo_normas JOIN bd_laboratorio.catalogo_normas ON catalogo_normas_id_norma=id_norma)
		JOIN bd_almacen.materiales ON bd_laboratorio.catalogo_normas.catalogo_materiales_id_material=bd_almacen.materiales.id_material)ORDER BY fecha ";	
				
		//Crear el mensaje que se mostrara en el titulo de la tabla
		$msg= "NORMAS Y CONCEPTOS REGISTRADOS";
		
		//Crear el Mensaje en caso de que la consulta no arroje ningún resultado
		$msg_error = "</br></br></br></br></br></br></br></br><label class='msje_correcto' align='center'>No se Encontr&oacute; Ninguna Norma Registrada</label>";	

		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($sql_stm);									
	
		//Confirmar que la consulta de datos fue realizada con exito.
		if($datos=mysql_fetch_array($rs)){
	
			//Desplegar los resultados de la consulta en una tabla
			echo "				
			<table cellpadding='5' width='100%'>				
				<tr>
					<td colspan='18' align='center' class='titulo_etiqueta'>$msg</td>
				</tr>
					
				<tr>
					<td class='nombres_columnas' align='center'>SELECCIONAR</td>
					<td class='nombres_columnas' align='center' >NORMA</td>
					<td class='nombres_columnas' align='center' >MATERIAL</td>
					<td class='nombres_columnas' align='center' >CONCEPTO</td>
					<td class='nombres_columnas' align='center' >L&Iacute;MITE INFERIOR</td>
					<td class='nombres_columnas' align='center' >L&Iacute;MITE SUPERIOR</td>
					<td class='nombres_columnas' align='center' >FECHA REGISTRO</td>	
					<td class='nombres_columnas' align='center' >HORA REGISTRO</td>					
				</tr>";

			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{	
				$nomMaterial=obtenerDato('bd_almacen', 'materiales', 'nom_material','id_material', $datos['catalogo_materiales_id_material']);
				//Mostrar todos los registros que han sido completados
				echo "
					<tr>
						<td class='nombres_filas' align='center'>
							<input type='radio' id='rdb_equipo' name='rdb_norma' value='$datos[id_norma]@$datos[concepto]'/>
						</td>
						<td class='$nom_clase' align='center'>$datos[norma]</td>
						<td class='$nom_clase' align='center'>$nomMaterial</td>
						<td class='$nom_clase' align='center'>$datos[concepto]</td>
						<td class='$nom_clase' align='center'>$datos[lim_inferior]</td>
						<td class='$nom_clase' align='center'>$datos[lim_superior]</td>
						<td class='$nom_clase' align='center'>".modFecha($datos['fecha'],1)."</td>	
						<td class='$nom_clase' align='center'>$datos[hora]</td>				
					</tr>";
					
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";				
			}while($datos=mysql_fetch_array($rs));
			//Fin de la tabla donde se muestran los resultados de la consulta
			echo "</table>";
			return 1;
			
		}// fin  if($datos=mysql_fetch_array($rs))
		else{//Si no se encuentra ningun resultado desplegar el mensaje de alerta notificando que no hay resultados disponibles
			echo $msg_error;		
			return 0;		
		}
	}//Fin mostrarNormas()
	
	
	
	//Función que permite eliminar el Registro segun sea seleccionado
	function eliminarRegistroSeguridad(){
		//Incluimos archivo de conexión
		include_once("../../includes/conexion.inc");
		
		//Realizar la conexion a la BD de Laboratorio	
		$conn = conecta("bd_laboratorio");
		
		//Recuperamos del Registro cargados en el radio button
		$datoNorma=explode("@",$_POST["rdb_norma"]);
		$idNorma=$datoNorma[0];
		$concepto=$datoNorma[1];
		
		//Contamos el numero de conceptos para saber si es necesario dar de baja el registro del catalogo de las normas
		$noConceptos = mysql_num_rows(mysql_query("SELECT * FROM detalle_catalogo_normas WHERE catalogo_normas_id_norma = '$idNorma'"));
		
		if($noConceptos==1){
			//Creamos la sentencia para eliminar el registro del catalogo de la norma
			$stm_sqlEliminar ="DELETE FROM catalogo_normas WHERE id_norma='$idNorma'";
			//Ejecutamos la consulta
			$rs2=mysql_query($stm_sqlEliminar);
		}
		//Creamos la conslulta SQL que permite eliminar el Equipo de la BD
		$stm_sql ="DELETE FROM detalle_catalogo_normas WHERE catalogo_normas_id_norma='$idNorma' AND concepto='$concepto'";	
		//Ejecutamos la consulta
		$rs=mysql_query($stm_sql);
		if($rs){
			//Registramos la operación en la bitacora de movimientos
			registrarOperacion("bd_laboratorio",$idNorma,"EliminoRegNorma",$_SESSION['usr_reg']);
			//Redireccionamos a la pantalla de éxito
			echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
		}
		else{
			//Si los datos no se agregaron correctamente, se redirecciona a la pagina de error
			$error = mysql_error();
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
		}	
		//Cerramos la conexion con la Base de Datos
		//mysql_close($conn);	
	} //Fin de function eliminarRegistroSeguridad()
	
	//Funcion que permite modificar el registro seleccionado
	function modificarNorma(){
		//Funcion que permite modificar las fechas
		include_once("../../includes/op_operacionesBD.php");
		//Conectar a la BD de laboratorio
		$conn = conecta("bd_laboratorio");
		
		
		//Recuperamos del Registro cargados en el radio button
		$datoNorma=explode("@",$_POST["rdb_norma"]);
		$idNorma=$datoNorma[0];
		$concepto=$datoNorma[1];
		
		//Crear sentencia SQL
		$sql_stm ="SELECT catalogo_normas_id_norma, concepto, lim_inferior, lim_superior, id_norma,catalogo_materiales_id_material, norma, fecha  
		FROM (detalle_catalogo_normas JOIN catalogo_normas ON catalogo_normas_id_norma=id_norma)  
		WHERE id_norma = '$idNorma'  AND concepto='$concepto'";

		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($sql_stm);

		//Confirmar que la consulta de datos fue realizada con exito.
		$datos=mysql_fetch_array($rs);
		$idMaterial= $datos['catalogo_materiales_id_material'];
		$nomMaterial=obtenerDato('bd_almacen', 'materiales', 'nom_material','id_material', $idMaterial);?>
		<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
   		<div class="titulo_barra" id="titulo-agregar">Modificar Catalogo de Normas </div>
	
		<fieldset class="borde_seccion" id="tabla-agregarNorma">
		<legend class="titulo_etiqueta">Ingrese Informaci&oacute;n</legend>	
		<br>
		<form name="frm_catalogoModificar"  id="frm_catalogoModificar" method="post" action="frm_opcionesCatalogoNormas.php" 
		onsubmit="return valFormModificarAgregados(this);">
		<table width="749" height="253" cellpadding="5" cellspacing="5" class="tabla_frm">
			<tr>
				<td height="31"><div align="right">*Norma</div></td>
				<td>
					<input name="txt_norma" id="txt_norma" type="text" class="caja_de_texto" size="40" maxlength="40" readonly="readonly" 
					value="<?php echo $datos['norma']; ?>"/>
				</td>
				<td><div align="right">*Agregado</div></td>
				<td>
					<input name="txt_agregado" id="txt_agregado" type="text" class="caja_de_texto" size="40" maxlength="40"  readonly="readonly" 
					value="<?php echo $nomMaterial; ?>"/>
					<input name="txt_noConcepto" id="txt_noConcepto" type="hidden" class="caja_de_texto" size="40" maxlength="40"  readonly="readonly" 
					value="<?php echo $datos['catalogo_normas_id_norma']; ?>"/>
				</td>
			</tr>
			<tr>
			 	<td height="31"><div align="right">*Concepto</div></td>
				<td><input name="txt_concepto" id="txt_concepto" type="text" class="caja_de_texto" size="40" maxlength="40" 
				onkeypress="return permite(event,'num_car', 0);" value="<?php echo $datos['concepto']; ?>"/></td>
				<td><div align="right">*L&iacute;mite Superior</div></td>
				<td>
					<input name="txt_limSup" id="txt_limSup" type="text" class="caja_de_texto" size="40" maxlength="40" 
					onkeypress="return permite(event,'num', 2);" onchange="formatCurrency(value,'txt_limSup')" value="<?php echo $datos['lim_superior'];?>"/>
				</td>
		 	</tr>
			<tr>
			<tr>
				<td height="31"><div align="right">*L&iacute;mite Inferior</div></td>
				<td>
					<input name="txt_limInf" id="txt_limInf" type="text" class="caja_de_texto" size="40" maxlength="40" 
					onkeypress="return permite(event,'num', 2);" onchange="formatCurrency(value,'txt_limInf')" value="<?php echo $datos['lim_inferior'];?>"/>
				</td>
			</tr>
			<tr><td height="42" colspan="4"><strong>* Los campos marcados con asterisco son <u>obligatorios</u>.</strong><br /></td></tr>
			<tr>
				<td colspan="6">
					<div align="center"> 
						<input type="hidden" name="hdn_conceptoOrg" id="hdn_conceptoOrg" value="<?php echo $datos['concepto']; ?>"/>
						<input name="sbt_guardarModificacion" type="submit" class="botones"  value="Guardar" title="Guardar Registro" 
						onMouseOver="window.status='';return true" />
						&nbsp;&nbsp;&nbsp;
						<input name="rst_limpiar" type="reset" class="botones"  value="Limpiar" title="Reestablecer Formulario" 
						onMouseOver="window.status='';return true"/>
						&nbsp;&nbsp;&nbsp;
						<input name="btn_cancelar" type="button" class="botones" value="Cancelar" title="Regresar al Men&uacute; de Pruebas" 
						onMouseOver="window.status='';return true" onclick="confirmarSalida('frm_opcionesCatalogoNormas.php?cancel')" />
					</div>			
				</td>
			</tr>
		</table>
		</form>
	</fieldset><?php
	}//Fin de function modificarNorma()
	
	//Funcion que permite guardar loa modificacion de la norma
	function guardarModificarNorma(){

		//Realizar la conexion a la BD de Laboratorio
		$conn = conecta("bd_laboratorio");	 			
		
		//Guardar los limites y quitar las comas para el correcto registro del mismo
		$concepto=strtoupper($_POST["txt_concepto"]);
		$limInf=str_replace(",","",$_POST["txt_limInf"]);
		$limSup=str_replace(",","",$_POST["txt_limSup"]);
		
		//Obtener el concepto original antes de ser modificado para poder relizar la consulta
		$conceptoOrg= $_POST["hdn_conceptoOrg"];
						
		//Crear la sentencia para realizar el registro del nuevo Equipo en la BD de Laboratorio
		$stm_sql = "UPDATE detalle_catalogo_normas SET concepto='$concepto', lim_inferior=$limInf, lim_superior=$limSup WHERE concepto='$conceptoOrg' 
		AND catalogo_normas_id_norma='$_POST[txt_noConcepto]'";					
		
		//Ejecutar la sentencia previamente creada		
		$rs = mysql_query($stm_sql);									
									
		//Confirmar que la insercion de datos fue realizada con exito.
		if($rs){
			registrarOperacion("bd_Laboratorio",$concepto,"ModNorma",$_SESSION['usr_reg']);
			echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
		}
		else{
			$error = mysql_error();
			echo "<meta http-equiv='refresh' content='7;url=error.php?err=$error'>";
		}	
	}//Fin guardarModificarNorma()	
	
?>