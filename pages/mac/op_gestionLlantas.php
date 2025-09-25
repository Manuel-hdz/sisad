<?php
	/**
	  * Nombre del Módulo: Mantenimiento
	  * Nombre Programador: Antonio de Jesús Jiménez Cuevas
	  * Fecha: 18/Octubre/2012
	  * Descripción: Este archivo contiene funciones para Consultar las Llantas y el Catálogo de la BD de Mantenimiento
	**/

	//Esta funcion Muestra los Aceites del Catálogo
	function mostrarLlantas(){
		//Abrimos la conexion con la Base de datos
		$conn=conecta("bd_mantenimiento");
		//Creamos la sentencia SQL para mostrar los datos del Equipo
		$stm_sql="SELECT * FROM llantas";
		//Ejecutamos la sentencia SQL
		$rs=mysql_query($stm_sql);
		if ($datos=mysql_fetch_array($rs)){
			echo "<table class='tabla_frm' cellpadding='5' width='100%'>";
			echo "<caption class='titulo_etiqueta'>Cat&aacute;logo de Llantas</caption>";
			echo "	<tr>
						<td class='nombres_columnas' align='center'>NO.</td>
						<td class='nombres_columnas' align='center'>NOMBRE</td>
						<td class='nombres_columnas' align='center'>MARCA</td>
						<td class='nombres_columnas' align='center'>FAMILIA</td>
						<td class='nombres_columnas' align='center'>MEDIDA LLANTA</td>
						<td class='nombres_columnas' align='center'>MEDIDA RIN</td>
						<td class='nombres_columnas' align='center'>PIEZAS NUEVAS</td>
						<td class='nombres_columnas' align='center'>PIEZAS PARA REUSO</td>
						<td class='nombres_columnas' align='center'>PIEZAS DESHECHADAS</td>
						<td class='nombres_columnas' align='center'>TOTAL PIEZAS UTILES</td>
					</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;	
			$estado="";
			do{	
				$nuevo = obtenerNumeroLlantas("NUEVA",$datos["id_marca"]);
				$usada = obtenerNumeroLlantas("USADA",$datos["id_marca"]);
				$desechada = obtenerNumeroLlantas("DESECHADA",$datos["id_marca"]);
				$total = $nuevo + $usada;
				$estilo="";
				if($total==0)
					$estilo="style='color:#FF0000'";
				echo "	<tr>					
						<td class='nombres_filas' align='center'>$datos[id_marca]</td>
						<td class='$nom_clase' align='center'>$datos[descripcion]</td>
						<td class='$nom_clase' align='center'>$datos[marca]</td>
						<td class='$nom_clase' align='center'>$datos[familia]</td>
						<td class='$nom_clase' align='center'>$datos[medida]</td>
						<td class='$nom_clase' align='center'>$datos[medida_rin]</td>
						<td class='$nom_clase' align='center'>$nuevo PZA(S)</td>
						<td class='$nom_clase' align='center'>$usada PZA(S)</td>
						<td class='$nom_clase' align='center'>$desechada PZA(S)</td>
						<td class='$nom_clase' align='center' $estilo>$total PZA(S)</td>
						</tr>";			
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
			}while($datos=mysql_fetch_array($rs)); 	
			echo "</table>";
		}
		//Cerramos la conexion con la Base de Datos
		mysql_close($conn);
	}//Fin de la funcion para mostrar el Catálogo de Aceites
	
	//Esta funcion se encargar de Actualizar el catálogo de Aceites
	function guardarActualizacionLlanta(){
		//Obtener el ID para la bitacora de Aceite
		$idBitacora=obtenerIdBitacoraLlanta();
		//Fecha
		$fecha=date("Y-m-d");
		//Verificar el tipo de Movimiento a realizar en la bitacora
		if($_POST["hdn_estado"]=="Agregar"){
			$tipoMov="N";
			$idLlanta=obtenerIdLlanta();
			//Abrimos la conexion con la Base de datos
			$conn=conecta("bd_mantenimiento");
			$nombre=strtoupper($_POST["cmb_llanta"]);
			$marca=strtoupper($_POST["cmb_marca"]);
			$familiaEquipos=strtoupper($_POST["cmb_equipos"]);
			$medida=strtoupper($_POST["txt_medida"]);
			$medidaRin=strtoupper($_POST["txt_medidaRin"]);
			//$nuevas=str_replace(",","",$_POST["txt_nuevas"]);
			//$reuso=str_replace(",","",$_POST["txt_reuso"]);
			//$deshecho=str_replace(",","",$_POST["txt_deshecho"]);
			//$costo=str_replace(",","",$_POST["txt_costo"]);
			//Sentencia para agregar la Llanta al "STOCK"
			//$sql_stm="INSERT INTO llantas (id_llanta,descripcion,marca,familia,medida,medida_rin,nueva,reuso,deshecho) 
			//			VALUES ('$idLlanta','$nombre','$marca','$familiaEquipos','$medida','$medidaRin','$nuevas','$reuso','$deshecho')";
			
			$sql_stm = "INSERT INTO llantas (id_marca,descripcion,marca,familia,medida,medida_rin) 
						VALUES ('$idLlanta','$nombre','$marca','$familiaEquipos','$medida','$medidaRin')";
			
			$rs=mysql_query($sql_stm);
			if($rs){
				//Sentencia para registrar el movimiento en la Bitacora
				/*$sql_stm="INSERT INTO bitacora_llantas (id_bitacora,area,equipo,responsable,metrica,fecha,turno,cambiadas,recuperadas,deshechadas,tipo_mov) 
							VALUES ('$idBitacora','CONCRETO','','',0,'$fecha','','0','0','0','$tipoMov')";
				$rs=mysql_query($sql_stm);
				if($rs){
					$cantIngreso=$nuevas+$reuso;
					//Sentencia para registrar el movimiento en la Bitacora
					$sql_stm = "INSERT INTO detalle_bitacora_llantas (bitacora_llantas_id_bitacora,llantas_id_llanta,tipo,cantidad,costo) 
								VALUES ('$idBitacora','$idLlanta','NUEVAS','$nuevas',0)";
					$rs=mysql_query($sql_stm);
					
					$sql_stm = "INSERT INTO detalle_bitacora_llantas (bitacora_llantas_id_bitacora,llantas_id_llanta,tipo,cantidad,costo) 
								VALUES ('$idBitacora','$idLlanta','REUSO','$reuso',0)";
					$rs=mysql_query($sql_stm);
					
					if($rs){*/
						//Cerramos la conexion con la Base de Datos
						mysql_close($conn);
						//Registrar el movimiento en la bitácora de Movimientos
						registrarOperacion("bd_mantenimiento","$idLlanta","RegistroTipoLlanta",$_SESSION['usr_reg']);
						?>
						<script type="text/javascript" language="javascript">
							setTimeout("alert('¡Tipo de Llanta Registrada con Éxito!');",1000);
						</script>
						<?php
					//}
				//}
			}
			else{
				?>
				<script type="text/javascript" language="javascript">
					setTimeout("alert('La Llanta NO pudo ser Registrada');",1000);
				</script>
				<?php
				mysql_close($conn);
			}
		}
		else{
			$tipoMov="E";
			//Abrimos la conexion con la Base de datos
			$conn=conecta("bd_mantenimiento");
			$idLlanta=strtoupper($_POST["cmb_llanta"]);
			$marca=strtoupper($_POST["cmb_marca"]);
			$familiaEquipos=strtoupper($_POST["cmb_equipos"]);
			$medida=strtoupper($_POST["txt_medida"]);
			$medidaRin=strtoupper($_POST["txt_medidaRin"]);
			//$nueva=str_replace(",","",$_POST["txt_nuevas"]);
			//$reuso=str_replace(",","",$_POST["txt_reuso"]);
			//$deshecho=str_replace(",","",$_POST["txt_deshecho"]);
			//$costo=str_replace(",","",$_POST["txt_costo"]);
			//Extraer la existencia de llantas nuevas y de reuso
			//$existencia=mysql_fetch_array(mysql_query("SELECT nueva,reuso FROM llantas WHERE id_llanta='$idLlanta'"));
			//if($existencia["nueva"]<=$nueva && $existencia["reuso"]<=$reuso){
				//Sentencia para actualizar la llanta en Stock
				$sql_stm="UPDATE llantas SET marca='$marca',familia='$familiaEquipos',medida='$medida',medida_rin='$medidaRin' WHERE id_marca='$idLlanta'";
				$rs=mysql_query($sql_stm);
				if($rs){
					//Sentencia para registrar el movimiento en la Bitacora
					/*$sql_stm="INSERT INTO bitacora_llantas (id_bitacora,area,equipo,responsable,metrica,fecha,turno,cambiadas,recuperadas,deshechadas,tipo_mov) 
							VALUES ('$idBitacora','CONCRETO','','',0,'$fecha','','".($nueva-$existencia["nueva"])."','".($reuso-$existencia["reuso"])."','0','$tipoMov')";
					$rs=mysql_query($sql_stm);
					if($rs){
						$cantIngreso=($nueva-$existencia["nueva"]) + ($reuso-$existencia["reuso"]);
						//Sentencia para registrar el movimiento en la Bitacora
						$sql_stm="INSERT INTO detalle_bitacora_llantas (bitacora_llantas_id_bitacora,llantas_id_llanta,cantidad,costo_unitario) VALUES ('$idBitacora','$idLlanta','$cantIngreso',0)";
						$rs=mysql_query($sql_stm);
						if($rs){*/
							//Cerramos la conexion con la Base de Datos
							mysql_close($conn);
							//Registrar el movimiento en la bitácora de Movimientos
							registrarOperacion("bd_mantenimiento","$idLlanta","ActualizarTipoLlanta",$_SESSION['usr_reg']);
							?>
							<script type="text/javascript" language="javascript">
								setTimeout("alert('¡Tipo de Llanta Actualizada con Éxito!');",1000);
							</script>
							<?php
						//}
					//}
				}
				else{
					//Cerrar la conexion
					mysql_close($conn);
					?>
					<script type="text/javascript" language="javascript">
						setTimeout("alert('La Llanta NO pudo ser Actualizada');",1000);
					</script>
					<?php
				}
			//}
			/*else{
				//Cerrar la conexion
				mysql_close($conn);
				?>
				<script type="text/javascript" language="javascript">
					setTimeout("msjeError();",1000);
					function msjeError(){
						alert('La Existencia de Llantas NO pudo ser Actualizada.\nError: No se puede Registrar Directamente una Disminución de Llantas Nuevas ni de Reuso, debe Registrarse a través de la Bitácora')
					}
				</script>
				<?php
			}*/
		}
	}//Fin de function guardarActualizacionLlanta()
	
	function guardarDetalleLlanta(){
		//Abrimos la conexion con la Base de datos
		$conn=conecta("bd_mantenimiento");
		
		$id_marca = strtoupper($_POST["cmb_tipo"]);
		$id_llan = strtoupper($_POST["txt_llanta"]);
		//$posicion = strtoupper($_POST["txt_posicion"]);
		$odometro = str_replace(",","",$_POST["txt_metrica"]);
		$horometro = str_replace(",","",$_POST["txt_horometro"]);
		$estado = strtoupper($_POST["cmb_estado"]);
		$costo = str_replace(",","",$_POST["txt_costoUni"]);
		
		$sql_stm = "INSERT INTO detalle_llantas (id_llanta,posicion,odometro,horometro,estado,id_marca,costo_unit) 
					VALUES ('$id_llan','','$odometro','$horometro','$estado','$id_marca','$costo')";
		
		$rs=mysql_query($sql_stm);
		if($rs){
			//Cerramos la conexion con la Base de Datos
				mysql_close($conn);
				//Registrar el movimiento en la bitácora de Movimientos
				registrarOperacion("bd_mantenimiento","$id_llan","RegistroDetalleLlanta",$_SESSION['usr_reg']);
				?>
				<script type="text/javascript" language="javascript">
					setTimeout("alert('Llanta Registrada con Éxito!');",1000);
				</script>
				<?php
		}
		else{
			?>
			<script type="text/javascript" language="javascript">
				setTimeout("alert('La Llanta NO pudo ser Registrada');",1000);
			</script>
			<?php
			mysql_close($conn);
		}
	}//Fin de function guardarActualizacionLlanta()
	
	//Funcion que calcula el id para la bitacora de Aceites
	function obtenerIdBitacoraLlanta(){
		//Realizar la conexion a la BD de Mantenimiento
		$conn = conecta("bd_mantenimiento");
		
		//Definir las tres letras en la Id de la Orden de Trabajo
		$id_cadena = "BITLLAN";
		//Obtener el mes y el año
		$fecha = date("m-Y");
		$id_cadena .= substr($fecha,0,2).substr($fecha,5,2);
		//Obtener el mes actual y el año actual 
		$mes = substr($fecha,0,2);
		$anio = substr($fecha,5,2);
		
		//Crear la sentencia para obtener la Orden de Trabajo Reciente acorde a la fecha
		$stm_sql = "SELECT MAX(id_bitacora) AS cant FROM bitacora_llantas WHERE id_bitacora LIKE 'BITLLAN$mes$anio%'";
		$rs = mysql_query($stm_sql);
		if($datos=mysql_fetch_array($rs)){
			//Obtener las ultimas 3 cifras de la orden de trabajo registrada en la BD y sumarle 1
			$cant = substr($datos['cant'],-3)+1;
			if($cant>0 && $cant<10)
				$id_cadena .= "00".$cant;
			if($cant>9 && $cant<100)
				$id_cadena .= "0".$cant;
			if($cant>=100)
				$id_cadena .= $cant;
		}
		//Cerrar la conexion con la BD		
		mysql_close($conn);
		
		return $id_cadena;
	}//Fin de obtenerIdBitacoraAceite()
	
	//Funcion que calcula el id para la bitacora de Aceites
	function obtenerIdLlanta(){
		//Realizar la conexion a la BD de Mantenimiento
		$conn = conecta("bd_mantenimiento");
		
		//Definir las tres letras en la Id de la Orden de Trabajo
		$id_cadena = "LLAN";
		//Obtener el mes y el año
		$fecha = date("m-Y");
		//$id_cadena .= substr($fecha,0,2).substr($fecha,5,2);
		//Obtener el mes actual y el año actual 
		$mes = substr($fecha,0,2);
		$anio = substr($fecha,5,2);
		
		//Crear la sentencia para obtener la Orden de Trabajo Reciente acorde a la fecha
		$stm_sql = "SELECT MAX(id_marca) AS cant FROM llantas WHERE id_marca LIKE 'LLAN%'";
		$rs = mysql_query($stm_sql);
		if($datos=mysql_fetch_array($rs)){
			//Obtener las ultimas 3 cifras de la orden de trabajo registrada en la BD y sumarle 1
			$cant = substr($datos['cant'],-3)+1;
			if($cant>0 && $cant<10)
				$id_cadena .= "00".$cant;
			if($cant>9 && $cant<100)
				$id_cadena .= "0".$cant;
			if($cant>=100)
				$id_cadena .= $cant;
		}
		//Cerrar la conexion con la BD		
		mysql_close($conn);
		
		return $id_cadena;
	}//Fin de obtenerIdBitacoraAceite()
	
	//funcion que registra en la bitacora las salidas de las llantas
	/*function guardarRegistroLlantas(){
		//Obtener el ID para la bitacora de Llantas
		$idBitacora=obtenerIdBitacoraLlanta();
		$area="MANTENIMIENTO";
		//Recuperar los datos del POST
		$equipo=$_POST["cmb_equipo"];
		$responsable=$_POST["cmb_responsable"];
		$metrica=str_replace(",","",$_POST["txt_metrica"]);
		$fecha=modFecha($_POST["txt_fecha"],3);
		$turno=$_POST["cmb_turno"];
		//Obtener las llantas que se quitaron del Equipo
		$idLlantaRetirada=$_POST["cmb_llantaRetirada"];
		$recuperadas=str_replace(",","",$_POST["txt_reusables"]);
		$deshechadas=str_replace(",","",$_POST["txt_deshechables"]);
		//Calcular la cantidad de llantas que se modificaron
		$cambiadas=$recuperadas+$deshechadas;
		$tipoMov="S";
		//Recuperar los datos que forman el detalle
		$idLlantaColocada=$_POST["cmb_llantaColocada"];
		$cantNuevas=str_replace(",","",$_POST["txt_nuevas"]);
		$cantReuso=str_replace(",","",$_POST["txt_reuso"]);
		//Abrir la conexion a la BD
		$conn=conecta("bd_mantenimiento");
		//Variable para controlar el flujo de guardado
		$band=0;
		//Sentencias SQL
		$sql="INSERT INTO bitacora_llantas (id_bitacora,area,equipo,responsable,metrica,fecha,turno,cambiadas,recuperadas,deshechadas,tipo_mov) 
			VALUES ('$idBitacora','$area','$equipo','$responsable','$metrica','$fecha','$turno','$cambiadas','$recuperadas','$deshechadas','$tipoMov')";
		//Ejecutar la sentencia SQL
		$rs=mysql_query($sql);
		//Verificar el resultado de la sentencia
		if(!$rs){
			$band=1;
			$error = mysql_error();	
			mysql_close($conn);		
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
		}
		if($cantNuevas>0 && $band==0){
			//Recuperar el costo Unitario de cada Llanta
			$costo=str_replace(",","",$_POST["txt_costoUniNvas"])*$cantNuevas;
			$sql="INSERT INTO detalle_bitacora_llantas (bitacora_llantas_id_bitacora,llantas_id_llanta,tipo,cantidad,costo) VALUES ('$idBitacora','$idLlantaColocada','NUEVAS','$cantNuevas','$costo')";
			//Ejecutar la sentencia SQL
			$rs=mysql_query($sql);
			//Verificar el resultado de la sentencia
			if(!$rs){
				$band=1;
				$error = mysql_error();			
				echo "</br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br>".mysql_error();
				break;
				mysql_close($conn);
				echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
			}
		}
		if($cantReuso>0 && $band==0){
			//Recuperar el costo Unitario de cada Llanta
			$costo=str_replace(",","",$_POST["txt_costoUniReuso"])*$cantReuso;
			$sql="INSERT INTO detalle_bitacora_llantas (bitacora_llantas_id_bitacora,llantas_id_llanta,tipo,cantidad,costo) VALUES ('$idBitacora','$idLlantaColocada','REUSO','$cantReuso','$costo')";
			//Ejecutar la sentencia SQL
			$rs=mysql_query($sql);
			//Verificar el resultado de la sentencia
			if(!$rs){
				$band=1;
				$error = mysql_error();			
				echo "</br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br>".mysql_error();
				break;
				mysql_close($conn);
				echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
			}
		}
		if($band==0){
			$sql="UPDATE llantas SET nueva=nueva-$cantNuevas, reuso=reuso-$cantReuso WHERE id_llanta='$idLlantaColocada'";
			//Ejecutar la sentencia SQL
			$rs=mysql_query($sql);
			//Verificar el resultado de la sentencia
			if(!$rs){
				$band=1;
				$error = mysql_error();			
				echo "</br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br>".mysql_error();
				break;
				mysql_close($conn);
				echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
			}
		}
		if($band==0){
			$sql="UPDATE llantas SET reuso=reuso+$recuperadas,deshecho=deshecho+$deshechadas WHERE id_llanta='$idLlantaRetirada'";
			//Ejecutar la sentencia SQL
			$rs=mysql_query($sql);
			//Verificar el resultado de la sentencia
			if(!$rs){
				$band=1;
				$error = mysql_error();
				echo "</br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br>".mysql_error();
				break;
				mysql_close($conn);			
				echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
			}
		}
		if($band==0){
			mysql_close($conn);
			registrarOperacion("bd_mantenimiento","$idBitacora","RegistrarBitacoraLlantas",$_SESSION['usr_reg']);
			echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
		}
	}*/
	
	function guardarRegistroLlantas(){
		//Obtener el ID para la bitacora de Llantas
		$idBitacora=obtenerIdBitacoraLlanta();
		$area="MANTENIMIENTO";
		//Recuperar los datos del POST
		$equipo=$_POST["cmb_equipo"];
		$responsable=$_POST["hdn_rfc"];
		$odometro=str_replace(",","",$_POST["txt_odometro"]);
		$horometro=str_replace(",","",$_POST["txt_horometro"]);
		$fecha=modFecha($_POST["txt_fecha"],3);
		$turno=$_POST["cmb_turno"];
		
		if($_POST["txt_existente"] != "")
			$existentes=str_replace(",","",$_POST["txt_existente"]);
		else
			$existentes=0;
		
		if($_POST["txt_sinCodigo"] != "")
			$sin_codigo=str_replace(",","",$_POST["txt_sinCodigo"]);
		else
			$sin_codigo=0;
		
		if($_POST["txt_desechadas"] != "")
			$deshechadas=str_replace(",","",$_POST["txt_desechadas"]);
		else
			$deshechadas=0;
			
		$recuperadas=$existentes+$sin_codigo;
		//Calcular la cantidad de llantas que se modificaron
		$cambiadas=$recuperadas+$deshechadas;
		$tipoMov="S";
		
		//Abrir la conexion a la BD
		$conn=conecta("bd_mantenimiento");
		//Variable para controlar el flujo de guardado
		$band=0;
		//Sentencias SQL
		$sql = "INSERT INTO bitacora_llantas (id_bitacora,area,equipo,responsable,odometro,horometro,fecha,turno,cambiadas,recuperadas,deshechadas,tipo_mov) 
				VALUES ('$idBitacora','$area','$equipo','$responsable','$odometro','$horometro','$fecha','$turno','$cambiadas','$recuperadas','$deshechadas','$tipoMov')";
		//Ejecutar la sentencia SQL
		$rs=mysql_query($sql);
		//Verificar el resultado de la sentencia
		if(!$rs){
			$band=1;
			$error = mysql_error();	
			mysql_close($conn);		
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
		}
		else{
			for($i=1; $i<=10; $i++){
				if($_POST["txt_llanta".$i] != ""){
					$id_llan = strtoupper($_POST["txt_llanta".$i]);
					$sql_detalle = "INSERT INTO detalle_bitacora_llantas (bitacora_llantas_id_bitacora, llantas_id_llanta, operacion)
									VALUES ('$idBitacora','$id_llan','INSTALADA')";
					$rs=mysql_query($sql_detalle);
					//Verificar el resultado de la sentencia
					if(!$rs){
						$band=1;
						$error = mysql_error();	
						mysql_close($conn);		
						echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
					}
					else{
						$sql_detalle = "UPDATE detalle_llantas SET estado='INSTALADA' WHERE id_llanta='$id_llan'";
						$rs=mysql_query($sql_detalle);
						if(!$rs){
							$band=1;
							$error = mysql_error();	
							mysql_close($conn);		
							echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
						}
					}
				}
			}
			for($i=1; $i<=$_POST["txt_existente"]; $i++){
				if($_POST["txt_existente".$i] != ""){
					$id_llan = strtoupper($_POST["txt_existente".$i]);
					$sql_detalle = "INSERT INTO detalle_bitacora_llantas (bitacora_llantas_id_bitacora, llantas_id_llanta, operacion)
									VALUES ('$idBitacora','$id_llan','DESINSTALADA')";
					$rs=mysql_query($sql_detalle);
					//Verificar el resultado de la sentencia
					if(!$rs){
						$band=1;
						$error = mysql_error();	
						mysql_close($conn);		
						echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
					}
					else{
						$sql_detalle = "UPDATE detalle_llantas SET estado='USADA' WHERE id_llanta='$id_llan'";
						$rs=mysql_query($sql_detalle);
						if(!$rs){
							$band=1;
							$error = mysql_error();	
							mysql_close($conn);		
							echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
						}
					}
				}
			}
			for($i=1; $i<=$_POST["txt_sinCodigo"]; $i++){
				if($_POST["txt_sinCodigo".$i] != ""){
					$id_llan = strtoupper($_POST["txt_sinCodigo".$i]);
					$tipo_llan = $_POST["cmb_tipo".$i];
					$sql_detalle = "INSERT INTO detalle_bitacora_llantas (bitacora_llantas_id_bitacora, llantas_id_llanta, operacion)
									VALUES ('$idBitacora','$id_llan','DESINSTALADA')";
					$rs=mysql_query($sql_detalle);
					//Verificar el resultado de la sentencia
					if(!$rs){
						$band=1;
						$error = mysql_error();	
						mysql_close($conn);		
						echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
					}
					else{
						$sql_stm = "INSERT INTO detalle_llantas (id_llanta,posicion,odometro,horometro,estado,id_marca,costo_unit) 
									VALUES ('$id_llan','','$odometro','$horometro','USADA','$tipo_llan','0')";
						$rs=mysql_query($sql_stm);
						if(!$rs){
							$band=1;
							$error = mysql_error();	
							mysql_close($conn);		
							echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
						}
						else{
							registrarOperacion("bd_mantenimiento","$id_llan","RegistroDetalleLlanta",$_SESSION['usr_reg']);
							//Abrir la conexion a la BD
							$conn=conecta("bd_mantenimiento");
						}
					}
				}
			}
			for($i=1; $i<=$_POST["txt_desechadas"]; $i++){
				if($_POST["txt_desechadas".$i] != ""){
					$id_llan = strtoupper($_POST["txt_desechadas".$i]);
					$sql_detalle = "INSERT INTO detalle_bitacora_llantas (bitacora_llantas_id_bitacora, llantas_id_llanta, operacion)
									VALUES ('$idBitacora','$id_llan','DESECHADA')";
					$rs=mysql_query($sql_detalle);
					//Verificar el resultado de la sentencia
					if(!$rs){
						$band=1;
						$error = mysql_error();						
						mysql_close($conn);
						echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
					}
					else{
						$sql_detalle = "UPDATE detalle_llantas SET estado='DESECHADA' WHERE id_llanta='$id_llan'";
						$rs=mysql_query($sql_detalle);
						if(!$rs){
							$band=1;
							$error = mysql_error();	
							mysql_close($conn);
							echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
						}
					}
				}
			}
		}
		if($band==0){
			mysql_close($conn);
			registrarOperacion("bd_mantenimiento","$idBitacora","RegistrarBitacoraLlantas",$_SESSION['usr_reg']);
			echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
		}
	}
	
	function obtenerNumeroLlantas($tipo,$clave){
		$num_llantas = 0;
		$sql_llan = "SELECT COUNT( * ) 
				FROM  `detalle_llantas` 
				WHERE id_marca =  '$clave'
				AND estado =  '$tipo'";
		$rs_llan = mysql_query($sql_llan);
		if($dato_llan = mysql_fetch_array($rs_llan)){
			$num_llantas = $dato_llan[0];
		}
		return $num_llantas;
	}
?>