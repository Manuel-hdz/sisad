<?php
	/**
	  * Nombre del Módulo: Recursos Humanos
	  * Nombre Programador: Nadia Madahí López Hernández
	  * Fecha: 09/Abril/2011
	  * Descripción: Este archivo contiene funciones para almacenar la información relacionada con el formulario de Eliminar Aspirantes en la BD
	**/
	
	//Funcion que se encarga de desplegar las capacitaciones en el rango de fechas
	function mostrarListadoAspirantes(){

		//Conectar a la BD de Recursos Humanos
		$conn = conecta("bd_recursos");
	
		if(isset($_POST["sbt_consultarPuesto"])){ 
		
			//Obtener las fechas en formato aaaa-mm-dd a partir de dd/mm/aaaa
			$f1 = modFecha($_POST['txt_fechaPuestoIni'],3);
			$f2 = modFecha($_POST['txt_fechaPuestoFin'],3);
			//Validar cuando en el combo donde se muestran los puestos
			$puesto = "";
			if(isset($_POST['cmb_puesto']))
				$puesto = $_POST['cmb_puesto'];
			
			//Crear sentencia SQL
			$stm_sql = "SELECT folio_aspirante, CONCAT(nombre,' ',ap_paterno,' ',ap_materno) AS nombre_aspirante, curp, edad, fecha_solicitud, estado_civil, bolsa_trabajo.telefono,
			 tel_referencia, lugar_nac, nacionalidad, experiencia_laboral, observaciones
						FROM area_puesto JOIN bolsa_trabajo ON  folio_aspirante = bolsa_trabajo_folio_aspirante 
						WHERE puesto='$puesto' AND fecha_solicitud>='$f1'  AND fecha_solicitud<='$f2' ORDER BY folio_aspirante";	

					
			//Crear el mensaje que se mostrara en el titulo de la tabla de Aspirantes
			$msg = "Aspirantes Registrados en el Periodo del <em><u>$_POST[txt_fechaPuestoIni]</u></em> al <em><u> $_POST[txt_fechaPuestoFin]</u></em> en el Puesto <em><u> '$puesto' </em></u>";
			
			//Crear el Mensaje en caso de que la consulta no arroje ningún resultado
			$msg_error = "<label class='msje_correcto' align='center'>No se Encontr&oacute; Ningun Aspirate Registrado en las Fechas del <em><u>$_POST[txt_fechaPuestoIni]
			</u></em> al <em><u>$_POST[txt_fechaPuestoFin]</u></em></label>";		
									
		}		
		else if(isset($_POST["sbt_consultarAspirante"])){//Segunda Consulta para mostrar todos los aspirantes registrados en el sistema
			//Crear sentencia SQL
			$stm_sql = "SELECT  folio_aspirante, CONCAT(nombre,' ',ap_paterno,' ',ap_materno) AS nombre_aspirante, curp, edad, fecha_solicitud, estado_civil, bolsa_trabajo.telefono,
			 tel_referencia, lugar_nac, nacionalidad, experiencia_laboral, observaciones
				FROM bolsa_trabajo ORDER BY folio_aspirante";	
			
			//Crear el mensaje que se mostrara en el titulo de la tabla
			$msg= "Datos de los Aspirantes Registrados";
			
			//Crear el Mensaje en caso de que la consulta no arroje ningún resultado
			$msg_error = "<label class='msje_correcto' align='center'>No se Encontraron Aspirantes Registrados </label>";										
		}
		
		
		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($stm_sql);										
		//Confirmar que la consulta de datos fue realizada con exito.
		if($datos=mysql_fetch_array($rs)){	
			//Desplegar los resultados de la consulta en una tabla
			echo "				
			<table cellpadding='5' width='100%'>								
				<tr>
					<td colspan='10' align='center' class='titulo_etiqueta'>$msg</td>
				</tr>";?>
				<tr>
					<td  colspan='13' class='nombres_columnas'>
						<input name="ckbTodo" type="checkbox" id="ckbTodo" onclick="checarTodos(this,'frm_resultadosAspirante');" value="Todos"/>SELECCIONAR TODOS
					</td>
				</tr><?php 				
			echo "
				<tr>
					<td class='nombres_columnas'>SELECCIONAR</td>
					<td class='nombres_columnas'>FOLIO ASPIRANTE</td>
					<td class='nombres_columnas'>NOMBRE DEL ASPIRANTE</td>				
					<td class='nombres_columnas'>CURP</td>
					<td class='nombres_columnas'>EDAD</td>					
					<td class='nombres_columnas'>FECHA DE SOLICITUD</td>
					<td class='nombres_columnas'>ESTADO CIVIL</td>			
					<td class='nombres_columnas'>TELEFONO</td>
					<td class='nombres_columnas'>TELEFONO REFERENCIA</td>
					<td class='nombres_columnas'>LUGAR NACIMIENTO</td>												
					<td class='nombres_columnas'>NACIONALIDAD</td>					
					<td class='nombres_columnas'>EXPERIENCIA</td>
					<td class='nombres_columnas'>OBSERVACIONES</td>
				</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{
				//Mostrar todos los registros que han sido completados
				echo "
				<tr>
					<td class='nombres_filas' align='center'>
						<input type='checkbox' name='ckb_$cont' value='$datos[folio_aspirante]' onclick='desSeleccionar(this);' />
					</td>
					<td class='$nom_clase'>$datos[folio_aspirante]</td>
					<td class='$nom_clase'>$datos[nombre_aspirante]</td>
					<td class='$nom_clase'>$datos[curp]</td>			
					<td class='$nom_clase'>$datos[edad]</td>																																					
					<td class='$nom_clase'>".modFecha($datos['fecha_solicitud'],1)."</td>																		
					<td class='$nom_clase'>$datos[estado_civil]</td>					
					<td class='$nom_clase'>$datos[telefono]</td>					
					<td class='$nom_clase'>$datos[tel_referencia]</td>
					<td class='$nom_clase'>$datos[lugar_nac]</td>					
					<td class='$nom_clase'>$datos[nacionalidad]</td>					
					<td class='$nom_clase'>$datos[experiencia_laboral]</td>		
					<td class='$nom_clase'>$datos[observaciones]</td>	
				</tr>";
					
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";				
			}while($datos=mysql_fetch_array($rs));
			echo "<input type='hidden' name='hdn_cant' value='$cont'>";
			//Fin de la tabla donde se muestran los resultados de la consulta
			echo "</table>";
				
		}//Cierre if($datos=mysql_fetch_array($rs))
		else//Si no se encuentra ningun resultado desplegar el mensaje de alerta notificando que no hay resultados disponibles
			echo $msg_error;		
		
		//Cierre de Conexion con la BD
		mysql_close($conn);	
	}
	
	
	/*Eliminar los datos del Aspirante*/
	function eliminarAspirante(){
		//Conectar a a BD de Recursos Humanos
		$conn = conecta("bd_recursos");
		//Esta variable nos ayudara controlar los mensajes de Exito o Error
		$ctrl_res = 0;
		
		//Iterar la cantidad de registros segun la opcion consultada y la cantidad de registros
		for($i=1; $i<=$_POST['hdn_cant']; $i++){			
			//Verificar que el combo que se quiere eliminar Exista dentro del POST, lo que significa que fue seleccionado
			if(isset($_POST['ckb_'.$i])){
				//Crear y ejecutar la Senetencia SQL para borrar el Aspirante de la Bolsa de Trabajo
				$stm_sql = "DELETE FROM bolsa_trabajo WHERE folio_aspirante = '".$_POST["ckb_".$i]."'";		
				//$rs = "SELECT * FROM bolsa_trabajo WHERE folio_aspirante =";		
				$rs =  mysql_query($stm_sql);				
				//Verificar el resultado de la ejecucion de la sentenca
				if($rs){
					//Eliminar el Contacto Asociado al Aspirante
					$id_aspirante = $_POST["ckb_".$i];			
					$error = eliminarContacto($id_aspirante);
					//Registrar la operacion dentro de la bitacora_movimiento
					registrarOperacion("bd_recursos",$id_aspirante,"EliminarAspiranteBolsaTrabajo",$_SESSION['usr_reg']);					
					$conn = conecta("bd_recursos");
					//Verificar el resultado obtenido de la Eliminación del Contacto
					if($error==""){
						//Si no hubo errores, proceder a eliminar el Perfil del Aspirante
						$error = eliminarPerfil($id_aspirante);
						if($error!=""){//Si hubo errores en la eliminacion del perfil, romper el ciclo
							$ctrl_res = 1;
							break;	
						}							
					}
					else{//Si hubo errores en la eliminacion del contacto, romper el ciclo
						$ctrl_res = 1;
						break;
					}
				}
				else{//Si hubo un error, guardarlos en la variable $error y romper el ciclo					
					$ctrl_res = 1;
					$error = mysql_error();
					break;
				}
			}
		}//Cierre for
		
		
		//Cerrar la Conexion con la BD
		mysql_close($conn);
		
		
		//Si no hubo errores, redireccionar a la pagina de Exito, de lo contrario redireccionar a la pagina de Error
		if($ctrl_res==0)
			echo "<meta http-equiv='refresh' content='0;url=exito.php?'>";
		else if($ctrl_res==1)
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
								
	}
	
	
	/*Eliinar el COntacto asociado al Aspirante que se quiere Elimanar*/
	function eliminarContacto($id_aspirante){
		//Crear la Sentencia SQL para Eliminar el Contacto asociado al Aspirante	
		$stm_sql = "DELETE FROM contacto WHERE bolsa_trabajo_folio_aspirante =  '".$id_aspirante."'";			
		$rs =  mysql_query($stm_sql);
		
		//Verificar el resultado arrojado por la sentencia
		$error = "";
		if(!$rs){
			$error = mysql_error();			
		}			
		
		return $error;
	}
	
	
	/*Eliminar el Perfil de Aspirante de la tabla area_puesto*/
	function eliminarPerfil($id_aspirante){
		//Crear y Ejecutar la Sentencia para Eliminar el Perfil del Aspirante
		$stm_sql = "DELETE FROM area_puesto WHERE bolsa_trabajo_folio_aspirante = '".$id_aspirante."'";		
		$rs =  mysql_query($stm_sql);
		//Verificar que la consulta arroje resultados ó de lo contarrio que me muestre el error
		$error = "";
		if(!$rs){
			$error = mysql_error();			
		}	
		
		return $error;
	}
	
	
?>