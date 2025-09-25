<?php
	/**
	  * Nombre del Módulo: Recursos Humanos-Desarrollo
	  * Nombre Programador: Antonio de Jesús Jiménez Cuevas
	  * Fecha: 15/Febrero/2012
	  * Descripción: Este archivo contiene funciones para Consultar un Empleado de la BD de Recursos y asignarle un Estado
	**/

	//Funcion que muestra uno o mas equipos segun el formulario frm_asignarRoles.php
	function mostrarTrabajadores(){
		//Creamos la sentencia SQL para mostrar los datos de todos los empleados que correspondan a DESARROLLO
		$stm_sql="SELECT rfc_empleado,CONCAT(nombre,' ',ape_pat,' ',ape_mat) AS nombre,area,puesto FROM empleados WHERE area='DESARROLLO' AND id_empleados_empresa>0 ORDER BY puesto,id_empleados_empresa";
		//Creamos el titulo de la tabla
		$titulo="Trabajadores y Rol de Trabajo";
		
		//Abrimos la conexion con la Base de datos
		$conn=conecta("bd_recursos");
			
		//Ejecutamos la sentencia SQL
		$rs=mysql_query($stm_sql);
		
		if ($datos=mysql_fetch_array($rs)){
			echo "<br />";
			echo "<table class='tabla_frm' cellpadding='5' width='100%'>";
			echo "<caption class='titulo_etiqueta'>$titulo</caption>";
			echo "<br />";
			echo "	<tr>
						<td class='nombres_columnas' align='center'>SELECCIONAR</td>
						<td class='nombres_columnas' align='center'>RFC</td>
						<td class='nombres_columnas' align='center'>NOMBRE</td>
						<td class='nombres_columnas' align='center'>&Aacute;REA</td>
						<td class='nombres_columnas' align='center'>PUESTO</td>
						<td class='nombres_columnas' align='center'>TURNO</td>
					</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;				
			echo "<tbody>";
			do{
				$rol=obtenerDato("bd_recursos","roles","turnos_id_turno","empleados_rfc_empleado",$datos["rfc_empleado"]);
				if ($rol=="")
					$accion="Agregar";
				else
					$accion="Modificar";
					
				$turno=obtenerDato("bd_recursos","turnos","nom_turno","id_turno",$rol);
				echo "	<tr>";?>
						<td class="nombres_filas" align="center">
							<input type="checkbox" name="ckb_seleccionar<?php echo $cont;?>" id="ckb_seleccionar<?php echo $cont;?>" value="<?php echo $datos["rfc_empleado"];?>" onclick="activarComboTurnos(this,cmb_turno<?php echo $cont;?>);"/>
						</td>
						<?php
				echo "					
						<td class='$nom_clase' align='center'>$datos[rfc_empleado]</td>
						<input  type='hidden' name='hdn_accion$cont' id='hdn_accion$cont' value='$accion'/>
						<input  type='hidden' name='hdn_nombre$cont' id='hdn_nombre$cont' value='$datos[nombre]'/>
						<td class='$nom_clase' align='left'>$datos[nombre]</td>
						<td class='$nom_clase' align='center'>$datos[area]</td>
						<td class='$nom_clase' align='left'>$datos[puesto]</td>";?>
						<td class="<?php echo $nom_clase; ?>" align="center">
							<?php
								//Llenado del combo mediante sentencia directa, para el caso de querer deshabilitar el combo de Turnos
								$stm_sqlCombo = "SELECT nom_turno,hora_entrada,hora_salida FROM turnos WHERE nom_turno!='' ORDER BY nom_turno";
								$rsCombo = mysql_query($stm_sqlCombo);
								//Confirmar que la consulta fue exitosa y guardar los datos en el ComboBox		
								if($datosCombo = mysql_fetch_array($rsCombo)){
									//Declarar el ComboBox con el nombre especificado en el parametro $nom_combo
									echo "<select name='cmb_turno$cont' id='cmb_turno$cont' class='combo_box' disabled='disabled'>";
									//Colocar el mensaje en definido en el parametro $msj para ser desplegado en el ComboBox
									echo "<option value=''>Turnos</option>";
									do{
										$horaE=modHora($datosCombo["hora_entrada"]);
										$horaS=modHora($datosCombo["hora_salida"]);
										if($datosCombo["nom_turno"]==$turno)//Colocar el valor preseleccionado
											echo "<option value='$datosCombo[nom_turno]'selected='selected' title='ENTRADA: $horaE - SALIDA: $horaS'>$datosCombo[nom_turno]</option>";
										else
											echo "<option value='$datosCombo[nom_turno]' title='ENTRADA: $horaE - SALIDA: $horaS'>$datosCombo[nom_turno]</option>";
									}while($datosCombo = mysql_fetch_array($rsCombo));
									echo "</select>";
								}
							?>
						</td>
						<input type="hidden" name="hdn_opt<?php echo $cont;?>" id="hdn_opt<?php echo $cont;?>" value="ckb_seleccionar<?php echo $cont;?>.options.selectedIndex"/>
						<?php
				echo "	</tr>";			
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
					
			}while($datos=mysql_fetch_array($rs)); 
			echo "<input  type='hidden' name='hdn_cantidad' id='hdn_cantidad' value='$cont'/>";
			echo "</table>";
			return $stm_sql;
		}
		else{
			echo "<br><br><br><br><br><br><br><br><p align='center' class='msje_correcto'>No hay Trabajadores Registrados</p>";
			//Cerramos la conexion con la Base de Datos
			mysql_close($conn);
			return "";
		}
	}//Fin de la funcion de mostrarEmpleados
	
	//Funcion que asignar los Turnos a los Trabajadores Seleccionados
	function asignarRol(){
		$tam=$_POST["hdn_cantidad"]-1;
		$cont=1;
		$conn=conecta("bd_recursos");
		do{
			if (isset($_POST["ckb_seleccionar".$cont])){
				$band=0;
				//Obtener el RFC del registro seleccionado
				$rfc=$_POST["ckb_seleccionar".$cont];
				//Obtener el nombre del Turno seleccionado
				$id_turno=$_POST["cmb_turno".$cont];
				//Obtener el Id del Turno seleccionado para guardarlo en la Tabla
				$id_turno=obtenerDato("bd_recursos","turnos","id_turno","nom_turno",$id_turno);
				if ($_POST["hdn_accion".$cont]=="Agregar")
					$stm_sql="INSERT INTO roles (empleados_rfc_empleado,turnos_id_turno) VALUES ('$rfc','$id_turno')";
				if ($_POST["hdn_accion".$cont]=="Modificar")
					$stm_sql="UPDATE roles SET turnos_id_turno='$id_turno' WHERE empleados_rfc_empleado='$rfc'";
				//Ejecutar la sentencia SQL
				$rs=mysql_query($stm_sql);
				//Si la sentencia genero errores, romper el proceso tras haber atrapado el error
				if (!$rs){
					$band=1;
					$error=mysql_error();
					break;
				}
			}
			$cont++;
		}while($cont<=$tam);
		if ($band==1){
			?>
			<script type="text/javascript" language="javascript">
				setTimeout("alert('Hubo un Problema con: <?php echo $error;?>');",1000);
			</script>
			<?php
		}
		else{
			?>
			<script type="text/javascript" language="javascript">
				setTimeout("alert('Actualización de Turno(s) Realizada con Éxito');",1000);
			</script>
			<?php
		}
		mysql_close($conn);
	}//Fin de asignarRol()
?>