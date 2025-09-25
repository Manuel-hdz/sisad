<?php
	/**
	  * Nombre del Módulo: Desarrollo                                               
	  * Nombre Programador: Miguel Angel Garay Castro
	  * Fecha: 22/Febrero/2012
	  * Descripción: Este archivo contiene las funciones para realizar las operaciones de Modificación en la Bitacora de Avance
	  **/ 
	  
	  
	/*Esta función muestra los registros de la bitácora de Avance disponibles en el Rango de Fechas seleccionado*/ 
	function verRegistrosBitAvance(){
		//Conectarse a la BD de Desarrallo
		$conn = conecta("bd_desarrollo");
		
		//Recuperar las fechas para realizar la consulta de los registros de la Bitácora de Avance
		$fechaIni = modFecha($_POST['txt_fechaIni'],3);
		$fechaFin = modFecha($_POST['txt_fechaFin'],3);
		
		//Crear la Sentencia SQL
		$sql_stm = "SELECT * FROM bitacora_avance WHERE fecha_registro BETWEEN '$fechaIni' AND '$fechaFin' ORDER BY fecha_registro";
		//Ejecutar la Sentencia y obtener los datos
		$rs = mysql_query($sql_stm);
		
		//Verificar si la consulta obtuvo registros para ser mostrados
		if($datos=mysql_fetch_array($rs)){
			//Colocar la definición de la Tabla, la Definicion del Formulario esta en el Archivo frm_modAvance.php?>			
			<table width="150%" cellpadding="5" class="tabla_frm">			
				<caption class="titulo_etiqueta"><?php echo "Registros Disponibles en el Periodo del ".$_POST['txt_fechaIni']." al ".$_POST['txt_fechaFin'];?></caption>
				<tr>
					<td rowspan="2" class="nombres_columnas">EDITAR</td>
					<td rowspan="2" class="nombres_columnas">ID BITACORA</td>
					<td rowspan="2" class="nombres_columnas">UBICACION</td>
					<td rowspan="2" class="nombres_columnas">FECHA REGISTRO</td>
					<td rowspan="2" class="nombres_columnas">MACHOTE</td>
					<td rowspan="2" class="nombres_columnas">MEDIDA</td>
					<td rowspan="2" class="nombres_columnas">AVANCE</td>
					<td rowspan="2" class="nombres_columnas">OBSERVACIONES</td>
					<td colspan="4" class="nombres_columnas">REGISTRO DE BITACORAS</td>
				</tr>
				<tr>
					<td class="nombres_columnas">BARRENACION JUMBO</td>
					<td class="nombres_columnas">BARRENACION MAQ. PIERNA</td>
					<td class="nombres_columnas">VOLADURA</td>
					<td class="nombres_columnas">REZAGADO</td>
				</tr><?php
			
			//Control del estilo de los renglones de la tabla
			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{
				//Obtener el Nombre de la Ubicacion
				$ubicacion = obtenerDato("bd_desarrollo", "catalogo_ubicaciones", "obra", "id_ubicacion", $datos['catalogo_ubicaciones_id_ubicacion']);?>
				<tr>
					<td class="nombres_filas"><input type="radio" name="rdb_idBitAvance" id="rdb_idBitAvance" value="<?php echo $datos['id_bitacora'];?>" /></td>
					<td class="<?php echo $nom_clase ?>"><?php echo $datos['id_bitacora'];?></td>
					<td class="<?php echo $nom_clase ?>"><?php echo $ubicacion;?></td>
					<td class="<?php echo $nom_clase ?>"><?php echo modFecha($datos['fecha_registro'],1);?></td>
					<td class="<?php echo $nom_clase ?>"><?php echo $datos['machote'];?></td>
					<td class="<?php echo $nom_clase ?>"><?php echo $datos['medida'];?></td>
					<td class="<?php echo $nom_clase ?>"><?php echo $datos['avance'];?></td>
					<td class="<?php echo $nom_clase ?>"><?php echo $datos['observaciones'];?></td>

					<td class="<?php echo $nom_clase ?>"><?php echo verificarRegBitacora($datos['id_bitacora'], "barrenacion_jumbo"); ?></td>
					<td class="<?php echo $nom_clase ?>"><?php echo verificarRegBitacora($datos['id_bitacora'], "barrenacion_maq_pierna"); ?></td>
					<td class="<?php echo $nom_clase ?>"><?php echo verificarRegBitacora($datos['id_bitacora'], "voladuras"); ?></td>
					<td class="<?php echo $nom_clase ?>"><?php echo verificarRegBitacora($datos['id_bitacora'], "rezagado"); ?></td>					
				</tr><?php								
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
			}while($datos=mysql_fetch_array($rs));
			
			//Cerrar la Tabla?>
			</table><?php
			return 1;
		}//Cierre if($datos=mysql_fetch_array($rs))
		else {?>
			<label class="msje_correcto">No Hay Registros en las Fechas del <?php echo $_POST['txt_fechaIni']; ?> al <?php echo $_POST['txt_fechaFin']; ?></label><?php
			//Cerrar la Conexion con la BD de Desarrollo
			mysql_close($conn);	
			return 0;
		}	
		
		
	}//Cierre de la funcion verRegistrosBitAvance()	 	
	
	
	/*Esta función verifica si existe un registro para la Bitacora de Avance en las Bitácoras de Barrenación, Voladura y Rezagado */
	function verificarRegBitacora($idBitAvance, $nomBitacora){
		//Conectarse a la BD de Desarrallo
		$conn = conecta("bd_desarrollo");
		
		//Crear la Sentencia SQL para Verificar si hay registro en la BD de la Bitácora indicada
		$sql_stm = "SELECT * FROM $nomBitacora WHERE bitacora_avance_id_bitacora = '$idBitAvance'";
		//Ejecutar la Sentencia
		$rs = mysql_query($sql_stm);
		
		if($datos=mysql_fetch_array($rs))
			return "SI";
		else
			return "NO";						
		
		//Cerrar la Conexion con la BD de Desarrollo
		mysql_close($conn);	
	}//Cierre de la funcion verificarRegBitacora($idBitAvance, $nomBitacora)
	
	
	//Esta funcion guardará los datos modificados del registro de la Bitácora de Avance
	function actualizarBitAvance(){
		//Conectarse a la BD de Desarrollo
		$conn = conecta("bd_desarrollo");
		
		//Recuperar los datos del POST
		$idBit = $_SESSION['bitacoraAvance']['idBitacora'];
		$lugar = $_POST['cmb_lugar'];
		$machote = str_replace(",","",$_POST['txt_machote']);
		$medida = str_replace(",","",$_POST['txt_medida']);
		$avance = str_replace(",","",$_POST['txt_avance']);		
		$fechaReg = modFecha($_POST['txt_fechaRegistro'],3);
		$obs = strtoupper($_POST['txa_observaciones']);
		
		//Crear la Sentencia SQL para alamcenar lo datos en la Base de Datos
		$sql_stm = "UPDATE bitacora_avance SET catalogo_ubicaciones_id_ubicacion = '$lugar', fecha_registro = '$fechaReg', machote = $machote, medida = $medida,
					avance = $avance, observaciones = '$obs' WHERE id_bitacora = '$idBit'";
		//Ejecutar la Sentencia SQL
		$rs = mysql_query($sql_stm);
		
		if($rs){
			//Quitar de la SESSION los datos utilizados en el registro de la Bitacora de Avance			
			unset($_SESSION['bitsActualizadas']);
			unset($_SESSION['bitacoraAvance']);
			
			//Guardar el Movimiento realizado en la tabla de Movimientos
			registrarOperacion("bd_desarrollo","$idBit","ModificarBitAvance",$_SESSION['usr_reg']);
			
			//Redireccionar a la pagina de exito
			echo "<meta http-equiv='refresh' content='0;url=exito.php'>";	
		}
		else{
			$error = mysql_error();
			echo "<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>$error";
			break;
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";			
			//Cerrar la conexicion con la BD
			mysql_close();
		}												
	}//Cierre de la funcion actualizarBitAvance()
				

?>