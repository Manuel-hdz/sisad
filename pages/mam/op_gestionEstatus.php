<?php
	/**
	  * Nombre del Módulo: Mantenimiento
	  * Nombre Programador: Antonio de Jesús Jiménez Cuevas
	  * Fecha: 02/Agosto/2012
	  * Descripción: Este archivo contiene funciones para Consultar los Aceites y el Catálogo de la BD de Mantenimiento
	**/

	//Funcion que muestra los Equipos para registrarles su consumo de aceite
	function mostrarEquipos(){
		//Abrimos la conexion con la Base de datos
		$conn=conecta("bd_mantenimiento");
		//Creamos la sentencia SQL para mostrar los datos del Equipo
		$stm_sql="SELECT id_equipo,nom_equipo FROM equipos WHERE area='MINA'";
		//Ejecutamos la sentencia SQL
		$rs=mysql_query($stm_sql);
		//Cantidad de Equipos
		$cantEquipos=mysql_num_rows($rs);
		//Extraer los datos de los Equipos
		if ($datos=mysql_fetch_array($rs)){
			echo "<br>";
			echo "<table class='tabla_frm' cellpadding='5' width='100%'>";
			echo "<caption class='titulo_etiqueta'>Registrar Status de los Equipos<br>";
			echo "	<tr>
						<td class='nombres_columnas' align='center' colspan='2'>SELECCIONAR</td>
						<td class='nombres_columnas' align='center' rowspan='2'>ID EQUIPO</td>
						<td class='nombres_columnas' align='center' rowspan='2'>TURNO</td>
						<td class='nombres_columnas' align='center' rowspan='2'>STATUS</td>
						<td class='nombres_columnas' align='center' rowspan='2'>OBSERVACIONES</td>
					</tr>";
			echo "	<tr>
						<td class='nombres_columnas' align='center'>No.</td>
						<td class='nombres_columnas' align='center'>REGISTRAR</td>
					</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{	
				echo "	<tr>					
						<td class='$nom_clase' align='center' rowspan='3'>$cont</td>";
						?>
						<td class="<?php echo $nom_clase;?>" rowspan="3">
							<input type="checkbox" name="ckb_equipo<?php echo $cont?>" id="ckb_equipo<?php echo $cont?>" value="<?php echo $datos["id_equipo"];?>"
							onclick="activarRegEstatus(this,'<?php echo $cont?>','<?php echo $datos["id_equipo"]?>');"/>
						</td>
						<?php
				echo "
						<td class='$nom_clase' align='center' rowspan='3'>$datos[id_equipo]</td>
						<td class='$nom_clase' align='center' >PRIMERA</td>
						<td class='$nom_clase' align='center' >
							<select class='combo_box' name='cmb_disponible$cont"."°1°$datos[id_equipo]' id='cmb_disponible$cont"."°1°$datos[id_equipo]' disabled='disabled'>
								<option value='DISPONIBLE' selected='selected'>DISPONIBLE</option>
								<option value='NO DISPONIBLE'>NO DISPONIBLE</option>
							</select>
						</td>
						<td class='$nom_clase' align='center' >
							<textarea name=\"txa_observaciones$cont"."°1°$datos[id_equipo]\" id=\"txa_observaciones$cont"."°1°$datos[id_equipo]\" maxlength=\"160\" onkeyup=\"return ismaxlength(this)\" 
							class=\"caja_de_texto\" rows=\"3\" cols=\"30\" onkeypress=\"return permite(event,'num_car', 0);\" disabled='disabled'></textarea>
						</td>";
				echo"	</tr>";
				echo "	<tr>
						<td class='$nom_clase' align='center' >SEGUNDA</td>
						<td class='$nom_clase' align='center' >
							<select class='combo_box' name='cmb_disponible$cont"."°2°$datos[id_equipo]' id='cmb_disponible$cont"."°2°$datos[id_equipo]' disabled='disabled'>
								<option value='DISPONIBLE' selected='selected'>DISPONIBLE</option>
								<option value='NO DISPONIBLE'>NO DISPONIBLE</option>
							</select>
						</td>
						<td class='$nom_clase' align='center' >
							<textarea name=\"txa_observaciones$cont"."°2°$datos[id_equipo]\" id=\"txa_observaciones$cont"."°2°$datos[id_equipo]\" maxlength=\"160\" onkeyup=\"return ismaxlength(this)\" 
							class=\"caja_de_texto\" rows=\"3\" cols=\"30\" onkeypress=\"return permite(event,'num_car', 0);\" disabled='disabled'></textarea>
						</td>
						</tr>";
				echo "	<tr>
						<td class='$nom_clase' align='center' >TERCERA</td>
						<td class='$nom_clase' align='center' >
							<select class='combo_box' name='cmb_disponible$cont"."°3°$datos[id_equipo]' id='cmb_disponible$cont"."°3°$datos[id_equipo]' disabled='disabled'>
								<option value='DISPONIBLE' selected='selected'>DISPONIBLE</option>
								<option value='NO DISPONIBLE'>NO DISPONIBLE</option>
							</select>
						</td>
						<td class='$nom_clase' align='center' >
							<textarea name=\"txa_observaciones$cont"."°3°$datos[id_equipo]\" id=\"txa_observaciones$cont"."°3°$datos[id_equipo]\" maxlength=\"160\" onkeyup=\"return ismaxlength(this)\" 
							class=\"caja_de_texto\" rows=\"3\" cols=\"30\" onkeypress=\"return permite(event,'num_car', 0);\" disabled='disabled'></textarea>
						</td>
						</tr>";
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
					
			}while($datos=mysql_fetch_array($rs)); 	
			$cont--;
			echo "<input type='hidden' value='$cont' name='hdn_cantidad' id='hdn_cantidad'/>";
			echo "</table>";
		}
		//Cerramos la conexion con la Base de Datos
		mysql_close($conn);
	}//Fin de function mostrarEquipos($fecha,$familia)
	
	//Funcion que guarda el registro de status de equipos
	function guardarStatusEquipos(){
		//Contador para recorrer los checks
		$cont=1;
		//Variable para verificar la ejecucion
		$band=0;
		$fecha=modFecha($_POST["txt_fecha"],3);
		$cant=$_POST["hdn_cantidad"];
		//Abrir la conexion a la BD
		$conn=conecta("bd_mantenimiento");
		do{
			if(isset($_POST["ckb_equipo$cont"])){
				$idEquipo=$_POST["ckb_equipo$cont"];
				$numTurno=1;
				do{
					//Averiguar el Nombre de Turno
					if($numTurno==1)
						$turno="TURNO DE PRIMERA";
					elseif($numTurno==2)
						$turno="TURNO DE SEGUNDA";
					elseif($numTurno==3)
						$turno="TURNO DE TERCERA";
					//Obtener la Disponibilidad y Observaciones
					$dispTurno=$_POST["cmb_disponible$cont"."°"."$numTurno"."°$idEquipo"];
					$observaciones=strtoupper($_POST["txa_observaciones$cont"."°"."$numTurno"."°$idEquipo"]);
					//Crear la sentencia SQL
					$sql="INSERT INTO estatus (equipos_id_equipo,fecha,turno,disponibilidad,observaciones) VALUES ('$idEquipo','$fecha','$turno','$dispTurno','$observaciones')";
					$rs=mysql_query($sql);
					if(!$rs){
						$band=1;
						break;
					}
					//Incrementar el contador
					$numTurno++;
				}while($numTurno<=3);
			}
			if($band==1)
				break;
			//Incrementar el contador
			$cont++;
		}while($cont<$cant);
		if($band==1)
			$error=mysql_error();
		//Cerrar la BD
		mysql_close($conn);
		if($band==1)
			//Si los datos no se agregaron correctamente, se redirecciona a la pagina de error
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
		else{
			//Registrar la Operacion
			registrarOperacion("bd_mantenimiento",$fecha,"RegistrarEstatusEquipo",$_SESSION['usr_reg']);
			//Abrir Formato PDF
			?>
			<script type='text/javascript' language='javascript'>
					setTimeout("window.open('../../includes/generadorPDF/statusEquipos.php?fecha=<?php echo $fecha; ?>', '_blank','top=0, left=0, width=1035, height=723, status=no, menubar=yes, resizable=yes, scrollbars=yes, toolbar=no, location=no, directories=no')",4000);
			</script>
			<?php
			//Enviar a exito si todo salio bien
			echo "<meta http-equiv='refresh' content='5;url=exito.php'>";
		}
	}
?>