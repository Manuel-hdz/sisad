<?php
	function mostrarCuadrilla($rs,$datos){
		$id_cc = $datos['id_control_costos'];
		$nom_clase = "renglon_gris";
		$cont = 1;
		?>
		<table width="100%"  cellpadding="5" cellspacing="5" class="tabla_frm">
			<tr>
				<td class='nombres_columnas' align='center'>EMPLEADO</td>
        		<td class='nombres_columnas' align='center'>PUESTO</td>
				<td class='nombres_columnas' align='center'>AVANCE</td>
			    <td class='nombres_columnas' align='center'>COMENTARIOS</td>
			    <td class='nombres_columnas' align='center'>TIPO</td>
      		</tr>
			<?php
			do{
				echo "
				<tr>
					<td class='$nom_clase' align='center'>";
						?>
						<input type="text" name="txt_integrante<?php echo $cont; ?>" id="txt_integrante<?php echo $cont; ?>" class="caja_de_texto" required="required" size="40" autocomplete="off"
						value="<?php echo $datos['nombre_emp']; ?>" onkeyup="lookup(this,'empleados','<?php echo $cont; ?>','hdn_rfc<?php echo $cont; ?>','<?php echo $id_cc; ?>');"/>
						<div id="res-spider<?php echo $cont; ?>" style="position:absolute; z-index:19;">
							<div align="left" class="suggestionsBox" id="suggestions<?php echo $cont; ?>" style="display: none;">
								<img src="../../images/upArrow.png" style="position: relative; top: -12px; left: 10px;" alt="upArrow" />
								<div class="suggestionList" id="autoSuggestionsList<?php echo $cont; ?>">&nbsp;</div>
							</div>
						</div>
						<input type="hidden" name="hdn_rfc<?php echo $cont; ?>" id="hdn_rfc<?php echo $cont; ?>" value="<?php echo $datos["rfc_trabajador"]; ?>"/>
						<input type="hidden" name="hdn_rfc_actual<?php echo $cont; ?>" id="hdn_rfc_actual<?php echo $cont; ?>" value="<?php echo $datos["rfc_trabajador"]; ?>"/>
					<?php
				echo "	
					</td>
					<td class='$nom_clase' align='center'>
						<input type='text' name='txt_puesto$cont' id='txt_puesto$cont' class='caja_de_texto' value='$datos[puesto]' readonly='readonly' size='14'/>
					</td>
					<td class='$nom_clase' align='center'>";
						?>
						<input name="txt_cantidad<?php echo $cont; ?>" id="txt_cantidad<?php echo $cont; ?>" type="text" class="caja_de_num" maxlength="6" value="<?php echo $datos["avance"]; ?>"
						onkeypress="return permite(event,'num', 0);" size="7" onchange="formatCurrency(this.value,'txt_cantidad<?php echo $cont; ?>');" required="required" autocomplete="off"/>
						<?php
					echo "
					</td>
					<td class='$nom_clase' align='center'>
						<textarea name='txa_comentarios$cont' cols='50' rows='3' class='caja_de_texto' id='txa_comentarios$cont' maxlength='120' style='resize: none;' placeholder='INTRODUCIR AQUI COMENTARIOS'>$datos[comentarios]</textarea>
					</td>
					<td class='$nom_clase' align='center'>";
					?>
						<input type='radio' name='chktipo<?php echo $cont; ?>' id='chktipo<?php echo $cont; ?>' value='ZARPEO'
						<?php if($datos["tipo"] == "ZARPEO")echo "checked='checked'" ?>/><br>ZARPEO
						<input type='radio' name='chktipo<?php echo $cont; ?>' id='chktipo<?php echo $cont; ?>' value='PISO'
						<?php if($datos["tipo"] == "PISO")echo "checked='checked'" ?>/><br>PISO
					</td>
				</tr>
				<?php
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
			}while($datos = mysql_fetch_array($rs));
			?>
		</table>
		<?php
		$num_reg = $cont - 1;
		return $num_reg;
	}
	
	function actualizarBitacora(){
		$id_bit = $_POST["rdb_idBitacora"];
		$fecha = modFecha($_POST["txt_fechaRegistro"],3);
		$conn = conecta("bd_gerencia");
		mysql_query("UPDATE  bitacora SET  fecha =  '$fecha' WHERE  id_bitacora =  '$id_bit'");
		
		$num_registros = $_POST["num_registros"];
		$correcto = true;
		for($i = 1; $i <= $num_registros; $i++){
			$empleado = $_POST["txt_integrante".$i];
			$rfc_nuevo = $_POST["hdn_rfc".$i];
			$rfc_antiguo = $_POST["hdn_rfc_actual".$i];
			$puesto = $_POST["txt_puesto".$i];
			$cantidad = str_replace(",","",$_POST["txt_cantidad".$i]);
			$comentarios = strtoupper($_POST["txa_comentarios".$i]);
			$tipo = $_POST["chktipo".$i];
			
			$stm_sql = "UPDATE detalle_bitacora SET 
							rfc_trabajador = '$rfc_nuevo',
							nombre_emp = '$empleado',
							puesto = '$puesto',
							avance = '$cantidad',
							comentarios = '$comentarios',
							tipo = '$tipo' 
						WHERE rfc_trabajador = '$rfc_antiguo'
						AND puesto = '$puesto' 
						AND id_bitacora = '$id_bit'";
			$rs = mysql_query($stm_sql);
			if(!$rs)
				$correcto = false;
		}
		if($correcto){
			registrarOperacion("bd_gerencia",$id_bit,"ModBitacoraZarp",$_SESSION['usr_reg']);
			?>
			<script>
				setTimeout("alert('SE REALIZARON LAS MODIFICACIONES CORRECTAMENTE');",1000);
			</script>
			<?php
		} else {
			?>
			<script>
				setTimeout("alert('HUBO PROBLEMAS AL MOMENTO DE REALIZAR LAS MODIFICACIONES EN LA BITACORA');",1000);
			</script>
			<?php
		}
	}
	
	//Funcion que guarda los registros de la bitacora de de zarpeo
	function actualizarRegLanzamientoBit(){				
		
		//Recuperar los valores de los elementos enviados en el post	
		$idCuadrilla = $_POST['txt_cuadrillas'];
		$aplicación = $_POST['cmb_aplicacionLanzamientos'];
		
		$nomEmpleado = "";		
		if(isset($_POST['cmb_aplicadorLanzamiento']))
			$nomEmpleado = $_POST['cmb_aplicadorLanzamiento'];
		else 	
			$nomEmpleado = strtoupper($_POST['txt_nomSuplente']);								
		
		$cantidad = str_replace(",","",$_POST['txt_cantidad']);
		$comentarios = strtoupper($_POST['txa_comentarios']);
		$idBitacora = $_POST['hdn_idBitacora'];
							
		//Conectar se a la Base de Datos
		$conn = conecta("bd_gerencia");													
		
		//Obtener el puesto de la tabla de Integrantes Cuadrillas
		$puesto = "";
		$datos_puesto = mysql_fetch_array(mysql_query("SELECT puesto FROM integrantes_cuadrilla WHERE cuadrillas_id_cuadrillas = '$idCuadrilla' AND nom_trabajador = '$nomEmpleado'"));
		$puesto = $datos_puesto['puesto'];
		
		//Crear la Sentencia SQL para actualizar el registro de la bitracora de zarpeo
		$stm_sql = "UPDATE bitacora_zarpeo SET aplicacion='$aplicación',realizado='$nomEmpleado',cantidad=$cantidad,comentarios='$comentarios' 
					WHERE cuadrillas_id_cuadrillas = '$idCuadrilla' AND bitacora_id_bitacora = '$idBitacora'";
		
		//Ejecutar la Sentencia
		$rs = mysql_query($stm_sql);
		
		//Verificar Resultado
		if ($rs){
			//Antes de registrar la Operación, verificar si cambio la persona que hizo el Lanzamiento y actualizar el registro en la tabla de Cuadrilas Zarpeo
			actualizarCuadrillasZarpeo($idBitacora,$idCuadrilla,$nomEmpleado,$puesto);
			
			//Guardar la operacion realizada
			registrarOperacion("bd_gerencia",$idBitacora,"ModRegBitacoraZarp",$_SESSION['usr_reg']);
			$conn = conecta("bd_gerencia");
			echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
		}
			
		else{
			$error = mysql_error();
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
		}
		
	}// Fin function actualizarRegLanzamientoBit()	
	 
	
	/*Esta funcion actualiza los registro de la Tabla de Cuadrillas Zarpeo cuando se realizan cambios en la Bitacora de Zarpeo*/
	function actualizarCuadrillasZarpeo($idBitacora,$idCuadrilla,$nomEmpleado,$puesto){
		
		//Reconectar se a la Base de Datos
		$conn = conecta("bd_gerencia");	
		
		//Ejecutar la Consulta para saber si es necesario actualizar el registro
		$rs_cuadrilla = mysql_query("SELECT * FROM cuadrillas_zarpeo WHERE id_cuadrilla='$idCuadrilla' AND id_bitacora=$idBitacora AND puesto='$puesto'");
		
		//Si esta registrado el puesto, verificamos si es la misma persona
		if($datosCuadrilla=mysql_fetch_array($rs_cuadrilla)){
			//En el caso que no se trate de la misma persona en el puesto indicado, procedemos a actualizar el registro
			if($datosCuadrilla['nom_empleado']!=$nomEmpleado){
				mysql_query("UPDATE cuadrillas_zarpeo SET nom_empleado='$nomEmpleado' WHERE id_cuadrilla='$idCuadrilla' AND id_bitacora=$idBitacora AND puesto='$puesto'");
			}
						
		}
		//Si no existe el puesto que estamos buscando, significa que se debe ser agregada la persona con el puesto a la cuadrilla y bitacora indicados
		else{
			mysql_query("INSERT INTO cuadrillas_zarpeo (id_cuadrilla,id_bitacora,nom_empleado,puesto) VALUES('$idCuadrilla', $idBitacora, '$nomEmpleado', 'SUPLENTE')");
		}				
	
	}//Cierre de la funcion actualizarCuadrillasZarpeo($idbitacora,$idCuadrilla,$nomEmpleado,$puesto) 
	 
	 
	//******************************************************************************************************//
	//*****************************  ACTAULIZAR LOS DATOS DE BITACORA DE TRANSPORTE  ***********************//
	//******************************************************************************************************//
	
	//Funcion que guarda los registros de la bitacora de de transporte
	function actualizarRegBitTrans(){
	
		//Recuperar los valores de los elementos enviados en el post
		$nombre= $_POST['txt_nombre'];
		$cantidad= str_replace(",","",$_POST['txt_cantidad']);
		$cargo= $_POST['cmb_choferSup'];
		$comentarios= strtoupper($_POST['txa_comentarios']);
		$idBitacora= $_POST['hdn_idBitacora'];
	
		//Conectar se a la Base de Datos
		$conn = conecta("bd_gerencia");
		
		//Crear la Sentencia SQL para actualizar el registro de la bitracora de transporte
		$stm_sql= "UPDATE bitacora_transporte SET nombre='$nombre',puesto='$cargo',cantidad='$cantidad',comentarios='$comentarios' 
		WHERE id_bitacora_transporte='$idBitacora'";
		
		//Ejecutar la Sentencia
		$rs=mysql_query($stm_sql);
		
		//Verificar Resultado
		if ($rs){
			//Guardar la operacion realizada
			registrarOperacion("bd_gerencia",$idBitacora,"ModRegBitacoraTransp",$_SESSION['usr_reg']);
			$conn = conecta("bd_gerencia");
			echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
		}
			
		else{
			$error = mysql_error();
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
		}
	 }// Fin function actualizarRegBitTrans()	
?>