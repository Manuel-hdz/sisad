<?php
	/**
	  * Nombre del Módulo: Mantenimiento
	  * Nombre Programador: Miguel Angel Garay Castro
	  * Fecha: 22/Febrero/2011
	  * Descripción: Este archivo contiene funciones para almacenar la información relacionada con las Gamas de los Equipos
	**/

	/*Esta función se encarga de almacenar los datos de la Gama en la BD de Mantenimiento*/
	function guardarDatosGama(){						
		//Realizar la conexion a la BD de Compras
		$conn = conecta("bd_mantenimiento");				
		
		//Extraer los datos de la SESSION
		$id_gama = $_SESSION['datosGamaNueva']['idGama'];
		$nom_gama = $_SESSION['datosGamaNueva']['nomGama'];
		$descripcion = $_SESSION['datosGamaNueva']['descripcion'];
		$area = $_SESSION['datosGamaNueva']['areaAplicacion'];
		$familia = $_SESSION['datosGamaNueva']['familiaAplicacion'];
		$cicloServ = $_SESSION['datosGamaNueva']['cicloServicio'];
		
		
		//Crear la Sentecnia SQL para insertar los datos de la Gama
		$stm_sql = "INSERT INTO gama (id_gama,nom_gama,descripcion,area_aplicacion,familia_aplicacion,ciclo_servicio) VALUES('$id_gama','$nom_gama','$descripcion','$area','$familia',$cicloServ)";
		//Ejecutar la Sentencia SQL
		$rs = mysql_query($stm_sql);
		//Verificar los resultados de la Inserción
		if($rs){
			//Los datos generales de la Gama se guardaron con Exito, guardar los sistemas y su detalle
			guardarSistemas($id_gama);
		}
		else{
			//Redireccionar a la Pagina de Error en el caso de que no se puedan Insertar los datos de la Gama
			$error = mysql_error();
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
			
			//Cerrar la Conexion con la BD
			mysql_close($conn);
		}		
				
	}//Fin de la Funcion guardarDatosGama()
	
	
	/*Esta función se encarga de guardar los datos del los Sistemas, Aplicaciones y Actividades de la Nueva Gama en la Base de Datos*/
	function guardarSistemas($idGama){
		//Estas variables nos ayudarán a controlar los errores en el caso que se presenten durante el proceso de guardar los Sistemas y su detalle dentro de la Gama.
		$band = 0;
		$error = "";
		
		//Obtener el id de la ultima actividad registrada en la bd
		$datos = mysql_fetch_array(mysql_query("SELECT MAX(id_actividad) as maxIdAct FROM actividades"));
		$idActividad = $datos['maxIdAct'] + 1;
		
		
		//Recorrer los Sistemas
		foreach($_SESSION['sistemasGamaNueva'] as $nomSistema => $sistema){
			//Recorrer las Aplicaciones del Sistema
			foreach($sistema as $nomApp => $aplicacion){
				//Registrar cada actividad dentro de la aplicación actual
				foreach($aplicacion as $idAct => $actividad){
					//Crear la Sentencia SQL para guardar cada acividad
					$stm_sql = "INSERT INTO actividades(id_actividad,sistema,aplicacion,descripcion) VALUES($idActividad,'$nomSistema','$nomApp','$actividad')";
					//Ejecutar la Consulta
					$rs = mysql_query($stm_sql);
					//Evauar Resultado
					if($rs){						
						//Guardar la relacion de la actividad con la Gama en la tabla "gama_actividades"
						$rs_act = mysql_query("INSERT INTO gama_actividades (gama_id_gama,actividades_id_actividad) VALUES('$idGama',$idActividad)");
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
					//Incrementar el Id de la Activiada
					$idActividad++;
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
			registrarOperacion("bd_mantenimiento",$idGama,"AgregarGamaNueva",$_SESSION['usr_reg']);
			echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
		}
		else{//Redireccionar a la pantalla de Error, en caso de que existan
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
			//Cerrar la Conexion con la BD
			mysql_close($conn);
		}
	} 
	
		
	/*Esta función se encarga de cargar las Aplicaciones del Sistema Seleccionado a la SESSION, en caso de que el sistema sea Nuevo o no tenga aplicaciones asociadas, devuelve un arreglo 
	 *con la leyenda "Sistema Nuevo, Agregar Aplicaciones"*/
	function cargarApps($nom_sistema){
		//Realizar la conexion a la BD de Compras
		$conn = conecta("bd_mantenimiento");
	
		$arrAplicaciones = array();
		
		//Ejecutar Consulta para obtener las aplicaciones del Sistema en turno
		$rs = mysql_query("SELECT DISTINCT aplicacion FROM actividades WHERE sistema = '$nom_sistema'");
		//Agrupar las aplicaciones del Sistema en la variable $aplicaciones
		if($datos=mysql_fetch_array($rs)){
			do{
				//Cargar el nombre de la aplicacion como indice dentro del arreglo y como contenido un arreglo con las actividades de esa aplicacion
				$arrAplicaciones[$datos['aplicacion']] = cargarActividadesApps($datos['aplicacion'],$nom_sistema);
			}while($datos=mysql_fetch_array($rs));			
		}		
		
		//Cerrar la Conexion con la BD
		mysql_close($conn);
		
		return $arrAplicaciones;
	}
	
	
	/*Esta función carga las actividades de las aplicaciones dentro del sistema seleccionado para integrar la Nueva Gama, regresa un arreglo con las actividades de la aplicación,
	 *o un arreglo con la Leyenda "Aplicación Nueva, Agregar Actividades"*/
	function cargarActividadesApps($nomApp,$nomSistema){
	
		$arrActividades = array();
		//Ejecutar Consulta para obtener las aplicaciones del Sistema en turno
		$rs = mysql_query("SELECT DISTINCT descripcion FROM actividades WHERE aplicacion = '$nomApp' AND sistema = '$nomSistema'");
		//Agrupar las aplicaciones del Sistema en la variable $aplicaciones
		if($datos=mysql_fetch_array($rs)){
			do{
				//Cargar las actividades de la Aplicacion indicada
				$arrActividades[] = $datos['descripcion'];
			}while($datos=mysql_fetch_array($rs));			
		}		
		
		return $arrActividades;
	}			
	
	
	/*Esta funcion se encarga de Desplegar los Sistemas y su detalle, que estan siendo agregados a la Gama*/
	function verSistemasGama($msg_tabla){						
		echo "<table cellpadding='5' width='100%'>";
		echo "<caption>
				<p class='msje_correcto'>Sistemas que se Agregaran a la Gama Creada <em><u>".$_SESSION['datosGamaNueva']['idGama']."</u></em></p>
				<p class='msje_incorrecto'>$msg_tabla</p>
			  </caption>";
		echo "      			
			<tr>
				<td class='nombres_columnas' align='center' width='10%'>EDITAR</td>
        		<td class='nombres_columnas' align='center' width='30%'>SISTEMA</td>
			    <td class='nombres_columnas' align='center' width='60%'>APLICACIONES</td>
      		</tr>";
		
		$nom_clase = "renglon_gris";
		$cont = 1;
		foreach ($_SESSION['sistemasGamaNueva'] as $ind => $sistema) {			
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
				<p class='msje_correcto'>Aplicaciones Registradas en el Sistema: <em><u>".$_SESSION['sistemaEditar']."</u></em> de la Gama Creada: <em><u>".$_SESSION['datosGamaNueva']['idGama']."</u></em></p>
				<p class='msje_incorrecto'>$msg_tabla</p>
			  </caption>";
		echo "      			
			<tr>
				<td class='nombres_columnas' align='center' width='15%'>EDITAR</td>
			    <td class='nombres_columnas' align='center'>APLICACION</td>
      		</tr>";
		
		$nom_clase = "renglon_gris";
		$cont = 1;
		foreach ($_SESSION['sistemasGamaNueva'][$_SESSION['sistemaEditar']] as $ind => $aplicacion) {			
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
				en la Gama Creada: <em><u>".$_SESSION['datosGamaNueva']['idGama']."</u></em></p>
				<p class='msje_incorrecto'>$msg_tabla</p>
			  </caption>";
		echo "      			
			<tr>
				<td class='nombres_columnas' align='center' width='10%'>EDITAR</td>
			    <td class='nombres_columnas' align='center'>ACTIVIDAD</td>
      		</tr>";
		
		$nom_clase = "renglon_gris";
		$cont = 1;
		foreach ($_SESSION['sistemasGamaNueva'][$_SESSION['sistemaEditar']][$_SESSION['appEditar']] as $ind => $actividad) {			
			echo "
				<tr>
					<td class='$nom_clase' ><input type='radio' name='rdb_actividad' value='$ind' /></td>
					<td class='$nom_clase' align='left'>$actividad</td>
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