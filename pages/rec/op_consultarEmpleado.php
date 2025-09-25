<?php
	/**
	  * Nombre del M�dulo: Recursos Humanos
	  * Nombre Programador: Antonio de Jes�s Jim�nez Cuevas
	  * Fecha: 30/Marzo/2011
	  * Descripci�n: Este archivo contiene funciones para Consultar un Empleado de la BD de Recursos
	**/

	//Funcion que muestra uno o mas equipos segun el formulario frm_consultarEmpleado.php
	/*Valores de PATRON
		1 -> Nombre
		2 -> Todos
		3 -> Area
	*/
	function mostrarEmpleados($patron){
		$stm_sql="";
		//Verificamos bajo que patron se esta pidiendo hacer la consulta
		if ($patron==1){
			//Creamos la sentencia SQL para mostrar los datos del empleado con el nombre que llega en el txt_nombre via POST
			$stm_sql="SELECT * FROM empleados WHERE CONCAT(nombre,' ',ape_pat,' ',ape_mat)='$_POST[txt_nombre]' AND estado_actual = 'ALTA'";
			//Creamos el titulo de la tabla
			$titulo="Datos del Empleado <em>".$_POST["txt_nombre"]."</em>";
			echo "<input type='hidden' name='hdn_nombre' id='hdn_nombre' value='$_POST[txt_nombre]'/>";
		}
		if ($patron==2){
			//Creamos la sentencia SQL para mostrar los datoa de todos los empleados
			$stm_sql="SELECT * FROM empleados WHERE estado_actual = 'ALTA'";
			//Creamos el titulo de la tabla
			$titulo="Datos de Todos los Empleados";
		}
		if ($patron==3){
			//Creamos la sentencia SQL para mostrar los datos de los empleados que estan en el �rea que llega via POST
			$stm_sql="SELECT * FROM empleados WHERE area='$_POST[cmb_area]' AND estado_actual = 'ALTA'";
			//Creamos el titulo de la tabla
			$titulo="Datos de los Empleados del &Aacute;rea <em><u>".$_POST["cmb_area"]."</u></em>";
			echo "<input type='hidden' name='hdn_area' id='hdn_area' value='$_POST[cmb_area]'/>";
		}
		if ($patron==4){
			//Creamos la sentencia SQL para mostrar los datos del empleado con el nombre que llega en el txt_nombre via POST
			$stm_sql="SELECT * FROM empleados WHERE CONCAT(nombre,' ',ape_pat,' ',ape_mat)='$_POST[txt_nombre_baja]' AND estado_actual = 'BAJA'";
			//Creamos el titulo de la tabla
			$titulo="Datos del Empleado <em>".$_POST["txt_nombre_baja"]."</em>";
			echo "<input type='hidden' name='hdn_nombre' id='hdn_nombre' value='$_POST[txt_nombre_baja]'/>";
		}
		if ($patron==5){
			//Creamos la sentencia SQL para mostrar los datoa de todos los empleados
			$stm_sql="SELECT * FROM empleados WHERE estado_actual = 'BAJA'";
			//Creamos el titulo de la tabla
			$titulo="Datos de Todos los Empleados Baja";
		}
		//Abrimos la conexion con la Base de datos
		$conn=conecta("bd_recursos");
			
		//Ejecutamos la sentencia SQL
		$rs=mysql_query($stm_sql);
		
		if ($datos=mysql_fetch_array($rs)){
			echo "<br />";
			echo "<table class='tabla_frm' cellpadding='5' id='tabla-resultadosEmpleados'>";
			echo "<caption class='titulo_etiqueta'>$titulo</caption>
				<thead>";
			echo "<br />";
			echo "	<tr>
						<td class='nombres_columnas' align='center'>TODOS<input type='checkbox' name='ckbTodo' id='ckbTodo' value='TODO' onclick=\"checarTodos(this,'frm_exportarEmpleados')\"/></td>
					</tr>
			";
			echo "	<tr>
						<td class='nombres_columnas' align='center'><input type='checkbox' name='ckb_col1' id='ckb_col1' value='rfc_empleado' onclick=\"desSeleccionar(this)\"/></td>
						<td class='nombres_columnas' align='center'><input type='checkbox' name='ckb_col2' id='ckb_col2' value='curp' onclick=\"desSeleccionar(this)\"/></td>
						<td class='nombres_columnas' align='center'><input type='checkbox' name='ckb_col3' id='ckb_col3' value='id_empleados_empresa' onclick=\"desSeleccionar(this)\"/></td>
						<td class='nombres_columnas' align='center'><input type='checkbox' name='ckb_col4' id='ckb_col4' value='id_empleados_area' onclick=\"desSeleccionar(this)\"/></td>
						<td class='nombres_columnas' align='center'><input type='checkbox' name='ckb_col5' id='ckb_col5' value='nombreCompleto' onclick=\"desSeleccionar(this)\"/></td>
						<td class='nombres_columnas' align='center'><input type='checkbox' name='ckb_col6' id='ckb_col6' value='sueldo_diario' onclick=\"desSeleccionar(this)\"/></td>
						<td class='nombres_columnas' align='center'><input type='checkbox' name='ckb_col7' id='ckb_col7' value='tipo_sangre' onclick=\"desSeleccionar(this)\"/></td>
						<td class='nombres_columnas' align='center'><input type='checkbox' name='ckb_col8' id='ckb_col8' value='no_ss' onclick=\"desSeleccionar(this)\"/></td>
						<td class='nombres_columnas' align='center'><input type='checkbox' name='ckb_col9' id='ckb_col9' value='fecha_ingreso' onclick=\"desSeleccionar(this)\"/></td>
						<td class='nombres_columnas' align='center'><input type='checkbox' name='ckb_col10' id='ckb_col10' value='antiguedad' onclick=\"desSeleccionar(this)\"/></td>
						<td class='nombres_columnas' align='center'><input type='checkbox' name='ckb_col11' id='ckb_col11' value='puesto' onclick=\"desSeleccionar(this)\"/></td>
						<td class='nombres_columnas' align='center'><input type='checkbox' name='ckb_col12' id='ckb_col12' value='no_cta' onclick=\"desSeleccionar(this)\"/></td>
						<td class='nombres_columnas' align='center'><input type='checkbox' name='ckb_col13' id='ckb_col13' value='area' onclick=\"desSeleccionar(this)\"/></td>
						<td class='nombres_columnas' align='center'><input type='checkbox' name='ckb_col14' id='ckb_col14' value='jornada' onclick=\"desSeleccionar(this)\"/></td>
						<td class='nombres_columnas' align='center'><input type='checkbox' name='ckb_col15' id='ckb_col15' value='oc_esp' onclick=\"desSeleccionar(this)\"/></td>
						
						<td class='nombres_columnas' align='center'><input type='checkbox' name='ckb_col16' id='ckb_col16' value='nivel_estudio' onclick=\"desSeleccionar(this)\"/></td>
						<td class='nombres_columnas' align='center'><input type='checkbox' name='ckb_col17' id='ckb_col17' value='titulo' onclick=\"desSeleccionar(this)\"/></td>
						<td class='nombres_columnas' align='center'><input type='checkbox' name='ckb_col18' id='ckb_col18' value='carrera' onclick=\"desSeleccionar(this)\"/></td>
						<td class='nombres_columnas' align='center'><input type='checkbox' name='ckb_col19' id='ckb_col19' value='tipo_escuela' onclick=\"desSeleccionar(this)\"/></td>
						
						<td class='nombres_columnas' align='center'><input type='checkbox' name='ckb_col20' id='ckb_col20' value='direccion' onclick=\"desSeleccionar(this)\"/></td>
						<td class='nombres_columnas' align='center'><input type='checkbox' name='ckb_col21' id='ckb_col21' value='localidad' onclick=\"desSeleccionar(this)\"/></td>
						<td class='nombres_columnas' align='center'><input type='checkbox' name='ckb_col22' id='ckb_col22' value='estado' onclick=\"desSeleccionar(this)\"/></td>
						<td class='nombres_columnas' align='center'><input type='checkbox' name='ckb_col23' id='ckb_col23' value='pais' onclick=\"desSeleccionar(this)\"/></td>
						<td class='nombres_columnas' align='center'><input type='checkbox' name='ckb_col24' id='ckb_col24' value='nacionalidad' onclick=\"desSeleccionar(this)\"/></td>
						<td class='nombres_columnas' align='center'><input type='checkbox' name='ckb_col25' id='ckb_col25' value='telefono' onclick=\"desSeleccionar(this)\"/></td>
						<td class='nombres_columnas' align='center'><input type='checkbox' name='ckb_col26' id='ckb_col26' value='lugar_nacimiento' onclick=\"desSeleccionar(this)\"/></td>
						<td class='nombres_columnas' align='center'><input type='checkbox' name='ckb_col27' id='ckb_col27' value='fechaNacimiento' onclick=\"desSeleccionar(this)\"/></td>
						<td class='nombres_columnas' align='center'><input type='checkbox' name='ckb_col28' id='ckb_col28' value='edo_civil' onclick=\"desSeleccionar(this)\"/></td>
						<td class='nombres_columnas' align='center'><input type='checkbox' name='ckb_col29' id='ckb_col29' value='discapacidad' onclick=\"desSeleccionar(this)\"/></td>
						
						<td class='nombres_columnas' align='center'><input type='checkbox' name='ckb_col30' id='ckb_col30' value='hijos_dep_eco' onclick=\"desSeleccionar(this)\"/></td>
						<td class='nombres_columnas' align='center'><input type='checkbox' name='ckb_col31' id='ckb_col31' value='contactoAccidente' onclick=\"desSeleccionar(this)\"/></td>
						<td class='nombres_columnas' align='center'><input type='checkbox' name='ckb_col32' id='ckb_col32' value='observaciones' onclick=\"desSeleccionar(this)\"/></td>
					</tr>
			";
			echo "	<tr>
						<th class='nombres_columnas' align='center'>RFC</th>
						<th class='nombres_columnas' align='center'>CURP</th>
						<th class='nombres_columnas' align='center'>ID EMPRESA</th>
						<th class='nombres_columnas' align='center'>ID &Aacute;REA</th>
						<td class='nombres_columnas' align='center'>NOMBRE</td>
						<td class='nombres_columnas' align='center'>SUELDO DIARIO</td>
						<td class='nombres_columnas' align='center'>TIPO SANGRE</td>
						<td class='nombres_columnas' align='center'>N&Uacute;MERO SEGURO SOCIAL</td>
						<td class='nombres_columnas' align='center'>FECHA INGRESO</td>
						<td class='nombres_columnas' align='center'>ANTIG&Uuml;EDAD</td>
						<td class='nombres_columnas' align='center'>PUESTO</td>
						<td class='nombres_columnas' align='center'>N&Uacute;MERO CUENTA</td>
						<th class='nombres_columnas' align='center'>&Aacute;REA</th>
						<td class='nombres_columnas' align='center'>JORNADA</td>
						<th class='nombres_columnas' align='center'>OCUPACI&Oacute;N ESPEC&Iacute;FICA</th>
						
						<th class='nombres_columnas' align='center'>M&Aacute;XIMO NIVEL DE ESTUDIOS</th>
						<th class='nombres_columnas' align='center'>T&Iacute;TULO</th>
						<th class='nombres_columnas' align='center'>CARRERA</th>
						<th class='nombres_columnas' align='center'>TIPO ESCUELA</th>
						
						<td class='nombres_columnas' align='center'>DIRECCI&Oacute;N</td>
						<th class='nombres_columnas' align='center'>MUNICIPIO/ LOCALIDAD</th>
						<th class='nombres_columnas' align='center'>ESTADO</th>
						<th class='nombres_columnas' align='center'>PAIS</th>
						<th class='nombres_columnas' align='center'>NACIONALIDAD</th>
						<th class='nombres_columnas' align='center'>TEL&Eacute;FONO</th>
						<th class='nombres_columnas' align='center'>LUGAR NACIMIENTO</th>
						<th class='nombres_columnas' align='center'>FECHA NACIMIENTO</th>
						<th class='nombres_columnas' align='center'>ESTADO CIVIL</th>
						<th class='nombres_columnas' align='center'>DISCAPACIDAD</th>
						<th class='nombres_columnas' align='center'>HIJOS DEPENDIENTES ECONOMICOS</th>
						<td class='nombres_columnas' align='center'>CONTACTO POR ACCIDENTE</td>
						<td class='nombres_columnas' align='center'>OBSERVACIONES</td>
						
						<td class='nombres_columnas' align='center'>FOTOGRAF&Iacute;A</td>
						<td class='nombres_columnas' align='center'>BENEFICIARIOS</td>
						<td class='nombres_columnas' align='center'>CAPACITACIONES</td>
						<td class='nombres_columnas' align='center'>BECARIOS</td>
						<td class='nombres_columnas' align='center'>CARATULA</td>
					</tr>
					</thead>";
			$nom_clase = "renglon_gris";
			$cont = 1;				
			echo "<tbody>";
			do{	
				$ctrl_imagen = "";
				if($datos['mime']=="")
					$ctrl_imagen = "disabled='disabled'";	
				
				$nivelEstudios="";
				switch($datos["nivel_estudio"]){
					case 1:
						$nivelEstudios="PRIMARIA";
						break;
					case 2:
						$nivelEstudios="SECUNDARIA";
						break;
					case 3:
						$nivelEstudios="BACHILLERATO";
						break;
					case 4:
						$nivelEstudios="CARRERA T&Eacute;CNICA";
						break;
					case 5:
						$nivelEstudios="LICENCIATURA";
						break;
					case 6:
						$nivelEstudios="ESPECIALIDAD";
						break;
					case 7:
						$nivelEstudios="MAESTR&Iacute;A";
						break;
					case 8:
						$nivelEstudios="DOCTORADO";
						break;
				}
				
				$titulo="";
				switch($datos["titulo"]){
					case 1:
						$titulo="T&Iacute;TULO";
						break;
					case 2:
						$titulo="CERTIFICADO";
						break;
					case 3:
						$titulo="DIPLOMA";
						break;
					case 4:
						$titulo="OTRO";
						break;
				}
				
				$tipoEscuela="";
				switch($datos["tipo_escuela"]){
					case 1:
						$tipoEscuela="P&Uacute;BLICA";
						break;
					case 2:
						$tipoEscuela="PRIVADA";
						break;
				}
				
				echo "	<tr>					
						<td class='nombres_filas' align='center'>$datos[rfc_empleado]</td>
						<td class='$nom_clase' align='left'>$datos[curp]</td>
						<td class='$nom_clase' align='center'>$datos[id_empleados_empresa]</td>
						<td class='$nom_clase' align='center'>$datos[id_empleados_area]</td>
						<td class='$nom_clase' align='center'>$datos[nombre] $datos[ape_pat] $datos[ape_mat]</td>
						<td class='$nom_clase' align='center'>$".number_format($datos["sueldo_diario"],2,".",",")."</td>
						<td class='$nom_clase' align='left'>$datos[tipo_sangre]</td>
						<td class='$nom_clase' align='center'>$datos[no_ss]</td>
						<td class='$nom_clase' align='left'>".modFecha($datos["fecha_ingreso"],2)."</td>
						<td class='$nom_clase' align='left'>".round((restarFechas($datos["fecha_ingreso"],date("Y-m-d"))/365),2)." a&ntilde;os</td>
						<td class='$nom_clase' align='left'>$datos[puesto]</td>
						<td class='$nom_clase' align='center'>$datos[no_cta]</td>
						<td class='$nom_clase' align='center'>$datos[area]</td>
						<td class='$nom_clase' align='center'>$datos[jornada]&nbsp;Hrs.</td>
						<td class='$nom_clase' align='center'>$datos[oc_esp]</td>
						
						<td class='$nom_clase' align='center'>$nivelEstudios</td> 
						<td class='$nom_clase' align='center'>$titulo</td>
						<td class='$nom_clase' align='center'>$datos[carrera]</td>
						<td class='$nom_clase' align='center'>$tipoEscuela</td>
						
						<td class='$nom_clase' align='center'>$datos[calle] $datos[num_ext] $datos[num_int] $datos[colonia] C.P. $datos[cp]</td>
						<td class='$nom_clase' align='center'>$datos[localidad]</td>
						<td class='$nom_clase' align='center'>$datos[estado]</td>
						<td class='$nom_clase' align='center'>$datos[pais]</td>
						<td class='$nom_clase' align='center'>$datos[nacionalidad]</td>
						<td class='$nom_clase' align='center'>$datos[telefono]</td>
						<td class='$nom_clase' align='center'>$datos[lugar_nacimiento]</td>
						<td class='$nom_clase' align='center'>".modFecha(calcularFecha(substr($datos["rfc_empleado"],4,6)),2)."</td>
						<td class='$nom_clase' align='center'>$datos[edo_civil]</td>
						<td class='$nom_clase' align='center'>$datos[discapacidad]</td>
						<td class='$nom_clase' align='center'>$datos[hijos_dep_eco]</td>
						<td class='$nom_clase' align='left'>Nombre: $datos[nom_accidente]<br>Tel: $datos[tel_accidente]<br>Cel: $datos[cel_accidente]</td>
						<td class='$nom_clase' align='left'>$datos[observaciones]</td>
						";
						?>

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
                        <!-- <td class="<?php echo $nom_clase; ?>" align="center">
							<input type="button" name="btn_caratula" class="botones" value="Caratula" onMouseOver="window.estatus='';return true" 
                            title="Ver Caratula del Empleado <?php echo $datos['rfc_empleado'];?>" onClick="javascript:window.open('VerCaratula.php?id_empleado=<?php echo $datos['rfc_empleado']; ?>','_blank','top=250, left=450, width=300, height=100, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');"/>							
						</td> -->
						<td class="<?php echo $nom_clase; ?>" align="center">
							<input type="button" name="btn_caratula" class="botones" value="Caratula" onMouseOver="window.estatus='';return true" title="Ver Caratula del Empleado <?php echo $datos['rfc_empleado'];?>" 
							onclick="window.open('../../includes/generadorPDF/registro_personal.php?id_empl=<?php echo $datos["id_empleados_empresa"]; ?>&usuario=<?php echo $_SESSION["usr_reg"]; ?>', '_blank','top=100, left=100, width=1035, height=723, status=no, menubar=no, resizable=yes, scrollbars=yes, toolbar=no, location=no, directories=no')"/>
						</td>
						<?php
				echo "
						</tr>";			
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
					
			}while($datos=mysql_fetch_array($rs)); 
			echo "</tbody>";	
			echo "</table>";
			echo "<input type='hidden' name='hdn_patron' id='hdn_patron' value='$patron'/>";
			return array($stm_sql,$patron);
		}
		else{
			if ($patron==1){
				echo "<br><br><br><br><br><br><br><br><p align='center' class='msje_correcto'>No hay Trabajadores Registrados con el Nombre <em><u>$_POST[txt_nombre]</u></em></p>";
			}
			if ($patron==2){
				echo "<br><br><br><br><br><br><br><br><p align='center' class='msje_correcto'>No hay Trabajadores Registrados</p>";
			}
			if ($patron==3){
				echo "<br><br><br><br><br><br><br><br><p align='center' class='msje_correcto'>No hay Trabajadores Registrados con el Nombre <em><u>$_POST[cmb_area]</u></em></p>";
			}
			if ($patron==4){
				echo "<br><br><br><br><br><br><br><br><p align='center' class='msje_correcto'>No hay Trabajadores Registrados con el Nombre <em><u>$_POST[txt_nombre_baja]</u></em></p>";
			}
			if ($patron==5){
				echo "<br><br><br><br><br><br><br><br><p align='center' class='msje_correcto'>No hay Trabajadores Registrados</p>";
			}
			return "";
		}

		//Cerramos la conexion con la Base de Datos
		mysql_close($conn);
	}//Fin de la funcion de mostrarEmpleados
	
	
	//Desplegar los Becarios Registrados en el arreglo de $beneficiarios
	function mostrarBeneficiariosReg($beneficiarios){
		//Variable que acumula el total del porcentaje de cada beneficiario
		$acum=0;
		echo "<table cellpadding='5' width='100%'>";
		echo "<caption><p class='msje_correcto'><strong>Beneficiarios Registrados a ".$_POST['txt_nombre']."</strong></p></caption>";
		echo "      			
			<tr>
				<td class='nombres_columnas' align='center'>NOMBRE</td>
        		<td class='nombres_columnas' align='center'>PARENTESCO</td>
			    <td class='nombres_columnas' align='center'>EDAD</td>
				<td class='nombres_columnas' align='center'>PORCENTAJE</td>
      		</tr>";
		$nom_clase = "renglon_gris";
		$cont = 1;
		foreach ($beneficiarios as $ind => $persona) {
			echo "<tr>";
			foreach ($persona as $key => $value) {
				switch($key){
					case "nombre":
						echo "<td class='nombres_filas'>$value</td>";
					break;
					case "parentesco":
						echo "<td class='$nom_clase'>$value</td>";
					break;
					case "edad":
						echo "<td class='$nom_clase' align='center'>$value A�OS</td>";
					break;
					case "porcentaje":
						echo "<td class='$nom_clase' align='center'>$value%</td>";
						$acum+=$value;
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
		echo 
		"<tr>
			<td colspan='2' align='right'>&nbsp;</td>
			<td class='nombres_filas' align='right'>TOTAL</td>
			<td class='nombres_columnas' align='center'>$acum%</td>
		</tr>
		<tr>
			<td colspan='4' align='right'><span id='error' class='msj_error'>Verificar Porcentaje Acumulado Total</span></td>
		</tr>
		";
		echo "</table>";
	}//Fin de la funcion mostrarBeneficiariosReg($beneficiarios)
	
	//Desplegar los Becarios Registrados en el arreglo de $becarios
	function mostrarBecariosReg($becarios){
		//Variable que acumula el total acumulado del valor de cada Beca asignada
		$acum=0;
		echo "<table cellpadding='5' width='100%'>";
		echo "<caption><p class='msje_correcto'><strong>Becarios Registrados de ".$_POST['txt_nombre']."</strong></p></caption>";
		echo "      			
			<tr>
				<td class='nombres_columnas' align='center'>NOMBRE</td>
        		<td class='nombres_columnas' align='center'>PARENTESCO</td>
			    <td class='nombres_columnas' align='center'>GRADO ESTUDIO</td>
				<td class='nombres_columnas' align='center'>PROMEDIO</td>
				<td class='nombres_columnas' align='center'>VALOR DE BECA</td>
      		</tr>";
		$nom_clase = "renglon_gris";
		$cont = 1;
		foreach ($becarios as $ind => $persona) {
			echo "<tr>";
			foreach ($persona as $key => $value) {
				switch($key){
					case "nombre":
						echo "<td class='nombres_filas'>$value</td>";
					break;
					case "parentesco":
						echo "<td class='$nom_clase'>$value</td>";
					break;
					case "grado_estudio":
						echo "<td class='$nom_clase' align='center'>$value</td>";
					break;
					case "promedio":
						echo "<td class='$nom_clase' align='center'>$value</td>";
					break;
					case "cantidad":
						echo "<td class='$nom_clase' align='center'>$value</td>";
						$acum+=$value;
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
		echo 
		"<tr>
			<td colspan='3' align='right'>&nbsp;</td>
			<td class='nombres_filas' align='right'>TOTAL</td>
			<td class='nombres_columnas' align='center'>$acum</td>
		</tr>";
		echo "</table>";
	}//Fin de la funcion mostrarBecariosReg($becarios)
	
	//Funcion que permite verificar si los valores introducidos suman menos del 100% segun el Arreglo de Sesion
	function verificarPorcentaje($beneficiarios,$prc){
		//Variable que acumular el total del porcentaje, su valor inicial es el porcentaje enviado a traves de la ultima caja de texto de porcentaje
		$acumulador=$prc;
		foreach ($beneficiarios as $ind => $porcentaje) {
			foreach ($porcentaje as $key => $value) {
				switch($key){
					case "porcentaje":
						//Sumar a la variable el valor de los porcentajes encontrados en el arreglo de Session
						$acumulador+=$value;
					break;
				}
			}
		}
		//Retornar el total acumulado
		return $acumulador;
	}
	
	//Funcion que permite verificar si los valores introducidos suman menos del 100% segun el Arreglo de Sesion y los Datos de la Base de Datos
	function verificarPorcentajeBase($rfc,$porcentaje,$beneficiarios){
		$totalPorcentaje=$porcentaje;
		$conn=conecta("bd_recursos");
		/*Se debe de verificar si el empleado ya tiene beneficiarios y que sus porcentajes no excedan el 100%*/
		$stm_sql_verificar="SELECT SUM(porcentaje) AS porcentaje FROM beneficiarios WHERE empleados_rfc_empleado='$rfc'";
		$rs_verificar=mysql_query($stm_sql_verificar);
		$dato=mysql_fetch_array($rs_verificar);
		if ($dato["porcentaje"]!=""){
			if ($dato["porcentaje"]==100)
				//Si los porcentajes en la BD alcanzan el 100%, retornar -1 indicando que ya no se pueden agregar Nuevos Beneficiarios
				$totalPorcentaje=-1;
			else{
				//Si beneficiarios viene con datos, revisar con el arreglo de beneficiarios
				if ($beneficiarios!=""){
					//En este else, se indica que el usuario tiene Beneficiarios registrados que no alcanzan a complementar el 100%
					foreach ($beneficiarios as $ind => $porcentaje) {
						foreach ($porcentaje as $key => $value) {
							switch($key){
								case "porcentaje":
									//Sumar a la variable el valor de los porcentajes encontrados en el arreglo de Session
									$totalPorcentaje+=$value;
								break;
							}
						}
					}
					$totalPorcentaje+=$dato["porcentaje"];
				}
				else{
					//Si el valor de beneficiarios es igual a vacio, sumar el total de la consulta con el valor enviado en la caja de Texto
					$totalPorcentaje+=$dato["porcentaje"];
				}
			}
		}
		else{
			//Si el porcentaje es regresado como null, el trabajador no tiene Beneficiarios registrados, en dicho caso, retornar 0
			$totalPorcentaje=0;
		}
		//Retornar $totalPorcentaje con el valor que haya tomado a lo largo de la validacion
		return $totalPorcentaje;
	}
?>