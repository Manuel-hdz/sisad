<?php
	/**
	  * Nombre del Módulo: Desarrollo                                               
	  * Nombre Programador: Miguel Angel Garay Castro
	  * Fecha: 30/Diciembre/2011
	  * Descripción: Este archivo contiene las funciones para realizar las operaciones de la Bitacora de Equipo Utilitario
	  **/ 
	  

/************************************************************************************************************************************************************/
/**********************************************************REGISTRAR BITACORA EQUIPO UTILIZATRIOS ***********************************************************/
/************************************************************************************************************************************************************/		  
	//Genera la Id de la Bitácora de Retros y Bulldozer
	function obtenerIdBitUtilitario(){
		//Conectarse a la BD de Desarrollo
		$conn = conecta("bd_desarrollo");
				
		//Definir las tres letras en la Id
		$id_cadena = "BEU";
		
		//Obtener el mes y el año
		$fecha = date("m-Y");
		$id_cadena .= substr($fecha,0,2).substr($fecha,5,2);
				
		//Obtener el mes actual y el año actual para ser agregados en la consulta y asi obtener las entradas del mes y año en curso
		$mes = substr($fecha,0,2);
		$anio = substr($fecha,5,2);
		
		//Crear la sentencia para obtener el numero de alertas registradas en la BD
		$stm_sql = "SELECT MAX(id_bitacora) AS clave FROM bitacora_retro_bull WHERE id_bitacora LIKE 'BEU$mes$anio%'";
		//Ejecutar Sentencia
		$rs = mysql_query($stm_sql);		
		//Evaluar Resultados y Generar Id a partir de ellos
		if($datos=mysql_fetch_array($rs)){
			$cant = intval(substr($datos['clave'],7,3));
			$cant += 1;
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
	}//Fin de la Funcion obtenerIdBitUtilitario()
	
	
	
	//Esta funcion guardará los datos de la bitácora de rezagado
	function guardarBitUtilitario(){
		//Conectarse a la BD de Desarrollo
		$conn = conecta("bd_desarrollo");
		
		//Recuperar los datos del POST del Empleado
		$idBit = $_POST['hdn_idBitacora'];
		$operador = $_POST['cmb_operador'];
		$puesto = $_POST['hdn_puesto'];
		$turno = $_POST['cmb_turno'];
		$fecha = modFecha($_POST['txt_fechaRegistro'],3);
		
		//Recuperar los datos del POST del Equipo
		$equipo = $_POST['cmb_equipo'];		
		$horoIni = str_replace(",","",$_POST['txt_horoIni']);		
		$horoFin = str_replace(",","",$_POST['txt_horoFin']);
		$horasTotales = str_replace(",","",$_POST['txt_horasTotales']);
		
		//Recuperar datos del Tepetate
		$lugarAmacizado = $_POST['cmb_lugarAmacizado'];
		$limpiaAcequia = $_POST['cmb_limpiaAcequia'];
		$lugarBalastreo = $_POST['cmb_lugarBalastreo'];		
		
		$obs = $_POST['txa_observaciones'];
		
		//Crear la Sentencia SQL para alamcenar lo datos en la Bitácora de Retro-Bull
		$sql_stm = "INSERT INTO bitacora_retro_bull(id_bitacora,turno,fecha,lugar_amacizado,lugar_balastreado,limpia_acequia,observaciones)
					VALUES('$idBit','$turno','$fecha','$lugarAmacizado','$lugarBalastreo','$limpiaAcequia','$obs')";
					
		//Ejecutar la Sentencia SQL
		$rs = mysql_query($sql_stm);
		
		if($rs){
			
			//Guardar los Datos en la Tabla de Personal
			mysql_query("INSERT INTO personal(bitacora_retro_bull_id_bitacora,bitacora_avance_id_bitacora,puesto,nombre,area) 
						VALUES('$idBit','N/A','$puesto','$operador','RETRO-BULL')");
			
			//Guardar los datos en la Tabla de Equipo
			mysql_query("INSERT INTO equipo(bitacora_retro_bull_id_bitacora,bitacora_avance_id_bitacora,id_equipo,horo_ini,horo_fin,horas_totales,area) 
						VALUES('$idBit','N/A','$equipo',$horoIni,$horoFin,$horasTotales,'RETRO-BULL')");
			
			//Guardar el Movimiento realizado en la tabla de Movimientos
			registrarOperacion("bd_desarrollo","$idBit","RegistroBitRetroBull",$_SESSION['usr_reg']);
			
			//Redireccionar a la pagina de exito
			echo "<meta http-equiv='refresh' content='0;url=exito.php'>";	
		}
		else{
			//Cerrar la conexicion con la BD
			mysql_close();
			
			//Obtener el error geerado
			$error = mysql_error();
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";			
		}
								
				
	}//Cierre de la funcion guardarBitAvance()
	
	
	/*Esta función guardará en un arreglo las obras registradas en el Catálogo de Obras y en la Bitácora de Equipo Utilitario*/
	function obtenerObrasBitUtilitario($campoBitUtilitario){
		//Conectarse con la BD de Desarrollo
		$conn = conecta("bd_desarrollo");
		
		//Arreglo que guardará las obras encontradas
		$arrObras = array();
		
		//OBTENER LAS OBRAS REGISTRADAS EN EL CATALOGO DE OBRAS Y EN LA BITACORA DE EQUIPO UTILITARIO
		$sql_obrasBitUtilitario = "SELECT DISTINCT $campoBitUtilitario FROM bitacora_retro_bull WHERE $campoBitUtilitario!=''";//Obras Bitacóra de Equipo Utilitario
		$sql_obrasCatalogo = "SELECT obra FROM catalogo_ubicaciones";//Obras Catálogo de Obras
		
		//Ejecutar las Sentencias
		$rs_obrasBitUtilitario = mysql_query($sql_obrasBitUtilitario);
		$rs_obrasCatalogo = mysql_query($sql_obrasCatalogo);
		
		//Obtener las Obras de la Bitácora de Equipo Utilitario
		if($datos_obrasBitUtilitario=mysql_fetch_array($rs_obrasBitUtilitario)){
			do{
				$arrObras[] = $datos_obrasBitUtilitario[$campoBitUtilitario];
			}while($datos_obrasBitUtilitario=mysql_fetch_array($rs_obrasBitUtilitario));
		}//Cierre if($datos_obrasBitUtilitario=mysql_fetch_array($rs_obrasBitUtilitario))
		
		
		//Obtener las Obras del Catálogo de Obras
		if($datos_obrasCatalogo=mysql_fetch_array($rs_obrasCatalogo)){
			do{
				$arrObras[] = $datos_obrasCatalogo['obra'];
			}while($datos_obrasCatalogo=mysql_fetch_array($rs_obrasCatalogo));
		}//Cierre if($datos_obrasRezagado=mysql_fetch_array($rs_obrasRezagado))
		
		
		//Eliminar las obras repetidas
		$obras = array_unique($arrObras);		
		
		//Ordenar el arreglo recien creado
		sort($obras);
		
		//Cerrar la conexión con la BD
		mysql_close($conn);
		
		//Regresar el Arreglo de obras
		return $obras;
		
	}//Cierre de la función obtenerObrasBitUtilitario($campoBitUtilitario)
	
	
	
/************************************************************************************************************************************************************/
/**********************************************************MODIFICAR BITACORA EQUIPO UTILIZATRIOS ***********************************************************/
/************************************************************************************************************************************************************/	
	/*Esta funcion mustra los registro disponibles en la Bitácora de Rezagado en las fechas seleccionadas*/
	function mostrarRegUtilitario(){
		//Variable que indica si existen resultados
		$band=0;
		
		//Recuperar datos del POST
		$fechaIni = modFecha($_POST['txt_fechaIni'],3);
		$fechaFin = modFecha($_POST['txt_fechaFin'],3);
		$equipo = $_POST["cmb_equipos"];
		$nomEquipo=obtenerDato("bd_mantenimiento","equipos","nom_equipo","id_equipo",$equipo);
		
		//Conectarse a la BD de Desarrollo
		$conn = conecta("bd_desarrollo");
		
		//Sentencia SQL para obtener los registros en las fechas seleccionadas
		$sql_stm = "SELECT id_bitacora,id_equipo,horo_ini,horo_fin,horas_totales,nombre,turno,fecha,lugar_amacizado,lugar_balastreado,limpia_acequia,observaciones 
					FROM bitacora_retro_bull 
					JOIN personal ON bitacora_retro_bull.id_bitacora=personal.bitacora_retro_bull_id_bitacora
					JOIN equipo ON bitacora_retro_bull.id_bitacora=equipo.bitacora_retro_bull_id_bitacora
					WHERE fecha BETWEEN '$fechaIni' AND '$fechaFin'AND id_equipo='$equipo'";
		//Ejecutar la Sentencia
		$rs = mysql_query($sql_stm);
		//Revisar la existencia de datos
		if($datos=mysql_fetch_array($rs)){?>
			<br>
			<table class="table_frm" cellpadding="5" width="150%">
				<caption class='titulo_etiqueta'><?php echo "Registros del Equipo $equipo-$nomEquipo del $_POST[txt_fechaIni] al $_POST[txt_fechaFin]";?></caption>
				<tr>
					<td class="nombres_columnas" align="center">SELECCIONAR</td>
					<td class="nombres_columnas" align="center">EQUIPO</td>
					<td class="nombres_columnas" align="center">HOROMETRO INICIAL</td>
					<td class="nombres_columnas" align="center">HOROMETRO FINAL</td>
					<td class="nombres_columnas" align="center">HORAS TOTALES</td>
					<td class="nombres_columnas" align="center">OPERADOR</td>
					<td class="nombres_columnas" align="center">TURNO</td>
					<td class="nombres_columnas" align="center">FECHA</td>
					<td class="nombres_columnas" align="center">LUGAR AMACIZADO</td>
					<td class="nombres_columnas" align="center">LUGAR BALASTREADO</td>
					<td class="nombres_columnas" align="center">LIMPIA ACEQUIA</td>
					<td class="nombres_columnas" align="center">OBSERVACIONES</td>					
				</tr><?php
			//Controlar el color del renglon que esta siendo dibujado
			$nom_clase = "renglon_gris";
			$cont = 1;
			do{?>
				<tr>
					<td class="nombres_filas" align="center">
						<input type="radio" name="rdb_idBitacora" id="rdb_idBitacora" value="<?php echo $datos['id_bitacora']; ?>" 
						onclick="frm_seleccionarRegistro.submit();"/>
					</td>
					<td class="<?php echo $nom_clase; ?>" align="center"><?php echo $datos['id_equipo']; ?></td>
					<td class="<?php echo $nom_clase; ?>" align="center"><?php echo number_format($datos['horo_ini'],2,".",","); ?></td>
					<td class="<?php echo $nom_clase; ?>" align="center"><?php echo number_format($datos['horo_fin'],2,".",","); ?></td>
					<td class="<?php echo $nom_clase; ?>" align="center"><?php echo number_format($datos['horas_totales'],2,".",","); ?></td>
					<td class="<?php echo $nom_clase; ?>" align="center"><?php echo $datos['nombre']; ?></td>
					<td class="<?php echo $nom_clase; ?>" align="center"><?php echo $datos['turno']; ?></td>
					<td class="<?php echo $nom_clase; ?>" align="center"><?php echo modFecha($datos['fecha'],1); ?></td>
					<td class="<?php echo $nom_clase; ?>" align="center"><?php echo $datos['lugar_amacizado']; ?></td>
					<td class="<?php echo $nom_clase; ?>" align="center"><?php echo $datos['lugar_balastreado']; ?></td>
					<td class="<?php echo $nom_clase; ?>" align="center"><?php echo $datos['limpia_acequia']; ?></td>
					<td class="<?php echo $nom_clase; ?>" align="center"><?php echo $datos['observaciones']; ?></td>					
				</tr><?php		
				
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";		
			}while($datos=mysql_fetch_array($rs));?>
			<input type="hidden" value="<?php echo $_POST['txt_fechaIni']?>" name="txt_fechaIni" id="txt_fechaIni"/>
			<input type="hidden" value="<?php echo $_POST['txt_fechaFin']?>" name="txt_fechaFin" id="txt_fechaFin"/>
			<input type="hidden" value="<?php echo $equipo?>" name="cmb_equipos" id="cmb_equipos"/>
			</table><?php
			$band=1;
		}
		else{
			//Si no hay registros en la Bitacora, se indica esto al usuario
			echo "<br><br><br><br><br><br><br><br><br><br><br><br>
					<p class='msje_correcto' align='center'>No hay Registros del Equipo $equipo-$nomEquipo del $_POST[txt_fechaIni] al $_POST[txt_fechaFin]</u></em></p>";
		}
		//Cerrar la Conexion con la BD
		mysql_close($conn);
		return $band;
	}//Fin de function mostrarRegUtilitario()
	
	
	//Funcion que guardar los cambios hechos n la bitacora de Equipo Utilitario
	function modificarBitUtilitario(){
		$idBitacora = $_POST["hdn_idBitacora"];
		$operador = $_POST["cmb_operador"];
		$puesto = $_POST["hdn_puesto"];
		$turno = $_POST["cmb_turno"];
		$fecha = modFecha($_POST["txt_fechaRegistro"],3);
		$equipo = $_POST["cmb_equipo"];
		$horo_ini = str_replace(",","",$_POST["txt_horoIni"]);
		$horo_fin = str_replace(",","",$_POST["txt_horoFin"]);
		$horas = str_replace(",","",$_POST["txt_horasTotales"]);
		$amacice = $_POST["cmb_lugarAmacizado"];
		$acequia = $_POST["cmb_limpiaAcequia"];
		$balastreo = $_POST["cmb_lugarBalastreo"];
		$observaciones = strtoupper($_POST["txa_observaciones"]);
		

		$conn = conecta("bd_desarrollo");
		//Sentencia SQL para modificar los datos de la Bitacora Retro Bull
		$sql_stm_equipoUtilitario = "UPDATE bitacora_retro_bull SET turno = '$turno', fecha = '$fecha', lugar_amacizado = '$amacice', lugar_balastreado = '$balastreo', 
									limpia_acequia='$acequia', observaciones='$observaciones' WHERE id_bitacora='$idBitacora'";
		//Ejecutar Sentencia para actualizar los datos dela Bitacora
		$rs_equipoUtilitario = mysql_query($sql_stm_equipoUtilitario);
		//Si la sentencia SQL se genero correctamente actualizar los datos del Equipo
		if($rs_equipoUtilitario){
			//Sentencia SQL para modificar los datos de la Bitacora Retro Bull
			$sql_stm_equipo = "UPDATE equipo SET id_equipo='$equipo',horo_ini='$horo_ini',horo_fin='$horo_fin',horas_totales='$horas' 
								WHERE bitacora_retro_bull_id_bitacora='$idBitacora'";
			//Ejecutar Sentencia para actualizar los datos del Equipo
			$rs_equipo = mysql_query($sql_stm_equipo);
			//Si la sentencia SQL se genero correctamente actualizar los datos del Personal
			if($rs_equipo){				
				
				//Obtener el ID del equipo registrado en la Bitácora de Fallas
				$rs_equipo_fallas = mysql_query("SELECT DISTINCT equipo FROM bitacora_fallas WHERE bitacora_retro_bull_id_bitacora = '$idBitacora' 
												AND tipo_registro = 'RETRO-BULL'");
				if($datosEquipo=mysql_fetch_array($rs_equipo_fallas)){			
					//Verificar si el equipo seleccionado en el Formulario de Rezagado es el Mismo que fue registrardo en el Formulario de Fallas
					if($equipo!=$datosEquipo['equipo']){
						//Actualizar la Clave del equipo registrada en la Bitácora de Fallas
						mysql_query("UPDATE bitacora_fallas SET equipo = '$equipo' WHERE bitacora_retro_bull_id_bitacora = '$idBitacora' AND tipo_registro = 'RETRO-BULL'");
					}
				}//Cierre if($datosEquipo=mysql_fetch_array($rs_equipo_fallas))
			
			
				//Sentencia SQL para modificar los datos del Personal
				$sql_stm_personal = "UPDATE personal SET puesto='$puesto',nombre='$operador' WHERE bitacora_retro_bull_id_bitacora='$idBitacora'";
				//Ejecutar Sentencia para actualizar los datos del Equipo
				$rs_personal = mysql_query($sql_stm_personal);
				//Si el personal se actualizo correctamente, verificar si se debe actualizar el Equipo en las bitacoras de fallas
				if($rs_personal){					
					//Guardar la operacion realizada
					registrarOperacion("bd_desarrollo",$idBitacora,"ActualizarBitEqUtil",$_SESSION['usr_reg']);
					echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
				}
				else{
					$error="Se Generaron Problemas al Modificar Datos";
					echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";					
				}
			}//Cierre if($rs_equipo)
		}//Cierre if($rs_equipoUtilitario)
		else{
			//Cerrar la conexion con la BD
			mysql_close($conn);
		}
	}//Fin de function guardarBitUtilitario()


?>