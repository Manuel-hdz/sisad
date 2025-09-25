<?php
	/**
	  * Nombre del M�dulo: Recursos Humanos
	  * Nombre Programador: Antonio de Jes�s Jim�nez Cuevas
	  * Fecha: 06/Abril/2011
	  * Descripci�n: Este archivo contiene funciones para Consultar un Empleado de la BD de Recursos y poder Eliminarlo
	**/

	/*
		Valores de Patron
		1 => Busqueda por nombre concatenado
		2 => Busqueda por RFC del trabajador
	*/
	//Funcion que muestra uno o mas equipos segun el formulario frm_consultarEmpleado.php
	function mostrarEmpleados($patron){
		if ($patron==1){
			//Creamos la sentencia SQL para mostrar los datos del empleado con el nombre que llega en el txt_nombre via POST
			$stm_sql="SELECT * FROM empleados WHERE CONCAT(nombre,' ',ape_pat,' ',ape_mat)='$_POST[txt_nombre]' AND estado_actual = 'ALTA'";	
			//Creamos el titulo de la tabla
			$titulo="Datos del Empleado <em>".$_POST["txt_nombre"]."</em>";
		}
		if ($patron==2){
			//Creamos la sentencia SQL para mostrar los datos del empleado con el nombre que llega en el txt_nombre via POST
			$stm_sql="SELECT * FROM empleados WHERE rfc_empleado='$_GET[rfc]' AND estado_actual = 'ALTA'";	
			$nombre=obtenerNombreEmpleado($_GET["rfc"]);
			//Creamos el titulo de la tabla
			$titulo="Datos del Empleado <em>".$nombre."</em>";
		}
		//Abrimos la conexion con la Base de datos
		$conn=conecta("bd_recursos");
			
		//Ejecutamos la sentencia SQL
		$rs=mysql_query($stm_sql);
		
		if ($datos=mysql_fetch_array($rs)){
			echo "<br />";
			echo "<table class='tabla_frm' cellpadding='5'>";
			echo "<caption class='titulo_etiqueta'>$titulo</caption>";
			echo "<br />";
			echo "	<tr>
						<td class='nombres_columnas' align='center'>SELECCIONAR</td>
						<td class='nombres_columnas' align='center'>RFC</td>
						<td class='nombres_columnas' align='center'>CURP</td>
						<td class='nombres_columnas' align='center'>ID EMPRESA</td>
						<td class='nombres_columnas' align='center'>ID &Aacute;REA </td>
						<td class='nombres_columnas' align='center'>NOMBRE </td>
						<td class='nombres_columnas' align='center'>SUELDO DIARIO</td>
						<td class='nombres_columnas' align='center'>TIPO SANGRE</td>
						<td class='nombres_columnas' align='center'>N&Uacute;MERO SEGURO SOCIAL</td>
						<td class='nombres_columnas' align='center'>FECHA INGRESO</td>
						<td class='nombres_columnas' align='center'>ANTIG&Uuml;EDAD</td>
						<td class='nombres_columnas' align='center'>PUESTO</td>
						<td class='nombres_columnas' align='center'>N&Uacute;MERO CUENTA</td>
						<td class='nombres_columnas' align='center'>&Aacute;REA</td>
						<td class='nombres_columnas' align='center'>JORNADA</td>
						<td class='nombres_columnas' align='center'>DIRECCI&Oacute;N</td>
						<th class='nombres_columnas' align='center'>MUNICIPIO/ LOCALIDAD</th>
						<td class='nombres_columnas' align='center'>ESTADO</td>
						<td class='nombres_columnas' align='center'>PAIS</td>
						<td class='nombres_columnas' align='center'>NACIONALIDAD</th>
						<td class='nombres_columnas' align='center'>FOTOGRAF&Iacute;A</td>
						<td class='nombres_columnas' align='center'>BENEFICIARIOS</td>
						<td class='nombres_columnas' align='center'>CAPACITACIONES</td>
						<td class='nombres_columnas' align='center'>BECARIOS</td>
						<td class='nombres_columnas' align='center'>CONTACTO POR ACCIDENTE</td>
						<td class='nombres_columnas' align='center'>OBSERVACIONES</td>
					</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{	
			$ctrl_imagen = "";
				if($datos['mime']=="")
					$ctrl_imagen = "disabled='disabled'";											
				echo "	<tr>
						<td class='nombres_filas' align='center'><input type='radio' name='rdb_rfc' value='$datos[rfc_empleado]'/></td>			
						<td class='nombres_filas' align='center'>$datos[rfc_empleado]</td>
						<td class='$nom_clase' align='left'>$datos[curp]</td>
						<td class='$nom_clase' align='center'>$datos[id_empleados_empresa]</td>
						<td class='$nom_clase' align='center'>$datos[id_empleados_area]</td>
						<td class='$nom_clase' align='center'>$datos[nombre] $datos[ape_pat] $datos[ape_mat]</td>
						<td class='$nom_clase' align='center'>$".number_format($datos["sueldo_diario"],2,".",",")."</td>
						<td class='$nom_clase' align='left'>$datos[tipo_sangre]</td>
						<td class='$nom_clase' align='center'>$datos[no_ss]</td>
						<td class='$nom_clase' align='left'>".modFecha($datos["fecha_ingreso"],2)."</td>
						<td class='$nom_clase' align='left'>".restarFechas($datos["fecha_ingreso"],date("Y-m-d"))." dias</td>
						<td class='$nom_clase' align='left'>$datos[puesto]</td>
						<td class='$nom_clase' align='center'>$datos[no_cta]</td>
						<td class='$nom_clase' align='center'>$datos[area]</td>
						<td class='$nom_clase' align='center'>$datos[jornada]&nbsp;Hrs.</td>
						<td class='$nom_clase' align='center'>$datos[calle] $datos[num_ext] $datos[num_int] $datos[colonia] $datos[localidad]</td>
						<td class='$nom_clase' align='center'>$datos[localidad]</td>
						<td class='$nom_clase' align='center'>$datos[estado]</td>
						<td class='$nom_clase' align='center'>$datos[pais]</td>
						<td class='$nom_clase' align='center'>$datos[nacionalidad]</td>";?>
						<td class="<?php echo $nom_clase; ?>" align="center">
							<input type="button" name="btn_verFoto" class="botones" value="Foto" onMouseOver="window.estatus='';return true" title="Ver Foto del Empleado <?php echo $datos['rfc_empleado'];?>" 
							onClick="javascript:window.open('verImagen.php?id_empleado=<?php echo $datos['rfc_empleado']; ?>',
							'_blank','top=50, left=50, width=800, height=600, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');" <?php echo $ctrl_imagen; ?>/>							
						</td>
						<td class="<?php echo $nom_clase; ?>" align="center">
							<input type="button" name="btn_verBeneficiarios" class="botones" value="Beneficiarios" onMouseOver="window.estatus='';return true" title="Ver Beneficiarios del Empleado <?php echo $datos['rfc_empleado'];?>" 
							onClick="javascript:window.open('verBeneficiarios.php?id_empleado=<?php echo $datos['rfc_empleado']; ?>',
							'_blank','top=50, left=50, width=800, height=600, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');"/>							
						</td>
						<td class="<?php echo $nom_clase; ?>" align="center">
							<input type="button" name="btn_verCapacitaciones" class="botones" value="Capacitaciones" onMouseOver="window.estatus='';return true" title="Ver verCapacitaciones del Empleado <?php echo $datos['rfc_empleado'];?>" 
							onClick="javascript:window.open('verCapacitaciones.php?id_empleado=<?php echo $datos['rfc_empleado']; ?>',
							'_blank','top=50, left=50, width=800, height=600, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');"/>							
						</td>
						<td class="<?php echo $nom_clase; ?>" align="center">
							<input type="button" name="btn_verBecarios" class="botones" value="Becarios" onMouseOver="window.estatus='';return true" title="Ver Becarios del Empleado <?php echo $datos['rfc_empleado'];?>" 
							onClick="javascript:window.open('verBecarios.php?id_empleado=<?php echo $datos['rfc_empleado']; ?>',
							'_blank','top=50, left=50, width=800, height=600, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');"/>							
						</td>
				<?php
				echo "	
						
						<td class='$nom_clase' align='left'>Nombre: $datos[nom_accidente]<br>Tel: $datos[tel_accidente]<br>Cel: $datos[cel_accidente]</td>
						<td class='$nom_clase' align='left'>$datos[observaciones]</td>
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
			if ($patron==1)
				echo "<br><br><br><br><br><br><br><br><p align='center' class='msje_correcto'>No hay Trabajadores Registrados con el Nombre <em><u>$_POST[txt_nombre]</u></em></p>";
			if ($patron==2)
				echo "<br><br><br><br><br><br><br><br><p align='center' class='msje_correcto'>No hay Trabajadores Registrados con el RFC <em><u>$_GET[rfc]</u></em></p>";
			return 0;
		}

		//Cerramos la conexion con la Base de Datos
		mysql_close($conn);
	}//Fin de la funcion de mostrarEmpleados
	
	//Funcion que permite registrar las Bajas de Empleados en la Tabla de Bajas
	function registrarBaja(){
		//Recoger las fechas en formato SQL
		$fechaIng=modFecha($_POST["txt_fechaIng"],3);
		$fechaBaja=modFecha($_POST["txt_fechaBaja"],3);
		//Convertir las observaciones en mayusculas
		$obs=strtoupper($_POST["txa_observaciones"]);
		//Sentencia SQL
		$stm_sql="INSERT INTO bajas_modificaciones VALUES('$_POST[txt_rfc]','$_POST[txt_apePat]','$_POST[txt_apeMat]','$_POST[txt_nombre]','$fechaIng','$fechaBaja','$_POST[txt_area]','$_POST[txt_puesto]','$obs','0000-00-00')";
		//Conectar a la BD de Recursos Humanos
		$conn=conecta("bd_recursos");
		//Ejecutar la sentencia SQL
		$rs=mysql_query($stm_sql);
		//Verificar si la sentencia ejecutada se genero con exito
		if ($rs){
			//Cerrar la conexion con la BD de Recursos Humanos
			mysql_close($conn);
			//Funcion que elimina al Empleado de la Base de Datos de Recursos Humanos
			bajaEmpleado();
		}
		else{
			//Capturar el error de mysql que se genero
			$error="registrarBaja: ".mysql_error();

			echo '<script> console.log("sql= '.$stm_sql.'"); </script>';

			//Cerrar la conexion con la BD de Recursos Humanos
			mysql_close($conn);
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
		}
	}
	
	//Funcion que borra a los Empleados de la tabla de Empleados
	function bajaEmpleado(){
		//Sentencia SQL
		$stm_sql="UPDATE empleados SET estado_actual = 'BAJA' WHERE rfc_empleado='$_POST[txt_rfc]'";
		//Conectar a la BD de Recursos Humanos
		$conn=conecta("bd_recursos");
		//Ejecutar la sentencia SQL
		$rs=mysql_query($stm_sql);
		//Verificar si la sentencia ejecutada se genero con exito
		if ($rs){
			//Cerrar la conexion con la BD de Recursos Humanos
			mysql_close($conn);
			//Verificar si el trabajador tiene beneficiarios
			/*if (obtenerDato("bd_recursos", "beneficiarios", "empleados_rfc_empleado", "empleados_rfc_empleado", $_POST["txt_rfc"])!="")
				//Funcion que elimina a los Beneficiarios de los Empleados de la Base de Datos de Recursos Humanos
				bajaEmpleadoBeneficiarios();
			//Verificar si el trabajador tiene becarios
			if (obtenerDato("bd_recursos", "becas", "empleados_rfc_empleado", "empleados_rfc_empleado",$_POST["txt_rfc"])!="")
				//Funcion que elimina a los Becarios de los Empleados de la Base de Datos de Recursos Humanos
				bajaEmpleadoBecarios();
			//Verificar si el trabajador tiene becarios*/
			if (($alerta=obtenerDato("bd_almacen", "alertas", "id_alerta", "rfc_empleado",$_POST["txt_rfc"]))!="")
				//Funcion que elimina a los Becarios de los Empleados de la Base de Datos de Recursos Humanos
				bajaAlertaAlmacenRH($alerta);
			//Registrar la Operacion en la Bit�cora de Movimientos
			registrarOperacion("bd_recursos",$_POST["txt_rfc"],"BajaEmpleado",$_SESSION['usr_reg']);
			//Redireccionar a la pantalla de Exito
			echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
		}
		else{
			//Capturar el error de mysql que se genero
			$error="bajaEmpleado: ".mysql_error();
			//Cerrar la conexion con la BD de Recursos Humanos
			//mysql_close($conn);
			echo "<meta http-equiv='refresh' content='7;url=error.php?err=$error'>";
		}
	}
	
	//Funcion que da de baja a los Beneficiarios de los empleados eliminados
	function bajaEmpleadoBeneficiarios(){
		//Sentencia SQL
		$stm_sql="DELETE FROM beneficiarios WHERE empleados_rfc_empleado='$_POST[txt_rfc]'";
		//Conectar a la BD de Recursos Humanos
		$conn=conecta("bd_recursos");
		//Ejecutar la sentencia SQL
		$rs=mysql_query($stm_sql);
		if (!$rs){
			//Capturar el error de mysql que se genero
			$error="bajaBeneficiarios: ".mysql_error();
			//Cerrar la conexion con la BD de Recursos Humanos
			mysql_close($conn);
			echo "<meta http-equiv='refresh' content='7;url=error.php?err=$error'>";
		}else{
			//Registrar la Operacion en la Bit�cora de Movimientos
			registrarOperacion("bd_recursos",$_POST["txt_rfc"],"EliminarBeneficiarios",$_SESSION['usr_reg']);
			//Cerrar la conexion con la BD de Recursos Humanos
			//mysql_close($conn);
		}
	}
	
	//Funcion que da de baja a los Becarios de los empleados eliminados
	function bajaEmpleadoBecarios(){
		//Conectar a la BD de Recursos Humanos
		$conn=conecta("bd_recursos");
		//Sentencia SQL
		$stm_sql="DELETE FROM becas WHERE empleados_rfc_empleado='$_POST[txt_rfc]'";
		//Ejecutar la sentencia SQL
		$rs=mysql_query($stm_sql);
		if (!$rs){
			$error="bajaBecarios: ".mysql_error();
			//Cerrar la conexion con la BD de Recursos Humanos
			mysql_close($conn);
			echo "<meta http-equiv='refresh' content='7;url=error.php?err=$error'>";
		}else{
			//Registrar la Operacion en la Bit�cora de Movimientos
			registrarOperacion("bd_recursos",$_POST["txt_rfc"],"EliminarBecarios",$_SESSION['usr_reg']);
			//Cerrar la conexion con la BD de Recursos Humanos
			//mysql_close($conn);
		}
	}
	
	//Funcion que da de baja las alertas que pudieron generarse en Almacen por el Trabajador
	function bajaAlertaAlmacenRH($alerta){
		//Conectar a la BD de Recursos Humanos
		$conn=conecta("bd_almacen");
		//Sentencia SQL
		$stm_sql="DELETE FROM alertas WHERE id_alerta='$alerta'";
		//Ejecutar la sentencia SQL
		$rs=mysql_query($stm_sql);
		if (!$rs){
			$error="bajaAlmacen: ".mysql_error();
			//Cerrar la conexion con la BD de Recursos Humanos
			mysql_close($conn);
			echo "<meta http-equiv='refresh' content='7;url=error.php?err=$error'>";
		}else{
			//Registrar la Operacion en la Bit�cora de Movimientos
			registrarOperacion("bd_recursos",$_POST["txt_rfc"],"EliminarAlertaAlmacen",$_SESSION['usr_reg']);
			//Cerrar la conexion con la BD de Recursos Humanos
			//mysql_close($conn);
		}
	}
?>