<?php
	/**
	  * Nombre del Módulo: Laboratorio                                               
	  * Nombre Programador: Nadia Madahí López Hernández                        
	  * Fecha: 18/Junio/2011                                      			
	  * Descripción: Este archivo permite generar las funciones para programar el servicio de mantenimiento a los equipos de laboratorio
	  **/
	  
	  	  			
	//Funcion que se encarga de desplegar los equipos de laboratorio de acuerdo a los parametros de busqueda
	function buscarEquipoLab(){

		//Conectar a la BD de Laboratorio
		$conn = conecta("bd_laboratorio");
		
		//Si viene sbt_consultarNombreEquipo se mostraran los equipos por la Marca del mismo
		if(isset($_POST["sbt_consultarMarcaEquipo"])){ 
					
			//Crear sentencia SQL
//			$sql_stm ="SELECT * FROM equipo_lab  WHERE marca ='$_POST[cmb_marcaEquipoLab]' AND estado=0";
			$sql_stm = "SELECT * FROM equipo_lab  WHERE  estado=1 AND marca ='$_POST[cmb_marca]'";		
			//Crear el mensaje que se mostrara en el titulo de la tabla
			$msg= "Equipos de Laboratorio de la Marca <em><u>  $_POST[cmb_marca]</u></em>";
			
			//Crear el Mensaje en caso de que la consulta no arroje ningún resultado
			$msg_error = "	<label class='msje_correcto' align='center'>No se Encontr&oacute; Ningun Equipo de Laboratorio de la Marca <em><u>  $_POST[cmb_marca]</u></em>";
		}	
		
		//Si viene sbt_consultarClaveEquipo la buqueda de los equipos sera por la clave o numero interno del equipo
		if(isset($_POST["sbt_consultarClaveEquipo"])){ 
		
			//Crear sentencia SQL
			$sql_stm ="SELECT * FROM equipo_lab  WHERE no_interno='$_POST[txt_claveEquipo]' AND estado!=0";		
					
			//Crear el mensaje que se mostrara en el titulo de la tabla
			$msg = "Equipos de Laboratorio con el Número Interno <em><u> $_POST[txt_claveEquipo]</u></em>";
			
			//Crear el Mensaje en caso de que la consulta no arroje ningún resultado
			$msg_error = "<label class='msje_correcto' align='center'>No se Encontr&oacute; Ningun Equipo con un Numero Interno<em><u> $_POST[txt_claveEquipo]</u></em>";
		}
		
		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($sql_stm);									
	
		//Confirmar que la consulta de datos fue realizada con exito.
		if($datos=mysql_fetch_array($rs)){
	
			//Desplegar los resultados de la consulta en una tabla
			echo "				
			<table cellpadding='5' width='100%'>				
				<tr>
					<td colspan='11' align='center' class='titulo_etiqueta'>$msg</td>
				</tr>
				<tr>
					<td class='nombres_columnas' align='center'>SELECCIONAR</td>
					<td class='nombres_columnas' align='center'>N° INTERNO</td>
					<td class='nombres_columnas' align='center'>NOMBRE </td>
					<td class='nombres_columnas' align='center'>MARCA</td>
					<td class='nombres_columnas' align='center'>N° DE SERIE</td>
					<td class='nombres_columnas' align='center'>RESOLUCI&Oacute;N</td>
					<td class='nombres_columnas' align='center'>ESCALA</td>
					<td class='nombres_columnas' align='center'>EXACTITUD</td>
					<td class='nombres_columnas' align='center'>ENCARGADO</td>
					<td class='nombres_columnas' align='center'>APLICACI&Oacute;N</td>
					<td class='nombres_columnas' align='center'>CALIBRABLE</td>
				</tr>";

			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{	
				//Mostrar todos los registros que han sido completados
				echo "
					<tr>
						<td class='nombres_filas' align='center'><input type='radio' name='rdb_noEquipo' id='rdb_noEquipo' value='$datos[no_interno]' />
						</td>
						<td class='$nom_clase'>$datos[no_interno]</td>
						<td class='$nom_clase'>$datos[nombre]</td>						
						<td class='$nom_clase'>$datos[marca]</td>
						<td class='$nom_clase'>$datos[no_serie]</td>
						<td class='$nom_clase'>$datos[resolucion]</td>
						<td class='$nom_clase'>$datos[escala]</td>
						<td class='$nom_clase'>$datos[exactitud]</td>
						<td class='$nom_clase'>$datos[encargado]</td>
						<td class='$nom_clase'>$datos[aplicacion]</td>";
						if($datos['calibrable']=='1')
							echo "<td class='$nom_clase'>SI</td>";
						else
							echo "<td class='$nom_clase'>NO</td>";
				echo "</tr>";
					
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";				
			}while($datos=mysql_fetch_array($rs));
			//Fin de la tabla donde se muestran los resultados de la consulta
			echo "</table>";
			return 1;
			
		}// fin  if($datos=mysql_fetch_array($rs))
		else{//Si no se encuentra ningun resultado desplegar el mensaje de alerta notificando que no hay resultados disponibles
			echo $msg_error;		
			return 0;		
		}
	}
	
	
	//Funcion que se encarga de  la seleccionar el equipo al cual se le agregara información
	function obtenerDatosEquipo(){
		//Relizar la consulta con el id del equipo seleccionado para poder precargar los datos 
		//Conectar a la BD de Laboratorio
		$conn = conecta("bd_laboratorio");

		//Crear sentencia SQL
		$sql_stm ="SELECT no_interno, marca, no_serie, nombre FROM equipo_lab WHERE no_interno = '$_POST[rdb_noEquipo]'";
		
		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($sql_stm);									
		//Confirmar que la consulta de datos fue realizada con exito.
		if($datos=mysql_fetch_array($rs))
			return $datos;
		else
			return $datos = array("no_interno"=>"---","marca"=>"---","no_serie"=>"---","nombre"=>"---");
	}
	
	
	/*Funcion que guarda los registros del programa de mantenimiento a los equipos de laboratorio*/
	function guardarProgramaMtto(){
		//Abrimos la Conexión a la bd de Laboratorio
		$conn = conecta("bd_laboratorio");
		
		//obtener datos del equipo de la SESSION
		$no_interno = $_SESSION['datosEquiposLab']['no_interno'];
		$nombre = $_SESSION['datosEquiposLab']['nombre'];
						
		//Esta variables se encarga de verificar si la inserción de datos fue exitosa
		$status = 0;

		//Recorrer el Arreglo para obtener los servicios programados
		foreach($_SESSION['datosMtto'] as $ind => $regMtto) {
		
			//Nos traemos la información que se va agregar a a la BD desde el vector $_POST[] ya que esta información vienen desde el formulario frm_programarMttoEquipo en el $_POST[]			
			$fechaMesAnio = strtoupper($regMtto['fechaMtto']);
			//Trasformar la fecha del tipo "MES DE AÑO" a "aaaa-mm-dd"
			$partesFecha = split(" ",$fechaMesAnio); 
			$numMes = obtenerNumMes($partesFecha[0]);			
			//Variable que establece el año actual
			$anio = $partesFecha[2];
			//Se obtiene la fecha de acuerdo al numero de mes y año actual
			$fecha = "$anio-$numMes-01";
			
			$tipoServicio = strtoupper($regMtto['tipoServicio']);			
			//Obtener el ID del Servicio
			$idServicio = obtenerIdServicio();

			//Creamos la sentencia SQL para guardar el servicio de mantenimiento a los equipos de laboratorio
			$stm_sql = "INSERT INTO cronograma_servicios (id_servicio,equipo_lab_no_interno, fecha_mtto, tipo_servicio, estado) 
				VALUES ('$idServicio','$no_interno', '$fecha', '$tipoServicio','0')";
	
			//Ejecutar la sentencia previamente creada
			$rs = mysql_query($stm_sql);
			
			//Verificar que la insersión sea exitosa
			if(!$rs){
				$status = 1;
				break;
			}
		
		}// fin de 	foreach($_SESSION['datosMtto'] as $ind => $regMtto) {


		//Confirmar que la inserción de datos fue realizada con exito.
		if($status==0){ 
			//liberar los datos del arreglo de sesion
			unset ($_SESSION['datosMtto']);	
			unset($_SESSION['datosEquiposLab']);								
			//Guardar la operacion realizada
			registrarOperacion("bd_laboratorio",$nombre,"ProgramarMttoEquipo",$_SESSION['usr_reg']);															
			echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
		}
		else{
			//liberar los datos del arreglo de sesion
			unset ($_SESSION['datosMtto']);	
			unset($_SESSION['datosEquiposLab']);
			$error = mysql_error();
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
			
			//Cerrar la conexion con la BD
			mysql_close($conn);
		}	
	}
	
	
	/*Función que no s sirve para mostrar el registro y programacion de los mantenimientos */
	function mostrarProgramaMtto(){
		echo "<table cellpadding='5' width='100%'>    
			<tr>						
				<td colspan='3' align='center' class='titulo_etiqueta'>Programa de Servicios de Mantenimiento para los Equipos de Laboratorio</td>
			</tr>
			<tr>
				<td class='nombres_columnas' align='center'>N° REGISTRO</td>
        		<td class='nombres_columnas' align='center'>FECHA MANTENIMIENTO</td>
			    <td class='nombres_columnas' align='center'>TIPO SERVICIO</td>
			</tr>";
		
		$nom_clase = "renglon_gris";
		$cont = 1;	
		
		foreach($_SESSION['datosMtto'] as $ind => $regMtto) {
			//Desplegar el nombre de los Contactos 
			echo "
				<tr>
					<td class='$nom_clase'>$cont</td>
					<td class='$nom_clase'align='center'>$regMtto[fechaMtto]</td>
					<td class='$nom_clase'align='center'>$regMtto[tipoServicio]</td>
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

	//Función que nos permitira revisar que los mantenimientos asignados a los equipos no se repitan en el mismo mes y año
	function revisarMttoAsignados(){
		//Conectar a la BD de Laboratorio
		$conn = conecta("bd_laboratorio");
		
		//Obtener los datos del $_POST		
		$numMes = obtenerNumMes($_POST['cmb_Mes']);
		$fecha = $_POST['cmb_Anio']."-$numMes-01";
				
		//Crear sentencia SQL
		$sql_stm ="SELECT id_servicio FROM cronograma_servicios WHERE equipo_lab_no_interno = $_POST[txt_numInterno] AND fecha_mtto = '$fecha' AND tipo_servicio = '$_POST[rdb_tipoServicio]'";
		
		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($sql_stm);
		
		if($datos=mysql_fetch_array($rs))
			return false;
		else
			return true;		
	}
	
	
	//Esta función se encarga de generar el Id para registrar los Cronogramas de Servicios
	function obtenerIdServicio(){		
		//Definir las tres letras en la Id del Servicio
		$id_cadena = "SER";	
		
		//Obtener el mes y el año
		$fecha = date("m-Y");
		$id_cadena .= substr($fecha,0,2).substr($fecha,5,2);
				
		//Obtener el mes actual y el año actual para ser agregados en la consulta y asi obtener los servicios del mes y año en curso
		$mes = substr($fecha,0,2);
		$anio = substr($fecha,5,2);
		
		//Crear la sentencia para obtener el numero de servicios registradas en la BD
		$stm_sql = "SELECT COUNT(id_servicio) AS cant FROM cronograma_servicios WHERE id_servicio LIKE 'SER$mes$anio%'";
		$rs = mysql_query($stm_sql);
		if($datos=mysql_fetch_array($rs)){
			$cant = $datos['cant'] + 1;
			if($cant>0 && $cant<10)
				$id_cadena .= "00".$cant;
			if($cant>9 && $cant<100)
				$id_cadena .= "0".$cant;
			if($cant>=100)
				$id_cadena .= $cant;
		}			
		
		//Regresar el ID generado
		return $id_cadena;
	}//Fin de la Funcion obtenerIdServicio()
		
		
?>	