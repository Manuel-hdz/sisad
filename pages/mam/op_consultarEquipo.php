<?php
	/**
	  * Nombre del Módulo: Mantenimiento
	  * Nombre Programador: Antonio de Jesús Jiménez Cuevas
	  * Fecha: 28/Febrero/2011
	  * Descripción: Este archivo contiene funciones para Consultar un Equipo de la BD de Mantenimiento
	**/

	//Esta funcion Da de Baja el Equipo en la Base de Datos
	function verEquipo($clave){
		//Abrimos la conexion con la Base de datos
		$conn=conecta("bd_mantenimiento");
		
		//Creamos la sentencia SQL para mostrar los datos del Equipo
		$stm_sql="SELECT * FROM equipos WHERE id_equipo='$clave'";
		
		//Verificar que el area este definida, de no ser asi, el usuario es AuxMtto
		if (isset($_POST["hdn_area"]))
			//De estar definida el area, concatenamos a la sentencia SQL la condicion que restringe el area del vehiculo
			$stm_sql.=" AND area='$_POST[hdn_area]'";
		
		$clave=strtoupper($clave);
		//Ejecutamos la sentencia SQL
		$rs=mysql_query($stm_sql);
		
		if ($datos=mysql_fetch_array($rs)){
			echo "<table class='tabla_frm' cellpadding='5'>";
			echo "<caption class='titulo_etiqueta'>Datos del Equipo <em><u>$clave</u></em></caption>";
			echo "	<tr>
						<td class='nombres_columnas' align='center'>CLAVE</td>
						<td class='nombres_columnas' align='center'>NOMBRE EQUIPO</td>
						<td class='nombres_columnas' align='center'>FECHA ALTA</td>
						<td class='nombres_columnas' align='center'>MARCA/MODELO</td>
						<td class='nombres_columnas' align='center'>MODELO</td>
						<td class='nombres_columnas' align='center'>P&Oacute;LIZA</td>
						<td class='nombres_columnas' align='center'>N&Uacute;MERO SERIE</td>
						<td class='nombres_columnas' align='center'>N&Uacute;MERO SERIE EQ. ADICIONAL</td>
						<td class='nombres_columnas' align='center'>PLACAS</td>
						<td class='nombres_columnas' align='center'>TENENCIA</td>
						<td class='nombres_columnas' align='center'>TARJETA CIRCULACI&Oacute;N</td>
						<td class='nombres_columnas' align='center'>TIPO MOTOR</td>
						<td class='nombres_columnas' align='center'>&Aacute;REA</td>
						<td class='nombres_columnas' align='center'>FAMILIA</td>
						<td class='nombres_columnas' align='center'>FECHA F&Aacute;BRICA</td>
						<td class='nombres_columnas' align='center'>ASIGNADO A</td>
						<td class='nombres_columnas' align='center'>ESTADO</td>
						<td class='nombres_columnas' align='center'>PROVEEDOR</td>
						<td class='nombres_columnas' align='center'>DESCRIPCI&Oacute;N</td>
						<td class='nombres_columnas' align='center'>HOR&Oacute;METRO/OD&Oacute;METRO</td>
						<td class='nombres_columnas' align='center'>FOTOGRAF&Iacute;A</td>
					</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;	
			$estado="";
			do{	
			$ctrl_imagen = "";
				if($datos['mime']=="")
					$ctrl_imagen = "disabled='disabled'";									
				echo "	<tr>					
						<td class='nombres_filas' align='center'>$datos[id_equipo]</td>					
						<td class='$nom_clase' align='left'>$datos[nom_equipo]</td>
						<td class='$nom_clase' align='left'>".modFecha($datos["fecha_alta"],2)."</td>
						<td class='$nom_clase' align='center'>$datos[marca_modelo]</td>
						<td class='$nom_clase' align='center'>$datos[modelo]</td>					
						<td class='$nom_clase' align='left'>$datos[poliza]</td>
						<td class='$nom_clase' align='center'>$datos[num_serie]</td>
						<td class='$nom_clase' align='left'>$datos[num_serie_olla]</td>
						<td class='$nom_clase' align='left'>$datos[placas]</td>					
						<td class='$nom_clase' align='center'>$datos[tenencia]</td>
						<td class='$nom_clase' align='center'>$datos[tar_circulacion]</td>
						<td class='$nom_clase' align='center'>$datos[tipo_motor]</td>
						<td class='$nom_clase' align='center'>$datos[area]</td>
						<td class='$nom_clase' align='left'>$datos[familia]</td>
						<td class='$nom_clase' align='left'>".modFecha($datos["fecha_fabrica"],2)."</td>
						<td class='$nom_clase' align='left'>$datos[asignado]</td>
						<td class='$nom_clase' align='left'>$datos[estado]</td>
						<td class='$nom_clase' align='left'>$datos[proveedor]</td>
						<td class='$nom_clase' align='left'>$datos[descripcion]</td>
						<td class='$nom_clase' align='left'>$datos[metrica]</td>";?>
						<td class="<?php echo $nom_clase; ?>" align="center">
							<input type="button" name="btn_verFoto" class="botones" value="Foto" onMouseOver="window.estatus='';return true;" title="Ver Foto del Equipo <?php echo $datos['nom_equipo'];?>" 
							onClick="javascript:window.open('verImagen.php?id_equipo=<?php echo $datos['id_equipo']; ?>',
							'_blank','top=50, left=50, width=800, height=600, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');" <?php echo $ctrl_imagen; ?>/>							
						</td>
				<?php
						$estado=$datos["estado"];
				echo "	</tr>";			
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
					
			}while($datos=mysql_fetch_array($rs)); 	
			echo "</table>";
			if ($estado=="ACTIVO")
				return 1;
			else
				return 2;
		}
		else{
			if (!isset($_POST["hdn_area"]))
				//Si no se encontraron Equipos con la clave proporcionada, mostrar mensaje
				echo "<br/><br/><br/><br/><p align='center'><label class='msje_correcto'>No se encontr&oacute; ning&uacute;n equipo con la clave: <u><em>".$clave."</u></em></label></p>";
			else
				//Si no se encontraron Equipos con la clave proporcionada, mostrar mensaje
				echo "<br/><br/><br/><br/><p align='center'><label class='msje_correcto'>No se encontr&oacute; ning&uacute;n equipo en el &Aacute;rea $_POST[hdn_area] con la clave: <u><em>".$clave."</u></em></label></p>";
			return 0;
		}
		//Cerramos la conexion con la Base de Datos
		mysql_close($conn);
		
	}//Fin de la funcion para buscar un equipo
	

	//Funcion que muestra uno o mas equipos segun el formulario frm_consultarEquipos.php
	/*Valores de PATRON
		1 -> Area
		2 -> Clave
		3 -> Familia
	*/
	function mostrarEquipos($patron){
		//Verificamos bajo que patron se esta pidiendo hacer la consults
		if ($patron==1){
			$patron=$_POST["cmb_area"];
			//Creamos la sentencia SQL para mostrar los datos de los Equipos de AREA = PATRON
			$stm_sql="SELECT * FROM equipos WHERE area='$patron' AND estado='ACTIVO'";
			//Creamos el titulo de la tabla
			$titulo="Datos de los Equipos del &Aacute;rea <em>".$patron."</em>";
		}
		if ($patron==2){
			$patron=$_POST["txt_clave"];
			//Creamos la sentencia SQL para mostrar los datos del Equipo de CLAVE = PATRON
			$stm_sql="SELECT * FROM equipos WHERE id_equipo='$patron' AND estado='ACTIVO'";
			//Verificar que el area este definida, de no ser asi, el usuario es AuxMtto
			if (isset($_POST["hdn_area"])){
				//De estar definida el area, concatenamos a la sentencia SQL la condicion que restringe el area del vehiculo
				$stm_sql.=" AND area='$_POST[hdn_area]'";
			}
			//Creamos el titulo de la tabla
			$titulo="Datos del Equipo con Clave <em><u>".strtoupper($patron)."</u></em>";
		}
		if ($patron==3){
			$patron=$_POST["cmb_familia"];
			//Creamos la sentencia SQL para mostrar los datos de los Equipos de FAMILIA = PATRON
			$stm_sql="SELECT * FROM equipos WHERE familia = '$patron' AND estado = 'ACTIVO'";
			//Verificar que el area este definida, de no ser asi, el usuario es AuxMtto
			if (isset($_POST["hdn_area"])){
				//De estar definida el area, concatenamos a la sentencia SQL la condicion que restringe el area del vehiculo
				$stm_sql.=" AND area = '$_POST[hdn_area]'";
			}
			//Creamos el titulo de la tabla			
			$titulo="Datos de los Equipos de la Familia <em><u>".$patron."</u></em>";
		}
		
		//Abrimos la conexion con la Base de datos
		$conn=conecta("bd_mantenimiento");
		
		//Ejecutamos la sentencia SQL
		$rs=mysql_query($stm_sql);
		
		if ($datos=mysql_fetch_array($rs)){
			echo "<br />";
			echo "<table class='tabla_frm' cellpadding='5' width='2380'>";
			echo "<caption class='titulo_etiqueta'>$titulo</caption>";
			echo "<br />";
			echo "	<tr>
						<td class='nombres_columnas' align='center' width='70'>CLAVE</td>
						<td class='nombres_columnas' align='center' width='90'>NOMBRE EQUIPO</td>
						<td class='nombres_columnas' align='center' width='110'>FECHA ALTA</td>
						<td class='nombres_columnas' align='center'>MARCA/MODELO</td>
						<td class='nombres_columnas' align='center'>MODELO</td>
						<td class='nombres_columnas' align='center'>P&Oacute;LIZA</td>
						<td class='nombres_columnas' align='center'>N&Uacute;MERO SERIE</td>
						<td class='nombres_columnas' align='center'>N&Uacute;MERO SERIE EQ. ADICIONAL</td>
						<td class='nombres_columnas' align='center'>PLACAS</td>
						<td class='nombres_columnas' align='center'>TENENCIA</td>
						<td class='nombres_columnas' align='center'>TARJETA CIRCULACI&Oacute;N</td>
						<td class='nombres_columnas' align='center'>TIPO MOTOR</td>
						<td class='nombres_columnas' align='center'>&Aacute;REA</td>
						<td class='nombres_columnas' align='center'>FAMILIA</td>
						<td class='nombres_columnas' align='center' width='110'>FECHA F&Aacute;BRICA</td>
						<td class='nombres_columnas' align='center'>ASIGNADO A</td>
						<td class='nombres_columnas' align='center'>ESTADO</td>
						<td class='nombres_columnas' align='center'>PROVEEDOR</td>
						<td class='nombres_columnas' align='center' width='110'>DESCRIPCI&Oacute;N</td>
						<td class='nombres_columnas' align='center'>HOR&Oacute;METRO/OD&Oacute;METRO</td>
						<td class='nombres_columnas' align='center'>DISPONIBILIDAD</td>
						<td class='nombres_columnas' align='center'>FOTOGRAF&Iacute;A</td>
						<td class='nombres_columnas' align='center'>DOCUMENTOS</td>
						<td class='nombres_columnas' align='center'>REFACCIONES</td>
					</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{	
			$ctrl_imagen = "";
				if($datos['mime']=="")
					$ctrl_imagen = "disabled='disabled'";											
				echo "	<tr>					
						<td class='nombres_filas' align='center'>$datos[id_equipo]</td>					
						<td class='$nom_clase' align='left'>$datos[nom_equipo]</td>
						<td class='$nom_clase' align='left'>".modFecha($datos["fecha_alta"],2)."</td>
						<td class='$nom_clase' align='center'>$datos[marca_modelo]</td>
						<td class='$nom_clase' align='center'>$datos[modelo]</td>					
						<td class='$nom_clase' align='left'>$datos[poliza]</td>
						<td class='$nom_clase' align='center'>$datos[num_serie]</td>
						<td class='$nom_clase' align='left'>$datos[num_serie_olla]</td>
						<td class='$nom_clase' align='left'>$datos[placas]</td>					
						<td class='$nom_clase' align='center'>$datos[tenencia]</td>
						<td class='$nom_clase' align='center'>$datos[tar_circulacion]</td>
						<td class='$nom_clase' align='center'>$datos[tipo_motor]</td>
						<td class='$nom_clase' align='center'>$datos[area]</td>
						<td class='$nom_clase' align='left'>$datos[familia]</td>
						<td class='$nom_clase' align='left'>".modFecha($datos["fecha_fabrica"],2)."</td>
						<td class='$nom_clase' align='left'>$datos[asignado]</td>
						<td class='$nom_clase' align='left'>$datos[estado]</td>
						<td class='$nom_clase' align='left'>$datos[proveedor]</td>
						<td class='$nom_clase' align='left'>$datos[descripcion]</td>
						<td class='$nom_clase' align='left'>$datos[metrica]</td>
						<td class='$nom_clase' align='left'>$datos[disponibilidad]";?>
						
						<input type="image" src="../../images/editar.png" width="30" height="25" border="0" title="Cambiar Disponibilidad" 
						onclick="location.href='frm_consultarDisponibilidadEq.php?id_equipo=<?php echo $datos['id_equipo']; ?>&disponibilidad=<?php echo $datos['disponibilidad']?>'" 
						onmouseover="window.status='';return true;"/>  <?php
						echo "</td>"; ?>
						
						<td class="<?php echo $nom_clase; ?>" align="center">
							<input type="button" name="btn_verFoto" class="botones" value="Foto" onMouseOver="window.estatus='';return true" title="Ver Foto del Equipo <?php echo $datos['nom_equipo'];?>" 
							onClick="javascript:window.open('verImagen.php?id_equipo=<?php echo $datos['id_equipo']; ?>',
							'_blank','top=50, left=50, width=800, height=600, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');"<?php echo $ctrl_imagen; ?>/>							
						</td>
						<td class="<?php echo $nom_clase; ?>" align="center">
							<input type="button" name="btn_verDocumentos" class="botones" value="Documentos" onMouseOver="window.estatus='';return true" title="Ver Documentos del Equipo <?php echo $datos['nom_equipo'];?>" 
							onClick="javascript:window.open('verDocumentos.php?id_equipo=<?php echo $datos['id_equipo']; ?>',
							'_blank','top=50, left=50, width=800, height=600, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');"/>							
						</td>
                        <td class="<?php echo $nom_clase; ?>" align="center">
							<input type="button" name="btn_verRefacciones" class="botones" value="Refacciones" onMouseOver="window.estatus='';return true" title="Ver Refacciones del Equipo <?php echo $datos['nom_equipo'];?>" 
							onClick="javascript:window.open('verRefacciones.php?id_equipo=<?php echo $datos['id_equipo']; ?>',
							'_blank','top=50, left=50, width=800, height=600, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');"/>							
						</td>

				<?php
				echo "	</tr>";			
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
					
			}while($datos=mysql_fetch_array($rs)); 	
			echo "</table>";
			return $patron;
		}
		else{
			if ($patron==4)
				//Si no se encontraron Equipos con la clave proporcionada, mostrar mensaje
				echo "<br><br><br><br><br><br><br><br><br><br><br><p class='msje_correcto' align='center'>No Hay Ning&uacute;n Equipo Registrado</u></em></p>";
			else{
				//Verificar que el area este definida, de no ser asi, el usuario es AuxMtto
				if (!isset($_POST["hdn_area"]))
					//Si no se encontraron Equipos con la clave proporcionada, mostrar mensaje
					echo "<br><br><br><br><br><br><br><br><br><br><br><p class='msje_correcto' align='center'>No se encontr&oacute; ning&uacute;n equipo con el dato: <u><em>".$patron."</u></em></p>";
				else
					//Si no se encontraron Equipos con la clave proporcionada, mostrar mensaje
					echo "<br><br><br><br><br><br><br><br><br><br><br><p class='msje_correcto' align='center'>No se encontr&oacute; ning&uacute;n equipo con el dato: <u><em>".$patron."</u></em> en el &Aacute;rea <u><em>$_POST[hdn_area]</u></em></p>";
			}
			return "0";
		}
		//Cerramos la conexion con la Base de Datos
		mysql_close($conn);
	}//Fin de la funcion de mostrarEquipos
	
	
	//Funcion que cambia la disponibilidad de los equipos
	function cambiarDisponibilidadEq(){
		//Obtener la disponibilidad
		$disponibilidad= $_POST['cmb_disponibilidad'];
		$id_equipo= $_POST['hdn_idEquipo'];
		
		//Abrimos la conexion con la Base de datos
		$conn=conecta("bd_mantenimiento");

		//Creamos la sentencia SQL para actualizar el estado del equipo
		$stm_sql="UPDATE equipos SET disponibilidad='$disponibilidad' WHERE id_equipo='$id_equipo'";
				
		//Ejecutar la sentencia previamente creada
		$rs=mysql_query($stm_sql);
		//verificamos que la sentencia sea ejecutada con exito
		if ($rs){
			echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
		}
		else{
			//Si los datos no se agregaron correctamente, se redirecciona a la pagina de error
			$error = mysql_error();
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
		}	
	}// FIN function cambiarDisponibilidadEq()
?>