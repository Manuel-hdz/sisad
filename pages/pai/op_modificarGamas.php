<?php
	/**
	  * Nombre del Módulo: Mantenimiento
	  * Nombre Programador: Miguel Angel Garay Castro
	  * Fecha: 08/Marzo/2011
	  * Descripción: Este archivo contiene funciones para almacenar la información modificada de las Gamas de los Equipos
	**/


	/*Esta función se encarga de almacenar los datos de la Gama en la BD de Mantenimiento*/
	function guardarDatosGama(){												
		//Realizar la conexion a la BD de Compras
		$conn = conecta("bd_paileria");	
		
		//Extraer los datos de la SESSION
		$id_gama = $_SESSION['datosGamaModificada']['idGama'];
		$nom_gama = $_SESSION['datosGamaModificada']['nomGama'];
		$descripcion = $_SESSION['datosGamaModificada']['descripcion'];
		$area = $_SESSION['datosGamaModificada']['areaAplicacion'];
		$familia = $_SESSION['datosGamaModificada']['familiaAplicacion'];	
		$cicloServ = $_SESSION['datosGamaModificada']['cicloServicio'];	
		$color = $_SESSION['datosGamaModificada']['color'];
		
		//Crear la Sentecnia SQL para insertar los datos de la Gama
		$stm_sql = "UPDATE gama SET id_gama='$id_gama', nom_gama='$nom_gama', descripcion='$descripcion', area_aplicacion='$area', familia_aplicacion='$familia', ciclo_servicio=$cicloServ, color='$color' 
					WHERE id_gama='".$_SESSION['datosGamaModificada']['idGamaAnt']."'";					
		//Ejecutar la Sentencia SQL
		$rs = mysql_query($stm_sql);
		//Verificar los resultados de la Inserción
		if($rs){
			//Los datos generales de la Gama se guardaron con Exito, guardar los sistemas y su detalle
			guardarSistemas();
		}
		else{
			//Redireccionar a la Pagina de Error en el caso de que no se puedan Insertar los datos de la Gama
			$error = mysql_error();
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
			//Cerrar la Conexion con la BD
			mysql_close($conn);
		}		
		
	}
	
	
	/*Esta función se encarga de guardar los datos del los Sistemas, Aplicaciones y Actividades de la Nueva Gama en la Base de Datos*/
	function guardarSistemas(){
		
		//Obtener el id de la ultima actividad registrada en la bd antes de borrar las actividades de la Gama
		$datos = mysql_fetch_array(mysql_query("SELECT MAX(id_actividad) as maxIdAct FROM actividades"));
		$idActividad = $datos['maxIdAct'] + 1;
		
		
		//Borrar todos los registros de la Gama modificada en las tablas de gama_actividades y actividades
		mysql_query("DELETE FROM actividades, gama_actividades USING actividades, gama_actividades 
					 WHERE actividades.id_actividad=gama_actividades.actividades_id_actividad AND gama_actividades.gama_id_gama = '".$_SESSION['datosGamaModificada']['idGamaAnt']."'"); 
						   
		//Guardar la id de la Gama actulizada
		$idGama = $_SESSION['datosGamaModificada']['idGama'];
		
		
		//Estas variables nos ayudarán a controlar los errores en el caso que se presenten durante el proceso de guardar los Sistemas y su detalle dentro de la Gama.
		$band = 0;
		$error = "";
								
		//Recorrer los Sistemas
		foreach($_SESSION['sistemasGamaModificada'] as $nomSistema => $sistema){
			//Recorrer las Aplicaciones del Sistema
			foreach($sistema as $nomApp => $aplicacion){
				//Registrar cada actividad dentro de la aplicación actual
				foreach($aplicacion as $idAct => $actividad){
					//Determinar el ID de la Actividad
					if(strlen($idAct)>=3 && substr($idAct,0,2)=="NA"){
						$claveActividad = $idActividad;	
						//Incrementar el Id de la Activiada
						$idActividad++;
					}
					else
						$claveActividad = $idAct;
					
					if($idAct>0)
						$indiceAuxiliar=$idAct*-1;
					//En el caso del 0, dejar -0 como cadena
					else
						$indiceAuxiliar="-0";
					if(isset($_SESSION["sistemasGamaNueva"][$nomSistema][$nomApp][$indiceAuxiliar])){
						$tiempoAprox=$_SESSION["sistemasGamaNueva"][$nomSistema][$nomApp][$indiceAuxiliar].":00";
					}
					else{
						$tiempoAprox="00:00:00";
					}
					
					//Crear la Sentencia SQL para guardar cada acividad
					$stm_sql = "INSERT INTO actividades(id_actividad,sistema,aplicacion,descripcion) VALUES($claveActividad,'$nomSistema','$nomApp','$actividad')";
					//Ejecutar la Consulta
					$rs = mysql_query($stm_sql);
					//Evauar Resultado
					if($rs){						
						//Guardar la relacion de la actividad con la Gama en la tabla "gama_actividades"
						$rs_act = mysql_query("INSERT INTO gama_actividades (gama_id_gama,actividades_id_actividad,tiempo_aprox) VALUES('$idGama',$claveActividad,'$tiempoAprox')");
						if(!$rs_act){//Si hubo algun error, romper el ciclo y enviar a una pagina de error
							$band = 1;
							$error = mysql_error();
							break;//Romper el foreach de las Actividades
						}
					}
					else{//Si hubo algun error, romper el ciclo y enviar a una pagina de error
						$band = 1;
						$error = mysql_error();
						break;//Romper el foreach de las Actividades
					}
					
				}
				//Romper el foreach de las Aplicaciones, cuando existan errores			
				if($band==1)
					break;
			}
			//Romper el foreach de los Sistemas, cuando existan errores			
			if($band==1)
				break;
		}
		 		 
		if($band==0){//Redireccionar a la Pagina donde serán agreados los Sistemas a la Gama
			//Registrar la Operacion realizada en la tabla de Bitacora de Movimientos
			registrarOperacion("bd_paileria",$idGama,"ModificarGama",$_SESSION['usr_reg']);
			echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
		}
		else{//Redireccionar a la pantalla de Error, en caso de que existan
			echo "</br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br>$error";
			break;
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";						
			
			//Cerrar la Conexion con la BD
			mysql_close($conn);
		}
	} 
	
	
	/*Esta función se encarga de cargar los Sistemas de la Gama Seleccionada a la SESSION*/
	function cargarSistemas($id_gama){
		//Realizar la conexion a la BD de Compras
		$conn = conecta("bd_paileria");
	
		$arrSistemas = array();
		
		//Ejecutar Consulta para obtener las aplicaciones del Sistema en turno
		$rs = mysql_query("SELECT DISTINCT sistema FROM actividades JOIN gama_actividades ON id_actividad=actividades_id_actividad WHERE gama_id_gama = '$id_gama'");
		//Agrupar las aplicaciones del Sistema en la variable $aplicaciones
		if($datos=mysql_fetch_array($rs)){
			do{
				//Cargar las aplicaciones de cada sistema que compone a la Gama que se quiere Editar, el ultimo parametro indica que será cargado el Id de cada actividad
				$arrSistemas[$datos['sistema']] = cargarApps($datos['sistema'],$id_gama,0);
			}while($datos=mysql_fetch_array($rs));			
		}		
		
		//Cerrar la Conexion con la BD
		mysql_close($conn);
		
		return $arrSistemas;
	}
		
		
	/*Esta función se encarga de cargar las Aplicaciones del Sistema Seleccionado a la SESSION, en caso de que el sistema sea Nuevo o no tenga aplicaciones asociadas, devuelve un arreglo 
	 *con la leyenda "Sistema Nuevo, Agregar Aplicaciones"
	 * PARAMETROS:
	 * 1. $nom_sistema: Nombre del Sistema que será Cargado
	 * 2. $idGama: Clave de la Gama a la que pertenece el Sistema
	 * 3. $tipoCarga: Cuando se carga la Gama para ser editada el valor sera 0 para indicar que debe ser cargada el Id de la Actividad, 
	 *    cuando se esten editando los Sistemas, Aplicaciones y Actividades el valor ser a 1 indicando que el Id de la Actividad no será cargado y en su lugar se pondrá NAnumConsecutivo */
	function cargarApps($nom_sistema,$idGama,$tipoCarga){
		$arrAplicaciones = array();
		
		//Verificar si el Sistema que se quiere agregar, pertenece a la Gama que se esta editando y se quiere agregar nuevamente despues de haberla borrado.
		if($tipoCarga==1){
			$stm_sql = "SELECT DISTINCT sistema FROM actividades JOIN gama_actividades ON id_actividad=actividades_id_actividad WHERE sistema = '$nom_sistema' AND gama_id_gama = '$idGama'";
			if($datos=mysql_fetch_array(mysql_query($stm_sql))){
				$tipoCarga = 0;
			}
		}
		
		
		if($tipoCarga==0)//Ejecutar Consulta para obtener las aplicaciones del Sistema en turno
			$rs = mysql_query("SELECT DISTINCT aplicacion FROM actividades JOIN gama_actividades ON id_actividad=actividades_id_actividad WHERE sistema = '$nom_sistema' AND gama_id_gama = '$idGama'");
		else
			$rs = mysql_query("SELECT DISTINCT aplicacion FROM actividades WHERE sistema = '$nom_sistema'");
			
		//Agrupar las aplicaciones del Sistema en la variable $aplicaciones
		if($datos=mysql_fetch_array($rs)){
			do{				
				//Cargar el nombre de la aplicacion como indice dentro del arreglo y como contenido un arreglo con las actividades
				$arrAplicaciones[$datos['aplicacion']] = cargarActividadesApps($datos['aplicacion'],$nom_sistema,$idGama,$tipoCarga);
			}while($datos=mysql_fetch_array($rs));			
		}				
		
		return $arrAplicaciones;
	}
	
	
	/*Esta función carga las actividades de las aplicaciones dentro del sistema seleccionado para integrar la Nueva Gama, regresa un arreglo con las actividades de la aplicación,
	 *o un arreglo con la Leyenda "Aplicación Nueva, Agregar Actividades"*/
	function cargarActividadesApps($nomApp,$nomSistema,$idGama,$tipoCarga){
		$arrActividades = array();
		
		//Verificar si la Aplicacion que se quiere agregar, pertenezca a la Gama que se esta editando y sequiere agregar nuevamente despues de haberla borrado.
		if($tipoCarga==1){
			$stm_sql = "SELECT DISTINCT aplicacion FROM actividades JOIN gama_actividades ON id_actividad=actividades_id_actividad WHERE aplicacion = '$nomApp' AND sistema = '$nomSistema' AND gama_id_gama = '$idGama'";
			if($datos=mysql_fetch_array(mysql_query($stm_sql))){
				echo "**********TIPO CARGA: ".$tipoCarga = 0;
			}
		}
		
		if($tipoCarga==0){
			//Ejecutar Consulta para obtener las actividades de la Aplicación en turno
			$rs = mysql_query("SELECT DISTINCT descripcion, id_actividad FROM actividades JOIN gama_actividades ON id_actividad=actividades_id_actividad 
								WHERE aplicacion = '$nomApp' AND sistema = '$nomSistema' AND gama_id_gama = '$idGama'");
		}
		else{
			//Ejecutar Consulta para obtener las actividades de la Aplicación en turno, cuando no es proporcionada una Id de Gama
			$rs = mysql_query("SELECT DISTINCT descripcion FROM actividades WHERE aplicacion = '$nomApp' AND sistema = '$nomSistema'");
		}
		
		//Agrupar las actividades del Sistema en la variable $arrActividades
		if($datos=mysql_fetch_array($rs)){
			$cont = 1;
			do{
				if($tipoCarga==0)//Cargar las actividades de la Aplicacion indicada, si el tipo de carga es 0, colocar el id de la Actividad como indice en el arreglo
					$arrActividades[$datos['id_actividad']] = $datos['descripcion'];
				else{ //Si el tipo de carga es 1, colocar como indice en el arreglo NA
					$arrActividades["NA$cont"] = $datos['descripcion'];
					$cont++;
				}
			}while($datos=mysql_fetch_array($rs));			
		}		
				
		return $arrActividades;
	}
	
	
	/*Esta funcion se encarga de Desplegar los Sistemas y su detalle, que estan siendo agregados a la Gama*/
	function verSistemasGama($msg_tabla){						
		echo "<table cellpadding='5' width='100%'>";
		echo "<caption>
				<p class='msje_correcto'>Sistemas Registrados en la Gama <em><u>".$_SESSION['datosGamaModificada']['idGama']."</u></em></p>
				<p class='msje_incorrecto'>$msg_tabla</p>
			  </caption>";
		echo "      			
			<tr>
				<td class='nombres_columnas_gomar' align='center' width='10%'>EDITAR</td>
        		<td class='nombres_columnas_gomar' align='center' width='30%'>SISTEMA</td>
			    <td class='nombres_columnas_gomar' align='center' width='60%'>APLICACIONES</td>
      		</tr>";
		
		$nom_clase = "renglon_gris";
		$cont = 1;
		foreach ($_SESSION['sistemasGamaModificada'] as $ind => $sistema) {			
			$aplicaciones = "";						
			//Obtener las aplicaciones de los sistemas almacenados en la SESSION
			if(count($sistema)!=0){
				foreach($sistema as $key => $aplicacion){
					$aplicaciones .= $key.", ";
				}
				$aplicaciones = substr($aplicaciones,0,strlen($aplicaciones)-2).".";
			}			
			
			//Si el Sistema es nuevo o en la Edición del mismo le fuernon retiradas todas sus Aplicaciones, entonces desplegar el mensaje
			if($aplicaciones=="")
				$aplicaciones = "<label class='msje_correcto'>Sistema Vac&iacute;o, Agregar Aplicaciones</label>";
			
			//Desplegar el Sistema con sus Aplicaciones
			echo "
				<tr>
					<td class='$nom_clase' ><input type='radio' name='rdb_sistema' value='$ind' /></td>
					<td class='$nom_clase' align='left'>$ind</td>
					<td class='$nom_clase' align='left'>$aplicaciones</td>
				</tr>";			
			//Determinar el color del siguiente renglon a dibujar
			$cont++;
			if($cont%2==0)
				$nom_clase = "renglon_blanco";
			else
				$nom_clase = "renglon_gris";
		}
		echo "</table>";	
	}
			
	
	/*Esta funcion despliega las Aplicaciones registradas en el Sistema seleccionado*/
	function verAplicacionesSistema($msg_tabla){				
		echo "<table cellpadding='5' width='100%'>";
		echo "<caption>
				<p class='msje_correcto'>Aplicaciones Registradas en el Sistema: <em><u>".$_SESSION['sistemaEditar']."</u></em> de la Gama: <em><u>".$_SESSION['datosGamaModificada']['idGama']."</u></em></p>
				<p class='msje_incorrecto'>$msg_tabla</p>
			  </caption>";
		echo "      			
			<tr>
				<td class='nombres_columnas_gomar' align='center' width='15%'>EDITAR</td>
			    <td class='nombres_columnas_gomar' align='center'>APLICACION</td>
      		</tr>";
		
		$nom_clase = "renglon_gris";
		$cont = 1;
		foreach ($_SESSION['sistemasGamaModificada'][$_SESSION['sistemaEditar']] as $ind => $aplicacion) {			
			echo "
				<tr>
					<td class='$nom_clase' ><input type='radio' name='rdb_aplicacion' value='$ind' /></td>
					<td class='$nom_clase' align='left'>$ind</td>
				</tr>";			
			//Determinar el color del siguiente renglon a dibujar
			$cont++;
			if($cont%2==0)
				$nom_clase = "renglon_blanco";
			else
				$nom_clase = "renglon_gris";
		}
		echo "</table>";
	}
	
	
	/*Esta función despliega las Actividades registradas en una Aplicación*/
	function verActividadesApp($msg_tabla){
		echo "<table cellpadding='5' width='100%'>";
		echo "<caption>
				<p class='msje_correcto'>Actividades Registradas en la Aplicaci&oacute;n: <em><u>".$_SESSION['appEditar']."</u></em> del Sistema: <em><u>".$_SESSION['sistemaEditar']."</u></em> 
				en la Gama: <em><u>".$_SESSION['datosGamaModificada']['idGama']."</u></em></p>
				<p class='msje_incorrecto'>$msg_tabla</p>
			  </caption>";
		echo "      			
			<tr>
				<td class='nombres_columnas_gomar' align='center' width='10%'>EDITAR</td>
			    <td class='nombres_columnas_gomar' align='center'>ACTIVIDAD</td>
				<td class='nombres_columnas_gomar' align='center' width='15%'>TIEMPO<br>APROXIMADO (HH:MM)</td>
      		</tr>";
		
		$nom_clase = "renglon_gris";
		$cont = 1;
		foreach ($_SESSION['sistemasGamaModificada'][$_SESSION['sistemaEditar']][$_SESSION['appEditar']] as $ind => $actividad) {
			$actTiempo=obtenerDato("bd_mantenimiento","gama_actividades","tiempo_aprox","actividades_id_actividad",$ind);
			$actTiempo=substr($actTiempo,0,5);
			echo "
				<tr>
					<td class='$nom_clase' ><input type='radio' name='rdb_actividad' value='$ind' /></td>
					<td class='$nom_clase' align='left'>$actividad</td>
					<td class='$nom_clase' align='center' valign='middle'>";
					?>
					<input type="text" name="txt_tiempoAprox<?php echo $ind?>" id="txt_tiempoAprox<?php echo $ind?>" class="caja_de_num" value="<?php echo $actTiempo;?>" readonly="readonly" size="5" 
					onchange="validarTiempoServicios(this)" onkeypress="return permite(event,'num',3);" maxlength="5"/>
					<img src="../../images/editar.png" width="25" height="25" onclick="modTiempoXActividad(txt_tiempoAprox<?php echo $ind?>,'<?php echo $ind?>')" style="cursor:pointer"/>
					<?php
			echo "</td>
				</tr>";			
			//Determinar el color del siguiente renglon a dibujar
			$cont++;
			if($cont%2==0)
				$nom_clase = "renglon_blanco";
			else
				$nom_clase = "renglon_gris";
		}
		echo "</table>";
	}			

?>